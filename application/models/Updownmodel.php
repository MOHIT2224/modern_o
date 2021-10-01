<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Updownmodel extends CI_Model{		
		function __construct() {
		        parent::__construct();
		        $_POST = json_decode(file_get_contents('php://input'), true);
	    }
		function getFancyData($matchId,$fancyId){
			/*$this->db->select("TypeID,ID,SessInptYes,SessInptNo,active,MFancyID as FncyId,MaxStake,NoValume,YesValume,pointDiff,rateDiff,DisplayMsg,RateChange");*/
			$this->db->select("*");
			$this->db->from('matchfancy');
			$this->db->where('MatchID',$matchId);
			$this->db->where('ID',$fancyId);
			$query = $this->db->get();
			return $query->result_array();	
		}
		function GetBetData($matchId,$fancyId,$userId,$usertype){
			$query =$this->db->query("call SP_GetFancy($userId,$usertype,$fancyId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
		}
		function scorePosition($userId,$fancyId,$typeId){			
			$query =$this->db->query("call sp_GetScorePosition($userId,$fancyId,$typeId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();					
			return $res;
		}
		//start here
		function update_updownFancy(){
			$row = $this->db->query('SELECT MAX(MFancyID) AS `maxid` FROM `matchfancy`')->row();						
			$maxid = $row->maxid+1; 
		
			$dataArray = array( 'active' => $_POST['fStatus'],'upDwnLay'=>$_POST['downLay'],'upDwnBack'=>$_POST['UpBack'],'MaxStake'=>$_POST['MaxStake'],'pointDiff'=>$_POST['pointDiff'],'rateDiff'=>$_POST['rateDiff'],'MFancyID'=>$maxid);
			$this->db->where('ID',$_POST['FancyId']);
			$this->db->where('result',NULL);
			$query=$this->db->update('matchfancy', $dataArray);
			return $query;
		}
		function getFancyByEdit($id,$type){
			$this->db->select('*');
			$this->db->from('matchfancy');
			$this->db->where('TypeID',$type);
			$this->db->where('ID',$id);
			$query1 = $this->db->get();					
			return $query1->result_array();	
		}
	}