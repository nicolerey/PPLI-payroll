<?php

class Payslip extends HR_Controller
{
	protected $active_nav = NAV_MY_PAYSLIP;
	protected $tab_title = 'Payslip';

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Payslip_model' => 'payslip', 'Employee_model' => 'employee', 'Department_model' => 'department', 'Loan_model' => 'loan']);
	}

	public function index()
	{
		$departments = $this->department->all();
		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script(['manage-payslip.js']);
		$this->generate_page('payslip/generate', [
			'title' => 'Generate payslip',
			'departments' => array_column($departments, 'name', 'id')
		]);
	}

	public function generate()
	{
		$input = $this->input->post();

		$date = [];
		$range = [
			date_format(date_create($input['from_date']), 'Y-m-d'),
			date_format(date_create($input['to_date']), 'Y-m-d')
		];

		$created = 0;
		if($this->department->exists($input['department_id'])){
			$last_batch_id = $this->payslip->get_last_batch_id();
			$all = array_column($this->department->get_employees($input['department_id']), 'id');
			foreach($all AS $row){
				$status = $this->payslip->create($row, $range, $last_batch_id['batch_id']+1, 0);
				if($status === TRUE){
					$created++;
				}
			}
			$this->session->set_flashdata('mass_payroll_status_complete', $created);
			redirect('payslip');
		}

		$this->session->set_flashdata('mass_payroll_status_complete', $created);
		redirect('payslip');
	}


	public function adjust()
	{
		$input = $this->input->post();

		$status = $this->payslip->get_payroll_status($input['id']);
		if($status['approval_status'] && $this->session->userdata('account_type')!='ad'){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}

		if(isset($input['additional_name']) || isset($input['deduction_name'])){
			$this->_perform_validation();
			if(!$this->form_validation->run()){
				$this->output->set_output(json_encode([
					'result' => FALSE,
					'messages' => array_values($this->form_validation->error_array())
				]));
				return;
			}
		}

		if($input){
			$employee_id = $input['employee_id'];
			$payroll_id = $input['id'];
			$payroll_particular = [];
			if(isset($input['additional_name'])){
				foreach ($input['additional_name'] as $key => $value) {
					$payroll_particular[] = [
						'payroll_id' => $payroll_id,
						'particulars_id' => $input['additional_name'][$key],
						'amount' => floatval(str_replace(',', '', $input['additional_particular_rate'][$key]))
					];
				}
			}

			if(isset($input['deduction_name'])){
				foreach ($input['deduction_name'] as $key => $value) {
					$payroll_particular[] = [
						'payroll_id' => $payroll_id,
						'particulars_id' => $input['deduction_name'][$key],
						'amount' => floatval(str_replace(',', '', $input['deduction_particular_rate'][$key]))
					];
				}
			}

			$payroll_update = [
				'current_daily_wage' => floatval(str_replace(',', '', $input['basic_rate']))
			];

			$emp_position = $this->employee->get_position($employee_id);
			$payroll_update['overtime_pay'] = $payroll_update['current_daily_wage'] * ($emp_position['overtime_rate']/100);

			$payroll_particulars_update = [];
			if(isset($input['particular_id'])){
				foreach ($input['particular_id'] as $key => $value) {
					$unit = 0;

					$payroll_particulars_update[] = [
						'particulars_id' => $value,
						'amount' => floatval(str_replace(',', '', $input['particular_rate'][$key]))
					];
				}
			}

			if(isset($input['loan_id'])){
				$loan_balance = [];
				$loan = [];
				foreach ($input['loan_id'] as $key => $value) {
					$loan[] = $this->loan->get_payroll_loans(false, false, $value, 'SING');
					$loan_balance[] = $loan[0]['p_total'];
				}

				$loan_data = [];
				foreach ($input['loan_payment_id'] as $key => $value) {
					$loan_data[] = [
						'id' => $value,
						'payment_amount' => floatval(str_replace(',', '', $input['loan_payment'][$key]))
					];
				}
				$this->loan->update_payment_batch($loan_data);
			}

			$insert_flag = 0;
			if(!empty($payroll_particular)){
				if($this->payslip->insert_salary_particular($payroll_particular))
					$insert_flag = 1;
			}
			else
				$insert_flag = 1;

			$update_flag = 0;
			if($this->payslip->update_payroll($payroll_id, $payroll_update, $payroll_particulars_update))
				$update_flag = 1;

			if($insert_flag && $update_flag){
				$this->output->set_output(json_encode(['result' => TRUE]));
				return;
			}

			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Unable to make save payslip. Please try again later.']
			]));
			return;
		}
	}

	public function _perform_validation()
	{
		$input = $this->input->post();
		if(isset($input['additional_name'])){
			$this->form_validation->set_rules('additional_name[]', 'particular name', 'required');
			$this->form_validation->set_rules('additional_particular_rate[]', 'particular rate', 'required');
		}

		if(isset($input['deduction_name'])){
			$this->form_validation->set_rules('deduction_name[]', 'particular name', 'required');
			$this->form_validation->set_rules('deduction_particular_rate[]', 'particular rate', 'required');
		}
	}

	public function store()
	{
		$input = elements(['month', 'employee_number' ,'adjustment'], $this->input->post());
		if($this->payslip->create($input['employee_number'], $input['month'], $input['adjustment'])){
			redirect('payslip');
		}
	}

}