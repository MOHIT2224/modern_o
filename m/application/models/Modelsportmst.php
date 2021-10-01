<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelsportmst extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function findBySports($sportId=NULL){
		$this->db->select('sport_json');
		$this->db->from('sportmst');
		$this->db->where('id',$sportId);		
		$this->db->where('sport_json_updated_on IS NOT NULL');
		$this->db->where('sport_json_updated_on >= DATE_ADD(now(),interval -6 HOUR)'); 
		$query = $this->db->get();
		return $query->row_array();
	}

	function findBySportJsons($sportId=NULL){
		$this->db->select('sport_json');
		$this->db->from('sportmst');
		$this->db->where('id',$sportId);		
		$query = $this->db->get();
		return $query->row_array();
	}



	function updateSportJson($sportId=NULL,$updateArr=NULL){
		$this->db->where('id',$sportId);
        $this->db->update('sportmst', $updateArr);
        return true; 
	}

}