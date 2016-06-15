<?php

class Users extends HR_Controller
{

	protected $tab_title = 'Users';
	protected $active_nav = NAV_USERS;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model', 'user');
	}

	public function index()
	{
		$data = [];
		$id_no = $this->input->get('employee_number');
		if($id_no){
			$this->load->model('Employee_model', 'employee');
			$data['employee_data'] = $this->employee->get(FALSE, $id_no);
			if($data['employee_data']){
				$data['employee_account'] = $this->user->get($data['employee_data']['id']);
			}
		}
		$this->import_page_script('manage-accounts.js');
        $this->generate_page('users/manage', [
        	'data' => $data
    	]);
	}

	public function save($employee_id = FALSE)
	{
		$this->load->model('Employee_model', 'employee');
		$this->output->set_content_type('json');
		if(!$employee_id || !$employee = $this->employee->get($employee_id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Please choose a valid employee.']
			]));
			return;
		}
		$this->form_validation->set_rules('type', 'account type', 'required|in_list[hr,sv,po,re]', ['in_list' => 'Please select a valid %s.']);
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}
		$account = [
			'employee_id' => $employee_id,
			'type' => $this->input->post('type'),
			'password' => md5($employee['id_number']),
			'is_locked' => $this->input->post('is_locked') ? 1 : NULL
		];
		if($password = $this->input->post('password')){
			$account['password'] = md5($password);
		}
		if($this->user->create($account)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Cannot create user account. Please try again later.']
		]));
	}

}