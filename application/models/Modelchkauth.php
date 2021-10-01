<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Check UserName and Password Of Login Page
*/
class Modelchkauth extends CI_Model
{
	
	function __construct()
	{
		# code...
	}
	function chkAuthName(){
		//Decode The Post Method and Get The Post Value in Login Form
		$_POST = json_decode(file_get_contents('php://input'), true);
		$password=$_POST['password'];
		$this->db->select('mstruserid as usename,mstrpassword as usepass,usetype');
		$this->db->from('createmaster');
		$this->db->where('mstruserid', $_POST['username']);
		$this->db->where('mstrpassword', $password);
		$query = $this->db->get();
		//echo $this->db->queries[0];
		if($query->num_rows()==1){
			foreach ($query->result() as $users) {
					$user_name=$users->usename;
					$user_type=$users->usetype;
			}
			$sessiondata = array(
            	'userName' 	=> $user_name,
            	'type' 		=> $user_type,
            );
            
        	$this->session->set_userdata($sessiondata);
			return $query;
		}
		else{
			return false;
		}
		

	}
}