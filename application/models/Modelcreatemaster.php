<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelcreatemaster extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function findUserType($userId=NULL){
		$this->db->select('usetype');
		$this->db->from('createmaster user');
		$this->db->where('user.mstrid', $userId);
		$query = $this->db->get();
		$result = $query->row_array();	
		return $result['usetype'];
	}

	function userParent($userId=NULL){
		$this->db->select('mstrid user_id,usetype,parentId dealer_id,(Select parentId from createmaster master where master.mstrid = user.parentId ) master_id');
		$this->db->from('createmaster user');
		$this->db->where('user.mstrid', $userId);
		$query = $this->db->get();
		return $query->row_array();	
	}

	function getUserDelayByUserId($userId){
        $this->db->select('set_timeout, admin_set_timeout, master_set_timeout, dealer_set_timeout, session_delay, admin_session_delay, master_session_delay, dealer_session_delay');
        $this->db->from('createmaster');
        $this->db->where('mstrid', $userId);
        $query = $this->db->get();
        return $query->row_array();
    }

    function getBetDelayByUserId($userId){
        $this->db->select('set_timeout, admin_set_timeout, master_set_timeout, dealer_set_timeout');
        $this->db->from('createmaster');
        $this->db->where('mstrid', $userId);
        $query = $this->db->get();
        return (int)max($query->row_array());
    }

    function getSessionDelayByUserId($userId){
        $this->db->select('session_delay, admin_session_delay, master_session_delay, dealer_session_delay');
        $this->db->from('createmaster');
        $this->db->where('mstrid', $userId);
        $query = $this->db->get();
        return (int)max($query->row_array());
    }

	function countChildByParentId($userId){
        $this->db->select('mstrid');
        $this->db->from('createmaster');
        $this->db->where('parentId', $userId);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function checkParentIds($userId, $data = array()) {

        $this->db->select('parentId dealer_id');
        $this->db->from('createmaster user');
        $this->db->where('user.mstrid', $userId);
        $query = $this->db->get();
        $query_data  = $query->row_array();
        //print_r($query_data['dealer_id']);die;
        if ($query_data['dealer_id'] > 0) {

            $data[] = $query_data['dealer_id'];

           return $this->checkParentIds($query_data['dealer_id'], $data);
        } else {
           return $data;
        }
    }

    function checkChildIds($userId, $data = "") {

        $this->db->select('GROUP_CONCAT(mstrid) mstrid');
        $this->db->from('createmaster');
        $this->db->where_in('parentId', $userId);
        $this->db->group_by('parentId');
        $query = $this->db->get();
        $query_data  = $query->row_array();
        if($data==""){
             $data.=$userId.",";
        }
        if (!empty($query_data)) {
           $ids=$query_data['mstrid'];
            $data.=$ids.",";
            return $this->checkChildIds(explode(',',$ids), $data);
        } else {
            return explode(',',rtrim($data,','));
        }

    }

    function getUserTreeBalance($userId){
	    $ids = $this->checkChildIds($userId);
        $this->db->select('mstrid, mstrname, mstruserid, usetype, sum(liability) as Liability,sum(balance) as Balance,sum(p_l) as P_L,sum(freechips) as FreeChip,sum(chip) as Chip,sum(sessionLiability) as sessionLiability,sum(unmatchliability) as unmatchliability,match_stake,one_click_stake,is_confirm_bet,sum(-liability)+sum(balance) as total_balance');
        $this->db->from('createmaster');
        $this->db->where_in('mstrid',$ids);
        $query = $this->db->get();
        return $query->row_array();
    }


	function changeClientPassword($userId=NULL){
		$old_password=sha1($_POST['old_password']);
		$this->db->select('mstrpassword as usepass');
		$this->db->from('createmaster');
		$this->db->where('mstrid', $userId);
		$query = $this->db->get();
		foreach ($query->result() as $getPass)
			$chkPass=$getPass->usepass;
		if ($chkPass==$old_password) {
			$passwordData = array('mstrpassword' => sha1($_POST['newpassword']),'chkupdatePass'=>1);
			$this->db->where('mstrid', $userId);
			$condition1=$this->db->update('createmaster', $passwordData);
			return 1;
		}else{
			return 0;
		}
	}

	function changePassword(){
		$old_password=sha1($_POST['old_password']);
		$this->db->select('mstrpassword as usepass');
		$this->db->from('createmaster');
		$this->db->where('mstrid', $_POST['user_id']);
		$query = $this->db->get();
		foreach ($query->result() as $getPass)
				$chkPass=$getPass->usepass;
			if ($chkPass==$old_password) {
				$passwordData = array('mstrpassword' => sha1($_POST['newpassword']));
				$this->db->where('mstrid', $_POST['user_id']);
				$condition1=$this->db->update('createmaster', $passwordData);
				//print_r($condition1);
				//echo $this->db->queries[1];die();
		       	return 1;
			}else{
				return 0;
			}
	}
	function updateAccount(){
		$dataArray = array('mobileno' => $_POST['mobileno'],'mstrname' => $_POST['mstrname']);
		$this->db->where('mstrid',$this->session->userdata('user_id'));	
		$condition1=$this->db->update('createmaster', $dataArray);
      	return $condition1;
	}

	function getUserBalance($userId){
		$this->db->select('liability as Liability,balance as Balance,p_l as P_L,freechips as FreeChip,chip as Chip,sessionLiability as sessionLiability,unmatchliability as unmatchliability,match_stake,one_click_stake,is_confirm_bet');
		$this->db->from('createmaster');
		$this->db->where('mstrid',$userId);		
		$query = $this->db->get();
		return $query->result_array();	
	}

	function updateUserBalLiablity($userId){

		$chipModel = $this->model_load_model('Chip_model');
		$data = $chipModel->getLiability($userId);

		$dataArray = array('liability' => $data[0]['Liability'],'balance' => $data[0]['Balance'],'p_l'=>$data[0]['chipspnl'],'freechips'=>$data[0]['FreeChip'],'chip'=>$data[0]['Chip'],'sessionLiability'=>$data[0]['sessionLiability'],'unmatchliability'=>$data[0]['unmatchliability']);

		$this->db->where('mstrid',$userId);	
		$isUpdated = $this->db->update('createmaster', $dataArray);

      	return $isUpdated;
	}
	function getDealerInfo(){
		$this->db->select('mstrname,mstruserid,mobileno');
		$this->db->from('createmaster');
		$this->db->where('mstrid',$this->session->userdata('user_id'));		
		$query = $this->db->get();
		return $query->result_array();	
	}
	function getFormData(){
		$this->db->select('max(mstrid) as maxid');
		$this->db->from('createmaster');
		$query = $this->db->get();
		if($query->num_rows()>0){
			foreach ($query->result() as $getMaxId)
				$id=$getMaxId->maxid;
				$getid=$id+1;
				$json = $getid;
			}else{
				$json = 1;
			}
		return $json;	
	}
	function chkMasterUsername($user){
		$this->db->trans_start();
		$this->db->select('mstruserid as usename');
		$this->db->from('createmaster');
		$this->db->where('mstruserid', $user);
		$query = $this->db->get();
		$num=$query->num_rows();
		$this->db->trans_complete();
		if($num==1){
			return $num;
		}
		else{
			return $num;
		}
	}

	/**
	 * [validateSaveCreateMaster valdiate save user]
	 * @return [array] [response array]
	 */
	function validateSaveCreateMaster(){

		$result = array();

		$parentId = $this->input->post('parantId');

		$userData = $this->viewUserAcData($parentId);
		
		if(isset($userData[0]['usetype'])){
			$userType = $userData[0]['usetype'];
			if($this->input->post('typeId')==1 && ($userType==0)){
				$result = array('error' => 0 ,'message' => '');	
			}else if($this->input->post('typeId')==2 && ($userType==0 || $userType==1)){
				$result = array('error' => 0 ,'message' => '');
			}else if($this->input->post('typeId')==3 && ($userType==0 || $userType==1 || $userType==2)){
				$result = array('error' => 0 ,'message' => '');
			}else{
				$result = array('error' => 1 ,'message' => 'Could not save user');
			}
		}else{
			$result = array('error' => 1 ,'message' => 'Parent User not found');

		}
		return $result;
	}

    function validateSaveSubAdmin(){

        $result = array();

        $parentId = $this->input->post('parantId');

        $userData = $this->viewUserAcData($parentId);

        if(isset($userData[0]['usetype'])){
            $userType = $userData[0]['usetype'];
            if($userType==0){
                $result = array('error' => 0 ,'message' => '');
            }else{
                $result = array('error' => 1 ,'message' => 'Could not save user');
            }
        }else{
            $result = array('error' => 1 ,'message' => 'Parent User not found');

        }
        return $result;
    }

	function autoCreateMaster(){

		$start = $this->input->post('start',1);

		$limit = $this->input->post('limit',200);

		$parentId = $this->input->post('parantId');

		$parentUser = $this->viewUserAcData($parentId);

		$username = $parentUser[0]['mstruserid'];

		for($i==$start;$i<=$limit;$i++){

			$mstruserid = $username . '_client_' . $i;

			if($this->input->post('partner')!='')
				$partner= $this->input->post('partner');
			else 
				$partner=0;

			if($this->input->post('Commission')!='')
				$Commission= $this->input->post('Commission');
			else 
				$Commission=0;

			if($this->input->post('maxProfit')!='')
				$lgnUserMaxProfit= $this->input->post('maxProfit');
			else 
				$lgnUserMaxProfit =0;

			if($this->input->post('maxLoss')!='')
				$lgnUserMaxLoss= $this->input->post('maxLoss');
			else 
				$lgnUserMaxLoss=0;

			if($this->input->post('sessionCommission')!='')
				$SessionComm= $this->input->post('sessionCommission');
			else 
				$SessionComm=0;

			if($this->input->post('otherCommission')!='')
				$OtherComm= $this->input->post('otherCommission');
			else 
				$OtherComm=0;

			if($this->input->post('maxStake')!='')
				$lgnUserMaxStake= $this->input->post('maxStake');
			else 
				$lgnUserMaxStake=0;

			if($this->input->post('betDelay')!='')
				$set_timeout= $this->input->post('betDelay');
			else 
				$set_timeout=0;

			if($this->input->post('GngInPlayStake')!='')
				$InPlayStack= $this->input->post('GngInPlayStake');
			else 
				$InPlayStack=0;

			if($this->input->post('remarks')!='')
				$remarks= $this->input->post('remarks');
			else 
				$remarks='';

			if($this->input->post('typeId')==3){
				$insertData1 = array(
					'mstrname' 			=> $mstruserid,
					'mstruserid' 		=> $mstruserid,
					'mstrpassword' 		=> sha1($this->input->post('password')),
					'usetype' 			=> $this->input->post('typeId'),
					'mstrremarks' 		=> $remarks,
					'ipadress' 			=> $_SERVER['REMOTE_ADDR'],
					'loginid'			=> 0,//$last_id,
					'parentId'			=> $this->input->post('parantId'),
					'usecrdt' 			=> $currentDateTime,
					'partner'			=> $partner,
					'Commission'		=> $Commission,
					'lgnUserMaxProfit'	=> $lgnUserMaxProfit,
					'lgnUserMaxLoss'	=> $lgnUserMaxLoss,
					'SessionComm'		=> $SessionComm,
					'OtherComm'			=> $OtherComm,
					'lgnUserMaxStake'	=> $lgnUserMaxStake,
					'set_timeout'		=> $set_timeout,
					'HelperID'			=> $this->input->post('HelperID'),
					'InPlayStack'		=> $InPlayStack
					);
			}					
			// print_r($insertData1);
			$condition=$this->db->insert('createmaster', $insertData1);
			$creMstId=$this->db->insert_id();
			$ParantId=$this->input->post('parantId');
			//start user working table save the data By Manish 31/12/2016
			$wortype="ADD_ACC";
			$remarks=$this->input->post('typeId').">>".$this->input->post('username').">>".$partner.">>".$this->input->post('HelperID');
			$userWrkingArray = array(
				'woruser' 			=> $this->input->post('master_name'),
				'wormode' 			=> 0,
				'wordate' 			=> $currentDateTime,
				'wortype' 			=> $wortype,
				'worcode' 			=> $creMstId,
				'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
				'worrema'			=> $remarks,
				'worcudt'			=> date('Y-m-d H:i:s',now()),
			);
			$condition=$this->db->insert('userworkin', $userWrkingArray);

			if($this->input->post('typeId')==3){
				$pTypeId=$this->input->post('typeId');
				$pParantId=$this->input->post('parantId');
				$query =$this->db->query("call InsPartner($pTypeId,$pParantId,$creMstId,0,0,0)");
			}
			//end of Free chip code
		}
	}	

	function saveCreateMaster(){
				$currentDateTime = date('Y-m-d H:i:s',now());
                $childLimit = $this->getUserById($this->input->post('parantId'));
                $countChild = $this->countChildByParentId($this->input->post('parantId'));

                if(!($countChild < $childLimit->create_no_of_child) && !empty($childLimit->create_no_of_child)){
                    return 3;
                }
	  			$chek=$this->chkMasterUsername($this->input->post('username'));       			
				if($chek==0){
					$this->db->trans_begin();
					//$this->db->insert('loginusers', $insertData);
					//$last_id=$this->db->insert_id();
					//print_r($_POST);
					
			             if($this->input->post('partner')!='')
							 $partner= $this->input->post('partner');
						 else 
							 $partner=0;
						 
			             if($this->input->post('Commission')!='')
							 $Commission= $this->input->post('Commission');
						 else 
							 $Commission=0;
						 
			             if($this->input->post('maxProfit')!='')
							 $lgnUserMaxProfit= $this->input->post('maxProfit');
						 else 
							 $lgnUserMaxProfit =0;
						 
						 if($this->input->post('maxLoss')!='')
							 $lgnUserMaxLoss= $this->input->post('maxLoss');
						 else 
						 $lgnUserMaxLoss=0;
					 
						 if($this->input->post('sessionCommission')!='')
							 $SessionComm= $this->input->post('sessionCommission');
						 else 
						 $SessionComm=0;
					 
						 if($this->input->post('otherCommission')!='')
							 $OtherComm= $this->input->post('otherCommission');
						 else 
						 $OtherComm=0;
					 
						 if($this->input->post('maxStake')!='')
							 $lgnUserMaxStake= $this->input->post('maxStake');
						 else 
						 $lgnUserMaxStake=0;
					 
						 if($this->input->post('betDelay')!='')
							 $set_timeout= $this->input->post('betDelay');
						 else 
						 $set_timeout=0;
					 
						 if($this->input->post('GngInPlayStake')!='')
							$InPlayStack= $this->input->post('GngInPlayStake');
						else 
							$InPlayStack=0;

						if($this->input->post('remarks')!='')
							$remarks= $this->input->post('remarks');
						else 
							$remarks='';
                        if( $this->input->post('create_no_of_child')!='')
                            $create_no_of_child= $this->input->post('create_no_of_child');
                        else
                            $create_no_of_child=null;
                        if( $this->input->post('session_delay')!='')
                            $session_delay= $this->input->post('session_delay');
                        else
                            $session_delay=0;

                       /* $userType = $this->session->userdata('type');
                        if($userType==0){
                            $sessionColoumKey = 'admin_session_delay';
                            $oddsColoumKey = 'admin_set_timeout';
                        }elseif ($userType==1){
                            $sessionColoumKey = 'master_session_delay';
                            $oddsColoumKey = 'master_set_timeout';
                        }elseif ($userType==2){
                            $sessionColoumKey = 'dealer_session_delay';
                            $oddsColoumKey = 'dealer_set_timeout';
                        }*/


						if ($this->input->post('typeId')==1) {



							$insertData1 = array(
					            'mstrname' 			=> $this->input->post('master_name'),
					            'mstruserid' 		=> $this->input->post('username'),
					            'mstrpassword' 		=> sha1($this->input->post('password')),
					            'usetype' 			=> $this->input->post('typeId'),
					            'mstrremarks' 		=> $remarks,
					            'ipadress' 			=> $_SERVER['REMOTE_ADDR'],
					            'loginid'			=> 0,//$last_id,
					            'parentId'			=> $this->input->post('parantId'),
					            'usecrdt' 			=> $currentDateTime,
					            'partner'			=> $partner,
					            'Commission'		=> $Commission,
					            'lgnUserMaxProfit'	=> $lgnUserMaxProfit,
								'lgnUserMaxLoss'	=> $lgnUserMaxLoss,
								'SessionComm'		=> $SessionComm,
								'OtherComm'			=> $OtherComm,
								'create_no_of_child'			=> $create_no_of_child,
                                'admin_session_delay'		=> $session_delay,
                                'admin_set_timeout'		=> $set_timeout,
								'HelperID'			=> $this->input->post('HelperID')
					        );
						}else if ($this->input->post('typeId')==2){
                            $parentDelay = $this->getUserDelayByUserId($this->input->post('parantId'));

                            $insertData1 = array(
                                'mstrname' 			=> $this->input->post('master_name'),
                                'mstruserid' 		=> $this->input->post('username'),
                                'mstrpassword' 		=> sha1($this->input->post('password')),
                                'usetype' 			=> $this->input->post('typeId'),
                                'mstrremarks' 		=> $remarks,
                                'ipadress' 			=> $_SERVER['REMOTE_ADDR'],
                                'loginid'			=> 0,//$last_id,
                                'parentId'			=> $this->input->post('parantId'),
                                'usecrdt' 			=> $currentDateTime,
                                'partner'			=> $partner,
                                'Commission'		=> $Commission,
                                'lgnUserMaxProfit'	=> $lgnUserMaxProfit,
                                'lgnUserMaxLoss'	=> $lgnUserMaxLoss,
                                'SessionComm'		=> $SessionComm,
                                'OtherComm'			=> $OtherComm,
                                'create_no_of_child'			=> $create_no_of_child,
                               	'admin_session_delay'	=>  $parentDelay['admin_session_delay'],
                               	'master_session_delay'	=>  $session_delay,
                                'admin_set_timeout'		=> $parentDelay['admin_set_timeout'],
                                'master_set_timeout'		=> $set_timeout,
                                'HelperID'			=> $this->input->post('HelperID')
                            );
                        }else if($this->input->post('typeId')==3){

                            $parentDelay = $this->getUserDelayByUserId($this->input->post('parantId'));

							$insertData1 = array(
					            'mstrname' 			=> $this->input->post('master_name'),
					            'mstruserid' 		=> $this->input->post('username'),
					            'mstrpassword' 		=> sha1($this->input->post('password')),
					            'usetype' 			=> $this->input->post('typeId'),
					            'mstrremarks' 		=> $remarks,
					            'ipadress' 			=> $_SERVER['REMOTE_ADDR'],
					            'loginid'			=> 0,//$last_id,
					            'parentId'			=> $this->input->post('parantId'),
					            'usecrdt' 			=> $currentDateTime,
					            'partner'			=> $partner,
					            'Commission'		=> $Commission,
					            'lgnUserMaxProfit'	=> $lgnUserMaxProfit,
								'lgnUserMaxLoss'	=> $lgnUserMaxLoss,
								'SessionComm'		=> $SessionComm,
								'OtherComm'			=> $OtherComm,
                                'create_no_of_child'			=> $create_no_of_child,

								'lgnUserMaxStake'	=> $lgnUserMaxStake,
                                'admin_session_delay'	=>  $parentDelay['admin_session_delay'],
                                'master_session_delay'	=>  $parentDelay['master_session_delay'],
                                'dealer_session_delay'	=>  $session_delay,
                                'admin_set_timeout'		=> $parentDelay['admin_set_timeout'],
                                'master_set_timeout'		=> $parentDelay['master_set_timeout'],
                                'dealer_set_timeout'		=> $set_timeout,
								'HelperID'			=> $this->input->post('HelperID'),
								'InPlayStack'		=> $InPlayStack
					        );
						}else if($this->input->post('typeId')==5){
                            $insertData1 = array(
                                'mstrname' 			=> $this->input->post('master_name'),
                                'mstruserid' 		=> $this->input->post('username'),
                                'mstrpassword' 		=> sha1($this->input->post('password')),
                                'usetype' 			=> $this->input->post('typeId'),
                                'mstrremarks' 		=> $remarks,
                                'ipadress' 			=> $_SERVER['REMOTE_ADDR'],
                                'loginid'			=> 0,//$last_id,
                                'parentId'			=> 0,
                                'usecrdt' 			=> $currentDateTime,
                                'partner'			=> $partner,
                                'Commission'		=> $Commission,
                                'lgnUserMaxProfit'	=> $lgnUserMaxProfit,
                                'lgnUserMaxLoss'	=> $lgnUserMaxLoss,
                                'SessionComm'		=> $SessionComm,
                                'OtherComm'			=> $OtherComm,
                                'create_no_of_child'			=> $create_no_of_child,
                                'session_delay'		=> $session_delay,
                                'lgnUserMaxStake'	=> $lgnUserMaxStake,
                                'set_timeout'		=> $set_timeout,
                                'HelperID'			=> 0,
                                'InPlayStack'		=> $InPlayStack
                            );
                        }
                  // echo "<pre>"; print_r($insertData1);die;
			        $condition=$this->db->insert('createmaster', $insertData1);
			        $creMstId=$this->db->insert_id();
			        $ParantId=$this->input->post('parantId');
			        //start user working table save the data By Manish 31/12/2016
			        $wortype="ADD_ACC";
			        $remarks=$this->input->post('typeId').">>".$this->input->post('username').">>".$partner.">>".$this->input->post('HelperID');
		        	$userWrkingArray = array(
								            'woruser' 			=> $this->input->post('master_name'),
								            'wormode' 			=> 0,
								            'wordate' 			=> $currentDateTime,
								            'wortype' 			=> $wortype,
								            'worcode' 			=> $creMstId,
								            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
								            'worrema'			=> $remarks,
								            'worcudt'			=> date('Y-m-d H:i:s',now()),
								        );
		        	$condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table
			        if ($this->input->post('typeId')==1) {

			        	$adminPval=100-$partner;
			        	$masterPval=$partner;
			        	$dealerPval=0;

			        }else if($this->input->post('typeId')==2){

			        	$adminPval=100-$this->input->post('PntPartenerShip');
			        	$masterPval=$this->input->post('PntPartenerShip')-$partner;
			        	$dealerPval=$partner;

			        }
			       
			        //Manish add for free chip
			        if ($this->input->post('typeId')==2 || $this->input->post('typeId')==1) {

				        $pTypeId=$this->input->post('typeId');
				        $pParantId=$this->input->post('parantId');
						$query =$this->db->query("call InsPartner($pTypeId,$pParantId,$creMstId,$adminPval,$masterPval,$dealerPval)");


			        }else if($this->input->post('typeId')==3){
			        	$pTypeId=$this->input->post('typeId');
				        $pParantId=$this->input->post('parantId');
						$query =$this->db->query("call InsPartner($pTypeId,$pParantId,$creMstId,0,0,0)");
			        }
			        
			        //end of Free chip code
			        
			      //echo $this->db->queries[2];die();
			        if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return 0;
					}
					else{
					    $this->db->trans_commit();
					    return 1;
					}
				}else{
					return 2;
				}
	}
	function getSportMstData(){
		$this->db->select('max(id) as maxid');
		$this->db->from('sportmst');
		$query = $this->db->get();
		if($query->num_rows()>0){
			foreach ($query->result() as $getMaxId)
				$id=$getMaxId->maxid;
				$getid=$id+1;
				$json = $getid;
			}else{
				$json = 1;
			}
		return $json;	
	}
	function lstSportData(){
		$this->db->select('id,name');
		$this->db->from('sportmst');			
		$query = $this->db->get();
		return $query->result_array();
    
	}
	function lstSportDataById(){
		$this->db->select('id,name');
		$this->db->from('sportmst');
		$this->db->where('name','Cricket');			
		$query = $this->db->get();
		return $query->result_array();
    
	}
	function SaveSportMaster(){
		$insertData = array('name'=> $_POST['Sport_name']);
		$query=$this->db->insert('sportmst', $insertData);
		if ($query) {
			return $this->lstSportData();
		}else{
			return false;
		}
		

		//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table

	}
	function getSportTypeId(){
		$this->db->select('max(id) as maxid');
		$this->db->from('sporttypemst');
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			
			foreach ($query->result() as $getMaxId)
				$id=$getMaxId->maxid;
				$getid=$id+1;
				$json = $getid;
			
			}else{
				$json = 1;
			}
		return $json;		
	}
	function lstSportTypeData(){
		$this->db->select('spmst.name,sptymst.Name,sptymst.ID');
		$this->db->from('sporttypemst sptymst');
		$this->db->join('sportmst spmst','sptymst.SportID=spmst.id');
       	$query = $this->db->get();
		return $query->result_array();
	}
	function SaveSportTypeMaster(){
		$insertData = array('Name'=> $_POST['Sport_name'],'SportID'=>$_POST['Sport_id']);
		$query=$this->db->insert('sporttypemst', $insertData);
		if ($query) {
			return true;
		}else{
			return false;
		}

		//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
	}
	function getTeameId(){
		$this->db->select('max(id) as maxid');
		$this->db->from('team');
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			
			foreach ($query->result() as $getMaxId)
				$id=$getMaxId->maxid;
				$getid=$id+1;
				$json = $getid;
			
			}else{
				$json = 1;
			}
		return $json;		
	}
	function lstTeamData(){
		$this->db->select('id,name');
		$this->db->from('team');			
		$query = $this->db->get();
		return $query->result_array();
	}
	function saveTeamData(){
		$insertData = array('name'=> $_POST['Team_name']);
		$query=$this->db->insert('team', $insertData);
		if ($query) {
			return true;
		}else{
			return false;
		}	

		//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
	}
	function getPlayerId(){
		$this->db->select('max(ID) as maxid');
		$this->db->from('player');
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			
			foreach ($query->result() as $getMaxId)
				$id=$getMaxId->maxid;
				$getid=$id+1;
				$json = $getid;
			
			}else{
				$json = 1;
			}
		return $json;		
	}
	function lstPlayerData(){
		$this->db->select('plr.ID,plr.Age,spmst.name,tm.name as teamName,plr.Name');
		$this->db->from('player plr');
		$this->db->join('sportmst spmst','plr.SportID=spmst.id');
		$this->db->join('team tm','plr.TeamId=tm.id');						
		$query = $this->db->get();
		return $query->result_array();
	}
	function savePlayerData(){
		$insertData = array('Name'=> $_POST['player_name'],'SportID'=> $_POST['Sport_id'],'TeamId'=> $_POST['team_id']);
		$query=$this->db->insert('player', $insertData);
		if ($query) {
			return true;
		}else{
			return false;
		}		

		//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
	}
	function getSeriesId(){
		$this->db->select('max(ID) as maxid');
		$this->db->from('seriesmst');
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			
			foreach ($query->result() as $getMaxId)
				$id=$getMaxId->maxid;
				$getid=$id+1;
				$json = $getid;
			
			}else{
				$json = 1;
			}
		return $json;		
	}
	function lstSeriesData($id){
			if ($id=='') {
				$this->db->select('srmst.ID,srmst.name,spmst.Name');
				$this->db->from('seriesmst srmst');
				$this->db->join('sportmst spmst','srmst.SportID=spmst.id');
			}else{
				$this->db->select('ID,Name');
				$this->db->from('seriesmst');
				$this->db->where('SportID', $id);
			}
			
        	$query = $this->db->get();
			return $query->result_array();
	}
	function saveSeriesData(){
			$insertData = array('Name'=> $_POST['Series_name'],'SportID'=>$_POST['Sport_id']);
			$query=$this->db->insert('seriesmst', $insertData);
			if ($query) {
				return true;
			}else{
				return false;
			}

			//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
	}
	function getMaxMatchId(){
			$this->db->select('max(MstCode) as maxid');
			$this->db->from('matchmst');			
			$query = $this->db->get();
			if($query->num_rows()>0){
				
				foreach ($query->result() as $getMaxId)
					$id=$getMaxId->maxid;
					$getid=$id+1;
					$json = $getid;
				
				}else{
					$json = 1;
				}
			return $json;	
	}

	function saveMatchEntryData(){
			
			$insertData = array(
							'MstDate'=> $_POST['matchDate'],
							'SportID'=> 1,
							'FromDt'=> $_POST['timeFrom'],
							'ToDt'=> $_POST['timeTo'],
							'Team1'=> $_POST['team1'],
							'Team2'=> $_POST['team2'],
							'TypeID'=> $_POST['matchType1'],
							'SeriesID'=> $_POST['matchSeries'],
							'Target'=> $_POST['matchOver'],
							
							
							);
			
			
			$query=$this->db->insert('matchmst', $insertData);
			if ($query) {
				return true;
			}else{
				return false;
			}		

			//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
	}
	function GetPlayerLstById($id){
			$this->db->select('ID,Name');
			$this->db->from('player');
			$this->db->where('SportID', $id);
			$query = $this->db->get();
			return $query->result_array();
	}
	function GetPlayerLst(){
			$this->db->select('ID,Name');
			$this->db->from('player');
			$query = $this->db->get();
			return $query->result_array();
	}
	function getMaxBetId(){
			$this->db->select('max(bet_id) as maxid');
			$this->db->from('bet_entry');			
			$query = $this->db->get();
			if($query->num_rows()>0){
				
				foreach ($query->result() as $getMaxId)
					$id=$getMaxId->maxid;
					$getid=$id+1;
					$json = $getid;
				
				}else{
					$json = 1;
				}
			return $json;	
	}
	function GetUserLst(){
			$this->db->select('mstrid as usecode,mstruserid as usename');
			$this->db->from('createmaster');
			$this->db->where('usetype<>',0 );
			$query = $this->db->get();
			return $query->result_array();
	}
	function changeUserPasswod1(){

			//print_r($_POST);die();
				$dataArray = array('mstrpassword' => sha1($_POST['newPassword']));				
				$this->db->where('mstrid',$_POST['userId']);	
				$condition1=$this->db->update('createmaster', $dataArray);
				//echo $this->db->queries[0];die();
				return true;
			
	}
	function saveFancyEntry(){
			$insertData = array('MstName'=> $_POST['HeadName'],'MstType'=> $_POST['fancyType'],'MstDate'=> $_POST['FromDate'],'Desc'=>$_POST['remarks']);
			$query=$this->db->insert('headmst', $insertData);

			if ($query) {
				return true;
			}else{
				return false;
			}		

			//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
	}
	function getFancyHeader(){
			$this->db->select('*');
			$this->db->from('headmst');
			$query = $this->db->get();
			return $query->result_array();
	}
	function mb_saveFancy($data){
			if($data['fancyType']==2){

					$Modeltblconfig = $this->model_load_model('Modeltblconfig');
					$configData = $Modeltblconfig->find();

					if(!empty($configData)){
						$max_session_bet_liability = $configData[0]['max_session_bet_liability'];
						$max_session_liability = $configData[0]['max_session_liability'];
					}else{
						$max_bet_liability = MAX_BET_LIABLITY;
						$max_market_liability = MAX_MARKET_LIABLITY;
					}

					$insertData = array('super_admin_fancy_id'=>$data['super_admin_fancy_id'],'HeadName'=> $data['HeadName'],'TypeID'=> $data['fancyType'],'MatchID'=> $data['mid'],'Remarks'=>$data['remarks'],'date'=>$data['date'],'time'=>$data['time'],'SessInptYes'=>$data['inputYes'],'SessInptNo'=>$data['inputNo'],'ind_fancy_selection_id'=>$data['ind_fancy_selection_id'],'SprtId'=>$data['sid'],'rateDiff'=>$data['RateDiff'],'pointDiff'=>$data['PointDiff'],'MaxStake'=>$data['MaxStake'],'NoValume'=>100,'YesValume'=>100,'active'=>1,'is_indian_fancy'=>1,'fancy_mode'=>'A','max_session_bet_liability'=>$max_session_bet_liability,'max_session_liability'=>$max_session_liability);
					/*start*/
					$this->db->trans_begin();
					/*Get Match Name*/
					$MatchName=$this->getMatchNameById($data['mid']);
					$Name=$MatchName[0]->matchName;
					$MenuName= $data['HeadName']."__".$Name;
					$query=$this->db->insert('matchfancy', $insertData);
					$creFancyId=$this->db->insert_id();
					$insertmunuoption = array('menName'=> $MenuName,'menCode'=> $creFancyId,'menDesc'=>$MenuName,'mstType'=> $data['fancyType']);
					$query1=$this->db->insert('menuoption', $insertmunuoption);
					//start user working table save the data By Manish 02/1/2017
			        $wortype="Session Fancy";
			        $remarks="Fancy Type>>".$data['fancyType'].">>Fancy Name >>".$data['HeadName'].">> Match ID >>".$data['mid'];
			        $userWrkingArray = array('woruser' => $data['HeadName'],'wormode'=> 0,'wordate'=> $data['date'],'wortype'=> $wortype,'worcode' => $creFancyId,'worsysn' => $_SERVER['REMOTE_ADDR'],'worrema' => $remarks,'worcudt'=> date('Y-m-d H:i:s',now()),);
			        $condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table
					if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}else{
					    $this->db->trans_commit();
					    return $creFancyId;
					}
			}
			return false;
	}	
	function saveFancy(){
			if ($_POST['fancyType']==1) {
					$insertData = array('HeadName'=> $_POST['HeadName'],'TypeID'=> $_POST['fancyType'],'MatchID'=> $_POST['mid'],'Remarks'=>$_POST['remarks'],'active'=>1,'date'=>date('Y-m-d'),'time'=>date('H:i:s'),'SprtId'=>$_POST['sid']);
					$this->db->trans_begin();
						$MatchName=$this->getMatchNameById($_POST['mid']);
						$Name=$MatchName[0]->matchName;
						$MenuName= $_POST['HeadName']."__".$Name;
						$query=$this->db->insert('matchfancy', $insertData);
						$creFancyId=$this->db->insert_id();
						$insertmunuoption = array('menName'=> $MenuName,'menCode'=> $creFancyId,'menDesc'=>$MenuName,'mstType'=> $_POST['fancyType']);
					    $query1=$this->db->insert('menuoption', $insertmunuoption);
			        	$wortype="OddEven fancy";
			        	$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'].">> Sport ID >>".$_POST['sid'];
			        	$userWrkingArray = array(
									            'woruser' 			=> $_POST['HeadName'],
									            'wormode' 			=> 0,
									            'wordate' 			=> $_POST['date'],
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $creFancyId,
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table

					if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return $creFancyId;
					}
				/*}		
				else{
					return 2;
				}*/
			}else if($_POST['fancyType']==2){

				$row = $this->db->query('SELECT MAX(MFancyID) AS `maxid` FROM `matchfancy`')->row();
				$maxid = $row->maxid+1;

				 $insertData = array('HeadName'=> $_POST['HeadName'],'TypeID'=> $_POST['fancyType'],'MatchID'=> $_POST['mid'],'Remarks'=>$_POST['remarks'],'date'=>$_POST['date'],'time'=>$_POST['time'],'SessInptYes'=>$_POST['inputYes'],'SessInptNo'=>$_POST['inputNo'],'MFancyID'=>$maxid,'SprtId'=>$_POST['sid'],'rateDiff'=>$_POST['RateDiff'],'pointDiff'=>$_POST['PointDiff'],'MaxStake'=>$_POST['MaxStake'],'NoValume'=>$_POST['NoLayRange'],'YesValume'=>$_POST['YesLayRange']);
                $this->db->trans_begin();
					/*Get Match Name*/
						$MatchName=$this->getMatchNameById($_POST['mid']);
						$Name=$MatchName[0]->matchName;
						$MenuName= $_POST['HeadName']."__".$Name;
					
					
					$query=$this->db->insert('matchfancy', $insertData);
					//echo $this->db->last_query();die;
					$creFancyId=$this->db->insert_id();
					$insertmunuoption = array('menName'=> $MenuName,'menCode'=> $creFancyId,'menDesc'=>$MenuName,'mstType'=> $_POST['fancyType']);
					$query1=$this->db->insert('menuoption', $insertmunuoption);
					//start user working table save the data By Manish 02/1/2017
			        	$wortype="Session Fancy";
			        	$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
			        	$userWrkingArray = array(
									            'woruser' 			=> $_POST['HeadName'],
									            'wormode' 			=> 0,
									            'wordate' 			=> $_POST['date'],
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $creFancyId,
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table

					if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return $creFancyId;
					}


			}else if ($_POST['fancyType']==3) {
				/*$num=$this->chkMatchFancy($_POST['mid'],$_POST['fancyType']);
				if($num==0){*/
					$insertData = array('HeadName'=> $_POST['HeadName'],'TypeID'=> $_POST['fancyType'],'MatchID'=> $_POST['mid'],'Remarks'=>$_POST['remarks'],'date'=>$_POST['date'],'time'=>$_POST['time'],'fancyRange'=>$_POST['fancyRange'],'active'=>1,'SprtId'=>$_POST['sid']);
					//$query=$this->db->insert('matchfancy', $insertData);
						/*start*/
					$this->db->trans_begin();
					/*Get Match Name*/
						$MatchName=$this->getMatchNameById($_POST['mid']);
						$Name=$MatchName[0]->matchName;
						$MenuName= $_POST['HeadName']."__".$Name;
						
						
						$query=$this->db->insert('matchfancy', $insertData);
						$creFancyId=$this->db->insert_id();
						$insertmunuoption = array('menName'=> $MenuName,'menCode'=> $creFancyId,'menDesc'=>$MenuName,'mstType'=> $_POST['fancyType']);
					    $query1=$this->db->insert('menuoption', $insertmunuoption);
					    //start user working table save the data By Manish 02/1/2017
			        	$wortype="Khaddal Fancy";
			        	$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
			        	$userWrkingArray = array(
									            'woruser' 			=> $_POST['HeadName'],
									            'wormode' 			=> 0,
									            'wordate' 			=> $_POST['date'],
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $creFancyId,
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table

					if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return $creFancyId;
					}
					/*end*/
				/*}else{
						return 2;
				}*/
			}else if ($_POST['fancyType']==4) {

					$insertData = array('HeadName'=> $_POST['HeadName'],'TypeID'=> $_POST['fancyType'],'MatchID'=> $_POST['mid'],'Remarks'=>$_POST['remarks'],'date'=>$_POST['date'],'time'=>$_POST['time'],'active'=>1,'upDwnLimit'=>$_POST['liability'],'SprtId'=>$_POST['sid']);

					$this->db->trans_begin();
						$MatchName=$this->getMatchNameById($_POST['mid']);
						$Name=$MatchName[0]->matchName;
						$MenuName= $_POST['HeadName']."__".$Name;
						$query=$this->db->insert('matchfancy', $insertData);
						$creFancyId=$this->db->insert_id();
						$insertmunuoption = array('menName'=> $MenuName,'menCode'=> $creFancyId,'menDesc'=>$MenuName,'mstType'=> $_POST['fancyType']);
					    $query1=$this->db->insert('menuoption', $insertmunuoption);
			        	$wortype="Last Digit";
			        	$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
			        	$userWrkingArray = array(
									            'woruser' 			=> $_POST['HeadName'],
									            'wormode' 			=> 0,
									            'wordate' 			=> $_POST['date'],
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $creFancyId,
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);


					if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return 1;
					}

			}else if ($_POST['fancyType']==5) {
				
					$insertData = array('HeadName'=> $_POST['HeadName'],'TypeID'=> $_POST['fancyType'],'MatchID'=> $_POST['mid'],'date'=>$_POST['date'],'time'=>$_POST['time'],'HeadName'=>$_POST['HeadName'],'rateDiff'=>$_POST['rateDiff'],'pointDiff'=>$_POST['pointDiff'],'MaxStake'=>$_POST['MaxStake'],'Remarks'=>$_POST['remarks'],'active'=>0,'upDwnLimit'=>$_POST['liability'],'SprtId'=>$_POST['sid']);
					//print_r($insertData);die();
					$this->db->trans_begin();

                    $MatchName=$this->getMatchNameById($_POST['mid']);
                    $Name=$MatchName[0]->matchName;
                    $MenuName= $_POST['HeadName']."__".$Name;
					$query=$this->db->insert('matchfancy', $insertData);
					$creFancyId=$this->db->insert_id();
					$insertmunuoption = array('menName'=> $MenuName,'menCode'=> $creFancyId,'menDesc'=>$MenuName,'mstType'=> $_POST['fancyType']);
					$query1=$this->db->insert('menuoption', $insertmunuoption);

					if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return 1;
					}
			}
		}
		function getMatchNameById($matchId){
				$this->db->select("matchName");
				$this->db->from('matchmst');
				$this->db->where('MstCode',$matchId);
				$query = $this->db->get();
				return $query->result();

			}
		function chkMatchFancy($matchId,$fancyType){

			 return $this->db->where('MatchID', $matchId)->where('TypeID',$fancyType)->count_all_results('matchfancy');
		
		}
		function GetFancyByIdnType($matchId,$fancyType){

			$this->db->select('MatchID,HeadName,TypeID,Remarks,date,time,SessInptYes,SessInptNo,ID');
			$this->db->from('matchfancy');
			$this->db->where('MatchID', $matchId);
			$this->db->where('TypeID', $fancyType);
			$query = $this->db->get();
			$data = $query->result_array();
				//echo $this->db->queries[0];die();
			//$password =$data[0]['usepass'];
			return $data;

		}
		function updateMatchStatus($matchId,$active){
			$dataArray = array('active' => $active);
    		$this->db->where('MstCode',$matchId);
            $this->db->update('matchmst', $dataArray);
            return true; 
		}

        function updateScoreBoard($matchId,$data){
            $dataArray = array('score_board_json' => json_encode($data));
            $this->db->where('MstCode',$matchId);
            $this->db->update('matchmst', $dataArray);
           // echo $this->db->last_query();die;
            return true;
        }

    function getScoreBoard($matchId){
        $this->db->select('score_board_json');
        $this->db->from('matchmst');
        $this->db->where('MstCode',$matchId);
        $query = $this->db->get();

        return $query->row_array();
    }



    function updateFancyStatus($fancyId,$active){
			$dataArray = array('active' => $active);
    		$this->db->where('ID',$fancyId);
            $this->db->update('matchfancy', $dataArray);
            return true; 
		}
		function getPlayer(){
			/*SELECT distinct(plr.Name),plr.ID FROM matchmst mtchmst INNER JOIN player plr ON mtchmst.Team1=plr.TeamId OR mtchmst.Team2=plr.TeamId WHERE mtchmst.SportID=1*/
			$this->db->select('distinct(Name),ID');
			$this->db->from('player');
			/*$this->db->join('player plr','mtchmst.Team1=plr.TeamId OR mtchmst.Team2=plr.TeamId');*/
			/*$this->db->where('mtchmst.SportID',1);*/
			$query = $this->db->get();
			return $query->result_array();
		}
		function GetPlayerById($playerId){
			$this->db->select('Name');
			$this->db->from('player');
			$this->db->where('ID', $playerId);
			$query = $this->db->get();
			$data = $query->result_array();

			$name =$data[0]['Name'];
			return $name;
		}
		function viewUserAc($id,$type){
			$this->db->select('c.mstrname,c.mstruserid,(case when b.TypeID=1 then b.Master else b.Dealer end) as partner,c.stakeLimit,c.Commission,c.lgnUserMaxProfit,c.lgnUserMaxLoss,c.lgnUserMaxStake');
			$this->db->from('createmaster c');
			if($type=1)
			$this->db->join('tblpartner b','c.mstrid = b.ParentID','left');
		else
			$this->db->join('tblpartner b','c.mstrid = b.UserID','left');
			$this->db->where('c.mstrid', $id);
			$query = $this->db->get();
			//echo $this->db->queries[0];die();
			return $data = $query->result_array();

		}
		function ChangeUserPassword(){
			
			if ($_POST['userType_id']==$_POST['SltUsrType_id']) {

				$getPassword=$this->chkUserPassword($_POST['userId']);

				if ($getPassword==sha1($_POST['oldPassword'])) {

					$dataArray = array('mstrpassword' => sha1($_POST['newPassword']),'HelperID' => $_POST['HelperID']);
					
					$this->db->where('mstrid',$_POST['userId']);	
					$condition1=$this->db->update('createmaster', $dataArray);
					//start user working table save the data By Manish 31/12/2016
			        	$wortype="CHANGE_PASSWORD";
			        	$remarks="USER TYPE_ID : ".$_POST['userType_id'].">>USER_ID : ".$_POST['userId'].">>".$_POST['HelperID'];
			        	$userWrkingArray = array(
									            'woruser' 			=> $_POST['userId'],
									            'wormode' 			=> 0,
									            'wordate' 			=> date('Y-m-d H:i:s',now()),
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $_POST['userId'],
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table
					
					if ($condition1) {
						return true;
						
					}else{
						return false;
					}

				}else{
					
						return false;
				
				}
			}else{
					$dataArray = array('mstrpassword' => sha1($_POST['newPassword']),'HelperID' => $_POST['HelperID']);
					$this->db->trans_begin();
					$this->db->where('mstrid',$_POST['userId']);	
					$condition1=$this->db->update('createmaster', $dataArray);
					//start user working table save the data By Manish 31/12/2016
			        	/*$wortype="CHANGE_PASSWORD";
			        	$remarks="USER TYPE_ID : ".$_POST['userType_id'].">>USER_ID : ".$_POST['userName'].">>".$_POST['HelperID'];
			        	$userWrkingArray = 	array(
									            'woruser' 			=> $_POST['userId'],
									            'wormode' 			=> 0,
									            'wordate' 			=> date('Y-m-d H:i:s',now()),
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $_POST['userId'],
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);*/
			        //End of useworking table
					 if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return true;
					}
			}
		}
		function chkUserPassword($userId){

			$this->db->select('mstrpassword as usepass');
			$this->db->from('createmaster');
			$this->db->where('mstrid', $userId);
			$query = $this->db->get();
			$data = $query->result_array();
			$password =$data[0]['usepass'];
			return $password;
		}
		function lockUser_bk(){
			
            $userType=$_POST['userType'];
            $userId=$_POST['userId'];
            $lockVal=$_POST['lockVal'];
			$loginUserID=$_POST['loginUserID'];//sourabh new 161224
			$HelperID=$_POST['HelperID'];
			
			//start user working table save the data By Manish 31/12/2016
        	$wortype="UserLock";
        	$remarks="USER TYPE_ID : ".$_POST['userType'].">> USER_ID : ".$_POST['HelperID'].">> USER_ID : ".$_POST['HelperID'];
        	$userWrkingArray = 	array(
						            'woruser' 			=> $_POST['userId'],
						            'wormode' 			=> 1,
						            'wordate' 			=> date('Y-m-d H:i:s',now()),
						            'wortype' 			=> $wortype,
						            'worcode' 			=> $_POST['userId'],
						            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
						            'worrema'			=> $remarks,
						            'worcudt'			=> date('Y-m-d H:i:s',now()),
						        );
        	
			//End of useworking table
			$this->db->trans_begin();

			$condition=$this->db->insert('userworkin', $userWrkingArray);
           //echo "call sp_updLockStatus($userId,$userType,$lockVal,$lockVal,$loginUserID)";die();
            $query =$this->db->query("call sp_updLockStatus($userId,$userType,$lockVal,$lockVal,$loginUserID,$HelperID)");
		
			 	if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
				}
				else{
				    $this->db->trans_commit();
				    return true;
				}

		}

        function lockUser(){

            $mstrlock_by_user_id = $this->session->userdata('user_id');
            $type = 'Lock';

            $this->db->select('a.mstrid AS user_id, b.mstrlock AS parent_user_mstrlock, a.self_mstrlock AS self_mstrlock, a.parent_mstrlock AS parent_mstrlock');
            $this->db->from('createmaster a');
            $this->db->join('createmaster b','a.parentId = b.mstrid', 'LEFT');
            $this->db->where('a.mstrid', $_POST['userId']);
            $this->db->limit(1);

            $query = $this->db->get();
            $data = $query->result_array();

            $lockVal = $_POST['lockVal'];
            if($lockVal == 1){

                $type = 'Unlock';
                $mstrlock_by_user_id = 0;

                if($data[0]['parent_user_mstrlock'] == 0){
                    return array('success' => false, 'message' => 'Failed: Parent User Locked');
                }

                if($data[0]['parent_mstrlock'] != 0){
                    $lockVal = 0;
                }
            }


            //start user working table save the data By Manish 31/12/2016
            $wortype="Lock User";
            $remarks="USER TYPE_ID : ".$_POST['userType'].">> USER_ID : ".$_POST['userName'].">> HelperID : ".$_POST['HelperID'];
            $userWrkingArray =     array(
                'woruser'        => $_POST['userId'],
                'wormode'        => 1,
                'wordate'        => date('Y-m-d H:i:s',now()),
                'wortype'        => $wortype,
                'worcode'        => $_POST['userId'],
                'worsysn'        => $_SERVER['REMOTE_ADDR'],
                'worrema'        => $remarks,
                'worcudt'        => date('Y-m-d H:i:s',now()),
            );
            $this->db->trans_begin();

            $dataArray = array('mstrlock' => $lockVal, 'HelperID' => $_POST['HelperID'], 'self_mstrlock' => $mstrlock_by_user_id);
            $this->db->where('mstrid',$_POST['userId']);


            $q1=$this->db->update('createmaster', $dataArray);
            $condition=$this->db->insert('userworkin', $userWrkingArray);
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return array('success' => false, 'message' => "User $type Failed");
            }
            else{
                $this->db->trans_commit();
                return array('success' => true, 'message' => "User $type Successfully");
            }

        }

        function lockUsers($users=array()){

        $lockVal = $_POST['lockVal'];
        $HelperID = 0;
        $mstrlock_by_user_id = $this->session->userdata('user_id');

        foreach($users as $user){

            $userType = $user['usetype'];
            $userName = $user['mstruserid'];
            $userId = $user['mstrid'];
            $success = true;

            if($lockVal == 1){

                $mstrlock_by_user_id = 0;

                $this->db->select('a.mstrid AS user_id, b.mstrlock AS parent_user_mstrlock, a.self_mstrlock AS self_mstrlock, a.parent_mstrlock AS parent_mstrlock');
                $this->db->from('createmaster a');
                $this->db->join('createmaster b','a.parentId = b.mstrid', 'LEFT');
                $this->db->where('a.mstrid', $userId);
                $this->db->limit(1);

                $query = $this->db->get();
                $data = $query->result_array();

                if($data[0]['self_mstrlock'] != 0){
                    $lockVal = 0;
                }
                if($data[0]['parent_mstrlock'] == 0){
                    $success = false;
                }
            }

            if($success){
                $wortype="Lock User";
                $remarks="USER TYPE_ID : ".$userType.">> USER_ID : ".$userName.">> HelperID : ".$HelperID;
                $userWrkingArray =     array('woruser'    => $userId,'wormode'=> 1,'wordate'=> date('Y-m-d H:i:s',now()),'wortype'=> $wortype,'worcode'=> $userId,'worsysn'=> $_SERVER['REMOTE_ADDR'],'worrema'=> $remarks,'worcudt'=> date('Y-m-d H:i:s',now()));
                $this->db->trans_begin();

                $dataArray = array('mstrlock' => $lockVal, 'HelperID' => $HelperID, 'parent_mstrlock' => $mstrlock_by_user_id);
                $this->db->where('mstrid',$userId);
                $q1=$this->db->update('createmaster', $dataArray);
                $condition=$this->db->insert('userworkin', $userWrkingArray);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }
                else{
                    $this->db->trans_commit();
                }
            }
        }
    }


		function lockUserBetting_bk(){
			//start user Lock betting
			
            
            //return true; 
			//start user working table save the data By Manish 31/12/2016
	        	$wortype="UserLock Betting";
	        	$remarks="USER TYPE_ID : ".$_POST['userType'].">> USER_ID : ".$_POST['userName'].">> HelperID : ".$_POST['HelperID'];
	        	$userWrkingArray = 	array(
							            'woruser' 			=> $_POST['userId'],
							            'wormode' 			=> 1,
							            'wordate' 			=> date('Y-m-d H:i:s',now()),
							            'wortype' 			=> $wortype,
							            'worcode' 			=> $_POST['userId'],
							            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
							            'worrema'			=> $remarks,
							            'worcudt'			=> date('Y-m-d H:i:s',now()),
							        );
	 			$this->db->trans_begin();

					$dataArray = array('lgnusrlckbtng' => $_POST['lockbettingVal'],'HelperID' => $_POST['HelperID']);
					$this->db->where('mstrid',$_POST['userId']);
					$q1=$this->db->update('createmaster', $dataArray);
					$condition=$this->db->insert('userworkin', $userWrkingArray);
				 	if ($this->db->trans_status() === FALSE){
						    $this->db->trans_rollback();
						     return false;
					}
					else{
					    $this->db->trans_commit();
					    return true;
					}
			//end of user lock betting
			
		}
        function lockUserBetting(){

        $lgnusrlckbtng_by_user_id = $this->session->userdata('user_id');
        $type = 'Lock';

        $this->db->select('a.mstrid AS user_id, b.lgnusrlckbtng AS parent_user_lgnusrlckbtng, a.self_lgnusrlckbtng AS self_lgnusrlckbtng, a.parent_lgnusrlckbtng AS parent_lgnusrlckbtng');
        $this->db->from('createmaster a');
        $this->db->join('createmaster b','a.parentId = b.mstrid', 'LEFT');
        $this->db->where('a.mstrid', $_POST['userId']);
        //$this->db->where('b.lgnusrlckbtng',1);
        $this->db->limit(1);

        $query = $this->db->get();


        $data = $query->result_array();

        $lockbettingVal = $_POST['lockbettingVal'];
        if($lockbettingVal == 1){

            $type = 'UnLock';
            $lgnusrlckbtng_by_user_id = 0;

            if($data[0]['parent_user_lgnusrlckbtng'] == 0){
                return array('success' => false, 'message' => 'Failed: Parent Betting Locked');
            }

            if($data[0]['parent_lgnusrlckbtng'] != 0){
                $lockbettingVal = 0;
            }
        }


        //start user working table save the data By Manish 31/12/2016
        $wortype="UserLock Betting";
        $remarks="USER TYPE_ID : ".$_POST['userType'].">> USER_ID : ".$_POST['userName'].">> HelperID : ".$_POST['HelperID'];
        $userWrkingArray =     array(
            'woruser'        => $_POST['userId'],
            'wormode'        => 1,
            'wordate'        => date('Y-m-d H:i:s',now()),
            'wortype'        => $wortype,
            'worcode'        => $_POST['userId'],
            'worsysn'        => $_SERVER['REMOTE_ADDR'],
            'worrema'        => $remarks,
            'worcudt'        => date('Y-m-d H:i:s',now()),
        );
        $this->db->trans_begin();

        $dataArray = array('lgnusrlckbtng' => $lockbettingVal, 'HelperID' => $_POST['HelperID'], 'self_lgnusrlckbtng' => $lgnusrlckbtng_by_user_id);
        $this->db->where('mstrid',$_POST['userId']);


        $q1=$this->db->update('createmaster', $dataArray);
        $condition=$this->db->insert('userworkin', $userWrkingArray);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return array('success' => false, 'message' => "Betting $type Failed");
        }
        else{
            $this->db->trans_commit();
            return array('success' => true, 'message' => "Betting $type Successfully");
        }

    }

		function UpdateUserAccount(){


			if($_POST['userType']==2){
				$this->closeDealerAccount($_POST['userId']);
			}elseif($_POST['userType']==1){
				$this->closeMasterAccount($_POST['userId']);
			}

			//start user working table save the data By Manish 31/12/2016
	        	$wortype="Close Account";
	        	$remarks="USER TYPE_ID : ".$_POST['userType'].">> USER_ID : ".$_POST['userName'].">> HelperID : ".$_POST['HelperID'];
	        	$userWrkingArray = 	array(
							            'woruser' 			=> $_POST['userId'],
							            'wormode' 			=> 1,
							            'wordate' 			=> date('Y-m-d H:i:s',now()),
							            'wortype' 			=> $wortype,
							            'worcode' 			=> $_POST['userId'],
							            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
							            'worrema'			=> $remarks,
							            'worcudt'			=> date('Y-m-d H:i:s',now()),
							        );
	        	
				//End of useworking table
						$this->db->trans_begin();

						$dataArray = array('lgnusrCloseAc' => $_POST['accValue'],'HelperID' => $_POST['HelperID']);
						$this->db->where('mstrid',$_POST['userId']);
						$q1=$this->db->update('createmaster', $dataArray);
						// $loginArray = array('lgnusrCloseAc' => $_POST['accValue']);
						// $this->db->where('usecode',$_POST['userId']);
						// $q2=$this->db->update('loginusers', $loginArray);

						$condition=$this->db->insert('userworkin', $userWrkingArray);

						
			
					 	if ($this->db->trans_status() === FALSE){
							    $this->db->trans_rollback();
							     return false;
						}
						else{
						    $this->db->trans_commit();
						    return true;
						}
			//end of user lock betting



			
            
          
		}
		function partnerValue($userId){
			$this->db->select('partner');
			$this->db->from('createmaster');
			$this->db->where('mstrid', $userId);
			$query = $this->db->get();
			$data = $query->result_array();

			$name =$data[0]['partner'];
			return $name;
		}
		function UpdateUserAccountData()
		{
			//print_r($_POST);die();

		//	if($_POST['partnership']!='')$partner = $_POST['partnership'];else $partner =0;
			if($_POST['set_timeout']!='')$set_timeout = $_POST['set_timeout'];else $set_timeout =0;
			if($_POST['session_delay']!='')$set_timeout = $_POST['session_delay'];else $set_timeout =0;
			if($_POST['maxProfit']!='')$lgnUserMaxProfit = $_POST['maxProfit'];else $lgnUserMaxProfit =0;
			if($_POST['maxLoss']!='')$lgnUserMaxLoss = $_POST['maxLoss'];else $lgnUserMaxLoss =0;
			if($_POST['maxStake']!='')$lgnUserMaxStake = $_POST['maxStake'];else $lgnUserMaxStake =0;
			if($_POST['InPlayStack']!='')$InPlayStack =$_POST['InPlayStack'];else $InPlayStack =0;
			if($_POST['remarks']!='')$remarks =$_POST['remarks'];else $remarks ='';
			if(isset($_POST['create_no_of_child']) && $_POST['create_no_of_child']!='')$create_no_of_child =$_POST['create_no_of_child'];else $create_no_of_child =null;

            $userType = $_POST['userType'];

			/*'partner' => $_POST['partnership'],*/
			$dataArray = array(
								'mstrname' => $_POST['Name'],	
								'mstrremarks' => $remarks,							
								'set_timeout' => $_POST['set_timeout'],
								'session_delay' => $_POST['session_delay'],
								'lgnUserMaxProfit' => $_POST['maxProfit'],
								'lgnUserMaxLoss' => $_POST['maxLoss'],
								'lgnUserMaxStake' => $_POST['maxStake'],
								'sessionMaxStake' => $_POST['sessionMaxStake'],
								'InPlayStack' => $_POST['InPlayStack'],
								'create_no_of_child' => $create_no_of_child,
								'HelperID' => $_POST['HelperID']
								);
			//print_r($dataArray);
            if($userType==1){
                $dataArray['master_session_delay']=$_POST['session_delay'];
                $dataArray['master_set_timeout']=$_POST['set_timeout'];
            }
			$this->db->trans_begin();

    		$this->db->where('mstrid',$_POST['id']);
            $q1=$this->db->update('createmaster', $dataArray);



            if($userType==1 || $userType==2){
                if($userType==1){
                    $sessionColoumKey = 'master_session_delay';
                    $oddsColoumKey = 'master_set_timeout';
                }elseif ($userType==2){
                    $sessionColoumKey = 'dealer_session_delay';
                    $oddsColoumKey = 'dealer_set_timeout';
                }
                //print_r($this->checkChildIds($_POST['id']));die;
                //$this->db->where('parentId',$_POST['id']);
                $this->db->where_in('parentId', $this->checkChildIds($_POST['id']));
                $q1=$this->db->update('createmaster', [$sessionColoumKey=>$_POST['session_delay'],$oddsColoumKey=>$_POST['set_timeout']]);
            }


            //$loginArray = array('mstrname' => $_POST['Name']);
    		//$this->db->where('mstrname',$_POST['userId']);
            //$q2=$this->db->update('loginusers', $loginArray);

            //start user working table save the data By Manish 31/12/2016
		        $wortype="VIEW_ACC";
		        $remarks=$_POST['userType'].">>".$_POST['userId'].">>";
//		        $remarks .= $_POST['partnership'];
		        	$userWrkingArray = array(
								            'woruser' 			=> $_POST['userId'],
								            'wormode' 			=> 1,
								            'wordate' 			=> date('Y-m-d H:i:s',now()),
								            'wortype' 			=> $wortype,
								            'worcode' 			=> $_POST['id'],
								            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
								            'worrema'			=> $remarks,
								            'worcudt'			=> date('Y-m-d H:i:s',now()),
								        );
		        	$condition=$this->db->insert('userworkin', $userWrkingArray);
		    //End of useworking table

		    /* Note : Partner is updating by Createmastercontroller/updatePartnerShip/ api 
            //start the tble Partner Update
            	if ($_POST['userType']==1) {

			        	$adminPval=100-$_POST['partnership'];
			        	$masterPval=$_POST['partnership'];
			        	$dealerPval=0;

		        }else if($_POST['userType']==2){

		        	$adminPval=100-$_POST['PntPartenerShip'];
		        	$masterPval=$_POST['PntPartenerShip']-$_POST['partnership'];
		        	$dealerPval=$_POST['partnership'];

		        } 
			       
			        //Manish add for free chip
		        if ($_POST['userType']==2 || $_POST['userType']==1) {
		        	
			        //print_r($_POST);die();
			        //$this->db->insert('tblpartner', $partnershipData);
			        $userId=$_POST['id'];
			        $pTypeId=$_POST['userType'];
			        $pParantId=$_POST['parantId'];
					$query =$this->db->query("call InsPartner($pTypeId,$pParantId,$userId,$adminPval,$masterPval,$dealerPval)");
		        } 
             //End of TblPartner
             */

            		if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return false;
					}
					else{
					    $this->db->trans_commit();
					    return true;
					}
            
            //return true; 
		}
	function viewUserAcData($id)//sourabh 11-nov-2016
	{
		$this->db->select('mstruserid,usetype,mstrlock,lgnusrlckbtng,lgnusrCloseAc,IFNULL(lgnUserMaxStake,"0") as stakeLimit,lgnUserMaxProfit,lgnUserMaxLoss,sessionMaxStake,IFNULL(InPlayStack,"0") as GoingInplayStakeLimit,set_timeout,match_stake,one_click_stake');
		$this->db->from('createmaster');
		$this->db->where('mstrid', $id);
		$query = $this->db->get();
		return $data = $query->result_array();
	}
	function updateOddsLimit($limit,$matchId)//sourabh 11-nov-2016
	{
		$dataArray = array('oddsLimit' => $limit);
    	$this->db->where('MstCode',$matchId);
        $this->db->update('matchmst', $dataArray);
        return true; 
	}
	function updateStakeLimit($limit,$usecode)//Manish 25-nov-2016
	{
		$dataArray = array('stakeLimit' => $limit);
    	$this->db->where('mstrid',$usecode);
        $this->db->update('createmaster', $dataArray);
        return true; 
	}
	function updateCommission($Commission,$usecode)//Manish 25-nov-2016
	{
		$dataArray = array('Commission' => $Commission);
    	$this->db->where('mstrid',$usecode);
        $this->db->update('createmaster', $dataArray);
        return true; 
	}
	function UpdateMaxProfit($profit,$usecode)//Manish 25-nov-2016
	{
		$dataArray = array('lgnUserMaxProfit' => $profit);
    	$this->db->where('mstrid',$usecode);
        $this->db->update('createmaster', $dataArray);
        return true; 
	}
	function UpdateMaxLoss($loss,$usecode)//Manish 25-nov-2016
	{
		$dataArray = array('lgnUserMaxLoss' => $loss);
    	$this->db->where('mstrid',$usecode);
        $this->db->update('createmaster', $dataArray);
        return true; 
	}
	function UpdateMaxStake($stake,$usecode)//Manish 25-nov-2016
	{
		$dataArray = array('lgnUserMaxStake' => $stake);
    	$this->db->where('mstrid',$usecode);
        $this->db->update('createmaster', $dataArray);
        return true; 
	}
	function suspendFancy(){
        try {
            $redis = new Redis();
            $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
            $key = $this->db->database.'ind_' . $_POST['matchId'] . '_'.$_POST['FancyId'];
            //echo $this->db->database.'ind_' . $_POST['matchId'] . '_'.$_POST['FancyId'];die;
            $sessionOdds = json_decode($redis->get($key),true);
            $sessionOdds['active'] = $_POST['status'];
            $sessionOdds['SessInptYes'] = $_POST['YesVal'];
            $sessionOdds['SessInptNo'] = $_POST['NoVal'];
            $sessionOdds['MaxStake'] = $_POST['MaxStake'];
            $sessionOdds['NoValume'] = $_POST['NoValume'];
            $sessionOdds['YesValume'] = $_POST['YesValume'];
            $sessionOdds['pointDiff'] = $_POST['pointDiff'];
            $sessionOdds['rateDiff'] = $_POST['rateDiff'];
            $sessionOdds['DisplayMsg'] = '';
            if(!empty($_POST['fancy_mode'])){
                $sessionOdds['fancy_mode'] = $_POST['fancy_mode'];
            }
           // print_r($sessionOdds);die;
            $redis->set($key, json_encode($sessionOdds));
            $redis->close();
            return true;
        } catch (Exception $e) {
            return false;
        }
	}
	function updateVolumeLimit($limit,$matchId)//sourabh 30-nov-2016
	{
		$dataArray = array('volumeLimit' => $limit);
    	$this->db->where('MstCode',$matchId);
        $this->db->update('matchmst', $dataArray);
        return true; 
	}

    function updateMinAndMaxStackLimit()//sourabh 30-nov-2016
    {

        $dataArray = array($_POST['key'] => $_POST['value']);
        $this->db->where('MstCode',$_POST['match_id']);
        $this->db->update('matchmst', $dataArray);
        //echo $this->db->last_query();die;
        return true;
    }

	function sumOfOdds($MarketId,$userId,$userType,$matchId)//sourabh 161226 change
	{
	if($userId==null)$userId1=0;else $userId1=$userId;
		
			$query =$this->db->query("call SP_OddsProfitLossNew($userId1,$userType,$matchId,$MarketId)");//sourabh 161226 change
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;

	}
	function get_userList($userType){
		$this->db->select('mstrid as id,mstruserid as label');
		$this->db->from('createmaster');
		$this->db->where('usetype', $userType);
		
		$query = $this->db->get();
		return $data = $query->result_array();
	}
	function get_menuList(){
		$this->db->select('*');
		$this->db->from('menuoption');
		#$this->db->where('usetype', $userType);
		
		$query = $this->db->get();
		return $data = $query->result_array();
	}
	function getMarketInfo($MarketId)//sourabh 7-dec-2016
	{
		$this->db->select('m.Name as MarketName,m.Id as MarketID,m.sportsId as SportID,s.name as SportName ');
		$this->db->from('market m');
		$this->db->where('m.Id', $MarketId);
		$this->db->join('sportmst s','s.id=m.sportsId','inner');
		$query = $this->db->get();
		return $data = $query->result_array();
	}
	function SaveSubAdmin(){
		$insertData = array(

                'mstrname' 		=> $this->input->post('name'),
                'usename' 		=> $this->input->post('username'),
                'usepass' 		=> sha1($this->input->post('password')),
                'usetype' 		=> $this->input->post('type'),
                'ipadress' 		=> $_SERVER['REMOTE_ADDR']
	            );
        $insertcreteMaster = array(

                'mstrname' 		=> $this->input->post('name'),
                'mstruserid' 		=> $this->input->post('username'),
                'mstrpassword' 		=> sha1($this->input->post('password')),
                'usetype' 		=> $this->input->post('type'),
                'ipadress' 		=> $_SERVER['REMOTE_ADDR']
        );
        $query1=$this->db->insert('createmaster', $insertcreteMaster);
		//$query=$this->db->insert('loginusers', $insertData);
		if ($query1) {
			return true;
		}else{
			return false;
		}


	}
	function getSubadmin()//sourabh 7-dec-2016
	{
		//$this->db->select('usecode,mstrname,usename');
		//$this->db->from('loginusers');
		//$this->db->where('usetype', 4);

		$this->db->select('mstrid as usecode,mstrname,mstruserid as usename');
		$this->db->from('createmaster');
		$this->db->where('usetype', 4);
		$query = $this->db->get();
		return $data = $query->result_array();
	}
	function getSelectionName($matchId,$marketId)//sourabh 161228
	{
		$this->db->select('*');
		$this->db->from('tblSelection');
		$this->db->where('matchId', $matchId);
		$this->db->where('marketId', $marketId);
		$query = $this->db->get();
		return $data = $query->result_array();
	}
	function getResetPassword(){

			$this->db->select('Password');
			$this->db->from('tblconfig');
			$query = $this->db->get();
			$data = $query->result_array();
			$password =$data[0]['Password'];
			return $password;

	}
	function updateUserAcPasswrd($userId,$passwrd,$HelperID){
			$dataArray = array('mstrpassword' => sha1($passwrd),'HelperID' => $this->input->post('HelperID'));
			$this->db->where('mstrid',$userId);
			$query=$this->db->update('createmaster', $dataArray);
			return $query; 
	}
	function UpdateCnfgPasssword($passwrd){
			$dataArray = array('Password' => $passwrd);
			$this->db->where('Id',1);
			$query=$this->db->update('tblconfig', $dataArray);
			return $query; 
	}
	function submitClearChip()//sourabh 161229
	{
        $ModelChipModel = $this->model_load_model('Chip_model');
        $GetpId=$this->Get_ParantId($this->input->post('UserID'));
        $ParantId=$GetpId[0]->parentId;
        $UserID=$_POST['UserID'];
        $LoginID=$_POST['LoginID'];
        $CrDr=$_POST['CrDr'];
        $Chips=$_POST['Chips'];
        $IsFree=$_POST['IsFree'];
        $Narration=$_POST['Narration'];
        $HelperID=$_POST['HelperID'];

        $data = $ModelChipModel->getLiability($ParantId);

       // $userData = $ModelChipModel->getLiability($this->input->post('UserID'));

        $sql = $this->db->query("SELECT SUM(a.ChipsDr-a.ChipsCr) chipspnl
FROM tblchipdet a
INNER JOIN createmaster b ON b.mstrid = a.oppAcID
WHERE a.ChildID=$UserID
GROUP BY b.mstrid, usetype, mstruserid, mstrname, ChildID");

        $userData = $sql->row_array();





        if(isset($userData['chipspnl']) && $Chips > abs($userData['chipspnl'])){ //echo 'if';
            $chipspnl = sprintf('%0.2f', $userData['chipspnl']);
            $settlementMsg = 'Settlement amount must be less then or equal to '.abs($chipspnl);
            $result = array(0=>array('resultV'=>1,'retMess'=>$settlementMsg));
            return $result;
        }

        $configSettlement = CONFIG_SETTLEMENT;
        if($configSettlement == 'CREDIT'){
            if($CrDr==2 && isset($data[0]['Balance']) && $data[0]['Balance'] < $Chips){
                $result = array(0=>array('resultV'=>1,'retMess'=>'Insufficient balance parent a/c'));
                return $result;
            }
        }

        $query = $this->db->query("call sp_insClearChip($UserID,$LoginID,$CrDr,$Chips,$IsFree,'$Narration',$ParantId,$HelperID)");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        $this->updateUserBalLiablity($this->input->post('UserID'));
        return $res;
    }
	function Get_ParantId($userId){
			$this->db->select("parentId");
			$this->db->from('createmaster');
			$this->db->where('mstrid',$userId);
			$query = $this->db->get();
			return $query->result();
	}
	function GetFancyById($FancyID,$fancyType){

			$this->db->select('MatchID,HeadName,TypeID,Remarks,date,time,SessInptYes,SessInptNo,ID');
			$this->db->from('matchfancy');
			$this->db->where('ID', $FancyID);
			$this->db->where('TypeID', $fancyType);
			$query = $this->db->get();
			$data = $query->result_array();
            //echo $this->db->queries[0];die();
            //$password =$data[0]['usepass'];
			return $data;

	}
	function UpdateSessionFancy(){
		$Fancy=$this->GetFancyById($_POST['FancyID'],$_POST['fancyType']);
					
					$fancyId=$Fancy[0]['ID'];
					$MatchID=$Fancy[0]['MatchID'];
					$HeadName=$Fancy[0]['HeadName'];
					$TypeID=$Fancy[0]['TypeID'];
					$Remarks=$Fancy[0]['Remarks'];
					$date=$Fancy[0]['date'];
					$time=$Fancy[0]['time'];
					$SessInptYes=$Fancy[0]['SessInptYes'];
					$SessInptNo=$Fancy[0]['SessInptNo'];
					//$insertData = array('HeadName'=> $HeadName,'TypeID'=> $TypeID,'MatchID'=> $MatchID,'Remarks'=>$Remarks,'date'=>$date,'time'=>$time,'SessInptYes'=>$SessInptYes,'SessInptNo'=>$SessInptNo,'MatchFancyId'=>$fancyId);
					//Start Transaction

					$this->db->trans_begin();

					//$insertQuery=$this->db->insert('tblsessionfancy', $insertData);
					$dataArray =  array('HeadName'=> $_POST['HeadName'],'TypeID'=> $_POST['fancyType'],'MatchID'=> $_POST['mid'],'Remarks'=>$_POST['remarks'],'date'=>date('Y-m-d',now()),'time'=>date('H:i:s',now()),'SessInptYes'=>$_POST['inputYes'],'SessInptNo'=>$_POST['inputNo'],'active'=>1);
		    		//Update The session Fancy
		    		$this->db->where('ID',$_POST['FancyID']);
		    		$this->db->where('TypeID',$_POST['fancyType']);
		    		
		            $UpdateQuery=$this->db->update('matchfancy', $dataArray);
		            $creFancyId=$this->db->insert_id();
					//start user working table save the data By Manish 02/1/2017
			        	$wortype="Session Fancy";
			        	$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
			        	$userWrkingArray = array(
									            'woruser' 			=> $_POST['HeadName'],
									            'wormode' 			=> 1,
									            'wordate' 			=> $date,
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $creFancyId,
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );
			        	$condition=$this->db->insert('userworkin', $userWrkingArray);
			        //End of useworking table
		           if ($this->db->trans_status() === FALSE)
					{
					    $this->db->trans_rollback();
					     return false;
					}
					else
					{
					    $this->db->trans_commit();
					    return true;
					}
		           //Complete Transaction
					
				//}

	}
	function getMaxProfit($id,$MatchId,$MarketId)//sourabh 170102 new
	{
			/*$query =$this->db->select('IFNULL(Sum(P_L),"0") as maxProfit');
			$query = $this->db->where('UserId',	$id);
            $query = $this->db->where('MatchId',$MatchId);
            $query = $this->db->where('MarketId',$MarketId);
			$query = $this->db->where('IFNULL(ResultID,0)',	'0');
			$query = $this->db->where('IFNULL(isBack,0)','0');
			$query = $this->db->get('tblbets');
			$result = $query->result();
			return $result;*/
			//echo "call getMarketLiability($id,$matchId,'$marketId')";
			//die();
			$query =$this->db->query("call getMarketLiability($id,$MatchId,'$MarketId')");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}
	function editFancy(){//sourabh 170118

			$row = $this->db->query('SELECT MAX(MFancyID) AS `maxid` FROM `matchfancy`')->row();
			//print_r($row);
			$maxid = $row->maxid+1; 
			if(empty($_POST['mid'])==1)  $pMatchID=0;else $pMatchID=$_POST['mid'];
			if(empty($_POST['HeadName'])==1)  $pHeadName='';else $pHeadName=$_POST['HeadName'];
			$pTypeID=$_POST['fancyType'];
			if(empty($_POST['remarks'])==1) $pRemarks='';else $pRemarks=$_POST['remarks']; 
			if(empty($_POST['date'])==1)  $pdate=null;else $pdate=$_POST['date'];
			if(empty($_POST['time'])==1) $ptime=null;else $ptime=$_POST['time']; 
			if(empty($_POST['inputYes'])==1) $pSessInptYes=0;else $pSessInptYes=$_POST['inputYes'];
			if(empty($_POST['inputNo'])==1) $pSessInptNo=0;else $pSessInptNo=$_POST['inputNo']; 
			if(empty($_POST['fancyRange'])==1)  $pfancyRange=0;else $pfancyRange=$_POST['fancyRange'];
			if(empty($_POST['PlayerId'])==1) $pPlayerId=0; else $pPlayerId=$_POST['PlayerId'];
			$pactive=1;  
			$presult='';  
			if(empty($_POST['Back_size'])==1)  $pupDwnBack=0;else $pupDwnBack=$_POST['Back_size'];
			if(empty($_POST['Lay_size'])==1) $pupDwnLay=0; else $pupDwnLay=$_POST['Lay_size'];
			$pMFancyID=$maxid; 
			if(empty($_POST['sid'])==1)  $pSprtId=0;else $pSprtId=$_POST['sid'];
			if(empty($_POST['RateDiff'])==1) $prateDiff=0;else $prateDiff=$_POST['RateDiff']; 
			if(empty($_POST['PointDiff'])==1) $ppointDiff=0;else $ppointDiff=$_POST['PointDiff']; 
			if(empty($_POST['MaxStake'])==1)  $pMaxStake=0;else $pMaxStake=$_POST['MaxStake'];
			if(empty($_POST['NoLayRange'])==1) $pNoValume=0;else $pNoValume=$_POST['NoLayRange']; 
			if(empty($_POST['YesLayRange'])==1)  $pYesValume=0;else $pYesValume=$_POST['YesLayRange'];
			$pID=$_POST['FancyId'];
			if(empty($_POST['upDwnLimit'])==1) $upDwnLimit=0;else $upDwnLimit=$_POST['upDwnLimit'];
			if($_POST['fancyType']==4){
				$upDwnLimit=$_POST['upDwnLimit'];
				//echo "call sp_UpdMatchfancy($pMatchID,'$pHeadName',$pTypeID,'$pRemarks','$pdate','$ptime',$pSessInptYes,$pSessInptNo,$pfancyRange,$pPlayerId,$pactive,'$presult',$pupDwnBack,$pupDwnLay,$pMFancyID,$pSprtId,$prateDiff,$ppointDiff,$pMaxStake,$pNoValume,$pYesValume,$pID,$upDwnLimit)";
				$query = $this->db->query("call sp_UpdMatchfancy($pMatchID,'$pHeadName',$pTypeID,'$pRemarks','$pdate','$ptime',$pSessInptYes,$pSessInptNo,$pfancyRange,$pPlayerId,$pactive,'$presult',$pupDwnBack,$pupDwnLay,$pMFancyID,$pSprtId,$prateDiff,$ppointDiff,$pMaxStake,$pNoValume,$pYesValume,$pID,$upDwnLimit)");
			}else{
				$upDwnLimit=0;
				$query = $this->db->query("call sp_UpdMatchfancy($pMatchID,'$pHeadName',$pTypeID,'$pRemarks','$pdate','$ptime',$pSessInptYes,$pSessInptNo,$pfancyRange,$pPlayerId,$pactive,'$presult',$pupDwnBack,$pupDwnLay,$pMFancyID,$pSprtId,$prateDiff,$ppointDiff,$pMaxStake,$pNoValume,$pYesValume,$pID,$upDwnLimit)");
			}
			//echo $this->db->queries[1];die();
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
			
		}
		function getMatchOddsLimit($matchId,$marketId){ //sourabh 170201
	    
			$mSql="select sum(result) as result,sum(oddsLimit) as oddsLimit,sum(volumeLimit) as volumeLimit from ( select 0 as result,oddsLimit,volumeLimit from matchmst where MstCode=".$matchId." UNION SELECT IFNULL(max(result),0) as result,0 as oddsLimit,0 as volumeLimit FROM tblresult where marketid=".$marketId." and matchId=".$matchId.") a";
			$query = $this->db->query($mSql);
			return $query->result_array();
		}

		function getMatchOddsLimitByMatches($matchId){
			$this->db->select('matchmst.MstCode as match_id,0 as result,matchmst.oddsLimit,matchmst.volumeLimit');
			$this->db->from('matchmst');
			$this->db->where_in('MstCode', $matchId);
			$query = $this->db->get();
			return $data = $query->result_array();
		}

		function get_Master_AltSubAdmin($userType,$adminId){//170210
			$this->db->select('l.mstrid as id,l.mstruserid as label,h.HelperID');
			$this->db->from('createmaster l');
			$this->db->join('tblHelper h','l.mstrid=h.MasterID','left');
			$this->db->where('l.usetype', $userType);
			$this->db->where('IFNULL(h.HelperID,'.$adminId.')', $adminId);
			$query = $this->db->get();
			return $data = $query->result_array();
		}
		function get_Helper_MenuList($adminId)//170210
		{
			$this->db->select('*');
			$this->db->from('tblHelperRight');
			$this->db->where('HelperID', $adminId);
			$query = $this->db->get();
			return $query->result_array();
		}
		function save_path($image){
            $saveImagePath = array(  'img_name'	=> $image,
                'date'	    => date('Y-m-d H:i:s',now()),
            );
            $condition=$this->db->insert('slider_img', $saveImagePath);
            return $condition;
        }
        function get_sliderImage(){
            $this->db->select('*');
            $this->db->from('slider_img');
            $query = $this->db->get();
            return $query->result_array();
        }
        function get_DashBoardImage(){
            $this->db->select('*');
            $this->db->from('slider_img');
            $this->db->where('active', 1);
            $query = $this->db->get();
            return $query->result_array();
        }
        function update_slider($id,$status){
            $updateData = array('active' => $status);
            $this->db->where('id', $id);
            $condition=$this->db->update('slider_img', $updateData);
            return $condition;
        }
        function deleteImage($id){
            $this->db->where('id', $id);
            $condition=$this->db->delete('slider_img');
            return $condition;
        }

        function deleteSubAdminById($id){

            $this->db->where('mstrid ', $id);
            $condition=$this->db->delete('createmaster');

            return $condition;
        }

		function updateSeriesSatatus($seriesId,$active){//170221
			$dataArray = array('active' => $active);
    		$this->db->where('seriesId',$seriesId);
            $this->db->update('seriesmst', $dataArray);
            return true; 
		}
		function updateMarketStake($MarketId,$stake){//170221
			$dataArray = array('gin_play_stake' => $stake);
    		$this->db->where('Id',$MarketId);
            $this->db->update('market', $dataArray);
            return true; 
		}
  
    function setLiability(){
        $updateData = array('upDwnLimit' => $_POST['liability']);
        $this->db->where('ID', $_POST['FancyId']);
        $condition=$this->db->update('matchfancy', $updateData);
        return $condition;
    }
    function updatePartnerShip($admin,$master,$dealer,$userId,$HelperID){
    	$dataArray = array( 'Admin' => $admin,'Master'=>$master,'Dealer'=>$dealer,'HelperID'=>$HelperID);
        $this->db->where('UserID',$userId);
        $query=$this->db->update('tblpartner', $dataArray);

        if($query){
        	$this->updateChildPartnership($userId,$dataArray);	
        }

        //echo $this->db->queries[0];
        return $query;
    }
     function updateUserCommission($oddsComm,$sessionComm,$otherComm,$ID,$HelperID){
    	$dataArray = array( 'Commission' => $oddsComm,'SessionComm'=>$sessionComm,'OtherComm'=>$otherComm,'HelperID'=>$HelperID);
        $this->db->where('mstrid',$ID);
        $query=$this->db->update('createmaster', $dataArray);
        //echo $this->db->queries[0];die();
        return $query;
    }
     function changeLgnPassword(){
     	$this->db->select('mstruserid as usename,mstrpassword as usepass,usetype');
		$this->db->from('createmaster');
		$this->db->where('mstrid', $_POST['userId']);
		$this->db->where('mstrpassword', sha1($_POST['oldPassword']));
		$query = $this->db->get();
		if($query->num_rows()==1){

			$password=sha1($_POST['newPassword']);
	    	$dataArray = array( 'mstrpassword' => $password,'chkupdatePass'=>1);
	        $this->db->where('mstrid',$_POST['userId']);
	        $query=$this->db->update('createmaster', $dataArray);
	        //echo $this->db->queries[1];die();			
			return $query;
		}
		else{
			return 0;
		}
     	
    }
	function changeLgnBitC(){
	    	$dataArray = array('chkupdatePass'=>1);
	        $this->db->where('mstrid',$_POST['userId']);
	        $query=$this->db->update('createmaster', $dataArray);
			return 1;//$query;
    }
	function chaneMarketPPStatus($userId,$matchId,$marketId,$fancyId,$usertype,$IsPlay){
		$GetpId=$this->Get_ParantId($userId);
		$ParantId=$GetpId[0]->parentId;
		//echo $IsPlay;
		/*if ($IsPlay==0) {
			$IsPlay1=0;
		}else{
			$IsPlay1=0;
		}*/
		//echo "call sp_SetRights($userId,$ParantId,$userId,$matchId,'$marketId',$fancyId,$IsPlay1,$usertype)";
		$query =$this->db->query("call sp_SetRights($userId,$ParantId,$userId,$matchId,'$marketId',$fancyId,$IsPlay,$usertype)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
    }
    function sp_getMTreeTemp($userId){
		
		$query =$this->db->query("call sp_getMTreeTemp($userId)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}
	function getBetDelay($userId){
		$this->db->select('GREATEST(set_timeout, admin_set_timeout, master_set_timeout, dealer_set_timeout) set_timeout,GREATEST(session_delay, admin_session_delay, master_session_delay, dealer_session_delay) session_delay',false);
		$this->db->from('createmaster');
		$this->db->where('mstrid',$userId);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * [getChildUsers get child user from parent user id]
	 * @param  int $userId [user id]
	 * @return array         [users array]
	 */
	function getChildUsers($userId=NULL){
		$this->db->select('mstrid,lgnusrlckbtng,lgnusrCloseAc,usetype,mstruserid,create_no_of_child');
		$this->db->from('createmaster');
		$this->db->where('parentId',$userId);
		$query = $this->db->get();
		return $query->result_array();
	}

    function getUserById($userId){
        $this->db->select('*');
        $this->db->from('createmaster');
        $this->db->where('mstrid',$userId);
        $query = $this->db->get();
        return $query->row();
    }

	/**
	 * [closeDealerAccount close dealer account]
	 * @param  [int] $dealerId [dealer id]
	 * @return [NULL]           
	 */
	function closeDealerAccount($dealerId=NULL){
		$updateDataArr = array('lgnusrCloseAc' => 0);

		$dealerChildUsers = $this->getChildUsers($dealerId);
		foreach($dealerChildUsers as $dcUsers){
			if($dcUsers['lgnusrCloseAc']==1){
				$dealerChildUserIds[] = $dcUsers['mstrid'];	
			}
		}

		if(!empty($dealerChildUserIds)){			
			$this->db->where_in('mstrid', $dealerChildUserIds);
			$this->db->update('createmaster', $updateDataArr);
		}
		
		$this->db->where('mstrid', $dealerId);
		$this->db->update('createmaster', $updateDataArr);
		
	}

	/**
	 * [closeMasterAccount close master account]
	 * @param  [int] $masterId [master id]
	 * @return [NULL]           
	 */
	function closeMasterAccount($masterId=NULL){
		$masterChildUsers = $this->getChildUsers($masterId);
		foreach($masterChildUsers as $dcUsers){
			$this->closeDealerAccount($dcUsers['mstrid']);
		}
		$updateDataArr = array('lgnusrCloseAc' => 0);
		$this->db->where('mstrid', $masterId);
		$this->db->update('createmaster', $updateDataArr);
	}

	/**
	 * [lockUnlockBettingUsers lock betting of users]
	 * @param  array  $users [array('user_type'=>3,'user_name'=>'John')]
	 * @return [boolean]        [response]
	 */
	function lockBettingUsers_bk($users=array()){

		$lockbettingVal = 0;
		$HelperID = 0;

		foreach($users as $user){
			$userType = $user['usetype'];
			$userName = $user['mstruserid'];
			$userId = $user['mstrid'];

			$wortype="UserLock Betting";
			$remarks="USER TYPE_ID : ".$userType.">> USER_ID : ".$userName.">> HelperID : ".$HelperID;
			$userWrkingArray = 	array('woruser'	=> $userId,'wormode'=> 1,'wordate'=> date('Y-m-d H:i:s',now()),'wortype'=> $wortype,'worcode'=> $userId,'worsysn'=> $_SERVER['REMOTE_ADDR'],'worrema'=> $remarks,'worcudt'=> date('Y-m-d H:i:s',now()));
			$this->db->trans_begin();

			$dataArray = array('lgnusrlckbtng' => $lockbettingVal,'HelperID' => $HelperID);
			$this->db->where('mstrid',$userId);
			$q1=$this->db->update('createmaster', $dataArray);
			$condition=$this->db->insert('userworkin', $userWrkingArray);
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			}
			else{
				$this->db->trans_commit();
			}
		}
	}

    function lockBettingUsers($users=array()){

        $lockbettingVal = $_POST['lockbettingVal'];
        $HelperID = 0;
        $lgnusrlckbtng_by_user_id = $this->session->userdata('user_id');

        foreach($users as $user){

            $userType = $user['usetype'];
            $userName = $user['mstruserid'];
            $userId = $user['mstrid'];
            $success = true;

            if($lockbettingVal == 1){

                $lgnusrlckbtng_by_user_id = 0;

                $this->db->select('a.mstrid AS user_id, b.lgnusrlckbtng AS parent_user_lgnusrlckbtng, a.self_lgnusrlckbtng AS self_lgnusrlckbtng, a.parent_lgnusrlckbtng AS parent_lgnusrlckbtng');
                $this->db->from('createmaster a');
                $this->db->join('createmaster b','a.parentId = b.mstrid', 'LEFT');
                $this->db->where('a.mstrid', $userId);
                $this->db->limit(1);

                $query = $this->db->get();
                $data = $query->result_array();

                if($data[0]['self_lgnusrlckbtng'] != 0){
                    $lockbettingVal = 0;
                }
                if($data[0]['parent_lgnusrlckbtng'] == 0){
                    $success = false;
                }
            }

            if($success){
                $wortype="UserLock Betting";
                $remarks="USER TYPE_ID : ".$userType.">> USER_ID : ".$userName.">> HelperID : ".$HelperID;
                $userWrkingArray =     array('woruser'    => $userId,'wormode'=> 1,'wordate'=> date('Y-m-d H:i:s',now()),'wortype'=> $wortype,'worcode'=> $userId,'worsysn'=> $_SERVER['REMOTE_ADDR'],'worrema'=> $remarks,'worcudt'=> date('Y-m-d H:i:s',now()));
                $this->db->trans_begin();

                $dataArray = array('lgnusrlckbtng' => $lockbettingVal, 'HelperID' => $HelperID, 'parent_lgnusrlckbtng' => $lgnusrlckbtng_by_user_id);
                $this->db->where('mstrid',$userId);
                $q1=$this->db->update('createmaster', $dataArray);
                $condition=$this->db->insert('userworkin', $userWrkingArray);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }
                else{
                    $this->db->trans_commit();
                }
            }
        }
    }

	function validateUser($username, $password) {

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
    }

    function findBetfairToken(){
		$this->db->select('betfair_session_token');
		$this->db->from('createmaster');
		$this->db->where('mstrid',1);		
		$query = $this->db->get();
		$res = $query->result_array();	
		$betfairSessionToken = '';
		if(!empty($res[0]['betfair_session_token'])){
			$betfairSessionToken = $res[0]['betfair_session_token'];
		}
		return $betfairSessionToken;
	}

	function saveBetfairToken($sessionToken=NULL){
		$dataArray = array('betfair_session_token' => $sessionToken);
		$this->db->where('mstrid',1);
		$this->db->update('createmaster', $dataArray);
	}
    function checkSubAdminActiveById($userId){
        $this->db->select('*');
        $this->db->from('sub_admin');
        $this->db->where('mstrid',$userId);
        $this->db->where('active',1);
        $query = $this->db->get();
        return $query->num_rows();
    }

	function checkUserStatus($username,$password){
		$result = array();
		$this->db->select('mstrid,usetype,parentId');
        if($this->session->userdata('subAdmin')){
            $this->db->from('sub_admin');
        }else{
            $this->db->from('createmaster');
        }
		$this->db->where('mstruserid',$username);	
		$encodedPassword = sha1($password);
		$this->db->where('(`mstrpassword` = \''.$password.'\' OR `mstrpassword` = \''.$encodedPassword.'\')', NULL, FALSE);
		$this->db->where('active',1);	
		$this->db->where('mstrlock',1);	
		$this->db->where('lgnusrCloseAc',1);	
		$query = $this->db->get();
		$res = $query->result_array();	
		if(!empty($res)){
			$result = $res[0];
		}
		return $result;
	}
    function activeUser(){

        $this->db->select('mstrid,usetype,parentId');
        $this->db->from('createmaster');
        $this->db->where('active',1);
        $query = $this->db->get();
        $res = $query->result_array();
        return count($res);
    }
	function checkAuthUser($username,$password){
		$result = array();
		$this->db->select('mstrid');
		$this->db->from('createmaster');
		$this->db->where('mstruserid',$username);	
		$encodedPassword = sha1($password);
		$this->db->where('(`mstrpassword` = \''.$password.'\' OR `mstrpassword` = \''.$encodedPassword.'\')', NULL, FALSE);
		$query = $this->db->get();
		$res = $query->result_array();	
		if(!empty($res)){
			$result = $res[0];
		}
		return $result;
	}

	function updatePatner($userId=NULL){

		$this->db->select('TypeID,Master,Dealer');
		$this->db->from('tblpartner');
		$this->db->where('UserID',$userId);	
		$query = $this->db->get();
		$res = $query->row_array();	

		if(!empty($res)){

			if($res['TypeID']==1){
				$patner = $res['Master'];
			}else if($res['TypeID']==2){
				$patner = $res['Dealer'];
			}

			$dataArray = array('partner' => $patner);
			$this->db->where('mstrid',$userId);
			$this->db->update('createmaster', $dataArray);
		}
		
	}


	function Tuncate_matchlst(){
		$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
		$this->db->truncate('seriesmst');
		$this->db->where('mstrid !=', '1');
		$this->db->delete('createmaster');
		$this->db->truncate('tblpartner');
		$this->db->truncate('matchmst');
		$this->db->truncate('matchfancy');
		$this->db->truncate('market');
		$this->db->truncate('tblbets');
		$this->db->truncate('tblchipdet');
		$this->db->truncate('tblchips');
		$this->db->truncate('tblresult');
		$this->db->truncate('tblsessionfancy');
		$this->db->truncate('bet_entry');
		$this->db->truncate('tblselection');
		$this->db->truncate('tblrights');
		$this->db->truncate('tblcashentry');
		$this->db->truncate('p_l_by_match');
		$this->db->truncate('manual_match_odds');
		$this->db->truncate('user_deactive_match');
		$this->db->truncate('user_deactive_match_session');			
		$this->db->truncate('check_detectamount');
		$this->db->truncate('userlogged');
		$this->db->truncate('userworkin');
		$this->db->where('MstCode >', '14');
		$this->db->delete('menuoption');
		$this->db->query("SET FOREIGN_KEY_CHECKS = 1"); 
		$superAdminId = 1;
		$this->updateUserBalLiablity($superAdminId);

	}

	/**
	 * [insertChipsAfterBetTruncate insert chips after bet truncate]
	 * @return 
	 */
	function insertChipsAfterBetTruncate(){

		$query =$this->db->query("call GetUnsettledMatch()");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result(); 

		if(empty($res)){
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->db->truncate('seriesmst');
			$this->db->truncate('matchmst');
			$this->db->truncate('matchfancy');
			$this->db->truncate('market');
			$this->db->truncate('tblbets');
			$this->db->truncate('tblabandonedbets');
			$this->db->truncate('tblbets_bak');
			$this->db->truncate('tblchipdet');
			$this->db->truncate('tblchips');
			$this->db->truncate('tblchips_bak');
			$this->db->truncate('tblresult');
			$this->db->truncate('tblsessionfancy');
			$this->db->truncate('bet_entry');
			$this->db->truncate('tblselection');
			$this->db->truncate('tblrights');
			$this->db->truncate('tblcashentry');
			$this->db->truncate('p_l_by_match');
			$this->db->truncate('manual_match_odds');
			$this->db->truncate('user_deactive_match');
			$this->db->truncate('user_deactive_match_session');			
			$this->db->truncate('check_detectamount');
			$this->db->where("logstdt <= DATE_FORMAT(date_sub(now(), interval 1 month),'%Y-%m-%d')");
			$this->db->delete('userlogged');
			$this->db->where("wordate <= DATE_FORMAT(date_sub(now(), interval 1 month),'%Y-%m-%d')");
			$this->db->delete('userworkin');
			$this->db->where('MstCode >', '14');
			$this->db->delete('menuoption');
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			$this->db->select('cm.mstrid,cm.balance,pm.mstrname parent_name');
			$this->db->from('createmaster cm');
			$this->db->join('createmaster pm','cm.parentId = pm.mstrid');		
			$this->db->where('cm.mstrid !=','1');							
			$query = $this->db->get();
			$users = $query->result_array();
			foreach($users as $user){
				$userId = $user['mstrid'];
				$ChipVal = $user['balance'];
				$ParentName = $user['parent_name']; 
				$narration = "Deposit ".$ChipVal.' Coins by '.$ParentName;
				$insertData = array(
					'MstDate' 	=> date('Y-m-d H:i:s',now()),
					'UserID' 	=> $userId,
					'LoginID' 	=> 1,
					'CrDr' 		=> 1,
					'Chips' 	=> $ChipVal,
					'txnId' 	=> 0,
					'IsFree' 	=> 1,
					'Narration'	=> $narration,
					'HelperID' 	=> 0
				);
				$this->db->insert('tblchips', $insertData);

				$dataArray = array('liability'=> 0,'p_l'=>0,'freechips'=>$ChipVal,'chip'=>0,'sessionLiability'=>0,'unmatchliability'=>0);
				$this->db->where('mstrid',$userId);	
				$this->db->update('createmaster', $dataArray);
			}
		}
		return $res;
	}


	/**
	 * [insertChipsAfterBetTruncateNot insert chips after bet truncate]
	 * @return 
	 */
	function insertChipsAfterBetTruncateNot(){

		$query =$this->db->query("call GetUnsettledMatch()");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();

		if(!empty($res)){  
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
		//	$this->db->truncate('seriesmst');

			$this->db->where("seriesId NOT IN ('11365612')");
			$this->db->delete('seriesmst');

		//	$this->db->truncate('matchmst');

			$this->db->where("MstCode NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('matchmst');

		//	$this->db->truncate('matchfancy');

			$this->db->where("MatchID NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('matchfancy');

		//	$this->db->truncate('market');

			$this->db->where("matchId NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('market');

		//	$this->db->truncate('tblbets');
			
			$this->db->where("MatchId NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('tblbets');


			$this->db->truncate('tblabandonedbets');
			$this->db->truncate('tblbets_bak');
			$this->db->truncate('tblchipdet');
			$this->db->truncate('tblchips');
			$this->db->truncate('tblchips_bak');
			$this->db->truncate('tblresult');
			$this->db->truncate('tblsessionfancy');
		//	$this->db->truncate('bet_entry');   matchId

			$this->db->where("matchId NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('bet_entry');

		//	$this->db->truncate('tblselection');

			$this->db->where("matchId NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('tblselection');

		//	$this->db->truncate('tblrights');

			$this->db->where("MatchID NOT IN ('28996785','29000332','29001606')");
			$this->db->delete('tblrights');

			
			$this->db->truncate('tblcashentry');
			$this->db->truncate('check_detectamount');
			$this->db->truncate('p_l_by_match');
			$this->db->truncate('manual_match_odds');
			$this->db->truncate('user_deactive_match');
			$this->db->truncate('user_deactive_match_session');			
			$this->db->where("logstdt <= DATE_FORMAT(date_sub(now(), interval 1 month),'%Y-%m-%d')");
			$this->db->delete('userlogged');
			$this->db->where("wordate <= DATE_FORMAT(date_sub(now(), interval 1 month),'%Y-%m-%d')");
			$this->db->delete('userworkin');
			$this->db->where('MstCode >', '14');
			$this->db->delete('menuoption');
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			$this->db->select('cm.mstrid,cm.balance,pm.mstrname parent_name');
			$this->db->from('createmaster cm');
			$this->db->join('createmaster pm','cm.parentId = pm.mstrid');		
			$this->db->where('cm.mstrid !=','1');							
			$query = $this->db->get();
			$users = $query->result_array();
			foreach($users as $user){
				$userId = $user['mstrid'];
				$ChipVal = $user['balance'];
				$ParentName = $user['parent_name']; 
				$narration = "Deposit ".$ChipVal.' Coins by '.$ParentName;
				$insertData = array(
					'MstDate' 	=> date('Y-m-d H:i:s',now()),
					'UserID' 	=> $userId,
					'LoginID' 	=> 1,
					'CrDr' 		=> 1,
					'Chips' 	=> $ChipVal,
					'txnId' 	=> 0,
					'IsFree' 	=> 1,
					'Narration'	=> $narration,
					'HelperID' 	=> 0
				);
				$this->db->insert('tblchips', $insertData);

				$dataArray = array('liability'=> 0,'p_l'=>0,'freechips'=>$ChipVal,'chip'=>0,'sessionLiability'=>0,'unmatchliability'=>0);
				$this->db->where('mstrid',$userId);	
				$this->db->update('createmaster', $dataArray);
			}
		}
		return $res;
	}
	function manageManualOdds($data){
	    return $data;
	    /*if($data < 10){
	        return '1.0'.$data;
        }else{
           return '1.'.$data;
        }*/
    }

    function manageManualOddsLay($data){
        return $this->manageManualOdds($data);
        /*if($data >= 100){
            return 2;
        }else{
            return $this->manageManualOdds($data);
        }*/
    }

    function saveManualMatchOdds(){

        $redis = new Redis();
        $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);

        $market_id 	= $_POST['market_id'];
        $cur_date 	= date('Y-m-d H:i:s',now());
        $insertData = array(
            'market_id' 	=> $market_id,
            'team1_back' 	=> !empty($_POST['team1_back']) ? $this->manageManualOdds($_POST['team1_back']) : 0,
            'team1_lay' 	=> !empty($_POST['team1_lay']) ? ($this->manageManualOddsLay($_POST['team1_lay']) ) : 0,
            'active_team1' 	=> !empty($_POST['active_team1'])  ? '1'  : '0',
            'team2_back' 	=> !empty($_POST['team2_back']) ? $this->manageManualOdds($_POST['team2_back'] ) : 0,
            'team2_lay' 	=> !empty($_POST['team2_lay']) ? ($this->manageManualOddsLay($_POST['team2_lay'] )): 0,
            'active_team2' 	=> !empty($_POST['active_team2'])  ? '1' : '0',
            'draw_back' 	=> !empty($_POST['draw_back']) ? $this->manageManualOdds($_POST['draw_back']  ): 0,
            'draw_lay' 		=> !empty($_POST['draw_lay']) ? ($this->manageManualOddsLay($_POST['draw_lay']  )): 0,
            'active_draw' 		=> !empty($_POST['active_draw'])  ? '1'  : '0',
            'dlay_time' 		=> !empty($_POST['dlay_time']) ? $_POST['dlay_time']  : 0,
            'updatedOn'		=> $cur_date
        );
       // $this->updateUnmatchManualBets();
        $redis->set($market_id,json_encode($insertData));

        return true;
    }

    function saveManualMarketStack(){
        $manualMarketDetaisl = $this->manualMatchOddsDetails($_POST['market_id']);
        $min_stack = !empty($_POST['min_stack'])  ? $_POST['min_stack']  : 0;
        $max_stack = !empty($_POST['max_stack'])  ? $_POST['max_stack']  : 0;
        $manualMarketDetaisl['min_stack']=$min_stack;
        $manualMarketDetaisl['max_stack']=$max_stack;
        $redis = new Redis();
        $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);

        $redis->set($_POST['market_id'],json_encode($manualMarketDetaisl));
        $this->db->update("market",['min_stack'=>$min_stack,'max_stack'=>$max_stack],['Id'=>$_POST['market_id']]);
        return true;
    }

    public function updateUnmatchManualOddsLayBets($layPrice,$marketId,$selection)
    {
        $this->db->update("tblbets",['IsMatched'=>1],['isBack'=>1,'Odds >='=>$layPrice,'MarketId'=>$marketId,'SelectionId'=>$selection]);

    }
    public function updateUnmatchManualOddsBackBets($backPrice,$marketId,$selection)
    {
        $this->db->update("tblbets",['IsMatched'=>1],['isBack'=>0,'Odds <='=>$backPrice,'MarketId'=>$marketId,'SelectionId'=>$selection]);

    }
    function manualMatchOddsDetails($market_id){

        $redis = new Redis();
        $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
        $res = $redis->get($market_id);
        return json_decode($res,true);

    }

	function update($id=NULL,$updateArr=NULL){
		$this->db->where('mstrid',$id);
        $this->db->update('createmaster', $updateArr);
        return true; 
	}

    function closeUserAccount(){


        $userBalance = $this->getUserTreeBalance($_POST['userId']);
        if($userBalance['total_balance']==0){
            $lgnusrCloseAc_by_user_id = $this->session->userdata('user_id');
            $type = 'Close';
            $this->db->select('a.mstrid AS user_id, b.lgnusrCloseAc AS parent_user_lgnusrCloseAc, a.self_lgnusrCloseAc AS self_lgnusrCloseAc, a.parent_lgnusrCloseAc AS parent_lgnusrCloseAc');
            $this->db->from('createmaster a');
            $this->db->join('createmaster b','a.parentId = b.mstrid', 'LEFT');
            $this->db->where('a.mstrid', $_POST['userId']);
            $this->db->limit(1);

            $query = $this->db->get();
            $data = $query->result_array();

            $accValue = $_POST['accValue'];
            if($accValue == 1){

                $type = 'Open';
                $lgnusrCloseAc_by_user_id = 0;

                if($data[0]['parent_user_lgnusrCloseAc'] == 0){
                    return array('success' => false, 'message' => 'Failed: Parent User Closed');
                }

                if($data[0]['parent_lgnusrCloseAc'] != 0){
                    $accValue = 0;
                }
            }


            //start user working table save the data By Manish 31/12/2016
            $wortype="Close Account";
            $remarks="USER TYPE_ID : ".$_POST['userType'].">> USER_ID : ".$_POST['userName'].">> HelperID : ".$_POST['HelperID'];
            $userWrkingArray =     array(
                'woruser'        => $_POST['userId'],
                'wormode'        => 1,
                'wordate'        => date('Y-m-d H:i:s',now()),
                'wortype'        => $wortype,
                'worcode'        => $_POST['userId'],
                'worsysn'        => $_SERVER['REMOTE_ADDR'],
                'worrema'        => $remarks,
                'worcudt'        => date('Y-m-d H:i:s',now()),
            );
            $this->db->trans_begin();

            $dataArray = array('lgnusrCloseAc' => $accValue, 'HelperID' => $_POST['HelperID'], 'self_lgnusrCloseAc' => $lgnusrCloseAc_by_user_id);
            $this->db->where('mstrid',$_POST['userId']);


            $q1=$this->db->update('createmaster', $dataArray);
            $condition=$this->db->insert('userworkin', $userWrkingArray);
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return array('success' => false, 'message' => "User $type Failed");
            }
            else{
                $this->db->trans_commit();
                return array('success' => true, 'message' => "User $type Successfully");
            }
        }else{
            return array('success' => false, 'message' => "First Of All Make Sure To Clear The User Account And Than Close The Account");
        }



    }
    function closeAccountUsers($users=array()){

        $accValue = $_POST['accValue'];
        $HelperID = 0;
        $lgnusrCloseAc_by_user_id = $this->session->userdata('user_id');

        foreach($users as $user){

            $userType = $user['usetype'];
            $userName = $user['mstruserid'];
            $userId = $user['mstrid'];
            $success = true;

            if($accValue == 1){

                $lgnusrCloseAc_by_user_id = 0;

                $this->db->select('a.mstrid AS user_id, b.lgnusrCloseAc AS parent_user_lgnusrCloseAc, a.self_lgnusrCloseAc AS self_lgnusrCloseAc, a.parent_lgnusrCloseAc AS parent_lgnusrCloseAc');
                $this->db->from('createmaster a');
                $this->db->join('createmaster b','a.parentId = b.mstrid', 'LEFT');
                $this->db->where('a.mstrid', $userId);
                $this->db->limit(1);

                $query = $this->db->get();
                $data = $query->result_array();

                if($data[0]['self_lgnusrCloseAc'] != 0){
                    $accValue = 0;
                }
                if($data[0]['parent_lgnusrCloseAc'] == 0){
                    $success = false;
                }
            }

            if($success){
                $wortype="Close Account";
                $remarks="USER TYPE_ID : ".$userType.">> USER_ID : ".$userName.">> HelperID : ".$HelperID;
                $userWrkingArray =     array('woruser'    => $userId,'wormode'=> 1,'wordate'=> date('Y-m-d H:i:s',now()),'wortype'=> $wortype,'worcode'=> $userId,'worsysn'=> $_SERVER['REMOTE_ADDR'],'worrema'=> $remarks,'worcudt'=> date('Y-m-d H:i:s',now()));
                $this->db->trans_begin();

                $dataArray = array('lgnusrCloseAc' => $accValue, 'HelperID' => $HelperID, 'parent_lgnusrCloseAc' => $lgnusrCloseAc_by_user_id);
                $this->db->where('mstrid',$userId);
                $q1=$this->db->update('createmaster', $dataArray);
                $condition=$this->db->insert('userworkin', $userWrkingArray);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }
                else{
                    $this->db->trans_commit();
                }
            }
        }
    }

    function updateBetAllowedOnManualMatch($market_id, $status){
        $updateData = array(
            'isBetAllowedOnManualMatchOdds' => $status
        );

        $this->db->where('Id', $market_id);
        $res = $this->db->update('market', $updateData);
        return $res;
    }

    function updateIsRs($market_id, $status){
        $updateData = array(
            'isRs' => $status
        );

        $this->db->where('Id', $market_id);
        $res = $this->db->update('market', $updateData);
        return $res;
    }
    function updateMatchOddsStatus($market_id, $status){

        $updateData = array(
            'isManualMatchOdds' 	=> $status
        );

        $this->db->where('id', $market_id);
        $res = $this->db->update('market', $updateData);

        try {
            $_POST['market_id'] = $market_id;
            $this->saveManualMatchOdds();
        } catch (Exception $e) {
        }
        return $res;
    }

    function getMatchOddsStatus($market_id){
        $this->db->select('isManualMatchOdds');
        $this->db->from('market');
        $this->db->where('id',$market_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $res = $query->row_array();
        return !empty($res) ? $res['isManualMatchOdds'] : 0 ;
    }

    function updateChildPartnership($userId,$dataArray){

    	$this->db->select('usetype');
		$this->db->from('createmaster');
		$this->db->where('mstrid',$userId);		
		$query = $this->db->get();
		$row = $query->row_array();	

		if($row['usetype']==2){
		//	$dataArray = array( 'Admin' => $admin,'Master'=>$master,'Dealer'=>$dealer,'HelperID'=>$HelperID);
	        $this->db->where('ParentID',$userId);
	        $query=$this->db->update('tblpartner', $dataArray);
		}
		return false;

    } 

}
