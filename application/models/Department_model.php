<?php

class Department_model extends CI_Model
{

	protected $table = 'departments';

	public function __construct()
	{
		parent::__construct();
	}

	public function all()
	{
		$this->db->select('dep.id, dep.id_number, dep.name, CONCAT(emp.lastname, ", ", emp.firstname, " ", emp.middleinitial) AS supervisor', FALSE);
		$this->db->from($this->table.' AS dep');
		$this->db->join('department_supervisors AS depsup', 'depsup.department_id = dep.id', 'left');
		$this->db->join('employees AS emp', 'emp.id = depsup.employee_id', 'left');
		$this->db->order_by('dep.name', 'ASC');
		return $this->db->get()->result_array();
	}

	public function get($id)
	{
		$this->db->select('dep.id, dep.id_number, dep.name, depsup.employee_id');
		$this->db->from($this->table.' AS dep');
		$this->db->join('department_supervisors AS depsup', 'depsup.department_id = dep.id', 'left');
		$this->db->where('dep.id', $id)->where('depsup.to IS NULL', FALSE, FALSE);;
		return $this->db->get()->row_array();
	}

	public function create($data)
	{
		$this->db->trans_start();

		$this->db->insert($this->table, $data['department']);
		$id = $this->db->insert_id();

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function update($id, $data)
	{
		$this->db->trans_start();

		$this->db->update($this->table, $data['department'], ['id' => $id]);
		
		// select current division of the department
		/*$this->db->select('division_id')->where('department_id', $id)->where('`to` IS NULL', FALSE, FALSE);
		$result = $this->db->get('division_departments')->row_array();
		$current_division = $result['division_id'];

		// if department has been changed, apply end date and insert new division
		if($current_division != $data['division_id']){
			$this->db->where('department_id', $id)->where('`to` IS NULL');
			$this->db->update('division_departments', ['to' => date('Y-m-d')]);

			$division = ['department_id' => $id, 'division_id' => $data['division_id'], 'from' => date('Y-m-d')];
			$this->db->insert('division_departments', $division);
		}*/

		
		// select current supervisor
		$this->db->select('employee_id')->from('department_supervisors');
		$this->db->where('department_id', $id)->where('`to` IS NULL');
		$result = $this->db->get()->row_array();
		if(!$result){
			//no current supervisor, insert new supervisor
			$this->db->insert('department_supervisors', [
				'department_id' => $id, 
				'employee_id' => $data['employee_id'],
				'from' => date('Y-m-d')
			]);
		}else if($result && $result['employee_id'] !== $data['employee_id']){
			//end term of current supervisor
			$this->db->where('department_id', $id)->where('`to` IS NULL');
			$this->db->update('department_supervisors', ['to' => date('Y-m-d')]);

			//insert new supervisor
			$this->db->insert('department_supervisors', [
				'department_id' => $id, 
				'employee_id' => $data['employee_id'],
				'from' => date('Y-m-d')
			]);
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
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

	public function get_employees($id)
	{
		$this->db->select('CONCAT(emp.lastname, ", ", emp.firstname, " ", emp.middleinitial) AS fullname, emp.id', FALSE);
		$this->db->from('employee_departments AS empdep');
		$this->db->join('employees AS emp', 'emp.id = empdep.employee_id');
		$this->db->where('empdep.to IS NULL', FALSE, FALSE)->where('empdep.department_id', $id);
		$this->db->order_by('fullname', 'ASC');
		return $this->db->get()->result_array();
	}

	public function has_employee($id, $employee_id)
	{
		$this->db->select('empdep.id')->from('employee_departments AS empdep');
		$this->db->where(['department_id' => $id, 'employee_id' => $employee_id]);
		$this->db->where('empdep.to IS NULL', FALSE, FALSE);
		return $this->db->get()->num_rows() > 0;
	}

	public function get_supervisor()
	{
		
	}


}