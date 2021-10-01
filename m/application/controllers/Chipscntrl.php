<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting("ERROR");

	class Chipscntrl extends CI_Controller {
		 	function __construct() {
		        parent::__construct();
				$_POST = json_decode(file_get_contents('php://input'), true);
		        $this->load->model('Chip_model');
		        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		    }
		 
		function SaveChip(){
			
		  	$CrDr = $_POST['CrDr'];
			//die();
			$IsFree = $_POST['IsFree'];

			if ($IsFree == 1){
				if ($CrDr == 1)
					$Msg = "Free Chip Credited Successfully ...";
				else
					$Msg = "Free Chip Debited Successfully ...";
			}else{
				if ($CrDr == 1)
					$Msg = "Chip Deposit Successfully ...";
				else
					$Msg = "Free Chip Withdrawal Successfully ...";
			}

			$condition = $this->Chip_model->SaveChip();
			if ($condition) {
				//echo $condition;
				echo json_encode(array('error' => 0 ,'message' => $Msg));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Chip Not Successfully Saved ...'));
			}	
		
		}
		function SaveChipAc(){
			
			$condition = $this->Chip_model->SaveChipAc();
			if ($condition) {
				//echo $condition;
				echo json_encode(array('error' => 0 ,'message' => 'Chips detucted successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Chip Not Successfully Saved ...'));
			}	
		
		}
		function updateUserDetection($Amount,$id,$Type){
			
			$condition = $this->Chip_model->updateUserDetection($Amount,$id,$Type);
			if ($condition) {
				//echo $condition;
				echo json_encode(array('error' => 0 ,'message' => 'Chips detucted successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Chip Not Successfully Saved ...'));
			}	
		
		}
		function GetChipDetectById($UserId){
			$data['jsonData'] = $this->Chip_model->GetUserDetectionAmt($UserId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Save_main_chip(){
			
		  	$CrDr = $_POST['CrDr'];
			//die();
			$IsFree = $_POST['IsFree'];

			if ($IsFree == 1){
				if ($CrDr == 1)
					$Msg = "Settlement Chip Credited Successfully ...";
				else
					$Msg = "Settlement Chip Debited Successfully ...";
			}else{
				if ($CrDr == 1)
					$Msg = "Settlement Chip Deposit Successfully ...";
				else
					$Msg = "Settlement Chip Withdrawal Successfully ...";
			}

			$condition = $this->Chip_model->Save_main_chips();
				echo json_encode(array('error' => 0 ,'message' => $Msg));
		
		}
		function getChipData($userId=null)
		{
			if ($userId==null) {
				$userId=$this->session->userdata('user_id');
			}else{
				$userId=$userId;
			}
			$data['betLibility'] = $this->Chip_model->getLiability($userId);
			/*$data['FreeChip'] = $this->Chip_model->getChipData(1,$userId);
			$data['ChipInOut'] = $this->Chip_model->getChipData(0,$userId);*/
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
		}
		function getChipDataById($userId)
		{
			$this->load->model('Modelcreatemaster');
			/*$data['FreeChip'] = $this->Chip_model->getChipDataById(1,$userId);
			$data['ChipInOut'] = $this->Chip_model->getChipDataById(0,$userId);*/
			if ($userId==null) {
				$userId1=0;//$this->session->userdata('user_id');
			}else{
				$userId1=$userId;
			}
//			$data['betLibility'] = $this->Chip_model->getLiability($userId1);
			$data['betLibility'] = $this->Modelcreatemaster->getUserBalance($userId1);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
		}




		function getParentById($userId){
			$data['parentData'] = $this->Chip_model->getParentById($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
		}
		function checkDeduction($userId,$matchID){
			$data['chkcnt'] = $this->Chip_model->checkDeduction($userId,$matchID);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
		}
		function getChipDelList(){
			$data['delChipData'] = $this->Chip_model->getChipDelList($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
		}
		function deleteCashEntry($id){
			$condition = $this->Chip_model->deleteCashEntry($id);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => $Msg));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Chip Not Successfully Saved ...'));
			}	
			
		
		}

	}