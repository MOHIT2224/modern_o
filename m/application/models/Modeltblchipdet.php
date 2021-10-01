<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblchipdet extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function updateByUserId($userId=0,$updateArr=NULL){
		$this->db->where('UserID',$userId);
        $this->db->update('tblchipdet', $updateArr);
        //echo $this->db->queries[0];die();		
        return true; 
	}

	/**
	 * [updateByMatchId update table chipdet by matchid
	 * @param  integer $matchId   [match id]
	 * @param  [type]  $updateArr [update array]
	 * @return [type]             [boolean]
	 */
	function updateByMatchId($matchId=0,$updateArr=NULL){
		$this->db->where('MatchId',$matchId);
        $this->db->update('tblchipdet', $updateArr);
        //echo $this->db->queries[0];die();		
        return true; 
	}

}