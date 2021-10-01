<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
		class Modelmarkettype extends CI_Model
		{
			function __construct()
			{
		        $_POST = json_decode(file_get_contents('php://input'), true);
			}
			function lstAllMarket(){
				$this->db->select("mtcmst.matchName,mtype.Name,mtype.marketCount");
				$this->db->from('markettype mtype');
				$this->db->join('matchmst mtcmst','mtcmst.MstCode=mtype.matchId');
				$this->db->where('mtype.active', 1);
				$query = $this->db->get();
				//echo $this->db->queries[0];die();
				return $query->result_array();
			}
		}
?>