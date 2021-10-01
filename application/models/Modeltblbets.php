<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblbets extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function getMatchUser($matchId,$marketId){

		$cmModel = $this->model_load_model('Modelcreatemaster');	

		$this->db->select('bet.UserId user_id,bet.ParantId dealer_id,dealer.parentId master_id');
		$this->db->from('tblbets bet');
		$this->db->join('createmaster dealer', 'bet.ParantId = dealer.mstrid', 'LEFT');
		$this->db->where('MatchId',$matchId);
		$this->db->where('MarketId',$marketId);
		$this->db->group_by('bet.UserId');
		$query = $this->db->get();
		$users = $query->result_array();

		$userIds = array();
		foreach($users as $user){
			$userIds[] = $user['user_id'];
			$userIds[] = $user['dealer_id'];
			$userIds[] = $user['master_id'];
		}
		$uIds = array_unique($userIds);

		return $uIds;
	}
	
	function updateUserBalByMatch($matchId,$marketId){

		$cmModel = $this->model_load_model('Modelcreatemaster');	

		$this->db->select('bet.UserId user_id,bet.ParantId dealer_id,dealer.parentId master_id');
		$this->db->from('tblbets bet');
		$this->db->join('createmaster dealer', 'bet.ParantId = dealer.mstrid', 'LEFT');
		$this->db->where('MatchId',$matchId);
		$this->db->where('MarketId',$marketId);
		$this->db->group_by('bet.UserId');
		$query = $this->db->get();
		$users = $query->result_array();

		$userIds = array();
		foreach($users as $user){
			$userIds[] = $user['user_id'];
			$userIds[] = $user['dealer_id'];
			$userIds[] = $user['master_id'];
		}
		$uIds = array_unique($userIds);

		foreach($uIds as $uid){
			$cmModel->updateUserBalLiablity($uid);
		}
		
	}

	function updateUserBalByMatchFancy($matchId,$fancyId){

		$cmModel = $this->model_load_model('Modelcreatemaster');	

		$this->db->select('bet.userId user_id,bet.parantId dealer_id,dealer.parentId master_id');
		$this->db->from('bet_entry bet');
		$this->db->join('createmaster dealer', 'bet.parantId = dealer.mstrid', 'LEFT');
		$this->db->where('matchId',$matchId);
		$this->db->where('fancyId',$fancyId);
		$this->db->group_by('bet.userId');
		$query = $this->db->get();
		$users = $query->result_array();

		$userIds = array();
		foreach($users as $user){
			$userIds[] = $user['user_id'];
			$userIds[] = $user['dealer_id'];
			$userIds[] = $user['master_id'];
		}
		$uIds = array_unique($userIds);
		foreach($uIds as $uid){
			$cmModel->updateUserBalLiablity($uid);
		}
		
	}

	function findById($id=NULL){
		$this->db->select('bet.MarketId,bet.SelectionId,bet.Odds,bet.isBack,bet.IsMatched,matchmst.SportID');
		$this->db->from('tblbets bet');
		$this->db->join('matchmst', 'bet.MatchId = matchmst.MstCode', 'LEFT');
		$this->db->where('bet.MstCode',$id);
		$query = $this->db->get();
	//	echo $this->db->last_query();
		$row = $query->row_array();
		return $row;
	}

	/**
	 * [deleteBet delete bet entry]
	 * @param  integer $id [primary key of bet table]
	 * @return NULL     
	 */
	function deleteBet($id=0){
	//	echo $id;die;
		if($id){
			$query = $this->db->query("DELETE FROM tblbets WHERE MstCode = $id");
		//	var_dump($query);die;
		}
	}

	function update($id=NULL,$updateArr=NULL){
		$this->db->where('MstCode',$id);
        $this->db->update('tblbets', $updateArr);
        return true; 
	} 

	function sumMarketLiablity($userId,$marketId){
			$this->db->select("SUM(Stack) as sum_market_liability");
			$this->db->from('tblbets');
			$this->db->where('MarketId',$marketId);
			$this->db->where('UserId',$userId);
			$query = $this->db->get();
		//	echo $this->db->last_query();
		//	die;
			$betData = $query->row_array();

			$sumBets = 0;
			if(!empty($betData['sum_market_liability'])){
				$sumBets = $betData['sum_market_liability'];
			}
		//	echo $sumBets;die;
			return $sumBets;
	}

	function findSelectionName($marketId=NULL,$selectionId=NULL){

		$this->db->select('bet.selectionName');
		$this->db->from('tblbets bet');
		$this->db->where('bet.MarketId',$marketId);
		$this->db->where('bet.SelectionId',$selectionId);
		$query = $this->db->get();
		$row = $query->row_array();

		$selectionName = '';
		if(!empty($row['selectionName'])){
			$selectionName = $row['selectionName'];
		}
		return $selectionName;
	}

	//stores pl into p_l_by_match_detail table from tblchipdet table
	function savePlByMatchDetail($matchId){
		$query = $this->db->query("call p_l_by_match_detail($matchId)");
		return true;
	}

	function deleteSessionBet($id=0){
		$query = $this->db->query("DELETE FROM bet_entry WHERE bet_id = $id LIMIT 1;");
		return true;
	}

}