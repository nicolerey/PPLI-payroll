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
				$data[$x]['name'] = $name['firstname']." ".$name['middleinitial']." ".$name['lastname'];
				$data[$x]['datetime_in'] = ($attendance['datetime_in']) ? date_format(date_create($attendance['datetime_in']), 'Y-m-d h:i A') : NULL;
				$data[$x]['datetime_out'] = ($attendance['datetime_out']) ? date_format(date_create($attendance['datetime_out']), 'Y-m-d h:i A') : NULL;
				if($attendance['datetime_out']){
					$date_diff = date_diff(date_create($attendance['datetime_out']), date_create($attendance['datetime_in']));
					$data[$x]['total_hours'] = number_format(($date_diff->d * 24) + $date_diff->h + ($date_diff->i / 60) + ($date_diff->s / 60 / 60), 2);
				}
				else
					$data[$x]['total_hours'] = number_format(0, 2);

				$x++;
			}
		}

		$this->import_plugin_script(['bootstrap-datepicker/js/bootstrap-datepicker.min.js', 'x_editable/bootstrap3-editable/js/bootstrap-editable.min.js', 'bootstrap-datetimepicker-smalot/js/bootstrap-datetimepicker.min.js', 'moment.js']);
		$this->import_page_script(['view-attendance.js']);
		$this->generate_page('attendance/view', array('data'=>$data, 'search_employee'=>$search_employee, 'test'=>$test));
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

			$string = str_replace('"', '', $this->dos2unix(file_get_contents('./assets/uploads/'.$file_info['file_name'])));


			$row = explode(PHP_EOL, $string);

			// echo "<pre>";
			// var_dump($row);
			// echo "</pre>";
			// return;

			$upload_batch_id = $this->employee->get_batch_id();
			if(!$upload_batch_id)
				$upload_batch_id = 1;

			$employee_attendance = [];
			$employee_id = 0;
			for($index = 6; $index<count($row); $index++){

				// this may contain the name
				// $col_val = explode('"', $row[$index]);

				// if col val will not contain name then it may contain datetimes
				$dif_val = explode(",", $row[$index]);

				if(($dif_val[0] && $dif_val[1])&& !$this->validateDate($dif_val[0])){
				// if($isset($col_val[1]) && !$this->validateDate($col_val[1])){

					// print_r($dif_val);

					// this will hold lastname
					$lastname = trim($dif_val[0]);

					//this will hold first name and MI
					$temp = explode(" ", $dif_val[1]);
					$mi_index = count($temp) - 1;
					$middleinitial = trim(rtrim($temp[$mi_index], '.'));
					unset($temp[$mi_index]);

					$firstname = trim(implode(" ", $temp));

					// unset($name[0]);
					// $f_m_name = explode(" ", implode(" ", $name));
					// $firstname = [];
					// $middleinitial = "";
					// foreach ($f_m_name as $value) {
					// 	if($value!="" && $value!=" "){
					// 		if(strpos($value, ".")===FALSE)
					// 			array_push($firstname, $value);
					// 		else
					// 			if($value!=".")
					// 				$middleinitial = chop($value, ".");
					// 	}
					// }
					// $firstname = implode(" ", $firstname);
					// $middleinitial = chop($middleinitial, " ");

					$employee_name = [
						'firstname' => $firstname,
						'lastname' => $lastname,
						'middleinitial' => $middleinitial
					];

					$res = $this->employee->get_employee($employee_name);

					$employee_id = $res['id'];
					// echo $employee_id;
					// print_r($employee_name);
				}
				else if($employee_id && isset($dif_val[0]) && $this->validateDate($dif_val[0])){
					// echo "lol";
					$attendance = explode(',', $row[$index]);

					$date = explode(" ", str_replace('"', "", $attendance[0]))[0];
					unset($attendance[0]);

					for($key=1; $key<7; $key+=2){
						if(!$attendance[$key])
							$datetime_in = NULL;
						else
							$datetime_in = date_format(date_create("{$date} ".str_replace('"', '', $attendance[$key])), 'Y-m-d H:i:s');


						if(!$attendance[$key+1])
							$datetime_out = NULL;
						else
							$datetime_out = date_format(date_create("{$date} ".str_replace('"', '', $attendance[$key+1])), 'Y-m-d H:i:s');

						if($datetime_in || $datetime_out){
							$employee_attendance[] = [
								'employee_id' => intval($employee_id),
								'datetime_in' => $datetime_in,
								'datetime_out' => $datetime_out,
								'upload_batch' => $upload_batch_id,
								'created_by' => $this->session->userdata('id'),
								'last_updated_by' => $this->session->userdata('id'),
								'last_approved_by' => $this->session->userdata('id')
							];
						}
					}
				}
			}

			unlink('./assets/uploads/'.$file_info['file_name']);

			// echo "<pre>";
			// var_dump($employee_attendance);
			// echo "</pre>";
			// return;

			if(!empty($employee_attendance)){
				if(count($employee_attendance)>20){
					$arr_chunk = array_chunk($employee_attendance, count($employee_attendance)/20);
					foreach ($arr_chunk as $key => $value) {
						if($this->employee->insert_attendance($value, "BATCH"))
							$this->session->set_flashdata('upload_status', 1);
						else
							$this->session->set_flashdata('upload_status', 0);
					}
				}
				else{
					if($this->employee->insert_attendance($employee_attendance, "BATCH"))
						$this->session->set_flashdata('upload_status', 1);
					else
						$this->session->set_flashdata('upload_status', 0);
				}
			}
			else
				$this->session->set_flashdata('upload_status', 2);

			
			redirect('attendance/view');
		}
	}

	public function validateDate($date)
	{
		$date = explode(" ", $date);
	    $d = DateTime::createFromFormat('n/j/Y', $date[0]);
	    return $d && $d->format('n/j/Y') === $date[0];
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