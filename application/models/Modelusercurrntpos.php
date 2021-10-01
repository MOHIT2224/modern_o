<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting("ERROR");
/**
* Check UserName and Password Of Login Page
*/
class Modelusercurrntpos extends CI_Model
{
	
	function __construct()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function getUserPosition($userId,$userType,$matchId,$marketId){
		$query =$this->db->query("call sp_getUserPosition($userId,$userType,$matchId,$marketId)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();		
		return $query;
	}
	function getOwnPosition($userId,$userType,$matchId,$marketId){
		$query =$this->db->query("call sp_getOwnPosition($userId,$userType,$matchId,$marketId)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();		
		return $query;
	}
	
}