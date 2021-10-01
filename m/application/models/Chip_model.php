<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Check UserName and Password Of Login Page
*/
class Chip_model extends CI_Model
{
	
	function __construct()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	function SaveChipAc(){
	//print_r($_POST);die(); 
	  
			 		$this->db->trans_begin();
					
					//$GetpId=$this->Get_ParantId($this->input->post('UserID'));
					//$ParantId=$GetpId[0]->parentId;
					$chipsUser="-".$this->input->post('ChpsVal');
					$narration="Chip Detected By Match First Bet Of ".$_POST['MatchName']." By ".$_POST['UserName'];
					$narrationAdmin="Received from  ".$_POST['MatchName']." First Bet of ".$_POST['UserName'];
					$insertUserData = array(
						            'MstDate' 		=>date('Y-m-d H:i:s',now()),
						            'UserID' 		=> $this->input->post('UserId'),
						            'RefID' 		=> 'N_a',
						            'LoginID' 		=> 1,
						            'CrDr' 			=> 2,
						            'Chips' 		=> $chipsUser,
						            'txnId' 		=> 1234,
						            'IsFree' 		=> 1,
						            'Narration'		=> $narration
		        	);
		        	$insertAdminData = array(
						            'MstDate' 		=>date('Y-m-d H:i:s',now()),
						            'UserID' 		=> 1,
						            'RefID' 		=> 'N_a',
						            'LoginID' 		=> 1,
						            'CrDr' 			=> 1,
						            'Chips' 		=> $this->input->post('ChpsVal'),
						            'txnId' 		=> 345,
						            'IsFree' 		=> 1,
						            'Narration'		=> $narrationAdmin
		        	);
		        	$UserData = array(
						            'cuDate' 		=>date('Y-m-d H:i:s',now()),
						            'userID' 		=> $this->input->post('UserId'),
						            'matchID' 		=> $this->input->post('MatchID'),
						            'amount' 		=> $this->input->post('ChpsVal'),
						            
		        	);
		        	
		        	if ($this->input->post('userType')==3) {
		        		$query=$this->db->insert('check_detectamount', $UserData);
		        		$query=$this->db->insert('tblchips', $insertUserData);
		        		$query=$this->db->insert('tblchips', $insertAdminData);	
		        		//End of useworking table
		        		$this->db->trans_commit();
		        		return true;
		        	}else{
		        		$query=$this->db->insert('check_detectamount', $UserData);
		        		$query=$this->db->insert('tblchips', $insertAdminData);	
						$query=$this->db->insert('tblchips', $insertUserData);	
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
	function SaveChip(){ 
	  $ChipVal =0;
	  	if ($this->input->post('IsFree')==1) {

	  		$dw_chips = $this->input->post('Chips');

			if ( $this->input->post('CrDr') == 2) {
				$ChipVal = "-" . $this->input->post('Chips');
				$narration = "Withdrawal ".$this->input->post('Chips').' Coins by '.$this->input->post('ParantName');

			} else{
				$ChipVal = $this->input->post('Chips');
				$narration = "Deposit ".$this->input->post('Chips').' Coins by '.$this->input->post('ParantName');
			}


	  	}else{

	  		if ( $this->input->post('CrDr') == 2) {
				$ChipVal = "-" . $this->input->post('Chips');
				$narration="Withdrawal To ".$this->input->post('UserName');

			} else{
				$ChipVal = $this->input->post('Chips');
				$narration="Deposit To ".$this->input->post('UserName');
			}

	  	}
	

				 	$insertData = array(
			            'MstDate' 		=>date('Y-m-d H:i:s',now()),
			            'UserID' 		=> $this->input->post('UserID'),
			            'RefID' 		=> $this->input->post('RefID'),
			            'LoginID' 		=> $this->input->post('LoginId'),
			            'CrDr' 			=> $this->input->post('CrDr'),
			            'Chips' 		=> $ChipVal,
			            'txnId' 		=>0,
			            'IsFree' 		=> $this->input->post('IsFree'),
			            'Narration'		=>$narration,
						'HelperID' 		=> $this->input->post('HelperID')
			        );
	        	 	if ($this->input->post('IsFree')==1) {

			        	if ($this->input->post('CrDr')==1) {
			        		$cr_dr=2;
			        		$ChipVal = "-" . $this->input->post('Chips');
			        		$narration="Issue to ".$this->input->post('UserName');
			        	}else{
			        		$cr_dr=1;
			        		$ChipVal = $this->input->post('Chips');
			        		$narration="Received From  ".$this->input->post('ParantName');
			        	}

			        }else{

			        	if ($this->input->post('CrDr')==1) {
			        		$cr_dr=2;
			        		$ChipVal = "-" . $this->input->post('Chips');
			        		$narration="Withdrawal To Parant ".$this->input->post('ParantName')."and  Deposit to ".$this->input->post('UserName');
			        	}else{
			        		$cr_dr=1;
			        		$ChipVal = $this->input->post('Chips');
			        		$narration="Deposit To Parant ".$this->input->post('ParantName')."and Withdrawal to ".$this->input->post('UserName');
			        	}

			        }
			 		$this->db->trans_begin();
					$this->db->insert('tblchips', $insertData);
					$last_id=$this->db->insert_id();
					$GetpId=$this->Get_ParantId($this->input->post('UserID'));
					$ParantId=$GetpId[0]->parentId;
					$insert_txnData = array(
						            'MstDate' 		=>date('Y-m-d H:i:s',now()),
						            'UserID' 		=> $ParantId,
						            'RefID' 		=> $this->input->post('RefID'),
						            'LoginID' 		=> $this->input->post('LoginId'),
						            'CrDr' 			=> $cr_dr,
						            'Chips' 		=> $ChipVal,
						            'txnId' 		=> $last_id,
						            'IsFree' 		=> $this->input->post('IsFree'),
						            'Narration'		=> $narration
		        	);
		        	//start user working table save the data By Manish 31/12/2016
			        	$wortype="Free Chip-In-Out";
			        	$remarks="Free Chip  : ".$_POST['userType_id'].">>USER_ID : ".$_POST['UserName'].">>"."PrntId--".$_POST['ParantName']."LgnId:".$_POST['LoginId'];
			        	$userWrkingArray = 	array(
									            'woruser' 			=> $_POST['UserID'],
									            'wormode' 			=> 0,
									            'wordate' 			=> date('Y-m-d H:i:s',now()),
									            'wortype' 			=> $wortype,
									            'worcode' 			=> $last_id,
									            'worsysn' 			=> $_SERVER['REMOTE_ADDR'],
									            'worrema'			=> $remarks,
									            'worcudt'			=> date('Y-m-d H:i:s',now()),
									        );

					$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
					$userId = $this->input->post('UserID');
			        //End of useworking table
		        	if ($this->input->post('userType')==0) {
		        		//start user working table save the data By Manish 31/12/2016
		        		$condition=$this->db->insert('userworkin', $userWrkingArray);
		        		$Modelcreatemaster->updateUserBalLiablity($userId);
		        		//End of useworking table
		        		$this->db->trans_commit();
		        		return true;
		        	}else{
		        		$condition=$this->db->insert('userworkin', $userWrkingArray);
						$query=$this->db->insert('tblchips', $insert_txnData);	
						if ($this->db->trans_status() === FALSE){
						    $this->db->trans_rollback();
						    return false;
						}
						else{
							$Modelcreatemaster->updateUserBalLiablity($userId);
							$Modelcreatemaster->updateUserBalLiablity($ParantId);
						    $this->db->trans_commit();
						    return true;
						}
		        	}
		        			
					
	 

	}
	function Save_main_chips(){
		if ($this->input->post('CrDr')==1) {
						$cr_dr=2;
						$ChipcrVal = $this->input->post('Chips');
						$ChipdrVal = 0;
						$setlmentChip = $this->input->post('Chips');
						$narration="Setllemet Amount Deposit to  ".$this->input->post('UserName');
					}else{
						$cr_dr=1;
						$ChipdrVal = $this->input->post('Chips');
						$ChipcrVal = 0;
						$setlmentChip = "-".$this->input->post('Chips');
						$narration="Setllemet Amount Withdrawal to  ".$this->input->post('UserName');
					}
					$insertData = array( 
					'MstDate' 		=>date('Y-m-d H:i:s',now()),
					'UserID' 		=> $this->input->post('UserID'),
					'RefID' 		=> $this->input->post('RefID'),
					'LoginID' 		=> $this->input->post('LoginId'),
					'CrDr' 			=> $this->input->post('CrDr'),
					'ChipsCr' 		=> $ChipcrVal,
					'ChipsDr' 		=> $ChipdrVal,
					'ResultID'		=>0,
					'BetID'			=>0,
					'Narration' 	=>$narration,
					'MstType' 		=>0,
					'MarketId' 		=>0,
					'MatchId' 		=>0,
					'ChildID' 		=>0,
					'pMaster' 		=>0,
					'pDealer' 		=>0,
					'pAdmin' 		=>0,
					'parantid' 		=>0,
					'LevelV' 		=>0,
					'oppAcID' 		=>0,
					'oppAcNm' 		=>0,
					'FancyId' 		=>0,
					'CashID' 		=>0,
					'cashentryid' 	=>0
				);
	
		$this->db->insert('tblchipdet', $insertData);
		
		$insertData = array( 
					'parentId' 		=> $this->input->post('LoginId'),
					'childId' 		=> $this->input->post('UserID'),
					'onDate' 		=>date('Y-m-d H:i:s',now()), 
					'remarkV' 		=> $this->input->post('RefID'),  
					'typeId' 		=> 50,
					'amountV' 		=> $setlmentChip,
					'remarkV'		=>$narration,
					'CrDr' 			=> $this->input->post('CrDr'),
				);
		$this->db->insert('tblcashentry', $insertData);
	}
	
	function Get_ParantId($userId){
			$this->db->select("parentId");
			$this->db->from('createmaster');
			$this->db->where('mstrid',$userId);
			$query = $this->db->get();
			return $query->result();
	}
	function GetUserDetectionAmt($userId){
			$this->db->select("value");
			$this->db->from('detect_amount');
			$this->db->where('user_id',$userId);
			$query = $this->db->get();
			$rowcount = $query->num_rows();
			if($rowcount==0){
				return $query->result();
			}else{
				return $query->result();
			}
			
	}
	function updateUserDetection($Amount,$id,$Type){
			$this->db->select("value");
			$this->db->from('detect_amount');
			$this->db->where('user_id',$id);
			$query = $this->db->get();
			$rowcount = $query->num_rows();
			if($rowcount==0){
				$DataArray = array('user_id'=> $id,'value'=> $Amount );
				$condition=$this->db->insert('detect_amount', $DataArray);
			}else{
				$DataArray = array('value'=> $Amount );
				$this->db->where('user_id',$id);	
				$condition1=$this->db->update('detect_amount', $DataArray);
				return $condition1;
			}
			
	}
	function getChipData($chipVal,$userId)
	{
			if ($chipVal==0) {
				$query =$this->db->select('Sum(Chips) as ChipInOut');
			}else{
				$query =$this->db->select('Sum(Chips) as FreeChip');
			}
			
			$query = $this->db->where('IsFree',$chipVal);
			$query = $this->db->where('UserID',	$userId);
			$query = $this->db->get('tblchips');
			$result = $query->result();
			return $result;
			
		
	}
	function getChipDataById($chipVal,$id)
	{
			if ($chipVal==0) {
				$query =$this->db->select('ifnull(Sum(Chips),0) as ChipInOut');
			}else{
				$query =$this->db->select('ifnull(Sum(Chips),0) as FreeChip');
			}
			
			$query = $this->db->where('IsFree',$chipVal);
			$query = $this->db->where('UserID',	$id);
			$query = $this->db->get('tblchips');
			$result = $query->result();
			return $result;
			
	}
	function getLiability($id){
			$query =$this->db->query("call getLiability($id)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}
	function checkDeduction($userId,$matchId){
			$this->db->select("count(Id) numb");
			$this->db->from('check_detectamount');
			$this->db->where('userID',$userId);
			$this->db->where('matchID',$matchId);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result();
	}
	function getParentById($id)//sourabh 161219
	{
			$query =$this->db->query("call sp_getParentData($id)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}
	function sumOfBetFancy($fancyId){
			//echo "call getRateChange($fancyId)";
			$query =$this->db->query("call getRateChange($fancyId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
			/*$this->db->select('Sum(bet_value) as TotalBet');			
			$this->db->where('fancyId',$fancyId);
			$query = $this->db->get('bet_entry');
			$result = $query->result();
			return $result;	*/
	}
	function GetAllFancyBet($FancyId){

			$query =$this->db->query("call SP_sessionFncyUsrLst($FancyId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}
	function UserChipSetting($userId){

			$this->db->select("*");
			$this->db->from('tblchipsetting');
			$this->db->where('UserID',$userId);
			$query = $this->db->get();
			return $query->result();
	}
	function updateUserChipSetting(){
		
   		$parameter=$_POST['UserID'].",'".$_POST['Name1']."',".$_POST['Value1'].",'".$_POST['Name2']."',".$_POST['Value2'].",'".$_POST['Name3']."',".$_POST['Value3'].",'".$_POST['Name4']."',".$_POST['Value4'].",'".$_POST['Name5']."',".$_POST['Value5'].",'".$_POST['Name6']."',".$_POST['Value6'];
		$query =$this->db->query("call sp_InsUpdChipSetting($parameter)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}
	function getChipDelList(){
		$query =$this->db->query("call sp_getCashEntry()");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
			
	}
	function deleteCashEntry($id){
		$query =$this->db->query("call sp_delCashEntry($id)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
			
	}
	function getFancyLength(){
		$this->db->select('max(ID) as ID');
		$this->db->from('matchfancy');
		$this->db->where('active <>', 2);
		$query = $this->db->get();
		/*$this->db->select_max('ID');
		//$this->db->where('active <>', 2);
		$query = $this->db->get('matchfancy');*/
		//echo $this->db->queries[0];die();
		//print_r()
		return $query->result();
	}
	function GetFancyIcon($fancyId){
		$this->db->select("mf.HeadName,mf.TypeID,mf.MatchID,mf.ID,M.matchName,M.SportID");
		$this->db->from('matchfancy mf');
		$this->db->join('matchmst M','M.MstCode=mf.MatchID');
		$this->db->where('mf.ID',$fancyId);
		$query = $this->db->get();
		return $query->result();
	}
}