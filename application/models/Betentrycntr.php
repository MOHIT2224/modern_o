<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Betentrycntr extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('Betentrymodel');
		       /* if ($this->session->userdata('user_name') != '') {

		            
		        } else {
		            redirect(base_url());
		        }*/

		       
		}
		function deleteGetbetting($betId,$userId){
			$condition=$this->Betentrymodel->deleteGetbetting($betId,$userId);
			if ($condition) {
                $this->load->model('Modelcreatemaster');
                $this->Modelcreatemaster->updateUserBalLiablity($userId);
				$data['error'] = 0;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);
			}else{
				$data['error'] = 1;
				$data['message'] = 'Record not deleted';
				echo json_encode($data);	
			}

		}
		function Save_bet($userId=null){
			$condition=$this->Betentrymodel->Save_bet();
			//print_r($condition[0]);
			if ($userId==null){

						$userId1=$_POST['userId'];
			}else{

				$userId1=$userId;
			}
			
			if ($condition[0]['resultV']==0) {
					
				$data['error'] = 0;
				$data['message'] = '"'.$condition[0]['retMess'].'"';
				//$data['betUserData']=$this->Betentrymodel->getBetEntry($_POST['MarketId'],$_POST['UserTypeId'],$_POST['loginId']);sourabh 170125 not use now in matchodds
				//$data['RunnerValue']=$this->Betentrymodel->sumOfOdds($_POST['MarketId'],$userId1);
				$data['RunnerValue']=$this->Betentrymodel->sumOfOdds($_POST['MarketId'],$_POST['loginId'],$_POST['UserTypeId'],$_POST['matchId']);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
			}else if($condition[0]['resultV']== -3){
				$data['error'] = 1;
				$data['message'] = '"'.$condition[0]['retMess'].'"';
				echo json_encode($data);
			}else if($condition[0]['resultV']== -1){
				$data['error'] = 1;
				$data['message'] = '"'.$condition[0]['retMess'].'"';
				echo json_encode($data);

			}
		}
		function GatBetData($marketId,$userTypeId,$userId=null,$matchId=null){//170131
				$data['betUserData']=$this->Betentrymodel->getBetEntry($marketId,$userTypeId,$userId,$matchId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function updateUnMatchedData($userId,$BackLay,$marketId,$userTypeId,$loginId,$matchId){//sourabh 170131
				$condition=$this->Betentrymodel->updateUnMatchedData($userId,$BackLay);
			
			if ($condition) {
				$data['betUserData']=$this->Betentrymodel->getBetEntry($marketId,$userTypeId,$loginId,$matchId);//sourabh 170110
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
            	
			}

		}
		function Chip_history($UserID,$TypeID,$matchId,$MarketId)//sourabh 161222
		{
				$data['chips_data']=$this->Betentrymodel->Chip_history($UserID,$TypeID,$matchId,$MarketId);//sourabh 161222
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
            	
		}
		function online_users(){
				$data['online_user']=$this->Betentrymodel->online_users();
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Chip_summery(){
				$data['chip_summery']=$this->Betentrymodel->Chip_summery($_POST['userId'],$_POST['type']);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function profit_loss($userId){
				$data['userPL']=$this->Betentrymodel->profit_loss($userId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetResult(){
				$data['matchOddsRes']=$this->Betentrymodel->matchOddsRes();
				$data['FancyRes']=$this->Betentrymodel->FancyRes();
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function ActiveMatchUsers($matchId){
				$data['activeUsers']=$this->Betentrymodel->ActiveMatchUsers($matchId);
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function getActiveMatches(){
				$data['getActiveMatches']=$this->Betentrymodel->getActiveMatches();
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function BetHistory($userId=null){
			
				$data['BetHistory']=$this->Betentrymodel->BetHistory($userId);
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function adminLimit(){
				$data['adminLimit']=$this->Betentrymodel->adminLimit();				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function UpdateAdminLimit($id,$limit){
                $this->load->model('ModelUserRights');
                $userRole = $this->ModelUserRights->hasRole('ManageSetting');
                if($userRole['status']){
                    return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 0 ,'message' => $userRole['message'])));
                }
				$condition=$this->Betentrymodel->UpdateAdminLimit($id,$limit);
				
				if ($condition==true) {
					$data['adminLimit']=$this->Betentrymodel->adminLimit();	
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
	            	
				}else{
					$data['error'] = 1;
					$data['message'] = 'LIMIT NOT UPDATED....';
					echo json_encode($data);
				}
		}
		function NewChip_history($userId,$type){
			$data['user_chipsP']=$this->Betentrymodel->NewChip_historyP($userId,$type);
			$data['user_chipsM']=$this->Betentrymodel->NewChip_historyM($userId,$type);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Chip_historyById($userId,$userType,$lgnType,$parentId,$FROMDate,$ToDate){
			$data['chips_data']=$this->Betentrymodel->Chip_historyById($userId,$userType,$lgnType,$parentId,$FROMDate,$ToDate);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Chip_leger($userId,$userType,$selectType,$fromDate,$ToDate){

			$data['chips_lgr']=$this->Betentrymodel->Chip_leger($userId,$userType,$selectType,$fromDate,$ToDate);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}