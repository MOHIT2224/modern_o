<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Mintmasterapicontroller extends CI_Controller {
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
		       $allowAuth = array('chkLoginUser');
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
			$data = array();
            $user_data=$this->Modelchkuser->chkAuthName();

			if ($user_data['iType']==0) {

				if($user_data['usetype']==1){

					/*	if($user_data['mstrid']==1){
					$getToken=$this->getACookie();
					$this->Modelcreatemaster->saveBetfairToken($getToken);
				}else{
					$getToken = $this->Modelcreatemaster->findBetfairToken();
				} */

			//	$this->session->set_userdata('TokenId', $getToken);

				$data['User']['type'] = $user_data['usetype'];

				$data['User']['user_name'] = $user_data['mstruserid'];

				$data['User']['user_id'] = $user_data['mstrid'];

			//	$data['User']['betfair_session_token'] = $this->Modelcreatemaster->findBetfairToken();

				$data['User']['betfair_session_token'] = '';

				$data['User']['betfair_app_key'] = BETFAIR_APP_KEY;

			//	$data['User']['error'] = $user_data['iType'];

			//	$data['User']['message'] = $user_data['Msg'];

				$data['User']['ChangePas'] = $user_data['ChangePas'];

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

					$response["code"] = ERROR_INVALID_USER_PASSWORD;
					$response["error"] = false;
	        		$response["message"] = ERROR_INVALID_USER_PASSWORD_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}

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


		function CheckUserName(){	

			$this->verifyRequiredParams($_POST,array('mstruserid'));		
			
			$userName = $_POST['mstruserid'];
			$check=$this->Modelcreatemaster->chkMasterUsername($userName);
			if ($check==0) {
				$response["code"] = 0;
    			$response["error"] = false;
    			$response["message"] = "Username available";
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = ERROR_USERNAME_EXISTS;
    			$response["error"] = true;
    			$response["message"] = ERROR_USERNAME_EXISTS_MSG;
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
			
		}

		

		/**
		 * [submitCreateMasterData create dealer api]
		 * @param [json] $[post] {"username":"dealer_1","master_name":"dealer_1","password":"123456","remarks":"testing","typeId":2,"parantId":"12","Commission":2,"sessionCommission":2,"otherCommission":2}
		 * @return [type] [description]
		 */
		function submitCreateDealerData(){

			$this->verifyRequiredParams($_POST,array('username','master_name','password'));		

			$validate = $this->Modelcreatemaster->validateSaveCreateMaster();

			if($validate['error']==1){
				$this->output->set_content_type('application/json')->set_output(json_encode($validate));
				return false;
			}

			$condition=$this->Modelcreatemaster->mb_saveCreateMaster($_POST);

			if ($_POST['typeId']==1) {
						$userType="Master ".$_POST['username'];
				}else if($_POST['typeId']==2){
						$userType="Dealer ".$_POST['username'];
				}elseif($_POST['typeId']==3){
						$userType="Client ".$_POST['username'];
				}
			if ($condition==1) {
				$response["code"] = 0;
    			$response["error"] = false;
    			$response["message"] = '['.$userType.'] Added Successfully...';
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else if($condition==2){
				$response["code"] = 1;
    			$response["error"] = true;
    			$response["message"] = '['.$userType.'] Already Exits...';
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else if ($condition==0) {
				$response["code"] = 1;
    			$response["error"] = true;
    			$response["message"] = '['.$userType.'] Not Added successfully...';
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
		}

		function getUserMatchResult(){
			$response = array();
			$this->load->model('Modeleventlst');
			
			$userId = $this->globalUserId;
			$userType = $this->globalUserType;
			$response["code"] = 0;
			$response["error"] = false;
        	$response["message"] = "master profit and loss listing";
        	$response["data"] = $this->Modeleventlst->mb_getUserMatchResult($userId,$userType);
        	$response["sockets"] = array(array('sportId'=>4,'socket_url'=> BR_LIVE_CRICKET_SOCKET_URL),array('sportId'=>2,'socket_url'=> BR_LIVE_TENNIS_SOCKET_URL),array('sportId'=>1,'socket_url'=> BR_LIVE_SOCCER_SOCKET_URL));
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
        		$matchOddmarketId = $marketArr[0];
        		$marketJson = json_encode($marketArr);

        	/*	$backLayOdds = '{"marketIds":'.$marketJson.',"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}';
 
				$sessionToken = $this->Modelcreatemaster->findBetfairToken(); */

        		$matchArr = array($matchId);
        		$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchArr);
				$matchOdds = array();

			//	$marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $backLayOdds);

			/*	$marketRunnerArr = $this->getMatchOdds($matchOddmarketId);

				if(empty($marketRunnerArr['result'])){
					$response["code"] = 1;
					$response["error"] = true;
        			$response["message"] = "Betting not allowed";
        			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				} */
				
				$matchOdds['marketRunner'] = array();
				if(!empty($volumeLimit)){
					$matchOdds['volumeLimit'] = $volumeLimit;
				}
				$temp = array_merge($temp,$matchOdds);


			//	$selectionParams = '{"filter":{"marketIds":'.$marketJson.'},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}';
				$selection = array();
			//	$selectionJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $selectionParams);
			//	$selectionArr = json_decode($selectionJson,true);
			//	$selection['selection'] = $selectionArr;
				$selection['selection'] = array();
				$temp = array_merge($temp,$selection);

				$response["data"] = $temp;
        	}
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		} 

		function GetScoreApi($matchId){
			$str = file_get_contents('https://www.betfair.com/inplayservice/v1/scores?_ak=nzIFcwyWhrlwYMrh&alt=json&eventIds='.$matchId.'&locale=en');
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output($str);
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
				$temp['id'] = $marketId;
				$runners = $this->Betentrymodel->sumOfOdds($marketId,$post['loginId'],$post['UserTypeId'],$matchId);
				$formatRunner = array();
				foreach($runners as $runner){
					$formatRunner[] = array('winValue'=>$runner['winValue'],'lossValue'=>$runner['lossValue'],'id'=>$runner['SelectionId']);
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
		
		
}