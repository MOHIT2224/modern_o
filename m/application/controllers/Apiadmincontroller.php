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

		function delete_match_profit_loss($matchId=0){

			$this->load->model('Modeltblchipdet');

			$response = array();
			$isUpdated = $this->Modeltblchipdet->updateByMatchId($matchId,array('is_deleted'=>'Y'));
			
			if ($isUpdated) {
				$response["code"] = 0;
				$response["error"] = false;
	        	$response["message"] = "Match deleted Successfully...";
			}else{
				$response["code"] = ERROR_DELETE;
				$response["error"] = true;
	        	$response["message"] = ERROR_DELETE_MSG;
			}

			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

		}

		/**
		 * [one_page_report description]
		 * @param  [type] $userId   [description]
		 * @param  [type] $fromDate [description]
		 * @param  [type] $toDate   [description]
		 * @param  [type] $type     [1=>'bet_history']
		 * @return [type]           [description]
		 */
		
		// {"user_id":15,"from_date":"2018-07-01","from_time":"01:00:00","to_date":"2018-08-28","to_time":"23:00:00","type":4,"page_no":1}

		function one_page_report(){	

			$userId=$_POST['user_id'];
			$fromDate=$_POST['from_date'];
			$fromTime=$_POST['from_time'];
			$toDate=$_POST['to_date'];
			$toTime=$_POST['to_time'];
			$type=$_POST['type'];
			$page_no=$_POST['page_no'];

			if(!empty($fromDate) && !empty($fromTime)){
				$fromDateTime = $fromDate.' '.$fromTime;
			}elseif(!empty($fromDate) && empty($fromTime)){
				$fromDateTime = $fromDate.' 00:00:00';
			}else{
				$fromDateTime = 0;
			}
			
			if(!empty($toDate) && !empty($toTime)){
				$toDateTime = $toDate.' '.$toTime;
			}elseif(!empty($toDate) && empty($toTime)){
				$toDateTime = $toDate.' 23:59:59';	
			}else{
				$toDateTime = 0;
			}	

			$pageLimit = ONE_REPORT_PAGING_LIMIT;
			$this->load->model('Betentrymodel');
			
			$loginUserId = $this->globalUserId;
			$data = array();
			if($type==1){
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no);
				$data = $this->Betentrymodel->myBetsFilters($params);
				$totalCount = $this->Betentrymodel->myBetsFiltersCount($params);
				$recordCount = $totalCount[0]['cnt'];
				$response["tot_p_l"] = $totalCount[0]['tot_p_l'];
				$response["tot_profit"] = $totalCount[0]['tot_profit'];
				$response["tot_liability"] = $totalCount[0]['tot_liability'];
			}elseif($type==2){
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no);
				$data = $this->Betentrymodel->mbdip_profit_loss($params);
				$totalCount = $this->Betentrymodel->mbdip_profit_loss_count($params);
				$recordCount = $totalCount[0]['cnt'];
				$response["tot_PnL"] = $totalCount[0]['PnL'];
				$response["tot_Comm"] = $totalCount[0]['Comm'];
			}elseif($type==3){
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>15000,'page_no'=>$page_no);
				$data = $this->Betentrymodel->AcStatementFilter($params);
				$totalCount = $this->Betentrymodel->AcStatementFilterCount($params);
			//	print_r($totalCount);die;
			//	$totalCount = 1;
				$recordCount = 1;
				$response["tot_credit"] = $totalCount[0]['tot_credit'];
				$response["tot_debit"] = $totalCount[0]['tot_debit'];
				$response["tot_balance"] = $totalCount[0]['tot_credit'] + $totalCount[0]['tot_debit'];
			}elseif($type==4){
				$this->load->model('Modeluserlogged');
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no);
				$data = $this->Modeluserlogged->findByUserId($params);
				$totalCount = $this->Modeluserlogged->findByUserIdCount($params);
				$recordCount = $totalCount[0]['cnt'];
			}

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Reports listing";
			$response["data"] = $data;
			$response["count"] = $recordCount;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

		/**
		 * [one_page_report_export description]
		 * @param  [type] $userId   [description]
		 * @param  [type] $fromDate [description]
		 * @param  [type] $toDate   [description]
		 * @param  [type] $type     [1=>'bet_history']
		 */
		// {"user_id":15,"from_date":"2018-08-28","from_time":"01:00:00","to_date":"2018-08-28","to_time":"23:00:00","type":1,"page_no":1}
		function one_page_report_export($userId=null,$fromDate=NULL,$toDate=NULL,$type=NULL){

			$userId=$_POST['user_id'];
			$fromDate=$_POST['from_date'];
			$fromTime=$_POST['from_time'];
			$toDate=$_POST['to_date'];
			$toTime=$_POST['to_time'];
			$type=$_POST['type'];

			if(!empty($fromDate) && !empty($fromTime)){
				$fromDateTime = $fromDate.' '.$fromTime;
			}elseif(!empty($fromDate) && empty($fromTime)){
				$fromDateTime = $fromDate.' 00:00:00';
			}else{
				$fromDateTime = 0;
			}
			
			if(!empty($toDate) && !empty($toTime)){
				$toDateTime = $toDate.' '.$toTime;
			}elseif(!empty($toDate) && empty($toTime)){
				$toDateTime = $toDate.' 23:59:59';	
			}else{
				$toDateTime = 0;
			}	

			$page_no = 1;
			$pageLimit = 9000;
			$this->load->model('Betentrymodel');
			
			$loginUserId = $this->globalUserId;
			$data = array();
			if($type==1){
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no);
				$data = $this->Betentrymodel->myBetsFilters($params);
				$totalCount = $this->Betentrymodel->myBetsFiltersCount($params);
				$recordCount = $totalCount[0]['cnt'];
			}elseif($type==2){
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no);
				$data = $this->Betentrymodel->mbdip_profit_loss($params);
				$totalCount = $this->Betentrymodel->mbdip_profit_loss_count($params);
				$recordCount = $totalCount[0]['cnt'];
			}elseif($type==3){
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>15000,'page_no'=>$page_no);
				$data = $this->Betentrymodel->AcStatementFilter($params);
			//	$totalCount = $this->Betentrymodel->AcStatementFilterCount($params);
				$totalCount = 1;
				$recordCount = 1;
			}elseif($type==4){
				$this->load->model('Modeluserlogged');
				$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no);
				$data = $this->Modeluserlogged->findByUserId($params);
				$totalCount = $this->Modeluserlogged->findByUserIdCount($params);
				$recordCount = $totalCount[0]['cnt'];
			}

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Reports listing";
			$response["data"] = $data;
			$response["count"] = $recordCount;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

		/**
		 * [one_page_report description]
		 * @param  [type] $userId   [description]
		 * @param  [type] $fromDate [description]
		 * @param  [type] $toDate   [description]
		 * @param  [type] $type     [1=>'bet_history']
		 * @return [type]           [description]
		 */
		


		function one_page_report_pl($userId=null,$fromDate=NULL,$toDate=NULL,$sportId=NULL,$page_no=1){	
			$userId=$_POST['user_id'];
			$fromDate=$_POST['from_date'];
			$fromTime=$_POST['from_time'];
			$toDate=$_POST['to_date'];
			$toTime=$_POST['to_time'];
			$sportId=$_POST['sport_id'];
			$page_no=$_POST['page_no'];

			if(!empty($fromDate) && !empty($fromTime)){
				$fromDateTime = $fromDate.' '.$fromTime;
			}elseif(!empty($fromDate) && empty($fromTime)){
				$fromDateTime = $fromDate.' 00:00:00';
			}else{
				$fromDateTime = 0;
			}
			
			if(!empty($toDate) && !empty($toTime)){
				$toDateTime = $toDate.' '.$toTime;
			}elseif(!empty($toDate) && empty($toTime)){
				$toDateTime = $toDate.' 23:59:59';	
			}else{
				$toDateTime = 0;
			}	

			$pageLimit = DEFAULT_PAGING_LIMIT;
			$this->load->model('Betentrymodel');
			$loginUserId = $this->globalUserId;
			$data = array();

			$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no,'sport_id'=>$sportId);
			$data = $this->Betentrymodel->mbdip_profit_loss($params);
			$totalCount = $this->Betentrymodel->mbdip_profit_loss_count($params);
			$recordCount = $totalCount[0]['cnt'];
			$response["tot_PnL"] = $totalCount[0]['PnL'];
			$response["tot_Comm"] = $totalCount[0]['Comm'];

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Reports listing";
			$response["data"] = $data;
			$response["count"] = $recordCount;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

		/**
		 * [one_page_report description]
		 * @param  [type] $userId   [description]
		 * @param  [type] $fromDate [description]
		 * @param  [type] $toDate   [description]
		 * @param  [type] $type     [1=>'bet_history']
		 * @return [type]           [description]
		 */
		function one_page_report_pl_export($userId=null,$fromDate=NULL,$toDate=NULL,$sportId=NULL){	

			$userId=$_POST['user_id'];
			$fromDate=$_POST['from_date'];
			$fromTime=$_POST['from_time'];
			$toDate=$_POST['to_date'];
			$toTime=$_POST['to_time'];
			$sportId=$_POST['sport_id'];

			if(!empty($fromDate) && !empty($fromTime)){
				$fromDateTime = $fromDate.' '.$fromTime;
			}elseif(!empty($fromDate) && empty($fromTime)){
				$fromDateTime = $fromDate.' 00:00:00';
			}else{
				$fromDateTime = 0;
			}
			
			if(!empty($toDate) && !empty($toTime)){
				$toDateTime = $toDate.' '.$toTime;
			}elseif(!empty($toDate) && empty($toTime)){
				$toDateTime = $toDate.' 23:59:59';	
			}else{
				$toDateTime = 0;
			}	

			
			$page_no = 1;
			$pageLimit = 9000;
			$this->load->model('Betentrymodel');
			$loginUserId = $this->globalUserId;
			$data = array();

			$params = array('user_id'=>$userId,'from_date'=>$fromDateTime,'to_date'=>$toDateTime,'page_limit'=>$pageLimit,'page_no'=>$page_no,'sport_id'=>$sportId);
			$data = $this->Betentrymodel->mbdip_profit_loss($params);
			$totalCount = $this->Betentrymodel->mbdip_profit_loss_count($params);
			$recordCount = $totalCount[0]['cnt'];

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Reports listing";
			$response["data"] = $data;
			$response["count"] = $recordCount;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

	    function get_all_matches(){
             $this->load->model('Modelmatchmst');
			//$url = BR_SUPER_AMDIN_URL."getAllMatches";
			$url = "http://109.74.202.195/api/v1/betting_api/getAllMatches.php";
		    $matchResult = $this->httpGet($url);
		    $data = json_decode($matchResult,true);

		    /*foreach ($data as $rec){
		        if(in_array($rec->))
            }*/

            $supperAdminMatchIds = $this->Modelmatchmst->getAcriveMatchIds();
            $supperAdminSeriesIds = $this->Modelmatchmst->getActiveSeriesIds();

            $temp =[];
            foreach ($data['cricket'] as $cricket){
                if(in_array($cricket['SeriesId'],$supperAdminSeriesIds)){
                    $cricket['active'] = in_array($cricket['eventId'],$supperAdminMatchIds) ? 1:0;
                    $temp['cricket'][] = $cricket;
                    $temppp[]=$cricket['eventId'];
                }

            }

            foreach ($data['soccer'] as $soccer){
                if(in_array($soccer['SeriesId'],$supperAdminSeriesIds)) {
                    $soccer['active'] = in_array($soccer['eventId'], $supperAdminMatchIds) ? 1 : 0;
                    $temp['soccer'][] = $soccer;
                }
            }
            foreach ($data['tennis'] as $tennis){
                if(in_array($tennis['SeriesId'],$supperAdminSeriesIds)) {
                    $tennis['active'] = in_array($tennis['eventId'], $supperAdminMatchIds) ? 1 : 0;
                    $temp['tennis'][] = $tennis;
                }
            }

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "match listing";
			$response["data"] = $temp;
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}

}