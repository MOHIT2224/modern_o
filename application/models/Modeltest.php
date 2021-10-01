<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Modeltest extends CI_Model{
		function __construct(){
				
		}

		function getDataByIdPaging($userId,$UserType,$search,$pageno,$limit){

			$page_max = !empty($limit) ? $limit : DEFAULT_PAGING_LIMIT;
	        $start = ($pageno - 1) * $page_max;
	        $end = $pageno * $page_max;

			$this->db->select("mstruserid");
			$this->db->from('createmaster cm');
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			$total_rows = $this->db->count_all_results();

			$this->db->select("SessionComm,OtherComm,InPlayStack,mstruserid,IFNULL(mstrremarks, '') mstrremarks,partner,parentId,set_timeout,lgnUserMaxProfit,lgnUserMaxLoss,lgnUserMaxStake,Commission,stakeLimit,mstrid as usecode,mstrname,mstruserid as usename,active,ipadress,usecrdt,usemodt,case when usetype=0 then 'Admin' else case when usetype=1 then 'Master' else case when usetype=2 then 'Dealer' else case when usetype=3 then 'Client' else case when usetype=4 then 'Helper' end end end end end as usetype1,usetype,FN_getLibility(cm.mstrid) as Liability,Fn_GetUserBal(cm.mstrid) as Balance,FN_getUserChips(cm.mstrid) as P_L,Fn_getUserFreeChip(cm.mstrid) as FreeChips,mstrlock,lgnusrlckbtng");
			$this->db->where('usetype > ',$UserType);
			$this->db->where('parentId',$userId);
			$this->db->where('lgnusrCloseAc != 0');
			$this->db->from('createmaster cm');
			if(!empty($search)){
				$this->db->like('mstrname', $search, 'both');
				$this->db->like('mstruserid', $search, 'both');
			}
			$this->db->limit($page_max, $start);
			$query = $this->db->get();

		//	echo $this->db->queries[0];die();		
			return array(
        		'total_rows' => $total_rows,
        		'result'     => $query->result_array(),
    		); 
			
		}





	}