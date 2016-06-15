<?php

class Payroll extends HR_Controller
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
		$this->generate_page('payroll/listing', [
			'items' => $this->payslip->all(user_id())
		]);
	}

	public function view($id)
	{
		$this->load->model('Employee_model', 'employee');
		$this->generate_page('my-payslip/view', [
			'payslip' => $this->payslip->get_by_employee($id, user_id()),
			'employee_data' => $this->employee->get(user_id())
		]);
	}


}