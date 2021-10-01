<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Apiadmincontroller extends CI_Controller {
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
		    /*   $allowAuth = array('chkLoginUser','getMarketListing','matchLstIndianSessionPublic');
		       if(!in_array($currentMethod, $allowAuth)){
					$this->checkAuthentication();
		       }  */
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

		function declareresult(){
			$response = array();
			$this->load->model('Modelmarket');
			$this->load->model('Modeltblselection');
			$this->load->model('Modeleventlst');
			$this->load->model('Modeltblbets');
			
        	$results = $this->Modelmarket->resultDeclareMarketId();

        	if(!empty($results)){
        		$marketIds = array();
        		$matchArr = array();
	        	foreach($results as $result){
	        		$marketId = $result['marketId'];
	        		$marketIds[] = $marketId;
	        		$matchArr[$marketId] = $result['matchName'];
	        	}
	        	$marketStr = implode(',', $marketIds);
	        	$resultUrl = BR_LIVE_RESULT_URL.'market_id='.$marketStr;
	        //	echo $resultUrl;die;
	        	$resultJson = $this->httpGet($resultUrl);
	        	$resultArr = json_decode($resultJson,true);

	        	$filterResult = array();
	        	$filterMatchResult = array();
	        	if(!empty($resultArr[0]['result'])){
	        		$finalResult = $resultArr[0]['result'];
	        		foreach($finalResult as $fResult){
	        			if($fResult['status'] == 'CLOSED'){
	        				$temp = array();
	        				$tempMatches = array();
	        				
	        				if(!empty($fResult['runners'])){
	        					foreach($fResult['runners'] as $runners){
	        						if($runners['status'] == 'WINNER'){
	        							$temp['selectionId'] = $runners['selectionId'];
	        						}		
	        					}
	        					if(!empty($temp['selectionId'])){
	        						$temp['marketId'] = $fResult['marketId'];
	        						$filterResult[] = $temp;
	        						$tempMatches['matchName'] =  $matchArr[$fResult['marketId']]; 
	        						$filterMatchResult[] = $tempMatches; 
	        					}
	        				}
	        				
	        			}
	        		}
	        	}

	        //	print_r($filterResult);die;

	        	foreach($filterResult as $data){
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

				if(!empty($filterMatchResult)){
					$response["code"] = 0;
					$response["error"] = false;
					$showMatches = array();
					foreach($filterMatchResult as $fmresult){
						$showMatches[] = $fmresult['matchName'];
					}
					$msg = "Following matches have been declared : ".implode(' , ' , $showMatches); 
        			$response["message"] = $msg;
        		/*	$filterMatchResult = array();
        			$filterMatchResult[] = array('matchName'=>'Eng Vs India');
        			$filterMatchResult[] = array('matchName'=>'India Vs Pakistan'); */
        			$response["data"] = $filterMatchResult;
				}else{
					$response["code"] = 1;
					$response["error"] = true;
        			$response["message"] = "No match result declared yet";
				} 
        	}else{
        		$response["code"] = 1;
				$response["error"] = true;
        		$response["message"] = "There are no match to declare a match";
        	}
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		} 

}