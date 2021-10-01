<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Testcntr extends CI_Controller {
		function __construct() {
		    parent::__construct();
		}

		function log(){
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

		function updateMatchRunners($matchId=NULL){

			$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;	
			$cricketJson = $this->httpGet($cricketUrl);
			$cricketArr = json_decode($cricketJson,true);

			$runners = array();
			
			if(!empty($cricketArr['result'])){
				foreach($cricketArr['result'] as $key => $cArr){
					$runners[$cArr['groupById']] = $cArr['runners'];
				}
			}

			$tennisUrl = BR_LIVE_TENNIS_SOCKET_URL;	
			$tennisJson = $this->httpGet($tennisUrl);

			$tennisArr = json_decode($tennisJson,true);

			if(!empty($tennisArr['result'])){
				foreach($tennisArr['result'] as $key => $cArr){
					$runners[$cArr['groupById']] = $cArr['runners'];
				}
			} 

			$soccerUrl = BR_LIVE_SOCCER_SOCKET_URL;	
			$soccerJson = $this->httpGet($soccerUrl);
			$soccerArr = json_decode($soccerJson,true);
			if(!empty($soccerArr['result'])){
				foreach($soccerArr['result'] as $key => $cArr){
					$runners[$cArr['groupById']] = $cArr['runners'];
				}
			}

			$this->load->model('Modelmatchmst');

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
			return true;
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
}