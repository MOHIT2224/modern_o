<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Createmastercontroller extends CI_Controller {
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
		function partnerValue($userId){
			$data=$this->Modelcreatemaster->partnerValue($userId);
			
			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function CheckUserName($user){
			$check=$this->Modelcreatemaster->chkMasterUsername($user);
			if ($check==0) {
				echo json_encode(array('error' => 0 ,'message' => 'Username available...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Username Already Exits...'));
			}
		}
        function CheckUserDelay($type,$parentId,$delay){
		    if($type=='b'){
                $check=$this->Modelcreatemaster->getBetDelayByUserId($parentId);
                $message = 'Minimum Bet Delay Id '.$check;
            }else{
                $check=$this->Modelcreatemaster->getSessionDelayByUserId($parentId);
                $message = 'Minimum Session Delay Id '.$check;
            }

            if ($check > $delay) {
                echo json_encode(array('error' => 1 ,'message' =>$message ));
            }else{
                echo json_encode(array('error' => 0 ,'message' => 'correct'));
            }
        }


		function autoCreateMasterData(){

			$this->Modelcreatemaster->autoCreateMaster();

			echo json_encode(array('error' => 0 ,'message' => 'Added Successfully...'));

		}	

		function submitCreateMasterData(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('AddUser');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$validate = $this->Modelcreatemaster->validateSaveCreateMaster();

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
				}
			if ($condition==1) {
				echo json_encode(array('error' => 0 ,'message' => '['.$userType.'] Added Successfully...'));
			}else if($condition==2){
				echo json_encode(array('error' => 1 ,'message' => '['.$userType.'] Already Exits...'));
			}else if ($condition==0) {
				echo json_encode(array('error' => 1 ,'message' => '['.$userType.'] Not Added successfully...'));
			}else if ($condition==3) {
                echo json_encode(array('error' => 1 ,'message' => 'you have already cross limit'));
            }
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
		//Get SportId and Data
		function SportMst(){
			$data['id']=$this->Modelcreatemaster->getSportMstData();
			$data['data']=$this->Modelcreatemaster->lstSportData();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function SaveSportMaster(){
			$condition=$this->Modelcreatemaster->SaveSportMaster();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Sport Inserted Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Not Inserted'));
			}
		} 
		//Get SportId and Sport type Data
		function SportTypeMst(){
			$data['id']=$this->Modelcreatemaster->getSportTypeId();
			$data['data1']=$this->Modelcreatemaster->lstSportData();
			$data['data']=$this->Modelcreatemaster->lstSportTypeData();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		//save data into Sport Type
		function saveSportType(){
			$condition=$this->Modelcreatemaster->SaveSportTypeMaster();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Data Inserted Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Not Inserted'));
			}
		}
		
		function lstTeamId(){
			$data['id']=$this->Modelcreatemaster->getTeameId();
			$data['data']=$this->Modelcreatemaster->lstTeamData();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function saveTeamData(){
			$condition=$this->Modelcreatemaster->saveTeamData();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Team Inserted Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Not Inserted'));
			}
		}
		function getPlayerId(){
			$data['id']=$this->Modelcreatemaster->getPlayerId();
			$data['data']=$this->Modelcreatemaster->lstPlayerData();
			$data['sportDropdown']=$this->Modelcreatemaster->lstSportDataById();
			$data['teamDropdown']=$this->Modelcreatemaster->lstTeamData();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function savePlayer(){
			$condition=$this->Modelcreatemaster->savePlayerData();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Player Inserted Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Not Inserted'));
			}	
		}
		function GetSeriesId(){
			$id='';
			$data['id']=$this->Modelcreatemaster->getSeriesId();
			$data['sportDropdown']=$this->Modelcreatemaster->lstSportData();
			$data['data']=$this->Modelcreatemaster->lstSeriesData($id);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function saveSeriesData(){
			$condition=$this->Modelcreatemaster->saveSeriesData();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Series Inserted Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Series Not Inserted'));
			}	
		}
		function GetSportId(){			
			$data['sportDropdown']=$this->Modelcreatemaster->lstSportDataById();
			$data['matchId']=$this->Modelcreatemaster->getMaxMatchId();			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetOnchangeEventSportId($sportId){
			$data['sportDropdown']=$this->Modelcreatemaster->lstSportData();
			$data['matchId']=$this->Modelcreatemaster->getMaxMatchId();
			$data['teamDropdown']=$this->Modelcreatemaster->lstTeamData();
			$data['matchType']=$this->Modelcreatemaster->lstSportTypeData();
			$data['getseries']=$this->Modelcreatemaster->lstSeriesData($sportId);
			$data['getPlayer']=$this->Modelcreatemaster->GetPlayerLstById($sportId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function SaveMatchEntryForm(){
			$condition=$this->Modelcreatemaster->saveMatchEntryData();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Fancy Heading Save Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Match Not Inserted'));
			}	
		}
		function betEntry(){
			$data['bet_id']=$this->Modelcreatemaster->getMaxBetId();
			$data['getPlayer']=$this->Modelcreatemaster->GetPlayerLst();
			$data['getUsers']=$this->Modelcreatemaster->GetUserLst();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function SaveFancyHeader(){
			$condition=$this->Modelcreatemaster->saveFancyEntry();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Match Inserted Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Match Not Inserted'));
			}	
		}
		function getFancyHeader(){
			$data['getFancyHeader']=$this->Modelcreatemaster->getFancyHeader();			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function SaveFancy(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('AddFancy');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $id=$this->Modelcreatemaster->saveFancy();

			if ($id) {
                try {
                    $redis = new Redis();
                    $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                    $this->load->model('Modelmatchfancy');
                    $result = $this->Modelmatchfancy->selectFancyById($id);
                    $redis->set($this->db->database.'ind_' . $_POST['mid'] . '_' . $id, json_encode($result));
                    $redis->close();
                } catch (Exception $e) {
                }
				echo json_encode(array('error' => 0 ,'message' => 'Fancy Inserted Successfully...'));
			}else if($id==2){
				echo json_encode(array('error' => 1 ,'message' => 'Fancy Alredy Exits'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Fancy Not Inserted'));
			}	
		}
		function viewUserAc($id,$type){
			$data['viewUserAc1']=$this->Modelcreatemaster->viewUserAc($id,$type);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function changeUserPasswod(){

			$condition=$this->Modelcreatemaster->changeUserPasswod1();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Password Change Successfully....'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Password Not match ....'));
			}	
		}
		function changekPassword(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ChangePwd');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$condition=$this->Modelcreatemaster->ChangeUserPassword();
			
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Password Change Successfully....'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Password Not match ....'));
			}	
		}
		function lockUser_bk(){
			$condition=$this->Modelcreatemaster->lockUser();
			if ($condition) {
				if ($_POST['lockVal']==0) {
					echo json_encode(array('error' => 0 ,'message' => 'User Lock Successfully...'));
				}else{
					echo json_encode(array('error' => 0 ,'message' => 'User Unlock Successfully...'));
				}
			}else{
				    echo json_encode(array('error' => 1 ,'message' => 'User not Lock Successfully'));
			}	
		}
        function lockUser(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('UserLock');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $condition=$this->Modelcreatemaster->lockUser();
            if ($condition['success']) {
                if(isset($_POST['userId'])){
                    $userId = $_POST['userId'];
                    $childUsers = $this->Modelcreatemaster->getChildUsers($userId);
                    foreach($childUsers as $childUser){
                        $childUserId = $childUser['mstrid'];
                        $subChildUsers = $this->Modelcreatemaster->getChildUsers($childUserId);

                        $this->Modelcreatemaster->lockUsers($subChildUsers);
                    }
                    $this->Modelcreatemaster->lockUsers($childUsers);
                }

                echo json_encode(array('error' => 0 ,'message' => $condition['message']));

            }else{
                echo json_encode(array('error' => 1 ,'message' => $condition['message']));
            }
        }
		function lockuserbetting_bk(){
			//echo $_POST['lockbettingVal'];die();
			$condition=$this->Modelcreatemaster->lockUserBetting();
			if ($condition) {
				if ($_POST['lockbettingVal']==0) {

					if(isset($_POST['userId'])){
						$userId = $_POST['userId'];
						$childUsers = $this->Modelcreatemaster->getChildUsers($userId);
						foreach($childUsers as $childUser){
							$childUserId = $childUser['mstrid'];
							$subChildUsers = $this->Modelcreatemaster->getChildUsers($childUserId);
							$this->Modelcreatemaster->lockBettingUsers($subChildUsers);
						}
						$this->Modelcreatemaster->lockBettingUsers($childUsers);
					}
					
					echo json_encode(array('error' => 0 ,'message' => 'User BettingLock Successfully'));
				}else{
					echo json_encode(array('error' => 0 ,'message' => 'User Betting UnLock Successfully'));
				}
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'BettingLock not Successfully'));
			}	
		}

        function lockuserbetting(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('BettingLock');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $condition=$this->Modelcreatemaster->lockUserBetting();
            if ($condition['success']) {
                if(isset($_POST['userId'])){
                    $userId = $_POST['userId'];
                    $childUsers = $this->Modelcreatemaster->getChildUsers($userId);
                    foreach($childUsers as $childUser){
                        $childUserId = $childUser['mstrid'];
                        $subChildUsers = $this->Modelcreatemaster->getChildUsers($childUserId);

                        foreach($subChildUsers as $subChildUser){
                            $subChildUserId = $subChildUser['mstrid'];
                            $subSubChildUsers = $this->Modelcreatemaster->getChildUsers($subChildUserId);
                            $this->Modelcreatemaster->lockBettingUsers($subSubChildUsers);
                        }
                        $this->Modelcreatemaster->lockBettingUsers($subChildUsers);
                    }
                    $this->Modelcreatemaster->lockBettingUsers($childUsers);
                }

                echo json_encode(array('error' => 0 ,'message' => $condition['message']));

            }else{
                echo json_encode(array('error' => 1 ,'message' => $condition['message']));
            }
        }

        function updateUserAccount(){
            $this->load->model('ModelUserRights');
            if($_POST['accValue']==1){
                $userRole = $this->ModelUserRights->hasRole('ManageClosedUsersAccount');
            }else{
                $userRole = $this->ModelUserRights->hasRole('Close_Ac');
            }

            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $condition=$this->Modelcreatemaster->closeUserAccount();
            if ($condition['success']) {
                if(isset($_POST['userId'])){
                    $userId = $_POST['userId'];
                    $childUsers = $this->Modelcreatemaster->getChildUsers($userId);
                    foreach($childUsers as $childUser){
                        $childUserId = $childUser['mstrid'];
                        $subChildUsers = $this->Modelcreatemaster->getChildUsers($childUserId);

                        foreach($subChildUsers as $subChildUser){
                            $subChildUserId = $subChildUser['mstrid'];
                            $subSubChildUsers = $this->Modelcreatemaster->getChildUsers($subChildUserId);
                            $this->Modelcreatemaster->closeAccountUsers($subSubChildUsers);
                        }
                        $this->Modelcreatemaster->closeAccountUsers($subChildUsers);
                    }
                    $this->Modelcreatemaster->closeAccountUsers($childUsers);
                }

                echo json_encode(array('error' => 0 ,'message' => $condition['message']));

            }else{
                echo json_encode(array('error' => 1 ,'message' => $condition['message']));
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('UpdateUser');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
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
		function viewUserAcData($id,$MatchId,$MarketId){ //sourabh 11-nov-2016

			$data['viewUserAc2']=$this->Modelcreatemaster->viewUserAcData($id);
			$data['maxProfitData'] = $this->Modelcreatemaster->getMaxProfit($id,$MatchId,$MarketId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetResetPasssword(){
			$data['gtCnfgPswrd']=$this->Modelcreatemaster->getResetPassword();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function UpdateRstPasssword($userId,$passwrd,$HelperID){
			$condition=$this->Modelcreatemaster->updateUserAcPasswrd($userId,$passwrd,$HelperID);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Password Reset Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Password Not Reset....'));
			}	
		}
		function UpdateCnfgPasssword($passwrd){
			$condition=$this->Modelcreatemaster->UpdateCnfgPasssword($passwrd);
			if ($condition) {
				//echo $condition;
				echo json_encode(array('error' => 0 ,'message' => 'Password set default Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Password Not set Successfully....'));
			}	
		}
		function submitClearChip(){//sourabh 161229
			$condition=$this->Modelcreatemaster->submitClearChip();
			echo json_encode(array('error' => $condition[0]['resultV'] ,'message' => $condition[0]['retMess']));
		}

		function dailySettleChip($userId=0){
			$this->load->model('Modeltblchipdet');
			$result = $this->Modeltblchipdet->updateByUserId($userId,array('is_settled'=>'Y'));
			if ($result) {
				echo json_encode(array('error' => 0 ,'message' => 'Daily profit settled'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Something went wrong'));
			}	
		}

		function UpdateSessionFancy(){
			$condition=$this->Modelcreatemaster->UpdateSessionFancy();
			if ($condition==1) {
				//echo $condition;
				echo json_encode(array('error' => 0 ,'message' => 'Fancy Inserted Successfully...'));
			
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Fancy Not Inserted'));
			}	

		}
		function EditFancy(){//sourabh 170118
		
			$condition=$this->Modelcreatemaster->editFancy();
			//$condition=$condition[0]['resultV'];
			
			echo json_encode(array('error' => $condition[0]['resultV'] ,'message' => "'".$condition[0]['retMess']."'"));
			
		}
		function updatePartnerShip($admin,$master,$dealer,$userId,$HelperID){
				$condition=$this->Modelcreatemaster->updatePartnerShip($admin,$master,$dealer,$userId,$HelperID);
			if ($condition==1) {
				$this->Modelcreatemaster->updatePatner($userId);
				echo json_encode(array('error' => 0 ,'message' => 'Partnership updated Successfully...'));			
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Partnership Not updated...'));
			}	
		}
		function updateCommission($oddsComm,$sessionComm,$otherComm,$ID,$HelperID){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('UpdateUser');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$condition=$this->Modelcreatemaster->updateUserCommission($oddsComm,$sessionComm,$otherComm,$ID,$HelperID);
			if ($condition==1) {
				echo json_encode(array('error' => 0 ,'message' => 'Commission updated Successfully...'));			
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Commission Not updated...'));
			}	

		}
	}