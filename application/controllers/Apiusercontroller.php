<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Apiusercontroller extends CI_Controller {
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

		function getUserFavouriteMatchLst($sportId,$seriesId=0) {

			$marketIds = array();
			$cricketIds = array();
			$tennisIds = array();
			$soccerIds = array();
			$allMarketIds = array();
			$response = array();
			$this->load->model('Modeleventlst');
			$this->load->model('Modelmatchmst');
			$this->load->model('Modelcreatemaster');
            $this->load->model('Betentrymodel');
			$user_id = $this->globalUserId;
            $user_type = $this->globalUserType;

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
            $devicetype = $this->input->request_headers();
			$response["code"] = 0;
            $response["data"]=[];
			$response["error"] = false;
        	$response["message"] = "match listing";
        	$response["match_stack"] = $matchStacks;
        	$response["session_stack"] = $sessionStacks;
        	$response["one_click_stack"] = $oneClickStacks;

        	

        	if($sportId == 0 || $sportId == 4){

        		$dbMatches = $this->Modeleventlst->mobileGetUserFavouriteMatchLst(BETFAIR_SPORT_CRICKET,$user_id,$seriesId);

				$filterMatches = array();
				foreach($dbMatches as $cricketDbMatch){
					$dbMatch = array('matchName'=>$cricketDbMatch['matchName'],'series_name'=>$cricketDbMatch['series_name'],'matchid'=>$cricketDbMatch['matchid'],'marketid'=>$cricketDbMatch['marketid'],'max_bet_liability'=>$cricketDbMatch['max_bet_liability'],'max_market_liability'=>$cricketDbMatch['max_market_liability'],'visibility'=>$cricketDbMatch['visibility'],'matchdate'=>$cricketDbMatch['MstDate'],'sportname'=>$cricketDbMatch['sportname'],'is_favourite'=>$cricketDbMatch['is_favourite'],'SportId'=>$cricketDbMatch['SportId'],'socket_url'=>$cricketDbMatch['socket_url'],'sport_image'=>$cricketDbMatch['sport_image'],'is_manual'=>$cricketDbMatch['is_manual'],'isfancy'=>$cricketDbMatch['isfancy'],'isBetAllowedOnManualMatchOdds'=>$cricketDbMatch['isBetAllowedOnManualMatchOdds'],'day'=>$cricketDbMatch['day']);
					$cricketIds[] = $dbMatch['marketid'];
					$allMarketIds[] = $dbMatch['marketid'];
					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);

					if($dbMatch['is_manual']==0){
                        if(!empty($cricketDbMatch['runner_json'])){
                            $runnerArr = json_decode($cricketDbMatch['runner_json'],true);
                            $backArr = array();
                            $backArr = @$runnerArr[0]['back'];
                            if(@$backArr[0]['price']=='--'){
                                $dbMatch['runners'] = $runnerArr;
                                $dbMatch['IsMatchDisable'] = true;
                            }else{
                                $dbMatch['runners'] = $runnerArr;
                                $dbMatch['IsMatchDisable'] = true;
                            }
                        }else{
                            $dbMatch['runners'] = array();
                            $dbMatch['IsMatchDisable'] = true;
                        }
                    }
                    else{
                        $response["isManualMatchOdds"] = 1;
                        $runner = [];
                        $runnerArray = json_decode($cricketDbMatch['runner_json'],true);

                        $manualMatchOddsDetails = $this->Modelcreatemaster->manualMatchOddsDetails($dbMatch['marketid']);

                        if(!empty($runnerArray)){
                            foreach ($runnerArray as $key=>$runnerRec){
                                $key = $key+1;
                                if($key <= 2){
                                    $runner[]=['selectionId'=>$runnerRec['id'],'id'=>$runnerRec['id'],'name'=>$runnerRec['name'],'back'=>[['price'=>$manualMatchOddsDetails['team'.$key.'_back']-1,'size'=>'--']],'lay'=>[['price'=>$manualMatchOddsDetails['team'.$key.'_lay']-1,'size'=>'--']],'active'=>$manualMatchOddsDetails['active_team'.$key]];
                                }else{
                                    $runner[]=['selectionId'=>$runnerRec['id'],'id'=>$runnerRec['id'],'name'=>$runnerRec['name'],'back'=>[['price'=>$manualMatchOddsDetails['draw_back']-1,'size'=>'--']],'lay'=>[['price'=>$manualMatchOddsDetails['draw_lay']-1,'size'=>'--']],'active'=>$manualMatchOddsDetails['active_draw']];
                                }
                            }
                        }
                        if($manualMatchOddsDetails['isManualMatchOdds']==1){
                            $dbMatch['is_active'] = 1;
                        }else{
                            $dbMatch['is_active'] = 0;
                        }
                        $dbMatch['runners']= $runner;
                    }


					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare;

                    $temp = array();
                    $temp['marketId'] = $dbMatch['marketid'];


                    if((!empty($devicetype['inplay']) &&  in_array($dbMatch['marketid'],explode(',',$devicetype['inplay']))) or !empty($devicetype['devicetype']) ) {
                        $runners = $this->Betentrymodel->sumOfOdds($dbMatch['marketid'], $user_id, $user_type, $dbMatch['matchid']);
                        $formatRunner = array();
                        foreach ($runners as $runner) {
                            $formatRunner[] = array('winValue' => $runner['winValue'], 'lossValue' => $runner['lossValue'], 'selectionId' => $runner['SelectionId']);
                        }
                        $temp['runners'] = $formatRunner;
                        $dbMatch['win_loss'] = $temp;
                    }else{
                        $temp['runners'] = array();
                        $dbMatch['win_loss'] = (object)array();
                    }

                    $filterMatches[] = $dbMatch;

				}

                    $cricket = array();
                    $cricket['sportname'] = 'Cricket';
                    $cricket['SportId'] = BETFAIR_SPORT_CRICKET;
                    $cricket['values'] = $filterMatches;
                    $response["data"][] = $cricket;

        	}	

			if($sportId == 0 || $sportId == 2){

				$dbMatches = $this->Modeleventlst->mobileGetUserFavouriteMatchLst(BETFAIR_SPORT_TENNIS,$user_id,$seriesId);

				$filterMatches = array();
				foreach($dbMatches as $tennisDbMatch){
					$dbMatch = array('matchName'=>$tennisDbMatch['matchName'],'series_name'=>$tennisDbMatch['series_name'],'matchid'=>$tennisDbMatch['matchid'],'marketid'=>$tennisDbMatch['marketid'],'max_bet_liability'=>$tennisDbMatch['max_bet_liability'],'max_market_liability'=>$tennisDbMatch['max_market_liability'],'visibility'=>$tennisDbMatch['visibility'],'matchdate'=>$tennisDbMatch['MstDate'],'sportname'=>$tennisDbMatch['sportname'],'is_favourite'=>$tennisDbMatch['is_favourite'],'SportId'=>$tennisDbMatch['SportId'],'socket_url'=>$tennisDbMatch['socket_url'],'sport_image'=>$tennisDbMatch['sport_image'],'is_active'=>$tennisDbMatch['active'],'is_manual'=>$tennisDbMatch['is_manual'],'isfancy'=>$tennisDbMatch['isfancy'],'isBetAllowedOnManualMatchOdds'=>$tennisDbMatch['isBetAllowedOnManualMatchOdds'],'day'=>$tennisDbMatch['day']);
					$tennisIds[] = $dbMatch['marketid'];
					$allMarketIds[] = $dbMatch['marketid'];

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);

                    if($dbMatch['is_manual']==0){
                        if(!empty($tennisDbMatch['runner_json'])){
                            $runnerArr = json_decode($tennisDbMatch['runner_json'],true);
                            $backArr = array();
                            $backArr = @$runnerArr[0]['back'];
                            if(@$backArr[0]['price']=='--'){
                                $dbMatch['runners'] = $runnerArr;
                                $dbMatch['IsMatchDisable'] = true;
                            }else{
                                $dbMatch['runners'] = $runnerArr;
                                $dbMatch['IsMatchDisable'] = true;
                            }
                        }else{
                            $dbMatch['runners'] = array();
                            $dbMatch['IsMatchDisable'] = true;
                        }
                    }
                    else{
                        $response["isManualMatchOdds"] = 1;

                        $runner = [];
                        $runnerArray = json_decode($tennisDbMatch['runner_json'],true);

                        $manualMatchOddsDetails = $this->Modelcreatemaster->manualMatchOddsDetails($dbMatch['marketid']);

                        if(!empty($runnerArray)){
                            foreach ($runnerArray as $key=>$runnerRec){
                                $key = $key+1;
                                if($key <= 2){
                                    $runner[]=['selectionId'=>$runnerRec['id'],'id'=>$runnerRec['id'],'name'=>$runnerRec['name'],'back'=>[['price'=>$manualMatchOddsDetails['team'.$key.'_back']-1,'size'=>'--']],'lay'=>[['price'=>$manualMatchOddsDetails['team'.$key.'_lay']-1,'size'=>'--']],'active'=>$manualMatchOddsDetails['active_team'.$key]];
                                }else{
                                    $runner[]=['selectionId'=>$runnerRec['id'],'id'=>$runnerRec['id'],'name'=>$runnerRec['name'],'back'=>[['price'=>$manualMatchOddsDetails['draw_back']-1,'size'=>'--']],'lay'=>[['price'=>$manualMatchOddsDetails['draw_lay']-1,'size'=>'--']],'active'=>$manualMatchOddsDetails['active_draw']];
                                }
                            }
                        }
                        if($manualMatchOddsDetails['isManualMatchOdds']==1){
                            $dbMatch['is_active'] = 1;
                        }else{
                            $dbMatch['is_active'] = 0;
                        }
                        $dbMatch['runners']= $runner;
                    }

					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare;

                    $temp = array();
                    $temp['marketId'] = $dbMatch['marketid'];

                    if((!empty($devicetype['inplay']) &&  in_array($dbMatch['marketid'],explode(',',$devicetype['inplay']))) or !empty($devicetype['devicetype']) ) {
                        $runners = $this->Betentrymodel->sumOfOdds($dbMatch['marketid'], $user_id, $user_type, $dbMatch['matchid']);

                        $formatRunner = array();
                        foreach ($runners as $runner) {
                            $formatRunner[] = array('winValue' => $runner['winValue'], 'lossValue' => $runner['lossValue'], 'selectionId' => $runner['SelectionId']);
                        }
                        $temp['runners'] = $formatRunner;
                        $dbMatch['win_loss'] = $temp;
                    }else{
                        $temp['runners'] = array();
                        $dbMatch['win_loss'] = (object)array();
                    }

						$filterMatches[] = $dbMatch;

				}

                    $tennis = array();
                    $tennis['sportname'] = 'Tennis';
                    $tennis['SportId'] = BETFAIR_SPORT_TENNIS;
                    $tennis['values'] = $filterMatches;
                    $response["data"][] = $tennis;


	        }
	        	
			if($sportId == 0 || $sportId == 1){

				$dbMatches = $this->Modeleventlst->mobileGetUserFavouriteMatchLst(BETFAIR_SPORT_SOCCER,$user_id,$seriesId);


				$filterMatches = array();
				foreach($dbMatches as $soccerDbMatch){
					$dbMatch = array('matchName'=>$soccerDbMatch['matchName'],'series_name'=>$soccerDbMatch['series_name'],'matchid'=>$soccerDbMatch['matchid'],'marketid'=>$soccerDbMatch['marketid'],'max_bet_liability'=>$soccerDbMatch['max_bet_liability'],'max_market_liability'=>$soccerDbMatch['max_market_liability'],'visibility'=>$soccerDbMatch['visibility'],'matchdate'=>$soccerDbMatch['MstDate'],'sportname'=>$soccerDbMatch['sportname'],'is_favourite'=>$soccerDbMatch['is_favourite'],'SportId'=>$soccerDbMatch['SportId'],'socket_url'=>$soccerDbMatch['socket_url'],'sport_image'=>$soccerDbMatch['sport_image'],'is_manual'=>$soccerDbMatch['is_manual'],'isfancy'=>$soccerDbMatch['isfancy'],'isBetAllowedOnManualMatchOdds'=>$soccerDbMatch['isBetAllowedOnManualMatchOdds'],'day'=>$soccerDbMatch['day']);
					$soccerIds[] = $dbMatch['marketid'];
					$allMarketIds[] = $dbMatch['marketid'];

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);

                    if($dbMatch['is_manual']==0){
                        if(!empty($soccerDbMatch['runner_json'])){
                            $runnerArr = json_decode($soccerDbMatch['runner_json'],true);
                            $backArr = array();
                            $backArr = @$runnerArr[0]['back'];
                            if(@$backArr[0]['price']=='--'){
                                $dbMatch['runners'] = $runnerArr;
                                $dbMatch['IsMatchDisable'] = true;
                            }else{
                                $dbMatch['runners'] = $runnerArr;
                                $dbMatch['IsMatchDisable'] = true;
                            }
                        }else{
                            $dbMatch['runners'] = array();
                            $dbMatch['IsMatchDisable'] = true;
                        }
                    }
                    else{
                        $response["isManualMatchOdds"] = 1;
                        $runner = [];
                        $runnerArray = json_decode($soccerDbMatch['runner_json'],true);

                        $manualMatchOddsDetails = $this->Modelcreatemaster->manualMatchOddsDetails($dbMatch['marketid']);

                        if(!empty($runnerArray)){
                            foreach ($runnerArray as $key=>$runnerRec){
                                $key = $key+1;
                                if($key <= 2){
                                    $runner[]=['selectionId'=>$runnerRec['id'],'id'=>$runnerRec['id'],'name'=>$runnerRec['name'],'back'=>[['price'=>$manualMatchOddsDetails['team'.$key.'_back']-1,'size'=>'--']],'lay'=>[['price'=>$manualMatchOddsDetails['team'.$key.'_lay']-1,'size'=>'--']],'active'=>$manualMatchOddsDetails['active_team'.$key]];
                                }else{
                                    $runner[]=['selectionId'=>$runnerRec['id'],'id'=>$runnerRec['id'],'name'=>$runnerRec['name'],'back'=>[['price'=>$manualMatchOddsDetails['draw_back']-1,'size'=>'--']],'lay'=>[['price'=>$manualMatchOddsDetails['draw_lay']-1,'size'=>'--']],'active'=>$manualMatchOddsDetails['active_draw']];
                                }
                            }
                        }
                        if($manualMatchOddsDetails['isManualMatchOdds']==1){
                            $dbMatch['is_active'] = 1;
                        }else{
                            $dbMatch['is_active'] = 0;
                        }
                        $dbMatch['runners']= $runner;
                    }
					
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare;


					$temp = array();
                    $temp['marketId'] = $dbMatch['marketid'];

                    if((!empty($devicetype['inplay']) &&  in_array($dbMatch['marketid'],explode(',',$devicetype['inplay']))) or !empty($devicetype['devicetype']) ) {
                        $runners = $this->Betentrymodel->sumOfOdds($dbMatch['marketid'], $user_id, $user_type, $dbMatch['matchid']);

                        $formatRunner = array();
                        foreach ($runners as $runner) {
                            $formatRunner[] = array('winValue' => $runner['winValue'], 'lossValue' => $runner['lossValue'], 'selectionId' => $runner['SelectionId']);
                        }
                        $temp['runners'] = $formatRunner;
                        $dbMatch['win_loss'] = $temp;
                    }else{
                        $temp['runners'] = array();
                        $dbMatch['win_loss'] = (object)array();
                    }

						$filterMatches[] = $dbMatch;

				}

                    $soccer = array();
                    $soccer['sportname'] = 'Soccer';
                    $soccer['SportId'] = BETFAIR_SPORT_SOCCER;
                    $soccer['values'] = $filterMatches;
                    $response["data"][] = $soccer;

        	}

        //	print_r($marketIds);die;
        	$response["market_ids"][] = array('SportId'=>4,'marketids'=>implode(',', $cricketIds));
        	$response["market_ids"][] = array('SportId'=>2,'marketids'=>implode(',', $tennisIds));
        	$response["market_ids"][] = array('SportId'=>1,'marketids'=>implode(',', $soccerIds));

        	$response["all_market_ids"] = implode(',', $allMarketIds);
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}


		function getFavouriteMatchLst($sportId){

			$response = array();
			$marketIds = array();

			$this->load->model('Modeleventlst');
			$this->load->model('Modelmatchmst');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Betentrymodel');
			
			$user_id = $this->globalUserId;
			$user_type = $this->globalUserType;
            $devicetype = $this->input->request_headers();

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
			$response["data"] = [];
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

				
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}
			//	}

				$filterMatches = array();
				foreach($dbMatches as $cricketDbMatch){

					$dbMatch = array('matchName'=>$cricketDbMatch['matchName'],'series_name'=>$cricketDbMatch['series_name'],'matchid'=>$cricketDbMatch['matchid'],'marketid'=>$cricketDbMatch['marketid'],'market_name'=>$cricketDbMatch['market_name'],'max_bet_liability'=>$cricketDbMatch['max_bet_liability'],'visibility'=>$cricketDbMatch['visibility'],'max_market_liability'=>$cricketDbMatch['max_market_liability'],'matchdate'=>$cricketDbMatch['MstDate'],'sportname'=>$cricketDbMatch['sportname'],'is_favourite'=>$cricketDbMatch['is_favourite'],'SportId'=>$cricketDbMatch['SportId'],'socket_url'=>$cricketDbMatch['socket_url'],'sport_image'=>$cricketDbMatch['sport_image'],'is_manual'=>$cricketDbMatch['is_manual'],'day'=>$cricketDbMatch['day']);

					$marketIds[] = $dbMatch['marketid'];

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);

					if(!empty($cricketDbMatch['runner_json'])){
						$runnerArr = json_decode($cricketDbMatch['runner_json'],true);
						$backArr = array();
						$backArr = $runnerArr[0]['back'];
						if(@$backArr[0]['price']=='--'){
							$dbMatch['runners'] = $runnerArr;
							$dbMatch['IsMatchDisable'] = true;
						}else{
							$dbMatch['runners'] = $runnerArr;
							$dbMatch['IsMatchDisable'] = true;
						}
					}else{
						$dbMatch['runners'] = array();
						$dbMatch['IsMatchDisable'] = true;
					}

				//	$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 

					// Market win and loss
					$temp = array();
					$temp['marketId'] = $dbMatch['marketid'];

					$runners = $this->Betentrymodel->sumOfOdds($dbMatch['marketid'],$user_id,$user_type,$dbMatch['matchid']);

                    $formatRunner = array();
                    foreach ($runners as $runner) {
                        $formatRunner[] = array('winValue' => $runner['winValue'], 'lossValue' => $runner['lossValue'], 'selectionId' => $runner['SelectionId']);
                    }
                    $temp['runners'] = $formatRunner;
                    $dbMatch['win_loss'] = $temp;

					// End Market win and loss


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

			//	$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}
			//	}

				$filterMatches = array();
				foreach($dbMatches as $tennisDbMatch){

					$dbMatch = array('matchName'=>$tennisDbMatch['matchName'],'series_name'=>$tennisDbMatch['series_name'],'matchid'=>$tennisDbMatch['matchid'],'marketid'=>$tennisDbMatch['marketid'],'market_name'=>$tennisDbMatch['market_name'],'max_bet_liability'=>$tennisDbMatch['max_bet_liability'],'visibility'=>$tennisDbMatch['visibility'],'max_market_liability'=>$tennisDbMatch['max_market_liability'],'matchdate'=>$tennisDbMatch['MstDate'],'sportname'=>$tennisDbMatch['sportname'],'is_favourite'=>$tennisDbMatch['is_favourite'],'SportId'=>$tennisDbMatch['SportId'],'socket_url'=>$tennisDbMatch['socket_url'],'sport_image'=>$tennisDbMatch['sport_image'],'is_manual'=>$tennisDbMatch['is_manual'],'day'=>$tennisDbMatch['day']);

					$marketIds[] = $dbMatch['marketid'];

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);

					if(!empty($tennisDbMatch['runner_json'])){
						$runnerArr = json_decode($tennisDbMatch['runner_json'],true);
						$backArr = array();
						$backArr = $runnerArr[0]['back'];
						if(@$backArr[0]['price']=='--'){
							$dbMatch['runners'] = $runnerArr;
							$dbMatch['IsMatchDisable'] = true;
						}else{
							$dbMatch['runners'] = $runnerArr;
							$dbMatch['IsMatchDisable'] = true;
						}
					}else{
						$dbMatch['runners'] = array();
						$dbMatch['IsMatchDisable'] = true;
					}	

				//	$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 

					// Market win and loss
					$temp = array();
					$temp['marketId'] = $dbMatch['marketid'];

					$runners = $this->Betentrymodel->sumOfOdds($dbMatch['marketid'],$user_id,$user_type,$dbMatch['matchid']);

                    $formatRunner = array();
                    foreach ($runners as $runner) {
                        $formatRunner[] = array('winValue' => $runner['winValue'], 'lossValue' => $runner['lossValue'], 'selectionId' => $runner['SelectionId']);
                    }
                    $temp['runners'] = $formatRunner;
                    $dbMatch['win_loss'] = $temp;

					// End Market win and loss

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

			//	$marketIds = array();
			//	foreach($matches['result'] as $match){
				//	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
			//			$marketIds[] = $match['id'];
				//	}	
			//	}

				$filterMatches = array();
				foreach($dbMatches as $soccerDbMatch){

					$dbMatch = array('matchName'=>$soccerDbMatch['matchName'],'series_name'=>$soccerDbMatch['series_name'],'matchid'=>$soccerDbMatch['matchid'],'marketid'=>$soccerDbMatch['marketid'],'market_name'=>$soccerDbMatch['market_name'],'max_bet_liability'=>$soccerDbMatch['max_bet_liability'],'visibility'=>$soccerDbMatch['visibility'],'max_market_liability'=>$soccerDbMatch['max_market_liability'],'matchdate'=>$soccerDbMatch['MstDate'],'sportname'=>$soccerDbMatch['sportname'],'is_favourite'=>$soccerDbMatch['is_favourite'],'SportId'=>$soccerDbMatch['SportId'],'socket_url'=>$soccerDbMatch['socket_url'],'sport_image'=>$soccerDbMatch['sport_image'],'is_manual'=>$soccerDbMatch['is_manual'],'day'=>$soccerDbMatch['day']);

					$marketIds[] = $dbMatch['marketid'];

					$resultDeclare = $this->Modelmatchmst->result($dbMatch['matchid']);
					$volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($dbMatch['matchid']);

					if(!empty($soccerDbMatch['runner_json'])){
						$runnerArr = json_decode($soccerDbMatch['runner_json'],true);
						$backArr = array();
						$backArr = $runnerArr[0]['back'];
						if(@$backArr[0]['price']=='--'){
							$dbMatch['runners'] = $runnerArr;
							$dbMatch['IsMatchDisable'] = true;
						}else{
							$dbMatch['runners'] = $runnerArr;
							$dbMatch['IsMatchDisable'] = true;
						}
					}else{
						$dbMatch['runners'] = array();
						$dbMatch['IsMatchDisable'] = true;
					}	

				//	$dbMatch['marketRunner'] = array();
					$dbMatch['volumeLimit'] = $volumeLimit;
					$dbMatch['selection'] = array(); 
					$dbMatch['result'] = $resultDeclare; 

					// Market win and loss
					$temp = array();
					$temp['marketId'] = $dbMatch['marketid'];

					$runners = $this->Betentrymodel->sumOfOdds($dbMatch['marketid'],$user_id,$user_type,$dbMatch['matchid']);

                    $formatRunner = array();
                    foreach ($runners as $runner) {
                        $formatRunner[] = array('winValue' => $runner['winValue'], 'lossValue' => $runner['lossValue'], 'selectionId' => $runner['SelectionId']);
                    }
                    $temp['runners'] = $formatRunner;
                    $dbMatch['win_loss'] = $temp;

					// End Market win and loss
					
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

        	$response["market_ids"] = implode(',', $marketIds);
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}



		function save_multiple_bets(){

			$this->load->model('Betentrymodel');
			$this->load->model('Modeltblbets');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Modellstmaster');
			$userId = $this->globalUserId;

			$betDelay = $this->Modelcreatemaster->getBetDelay($userId);
            if(IS_MULTI_BET=='Y'){
                if(!empty($betDelay[0]['set_timeout'])){
                    $timeout = $betDelay[0]['set_timeout'];
                    sleep($timeout);
                }
            }


			
		//	echo $redisUrl;
        //  print_r($redisArr);die;
		//	echo 'End'.time();

		//	echo $redisUrl;
		//	print_r($redisArr);die;

			$returnData = array();
			foreach($_POST['bet_slip'] as $data){
                if(IS_MULTI_BET=='N' and $data['isManual']!=1){
                	$globalDelay = 0;
                    if($data['is_session_fancy']=='Y'){
                    	$globalDelay = CRICKET_BET_DELAY;
                        $timeout = $betDelay[0]['session_delay'] + $globalDelay;
                        sleep($timeout);
                    }else{
                    	if($data['SportId']==4){
                    		$globalDelay = CRICKET_BET_DELAY;
                    	}elseif($data['SportId']==2){
                    		$globalDelay = TENNIS_BET_DELAY;
                    	}elseif($data['SportId']==1){
                    		$globalDelay = SOCCER_BET_DELAY;
                    	}
                        $timeout = $betDelay[0]['set_timeout'] + $globalDelay;
                        sleep($timeout);
                    }

                }
                //echo "<pre>";print_r($data);die;
                //echo "<pre>";print_r($data);die;

                $redisUrl = EXCH_BACK_LAY_BY_MARKETS_URL.'?back_lay_ids='.$_POST['back_lay_ids']; 
                $redisJson = $this->httpGet($redisUrl);
                $redisArr = json_decode($redisJson,true);
                //echo "<pre>";print_r($redisArr);die;
				if($data['is_session_fancy']=='Y'){

					$data['OddsNumber'] = $data['priceVal'];
					$data['betValue'] = $data['stake'];
					$data['loginId'] = $userId;
					$data['lay_size'] = $data['NoValume'];
					$data['back_size'] = $data['YesValume'];
					$data['deviceInformation'] = $data['deviceInfo'];

					$validate = $this->Betentrymodel->validateSessionSaveBet($data,$redisArr);
                    //print_r($data);die;
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
                        $this->Modelcreatemaster->updateUserBalLiablity($userId);
					}

					$returnData[] = $tempData;

				}
				else{

				$data['loginId'] = $userId;
				$data['UserTypeId'] = $this->globalUserType;
				$data['ApiVal'] = 0;

				if($data['isback'] == 0){
					$isBack = 1;
				}else{
					$isBack = 0;
				}

				$data['isback'] = $isBack;
				$priceVal = $data['priceVal'];
				$stake = $data['stake'];
                if(isset($data['isManual']) && $data['isManual']==1){

                    $manualMatchOddsDetails = $this->Modelcreatemaster->manualMatchOddsDetails($data['MarketId']);
                    $data['type'] = 'manual';

                    if($data['teamName']=='team_0'){
                        $team = 'team1_';
                    }elseif ($data['teamName']=='team_1'){
                        $team = 'team2_';
                    }elseif ($data['teamName']=='team_2'){
                        $team = 'draw_';
                    }
                     $priceValTemp = $data['priceVal'];
                   // echo "<pre>";print_r($manualMatchOddsDetails);die;

                    //echo strlen($manualMatchOddsDetails[$team.'lay']);die;
                   // echo $data['isback'];die;
                    if($data['isback']==1){
                        if($data['IsRs']==false){
                            if(strlen($manualMatchOddsDetails[$team.'back'])==1){
                                $manualMatchOddsDetails[$team.'back'] = $manualMatchOddsDetails[$team.'back']/100;
                            }elseif (strlen($manualMatchOddsDetails[$team.'back'])==2){
                                $manualMatchOddsDetails[$team.'back'] = $manualMatchOddsDetails[$team.'back']/100;
                            }else{
                                $manualMatchOddsDetails[$team.'back'] = $manualMatchOddsDetails[$team.'back']/10000;
                            }
                        } //echo $manualMatchOddsDetails[$team.'back'] , $priceValTemp;
                        if($manualMatchOddsDetails[$team.'back'] == $priceValTemp){ //echo 'if1';

                            $p_l = ($stake * ($priceValTemp+1)) - $stake;
                            $isMatched =  1;
                        }else{
                            $p_l = ($stake * ($priceValTemp+1)) - $stake;
                            $isMatched = 0;
                        }
                    }else{
                        if($data['IsRs']==false){
                            if(strlen($manualMatchOddsDetails[$team.'lay'])==1){
                                $manualMatchOddsDetails[$team.'lay'] = $manualMatchOddsDetails[$team.'lay']/100;
                            }elseif (strlen($manualMatchOddsDetails[$team.'lay'])==2){
                                $manualMatchOddsDetails[$team.'lay'] = $manualMatchOddsDetails[$team.'lay']/100;
                            }else{
                                $manualMatchOddsDetails[$team.'lay'] = $manualMatchOddsDetails[$team.'lay']/10000;
                            }
                        }
                        if($manualMatchOddsDetails[$team.'lay'] == $priceValTemp){// echo 'if2';
                            $p_l = ($stake * ($priceValTemp+1)) - $stake;
                            $isMatched = 1;
                        }else{
                            $p_l = ($stake * ($priceValTemp+1)) - $stake;
                            $isMatched = 0;
                        }
                    }

                }
                else{
                    $data['type'] = 'auto';
                    if($data['isback']==1){
                        $backKey = $data['MarketId'].'_'.$data['selectionId'].'_back';
                        $data['betfair_status'] = explode(',',$redisArr[$backKey])[2];
                        $redisArr[$backKey] = (float)explode(',',$redisArr[$backKey])[0];

                     //   echo "<pre>";print_r($data);die;
                        if($redisArr[$backKey] > $priceVal){ // echo 'if1';
                            $priceVal = $redisArr[$backKey];
                            $p_l = ($priceVal * $stake) - $stake;
                            $isMatched = 1;
                        }elseif($redisArr[$backKey] == $priceVal){ //echo 'if2';
                            $p_l = ($priceVal * $stake) - $stake;
                            $isMatched = 1;
                        }else{
                            $p_l = ($priceVal * $stake) - $stake;
                            $isMatched = 0;
                        }
                    }
                    else{
                        $layKey = $data['MarketId'].'_'.$data['selectionId'].'_lay';
                        $data['betfair_status'] = explode(',',$redisArr[$layKey])[2];
                        $redisArr[$layKey] = (float)explode(',',$redisArr[$layKey])[0];

                        if($redisArr[$layKey] < $priceVal){ //echo 'if1';
                            $priceVal = $redisArr[$layKey];
                            $p_l = ($stake * $priceVal) - $stake;
                            //	echo 'statke'.$stake.'p_l'.$p_l.'price value'.$priceVal;
                            $isMatched = 1;
                        }elseif($redisArr[$layKey] == $priceVal){// echo 'if2';
                            $p_l = ($stake * $priceVal) - $stake;
                            $isMatched = 1;
                        }else{
                            $p_l = ($stake * $priceVal) - $stake;
                            $isMatched = 0;
                        }
                    }
                }



			//	settype($p_l, "integer");
				$data['p_l'] = $p_l;
				$data['priceVal'] = $priceVal;
				$data['isMatched'] = $isMatched;
				$data['stake'] = $stake;



				$response = array();

				$validate = $this->Betentrymodel->mobileValidateSaveBet($data);
				
				
				
				$tempData = array();
				if(!empty($validate)){

					$tempData['unique_id'] = $data['unique_id'];
					$tempData['result'] = $validate;
					
					 

				}else{
 
                    $unMatchRedis = [];
					$condition=$this->Betentrymodel->mobileSave_bet($data);
                    //print_r($condition);die;
					if ($condition[0]['resultV']==0) {
					//  Comment the code of profit and loss for speeding up save bet process
					//	$rvData['RunnerValue']=$this->Betentrymodel->sumOfOdds($data['MarketId'],$data['loginId'],$data['UserTypeId'],$data['matchId']);
						$response = array();
						$response["code"] = 0;
						$response["error"] = false;
		        		$response["message"] = $condition[0]['retMess'];
		        	//	$response["data"] = $rvData;
		        		$response["data"] = array('RunnerValue'=>'');

		        		$tempData['unique_id'] = $data['unique_id'];
						$tempData['result'] = $response;
						if($data['isMatched']==0){
                            $unMatchRedis[]=$condition[0]['resultV'];
                        }
                        $this->load->model('Betentrymodel');

                        $this->Betentrymodel->saveOddsProfitLoss($userId, $condition[0]['LID'], $_POST['matchId'], $data['MarketId']);

					}
					else {
						$id = $condition[0]['resultV'];
						$this->Modeltblbets->deleteBet($id);

						$response = array();
						$response["code"] = -6;
						$response["error"] = true;
		        		$response["message"] = $condition[0]['retMess'];

						$tempData['unique_id'] = $data['unique_id'];
						$tempData['result'] = $response;
					}
                    $this->Modelcreatemaster->updateUserBalLiablity($userId);
				}
					$returnData[] = $tempData;
				}
                if(!empty($unMatchRedis)){
                    $this->Betentrymodel->updateUnMatchDataOnRedis();
                }
			}

			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($returnData));

		}

        function save_make_bet(){

		    if(MAKE_BAT_URL=='N'){
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode(array('error'=>true,'code'=>0,'message'=>'not allow')));
            }
            $this->load->model('Betentrymodel');
            $this->load->model('Modeltblbets');
            $this->load->model('Modelcreatemaster');
            $this->load->model('Modellstmaster');
            $userId = $_POST['user_id'];
            $data = $_POST;



            $data['loginId'] =  $_POST['user_id'];
            $data['UserTypeId'] = 3;
            $data['ApiVal'] = 0;
            if($_POST['isback'] == 0){
                $isBack = 1;
            }else{
                $isBack = 0;
            }

            $data['isback'] = $isBack;
            $priceVal = $_POST['priceVal'];
            $stake = $_POST['stake'];
            $isMatched = 1;


            $data['type'] = 'auto';
            $p_l = ($stake * $priceVal) - $stake;

            $data['p_l'] = $p_l;
            $data['priceVal'] = $priceVal;
            $data['isMatched'] = $isMatched;
            $data['stake'] = $stake;


            $response = array();

            $tempData = array();
            $unMatchRedis = [];

            $condition=$this->Betentrymodel->mobileSave_bet($data);

            if ($condition[0]['resultV']==0) {

                $response = array();
                $response["code"] = 0;
                $response["error"] = false;
                $response["message"] = $condition[0]['retMess'];
                //	$response["data"] = $rvData;
                $response["data"] = array('RunnerValue'=>'');

                $this->load->model('Betentrymodel');

                $this->Betentrymodel->saveOddsProfitLoss($userId, $condition[0]['LID'], $_POST['matchId'], $data['MarketId']);
                $this->Modelcreatemaster->updateUserBalLiablity($userId);
            }
            else {
                $id = $condition[0]['resultV'];
                $this->Modeltblbets->deleteBet($id);

                $response = array();
                $response["code"] = -6;
                $response["error"] = true;
                $response["message"] = $condition[0]['retMess'];
            }

            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

        }

		function save_multi_bet(){


			$marketIds = array();
			foreach($_POST['bet_slip'] as $betslip){
				if($betslip['SportId']=='111'){
					$marketIds[] = $betslip['MarketId'].'_s';	
				}else{
					$marketIds[] = $betslip['MarketId'];	
				}
				
			}

			//print_r($marketIds);die;

		//	echo count($marketIds); die;

		/*	$results = array_filter($_POST['bet_slip'], function($result) {
				  return $result['id'] == $data['MarketId'];
				});

				echo $data['MarketId'];
				print_r($results);die; */


			$this->load->model('Betentrymodel');
			$this->load->model('Modeltblbets');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Modellstmaster');
			$userId = $this->globalUserId;

			$sportsIds = $_POST['sport_id'];

			$redisUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.implode(',', $marketIds);	
			$redisJson = $this->httpGet($redisUrl);
			$redisArr = json_decode($redisJson,true);

			$matchArr = array();

			foreach($redisArr as $markets){
				if($markets['eventTypeId']==111){

					$runners = array();
					foreach($markets['value']['session'] as $runner){
						$selectionId = $runner['SelectionId'];
						$back = $runner['BackPrice1'];
						$lay = $runner['LayPrice1'];
						$laySize = $runner['LaySize1'];
						$BackSize = $runner['BackSize1'];
						$runners[$selectionId] = array('back'=> $back,'lay'=>$lay,'lay_size'=>$laySize,'back_size'=>$BackSize);
					}
					$sessionMarketId = $markets['market_id'].'_s';
					$matchArr[$sessionMarketId] = $runners;	
					
				}else{

					$runners = array();
					foreach($markets['runners'] as $runner){
						$back = $runner['back'][0];
						$lay = $runner['lay'][0];
						$runners[$runner['id']] = array('back'=> $back,'lay'=>$lay);
					}
					$matchArr[$markets['id']] = $runners;

				}
			}

		//	echo $redisUrl;
		//	print_r($matchArr);die;
					
		
		/*	if(in_array(4, $sportsIds)){ // echo 'if1';

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
					if(in_array($cArr['id'],$marketIds)){
						$matchArr[$cArr['id']] = $runners;
						if(count($marketIds)==1){
							break;
						}
					}
					
				}
			} 

			}

		//	print_r($matchArr);die;

			if(in_array(2, $sportsIds)){ // echo 'if1';
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
						if(in_array($cArr['id'],$marketIds)){
							$matchArr[$cArr['id']] = $runners;
							if(count($marketIds)==1){
								break;
							}
						}
					}
				}
			}

			if(in_array(1, $sportsIds)){ // echo 'if1';
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
						if(in_array($cArr['id'],$marketIds)){
							$matchArr[$cArr['id']] = $runners;
							if(count($marketIds)==1){
								break;
							}
						}	
					}
				}
			} 

			if(in_array(111, $sportsIds)){ // echo 'if1';
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
								$laySize = $runner['LaySize1'];
								$BackSize = $runner['BackSize1'];
								$runners[$selectionId] = array('back'=> $back,'lay'=>$lay,'lay_size'=>$laySize,'back_size'=>$BackSize);
							}
							if(in_array($session['market_id'],$marketIds)){
								$sessionMatchArr[$session['market_id']] = $runners;	
								if(count($marketIds)==1){
									break;
								}
							}
						}	
					}
				}
			} */

		/*	echo $sessionUrl;
			print_r($sessionArr);
			print_r($sessionMatchArr);die; */
			
			$multiData = $_POST;

		//	print_r($sportsIds);
		//	print_r($sessionMatchArr);
		//	die;
			

			$returnData = array();
			foreach($multiData['bet_slip'] as $data){

				if($data['is_session_fancy']=='Y'){

					$data['OddsNumber'] = $data['priceVal'];
					$data['betValue'] = $data['stake'];
					$data['loginId'] = $userId;
					$validate = $this->Betentrymodel->validateSessionSaveBet($data);

				//	print_r($sessionMatchArr[$data['MarketId']]);
				//	die;

					if($data['ind_fancy_selection_id']){
						if(!empty($sessionMatchArr[$data['MarketId']])){
							$marketId = $data['MarketId'].'_s';
							$selectionId = $data['ind_fancy_selection_id'];
							$sessionVal = $sessionMatchArr[$marketId];
							if(!empty($sessionVal[$selectionId])){
								$selectionVal = $sessionVal[$selectionId];

								$oddsNumbers = $data['OddsNumber'];
								settype($oddsNumbers, "integer");

								$layPrice = $selectionVal['lay'];
								settype($layPrice, "integer");

								$backPrice = $selectionVal['back'];
							
								settype($backPrice, "integer");

								$laySize = $selectionVal['lay_size'];
							
								settype($laySize, "integer");

								$data['lay_size'] = $laySize;

								$backSize = $selectionVal['back_size'];
							
								settype($backSize, "integer");

								$data['back_size'] = $backSize;


							//	echo "oddsNumbers $oddsNumbers layPrice $layPrice backPrice $backPrice";
							//	die;  
  
								if($data['OddValue']==0){
									if($backPrice == $oddsNumbers){
										
									}else{
										$validate = array('code' => 9 ,'error'=>true,'message' => 'Runs changed');
									}
								}else{
									if($layPrice == $oddsNumbers){
									
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

						//echo '<pre>';
					//	print_r($chk);die;

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

				if($data['isback'] == 0){
					$isBack = 1;
				}else{
					$isBack = 0;
				} 

				$data['isback'] = $isBack;
				$stake = $data['stake'];
				$priceVal = $data['priceVal'];

				

				if(!empty($matchArr[$data['MarketId']])){
					$marketId = $data['MarketId'];
					$selectionId = $data['selectionId'];
					$marketVal = $matchArr[$marketId];
					if(!empty($marketVal[$selectionId])){
						$selectionVal = $marketVal[$selectionId];

						if($data['isback']==1){
							if($selectionVal['back']['price'] > $priceVal){ // echo 'if1';
								$priceVal = $selectionVal['back']['price'];
								$p_l = ($priceVal * $stake) - $stake;
								$isMatched = 1;
							}elseif($selectionVal['back']['price'] == $priceVal){ //echo 'if2';
								$p_l = ($priceVal * $stake) - $stake;
								$isMatched = 1;
							}else{
								$p_l = ($priceVal * $stake) - $stake;
								$isMatched = 0;
							}
						}else{  
							if($selectionVal['lay']['price'] < $priceVal){ //echo 'if1';
								$priceVal = $selectionVal['lay']['price'];
								$p_l = ($stake * $priceVal) - $stake;
							//	echo 'statke'.$stake.'p_l'.$p_l.'price value'.$priceVal;
								$isMatched = 1;
							}elseif($selectionVal['lay']['price'] == $priceVal){// echo 'if2';
								$p_l = ($stake * $priceVal) - $stake;
								$isMatched = 1;
							}else{
								$p_l = ($stake * $priceVal) - $stake;
								$isMatched = 0;
							}
						}

					//	echo $stake;
						settype($p_l, "integer");
					//	echo $isMatched;
						$data['p_l'] = $p_l;
						$data['priceVal'] = $priceVal;
						$data['isMatched'] = $isMatched;
						$data['stake'] = $stake;
					//	print_r($data);
					//	die;

					/*	if($data['isback']==1){
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
						} */
					}
				}

			/*	print_r($data);
				die;  */
				
				$response = array();
				
				$validate = $this->Betentrymodel->mobileValidateSaveBet($data);

				$tempData = array();
				if(!empty($validate)){

					$tempData['unique_id'] = $data['unique_id'];
					$tempData['result'] = $validate;

				}else{



					$condition=$this->Betentrymodel->mobileSave_bet($data);


					if ($condition[0]['resultV']==0) {
					//  Comment the code of profit and loss for speeding up save bet process	
					//	$rvData['RunnerValue']=$this->Betentrymodel->sumOfOdds($data['MarketId'],$data['loginId'],$data['UserTypeId'],$data['matchId']);
						$response = array();
						$response["code"] = 0;
						$response["error"] = false;
		        		$response["message"] = $condition[0]['retMess'];
		        	//	$response["data"] = $rvData;
		        		$response["data"] = array('RunnerValue'=>''); 

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

			if(empty($_POST['match_stake'])){
				$response["code"] = ERROR_PARAM_REQUIRED;
				$response["error"] = true;
				$response["message"] = 'Json is empty';
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				return false;
			}

			$updateData['match_stake'] = json_encode($_POST['match_stake']);

			$errorMinStakeFlag = false;
			$errorMinStake = MIN_STAKE;
			foreach($_POST['match_stake'] as $mStake){
				if($mStake < $errorMinStake){
					$errorMinStakeFlag = true;
				}
			}

			if($errorMinStakeFlag){
				$response["code"] = ERROR_MIN_STAKE;
				$response["error"] = true;
				$response["message"] = 'Minimum stake is '.$errorMinStake;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
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


		function one_click_stake_setting(){	

			$this->load->model('Modelcreatemaster');
		//	$this->verifyRequiredParams($_POST,array('one_click_stake'));	

			$userId = $this->globalUserId;

		//	var_dump($_POST['one_click_stake']);			


			if(empty($_POST['one_click_stake'])){// echo 'if';
				$response["code"] = ERROR_PARAM_REQUIRED;
				$response["error"] = true;
				$response["message"] = 'Json is empty';
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				return false;
			}


			$updateData['one_click_stake'] = json_encode($_POST['one_click_stake']);

			$errorMinStakeFlag = false;
			$errorMinStake = MIN_STAKE;
			foreach($_POST['one_click_stake'] as $ocStake){
				if($ocStake < $errorMinStake){
					$errorMinStakeFlag = true;
				}
			}

			if($errorMinStakeFlag){
				$response["code"] = ERROR_MIN_STAKE;
				$response["error"] = true;
				$response["message"] = 'Minimum stake is '.$errorMinStake;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
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


		function get_all_bets(){	

			$this->load->model('Betentrymodel');
			$userId = $this->globalUserId;
			$userType = $this->globalUserType;

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Bet listing";
			$response["data"] = $this->Betentrymodel->getAllBetEntry($userType,$userId);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

		function match_autocomplete(){
			$userId = $this->globalUserId;

			$response = array();
			$this->verifyRequiredParams($_POST,array('search'));	
			$params = array('user_id'=>$userId,'search'=>$_POST['search']);

			$this->load->model('Modelmatchmst');
			$user_id = $this->globalUserId;
			$response["code"] = 0;	
			$response["error"] = false;
        	$response["message"] = "match autocomplete listing";
        	$response["data"] = $this->Modelmatchmst->getUserMatchAutoComplete($params);
        	
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		
		}


}