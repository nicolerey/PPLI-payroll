<?php

class Request_model extends CI_Model
{
	protected $table = 'employee_requests';   

	public function __construct()
	{
		parent::__construct();
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data) ? $this->db->insert_id() : FALSE;
	}

	public function get_by_department($department_id, $status = FALSE)
	{
		$this->db->select('empreq.*, CONCAT(emp.lastname, ", ", emp.firstname, " ", emp.middlename) AS sender_fullname', FALSE);
		$this->db->from($this->table. ' AS empreq');
		$this->db->join('employees AS emp', 'emp.id = empreq.sender_id');
		$this->db->where('empreq.department_id', $department_id);
		if($status){
			$this->db->where('empreq.status', $status);
		}
		$this->db->order_by('empreq.id', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get($id)
	{
		$this->db->select('empreq.*, CONCAT(emp.lastname, ", ", emp.firstname, " ", emp.middlename) AS sender_fullname', FALSE);
		$this->db->from($this->table. ' AS empreq');
		$this->db->join('employees AS emp', 'emp.id = empreq.sender_id');
		return $this->db->get_where($this->table, ['empreq.id' => $id])->row_array();
	}

	public function set_status($id, $status)
	{
		return $this->db->update($this->table, ['status' => $status], ['id' => $id]);
	}

	public function exists($id)
    {
        return $this->db->select('id')->from($this->table)->where('id', $id)->get()->num_rows() === 1;
    }

    public function set_attendance_for_leave($request_id)
    {
    	$request = $this->get($request_id);
    	if(in_array($request['type'], ['o', 'vl'])){
    		return TRUE;
    	}
    	$this->load->model('Employee_model', 'employee');
        $this->load->model('Position_model', 'position');

    	$requestor_position = $this->employee->get_position($request['sender_id']);
    	$requestor_schedule = $this->position->get($requestor_position['id']);

    	$start_leave = strtotime($request['date_start']);
    	$end_leave = strtotime($request['date_end']);

    	$start = date_create($request['date_start']);
        $end = date_create($request['date_end']);
        $duration = date_diff($start, $end)->format('%a')+1;    

        $request_schedule = [];
        $this->db->trans_start();
        $this->db->delete('employee_attendance', ['request_id' => $request_id]);
    	for($x = 0; $x < $duration; $x++){
    		$int_date = strtotime("+{$x} day", $start_leave);
    		$date = date('Y-m-d', $int_date);
    		$day = date('N', $int_date);
    		if($day >= $requestor_schedule['day_of_week_start'] && $day <= $requestor_schedule['day_of_week_end']){

                $temp = [
                    'employee_id' => $request['sender_id'],
                    'request_id' => $request_id
                ];

                if($request['halfday'] === 'am'){
                    $temp += [
                        'datetime_in' => "{$date} {$requestor_schedule['hour_of_day_start_am']}",
                        'datetime_out' => "{$date} {$requestor_schedule['hour_of_day_end_am']}",
                    ];
                }else if($request['halfday'] === 'pm'){
                    $temp += [
                        'datetime_in' => "{$date} {$requestor_schedule['hour_of_day_start_pm']}",
                        'datetime_out' => "{$date} {$requestor_schedule['hour_of_day_end_pm']}",
                    ];
                }else{
                    $temp += [
                        'datetime_in' => "{$date} {$requestor_schedule['hour_of_day_start_am']}",
                        'datetime_out' => "{$date} {$requestor_schedule['hour_of_day_end_pm']}",
                    ];
                }

                $request_schedule[] = $temp;
    		}
    	}
    	$this->db->insert_batch('employee_attendance', $request_schedule);
        $this->db->trans_complete();
    	return $this->db->trans_status();
        
    }

  

    public function update($id, $data)
    {
    	$this->db->trans_start();
    	$this->db->update($this->table, $data, ['id' => $id]);
    	if(isset($data['status']) && $data['status'] === 'a'){
    		$this->set_attendance_for_leave($id);
    	}
    	$this->db->trans_complete();
    	return $this->db->trans_status();
    }

}