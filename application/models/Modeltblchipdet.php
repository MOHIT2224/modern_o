<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblchipdet extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}
	
	function updateByUserId($userId=0,$updateArr=NULL){
		$this->db->where('UserID',$userId);
        $this->db->update('tblchipdet', $updateArr);
        //echo $this->db->queries[0];die();		
        return true; 
	}

	/**
	 * [updateByMatchId update table chipdet by matchid
	 * @param  integer $matchId   [match id]
	 * @param  [type]  $updateArr [update array]
	 * @return [type]             [boolean]
	 */
	function updateByMatchId($matchId=0,$updateArr=NULL){
		$this->db->where('MatchId',$matchId);
        $this->db->update('tblchipdet', $updateArr);
        //echo $this->db->queries[0];die();		
        return true; 
	}


	/**
	 * [resetUserSettlement reset user settlement
	 * @param  integer $userId   [user id]
	 * @return [type]             [boolean]
	 */
	function resetUserSettlement($userId=0){

		$sql = "SELECT GROUP_CONCAT(a.MstCode) ids
					FROM tblchipdet a
					INNER JOIN createmaster b ON b.mstrid = a.UserID
					WHERE a.UserID = $userId AND msttype < 50 AND msttype NOT IN (6,9) AND a.reset_settlement = 'N'
					GROUP BY b.mstrid, usetype, mstruserid, b.mstrname
					HAVING SUM(a.ChipsCr-a.ChipsDr) > 0";

		$query = $this->db->query($sql);
		$data = $query->row_array();

		if(!empty($data['ids'])){
				$ids = $data['ids'];
				$idArr = explode(',', $ids);
				$updateArr = array('tblchipdet.reset_settlement'=>'Y');
				$this->db->where_in('tblchipdet.MstCode',$idArr);
        		$this->db->update('tblchipdet', $updateArr);

		}
		
        return true; 	
	}

}