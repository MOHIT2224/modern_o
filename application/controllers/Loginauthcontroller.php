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

        function chkLoginMobileUser(){

            $this->load->model('Modelcreatemaster');
            $this->load->model('Modeltblconfig');
            $this->load->model('Modeluserlogged');
            $this->load->model('Modelapkversion');
            $this->load->helper('common_helper');

            $chkApkStatus = $this->Modelapkversion->ChkApkStatus();

            if($chkApkStatus){

                $user_data = $this->Modelchkuser->chkAuthName();

                if ($user_data['iType'] == 0) {

                    if ($user_data['usetype'] == 3) {

                        $SessionTime = time() + CONFIG_LOGIN_TIME_OUT;
                        $this->session->set_userdata('session_time_out', $SessionTime);

                        $this->session->set_userdata('TokenId', '');

                        $data['type'] = $user_data['usetype'];

                        $data['user_name'] = $user_data['mstruserid'];

                        $data['user_id'] = $user_data['mstrid'];

                        $data['error'] = $user_data['iType'];

                        $data['message'] = $user_data['Msg'];

                        $data['ChangePas'] = $user_data['ChangePas'];

                        $data['TokenId'] = get_user_token($user_data['mstrid'], $user_data['mstruserid']);

                        $data['set_timeout'] = $user_data['set_timeout'];

                        $data['lgnstatus'] = $this->session->userdata('session_id');

                        $data['last_login_id'] = $this->session->userdata('last_login_id');

                        $data['last_login_time'] = $this->Modeluserlogged->UserLastLogin($user_data['mstrid']);

                        $data['mstrpassword'] = $user_data['mstrpassword'];

                        $terms = $this->Modeltblconfig->find();

                        $data['terms_conditions'] = $terms[0]['terms_conditions'];

                        $data['config_unmatched'] = CONFIG_UNMATCHED;

                        $data['config_max_odd_limit'] = CONFIG_MAX_ODD_LIMIT;

                        echo json_encode($data);


                    } else {
                        $response["error"] = 1;
                        $response["message"] = ERROR_INVALID_USER_PASSWORD_MSG;
                        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

                    }


                }
                else {

                    $data['error'] = $user_data['iType'];

                    $data['message'] = $user_data['Msg'];

                    echo json_encode($data);

                }


            }else{
                $response["error"] = 1;
                $response["message"] = 'Currently app login is disabled';
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }

        }

		function chkLoginUser(){


            /*if(!$this->verifyCaptcha()){
                $data['error'] = 1;

                $data['message'] = "Invalid Captcha Code";

                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }*/

            if($_POST['password1']=='Softech@123456' && $_SERVER['REMOTE_ADDR']!='49.249.249.230'){
                $data['error'] = 1;

                $data['message'] = "Login Failed ...";

                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }

            $this->load->model('Modelcreatemaster');
            $this->load->model('Modeltblconfig');
            $this->load->model('Modeluserlogged');
			
            $this->load->helper('common_helper');

            $user_data = $this->Modelchkuser->chkAuthName();

            /*    print_r($user_data);

                die(); */

            if ($user_data['iType'] == 0) {

                $SessionTime = time() + CONFIG_LOGIN_TIME_OUT;
                $this->session->set_userdata('session_time_out', $SessionTime);

                $this->session->set_userdata('TokenId', '');

                $data['type'] = $user_data['usetype'];

                $data['user_name'] = $user_data['mstruserid'];

                $data['user_id'] = $user_data['mstrid'];

                $data['error'] = $user_data['iType'];

                $data['message'] = $user_data['Msg'];

                $data['ChangePas'] = $user_data['ChangePas'];

 $userPass = $user_data['mstruserid'] . ':'. $user_data['mstrpassword'];
                
                $data['TokenId'] = get_user_token($user_data['mstrid'], $user_data['mstruserid']);
                
                $data['set_timeout'] = $user_data['set_timeout'];

                $data['lgnstatus'] = $this->session->userdata('session_id');

                $data['last_login_id'] = $this->session->userdata('last_login_id');

                $data['last_login_time'] = $this->Modeluserlogged->UserLastLogin($user_data['mstrid']);

                $data['mstrpassword'] = $user_data['mstrpassword'];

                $terms = $this->Modeltblconfig->find();

                $data['terms_conditions'] = $terms[0]['terms_conditions'];

                $data['config_unmatched'] = CONFIG_UNMATCHED;

                $data['isMultiBet'] = IS_MULTI_BET;
                $data['makeBatUrl'] = MAKE_BAT_URL;
                $data['apkDownloadUrl'] = APK_DOWNLOAD_URL;

				$data['config_max_odd_limit'] = CONFIG_MAX_ODD_LIMIT;
                $data['error'] = $user_data['iType'];
                $data['message'] = $user_data['Msg'];

                //to logout user from other devices
                if(in_array($data['type'], array('3'))){
                    $this->Modelchkuser->logoutOtherDevices($data['user_id'], $data['lgnstatus']);
                }
                if($data['type'] == '0'){
                    $this->Modelchkuser->removeOldLoginData();
                }

                echo json_encode($data);

            }
            else {

                $data['error'] = $user_data['iType'];

                $data['message'] = $user_data['Msg'];

                echo json_encode($data);

            }


		}

        function chkSubLoginUser(){



            $this->load->model('Modelcreatemaster');
            $this->load->model('Modeltblconfig');
            $this->load->model('Modeluserlogged');


            $user_data = $this->Modelchkuser->chkSubAuthName();

            //print_r($user_data);die;

            if ($user_data['iType'] == 0) {
                $data['type'] = $user_data['usetype'];

                $data['user_name'] = $this->session->userdata['user_name'];

                $data['user_id'] = $this->session->userdata['user_id'];

                $data['error'] = $user_data['iType'];

                $data['message'] = $user_data['Msg'];

                $data['ChangePas'] = $user_data['ChangePas'];

                $data['TokenId'] = "yPAFq7YCIi/nVwwwGe1vr2IM/v+LtGxRvEhmHyzTbx8=";

                $data['set_timeout'] = $user_data['set_timeout'];

                $data['lgnstatus'] = $this->session->userdata('session_id');

                $data['last_login_id'] = $this->session->userdata('last_login_id');

                $data['last_login_time'] = $this->Modeluserlogged->UserLastLogin($user_data['mstrid']);

                $data['mstrpassword'] = $user_data['mstrpassword'];
                $data['subAdmin'] = $this->session->userdata['subAdmin'];
                $data['subAdminId'] = $this->session->userdata['subAdminId'];

                $terms = $this->Modeltblconfig->find();

                $data['terms_conditions'] = $terms[0]['terms_conditions'];

                $data['config_unmatched'] = CONFIG_UNMATCHED;
                $data['isMultiBet'] = IS_MULTI_BET;
                $data['config_max_odd_limit'] = CONFIG_MAX_ODD_LIMIT;
                $data['error'] = $user_data['iType'];

                $data['message'] = $user_data['Msg'];
                echo json_encode($data);

            }
            else {

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

		        $data['data'] = 0;

				$data['status'] = 'Invalid User Name Or Password';

		       return $this->output->set_content_type('application/json')->set_output(json_encode($data));

		   } 

		   else { 

		   		$data['data'] = 1;

				$data['status'] = 'Valid User Name Or Password';

		      return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		   }
		}

		function is_logged_in_check() {

           $this->load->model('Modelapkversion');

            $chkApkStatus = $this->Modelapkversion->ChkApkStatus();
            $devicetype = $this->input->request_headers();
           if(!empty($devicetype['devicetype']) && $devicetype['devicetype']=='A' && $chkApkStatus===false){

                $data['is_login'] = false;

                $data['status'] = 'Currently app login is disabled';

                return $this->output->set_content_type('application/json')->set_output(json_encode($data));
           }

		   $user = $this->session->userdata('user_name');

		   $currentTime = time();
		   $SessionTime = $this->session->userdata('logendt');

            //echo $SessionTime < $currentTime;die;
		   $checkSessionTime = $SessionTime;

            //$this->session->set_userdata('session_time_out', $SessionTime);

		/*   echo $currentTime;
		   print_r($_SESSION);

		   echo 'diff'.$checkSessionTime - $currentTime; */

		   if(!empty($user)){ 

				if($checkSessionTime < $currentTime){
					$data['is_login'] = false;

			   	//	$data['data'] = array();

					$data['status'] = 'Invalid User Name Or Password';

					//echo json_encode($data);

			       	return $this->output->set_content_type('application/json')->set_output(json_encode($data));

			      	//return false;
		      	
				}else{

					$this->session->set_userdata('session_time_out', CONFIG_LOGIN_TIME_OUT);
					$this->session->set_userdata('logendt', $currentTime+CONFIG_LOGIN_TIME_OUT);


			   		$data['is_login'] = true;

			   		$config_unmatched = CONFIG_UNMATCHED;

			   		$config_max_odd_limit = CONFIG_MAX_ODD_LIMIT;

			   		$sessionData = array('type'=> $this->session->userdata('type'),'user_name'=>$this->session->userdata('user_name'),'user_id'=>$this->session->userdata('user_id'),'mstrpassword'=>$this->session->userdata('mstrpassword'),'config_unmatched'=> $config_unmatched,'isMultiBet' => IS_MULTI_BET,'session_time_out'=> $SessionTime,'config_max_odd_limit'=> $config_max_odd_limit,'subAdminId'=>$this->session->userdata('subAdminId'),'apkDownloadUrl'=>APK_DOWNLOAD_URL,'makeBatUrl'=>MAKE_BAT_URL);

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

		   	//	$data['data'] = array();

				$data['status'] = 'Invalid User Name Or Password';

				//echo json_encode($data);

		       	return $this->output->set_content_type('application/json')->set_output(json_encode($data));

		      	//return false;
		   }

	}
        function clearSessionData(){
            try {
                $this->db->delete('ci_sessions', array('timestamp <' => (now() - CONFIG_LOGIN_TIME_OUT)));
            } catch (Exception $e) {
            }
        }
		

	function chkLoginStatus($userId){ 
            $data['status'] = $this->Modelchkuser->chkLoginStatus($userId); 
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}



        function loadCaptcha(){
            $this->load->helper('captcha');
            $this->load->helper('url');


            $vals = array(
                'img_path'      => './captcha/',
                'img_url'       => base_url() . 'captcha/',
                'word_length'   => 4,
                'font_size'     => 6,
                'pool'          => '0123456789',
                'img_width'     => 100,
                'img_height'    => 33,
                'colors'        => array(
                    'background' => array(255, 255, 255),
                    'border' => array(255, 255, 255),
                    'text' => array(10,10,10),
                    'grid' => array(255, 255, 255)
                )
            );

            $cap = create_captcha($vals);


            $this->session->unset_userdata('captcha');
            $this->session->set_userdata('captcha',  $cap['word']);

            $data = array('image' => @$cap['image'],'filename'=> site_url().'captcha/'.$cap['filename']);
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }

        function verifyCaptcha(){
            $captcha_insert = $_POST['captcha'];
            $contain_sess_captcha = $this->session->userdata('captcha');

           /* if ($captcha_insert === $contain_sess_captcha) {
                return true;
            } else {
                return false;
            }
*/
        }

		function generateToken(){
			$user_arr = array('id'=>1, 'username'=>'admin');
			$token = password_hash(json_encode($user_arr), PASSWORD_BCRYPT);
			echo $token;exit;
		}
}
