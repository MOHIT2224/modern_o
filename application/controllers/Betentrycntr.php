<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Betentrycntr extends CI_Controller {
		function __construct() {
            header('Access-Control-Allow-Origin: *');
		        parent::__construct();
		        $this->load->model('Betentrymodel');
            $this->load->model('Modelchkuser');
            $currentMethod = $this->router->method;
            $allowAuth = array('moveUnMatchToMatch');
            if(!in_array($currentMethod, $allowAuth)){
                //$this->checkAuthentication();
            }
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
			
		
			if ($condition) {
                $this->load->model('Modelcreatemaster');
                $this->Modelcreatemaster->updateUserBalLiablity($userId);
				$data['error'] = 0;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);
			}else{
				$data['error'] = 1;
				$data['message'] = 'Record deleted Successfully...';
				echo json_encode($data);	
			}

		}

        function moveToAvoidBet($betId,$userId,$marketId){

            $condition=$this->Betentrymodel->moveToAvoidBet($betId,$userId,$marketId);


            if ($condition) {
                $this->load->model('Modelcreatemaster');
                $this->Modelcreatemaster->updateUserBalLiablity($userId);
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
			$this->load->model('Modelcreatemaster');
			$this->load->model('Modelmatchmst');
			$this->load->model('Modeltblbets');

			$validate = $this->Betentrymodel->validateSaveBet();

			if($validate['error']==1){
				$this->output->set_content_type('application/json')->set_output(json_encode($validate));
				return false;
			}

			if ($userId==null){
				$loginUserId1=$_POST['userId'];
			}else{

				$loginUserId1=$userId;
			}

			$betDelay = $this->Modelcreatemaster->getBetDelay($loginUserId1);

			if(isset($betDelay[0]['set_timeout'])){
				$timeout = $betDelay[0]['set_timeout'];
				sleep($timeout);
			}

			$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;	
			$cricketJson = $this->httpGet($cricketUrl);
			$cricketArr = json_decode($cricketJson,true);



			$matchArr = array();
			$sessionMatchArr = array();
			if(!empty($cricketArr['result'])){
				foreach($cricketArr['result'] as $key => $cArr){
					$runners = array();
					foreach($cArr['runners'] as $runner){
						$back = $runner['back'][0];
						$lay = $runner['lay'][0];
						$runners[$runner['id']] = array('back'=> $back,'lay'=>$lay);
					}
					$matchArr[$cArr['id']] = $runners;
				}
			}

			$soccerUrl = BR_LIVE_SOCCER_SOCKET_URL;	
			$soccerJson = $this->httpGet($soccerUrl);
			$soccerArr = json_decode($soccerJson,true);
			if(!empty($soccerArr['result'])){
				foreach($soccerArr['result'] as $key => $cArr){
					$runners = array();
					foreach($cArr['runners'] as $runner){
						$back = $runner['back'][0];
						$lay = $runner['lay'][0];
						$runners[$runner['id']] = array('back'=> $back,'lay'=>$lay);
					}
					$matchArr[$cArr['id']] = $runners;
				}
			}

			$tennisUrl = BR_LIVE_TENNIS_SOCKET_URL;	
			$tennisJson = $this->httpGet($tennisUrl);
			$tennisArr = json_decode($tennisJson,true);
			if(!empty($tennisArr['result'])){
				foreach($tennisArr['result'] as $key => $cArr){
					$runners = array();
					foreach($cArr['runners'] as $runner){
						$back = $runner['back'][0];
						$lay = $runner['lay'][0];
						$runners[$runner['id']] = array('back'=> $back,'lay'=>$lay);
					}
					$matchArr[$cArr['id']] = $runners;
				}
			}

			$MarketId =  $this->input->post('MarketId');
			if(!empty($matchArr[$MarketId])){
					$selectionId = $this->input->post('selectionId');
					$isback = $this->input->post('isback');
					$priceVal = $this->input->post('priceVal');

					$marketVal = $matchArr[$MarketId];
					if(!empty($marketVal[$selectionId])){
						$selectionVal = $marketVal[$selectionId];
						if($isback==0){
							if($selectionVal['back']['price'] == $priceVal){
								$isMatched = 1;
							}else{
								$isMatched = 0;
							}
						}else{
							if($selectionVal['lay']['price'] == $priceVal){
								$isMatched = 1;
							}else{
								$isMatched = 0;
							}
						}
					}
			}else{
				$validate = array('code' => 9 ,'error'=>true,'message' => 'Match not active');
				$this->output->set_content_type('application/json')->set_output(json_encode($validate));
				return false;
			}

			$config_matched = CONFIG_UNMATCHED;

			if($isMatched == 0 && $config_matched=='N'){
				$validate = array('code' => 9 ,'error'=>true,'message' => 'Odds changed');
				$this->output->set_content_type('application/json')->set_output(json_encode($validate));
				return false;
			} 

		/*	echo "ismatched $isMatched marketId $MarketId selectionId $selectionId isback $isback priceVal $priceVal";
			die;  */

			$condition=$this->Betentrymodel->Save_bet($isMatched);
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
				$id = $condition[0]['resultV'];
				$this->Modeltblbets->deleteBet($id);
				$data['error'] = $condition[0]['resultV'];
				$data['message'] = '"'.$condition[0]['retMess'].'"';
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
			}
		}

		function moveUnMatchToMatchBetByAdmin($betId,$userId){

            if($this->Betentrymodel->moveUnMatchToMatchBetByAdmin($betId)){
                $this->load->model('Modelcreatemaster');
                $this->Modelcreatemaster->updateUserBalLiablity($userId);
                $validate = array('code' => 0 ,'error'=>true,'message' => 'successfully');
            }else{
                $validate = array('code' => 1 ,'error'=>true,'message' => 'some thing is wrong');
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($validate));
        }

        function moveUnMatchToMatch($betId=null){
           // var_dump(file_put_contents('test123.txt',$betId));
            try {
                if (!empty($betId)) {
                    $ids = explode('-', $betId);
                    foreach ($ids as $id) {
                        $this->Betentrymodel->updateOddsProfitLossOnBetMoveUnmatchToMatch($id);
                        $this->db->query(" UPDATE `tblbets` SET `MatchedDate`= now() WHERE MstCode = $id");
                    }
                }
            } catch (Exception $e) {
            }
        }

		function GatBetData($marketId,$userTypeId,$userId=null,$matchId=null){//170131
				$this->load->model('Modelmatchmst');
				$data['result'] = $this->Modelmatchmst->result($matchId);
				$data['betUserData']=$this->Betentrymodel->getBetEntry($marketId,$userTypeId,$userId,$matchId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

		

        function GatBetDataAll($marketId,$userTypeId,$userId=null,$matchId=null){
            $this->load->model('Modelmatchmst');
            $data['result'] = $this->Modelmatchmst->result($matchId);
            $data['betUserDataAll']=$this->Betentrymodel->getBetEntryAll($marketId,$userTypeId,$userId,$matchId);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

		function GatBetFancyData($marketId,$userTypeId,$userId=null,$matchId=null,$fancyId=NULL){//170131
				$this->load->model('Modelmatchmst');
				$data['result'] = $this->Modelmatchmst->result($matchId);
				$data['betUserData']=$this->Betentrymodel->getBetFancyEntry($marketId,$userTypeId,$userId,$matchId,$fancyId);
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		
		function updateUnMatchedData($userId,$BackLay,$marketId,$userTypeId,$loginId,$matchId){

				$tblBetId = $userId;
				$result = false;
			//	$this->load->model('Betentrymodel');
				$this->load->model('Modeltblbets');
				$this->load->model('Modelcreatemaster');
				$betData = $this->Modeltblbets->findById($tblBetId);

				if(!empty($betData)){
					$MarketId = $betData['MarketId'];

					$redisUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$MarketId;	
					$redisJson = $this->httpGet($redisUrl);
					$redisArr = json_decode($redisJson,true);

				//	print_r($redisArr);

				/*	if($betData['SportID']==4){
						$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;	
					}elseif($betData['SportID']==2){
						$cricketUrl = BR_LIVE_TENNIS_SOCKET_URL;	
					}elseif($betData['SportID']==1){
						$cricketUrl = BR_LIVE_SOCCER_SOCKET_URL;	
					}

					$cricketJson = $this->httpGet($cricketUrl);
					$cricketArr = json_decode($cricketJson,true);   */

					$matchArr = array();
					$sessionMatchArr = array();
					foreach($redisArr as $cArr){
							$runners = array();
							foreach($cArr['runners'] as $runner){
								$back = $runner['back'][0];
								$lay = $runner['lay'][0];
								$runners[$runner['id']] = array('back'=> $back,'lay'=>$lay);
							}
							$matchArr[$cArr['id']] = $runners;
					}

				//	print_r($matchArr);

				//	print_r($betData);die;

					if(!empty($matchArr[$MarketId])){
						$selectionId = $betData['SelectionId'];
						$isback = $betData['isBack'];
						$priceVal = $betData['Odds'];;

						$marketVal = $matchArr[$MarketId];
						if(!empty($marketVal[$selectionId])){
							$selectionVal = $marketVal[$selectionId];
							if($isback==0){
								if($selectionVal['back']['price'] >= $priceVal){
									$this->Modeltblbets->update($tblBetId,array('IsMatched' => 1));
									$this->Modelcreatemaster->updateUserBalLiablity($loginId);
									$result = true;
								}else{
									
								}
							}else{
								if($selectionVal['lay']['price'] <= $priceVal){
									$this->Modeltblbets->update($tblBetId,array('IsMatched' => 1));
									$this->Modelcreatemaster->updateUserBalLiablity($loginId);
									$result = true;
								}else{
									
								}
							}
						}
					}

				}

			/*	echo '<pre>';
				echo 'test';
				print_r($betData);
				print_r($matchArr[$MarketId]);
				var_dump($result);die;  */


			//	$condition = $this->Betentrymodel->updateUnMatchedData($userId,$BackLay);			


				if ($result) {
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('OnlineUsers');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewProfitAndLoss');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $data['userPL']=$this->Betentrymodel->profit_loss1($userId,4);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

		function profitLossByMatchId(){
            $userId = $_POST['user_id'];
            $matchId = $_POST['match_id'];
            $event_name = (isset($_POST['event_name']) ? $_POST['event_name'] : '');
            $from_date = (isset($_POST['from_date']) ? $_POST['from_date'] : 0);
            $to_date = (isset($_POST['to_date']) ? $_POST['to_date'] : 0);
            $param = ['user_id'=>$userId,'match_id'=>$matchId,'event_name'=>$event_name,'from_date'=>$from_date,'to_date'=>$to_date];
            $data['userPL']=$this->Betentrymodel->mbdip_profit_loss_by_match($param);
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

        function activeUser(){
            $this->load->model('Modelcreatemaster');

            $data['activeUsers']=$this->Modelcreatemaster->activeUser();;
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

		function BetHistoryFilters($userId=null,$fromDate=NULL,$toDate=NULL){

				if(!empty($fromDate) && !empty($toDate)){
					$data['BetHistory']=$this->Betentrymodel->BetHistoryFilter($userId,$fromDate,$toDate);
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
                $this->load->model('ModelUserRights');
                $userRole = $this->ModelUserRights->hasRole('ManageSetting');
                if($userRole['status']){
                    return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
                }
				$condition=$this->Betentrymodel->UpdateAdminLimit($id,$limit);
				
				if ($condition==true) {
					$data['adminLimit']=$this->Betentrymodel->adminLimit();
                    $data['error'] = 0;
                    $data['message'] = 'Limit set Successfully...';
					$this->output->set_content_type('application/json')->set_output(json_encode($data));
	            	
				}else{
					$data['error'] = 1;
					$data['message'] = 'LIMIT NOT UPDATED....';
					echo json_encode($data);
				}
		}
		function UpdateGngInPlayLimitLimit($limit){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data['GetPoint']=$this->Betentrymodel->PointValue($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function NewChip_history($userId,$type){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ChipSummary');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data['user_chipsP']=$this->Betentrymodel->NewChip_historyP($userId,$type);
			$data['user_chipsM']=$this->Betentrymodel->NewChip_historyM($userId,$type);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Chip_historyById($userId,$userType,$lgnType,$parentId,$FROMDate,$ToDate){
			$data['chips_data']=$this->Betentrymodel->Chip_historyById($userId,$userType,$lgnType,$parentId,$FROMDate,$ToDate);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function Chip_leger($userId,$userType,$selectType,$fromDate,$ToDate1){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ChipHistory');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
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
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$condition=$this->Betentrymodel->setHeaderMsg();

            $data['error'] = 0;
            $data['message'] = 'Successfully Updated';
            $this->output->set_content_type('application/json')->set_output(json_encode($data));

		}
		function DisplayMsgOnHeader(){

			$this->checkAuthentication();

			$data['marqueMsg']=[["Marquee"=>$this->Betentrymodel->DisplayMsgOnHeader()]];

			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
//			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

        function deleteBetByMatchId($matchId){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSettledMatches');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $data['message'] = "some thing is wrong";
            $data['error'] = 1;
            if($this->Betentrymodel->deleteBetByMatchId($matchId)){
                $data['message'] = "bet successfully deleted";
                $data['error'] = 0;
            }
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
        }

        function hardDeleteBetByMatchId($matchId){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageTrashBets');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $data['message'] = "some thing is wrong";
            $data['error'] = 1;
            if($this->Betentrymodel->hardHeleteBetByMatchId($matchId)){
                $data['message'] = "bet successfully deleted";
                $data['error'] = 0;
            }
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
        }

        function undoBetByMatchId($matchId){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageTrashBets');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
            $data['message'] = "some thing is wrong";
            $data['error'] = 1;
            if($this->Betentrymodel->undoBetByMatchId($matchId)){
                $data['message'] = "bet successfully undo";
                $data['error'] = 0;
            }
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
        }

        function getDeletedBetByMatchId($matchId){
            $data['message'] = "some thing is wrong";
            $data['error'] = 1;
            if($this->Betentrymodel->getDeletedBetByMatchId($matchId)){
                $data['message'] = "bet successfully undo";
                $data['error'] = 0;
            }
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
        }

        function getDeletedBetHistoryPL($userId,$matchId,$MarketId,$fancyId){
            $data['message'] = "some thinf is wrong";
            $data['error'] = 1;
            if($this->Betentrymodel->getDeletedBetHistoryPL($userId,$matchId,$MarketId,$fancyId)){
                $data['message'] = "bet successfully ";
                $data['error'] = 0;
            }
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
        }

		
	}