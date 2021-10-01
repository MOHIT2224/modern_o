<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelseriesmst extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function findBySeries($seriesId=NULL){
		$this->db->select('match_json');
		$this->db->from('seriesmst');
		$this->db->where('seriesId',$seriesId);		
		$this->db->where('match_json_updated_on IS NOT NULL');
		$this->db->where('match_json_updated_on >= DATE_ADD(now(),interval -1 day)'); 
		$query = $this->db->get();
		return $query->row_array();
	}

	function updateMatchJson($seriesId=NULL,$updateArr=NULL){
		$this->db->where('seriesId',$seriesId);
        $this->db->update('seriesmst', $updateArr);
        return true; 
	}

}