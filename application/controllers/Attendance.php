<?php

class Attendance extends HR_Controller
{

	protected $logged_employee = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Employee_model' => 'employee', 'Payslip_model' => 'payslip', 'Position_model' => 'position']);
		$this->active_nav = '';
	}

	public function index()
	{
		$this->import_plugin_script(['moment.js']);
		$this->import_page_script('attendance.js');
		$this->generate_page('attendance');
	}

	public function log()
	{
		$this->form_validation->set_rules('uid', 'employee', 'required|callback__validate_uid');
		$this->form_validation->set_rules('timestamp', 'time', 'required');
		if($this->form_validation->run()){
			$this->logged_employee['log_time'] = $this->input->post('timestamp');
			if($result = $this->employee->set_attendance($this->logged_employee['id'], $this->logged_employee['log_time'])){
				$result['datetime_in'] = date_create($result['datetime_in'])->format('d-M-Y h:i A');
				if($result['datetime_out']){
					$result['datetime_out'] = date_create($result['datetime_out'])->format('d-M-Y h:i A');
				}
				$this->logged_employee += elements(['datetime_in', 'datetime_out'], $result);
				$this->json_response(['result' => TRUE, 'data' => $this->logged_employee]);
				return;
			}
			$this->json_response(['result' => FALSE]);
		}else{
			$this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
		}
	}

	public function _validate_uid($uid)
	{
		$this->form_validation->set_message('_validate_uid', "UID: {$uid} does not exist!");
		$employee = $this->employee->get_by_uid($uid);
		if($employee){
			$this->logged_employee = elements([
				'firstname', 'middleinitial', 'lastname', 'department', 'id_number', 'position', 'id'
			], $employee);
		}
		return $this->logged_employee !== NULL;
	}

	public function view()
	{		
		$this->active_nav = NAV_VIEW_ATTENDANCE;
		$data = [];
		$test=  [];
		$range = elements(['start_date', 'end_date', 'employee_number'], $this->input->get(), NULL);

		if(!empty($range['start_date']))
			$start_date = is_valid_date($range['start_date'], 'm/d/Y') ? date_create($range['start_date'])->format('Y-m-d') : date('Y-m-d');
		else
			$start_date = NULL;

		if(!empty($range['end_date']))
			$end_date = is_valid_date($range['end_date'], 'm/d/Y') ? date_create($range['end_date'])->format('Y-m-d') : date('Y-m-d');
		else
			$end_date = NULL;

		$upload_batch_id = $this->employee->get_batch_id();

		$search_employee = TRUE;

		$employee_number = NULL;
		if(!empty($range['employee_number']))
			$employee_number = $range['employee_number'];

		$emp_result = $this->employee->attendance($employee_number, $start_date, $end_date, $upload_batch_id-1);

		if($emp_result){
			$x = 0;
			foreach ($emp_result as $attendance) {
				$name = $this->employee->get_employee_name($attendance['employee_id']);
				$data[$x]['emp_attendance_id'] = $attendance['id'];
				$data[$x]['name'] = $name['firstname']." ".$name['middleinitial']." ".$name['lastname']." ({$attendance['employee_id']})";
				$data[$x]['datetime_in'] = ($attendance['datetime_in']) ? date_format(date_create($attendance['datetime_in']), 'Y-m-d h:i A') : NULL;
				$data[$x]['datetime_out'] = ($attendance['datetime_out']) ? date_format(date_create($attendance['datetime_out']), 'Y-m-d h:i A') : NULL;
				if($attendance['datetime_out'] && $attendance['datetime_in']){
					$date_diff = date_diff(date_create($attendance['datetime_out']), date_create($attendance['datetime_in']));
					$data[$x]['total_hours'] = number_format(($date_diff->d * 24) + $date_diff->h + ($date_diff->i / 60) + ($date_diff->s / 60 / 60), 2);
				}
				else
					$data[$x]['total_hours'] = number_format(0, 2);

				$x++;
			}
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js', 'x_editable/bootstrap3-editable/js/bootstrap-editable.min.js', 'bootstrap-datetimepicker-smalot/js/bootstrap-datetimepicker.min.js', 'moment.js']);
		$this->import_page_script(['view-attendance.js', 'select2.min.js']);
		$this->generate_page('attendance/view', array('data'=>$data, 'search_employee'=>$search_employee, 'test'=>$test, 'employee' => $this->employee->all()));
	}

	function dos2unix($s) {
	    $s = str_replace("\r\n", "\n", $s);
	    $s = str_replace("\r", "\n", $s);
	    $s = preg_replace("/\n{2,}/", "\n\n", $s);
	    return $s;
	}

	public function upload_attendance(){
		$config['upload_path']   = './assets/uploads/'; 
		$config['allowed_types'] = 'txt|csv';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('userfile')){
			$this->session->set_flashdata('upload_status', 0);
			redirect('attendance/view');
		}
		else {
			$file_info = $this->upload->data();

			$this->load->helper('file');

			// convert EOLs to unix (for cross platform compatibility) and strip double quotes
			$string = str_replace('"', '', $this->dos2unix(file_get_contents('./assets/uploads/'.$file_info['file_name'])));

			// convert string to array using EOL as delimiter
			$data = explode(PHP_EOL, $string);

			// get upload batch
			$upload_batch_id = $this->employee->get_batch_id();
			if(!$upload_batch_id) $upload_batch_id = 1;
				
			$employee_attendance = [];
			$employee_id = NULL;

			$start_line = 6;
			$end_line = count($data);

			$employeeData = [];
			$currentUid = NULL;
			$employeeId = NULL;
			$logs = [];

			for($i = $start_line; $i < $end_line ; $i++){

				$uid = NULL;


				/* Try to match if current cursor contains employee name and id. If it did, get UID enclosed in parenthesis */
				preg_match('/\((.*?)\)/', $data[$i], $uid);	
				
				if($uid){
					/* Current cursor contained employee name and UID. Extract UID using regex */
					$currentUid = $uid[1];
					/* Add salt "CEB-" */
					$employeeData[$currentUid]['id'] = "CEB-{$uid[1]}";
					/* Get auto generated id from table using the salted UID */
					$employeeId = $this->employee->get_id_by_uid($employeeData[$currentUid]['id']);
				}else{
					/* This is an empty line or a time log */

					/* The UID doesnt match any employee. Proceed to next line  */
					if(!$employeeId) continue;

					/* This is a time log. Separate each data using comma */
					$cells = explode(',', $data[$i]);

					/* If the separation yields only one member, its a white space. Proceed to next line */
					if(count($cells) === 1) continue;
 
					$dateTemplate = explode(' ', $cells[0]);
					$rawDate = trim($dateTemplate[0]);

					
					if($this->isValidDate($rawDate)){

						$date = date_create_immutable_from_format('n/j/Y', $rawDate)->format('Y-m-d');

						for($ii = 1; $ii < 7; $ii+=2){

							$temp = ['datetime_in' => NULL, 'datetime_out' => NULL];

							if(is_valid_date($cells[$ii], 'g:i A')){
								$time = date_create_immutable_from_format('g:i A', $cells[$ii])->format('H:i');
								$temp['datetime_in'] = "{$date} {$time}";
							}

							if(is_valid_date($cells[$ii+1], 'g:i A')){
								$time = date_create_immutable_from_format('g:i A', $cells[$ii+1])->format('H:i');
								$temp['datetime_out'] = "{$date} {$time}";
							}

							if(!$temp['datetime_out'] && !$temp['datetime_in']){
								continue;
							}

							$employeeData[$currentUid]['logs'][] = $temp;

							$logs[] = [
								'employee_id' => $employeeId,
								'datetime_in' => $temp['datetime_in'],
								'datetime_out' => $temp['datetime_out'],
								'upload_batch' => $upload_batch_id,
								'created_by' => user_id(),
								'last_updated_by' => user_id(),
								'last_approved_by' => user_id()
							];
							
						}

					}
					
				}
				
			}

			unlink('./assets/uploads/'.$file_info['file_name']);

			$this->employee->insert_attendance($logs);

			redirect('attendance/view');
		}
	}

	public function isValidDate($date)
	{
	    $d = DateTime::createFromFormat('n/j/Y', $date);
	    return $d && $d->format('n/j/Y') === $date;
	}

	public function authorize_changes()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		$input['password'] = md5($input['password']);
		$input['account_type'] = 'ad';

		$result = $this->employee->get_employee($input);
		if($result){
			$this->output->set_output(json_encode([
				'status' => TRUE,
				'approved_by' => $result['id']
			]));
		}
		else{
			$this->output->set_output(json_encode([
				'status' => FALSE
			]));
		}

		return;
	}

	public function save_datetime()
	{
		$input = $this->input->post();
		$input = $input['data'];
		foreach ($input as $key => $value) {
			$input[$key]['last_updated_by'] = $this->session->userdata('id');
			if(isset($value['datetime_in']))
				$input[$key]['datetime_in'] = date_format(date_create($value['datetime_in']), 'Y-m-d H:i:s');

			if(isset($value['datetime_out']))
				$input[$key]['datetime_out'] = date_format(date_create($value['datetime_out']), 'Y-m-d H:i:s');
		}

		if($this->employee->update_attendance_batch($input))
			$this->session->set_flashdata('save_status', true);
		else
			$this->session->set_flashdata('save_status', false);

		return;
	}
}