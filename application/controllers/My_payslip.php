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
		$this->load->model(['Employee_model' => 'employee']);
		$current_date = date('Y-m-d');
		$previous_date = date_format(date_sub(date_create($current_date), date_interval_create_from_date_string("1 month")), 'Y-m-d');

		$this->import_page_script(['payslip_listing.js', 'jquery.printPage.js', 'select2.min.js']);
		$this->generate_page('my-payslip/listing', [
			'items' => $this->payslip->all(FALSE, "DATE(end_date) >= '{$previous_date}' AND DATE(end_date) <= '{$current_date}'"),
			'employee' => $this->employee->all()
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

	public function search_employee()
	{
		$input = $this->input->post();
		$items = $this->payslip->all(FALSE, $input);
		$this->load->view('my-payslip/listing_prototype', ['items' => $items]);
	}

	public function approve_payslip()
	{
		$this->output->set_content_type('json');
		if($this->session->userdata('account_type')=='ad'){
			$input = $this->input->post();
			if(empty($input)){
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
			else if(isset($input['id'])){
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

	public function print_payslip($batch_id = FALSE)
	{
		$payslips = [];
		if($batch_id)
			$payslips = $this->payslip->get_by_batch_to_print($batch_id);

		$this->load->view('print_docu', ['payslips' => $payslips]);
	}
}