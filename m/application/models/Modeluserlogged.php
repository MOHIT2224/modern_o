<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeluserlogged extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function findByUserId($data){

		$userId = $data['user_id'];
		$fromDate = (isset($data['from_date']) ? $data['from_date'] : 0);
		$toDate = (isset($data['to_date']) ? $data['to_date'] : 0); 

		$page_limit = $data['page_limit'];
		$pageno = $data['page_no'];
		$page_max = $page_limit;
        $start = ($pageno - 1) * $page_max; 
		$this->db->select(" CONVERT_TZ(userlogged.logstdt,'+00:00','+05:30') logstdt,userlogged.ipadress,IFNULL(userlogged.device_info,'') device_info,IFNULL(userlogged.browser_info,'') browser_info,createmaster.mstruserid");
		$this->db->from('userlogged');
		$this->db->join('createmaster', 'userlogged.loguser = createmaster.mstrid', 'LEFT');
		$this->db->where('userlogged.loguser',$userId);	
		$this->db->order_by("userlogged.logcode DESC");

		if(!empty($fromDate) && !empty($toDate)){
			$this->db->where("CONVERT_TZ(userlogged.logstdt,'+00:00','+05:30') >=",$fromDate);	
			$this->db->where("CONVERT_TZ(userlogged.logstdt,'+00:00','+05:30') <=",$toDate);	
		}

//		$this->db->limit($start, $page_max); 

		$this->db->limit($page_max, $start);  
		
		$query = $this->db->get();
	//	$error = $this->db->error();
	//	var_dump($error);
	/*	echo $this->db->last_query();
		die;  */
		return $query->result_array();	
	}

	function findByUserIdCount($data){

		$userId = $data['user_id'];
		$fromDate = (isset($data['from_date']) ? $data['from_date'] : 0);
		$toDate = (isset($data['to_date']) ? $data['to_date'] : 0); 

		$this->db->select('userlogged.logstdt,userlogged.ipadress,createmaster.mstruserid,count(*) cnt');
		$this->db->from('userlogged');
		$this->db->join('createmaster', 'userlogged.loguser = createmaster.mstrid', 'LEFT');
		$this->db->where('userlogged.loguser',$userId);	

		if(!empty($fromDate) && !empty($toDate)){
			$this->db->where(' userlogged.logstdt >=',$fromDate);	
			$this->db->where(' userlogged.logstdt <=',$toDate);	
		}
		
		$query = $this->db->get();
		return $query->result_array();	
	}

	function UserLastLogin($userId){

		$this->db->select(" CONVERT_TZ(userlogged.logstdt,'+00:00','+05:30') last_login_time");
		$this->db->from('userlogged');
		$this->db->where('userlogged.loguser',$userId);	
		$this->db->order_by("userlogged.logcode DESC");
		$this->db->limit(1, 0);  
		$query = $this->db->get();

		$row = $query->row_array();
		$lastLoginDatetime = $row['last_login_time'];
	/*	echo $this->db->last_query();
		die;  */
		return $lastLoginDatetime;	
	}



	function update($id=NULL,$updateArr=NULL){
		$this->db->where('logcode',$id);
        $this->db->update('userlogged', $updateArr);
        return true; 
	} 

}