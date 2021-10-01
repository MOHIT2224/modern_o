<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Adminlogincontroller extends CI_Controller {
		function index(){
			$this->load->view('admin_pages/adminLogin');
		}
		function chkAuth(){
			$this->load->model('Modelchkauth');
			//echo $this->Modelchkauth->chkAuthName();die();
			if ($user_data=$this->Modelchkauth->chkAuthName()) {
				
            	$data['error'] = 0;
				$data['message'] = 'success';
				echo json_encode($data);
			}else{
				$data['error'] = 1;
				$data['message'] = 'Invalid User Name Or Password';
				echo json_encode($data);
			}
		}
		function dashboard(){
			$this->load->view('admin_pages/dashboard');
		}
		function userLogout(){
			 $this->session->sess_destroy();
			$this->load->view('userDashboard');
		}

	}