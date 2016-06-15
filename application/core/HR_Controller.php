<?php

class HR_Controller extends CI_Controller
{

	private $main_view = 'app';
	private $main_view_data = [ 'page_scripts' => [], 'plugin_scripts' => [] ];

	public function __construct()
	{
		parent::__construct();
		// ensure there is a user session
		if(!$this->session->userdata('id')){
			redirect('login');
		}
		$this->main_view_data['tab_title'] = isset($this->tab_title) ? $this->tab_title. ' |' : '';
	}

	public function generate_page($view, $data = FALSE)
	{
		$this->main_view_data['content'] = $this->load->view($view, $data, TRUE);
		$this->main_view_data['active_nav'] = $this->active_nav;
		$this->main_view_data['active_subnav'] = isset($this->active_subnav) ? $this->active_subnav : '';
		$this->load->view($this->main_view, $this->main_view_data);
	}

	public function import_page_script($script)
    {
        $this->main_view_data['page_scripts'] = array_merge($this->main_view_data['page_scripts'], (array)$script);
        return $this;
    }

    public function import_plugin_script($script)
    {
        $this->main_view_data['plugin_scripts'] = array_merge($this->main_view_data['plugin_scripts'], (array)$script);
        return $this;
    }

    public function json_response($response)
    {
        $this->output->set_content_type('json')->set_output(json_encode($response));
    }	
}