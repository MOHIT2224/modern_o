<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Apicontroller extends CI_Controller {

		var $globalUserId;
		var $globalUserType;
		public $APP_KEY = BETFAIR_APP_KEY;

		function __construct() {

				header('Access-Control-Allow-Origin: *');

		        parent::__construct();

                $_POST = json_decode(file_get_contents('php://input'), true);

		        $node1=$this->session->userdata('user_id');

		        $this->load->model('Modelchkuser');
		       // if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		       $currentMethod = $this->router->method;
//		       $allowAuth = array('chkLoginUser','getMarketListing','matchLstIndianSessionPublic');
		       $allowAuth = array('chkLoginUser','matchLstIndianSessionPublic');
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

		function chkLoginUser(){

			$this->load->model('Modeltblconfig');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Chip_model');

			$response = array();
            $user_data=$this->Modelchkuser->chkAuthName();

			if ($user_data['iType']==0) {

				if($user_data['mstrid']==1){
					$getToken=$this->getACookie();
					$this->Modelcreatemaster->saveBetfairToken($getToken);
				}else{
					$getToken = $this->Modelcreatemaster->findBetfairToken();
				}

				$this->session->set_userdata('TokenId', $getToken);

				$data['User']['type'] = $user_data['usetype'];

				$data['User']['user_name'] = $user_data['mstruserid'];

				$data['User']['user_id'] = $user_data['mstrid'];

				$data['User']['betfair_session_token'] = $this->Modelcreatemaster->findBetfairToken();

				$data['User']['betfair_app_key'] = BETFAIR_APP_KEY;

			//	$data['User']['error'] = $user_data['iType'];

			//	$data['User']['message'] = $user_data['Msg'];

			//	$data['User']['ChangePas'] = $user_data['ChangePas'];

			//	$data['User']['TokenId'] = "yPAFq7YCIi/nVwwwGe1vr2IM/v+LtGxRvEhmHyzTbx8=";

			//	$data['User']['set_timeout'] = $user_data['set_timeout'];

			//	$data['User']['lgnstatus'] = $this->session->userdata('session_id');

			//	$data['User']['last_login_id'] = $this->session->userdata('last_login_id');

				$data['User']['mstrpassword'] = $user_data['mstrpassword'];

				$terms = $this->Modeltblconfig->find();
				
				$data['User']['terms_conditions'] = $terms[0]['terms_conditions'];

				$balance = $this->Chip_model->getLiability($user_data['mstrid']);

				$data['UserBalance']['Liability'] = $balance[0]['Liability'];
				$data['UserBalance']['Balance'] = $balance[0]['Balance'];

				$userSetting = $this->Modelcreatemaster->viewUserAcData($user_data['mstrid']);
				$data['UserSetting'] = $userSetting[0];

				$response["code"] = 0;
				$response["error"] = false;
        		$response["message"] = "successfully login";
        		$response["data"] = $data;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

			}else{

				$response["code"] = $user_data['iType'];
				$response["error"] = true;
        		$response["message"] = $user_data['Msg'];
        		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}

		}

		function getChipDataById()
		{
			$response = array();
			$this->load->model('Chip_model');
			$this->load->model('Betentrymodel');
			
			$userId1 = $this->globalUserId;
			$balance = $this->Chip_model->getLiability($userId1);
			
			$data['Liability'] = $balance[0]['Liability'];
			$data['Balance'] = $balance[0]['Balance'];
			$data['marqueMsg'] = $this->Betentrymodel->DisplayMsgOnHeader();
			
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "user balance";
        	$response["data"] = $data;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function changePassword(){			
			if ($_POST['newpassword']==$_POST['Renewpassword']) {
				$userId = $this->globalUserId;
				$query=$this->Modelcreatemaster->changeClientPassword($userId);
				if ($query) {
					$passwordData = array('mstrpassword' => sha1($_POST['newpassword']));
					$response["code"] = 0;
        			$response["error"] = false;
        			$response["message"] = "Password Change Successfully";
        			$response["data"] = $passwordData;
        			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}else{
					$response["code"] = ERROR_INVALID_PASSWORD;
        			$response["error"] = true;
        			$response["message"] = ERROR_INVALID_PASSWORD_MSG;
        			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}
			}else{
					$response["code"] = ERROR_PASSWORD_MATCH;
        			$response["error"] = true;
        			$response["message"] = ERROR_PASSWORD_MATCH_MSG;
        			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
		}

		function getUserMatchLst($sportId){

			$response = array();
			$this->load->model('Modeleventlst');

			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "match listing";

        	if($sportId == 0 || $sportId == 4){

        		$dbMatches = $this->Modeleventlst->mobileGetUserMatchLst(BETFAIR_SPORT_CRICKET);

			/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true); */

				$cricketUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.$sportId;
				$matchesJson = $this->httpGet($cricketUrl);
				$matches = json_decode($matchesJson,true);

				$marketIds = array();
				foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
						$marketIds[] = $match['id'];
				//	}
				}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$dbMarkets = explode(',', $dbMatch['marketid']);
					$matched = array_intersect($marketIds,$dbMarkets);
					if(!empty($matched)){
						$filterMatches[] = $dbMatch;
					}
				}

        		$cricket = array();
	        	$cricket['sportname'] = 'Cricket';
	        	$cricket['SportId'] = BETFAIR_SPORT_CRICKET;
	        	$cricket['values'] = $filterMatches;
	        	$response["data"][] = $cricket;
        	}	

			if($sportId == 0 || $sportId == 2){

				$dbMatches = $this->Modeleventlst->mobileGetUserMatchLst(BETFAIR_SPORT_TENNIS);
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true);
				$marketIds = array();
				foreach($betfairMatches as $bfmatch){
					if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
						$marketIds[] = $bfmatch['marketId'];	
					}
				}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$dbMarkets = explode(',', $dbMatch['marketid']);
					$matched = array_intersect($marketIds,$dbMarkets);
					if(!empty($matched)){
						$filterMatches[] = $dbMatch;
					}
				}

	        	$tennis = array();
	        	$tennis['sportname'] = 'Tennis';
	        	$tennis['SportId'] = BETFAIR_SPORT_TENNIS;
	        	$tennis['values'] = $filterMatches;
	        	$response["data"][] = $tennis;
	        }
	        	
			if($sportId == 0 || $sportId == 1){

				$dbMatches = $this->Modeleventlst->mobileGetUserMatchLst(BETFAIR_SPORT_SOCCER);
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
				$betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				$betfairMatches = json_decode($betfairMatchesJson,true);
				$marketIds = array();
				foreach($betfairMatches as $bfmatch){
					if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
						$marketIds[] = $bfmatch['marketId'];
					}	
				}

				$filterMatches = array();
				foreach($dbMatches as $dbMatch){
					$dbMarkets = explode(',', $dbMatch['marketid']);
					$matched = array_intersect($marketIds,$dbMarkets);
					if(!empty($matched)){
						$filterMatches[] = $dbMatch;
					}
				}

	        	$soccer = array();
	        	$soccer['sportname'] = 'Soccer';
	        	$soccer['SportId'] = BETFAIR_SPORT_SOCCER;
	        	$soccer['values'] = $filterMatches;
	        	$response["data"][] = $soccer;
        	}
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function getMarketLst($matchId){
			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelcreatemaster');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "market listing";
        	$data = $this->Modeleventlst->mobileGetMrktByMatchId($matchId);
        
        	if(!empty($data[0])){
        		$temp  = array();
        		$temp = array_merge($temp,$data[0]);
        		$marketArr = explode(',', $data[0]['marketid']);
        		$marketJson = json_encode($marketArr);
        		$backLayOdds = '{"marketIds":'.$marketJson.',"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}';

				$sessionToken = $this->Modelcreatemaster->findBetfairToken();

        		$matchArr = array($matchId);
        		$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchArr);
				$matchOdds = array();
				$marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $backLayOdds);

				$marketRunnerArr = json_decode($marketRunnerJson,true);
				$matchOdds['marketRunner'] = $marketRunnerArr;
				if(!empty($volumeLimit)){
					$matchOdds['volumeLimit'] = $volumeLimit;
				}
				$temp = array_merge($temp,$matchOdds);


				$selectionParams = '{"filter":{"marketIds":'.$marketJson.'},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}';
				$selection = array();
				$selectionJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $selectionParams);
				$selectionArr = json_decode($selectionJson,true);
				$selection['selection'] = $selectionArr;
				$temp = array_merge($temp,$selection);

				$response["data"] = $temp;
        	}
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		} 


		function getMarketListing($matchId){

			$userId = $this->globalUserId;

			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Modelmatchmst');

		/*	$checkMatchPermission = $this->Modelmatchmst->checkMatchPermission($userId,$matchId);

		//	var_dump($checkMatchPermission);

			if($checkMatchPermission){
				$response["code"] = ERROR_RECORD_NOT_ACTIVE;
				$response["error"] = true;
    			$response["message"] = "Match not active";
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				return false;
			}  */

			$userSetting = $this->Modelcreatemaster->viewUserAcData($userId);

        /*    echo 'Userid'.$this->globalUserId;
			print_r($userSetting);
			die; */

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
        	$response["message"] = "market listing";
        	$response["match_stack"] = $matchStacks;
        	$response["session_stack"] = $sessionStacks;
        	$response["one_click_stack"] = $oneClickStacks;
        	
        //	$data = $this->Modeleventlst->mobileGetMrktByMatchId($matchId);
        	$data = $this->Modeleventlst->mobileGetFavMrktByMatchId($matchId,$userId);
        	$resultDeclare = $this->Modelmatchmst->result($matchId);

        //	print_r($data);die;
        
        	if(!empty($data[0])){

        		$dbMatch = array('matchName'=>$data[0]['matchName'],'series_name'=>$data[0]['series_name'],'matchid'=>$data[0]['matchid'],'marketid'=>$data[0]['marketid'],'matchdate'=>$data[0]['MstDate'],'sportname'=>$data[0]['sportname'],'is_favourite'=>$data[0]['is_favourite'],'SportId'=>$data[0]['SportId']);

        		if($data[0]['SportId']==4){
        			$dbMatch['socket_url'] = $data[0]['cricket_socket_url'];
        			$dbMatch['sport_image'] = $data[0]['cricket_sport_image'];
				}elseif ($data[0]['SportId']==2) {
					$dbMatch['socket_url'] = $data[0]['tennis_socket_url'];
        			$dbMatch['sport_image'] = $data[0]['tennis_sport_image'];
				}elseif ($data[0]['SportId']==1) {
					$dbMatch['socket_url'] = $data[0]['soccer_socket_url'];
        			$dbMatch['sport_image'] = $data[0]['soccer_sport_image'];
				}

        		$temp  = array();
        		$temp = array_merge($temp,$dbMatch);
        		$marketArr = explode(',', $data[0]['marketid']);
        		$matchOddmarketId = $marketArr[0];
        		$marketJson = json_encode($marketArr);

        		$matchArr = array($matchId);
        		$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchArr);

        	/*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();

        		$backLayOdds = '{"marketIds":'.$marketJson.',"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}';

        		
				$matchOdds = array();
				$marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $backLayOdds);

				$marketRunnerArr = json_decode($marketRunnerJson,true);
				$matchOdds['marketRunner'] = $marketRunnerArr; */

			//	$matchOddsApi = $this->getMatchOdds($matchOddmarketId);

			//	print_r($matchOddsApi);die;

			/*	if(empty($matchOddsApi['result'])){
					$response["code"] = 1;
					$response["error"] = true;
        			$response["message"] = "Betting not allowed";
        			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				} 

				$matchOdds['marketRunner'] = $matchOddsApi; */
			//	$matchOdds['marketRunner'] = array();

				if(!empty($data[0]['runner_json'])){
						$runnerArr = json_decode($data[0]['runner_json'],true);
						$backArr = array();
						$backArr = $runnerArr[0]['back'];
						if(@$backArr[0]['price']=='--'){
							$matchOdds['runners'] = $runnerArr;
							$matchOdds['IsMatchDisable'] = true;
						}else{
							$matchOdds['runners'] = $runnerArr;
							$matchOdds['IsMatchDisable'] = true;
						}
				}else{
					$matchOdds['runners'] = array();
					$matchOdds['IsMatchDisable'] = true;
				}	

				if(!empty($volumeLimit)){
					$matchOdds['volumeLimit'] = $volumeLimit;
				}
				$temp = array_merge($temp,$matchOdds); 

			/*	$selectionParams = '{"filter":{"marketIds":'.$marketJson.'},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}';
				$selection = array();
				$selectionJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $selectionParams); 
				$selectionArr = json_decode($selectionJson,true);  */

			/*	$selectionArr = array('runners'=>array(array('selectionId'=>1,'runnerName'=>'India'),array('selectionId'=>2,'runnerName'=>'Pakistan'))); */
			//	$selection['selection'] = $selectionArr; 
				$selection['selection'] = array(); 
				$temp = array_merge($temp,$selection);

				
				$temp = array_merge($temp,array('result'=>$resultDeclare));

				$response["data"] = $temp;
        	}else{
        		$temp = array('marketid'=>'','volumeLimit'=>array(),'marketRunner'=>array(),'selection'=>array(),'result'=>$resultDeclare);	
        		$response["data"] = $temp;
        	}
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		} 



		function getBetfairSessionToken(){
			$response = array();
			$this->load->model('Modelcreatemaster');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "betfair session token";
        	$getToken = $this->getACookie();
			$this->Modelcreatemaster->saveBetfairToken($getToken);
			$data = array('betfair_session_token' => $getToken);
	        $response["data"] = $data;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		} 

		/**
		 * [getBackLaysOfMarketSelectionName description]
		 * params {"filter":{"marketIds":["1.144156561","1.144156560"]},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}
		 * @return [type] [description]
		 */
		function getBackLaysOfMarketSelectionName(){

			$this->load->model('Modelcreatemaster');
			
			if (!empty($_POST)){
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$params =  json_encode($_POST);
				$data = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $params);
				$this->output->set_content_type('application/json')->set_output(($data));
			}

		}


		/**
		 * [getBackLaysOfMarket description]
		 * params json  {"marketIds":["1.144156561","1.144156560"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}
		 * @return [type] [description]
		 */
		function getBackLaysOfMarket(){

			$this->load->model('Modelcreatemaster');
			
			if (!empty($_POST)){
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$params =  json_encode($_POST);
				$data = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $params);
				$this->output->set_content_type('application/json')->set_output(($data));
			}
		}

		function Save_bet(){
			
			$data = $_POST;
			$data['loginId'] = $this->globalUserId;
			$data['UserTypeId'] = $this->globalUserType;
			$data['ApiVal'] = 0;
			
			$this->load->model('Betentrymodel');

			$response = array();
			
			$validate = $this->Betentrymodel->mobileValidateSaveBet($data);

			if(!empty($validate)){
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($validate));
				return false;
			}
			
			$condition=$this->Betentrymodel->mobileSave_bet($data);
			
			if ($condition[0]['resultV']==0) {
				$rvData['RunnerValue']=$this->Betentrymodel->sumOfOdds($data['MarketId'],$data['loginId'],$data['UserTypeId'],$data['matchId']);
				$response["code"] = 0;
				$response["error"] = false;
        		$response["message"] = $condition[0]['retMess'];
        		$response["data"] = $rvData;
        		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else {
				$response["code"] = $condition[0]['resultV'];
				$response["error"] = true;
        		$response["message"] = $condition[0]['retMess'];
        		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			} 
		}

		function marketBets($matchId,$marketId){

			$userId = $this->globalUserId;

			$response = array();
			$this->load->model('Betentrymodel');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Matched/Unmatched bets listing";

        	$data = array();
			$data['matched'] = $this->Betentrymodel->mobileMarketMatchedBets($userId,$matchId,$marketId);
			$data['unmatched'] = $this->Betentrymodel->mobileMarketUnMatchedBets($userId,$matchId,$marketId);

	        $response["data"] = $data;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function deleteGetbetting($betId){

			$this->load->model('Betentrymodel');

			$response = array();
			$userId = $this->globalUserId;

			$condition=$this->Betentrymodel->deleteGetbetting($betId,$userId);
			
			if (isset($condition[0]['resultV']) && $condition[0]['resultV']==0) {
				$response["code"] = 0;
				$response["error"] = false;
	        	$response["message"] = "Record deleted Successfully...";
			}else{
				$response["code"] = ERROR_DELETE;
				$response["error"] = true;
	        	$response["message"] = ERROR_DELETE_MSG;
			}

			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

		}

		function updateUnMatchedData($betId){

				$response = array();
				$this->load->model('Betentrymodel');
				$loginId = $this->globalUserId;
				$userTypeId = $this->globalUserType;

				$condition=$this->Betentrymodel->updateUnMatchedData($betId);			
				if ($condition) {
//					$data['betUserData']=$this->Betentrymodel->getBetEntry($marketId,$userTypeId,$loginId,$matchId);
					$response["code"] = 0;
					$response["error"] = false;
        			$response["message"] = 'Bet matched successfully';
        		//	$response["data"] = $data;
				}else{
					$response["code"] = ERROR_TRY_AGAIN;
					$response["error"] = true;
        			$response["message"] = ERROR_TRY_AGAIN_MSG;
				}
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function getUserSetting(){
			
			$response = array();
			$this->load->model('Modelcreatemaster');
			$userId1 = $this->globalUserId;
			
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "User settings";
        	$userSetting = $this->Modelcreatemaster->viewUserAcData($userId1);
        	$response["data"] = $userSetting[0];
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function allUnmatchedBets(){

			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Betentrymodel');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "All unmatched bets listing";
			$data = $this->Betentrymodel->mobileAllUnMatchedBets($userId);

	        $response["data"] = $data;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function myBets($betType,$pageno,$limit){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Betentrymodel');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "My bets listing";
	        $response["data"] = $this->Betentrymodel->myBetsPaging($userId,$betType,$pageno,$limit);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function acStatement($pageno,$limit){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Betentrymodel');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Acount statement listing";
	        $response["data"] = $this->Betentrymodel->mbdip_acStatement($userId,$pageno,$limit);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function matchMarketLst($matchId){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Modeleventlst');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Session fancy listing";
	        $response["data"] = $this->Modeleventlst->mbdip_matchFancyList($matchId,$userId);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function market_win_loss(){
			
			$post = $_POST;

			$post['loginId'] = $this->globalUserId;
			$post['UserTypeId'] = $this->globalUserType;
			$post['ApiVal'] = 0;
			
			$this->load->model('Betentrymodel');

			$data = array();
			$marketIds = $post['MarketId'];
			$matchId = $post['matchId'];
			$marketArr = explode(',', $marketIds);
			
			foreach($marketArr as $key=>$marketId){
				$temp = array();
				$temp['marketId'] = $marketId;
				$runners = $this->Betentrymodel->sumOfOdds($marketId,$post['loginId'],$post['UserTypeId'],$matchId);
				$formatRunner = array();
				foreach($runners as $runner){
					$formatRunner[] = array('winValue'=>$runner['winValue'],'lossValue'=>$runner['lossValue'],'selectionId'=>$runner['SelectionId']);
				}
				$temp['runners'] = $formatRunner;
				$data[] = $temp;
			}

			$response["code"] = 0;
			$response["error"] = false;
    		$response["message"] = 'market profit and loss';
    		$response["data"] = $data;
    		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		function save_session_bet(){

			$this->load->model('Modellstmaster');
			$post = $_POST;
			$post['loginId'] = $this->globalUserId;
			$chk=$this->Modellstmaster->ChkFancyOnBet($post['matchId'],$post['FancyID'],$post['SessInptYes'],$post['SessInptNo']);
			if ($chk[0]['resultV'] > 0) {
			    $condition = $this->Modellstmaster->mbdip_save_session_bet($post);
				$response["code"] = 0;
				$response["error"] = false;
    			$response["message"] = $condition[0]['retMess'];
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = $chk[0]['resultV'];
				$response["error"] = true;
        		$response["message"] = $chk[0]['retMess'];
        		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
		}

		function GetScoreApi($matchId){
			$str = file_get_contents('https://www.betfair.com/inplayservice/v1/scores?_ak=nzIFcwyWhrlwYMrh&alt=json&eventIds='.$matchId.'&locale=en');
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output($str);
		}

		/**
		 * [getBackLaysOfMarketSetting description]
		 * params json  {"betfair":{"marketIds":["1.144156561","1.144156560"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"},"matchId":"28763538"}
		 * @return [type] [description]
		 */
		function getBackLaysOfMarketSetting(){

			$this->load->model('Modelcreatemaster');
			
			if (!empty($_POST)){
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$betfairParams = json_encode($_POST['betfair']);
				$marketId = $_POST['betfair']['marketIds'][0];
				$matchId = $_POST['matchId'];
				$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimit($matchId,$marketId);
				$data = array();
			/*	$marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $betfairParams);
				$marketRunnerArr = json_decode($marketRunnerJson,true);
				$data['marketRunner'] = $marketRunnerArr; */

				$data['marketRunner'] = $this->getMatchOdds($marketId);

				if(!empty($volumeLimit)){
					$data = array_merge($data,$volumeLimit[0]);
				}
				$this->output->set_content_type('application/json')->set_output((json_encode($data)));
			}
		}

		/**
		 * [getBackLaysOfMarketSetting description]
		 * params json  {"betfair":{"marketIds":["1.144156561","1.144156560"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"},"matchId":["28763538"]}
		 * @return [type] [description]
		 */
		function getBackLaysOfMarketMatchVolume(){

			$this->load->model('Modelcreatemaster');

			if (!empty($_POST)){
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				$betfairParams = json_encode($_POST['betfair']);
				$marketId = $_POST['betfair']['marketIds'][0];
				$matchId = $_POST['matchId'];
				$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchId);
				$data = array();
				$marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $betfairParams);
				$marketRunnerArr = json_decode($marketRunnerJson,true);
				$data['marketRunner'] = $marketRunnerArr;
				if(!empty($volumeLimit)){
					$data['volumeLimit'] = $volumeLimit;
				}
				$this->output->set_content_type('application/json')->set_output((json_encode($data)));
			}
		}

		/**
		 * [sessionBets session user bets listing]
		 * @param  [int] $fancyId [session fancy id]
		 * @return [json]          [resposne]
		 */
		function sessionBets($fancyId){

			$userId = $this->globalUserId;

			$response = array();
			$this->load->model('Betentrymodel');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Matched session fancy bets listing";

        	$data = array();
			$data['matched'] = $this->Betentrymodel->mobileSessionMatchedBets($userId,$fancyId);
			$data['unmatched'] = array();

	        $response["data"] = $data;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [GatBetData latest bet listing]
		 * @param [int] $matchId [match id]
		 */
		function GatBetData($matchId=null){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Betentrymodel');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Latest bet listing";
	        $response["data"] = $this->Betentrymodel->mbdip_getBetEntry($userId,$matchId);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [SetResult set match result]
		 */
		function SetResult(){

			$this->load->model('Modelmarket');
			$this->load->model('Modeltblselection');
			$this->load->model('Modeleventlst');
			$this->load->model('Modeltblbets');

/*			$post = array(array('marketId'=>1.144844337,'selectionId'=>266950),array('marketId'=>1.144844337,'selectionId'=>266950));

			echo json_encode($post);
			die; */

			$post = $_POST;

			foreach($post as $data){

				$marketId = $data['marketId'];
				$selectionId = $data['selectionId'];
				$marketData = $this->Modelmarket->findByMarketId($marketId);
				$selectionName = $this->Modeltblselection->findBySelectionName($selectionId);

				$marketData = array_merge($marketData,array('market_id'=>$marketId,'selectionId'=>$selectionId,'selectionName'=>$selectionName,'isFancy'=>1,'result'=>1));
				$matchId = $marketData['Match_id'];

				$condition = $this->Modeleventlst->SetMatchResult($marketData);

				if ($condition[0]['resultV']==0) {
					$this->Modeltblbets->updateUserBalByMatch($matchId,$marketId);
				}
			}  

			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Match result saved";
	        
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [get_indian_session get indian session]
		 * @return [json] [response]
		 */
		   function get_indian_session($matchId=null){

        $this->load->model('Modelmarket');
        $this->load->model('Modelmatchfancy');

        $response = array();
        //$matchData = $this->Modelmarket->getMatchByMarketId($matchId);

        //print_r($matchId);die();

        $matchMarket = $this->Modelmarket->findMarketIdByMatch($matchId);

        if(!empty($matchId)){

            $marketId = $matchMarket['marketId'];

            //	$getSessionOdds = $this->getIndFancyByMatchId($matchId);

            $sessOddArr = $this->getIndFancyAdmin($marketId);

            //	print_r($sessOddArr);die;

            //	$sessOddArr = json_decode($getSessionOdds,true);

            $activeSess = $this->Modelmatchfancy->addedSession($matchId);
            //echo "<pre>"; print_r($activeSess);die;
            $checkActive = array();
            $decliredSessionIds = [];

            foreach($activeSess as $aSess){
                $checkActive[] = $aSess['ind_fancy_selection_id'];
                if($aSess['result']!=''){
                    $decliredSessionIds[] = $aSess['ind_fancy_selection_id'];
                }
            }

            //	print_r($checkActive);
            $indianSession = array();
            if(!empty($sessOddArr['data']['session'])){
                foreach($sessOddArr['data']['session'] as $sessOdd){
                    if(!in_array($sessOdd['SelectionId'], $decliredSessionIds)){
                        if(in_array($sessOdd['SelectionId'], $checkActive)){
                            $sessOdd = array_merge($sessOdd,array('is_exists'=>1,'match_id'=>$matchId));
                        }else{
                            $sessOdd = array_merge($sessOdd,array('is_exists'=>0,'match_id'=>$matchId));
                        }
                        $indianSession[] = $sessOdd;
                    }

                }
                $response["code"] = 0;
                $response["error"] = false;
                $response["message"] = "Session fancy listing";
                $response["data"] = $indianSession;
            }else{
                $response["code"] = 1;
                $response["error"] = true;
                $response["message"] = 'Session fancy has not been created for this match';
            }
        }else{
            $response["code"] = 1;
            $response["error"] = true;
            $response["message"] = 'Please actived match odd market';
        }

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

    }

    /**
     * [save_indian_session save indian session]
     * params : {"SelectionId":"27","RunnerName":"FALL OF 1ST WKT HAMP ADV","LayPrice1":"125","LaySize1":"110","BackPrice1":"125","BackSize1":"95","GameStatus":"","FinalStatus":"OPEN","is_exists":0,"match_id":"28772000"}
     */
    function save_indian_session(){

        $post = $_POST;
        $data = array('super_admin_fancy_id'=>$post['super_admin_fancy_id'],'HeadName'=>$post['RunnerName'],'remarks'=>'INDIAN_SESSION_FANCY','mid'=>$post['match_id'],'fancyType'=>2,'date'=>date('Y-m-d'),'time'=>date('H:i'),'inputYes'=> $post['BackPrice1'],'inputNo'=> $post['LayPrice1'],'sid'=>4,'NoLayRange'=>$post['LaySize1'],'YesLayRange'=>$post['BackSize1'],'RateDiff'=>1,'MaxStake'=>10000000000000000,'PointDiff'=>10,'ind_fancy_selection_id'=>$post['SelectionId']);

        $this->load->model('Modelcreatemaster');
        $this->load->model('Modelmatchfancy');


        $chkSession = $this->Modelmatchfancy->checkSessionStatus($data['mid'],$data['ind_fancy_selection_id']);

        if(empty($chkSession)){

            $id = $this->Modelcreatemaster->mb_saveFancy($data);

            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $result = $this->Modelmatchfancy->selectFancyById($id);
                $redis->set($this->db->database.'ind_' . $post['match_id'] . '_' . $id, json_encode($result));
                $redis->close();
            } catch (Exception $e) {
            }
            if (!empty($result)) {
                $response["code"] = 0;
                $response["error"] = false;
                $response["message"] = 'Indian session saved successfully';
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }else{
                $response["code"] = 1;
                $response["error"] = true;
                $response["message"] = 'Error';
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }

        }else{
            $response["code"] = 1;
            $response["error"] = true;
            $response["message"] = 'Already exists';
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }

    }
		/**
		 * [matchLstIndianSessionPublic indian session match listing]
		 * @param  [int] $matchId [match id]
		 * @return [json]          [response]
		 */
		function matchLstIndianSessionPublic($matchId=0,$marketId=0){
			$response = array();
			$this->load->model('Modeleventlst');
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Session fancy listing";
			$sessionOdds = $this->Modeleventlst->matchFancyListPublicByMatchId($matchId);
			$response["data"] = $sessionOdds;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [matchLstIndianSession indian session match listing]
		 * @param  [int] $matchId [match id]
		 * @return [json]          [response]
		 */
		function matchLstIndianSession($matchId=0,$marketId=0){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelmatchfancy');
		//	$this->load->model('Modelmarket');
			
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Session fancy listing";

			//$getSessionOdds = $this->getIndianSessionOdds($marketId);
		/*	$getSessionOdds = $this->getSuperAdminFancyOdds($marketId);
			$sessOddArr = json_decode($getSessionOdds,true);

			$getSessPrice = array();
			foreach($sessOddArr['session'] as $sessOdd){					
				$getSessPrice[$sessOdd['SelectionId']] = array('SessInptYes'=> $sessOdd['BackPrice1'] ,'SessInptNo'=>$sessOdd['LayPrice1'],'GameStatus'=>$sessOdd['GameStatus']);
			}  */


		//	$sessionOdds = $this->Modeleventlst->mbdip_matchFancyList($matchId,$userId);
		//	print_r($sessionOdds);
			$sessionOdds = $this->Modelmatchfancy->getUserFancyList($userId,$matchId);
		//	print_r($sessionOdds);
		//	die;

		/*	$filterArr = array();
			foreach($sessionOdds as $sessionOdd){
				$mFancyId = $sessionOdd['ind_fancy_selection_id'];
			
				if($sessionOdd['is_indian_fancy'] == 1 && $sessionOdd['fancy_mode'] == 'A'){
					if(!empty($getSessPrice[$mFancyId])){

						if($getSessPrice[$mFancyId]['SessInptYes'] === 0 || $getSessPrice[$mFancyId]['SessInptNo'] === 0){
							$sessionOdd['SessInptYes'] = '';
							$sessionOdd['SessInptNo'] = '';
							$sessionOdd['DisplayMsg'] = 'Result awaiting';
							$sessionOdd['active'] = 4;
						}else{
							$sessionOdd['SessInptYes'] = $getSessPrice[$mFancyId]['SessInptYes'];
							$sessionOdd['SessInptNo'] = $getSessPrice[$mFancyId]['SessInptNo'];
							if(!empty($getSessPrice[$mFancyId]['GameStatus'])){
								$sessionOdd['DisplayMsg'] = $getSessPrice[$mFancyId]['GameStatus'];
								$sessionOdd['active'] = 4;
							}
						}

					}else{
						$sessionOdd['SessInptYes'] = '';
						$sessionOdd['SessInptNo'] = '';
						$sessionOdd['DisplayMsg'] = 'Result awaiting';
						$sessionOdd['active'] = 4;
					}
				}
				$filterArr[] = $sessionOdd;
			}  
		
			$response["data"] = $filterArr; */
			$response["data"] = $sessionOdds;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [matchLstAdminSession indian session match listing]
		 * @param  [int] $matchId [match id]
		 * @return [json]          [response]
		 */
		function matchLstAdminSession($matchId=0,$marketId=0){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Modeleventlst');
			
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Admin Session fancy listing";

        //	echo $matchId;
        //	echo 'UserID'.$userId; die;
			$sessionOdds = $this->Modeleventlst->mbdip_adminFancyList($matchId,$userId);
		
			$response["data"] = $sessionOdds;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [adminIndianSession indian session match listing]
		 * @param  [int] $matchId [match id]
		 * @return [json]          [response]
		 */
		function adminIndianSession($mFancyId,$marketId){
			$userId = $this->globalUserId;
			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelmarket');
			
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "Session fancy listing";
	        

			//$getSessionOdds = $this->getIndianSessionOdds($marketId);
			$getSessionOdds = $this->getSuperAdminFancyOdds($marketId);
			$sessOddArr = json_decode($getSessionOdds,true);

			$getSessPrice = array();
			foreach($sessOddArr['session'] as $sessOdd){	
				if($sessOdd['SelectionId'] == $mFancyId){
					$getSessPrice = array('HeadName'=>$sessOdd['RunnerName'],'SessInptYes'=> $sessOdd['BackPrice1'] ,'SessInptNo'=>$sessOdd['LayPrice1'],'GameStatus'=>$sessOdd['GameStatus'],'NoValume'=>$sessOdd['LaySize1'],'YesValume'=>$sessOdd['BackSize1'],'active'=>1);
					if(!empty($sessOdd['GameStatus'])){
						$getSessPrice['active'] = 4;
					}
				}
			}
		
			$response["data"] = $getSessPrice;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

		/**
		 * [updateFancyMode update fancy mode]
		 * @param [json] {"FancyId":"10","fancy_mode":"A"}
		 * @return [json] 
		 */
		function updateFancyMode(){

				$post = $_POST;
				$betId = $post['FancyId'];
				$udpateArr = array('fancy_mode'=>$post['fancy_mode']);

				$response = array();
				$this->load->model('Modelmatchfancy');

				$condition=$this->Modelmatchfancy->update($betId,$udpateArr);			
				if ($condition) {
					$response["code"] = 0;
					$response["error"] = false;
        			$response["message"] = 'fancy mode updated successfully';
				}else{
					$response["code"] = ERROR_TRY_AGAIN;
					$response["error"] = true;
        			$response["message"] = ERROR_TRY_AGAIN_MSG;
				}
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}


}