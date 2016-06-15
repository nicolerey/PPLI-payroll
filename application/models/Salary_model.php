<?php

class Salary_model extends CI_Model
{

	protected $table = 'salaries';
	public function create($data)
	{
		$this->db->trans_start();

		$this->db->insert($this->table, $data['salary']);
		$id = $this->db->insert_id();

		if(!empty($data['particulars'])){
			foreach($data['particulars'] AS &$item){
				$item['salary_id'] = $id;	
			}
			$this->db->insert_batch('salary_particulars', $data['particulars']);
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function update($id, $data)
	{
		$this->db->trans_start();

		$this->db->update($this->table, $data['salary'], ['id' => $id]);

		$this->db->delete('salary_particulars', ['salary_id' => $id]);
		if(!empty($data['particulars'])){
			foreach($data['particulars'] AS &$item){
				$item['salary_id'] = $id;	
			}
			$this->db->insert_batch('salary_particulars', $data['particulars']);
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function get($id = FALSE, $position_id = FALSE)
	{
		if($id){
			$param = ['id' => $id];
		}else{
			$param = ['position_id' => $position_id];
		}
		$data = $this->db->get_where($this->table, $param)->row_array();

		$this->db->select('p.id, p.name, sp.amount, p.type, sp.particulars_id, sp.type AS sp_type')->from('salary_particulars AS sp, pay_modifiers AS p');
		$this->db->where('sp.particulars_id = p.id', FALSE, FALSE)->where('salary_id', $data['id']);
		$data['particulars'] = $this->db->get()->result_array();
		
		return $data;
	}

	public function all()
	{
		$this->db->select('s.id, p.name')->from($this->table.' AS s');
		$this->db->join('positions AS p', 'p.id = s.position_id');
		return $this->db->get()->result_array();
	}

}