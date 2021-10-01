<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class UserRightsCntr extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('ModelUserRights');
		        $_POST = json_decode(file_get_contents('php://input'), true);
		        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
	
		}

		function Save_userRights(){

			
			$condition=$this->ModelUserRights->Save_userRights();
			
			/*if ($condition==true) {
				$data['betUserData']=$this->ModelUserRights->getBetEntry($_POST['MarketId'],$_POST['UserTypeId'],$userId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
            	
			}else{
				$data['error'] = 1;
				$data['message'] = 'Invalid User Name Or Password';
				echo json_encode($data);
			}*/
		}
		function SavePermition(){
			$condition=$this->ModelUserRights->SavePermition();
			
			/*if ($condition==true) {
				$data['betUserData']=$this->ModelUserRights->getBetEntry($_POST['MarketId'],$_POST['UserTypeId'],$userId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
            	
			}else{
				$data['error'] = 1;
				$data['message'] = 'Invalid User Name Or Password';
				echo json_encode($data);
			}*/
		}
		
	}