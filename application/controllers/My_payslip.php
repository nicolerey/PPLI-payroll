<?php

class My_payslip extends HR_Controller
{
	protected $tab_title = 'View payslip';
	protected $active_nav = NAV_MY_PAYSLIP;
	protected $active_subnav;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Payslip_model', 'payslip');
	}

	public function index()
	{
		$active_subnav = SUBNAV_LISTING;

		$this->generate_page('my-payslip/batch_select', [
			'batches' => $this->payslip->get_batches()
		]);
	}

	public function view_payslip($batch_id)
	{
		$this->load->model(['Employee_model' => 'employee']);

		$this->import_page_script(['payslip_listing.js', 'jquery.printPage.js', 'select2.min.js']);
		$this->generate_page('my-payslip/listing', [
			'items' => $this->payslip->all(FALSE, "batch_id = {$batch_id}"),
			'employee' => $this->employee->all(),
			'batch_id' => $batch_id
		]);
	}

	public function create_manual_payslip()
	{
		$this->load->model(['Employee_model' => 'employee']);
		
		$employee = $this->employee->all();

		$this->import_plugin_script(['price-format.js', 'bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('manual-payslip.js');

		$this->generate_page('my-payslip/manual_payslip_view', [
			'employee_data' => $employee
		]);
	}

	public function select_employee()
	{
		$this->load->model(['Employee_model' => 'employee', 'Position_model' => 'position', 'Pay_modifier_model' => 'pay_modifier', 'Loan_model' => 'loan']);

		$input = $this->input->post();

		$emp_position = $this->employee->get_position($input['id']);
		$position = $this->position->get($emp_position['id']);

		$pm_flag = ($this->session->userdata('account_type')=='pm')?TRUE:FALSE;

		$emp_particulars = [];
		if($position['particulars']){
			foreach ($position['particulars'] as $key => $value) {
				array_push($emp_particulars, $value['particulars_id']);
			}
		}

		$data = [
			'basic_rate' => $emp_position['daily_rate'],
			'overtime_rate' => $emp_position['overtime_rate'],
			'late_penalty_rate' => $emp_position['late_penalty'],
			'emp_particulars' => $position['particulars'],
			'loans' => $this->loan->all($input['id']),
			'particulars' => $this->pay_modifier->all($emp_particulars, $pm_flag)
		];

		$this->load->view('my-payslip/manual_payslip_blueprint', $data);
	}

	public function view($id)
	{
		$this->load->model(['Employee_model' => 'employee', 'Pay_modifier_model' => 'pay_modifier', 'Loan_model' => 'loan']);
		
		$last_batch_id = $this->payslip->get_last_batch_id();

		$payslip = $this->payslip->get_by_employee($id, $last_batch_id['batch_id']);
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
			'batch_id' => $payslip['batch_id'],
			'payslip' => $payslip,
			'loans' => $this->loan->get_payroll_loans($id),
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

	public function store_manual_payslip()
	{
		$this->output->set_content_type('json');

		$this->load->model(['Employee_model' => 'employee']);

		$this->_perform_validation();
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}

		$input = $this->input->post();

		$emp_position = $this->employee->get_position($input['emp_id']);

		$batch_id = $this->payslip->get_last_batch_id();
		$ad_flag = ($this->session->userdata('account_type')=='ad')?1:0;
		$payroll_data = [
			'employee_id' => $input['emp_id'],
			'start_date' => date_format(date_create($input['start_date']), 'Y-m-d'),
			'end_date' => date_format(date_create($input['end_date']), 'Y-m-d'),
			'days_rendered' => $input['days_rendered'],
			'overtime_hours_rendered' => $input['overtime_time'],
			'late_minutes' => $input['late_minutes'],
			'current_daily_wage' => $emp_position['daily_rate']?$emp_position['daily_rate']:0,
			'daily_wage_units' => 1,
			'current_late_penalty' => $emp_position['late_penalty']?$emp_position['late_penalty']:0,
			'overtime_pay' => $emp_position['overtime_rate']?$emp_position['overtime_rate']:0,
			'batch_id' => $batch_id['batch_id'] + 1,
			'approval_status' => $ad_flag,
			'approved_by' => ($ad_flag)?$this->session->userdata('id'):NULL,
			'created_by' => $this->session->userdata('id')
		];

		$payroll_particulars_data = [];
		if(isset($input['additional_name'])){
			foreach ($input['additional_name'] as $key => $value) {
				$payroll_particulars_data[] = [
					'particulars_id' => $value,
					'units' => 1,
					'amount' => $input['additional_particular_rate'][$key]
				];
			}
		}
		if(isset($input['deduction_name'])){
			foreach ($input['deduction_name'] as $key => $value) {
				$payroll_particulars_data[] = [
					'particulars_id' => $value,
					'units' => 1,
					'amount' => $input['deduction_particular_rate'][$key]
				];
			}
		}

		if($this->payslip->create_manual_payslip($payroll_data, $payroll_particulars_data, $input['loan_payment'])){
			$this->output->set_output(json_encode([
				'result' => TRUE,
				'messages' => []
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Cannot add payslip. Please try again later.']
		]));
		return;
	}

	public function _perform_validation()
	{
		$this->form_validation->set_rules('emp_id', 'employee', 'required');
		$this->form_validation->set_rules('start_date', 'start date', 'required');
		$this->form_validation->set_rules('end_date', 'end date', 'required');

		$input = $this->input->post();

		if(isset($input['additional_name'])){
			$this->form_validation->set_rules('additional_name[]', 'particular name', 'required');
			$this->form_validation->set_rules('additional_name[]', 'particular rate', 'required');
		}

		if(isset($input['deduction_name'])){
			$this->form_validation->set_rules('deduction_name[]', 'particular name', 'required');
			$this->form_validation->set_rules('deduction_particular_rate[]', 'particular rate', 'required');
		}
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

			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['No permission to approve.']
		]));
		return;
	}

	public function unapprove_payslip()
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
						'approval_status' => FALSE
					];
				}
				$result = $this->payslip->update_payroll_batch_normal($data, "BATCH");
			}
			else if(isset($input['id'])){
				$data = [
					'id' => $input['id'],
					'approval_status' => FALSE
				];
				$result = $this->payslip->update_payroll_batch_normal($data, "NORMAL");
			}

			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['No permission to unapprove.']
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