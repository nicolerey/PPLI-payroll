<?php

class Loan_model extends CI_Model
{

	protected $table = 'loans';

	public function all($emp_id = FALSE, $loan_id = FALSE, $start_date = FALSE, $end_date = FALSE, $payment_start = FALSE, $payment_end = FALSE)
	{
		if($emp_id)
			$this->db->where('employee_id', $emp_id);
		if($loan_id)
			$this->db->where('id', $loan_id);
		if($start_date)
			$this->db->where('loan_date>=', $start_date);
		if($end_date)
			$this->db->where('loan_date<=', $end_date);

		$this->db->from($this->table);
		$this->db->order_by('loan_date', 'desc');
		$loans = $this->db->get()->result_array();

		foreach ($loans as $key=>$value) {
			if($payment_start)
				$this->db->where('payment_date>=', $payment_start);
			if($payment_end)
				$this->db->where('payment_date<=', $payment_end);
			$this->db->where('loan_id', $value['id']);
			$this->db->from('payment_terms');
			$loans[$key]['payment_terms'] = $this->db->get()->result_array();
		}

		return $loans;
	}

	public function get_payroll_loans($payroll_id)
	{
		$this->db->select('pt.*, l.loan_name, l.loan_minimum_pay');
		$this->db->join('loans as l', 'l.id=pt.loan_id');
		$this->db->where('pt.payroll_id', $payroll_id);
		return $this->db->get('payment_terms as pt')->result_array();
	}

	public function update_payment_batch($data)
	{
		return $this->db->update_batch('payment_terms', $data, 'id');
	}

	public function create($data)
	{
		$loan_table_data = [
			'loan_name' => $data['loan_name'],
			'loan_date' => date_format(date_create($data['loan_date']), 'Y-m-d H:i:s'),
			'employee_id' => $data['employee_number'],
			'loan_amount' => $data['loan_amount'],
			'loan_minimum_pay' => $data['loan_minimum_pay']
		];
		return $this->db->insert('loans', $loan_table_data);
	}

	public function update_loan($data)
	{
		$update_flag = 0;
		$loan_table_data = [
			'loan_name' => $data['loan_name'],
			'loan_date' => date_format(date_create($data['loan_date']), 'Y-m-d H:i:s'),
			'employee_id' => $data['employee_number'],
			'loan_amount' => $data['loan_amount'],
			'loan_minimum_pay' => $data['loan_minimum_pay']
		];
		$this->db->where('id', $data['id']);
		return $this->db->update($this->table, $loan_table_data);
	}

	public function delete($id)
	{
		return $this->db->delete('loans', ['id'=>$id]);
	}

	public function exists($id)
	{
		return $this->db->get_where('loans', ['id'=>$id])->num_rows();
	}
}