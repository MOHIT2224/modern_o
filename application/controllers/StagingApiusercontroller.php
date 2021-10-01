<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class StagingApiusercontroller extends CI_Controller {
		var $globalUserId;
		var $globalUserType;
	//	public $APP_KEY = BETFAIR_APP_KEY;

		function __construct() {

				header('Access-Control-Allow-Origin: *');

		        parent::__construct();

                $_POST = json_decode(file_get_contents('php://input'), true);

		        $node1=$this->session->userdata('user_id');

		        $this->load->model('Modelchkuser');
		       // if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		       $currentMethod = $this->router->method;
		       $allowAuth = array();
		       if(!in_array($currentMethod, $allowAuth)){
					$this->checkAuthentication();
		       }  
		}

		/**
		 * [checkAuthentication check user authentication by headers]
		 * @return [type] [description]
		 */
		function checkAuthentication(){

			$this->load->model('Modelcreatemaster');

			$username = $this->input->request_headers('PHP_AUTH_USER');
    		$password = $this->input->request_headers('PHP_AUTH_PW'); 
			$http_auth = $this->input->request_headers('Authorization');

			if(!empty($http_auth['Authorization'])){ 
 
				$basicauth = explode(' ', $http_auth['Authorization']);

				$userPass = $basicauth[1]; 
				$userPass = base64_decode($userPass);
				$authUser = explode(':', $userPass);
				$userName = $authUser[0];
				$password = $authUser[1];
				$checkUser = $this->Modelcreatemaster->checkUserStatus($userName,$password);
				
				if(empty($checkUser)){
					$response = array();
					$response["code"] = 1;
        			$response["error"] = true;
        			$response["message"] = "unauthorized access";
					$this->output->set_status_header(412)->set_content_type('application/json')->set_output(json_encode($response));
					exit();
				}else{
					$this->globalUserId = $checkUser['mstrid'];
					$this->globalUserType = $checkUser['usetype'];
				}
			}else{
					$response = array();
					$response["code"] = 1;
        			$response["error"] = true;
        			$response["message"] = "unauthorized access";
					$this->output->set_status_header(412)->set_content_type('application/json')->set_output(json_encode($response));
					exit();
			}
		}

		function favourite(){	

			$this->load->model('Modelfavouritemarket');
			$this->verifyRequiredParams($_POST,array('market_id'));	

			$data['user_id'] = $this->globalUserId;
			$data['market_id'] = $_POST['market_id'];

			$isAlreadyExists = $this->Modelfavouritemarket->checkMarketExists($data);

			if($isAlreadyExists){

				$isInserted = $this->Modelfavouritemarket->insert($data);
				if ($isInserted) {
					$response["code"] = 0;
					$response["error"] = false;
					$response["message"] = "Market is favourite";
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}else{
					$response["code"] = ERROR_TRY_AGAIN;
					$response["error"] = true;
					$response["message"] = ERROR_TRY_AGAIN_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}

			}else{

					$response["code"] = ERROR_EXITS;
					$response["error"] = true;
					$response["message"] = ERROR_EXITS_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}	
			
			
			
		}

		function unfavourite(){	

			$this->load->model('Modelfavouritemarket');
			$this->verifyRequiredParams($_POST,array('market_id'));	

			$data['user_id'] = $this->globalUserId;
			$data['market_id'] = $_POST['market_id'];

			$isAlreadyExists = $this->Modelfavouritemarket->checkMarketExists($data);

			if(!$isAlreadyExists){
			
			$isdeleted = $this->Modelfavouritemarket->delete($data);
			if ($isdeleted) {
				$response["code"] = 0;
    			$response["error"] = false;
    			$response["message"] = "Market is unfavourite";
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = ERROR_TRY_AGAIN;
    			$response["error"] = true;
    			$response["message"] = ERROR_TRY_AGAIN_MSG;
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}

			}else{
				$response["code"] = ERROR_TRY_AGAIN;
    			$response["error"] = true;
    			$response["message"] = ERROR_TRY_AGAIN_MSG;
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
			
		}

		function getUserFavouriteMatchLst($sportId){

			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelmatchmst');
			$this->load->model('Modelcreatemaster');
			$user_id = $this->globalUserId;

			$userSetting = $this->Modelcreatemaster->viewUserAcData($user_id);

        	if(!empty($userSetting[0]['match_stake'])){
        		$match = $userSetting[0]['match_stake'];
        		$session = $userSetting[0]['match_stake'];
        	}else{
        		$match = MATCH_STAKE_OPTION;	
        		$session = SESSION_STAKE_OPTION;
        	}

        	if(!empty($userSetting[0]['one_click_stake'])){
        		$oneclick = $userSetting[0]['one_click_stake'];
        	}else{
        		$oneclick = ONE_CLICK_STAKE_OPTION;	
        	}
        	
			$matchStacks = json_decode($match,true);
			$sessionStacks = json_decode($session,true);
			$oneClickStacks = json_decode($oneclick,true);

			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "match listing";
        	$response["match_stack"] = $matchStacks;
        	$response["session_stack"] = $sessionStacks;
        	$response["one_click_stack"] = $oneClickStacks;

        	if($sportId == 0 || $sportId == 4){

        		$dbMatches = $this->Modeleventlst->mobileGetUserFavouriteMatchLst(BETFAIR_SPORT_CRICKET,$user_id);

			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

			/*	$cricketUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.BETFAIR_SPORT_CRICKET;
				$matchesJson = $this->httpGet($cricketUrl);
				$matches = json_decode($matchesJson,true); */

			/*	echo $cricketUrl;
				echo $matchesJson;
				print_r($matches);die;  */

				$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}
			//	}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);
					$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 
				//	$dbMarkets = explode(',', $dbMatch['marketid']);
				//	$matched = array_intersect($marketIds,$dbMarkets);
				//	if(!empty($matched)){
						$filterMatches[] = $dbMatch;
				//	}
				}

        		$cricket = array();
	        	$cricket['sportname'] = 'Cricket';
	        	$cricket['SportId'] = BETFAIR_SPORT_CRICKET;
	        	$cricket['values'] = $filterMatches;
	        	$response["data"][] = $cricket;
        	}	

			if($sportId == 0 || $sportId == 2){

				$dbMatches = $this->Modeleventlst->mobileGetUserFavouriteMatchLst(BETFAIR_SPORT_TENNIS,$user_id);
			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

			/*	$tennisUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.BETFAIR_SPORT_TENNIS;
				$matchesJson = $this->httpGet($tennisUrl);
				$matches = json_decode($matchesJson,true); */

				$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}
			//	}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);
					$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 
				//	$dbMarkets = explode(',', $dbMatch['marketid']);
				//	$matched = array_intersect($marketIds,$dbMarkets);
				//	if(!empty($matched)){
						$filterMatches[] = $dbMatch;
				//	}
				}

	        	$tennis = array();
	        	$tennis['sportname'] = 'Tennis';
	        	$tennis['SportId'] = BETFAIR_SPORT_TENNIS;
	        	$tennis['values'] = $filterMatches;
	        	$response["data"][] = $tennis;
	        }
	        	
			if($sportId == 0 || $sportId == 1){

				$dbMatches = $this->Modeleventlst->mobileGetUserFavouriteMatchLst(BETFAIR_SPORT_SOCCER,$user_id);
			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

			/*	$soccerUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.BETFAIR_SPORT_SOCCER;
				$matchesJson = $this->httpGet($soccerUrl);
				$matches = json_decode($matchesJson,true); */

				$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}	
			//	}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);
					$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 
			//		$dbMarkets = explode(',', $dbMatch['marketid']);
				//	$matched = array_intersect($marketIds,$dbMarkets);
				//	if(!empty($matched)){
						$filterMatches[] = $dbMatch;
				//	}
				}

	        	$soccer = array();
	        	$soccer['sportname'] = 'Soccer';
	        	$soccer['SportId'] = BETFAIR_SPORT_SOCCER;
	        	$soccer['values'] = $filterMatches;
	        	$response["data"][] = $soccer;
        	}
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}


		function getFavouriteMatchLst($sportId){

			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelmatchmst');
			$this->load->model('Modelcreatemaster');
			$user_id = $this->globalUserId;

			$userSetting = $this->Modelcreatemaster->viewUserAcData($user_id);

        	if(!empty($userSetting[0]['match_stake'])){
        		$match = $userSetting[0]['match_stake'];
        		$session = $userSetting[0]['match_stake'];
        	}else{
        		$match = MATCH_STAKE_OPTION;	
        		$session = SESSION_STAKE_OPTION;
        	}

        	if(!empty($userSetting[0]['one_click_stake'])){
        		$oneclick = $userSetting[0]['one_click_stake'];
        	}else{
        		$oneclick = ONE_CLICK_STAKE_OPTION;	
        	}
        	
			$matchStacks = json_decode($match,true);
			$sessionStacks = json_decode($session,true);
			$oneClickStacks = json_decode($oneclick,true);

			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "match listing";
        	$response["match_stack"] = $matchStacks;
        	$response["session_stack"] = $sessionStacks;
        	$response["one_click_stack"] = $oneClickStacks;

        	if($sportId == 0 || $sportId == 4){

        		$dbMatches = $this->Modeleventlst->mobileGetFavouriteMatchLst(BETFAIR_SPORT_CRICKET,$user_id);

			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

			/*	$cricketUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.BETFAIR_SPORT_CRICKET;
				$matchesJson = $this->httpGet($cricketUrl);
				$matches = json_decode($matchesJson,true); */

			/*	echo $cricketUrl;
				echo $matchesJson;
				print_r($matches);die;  */

				$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}
			//	}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);
					$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 

				//	$dbMarkets = explode(',', $dbMatch['marketid']);
				//	$matched = array_intersect($marketIds,$dbMarkets);
				//	if(!empty($matched)){
						$filterMatches[] = $dbMatch;
				//	}
				}

        		$cricket = array();
	        	$cricket['sportname'] = 'Cricket';
	        	$cricket['SportId'] = BETFAIR_SPORT_CRICKET;
	        	$cricket['values'] = $filterMatches;
	        	$response["data"][] = $cricket;
        	}	

			if($sportId == 0 || $sportId == 2){

				$dbMatches = $this->Modeleventlst->mobileGetFavouriteMatchLst(BETFAIR_SPORT_TENNIS,$user_id);
			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

			/*	$tennisUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.BETFAIR_SPORT_TENNIS;
				$matchesJson = $this->httpGet($tennisUrl);
				$matches = json_decode($matchesJson,true); */

				$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}
			//	}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);
					$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 

				//	$dbMarkets = explode(',', $dbMatch['marketid']);
				//	$matched = array_intersect($marketIds,$dbMarkets);
				//	if(!empty($matched)){
						$filterMatches[] = $dbMatch;
				//	}
				}

	        	$tennis = array();
	        	$tennis['sportname'] = 'Tennis';
	        	$tennis['SportId'] = BETFAIR_SPORT_TENNIS;
	        	$tennis['values'] = $filterMatches;
	        	$response["data"][] = $tennis;
	        }
	        	
			if($sportId == 0 || $sportId == 1){

				$dbMatches = $this->Modeleventlst->mobileGetFavouriteMatchLst(BETFAIR_SPORT_SOCCER,$user_id);
			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

			/*	$soccerUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.BETFAIR_SPORT_SOCCER;
				$matchesJson = $this->httpGet($soccerUrl);
				$matches = json_decode($matchesJson,true); */

				$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}	
			//	}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);
					$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 
			//		$dbMarkets = explode(',', $dbMatch['marketid']);
				//	$matched = array_intersect($marketIds,$dbMarkets);
				//	if(!empty($matched)){
						$filterMatches[] = $dbMatch;
				//	}
				}

	        	$soccer = array();
	        	$soccer['sportname'] = 'Soccer';
	        	$soccer['SportId'] = BETFAIR_SPORT_SOCCER;
	        	$soccer['values'] = $filterMatches;
	        	$response["data"][] = $soccer;
        	}
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}


	


		function save_multi_bet(){

			$this->load->model('Betentrymodel');
			$this->load->model('Modeltblbets');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Modellstmaster');
			$userId = $this->globalUserId;
			
			$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;	
			$cricketJson = $this->httpGet($cricketUrl);
			$cricketArr = json_decode($cricketJson,ture);

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
			$soccerArr = json_decode($soccerJson,ture);
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
			$tennisArr = json_decode($tennisJson,ture);
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

		/*	print_r($matchArr);
			die; */

			$sessionUrl = BR_LIVE_SESSION_SOCKET_URL;	
			$sessionJson = $this->httpGet($sessionUrl);
			$sessionArr = json_decode($sessionJson,ture);

			if(!empty($sessionArr['message'])){
				foreach($sessionArr['message'] as $session){
					if(!empty($session['market_id']) && !empty($session['value']['session'])){
						$runners = array();
						foreach($session['value']['session'] as $runner){
							$selectionId = $runner['SelectionId'];
							$back = $runner['BackPrice1'];
							$lay = $runner['LayPrice1'];
							$runners[$selectionId] = array('back'=> $back,'lay'=>$lay);
						}
						$sessionMatchArr[$session['market_id']] = $runners;	
					}	
				}
			}

		/*	echo $sessionUrl;
			print_r($sessionArr);
			print_r($sessionMatchArr);die; */
			
			$multiData = $_POST;
			$returnData = array();
			foreach($multiData as $data){

				if($data['is_session_fancy']=='Y'){

					$data['loginId'] = $userId;
					$validate = $this->Betentrymodel->validateSessionSaveBet($data);

					if($data['ind_fancy_selection_id']){
						if(!empty($sessionMatchArr[$data['MarketId']])){
							$marketId = $data['MarketId'];
							$selectionId = $data['ind_fancy_selection_id'];
							$sessionVal = $sessionArr[$marketId];
							if(!empty($sessionVal[$selectionId])){
								$selectionVal = $sessionVal[$selectionId];
								if($data['OddValue']==1){
									if($selectionVal['back']['price'] === $data['OddsNumber']){
										
									}else{
										$validate = array('code' => 9 ,'error'=>true,'message' => 'Runs changed');
									}
								}else{
									if($selectionVal['lay']['price'] === $data['OddsNumber']){
									
									}else{
										$validate = array('code' => 9 ,'error'=>true,'message' => 'Runs changed');
									}
								}
							}
						}else{
							$validate = array('code' => 9 ,'error'=> true,'message' => 'Match not active');
						}
					}
				//	print_r($validate);die;	

					$tempData = array();
					if(!empty($validate)){

						$tempData['unique_id'] = $data['unique_id'];
						$tempData['result'] = $validate;

					}else{
						
						$chk=$this->Modellstmaster->ChkFancyOnBet($data['matchId'],$data['FancyID'],$data['SessInptYes'],$data['SessInptNo']);
						if ($chk[0]['resultV'] > 0) {
						    $condition = $this->Modellstmaster->mbdip_save_session_bet($data);
						    if($condition[0]['resultV']==0){
						    	$errorFlag = false;	
						    }else{
						    	$errorFlag = true;	
						    }
						    $response = array();
							$response["code"] = $condition[0]['resultV'];
							$response["error"] = $errorFlag;
			    			$response["message"] = $condition[0]['retMess'];

			    			$tempData['unique_id'] = $data['unique_id'];
							$tempData['result'] = $response;		    		
						}else{
							$response = array();
							$response["code"] = $chk[0]['resultV'];
							$response["error"] = true;
			        		$response["message"] = $chk[0]['retMess'];

			        		$tempData['unique_id'] = $data['unique_id'];
							$tempData['result'] = $response;
			        
						}

					}

					$returnData[] = $tempData;

				}else{

				$data['loginId'] = $userId;
				$data['UserTypeId'] = $this->globalUserType;
				$data['ApiVal'] = 0;

				if(!empty($matchArr[$data['MarketId']])){
					$marketId = $data['MarketId'];
					$selectionId = $data['selectionId'];
					$marketVal = $matchArr[$marketId];
					if(!empty($marketVal[$selectionId])){
						$selectionVal = $marketVal[$selectionId];
						if($data['isback']==0){
							if($selectionVal['back']['price'] == $data['priceVal']){
								$data['isMatched'] = 1;
							}else{
								$data['isMatched'] = 0;
							}
						}else{
							if($selectionVal['lay']['price'] == $data['priceVal']){
								$data['isMatched'] = 1;
							}else{
								$data['isMatched'] = 0;
							}
						}
					}
				}

			/*	print_r($data);
				die; */
				
				$response = array();
				
				$validate = $this->Betentrymodel->mobileValidateSaveBet($data);

				$tempData = array();
				if(!empty($validate)){

					$tempData['unique_id'] = $data['unique_id'];
					$tempData['result'] = $validate;

				}else{

					$condition=$this->Betentrymodel->mobileSave_bet($data);
				
					if ($condition[0]['resultV']==0) {
						$rvData['RunnerValue']=$this->Betentrymodel->sumOfOdds($data['MarketId'],$data['loginId'],$data['UserTypeId'],$data['matchId']);
						$response = array();
						$response["code"] = 0;
						$response["error"] = false;
		        		$response["message"] = $condition[0]['retMess'];
		        		$response["data"] = $rvData;

		        		$tempData['unique_id'] = $data['unique_id'];
						$tempData['result'] = $response;
		        		
					}else {
						$id = $condition[0]['resultV'];
						$this->Modeltblbets->deleteBet($id); 

						$response = array();
						$response["code"] = -6;
						$response["error"] = true;
		        		$response["message"] = $condition[0]['retMess'];

						$tempData['unique_id'] = $data['unique_id'];
						$tempData['result'] = $response;
					} 

				}
					$returnData[] = $tempData;
				}
				
			}

			$this->Modelcreatemaster->updateUserBalLiablity($userId);

			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($returnData));
			
		}

		function stake_setting(){	

		/*	$matchstake = array('one_click_stake'=>array(500,1000,1500,2000,2500));
			echo json_encode($matchstake); die; */

			$this->load->model('Modelcreatemaster');
		//	$this->verifyRequiredParams($_POST,array('match_stake'));	

			$userId = $this->globalUserId;
			$updateData['match_stake'] = json_encode($_POST['match_stake']);

			$isUpdated = $this->Modelcreatemaster->update($userId,$updateData);
			if ($isUpdated) {
				$response["code"] = 0;
				$response["error"] = false;
				$response["message"] = "Saved successfully";
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = ERROR_TRY_AGAIN;
				$response["error"] = true;
				$response["message"] = ERROR_TRY_AGAIN_MSG;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
			
		}


		function one_click_stake_setting(){	

			$this->load->model('Modelcreatemaster');
		//	$this->verifyRequiredParams($_POST,array('one_click_stake'));	

			$userId = $this->globalUserId;
			$updateData['one_click_stake'] = json_encode($_POST['one_click_stake']);

			$isUpdated = $this->Modelcreatemaster->update($userId,$updateData);
			if ($isUpdated) {
				$response["code"] = 0;
				$response["error"] = false;
				$response["message"] = "Saved successfully";
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = ERROR_TRY_AGAIN;
				$response["error"] = true;
				$response["message"] = ERROR_TRY_AGAIN_MSG;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
			
		}

		function get_stake_setting(){	

			$this->load->model('Modelcreatemaster');
			$userId = $this->globalUserId;

			$userSetting = $this->Modelcreatemaster->viewUserAcData($userId);

        	if(!empty($userSetting[0]['match_stake'])){
        		$match = $userSetting[0]['match_stake'];
        	}else{
        		$match = MATCH_STAKE_OPTION;	
        	}

        	if(!empty($userSetting[0]['one_click_stake'])){
        		$oneclick = $userSetting[0]['one_click_stake'];
        	}else{
        		$oneclick = ONE_CLICK_STAKE_OPTION;	
        	}
        	
			$matchStacks = json_decode($match,true);
			$oneClickStacks = json_decode($oneclick,true);

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Stack listing";
			$response["data"] = array('match_stake'=>$matchStacks,'one_click_stake'=>$oneClickStacks);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

		function confirm_bet(){	

			$this->load->model('Modelcreatemaster');
			$this->verifyRequiredParams($_POST,array('is_confirm_bet'));	

			$userId = $this->globalUserId;
			$updateData['is_confirm_bet'] = $_POST['is_confirm_bet'];

			$isUpdated = $this->Modelcreatemaster->update($userId,$updateData);
			if ($isUpdated) {
				$response["code"] = 0;
				$response["error"] = false;
				$response["message"] = "Saved successfully";
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = ERROR_TRY_AGAIN;
				$response["error"] = true;
				$response["message"] = ERROR_TRY_AGAIN_MSG;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
			
		}

}