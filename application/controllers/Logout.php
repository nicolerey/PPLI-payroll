<?php

class Logout extends HR_Controller
{
	public function index()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}