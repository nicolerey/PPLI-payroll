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

	public function get_payments($id)
	{
		return $this->db->get_where('payment_terms', ['loan_id' => $id])->result_array();
	}

	public function insert_loan_payment($payroll_id, $employee_number, $payments)
	{
		$loans = $this->all($employee_number);
		foreach ($loans as $key => $value) {
			$payroll_data = [
				'payroll_id' => $payroll_id,
				'loan_id' => $value['id'],
				'amount' => 0.00
			];
			$payment_data = [
				'loan_id' => $value['id'],
				'payroll_id' => $payroll_id,
				'payment_date' => date('Y-m-d'),
				'payment_amount' => $payments[$key]
			];

			$this->db->insert('payroll_particulars', $payroll_data);
			$this->db->insert('payment_terms', $payment_data);
		}

		return;
	}

	public function get_payroll_loans($payroll_id = false, $payment_id = false, $loan_id = false, $type = 'MUL', $emp_id = false)
	{
		$this->db->select('pt.*, l.loan_name, l.loan_minimum_pay, l.loan_amount, l.id as loan_id');
		$this->db->join('loans as l', 'l.id=pt.loan_id');

		if($payroll_id)
			$this->db->where('pt.payroll_id', $payroll_id);
		if($payment_id)
			$this->db->where('pt.id', $payment_id);
		if($loan_id)
			$this->db->where('loan_id', $loan_id);

		if($type=='MUL')
			return $this->db->get('payment_terms as pt')->result_array();
		else{
			$this->db->select('SUM(pt.payment_amount) as p_total');
			return $this->db->get('payment_terms as pt')->row_array();
		}
	}

	public function get_loan_and_payments($loan_id)
	{
		$loan = $this->db->get_where('loans', ['id' => $loan_id])->row_array();
		$loan['payments'] = $this->db->get_where('payment_terms', ['loan_id' => $loan_id])->result_array();

		return $loan;
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
			'loan_amount' => floatval(str_replace(',', '', $data['loan_amount'])),
			'loan_minimum_pay' => floatval(str_replace(',', '', $data['loan_minimum_pay']))
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
			'loan_amount' => floatval(str_replace(',', '', $data['loan_amount'])),
			'loan_minimum_pay' => floatval(str_replace(',', '', $data['loan_minimum_pay']))
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