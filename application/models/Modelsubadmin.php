<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelsubadmin extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}


	function changePassword(){
		$old_password=sha1($_POST['old_password']);
		$this->db->select('mstrpassword as usepass');
		$this->db->from('sub_admin');
		$this->db->where('mstrid', $_POST['user_id']);
		$query = $this->db->get();
		foreach ($query->result() as $getPass)
				$chkPass=$getPass->usepass;
			if ($chkPass==$old_password) {
				$passwordData = array('mstrpassword' => sha1($_POST['newpassword']));
				$this->db->where('mstrid', $_POST['user_id']);
				$condition1=$this->db->update('sub_admin', $passwordData);

		       	return 1;
			}else{
				return 0;
			}
	}

	function updateAccount(){
        $chek=$this->chkUsernameForUpdate($this->input->post('username'),$this->input->post('user_id'));

        if(!$chek) {
            $dataArray = array('mobileno' => $_POST['mobileno'], 'mstrname' => $_POST['name'],
                'mstruserid' => $_POST['username'],'mstrremarks' => $_POST['remarks'],'HelperID'=>$_POST['role_id']);
            if(isset($_POST['active'])){
                $dataArray['active']=$_POST['active'];
                /*if(!$_POST['active']){
                    $this->session->sess_destroy();
                }*/
            }

            $this->db->where('mstrid', $this->input->post('user_id'));
            $condition1 = $this->db->update('sub_admin', $dataArray);
            if($condition1){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 3;
        }
	}

    function chkUsernameForSave($user){
        $this->db->trans_start();
        $this->db->select('mstruserid as usename');
        $this->db->from('sub_admin');
        $this->db->where('mstruserid', $user);
        $query = $this->db->get();
        $num=$query->num_rows();
        $this->db->trans_complete();
        return $num;
    }

    function chkUsernameForUpdate($userName,$userId){
        $this->db->trans_start();
        $this->db->select('mstruserid as usename');
        $this->db->from('sub_admin');
        $this->db->where('mstruserid', $userName);
        $this->db->where('mstrid !=', $userId);
        $query = $this->db->get();
        $num=$query->num_rows();
        $this->db->trans_complete();
        return $num;
    }

	function SaveSubAdmin(){

        $chek=$this->chkUsernameForSave($this->input->post('username'));

        if(!$chek){
            $insertcreteMaster = array(

                'mstrname' 		    => $this->input->post('name'),
                'mobileno'          => $_POST['mobileno'],
                'mstrcrdate'          => date('Y-m-d H:i:s',now()),
                'HelperID'          => $_POST['role_id'],
                'mstruserid' 		=> $this->input->post('username'),
                'mstrremarks' 		=> $this->input->post('remarks'),
                'mstrpassword' 		=> sha1($this->input->post('password')),
                'ipadress' 		    => $_SERVER['REMOTE_ADDR']
            );
            $query1=$this->db->insert('sub_admin', $insertcreteMaster);

            if ($query1) {
                return 1;
            }else{
                return 0;
            }
        }else{
            return 3;
        }



	}
	function getSubadmin()
	{

		$this->db->select('sa.mstrid, sa.mstrcrdate, sa.mstrname, sa.mstruserid,sa.active,sa.HelperID,sa.mstrremarks,r.name as role_name, sa.lgnusrCloseAc');
		$this->db->from('sub_admin sa');
        $this->db->join('tblhelperright r','r.ID=sa.HelperID', 'left');
        $this->db->where('sa.lgnusrCloseAc','1');
		$query = $this->db->get();
		return $data = $query->result_array();
	}


    function getUserById($userId){

        $this->db->select('*');
        $this->db->from('sub_admin');
        $this->db->where('mstrid',$userId);
        $query = $this->db->get();
        return $query->row();
    }

	/*function validateUser($username, $password) {

		$this->db->select('mstrid,lgnusrlckbtng,lgnusrCloseAc,usetype,mstruserid');
		$this->db->from('createmaster');
		$this->db->where('mstrid',$userId);
		$this->db->where('ID', $FancyID);
		$this->db->where('TypeID', $fancyType);

		$query = $this->db->get();
		return $query->result_array();


        $sel_user = "SELECT id, password from users WHERE (phone='$username' OR email = '$username') AND (password = '$password' OR  password = md5('$password')) and status='A'";
        $user = mysqli_query($this->conn, $sel_user);
        $userid = mysqli_fetch_assoc($user);
        if (mysqli_num_rows($user) > 0) {
            return $userid;
        }
    }*/


    function closeUserAccount(){


        $this->db->trans_begin();

        $dataArray = array('lgnusrCloseAc' => 0);
        $this->db->where('mstrid',$_POST['userId']);


        $q1=$this->db->update('sub_admin', $dataArray);

       // echo $this->db->last_query();die;

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return  false;
        }
        else{
            $this->db->trans_commit();
            return  true;
        }

    }


}