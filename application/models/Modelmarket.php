<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelmarket extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function findByMatchId($matchId=0){
		$this->db->select('Id marketId,Name marketName');
		$this->db->from('market');
		$this->db->where('matchId',$matchId);		
		$query = $this->db->get();
		return $query->result_array();	
	}

	function findByMarketId($marketId=0){
		$this->db->select('sportsId Sport_id,matchId Match_id,sportmst.name sportName,matchmst.matchName matchName,market.Name MarketName,market.max_bet_liability,market.max_market_liability,market.max_market_profit,isManualMatchOdds,isBetAllowedOnManualMatchOdds,maxStack,minStack');
		$this->db->from('market');
		$this->db->join('sportmst','market.sportsId=sportmst.id');
		$this->db->join('matchmst','market.matchId=matchmst.MstCode');
		$this->db->where('market.Id',$marketId);		
		$query = $this->db->get();
		return $query->row_array();	
	}

	function checkMarketId($matchId=0){
		$this->db->select('Id marketId');
		$this->db->from('market');
		$this->db->where('matchId',$matchId);	
		$this->db->where('Name','Match Odds');	
		$this->db->where('active',1);	
		$query = $this->db->get();
		return $query->row_array();	
	}

	function findMarketIdByMatch($matchId=0){
		$this->db->select('Id marketId');
		$this->db->from('market');
		$this->db->where('matchId',$matchId);	
		$this->db->where('Name','Match Odds');	
		$query = $this->db->get();
		return $query->row_array();	
	}


	function findAllMarketIdByMatch($matchId=0){
		$this->db->select('GROUP_CONCAT(market.Id) as marketId');
		$this->db->from('market');
		$this->db->where('matchId',$matchId);		
		$query = $this->db->get();
		return $query->row_array();	
	}

	function findAllActiveMarketId(){
		$this->db->select('GROUP_CONCAT(market.Id) as marketId');
		$this->db->from('market');
		$query = $this->db->get();
		return $query->row_array();	
	}

	function resultDeclareMarketId(){
		$this->db->select('market.Id marketId,tblresult.resId,matchmst.matchName');
		$this->db->from('market');
		$this->db->join('matchmst','market.matchId=matchmst.MstCode','left');
		$this->db->join('tblresult','market.Id=tblresult.marketId','left');
		$this->db->where('tblresult.resId IS NULL');	
		$this->db->where('matchmst.MstDate < NOW()');
		$this->db->order_by("RAND()");
		$this->db->limit(5);

		$query = $this->db->get();
		return $query->result_array();	
	}

	function saveMarket($data){

	    $Modeleventlst = $this->model_load_model('Modeleventlst');

		$this->db->trans_start();
		if($data['SportsId'] == '7'){
			$insertData = array('Id'=> $data['marketId'],'Name'=> $data['marketName'],'matchId'=> $data['MatchId'],'sportsId'=> $data['SportsId'],'totalmatched'=> $data['totalMatched'],
			'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> '210','HelperID'=> 0,'market_runner_json'=>$data['market_runner_json']);
		}
		else {
		$insertData = array('Id'=> $data['marketId'],'Name'=> $data['marketName'],'matchId'=> $data['MatchId'],'sportsId'=> $data['SportsId'],'totalmatched'=> 0,
			'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> $data['seriesId'],'HelperID'=> 0,'market_runner_json'=>$data['market_runner_json']);
		}
		
		$MatchName=$Modeleventlst->getMatchNameById($data['MatchId']);
		
		$Name=$MatchName[0]->matchName;
		
		$query=$this->db->insert('market', $insertData);
		
		$GetMarket= $data['marketName']."__".$Name;
		$menuoption1 = array('menCode'=> $data['marketId'],'menName'=> $GetMarket,'menDesc'=> $GetMarket,'mstType'=> '8');
		$query1=$this->db->insert('menuoption', $menuoption1);

		$selectionJson = json_encode($data['selection_json']);
						
		$Modeleventlst->SaveSelection($selectionJson,$insertData['matchId'],$insertData['sportsId'],$insertData['Id']);

		$this->db->trans_complete();

		return true;
	}

	function update($marketId=NULL,$updateArr=NULL){
		$this->db->where('Id',$marketId);
        $this->db->update('market', $updateArr);
        return true; 
	} 

	function checkActiveMarket($marketId=NULL){
		$this->db->select('active,visibility');
		$this->db->from('market');
		$this->db->where('Id',$marketId);
		$this->db->where('active',1);
		$this->db->where('visibility',1);
		$query = $this->db->get();
		$count = $query->num_rows();
		if($count==0){
			return true;
		}else{
			return false;
		}
	}

	function updateDefaultRunnerMarket($marketId=NULL){

		$tblSelectionModel = $this->model_load_model('Modeltblselection');	

		$matchArr = $tblSelectionModel->findByMarketId($marketId);

		$defaultRunners = array();
		foreach($matchArr as $mArr){
			$temp = array();
			$temp['id'] = (int)$mArr['selectionId'];
			$temp['selectionId'] = (int)$mArr['selectionId'];
			$temp['name'] = $mArr['selectionName'];
			$temp['hdp'] = 0;
			$temp['sort'] = 2;
			$temp['back'] = array('0'=>array('price'=>'--','size'=>'--'),'1'=>array('price'=>'--','size'=>'--'),'2'=>array('price'=>'--','size'=>'--'));
			$temp['lay'] = array('0'=>array('price'=>'--','size'=>'--'),'1'=>array('price'=>'--','size'=>'--'),'2'=>array('price'=>'--','size'=>'--'));
			$defaultRunners[] = $temp;
		}



		if(!empty($defaultRunners)){
			$defaultRunnersJson = json_encode($defaultRunners);
			$this->update($marketId,array('market_runner_json'=>$defaultRunnersJson));
		}

	}
}