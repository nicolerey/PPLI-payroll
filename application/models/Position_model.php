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
		$result = $this->db->get_where($this->table, ['id' => $id])->row_array();

		$result['particulars'] = $this->db->select('sp.amount, pm.type, pm.name, pm.particular_type, pm.id, sp.particulars_id')
            ->from('salary_particulars AS sp')
            ->join('pay_modifiers AS pm', 'pm.id = sp.particulars_id')
            ->where( ['position_id' => $id])
            ->get()
            ->result_array();

        return $result;
	}

	public function create($data)
	{
		$particulars = $data['particulars'];
		unset($data['particulars']);

		$this->db->insert($this->table, $data);

		$id = $this->db->insert_id();

		if(!empty($particulars)){
            foreach($particulars AS &$row){
                $row['position_id'] = $id;
            }

            $this->db->insert_batch('salary_particulars', $particulars);
        }

        return TRUE;
	}

	public function update($id, $data)
	{
		$particulars = $data['particulars'];
		unset($data['particulars']);

		if($this->db->update($this->table, $data, ['id' => $id])){
			$this->db->delete('salary_particulars', ['position_id' => $id]);
			if(!empty($particulars)){
	            foreach($particulars AS &$row){
	                $row['position_id'] = $id;
	            }

	            return $this->db->insert_batch('salary_particulars', $particulars);
	        }

	        return TRUE;
	    }
	    else
        	return FALSE;
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