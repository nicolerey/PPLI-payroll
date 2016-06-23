<?php

class My_payslip extends HR_Controller
{
	protected $tab_title = 'View my payslip';
	protected $active_nav = NAV_MY_PAYSLIP;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Payslip_model', 'payslip');
	}

	public function index()
	{
		$this->import_page_script('payslip_listing.js');
		$this->generate_page('my-payslip/listing', [
			'items' => $this->payslip->all()
		]);
	}

	public function view($id)
	{

		$this->load->model(['Employee_model' => 'employee', 'Pay_modifier_model' => 'pay_modifier']);
		
		$payslip = $this->payslip->get_by_employee($id);
		$employee = $this->employee->get($payslip['employee_id']);

		$particular_result = $this->employee->get_employee_pay_particulars($payslip['employee_id']);
		$emp_particulars = [];
		if($particular_result){
			foreach ($particular_result as $key => $value) {
				array_push($emp_particulars, $value['particulars_id']);
			}
		}

		$this->import_plugin_script(['price-format.js']);
		$this->import_page_script('adjust-payslip.js');

		$pm_flag = ($this->session->userdata('account_type')=='pm')?TRUE:FALSE;
		$this->generate_page('my-payslip/view', [
			'payslip' => $payslip,
			'employee_data' => $employee,
			'particulars' => $this->pay_modifier->all($emp_particulars, $pm_flag)
		]);
	}

	public function approve_payslip()
	{
		$this->output->set_content_type('json');
		if($this->session->userdata('account_type')=='ad'){
			$input = $this->input->post();
			if(isset($input['checkbox']) && empty($input['checkbox'])){
				$this->output->set_output(json_encode([
					'result' => FALSE,
					'messages' => ['No payslip selected.']
				]));
				return;
			}

			$data = [];
			$result = 0;
			if(isset($input['checkbox'])){
				foreach ($input['checkbox'] as $key => $value) {
					$data[] = [
						'id' => $value,
						'approval_status' => TRUE,
						'approved_by' => $this->session->userdata('id')
					];
				}
				$result = $this->payslip->update_payroll_batch_normal($data, "BATCH");
			}
			else{
				$data = [
					'id' => $input['id'],
					'approval_status' => TRUE,
					'approved_by' => $this->session->userdata('id')
				];
				$result = $this->payslip->update_payroll_batch_normal($data, "NORMAL");
			}

			if($result){
				$this->output->set_output(json_encode([
					'result' => TRUE
				]));
				return;
			}
			else{
				$this->output->set_output(json_encode([
					'result' => FALSE,
					'messages' => ['An error occured.']
				]));
				return;
			}
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['No permission to approve.']
		]));
		return;
	}
}