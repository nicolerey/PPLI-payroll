<?php 

class Test extends CI_Controller
{
	public function index()
	{
		echo date_diff(date_create(NULL), date_create(NULL))->format('%d')+1;
	}
}