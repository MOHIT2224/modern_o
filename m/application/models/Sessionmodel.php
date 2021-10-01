<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Sessionmodel extends CI_Model{		
		
		function getFancyData($matchId,$fancyId){
			$this->db->select("TypeID,ID,SessInptYes,SessInptNo,active,MFancyID as FncyId,MaxStake,NoValume,YesValume,pointDiff,rateDiff,DisplayMsg,RateChange");
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
			//echo "call sp_GetScorePosition($userId,$fancyId,$typeId)";	die();		
			$query =$this->db->query("call sp_GetScorePosition($userId,$fancyId,$typeId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);die();					
			return $res;
		}
		function GetSumOfBet($userId,$matchId,$FancyId,$fancyType,$yes,$no){
			//echo "call sp_getSumSessBetYesNo($userId,$matchId,$FancyId,$fancyType,$yes,$no)";
			$query =$this->db->query("call sp_getSumSessBetYesNo($userId,$matchId,$FancyId,$fancyType,$yes,$no)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();					
			return $res;
		}
		function DisableAllFancy($MatchID){
			$data=array('active'=>0);
			$this->db->where('MatchID',$MatchID);
			$this->db->where('active !=',2);
			$this->db->where('result',NULL);
			$this->db->update('matchfancy',$data);

		}
		function sessionBetList($matchId){
			/*SELECT be.bet_Id,cm.mstruserid as userId,be.oddsNumber as odds,be.oddValue as isBack,be.dateTime as MstDate,be.IP_ADDRESS as ip,be.DeviceDesc as descp FROM `bet_entry` be inner join createmaster cm on be.userId=cm.mstrid and be.matchId=28595293 and be.ResultID is null and TypeID=2*/
			$this->db->select("mf.HeadName fName,be.bet_Id,cm.mstruserid as userId,be.oddsNumber as odds,be.oddValue as isBack,be.dateTime as MstDate,be.IP_ADDRESS as ip,be.DeviceDesc as descp,bet_value");
			$this->db->from('bet_entry be');
			$this->db->join('createmaster cm','be.userId=cm.mstrid');
			$this->db->join('matchfancy mf','be.fancyId=mf.ID');
			$this->db->where('be.matchId',$matchId);
			$this->db->where('be.ResultID', NULL);
			$this->db->where('be.TypeID', 2);
			$query = $this->db->get();
			return $query->result_array();	
		}
		

	}