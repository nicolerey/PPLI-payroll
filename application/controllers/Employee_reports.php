<?php

class Employee_reports extends HR_Controller
{

	protected $tab_title = 'Employee reports';
	protected $active_nav = NAV_REPORTS;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Employee_reports_model' => 'reports', 'Employee_model' => 'employee']);
	}

	public function index()
	{
		$employee_reports = $this->reports->all(NULL, "id, employee_id, title, date, status, created_by");
		foreach ($employee_reports as $key => $value) {
			$employee_reports[$key]['employee_name'] = $this->employee->get_employee_name($value['employee_id']);
			$employee_reports[$key]['created_by'] = $this->employee->get_employee_name($value['created_by']);
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script(['listing-reports.js']);
		$this->generate_page('reports/listing', [
			'data' => $employee_reports
		]);
	}

	public function create()
	{
		$employees = $this->employee->all();
		$emp = [];
		foreach ($employees as $key => $value) {
			$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('create-report.js');
        $this->generate_page('reports/create', [
        	'title' => 'Make a report',
        	'action' => "store",
			'employees' => $emp
        ]);
	}

	public function view($id)
	{
		$employees = $this->employee->all();
		$emp = [];
		foreach ($employees as $key => $value) {
			$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
		}

		$data = $this->reports->get(['id' => $id]);

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
		$this->import_page_script('create-report.js');
        $this->generate_page('reports/create', [
        	'title' => 'Edit report',
        	'action' => "update",
			'employees' => $emp,
			'data' => $data
        ]);
	}

	public function store(){
		$config['upload_path']   = './assets/img/reports'; 
		$config['allowed_types'] = 'jpg|gif|png';
		$this->load->library('upload', $config);

		$input = $this->input->post();
		$input['date'] = date_format(date_create($input['date']), 'Y-m-d');
		$input['created_by'] = $this->session->userdata('id');
		$input['last_updated_by'] = $this->session->userdata('id');
		$input['resolved_by'] = $this->session->userdata('id');
		unset($input['submit']);

		if($_FILES['image']['name']){
			if (!$this->upload->do_upload('image')){
				$employees = $this->employee->all();
				$emp = [];
				foreach ($employees as $key => $value) {
					$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
				}

				$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
				$this->import_page_script('create-report.js');
		        $this->generate_page('reports/create', [
		        	'action' => "store",
		        	'data' => $$this->reports->get(['id' => $input['id']]),
		        	'employees' => $emp,
		        	'image_error' => $this->upload->display_errors()
		        ]);
			}
			else{
				$file_info = $this->upload->data();

				$input['image'] = $file_info['file_name'];
				$this->reports->insert($input);

				redirect('employee_reports');
			}
		}
		else{
			$this->reports->insert($input);

			redirect('employee_reports');
		}
	}

	public function update()
	{
		$config['upload_path']   = './assets/img/reports'; 
		$config['allowed_types'] = 'jpg|gif|png';
		$this->load->library('upload', $config);

		$input = $this->input->post();

		$stat = $this->reports->get(['id' => $input['id']], 'status');
		if($stat['status'] && $this->session->userdata('account_type')=='pm'){
			$employees = $this->employee->all();
			$emp = [];
			foreach ($employees as $key => $value) {
				$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
			}

			$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
			$this->import_page_script('create-report.js');
	        $this->generate_page('reports/create', [
	        	'title' => 'Edit report',
	        	'action' => "update",
	        	'data' => $$this->reports->get(['id' => $input['id']]),
	        	'employees' => $emp,
	        	'edit_error' => TRUE
	        ]);
		}

		$input['date'] = date_format(date_create($input['date']), 'Y-m-d');
		$input['last_updated_by'] = $this->session->userdata('id');

		if(isset($input['submit'])){
			$input['condition'] = [
				'id' => $input['id']
			];
			$id = $input['id'];
			unset($input['id']);
			unset($input['submit']);

			if($_FILES['image']['name']){
				if (!$this->upload->do_upload('image')){
					$employees = $this->employee->all();
					$emp = [];
					foreach ($employees as $key => $value) {
						$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
					}

					$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
					$this->import_page_script('create-report.js');
			        $this->generate_page('reports/create', [
			        	'title' => 'Edit report',
			        	'action' => "update",
			        	'data' => $this->reports->get(['id' => $id]),
			        	'employees' => $emp,
			        	'image_error' => $this->upload->display_errors()
			        ]);
				}
				else{
					$file_info = $this->upload->data();

					$input['image'] = $file_info['file_name'];
					$this->reports->update($input);

					redirect('employee_reports');
				}
			}
			else{
				$this->reports->update($input);

				redirect('employee_reports');
			}
		}
		else if(isset($input['remove'])){
			$report = $this->reports->get(['id' => $input['id']]);
			if(!$report['status'] || ($report['status'] && $this->session->userdata('account_type')=='ad')){
				$this->reports->update(['image' => NULL, 'condition' => ['id' => $input['id']]]);

				$employees = $this->employee->all();
				$emp = [];
				foreach ($employees as $key => $value) {
					$emp[$value['id']] = "{$value['lastname']}, {$value['firstname']} {$value['middleinitial']}.";
				}

				$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js']);
				$this->import_page_script('create-report.js');
		        $this->generate_page('reports/create', [
		        	'title' => 'Edit report',
		        	'action' => "update",
		        	'data' => $this->reports->get(['id' => $input['id']]),
		        	'employees' => $emp
		        ]);
		    }
		}
		else if(isset($input['resolve'])){
			if($this->session->userdata('account_type')=='ad')
				$this->reports->update(['status' => TRUE, 'condition' => ['id' => $input['id']]]);

			redirect('employee_reports');
		}
	}

	public function delete()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($this->reports->delete($input)){
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Unable to delete report. Please try again later.']
		]));
		return;
	}

	public function _perform_validation()
	{
		$this->form_validation->set_rules('report_date', 'report date', 'required');
		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('body', 'report body', 'required');
	}

	public function _format_data()
	{
		$reports_info = [];
		$reports_info += elements([
			'employee_id',
			'report_date',
			'title',
			'body',
			'id'
		], $this->input->post(), NULL);

		return $reports_info;
	}
}