<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Betentrycntr extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('Betentrymodel');
		        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}		        
		}
		function deleteGetbettingmatch($betId,$userId)
		{
			
			$condition=$this->Betentrymodel->deleteGetbettingmatched($betId,$userId);
			
			if ($condition) {
				$data['error'] = 0;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);
			}else{
				$data['error'] = 1;
				$data['message'] = 'Record not deleted';
				echo json_encode($data);	
			}

		}
		function deleteGetbetting($betId,$userId){
			
			$condition=$this->Betentrymodel->deleteGetbetting($betId,$userId);
		
			if ($condition) {
				$data['error'] = 0;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);
			}else{
				$data['error'] = 1;
				$data['message'] = 'Record not deleted';
				echo json_encode($data);	
			}

		}
		function GetMasterList(){
			$data['jsonData']=$this->Betentrymodel->GetMasterList();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}
		function GetDealer($masterId){
			$data['jsonData']=$this->Betentrymodel->GetDealer($masterId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function deleteGetbettingmat($betId,$userId,$marketId){
			
			$condition=$this->Betentrymodel->deleteGetbettingmat($betId,$userId,$marketId);
			//print_r($condition);
		
			if ($condition) {
				$data['error'] = 0;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);
			}else{
				$data['error'] = 1;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);	
			}

		}
		function Save_bet($userId=null){
			$this->load->model('Modelmatchmst');

			$validate = $this->Betentrymodel->validateSaveBet();

			if($validate['error']==1){
				$this->output->set_content_type('application/json')->set_output(json_encode($validate));
				return false;
			}


			$condition=$this->Betentrymodel->Save_bet();
			//print_r($condition[0]);
			if ($userId==null){

						$userId1=$_POST['userId'];
			}else{

				$userId1=$userId;
			}
			
			if ($condition[0]['resultV']==0) {
				$data['error'] = $condition[0]['resultV'];
				$data['message'] = '"'.$condition[0]['retMess'].'"';
				//$data['betUserData']=$this->Betentrymodel->getBetEntry($_POST['MarketId'],$_POST['UserTypeId'],$_POST['loginId']);sourabh 170125 not use now in matchodds
				//$data['RunnerValue']=$this->Betentrymodel->sumOfOdds($_POST['MarketId'],$userId1);
				$data['RunnerValue']=$this->Betentrymodel->sumOfOdds($_POST['MarketId'],$_POST['loginId'],$_POST['UserTypeId'],$_POST['matchId']);
				$this->Modelcreatemaster->updateUserBalLiablity($_POST['loginId']);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
			}else {
				$data['error'] = $condition[0]['resultV'];
				$data['message'] = '"'.$condition[0]['retMess'].'"';
				echo json_encode($data);
			}
		}
		function GatBetData($marketId,$userTypeId,$userId=null,$matchId=null){//170131
				$this->load->model('Modelmatchmst');
				$data['result'] = $this->Modelmatchmst->result($matchId);
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
		function Chip_history($UserID,$TypeID,$matchId,$MarketId,$OppAcID)//sourabh 161222
		{
				$data['chips_data']=$this->Betentrymodel->Chip_history($UserID,$TypeID,$matchId,$MarketId,$OppAcID);//sourabh 161222
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
            	
		}
		function online_users($userType,$userId){
				$data['online_user']=$this->Betentrymodel->online_users($userId,$userType);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Chip_summery(){
				$data['chip_summery']=$this->Betentrymodel->Chip_summery($_POST['userId'],$_POST['type']);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function profit_loss($userId,$sportId){
				$data['userPL']=$this->Betentrymodel->profit_loss($userId,$sportId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function profit_lossAll($userId,$sportId){
				$data['userPL']=$this->Betentrymodel->profit_loss1($userId,4);
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
		function BetHistory($userId=null,$search=NULL,$pageNo,$limit=DEFAULT_PAGING_LIMIT){

				if(!empty($pageNo)){
					$data['BetHistory']=$this->Betentrymodel->BetHistoryPaging($userId,$search,$pageNo,$limit);
				}else{
					$data['BetHistory']=$this->Betentrymodel->BetHistory($userId);
				}
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function LiablityHistory($userId=null){
			
				$data['BetHistory']=$this->Betentrymodel->LiablityHistory($userId);
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function AcStatement($userId=null){
			
				$data['BetHistory']=$this->Betentrymodel->AcStatement($userId);
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

		function SlmAcStatement($userId=null){
			
				$data['BetHistory']=$this->Betentrymodel->SlmAcStatement($userId);
				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function adminLimit(){
				$data['adminLimit']=$this->Betentrymodel->adminLimit();				
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function UpdateAdminLimit($id,$limit){
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
		function UpdateGngInPlayLimitLimit($limit){
				$condition=$this->Betentrymodel->UpdateGngInPlayLimitLimit($limit);
				
				if ($condition==true) {
					$data['error'] = 0;
					$data['message'] = 'LIMIT SET SUCCESSFULLY....';
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
	            	
				}else{
					$data['error'] = 1;
					$data['message'] = 'LIMIT NOT UPDATED....';
					echo json_encode($data);
				}
		}
		function UpdatepointVal($pointVal){
				$condition=$this->Betentrymodel->UpdatepointVal($pointVal);
				
				if ($condition==true) {
					$data['error'] = 0;
					$data['message'] = 'LIMIT SET SUCCESSFULLY....';
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
	            	
				}else{
					$data['error'] = 1;
					$data['message'] = 'LIMIT NOT UPDATED....';
					echo json_encode($data);
				}
		}
		function UpdateBetDelay($betDelay){
				$condition=$this->Betentrymodel->UpdateBetDelay($betDelay);
				
				if ($condition==true) {
					$data['error'] = 0;
					$data['message'] = 'BET DELAY SET '.$betDelay.' SECONDS SUCCESSFULLY....';
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
	            	
				}else{
					$data['error'] = 1;
					$data['message'] = 'BET DELAY NOT UPDATED....';
					echo json_encode($data);
				}
		}

		function PointValue($userId){
			$data['GetPoint']=$this->Betentrymodel->PointValue($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
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
		function Chip_leger($userId,$userType,$selectType,$fromDate,$ToDate1){
            $data['chips_lgr']=$this->Betentrymodel->Chip_leger($userId,$userType,$selectType,$fromDate,$ToDate1);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetPlus_Minus_Ac($userId,$matchId,$MarketId,$fancyId){
            $data['getPlusAc']=$this->Betentrymodel->GetPlusA_c($userId,$matchId,$MarketId,$fancyId);
            $data['getMiusAc']=$this->Betentrymodel->GetMinusA_c($userId,$matchId,$MarketId,$fancyId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function BetHistoryPL($userId,$matchId,$MarketId,$fancyId){
			$data['getBetPl']=$this->Betentrymodel->BetHistoryPL($userId,$matchId,$MarketId,$fancyId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function setHeaderMsg(){
			$condition=$this->Betentrymodel->setHeaderMsg();				
				if ($condition[0]['resultV']==0) {
					$data['error'] = $condition[0]['resultV'];
					$data['message'] = '"'.$condition[0]['retMess'].'"';
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
	            	
				}else{
					$data['error'] = $condition[0]['resultV'];
					$data['message'] = '"'.$condition[0]['retMess'].'"';
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
				}
		}
		function DisplayMsgOnHeader(){

			$this->checkAuthentication();

			$data['marqueMsg']=$this->Betentrymodel->DisplayMsgOnHeader();
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
//			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

		
	}