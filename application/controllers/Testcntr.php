<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Testcntr extends CI_Controller {

		function log(){
            $this->load->model('Betentrymodel');
            $this->Betentrymodel->updateUnMatchDataOnRedis();die;
				$arr = array('code'=>1,'msg'=>'testing');
				$this->savelog($arr);
		}

		function savelog($response=NULL){
			$url = (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
	        $get = print_r($_GET, true);
	        $post = "\n";
	        $post .= json_encode($_POST);
	        $post .= "\n RESPONSE";
	        $post .= "\n";
	        $post .= json_encode($response);

	        $msg = ' URL ' . $url . PHP_EOL .' GET ' . $get . PHP_EOL . ' POST ' . $post . PHP_EOL   ;
	        $filename = 'logs.txt';
	        $myfile = fopen($filename, "a") or die("Unable to open file!");
	        $txt = "<!---------------------[" . date("Y/m/d h:i:s") . "]----------------------->" . PHP_EOL . $msg . '<!------------------------------End-------------------------------->' . PHP_EOL ;
	        fwrite($myfile, $txt);
	        fclose($myfile);
	        return false;
		}


        function httpGet($url){
            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  curl_setopt($ch,CURLOPT_HEADER, false);
            $output=curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($httpcode!=200){
                $output = json_encode(array());
            }

            curl_close($ch);
            return $output;
            
        }

        function httpGetArr($url){
            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $output=curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode!=200){
                $output = json_encode(array());
            }

            $outputArr = json_decode($output,true);

            curl_close($ch);

            return $outputArr;


        }

        function httpPost($url,$params){
            $postData = '';
            foreach($params as $k => $v)
            {
                $postData .= $k . '='.$v.'&';
            }
            $postData = rtrim($postData, '&');

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, count($postData));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            $output=curl_exec($ch);

            curl_close($ch);
            $this->output->set_content_type('application/json')->set_output(json_encode($output));

        }


		function getDataByIdPaging($userId,$userType,$search=NULL,$pageNo=1,$limit=DEFAULT_PAGING_LIMIT){
			$this->load->model('Modeltest');
			$data = $this->Modeltest->getDataByIdPaging($userId,$userType,$search,$pageNo,$limit);
        	$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

		function userBal($userId){
			$this->load->model('Modelcreatemaster');
			$data['betLibility'] = $this->Modelcreatemaster->getUserBalance($userId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

		function updAllUserPatnership(){
			$useType = array('1','2');
			$this->load->model('Modelcreatemaster');
			$this->load->model('Modelcreatemaster');
			$this->db->select('mstrid as usecode');
			$this->db->from('createmaster');
			$this->db->where_in('usetype', $useType);
			$query = $this->db->get();
			$users = $query->result_array();
			foreach($users as $user){
				$this->Modelcreatemaster->updatePatner($user['usecode']);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($users));
		}

		function updAllUserBal(){
			$this->load->model('Modelcreatemaster');
			$this->db->select('mstrid as usecode');
			$this->db->from('createmaster');
			$query = $this->db->get();
			$users = $query->result_array();
			foreach($users as $user){
				$this->Modelcreatemaster->updateUserBalLiablity($user['usecode']);
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($users));
		}

        function updSingleUserBal($userId){
            $this->load->model('Modelcreatemaster');
            $this->Modelcreatemaster->updateUserBalLiablity($userId);
            $this->output->set_content_type('application/json')->set_output(json_encode($userId));
        }

		function updateBalOnMatch($matchId,$marketId){
			$this->load->model('Modeltblbets');
			$this->Modeltblbets->updateUserBalByMatch($matchId,$marketId);
		}

		function updateBalOnSessionMatch($matchId,$fancyId){
			$this->load->model('Betentrymodel');
			$this->Betentrymodel->updateBalByMatchSession($matchId,$fancyId);
		}

		function testSessionFancy(){

			$backRun = rand(50,400);
			$layRun = $backRun + 3;

			$sessFancy = array('session'=>array('0'=>array('SelectionId'=>29,'RunnerName'=>'DB DRUMMOND RUN (KENT VS HAMP)ADV','LayPrice1'=>$layRun,'LaySize1'=>95,'BackPrice1'=>$backRun,'BackSize1'=>110,'GameStatus'=>'','FinalStatus'=>'OPEN'),'1'=>array('SelectionId'=>29,'RunnerName'=>'10 Overs','LayPrice1'=>$layRun - 5,'LaySize1'=>95,'BackPrice1'=>$backRun + 5,'BackSize1'=>110,'GameStatus'=>'','FinalStatus'=>'OPEN'),'2'=>array('SelectionId'=>29,'RunnerName'=>'15 Overs','LayPrice1'=>$layRun - 10,'LaySize1'=>95,'BackPrice1'=>$backRun +10 ,'BackSize1'=>110,'GameStatus'=>'','FinalStatus'=>'OPEN'),'3'=>array('SelectionId'=>29,'RunnerName'=>'20 Overs','LayPrice1'=>$layRun - 15,'LaySize1'=>95,'BackPrice1'=>$backRun + 15,'BackSize1'=>110,'GameStatus'=>'','FinalStatus'=>'OPEN')));

			print_r($sessFancy);die;


			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($sessFancy));
		}

		function testSessionFancyArr(){
			$fancyJson = '{"market":[{"marketId":"1.144813000","inplay":true,"totalMatched":45226432.32,"totalAvailable":143559,"priceStatus":"1","events":[{"SelectionId":5901,"RunnerName":"Kent","LayPrice1":"0.0","LaySize1":"$ 0","LayPrice2":"0.0","LaySize2":"$ 0","LayPrice3":"0.0","LaySize3":"$ 0","BackPrice1":"1000","BackSize1":"$ 83","BackPrice2":"36","BackSize2":"$ 57","BackPrice3":"11","BackSize3":"$ 412"},{"SelectionId":3961,"RunnerName":"Hampshire","LayPrice1":"1.01","LaySize1":"$ 57529","LayPrice2":"1.02","LaySize2":"$ 19186","LayPrice3":"1.03","LaySize3":"$ 1571","BackPrice1":"0.0","BackSize1":"$ 0","BackPrice2":"0.0","BackSize2":"$ 0","BackPrice3":"0.0","BackSize3":"$ 0"}]}],"session":[{"SelectionId":"29","RunnerName":"DB DRUMMOND RUN (KENT VS HAMP)ADV","LayPrice1":"-","LaySize1":"SUSPENDED","BackPrice1":"-","BackSize1":"SUSPENDED","GameStatus":"SUSPENDED","FinalStatus":"SUSPENDED"}],"commentary":""}';

			$fanyArr = json_decode($fancyJson,true);
			print_r($fanyArr);
			die;
		}

		function get_series(){
			$url = 'https://www.mint777.com/api/exchange/eventType/4';
			$res = $this->httpGet($url);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output($res);
		}

		function get_multimarket($sportId){

			$this->load->model('Modeleventlst');
			$MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);

			if(!empty($MarketId1[0]['ids'])){
                $getMarkets = $this->getMultiMarkets($MarketId1[0]['ids']);
                if(!empty($getMarkets)){
                    $data['matchOdds'] = json_decode($getMarkets,true);
                }
			}


		/*	$url = 'https://www.mint777.com/api/exchange/eventType/4';
			$res = $this->httpGet($url);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output($res); */
		}

		function updateDefaultRunners(){

			echo '<pre>';
		$json =	'[{"id":7445679,"name":"Kristie Ahn","hdp":0,"sort":1,"back":[{"price":3.25,"size":13.03},{"price":3.2,"size":9.99},{"price":3.15,"size":20.9}],"lay":[{"price":3.35,"size":84.88},{"price":3.4,"size":214.3},{"price":3.45,"size":90.29}]},{"id":9634670,"name":"Dayana Yastremska","hdp":0,"sort":2,"back":[{"price":1.43,"size":198.83},{"price":1.42,"size":513.1},{"price":1.41,"size":220.93}],"lay":[{"price":1.45,"size":29.2},{"price":1.46,"size":21.9},{"price":1.47,"size":154.87}]}]';

		$jsonArr = json_decode($json,true);

		print_r($jsonArr);

		$matchId = 28887744;

		$this->load->model('Modelmatchmst');
		$this->Modelmatchmst->updateDefaultRunner($matchId);
		die;


		$this->load->model('Modeltblselection');

		$matchArr = $this->Modeltblselection->findByMatchId($matchId);

		$defaultRunners = array();
		foreach($matchArr as $mArr){
			$temp = array();
			$temp['id'] = $mArr['selectionId'];
			$temp['name'] = $mArr['selectionName'];
			$temp['hdp'] = 0;
			$temp['sort'] = 2;
			$temp['back'] = array('0'=>array('price'=>1.43,'size'=>198.83),'1'=>array('price'=>1.42,'size'=>198.83),'2'=>array('price'=>1.42,'size'=>198.83));
			$temp['lay'] = array('0'=>array('price'=>1.43,'size'=>198.83),'1'=>array('price'=>1.42,'size'=>198.83),'2'=>array('price'=>1.42,'size'=>198.83));
			$defaultRunners[] = $temp;
		}

		print_r($defaultRunners);
		die;

		}
/*
		function updateMarketRunners($marketId=NULL){

			$this->load->model('Modelmarket');
			$marketUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$marketId;
			$marketJson = $this->httpGet($marketUrl);
			$marketArr = json_decode($marketJson,true);

			foreach($marketArr as $mArr){
				if(!empty($mArr['runners'])){
					$marketId = $mArr['id'];
					$this->Modelmarket->update($marketId,array('market_runner_json'=>json_encode($mArr['runners'])));
				}
			}
			return false;
		}*/


		function updateALLMarketRunners(){

			$this->load->model('Modelmarket');
			$this->db->select('market.Id as market_id,matchmst.matchName');
			$this->db->from('market');
			$this->db->join('matchmst','market.matchId=matchmst.MstCode','left');
			$this->db->where('matchmst.active',1);
			$query = $this->db->get();
			$matchArr = $query->result_array();

			//echo "<pre>";print_r($matchArr);die;

			foreach($matchArr as $mArr){
				$marketId = $mArr['market_id'];

                $this->updateMarketRunners($marketId);

				/*$marketUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$marketId;


				$marketArr = $this->httpGetArr($marketUrl);



				if(!empty($marketArr)){ echo 'if';
					foreach($marketArr as $mArr){
						if(!empty($mArr['runners'])){
							$marketId = $mArr['id'];

							$this->Modelmarket->update($marketId,array('market_runner_json'=>json_encode($mArr['runners'])));
						}
					}


				}else{ echo 'else';
					$this->Modelmarket->updateDefaultRunnerMarket($marketId);
				}*/



			}

		//	print_r($matchArr);die;



		}

		function updateMatchRunners($matchId=NULL){

			$this->load->model('Modelmatchmst');

			$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;
			$cricketJson = $this->httpGet($cricketUrl);
			$cricketArr = json_decode($cricketJson,true);

			$runners = array();

			if(!empty($cricketArr['result'])){
				foreach($cricketArr['result'] as $key => $cArr){
					if($cArr['mtype']=='MATCH_ODDS'){
						$runners[$cArr['groupById']] = $cArr['runners'];
					}
				}
			}

			$tennisUrl = BR_LIVE_TENNIS_SOCKET_URL;
			$tennisJson = $this->httpGet($tennisUrl);

			$tennisArr = json_decode($tennisJson,true);

			if(!empty($tennisArr['result'])){
				foreach($tennisArr['result'] as $key => $cArr){
					if($cArr['mtype']=='MATCH_ODDS'){
						$runners[$cArr['groupById']] = $cArr['runners'];
					}
				}
			}

			$soccerUrl = BR_LIVE_SOCCER_SOCKET_URL;
			$soccerJson = $this->httpGet($soccerUrl);
			$soccerArr = json_decode($soccerJson,true);
			if(!empty($soccerArr['result'])){
				foreach($soccerArr['result'] as $key => $cArr){
					if($cArr['mtype']=='MATCH_ODDS'){
						$runners[$cArr['groupById']] = $cArr['runners'];
					}
				}
			}

			$allMatchArr = $this->Modelmatchmst->findActiveMatches($matchId);

			if(!empty($allMatchArr)){

				foreach($allMatchArr as $matchArr){

					$matchId = $matchArr['MstCode'];

					if($matchArr['SportID']==4){

						if(!empty($runners[$matchId])){
							$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners[$matchId])));
						}else{
							$this->Modelmatchmst->updateDefaultRunner($matchId);
						}

					}elseif($matchArr['SportID']==2){

						if(!empty($runners[$matchId])){
							$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners[$matchId])));
						}else{
							$this->Modelmatchmst->updateDefaultRunner($matchId);
						}

					}elseif($matchArr['SportID']==1){

						if(!empty($runners[$matchId])){
							$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners[$matchId])));
						}else{
							$this->Modelmatchmst->updateDefaultRunner($matchId);
						}
					}

				}
			}
			return false;
		}

		function updateRunnerSelection($marketId=NULL){

			$this->load->model('Modeleventlst');

			$marketUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$marketId;
			$marketJson = $this->httpGet($marketUrl);
			$marketArr = json_decode($marketJson,true);

			foreach($marketArr as $mArr){
				if(!empty($mArr['runners'])){
					$marketId = $mArr['id'];
					$data['market_runner_json'] = json_encode($mArr['runners']);
					foreach($mArr['runners'] as $runner){
						$data['selection_json'][] = array('selectionId'=>$runner['id'],'runnerName'=>$runner['name']);
					}

					$selectionJson = json_encode($data['selection_json']);

					$this->Modeleventlst->SaveSelection($selectionJson,$mArr['groupById'],$mArr['eventTypeId'],$mArr['id']);
				}
			}

		}

		function test(){

		/*	echo 'test1';
			die; */

			$matchId = 28887744;

			$this->load->model('Modelmatchmst');

			$matchArr = $this->Modelmatchmst->findByMatchId($matchId);


		//	echo '<pre>';
		//	print_r($matchArr);

			if(!empty($matchArr)){

				if($matchArr['SportID']==4){

					$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;
					$cricketJson = $this->httpGet($cricketUrl);
					$cricketArr = json_decode($cricketJson,true);

					$matchArr = array();
					$sessionMatchArr = array();
					if(!empty($cricketArr['result'])){
						foreach($cricketArr['result'] as $key => $cArr){
							$runners = array();
							if($cArr['groupById']==$matchId){
								$runners = $cArr['runners'];
							}
						}
					}
					$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners)));

				}elseif($matchArr['SportID']==2){

					$tennisUrl = BR_LIVE_TENNIS_SOCKET_URL;
					$tennisJson = $this->httpGet($tennisUrl);

					$tennisArr = json_decode($tennisJson,true);

				//	print_r($tennisArr);
					if(!empty($tennisArr['result'])){
						foreach($tennisArr['result'] as $key => $cArr){

				//			print_r($cArr['runners']);

							if($cArr['groupById']==$matchId){
								$runners = array();
								$runners = $cArr['runners'];
							}
						}
					}

					$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners)));

				}elseif($matchArr['SportID']==1){

					$soccerUrl = BR_LIVE_SOCCER_SOCKET_URL;
					$soccerJson = $this->httpGet($soccerUrl);
					$soccerArr = json_decode($soccerJson,true);
					if(!empty($soccerArr['result'])){
						foreach($soccerArr['result'] as $key => $cArr){
							$runners = array();
							if($cArr['groupById']==$matchId){
								$runners = $cArr['runners'];
							}


						}
					}



					if(!empty($runners)){
						$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners)));
					}


				}
			}

			echo 'test<pre>';
			print_r($runners);die;

		}

		function updatedselectiontblByResult(){

			$this->load->model('Modeleventlst');
			$this->load->model('Modeltblbets');

		/*	$selectionName = $this->Modeltblbets->findSelectionName('1.149077014','5458207');
			print_r($selectionName);die; */

			$this->db->select('market.Id as market_id,market.matchId,market.sportsId,market.createdOn,market.market_runner_json,matchmst.matchName');
			$this->db->from('market');
			$this->db->join('tblselection','market.Id=tblselection.marketId','left');
			$this->db->join('matchmst','market.matchId = matchmst.MstCode','left');
			$this->db->where('tblselection.id IS NULL');
		//	$this->db->where('market.Id','1.149077014');
		//	$this->db->where('market.market_runner_json IS NOT NULL');
			$this->db->order_by('market.createdOn DESC');

		//	echo '<pre>';
			$query = $this->db->get();
			$matchArr = $query->result_array();

			$marketList = array();
			$marketIds = array();
			foreach($matchArr as $mArr){

				$market_id = $mArr['market_id'];

				$matchId = $mArr['matchId'];
				$sportsId = $mArr['sportsId'];

				$url = "http://176.58.100.128:9198/api/betfairApi?marketid=$market_id";
				$selectionJson = $this->httpGet($url);
				$selectionArr = json_decode($selectionJson,true);
			
				$runners = $selectionArr[0]['runners'];

				if(!empty($runners)){

					for($i=1;$i<=3;$i++){
						$selection_id = 'selectionId'.$i;
						$runner_name_id = 'runnerName'.$i;

						$key = $i - 1;
						$postData[$selection_id] = !empty($runners[$key]['selectionId']) ? $runners[$key]['selectionId'] : '';
						$postData[$runner_name_id] = !empty($runners[$key]['name']) ? $runners[$key]['name'] : '';
					}

					$selectionData = array();
					if(!empty($postData['selectionId1'])){

						$selectionName = $this->Modeltblbets->findSelectionName($market_id,$postData['selectionId1']);

						$selectionData[] = array('selectionId'=>$postData['selectionId1'],'runnerName'=>$selectionName);
					}

					if(!empty($postData['selectionId2'])){

						$selectionName = $this->Modeltblbets->findSelectionName($market_id,$postData['selectionId2']);

						$selectionData[] = array('selectionId'=>$postData['selectionId2'],'runnerName'=>$selectionName);
					}

					if(!empty($postData['selectionId3'])){

						$selectionName = $this->Modeltblbets->findSelectionName($market_id,$postData['selectionId3']);

						$selectionData[] = array('selectionId'=>$postData['selectionId3'],'runnerName'=>$selectionName);
					}

				//	print_r($selectionData); die;

					$selectionJson = json_encode($selectionData);

					$this->Modeleventlst->SaveSelection($selectionJson,$matchId,$sportsId,$market_id);

				//	echo $market_id;

				}

				//	die;

				

			}

			echo '<pre>';
			print_r($matchArr);
			die;

		}
			
		function updatedselectiontbl(){

			$this->load->model('Modeleventlst');
			$this->db->select('market.Id as market_id,market.matchId,market.sportsId,market.createdOn,market.market_runner_json,matchmst.matchName');
			$this->db->from('market');
			$this->db->join('tblselection','market.Id=tblselection.marketId','left');
			$this->db->join('matchmst','market.matchId = matchmst.MstCode','left');
			$this->db->where('tblselection.id IS NULL');
			$this->db->where('market.market_runner_json IS NOT NULL');
			$this->db->order_by('market.createdOn DESC');

			$query = $this->db->get();
			$matchArr = $query->result_array();

			$marketList = array();
			$marketIds = array();
			foreach($matchArr as $mArr){

				$market_id = $mArr['market_id'];

				$matchId = $mArr['matchId'];
				$sportsId = $mArr['sportsId'];
				$marketRunnerJson = $mArr['market_runner_json'];

				if(!empty($marketRunnerJson)){

					$runners = json_decode($marketRunnerJson,true);

					if(!empty($runners)){

						for($i=1;$i<=3;$i++){
							$selection_id = 'selectionId'.$i;
							$runner_name_id = 'runnerName'.$i;

							$key = $i - 1;
							$postData[$selection_id] = !empty($runners[$key]['id']) ? $runners[$key]['id'] : '';
							$postData[$runner_name_id] = !empty($runners[$key]['name']) ? $runners[$key]['name'] : '';
						}

						$selectionData = array();
						if(!empty($postData['selectionId1'])){
							$selectionData[] = array('selectionId'=>$postData['selectionId1'],'runnerName'=>$postData['runnerName1']);
						}

						if(!empty($postData['selectionId2'])){
							$selectionData[] = array('selectionId'=>$postData['selectionId2'],'runnerName'=>$postData['runnerName2']);
						}

						if(!empty($postData['selectionId3'])){
							$selectionData[] = array('selectionId'=>$postData['selectionId3'],'runnerName'=>$postData['runnerName3']);
						}

						$selectionJson = json_encode($selectionData);

						$this->Modeleventlst->SaveSelection($selectionJson,$matchId,$sportsId,$market_id);

						echo $market_id;

					}

				}

			}

			echo '<pre>';
			print_r($matchArr);
			die;

		}


		function updatedseltbl(){

			$this->load->model('Modeleventlst');
			

			$this->db->select('market.Id as market_id,market.matchId,market.sportsId,market.createdOn');
			$this->db->from('market');
			$this->db->join('tblselection','market.Id=tblselection.marketId','left');
			$this->db->where('tblselection.id IS NULL');		
			$this->db->order_by('market.createdOn DESC');
			$this->db->limit(5);


			$query = $this->db->get();
			$matchArr = $query->result_array();	

		//	print_r($matchArr);die;

			
			$marketList = array();
			$marketIds = array();
			foreach($matchArr as $mArr){
			//	$marketIds[] = $mArr['market_id'];
				$market_id = $mArr['market_id'];

				$matchId = $mArr['matchId'];
				$sportsId = $mArr['sportsId'];

			//	$marketList[$mArr['market_id']] = array('matchId'=>$mArr['matchId'],'sportsId'=>$mArr['sportsId']);

							//API URL
			$url = 'http://18.130.213.12/matchbook/v1/get_selection';

			//create a new cURL resource
			$ch = curl_init($url);


			$payload = array("market_json" => json_encode($marketIds));

		//	print_r($marketIds);
		//	print_r($payload);

			//attach encoded JSON string to the POST fields
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

			//set the content type to application/json
		//	 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:form-data'));

			//return response instead of outputting
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			//execute the POST request
			$result = curl_exec($ch);


			//close cURL resource
			curl_close($ch);

			if(!empty($result)){

				$resArr = json_decode($result,true);

			//	print_r($resArr);die;

				if(!empty($resArr['data'])){
				foreach($resArr['data'] as $res){

				//	print_r($res);die;

					if(!empty($res['runners'])){

						$runners = $res['runners'];

						for($i=1;$i<=3;$i++){
							$selection_id = 'selectionId'.$i;
							$runner_name_id = 'runnerName'.$i;
						//	echo $selection_id;
							$key = $i - 1; 
							$postData[$selection_id] = !empty($runners[$key]['selectionId']) ? $runners[$key]['selectionId'] : '';
							$postData[$runner_name_id] = !empty($runners[$key]['runnerName']) ? $runners[$key]['runnerName'] : '';
						}

						$selectionData = array();
						if(!empty($postData['selectionId1'])){
							 $selectionData[] = array('selectionId'=>$postData['selectionId1'],'runnerName'=>$postData['runnerName1']);
						}

						if(!empty($postData['selectionId2'])){
							$selectionData[] = array('selectionId'=>$postData['selectionId2'],'runnerName'=>$postData['runnerName2']);
						}	

						if(!empty($postData['selectionId3'])){
							$selectionData[] = array('selectionId'=>$postData['selectionId3'],'runnerName'=>$postData['runnerName3']);
						}	

					//	print_r($selectionData);die;
						$selectionJson = json_encode($selectionData);
									
					//	$this->Modeleventlst->SaveSelection($selectionJson,$marketList[$res['marketId']]['matchId'],$marketList[$res['marketId']]['sportsId'],$res['marketId']);

						$this->Modeleventlst->SaveSelection($selectionJson,$matchId,$sportsId,$market_id);

						echo $market_id;

					}

				}

				}

			}

			}

		
			print_r($matchArr);
			die;



		}

		// Testcntr/removeoldbets
		function removeoldbets(){

			$this->load->model('Modelcreatemaster');			
			$result = $this->Modelcreatemaster->insertChipsAfterBetTruncateNot();
			print_r($result);die;

		}



		function updateAllMatchProfitLoss(){
			
			$this->db->select('matchId');
			$this->db->from('bet_entry');
			$this->db->group_by('matchId');

			$query = $this->db->get();
			$sessionArr = $query->result_array();

			$matchIds = array();
			foreach($sessionArr as $sArr){
				$matchIds[] = $sArr['matchId'];
			}

			$this->db->select('MatchId');
			$this->db->from('tblbets');
			$this->db->group_by('MatchId');

			$query = $this->db->get();
			$betArr = $query->result_array();
			foreach($betArr as $bArr){
				$matchIds[] = $bArr['MatchId'];
			}

			$matchIds = array_unique($matchIds);

			foreach($matchIds as $key=>$value){
				$this->db->query("call p_l_by_match($value)");
			}
			print_r($matchIds);die;


			

		}


		function saveOddsProfitLoss11111(){
            $userquery =$this->db->query("select mstrid,parentId from createmaster where usetype=3");
            $users = $userquery->result_array();

            foreach ($users as $user){
                $userId = $user['mstrid'];
                $parentId = $user['parentId'];

                $tblpartnerquery =$this->db->query("select Admin,Master,Dealer  from tblpartner where UserID=$userId limit 1");

                $tblpartnerresult = $tblpartnerquery->row_array();
                $admin = $tblpartnerresult['Admin'];
                $master = $tblpartnerresult['Master'];
                $dealer = $tblpartnerresult['Dealer'];

                $marketquery =$this->db->query("select MarketId,MatchId from tblbets where UserId=".$userId);
                $markets = $marketquery->result_array();

                if (!empty($markets)) {
                    foreach ($markets as $market) {
                        $MarketId = $market['MarketId'];
                        $matchId = $market['MatchId'];
                        $query = $this->db->query("call SP_OddsProfitLossNew($userId,3,$matchId,$MarketId)");
                        //echo "call SP_getOddsProfitLoss($userId,3,$matchId,$MarketId)";die;
                        $res = $query->result_array();

                        $query->next_result();
                        $query->free_result();
                        //print_r($res);
                        if (!empty($res)) {

                            $q1 = $this->db->query("select id from odds_profit_loss where userId =$userId and marketId=$MarketId ");

                            $rowData = $q1->result_array();
                            //echo "Ddsfjsdkfj";die;
                            if (!empty($rowData)) {
                                //update existing values
                                foreach ($res as $key => $value) {
                                    $this->db->set('winValue', 'winValue + ' . $value['winValue'], FALSE);
                                    $this->db->set('lossValue', 'lossValue + ' . $value['lossValue'], FALSE);
                                    $this->db->where('userId', $userId);
                                    $this->db->where('marketId', $MarketId);
                                    $this->db->where('selectionId', $value['SelectionId']);
                                    $this->db->limit(1);
                                    $this->db->update('odds_profit_loss');
                                }
                            } else {
                                //insert new values
                                foreach ($res as $key => $value) {
                                    $insertData = array(
                                        'userId' => $userId,
                                        'parentId' => $parentId,
                                        'matchId' => $matchId,
                                        'selectionId' => $value['SelectionId'],
                                        'marketId' => $MarketId,
                                        'teamType' => $value['teamType'],
                                        'selectionName' => $value['selectionName'],
                                        'winValue' => $value['winValue'],
                                        'lossValue' => $value['lossValue'],
                                        'admin' => $admin,
                                        'master' => $master,
                                        'dealer' => $dealer,
                                    );

                                    $this->db->insert('odds_profit_loss', $insertData);
                                    //echo $this->db->last_query();die;
                                }
                            }
                        }
                    }
                }
            }

        }
        function saveOddsProfitLoss(){

		    $betQuery  =  $this->db->query(" select * from tblbets");
            $betRes = $betQuery->result_array();
            foreach ($betRes as $betRe){
                $userId = $betRe['UserId']; $betId=$betRe['MstCode']; $matchId=$betRe['MatchId']; $marketId=$betRe['MarketId'];

                $query = "SELECT 
				`b`.`MstCode` AS `bet_id`, `a`.`matchId` AS `matchId`, `a`.`selectionId` AS `selectionId`, `a`.`marketId` AS `marketId`, `a`.`selectionName` AS `selectionName`, `b`.`UserId` AS `userId`, `b`.`ParantId` AS `parentId`, `a`.`teamType` AS `teamType`, `b`.`Admin` AS `admin`, `b`.`Master` AS `master`, `b`.`Dealer` AS `dealer`, 
				CASE
					WHEN (`b`.`SelectionId` = `a`.`selectionId`)
					THEN
						(CASE WHEN (`b`.`isBack` = 0) THEN `b`.`P_L` ELSE -(`b`.`P_L`) END)
					ELSE
						0
				END AS `winValue`,
				CASE
					WHEN (`b`.`SelectionId` = `a`.`selectionId`)
					THEN
						0
					ELSE
						(CASE WHEN (`b`.`isBack` = 0) THEN -(`b`.`Stack`) ELSE `b`.`Stack` END)
				END AS `lossValue`				 
			FROM 
				`tblselection` `a` 
				INNER JOIN `tblbets` `b` ON(`a`.`marketId` = `b`.`MarketId` AND `b`.`IsMatched` = 1)	
			WHERE 
				b.userId = $userId AND b.MstCode = $betId;";

                $query =$this->db->query($query);
                $res = $query->result_array();

                if(!empty($res)){

                    $this->db->select("id");
                    $this->db->from('odds_profit_loss');
                    $this->db->where('userId', $userId);
                    $this->db->where('marketId', $marketId);
                    $this->db->limit(1);
                    $q = $this->db->get();
                    $rowCount = $q->num_rows();

                    if($rowCount > 0){
                        //update existing values
                        foreach ($res as $key => $value) {
                            $this->db->set('winValue', 'winValue + ' . $value['winValue'], FALSE);
                            $this->db->set('lossValue', 'lossValue + ' . $value['lossValue'], FALSE);
                            $this->db->where('userId', $userId);
                            $this->db->where('marketId', $marketId);
                            $this->db->where('selectionId', $value['selectionId']);
                            $this->db->limit(1);
                            $this->db->update('odds_profit_loss');
                        }
                    }else{
                        //insert new values
                        foreach ($res as $key => $value) {
                            $insertData = array(
                                'userId' => $value['userId'],
                                'parentId' => $value['parentId'],
                                'matchId' => $value['matchId'],
                                'selectionId' => $value['selectionId'],
                                'marketId' => $value['marketId'],
                                'selectionName' => $value['selectionName'],
                                'teamType' => $value['teamType'],
                                'winValue' => $value['winValue'],
                                'lossValue' => $value['lossValue'],
                                'admin' => $value['admin'],
                                'master' => $value['master'],
                                'dealer' => $value['dealer'],
                            );

                            $this->db->insert('odds_profit_loss', $insertData);
                        }
                    }
                }
            }



            return true;
        }

		
}