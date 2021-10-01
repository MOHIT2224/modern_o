<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Lstmarkettypecntr extends CI_Controller {
        function __construct() {
            parent::__construct();
            $this->load->model('Modelmarkettype');
            if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
        }
        function getAllMarketType(){
            $data['lstMrktType'] = $this->Modelmarkettype->lstAllMarket();
            $this->output->set_content_type('application/json')->set_output(json_encode($data));    
        }
        function deleteMarketType($mid){
                $query=$this->Modelmarkettype->deleteMarketType($mid);
                if ($query) {
                    echo json_encode(array('error' => 0 ,'message' => 'Record Deleted Successfully...'));
                }else{
                    echo json_encode(array('error' => 1 ,'message' => 'Record not Deleted Successfully...'));
                }
        }
    
    }       
               
