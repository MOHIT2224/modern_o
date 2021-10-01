<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Geteventcntr extends CI_Controller {

		//public $APP_KEY = " asfqjbpDSPULqMZ1";

		//public $APP_KEY = "WaIiEmhVft0FXzxr";
		public $APP_KEY = BETFAIR_APP_KEY;

		function __construct() {

			parent::__construct();

			$this->load->model('Modeleventlst');

			$this->load->model('Modelcreatemaster');

			$_POST = json_decode(file_get_contents('php://input'), true);
		//	$node1=$this->session->userdata('user_id');

			$currentMethod = $this->router->method;
		    $allowAuth = array('getSeriesLst','getMatchLst','getMarketOfMatch','GetSportFrmDatabase','getBackLaysOfMarket','matchMarketLstPublic','getBackLaysOfMarketSelectionName');
		  /*  if(!in_array($currentMethod, $allowAuth)){
					if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		    } */
//			if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}

		}

		function getTokenId(){

			if ($_POST['TokenId']!=null) {

				$TokenId = $_POST['TokenId'];

				//print_r($TokenId);

				//die();

			}

			else{

				$TokenId = $this->session->userdata('TokenId');

				//print_r($TokenId);

				//die();

			}

			return $TokenId;

		}

		/*function getTokenId(){
 
			$TokenId = $this->getACookie(); 

			return $TokenId;

		}*/

        function getAllSports(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSport');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }

            $data['sportData']  =$this->Modeleventlst->getAllSports();


            $this->output->set_content_type('application/json')->set_output(json_encode($data));

        }
        function updateSportById(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ActiveSport');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }

           $responce  = $this->Modeleventlst->updateSportById($_POST['id'],$_POST['active']);
            if($responce){
                $data=['error'=>0,'message'=>'update successfully'];
            }else{
                $data=['error'=>1,'message'=>'some thing is wrong'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));

        }
        function updateMarketById(){

            $responce  = $this->Modeleventlst->updateMarketById($_POST['id'],$_POST['visibility']);
            if($responce){
                $data=['error'=>0,'message'=>'update successfully'];
            }else{
                $data=['error'=>1,'message'=>'some thing is wrong'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));

        }

		function GetSportFrmDatabase(){

			$data['sportData'] = $this->Modeleventlst->getSportData();

			$data['getplayer']=$this->Modelcreatemaster->getPlayer();

	       	$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}
		function GetScoreApi($eventIds){
//$str = file_get_contents('https://www.betfair.com/inplayservice/v1/scores?_ak=nzIFcwyWhrlwYMrh&alt=json&eventIds='.$eventIds.'&locale=en');
$str = file_get_contents('https://ips.betfair.com/inplayservice/v1/scores?_ak=nzIFcwyWhrlwYMrh&alt=json&eventIds='.$eventIds.'&locale=en_GB&productType=EXCHANGE&regionCode=UK');
			if(!empty(json_decode($str))){
                echo $json =$str;
            }else{
			    $data = $this->Modeleventlst->GetMatchByMatchId($eventIds);

               echo $data['score_board_json'];
            }


		}

		function getMarketOfMatch($MatchId=null){

			if ($_POST['MatchId']!=null) {

				$matchID=$_POST['MatchId'];

			}

			else{

				$matchID=$MatchId;

			}

			$sessionToken = $this->Modelcreatemaster->findBetfairToken();

			//$horseRacingEventTypeId = $this->getNextUkHorseRacingMarket($this->APP_KEY, $sessionToken, $matchID);

			$data['marketfrmApi'] =  $this->getNextUkHorseRacingMarket($this->APP_KEY, $sessionToken, $matchID);//sourabh 170105

			$data['marketfrmDataBase'] = $this->Modeleventlst->GetMarketFrmDatabase();//sourabh 170105

			$this->output->set_content_type('application/json')->set_output(json_encode($data));//echo $markeetName=json_encode($horseRacingEventTypeId); sourabh 170106

		}

		function getBackLaysOfMarket($MarketId=null,$MatchId=null){//170201

			if ($_POST['MarketId']!=null) {

				$MarketId1=$_POST['MarketId'];

			}else{

				$MarketId1=$MarketId;

			}

			if ($_POST['MatchId']!=null) {//170201

				$MatchId1=$_POST['MatchId'];

			}else{

				$MatchId1=$MatchId;

			}

			$sessionToken = $this->Modelcreatemaster->findBetfairToken();

		//	$data['MarketRunner'] = $this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);//170131

			$data['MarketRunner'] = $this->getMatchOdds($MarketId1);//170131

			$data['MatchOddsVolVal']=$this->Modelcreatemaster->getMatchOddsLimit($MatchId1,$MarketId1);//170201

			$data['sessionToken']=$sessionToken;


			echo $markeetName=json_encode($data);

		}
		function getBackLaysOfMarket_New($MarketId=null,$MatchId=null){//170201

			if ($_POST['MarketId']!=null) {

				$MarketId1=$_POST['MarketId'];

			}else{

				$MarketId1=$MarketId;

			}

			if ($_POST['MatchId']!=null) {//170201

				$MatchId1=$_POST['MatchId'];

			}else{

				$MatchId1=$MatchId;

			}

			$sessionToken=$this->getTokenId();

			

			//$horseRacingEventTypeId = $this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);170131

			//echo $markeetName=json_encode($horseRacingEventTypeId);170131
			// add new code for api 
			$MarketId1 = substr($MarketId1, 2);
			//die();
			//$obj = json_decode($this->GetOddsAPi($MarketId1));
			$data['MarketRunner'] = $this->GetOddsAPi($MarketId1);//170131
			//$data['MarketRunner'] = $this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);//170131
			//end of api code
			//$data['MarketRunner'] = $this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);//170131

			$data['MatchOddsVolVal']=$this->Modelcreatemaster->getMatchOddsLimit($MatchId1,$MarketId1);//170201

			echo json_encode($data);

		}
		function GetOddsAPi($MarketId1){
			ob_start();
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL, "http://ibexodds.azurewebsites.net/api/odds?id=".$MarketId1); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				$output = curl_exec($ch); 
				return json_decode($output); 
				curl_close($ch);
			ob_flush();
		}


        function getBackLaysOfMarketSelectionName($MarketId=null,$userId=null,$userType=null,$matchId=null)

        {

            if ($_POST['MarketId']!=null) {//sourabh 170107 for Android

                $MarketId1=$_POST['MarketId'];

                $userId1=$_POST['userId'];

                $userType1=$_POST['userType'];

                $matchId1=$_POST['matchId'];

                //$sessionToken=$_POST['sessionToken'];

            }else{//For PHP

                $MarketId1=$MarketId;

                $userId1=$userId;

                $userType1=$userType;

                $matchId1=$matchId;

                if($userId==null)$userId1=0;else $userId1=$userId;


            }

            $sessionToken = $this->Modelcreatemaster->findBetfairToken();

            //if($userId==null)$userId1=0;else $userId1=$userId;

            //$data['MarketRunner']=$this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);//sourabh 170130

            //	$data['runnerSlName']=$this->getNextUkHorseRacingMarket2($this->APP_KEY, $sessionToken, $MarketId1);//sourabh 170130

            $data['runnerSlName'] = $this->getMatchOdds($MarketId1);


            $data['RunnerValue']=$this->Modelcreatemaster->sumOfOdds($MarketId1,$userId1,$userType1,$matchId1);

            //$data['RunnerValue']=$_POST['userId'];
            $data['MarketData']=$this->Modelcreatemaster->getMarketInfo($MarketId1);
            $data['ManualMatchOddsData'] = $this->Modelcreatemaster->manualMatchOddsDetails($MarketId1);

            //$data['MatchOddsVolVal']=$this->Modelcreatemaster->getMatchOddsLimit($matchId1);//sourabh 170130



            $this->output->set_content_type('application/json')->set_output(json_encode($data));

        }

		function saveSelectionName($MarketId,$MatchId,$SportsId){

			

			$sessionToken=$this->getTokenId();

			

			$horseRacingEventTypeId = $this->getNextUkHorseRacingMarket2($this->APP_KEY, $sessionToken, $MarketId)[0]->runners;//sourabh 7-dec-2016 add [0]->runners;

        

			$selectionData=json_encode($horseRacingEventTypeId);

        

			$condition=$this->Modeleventlst->SaveSelection($selectionData,$MatchId,$SportsId,$MarketId);

				if ($condition) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

					echo json_encode(array('error' => 1 ,'message' => 'Record Already Exits...'));

				}			

		}

		function get_drpdwnSelectionName($marketId){

			$selectionArr = $this->Modeleventlst->GetSelectionFrmDatabase($marketId);

			if(!empty($selectionArr)){
				$suspendArr = $selectionArr[0];
				$suspendArr['selectionId'] = 0;
				$suspendArr['selectionName'] = 'Match Abandoned';
				$selectionArr[] = $suspendArr;
			}

			$data['runner'] = $selectionArr;

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

        

		}

		function Save_SelectionName($MarketId=null){

        

			if ($_POST['MarketId']!=null) {

				$MarketId1=$_POST['MarketId'];

			}else{

				$MarketId1=$MarketId;

			}

        

			$sessionToken=$this->getTokenId();

			

			$horseRacingEventTypeId = $this->getNextUkHorseRacingMarket2($this->APP_KEY, $sessionToken, $MarketId1)[0]->runners;//sourabh 7-dec-2016

			echo $markeetName=json_encode($horseRacingEventTypeId);

        

		}

		function getNextUkHorseRacingMarket2($appKey, $sessionToken, $horseRacingEventTypeId)

		{

			$params = '{"filter":{"marketIds":["' . $horseRacingEventTypeId. '"]},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketCatalogue', $params);

			return $jsonResponse;//[0]->runners;sourabh 7-dec-2016

		}

		function saveSport(){

			

			$condition=$this->Modeleventlst->saveSport();

				if ($condition) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

					echo json_encode(array('error' => 1 ,'message' => 'Record Already Exits...'));

				}		

		}

		function saveSportMatch(){

            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSeriesMatch');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }

            if(isset($_POST['is_manual'])){
                $_POST['matchId'] = '999'.substr(base_convert(mt_rand(), 10, 8), 0, 6);
                $_POST['selectionId1'] = '999'.substr(base_convert(mt_rand(), 10, 8), 0, 6);
                $_POST['selectionId2'] = '999'.substr(base_convert(mt_rand(), 10, 8), 0, 6);
                $_POST['selectionId3'] = '999'.substr(base_convert(mt_rand(), 10, 8), 0, 6);
                $_POST['marketId'] =  $marketId = '9.'.time();

                $_POST['marketName'] = 'Match Odds';
                $_POST['is_manual'] = 1;
                $_POST['isManualMatchOdds'] = 1;


                $_POST['score_board_json'] =[["eventTypeId"=>$_POST['sportId'],"eventId"=>$_POST['matchId'],'toss'=>'','bettingTeam'=>'',"score"=>["home"=>['selection_id'=> $_POST['selectionId1'],"name"=>$_POST['runnerName1'],"halfTimeScore"=>"","fullTimeScore"=>"","penaltiesScore"=>"","penaltiesSequence"=>[],"highlight"=>false],"away"=>['selection_id'=> $_POST['selectionId2'],"name"=>$_POST['runnerName2'],"halfTimeScore"=>"","fullTimeScore"=>"","penaltiesScore"=>"","penaltiesSequence"=>[],"highlight"=>true,"inning1"=>["runs"=>"0","wickets"=>"0","overs"=>"0"]]],"description"=>"3rd Test","currentSet"=>1,"stateOfBall"=>["overNumber"=>"0","overBallNumber"=>"0","bowlerName"=>"Shaheen Afridi","batsmanName"=>"Jeet Raval","batsmanRuns"=>"0","appealId"=>"0","appealTypeName"=>"Not Out","wide"=>"0","bye"=>"0","legBye"=>"0","noBall"=>"0","outcomeId"=>"0","dismissalTypeName"=>"Not Out","referralOutcome"=>"0"],"currentDay"=>"1","matchType"=>"TEST","fullTimeElapsed"=>["hour"=>0,"min"=>0,"sec"=>0],"matchStatus"=>"InPlay"]];


            }else{
                $_POST['score_board_json']=[];
                $_POST['isManualMatchOdds'] = 0;
                $_POST['is_manual'] = 0;
            }
            //echo "<pre>";print_r($_POST);die;

			$condition=$this->Modeleventlst->saveSportMatch($_POST);

			if($condition){

				//  Note : Need to disalbe this as we are saveing runners in market table
			/*	$matchId = $_POST['matchId'];
				$this->updateMatchRunners($matchId); */

				 $marketid = $_POST['marketId'];
                if(isset($_POST['is_manual'])){
                    $this->load->model('Modelmarket');
                    $this->Modelmarket->updateDefaultRunnerMarket($marketId);
                }else{
                    if(empty($_POST['market_runner_json'])){
                        $this->updateMarketRunners($marketid);
                    }

                }

			}

			$this->output->set_content_type('application/json')->set_output(json_encode($condition));

			/*	if ($condition==0) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

				echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));

			 	}	*/		 

		}

		/**
		 * [saveSportMatchSelectAll active all sports match]
		 * @return [json] [success message]
		 */
		function saveSportMatchSelectAll(){
			$condition=$this->Modeleventlst->saveSportMatchSelectAll();
			echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));
		}

		/**
		 * [saveSportMatchDeSelectAll deactive all sports match]
		 * @return [json] [success message]
		 */
		function saveSportMatchDeSelectAll(){
			$condition=$this->Modeleventlst->saveSportMatchDeSelectAll();
			echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));
		}
		

		function saveMatchMarket(){ 

			$condition=$this->Modeleventlst->saveMatchMarket();

				if ($condition==true) {

        

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

        

				}else{

        

					echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));

				}			

		} 

		/**
		 * [saveSportMatchSelectAll active all match market]
		 * @return [json] [success message]
		 */
		function saveMatchMarketSelectAll(){			
			$condition=$this->Modeleventlst->saveMatchMarketSelectAll();
			echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));
		}

		/**
		 * [saveMatchMarketDeSelectAll deactive all match market]
		 * @return [json] [success message]
		 */
		function saveMatchMarketDeSelectAll(){
			$condition=$this->Modeleventlst->saveMatchMarketDeSelectAll();
			echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));
		}

		function saveMatchMarketType(){
			$condition=$this->Modeleventlst->saveMatchMarketType();

				if ($condition) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

					echo json_encode(array('error' => 1 ,'message' => 'Record Already Exits...'));

				}			

		}

		public function getOnlineMatchBySportId($sportId){
		    $result=[];

            if($sportId==4){
                $sportResult = $this->httpGet(BR_LIVE_CRICKET_SOCKET_URL);
                $result = json_decode($sportResult,true)['result'];
            }
            if($sportId==2){
                $sportResult = $this->httpGet(BR_LIVE_TENNIS_SOCKET_URL);
                $result = json_decode($sportResult,true)['result'];

            }
            if($sportId==1){
                $sportResult = $this->httpGet(BR_LIVE_SOCCER_SOCKET_URL);
                $result = json_decode($sportResult,true)['result'];

            }
            $MstCode=[];
            if(count($result)){
                foreach ($result as $rec){
                    $MstCode[]=$rec['event']['id'];
                }
            }
            return $MstCode;

        }

		function getMatchLst($sportId,$seriesId=0){


            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewMatch');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }

            $userId = $this->session->userdata('user_id');
            $data['matchLst']  = $this->Modeleventlst->getMatchLst($sportId,$seriesId,$userId);

            $data['seriesLst'] = $this->Modeleventlst->getSeriesLst($sportId);



			//$data['getplayer']=$this->Modelcreatemaster->getPlayer();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}
		
		function updateScoreBoardId($matchId, $scoreboard_id){
			$data = array();
			if(strtolower($scoreboard_id)!="undefined"){
				$this->Modeleventlst->updateScoreBoardId($matchId, $scoreboard_id);
				$data = array('status'=>'success', 'message'=>'Scoreboard updated successfully!', 'scoreboard_id'=>$scoreboard_id);	
			}
			else{
				$data = array('status'=>'error', 'message'=>'Invalid scoreboard!');
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($data));	
		}

        function getMatchLstForMatchSetting($sportId,$seriesId=0,$fromDate=0,$toDate=0){

            $onlineMatch = $this->getOnlineMatchBySportId($sportId);


            $userId = $this->session->userdata('user_id');
            $matches  = $this->Modeleventlst->getMatchLstForMatchSetting($sportId,$seriesId,$userId,$fromDate,$toDate);


            $resultSeries = array();
            foreach($matches as $matche){
                $MstCode = $matche['MstCode'];
                if(in_array($MstCode, $onlineMatch)){
                    $matche['is_online'] = 'Y';
                }else{
                    $matche['is_online'] = 'N';
                }
                $resultSeries[] = $matche;
            }
            $data['matchLst'] = $resultSeries;
            $data['seriesLst'] = $this->Modeleventlst->getSeriesLst($sportId);



            //$data['getplayer']=$this->Modelcreatemaster->getPlayer();

            $this->output->set_content_type('application/json')->set_output(json_encode($data));

        }


        /**
		 * [getDeclareMatchResult declare match result]
		 * @param  [int]  $sportId  [sport id ]
		 * @param  integer $seriesId [series id]
		 * @return [json]            [result]
		 */
		function getDeclareMatchResult($sportId,$seriesId=0){

			$data['matchLst'] = $this->Modeleventlst->getBettedMatchLst($sportId,$seriesId);

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getSeriesLst($matchId)//sourabh 9-dec-2016
		{

			$onlineSeries = $this->getAllSeries();

			$seriesLst = $this->Modeleventlst->getSeriesLst($matchId);

			$resultSeries = array();
			foreach($seriesLst as $sLst){
				$temp = $sLst;
				$seriesId = $sLst['seriesId'];
				if(in_array($seriesId, $onlineSeries)){
					$temp['is_online'] = 'Y';
				}else{
					$temp['is_online'] = 'N';
				}
				$resultSeries[] = $temp;
			} 

			$data['seriesLst'] = $resultSeries;

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getMarketLst()	{
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewMarketSetting');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data['getMarketLst'] = $this->Modeleventlst->getMarketLst();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		/*Get market Data By MatchId From Database*/

		function matchMarketTypeLst(){

			$data['MatchMarket'] = $this->Modeleventlst->matchMarketLst();

			$data['getMatchFancy']=$this->Modeleventlst->matchFancyList();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

		}

		function matchMarketLstPublic(){

			$data['MatchMarket'] = $this->Modeleventlst->matchMarketLstPublic();

			$data['getMatchFancy']=$this->Modeleventlst->matchFancyListPublic();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

		}

		function matchMarketLst(){

			$data['MatchMarket'] = $this->Modeleventlst->matchMarketLst();

			$data['getMatchFancy']=$this->Modeleventlst->matchFancyList();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

		}

		/*End of Get market Data By MatchId From Database*/

		function testapi(){

			$this->APP_KEY;

			//$sessionToken = 'OMBoBRSomihCLZ2o8D2GKlNQHC731kGwS8/Vbq0C/EQ=';

			$sessionToken = $this->getTokenId(); 	
			 $allEventTypes = $this->getAllEventTypes($this->APP_KEY, $sessionToken); 
			 $horseRacingEventTypeId = $this->extractHorseRacingEventTypeId($allEventTypes);
			 $nextHorseRacingMarket = $this->getNextUkHorseRacingMarket($this->APP_KEY, $sessionToken, $horseRacingEventTypeId);
			 $this->printMarketIdAndRunners($nextHorseRacingMarket);
			 
			//$test=json_encode($nextHorseRacingMarket);

			//echo $test;

			//echo $allEventTypes = $this->getAllEventTypes($APP_KEY, $SESSION_TOKEN);

		}
		function getSeriesWithMatchData($sportId,$seriesId,$userId){
		   $matches =  $this->Modeleventlst->getMatchLst($sportId,$seriesId,$userId);
            $responce = [];
            $result = array();
            foreach ($matches as $matche) {
                $result[$matche['date']][] = $matche;
            }
            if(!empty($result)){
                foreach ($result as $key=>$rec){
                    $responce[]=['date'=>$key,'match'=>$rec];
                }
            }

		   echo json_encode($responce);die;
        }

        function getInPlayMatchBySportId($sportId,$userId){
		    $inpalyArray = [];
           $matches =  $this->Modeleventlst->getMatchLstBySportId($sportId,$userId);
           if(!empty($matches)){
               $marketIds =implode(',',array_column($matches, 'marketid'));
               $matchStatus = $this->httpGet(EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$marketIds);
           }
            if(!empty($matchStatus)){
                $matchStatusArray = json_decode($matchStatus,true);
                //echo count($matchStatusArray);die;
                foreach ($matchStatusArray as $matchRec){
                    if($matchRec['inPlay']==1 && $matchRec['status']=='OPEN'){

                        $inpalyArray[]= $matches[array_search($matchRec['id'],array_column($matches,'marketid'))];
                    }

                }

            }
            $seriesArray = $this->Modeleventlst->getSeriesLst($sportId);

            echo json_encode(['inPlay'=>$inpalyArray,'series'=>$seriesArray]);
		}

		function getMatchOfSport($sprtId=null,$seriesId=null)

		{

			if($sprtId==null){

				$sportId=$_POST['sprtId'];

			}else{

				$sportId=$sprtId;

			}
			if($sportId=='7'){ 
			$seriesId = '2010';
			}
        

		//	$sessionToken=$this->getTokenId();
			$sessionToken= '';
			
			if($sportId=='7'){ 
				$data['matchfrmApi'] = $this->getNextUkHorseRacingMarketswithoutrunner($this->APP_KEY, $sessionToken, $sportId);
				/*$array = json_decode( json_encode($array), true); 
				foreach($array as $row){
				$datta[] = $row['event'];
				}
					foreach ($datta as $val) {
					$newArr[$val['id']] = $val;    
					}
				$data['matchfrmApi'] = array_values($newArr); */
			}
			//$sessionToken = $this->session->userdata('TokenId');

			//$horseRacingEventTypeId = $this->getAllUkHorseRacingEvents($this->APP_KEY, $sessionToken, $sportId,$seriesId);
			else{
				
				$matchApi = $this->getAllUkHorseRacingEvents($this->APP_KEY, $sessionToken, $sportId,$seriesId);//sourabh 170105
                $matchManual = $this->Modeleventlst->GetManualMatchFrmDatabase($sportId,$seriesId);
                $data['matchfrmApi'] = array_merge($matchApi, $matchManual);
			
			}
			

			$data['matchfrmDataBase'] = $this->Modeleventlst->GetMatchFrmDatabase();//sourabh 170105
			  
			$this->output->set_content_type('application/json')->set_output(json_encode($data));//echo $matchName=json_encode($horseRacingEventTypeId);sourabh 170105
 		

		}
		
	    

		

		function getAllEventTypes($appKey, $sessionToken)

		{

			//parameter to Function //$appKey, $sessionToken

			/*Start Changes By Subodh Sir*/

			//if ($_POST['appKey'] != "")  

				//$appKey = $_POST['appKey'];

        

			//if ($_POST['sessionToken'] != "")  

				//$sessionToken = $_POST['sessionToken'];

			//print_r($_POST);

        

		/*end Changes of Session*/

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listEventTypes', '{"filter":{}}');

             //echo $jsonResponse;die;

		    return $jsonResponse;

        

		}

        

		function extractHorseRacingEventTypeId($allEventTypes)

		{

		    foreach ($allEventTypes as $eventType) {

		        if ($eventType->eventType->name == 'Horse Racing') {

		            return $eventType->eventType->id;

		        }

		    }

        

		}

        

		function getAllUkHorseRacingEvents($appKey, $sessionToken, $horseRacingEventTypeId,$seriesId){

		/*    $params = '{"filter":{"eventTypeIds":["'.$horseRacingEventTypeId.'"],"competitionIds":["'.$seriesId.'"]}}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listEvents', $params); */

		//	$url = BR_LIVE_MATCHES_URL."eventType=$horseRacingEventTypeId&seriesId=$seriesId";
			$url = BR_SUPER_AMDIN_URL."getMatches/$seriesId";
		    $matchResult = $this->httpGet($url);
		    $jsonResponse = json_decode($matchResult,true);

		/*	$jsonResponse = array();
		    $this->load->model('Modelseriesmst');
		    $checkSeries = $this->Modelseriesmst->findBySeries($seriesId);

		//    print_r($checkSeries);
		    if(!empty($checkSeries['match_json'])){
				$jsonResponse = json_decode($checkSeries['match_json'],true);		    
		    }else{

		    //	echo 'else';
		    //	$url = 'http://192.168.1.24:8080/betting/api/match.json';
		    	$url = BR_LIVE_MATCHES_URL."eventType=$horseRacingEventTypeId&seriesId=$seriesId";
		    //	echo $url; die;
		    	$matchResult = $this->httpGet($url);
		    	$jsonResponse = json_decode($matchResult,true);
		   // 	var_dump($matchResult);die;
		    	if(!empty($jsonResponse)){
					$this->Modelseriesmst->updateMatchJson($seriesId,array('match_json'=>$matchResult,'match_json_updated_on'=>date('Y-m-d H:i:s')));    
		    	}

		    } */
		    return $jsonResponse;
		}

        

		function getNextUkHorseRacingMarket($appKey, $sessionToken, $horseRacingEventTypeId)

		{ 	
			if($horseRacingEventTypeId == '7') {
			/*$params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"],
              "marketStartTime":{"from":"' . date('c') . '"}},
              "sort":"FIRST_TO_START",
              "maxResults":"200",
              "marketProjection":[ "COMPETITION",
			  "EVENT",           
			  "EVENT_TYPE", 
			  "RUNNER_DESCRIPTION",  
              "RUNNER_METADATA",     
			  "MARKET_START_TIME" 
			  ]}';*/
			  $params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"],
              "marketStartTime":{"from":"' . date('c') . '"}},
              "sort":"FIRST_TO_START",
              "maxResults":"800",
              "marketProjection":["EVENT"]}';
			}
			else {
				$params = '{"filter":{"eventIds":["' . $horseRacingEventTypeId . '"],"marketBettingTypes":["ODDS","ASIAN_HANDICAP_SINGLE_LINE"]},"maxResults":"100"}';
			}

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketCatalogue', $params); 
		    return $jsonResponse; 
		}
		
		
		function getNextUkHorseRacingMarketswithoutrunner($appKey, $sessionToken, $horseRacingEventTypeId)

		{ 
			$params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"],
              "marketStartTime":{"from":"' . date('c') . '"}},
              "sort":"FIRST_TO_START",
              "maxResults":"100",
              "marketProjection":["EVENT"]}';
		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listEvents', $params); 
		    return $jsonResponse; 
		}
 		function getNextUkHorseRacingMarket1($appKey, $sessionToken, $horseRacingEventTypeId)

		{

		/*,

		              "marketCountries":["GB"],

		              "marketTypeCodes":["WIN"],

		              "marketStartTime":{"from":"' . date('c') . '"}

		              "sort":"FIRST_TO_START",

		              "maxResults":"1",

		              "marketProjection":["RUNNER_DESCRIPTION"]*/			  

		    $params = '{"filter":{"eventIds":["' . $horseRacingEventTypeId . '"]},"maxResults":"10"}';

        

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketTypes', $params);

        

		    return $jsonResponse;

		}

        

		function printMarketIdAndRunners($nextHorseRacingMarket)

		{
 
			$nextHorseRacingMarket = json_encode($nextHorseRacingMarket);
			$nextHorseRacingMarket = json_decode($nextHorseRacingMarket);
			echo"<pre>";
			print_r($nextHorseRacingMarket);
			echo "</pre>";
			
			foreach($nextHorseRacingMarket as $nextHorseRacingMarket){
			
		  //  echo "MarketId: " . $nextHorseRacingMarket->marketId . "\n";

		   // echo "MarketName: " . $nextHorseRacingMarket->marketName . "\n\n";

         

			}

		}

        

		function getMarketBook($appKey, $sessionToken, $marketId)//Use to get match odds back and lay

		{

		    //$params = '{"marketIds":["' . $marketId . '"], "priceProjection":{"priceData":["EX_BEST_OFFERS"], "virtualise": "true"}}';

			$params = '{"marketIds":["' . $marketId . '"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}';

			

			

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

        

		    if($jsonResponse!=null)

				return $jsonResponse[0];

			else

				return $jsonResponse;

		}

        function printMarketIdRunnersAndPrices($nextHorseRacingMarket, $marketBook)
		{

			function printAvailablePrices($selectionId, $marketBook)
			{

				// Get selection
				foreach ($marketBook->runners as $runner)
					if ($runner->selectionId == $selectionId) break;

				echo "\nAvailable to Back: \n";
				foreach ($runner->ex->availableToBack as $availableToBack)
					echo $availableToBack->size . "@" . $availableToBack->price . " | ";

				echo "\n\nAvailable to Lay: \n";
				foreach ($runner->ex->availableToLay as $availableToLay)
					echo $availableToLay->size . "@" . $availableToLay->price . " | ";

			}


			echo "MarketId: " . $nextHorseRacingMarket->marketId . "\n";
			echo "MarketName: " . $nextHorseRacingMarket->marketName;

			foreach ($nextHorseRacingMarket->runners as $runner) {
				echo "\n\n\n===============================================================================\n";

				echo "SelectionId: " . $runner->selectionId . " RunnerName: " . $runner->runnerName . "\n";
				echo printAvailablePrices($runner->selectionId, $marketBook);
			}
		}

		/*function printMarketIdRunnersAndPrices($nextHorseRacingMarket, $marketBook)

		{ 

		    function printAvailablePrices($selectionId, $marketBook)

		    { 

		        // Get selection

		        foreach ($marketBook->runners as $runner)

		            if ($runner->selectionId == $selectionId) break;

        

		        echo "\nAvailable to Back: \n";

		        foreach ($runner->ex->availableToBack as $availableToBack)

		            echo $availableToBack->size . "@" . $availableToBack->price . " | ";

        

		        echo "\n\nAvailable to Lay: \n";

		        foreach ($runner->ex->availableToLay as $availableToLay)

		            echo $availableToLay->size . "@" . $availableToLay->price . " | ";

        

		    }

        

        

		    echo "MarketId: " . $nextHorseRacingMarket->marketId . "\n";

		    echo "MarketName: " . $nextHorseRacingMarket->marketName;

        

		    foreach ($nextHorseRacingMarket->runners as $runner) {

		        echo "\n\n\n===============================================================================\n";

        

		        echo "SelectionId: " . $runner->selectionId . " RunnerName: " . $runner->runnerName . "\n";

		        echo printAvailablePrices($runner->selectionId, $marketBook);

		    }

		}*/

        

		function placeBet($appKey, $sessionToken, $marketId, $selectionId)

		{

        

		    $params = '{"marketId":"' . $marketId . '",

		                "instructions":

		                     [{"selectionId":"' . $selectionId . '",

		                       "handicap":"0",

		                       "side":"BACK",

		                       "orderType":

		                       "LIMIT",

		                       "limitOrder":{"size":"1",

		                                    "price":"1000",

		                                    "persistenceType":"LAPSE"}

		                       }], "customerRef":"fsdf"}';

        

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'placeOrders', $params);

        

		    return $jsonResponse;

        

		}

        

		function printBetResult($betResult)

		{

		    echo "Status: " . $betResult->status;

        

		    if ($betResult->status == 'FAILURE') {

		        echo "\nErrorCode: " . $betResult->errorCode;

		        echo "\n\nInstruction Status: " . $betResult->instructionReports[0]->status;

		        echo "\nInstruction ErrorCode: " . $betResult->instructionReports[0]->errorCode;

		    } else

		        echo "Warning!!! Bet placement succeeded !!!";

		}

        

        
/*
		function sportsApingRequest($appKey, $sessionToken, $operation, $params)

		{

		    

        

		    $ch = curl_init();

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 	

		    curl_setopt($ch, CURLOPT_URL, "https://api.betfair.com/exchange/betting/rest/v1/$operation/");

		    curl_setopt($ch, CURLOPT_POST, 1);

		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		    curl_setopt($ch, CURLOPT_HTTPHEADER, array(

		        'X-Application: ' . $appKey,

		        'X-Authentication: ' . $sessionToken,

		        'Accept: application/json',

		        'Content-Type: application/json'

		    ));

        

		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        

		    $this->debug('Post Data: ' . $params);

		    $response = json_decode(curl_exec($ch));

		    $this->debug('Response: ' . json_encode($response));

        

		    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		    curl_close($ch);

        

		    if ($http_status == 200) {

		        return $response;

		    } else {

		        echo 'Call to api-ng failed: ' . "\n";

		        echo  'Response: ' . json_encode($response);

		        exit(-1);

		    }

        

        

		}   */

        

        

		function debug($debugString)

		{

		    $DEBUG=false;

		    if ($DEBUG)

		        echo $debugString . "\n\n";

		}


		function SetResult(){
            try {
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSetMatchResult');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$this->load->model('Modeltblbets');
			$this->load->model('Modelcreatemaster');

			$selectionId=$_POST['selectionId'];


            if(!empty($_POST['market_id'])){
                try {
                    $redis = new Redis();
                    $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                    $database = $this->db->database;
                    $key = $database . '_' . $_POST['market_id'] . '*';
                    $redis->delete($redis->keys($key));
                } catch (Exception $e) {
                    log_message('error',  now().' :- '.$e->getMessage());

                }

            }
            $matchId = $_POST['Match_id'];
            $marketId = $_POST['market_id'];

            if($selectionId==0){

                $uIds = $this->Modeltblbets->getMatchUser($matchId,$marketId);

				$condition=$this->Modeleventlst->SetAbandonedResult();



				$data['status']=array('error' => 0 ,'message' => 'Saved Successfully ...');


				$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

                /* update user balance and liablity */

                foreach($uIds as $uid){
                    $this->Modelcreatemaster->updateUserBalLiablity($uid);
                }


				$this->output->set_content_type('application/json')->set_output(json_encode($data));


			}
			else{

				$condition=$this->Modeleventlst->SetResult();
               
				if ($condition[0]['resultV']==0) {
					$data['status']=array('error' => 0 ,'message' => $condition[0]['retMess']);

					$matchId = $_POST['Match_id'];
					$marketId = $_POST['market_id']; 
					$this->load->model('Modeltblbets');
					$this->Modeltblbets->updateUserBalByMatch($matchId,$marketId);

					$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

                    /* update user balance and liablity */
                    $uIds = $this->Modeltblbets->getMatchUser($matchId,$marketId);
                    foreach($uIds as $uid){
                        $this->Modelcreatemaster->updateUserBalLiablity($uid);
                    }

					$this->output->set_content_type('application/json')->set_output(json_encode($data));	

				}else{

					$data['status']=array('error' => 0 ,'message' => $condition[0]['retMess']);

					$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

					$this->output->set_content_type('application/json')->set_output(json_encode($data));	

				}

			}
            } catch (Exception $e) {
                log_message('error', now().' :- '.$e->getMessage());

            }


		}

		function GetMatchOddsResult(){
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSetMatchResult');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

		}

		function DeleteMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy=0){

			$this->load->model('Modeltblbets');
			$this->load->model('Modelmatchmst');
			$this->load->model('Modeltblresult');

			$matchData = $this->Modelmatchmst->findByMatchId($matchId);
			if($matchData['hard_bet_deleted']==1){
                $data['status']=array('error' => true ,'message' => "This Match Bet Has Been Removed From Admin So You Can Not Roll Back It.");
            }elseif($matchData['bet_deleted']==1){
                $data['status']=array('error' => true ,'message' =>"First Of All Undo Bets And Than Roll Back");
            }else{
                $fancyId = $selectionId;
                if($selectionId==0 && $isFancy==0){                	

                    $this->Modeleventlst->DeleteAbandonedMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy);

                    $this->Modeltblbets->updateUserBalByMatch($matchId,$marketId);
                    $query1 = $this->db->query("call p_l_by_match($matchId)");

                    $data['status']=array('error' => 1 ,'message' => 'Saved Successfully ...');

                }else{

                	$resData = $this->Modeltblresult->findById($resId);

                	if($resData['result']==-1){

                		$this->Modeleventlst->DeleteAbandonedMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy);
                		

	                    $this->Modeltblbets->updateUserBalByMatchFancy($matchId,$fancyId);
	                    $query1 = $this->db->query("call p_l_by_match($matchId)");

	                    $data['status']=array('error' => 1 ,'message' => 'Saved Successfully ...');

                	}else{

                		$condition=$this->Modeleventlst->DeleteMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy);

	                    if ($condition[0]['iReturn']==1) {
	                        if($isFancy==1){
	                            $this->Modeltblbets->updateUserBalByMatchFancy($matchId,$selectionId);
	                        }else{
	                            $this->Modeltblbets->updateUserBalByMatch($matchId,$marketId);
	                        }

							$query1 = $this->db->query("call p_l_by_match($matchId)");                        
	                        $condition[0]['sMsg'] ="Roll Back Successfully... ";
	                    }
	                    $data['status']=array('error' => $condition[0]['iReturn'] ,'message' => $condition[0]['sMsg']);
                	}
                }

                try {
                    $redis = new Redis();
                    $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                    $this->load->model('Modelmatchfancy');
                    $result = $this->Modelmatchfancy->selectFancyById($fancyId);
                    $redis->set($this->db->database.'ind_' . $matchId . '_' . $fancyId, json_encode($result));
                    $redis->close();
                } catch (Exception $e) {
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getSeriesOfSport($sprtId=null)//sourabh 9-dec-2016

		{
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ViewSeriesMatch');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			if($sprtId==null){

				$sportId=$_POST['sprtId'];

			}else{

				$sportId=$sprtId;

			}



			$sessionToken=$this->getTokenId();

			//$horseRacingEventTypeId["SeriesData"] = $this->getSeries($this->APP_KEY, $sessionToken, $sportId);

			//$matchName=json_encode($horseRacingEventTypeId);

			//$matchName=$horseRacingEventTypeId;
			if($sportId=='7'){

				$data['seriesfrmApi'] = $this->getNextUkHorseRacingMarketswithoutrunner($this->APP_KEY, $sessionToken, $sportId);
				/*$array = json_decode( json_encode($array), true); 
				foreach($array as $row){
				$datta[] = $row['event'];
				}
					foreach ($datta as $val) {
					$newArr[$val['id']] = $val;    
					}
				$data['seriesfrmApi'] = array_values($newArr); */
			}
			else {
			/*	$url = 'http://18.130.124.176/super_admin/StagingApicontroller/testapi';
				$json = $this->httpGet($url);
				$data['seriesfrmApi'] = json_decode($json,true); */
				$dataApi = $this->getSeries($this->APP_KEY, $sessionToken, $sportId);
                $dataManual = $this->Modeleventlst->GetManualSeriesFrmDatabase();

                $data['seriesfrmApi']=array_merge($dataApi,$dataManual);
			}	
			$data['seriesfrmDataBase'] = $this->Modeleventlst->GetSeriesFrmDatabase();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

						

		} 
		 
		
		function filter_event($sportId){  
				$sessionToken=$this->getTokenId();
				$array = $this->getNextUkHorseRacingMarketswithoutrunner($this->APP_KEY, $sessionToken, $sportId); 
				$array = json_decode( json_encode($array), true); 
				foreach($array as $row){
				$datta[] = $row['event'];
				}
					foreach ($datta as $val) {
					$newArr[$val['id']] = $val;    
					}
					$array = array_values($newArr); 
		}

		function getAllSeries(){

		//	echo '<pre>';
			$result = array();
			$cricket = 4;
			//$url = BR_SUPER_AMDIN_URL.'getSeries/'.$cricket;
			$url='http://109.74.202.195/api/v1/betting_api/get_series_by_sport.php?sport_id='.$cricket;
			$sportResult = $this->httpGet($url);
			$result = json_decode($sportResult,true);		

			$tennis = 2;
			//$url = BR_SUPER_AMDIN_URL.'getSeries/'.$tennis;
			$url='http://109.74.202.195/api/v1/betting_api/get_series_by_sport.php?sport_id='.$tennis;
			$sportResult = $this->httpGet($url);
			$tennisResult = json_decode($sportResult,true);		
			$result = array_merge($result,$tennisResult);

			$soccer = 1;
			//$url = BR_SUPER_AMDIN_URL.'getSeries/'.$soccer;
			$url='http://109.74.202.195/api/v1/betting_api/get_series_by_sport.php?sport_id='.$soccer;
			$sportResult = $this->httpGet($url);
			$soccerResult = json_decode($sportResult,true);	
			$result = array_merge($result,$soccerResult);

		//	print_r($result);die;
			$onlineSeries = array();
			foreach($result as $data){
				if($data['is_online']=='Y'){
					$onlineSeries[] = $data['seriesId'];
				}
			}

		//	print_r($onlineSeries);die;

			return $onlineSeries;
		}

		// [{"srno":1,"seriesId":"11365612","SeriesName":"Test Matches"},{"srno":1,"seriesId":"7126733","SeriesName":"ICC Cricket World Cup 2019"},{"srno":1,"seriesId":"9998907","SeriesName":"Tamil Nadu Premier League"},{"srno":1,"seriesId":"10658342","SeriesName":"The Ashes (Series Markets)"},{"srno":1,"seriesId":"9991613","SeriesName":"County Championship Div 2 Matches"},{"srno":1,"seriesId":"9991615","SeriesName":"County Championship Div 1 Matches"},{"srno":1,"seriesId":"11893330","SeriesName":"T20 Blast"},{"srno":1,"seriesId":"11416933","SeriesName":"Caribbean Premier League"},{"srno":1,"seriesId":"11678489","SeriesName":"ICC World Twenty20"},{"srno":1,"seriesId":"11586749","SeriesName":"Test Series Markets"},{"srno":1,"seriesId":"11940941","SeriesName":"Global T20 Canada"},{"srno":1,"seriesId":"9962116","SeriesName":"One Day Internationals"},{"srno":1,"seriesId":"11418704","SeriesName":"Tour Matches"},{"srno":1,"seriesId":"9886504","SeriesName":"Womens One Day Internationals"}]
		
		function getSeries($appKey, $sessionToken, $horseRacingEventTypeId){

		/*  $params = '{"filter":{"eventTypeIds":["'.$horseRacingEventTypeId.'"]}}';
		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listCompetitions', $params); */

			$this->load->model('Modelsportmst');

		    if($horseRacingEventTypeId == 1){
		    		//$url = BR_SUPER_AMDIN_URL.'getSeries/'.$horseRacingEventTypeId;
					$url='http://109.74.202.195/api/v1/betting_api/get_series_by_sport.php?sport_id='.$horseRacingEventTypeId;
					$sportResult = $this->httpGet($url);
					$jsonResponse = json_decode($sportResult,true);		

				/*	$checkSports = $this->Modelsportmst->findBySports($horseRacingEventTypeId);
					if(!empty($checkSports['sport_json'])){
						$jsonResponse = json_decode($checkSports['sport_json'],true);		    
				    }else{
				    	$url = BR_SUPER_AMDIN_URL.'getSeries/'.$horseRacingEventTypeId;
						$sportResult = $this->httpGet($url);
						$jsonResponse = json_decode($sportResult,true);		
						if(!empty($jsonResponse)){
							$this->Modelsportmst->updateSportJson($horseRacingEventTypeId,array('sport_json'=>$sportResult,'sport_json_updated_on'=>date('Y-m-d H:i:s')));    
						}else{
							$checkSports = $this->Modelsportmst->findBySportJsons($horseRacingEventTypeId);
							if(!empty($checkSports['sport_json'])){
								$jsonResponse = json_decode($checkSports['sport_json'],true);		    
							}	
						}	
				    } */
			    
		    }elseif ($horseRacingEventTypeId == 2) {
		    		//$url = BR_SUPER_AMDIN_URL.'getSeries/'.$horseRacingEventTypeId;
					$url='http://109.74.202.195/api/v1/betting_api/get_series_by_sport.php?sport_id='.$horseRacingEventTypeId;
					$sportResult = $this->httpGet($url);
					$jsonResponse = json_decode($sportResult,true);		

					/*
					$checkSports = $this->Modelsportmst->findBySports($horseRacingEventTypeId);
					if(!empty($checkSports['sport_json'])){
						$jsonResponse = json_decode($checkSports['sport_json'],true);		    
				    }else{
				    	$url = BR_SUPER_AMDIN_URL.'getSeries/'.$horseRacingEventTypeId;
						$sportResult = $this->httpGet($url);
						$jsonResponse = json_decode($sportResult,true);		
						if(!empty($jsonResponse)){
							$this->Modelsportmst->updateSportJson($horseRacingEventTypeId,array('sport_json'=>$sportResult,'sport_json_updated_on'=>date('Y-m-d H:i:s')));    
						}else{
							$checkSports = $this->Modelsportmst->findBySportJsons($horseRacingEventTypeId);
							if(!empty($checkSports['sport_json'])){
								$jsonResponse = json_decode($checkSports['sport_json'],true);		    
							}	
						}
						
				    } */
			}elseif ($horseRacingEventTypeId == 4) {
					//$url = BR_SUPER_AMDIN_URL.'getSeries/'.$horseRacingEventTypeId;
					$url='http://109.74.202.195/api/v1/betting_api/get_series_by_sport.php?sport_id='.$horseRacingEventTypeId;
					$sportResult = $this->httpGet($url);
					$jsonResponse = json_decode($sportResult,true);		

				/*	$checkSports = $this->Modelsportmst->findBySports($horseRacingEventTypeId);
					if(!empty($checkSports['sport_json'])){
						$jsonResponse = json_decode($checkSports['sport_json'],true);		    
				    }else{
				    	$url = BR_SUPER_AMDIN_URL.'getSeries/'.$horseRacingEventTypeId;
						$sportResult = $this->httpGet($url);
						$jsonResponse = json_decode($sportResult,true);		
						if(!empty($jsonResponse)){  
							$this->Modelsportmst->updateSportJson($horseRacingEventTypeId,array('sport_json'=>$sportResult,'sport_json_updated_on'=>date('Y-m-d H:i:s')));    
						}else{
							$checkSports = $this->Modelsportmst->findBySportJsons($horseRacingEventTypeId);
							if(!empty($checkSports['sport_json'])){
								$jsonResponse = json_decode($checkSports['sport_json'],true);		    
							}	
						}
				    } */
			}
		/*    print_r($jsonResponse);
		    print_r($_SESSION);
		    die; */
		    return $jsonResponse;
		}

		function saveSportSeries()

		{
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('ManageSeriesMatch');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$condition=$this->Modeleventlst->saveSportSeries();
			
					
				if ($condition==0) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

					echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));

				}			

		}

		/**
		 * [saveAllCheckboxSportSeries This api is used to active all the match]
		 * @return [json] [success message]
		 */
		function saveAllCheckboxSportSeries(){
			$condition=$this->Modeleventlst->saveAllCheckboxSportSeries();
			echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));	
		}

		/**
		 * [saveSportSeriesDeSelectAll This api is used to de-active all the match]
		 * @return [json] [success message]
		 */
		function saveSportSeriesDeSelectAll(){
			$condition=$this->Modeleventlst->saveSportSeriesDeSelectAll();
			echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));
		}


		function getUserMatchLst($sportId)//sourabh 13-dec-2016

		{

			$data['matchLst'] = $this->Modeleventlst->getUserMatchLst($sportId);

			//{sourabh 161227

				if($sportId!=-1)//$sportId!=0 && //sourabh 170106

				{

					$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);

				/*	$sessionToken=$this->getTokenId();

					$data['matchOdds'] = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']); */

					$data['matchOdds'] = '';
					
					if(!empty($MarketId1[0]['ids'])){
						$data['matchOdds'] = $this->getMultiMarkets($MarketId1[0]['ids']);
					} 
					
				}

				else

					$data['matchOdds'] ="";

			//}sourabh 161227

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}


		function getMarketBookUser($appKey, $sessionToken, $marketId){

			$marketArr = explode(',', $marketId);
			$marketIdSlice = array_slice($marketArr, 0, 40);
			$marketIds = implode(',', $marketIdSlice);

		    $params = '{"marketIds":[' . $marketIds . '], "priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

		    return $jsonResponse;

		}

		function getUserMatchResult($useId,$usetype)//sourabh 13-dec-2016
        {
            $this->load->model('ModelUserRights');
            $userRole = $this->ModelUserRights->hasRole('MarketWatch');
            if($userRole['status']){
                return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
            }
			$data['matchRslt'] = $this->Modeleventlst->getUserMatchResult($useId,$usetype);

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getSelectionName($marketId,$sportId)//sourabh 161228

		{

				$data['RunnerValue']=$this->Modelcreatemaster->getSelectionName($sportId,$marketId);//sourabh 161226 change

		       	$this->output->set_content_type('application/json')->set_output(json_encode($data));


		}
		function matchMatchFancy(){//sourbh 170103 new

			$data['getMatchFancy']=$this->Modeleventlst->matchFancyList(1);

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

		}

        function SessionFancyData(){//sourbh 170103 new

           $data['SessionFancyData']=$this->Modeleventlst->SessionFancyData(1);

           $this->output->set_content_type('application/json')->set_output(json_encode($data));

        }

        function GetCollectionReport($userId){
            $data['CollectionReport']=$this->Modeleventlst->GetCollectionReport($userId);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
	}
