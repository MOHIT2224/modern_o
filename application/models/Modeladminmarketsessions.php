<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * admin_market_sessions
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Modeladminmarketsessions extends CI_Model
{
    function __construct()
    {
            parent::__construct();
            $_POST = json_decode(file_get_contents('php://input'), true);
    }
    
    
    function insert($data=NULL){
            $condition = $this->db->insert('admin_market_sessions', $data);
            $admin_market_sessionId = $this->db->insert_id();

            return $admin_market_sessionId;
    }
    
    //Market Save Function In Model
    
    function SaveMarketSession($sesions=NULL){
        $currentDateTime = date('Y-m-d H:i:s',now());
        $data = array();
        foreach($sesions as $session){
            foreach($session->value->session as $sessionAtr){
                $data['market_id'] = $session->market_id;
                $data['selection_id'] = $sessionAtr->SelectionId;
                $data['runner_name'] = $sessionAtr->RunnerName;
                $data['game_status'] = $sessionAtr->GameStatus;
                $data['BackSize1'] = $sessionAtr->BackSize1;
                $data['BackPrice1'] = $sessionAtr->BackPrice1;
                $data['LayPrice1'] = $sessionAtr->LayPrice1;
                $data['LaySize1'] = $sessionAtr->LaySize1;
                $data['result'] = '';
                $data['created'] = $currentDateTime;
                $data['modified'] = $currentDateTime;
                
                $match_id = $this->db->get_where('market', array(
                    'Id' => "$session->market_id"
                )); //Query to check duplicate data
                $matchs = $match_id->result();
                print_r($matchs);
                exit;
                $q = $this->db->get_where('admin_market_sessions', array(
                    'market_id' => $session->market_id, 'selection_id' => $sessionAtr->SelectionId
                )); //Query to check duplicate data
            
                if($q->num_rows()<1) //Duplicate check funtion
                {
                    $this->db->insert('admin_market_sessions', $data);
                }
            }
            
        }
        return 1;
    }
    
    function getMarketSession($markets = NULL,$matchId=NULL){
        
        $where = "market_id IN ($markets)";
        
        $this->db->where($where);
        $active_m=$this->db->get('admin_market_sessions');
        $results=$active_m->result();
        $data = array();
        $i=0;
        foreach($results as $resultAtr) { 
            $data[$i]['id'] = $resultAtr->id;
            $data[$i]['market_id'] = $resultAtr->market_id;
            $data[$i]['MatchID'] = $matchId;
            $data[$i]['selection_id'] = $resultAtr->selection_id;
            $data[$i]['runner_name'] = $resultAtr->runner_name;
            $data[$i]['created'] = $resultAtr->created;
            $data[$i]['modified'] = $resultAtr->modified;
            $data[$i]['BackSize1'] = $resultAtr->BackSize1;
            $data[$i]['game_status'] = $resultAtr->game_status;
            $data[$i]['BackPrice1'] = $resultAtr->BackPrice1;
            $data[$i]['LayPrice1'] = $resultAtr->LayPrice1;
            $data[$i]['LaySize1'] = $resultAtr->LaySize1;
            $data[$i]['result'] = $resultAtr->result;   
            $i++;
        } 
//        echo '<pre>';
//        print_r($data);
//        exit;
        
        return $data;
    }
    
    
    function activeMatchMarket(){
        
        $this->db->select("mrt.Id");
        $this->db->from(' matchmst mtchmst');
        $this->db->join('market mrt', 'mrt.matchId=mtchmst.MstCode', 'INNER');
        $this->db->where(array('mtchmst.active'=>1,'mrt.Name'=>'Match Odds'));

        $query = $this->db->get();
        return $query->result();
    }
    
    //Update session to save the results
        
        function updateSessionResult($data = NULL){
//            echo '<pre>';
//            print_r($data);
//            exit;
            
            $id = $data['id'];
            $result = $data['result'];
            if(!empty($result)){
                $save_data=array('result'=>$result);
                $this->db->where('id',$id);
                $this->db->update('admin_market_sessions',$save_data);
                
//                $get_data = $this->db->get_where('admin_market_sessions',array('id'=>$id))->result();
//                $sql ="call SP_SetResult_Session(4,$match_id,4,$result)";
//                $conn1 = mysqli_connect('139.162.219.17', 'dev', 'Mysql@Password1234!', 'kv7');
//                mysqli_query($conn1, $sql);
                //$query =$this->db->query("call SP_SetResult_Session($sportId,$match_id,$fancy_Id,$result)");
                return $get_data;
                
                
            }
            else
            {
                return false;
            }
        }
    
}