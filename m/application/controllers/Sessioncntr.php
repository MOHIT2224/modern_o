<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class Sessioncntr extends CI_Controller {
		function __construct() {
	        parent::__construct();
			//$this->load->model('Sessionmodel');
			if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		}
		function getFancyData($matchId,$fancyId,$userId,$usertype,$TypeID){		
			$data['fancyForm'] = $this->Sessionmodel->getFancyData($matchId,$fancyId);
			/*if($usertype!='3')
				$data['UserBetData'] = $this->Sessionmodel->GetBetData($matchId,$fancyId,$userId,$usertype);
			else*/
			$data['UserBetData'] = $this->Sessionmodel->GetBetData($matchId,$fancyId,$userId,$usertype);
            $data['scorePosition'] = $this->Sessionmodel->scorePosition($userId,$fancyId,$TypeID);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetSumOfBet($userId,$matchId,$FancyId,$fancyType,$yes,$no){
			$data['betSum'] = $this->Sessionmodel->GetSumOfBet($userId,$matchId,$FancyId,$fancyType,$yes,$no);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function FancyScorePosition($fancyId,$userId,$TypeID){		
			$data['scorePosition'] = $this->Sessionmodel->scorePosition($userId,$fancyId,$TypeID);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function DisableAllFancy($MatchId){
			$data['jsonData'] = $this->Sessionmodel->DisableAllFancy($MatchId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function sessionBetList($matchId){		
			$data['sessionBet'] = $this->Sessionmodel->sessionBetList($matchId);
			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}