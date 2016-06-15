<?php

class Home extends HR_Controller
{

	protected $tab_title = 'Home';
	protected $active_nav = NAV_DASHBOARD;

	public function index()
	{
		$this->load->model('Employee_model', 'employee');
		$this->load->model('User_model', 'account');	
		$employee = $this->employee->get(user_id());
		$account = $this->account->get(user_id());

		$this->import_page_script('home.js');
        $this->generate_page('home', compact(['employee', 'account']));
	}

	public function save_password()
	{
		$this->load->model('User_model', 'account');	
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_rules('confirm_password', 'password confirmation', 'required|matches[password]');
		if(!$this->form_validation->run()){
			$this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
			return;
		}
		if($this->account->update_password(user_id(), $this->input->post('password'))){
			$this->json_response(['result' => TRUE]);
			return;
		}
		$this->json_response(['result' => FALSE, 'messages' => ['Unable to perform action due to an unknown error.']]);
	}
}