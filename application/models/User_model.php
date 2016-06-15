<?php

class User_model extends CI_Model
{

    protected $table = 'employees';

    public function exists($email)
    {
       return $this->db->select('id')
            ->from($this->table)
            ->where('id', $email)
            ->limit(1)
            ->get()->num_rows() > 0;
    }

    public function authenticate($email, $password)
    {
        /*$this->db->select('emp.email_address AS id_number, emp.id, emp.firstname, emp.middleinitial, emp.lastname, emp.id_number, emp.is_locked, emp.gender, emp.account_type AS type');
        $this->db->from($this->table.' AS emp');
        $this->db->join('employee_positions AS emppos', 'emppos.employee_id = emp.id AND emppos.to IS NULL', 'left', FALSE);
        $this->db->join('positions AS p', 'p.id = emppos.position_id');*/
        $this->db->where(['id' => $email, 'password' => $password]);
        return $this->db->get($this->table)->row_array();
    }

    public function get($employee_id)
    {
        $this->db->select('emp.account_type AS type, emp.is_locked')
            ->from($this->table.' AS emp')
            ->join('employee_positions AS emppos', 'emppos.employee_id = emp.id AND emppos.to IS NULL', 'left', FALSE)
            ->join('positions AS p', 'p.id = emppos.position_id')
            ->where('emp.id', $employee_id);
        return $this->db->get()->row_array();
    }

    public function create($data)
    {
        return $this->db->replace($this->table, $data);
    }

    public function update_password($id, $password)
    {
        return $this->db->update($this->table, ['password' => md5($password)], ['id' => $id]);
    }
}
