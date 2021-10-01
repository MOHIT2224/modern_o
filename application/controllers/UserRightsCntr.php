<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class UserRightsCntr extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('ModelUserRights');
		        $_POST = json_decode(file_get_contents('php://input'), true);
		        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
	
		}

        function getLoginUserRole(){
            $data=$this->ModelUserRights->getLoginUserRole();
            $responce =  array('error' => 0 ,'message' => 'Role List...','data'=>$data);
            $this->output->set_content_type('application/json')->set_output(json_encode($responce));
        }

		function getHelperRightsById($id){
            $data=$this->ModelUserRights->getHelperRightsById($id);
            $responce =  array('error' => 0 ,'message' => 'Role List...','data'=>$data);
            $this->output->set_content_type('application/json')->set_output(json_encode($responce));
        }
        function roleList(){

            $userRole = $this->ModelUserRights->hasRole('ViewRole');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $data=$this->ModelUserRights->roleList();
            $responce =  array('error' => 0 ,'message' => 'Role List...','data'=>$data);
            $this->output->set_content_type('application/json')->set_output(json_encode($responce));
        }

		function listHelperRights(){
            $data=$this->ModelUserRights->listHelperRights();
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
		function saveHelperRights(){
            if($_POST['ID'] > 0){
                $userRole = $this->ModelUserRights->hasRole('ManageRole');
            }else{
                $userRole = $this->ModelUserRights->hasRole('AddRole');
            }

            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $data=$this->ModelUserRights->saveHelperRights();
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function updateHelperRights(){
            $data=$this->ModelUserRights->updateHelperRights($_POST['id']);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		
	}