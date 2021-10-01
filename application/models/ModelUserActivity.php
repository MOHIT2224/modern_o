<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class ModelUserActivity extends CI_Model{
        function __construct()
        {
            parent::__construct();
            $_POST = json_decode(file_get_contents('php://input'), true);
        }


        function saveAcivityData($beforeData,$afterData){
            $diff = array_diff_assoc($afterData,$beforeData);

            if(count($diff)){
                $afterDataJson = json_encode(array_diff_assoc($afterData,$beforeData));
                $beforeDataJson = json_encode(array_diff_assoc($beforeData,$afterData));
                $user_id = $this->session->userdata('user_id');
                $arrayValue = ['user_id'=>$user_id,'before_data'=>$beforeDataJson,'after_data'=>$afterDataJson];
                $query=$this->db->insert('tblactivity', $arrayValue);
            }


        }

	}