<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if($this->session->userdata('id')){
			redirect('home');
		}
		$this->load->view('login');
	}

	public function attempt()
	{
		$this->output->set_content_type('json');
		$this->load->model('User_model', 'user');
		$this->form_validation->set_rules('id_number', 'id number', 'required|callback__validate_id_number');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if($this->form_validation->run()){
			$user = $this->user->authenticate($this->input->post('id_number'),  md5($this->input->post('password')));
			if($user){
				if(intval($user['is_locked'])){
					$this->output->set_output(json_encode([
						'result' => FALSE,
						'messages' => ['The account is locked. Please contact an HR officer.']
					]));
				}else{
					$this->session->set_userdata($user);
					$this->output->set_output(json_encode(['result' => TRUE]));
				}
				return;
			}
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Invalid password.']
			]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => array_values($this->form_validation->error_array())
		]));
	}

	public function _validate_id_number($val)
	{
		$this->load->model('User_model', 'user');
		$this->form_validation->set_message('_validate_id_number', 'No account has been associated with your %s. Please contact an HR officer.');
		return $this->user->exists($val);
	}

}
