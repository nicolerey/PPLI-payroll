<?php

class Bonus_model extends CI_Model
{

	protected $table = 'bonus';

	public function __construct()
	{
		parent::__construct();
	}

	public function all($condition = FALSE, $select = FALSE, $start_date = FALSE, $end_date = FALSE)
	{
		if($condition)
			$this->db->where($condition);
		if($select)
			$this->db->select($select);
		if($start_date)
			$this->db->where('start_date >=', $start_date);
		if($end_date)
			$this->db->where('end_date >=', $end_date);

		return $this->db->get($this->table)->result_array();
	}

	public function all_table($table, $condition = FALSE, $select = FALSE, $start_date = FALSE, $end_date = FALSE)
	{
		if($condition)
			$this->db->where($condition);
		if($select)
			$this->db->select($select);
		if($start_date)
			$this->db->where('start_date >=', $start_date);
		if($end_date)
			$this->db->where('end_date >=', $end_date);

		return $this->db->get($table)->result_array();
	}

	public function get($condition = FALSE, $select = FALSE)
	{
		if($select)
			$this->db->select($select);
		if($condition)
			$this->db->where($condition);
		return $this->db->get($this->table)->row_array();
	}

	public function get_table($table, $condition = FALSE, $select = FALSE)
	{
		if($select)
			$this->db->select($select);
		if($condition)
			$this->db->where($condition);
		return $this->db->get($table)->row_array();
	}

	public function insert($data, $type = 'NORMAL')
	{
		if($type=='NORMAL')
			$this->db->insert($this->table, $data);
		else if($type=='BATCH')
			$this->db->insert_batch($this->table, $data);

		return $this->db->insert_id();
	}

	public function insert_to_table($table, $data, $type = 'NORMAL')
	{
		if($type=='NORMAL')
			return $this->db->insert($table, $data);
		else if($type=='BATCH')
			return $this->db->insert_batch($table, $data);
	}

	public function update($data, $type = 'NORMAL')
	{
		if($type=='NORMAL'){
			$this->db->where($data['condition']);
			unset($data['condition']);
			return $this->db->update($this->table, $data);
		}
		else if($type=='BATCH'){
			$column = $data['column'];
			unset($data['column']);
			return $this->db->update_batch($this->table, $data, $column);
		}
	}

	public function delete($condition)
	{
		return $this->db->delete($this->table, $condition);
	}

	public function delete_table($table, $condition)
	{
		return $this->db->delete($table, $condition);
	}
}