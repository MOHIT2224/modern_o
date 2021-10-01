<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class GetPublicBetscntr extends CI_Controller {

		//public $APP_KEY = " asfqjbpDSPULqMZ1";
		//public $APP_KEY = "WaIiEmhVft0FXzxr";
		public $APP_KEY = BETFAIR_APP_KEY;

		function __construct() {

			parent::__construct();

			$this->load->model('Modeleventlst');

			$this->load->model('Modelcreatemaster');

			$_POST = json_decode(file_get_contents('php://input'), true);

/*			$node1=$this->session->userdata('user_id');
			if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());} */

		}

		/**
		 * [debug debug the code]
		 * @param  [string] $debugString [debug string]
		 * @return [string]              [resposne]
		 */
		function debug($debugString){

		    $DEBUG=false;

		    if ($DEBUG)

		        echo $debugString . "\n\n";

		}

		/**
		 * [sportsApingRequest sport api ]
		 * @param  [string] $appKey       [api key]
		 * @param  [string] $sessionToken [session token]
		 * @param  [string] $operation    [operation]
		 * @param  [array] $params       [post fields]
		 * @return [json]               [response]
		 */
		function sportsApingRequest($appKey, $sessionToken, $operation, $params){

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


		/**
		 * [getMarketBookUser user dashboard bets]
		 * @param  [string] $appKey       [api key]
		 * @param  [string] $sessionToken [session token]
		 * @param  [int] $marketId     [market id]
		 * @return [json]               [response]
		 */
		function getMarketBookUser($appKey, $sessionToken, $marketId){

			$marketArr = explode(',', $marketId);
			$marketIdSlice = array_slice($marketArr, 0, 40);
			$marketIds = implode(',', $marketIdSlice);

		    $params = '{"marketIds":[' . $marketIds . '], "priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

		    return $jsonResponse;

		}

		/**
		 * [getBetfairMarket betfair market matches]
		 * @param  [string] $appKey       [api key]
		 * @param  [string] $sessionToken [session token]
		 * @param  [int] $marketId     [market id]
		 * @return [json]               [response]
		 */
		function getBetfairMarket($appKey, $sessionToken, $marketId){

		    $params = '{"priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketBook');

		    return $jsonResponse;

		}


		/**
		 * [getMatchLst Show match odds without login on login page]
		 * @param  [int] $sportId [sport id]
		 * @return [json]          [response]
		 */
		function getMatchLst($sportId){

			$data['matchLst'] = $this->Modeleventlst->getUserMatchLst($sportId);

				//{sourabh 161227
				//$sportId!=0 && //sourabh 170106
				if($sportId!=-1){

					$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);

				//	$sessionToken=$this->betfairToken();

				//	$data['matchOdds'] = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
				
			/*	 $json = '[{"marketId":1.145283209,"status":"OPEN","totalMatched":135461,"runners":[{"ex":{"availableToBack":[{"price":1,"size":2},{"price":1,"size":2},{"price":1,"size":2}],"availableToLay":[{"price":1,"size":2},{"price":1,"size":2},{"price":1,"size":2}]}}]},{"marketId":1.145440553,"status":"OPEN","totalMatched":135461,"runners":[{"ex":{"availableToBack":[{"price":1,"size":2},{"price":1,"size":2},{"price":1,"size":2}],"availableToLay":[{"price":1,"size":2},{"price":1,"size":2},{"price":1,"size":2}]}}]}]';
			
					$data['matchOdds'] = json_decode($json,true); */

					$data['matchOdds'] = '';

					if(!empty($MarketId1[0]['ids'])){
						$data['matchOdds'] = $this->getMultiMarkets($MarketId1[0]['ids']);
					}  
				}
				else
					$data['matchOdds'] = '';

			//}sourabh 161227

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}


		/**
		 * [getBetfairMatchLst Show match odds from betfair]
		 * @return [json]          [response]
		 */
		function getBetfairMatchLst(){
			
			$sessionToken = $this->betfairToken();

			$matchLst = array();

			$marketIds = '';

			$cricketMatches = $this->betfairMatchBySportId(4);

			foreach($cricketMatches as $circketMatch){
				$marketIds[] = $circketMatch->marketId;
				$data['matchLst'][] = array("matchName" => $circketMatch->event->name ,"HeadName" => "","TypeID" => "0","matchid" =>  $circketMatch->event->id,"marketid" => $circketMatch->marketId,"MstDate"=> $circketMatch->event->openDate,"oddsLimit"=> "0","sportname"=> $circketMatch->eventType->name,"SportId" => $circketMatch->eventType->id);
			}  

			$soccerMatches = $this->betfairMatchBySportId(1);

			foreach($soccerMatches as $soccerMatch){
				$marketIds[] = $soccerMatch->marketId;
				$data['matchLst'][] = array("matchName" => $soccerMatch->event->name ,"HeadName" => "","TypeID" => "0","matchid" =>  $soccerMatch->event->id,"marketid" => $soccerMatch->marketId,"MstDate"=> $soccerMatch->event->openDate,"oddsLimit"=> "0","sportname"=> $soccerMatch->eventType->name,"SportId" => $soccerMatch->eventType->id);
			}  

			$tennisMatches = $this->betfairMatchBySportId(2);

			foreach($tennisMatches as $tennisMatch){
				$marketIds[] = $tennisMatch->marketId;
				$data['matchLst'][] = array("matchName" => $tennisMatch->event->name ,"HeadName" => "","TypeID" => "0","matchid" =>  $tennisMatch->event->id,"marketid" => $tennisMatch->marketId,"MstDate"=> $tennisMatch->event->openDate,"oddsLimit"=> "0","sportname"=> $tennisMatch->eventType->name,"SportId" => $tennisMatch->eventType->id);
			}  



			if(!empty($marketIds)){
				$marketIdsStr = implode(',', $marketIds);
				$data['matchOdds'] = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $marketIdsStr);
			}

		    $this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		/**
		 * [getBetfairMatchBySportId Get all matches by sport Id]
		 * @param  [int] $sportId [sport id]
		 * @return [json]          [All match with market ids]
		 */
		function getBetfairMatchBySportId($sportId){
			
			$sessionToken = $this->betfairToken();
			
			$matches = $this->betfairMatchBySportId($sportId);

			$data = array();

			$marketIds = '';

			foreach($matches as $match){
				$marketIds[] = $match->marketId;
				$data['matchLst'][] = array("matchName" => $match->event->name ,"HeadName" => "","TypeID" => "0","matchid" =>  $match->event->id,"marketid" => $match->marketId,"MstDate"=> $match->event->openDate,"oddsLimit"=> "0","sportname"=> $match->eventType->name,"SportId" => $match->eventType->id);
			}  

			if(!empty($marketIds)){
				$marketIdsStr = implode(',', $marketIds);
				$data['matchOdds'] = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $marketIdsStr);
			}

		    $this->output->set_content_type('application/json')->set_output(json_encode($data));

		}


		/**
		 * [getUserMatchLst Get all matches by sport Id]
		 * @param  [int] $sportId [sport id]
		 * @return [json]          [All match with market ids]
		 */
		function getUserMatchLst($sportId){

			$data['matchLst'] = $this->Modeleventlst->getUserMatchLst($sportId);

			//{sourabh 161227

				if($sportId!=-1)//$sportId!=0 && //sourabh 170106

				{

					$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);

				/*	$sessionToken= $this->betfairToken();

					$data['matchOdds'] = $this->getMarketBookUser($this->APP_KEY, $sessionToken,  $MarketId1[0]['ids']); */

					$data['matchOdds'] = '';

					if(!empty($MarketId1[0]['ids'])){
						$data['matchOdds'] = $this->getMultiMarkets($MarketId1[0]['ids']);
					}  
					
				/*	if(!empty($MarketId1[0]['ids'])){
						$getMarkets = $this->getMultiMarkets($MarketId1[0]['ids']);
						if(!empty($getMarkets)){
							$data['matchOdds'] = json_decode($getMarkets,true);
						}
					}  */

				}

				else

					$data['matchOdds'] ="";

			//}sourabh 161227

			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}

		/**
		 * [getSelectionName get match odds by market ids]
		 * @param  [float] $marketId [market id]
		 * @param  [int] $matchId  [match id]
		 * @return [json]           [match odds]
		 */
		function getSelectionName($marketId,$matchId){

				$data['RunnerValue']=$this->Modelcreatemaster->getSelectionName($matchId,$marketId);//sourabh 161226 change

		       	$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}


		/**
		 * [getBetfairSelectionName Get match team names]
		 * @param  [float] $marketId [market id]
		 * @return [json]           [team selection names]
		 */
		function getBetfairSelectionName($marketId=0){

			$matchLst = array();

			$getMatchNames = $this->betfairTeamByMarketId($marketId);

			$data = array();

			foreach($getMatchNames as $getMatchName){
				$marketIds[] = $match->marketId;
				$data['matchLst'][] = array("matchName" => $match->event->name ,"HeadName" => "","TypeID" => "0","matchid" =>  $match->event->id,"marketid" => $match->marketId,"MstDate"=> $match->event->openDate,"oddsLimit"=> "0","sportname"=> $match->eventType->name,"SportId" => $match->eventType->id);
			}  

		/*	
			"id": "1634",
            "sportId": "4",
            "matchId": "28652577",
            "selectionId": "448",
            "marketId": "1.141835708",
            "result": null,
            "selectionName": "New Zealand",
            "teamType": "1"  */

		}



		/**
		 * [betfairToken this function is used to get session token from betfair and store it in session variable]
		 * @return [string] [session token]
		 */
		function betfairToken(){

		//	$this->load->library('session');

			$sessionToken = '';

			if(!empty($this->session->userdata('public_betfair_token'))){
				$sessionToken = $this->session->userdata('public_betfair_token');
			}else{
				$this->load->model('Modelcreatemaster');
				$sessionToken = $this->Modelcreatemaster->findBetfairToken();
				if(!empty($sessionToken)){
					$this->session->set_userdata('public_betfair_token', $sessionToken);
				}
			}

			return $sessionToken;

		}

		/**
		 * [betfairMatchBySportId get matches by sport id]
		 * @param  [int] $sportId [cricket -> 4 , Soccer -> 2 , Tennis -> 1]
		 * @return [array]          [result]
		 */
		function betfairMatchBySportId($sportId){

			$sessionToken = $this->betfairToken();

			$params = '{"filter":{"eventTypeIds":["'.$sportId.'"],"marketTypeCodes":["MATCH_ODDS"]},"maxResults":"10","marketProjection":["EVENT","EVENT_TYPE"]}';

			$matches = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $params);

		/*	print_r($matches);
			die; */

			return $matches;

		}



		function betfairTeamByMarketId($marketId=0){

			$sessionToken = $this->betfairToken();

			$params = '{"filter":{"marketIds":["'.$marketId.'"]},"maxResults":"1","marketProjection":["EVENT","EVENT_TYPE","RUNNER_DESCRIPTION"]}';

			$teamNames = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $params);

			return $teamNames;

		}

		/**
		 * [DisplayMsgOnHeader Show message on home page]
		 */
		function DisplayMsgOnHeader(){
			$this->load->model('Betentrymodel');
			$data['marqueMsg']=$this->Betentrymodel->DisplayMsgOnHeader();
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
		}

}