<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblresult extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function findById($id=NULL){
		$this->db->select('*');
		$this->db->from('tblresult');
		$this->db->where('tblresult.resId',$id);
		$this->db->limit(1);  
		$query = $this->db->get();
		return $query->row_array();	
	}
}