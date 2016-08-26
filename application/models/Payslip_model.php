<?php

class Payslip_model extends CI_Model
{
	protected $table = 'payroll';

	public function calculate($employee_id, $from, $to, $bypass_check = FALSE)
	{
		$this->check($employee_id, $from, $to);

		$this->load->model(['Employee_model' => 'employee', 'Position_model' => 'position', 'Loan_model' => 'loan']);
		$employee_data = $this->employee->get($employee_id);
		$position = $this->position->get($employee_data['position_id']);

		$upload_batch_id = $this->employee->get_batch_id();

		$attendance = $this->employee->get_attendance($employee_id, $from, $to, $upload_batch_id-1);
		if(!$attendance){
			return [];
		}

		$total_regular_hrs = 0;
		$total_overtime_hrs = 0;
		$total_late_minutes = 0;

		// real actual hrs rendered
		$actual_hrs_rendered = 0;

		$pos_workday = json_decode($position['workday'], true);

		$emp_att = [];
		if(!empty($pos_workday)){
			foreach($attendance AS $row){
				$datetime_in = date_create($row['datetime_in']); //datetime in
				$datetime_out = date_create($row['datetime_out']); //datetime out

				//extract time from datetime in and out
				$time_in = date_format($datetime_in, 'h:i A');
				$time_out = date_format($datetime_out, 'h:i A');			

				$day_in = date_format($datetime_in, 'N'); //get day in numeric form
				$day_out = date_format($datetime_out, 'N');

				$date_in = date_format($datetime_in, 'Y-m-d');				
				$date_out = date_format($datetime_out, 'Y-m-d');

				if(!$row['datetime_in'] || !$row['datetime_out']){
					continue;
				}

				foreach ($pos_workday as $key => $value) {
					$late = 0;
					$workhours = 0;
					$am_workhours = 0;
					$pm_workhours = 0;
					$ot = 0;

					$att_flag = 0;

					$first_workday_date1 = date_create($date_in." ".$value['time']['from_time_1']);
					$first_workday_date2 = date_create($date_in." ".$value['time']['to_time_1']);

					$second_workday_date1 = date_create($date_in." ".$value['time']['from_time_2']);
					$second_workday_date2 = date_create($date_in." ".$value['time']['to_time_2']);

					if($value['day']==$day_in){
						if(($first_workday_date2>$datetime_in && $first_workday_date2<$datetime_out) && ($second_workday_date1>$datetime_in && $second_workday_date1<$datetime_out)){

							if($first_workday_date1<$datetime_in){
								$late_diff = date_diff($datetime_in, $first_workday_date1);
								$late = ($late_diff->h * 60) + $late_diff->i + ($late_diff->s / 60);
								$time_diff = date_diff($first_workday_date2, $datetime_in);
							}
							else if($first_workday_date1>=$datetime_in)
								$time_diff = date_diff($first_workday_date2, $first_workday_date1);

							$am_workhours = $time_diff->h + ($time_diff->i / 60) + ($time_diff->s / 60 / 60);
							$workhours += $am_workhours;

							if($datetime_out>$second_workday_date2){
								$time_diff = date_diff($second_workday_date2, $second_workday_date1);
								$t_d = date_diff($datetime_out, $second_workday_date2);
								$ot += ($t_d->h + ($t_d->i / 60) + ($t_d->s / 60 / 60));
							}
							else
								$time_diff = date_diff($datetime_out, $second_workday_date1);

							$hours = $time_diff->h + ($time_diff->i / 60) + ($time_diff->s / 60 / 60);
							$workhours += $hours;
							$pm_workhours += $hours;

							$att_flag = 1;
						}
						else{
							if($second_workday_date1>$datetime_in && $second_workday_date1>$datetime_out){
								if($first_workday_date1<=$datetime_in && $first_workday_date2>=$datetime_in){

									$late_diff = date_diff($datetime_in, $first_workday_date1);
									$late = ($late_diff->h * 60) + $late_diff->i + ($late_diff->s / 60);

									if($datetime_out>$first_workday_date2)
										$time_diff = date_diff($first_workday_date2, $datetime_in);
									else
										$time_diff = date_diff($datetime_out, $datetime_in);
									$workhours += $time_diff->h + ($time_diff->i / 60) + ($time_diff->s / 60 / 60);
									$am_workhours += $workhours;

									$att_flag = 1;
								}
								else if($first_workday_date1>$datetime_in && $first_workday_date1<$datetime_out){

									if($datetime_out>$first_workday_date2)
										$time_diff = date_diff($first_workday_date2, $first_workday_date1);
									else
										$time_diff = date_diff($datetime_out, $first_workday_date1);

									$workhours += $time_diff->h + ($time_diff->i / 60) + ($time_diff->s / 60 / 60);
									$am_workhours += $workhours;

									$att_flag = 1;
								}
							}
							else{
								if($second_workday_date1<=$datetime_in && $second_workday_date2>=$datetime_in){

									$late_diff = date_diff($datetime_in, $second_workday_date1);
									$late = ($late_diff->h * 60) + $late_diff->i + ($late_diff->s / 60);

									if($datetime_out>$second_workday_date2){
										$time_diff = date_diff($second_workday_date2, $datetime_in);
										$t_d = date_diff($datetime_out, $second_workday_date2);
										$ot += ($t_d->h + ($t_d->i / 60) + ($t_d->s / 60 / 60));
									}
									else
										$time_diff = date_diff($datetime_out, $datetime_in);
									$workhours += $time_diff->h + ($time_diff->i / 60) + ($time_diff->s / 60 / 60);
									$pm_workhours += $workhours;

									$att_flag = 1;
								}
								else if($second_workday_date1>$datetime_in && $second_workday_date1<$datetime_out){

									if($datetime_out>$second_workday_date2){
										$time_diff = date_diff($second_workday_date2, $second_workday_date1);
										$t_d = date_diff($datetime_out, $second_workday_date2);
										$ot += ($t_d->h + ($t_d->i / 60) + ($t_d->s / 60 / 60));
									}
									else
										$time_diff = date_diff($datetime_out, $second_workday_date1);

									$workhours += $time_diff->h + ($time_diff->i / 60) + ($time_diff->s / 60 / 60);
									$pm_workhours += $workhours;

									$att_flag = 1;
								}
							}
						}

						if($att_flag){
							$emp_att_flag = 1;
							if(!empty($emp_att)){
								foreach($emp_att as $emp_att_index=>$emp_att_value){
									if($emp_att_value['date']==$date_in){
										$emp_att[$emp_att_index]['total_late'] += $late;
										$emp_att[$emp_att_index]['total_first_hours'] += $am_workhours;
										$emp_att[$emp_att_index]['total_second_hours'] += $pm_workhours;
										$emp_att[$emp_att_index]['total_working_hours'] += $workhours;
										$emp_att[$emp_att_index]['overtime'] += $ot;

										$emp_att_flag = 0;
										break;
									}
								}
							}

							if($emp_att_flag){
								array_push($emp_att, [
									'date' => $date_in,
									'workday_index' => $key,
									'total_late' => $late,
									'total_first_hours' => $am_workhours,
									'total_second_hours' => $pm_workhours,
									'total_working_hours' => $workhours,
									'overtime' => $ot
								]);
							}

							break;
						}
					}
				}
			}
		}

		$data['employee_profile'] = $employee_data;
		$data['attendance'] = $attendance;
		$data['additionals'] = [];
		$data['deductions'] = [];
		$data['total_daily_deductions'] = 0;
		$data['total_monthly_deductions'] = 0;
		$data['total_deductions'] = 0;
		$data['total_daily_additionals'] = 0;
		$data['total_monthly_additionals'] = 0;
		$data['total_additionals'] = 0;

		$emp_loan = $this->loan->all($employee_data['id'], NULL, NULL, NULL, $from, $to);
		if($emp_loan){
			foreach ($emp_loan as $key => $loan) {
				$data['total_deductions'] += $loan['loan_minimum_pay'];
			}
		}

		array_map(function($var) USE(&$data){
			if($var['type'] === 'a'){
				$data['additionals'][] = $var;
				if($var['particular_type'] === 'd')
					$data['total_daily_additionals']  += $var['amount'];
				else if($var['particular_type'] === 'm')
					$data['total_monthly_additionals']  += $var['amount'];
				return;
			}
			$data['deductions'][] = $var;
			if($var['particular_type'] === 'd')
				$data['total_daily_deductions']  += $var['amount'];
			else if($var['particular_type'] === 'm')
				$data['total_monthly_deductions']  += $var['amount'];
		}, $employee_data['particulars']);

		$total_overtime_hrs = 0;
		$total_regular_hrs = 0;
		$total_late_minutes = 0;
		$total_regular_days = 0;
		$data['regular_overtime_pay'] = $position['daily_rate'] * ($position['overtime_rate'] / 100);
		if(!empty($emp_att)){
			foreach ($emp_att as $key => $value) {
				if($value['total_late']>$position['allowed_late_period']){
					$total_late_minutes += $value['total_late'] - $position['allowed_late_period'];
				}

				$total_regular_hrs += $value['total_working_hours'];

				$total_overtime_hrs += floor($value['overtime']);

				if($value['total_working_hours']>=$pos_workday[$value['workday_index']]['total_working_hours'])
					$total_regular_days += 1;
				else
					$total_regular_days += $value['total_working_hours'] / $pos_workday[$value['workday_index']]['total_working_hours'];
			}
		}

		$data['daily_wage'] = $position['daily_rate'];
		$data['daily_wage_units'] = 1;
		$data['late_penalty'] = $position['late_penalty'];

		$data['total_regular_days'] = round($total_regular_days, 2);
		$data['total_overtime_hrs'] = round($total_overtime_hrs, 2);
		$data['total_late_minutes'] = round($total_late_minutes, 2);
		$data['total_late_deduction'] = $data['total_late_minutes'] * $position['late_penalty'];

		$data['regular_pay'] = round($data['total_regular_days'] * $position['daily_rate'], 2);

		$data['total_earnings'] = $data['regular_pay'] + $data['regular_overtime_pay'];

		$data['total_additionals'] += ($data['total_regular_days'] * $data['total_daily_additionals']);
		$data['total_deductions'] += ($data['total_regular_days'] * $data['total_daily_deductions']);

		$data['net_pay'] = $data['total_earnings'] + $data['total_additionals'] - $data['total_deductions'] - $data['total_late_deduction'];
		
		return $data;
	}

	public function get_current_month_payroll()
	{
		$month = date('m');
		$year = date('Y');

		$condition = [
			'month(start_date)' => $month,
			'year(start_date)' => $year,
			'month(end_date)' => $month,
			'year(end_date)' => $year
		];

		$this->db->select('id');
		return $this->db->get_where('payroll', $condition)->result_array();
	}

	public function check_particular_in_payslip($id, $payroll)
	{
		$this->db->where_in('payroll_id', $payroll);
		return $this->db->get('payroll_particulars')->result_array();
	}

	public function get_date_difference($datetime_1, $datetime_2)
	{
		$date_difference = date_diff($datetime_2, $datetime_1);
		if($date_difference->invert==1){
			$hour = $date_difference->h + ($date_difference->d * 24);
			$minutes = $date_difference->i / 60;

			return $hour+$minutes;
		}

		return NULL;
	}

	public function search_for_day_in_workday($data, $value_to_search)
	{
		$value_index = [];
		foreach ($data as $key => $value) {
			if($value['day']==$value_to_search)
				array_push($value_index, $key);
		}

		return $value_index;
	}

	public function get_last_batch_id()
	{
		$this->db->select_max('batch_id');
		return $this->db->get($this->table)->row_array();
	}

	public function get_payroll_status($id)
	{
		$this->db->select('approval_status');
		return $this->db->get_where($this->table, ['id' => $id])->row_array();
	}

	public function create($employee_number, $range, $batch_id, $adjustment = 0)
	{
		$this->load->model(['Loan_model' => 'loan']);

		$payslip = $this->calculate($employee_number, $range[0], $range[1]);
		if(is_numeric($payslip) || empty($payslip)){
			return;
		}
		$data = [
			'employee_id' => $employee_number,
			'start_date' => $range[0],
			'end_date' => $range[1],
			'daily_wage_units' => 1,
			'days_rendered' => $payslip['total_regular_days'],
			'overtime_hours_rendered' => $payslip['total_overtime_hrs'],
			'late_minutes' => $payslip['total_late_minutes'],
			'wage_adjustment' => $adjustment,
			'current_daily_wage' => $payslip['daily_wage'],
			'current_late_penalty' => $payslip['late_penalty'],
			'overtime_pay' => $payslip['regular_overtime_pay'],
			'created_by' => user_id(),
			'batch_id' => $batch_id
		];
		if($this->session->userdata('account_type')=='ad'){
			$data['approval_status'] = TRUE;
			$data['approved_by'] = $this->session->userdata('id');
		}
		else
			$data['approved_by'] = NULL;

		$this->db->trans_start();

		$this->db->insert('payroll', $data);
		$id = $this->db->insert_id();

		$particulars = [];				
		$payroll = $this->get_current_month_payroll();
		$payroll = array_column($payroll, 'id');
		foreach($payslip['employee_profile']['particulars'] AS $p){
			$particular_flag = 1;
			if($p['particular_type']==='m'){
				if($this->check_particular_in_payslip($p['id'], $payroll))
					$particular_flag = 0;
			}

			if($particular_flag){
				$particulars[] = [
					'payroll_id' => $id,
					'particulars_id' => $p['particulars_id'],
					'amount' => $p['amount']
				];
			}
		}

		if(!empty($particulars)){
			$this->db->insert_batch('payroll_particulars', $particulars);
		}

		$loans = $this->loan->all($employee_number);
		foreach ($loans as $key => $value) {
			$payment_total = 0;
			foreach ($value['payment_terms'] as $payments_key => $payments_value) {
				$payment_total += $payments_value['payment_amount'];
			}

			if($payment_total<$value['loan_amount']){
				$payroll_data = [
					'payroll_id' => $id,
					'loan_id' => $value['id'],
					'amount' => 0.00
				];
				$payment_data = [
					'loan_id' => $value['id'],
					'payroll_id' => $id,
					'payment_date' => date('Y-m-d'),
					'payment_amount' => $value['loan_minimum_pay']
				];

				$this->db->insert('payroll_particulars', $payroll_data);
				$this->db->insert('payment_terms', $payment_data);
			}
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function create_manual_payslip($payroll_data, $payroll_particulars_data, $payments = false)
	{
		if($this->db->insert($this->table, $payroll_data)){

			$id = $this->db->insert_id();
			foreach ($payroll_particulars_data as $key => $value) {
				$payroll_particulars_data[$key]['payroll_id'] = $id;
			}

			$this->load->model('Loan_model', 'loan');
			$this->loan->insert_loan_payment($id, $payroll_data['employee_id'], $payments);

			if(!empty($payroll_particulars_data) && $this->db->insert_batch('payroll_particulars', $payroll_particulars_data))
				return true;
			else if(empty($payroll_particulars_data))
				return true;

			return false;
		}

		return false;
	}

	public function check($employee_id, $from, $to)
	{
		$this->db->select('id')->from('payroll')->where([
			'employee_id' => $employee_id,
			'start_date' => $from,
			'end_date' => $to,
		]);

		$result = $this->db->get()->row_array();
		if($result){
			$this->db->where('id', $result['id']);
			$this->db->delete('payroll');
		}
		return;
	}

	public function get_by_batch_to_print($batch_id)
	{
		$this->db->select("p.*, e.id AS emp_id, CONCAT(e.firstname, ' ', e.middleinitial, '. ', e.lastname) AS employee_name", FALSE);
		$this->db->where(['p.batch_id' => $batch_id, 'p.approval_status' => 1]);
		$this->db->join('payroll as p', 'e.id = p.employee_id');
		$payroll_result = $this->db->get('employees as e')->result_array();

		$data = [];
		foreach ($payroll_result as $key => $value) {
			$this->db->where('id', $value['id']);
			$this->db->update($this->table, ['is_printed' => TRUE]);

			$additionals = [];
			$deductions = [];
			$total_particulars = 0;
			
			$this->load->model('Loan_model', 'loan');
			$loan_payments = $this->loan->get_payroll_loans($value['id']);
			foreach ($loan_payments as $loan_key => $loan_value) {
				$total_particulars -= $loan_value['payment_amount'];
			}

			$this->db->select('pm.name, pm.type, pp.amount, pm.particular_type');
			$this->db->join('pay_modifiers as pm', 'pm.id = pp.particulars_id');
			$particulars = $this->db->get_where('payroll_particulars as pp', ['pp.payroll_id' => $value['id']])->result_array();
			foreach ($particulars as $particulars_key => $particulars_value) {
				if($particulars_value['type']=='a'){
					$additionals[] = $particulars_value;

					if($particulars_value['particular_type']=='d')
						$total_particulars += $particulars_value['amount'] * $value['days_rendered'];
					else
						$total_particulars += $particulars_value['amount'];
				}
				else{
					$deductions[] = $particulars_value;
					$total_particulars -= $particulars_value['amount'];
				}
			}

			$net_pay = ($value['current_daily_wage'] * $value['days_rendered']) + ($value['overtime_pay'] * $value['overtime_hours_rendered']) - ($value['current_late_penalty'] * $value['late_minutes']) + $total_particulars;

			$start_date = date_format(date_create($value['start_date']), 'M d, Y');
			$end_date = date_format(date_create($value['end_date']), 'M d, Y');
			$data[] = [
				'date' => "{$start_date} - {$end_date}",
				'employee_name' => $value['employee_name'],
				'regular_wage' => $value['current_daily_wage'] * $value['days_rendered'],
				'overtime_pay' => $value['overtime_pay'] * $value['overtime_hours_rendered'],
				'late_pay' => $value['current_late_penalty'] * $value['late_minutes'],
				'particulars' => $particulars,
				'loan_payments' => $loan_payments,
				'additionals' => $additionals,
				'deductions' => $deductions,
				'net_pay' => $net_pay,
				'days_rendered' => $value['days_rendered']
			];
		}

		return $data;
	}

	public function get_batches()
	{
		$this->db->select('DISTINCT(batch_id), start_date, end_date');
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get($this->table)->result_array();
	}

	public function all($employee_id = FALSE, $condition = FALSE)
	{
		$this->db->select('p.start_date, p.batch_id, p.approval_status, p.end_date, p.id, e.firstname, e.middleinitial, e.lastname')->from('payroll AS p')->join('employees AS e', 'p.employee_id = e.id');
		if($employee_id){
			$this->db->where('employee_id', $employee_id);
		}
		if($condition)
			$this->db->where($condition);
		$this->db->order_by('end_date', 'DESC');
		$a = $this->db->get()->result_array();
		//print_r($this->db->last_query());
		return $a;
	}

	public function adjust($id, $amount)
	{
		return $this->db->update('payroll', ['wage_adjustment' => $amount], ['id' => $id]);
	}

	public function get_by_employee($id, $batch_id)
	{
		$this->load->model(['Loan_model' => 'loan']);
		$data = $this->db->get_where('payroll', ['id' => $id, 'batch_id' => $batch_id])->row_array();
		if($data){
			$data['particulars'] = ['deductions' => [], 'additionals' => []];
			$this->db->select('p.id, p.name, p.type, p.particular_type, pp.amount, pp.units');
			$this->db->from('payroll_particulars AS pp');
			$this->db->join('pay_modifiers AS p', 'p.id = pp.particulars_id');
			$this->db->where('payroll_id', $data['id']);
			$particulars = $this->db->get()->result_array();
			foreach($particulars AS $p){
				if($p['type'] === 'a'){
					$data['particulars']['additionals'][] = $p;
				}else{
					$data['particulars']['deductions'][] = $p;
				}
			}
		}
		return $data;
		
	}

	public function insert_salary_particular(/*$salary_particular, */$payroll_particular)
	{
		/*$salary_flag = 0;
		if($this->db->insert_batch('salary_particulars', $salary_particular))
			$salary_flag = 1;*/

		$pp_flag = 0;
		if($this->db->insert_batch('payroll_particulars', $payroll_particular))
			$pp_flag = 1;

		return (/*$salary_flag && */$pp_flag)?TRUE:FALSE;
	}

	public function update_payroll_batch_normal($data, $type)
	{
		if($type=="BATCH")
			return $this->db->update_batch('payroll', $data, 'id');
		else if($type=="NORMAL"){
			$this->db->where('id', $data['id']);
			unset($data['id']);
			return $this->db->update('payroll', $data);
		}
	}

	public function update_payroll($payroll_id, $payroll_update, $payroll_particulars_update)
	{
		$payrol_flag = 0;
		$this->db->where('id', $payroll_id);
		if($this->db->update('payroll', $payroll_update))
			$payrol_flag = 1;

		if($payroll_particulars_update){
			$this->db->where('payroll_id', $payroll_id);
			$this->db->update_batch('payroll_particulars', $payroll_particulars_update, 'particulars_id');
		}

		

		return ($payrol_flag)?TRUE:FALSE;
	}
}