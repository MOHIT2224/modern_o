<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BetfairApicontroller extends CI_Controller {

	var $globalUserId;
	var $globalUserType;
	public $APP_KEY = BETFAIR_APP_KEY;

	function __construct() {
			header('Access-Control-Allow-Origin: *');
	        parent::__construct();
            $_POST = json_decode(file_get_contents('php://input'), true);
	}

	function indian_session_fancy($matchId=NULL){

		$this->load->model('Modelmatchfancy');

		$sessionFancyJson = $this->Modelmatchfancy->getFancyByMatchId($matchId);

		$tempSessionFancy = array();
		foreach($sessionFancyJson as $sfJson){
			$tempSessionFancy[] = json_decode($sfJson['betfair_session_json']);
		}

		$sessionFancyArr = array('session'=>$tempSessionFancy);

		$response["code"] = 0;
		$response["error"] = false;
		$response["message"] = 'Session fancy lsiting';
		$response["data"] = $sessionFancyArr;

		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
	}

	function live_session_fancy($marketId=0){
		$this->output->set_status_header(200)->set_content_type('application/json')->set_output($this->getIndianSessionOdds($marketId));
	}

	function socket(){

		$this->load->model('Modeleventlst');
		$MarketId1 = $this->Modeleventlst->getUserMatchOdds(0);

		print_r($MarketId1);die;

		if(!empty($MarketId1[0]['ids'])){
			$marketIdsStr = explode(',', $MarketId1[0]['ids']);
			return $this->multiple_threads_request($marketIdsStr);
		}else{
			return json_decode(array());	
		}  
	}

}