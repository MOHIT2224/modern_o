	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Userdeactivematchsession_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function insert($insertArr=NULL){
		$insertUserMatch = array('created'=>date('Y-m-d H:i:s',now()),'fancy_id'=>$insertArr['fancy_id'],'user_id'=>$insertArr['user_id']);
        $this->db->insert('user_deactive_match_session', $insertUserMatch);
        return true; 
	}	

	function delete($deleteArr=NULL){
		if(!empty($deleteArr['user_id']) && !empty($deleteArr['fancy_id'])){
			$userId = $deleteArr['user_id'];
			$fancyId = $deleteArr['fancy_id'];
			$this->db->where('fancy_id', $fancyId);
			$this->db->where('user_id', $userId);
          	$isdeleted = $this->db->delete('user_deactive_match_session');
          	return $isdeleted;
		}	
		return false;
	}

	function checkRecordExists($data){
		$userId = $data['user_id'];
		$fancyId = $data['fancy_id'];
		$this->db->select('id');
		$this->db->from('user_deactive_match_session');
		$this->db->where('fancy_id', $fancyId);
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