<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Createdealercontroller extends CI_Controller {
		 	function __construct() {
		        parent::__construct();
		        $this->load->model('Modelcreatemaster');
		        $this->load->model('Chip_model');
		        $_POST = json_decode(file_get_contents('php://input'), true);
		    	if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}	       
		    }
		function index(){
			$this->load->model('Modelcreatemaster');
			$data['id']=$this->Modelcreatemaster->getFormData();
			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
        	
		}
		
		function SaveSubAdmin(){
			$this->load->model('Modelcreatemaster');
			
			//$query=$this->Modelcreatemaster->SaveSubAdmin();
			
			if ($query=$this->Modelcreatemaster->SaveSubAdmin()) {

				$data['subadmin']=$this->Modelcreatemaster->getSubadmin();
				$data["message"]=array('error' => 0 ,'message' => 'Subadmin Register Successfully...');			
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
				
				
				
			}else{

				$data['subadmin']=$this->Modelcreatemaster->getSubadmin();
				$data["message"]=array('error' => 1 ,'message' => 'Subadmin Not Register...');			
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
			}
		}
		function getSubadmin(){
					$data['subadmin']=$this->Modelcreatemaster->getSubadmin();
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
        	
		}
		function updateAccount(){
				$this->load->model('Modelcreatemaster');
				$query=$this->Modelcreatemaster->updateAccount();
				if ($query) {
					echo json_encode(array('error' => 0 ,'message' => 'Records Updated Successfully...'));
				}else{
					echo json_encode(array('error' => 1 ,'message' => 'Records Updated Successfully...'));
				}
		}
		function changePassword(){
			/*$_POST = json_decode(file_get_contents('php://input'), true);*/
			if ($_POST['newpassword']==$_POST['Renewpassword']) {
				$query=$this->Modelcreatemaster->changePassword();
				//print_r($query);die();
				if ($query) {
					$passwordData = array('mstrpassword' => sha1($_POST['newpassword']));
					$this->session->set_userdata('mstrpassword', $passwordData['mstrpassword']); 
					echo json_encode(array('error' => 0 ,'message' => 'Password Change Successfully...','data'=>$passwordData));
				}else{
					echo json_encode(array('error' => 1 ,'message' => 'Invalid Password ...'));
				}
				
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'New Password and Retype Password not Match...'));
			}
			

		}
		function get_userList($userType){

			$data['userList']=$this->Modelcreatemaster->get_userList($userType);
			$data['menuList']=$this->Modelcreatemaster->get_menuList();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}
		function getFancySomOfBet($fancyId){
			//$this->load->model('Chip_model');
			$data['sumOfBetFancy']=$this->Chip_model->sumOfBetFancy($fancyId);			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetAllSessFancyBet($fancyId){
			//$this->load->model('Chip_model');
			$data['GetSesFancyUserLst']=$this->Chip_model->GetAllFancyBet($fancyId);			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function UserChipSetting($user_id){
			
			$data['getChipsetting']=$this->Chip_model->UserChipSetting($user_id);			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function updateUserChipSetting(){

				$query=$this->Chip_model->updateUserChipSetting();
				//print_r($query[0]['resultV']);
				if ($query[0]['resultV']==1) {
					
					$data['status']=array('error' => $query[0]['resultV'] ,'message' => ''.$query[0]['retMess']);
					$data['getChipsetting']=$this->Chip_model->UserChipSetting($_POST['UserID']);				
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
					//echo json_encode(array('error' => $query[0]['resultV'] ,'message' => ''.$query[0]['retMess']));
				}else{
					$data['status']=array('error' => $query[0]['resultV'] ,'message' => ''.$query[0]['retMess']);
					$data['getChipsetting']=$this->Chip_model->UserChipSetting($_POST['UserID']);				
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
					//echo json_encode(array('error' => $query[0]['resultV'] ,'message' => ''.$query[0]['retMess']));
				}
		}
		function get_Master_AltSubAdmin($userType,$adminId){
			$data['userList']=$this->Modelcreatemaster->get_Master_AltSubAdmin($userType,$adminId);
			$data['menuList']=$this->Modelcreatemaster->get_Helper_MenuList($adminId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}	
		function get_HelperRights($adminId){
			$data['menuList']=$this->Modelcreatemaster->get_Helper_MenuList($adminId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

        public function do_upload(){
                $image=$_FILES['file']['name'];
                $config = array(
                    'upload_path' =>"app/images/slider/",
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE,
                    'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    /*'max_height' => "768",
                    'max_width' => "1024"*/
                );
                //print_r($config);
                $this->load->library('upload', $config);
                if($this->upload->do_upload('file'))
                {
                    $query=$this->Modelcreatemaster->save_path($image);
                    if ($query){
                        $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
                        $data['success'] = array('msg' => 'Image Uploaded successfully...');
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                    else{
                        $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
                        $data['success'] = array('msg' => 'Database Error...');
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }

                }
                else
                {
                    $data['success'] = array('msg' => $this->upload->display_errors());
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
        }
        function get_sliderImage(){
            $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        function update_slider($id,$status){
            $query=$this->Modelcreatemaster->update_slider($id,$status);
            if ($query){
                $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
                $data['success'] = array('msg' => 'Image Updated successfully...');
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            else{
                $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
                $data['success'] = array('msg' => 'Image Not Updated...');
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        function deleteImage($id,$name){
            //echo $path='./app/images/slider/'.$name;

            unlink('app/images/slider/'.$name);
            //die();
            $query=$this->Modelcreatemaster->deleteImage($id);
            if ($query){
                $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
                $data['success'] = array('msg' => 'Image Deleted successfully...');
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
            else{
                $data['sliderLst']=$this->Modelcreatemaster->get_sliderImage();
                $data['success'] = array('msg' => 'Image Not Deleted...');
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
        function sp_getMTreeTemp($userId){
			$data['Mtree']=$this->Modelcreatemaster->sp_getMTreeTemp($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function getBetDelay($userId){
			$data['BetDelay']=$this->Modelcreatemaster->getBetDelay($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function getFancyLength(){
	       //$this->load->model('Modelchkuser');
	        $data['FancyNum']=$this->Chip_model->getFancyLength();
	        $this->output->set_content_type('application/json')->set_output(json_encode($data));  
	    }
	    function getFancyInIcon($FancyId){
	    	$data['GetFancyIcon']=$this->Chip_model->GetFancyIcon($FancyId);
	        $this->output->set_content_type('application/json')->set_output(json_encode($data));
	    }
	}