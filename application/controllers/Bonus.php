<?php

class Bonus extends HR_Controller
{

	protected $tab_title = 'Bonus';
	protected $active_nav = NAV_BONUSES;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Bonus_model' => 'bonus', 'Employee_model' => 'employee', 'Pay_modifier_model' => 'pay_modifier', 'Department_model' => 'department']);
	}

	public function index()
	{
		$bonus = $this->bonus->all(NULL, "id, date, type, pay_modifier_id, multiplier, status, created_by");
		foreach ($bonus as $key => $value) {
			$pay_modifier = $this->pay_modifier->get($value['pay_modifier_id']);
			$bonus[$key]['pay_modifier'] = $pay_modifier['name'];
			$bonus[$key]['created_by'] = $this->employee->get_employee_name($value['created_by']);

			$bonus_employees = $this->bonus->all_table('bonus_employees', ['bonus_id' => $value['id']]);
			$total = 0;
			foreach ($bonus_employees as $bonus_employees_key => $bonus_employees_value) {
				$total += ($bonus_employees_value['daily_wage'] * $value['multiplier']);
			}
			$bonus[$key]['total'] = $total;
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script(['listing-bonus.js']);
		$this->generate_page('bonus/listing', [
			'data' => $bonus
		]);
	}

	public function create()
	{
		$departments = $this->department->all();

		$employees = $this->employee->all();
		$emp = [];
		foreach ($employees as $key => $value) {
			$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
		}

		$pay_modifier = $this->pay_modifier->all_with_condition(['type' => 'a'], ($this->session->userdata('account_type')=='ad')?FALSE:TRUE);

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('manage-bonus.js');
        $this->generate_page('bonus/manage', [
        	'title' => 'Add bonus',
        	'action' => "store",
			'employees' => $emp,
			'departments' => $departments,
			'pay_modifier' => $pay_modifier
        ]);
	}

	public function view($id)
	{
		$departments = $this->department->all();

		$employees = $this->employee->all();
		$emp = [];
		foreach ($employees as $key => $value) {
			$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
		}

		$pay_modifier = $this->pay_modifier->all_with_condition(['type' => 'a'], ($this->session->userdata('account_type')=='ad')?FALSE:TRUE);

		$bonus = $this->bonus->get(['id' => $id]);
		if($bonus['type']=='emp')
			$bonus['employee'] = $this->bonus->get_table('bonus_employees', ['bonus_id' => $id]);
		else
			$bonus['department'] = $this->bonus->all_table('bonus_departments', ['bonus_id' => $id]);

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('manage-bonus.js');
        $this->generate_page('bonus/manage', [
        	'title' => 'Add bonus',
        	'action' => "update",
			'employees' => $emp,
			'departments' => $departments,
			'pay_modifier' => $pay_modifier,
			'data' => $bonus
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
		$input['date'] = date_format(date_create($input['date']), 'Y-m-d');
		$input['created_by'] = $this->session->userdata('id');
		$input['last_updated_by'] = $this->session->userdata('id');
		$department_id = $input['department_id'];
		$employee_id = $input['employee_id'];
		unset($input['change']);
		unset($input['department_id']);
		unset($input['employee_id']);

		if($bonus_id = $this->bonus->insert($input)){
			$this->insert_bonus_employee_department($input['type'], $department_id, $employee_id, $bonus_id);

			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Cannot create bonus. Try again later.']
		]));
		return;
	}

	public function insert_bonus_employees($employee_id, $bonus_id)
	{
		$emp_pos = $this->employee->get_position($employee_id);
		$data = [
			'bonus_id' => $bonus_id,
			'employee_id' => $employee_id,
			'daily_wage' => $emp_pos['daily_rate']
		];

		return $this->bonus->insert_to_table('bonus_employees', $data);
	}

	public function insert_bonus_departments($department_id, $bonus_id)
	{
		$data = [
			'bonus_id' => $bonus_id,
			'department_id' => $department_id
		];

		$this->bonus->insert_to_table('bonus_departments', $data);
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

		$stat = $this->bonus->get(['id' => $input['id']], 'status');
		if($stat['status'] && $this->session->userdata('account_type')=='pm'){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Account not authorized to edit.']
			]));
			return;
		}

		$input['date'] = date_format(date_create($input['date']), 'Y-m-d');
		$input['last_updated_by'] = $this->session->userdata('id');
		$input['condition'] = ['id' => $input['id']];
		$id = $input['id'];
		unset($input['id']);

		$department_id = $input['department_id'];
		$employee_id = $input['employee_id'];
		$change = $input['change'];
		unset($input['department_id']);
		unset($input['employee_id']);
		unset($input['change']);

		if($this->bonus->update($input)){
			if($change==1){
				$this->bonus->delete_table('bonus_employees', ['bonus_id' => $id]);
				$this->bonus->delete_table('bonus_departments', ['bonus_id' => $id]);
				$this->insert_bonus_employee_department($input['type'], $department_id, $employee_id, $id);
			}

			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Cannot update bonus. Try again later.']
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
				'approved_by' => $this->session->userdata('id'),
				'condition' => ['id' => $input['id']]
			];

			if($this->bonus->update($data)){
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

	public function insert_bonus_employee_department($type, $department_id, $employee_id, $bonus_id)
	{
		if($type=='dep'){
			if($department_id!='all'){
				$this->insert_bonus_departments($department_id, $bonus_id);

				$employees = $this->department->get_employees($department_id);
				foreach ($employees as $key => $value) {
					$this->insert_bonus_employees($value['id'], $bonus_id);
				}
			}
			else{
				$departments = $this->department->all();
				foreach ($departments as $departments_key => $departments_value) {
					$this->insert_bonus_departments($departments_value['id'], $bonus_id);

					$employees = $this->department->get_employees($departments_value['id']);
					foreach ($employees as $key => $value) {
						$this->insert_bonus_employees($value['id'], $bonus_id);
					}
				}
			}
		}
		else
			$this->insert_bonus_employees($employee_id, $bonus_id);
	}

	public function delete()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($this->bonus->delete($input)){
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to delete bonus. Please try again later.']
		]));
		return;
	}

	public function _perform_validation()
	{
		$this->form_validation->set_rules('date', 'date', 'required');
		$this->form_validation->set_rules('pay_modifier_id', 'particular', 'required');
		$this->form_validation->set_rules('type', 'type', 'required');
		if($this->input->post('type')=='dep')
			$this->form_validation->set_rules('department_id', 'department', 'required');
		else if($this->input->post('type')=='emp')
			$this->form_validation->set_rules('employee_id', 'employee', 'required');

		$this->form_validation->set_rules('multiplier', 'multiplier', 'required');
	}
}