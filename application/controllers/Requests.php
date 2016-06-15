<?php

class Requests extends HR_Controller
{
	protected $active_nav;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Request_model', 'request');
		$this->load->model('Employee_model', 'employee');
	}

	public function view_all()
	{
		$extras = [];
		$this->active_nav = NAV_VIEW_REQUESTS;
		$department_id = $this->employee->get_department(user_id());
		$status = $this->input->get('status');
		if(!in_array($status, ['p', 'a', 'da'])){
			$status = 'p';
		}
		switch($status){
			case 'p': 
				$extras['request_status'] = 'Pending';
				$this->active_subnav = SUBNAV_PENDING_REQUESTS;
				break;
			case 'a': 
				$extras['request_status'] = 'Approved';
				$this->active_subnav = SUBNAV_APPROVED_REQUESTS;
				break;
			case 'da': 
				$extras['request_status'] = 'Discarded';
				$this->active_subnav = SUBNAV_DISCARDED_REQUESTS;
				break;
		}
		$extras['items'] = $this->request->get_by_department($department_id, $status);
		$this->generate_page('requests/listing', $extras);
	}

	public function track()
	{
		$this->active_nav = NAV_TRACK_REQUESTS;
		$this->generate_page('requests/listing', [
			'request_status' => 'Track',
			'items' => $this->employee->get_filed_requests(user_id()),
			'edit' => TRUE
		]);
	}

	public function edit($id)
	{
		$this->active_nav = NAV_TRACK_REQUESTS;
		$this->generate_page('requests/listing', [
			'data' => TRUE
		]);
	}

	public function view($id = FALSE)
	{
		if(!$id || !$request = $this->request->get($id)){
			show_404();
		}
		$extras = [];
		$this->active_nav = NAV_VIEW_REQUESTS;
		switch($request['status']){
			case 'p': 
				$extras['request_status'] = 'Pending';
				$this->active_subnav = SUBNAV_PENDING_REQUESTS;
				break;
			case 'a': 
				$extras['request_status'] = 'Approved';
				$this->active_subnav = SUBNAV_APPROVED_REQUESTS;
				break;
			case 'da': 
				$extras['request_status'] = 'Discarded';
				$this->active_subnav = SUBNAV_DISCARDED_REQUESTS;
				break;
			default: 
				$extras['request_status'] = 'Track';
				$this->active_subnav = NAV_TRACK_REQUESTS;
				break;
		}
		$extras['title'] = 'View request';
		$extras['data'] = $request;
		$this->import_page_script('file-request.js');
		$this->generate_page('requests/view', $extras);
	}

	public function file_request()
	{
		$this->active_nav = NAV_FILE_REQUEST;
		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js', 'moment.js']);
		$this->import_page_script('file-request.js');
		$this->generate_page('requests/file-request', [
			'title' => 'File new request',
			'data' => [],
			'sick' => $this->employee->get_remaining_sick_leaves(user_id()),
			'menstruation' => $this->employee->get_remaining_menstruation_leaves(user_id())
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
		if($this->request->create($this->_format_data())){
			$this->output->set_output(json_encode(['result' => TRUE]));
		}else{
			$this->output->set_output(json_encode(['result' => FALSE, 'messages' => ['Cannot file request. Please try again later.']]));
		}

	}

	public function update($id = FALSE)
	{
		$this->output->set_content_type('json');
		if(!$id || !$request = $this->request->get($id)){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Please select a request to update!']
			]));
			return;
		}
		$this->_perform_validation(MODE_EDIT);
		if(!$this->form_validation->run()){
			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => array_values($this->form_validation->error_array())
			]));
			return;
		}
		$data = [];
		if(role_is('sv')){
			$data['status'] = $this->input->post('status');
		}
		if(role_is('re') && $request['status'] === 'a'){
			$data['is_acknowledged'] = $this->input->post('is_acknowledged') ? 1 : 0;
		}
		if($this->request->update($id, $data)){
			$this->output->set_output(json_encode(['result' => TRUE]));
		}else{
			$this->output->set_output(json_encode(['result' => FALSE, 'messages' => ['Cannot update request. Please try again later.']]));
		}
	}

	public function _perform_validation($mode)
	{
		if($mode === MODE_CREATE){
			$this->form_validation->set_rules('date_start', 'date start', 'required|callback__validate_date');
			$this->form_validation->set_rules('date_end', 'date end', 'required|callback__validate_date');
			$this->form_validation->set_rules('custom_type_name', 'leave title', 'callback__validate_custom_type_name');
			$this->form_validation->set_rules('content', 'request content', 'required');
			$this->form_validation->set_rules('type', 'request type', 'required|in_list[matpat,vl,o,sl,wml]|callback__validate_leave_type', ['required' => 'Please select a valid leave type.']);
		}else{
			$this->form_validation->set_rules('status', 'status', 'in_list[a,da,p]|callback__validate_status', ['in_list' => 'Please select a valid %s.']);
		}
	}

	public function _format_data()
	{
		$this->load->model('Employee_model', 'employee');
		$data = [
			'datetime_filed' => date('Y-m-d H:i:s'),
			'date_start' => format_date($this->input->post('date_start')),
			'date_end' => format_date($this->input->post('date_end')),
			'custom_type_name' => $this->input->post('type') === 'o' ? $this->input->post('custom_type_name') : NULL,
			'content' => $this->input->post('content'),
			'type' => $this->input->post('type'),
			'status' => 'p',
			'sender_id' => user_id(),
			'department_id' => $this->employee->get_department(user_id())
		];
		if(($data['date_start'] === $data['date_end']) && in_array($this->input->post('halfday'), ['am', 'pm'])){
			$data['halfday'] = $this->input->post('halfday');
		}
		return $data;
	}

	public function _validate_leave_type($type)
	{
		$start_leave = date_create($this->input->post('date_start'));
        $end_leave = date_create($this->input->post('date_end'));
        $duration = date_diff($end_leave, $start_leave)->format('%a')+1;

        if(intval($duration) === 1 && in_array($this->input->post('halfday'), ['am', 'pm'])){
        	$duration = 0.5;
        }

		if($type === 'sl'){
			$remaining = $this->employee->get_remaining_sick_leaves(user_id());
			$this->form_validation->set_message('_validate_leave_type', "Not enought credit for sick leave. Remaining: {$remaining}");
			return $remaining >= $duration;
		}else if($type === 'wml'){
			$remaining = $this->employee->get_remaining_menstruation_leaves(user_id());
			$this->form_validation->set_message('_validate_leave_type', "Not enought credit for menstruation leave. Remaining: {$remaining}");
			return $remaining >= $duration;
		}
	}

	public function _validate_date($val)
	{
		$this->form_validation->set_message('_validate_date', 'Please input a valid %s');
		return is_valid_date($val, 'm/d/Y');
	}

	public function _validate_custom_type_name($name)
	{
		$this->form_validation->set_message('_validate_custom_type_name', 'Please fill up the %s');
		if($this->input->post('type') === 'o'){
			return trim($name);
		}
		return TRUE;
	}

	public function _validate_status($val)
	{
		$this->form_validation->set_message('_validate_status', 'You are not allowed to update requests.');
		return trim($val) ? role_is('sv') : TRUE;
	}
}