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

		function declareResultApi(){
			
			$this->load->model('Modelmarket');
			$this->load->model('Modeltblselection');
			$this->load->model('Modeleventlst');
			$this->load->model('Modeltblbets');
			
        	$results = $this->Modelmarket->resultDeclareMarketId();

        	if(!empty($results)){
        		$marketIds = array();
	        	foreach($results as $result){
	        		$marketIds[] = $result['marketId'];
	        	}
	        	$marketStr = implode(',', $marketIds);
	        	$resultUrl = BR_LIVE_RESULT_URL.'market_id='.$marketStr;
	        //	echo $resultUrl;die;
	        	$resultJson = $this->httpGet($resultUrl);
	        //	echo $resultJson;
	        	$resultArr = json_decode($resultJson,true);

	        	$filterResult = array();
	        	if(!empty($resultArr[0]['result'])){
	        		$finalResult = $resultArr[0]['result'];
	        		foreach($finalResult as $fResult){
	        			if($fResult['status'] == 'CLOSED'){
	        				$temp = array();
	        				
	        				if(!empty($fResult['runners'])){
	        					foreach($fResult['runners'] as $runners){
	        						if($runners['status'] == 'WINNER'){
	        							$temp['selectionId'] = $runners['selectionId'];
	        						}		
	        					}
	        					if(!empty($temp['selectionId'])){
	        						$temp['marketId'] = $fResult['marketId'];
	        						$filterResult[] = $temp;
	        					}
	        				}
	        			}
	        		}
	        	}

	        //	print_r($filterResult);die;

	        	foreach($filterResult as $data){
					$marketId = $data['marketId'];
					$selectionId = $data['selectionId'];
					$marketData = $this->Modelmarket->findByMarketId($marketId);
					$selectionName = $this->Modeltblselection->findBySelectionName($selectionId);

					$marketData = array_merge($marketData,array('market_id'=>$marketId,'selectionId'=>$selectionId,'selectionName'=>$selectionName,'isFancy'=>1,'result'=>1));
					$matchId = $marketData['Match_id'];

					$condition = $this->Modeleventlst->SetMatchResult($marketData);

					if ($condition[0]['resultV']==0) {
						$this->Modeltblbets->updateUserBalByMatch($matchId,$marketId);
					}
				}  
        	}
		}	

		function chkLoginUser(){

            try {
                $this->db->delete('ci_sessions', array('timestamp <' => (now() - 1800)));
            } catch (Exception $e) {
            }

			$this->load->model('Modelcreatemaster');
			$this->load->model('Modeltblconfig');
			$this->load->model('Modeluserlogged');
			

            $user_data=$this->Modelchkuser->chkAuthName();

        /*    print_r($user_data);

            die(); */

			if ($user_data['iType']==0) {

				if($user_data['mstrid']==1){
				//	$this->declareResultApi();
				/*	$getToken=$this->getACookie();
					if(strlen($getToken) < 60){
						$this->Modelcreatemaster->saveBetfairToken($getToken);
					}else{
						$getToken = $this->Modelcreatemaster->findBetfairToken();	
					} */
				}else{
				//	$getToken = $this->Modelcreatemaster->findBetfairToken();
				} 

				$SessionTime = time() + CONFIG_LOGIN_TIME_OUT;
				$this->session->set_userdata('session_time_out', $SessionTime); 

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

				$data['last_login_time'] = $this->Modeluserlogged->UserLastLogin($user_data['mstrid']);

				$data['mstrpassword'] = $user_data['mstrpassword'];

				$terms = $this->Modeltblconfig->find();
				
				$data['terms_conditions'] = $terms[0]['terms_conditions'];

				$data['config_unmatched'] = CONFIG_UNMATCHED;

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

		   $currentTime = time();
		   $SessionTime = time() + CONFIG_LOGIN_TIME_OUT;

		   $checkSessionTime = @$this->session->userdata('session_time_out');

		/*   echo $currentTime;
		   print_r($_SESSION);

		   echo 'diff'.$checkSessionTime - $currentTime; */

		   if(!empty($user)){ 

				if($checkSessionTime < $currentTime){
					$data['is_login'] = false;

			   		$data['data'] = array();

					$data['status'] = 'Invalid User Name Or Password';

					//echo json_encode($data);

			       	return $this->output->set_content_type('application/json')->set_output(json_encode($data));

			      	//return false;
		      	
				}else{

					$this->session->set_userdata('session_time_out', $SessionTime); 

			   		$data['is_login'] = true;

			   		$config_unmatched = CONFIG_UNMATCHED;

			   		$sessionData = array('type'=> $this->session->userdata('type'),'user_name'=>$this->session->userdata('user_name'),'user_id'=>$this->session->userdata('user_id'),'mstrpassword'=>$this->session->userdata('mstrpassword'),'config_unmatched'=> $config_unmatched,'session_time_out'=> $SessionTime);

			   		$data['data'] = $sessionData;

					$data['status'] = 'Valid User Name Or Password';

					//echo json_encode($data);

			   		/*$data['data'] = 1;

					$data['status'] = 'valid';

					echo json_encode($data);*/

			        return $this->output->set_content_type('application/json')->set_output(json_encode($data));

				}

				//return true;

		   }else {

		   		$data['is_login'] = false;

		   		$data['data'] = array();

				$data['status'] = 'Invalid User Name Or Password';

				//echo json_encode($data);

		       	return $this->output->set_content_type('application/json')->set_output(json_encode($data));

		      	//return false;
		   }

	}
        function clearSessionData(){
            try {
                $this->db->delete('ci_sessions', array('timestamp <' => (now() - 132800)));
            } catch (Exception $e) {
            }
        }
		

	function chkLoginStatus($userId){ 
            $data['status'] = $this->Modelchkuser->chkLoginStatus($userId); 
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}

}