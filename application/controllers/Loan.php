<?php

class Loan extends HR_Controller
{

	protected $tab_title = 'Loan';
	protected $active_nav = NAV_LOANS;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Loan_model'=>'loan', 'Employee_model'=>'employee']);
	}

	public function index()
	{
		$search_employee = FALSE;
		$this->active_nav = NAV_LOANS;
		$data = [];
		$test=  [];
		$range = elements(['start_date', 'end_date', 'employee_number'], $this->input->get(), NULL);

		if(!empty($range['start_date']))
			$start_date = is_valid_date($range['start_date'], 'm/d/Y') ? date_create($range['start_date'])->format('Y-m-d') : date('Y-m-d');
		else
			$start_date = NULL;

		if(!empty($range['end_date']))
			$end_date = is_valid_date($range['end_date'], 'm/d/Y') ? date_create($range['end_date'])->format('Y-m-d') : date('Y-m-d');
		else
			$end_date = NULL;

		$search_employee = TRUE;
		$emp_result = [];

		$employee_number = NULL;
		if(!empty($range['employee_number']))
			$employee_number = $range['employee_number'];

		$emp_result = $this->loan->all($employee_number, NULL, $start_date, $end_date);

		if($emp_result){
			$search_employee = TRUE;
			$x = 0;
			foreach ($emp_result as $loan) {
				$name = $this->employee->get_employee_name($loan['employee_id']);
				$data[$x]['name'] = $name['firstname']." ".$name['middleinitial']." ".$name['lastname'];
				$data[$x]['loan'] = $loan;

				$x++;
			}
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script(['listing-loan.js']);
		$this->generate_page('loan/listing', ['data' => $data, 'search_employee' => $search_employee]);
	}

	public function create()
	{
		$employees = $this->employee->all();
		foreach($employees AS &$emp){
			$emp['fullname'] = "{$emp['lastname']}, {$emp['firstname']} {$emp['middleinitial']} [{$emp['id']}]";
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js', 'price-format.js']);
		$this->import_page_script('create-loan.js');
        $this->generate_page('loan/create', [
        	'title' => 'Make a loan',
        	'employees' => array_column($employees, 'fullname', 'id'),
        	'action' => "store"
        ]);
	}

	public function view($id)
	{
		$employees = $this->employee->all();
		foreach($employees AS &$emp){
			$emp['fullname'] = "{$emp['lastname']}, {$emp['firstname']} {$emp['middleinitial']} [{$emp['id']}]";
		}
		$loan = $this->loan->all(NULL, $id);

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js', 'price-format.js']);
		$this->import_page_script('create-loan.js');
        $this->generate_page('loan/create', [
        	'title' => 'Make a loan',
        	'employees' => array_column($employees, 'fullname', 'id'),
        	'loan' => $loan[0],
        	'action' => "update"
        ]);
	}

	public function store(){
		$this->output->set_content_type('json');
		$this->_perform_validation();
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}

		$input = $this->_format_data();
		if($input['employee_number']=='all'){
			$all = array_column($this->employee->all(), 'id');
			foreach($all as $emp_id){
				$input['employee_number'] = $emp_id;
				if($this->loan->create($input)){
					$this->output->set_output(json_encode(['result' => TRUE]));
					return;
				}
			}
		}
		else{
			if($this->loan->create($input)){
				$this->output->set_output(json_encode(['result' => TRUE]));
				return;
			}
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to make a loan. Please try again later.']
		]));
		return;
	}

	public function update()
	{
		$this->output->set_content_type('json');
		$this->_perform_validation();
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}
		$input = $this->_format_data();
		if($this->loan->update_loan($input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to make a loan. Please try again later.']
		]));
		return;
	}

	public function delete($loan_id)
	{
		$this->output->set_content_type('json');
		if(!$loan_id || !$this->loan->exists($loan_id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Loan does not exist.']
			]));
			return;
		}
		if($this->loan->delete($loan_id)){			
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to make a loan. Please try again later.']
		]));
		return;
	}

	public function _perform_validation()
	{
		$this->form_validation->set_rules('loan_date', 'loan date', 'required|callback__validate_date');
		$this->form_validation->set_rules('employee_number', 'employee', 'required');
		$this->form_validation->set_rules('loan_amount', 'loan amount', 'required|callback__validate_numeric');
		$this->form_validation->set_rules('payment_date[]', 'payment date', 'required|callback__validate_date');
		$this->form_validation->set_rules('payment_amount[]', 'payment amount', 'required|callback__validate_numeric');
		$this->form_validation->set_rules('payment_total', 'payment total', 'matches[loan_amount]');
	}

	public function _format_data()
	{
		$loan_info = [];
		$loan_info += elements([
			'loan_date',
			'employee_number',
			'loan_amount',
			'id'
		], $this->input->post(), NULL);

		$loan_info['loan_amount'] = floatval(str_replace(',', '', $loan_info['loan_amount']));

		$payment_date = $this->input->post('payment_date');
		$payment_amount = $this->input->post('payment_amount');
		if(count($payment_date)>0){
			for($x=0; $x<count($payment_date); $x++){
				$loan_info['payment_terms'][$x]['payment_date'] = date_format(date_create($payment_date[$x]), 'Y-m-d');
				$loan_info['payment_terms'][$x]['payment_amount'] = floatval(str_replace(',', '', $payment_amount[$x]));
			}
		}

		return $loan_info;
	}

	public function _validate_date($val)
	{
		$this->form_validation->set_message('_validate_date', 'Please provide a valid %s.');
		return is_valid_date($val, 'm/d/Y');
	}

	public function _validate_numeric($val)
	{
		$this->form_validation->set_message('_validate_numeric', 'The %s is invalid.');
		return is_numeric(str_replace(',', '', $val));
	}
}