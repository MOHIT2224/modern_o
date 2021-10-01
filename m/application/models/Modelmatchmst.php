<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelmatchmst extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	function result($matchId=NULL){

		$this->db->select('active');
		$this->db->from('matchmst');
		$this->db->where('MstCode',$matchId);		
		$query = $this->db->get();
		$result = $query->row_array();	
		$matchStatus = $result['active'];
	//	print_r($result);die;
		return $matchStatus;

	}

	/*function checkMatchPermission($userId=NULL,$matchId=NULL){

			$result = false;

			$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
			$userData = $Modelcreatemaster->userParent($userId);

			$dealerId = $userData['dealer_id'];
			$masterId = $userData['master_id'];
			$usetype = $userData['usetype'];

		//	echo $userId; 
		//	echo $matchId;
		//	die;
			
			if($usetype==3){
				$this->db->select("mtchMst.MstCode");
				$this->db->from('matchmst mtchMst');
				$this->db->join('user_deactive_match ddm', "mtchMst.MstCode = ddm.match_id AND ddm.user_id = $dealerId", 'LEFT');
				$this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
				$this->db->where('(ddm.id IS NOT NULL OR ddm.id IS NOT NULL)');
				$this->db->where('active', 1);
				$this->db->order_by("matchName asc");
				$query = $this->db->get();
				$res = $query->result_array();
			//	$query->next_result();
				$query->free_result();
				$count = count($res);
				//echo '<pre>';
				//print_r($res);
				//echo "userId $userId matchId $matchId count ".$count;
			//	$count = $query1->num_rows();
				if($count){
					$result = true;
				}
			}elseif($usetype==2){
				$this->db->select("mtchMst.MstCode");
				$this->db->from('matchmst mtchMst');
				$this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
				$this->db->where('mdm.id IS NOT NULL');
				$this->db->where('active', 1);
				$this->db->order_by("matchName asc");
				$query = $this->db->get();
				$query->free_result();
				$count = $query->num_rows();
				if($count){
					$result = true;
				}
			}
			
			return $result;
	}*/

    function checkMatchPermission($userId=NULL,$matchId=NULL){

        $responce  = false;


        $Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
        $userData = $Modelcreatemaster->checkParentIds($userId);

        $this->db->select('match_id');

        $this->db->where_in('user_id',$userData);

        $this->db->from('user_deactive_match');
        $user_deactive_match_result = $this->db->get();

        $user_deactive_match_data = $user_deactive_match_result->result_array();

        $result= [];
        if(count($user_deactive_match_data)){
            foreach ($user_deactive_match_data as $user_deactive_match_rec ){
                $result[]=$user_deactive_match_rec['match_id'];
            }
        }
//return $result;
        if(in_array($matchId ,$result )){
            $responce = true;
        }
        return $responce;
    }

    function getAcriveMatchIds(){

        $this->db->select('GROUP_CONCAT(MstCode) as matchIds');
        $this->db->from('matchmst');
        $this->db->where('active','1');
        $this->db->group_by('active');
        $query = $this->db->get();

        $result = $query->row_array();
        //print_r($result);die;
        try {
           return explode(',', $result['matchIds']);
        } catch (Exception $e) {
            return [];
        }

    }

    function getActiveSeriesIds(){

        $this->db->select('GROUP_CONCAT(seriesId) as seriesId');
        $this->db->from('seriesmst');
        $this->db->where('active',1);
        $this->db->group_by('active');
        $query = $this->db->get();

        $result = $query->row_array();
        //print_r($result);die;
        try {
            return explode(',', $result['seriesId']);
        } catch (Exception $e) {
            return [];
        }

    }

	function findActiveMatches($matchId=NULL){
	//	$matchId = 28888445;
		$this->db->select('MstCode,SportID');
		$this->db->from('matchmst');		
		if(!empty($matchId)){
			$this->db->where('MstCode',$matchId);		
		}	
		$this->db->where('active',1);
		$query = $this->db->get();
		$result = $query->result_array();	
		return $result;
	}

	function findByMatchId($matchId=NULL){
		$this->db->select('MstCode,SportID');
		$this->db->from('matchmst');
		$this->db->where('MstCode',$matchId);		
		$query = $this->db->get();
		$result = $query->row_array();	
		return $result;
	}

	function updateDefaultRunner($matchId=NULL){

		$tblSelectionModel = $this->model_load_model('Modeltblselection');	

		$matchArr = $tblSelectionModel->findByMatchId($matchId);

		$defaultRunners = array();
		foreach($matchArr as $mArr){
			$temp = array();
			$temp['id'] = $mArr['selectionId'];
			$temp['name'] = $mArr['selectionName'];
			$temp['hdp'] = 0;
			$temp['sort'] = 2;
			$temp['back'] = array('0'=>array('price'=>'--','size'=>'--'),'1'=>array('price'=>'--','size'=>'--'),'2'=>array('price'=>'--','size'=>'--'));
			$temp['lay'] = array('0'=>array('price'=>'--','size'=>'--'),'1'=>array('price'=>'--','size'=>'--'),'2'=>array('price'=>'--','size'=>'--'));
			$defaultRunners[] = $temp;
		}

	//	print_r($defaultRunners);die;

		if(!empty($defaultRunners)){
			$defaultRunnersJson = json_encode($defaultRunners);
			$this->update($matchId,array('runner_json'=>$defaultRunnersJson));
		}

	}

	function update($MstCode=NULL,$updateArr=NULL){
		$this->db->where('MstCode',$MstCode);
        $this->db->update('matchmst', $updateArr);
        return true; 
	} 

	function getUserMatchAutoComplete($params=NULL){
		$userId = $params['user_id'];
		$search = $params['search'];

		$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
		$userData = $Modelcreatemaster->userParent($userId);

		$dealerId = $userData['dealer_id'];
		$masterId = $userData['master_id'];
		$usetype = $userData['usetype'];

		$selectQuery = "mf.matchName,mf.MstCode as matchid,mf.MstDate,sm.id as SportId";
		
		$this->db->select($selectQuery);
		$this->db->from('matchmst mf');
		$this->db->join('market ma','ma.matchId=mf.MstCode', 'INNER');
		$this->db->join('sportmst sm', 'mf.SportID=sm.id', 'INNER');
		$this->db->join('seriesmst series', 'series.seriesId=mf.seriesId', 'INNER');
		$this->db->join('market mrkt', 'mrkt.matchId = mf.MstCode', 'INNER');
		$this->db->join('favourite_market fav_mrkt', "mrkt.Id = fav_mrkt.market_id AND fav_mrkt.user_id = $userId", 'LEFT');
		if($usetype==3){
			$this->db->join('user_deactive_match ddm', "mf.MstCode = ddm.match_id AND ddm.user_id = $dealerId", 'LEFT');
			$this->db->join('user_deactive_match mdm', "mf.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
			$this->db->where('ddm.id IS NULL');
			$this->db->where('mdm.id IS NULL');
		}elseif($usetype==2){
			$this->db->join('user_deactive_match mdm', "mf.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
			$this->db->where('mdm.id IS NULL');
		}
		
		$this->db->where('mf.active', 1);
		$this->db->where('ma.Name', 'Match Odds');
		if(!empty($search)){
			$this->db->like('mf.matchName', $search);
		}
		$this->db->order_by("mf.matchName asc,mf.MstDate asc");
		$this->db->group_by("mrkt.matchId");
		$query = $this->db->get();
		return $query->result_array();

	}



}