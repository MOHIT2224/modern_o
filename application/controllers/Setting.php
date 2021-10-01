<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Setting extends CI_Controller {

		function __construct() {
		        parent::__construct();
		        $this->load->model('Modeltblconfig');
		        $_POST = json_decode(file_get_contents('php://input'), true);
		    	if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}	       
		}
		
		function index(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data = $this->Modeltblconfig->find();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

}