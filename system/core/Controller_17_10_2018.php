<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');

	//	$this->savelog();
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 * [savelog save log in file]
	 * @param  [array] $response [response in json array]
	 * @return [BOOLEAN]           [description]
	 */
	function savelog($response=NULL){

			$filename = 'logs.txt';

			$url = (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');

			$urlArr = explode('/', $url);

			$search = $urlArr[0].'/'.$urlArr[1];			

			$searchthis = $search;
			$matches = array();

			$handle = @fopen($filename, "r");
			if ($handle)
			{
			    while (!feof($handle))
			    {
			        $buffer = fgets($handle);
			        if(strpos($buffer, $searchthis) !== FALSE)
			            $matches[] = $buffer;
			    }
			}
			fclose($handle);

			if(empty($matches) && $url != ''){
			
				if(!empty($_POST)){
					$method = 'POST';
					$params = json_encode($_POST);
				}else{
					$method = 'GET';
					$params = '';
				}

		    /*  $post = "";
		        $post .= json_encode($_POST);
		        $post .= "\n RESPONSE";
		        $post .= "\n";
		        $post .= json_encode($response); */

		        $msg = ' Url    : ' . $url . PHP_EOL .' Method : ' . $method . PHP_EOL . ' Params : ' . $params . PHP_EOL ;

		        if(!empty($response)){
		        	$res = json_encode($response);
		        	$msg .= ' Response :' . $res . PHP_EOL;
		        }

		      //  echo $msg;die;

				$myfile = fopen($filename, "a") or die("Unable to open file!");
		        $txt =  $msg . PHP_EOL ;
		        fwrite($myfile, $txt);
		        fclose($myfile);

			}
	        
	        return false;
	}	

	function getACookie(){

		$loginEndpoint= "https://identitysso.betfair.com/api/login";
		$cookie = "";
						//$username = "handicrunch@gmail.com";
						//$password = "Sumer@000";
						//$username = "halepoto";
						//$password = "inayatshah12";
						//$username = "inayatshah82";
						 //$password = "Shahjee12";
		$username = BETFAIR_USERNAME;
		$password = BETFAIR_PASSWORD;
		$login = "true";
		$redirectmethod = "POST";
		$product = "home.betfair.int";
		$url = "https://www.betfair.com/";

		$fields = array('username' => urlencode($username),'password' => urlencode($password),'login' => urlencode($login),'redirectmethod' => urlencode($redirectmethod),'product' => urlencode($product),'url' => urlencode($url));
						//open connection
		$ch = curl_init($loginEndpoint);
						//url-ify the data for the POST
		$counter = 0;
		$fields_string = "&";

		foreach($fields as $key=>$value){ 
			if ($counter > 0){
				$fields_string .= '&';
			}
			$fields_string .= $key.'='.$value; 
			$counter++;
		}
		rtrim($fields_string,'&');

		curl_setopt($ch, CURLOPT_URL, $loginEndpoint);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);

		curl_setopt($ch, CURLOPT_HEADER, true);  // DO  RETURN HTTP HEADERS

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // DO RETURN THE CONTENTS OF THE CALL

		//execute post

		//print_r($ch);

		$result = curl_exec($ch);

		//echo $result;

		if($result == false){

			echo "API RESPONSE FAILED...";

                //die();

			echo 'Curl error: ' . curl_error($ch);

		}else{

			$temp = explode(";", $result);

			$result = $temp[0];

			$end = strlen($result);

			$start = strpos($result, 'ssoid=');

			$start = $start + 6;

			$cookie = substr($result, $start, $end);

		}

		curl_close($ch);

		return $cookie;

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

				$checkUser = $this->Modelcreatemaster->checkAuthUser($userName,$password);
				if(!empty($checkUser['mstrid'])){   
					$this->load->model('Modelchkuser');
					$user_data=$this->Modelchkuser->logoutentry($checkUser['mstrid']);
					$this->session->sess_destroy();
				}

				$response = array();
				$response["code"] = 1;
    			$response["error"] = true;
    			$response["message"] = "unauthorized access";
				$this->output->set_status_header(412)->set_content_type('application/json')->set_output(json_encode($response));
				exit();
			}else{
				$this->globalUserId = $checkUser['mstrid'];
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

	function sportsApingRequest($appKey, $sessionToken, $operation, $params){
        

	/*	    $ch = curl_init();

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 	

		    curl_setopt($ch, CURLOPT_URL, "https://api.betfair.com/exchange/betting/rest/v1/$operation/");

		    curl_setopt($ch, CURLOPT_POST, 1);

		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		    curl_setopt($ch, CURLOPT_HTTPHEADER, array(

		        'X-Application: ' . $appKey,

		        'X-Authentication: ' . $sessionToken,

		        'Accept: application/json',

		        'Content-Type: application/json'

		    ));

        

		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		    $response = curl_exec($ch);

		    curl_close($ch);

		    return $response; */

	//	    $response = $this->httpGet('http://173.236.80.221/apidata_curl2.php?marketid=1.144476996');

		/*    $temp = array(array('marketName'=>'Match Odds','marketId'=>1.145377680,'status'=>'OPEN'));
			$response = json_encode($temp); */
			$temp = array();
			$response = json_encode($temp);
		    return $response;
		}


		/**
		 * [getMarketBookUser user dashboard bets]
		 * @param  [string] $appKey       [api key]
		 * @param  [string] $sessionToken [session token]
		 * @param  [int] $marketId     [market id]
		 * @return [json]               [response]
		 */
		function getMarketBookUser($appKey, $sessionToken, $marketId){

			$marketArr = explode(',', $marketId);
			$marketIdSlice = array_slice($marketArr, 0, 40);
			$marketIds = implode(',', $marketIdSlice);

		    $params = '{"marketIds":[' . $marketIds . '], "priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

		    $jsonResponse = $this->sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

		    return $jsonResponse;

		}

		/**
		 * [updateAllUserBalLiablity update all user balancy]
		 * @return 
		 */
		function updateAllUserBalLiablity(){
			$this->load->model('Modelcreatemaster');
			$this->db->select('mstrid as usecode');
			$this->db->from('createmaster');
			$query = $this->db->get();
			$users = $query->result_array();
			foreach($users as $user){
				$this->Modelcreatemaster->updateUserBalLiablity($user['usecode']);
			}
		}

		/**
		 * [httpGet http get request]
		 * @param  [sting] $url [http url]
		 * @return [json]      [response]
		 */
		function httpGet($url){
		    $ch = curl_init();  

		    curl_setopt($ch,CURLOPT_URL,$url);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			//  curl_setopt($ch,CURLOPT_HEADER, false); 
		    $output=curl_exec($ch);

		/*    echo $url;
		    echo $output;
		    die; */
		    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    if($httpcode!=200){
		    	$output = json_encode(array());
		    }

		    curl_close($ch);
		    return $output;
		}


		/**
		 * [httpGet http get request]
		 * @param  [sting] $url [http url]
		 * @return [json]      [response]
		 */
		function httpGetArr($url){
		    $ch = curl_init();  

		    curl_setopt($ch,CURLOPT_URL,$url);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			//  curl_setopt($ch,CURLOPT_HEADER, false); 
		    $output=curl_exec($ch);

		/*    echo $url;
		    echo $output;
		    die; */
		    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    if($httpcode!=200){
		    	$output = json_encode(array());
		    }

			$outputArr = json_decode($output,true);		    

		    curl_close($ch);
		    return $outputArr;
		}



		/**
		 * [httpPost http post request]
		 * @param  [sting] $url    [http url]
		 * @param  [array] $params [post params]
		 * @return [json]         [response]
		 */
		function httpPost($url,$params){
		    $postData = '';
			//create name value pairs seperated by &
		    foreach($params as $k => $v) 
		    { 
		        $postData .= $k . '='.$v.'&'; 
		    }
		    $postData = rtrim($postData, '&');

		    $ch = curl_init();  

		    curl_setopt($ch,CURLOPT_URL,$url);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($ch,CURLOPT_HEADER, false); 
		    curl_setopt($ch, CURLOPT_POST, count($postData));
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

		    $output=curl_exec($ch);

		    curl_close($ch);
		    return $output;
		}


		function getIndFancyAdmin($marketId=0){
			$url = BR_LIVE_SESSION_URL.$marketId;
			$result = $this->httpGet($url);

			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = 'Session fancy listing';


            $resultArray = json_decode($result,true);
            $indianSessions  = $resultArray['result'];
            try {
                array_shift($indianSessions);
            } catch (Exception $e) {
            }

            $responceTemp = [] ;
            if(!empty($indianSessions)){

                foreach ($indianSessions as $indianSession){
                	if($indianSession['btype']=='LINE'){
                		$runners = $indianSession['runners'][0];
                    	$responceTemp[]=["BackPrice1"=>isset($runners['back'][0]['price'])?$runners['back'][0]['price'] : null,"BackSize1"=>isset($runners['back'][0]['size'])?$runners['back'][0]['size']:null,"GameStatus"=>$indianSession['status'],"LayPrice1"=>isset($runners['lay'][0]['price'])? $runners['lay'][0]['price']:null,"LaySize1"=>isset($runners['lay'][0]['size'])?$runners['lay'][0]['size']:null,"RunnerName"=>$indianSession['name'],"SelectionId"=>$runners['id']];
                	}
                }

            }
            $response["data"]['session'] = $responceTemp;
			return $response;
		}

		function getIndFancyByMatchId($matchId=0){
			$url = BR_SUPER_AMDIN_URL.'indian_session_fancy/'.$matchId;
			return $this->httpGet($url);
		}

		function getSuperAdminFancyOdds($marketId){
			$url = BR_LIVE_SESSION_URL.$marketId;
			return $this->httpGet($url);
		}		

		function getMatchOdds($marketId){
			$url = BR_LIVE_ODDS_URL.'market_id='.$marketId;
			$result = $this->httpGet($url);
			$jsonResp = json_decode($result,true);
			return $jsonResp;
		}	
		
		function getIndianSessionOdds($marketId){

		//	$marketId = '1.145377680';
			$url = BR_LIVE_SESSION_URL.$marketId;
			return $this->httpGet($url);

			/*
			$backRun = rand(50,400);
			$layRun = $backRun + 3;

			$backPercentage = rand(10,95);
			$layPercentage = $backPercentage + 5;

			$gameStatus = rand(1,8);
			if($gameStatus == 1){
				$game1 = 'Ball Running';
				$game2 = '';
				$game3 = '';
			}elseif($gameStatus == 2){
				$game1 = '';
				$game2 = 'Ball Running';
				$game3 = '';

			}elseif($gameStatus == 3){
				$game1 = '';
				$game2 = '';
				$game3 = 'Ball Running';
			}else{
				$game1 = '';
				$game2 = '';
				$game3 = '';
			}

			$sessFancy = array('session'=>array('0'=>array('SelectionId'=>1,'RunnerName'=>'FALL OF 2ND WKT HAMP','LayPrice1'=>$layRun,'LaySize1'=>$layPercentage,'BackPrice1'=>$backRun,'BackSize1'=>$backPercentage,'GameStatus'=>$game1,'FinalStatus'=>'OPEN'),'1'=>array('SelectionId'=>2,'RunnerName'=>'S NORTHEAST RUN','LayPrice1'=>$layRun - 5,'LaySize1'=>$layPercentage - 1,'BackPrice1'=>$backRun + 5,'BackSize1'=>$backPercentage + 1,'GameStatus'=>$game2,'FinalStatus'=>'OPEN'),'2'=>array('SelectionId'=>3,'RunnerName'=>'R ROSSOUW RUN (KENT VS HAMP)ADV','LayPrice1'=>$layRun - 10,'LaySize1'=> $layPercentage - 2 ,'BackPrice1'=>$backRun +10 ,'BackSize1'=>$backPercentage + 2,'GameStatus'=>'','FinalStatus'=>'OPEN'),'3'=>array('SelectionId'=>4,'RunnerName'=>'35 OVER RUN HAMP','LayPrice1'=>$layRun - 15,'LaySize1'=> $layPercentage - 3,'BackPrice1'=>$backRun + 15,'BackSize1'=>$backPercentage + 3,'GameStatus'=>$game3,'FinalStatus'=>'OPEN'),'4'=>array('SelectionId'=>456,'RunnerName'=>'1 OVER Match Run','LayPrice1'=>$layRun - 15,'LaySize1'=> $layPercentage - 3,'BackPrice1'=>$backRun + 15,'BackSize1'=>$backPercentage + 3,'GameStatus'=>$game3,'FinalStatus'=>'OPEN'),'5'=>array('SelectionId'=>454,'RunnerName'=>'10 OVER Match Run','LayPrice1'=>$layRun - 15,'LaySize1'=> $layPercentage - 3,'BackPrice1'=>$backRun + 15,'BackSize1'=>$backPercentage + 3,'GameStatus'=>$game3,'FinalStatus'=>'OPEN'),'6'=>array('SelectionId'=>457,'RunnerName'=>'15 OVER Match Run','LayPrice1'=>$layRun - 15,'LaySize1'=> $layPercentage - 3,'BackPrice1'=>$backRun + 15,'BackSize1'=>$backPercentage + 3,'GameStatus'=>$game3,'FinalStatus'=>'OPEN')));

			return json_encode($sessFancy); */
		}

		function getMultiMarkets($marketIds=NULL){
			$url = 'https://www.betfair.com/www/sports/exchange/readonly/v1/bymarket?alt=json&currencyCode=USD&locale=en&rollupLimit=25&rollupModel=STAKE&types=MARKET_STATE,RUNNER_STATE,RUNNER_EXCHANGE_PRICES_BEST,RUNNER_DESCRIPTION&marketIds='.$marketIds;

 			$ch = curl_init();  
		    curl_setopt($ch,CURLOPT_URL,$url);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			//  curl_setopt($ch,CURLOPT_HEADER, false); 
		    $output=curl_exec($ch);
		    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	//	    echo $output;die;

		    if($httpcode!=200){
		    	$marketArr = array();
		    }else{
		    	$marketArr = json_decode($output,true); 
		    }
		    curl_close($ch); 

		/*    $output = '{"currencyCode":"USD","eventTypes":[{"eventTypeId":1,"eventNodes":[{"eventId":28796965,"marketNodes":[{"marketId":"1.145405353","isMarketDataDelayed":true,"state":{"betDelay":0,"bspReconciled":false,"complete":true,"inplay":false,"numberOfWinners":1,"numberOfRunners":3,"numberOfActiveRunners":3,"lastMatchTime":"2018-07-15T08:06:14.376Z","totalMatched":16.931618374558305,"totalAvailable":1080.5375355099204,"crossMatching":true,"runnersVoidable":false,"version":2287979488,"status":"OPEN"},"runners":[{"selectionId":5965613,"handicap":0.0,"description":{"runnerName":"Vereya"},"state":{"sortPriority":1,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":2.36,"size":40.32},{"price":1.3,"size":51.1}],"availableToLay":[{"price":4.9,"size":18.63}]}},{"selectionId":11801202,"handicap":0.0,"description":{"runnerName":"Septemvri"},"state":{"sortPriority":2,"lastPriceTraded":2.34,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":2.06,"size":25.31},{"price":2.02,"size":25.82},{"price":1.3,"size":45.78}],"availableToLay":[{"price":2.96,"size":31.29}]}},{"selectionId":58805,"handicap":0.0,"description":{"runnerName":"The Draw"},"state":{"sortPriority":3,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":3.2,"size":27.96},{"price":1.01,"size":69.61}],"availableToLay":[{"price":4.3,"size":25.97},{"price":950.0,"size":10.51}]}}],"isMarketDataVirtual":true}]},{"eventId":28796966,"marketNodes":[{"marketId":"1.145405444","isMarketDataDelayed":true,"state":{"betDelay":0,"bspReconciled":false,"complete":true,"inplay":false,"numberOfWinners":1,"numberOfRunners":3,"numberOfActiveRunners":3,"totalMatched":0.0,"totalAvailable":1100.6583477815748,"crossMatching":true,"runnersVoidable":false,"version":2287979496,"status":"OPEN"},"runners":[{"selectionId":46982,"handicap":0.0,"description":{"runnerName":"CSKA Sofia"},"state":{"sortPriority":1,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":1.28,"size":125.09},{"price":1.01,"size":359.51}],"availableToLay":[{"price":3.8,"size":45.02}]}},{"selectionId":878125,"handicap":0.0,"description":{"runnerName":"Lokomotiv Plovdiv"},"state":{"sortPriority":2,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":1.7,"size":65.26},{"price":1.01,"size":351.9}],"availableToLay":[{"price":27.0,"size":11.94}]}},{"selectionId":58805,"handicap":0.0,"description":{"runnerName":"The Draw"},"state":{"sortPriority":3,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":1.5,"size":65.93},{"price":1.01,"size":44.83}],"availableToLay":[{"price":950.0,"size":31.02}]}}],"isMarketDataVirtual":true}]}]},{"eventTypeId":4,"eventNodes":[{"eventId":28755539,"marketNodes":[{"marketId":"1.144476996","isMarketDataDelayed":true,"state":{"betDelay":0,"bspReconciled":false,"complete":true,"inplay":false,"numberOfWinners":1,"numberOfRunners":3,"numberOfActiveRunners":3,"lastMatchTime":"2018-07-16T06:19:23.017Z","totalMatched":39216.40489055046,"totalAvailable":53055.2314642497,"crossMatching":true,"runnersVoidable":false,"version":2251155984,"status":"OPEN"},"runners":[{"selectionId":10301,"handicap":0.0,"description":{"runnerName":"England"},"state":{"sortPriority":1,"lastPriceTraded":2.16,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":2.12,"size":586.37},{"price":2.08,"size":43.24},{"price":2.06,"size":157.46}],"availableToLay":[{"price":2.18,"size":197.61},{"price":2.2,"size":273.47},{"price":2.22,"size":66.24}]}},{"selectionId":414464,"handicap":0.0,"description":{"runnerName":"India"},"state":{"sortPriority":2,"lastPriceTraded":3.3,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":3.2,"size":1218.03},{"price":3.0,"size":29.83},{"price":2.96,"size":55.45}],"availableToLay":[{"price":3.3,"size":129.72},{"price":3.35,"size":182.18},{"price":3.4,"size":277.62}]}},{"selectionId":60443,"handicap":0.0,"description":{"runnerName":"The Draw"},"state":{"sortPriority":3,"lastPriceTraded":4.6,"totalMatched":0.0,"status":"ACTIVE"},"exchange":{"availableToBack":[{"price":4.4,"size":91.41},{"price":4.3,"size":139.91},{"price":4.2,"size":139.91}],"availableToLay":[{"price":4.7,"size":427.77},{"price":4.8,"size":662.4}]}}],"isMarketDataVirtual":true}]}]}],"indicative":false}';

		    $marketArr = json_decode($output,true); */
			
		/*	$markets = array();
			foreach($marketArr['eventTypes'] as $eventTypes){
				foreach($eventTypes['eventNodes'] as $eventNodes){
						foreach($eventNodes['marketNodes'] as $marketNodes){
							$temp = array('marketId'=>$marketNodes['marketId'],'status'=>'OPEN','totalMatched'=>$marketNodes['state']['totalMatched']);
							$tempEx = array();
							foreach($marketNodes['runners'] as $runners){
								$tempEx[] = array('ex'=>$runners['exchange']);
							}
							$temp['runners'] = $tempEx;
							$markets[] = $temp;
						}
				}
			} */
 

			return $marketArr;
			
		}	

		function multiple_threads_request($marketIds){ 

				foreach($marketIds as $market){
					$nodes[] = 'http://173.236.80.221/apidata_curl.php?marketid='.$market;
				}

		        $mh = curl_multi_init(); 
		        $curl_array = array(); 
		        foreach($nodes as $i => $url) 
		        { 
		            $curl_array[$i] = curl_init($url); 
		            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true); 
		            curl_multi_add_handle($mh, $curl_array[$i]); 
		        } 
		        $running = NULL; 
		        do { 
		            usleep(10000); 
		            curl_multi_exec($mh,$running); 
		        } while($running > 0); 
		        
		        $res = array(); 
		        foreach($nodes as $i => $url) 
		        { 
		            $res[$url] = curl_multi_getcontent($curl_array[$i]); 
		        } 
		        
		        foreach($nodes as $i => $url){ 
		            curl_multi_remove_handle($mh, $curl_array[$i]); 
		        } 
		        curl_multi_close($mh);        
		        return $res; 
		} 

		/**
		 * Verifying required params posted or not
		 */
		function verifyRequiredParams($postData,$required_fields) {
			$error = false;
			$error_fields = "";
			$request_params = $postData;

			foreach ($required_fields as $field) {
				if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
					$error = true;
					$error_fields .= $field . ', ';
				}
			}

		/*	print_r($postData);
			print_r($required_fields);
			var_dump($error);
			echo $error_fields;
			die; */

			if ($error) {
				$response = array();
				$response["code"] = ERROR_PARAM_REQUIRED;
				$response["error"] = true;
				$response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
				echo json_encode($response);die;
			/*	$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
				print_r($response);
				exit; */
			}
		}

		function updateMatchRunners($matchId=NULL){

			$this->load->model('Modelmatchmst');

			$cricketUrl = BR_LIVE_CRICKET_SOCKET_URL;	
			$cricketJson = $this->httpGet($cricketUrl);
			$cricketArr = json_decode($cricketJson,true);

			$runners = array();
			
			if(!empty($cricketArr['result'])){
				foreach($cricketArr['result'] as $key => $cArr){
					if($cArr['mtype']=='MATCH_ODDS'){
						$runners[$cArr['groupById']] = $cArr['runners'];	
					}
				}
			}

			$tennisUrl = BR_LIVE_TENNIS_SOCKET_URL;	
			$tennisJson = $this->httpGet($tennisUrl);

			$tennisArr = json_decode($tennisJson,true);

			if(!empty($tennisArr['result'])){
				foreach($tennisArr['result'] as $key => $cArr){
					if($cArr['mtype']=='MATCH_ODDS'){
						$runners[$cArr['groupById']] = $cArr['runners'];
					}	
				}
			} 

			$soccerUrl = BR_LIVE_SOCCER_SOCKET_URL;	
			$soccerJson = $this->httpGet($soccerUrl);
			$soccerArr = json_decode($soccerJson,true);
			if(!empty($soccerArr['result'])){
				foreach($soccerArr['result'] as $key => $cArr){
					if($cArr['mtype']=='MATCH_ODDS'){
						$runners[$cArr['groupById']] = $cArr['runners'];
					}	
				}
			}

			$allMatchArr = $this->Modelmatchmst->findActiveMatches($matchId);

			if(!empty($allMatchArr)){

				foreach($allMatchArr as $matchArr){

					$matchId = $matchArr['MstCode'];

					if($matchArr['SportID']==4){ 

						if(!empty($runners[$matchId])){
							$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners[$matchId])));
						}else{ 
							$this->Modelmatchmst->updateDefaultRunner($matchId);
						}

					}elseif($matchArr['SportID']==2){

						if(!empty($runners[$matchId])){
							$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners[$matchId])));
						}else{ 
							$this->Modelmatchmst->updateDefaultRunner($matchId);
						}

					}elseif($matchArr['SportID']==1){

						if(!empty($runners[$matchId])){
							$this->Modelmatchmst->update($matchId,array('runner_json'=>json_encode($runners[$matchId])));
						}else{ 
							$this->Modelmatchmst->updateDefaultRunner($matchId);
						}
					}

				}
			}
			return false;
		}

		/**
		 * [pr format data]
		 * @param  [array] $data [data]
		 * @return [array]       [format data]
		 */
		function pr($data){
			echo '<pre>';
			print_r($data);
			die;
		}

		/**
		 * [updateMarketRunners update market runners]
		 * @param  [int] $marketId [market id]
		 * @return [boolean]           [void]
		 */
		function updateMarketRunners($marketId=NULL){

			$this->load->model('Modelmarket');
			$marketUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$marketId;
			$marketJson = $this->httpGet($marketUrl);
			$marketArr = json_decode($marketJson,true);

			foreach($marketArr as $mArr){
				if(!empty($mArr['runners'])){
					$marketId = $mArr['id'];
					$this->Modelmarket->update($marketId,array('market_runner_json'=>json_encode($mArr['runners'])));
				}
			}
			return false;
		}	
}