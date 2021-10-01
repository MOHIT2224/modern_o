<?php
/*
 * Controller for save session in DB ""
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Savesession extends CI_Controller {
    
    public $APP_KEY = BETFAIR_APP_KEY;
    
    function __construct() {

            parent::__construct();

            $this->load->model('Modeladminmarketsessions');

            $this->load->model('Modelmatchmst');

            $_POST = json_decode(file_get_contents('php://input'), true);
            //	$node1=$this->session->userdata('user_id');

            $currentMethod = $this->router->method;
            $allowAuth = array('saveMarketSession');
            if(!in_array($currentMethod, $allowAuth)){
                  if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
            } 
            //if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
    }
            function getTokenId(){

                if ($_POST['TokenId']!=null) {

                        $TokenId = $_POST['TokenId'];
                        //print_r($TokenId);
                        //die();
                }

                else{

                        $TokenId = $this->session->userdata('TokenId');

                        //print_r($TokenId);

                        //die();

                }
                return $TokenId;
            }

        function saveMarketSession(){
            
            $markets = $this->Modeladminmarketsessions->activeMatchMarket();
            $data_markets = array();
            foreach($markets as $mrkt){
                $data_markets[]= $mrkt->Id;
            }
            $forsessions = array_map(function($value) { return $value.'_s'; }, $data_markets);
            
            $forsessions = array('1.151424589_s');
            
            if(!empty($forsessions)){
                
                $marketsN = implode(',',$forsessions);
                
                //$marketsN = '1.151424589_s';
                
//                $base_url = 'http://139.162.242.237:8383/get_odds_by_market_ids.php?market_id='.$marketsN;
//                $sessions = $this->GetOddsAPi($base_url);
//                 $marketsession = $this->db->get('admin_market_sessions');
//                 $marketsessions = $marketsession->result();
                 
                 $sessions = '[{"market_id":"1.151424589","value":{"session":[{"BackSize1":100,"GameStatus":"","BackPrice1":7,"RunnerName":"MATCH 1ST OVER RUN (JS VS PLR)ADV","SelectionId":"3","LayPrice1":6,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":49,"RunnerName":"6 OVER RUN JS (JS VS PLR)ADV","SelectionId":"4","LayPrice1":47,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":49,"RunnerName":"6 OVER RUN PLR (JS VS PLR)ADV","SelectionId":"5","LayPrice1":47,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":172,"RunnerName":"20 OVER RUN JS (JS VS PLR)ADV","SelectionId":"6","LayPrice1":170,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":172,"RunnerName":"20 OVER RUN PLR (JS VS PLR)ADV","SelectionId":"7","LayPrice1":170,"LaySize1":100},{"BackSize1":90,"GameStatus":"","BackPrice1":23,"RunnerName":"FALL OFF 1ST WKT JS ADV","SelectionId":"8","LayPrice1":23,"LaySize1":110},{"BackSize1":90,"GameStatus":"","BackPrice1":25,"RunnerName":"FALL OFF 1ST WKT PLR ADV","SelectionId":"9","LayPrice1":25,"LaySize1":110},{"BackSize1":90,"GameStatus":"","BackPrice1":26,"RunnerName":"C GAYLE RUN (JS VS PLR)ADV","SelectionId":"10","LayPrice1":26,"LaySize1":110},{"BackSize1":90,"GameStatus":"","BackPrice1":18,"RunnerName":"R RICKELTON RUN (JS VS PLR)ADV","SelectionId":"11","LayPrice1":18,"LaySize1":110},{"BackSize1":90,"GameStatus":"","BackPrice1":24,"RunnerName":"C DELPORT RUN (JS VS PLR)ADV","SelectionId":"12","LayPrice1":24,"LaySize1":110},{"BackSize1":90,"GameStatus":"","BackPrice1":24,"RunnerName":"M KLINGER RUN (JS VS PLR)ADV","SelectionId":"13","LayPrice1":24,"LaySize1":110},{"BackSize1":100,"GameStatus":"","BackPrice1":30,"RunnerName":"TOTAL MATCH FOUR (JS VS PLR)ADV","SelectionId":"14","LayPrice1":28,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":11,"RunnerName":"TOTAL MATCH SIXES (JS VS PLR)ADV","SelectionId":"15","LayPrice1":10,"LaySize1":100},{"BackSize1":85,"GameStatus":"","BackPrice1":325,"RunnerName":"TOTAL MATCH RUN (JS VS PLR)ADV","SelectionId":"16","LayPrice1":325,"LaySize1":115},{"BackSize1":100,"GameStatus":"","BackPrice1":13,"RunnerName":"TOTAL MATCH WKT (JS VS PLR)ADV","SelectionId":"17","LayPrice1":12,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":9,"RunnerName":"TOTAL MATCH WIDE (JS VS PLR)ADV","SelectionId":"18","LayPrice1":8,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":16,"RunnerName":"TOTAL MATCH EXTRA RUN (JS VS PLR)ADV","SelectionId":"19","LayPrice1":14,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":21,"RunnerName":"HIGHEST SCORING OVER IN MATCH (JS VS PLR)ADV","SelectionId":"20","LayPrice1":20,"LaySize1":100},{"BackSize1":100,"GameStatus":"","BackPrice1":69,"RunnerName":"TOP BATSMEN RUN IN MATCH (JS VS PLR)ADV","SelectionId":"21","LayPrice1":67,"LaySize1":100}]}}]';
                 
                if(count($sessions)>0){
                    
                    $condition=$this->Modeladminmarketsessions->SaveMarketSession($sessions); //Model function call to save sessions
                    
                    if($condition) {
                        echo json_encode(array('error' => 0 ,'message' => 'Session Inserted Successfully...'));
                    }
                    else
                    {
                        echo json_encode(array('error' => 1 ,'message' => 'Not Inserted'));
                    }
                }
                else
                {
                    echo json_encode(array('error' => 1 ,'message' => 'Session Not Found In That Market IDs!'));
                }
            }
            else
            {
                echo json_encode(array('error' => 1 ,'message' => 'Market Ids Not Found!'));
            }
        }
        
        function GetOddsAPi($baseURL){
                ob_start();
                        $ch = curl_init(); 
                        curl_setopt($ch, CURLOPT_URL, $baseURL); 
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                        $output = curl_exec($ch); 
                        return json_decode($output); 
                        curl_close($ch);
                ob_flush();
        }
        
        function active_match($sportId = NUll){
            //Need to add the get sport ID Code Here
            $this->db->where(array('active'=>'1','SportID'=>4));
            $active_m=$this->db->get('matchmst');
            $results=$active_m->result();
            
            $data = array();
            $i=0;
            foreach($results as $result){
              $data[$i]['matchName'] =  $result->matchName;
              $data[$i]['MstCode'] =  $result->MstCode;
              $data[$i]['MstDate'] =  $result->MstDate;
              $data[$i]['SportID'] =  $result->SportID;
              $data[$i]['volumeLimit'] =  $result->volumeLimit;
              $data[$i]['seriesId'] =  $result->seriesId;
              $i++;  
            }
            echo json_encode($data);
        }
        
        //Function to get the sessions by market ID
        
        function getSession($matchId){
            
            if(!empty($matchId)){
                
                $this->db->select('Id');
                $this->db->where(array('matchId'=>"$matchId"));
                $markt_l =$this->db->get('market');
                $market_list = $markt_l->result();

                $data_markets = array();
                foreach($market_list as $mrkt){
                    $data_markets[]= $mrkt->Id;
                }
        //        echo '<pre>';
        //        print_r($data_markets);
        //        exit;

                $markets = implode(',',$data_markets);
                
                 $condition=$this->Modeladminmarketsessions->getMarketSession($markets,$matchId); //Model function call to save sessions
//                 echo '<pre>';
//                 print_r($condition);
//                 exit;
                 
                 if(count($condition)>0){
                     echo json_encode(array('code'=>0,'error'=>false,"message"=>"Session fancy listing",'market_id'=>$markets,'data'=>$condition));
                 }
                 else
                 {
                     echo json_encode(array('error' => 1 ,'message' => 'Sessions Not Found Yet!'));
                 }
            }
            else
            {
                echo json_encode(array('error' => 1 ,'message' => 'Please Send Match Id!'));
            }
        }
        //
        function resultDeclared(){
            
              $jsonStr = $this->input->post();
            
//            echo '<pre>';
//            print_r($jsonStr);
//            exit;
            //$jsonStr = '{"id":"69","result":"Done"}';
            
            $data = $jsonStr;
            if(!empty($data)){
                $condition=$this->Modeladminmarketsessions->updateSessionResult($data); //Model function call to update session result
                
                if(count($condition)>0){
                    echo json_encode(array('error' => 0 ,'message' => 'Session Updated Successfully...','data'=>$condition));
                }
                else
                {
                    echo json_encode(array('error' => 1 ,'message' => 'Sessions Not updated. Please try again later!'));
                }
                
            }
            else
            {
                echo json_encode(array('error' => 1 ,'message' => 'Fency data not provided'));
            }
        }
        
}