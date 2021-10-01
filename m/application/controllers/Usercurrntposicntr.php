<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Usercurrntposicntr extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('Modelusercurrntpos');
		        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		}
		function getUserPosition($userId,$userType,$matchId,$marketId){  
				$data['userPosition']=$this->Modelusercurrntpos->getUserPosition($userId,$userType,$matchId,$marketId);
				$data['userOwnPosition']=$this->Modelusercurrntpos->getOwnPosition($userId,$userType,$matchId,$marketId);				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}