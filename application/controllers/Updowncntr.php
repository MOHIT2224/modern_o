<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Updowncntr extends CI_Controller {
		function __construct() {
	        parent::__construct();
	        if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		}
		function getFancyData($matchId,$fancyId,$userId,$usertype,$TypeID){		
			$data['fancyForm'] = $this->Updownmodel->getFancyData($matchId,$fancyId);
			if($usertype!='3')
				$data['UserBetData'] = $this->Updownmodel->GetBetData($matchId,$fancyId,$userId,$usertype);
			else
				$data['UserBetData'] =null;
			
            $data['scorePosition'] = $this->Updownmodel->scorePosition($userId,$fancyId,$TypeID);

			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		//start here
		function update_updownFancy(){
			//$condition=$this->Modelcreatemaster->update_updownFancy();
            $condition=$this->Updownmodel->update_updownFancy();
            if ($condition) {
                echo json_encode(array('error' => 0 ,'message' => 'status Change Successfully...'));
            }else{
                echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
            }
        }
		function getFancyByEdit($id,$type){
			//$data['FancyData'] = $this->Modellstmaster->getFancyByEdit($id,$type);
			$data['FancyData'] = $this->Updownmodel->getFancyByEdit($id,$type);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}