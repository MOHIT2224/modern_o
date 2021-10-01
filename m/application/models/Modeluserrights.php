<?php
	error_reporting("ERROR");
	defined('BASEPATH') OR exit('No direct script access allowed');
		class ModelUserRights extends CI_Model
		{
			function __construct()
			{
				$_POST = json_decode(file_get_contents('php://input'), true);
			}
			function Save_userRights(){
				//print_r($_POST['view']);
				//print_r($_POST['userId']);
				foreach ($_POST['view'] as $name => $value) {
					//echo $value;
					foreach ($_POST['userId'] as $userName => $userId) {
						//echo $userId;
						$arrayValue = array('mencode'=> $value,'menuser'=> $userId["id"],'menview'=> 1);
						//print_r($arrayValue);
						$query=$this->db->insert('usermenust', $arrayValue);
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
				}
			}
			function SavePermition(){//170210
				$this->db->trans_begin();
				$this->db->where('HelperID', $_POST['adminId']);
				$this->db->delete('tblHelper');

				foreach ($_POST['userId'] as $userName => $userId) {
					$arrayValue = array('HelperID'=> $_POST['adminId'],'MasterID'=> $userId["id"]);
					$query=$this->db->insert('tblHelper', $arrayValue);
					
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

				if($_POST['addMatch']==null)$_POST['addMatch']=0;
				if($_POST['chkAddUser']==null)$_POST['chkAddUser']=0;
				if($_POST['chkUpdateUser']==null)$_POST['chkUpdateUser']=0;
				if($_POST['chkChangePassword']==null)$_POST['chkChangePassword']=0;
				if($_POST['chkChipDeposite']==null)$_POST['chkChipDeposite']=0;
				if($_POST['chkChipWithdraw']==null)$_POST['chkChipWithdraw']=0;
				if($_POST['chkUserLock']==null)$_POST['chkUserLock']=0;
				if($_POST['chkBettingLock']==null)$_POST['chkBettingLock']=0;
				if($_POST['chkCloseAc']==null)$_POST['chkCloseAc']=0;
				if($_POST['createFancy']==null)$_POST['createFancy']=0;
				if($_POST['chkEditFancy']==null)$_POST['chkEditFancy']=0;
				if($_POST['setResult']==null)$_POST['setResult']=0;
				if($_POST['chkChipHistory']==null)$_POST['chkChipHistory']=0;
				if($_POST['chkChipSummary']==null)$_POST['chkChipSummary']=0;

				$arrayValue = array('HelperID'=> $_POST['adminId'],'addMatch'=> $_POST['addMatch'],'AddUser'=> $_POST['chkAddUser'],'UpdateUser'=> $_POST['chkUpdateUser'],'ChangePwd'=> $_POST['chkChangePassword'],'ChipCr'=> $_POST['chkChipDeposite'],'ChipDr'=> $_POST['chkChipWithdraw'],'UserLock'=> $_POST['chkUserLock'],'BettingLock'=> $_POST['chkBettingLock'],'Close_Ac'=> $_POST['chkCloseAc'],'AddFancy'=> $_POST['createFancy'],'EditFancy'=> $_POST['chkEditFancy'],'Result'=> $_POST['setResult'],'ChipHistory'=> $_POST['chkChipHistory'],'ChipSummary'=> $_POST['chkChipSummary']);

				if($this->checkHelperRights($_POST['adminId'])==0)
				{
					$query=$this->db->insert('tblHelperRight', $arrayValue);
				}
				else
				{
					$this->db->where('HelperID',$_POST['adminId']);
					$query=$this->db->update('tblHelperRight', $arrayValue);
				}
				if ($this->db->trans_status() === FALSE){
					    $this->db->trans_rollback();
					     return 0;
				}
				else{
					    $this->db->trans_commit();
					    return 1;
				}
			}
			function checkHelperRights($HelperId)
			{
				$this->db->select('HelperID');
				$this->db->from('tblHelperRight');
				$this->db->where('HelperID', $HelperId);
				$query = $this->db->get();
				$num=$query->num_rows();
				return $num;
			}
		}