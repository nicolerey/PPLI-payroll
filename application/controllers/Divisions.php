<?php

class Divisions extends HR_Controller
{
	protected $active_nav = NAV_DATA_ENTRY;
	protected $active_subnav = SUBNAV_DIVISIONS;
	protected $id;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Division_model', 'division');
	}

	public function index()
	{
		$this->generate_page('divisions/listing', [
			'items' => $this->division->all()
		]);
	}

	public function create()
	{
		$this->import_page_script('manage-divisions.js');
		$this->generate_page('divisions/manage', [
			'title' => 'Create new division',
			'mode' => MODE_CREATE, 
			'data' => []
		]);
	}

	public function edit($id = FALSE)
	{
		if(!$id || !$division = $this->division->get($id)){
			show_404();
		}
		$this->import_page_script('manage-divisions.js');
		$this->generate_page('divisions/manage', [
			'title' => 'Update existing division',
			'mode' => MODE_EDIT, 
			'data' => $division
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
		if($this->division->create($input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to create new division. Please try again later.']
		]));
		return;
	}

	public function update($id = FALSE)
	{
		$this->output->set_content_type('json');
		if(!$id || !$this->division->exists($id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' =>['Please provide a valid division id to update.']
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
		if($this->division->update($id, $input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to update division. Please try again later.']
		]));
		return;
	}

	public function _perform_validation($mode)
	{
		if($mode === MODE_CREATE){
			$this->form_validation->set_rules('id_number', 'division number', 'required|numeric|is_unique[divisions.id_number]');
			$this->form_validation->set_rules('name', 'division name', 'required|is_unique[divisions.name]');
		}else{
			$this->form_validation->set_rules('name', 'division name', 'required|callback__validate_division_name');
		}
	}

	public function _format_data($mode)
	{
		if($mode === MODE_CREATE){
			return elements(['id_number', 'name'], $this->input->post());
		}else{
			return ['name' => $this->input->post('name')];
		}
	}

	public function _validate_division_name($val)
	{
		$this->form_validation->set_message('_validate_division_name', 'The %s is already in use.');
		return $this->division->has_unique_name($val, $this->id);
	}
}