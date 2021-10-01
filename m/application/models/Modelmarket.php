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
		$this->db->select('sportsId Sport_id,matchId Match_id,sportmst.name sportName,matchmst.matchName matchName,market.Name MarketName');
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

	function resultDeclareMarketId(){
		$this->db->select('market.Id marketId,tblresult.resId,matchmst.matchName');
		$this->db->from('market');
		$this->db->join('matchmst','market.matchId=matchmst.MstCode','left');
		$this->db->join('tblresult','market.Id=tblresult.marketId','left');
		$this->db->where('tblresult.resId IS NULL');		
		$this->db->order_by("RAND()");
		$this->db->limit(5);  
		$query = $this->db->get();
		return $query->result_array();	
	}
}