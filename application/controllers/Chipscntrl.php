<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting("ERROR");

	class Chipscntrl extends CI_Controller {
		 	function __construct() {
		        parent::__construct();
				$_POST = json_decode(file_get_contents('php://input'), true);
		        $this->load->model('Chip_model');
		       // if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		    }
		 
		function SaveChip(){

		    	$validate = $this->Chip_model->validateSaveChipAc($_POST);

		//	print_r($validate);die;

		    	if(!empty($validate)){
		    		$this->output->set_content_type('application/json')->set_output(json_encode($validate));
		    	}else{
		    		$CrDr = $_POST['CrDr'];
				//die();
		    		$IsFree = $_POST['IsFree'];

		    		if ($IsFree == 1){
		    			if ($CrDr == 1) {
		    				$this->load->model('ModelUserRights');
		    				$userRole = $this->ModelUserRights->hasRole('ChipCr');
		    				if ($userRole['status']) {
		    					return $this->output->set_content_type('application/json')->set_output(json_encode(array('error' => 0, 'message' => $userRole['message'])));
		    				}
		    				$Msg = "Free Chip Credited Successfully ...";
		    			}else {
		    				$this->load->model('ModelUserRights');
		    				$userRole = $this->ModelUserRights->hasRole('ChipDr');
		    				if ($userRole['status']) {
		    					return $this->output->set_content_type('application/json')->set_output(json_encode(array('error' => 0, 'message' => $userRole['message'])));
		    				}
		    				$Msg = "Free Chip Debited Successfully ...";
		    			}
		    		}else{
		    			if ($CrDr == 1){
		    				$Msg = "Chip Deposit Successfully ...";
		    			}else{
		    				$Msg = "Free Chip Withdrawal Successfully ...";
		    			}

		    		}

		    		$condition = $this->Chip_model->SaveChip();
		    		if ($condition) {
					//echo $condition;
		    			echo json_encode(array('error' => 0 ,'message' => $Msg));
		    		}else{
		    			echo json_encode(array('error' => 1 ,'message' => 'Chip Not Successfully Saved ...'));
		    		}	
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

        function getUserTreeBalance($userId){
            $this->load->model('Modelcreatemaster');
            $data = $this->Modelcreatemaster->getUserTreeBalance($userId);

            echo "<pre>";print_r($data);die;
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

			$userBalance = $this->Modelcreatemaster->getUserBalance($userId1);
			//$functionLiability = $this->Chip_model->getLiability($userId1);

			$betLiability = array();
  			$betLiability[0] = array('Liability'=>$userBalance[0]['Liability'],'Balance'=>$userBalance[0]['Balance'],'P_L'=> sprintf('%0.2f', $userBalance[0]['P_L']),'FreeChip'=>$userBalance[0]['FreeChip'],'Chip'=>$userBalance[0]['Chip'],'sessionLiability'=>$userBalance[0]['sessionLiability'],'unmatchliability'=>$userBalance[0]['unmatchliability'],'match_stake'=>$userBalance[0]['match_stake'],'one_click_stake'=>$userBalance[0]['one_click_stake'],'is_confirm_bet'=>$userBalance[0]['is_confirm_bet'],'ShowVideoTv'=>SHOW_VIDEO_TV,'ShowSettlementButton'=>SHOW_SETTLEMENT_BUTTON,'ShowOtherBets'=>SHOW_OTHER_BETS);

			$data['betLibility'] = $betLiability;
		
            $user = $this->session->userdata('user_name');


            $currentTime = time();
            $SessionTime = $this->session->userdata('logendt');

            $checkSessionTime = $SessionTime;
            if(empty($user)){
                $is_login = false;
            }else{
                if($checkSessionTime < $currentTime){
                    $is_login = false;
                    $data['is_login'] = false;

                    //	$data['data'] = array();

                    $data['status'] = 'Invalid User Name Or Password';

                }else{
                    if($this->session->userdata('subAdminId')){
                        if(!$this->Modelcreatemaster->checkSubAdminActiveById($this->session->userdata('subAdminId'))){
                            $is_login=false;
                        }else{
                            $is_login=true;
                        }
                    }else{
                        $is_login=true;
                    }


                }

            }
			$data['is_login']=$is_login;
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSettlementEntryList');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data['delChipData'] = $this->Chip_model->getChipDelList($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
		}
		function deleteCashEntry($id){
			
			$this->load->model('Modelcreatemaster');
            $this->load->model('Modeltblcashentry');
            $this->load->model('ModelUserRights');

            $tblCashData = $this->Modeltblcashentry->findById($id);

            $userRole = $this->ModelUserRights->hasRole('ManageSettlementEntryList');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$condition = $this->Chip_model->deleteCashEntry($id);
			if($condition){

			//	print_r($tblCashData);
				if(!empty($tblCashData['childId'])){ 
					$this->Modelcreatemaster->updateUserBalLiablity($tblCashData['childId']);	
				}

				if(!empty($tblCashData['parentId'])){
					$this->Modelcreatemaster->updateUserBalLiablity($tblCashData['parentId']);	
				}
				
				echo json_encode(array('error' => 0 ,'message' => $Msg));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Chip Not Successfully Saved ...'));
			}	
			
		
		}

	}