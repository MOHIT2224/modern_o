<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modeltblconfig extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    function find(){
        $this->db->select('adminLImit,Marquee,match_detection_point,going_in_play_limit,bet_delay,terms_conditions,max_bet_liability,max_market_liability,max_session_bet_liability,max_session_liability,max_market_profit');
        $this->db->from('tblconfig');
        $this->db->where('Id',1);
        $query = $this->db->get();
        $data =  $query->result_array();

        $myfile = fopen("marquee.txt", "r") ;
        $res =  fread($myfile,filesize("marquee.txt"));
        fclose($myfile);
        $data[0]['Marquee'] = $res;
        return $data;
    }

    function update($updateArr=NULL){
        $this->db->where('Id',1);
        $this->db->update('tblconfig', $updateArr);
        //echo $this->db->queries[0];die();		
        return true;
    }

}