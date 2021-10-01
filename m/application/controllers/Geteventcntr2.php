<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class TestoddsCtrl extends CI_Controller {

		//public $APP_KEY = " asfqjbpDSPULqMZ1";

		public $APP_KEY = BETFAIR_APP_KEY;//"MCQjQ1s9SVgVIsbP"
	

		function __construct() {

			parent::__construct();



			$this->load->model('Modeleventlst');

			$this->load->model('Modelcreatemaster');

			$_POST = json_decode(file_get_contents('php://input'), true);

			       

			$node1=$this->session->userdata('user_id');

			if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
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

		function GetSportFrmDatabase(){

			$data['sportData'] = $this->Modeleventlst->getSportData();

			$data['getplayer']=$this->Modelcreatemaster->getPlayer();

	       	$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getMarketOfMatch($MatchId=null){

			if ($_POST['MatchId']!=null) {

				$matchID=$_POST['MatchId'];

			}

			else{

				$matchID=$MatchId;

			}

        	$sessionToken=$this->getTokenId();

			

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

			$sessionToken=$this->getTokenId();

			

			//$horseRacingEventTypeId = $this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);170131

			//echo $markeetName=json_encode($horseRacingEventTypeId);170131

			

			$data['MarketRunn'] = "shakti";//170131

			$data['MatchOddsVolVal']=$sessionToken;//170201

			echo $markeetName=json_encode($data);

		}

		function getBackLaysOfMarketSelectionName($MarketId=null,$userId=null,$userType=null,$matchId=null)//sourabh 161226 change

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

			$sessionToken=$this->getTokenId();

			//if($userId==null)$userId1=0;else $userId1=$userId;

				//$data['MarketRunner']=$this->getMarketBook($this->APP_KEY, $sessionToken, $MarketId1);//sourabh 170130

				$data['runnerSlName']=$this->getNextUkHorseRacingMarket2($this->APP_KEY, $sessionToken, $MarketId1);//sourabh 170130

				$data['RunnerValue']=$this->Modelcreatemaster->sumOfOdds($MarketId1,$userId1,$userType1,$matchId1);

				//$data['RunnerValue']=$_POST['userId'];
				$data['MarketData']=$this->Modelcreatemaster->getMarketInfo($MarketId1);

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

			$data['runner'] = $this->Modeleventlst->GetSelectionFrmDatabase($marketId);

			

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

			$condition=$this->Modeleventlst->saveSportMatch();

				if ($condition==0) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

				echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));

				}			

		}

		

		function saveMatchMarket(){ 

			$condition=$this->Modeleventlst->saveMatchMarket();

				if ($condition==true) {

        

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

        

				}else{

        

					echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));

				}			

		} 
		function saveMatchMarketType(){

        

			$condition=$this->Modeleventlst->saveMatchMarketType();

				if ($condition) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

					echo json_encode(array('error' => 1 ,'message' => 'Record Already Exits...'));

				}			

		}

		function getMatchLst($sportId,$seriesId=0){

			

			$data['matchLst'] = $this->Modeleventlst->getMatchLst($sportId,$seriesId);

			$data['getplayer']=$this->Modelcreatemaster->getPlayer();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getSeriesLst($matchId)//sourabh 9-dec-2016

		{

		$data['seriesLst'] = $this->Modeleventlst->getSeriesLst($matchId);

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getMarketLst()	{

			$data['getMarketLst'] = $this->Modeleventlst->getMarketLst();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		/*Get market Data By MatchId From Database*/

		function matchMarketTypeLst(){

			$data['MatchMarket'] = $this->Modeleventlst->matchMarketLst();

			$data['getMatchFancy']=$this->Modeleventlst->matchFancyList();

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
        

			$sessionToken=$this->getTokenId();
			
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
				
				$data['matchfrmApi'] = $this->getAllUkHorseRacingEvents($this->APP_KEY, $sessionToken, $sportId,$seriesId);//sourabh 170105
			
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

        

		function getAllUkHorseRacingEvents($appKey, $sessionToken, $horseRacingEventTypeId,$seriesId)

		{

        

		    $params = '{"filter":{"eventTypeIds":["'.$horseRacingEventTypeId.'"],"competitionIds":["'.$seriesId.'"]}}';

			

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listEvents', $params);

        

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
				$params = '{"filter":{"eventIds":["' . $horseRacingEventTypeId . '"]},"maxResults":"100"}';
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

		    $params = '{"marketIds":["' . $marketId . '"], "priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

			//$params = '{"marketIds":["' . $marketId . '"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":false},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}';

			

			

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

        

        

		}

        

        

		function debug($debugString)

		{

		    $DEBUG=false;

		    if ($DEBUG)

		        echo $debugString . "\n\n";

		}

		function SetResult(){

        

			$condition=$this->Modeleventlst->SetResult();

				if ($condition[0]['resultV']==0) {

        

					$data['status']=array('error' => 0 ,'message' => $condition[0]['retMess']);

					$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

					$this->output->set_content_type('application/json')->set_output(json_encode($data));	

					

        

				}else{

					$data['status']=array('error' => 0 ,'message' => $condition[0]['retMess']);

					$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

					$this->output->set_content_type('application/json')->set_output(json_encode($data));	

				}

					

		}

		function GetMatchOddsResult(){

			$data['MatchOddsResult'] = $this->Modeleventlst->GetMatchOddsResult();

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	

		}

		function DeleteMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy){



            $condition=$this->Modeleventlst->DeleteMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy);

            $data['status']=array('error' => $condition[0]['iReturn'] ,'message' => $condition[0]['sMsg']);

            $this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getSeriesOfSport($sprtId=null)//sourabh 9-dec-2016

		{

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
				$data['seriesfrmApi']=$this->getSeries($this->APP_KEY, $sessionToken, $sportId);
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
		
		function getSeries($appKey, $sessionToken, $horseRacingEventTypeId)//sourabh 9-dec-2016

		{

        

		    $params = '{"filter":{"eventTypeIds":["'.$horseRacingEventTypeId.'"]}}';

			

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listCompetitions', $params);

        

		    return $jsonResponse;

		}

		function saveSportSeries()//sourabh 9-dec-2016

		{

			$condition=$this->Modeleventlst->saveSportSeries();
			
					
				if ($condition==0) {

					echo json_encode(array('error' => 0 ,'message' => 'Record Added Successfully...'));

				}else{

					echo json_encode(array('error' => 1 ,'message' => 'Record Deactive Successfully...'));

				}			

		}

		function getUserMatchLst($sportId)//sourabh 13-dec-2016

		{

			$data['matchLst'] = $this->Modeleventlst->getUserMatchLst($sportId);

			//{sourabh 161227

				if($sportId!=-1)//$sportId!=0 && //sourabh 170106

				{

					$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);

					$sessionToken=$this->getTokenId();

					$data['matchOdds'] = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);

				}

				else

					$data['matchOdds'] ="";

			//}sourabh 161227

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		function getMarketBookUser($appKey, $sessionToken, $marketId)//userdashboard sourabh 161227

		{

		    $params = '{"marketIds":[' . $marketId . '], "priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

		    return $jsonResponse;

		}

		function getUserMatchResult($useId,$usetype)//sourabh 13-dec-2016

		{

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
          
        function test11(){

$data['getMatchFancy']='shakti';

			$this->output->set_content_type('application/json')->set_output(json_encode($data));	
}

	}