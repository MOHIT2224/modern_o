<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelapkversion extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function ChkApkStatus(){
		$this->db->select('status');
		$this->db->from('apk_version');
		$this->db->where('apk_version.id',1);
		$this->db->order_by('apk_version.createdOn DESC');
		$this->db->limit(1);  
		$query = $this->db->get();
		$data = $query->row_array();
		if(isset($data['status']) && $data['status']==0){
			return false;
		}else{
			return true;
		}
	}

	function findLatestVersion(){
		$this->db->select('version_code,version_name,apk_download_link,status');
		$this->db->from('apk_version');
		$this->db->where('apk_version.id',1);
		$this->db->order_by('apk_version.createdOn DESC');
		$this->db->limit(1);  
		$query = $this->db->get();
		return $query->row_array();	
	}

	function add($insertData=NULL){
		$this->db->select('version_code,version_name,apk_download_link');
		$this->db->from('apk_version');
		$this->db->where('apk_version.id',1);
		$this->db->limit(1);
		$query = $this->db->get();
	
		if(!empty($query->row_array())){
			$this->update($insertData);
		}else{
			$this->db->insert('apk_version', $insertData);
		}
		return true;
	}

	function update($updateArr=NULL){ 
		$this->db->where('id',1);
        $this->db->update('apk_version', $updateArr);
        
        return true; 
	}

	
			
}