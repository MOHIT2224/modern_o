<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting("ERROR");

	class GeneralSettingsCntrl extends CI_Controller {
		 	function __construct() {
		        parent::__construct();
				$_POST = json_decode(file_get_contents('php://input'), true);
		        $this->load->model('Model_general_setting');
		        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		    }
		 

		function updateGeneralSettings(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$key = $_POST['key'];
			$value = $_POST['value']=='' ? null : $_POST['value'];
			$condition = $this->Model_general_setting->update($key,$value);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Updated Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Updated Not Successfully Saved ...'));
			}	
		
		}

        function getGeneralSettings($key=null){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $condition = $this->Model_general_setting->get($key);
            if (!empty($condition)) {
                echo json_encode(array('error' => 0 ,'data'=>$condition,'message' => 'Data Get Successfully...'));
            }else{
                echo json_encode(array('error' => 1 ,'data'=>[],'message' => 'NO Data Found'));
            }

        }


	}