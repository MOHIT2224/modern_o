<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Apidealercontroller extends CI_Controller {
		var $globalUserId;
		var $globalUserType;
		public $APP_KEY = BETFAIR_APP_KEY;

		function __construct() {

				header('Access-Control-Allow-Origin: *');

		        parent::__construct();

                $_POST = json_decode(file_get_contents('php://input'), true);

		        $node1=$this->session->userdata('user_id');

		        $this->load->model('Modelchkuser');
		       // if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}
		       $currentMethod = $this->router->method;
		       $allowAuth = array();
		       if(!in_array($currentMethod, $allowAuth)){
					$this->checkAuthentication();
		       }  
		}

		/**
		 * [checkAuthentication check user authentication by headers]
		 * @return [type] [description]
		 */
		function checkAuthentication(){

			$this->load->model('Modelcreatemaster');

			$username = $this->input->request_headers('PHP_AUTH_USER');
    		$password = $this->input->request_headers('PHP_AUTH_PW'); 
			$http_auth = $this->input->request_headers('Authorization');

			if(!empty($http_auth['Authorization'])){ 
 
				$basicauth = explode(' ', $http_auth['Authorization']);

				$userPass = $basicauth[1]; 
				$userPass = base64_decode($userPass);
				$authUser = explode(':', $userPass);
				$userName = $authUser[0];
				$password = $authUser[1];
				$checkUser = $this->Modelcreatemaster->checkUserStatus($userName,$password);
				
				if(empty($checkUser)){
					$response = array();
					$response["code"] = 1;
        			$response["error"] = true;
        			$response["message"] = "unauthorized access";
					$this->output->set_status_header(412)->set_content_type('application/json')->set_output(json_encode($response));
					exit();
				}else{
					$this->globalUserId = $checkUser['mstrid'];
					$this->globalUserType = $checkUser['usetype'];
					$this->globalParentId = $checkUser['parentId'];
				}
			}else{
					$response = array();
					$response["code"] = 1;
        			$response["error"] = true;
        			$response["message"] = "unauthorized access";
					$this->output->set_status_header(412)->set_content_type('application/json')->set_output(json_encode($response));
					exit();
			}
		}

		function match_status(){	

			$this->load->model('Modeluserdeactivematch');
			$this->verifyRequiredParams($_POST,array('match_id'));	

			$data['user_id'] = $this->globalUserId;
			$data['match_id'] = $_POST['match_id'];

			$data['status'] = $_POST['status'];  

			if($_POST['status']=="D"){

			$isAlreadyExists = $this->Modeluserdeactivematch->checkMatchExists($data);

			if($isAlreadyExists){
				$isInserted = $this->Modeluserdeactivematch->insert($data);
				if ($isInserted) {
					$response["code"] = 0;
					$response["error"] = false;
					$response["message"] = "Match is deactived";
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}else{
					$response["code"] = ERROR_TRY_AGAIN;
					$response["error"] = true;
					$response["message"] = ERROR_TRY_AGAIN_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}
			}else{
					$response["code"] = ERROR_EXITS;
					$response["error"] = true;
					$response["message"] = ERROR_EXITS_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}	
			}elseif($_POST['status']=="A"){

				$isAlreadyExists = $this->Modeluserdeactivematch->checkMatchExists($data);

				if($isAlreadyExists){
					$response["code"] = ERROR_RECORD_NOT_EXITS;
					$response["error"] = true;
					$response["message"] = ERROR_RECORD_NOT_EXITS_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}else{
					$isInserted = $this->Modeluserdeactivematch->delete($data);
					if ($isInserted) {
						$response["code"] = 0;
						$response["error"] = false;  
						$response["message"] = "Success";
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
					}else{
						$response["code"] = ERROR_TRY_AGAIN;
						$response["error"] = true;
						$response["message"] = ERROR_TRY_AGAIN_MSG;
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
					}
				}	
			}else{
				$response["code"] = ERROR_PARAM_REQUIRED;
				$response["error"] = true;
				$response["message"] = ERROR_PARAM_REQUIRED_MSG;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			}
		}

		function get_matches(){	

			$this->load->model('Modeleventlst');
			$userId = $this->globalUserId;
			$parentId = $this->globalParentId;

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Match listing";
			$response["data"] = $this->Modeleventlst->getDealerMatchLst($userId,$parentId);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}

		function get_match_session($matchId=NULL){	
			$this->load->model('Modellstmaster');
			$userId = $this->globalUserId;
			$parentId = $this->globalParentId;
			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = "Session listing";
			$response["data"] = $this->Modellstmaster->getDealerFancylist($userId,$parentId,$matchId);
			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response)); 
		}

		function match_session_status(){	

			$this->load->model('Userdeactivematchsession_model');
			$this->verifyRequiredParams($_POST,array('fancy_id'));	

			$data['user_id'] = $this->globalUserId;
			$data['fancy_id'] = $_POST['fancy_id'];  
			$data['status'] = $_POST['status'];  

			if($_POST['status']=="D"){

				$isAlreadyExists = $this->Userdeactivematchsession_model->checkRecordExists($data);

				if($isAlreadyExists){
					$isInserted = $this->Userdeactivematchsession_model->insert($data);
					if ($isInserted) {
						$response["code"] = 0;
						$response["error"] = false;  
						$response["message"] = "Success";
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
					}else{
						$response["code"] = ERROR_TRY_AGAIN;
						$response["error"] = true;
						$response["message"] = ERROR_TRY_AGAIN_MSG;
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
					}
				}else{
						$response["code"] = ERROR_EXITS;
						$response["error"] = true;
						$response["message"] = ERROR_EXITS_MSG;
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}	

			}elseif($_POST['status']=="A"){

				$isAlreadyExists = $this->Userdeactivematchsession_model->checkRecordExists($data);

				if($isAlreadyExists){
					$response["code"] = ERROR_RECORD_NOT_EXITS;
					$response["error"] = true;
					$response["message"] = ERROR_RECORD_NOT_EXITS_MSG;
					$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				}else{
					$isInserted = $this->Userdeactivematchsession_model->delete($data);
					if ($isInserted) {
						$response["code"] = 0;
						$response["error"] = false;  
						$response["message"] = "Success";
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
					}else{
						$response["code"] = ERROR_TRY_AGAIN;
						$response["error"] = true;
						$response["message"] = ERROR_TRY_AGAIN_MSG;
						$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
					}
				}	
			}else{
				$response["code"] = ERROR_PARAM_REQUIRED;
				$response["error"] = true;
				$response["message"] = ERROR_PARAM_REQUIRED_MSG;
				$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
			}
		}
}