<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelfavouritemarket extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function insert($insertArr=NULL){
		$insertFavMarket = array('created'=>date('Y-m-d H:i:s',now()),'market_id'=>$insertArr['market_id'],'user_id'=>$insertArr['user_id']);
        $this->db->insert('favourite_market', $insertFavMarket);
        return true; 
	}	

	function delete($deleteArr=NULL){
		if(!empty($deleteArr['user_id']) && !empty($deleteArr['market_id'])){
			$userId = $deleteArr['user_id'];
			$marketId = $deleteArr['market_id'];
			$this->db->where('market_id', $marketId);
			$this->db->where('user_id', $userId);
          	$isdeleted = $this->db->delete('favourite_market');
          	return $isdeleted;
		}	
		return false;
	}

	function checkMarketExists($data){
		$userId = $data['user_id'];
		$marketId = $data['market_id'];
		$this->db->select('id');
		$this->db->from('favourite_market');
		$this->db->where('market_id', $marketId);
		$this->db->where('user_id', $userId);	
		$query = $this->db->get();
		$count = $query->num_rows();
		if($count==0){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	function update($updateArr=NULL){
		$this->db->where('Id',1);
        $this->db->update('tblconfig', $updateArr);
        return true; 
	} */

}