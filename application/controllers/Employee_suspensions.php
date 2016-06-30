<?php

class Employee_suspensions extends HR_Controller
{

	protected $tab_title = 'Employee suspensions';
	protected $active_nav = NAV_SUSPENSIONS;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Employee_suspensions_model' => 'suspensions', 'Employee_model' => 'employee']);
	}

	public function index()
	{
		$employee_suspensions = $this->suspensions->all(NULL, "id, employee_id, title, end_date, start_date, status, created_by");
		foreach ($employee_suspensions as $key => $value) {
			$employee_suspensions[$key]['employee_name'] = $this->employee->get_employee_name($value['employee_id']);
			$employee_suspensions[$key]['created_by'] = $this->employee->get_employee_name($value['created_by']);
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script(['listing-suspensions.js']);
		$this->generate_page('suspensions/listing', [
			'data' => $employee_suspensions
		]);
	}

	public function create()
	{
		$employees = $this->employee->all();
		$emp = [];
		foreach ($employees as $key => $value) {
			$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('manage-suspension.js');
        $this->generate_page('suspensions/manage', [
        	'title' => 'Make a suspension',
        	'action' => "store",
			'employees' => $emp
        ]);
	}

	public function view($id)
	{
		$employees = $this->employee->all();
		$emp = [];
		foreach ($employees as $key => $value) {
			$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
		}

		$data = $this->suspensions->get(['id' => $id]);

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('manage-suspension.js');
        $this->generate_page('suspensions/manage', [
        	'title' => 'Edit suspension',
        	'action' => "update",
			'employees' => $emp,
			'data' => $data
        ]);
	}

	public function store()
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

		$input = $this->input->post();
		$input['start_date'] = date_format(date_create($input['start_date']), 'Y-m-d');
		$input['end_date'] = date_format(date_create($input['end_date']), 'Y-m-d');
		$input['created_by'] = $this->session->userdata('id');
		$input['last_updated_by'] = $this->session->userdata('id');
		$input['resolved_by'] = NULL;

		if($this->suspensions->insert($input)){
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Cannot create suspension. Try again later.']
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

		$input = $this->input->post();

		$stat = $this->suspensions->get(['id' => $input['id']], 'status');
		if($stat['status'] && $this->session->userdata('account_type')=='pm'){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Account is not authorized to edit.']
			]));
			return;
		}

		$input['start_date'] = date_format(date_create($input['start_date']), 'Y-m-d');
		$input['end_date'] = date_format(date_create($input['end_date']), 'Y-m-d');
		$input['resolved_by'] = $this->session->userdata('id');
		$input['condition'] = ['id' => $input['id']];
		unset($input['id']);

		if($this->suspensions->update($input)){
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Cannot update suspension. Try again later.']
		]));
		return;
	}

	public function approve()
	{
		$this->output->set_content_type('json');

		if($this->session->userdata('account_type')=='ad'){
			$input = $this->input->post();
			$data = [
				'status' => TRUE,
				'last_updated_by' => $this->session->userdata('id'),
				'resolved_by' => $this->session->userdata('id'),
				'condition' => ['id' => $input['id']]
			];

			if($this->suspensions->update($data)){
				$this->output->set_output(json_encode([
					'result' => TRUE
				]));
				return;
			}

			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Cannot approve bonus. Try again later.']
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Account is invalid.']
		]));
		return;
	}

	public function delete()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($this->suspensions->delete($input)){
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to delete report. Please try again later.']
		]));
		return;
	}

	public function _perform_validation()
	{
		$this->form_validation->set_rules('start_date', 'start date', 'required');
		$this->form_validation->set_rules('end_date', 'end date', 'required');
		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('employee_id', 'employee', 'required');
		$this->form_validation->set_rules('body', 'body', 'required');
	}
}