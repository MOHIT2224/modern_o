<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Deletedata extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('Modelcreatemaster');
		        $_POST = json_decode(file_get_contents('php://input'), true);
		  		       
		    }
		function index(){ 
			$this->output->set_content_type('application/json')->set_output(json_encode($data)); 
		}
		function delete_all-@gkk(){
		$this->load->model('Modelcreatemaster');			
			$this->Modelcreatemaster->Tuncate_matchlst();
			echo json_encode(array('error' => 0 ,'message' => 'Data Deleted '));
		}

		function delete_all_bet-@gkk(){

			$this->load->model('Modelcreatemaster');			
			$result = $this->Modelcreatemaster->insertChipsAfterBetTruncate();

			$response = array();

			if(!empty($result)) {
				$response["code"] = 1;
				$response["error"] = true;
    			$response["message"] = 'There are unsettled matches';
    			$response["data"] = $result;
    			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}else{
				$response["code"] = 0;
				$response["error"] = false;
        		$response["message"] = 'Bets deleted successfully';
        		$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
		}
	}