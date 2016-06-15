<?php

class position_model extends CI_Model
{

	protected $table = 'positions';

	public function __construct()
	{
		parent::__construct();
	}

	public function all()
	{
		$this->db->order_by('name', 'ASC');
		return $this->db->get($this->table)->result_array();
	}

	public function get($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row_array();
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data) ? $this->db->insert_id() : FALSE;
	}

	public function update($id, $data)
	{
		return $this->db->update($this->table, $data, ['id' => $id]);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, ['id' => $id]);
	}

	public function exists($id)
	{
		return $this->db->select('id')->from($this->table)->where('id', $id)->get()->num_rows() > 0;
	}

	public function has_unique_name($name, $id = FALSE)
	{
		if($id !== FALSE){
			$this->db->where('id !=', $id);
		}
		return $this->db->select('name')->from($this->table)->where('name', $name)->get()->num_rows() === 0;
	}


}