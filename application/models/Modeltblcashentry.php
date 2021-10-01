<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblcashentry extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function findById($id=0){
		$this->db->select('childId,parentId');
		$this->db->from('tblcashentry');
		$this->db->where('rowId',$id);		
		$query = $this->db->get();
		return $query->row_array();	
	}

}