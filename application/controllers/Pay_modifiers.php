<?php

class Pay_modifiers extends HR_Controller
{
	protected $active_nav = NAV_PAY_MODIFIERS;
	protected $id;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pay_modifier_model', 'pay_modifier');
	}

	public function index()
	{
		$this->generate_page('pay-modifiers/listing', [
			'items' => $this->pay_modifier->all()
		]);
	}

	public function create()
	{
		$this->import_page_script('manage-pay-modifiers.js');
		$this->generate_page('pay-modifiers/manage', [
			'title' => 'Create new pay particular',
			'mode' => MODE_CREATE, 
			'data' => []
		]);
	}

	public function edit($id = FALSE)
	{
		if(!$id || !$pay_modifier = $this->pay_modifier->get($id)){
			show_404();
		}
		$this->import_page_script('manage-pay-modifiers.js');
		$this->generate_page('pay-modifiers/manage', [
			'title' => 'Update existing pay particular',
			'mode' => MODE_EDIT, 
			'data' => $pay_modifier
		]);
	}

	public function store()
	{
		$this->output->set_content_type('json');
		$this->_perform_validation(MODE_CREATE);
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}
		$input = $this->_format_data(MODE_CREATE);
		if($this->pay_modifier->create($input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to create new pay modifier. Please try again later.']
		]));
		return;
	}

	public function update($id = FALSE)
	{
		$this->output->set_content_type('json');
		if(!$id || !$this->pay_modifier->exists($id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' =>['Please provide a valid pay modifier id to update.']
			]));
			return;
		}
		$this->id = $id;
		$this->_perform_validation(MODE_EDIT);
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}
		$input = $this->_format_data(MODE_EDIT);
		if($this->pay_modifier->update($id, $input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to update pay modifier. Please try again later.']
		]));
		return;
	}

	public function _perform_validation($mode)
	{
		if($mode === MODE_CREATE){
			$this->form_validation->set_rules('name', 'name', 'required|is_unique[pay_modifiers.name]');
		}else{
			$this->form_validation->set_rules('name', 'type', 'required|callback__validate_pay_modifier_name');
		}
		$this->form_validation->set_rules('particular_type', 'particular type', 'required|in_list[d,m]', ['in_list' => 'Please select a valid %s']);
		$this->form_validation->set_rules('type', 'pay modifier type', 'required|in_list[a,d]', ['in_list' => 'Please select a valid %s']);
	}

	public function _format_data($mode)
	{
		$data = elements(['type', 'name', 'particular_type'], $this->input->post());
		return $data;
	}

	public function _validate_pay_modifier_name($val)
	{
		$this->form_validation->set_message('_validate_pay_modifier_name', 'The %s is already in use.');
		return $this->pay_modifier->has_unique_name($val, $this->id);
	}
}