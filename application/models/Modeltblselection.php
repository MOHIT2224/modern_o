<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblselection extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function findBySelectionName($selectionId=0){
		$this->db->select('selectionName');
		$this->db->from('tblselection');
		$this->db->where('selectionId',$selectionId);		
		$query = $this->db->get();

		$selName = '';
		if(!empty($query->row()->selectionName)){
			$selName = $query->row()->selectionName;	
		}
		return $selName;
	}

	function findByMatchId($matchId=0){
		$this->db->select('id,sportId,selectionId,selectionName');
		$this->db->from('tblselection');
		$this->db->where('matchId',$matchId);		
		$query = $this->db->get();
		return $query->result_array();
	}

	function findByMarketId($marketId=0){
		$this->db->select('id,sportId,selectionId,selectionName');
		$this->db->from('tblselection');
		$this->db->where('marketId',$marketId);		
		$query = $this->db->get();

		return $query->result_array();
	}

	function update($id=NULL,$updateArr=NULL){
		$this->db->where('id',$id);
        $this->db->update('tblselection', $updateArr);
        return true; 
	} 
}