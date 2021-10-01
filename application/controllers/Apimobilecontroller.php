<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apimobilecontroller extends CI_Controller {
	var $globalUserId;
	var $globalUserType;

	function __construct() {

		header('Access-Control-Allow-Origin: *');

		parent::__construct();

		$_POST = json_decode(file_get_contents('php://input'), true);

		$node1=$this->session->userdata('user_id');

		$this->load->model('Modelchkuser');
		       // if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		$currentMethod = $this->router->method;
		$allowAuth = array('chkAppVersion');
		if(!in_array($currentMethod, $allowAuth)){
			$this->checkAuthentication();
		}  
	}

	function chkAppVersion(){

		$this->load->model('Modelapkversion');
		$data = $this->Modelapkversion->findLatestVersion();

		if (!empty($data)) {
			$data['version_code'] = $data['version_code'];
			$data['version_name'] = $data['version_name'];
			$data['apk_download_link'] = $data['apk_download_link'];
			$data['error'] = 0;
			$data['message'] = '';
		}else {
			$data['error'] = 1;
			$data['message'] = 'No apk version found';      

		}

		return $this->output->set_content_type('application/json')->set_output(json_encode($data));

	}
}