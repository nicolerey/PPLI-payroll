<?php

class Salaries extends HR_Controller
{

	protected $tab_title = 'Salaries';

	protected $active_nav = NAV_SALARIES;

	protected $days = [
		1 => 'Monday', 
		2 => 'Tuesday', 
		3 => 'Wednesday', 
		4 => 'Thursday', 
		5 => 'Friday', 
		6 => 'Saturday', 
		7 => 'Sunday'
	];

	protected $fields = [
		['name' => 'position_id', 			'label' => 'position', 				'rules' => 'required'],
		['name' => 'day_of_week_start', 	'label' => 'day of week start', 	'rules' => 'callback__validate_day_of_week'],
		['name' => 'day_of_week_end', 		'label' => 'day of week end', 		'rules' => 'callback__validate_day_of_week'],
		['name' => 'hour_of_day_start_am', 	'label' => 'time in (AM)', 			'rules' => 'callback__validate_am_time'],
		['name' => 'hour_of_day_start_pm', 	'label' => 'time in (PM)', 			'rules' => 'callback__validate_pm_time'],
		['name' => 'hour_of_day_end_am', 	'label' => 'time out (AM)', 		'rules' => 'callback__validate_am_time'],
		['name' => 'hour_of_day_end_pm', 	'label' => 'time out (PM)', 		'rules' => 'callback__validate_pm_time'],
		['name' => 'daily_rate', 			'label' => 'daily wage', 			'rules' => 'required|numeric'],
		['name' => 'overtime_rate', 		'label' => 'overtime rate', 		'rules' => 'required|numeric'],
		['name' => 'allowed_late_period', 	'label' => 'allowed late  period', 	'rules' => 'required|numeric'],
		['name' => 'late_penalty', 			'label' => 'late penalty', 			'rules' => 'required|numeric'],
	];

	protected $validation_errors = [];



	public function __construct()
	{
		parent::__construct();
		$this->load->model('Salary_model', 'salary');
	}

	public function index()
	{
		$this->generate_page('salaries/listing', [
			'items' => $this->salary->all()
		]);
	}

	public function create()
	{
		$this->import_page_script('manage-salary.js');
		$this->load->model(['Pay_modifier_model' => 'particulars', 'Position_model' => 'position']);
		$this->generate_page('salaries/manage', [
			'title' => 'Create new salary',
			'data' => [],
			'mode' => MODE_CREATE,
			'days' => $this->days,
			'particulars' => array_column($this->particulars->all(), 'name', 'id'),
			'positions' => array_column($this->position->all(), 'name', 'id')
		]);
	}

	public function edit($id = FALSE)
	{
		if(!$id || !$salary = $this->salary->get($id)){
			show_404();
		}
		$this->import_page_script('manage-salary.js');
		$this->load->model(['Pay_modifier_model' => 'particulars', 'Position_model' => 'position']);
		$this->generate_page('salaries/manage', [
			'title' => 'Create new salary',
			'data' => $salary,
			'mode' => MODE_EDIT,
			'days' => $this->days,
			'particulars' => array_column($this->particulars->all(), 'name', 'id'),
			'positions' => array_column($this->position->all(), 'name', 'id')
		]);
	}

	public function store()
	{
		$this->_perform_validation();
		if(!empty($this->validation_errors)){
			$this->json_response(['result' => FALSE, 'messages' => $this->validation_errors]);
			return;
		}
		$input = $this->_format_data();
		if($this->salary->create($input)){
			$this->json_response(['result' => TRUE]);
		}
	}

	public function update($id = FALSE)
	{
		if(!$id || !$salary = $this->salary->get($id)){
			$this->json_response(['result' => FALSE, 'messages' => ['Please select a salary.']]);
		}
		$this->_perform_validation();
		if(!empty($this->validation_errors)){
			$this->json_response(['result' => FALSE, 'messages' => $this->validation_errors]);
			return;
		}
		$input = $this->_format_data();
		if($this->salary->update($id, $input)){
			$this->json_response(['result' => TRUE]);
		}
	}

	public function _perform_validation()
	{
		foreach($this->fields AS $field){
			$this->form_validation->set_rules($field['name'], $field['label'], $field['rules']);
		}
		if(!$this->form_validation->run()){
			$this->validation_errors += array_values($this->form_validation->error_array());
		}
		$particulars = $this->input->post('particulars');
		if(!is_array($particulars) && $particulars !== NULL){
			$this->validation_errors[] = 'Particulars should include the an item from the list and the amount.';
			return;
		}
		foreach($particulars AS $row){
			if(!isset($row['amount']) || !isset($row['particulars_id'])){
				$this->validation_errors[] = 'Particulars should include an item from the list and the amount.';
				break;
			}
			if(!$this->form_validation->required($row['particulars_id'])){
				$this->validation_errors[] = 'Particulars should include an item from the list and the amount.';
				break;
			}
			if(!$this->form_validation->decimal(str_replace(',', '', $row['amount']))){
				$this->validation_errors[] = 'Particulars should include an item from the list and the amount.';
				break;
			}
		}
	}

	public function _format_data()
	{
		$data['salary'] = elements(array_column($this->fields, 'name'), $this->input->post());
		if(!$this->input->post('particulars')){
			$data['particulars'] = [];
			return $data;
		}
		$data['particulars'] = array_map(function($var){
			return [
				'particulars_id' => $var['particulars_id'],
				'amount' => str_replace(',', '', $var['amount'])
			];
		}, $this->input->post('particulars'));
		return $data;
	}

	public function _validate_day_of_week($val)
	{
		$this->form_validation->set_message('_validate_day_of_week', 'Please select %s.');
		return in_array($val, array_keys($this->days));
	} 

	public function _validate_am_time($val)
	{
		$this->form_validation->set_message('_validate_am_time', 'Please enter a valid %s.');
		if(!is_valid_date($val, 'H:i')){
			return FALSE;
		}
		$from = strtotime('00:00');
		$to = strtotime('12:00');
		$time = strtotime($val);
		return ($time >= $from) && ($time <= $to);
	}

	public function _validate_pm_time($val)
	{
		$this->form_validation->set_message('_validate_pm_time', 'Please enter a valid %s.');
		if(!is_valid_date($val, 'H:i')){
			return FALSE;
		}
		$from = strtotime('12:00');
		$to = strtotime('23:59');
		$time = strtotime($val);
		return ($time >= $from) && ($time <= $to);
	}
}