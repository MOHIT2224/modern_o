<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Modellstmaster extends CI_Model{
		function __construct(){
				
		}
		function getLstMaster(){
			
			$this->load->model('Chip_model');
			$this->db->select("lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype,balance");
			$this->db->from('createmaster');
			$this->db->where('lgnusrCloseAc != 0');
			$this->db->order_by("usecrdt","desc");
			$query = $this->db->get();
			$response = $query->result_array();

		/*	$response = array();
			foreach($userList as $ulist){
				$temp = $ulist;
				$userId = $ulist['usecode'];
				$res = $this->Chip_model->getLiability($userId);
				$temp['balance'] = (!empty($res[0]['Balance']) ? $res[0]['Balance'] : 0);
				$response[] = $temp;
			} */
			return $response;
		}

		function updateLstMaster($id,$status){
			if ($status==0) {
				$st=1;
			}else{
				$st=0;
			}
			$dataArray = array('active' => $st);
    		$this->db->where('mstrid',$id);
            $this->db->update('createmaster', $dataArray);
            return $dataArray;  
		}
		function GetUserTree($id,$type,$helperid,$helperType){

		  	$query =$this->db->query("call sp_MemberTree($id,$helperid)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
		}
		function getUserDataById($userId){
			$this->db->select('*');
			$this->db->from('createmaster');
			$this->db->where('parentId', $userId);
			$query = $this->db->get();
			return $query->result_array();
		}
        function getUserInfo($userId){

            $this->db->select('cm.mstrlock,cm.lgnusrlckbtng,cm.lgnusrCloseAc,cm.lgnUserMaxStake as stakeLimit,active,cm.usetype');
            $this->db->from('createmaster cm');
            
            $this->db->where('mstrid', $userId);
            $query = $this->db->get();
            return $query->result_array();
        }
		function getMatch(){
			
			$this->db->select("se.Name,m.MstCode,m.MstDate,m.FromDt as FromTime,m.ToDt as ToTime,tm1.name as Team1,tm2.name as Team2,typeMst.Name as MatchType,m.Target as over,CASE WHEN m.active=1 
							then 'Active' ELSE 'Inactive' end as status ,m.active");
			$this->db->from('matchmst m');
			$this->db->join('seriesmst se', 'm.SeriesID=se.ID', 'INNER');
			$this->db->join('team tm1', 'm.Team1=tm1.id', 'INNER');
			$this->db->join('team tm2', 'm.Team2=tm2.id', 'INNER');
			$this->db->join('sporttypemst typeMst', 'typeMst.ID=m.TypeID', 'INNER');
			$query = $this->db->get();
			/*echo $this->db->queries[0];die();	*/	

			return $query->result_array();
		}
		function getActivatedMatch(){
			$this->db->select("se.Name,m.MstCode");
			$this->db->from('matchmst m');
			$this->db->join('seriesmst se', 'm.SeriesID=se.ID', 'INNER');
			$this->db->where('m.active',1);
			$query = $this->db->get();
			return $query->result_array();	
		}
		function FancyItem(){
			$this->db->select('*');
			$this->db->from('matchfancy');
			$this->db->order_by("TypeID","asc");
			$query = $this->db->get();
			return $query->result_array();
		}
        function getFandyIdByFancySupaerAdminId($super_admin_fancy_id){
            $this->db->select('*');
            $this->db->from('matchfancy');
            $this->db->where("super_admin_fancy_id",$super_admin_fancy_id);
            $query = $this->db->get();
            return $query->row_array();
        }
		function getFancyByMatchId($matchId,$fancyId){
			$this->db->select("m.MstCode,mf.HeadName,mf.TypeID,mf.date,mf.time,mf.ID,mf.Remarks,mf.SessInptYes,mf.SessInptNo,mf.fancyRange,mf.PlayerId,mf.active,mf.result,mf.upDwnBack,mf.upDwnLay,mf.MFancyID as FncyId,mf.MaxStake,mf.NoValume,mf.YesValume,mf.pointDiff,mf.rateDiff,mf.DisplayMsg,mf.RateChange,mf.upDwnLimit");
			$this->db->from('matchmst m');
			/*$this->db->join('seriesmst se', 'm.SeriesID=se.ID', 'INNER');*/
			$this->db->join('matchfancy mf', 'mf.MatchID=m.MstCode', 'INNER');
			$this->db->where('m.MstCode',$matchId);
			$this->db->where('mf.ID',$fancyId);
			$this->db->where('m.active<>',2);
			$this->db->where('mf.result',NULL);
			$query = $this->db->get();
			/*echo $this->db->queries[0];die();	*/	

			return $query->result_array();	
		}
		function getFancyDataM($matchId,$TypeID){
			$this->db->select("m.MstCode,mf.HeadName,mf.TypeID,mf.date,mf.time,mf.ID,mf.Remarks,mf.SessInptYes,mf.SessInptNo,mf.fancyRange,mf.PlayerId,mf.active,mf.result,mf.upDwnBack,mf.upDwnLay,mf.MFancyID as FncyId,mf.MaxStake,mf.NoValume,mf.YesValume,mf.pointDiff,mf.rateDiff,mf.DisplayMsg,mf.RateChange,mf.upDwnLimit");
			$this->db->from('matchmst m');
			/*$this->db->join('seriesmst se', 'm.SeriesID=se.ID', 'INNER');*/
			$this->db->join('matchfancy mf', 'mf.MatchID=m.MstCode', 'INNER');
			$this->db->where('m.MstCode',$matchId);
			$this->db->where('mf.TypeID',$TypeID);
			$this->db->where('m.active<>',2);
			$this->db->where('mf.result',NULL);
			$query = $this->db->get();
			/*echo $this->db->queries[0];die();	*/	

			return $query->result_array();	
		}
		function listFancy($matchId){
			
			$this->db->select("active,ID,HeadName,HeadName,Remarks,date,case when TypeID=1 then 'Odd/Even' else case when TypeID=2 then 'Session' else case when TypeID=3 then 'Khaddal' else case when TypeID=5 then 'UpDown' else case when TypeID=4 then 'LastDigit' end end end end end as TypeID");
			$this->db->from('matchfancy');
			$this->db->where('MatchID',$matchId);
			$this->db->where('active!=','2');
			$this->db->order_by('ID',"desc");

			$query = $this->db->get();
			//echo $this->db->queries[0];die();		

			return $query->result_array();
		}
		function save_session_bet($paramsData){
							
				if($_POST['TypeID']==2){
					$GetpId=$this->Get_ParantId($this->input->post('loginId'));
					$ParantId=$GetpId[0]['parentId'];
					$userId=$this->input->post('loginId');
					$loginId=$this->input->post('loginId');

					$fancyId = $this->input->post('FancyID');
					$Modelmatchfancy = $this->model_load_model('Modelmatchfancy');
  					$fancyData = $Modelmatchfancy->getFancyById($fancyId);

  				//	print_r($fancyData);


  					if($fancyData['is_indian_fancy']==0 || $fancyData['fancy_mode']=='M'){
  						$SessSizeYes = !empty($fancyData['YesValume']) ? $fancyData['YesValume']: 100;
						$SessSizeNo = !empty($fancyData['NoValume']) ? $fancyData['NoValume'] : 100;
  					}else{
  						$SessSizeYes = !empty($paramsData['back_size']) ? $paramsData['back_size']: 100;
						$SessSizeNo = !empty($paramsData['lay_size']) ? $paramsData['lay_size'] : 100;
  					}

					$parameter="".$this->input->post('betValue').",'".$this->input->post('OddValue')."',".$this->input->post('OddsNumber').",".$this->input->post('TypeID').",".$this->input->post('matchId').",".$this->input->post('FancyID').",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$this->input->post('FancyId').",'".$this->input->post('HeadName')."',".$this->input->post('SessInptYes').",".$this->input->post('SessInptNo').",".$this->input->post('sportId').",'".$_SERVER["REMOTE_ADDR"]."',".$this->input->post('pointDiff').",".$this->input->post('deviceInformation').","."null".",".$SessSizeYes.",".$SessSizeNo;
				//	echo "call sp_PlaceBet_Fancy($parameter)";die;
					$query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");

					$res = $query->result_array();
					$query->next_result();
					$query->free_result();

					$FancyID = $this->input->post('FancyID');
					$SessInptYes = $this->input->post('SessInptYes');
					$SessInptNo = $this->input->post('SessInptNo');

					$this->load->model('Modelmatchfancy');
					$this->Modelmatchfancy->update($FancyID,array('SessInptYes'=>$SessInptYes,'SessInptNo'=>$SessInptYes));

					return $res;
                }
 		}

 		function mbdip_save_session_bet($data){
							
				if($data['TypeID']==2){

					$data['sportId'] = 4;

					$GetpId=$this->Get_ParantId($data['loginId']);
					$ParantId=$GetpId[0]['parentId'];
					$userId= $data['loginId'];
					$loginId= $data['loginId'];

					$fancyId = $data['FancyID'];
					$Modelmatchfancy = $this->model_load_model('Modelmatchfancy');
  					$fancyData = $Modelmatchfancy->getFancyById($fancyId,$data['matchId']);

  					//print_r($data);die;
  					//print_r($fancyData);die;

  					if($fancyData['is_indian_fancy']==0 || $fancyData['fancy_mode']=='M'){
  						$SessSizeYes = !empty($fancyData['YesValume']) ? $fancyData['YesValume']: 100;
						$SessSizeNo = !empty($fancyData['NoValume']) ? $fancyData['NoValume'] : 100;
  					}else{
  						$SessSizeYes = !empty($data['back_size']) ? $data['back_size']: 100;
						$SessSizeNo = !empty($data['lay_size']) ? $data['lay_size'] : 100;
  					}

  				/*	if($data['OddValue']==1){
  						$data['betValue'] = ($data['betValue'] * ($SessSizeNo/100));
  					} */

					$parameter="".$data['betValue'].",'".$data['OddValue']."',".$data['OddsNumber'].",".$data['TypeID'].",".$data['matchId'].",".$data['FancyID'].",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$data['FancyId'].",'".$data['HeadName']."',".$SessSizeYes.",".$SessSizeNo.",".$data['sportId'].",'".$_SERVER["REMOTE_ADDR"]."',".$data['pointDiff'].",'".$data['deviceInformation']."',"."null".",".$SessSizeYes.",".$SessSizeNo;
                    //echo "call sp_PlaceBet_Fancy($parameter)";die;
					$query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");

					$res = $query->result_array();
					$query->next_result();
					$query->free_result();

					$FancyID = $data['FancyID']; 
					$SessInptYes = $data['SessInptYes'];
					$SessInptNo = $data['SessInptNo'];

					$this->load->model('Modelmatchfancy');
					$this->Modelmatchfancy->update($FancyID,array('SessInptYes'=>$SessInptYes,'SessInptNo'=>$SessInptNo));
					
					return $res;
                }
 		}
		function saveUserFancyEvenOdd(){
				if($this->input->post('type')==3){
					$GetpId=$this->Get_ParantId($this->input->post('loginId'));
					$ParantId=$GetpId[0]['parentId'];
					$userId=$this->input->post('loginId');
					$loginId=$this->input->post('loginId');
				}else{
					$ParantId=$this->input->post('ParantId');
					$userId=$this->input->post('userId');
					$loginId=$this->input->post('loginId');
				}
				if($_POST['TypeID']==1){

                    $parameter="".$this->input->post('betValue').",'".$this->input->post('OddValue')."',".$this->input->post('OddsNumber').",".$this->input->post('TypeID').",".$this->input->post('matchId').",".$this->input->post('FancyID').",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$this->input->post('FancyID').",'".$this->input->post('HeadName')."',0,0,".$this->input->post('sportId').",'".$_SERVER["REMOTE_ADDR"]."',0,'".$this->input->post('deviceInformation')."',"."null";
                    $query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");
                    $res = $query->result_array();
                    $query->next_result();
                    $query->free_result();
                    return $res;
				}
				if($_POST['TypeID']==2){

                   $parameter="".$this->input->post('betValue').",'".$this->input->post('OddValue')."',".$this->input->post('OddsNumber').",".$this->input->post('TypeID').",".$this->input->post('matchId').",".$this->input->post('FancyID').",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$this->input->post('FancyId').",'".$this->input->post('HeadName')."',".$this->input->post('SessInptYes').",".$this->input->post('SessInptNo').",".$this->input->post('sportId').",'".$_SERVER["REMOTE_ADDR"]."',".$this->input->post('pointDiff').",".$this->input->post('deviceInformation').","."null";
                   //echo "call sp_PlaceBet_Fancy($parameter)";
                    $query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");
                    $res = $query->result_array();
                    $query->next_result();
                    $query->free_result();
                    return $res;
                }
                if($_POST['TypeID']==4){
                   // $parameter="BetValue=".$this->input->post('betValue').",oddsValue='".$this->input->post('OddValue')."',oddsNo".$this->input->post('OddsNumber').",typeid=".$this->input->post('TypeID').",MatchId=".$this->input->post('matchId').",FancyId=".$this->input->post('FancyID').",ParantId=".$ParantId.",Userid=".$userId.",date='".date('Y-m-d H:i:s',now())."',LoginId=".$loginId.",FancyId=".$this->input->post('FancyId').",HeadName='".$this->input->post('HeadName')."',SEssonYes=".$this->input->post('SessInptYes').",SessionNo=".$this->input->post('SessInptNo').",SPRTID=".$this->input->post('sportId').",REMOTEADD='".$_SERVER["REMOTE_ADDR"]."',POINTDIFF=".$this->input->post('pointDiff').",DEviceInfo".$this->input->post('deviceInformation').",result="."null";
                    $parameter="".$this->input->post('betValue').",'".$this->input->post('OddValue')."',".$this->input->post('OddsNumber').",".$this->input->post('TypeID').",".$this->input->post('matchId').",".$this->input->post('FancyID').",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$this->input->post('FancyId').",'".$this->input->post('HeadName')."','".$this->input->post('SessInptYes')."','".$this->input->post('SessInptNo')."',".$this->input->post('sportId').",'".$_SERVER["REMOTE_ADDR"]."',".$this->input->post('pointDiff').",".$this->input->post('deviceInformation').","."null";
                    //die();
                    $query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");
                    $res = $query->result_array();
                    $query->next_result();
                    $query->free_result();
                    return $res;
                }
            if($_POST['TypeID']==3){
                // $parameter="BetValue=".$this->input->post('betValue').",oddsValue='".$this->input->post('OddValue')."',oddsNo".$this->input->post('OddsNumber').",typeid=".$this->input->post('TypeID').",MatchId=".$this->input->post('matchId').",FancyId=".$this->input->post('FancyID').",ParantId=".$ParantId.",Userid=".$userId.",date='".date('Y-m-d H:i:s',now())."',LoginId=".$loginId.",FancyId=".$this->input->post('FancyId').",HeadName='".$this->input->post('HeadName')."',SEssonYes=".$this->input->post('SessInptYes').",SessionNo=".$this->input->post('SessInptNo').",SPRTID=".$this->input->post('sportId').",REMOTEADD='".$_SERVER["REMOTE_ADDR"]."',POINTDIFF=".$this->input->post('pointDiff').",DEviceInfo".$this->input->post('deviceInformation').",result="."null";
                $parameter="".$this->input->post('betValue').",'".$this->input->post('OddValue')."',".$this->input->post('OddsNumber').",".$this->input->post('TypeID').",".$this->input->post('matchId').",".$this->input->post('FancyID').",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$this->input->post('FancyId').",'".$this->input->post('HeadName')."','".$this->input->post('SessInptYes')."','".$this->input->post('SessInptNo')."',".$this->input->post('sportId').",'".$_SERVER["REMOTE_ADDR"]."',".$this->input->post('pointDiff').",".$this->input->post('deviceInformation').","."null";
                //die();
                $query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");
                $res = $query->result_array();
                $query->next_result();
                $query->free_result();
                return $res;
            }
            if($_POST['TypeID']==5){
                // $parameter="BetValue=".$this->input->post('betValue').",oddsValue='".$this->input->post('OddValue')."',oddsNo".$this->input->post('OddsNumber').",typeid=".$this->input->post('TypeID').",MatchId=".$this->input->post('matchId').",FancyId=".$this->input->post('FancyID').",ParantId=".$ParantId.",Userid=".$userId.",date='".date('Y-m-d H:i:s',now())."',LoginId=".$loginId.",FancyId=".$this->input->post('FancyId').",HeadName='".$this->input->post('HeadName')."',SEssonYes=".$this->input->post('SessInptYes').",SessionNo=".$this->input->post('SessInptNo').",SPRTID=".$this->input->post('sportId').",REMOTEADD='".$_SERVER["REMOTE_ADDR"]."',POINTDIFF=".$this->input->post('pointDiff').",DEviceInfo".$this->input->post('deviceInformation').",result="."null";
                $parameter="".$this->input->post('betValue').",'".$this->input->post('OddValue')."',".$this->input->post('OddsNumber').",".$this->input->post('TypeID').",".$this->input->post('matchId').",".$this->input->post('FancyID').",".$ParantId.",".$userId.",'".date('Y-m-d H:i:s',now())."',".$loginId.",".$this->input->post('FancyId').",'".$this->input->post('HeadName')."','".$this->input->post('SessInptYes')."','".$this->input->post('SessInptNo')."',".$this->input->post('sportId').",'".$_SERVER["REMOTE_ADDR"]."',".$this->input->post('pointDiff').",".$this->input->post('deviceInformation').","."null";
                //die();
                $query =$this->db->query("call sp_PlaceBet_Fancy($parameter)");
                $res = $query->result_array();
                $query->next_result();
                $query->free_result();
                return $res;
            }

		}
		function Get_ParantId($userId){
			$this->db->select("parentId");
			$this->db->from('createmaster');
			$this->db->where('mstrid',$userId);
			$query = $this->db->get();
            //echo $this->db->queries[1];
            return $query->result_array();
		}
		function GetSettlementAmt($userId){
			$this->db->select("Fn_getSettingP($userId) as parentSettling,Fn_getSettlingU($userId) as userSettling");
			$query = $this->db->get();            
            return $query->result_array();
		}
		function getDataById($userId,$UserType){
			$this->db->select("SessionComm,OtherComm,InPlayStack,mstruserid,IFNULL(mstrremarks, '') mstrremarks,partner,parentId,set_timeout,lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype1,usetype,liability as Liability,balance as Balance,p_l as P_L,freechips	as FreeChips,mstrlock,lgnusrlckbtng");
			$this->db->where('usetype > ',$UserType);
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			$this->db->from('createmaster cm');
			$query = $this->db->get();
		//	echo $this->db->queries[0];die();		
			return $query->result_array();
		}



		/**
		 * [getDataByIdPaging get user listing for dealers]
		 * @param  [int] $userId   [user id]
		 * @param  [int] $UserType [usertype]
		 * @param  [string] $search   [search by username]
		 * @param  [int] $pageno   [page no]
		 * @param  [int] $limit    [limit]
		 * @return [array]           [result]
		 */
		function getDataByIdPaging($userId,$UserType,$search,$pageno,$limit){

			$this->db->select("mstruserid");
			$this->db->from('createmaster cm');
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			if(!empty($search)){
				$where = "(mstrname LIKE '%$search%' OR mstruserid LIKE '%$search%')";
        		$this->db->where($where);
			}

			$total_rows = $this->db->count_all_results(); 

			$this->db->select("SessionComm,OtherComm,InPlayStack,mstruserid,IFNULL(mstrremarks, '') mstrremarks,partner,parentId,set_timeout,lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype1,usetype,FN_getLibility(cm.mstrid) as Liability,Fn_GetUserBal(cm.mstrid) as Balance,FN_getUserChips(cm.mstrid) as P_L,Fn_getUserFreeChip(cm.mstrid) as FreeChips,mstrlock,lgnusrlckbtng");
			$this->db->where('usetype > ',$UserType);
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			$this->db->from('createmaster cm');
			if(!empty($search)){

				$where = "(mstrname LIKE '%$search%' OR mstruserid LIKE '%$search%')";
        		$this->db->where($where);

			/*	$this->db->like('mstrname', $search, 'both');
				$this->db->like('mstruserid', $search, 'both'); */
			}

			if(!empty($limit)){
				$page_max = $limit;
	        	$start = ($pageno - 1) * $page_max;
	        	$end = $pageno * $page_max;
				$this->db->limit($page_max, $start);	
			}

			$query = $this->db->get();

		//	echo $this->db->queries[0];die();		
			return array(
        		'total_rows' => $total_rows,
        		'result'     => $query->result_array(),
    		); 
			
		}

        function getDataByUserType($UserType ,$parentId=null){
            $this->db->select("SessionComm,OtherComm,InPlayStack,mstruserid,IFNULL(mstrremarks, '') mstrremarks,partner,parentId,set_timeout,lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype1,usetype,liability as Liability,balance as Balance,p_l as P_L,freechips	as FreeChips,mstrlock,lgnusrlckbtng");
            $this->db->where('usetype  ',$UserType);
            if($parentId!=null){
                $this->db->where('parentId  ',$parentId);
            }
            $this->db->where('lgnusrCloseAc != 0');
            $this->db->from('createmaster cm');
            $query = $this->db->get();
            //	echo $this->db->queries[0];die();
            return $query->result_array();
        }
       

		function getDealerById($userId,$UserType){
			$this->db->select("SessionComm,OtherComm,InPlayStack,mstruserid,IFNULL(mstrremarks, '') mstrremarks,partner,parentId,set_timeout,lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype1,usetype,liability as Liability,balance as Balance,p_l as P_L,freechips	as FreeChips,mstrlock,Fn_DealerAvailBal(cm.mstrid) as availBal,lgnusrlckbtng,create_no_of_child");
			$this->db->where('usetype',2);
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			$this->db->from('createmaster cm');
			$query = $this->db->get();
			//echo $this->db->queries[0];die();		
			return $query->result_array();
		}

		function getMasterById($userId,$UserType){
			$this->db->select("SessionComm,OtherComm,InPlayStack,mstruserid,IFNULL(mstrremarks, '') mstrremarks,partner,parentId,set_timeout,lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype1,usetype,liability as Liability,balance as Balance,p_l as P_L,freechips	as FreeChips,mstrlock,Fn_DealerAvailBal(cm.mstrid) as availBal,lgnusrlckbtng, create_no_of_child");
			$this->db->where('usetype',1);
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			$this->db->from('createmaster cm');
			$query = $this->db->get();
			//echo $this->db->last_query();die();
			return $query->result_array();
		}

		function ChkFancyOnBet($matchId,$fancyId,$SessInptYes,$SessInptNo){

            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $key = $this->db->database.'ind_' . $matchId . '_'.$fancyId;
                $sessionOdds = json_decode($redis->get($key));
                if((($sessionOdds->SessInptYes ==$SessInptYes AND $sessionOdds->SessInptNo=$SessInptNo ) or ($sessionOdds->fancy_mode=='A')) and $sessionOdds->active==1){
                    return array(array('resultV'=>1,'retMess'=>'Pass the next Process...'));
                }else{
                    return array(array('resultV'=>0,'retMess'=>'Fancy is not active or Session Value not Match'));
                }
                $redis->close();

            } catch (Exception $e) {
                return array(array('resultV'=>0,'retMess'=>'Some thing is wrong'));
            }

			/*$query =$this->db->query("call sp_ChkFancyOnBet($matchId,$fancyId,$SessInptYes,$SessInptNo)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;*/
			

		}
		function GetBetData($matchId,$fancyId,$userId,$usertype){

					$query =$this->db->query("call SP_GetFancy($userId,$usertype,$fancyId)");
					$res = $query->result_array();
					$query->next_result();
					$query->free_result();
					return $res;
		}
		function getFancylistHeader(){

			$this->db->select("mf.ID,mf.Remarks,mchmst.matchName,mf.HeadName,mf.active,mf.result,mf.TypeID,mchmst.SportID as SportID,mf.MatchID,mf.is_indian_fancy,mf.fancy_mode,market.Id market_id,mf.ind_fancy_selection_id mFancyId,mf.max_session_bet_liability,mf.max_session_liability");
			$this->db->from('matchfancy mf');
			$this->db->join('matchmst mchmst', 'mf.MatchID=mchmst.MstCode', 'INNER');
			$this->db->join('market', "mf.MatchID=market.matchId AND market.Name = 'Match Odds'", 'LEFT');

			//$this->db->where('mchmst.active',1);by aj
			// $this->db->where('mf.active!=','2');
			$this->db->where('mf.result',null);
			$this->db->order_by("mf.ID desc");

			/*$this->db->join('seriesmst srmst', 'srmst.ID=mchmst.SeriesID', 'INNER');*/
		
			$query = $this->db->get();
            //echo $this->db->queries[0];

			return $query->result_array();
		}	

		function getMasterFancylist($userId=NULL,$matchId=NULL){
			$this->db->select("mf.ID,mchmst.matchName,mf.HeadName,(
			        CASE WHEN udms.id IS NULL
			            THEN 1
			            ELSE 0
			            END
			        ) AS active");
			$this->db->from('matchfancy mf');
			$this->db->join('matchmst mchmst', 'mf.MatchID=mchmst.MstCode', 'INNER');	
			$this->db->join('user_deactive_match_session udms', "mf.ID = udms.fancy_id AND udms.user_id = $userId", 'LEFT');		
			$this->db->where('mf.active','1');
			$this->db->where('mf.result',NULL);
			$this->db->where('mf.MatchID',$matchId);
			$this->db->order_by("mf.HeadName asc");

			$query = $this->db->get();
		//	print_r($this->db->queries);die();		
			return $query->result_array();
		}

		function getDealerFancylist($userId=NULL,$parentId=NULL,$matchId=NULL){
			$this->db->select("mf.ID,mchmst.matchName,mf.HeadName,(
			        CASE WHEN udms.id IS NULL
			            THEN 1
			            ELSE 0
			            END
			        ) AS active");
			$this->db->from('matchfancy mf');
			$this->db->join('matchmst mchmst', 'mf.MatchID=mchmst.MstCode', 'INNER');	
			$this->db->join('user_deactive_match_session udms', "mf.ID = udms.fancy_id AND udms.user_id = $userId", 'LEFT');			
			$this->db->join('user_deactive_match_session mdms', "mf.ID = mdms.fancy_id AND mdms.user_id = $parentId", 'LEFT');			
			$this->db->where('mf.active','1');
			$this->db->where('mf.result',NULL);
			$this->db->where('mf.MatchID',$matchId);
			$this->db->where('mdms.id IS NULL');
			$this->db->order_by("mf.HeadName asc");

			$query = $this->db->get();
		//	print_r($this->db->queries);die();		
			return $query->result_array();
		}

		function updateFancyHeaderSatatus(){

            $dataArray = array('active' => $_POST['active'],'HelperID' => $_POST['HelperID']);
            $this->db->where('ID',$_POST['id']);
            $this->db->update('matchfancy', $dataArray);
            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $key = $this->db->database.'ind_' . $_POST['MatchID'] . '_'.$_POST['id'];
                if($_POST['active']==2){
                    $redis->delete($key);
                }else{
                    if(empty($redis->get($key))){
                        $this->model_load_model('Modelmatchfancy');
                        $result = $this->Modelmatchfancy->selectFancyById($_POST['id']);
                        $result['ID'] = $_POST['id'];
                        $redis->set($key, json_encode($result));
                    }else{
                        $sessionOdds = json_decode($redis->get($key),true);
                        $sessionOdds['active'] = $_POST['active'];
                        $sessionOdds['HelperID'] = $_POST['HelperID'];
                        $redis->set($key, json_encode($sessionOdds));
                    }

                }

                $redis->close();
            } catch (Exception $e) {
            }
            return true;
		
		}

        function updateFancyHeader(){

            $dataArray = array('HeadName' => $_POST['HeadName']);
            $this->db->where('ID',$_POST['id']);
            $this->db->update('matchfancy', $dataArray);

            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $key = $this->db->database.'ind_' . $_POST['MatchID'] . '_'.$_POST['id'];
                $sessionOdds = json_decode($redis->get($key),true);

                $sessionOdds['HeadName'] = $_POST['HeadName'];
                $redis->set($key, json_encode($sessionOdds));
                $redis->close();
                return true;
            } catch (Exception $e) {
                return false;
            }

        }

		function updateFancyResult(){

			$sportId=$_POST['sportId'];
			$match_id=$_POST['match_id'];
			$fancy_Id=$_POST['fancy_Id'];
			$result=$_POST['result'];
			/*$dataArray = array('result' => $_POST['result']);
    		$this->db->where('ID',$_POST['id']);
            $this->db->update('matchfancy', $dataArray);
            //echo $this->db->queries[0];die();
            return true; */
           //echo "call SP_SetResult_Session($sportId,$match_id,$fancy_Id,$result)";die;
           //file_put_contents('prashant123.txt',"call SP_SetResult_Session($sportId,$match_id,$fancy_Id,$result)");die;
            //$query =$this->db->query("call SP_SetResult_Session($sportId,$match_id,$fancy_Id,$result)");
            $query =$this->db->query("call SP_SetResult_Session($sportId,$match_id,$fancy_Id,$result)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();

			$query1=$this->db->query("call p_l_by_match($match_id)");

			return $res;
		}
        function updateKhaddalResult(){
            $sportId=$_POST['sportId'];
            $match_id=$_POST['match_id'];
            $fancy_Id=$_POST['fancy_Id'];
            $fancyType=$_POST['fancyType'];
            $result=$_POST['result'];
			$HelperID=$_POST['HelperID'];
            //print_r($_POST);
            //echo "call SP_SetResult_Other($sportId,$match_id,$fancy_Id,$result,$fancyType,$HelperID)";
            $query =$this->db->query("call SP_SetResult_Other($sportId,$match_id,$fancy_Id,$result,$fancyType,$HelperID)");
            $res = $query->result_array();
            $query->next_result();
            $query->free_result();
            //print_r($res);die();
            return $res;
        }
		function getMatchOddsLimit($matchId)//sourabh 11-nov-2016
	    {
		
			$this->db->select('oddsLimit,volumeLimit');
			$this->db->from('matchmst');
			$this->db->where('MstCode', $matchId);
			$query = $this->db->get();
			//$result=$query->result()[0]->oddsLimit;
			//return $result;
			return $query->result_array();
		}
		function closeUserList(){
			$query  =  "SET @srno = 0;";
			$this->db->query($query);
			$this->db->select(" (@srno := @srno + 1) AS Sno,mstrid,mstrname,mstruserid,usecrdt,lgnusrCloseAc, CASE WHEN usetype=0 THEN 'ADMIN' ELSE CASE WHEN usetype=1 THEN 'Master' ELSE CASE WHEN usetype=2 THEN 'Dealer' ELSE CASE WHEN usetype=3 THEN 'Client'  END  END  END END as UserType");
			$this->db->from('createmaster');
			$this->db->where('lgnusrCloseAc', 0);
		//	echo $this->db->queries[0];die();	
			$query = $this->db->get();
			return $query->result_array();
		}
		function updateClsAc($userId,$status){

			$dataArray = array('lgnusrCloseAc' => $status);
    		$this->db->where('mstrid',$userId);
            $query=$this->db->update('createmaster', $dataArray);
            //echo $this->db->queries[0];die();	
            return $query; 
		}
		function getFancyByEdit($id,$type,$matchID=null){

            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $key = $this->db->database.'ind_' . $matchID . '_'.$id;
                //echo $this->db->database.'ind_' . $matchID . '_'.$id;die;
                $sessionOdds = $redis->get($key);

                $redis->close();


                return array(json_decode($sessionOdds));
            } catch (Exception $e) {
            }
		}
		function scorePosition($userId,$fancyId,$typeId){
			
			$query =$this->db->query("call sp_GetScorePosition($userId,$fancyId,$typeId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
					
			return $res;
		}
        function scorePosition_evenOdd($userId,$fancyId,$typeId,$matchId){

        $query =$this->db->query("call sp_GetScorePosition_OE($userId,$fancyId,$matchId)");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();

        return $res;
        }
        function EditMultipleFancy($MatchId){
			$this->db->select('*');
			$this->db->from('matchfancy');
			$this->db->where('TypeID',2);
			$this->db->where('MatchID',$MatchId);
			$this->db->where('active <>',2);
			$this->db->where('result',null);
			$this->db->order_by('ID', 'DESC');
			$query1 = $this->db->get();		
			//echo $this->db->queries[0];die();				
			return $query1->result_array();	
		}
        function scorePosition_Khaddal($userId,$fancyId,$typeId,$matchId){

            $query =$this->db->query("call sp_GetScorePosition_Kaddal ($userId,$fancyId,$matchId)");
            $res = $query->result_array();
            $query->next_result();
            $query->free_result();

            return $res;
        }
        function scorePosition_LastDigit($userId,$fancyId,$typeId,$matchId){

            $query =$this->db->query("call sp_GetScorePosition_LD ($userId,$fancyId,$matchId)");
            $res = $query->result_array();
            $query->next_result();
            $query->free_result();

            return $res;
        }
        function scorePosition_Up_Down($userId,$fancyId,$typeId,$matchId){

            $query =$this->db->query("call sp_GetScorePosition_UpDown ($userId,$fancyId,$matchId)");
            $res = $query->result_array();
            $query->next_result();
            $query->free_result();

            return $res;
        }

		function setFancyMsg(){
			$dataArray = array('DisplayMsg' => $_POST['message'],'active' =>4);
    		$this->db->where('MatchID',$_POST['MatchID']);
    		//$this->db->where('result',null);
    		$this->db->where('TypeID',$_POST['TypeId']);
            $query=$this->db->update('matchfancy', $dataArray);
        //    echo $this->db->queries[0];

            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $key = $this->db->database.'ind_' . $_POST['MatchID'] . '_'.$_POST['FancyID'];
                $sessionOdds = json_decode($redis->get($key),true);

                $sessionOdds['active'] = 4;

                $sessionOdds['DisplayMsg'] = $_POST['message'];
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
		function chnageRate(){
			$dataArray = array('RateChange' => $_POST['RateChange']+1,'active' =>4,'DisplayMsg' => 'Rate Change');
    		$this->db->where('ID',$_POST['FancyId']);
    		$query=$this->db->update('matchfancy', $dataArray);
            return $query;
		}
		function updateRateChangeMsg($FancyID,$TypeID){
			$dataArray = array('active' =>4,'DisplayMsg' => 'Rate Change');
    		$this->db->where('ID',$FancyID);
    		$query=$this->db->update('matchfancy', $dataArray);
            return $query;
		}
		function getPartnerShip($userId){
			$this->db->select('*');
			$this->db->from('tblpartner');
			$this->db->where('UserID',$userId);
			$query1 = $this->db->get();					
			return $query1->result_array();	
		}
		function getParentData($userId){
			$this->db->select('ca.*');
			$this->db->from('createmaster cm');
			$this->db->join('createmaster ca', 'ca.mstrid=cm.parentId', 'INNER');
			$this->db->where('cm.mstrid',$userId);
			$query1 = $this->db->get();					
			return $query1->result_array();	
		}				
		function getMTree(){
		  	$query =$this->db->query("call sp_getMTree()");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
		}
	}