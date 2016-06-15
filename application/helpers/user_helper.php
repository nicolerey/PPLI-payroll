<?php

if(!function_exists('user_full_name')){
	function user_full_name()
	{
		$CI =& get_instance();
		return $CI->session->userdata('firstname').' '.$CI->session->userdata('middlename').' '.$CI->session->userdata('lastname');
	}
}

if(!function_exists('user_id_number')){
	function user_id_number()
	{
		$CI =& get_instance();
		return $CI->session->userdata('id_number');
	}
}

if(!function_exists('user_id')){
	function user_id()
	{
		$CI =& get_instance();
		return $CI->session->userdata('id');
	}
}

if(!function_exists('role_is')){
	function role_is($role)
	{
		$CI =& get_instance();
		return in_array($CI->session->userdata('type'), [$role, 'su']);
	}	
}


if(!function_exists('pending_count')){
	function pending_count()
	{
		$CI =& get_instance();
		$CI->load->model(['Request_model' => 'request', 'Employee_model' => 'employee']);
		$user_department = $CI->employee->get_department(user_id());
		return count($CI->request->get_by_department($user_department, 'p'));
	}	
}



if(!function_exists('gender')){
	function gender($gender)
	{
		switch($gender){
			case 'M': return 'Male';
			case 'F': return 'Female';
			default: return NULL;
		}
	}	
}

if(!function_exists('civil_status')){
	function civil_status($civil_status)
	{
		switch($civil_status){
			case 'sg': return 'Single';
			case 'm': return 'Married';
			case 'sp': return 'Separated';
			case 'd': return 'Divorced';
			case 'w': return 'Widowed';
			default: return NULL;
		}
	}	
}


if(!function_exists('account_type')){
	function account_type($account_type)
	{
		switch($account_type){
			case 're': return 'Regular Employee';
			case 'fl': return 'Flexible Employee';
			default: return NULL;
		}
	}	
}


if(!function_exists('display_time')){
	function display_time($arr, $key)
	{
		return isset($arr[$key]) ? date_create($arr[$key])->format('h:i A') : '';	
	}	
}

if(!function_exists('tax')){
	function tax($salary_value)
	{
		if($salary_value < 417){
			return ['raw' => 0, 'over' => 0];
		}
		if($salary_value >= 417 && $salary_value <= 1250){
			return ['raw' => 20.83, 'over' => 0.1];
		}
		if($salary_value > 1250 && $salary_value <= 2917){
			return ['raw' => 104.17, 'over' => 0.15];
		}
		if($salary_value > 2917 && $salary_value <= 5833){
			return ['raw' => 354.17, 'over' => 0.2];
		}
		if($salary_value > 5833 && $salary_value <= 10417){
			return ['raw' => 937.5, 'over' => 0.25];
		}
		if($salary_value > 10417 && $salary_value <= 20833){
			return ['raw' => 2083.33, 'over' =>  0.3];
		}
		if($salary_value > 20833){
			return ['raw' => 5208.33, 'over' => 0.32];
		}
	}	
}




