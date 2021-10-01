<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class Loginauthcontroller extends CI_Controller {

		function __construct() {

		        parent::__construct();

                $_POST = json_decode(file_get_contents('php://input'), true);

		        $node1=$this->session->userdata('user_id');

		        $this->load->model('Modelchkuser');
		       // if ($this->session->userdata('user_id') != '') { } else { redirect(base_url());}

			}

		function chkLoginUser(){

			$this->load->model('Modelcreatemaster');

            $user_data=$this->Modelchkuser->chkAuthName();

        /*    print_r($user_data);

            die(); */

			if ($user_data['iType']==0) {

			/*	if($user_data['mstrid']==1){
					$getToken=$this->getACookie();
					if(strlen($getToken) < 60){
						$this->Modelcreatemaster->saveBetfairToken($getToken);
					}else{
						$getToken = $this->Modelcreatemaster->findBetfairToken();	
					}
				}else{
					$getToken = $this->Modelcreatemaster->findBetfairToken();
				} */

				$this->session->set_userdata('TokenId', ''); 

				$data['type'] = $user_data['usetype'];

				$data['user_name'] = $user_data['mstruserid'];

				$data['user_id'] = $user_data['mstrid'];

            	$data['error'] = $user_data['iType'];

				$data['message'] = $user_data['Msg'];

				$data['ChangePas'] = $user_data['ChangePas'];

				$data['TokenId'] = "yPAFq7YCIi/nVwwwGe1vr2IM/v+LtGxRvEhmHyzTbx8=";

				$data['set_timeout'] = $user_data['set_timeout'];

				$data['lgnstatus'] = $this->session->userdata('session_id');

				$data['last_login_id'] = $this->session->userdata('last_login_id');

				$data['mstrpassword'] = $user_data['mstrpassword'];

				echo json_encode($data);

			}else{

                $data['error'] = $user_data['iType'];

                $data['message'] = $user_data['Msg'];

				echo json_encode($data);

			}

		}

		function adminDashboard(){

			$this->load->view('templates/adminDashboard');

		}

		function logout(){

			/*$logoutId = $this->session->userdata('last_login_id');

			$session_id = $this->session->userdata('session_id');*/

			//$user_data=$this->Modelchkuser->logoutentry($logoutId,$session_id,$userId);

			$user_data=$this->Modelchkuser->logoutentry();

			$this->session->sess_destroy();

			$data['message'] = "Logout";

			echo json_encode($data);

		}

		function is_logged_in() {



		   $user = $this->session->userdata('user_name');





		   if (empty($user)) { 

		  // 	$this->session->sess_destroy();

		   	//redirect(base_url());

		   		$data['data'] = 0;

				$data['status'] = 'Invalid User Name Or Password';

				//echo json_encode($data);

		       return $this->output->set_content_type('application/json')->set_output(json_encode($data));

		      //return false;

		   } 

		   else { 

		   		$data['data'] = 1;

				$data['status'] = 'Valid User Name Or Password';

				//echo json_encode($data);

		   		/*$data['data'] = 1;

				$data['status'] = 'valid';

				echo json_encode($data);*/

		      return $this->output->set_content_type('application/json')->set_output(json_encode($data));

				//return true;

		   }



		}

		function is_logged_in_check() {

		   $user = $this->session->userdata('user_name');

		   if (empty($user)) { 

		   		$data['is_login'] = false;

		   		$data['data'] = array();

				$data['status'] = 'Invalid User Name Or Password';

				//echo json_encode($data);

		       return $this->output->set_content_type('application/json')->set_output(json_encode($data));

		      //return false;

		   }else { 

		   		$data['is_login'] = true;

		   		$sessionData = array('type'=> $this->session->userdata('type'),'user_name'=>$this->session->userdata('user_name'),'user_id'=>$this->session->userdata('user_id'),'mstrpassword'=>$this->session->userdata('mstrpassword'));

		   		$data['data'] = $sessionData;

				$data['status'] = 'Valid User Name Or Password';

				//echo json_encode($data);

		   		/*$data['data'] = 1;

				$data['status'] = 'valid';

				echo json_encode($data);*/

		      return $this->output->set_content_type('application/json')->set_output(json_encode($data));

				//return true;

		   }



		}

		

	function chkLoginStatus($userId){ 
            $data['status'] = $this->Modelchkuser->chkLoginStatus($userId); 
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

}