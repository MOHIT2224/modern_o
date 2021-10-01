<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeluserdeactivematch extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function insert($insertArr=NULL){
		$insertUserMatch = array('created'=>date('Y-m-d H:i:s',now()),'match_id'=>$insertArr['match_id'],'user_id'=>$insertArr['user_id']);
        $this->db->insert('user_deactive_match', $insertUserMatch);
        return true; 
	}	

	function delete($deleteArr=NULL){
		if(!empty($deleteArr['user_id']) && !empty($deleteArr['match_id'])){
			$userId = $deleteArr['user_id'];
			$matchId = $deleteArr['match_id'];
			$this->db->where('match_id', $matchId);
			$this->db->where('user_id', $userId);
          	$isdeleted = $this->db->delete('user_deactive_match');
          	return $isdeleted;
		}	
		return false;
	}

	function checkMatchExists($data){
		$userId = $data['user_id'];
		$matchId = $data['match_id'];
		$this->db->select('id');
		$this->db->from('user_deactive_match');
		$this->db->where('match_id', $matchId);
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