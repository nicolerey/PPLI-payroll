<?php

class positions extends HR_Controller
{
	protected $active_nav = NAV_DATA_ENTRY;
	protected $active_subnav = SUBNAV_POSITIONS;
	protected $id;

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
		['name' => 'workday[]', 'label' => 'workday/s', 'rules' => 'required']
	];

	protected $validation_errors = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Position_model', 'position');
	}

	public function index()
	{
		$this->import_page_script('position-list.js');
		$this->generate_page('positions/listing', [
			'items' => $this->position->all(),
			'days' => $this->days
		]);
	}

	public function create()
	{
		$this->import_plugin_script('bootstrap-timepicker/bootstrap-timepicker.min.js');
		$this->import_page_script('manage-positions.js');
		$this->generate_page('positions/manage', [
			'title' => 'Create new position',
			'mode' => MODE_CREATE, 
			'days' => $this->days,
			'data' => []
		]);
	}

	public function edit($id = FALSE)
	{
		if(!$id || !$position = $this->position->get($id)){
			show_404();
		}
		$this->import_plugin_script('bootstrap-timepicker/bootstrap-timepicker.min.js');
		$this->import_page_script('manage-positions.js');
		$this->generate_page('positions/manage', [
			'title' => 'Update existing position',
			'mode' => MODE_EDIT, 
			'days' => $this->days,
			'data' => $position
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
		if($this->position->create($input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to create new position. Please try again later.']
		]));
		return;
	}

	public function update($id = FALSE)
	{
		$this->output->set_content_type('json');
		if(!$id || !$this->position->exists($id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' =>['Please provide a valid position id to update.']
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
		if($this->position->update($id, $input)){
			$this->output->set_output(json_encode(['result' => TRUE]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to update position. Please try again later.']
		]));
		return;
	}

	public function delete($pos_id)
	{
		$this->output->set_content_type('json');
		if(!$pos_id || !$this->position->exists($pos_id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' =>['Please provide a valid position id to update.']
			]));
			return;
		}
		$this->id = $pos_id;
		if($this->position->delete($pos_id)){
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}
		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to update position. Please try again later.']
		]));
		return;
	}

	public function _perform_validation($mode)
	{
		if($mode === MODE_CREATE){
			$this->form_validation->set_rules('name', 'position name', 'required|is_unique[positions.name]');
		}else{
			$this->form_validation->set_rules('name', 'position name', 'required|callback__validate_position_name');
		}
		
		$this->form_validation->set_rules('day[]', 'work day', 'required');

		$this->form_validation->set_rules('from_time_1[]', 'starting work time', 'required');
		$this->form_validation->set_rules('to_time_1[]', 'ending work time', 'required');
		$this->form_validation->set_rules('from_time_2[]', 'starting work time', 'required');
		$this->form_validation->set_rules('to_time_2[]', 'ending work time', 'required');
	}

	public function _format_data($mode)
	{
		$input = $this->input->post();
		$data = elements(['name'], $this->input->post());
		
		$workday = [];
		for ($x=0; $x<count($input['day']); $x++) {
			$workday[$x] = [
				'day' => $input['day'][$x],
				'time' => [
					'from_time_1' => $input['from_time_1'][$x],
					'to_time_1' => $input['to_time_1'][$x],
					'from_time_2' => $input['from_time_2'][$x],
					'to_time_2' => $input['to_time_2'][$x]
				]
			];

			$workday_date1 = date_create(date('Y-m-d')." ".$input['from_time_1'][$x]);
			$workday_date2 = date_create(date('Y-m-d')." ".$input['to_time_1'][$x]);
			if(($input['to_time_1'][$x] - $input['from_time_1'][$x])<0)
				$workday_date2 = date_modify($workday_date2, "+1 day");
			$time_diff = date_diff($workday_date2, $workday_date1);
			$workday[$x]['first_hours'] = $time_diff->h + ($time_diff->i / 60);

			$workday_date1 = date_create(date('Y-m-d')." ".$input['from_time_2'][$x]);
			$workday_date2 = date_create(date('Y-m-d')." ".$input['to_time_2'][$x]);
			if(($input['to_time_2'][$x] - $input['from_time_2'][$x])<0)
				$workday_date2 = date_modify($workday_date2, "+1 day");
			$time_diff = date_diff($workday_date2, $workday_date1);
			$workday[$x]['second_hours'] = $time_diff->h + ($time_diff->i / 60);

			$workday[$x]['total_working_hours'] = $workday[$x]['first_hours'] + $workday[$x]['second_hours'];
		}
		
		$data += [
			'attendance_type' => 're',
			'workday' => json_encode($workday)
		];

		return $data;
	}

	public function _validate_position_name($val)
	{
		$this->form_validation->set_message('_validate_position_name', 'The %s is already in use.');
		return $this->position->has_unique_name($val, $this->id);
	}

	public function _validate_workday($val)
	{
		$this->form_validation->set_message('_validate_workday', 'Please select %s.');
		return in_array($val, array_keys($this->days));
	}
}