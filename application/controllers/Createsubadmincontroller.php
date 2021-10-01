<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Createsubadmincontroller extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('Modelcreatemaster');
		        $_POST = json_decode(file_get_contents('php://input'), true);
		  		if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}       
		    }
		function index(){
			$this->load->model('Modelcreatemaster');
			$data['id']=$this->Modelcreatemaster->getFormData();
			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
        	
		}

        function submitCreateSubAdminData(){

            $validate = $this->Modelcreatemaster->validateSaveSubAdmin();
           
            if($validate['error']==1){
                $this->output->set_content_type('application/json')->set_output(json_encode($validate));
                return false;
            }

            $condition=$this->Modelcreatemaster->saveCreateMaster();

            if ($_POST['typeId']==1) {
                $userType="Master ".$_POST['username'];
            }else if($_POST['typeId']==2){
                $userType="Dealer ".$_POST['username'];
            }elseif($_POST['typeId']==3){
                $userType="Client ".$_POST['username'];
            }elseif($_POST['typeId']==5){
                $userType="Sub Admin ".$_POST['username'];
            }
            if ($condition==1) {
                echo json_encode(array('error' => 0 ,'message' => '['.$userType.'] Added Successfully...'));
            }else if($condition==2){
                echo json_encode(array('error' => 1 ,'message' => '['.$userType.'] Already Exits...'));
            }else if ($condition==0) {
                echo json_encode(array('error' => 1 ,'message' => '['.$userType.'] Not Added successfully...'));
            }
        }


        function deleteSubAdminById(){

            $condition=$this->Modelcreatemaster->deleteSubAdminById($_POST['user_id']);
            
            if ($condition) {

                echo json_encode(array('error' => 0 ,'message' => 'Account Deleted Successfully'));
            }else{
                echo json_encode(array('error' => 1 ,'message' => ' Account Not Delete Successfully'));
            }
        }

		function updateUserAccountData()//sourabh 11-nov-2016
		{
		    $noOfChild = $this->Modelcreatemaster->countChildByParentId($_POST['id']);
            if(isset($_POST['create_no_of_child']) && $_POST['create_no_of_child']!='')$create_no_of_child =$_POST['create_no_of_child'];else $create_no_of_child =0;
		    if($create_no_of_child < $noOfChild){
                echo json_encode(array('error' => 1, 'message' => "You have already register $noOfChild users "));
            }else {
                $condition = $this->Modelcreatemaster->updateUserAccountData();
                if ($condition) {
                    //echo $condition;
                    echo json_encode(array('error' => 0, 'message' => 'User Account Updated Successfully'));
                } else {
                    echo json_encode(array('error' => 1, 'message' => 'User Account not Updated Successfully'));
                }
            }
		}

	}