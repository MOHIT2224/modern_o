<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting("ERROR");
/**
* Check UserName and Password Of Login Page
*/
class Betentrymodel extends CI_Model
{
	function __construct()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

	/**
	 * [validateSaveBet valdiate save bet]
	 * @return [array] [response array]
	 * Note: isback=0 When team is backed isback=1 When team is layed
	 */
	function validateSaveBet(){

		$result = array();
		$isback = $this->input->post('isback');
		$profitAndLoss = $this->input->post('p_l');
		$loginId = $this->input->post('loginId');
		$stake = $this->input->post('stake'); 
		$inplay = $this->input->post('inplay'); 
		$priceVal = $this->input->post('priceVal'); 
		if($stake < 0 || $stake == 0){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Stack cannot be zero');
			return $result;	
		}
		if($priceVal >= 30){
			$result = array('code' => 9 ,'error'=>true,'message' => 'Bet not allowed');
			return $result;	
		}

		$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
		$userData = $Modelcreatemaster->viewUserAcData($loginId);
	//	print_r($userData);

        if (empty($userData)) {
            $result = array('code' => 9 ,'error'=>true,'message' => 'Something went wrong');
			return $result;	
        } elseif (isset($userData[0]['lgnusrCloseAc']) && $userData[0]['lgnusrCloseAc'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Account is Closed...');
        	return $result;	
        } elseif (isset($userData[0]['mstrlock']) && $userData[0]['mstrlock'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Account is InActive...');
        	return $result;	
        } elseif (isset($userData[0]['lgnusrlckbtng']) && $userData[0]['lgnusrlckbtng'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Betting is Locked...');
        	return $result;	
        }elseif (isset($userData[0]['stakeLimit']) && $userData[0]['stakeLimit'] != 0 && ($userData[0]['stakeLimit'] < $stake)) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Stake Limit is Over...');
        	return $result;	
        }elseif (isset($userData[0]['GoingInplayStakeLimit']) && $userData[0]['GoingInplayStakeLimit'] != 0 && $inplay == false &&  ($userData[0]['GoingInplayStakeLimit'] < $stake)) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Going Inplay Stake Limit is Over...');
        	return $result;	
        }

        if(isset($userData[0]['lgnUserMaxProfit'])){
        	$lgnUserMaxProfit = $userData[0]['lgnUserMaxProfit'];
			if($isback == 0 && ($lgnUserMaxProfit > 0) && ($profitAndLoss > $lgnUserMaxProfit)){
				$result = array('code'=>5,'error' => true ,'message' => 'Your max profit is over');	
				return $result;	
			}
        }
    /*  echo $lgnUserMaxProfit;
        echo ' P_L '.$profitAndLoss;
        die; */

		if(isset($userData[0]['lgnUserMaxLoss'])){
			$lgnUserMaxLoss = $userData[0]['lgnUserMaxLoss'];
			if($isback == 1 && ($lgnUserMaxLoss > 0) && ($profitAndLoss > $lgnUserMaxLoss)){
				$result = array('code'=>6,'error' => true,'message' => 'Your max loss is over');
				return $result;	
			}
		}
		
		return $result;

	}	

	function validateIndinaSessionSaveBet($data){
		
		$oddsNumbers = $data['OddsNumber'];
		settype($oddsNumbers, "integer");

		$result = array();
	
		if($oddsNumbers < 0 || $oddsNumbers == 0){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Stack cannot be zero');
			return $result;	
		}
		
		return $result;

	}	

	function validateSessionSaveBet($data,$redisArr){
		//print_r($data['OddsNumber']);die;
	//	print_r($data);die;
		$result = array();
	
		$stake = $data['betValue']; 
		$loginId = $data['loginId'];
		$fancyId = $data['FancyID'];
        $inplay = $data['inplay'];

		$Modelmatchfancy = $this->model_load_model('Modelmatchfancy');
		$fancyData = $Modelmatchfancy->getFancyById($fancyId);

		if($fancyData['fancy_mode']=='A'){
			if($data['ind_fancy_selection_id']){
				if($data['OddValue']==0){
					$sBackKey = $data['MarketId'].'_s'.$data['ind_fancy_selection_id'].'_back';
					$backStr = $redisArr[$sBackKey];
					$backArr = explode(',', $backStr);			
					if($backArr[0] == $data['OddsNumber']){

					}else{
						$result = array('code' => 9 ,'error'=>true,'message' => 'Runs changed');
						return $result;
					}
				}else{
					$sLayKey = $data['MarketId'].'_s'.$data['ind_fancy_selection_id'].'_lay';
					$layStr = $redisArr[$sLayKey];
					$layArr = explode(',', $layStr);
					if($layArr[0] == $data['OddsNumber']){

					}else{
						$result = array('code' => 9 ,'error'=>true,'message' => 'Runs changed');
						return $result;
					}
				}
			}
		}

		$errorMinStake = MIN_STAKE;
		
		if($stake < $errorMinStake){
			$minStakeMsg = 'Minimum stake is '.$errorMinStake;
			$result = array('code' => ERROR_MIN_STAKE ,'error'=>true,'message' => $minStakeMsg);
            return $result;
		}
	
		if($stake < 0 || $stake == 0){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Stack cannot be zero');
			return $result;	
		}


		if(!empty($fancyData['max_session_bet_liability']) && ($stake > $fancyData['max_session_bet_liability'])){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Max Session bet liablity is '.$fancyData['max_session_bet_liability']);
			return $result;	
		}

		if(!empty($fancyData['max_session_liability'])){
			$sessionLiablity = $this->sumSessionLiablity($loginId,$fancyId);
			$sumSessionLiablity = $sessionLiablity + $stake;
			if($sumSessionLiablity > $fancyData['max_session_liability']){
				$result = array('code' => 4 ,'error'=>true,'message' => 'Max Session liablity is '.$sessionLiablity);
				return $result;	
			}
			
		}


		$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
		$userData = $Modelcreatemaster->viewUserAcData($loginId);
	//	print_r($userData);

        if (empty($userData)) {
            $result = array('code' => 9 ,'error'=>true,'message' => 'Something went wrong');
			return $result;	
        } elseif (isset($userData[0]['lgnusrCloseAc']) && $userData[0]['lgnusrCloseAc'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Account is Closed...');
        	return $result;	
        }elseif ($data['OddsNumber']<=0 ) {
            $result = array('code' => 9 ,'error'=>true,'message' => 'run must be greater than 0');
            return $result;
        } elseif (isset($userData[0]['mstrlock']) && $userData[0]['mstrlock'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Account is InActive...');
        	return $result;	
        } elseif (isset($userData[0]['lgnusrlckbtng']) && $userData[0]['lgnusrlckbtng'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Betting is Locked...');
        	return $result;	
        }elseif (isset($userData[0]['sessionMaxStake']) && $userData[0]['sessionMaxStake'] != 0 && ($userData[0]['sessionMaxStake'] < $stake)) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Stake Limit is Over...');
        	return $result;	
        }elseif (isset($userData[0]['GoingInplayStakeLimit']) && $userData[0]['GoingInplayStakeLimit'] != 0 && $inplay == "" &&  ($userData[0]['GoingInplayStakeLimit'] < $stake)) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Going Inplay Stake Limit is Over...');
        	return $result;	
        }

    /*  if(isset($userData[0]['lgnUserMaxProfit'])){
        	$lgnUserMaxProfit = $userData[0]['lgnUserMaxProfit'];
			if($isback == 0 && ($lgnUserMaxProfit > 0) && ($profitAndLoss > $lgnUserMaxProfit)){
				$result = array('code'=>5,'error' => true ,'message' => 'Your max profit is over');	
				return $result;	
			}
        }

		if(isset($userData[0]['lgnUserMaxLoss'])){
			$lgnUserMaxLoss = $userData[0]['lgnUserMaxLoss'];
			if($isback == 1 && ($lgnUserMaxLoss > 0) && ($profitAndLoss > $lgnUserMaxLoss)){
				$result = array('code'=>6,'error' => true,'message' => 'Your max loss is over');
				return $result;	
			}
		}  */
		
		return $result;

	}	

	/**
	 * [mobileValidateSaveBet valdiate save bet]
	 * @return [array] [response array]
	 * Note: isback=0 When team is backed isback=1 When team is layed
	 */
	function mobileValidateSaveBet($data=NULL){


		$result = array();
		$isback = $data['isback'];
		$profitAndLoss = $data['p_l'];
		$loginId = $data['loginId'];
		$UserTypeId = $data['UserTypeId'];
		$stake = $data['stake']; 
		$inplay = $data['inplay']; 
		$isMatched = $data['isMatched']; 
		$matchId = $data['matchId']; 
		$MarketId = $data['MarketId'];
		$priceVal = $data['priceVal'];
		$selectionId = $data['selectionId'];
		$betfair_status = $data['betfair_status'];

		//print_r($betfair_status);die;


		$config_matched = CONFIG_UNMATCHED;



		$Modelmarket = $this->model_load_model('Modelmarket');
		$marketData = $Modelmarket->findByMarketId($MarketId);

		if(isset($data['isManual']) && $data['isManual']==1 && $marketData['isManualMatchOdds']==1 && $marketData['isBetAllowedOnManualMatchOdds']==0){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Manual odds suspended');
			return $result;	
		}

       //var_dump($betfair_status!='OPEN' and $data['isManual']==0 );die;
        if($betfair_status!='OPEN' and $data['isManual']==0){
            $result = array('code' => 4 ,'error'=>true,'message' => 'Market Is Suspended');
            return $result;
        }
       




		if(!empty($marketData['max_bet_liability']) && ($stake > $marketData['max_bet_liability'])){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Max Market bet liablity is '.$marketData['max_bet_liability']);
			return $result;	
		}


	//	echo "$MarketId,$loginId,$UserTypeId,$matchId";
		$runners = $this->sumOfOdds($MarketId,$loginId,$UserTypeId,$matchId);

	/*	echo '<pre>';
		print_r($runners);
		echo ' Selection id '.$selectionId.'back '.$isback.' profit and loss '.$profitAndLoss;  */

		if($isback==1){ 

			$runTimeProfitLoss = array();
			foreach($runners as $runner){
				if($runner['SelectionId'] == $selectionId){ // echo 'if2';
					$runner['winValue'] = $runner['winValue'] + $profitAndLoss;
					$runTimeProfitLoss[] = $runner;
				}else{ // echo 'else1';
					$runner['lossValue'] = $runner['lossValue'] - $stake;
					$runTimeProfitLoss[] = $runner;
				}
			}

		}
		else{

			$runTimeProfitLoss = array();
			foreach($runners as $runner){
				if($runner['SelectionId'] == $selectionId){ // echo 'if2';
					$runner['winValue'] = $runner['winValue'] - $profitAndLoss;
					$runTimeProfitLoss[] = $runner;
				}else{ // echo 'else1';
					$runner['lossValue'] = $runner['lossValue'] + $stake;
					$runTimeProfitLoss[] = $runner;
				}
		 	} 

		}

		$userMaxProfit = 0;
		if(!empty($runTimeProfitLoss)){
			foreach($runTimeProfitLoss as $rPl){
				$winLossValue = $rPl['winValue'] + $rPl['lossValue'];
				if($userMaxProfit < $winLossValue){
					$userMaxProfit = $winLossValue;
				} 
			}
		}else{
			if($isback==1){
				$userMaxProfit = $profitAndLoss;
			}else{
				$userMaxProfit = $stake;
			}
		}

		$userMaxLoss = 0;
		if(!empty($runTimeProfitLoss)){
			foreach($runTimeProfitLoss as $rPl){
				$winLossValue = $rPl['winValue'] + $rPl['lossValue'];
				if($userMaxLoss > $winLossValue){
					$userMaxLoss = $winLossValue;
				} 
			}
		}else{
			if($isback==1){
				$userMaxLoss = $stake;
			}else{
				$userMaxLoss = $profitAndLoss;
			}
		}

	//	echo 'User loss '.$userMaxLoss;
	//	print_r($runTimeProfitLoss);
	//	die;
		
		
		if(!empty($marketData['max_market_profit']) && ($userMaxProfit > $marketData['max_market_profit'])){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Max Market profit is '.$marketData['max_market_profit']);
			return $result;	
		}

	/*	echo 'User Max Profit '.$userMaxProfit;
		print_r($runTimeProfitLoss);
		print_r($marketData);
		die;  */

	//	echo 'Max profit '.$maxProfit;
	//	print_r($runners);
	//	die;

        $errorMinStake = MIN_STAKE;

        if($marketData['minStack']<=0){
            if($stake < $errorMinStake){
                $minStakeMsg = 'Minimum stake is '.$errorMinStake;
                $result = array('code' => ERROR_MIN_STAKE ,'error'=>true,'message' => $minStakeMsg);
                return $result;
            }
        }else{
            if($stake < $marketData['minStack']){
                $result = array('code' => 4 ,'error'=>true,'message' => 'Min Match Stack is '.$marketData['minStack']);
                return $result;
            }
        }
        if($marketData['maxStack']<=0){
            if($stake > MAX_STAKE){
                $maxStakeMsg = 'Maximum stake is '.MAX_STAKE;
                $result = array('code' => ERROR_MIN_STAKE ,'error'=>true,'message' => $maxStakeMsg);
                return $result;
            }
        }else{
            if( $stake > $marketData['maxStack']){
                $result = array('code' => 4 ,'error'=>true,'message' => 'Max Match Stack is '.$marketData['maxStack']);
                return $result;
            }
        }

		if(!empty($marketData['max_market_liability'])){

		/*	$Modeltblbets = $this->model_load_model('Modeltblbets');
			$MarketLiablity = $Modeltblbets->sumMarketLiablity($loginId,$MarketId);
			$sumMarketLiablity = $MarketLiablity + $stake;

			if($sumMarketLiablity > $marketData['max_market_liability']){
				$result = array('code' => 4 ,'error'=>true,'message' => 'Max Market liablity is '.$marketData['max_market_liability']);
				return $result;	 
			}
		*/	
			$sumMarketLiablity = abs($userMaxLoss);
			if($sumMarketLiablity > $marketData['max_market_liability']){
				$result = array('code' => 4 ,'error'=>true,'message' => 'Max Market liablity is '.$marketData['max_market_liability']);
				return $result;	 
			}
		}
		

		$config_max_odd_limit = CONFIG_MAX_ODD_LIMIT;

		if($config_max_odd_limit > 0){
			if($priceVal > $config_max_odd_limit){
				$result = array('code' => 9 ,'error'=>true,'message' => 'Max odd limit reached');
            	return $result;
			}
		}
        if($data['type']=='auto'){
            $Model_general_setting = $this->model_load_model('Model_general_setting');
            $bet_start_time_before = $Model_general_setting->get('BET_START_TIME_BEFORE');
            $key_value = $bet_start_time_before->key_value;


            $currentTime = time();
            $matchTime = strtotime($data['matchdate']);
            $diff = $matchTime-$currentTime;
            if($key_value > 0){
                if($diff >= 0 && $diff > $key_value){

                    if($key_value <= 0){
                        $message = "Betting Allow when match is in-play";
                    }else{
                        $message = "Betting allow before ".($key_value/60)." minutes ";
                    }

                    $result = array('code' => 4 ,'error'=>true,'message' => $message);
                    return $result;
                }
            }
        }


		if($stake < 0 || $stake == 0){
			$result = array('code' => 4 ,'error'=>true,'message' => 'Stack cannot be zero');
			return $result;	
		}


        $Modelmarket = $this->model_load_model('Modelsportmst');
        if($Modelmarket->checkActiveSport($data['SportId'])){
            $result = array('code' => 9 ,'error'=>true,'message' => 'Sport not active');
            return $result;
        }


		$Modelmarket = $this->model_load_model('Modelmarket');
		if($Modelmarket->checkActiveMarket($MarketId)){
			$result = array('code' => 9 ,'error'=>true,'message' => 'Market not active');
			return $result;
		}


		$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');

        if($data['type']=='manual'){
            $manualMatchOddsDetails = $this->Modelcreatemaster->manualMatchOddsDetails($data['MarketId']);
            if($data['teamName']=='team_0'){
                $team = 'team1';
            }elseif ($data['teamName']=='team_1'){
                $team = 'team2';
            }elseif ($data['teamName']=='team_2'){
                $team = 'draw';
            }

            if($manualMatchOddsDetails['active_'.$team]==1){
                $result = array('code' => 9 ,'error'=>true,'message' => 'Market not active');
                return $result;
            }

        }

		$userData = $Modelcreatemaster->viewUserAcData($loginId);


        if (empty($userData)) {
            $result = array('code' => 9 ,'error'=>true,'message' => 'Something went wrong');
			return $result;	
        } elseif (isset($userData[0]['lgnusrCloseAc']) && $userData[0]['lgnusrCloseAc'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Account is Closed...');
        	return $result;	
        } elseif (isset($userData[0]['mstrlock']) && $userData[0]['mstrlock'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Account is InActive...');
        	return $result;	
        } elseif (isset($userData[0]['lgnusrlckbtng']) && $userData[0]['lgnusrlckbtng'] == 0) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Betting is Locked...');
        	return $result;	
        }elseif (isset($userData[0]['stakeLimit']) && $userData[0]['stakeLimit'] != 0 && ($userData[0]['stakeLimit'] < $stake)) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Your Stake Limit is Over...');
        	return $result;	
        }elseif (isset($userData[0]['GoingInplayStakeLimit']) && $userData[0]['GoingInplayStakeLimit'] != 0 && $inplay == "" &&  ($userData[0]['GoingInplayStakeLimit'] < $stake)) {
        	$result = array('code' => 9 ,'error'=>true,'message' => 'Going Inplay Stake Limit is Over...');
        	return $result;	
        }

        if(isset($userData[0]['lgnUserMaxProfit'])){
        	$lgnUserMaxProfit = $userData[0]['lgnUserMaxProfit'];
			if($isback == 1 && ($lgnUserMaxProfit > 0) && ($profitAndLoss > $lgnUserMaxProfit)){
				$result = array('code'=>5,'error' => true ,'message' => 'Your max profit is over');	
				return $result;	
			}
        }
    /*  echo $lgnUserMaxProfit;
        echo ' P_L '.$profitAndLoss;
        die; */

		if(isset($userData[0]['lgnUserMaxLoss'])){
			$lgnUserMaxLoss = $userData[0]['lgnUserMaxLoss'];
			if($isback == 0 && ($lgnUserMaxLoss > 0) && ($profitAndLoss > $lgnUserMaxLoss)){
				$result = array('code'=>6,'error' => true,'message' => 'Your max loss is over');
				return $result;	
			}
		}
        if($isMatched == 0 && $data['type']=='manual'){
            $result = array('code' => 9 ,'error'=>true,'message' => 'Odds Change');
            return $result;
        }
        if($isMatched == 0 && $config_matched=='N'){
            $result = array('code' => 9 ,'error'=>true,'message' => 'Unmatched bet not available');
            return $result;
        }
		
		return $result;
	}	

	function mobileSave_bet($data){ 

		if ($data['UserTypeId']==3) {
			$GetpId=$this->Get_ParantId($data['loginId']);
			$ParantId=$GetpId[0]->parentId;
			$UserId= $data['loginId'];
		}

		if($data['isback'] == 1){
			$isBack = 0;
		}else{
			$isBack = 1;
		} 
		
	 	$insertbet = array(
			            'MstDate' 		=> date('Y-m-d H:i:s',now()),
			            'LogInId' 		=> $data['loginId'],
			            'UserId' 		=> $UserId,
			            'ParantId' 		=> $ParantId,
			            'MatchId' 		=> $data['matchId'],
			            'MarketId' 		=> $data['MarketId'],
			            'SelectionId' 	=> $data['selectionId'], 
						'Odds' 			=> $data['priceVal'],
						'P_L' 			=> $data['p_l'],
						'isBack' 		=> $isBack,
						'Stack'			=> $data['stake'],
						'IsMatched' 	=> $data['isMatched'],
						'type' 	        => $data['type'],
						'selectionName' => $data['placeName'],
						'IP_ADDESSS' 	=> $_SERVER['REMOTE_ADDR'],
						'deviceInfo' 	=> $data['deviceInfo']
			        );
			$stake= $data['stake'];
			$inplay= $data['inplay'];
			if($inplay==true){
				$InplayVal=0; //Inplay
			}else{
				$InplayVal=1; //Going Inplay 
			}
		$stateMent='Chips Deducted From Betting >>'.$_POST['MatchName'];
           //echo "<pre>"; print_r($data);die;
		 $parameter=$data['loginId'].','.$UserId.','.$ParantId.','.$data['matchId'].','.$data['selectionId'].','.$data['stake'].',"'.$data['MarketId'].'","'.$data['placeName'].'","'.date('Y-m-d H:i:s',now()).'",'.$data['priceVal'].','.$data['p_l'].','.$isBack.','.$data['isMatched'].',"'.$stateMent.'","'.$data['deviceInfo'].'","'.$_SERVER['REMOTE_ADDR'].'",'.$InplayVal.','.$data['ApiVal'].',"'.$data['type'].'"';
           // echo "call sp_PlaceBet($parameter)";die;
			$query =$this->db->query("call sp_PlaceBet($parameter)");
			$res = $query->result_array();


			$query->next_result();
			$query->free_result();
			return $res;
	}
    function deleteUnMatchBetData(){

        try {
            $currentTime = now();
            $this->db->select('b.UserId , b.MstCode,b.MstDate');
            $this->db->from('tblbets b');
            $this->db->where('IsMatched',0);
            $this->db->where('type','auto');
            $query = $this->db->get();
            $results = $query->result_array();

            if(!empty($results)){
               foreach ($results as $result){
                   $betTime =    strtotime($result['MstDate']);
                   $deff = $currentTime-$betTime;
                   if($deff > UN_MATCH_DELETE_TIME_IN_SECOND){
                       $betId = $result['MstCode'];
                       $userId = $result['UserId'];
                       $query =$this->db->query("call SP_DelUnmatch($userId,$betId)");
                   }

                }
                $this->load->model_load_model('Modelcreatemaster');
                $this->Modelcreatemaster->updateUserBalLiablity($userId);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

	function updateUnMatchDataOnRedis(){

        try {
            $redis = new Redis();
            $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
            $query =  $this->db->query("SELECT group_concat(distinct c.MstCode) as MstCode, `b`.`Stack`, `b`.`isBack`, `b`.`SelectionId`, `b`.`MarketId`, `b`.`Odds`, `b`.`MatchId`, `m`.`SportID`
FROM `tblbets` `b`
JOIN `matchmst` `m` ON `b`.`MatchId`=`m`.`MstCode`
join tblbets c on (((b.Odds >= c.Odds and b.`isBack`=0) or (b.Odds <= c.Odds and b.`isBack`=1) ) and b.`MarketId`=c.`MarketId` and b.`SelectionId`=c.`SelectionId` and b.`isBack`=c.`isBack`  and c.`IsMatched` =0)
WHERE b.`IsMatched` =0
AND b.`type` = 'auto'
GROUP BY b.`isBack`, b.`Odds`, b.`MarketId`, b.`SelectionId`");

            $results = $query->result_array();

            if(!empty($results)){
                $database = $this->db->database;

                foreach ($results as $result){
                   $key =$database.'_'.$result['MarketId'].'_'.$result['SelectionId'].'_'.$result['isBack'].'_'.$result['SportID'].'_'.$result['Odds'].'_'.$_SERVER['HTTP_HOST'];
                   $value= $result['MstCode'];
                    $redis->set($key,$value);
                }

            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

	function Save_bet($isMatched){ 
			if ($this->input->post('UserTypeId')==3) {
				$GetpId=$this->Get_ParantId($this->input->post('loginId'));
				$ParantId=$GetpId[0]->parentId;
				$UserId=$this->input->post('loginId');
			}else{
				$ParantId=$this->input->post('ParantId');
				$UserId=$this->input->post('userId');
			}
		 	$insertbet = array(
				            'MstDate' 		=> date('Y-m-d H:i:s',now()),
				            'LogInId' 		=> $this->input->post('loginId'),
				            'UserId' 		=> $UserId,
				            'ParantId' 		=> $ParantId,
				            'MatchId' 		=> $this->input->post('matchId'),
				            'MarketId' 		=> $this->input->post('MarketId'),
				            'SelectionId' 	=> $this->input->post('selectionId'), 
							'Odds' 			=> $this->input->post('priceVal'),
							'P_L' 			=> $this->input->post('p_l'),
							'isBack' 		=> $this->input->post('isback'),
							'Stack'			=> $this->input->post('stake'),
							'IsMatched' 	=> $isMatched,
							'selectionName' => $this->input->post('placeName'),
							'IP_ADDESSS' 	=> $_SERVER['REMOTE_ADDR'],
							'deviceInfo' 	=> $this->input->post('deviceInfo')
				        );

		// 	print_r($insertbet);
		// 	die;
		 			$stake= $this->input->post('stake');
		 			$inplay= $this->input->post('inplay');
		 			if($inplay==true){
		 				$InplayVal=0; //Inplay
		 			}else{
		 				$InplayVal=1; //Going Inplay 
		 			}
					$stateMent='Chips Deducted From Betting >>'.$_POST['MatchName'];	
					$parameter=$this->input->post('loginId').','.$UserId.','.$ParantId.','.$this->input->post('matchId').','.$this->input->post('selectionId').','.$this->input->post('stake').',"'.$this->input->post('MarketId').'","'.$this->input->post('placeName').'","'.date('Y-m-d H:i:s',now()).'",'.$this->input->post('priceVal').','.$this->input->post('p_l').','.$this->input->post('isback').','.$isMatched.',"'.$stateMent.'","'.$this->input->post('deviceInfo').'","'.$_SERVER['REMOTE_ADDR'].'",'.$InplayVal.','.$this->input->post('ApiVal');
					//echo "call sp_PlaceBet($parameter)";
					/*START pROCEDURE CALL*/
						$query =$this->db->query("call sp_PlaceBet($parameter)");
						$res = $query->result_array();
						$query->next_result();
						$query->free_result();
						//print_r($query);
						//echo $this->db->queries[0];
						return $res;
					/*END OF PROCEDURE CALL*/

					

					//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table

					return true;
	}
	function sumOfOdds($MarketId,$userId,$userType,$matchId)//170201_1
	{
		if($userId==null)$userId1=0;else $userId1=$userId;

			//$query =$this->db->query("call SP_OddsProfitLossNew($userId1,$userType,$matchId,$MarketId)");//sourabh 170201_1
			//$query =$this->db->query("call getOddsProfitLoss($MarketId,$userId1,$userType,$matchId)");//sourabh 170201_1
        $query =$this->db->query("call SP_getOddsProfitLoss($userId,$userType,$matchId,$MarketId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;

	}
	function getBetEntry($marketId,$UserTypeId,$userId,$matchId)
	{
			
			if ($userId==null) {
				$userId1=$_POST['userId'];
			}
			else{
				$userId1=$userId;
			}
			$query =$this->db->query("call SP_GetBetting($userId1,$UserTypeId,0,$matchId)");//170131
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
				
			return $res;
	}
	function moveUnMatchToMatchBet(){

	}
    function getBetEntryAll($marketId,$UserTypeId,$userId,$matchId)
    {

        if ($userId==null) {
            $userId1=$_POST['userId'];
        }
        else{
            $userId1=$userId;
        }
    //    echo "call SP_GetBettingAll($userId1,$UserTypeId,$marketId,$matchId)";die;
        $query =$this->db->query("call SP_GetBettingAll($userId1,$UserTypeId,$marketId,$matchId)");//170131
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();

        return $res;
    }

	function getAllBetEntry($UserTypeId,$userId)
	{
			
			if ($userId==null) {
				$userId1=$_POST['userId'];
			}
			else{
				$userId1=$userId;
			}
			//echo "call SP_GetAllBetting($userId1,$UserTypeId)";die;
			$query =$this->db->query("call SP_GetAllBetting($userId1,$UserTypeId)");//170131
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
				
			return $res;
	}

	function getBetFancyEntry($marketId,$UserTypeId,$userId,$matchId,$fancyId)
	{
			
			if ($userId==null) {
				$userId1=$_POST['userId'];
			}
			else{
				$userId1=$userId;
			}
			$query =$this->db->query("call SP_GetFancyBetting($userId1,$UserTypeId,$marketId,$matchId,$fancyId)");//170131
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
				
			return $res;
	}

	function mbdip_getBetEntry($userId,$matchId){
		$query =$this->db->query("call SP_GetBettingLatest($userId,$matchId)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

	function updateUnMatchedData($userId,$BackLay){
        $dataArray = array('IsMatched' => 1,'MatchedDate'=>date('Y-m-d H:i:s',now()));
    		$this->db->where('MstCode',$userId);
            $this->db->update('tblbets', $dataArray);
            //echo $this->db->queries[0];die();		
            return true; 
	}

    function moveUnMatchToMatchBetByAdmin($betId){

        $this->updateOddsProfitLossOnBetMoveUnmatchToMatch($betId);
        $dataArray = array('IsMatched' => 1,'MatchedDate'=>date('Y-m-d H:i:s',now()));
        $this->db->where('MstCode',$betId);
        $this->db->update('tblbets', $dataArray);
       // echo $this->db->last_query();die();


        return true;
    }

	function UpdatepointVal($pointVal){
			$dataArray = array('value' => $pointVal);
    		$this->db->where('Id',1);
            $this->db->update('detect_amount', $dataArray);

            $modeltblconfig = $this->model_load_model('Modeltblconfig');		
			$modeltblconfig->update(array('match_detection_point'=>$pointVal));

            //echo $this->db->queries[0];die();		
            return true; 
	}
	function UpdateBetDelay($betdelay){
			$dataArray = array('set_timeout' => $betdelay,'session_delay' => $betdelay,'admin_set_timeout'=>$betdelay,'admin_session_delay'=>$betdelay);
    		//$this->db->where('usetype',3);
            $this->db->update('createmaster', $dataArray);
            
            $modeltblconfig = $this->model_load_model('Modeltblconfig');		
			$modeltblconfig->update(array('bet_delay'=>$betdelay));

            //echo $this->db->queries[0];die();		
            return true; 
	}
	function PointValue($userId){
			/*$this->db->select("value");
			$this->db->from('detect_amount');
			$this->db->where('Id',1);
			$query = $this->db->get();
			return $query->result();*/
			$this->db->select("value");
			$this->db->from('detect_amount');
			$this->db->where('user_id',$userId);
			$query = $this->db->get();
			$rowcount = $query->num_rows();
			if($rowcount==0){
				return $query->result();
			}else{
				return $query->result();
			}
	}
	function GetMasterList(){
			$this->db->select("*");
			$this->db->from('createmaster');
			$this->db->where('usetype',1);
			$query = $this->db->get();
			return $query->result();
	}
	
	function GetDealer($masterId){
			$this->db->select("*");
			$this->db->from('createmaster');
			$this->db->where('parentId',$masterId);
			$this->db->order_by("createmaster.mstruserid ASC");
			$query = $this->db->get();
			return $query->result();
	}

	function Get_ParantId($userId){
			$this->db->select("parentId");
			$this->db->from('createmaster');
			$this->db->where('mstrid',$userId);
			$query = $this->db->get();
			return $query->result();
	}
	function Chip_history($UserID,$TypeID,$matchId,$MarketId,$OppAcID){
	//sourabh 161222
			$query =$this->db->query("call getChipHistory($TypeID,$UserID,$matchId,$MarketId,$OppAcID)");//sourabh 161222
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;

	}
	function online_users($userId,$userType){
			$query =$this->db->query("call getLoginUser($userId,$userType)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}
	function Chip_summery($userId,$type){
			$query =$this->db->query("call sp_ChipSummary($userId,$type)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}
	function profit_loss($userId,$sportId){
			$query =$this->db->query("call sp_GetP_L($userId,$sportId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}
	function profit_loss1($userId,$sportId){
			$query =$this->db->query("call sp_getAllp_l($userId,$sportId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}

	function mbdip_profit_loss($data){
			$userId = $data['user_id'];
			$sportId = (isset($data['sport_id']) ? $data['sport_id'] : 0);
			$event_name = (isset($data['event_name']) ? $data['event_name'] : ''); 
			$from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
			$to_date = (isset($data['to_date']) ? $data['to_date'] : 0); 

			$page_limit = $data['page_limit'];
			$pageno = $data['page_no'];
			$page_max = $page_limit;
        	$start = ($pageno - 1) * $page_max; 
        	
        /*	print_r($data); 
        	die; */ 

        /*	echo "CALL `sp_getAllp_l_filters`($userId, $sportId, '$event_name', '$from_date', '$to_date' , $start , $page_limit)";
        	die;  */ 
			$query =$this->db->query("CALL `sp_getAllp_l_filters`($userId, $sportId, '$event_name', '$from_date', '$to_date' , $start , $page_limit)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}


	function mbdip_profit_loss_count($data){
			$userId = $data['user_id'];
			$sportId = (isset($data['sport_id']) ? $data['sport_id'] : 0);
			$event_name = (isset($data['event_name']) ? $data['event_name'] : ''); 
			$from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
			$to_date = (isset($data['to_date']) ? $data['to_date'] : 0); 

			$query =$this->db->query("CALL `sp_getAllp_l_filters_count`($userId, $sportId, '$event_name', '$from_date', '$to_date')");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}

    function mbdip_profit_loss_by_match_possition($data){
        $userId = $data['user_id'];
        $sportId = (isset($data['sport_id']) ? $data['sport_id'] : 0);
        $event_name = (isset($data['event_name']) ? $data['event_name'] : '');
        $from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
        $to_date = (isset($data['to_date']) ? $data['to_date'] : 0);

        $page_limit = $data['page_limit'];
        $pageno = $data['page_no'];
        $page_max = $page_limit;
        $start = ($pageno - 1) * $page_max;

        /*	print_r($data);
        	die; */


        $query =$this->db->query("CALL `sp_getAllp_l_filters_by_match_possition`($userId, $sportId, '$event_name', '$from_date', '$to_date' , $start , $page_limit)");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        //print_r($res);
        //die();
        return $res;
    }
    function mbdip_profit_loss_count_by_match_possition($data){
        $userId = $data['user_id'];
        $sportId = (isset($data['sport_id']) ? $data['sport_id'] : 0);
        $event_name = (isset($data['event_name']) ? $data['event_name'] : '');
        $from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
        $to_date = (isset($data['to_date']) ? $data['to_date'] : 0);



        $query =$this->db->query("CALL `sp_getAllp_l_filters_count_by_match_possition`($userId, $sportId, '$event_name', '$from_date', '$to_date')");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        //print_r($res);
        //die();
        return $res;
    }

    function mbdip_profit_loss_by_match($data){
        $userId = $data['user_id'];
        $matchId = $data['match_id'];
        $event_name = (isset($data['event_name']) ? $data['event_name'] : '');
        $from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
        $to_date = (isset($data['to_date']) ? $data['to_date'] : 0);


        /*	print_r($data);
        	die; */
/*
        	echo "CALL `sp_get_p_l_by_match_id_filters`($matchId , $userId, '$event_name', '$from_date', '$to_date' )";
        	die;*/
        $query =$this->db->query("CALL `sp_get_p_l_by_match_id_filters`($matchId , $userId, '$event_name', '$from_date', '$to_date' )");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        //print_r($res);
        //die();
        return $res;
    }

    function sessionBetDetailsGroupByMatch(){
        $this->db->select("mtchmst.matchName name,mtchmst.startDate date,count(b.MstCode) noOfRecord,sum(b.Stack) totalAmount");
        $this->db->from('tblbets b');
        $this->db->join('matchmst mtchmst', 'mtchmst.MstCode=b.MatchId', 'INNER');
        $this->db->group_by('mtchmst.MstCode');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result_array();
    }

    function oddBetDetailsGroupByMatch(){
        $this->db->select("mtchmst.matchName name,mtchmst.startDate date,count(b.matchId) noOfRecord,sum(b.bet_value) totalAmount");
        $this->db->from('bet_entry b');
        $this->db->join('matchmst mtchmst', 'mtchmst.MstCode=b.matchId', 'INNER');
        $this->db->group_by('mtchmst.MstCode');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result_array();
    }

    /**
     * [myBetsFilters description]
     * @param  [bet_type -> M=>Matched,U=>Unmatched,P=>Past]
     * @return [type]       [description]
     */
    function myBetsFilters($data){
			$betType = $data['bet_type']; 
			$userId = $data['user_id'];
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];

			$page_limit = $data['page_limit'];
			$pageno = $data['page_no'];
			$page_max = $page_limit;
        	$start = ($pageno - 1) * $page_max; 

			$query =$this->db->query("CALL `GetBetHistoryFilterPaging`($userId, '$from_date', '$to_date' , $start , $page_limit, '$betType')");
			
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}

	function myBetsFiltersCount($data){
			$betType = $data['bet_type']; 
			$userId = $data['user_id'];
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];

			$query =$this->db->query("CALL `GetBetHistoryFilterPagingCount`($userId, '$from_date', '$to_date','$betType')");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}

    function deleteBetsFilters($data){
        $betType = $data['bet_type'];
        $userId = $data['user_id'];
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];

        $page_limit = $data['page_limit'];
        $pageno = $data['page_no'];
        $page_max = $page_limit;
        $start = ($pageno - 1) * $page_max;

        $query =$this->db->query("CALL `GetDeleteBetHistoryFilterPaging`($userId, '$from_date', '$to_date' , $start , $page_limit, '$betType')");

        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        //print_r($res);
        //die();
        return $res;
    }
    function myDeleteBetsFiltersCount($data){
        $betType = $data['bet_type'];
        $userId = $data['user_id'];
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $query =$this->db->query("CALL `GetDeleteBetHistoryFilterPagingCount`($userId, '$from_date', '$to_date','$betType')");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        return $res;
    }

	function mb_myBetsFilters($data){
			$userId = $data['user_id'];
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];

			$page_limit = $data['page_limit'];
			$pageno = $data['page_no'];
			$page_max = $page_limit;
        	$start = ($pageno - 1) * $page_max; 
        	
        /*	print_r($data); 
        	die; */ 

        /*	echo "CALL `sp_getAllp_l_filters`($userId, $sportId, '$event_name', '$from_date', '$to_date' , $start , $page_limit)";
        	die;  */
			$query =$this->db->query("CALL `mb_GetBetHistoryFilterPaging`($userId, '$from_date', '$to_date' , $start , $page_limit)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			//print_r($res);
			//die();			
			return $res;
	}

	function matchOddsRes(){
			$this->db->select("res.selectionName,mtchmst.matchName,mrt.Name,res.id,res.selectionId,sprt.name");
			$this->db->from(' tblselectionname res');
			$this->db->join('matchmst mtchmst', 'mtchmst.MstCode=res.matchId', 'INNER');
			$this->db->join('market mrt', 'mrt.Id=res.marketId', 'INNER');
			$this->db->join('sportmst sprt', 'sprt.id=res.sportId', 'INNER');
			
			$query = $this->db->get();
			return $query->result();
	}
	function FancyRes(){
		
			$this->db->select("mf.HeadName,mf.MatchID,mf.TypeID,mtchmst.matchName");
			$this->db->from('matchfancy mf');
			$this->db->join('matchmst mtchmst', 'mtchmst.MstCode=mf.MatchID', 'INNER');
			
			$query = $this->db->get();
			return $query->result();
	}
	function ActiveMatchUsers($matchId){
			$this->db->select("DISTINCT(cm.mstruserid) as UserName,COUNT(cm.mstruserid) as cntBetting");
			$this->db->from('matchmst mmst');
			$this->db->join('tblbets bts', 'bts.MatchId=mmst.MstCode', 'INNER');
			$this->db->join('createmaster cm', 'cm.mstrid=bts.UserId', 'INNER');
			$this->db->where('bts.MatchId',$matchId);
			$this->db->group_by('cm.mstruserid'); 
			$query = $this->db->get();
			return $query->result();
	}
	function getActiveMatches(){
			$this->db->select("MstCode,matchName");
			$this->db->from('matchmst');
			$this->db->where('active',1);
			$query = $this->db->get();
			return $query->result();
	}
	function BetHistory($userId){
		if ($userId==null) {
			$userId1=$this->session->userdata('user_id');
		}else{
			$userId1=$userId;
		}

		$query =$this->db->query("call GetBetHistory($userId1)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		//print_r($res);
		//die();			
		return $res;
	}

	function BetHistoryFilter($userId,$fromDate,$toDate){
		if ($userId==null) {
			$userId1=$this->session->userdata('user_id');
		}else{
			$userId1=$userId;
		}

	//	echo "call GetBetHistoryFilter($userId1,$fromDate,$toDate)";

		$query =$this->db->query("call GetBetHistoryFilter($userId1,'$fromDate','$toDate')");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
	//	print_r($res);
	//	die();			
		return $res;
	}

	function BetHistoryPaging($userId,$search,$pageno,$limit){
		if ($userId==null) {
			$userId1=$this->session->userdata('user_id');
		}else{
			$userId1=$userId;
		}

		$page_max = $limit;
        $start = ($pageno - 1) * $page_max;
    //  $end = $pageno * $page_max;

        if(!empty($search)){
        	$query =$this->db->query("call GetBetHistoryPaging($userId1,$limit,$start,'$search')");	
        }else{
        	$query =$this->db->query("call GetBetHistoryPaging($userId1,$limit,$start,'')");	
        }
		

		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		//print_r($res);
		//die();			
		return $res;
	}

	function myBetsPaging($userId,$betType,$pageno,$limit){
		$page_max = $limit;
        $start = ($pageno - 1) * $page_max;
        $query =$this->db->query("call GetMyBetsPaging($userId,'$betType',$limit,$start,'')");	
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

	function mobileMarketMatchedBets($userId,$matchId,$marketId){

	//	echo "$userId,$matchId,$marketId";die;

		$query =$this->db->query("call GetMatchedBet($userId,$matchId,$marketId)");	
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

	function mobileSessionMatchedBets($userId,$fancyId){
		$query =$this->db->query("call GetSessionMatchedBet($userId,$fancyId)");	
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

	function mobileMarketUnMatchedBets($userId,$matchId,$marketId){

		$query =$this->db->query("call GetUnMatchedBet($userId,$matchId,$marketId)");	
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

	function mobileAllUnMatchedBets($userId){

		$query =$this->db->query("call GetALLUnMatchedBet($userId)");	
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}


	function LiablityHistory($userId){
		if ($userId==null) {
			$userId1=$this->session->userdata('user_id');
		}else{
			$userId1=$userId;
		}

		$query =$this->db->query("call GetLiablityHistory($userId1)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}
	function AcStatement($userId){
		if ($userId==null) {
			$userId1=$this->session->userdata('user_id');
		}else{
			$userId1=$userId;
		}
			$query =$this->db->query("call sp_acStatement($userId1)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
						
			return $res;
	}
	function AcStatementFilter($data){

			$transaction_type = $data['transaction_type'];
			$userId = $data['user_id'];
			$from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
			$to_date = (isset($data['to_date']) ? $data['to_date'] : 0); 

			$page_limit = $data['page_limit'];
			$pageno = $data['page_no'];
			$page_max = $page_limit;
        	$start = ($pageno - 1) * $page_max; 

       // 	echo "CALL `sp_acStatement_filters`($userId, '$from_date', '$to_date' , '$transaction_type' , $start , $page_limit )";die;
			$query =$this->db->query("CALL `sp_acStatement_filters`($userId, '$from_date', '$to_date' , '$transaction_type' , $start , $page_limit )");
			
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
						
			return $res;
	}
	function AcStatementFilterCount($data){

			$transaction_type = $data['transaction_type'];
			$userId = $data['user_id'];
			$from_date = (isset($data['from_date']) ? $data['from_date'] : 0);
			$to_date = (isset($data['to_date']) ? $data['to_date'] : 0); 

			$query =$this->db->query("CALL `sp_acStatement_filters_count`($userId, '$from_date', '$to_date' , '$transaction_type' )");

			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
						
			return $res;
	}
	function SlmAcStatement($userId){
		if ($userId==null) {
			$userId1=$this->session->userdata('user_id');
		}else{
			$userId1=$userId;
		}
			$query =$this->db->query("call slm_acStatement($userId1)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
						
			return $res;
	}

	function mbdip_acStatement($userId,$pageno,$limit){
		$page_max = $limit;
        $start = ($pageno - 1) * $page_max;

//        $query  =  "SET @variable = 0;";
//        $this->db->query($query);
//		$query = $this->db->query("SELECT *,ROUND((@variable := @variable + `Chips`),2)  AS `Balance` from viewacstatement where user_id = $userId order by Sdate DESC LIMIT $start,$limit;");

		$query = $this->db->query("SELECT * from viewacstatement where user_id = $userId order by Sdate DESC LIMIT $start,$limit;");

/*	echo 	"SELECT *,ROUND((@variable := @variable + `Chips`),2)  AS `Balance` from viewacstatement where user_id = $userId order by Sdate DESC LIMIT $start,$limit;";
	die; */

		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

	function adminLimit(){
			$this->db->select("*");
			$this->db->from('tblconfig');
			$query = $this->db->get();
			return $query->result();
	}
	function UpdateAdminLimit($id,$limit){
			$limitData = array('adminLImit'  => $limit);
        	$this->db->where('Id', 1);
		    $query=$this->db->update('tblconfig', $limitData);
		    return true;
	}
	function UpdateGngInPlayLimitLimit($limit){
			$limitData = array('InPlayStack'  => $limit);
        	$this->db->where('usetype', 3);
		    $query=$this->db->update('createmaster', $limitData);
			$modeltblconfig = $this->model_load_model('Modeltblconfig');		
			$modeltblconfig->update(array('going_in_play_limit'=>$limit));
		    return true;
	}
	function deleteGetbetting($betId,$userId){
		$query =$this->db->query("call SP_DelUnmatch($userId,$betId)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $res;
	}

    function deleteBetByMatchId($matchId){
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
         $this->db->query("delete from tblbets where MatchId = $matchId and Result IS NOT NULL");
         $this->db->query("delete from bet_entry where matchId = $matchId and ResultID IS NOT NULL");
         $this->db->query(" UPDATE `matchmst` SET `bet_deleted`=1 WHERE MstCode = $matchId");
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function hardHeleteBetByMatchId($matchId){
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        $this->db->query("delete from tblbets_bak where MatchId = $matchId ");
        $this->db->query("delete from bet_entry_bak where matchId = $matchId ");
        $this->db->query("UPDATE `matchmst` SET `hard_bet_deleted`=1 WHERE MstCode = $matchId ");
        $this->db->trans_complete();

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function undoBetByMatchId($matchId){
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

         $this->db->query("INSERT INTO tblbets (LogInId, UserId, ParantId, MatchId, MarketId, SelectionId, selectionName, MstDate, Odds, Stack, P_L, isBack, IsMatched, Result, ResultID, IP_ADDESSS, deviceInfo, SAdmin, Admin, Master, Dealer, Chips, IsInPlay, isApp,type)
SELECT LogInId, UserId, ParantId, MatchId, MarketId, SelectionId, selectionName, MstDate, Odds, Stack, P_L, isBack, IsMatched, Result, ResultID, IP_ADDESSS, deviceInfo, SAdmin, Admin, Master, Dealer, Chips, IsInPlay, isApp,type
FROM tblbets_bak where MatchId=$matchId");

         $this->db->query("INSERT INTO bet_entry (bet_id, bet_value, OddValue, OddsNumber, TypeID, matchId, fancyId, parantId, userId, dateTime, loginid, ResultID, fncy_refId, FheadName, SessInpYes, SessInpNo, sportId,ponitDiff,IP_ADDRESS,DeviceDesc, SAdmin, Admin, Master, Dealer, Chips, session_no_size, session_yes_size)
SELECT bet_id, bet_value, OddValue, OddsNumber, TypeID, matchId, fancyId, parantId, userId, dateTime, loginid, ResultID, fncy_refId, FheadName, SessInpYes, SessInpNo, sportId,ponitDiff,IP_ADDRESS,DeviceDesc, SAdmin, Admin, Master, Dealer, Chips, session_no_size, session_yes_size
FROM bet_entry_bak where matchId=$matchId");

        $this->db->query(" UPDATE `matchmst` SET `bet_deleted`= 0 WHERE MstCode = $matchId");
        $this->db->query("  delete from tblbets_bak where MatchId=$matchId");
        $this->db->query("  delete from bet_entry_bak where matchId=$matchId");

        $this->db->trans_complete();

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

	function deleteGetbettingmat($betId,$userId,$marketId){
		$query =$this->db->query("call SP_DelMatch($userId,$betId,$marketId)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();

		if($marketId == 2){
			$this->updateUserFancyLiabilityOnBetDelete($betId, 'bet_entry_bak');
		}else{
			$this->updateOddsProfitLossOnBetDelete($betId, $marketId, 'tblbets_bak');
		}

		$cmModel = $this->model_load_model('Modelcreatemaster');
		$cmModel->updateUserBalLiablity($userId);
		return $query;
	}

    function moveToAvoidBet($betId,$userId,$marketId){

        $query =$this->db->query("call SP_MoveToVoidBats($userId,$betId,$marketId)");

        $res = $query->result_array();
        $query->next_result();
        $query->free_result();

        if($marketId == 2){
            $this->updateUserFancyLiabilityOnBetVoid($betId, 'void_bet_entry');
        }else{
            $this->updateOddsProfitLossOnBetVoid($betId, $marketId, 'void_tblbets');
        }
        echo $query;
    }
	
	function NewChip_historyP($userId,$type){

		$query =$this->db->query("call sp_ChipSumm_P($userId,$type)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();

		return $query;
	}
	function NewChip_historyM($userId,$type){
		$query =$this->db->query("call sp_ChipSumm_M($userId,$type)");
		$res = $query->result_array();
		$query->next_result();
		$query->free_result();
		return $query;
	}
	function Chip_historyById($userId,$userType,$lgnType,$parentId,$FROMDate,$ToDate){

                $query =$this->db->query("call GetLedger($lgnType,$userId,$userType,$parentId,'$FROMDate','$ToDate')");//170201_3//getChipHistory
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;

	}
	function Chip_leger($userId,$userType,$selectType,$fromDate1,$ToDate1){
	   // echo $fromDate1;
       // echo $ToDate1;
	        if($fromDate1==null ||$ToDate1==null){
                //$fromDate2="''";
                //$ToDate2="''";
                //echo "true";
              //echo "call GetLedger($userType,$userId,$selectType,0,null,null)";
                $query =$this->db->query("call GetLedger($userType,$userId,$selectType,0,null,null)");//170201
            }else{
                $fromDate2="'".$fromDate1."'";
               // echo"||";
                $ToDate2="'".$ToDate1."'";

                //echo $userType,$userId,$selectType,0,$fromDate2,$ToDate2;die;
                //echo "False";
               // echo "call GetLedger($userType,$userId,$selectType,0,$fromDate2,$ToDate2)";
                $query =$this->db->query("call GetLedger($userType,$userId,$selectType,0,$fromDate2,$ToDate2)");//170201
            }

			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}

	function Chip_leger_filter($userId,$userType,$selectType,$fromDate1,$ToDate1,$transaction_type){
	   // echo $fromDate1;
       // echo $ToDate1;
	        if($fromDate1==null ||$ToDate1==null){
                //$fromDate2="''";
                //$ToDate2="''";
                //echo "true";
            //  echo "call GetLedgerFilters($userType,$userId,$selectType,0,'$transaction_type',null,null)";
                $query =$this->db->query("call GetLedgerFilters($userType,$userId,$selectType,0,'$transaction_type',null,null)");//170201
            }else{
                $fromDate2="'".$fromDate1."'";
               // echo"||";
                $ToDate2="'".$ToDate1."'";

                //echo $userType,$userId,$selectType,0,$fromDate2,$ToDate2;die;
                //echo "False";
            // echo "call GetLedgerFilters($userType,$userId,$selectType,0,'$transaction_type',null,null)";die;
                $query =$this->db->query("call GetLedgerFilters($userType,$userId,$selectType,0,'$transaction_type',$fromDate2,$ToDate2)");//170201
            }

			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}

	

	function Chip_leger_filter_count($userId,$userType,$selectType,$fromDate1,$ToDate1,$transaction_type){
	   // echo $fromDate1;
       // echo $ToDate1;
	        if($fromDate1==null ||$ToDate1==null){
                //$fromDate2="''";
                //$ToDate2="''";
                //echo "true";
              //echo "call GetLedger($userType,$userId,$selectType,0,null,null)";
                $query =$this->db->query("call GetLedgerFiltersCount($userType,$userId,$selectType,0,'$transaction_type',null,null)");//170201
            }else{
                $fromDate2="'".$fromDate1."'";
               // echo"||";
                $ToDate2="'".$ToDate1."'";

                //echo $userType,$userId,$selectType,0,$fromDate2,$ToDate2;die;
                //echo "False";
            //    echo "call GetLedgerFiltersCount($userType,$userId,$selectType,0,'$transaction_type',$fromDate2,$ToDate2)"; die;
                $query =$this->db->query("call GetLedgerFiltersCount($userType,$userId,$selectType,0,'$transaction_type',$fromDate2,$ToDate2)");//170201
            }

			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;
	}




	function GetPlusA_c($userId,$matchId,$MarketId,$fancyId){
	
			$query =$this->db->query("call sp_PL_ChipSumm_P($userId,$matchId,'$MarketId',$fancyId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;

	}
	function GetMinusA_c($userId,$matchId,$MarketId,$fancyId){
	
			$query =$this->db->query("call sp_PL_ChipSumm_M($userId,$matchId,'$MarketId',$fancyId)");
			$res = $query->result_array();
			$query->next_result();
			$query->free_result();
			return $res;

	}
	function BetHistoryPL($userId,$matchId,$MarketId,$fancyId){

        $resultQuery = $this->db->query("SELECT `bet_deleted` FROM `matchmst` WHERE MstCode = $matchId");
        $result = $resultQuery->result_array();
        if($result[0]['bet_deleted']){
            $query =$this->db->query("call GetDeletedBetHistory_PL($userId,$matchId,'$MarketId',$fancyId)");
        }else{
            $query =$this->db->query("call GetBetHistory_PL($userId,$matchId,'$MarketId',$fancyId)");
        }

        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        return $res;

	}
    function getDeletedBetHistoryPL($userId,$matchId,$MarketId,$fancyId){

        $query =$this->db->query("call GetDeletedBetHistory_PL($userId,$matchId,'$MarketId',$fancyId)");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        return $res;

    }

	function setHeaderMsg(){

        $message=$_POST['setMessage'];
        $myfile = fopen("marquee.txt", "w");
        $txt = $message;
        fwrite($myfile, $txt);
        fclose($myfile);
		return true;
	}
	function DisplayMsgOnHeader(){
        $myfile = fopen("marquee.txt", "r") ;
        $res =  fread($myfile,filesize("marquee.txt"));
        fclose($myfile);
        return $res;
	}

	function updateBalByMatchSession($matchId,$fancyId){
			$cmModel = $this->model_load_model('Modelcreatemaster');	
			$this->db->select('sbet.userId user_id,sbet.parantId dealer_id,dealer.parentId master_id');
			$this->db->from('bet_entry sbet');
			$this->db->join('createmaster dealer', 'sbet.parantId = dealer.mstrid', 'LEFT');
			$this->db->where('matchId',$matchId);
			$this->db->where('fancyId',$fancyId);
			$this->db->group_by('sbet.userId');
			$query = $this->db->get();
			$users = $query->result_array();

			$userIds = array();
			foreach($users as $user){
				$userIds[] = $user['user_id'];
				$userIds[] = $user['dealer_id'];
				$userIds[] = $user['master_id'];
			}
			$uIds = array_unique($userIds);
			foreach($uIds as $uid){
				$cmModel->updateUserBalLiablity($uid);
			}
	}

	function getUsersMatchSession($matchId,$fancyId){
			$cmModel = $this->model_load_model('Modelcreatemaster');	
			$this->db->select('sbet.userId user_id,sbet.parantId dealer_id,dealer.parentId master_id');
			$this->db->from('bet_entry sbet');
			$this->db->join('createmaster dealer', 'sbet.parantId = dealer.mstrid', 'LEFT');
			$this->db->where('matchId',$matchId);
			$this->db->where('fancyId',$fancyId);
			$this->db->group_by('sbet.userId');
			$query = $this->db->get();
			$users = $query->result_array();

			$userIds = array();
			foreach($users as $user){
				$userIds[] = $user['user_id'];
				$userIds[] = $user['dealer_id'];
				$userIds[] = $user['master_id'];
			}
			$uIds = array_unique($userIds);
			return $uIds;
	}

	function sumSessionLiablity($userId,$fancyId){
			$this->db->select("SUM(bet_value) as sum_session_liability");
			$this->db->from('bet_entry');
			$this->db->where('fancyId',$fancyId);
			$this->db->where('userId',$userId);
			$query = $this->db->get();

			$sessionData = $query->row_array();
			$sumsessions = 0;
			if(!empty($sessionData['sum_session_liability'])){
				$sumsessions = $sessionData['sum_session_liability'];
			}
			return $sumsessions;

	}

    function saveOddsProfitLoss($userId, $betId, $matchId, $marketId){

        $query = "SELECT 
				`b`.`MstCode` AS `bet_id`, `a`.`matchId` AS `matchId`, `a`.`selectionId` AS `selectionId`, `a`.`marketId` AS `marketId`, `a`.`selectionName` AS `selectionName`, `b`.`UserId` AS `userId`, `b`.`ParantId` AS `parentId`, `a`.`teamType` AS `teamType`, `b`.`Admin` AS `admin`, `b`.`Master` AS `master`, `b`.`Dealer` AS `dealer`, 
				CASE
					WHEN (`b`.`SelectionId` = `a`.`selectionId`)
					THEN
						(CASE WHEN (`b`.`isBack` = 0) THEN `b`.`P_L` ELSE -(`b`.`P_L`) END)
					ELSE
						0
				END AS `winValue`,
				CASE
					WHEN (`b`.`SelectionId` = `a`.`selectionId`)
					THEN
						0
					ELSE
						(CASE WHEN (`b`.`isBack` = 0) THEN -(`b`.`Stack`) ELSE `b`.`Stack` END)
				END AS `lossValue`				 
			FROM 
				`tblselection` `a` 
				INNER JOIN `tblbets` `b` ON(`a`.`marketId` = `b`.`MarketId` AND `b`.`IsMatched` = 1)	
			WHERE 
				b.userId = $userId AND b.MstCode = $betId;";

        $query =$this->db->query($query);
        $res = $query->result_array();

        if(!empty($res)){

            $this->db->select("id");
            $this->db->from('odds_profit_loss');
            $this->db->where('userId', $userId);
            $this->db->where('marketId', $marketId);
            $this->db->limit(1);
            $q = $this->db->get();
            $rowCount = $q->num_rows();

            if($rowCount > 0){
                //update existing values
                foreach ($res as $key => $value) {
                    $this->db->set('winValue', 'winValue + ' . $value['winValue'], FALSE);
                    $this->db->set('lossValue', 'lossValue + ' . $value['lossValue'], FALSE);
                    $this->db->where('userId', $userId);
                    $this->db->where('marketId', $marketId);
                    $this->db->where('selectionId', $value['selectionId']);
                    $this->db->limit(1);
                    $this->db->update('odds_profit_loss');
                }
            }else{
                //insert new values
                foreach ($res as $key => $value) {
                    $insertData = array(
                        'userId' => $value['userId'],
                        'parentId' => $value['parentId'],
                        'matchId' => $value['matchId'],
                        'selectionId' => $value['selectionId'],
                        'marketId' => $value['marketId'],
                        'selectionName' => $value['selectionName'],
                        'teamType' => $value['teamType'],
                        'winValue' => $value['winValue'],
                        'lossValue' => $value['lossValue'],
                        'admin' => $value['admin'],
                        'master' => $value['master'],
                        'dealer' => $value['dealer'],
                    );

                    $this->db->insert('odds_profit_loss', $insertData);
                }
            }
        }
        return true;
    }

    function getOddsProfitLoss($MarketId,$userId,$userType,$matchId)
    {

        $query =$this->db->query("call SP_getOddsProfitLoss($userId,$userType,$matchId,$MarketId)");
        $res = $query->result_array();
        $query->next_result();
        $query->free_result();
        return $res;

    }

    function updateOddsProfitLossOnBetDelete($betId, $marketId, $tableName = 'tblbets_bak'){
        //Here we fetch deleted bet data from tblbets_cancelled table
        $query = "SELECT 
					`b`.`MstCode` AS `bet_id`, `a`.`matchId` AS `matchId`, `a`.`selectionId` AS `selectionId`, `a`.`marketId` AS `marketId`, `b`.`UserId` AS `userId`, 
					CASE
						WHEN (`b`.`SelectionId` = `a`.`selectionId`)
						THEN
							(CASE WHEN (`b`.`isBack` = 0) THEN `b`.`P_L` ELSE -(`b`.`P_L`) END)
						ELSE
							0
					END AS `winValue`,
					CASE
						WHEN (`b`.`SelectionId` = `a`.`selectionId`)
						THEN
							0
						ELSE
							(CASE WHEN (`b`.`isBack` = 0) THEN -(`b`.`Stack`) ELSE `b`.`Stack` END)
					END AS `lossValue`				 
				FROM 
					`tblselection` `a` 
					INNER JOIN $tableName `b` ON(`a`.`marketId` = `b`.`MarketId` AND `b`.`IsMatched` = 1)	
				WHERE 
					b.MstCode = $betId AND b.MarketId = $marketId;";

        $query =$this->db->query($query);
        $res = $query->result_array();
       // echo $this->db->last_query();die;
        if(!empty($res)){
            //update existing values with subtract values which deleted
            foreach ($res as $key => $value) {
                $this->db->set('winValue', 'winValue - ' . $value['winValue'], FALSE);
                $this->db->set('lossValue', 'lossValue - ' . $value['lossValue'], FALSE);
                $this->db->where('userId', $value['userId']);
                $this->db->where('marketId', $marketId);
                $this->db->where('selectionId', $value['selectionId']);
                $this->db->limit(1);
                $this->db->update('odds_profit_loss');
            }
        }
        return true;
    }

    function updateUserFancyLiabilityOnBetDelete($deletedBetId = 0, $tableName = 'bet_entry_bak'){

        //first get bet data from deleted bet
        //then find latest bet of this user for this fancy
        //if bet found then update calculation with latest bet id else update with 0 liabilities by passing bet id 0

        $this->db->select("*");
        $this->db->from($tableName);
        $this->db->where('bet_id', $deletedBetId);
        $this->db->limit(1);
        $query = $this->db->get();
        $res = $query->result_array();
        if(!empty($res)){

            $userId = $res[0]['userId'];
            $matchId = $res[0]['matchId'];
            $sessionId = $res[0]['fancyId'];
            $betId = 0;

            $this->db->select("bet_id");
            $this->db->from('bet_entry');
            $this->db->where('userId', $userId);
            $this->db->where('matchId', $matchId);
            $this->db->where('fancyId', $sessionId);

            if($tableName == 'bet_entry'){
                $this->db->where('bet_id !=', $deletedBetId);
            }

            $this->db->order_by("bet_id",'DESC');
            $this->db->limit(1);
            $query1 = $this->db->get();
            $res1 = $query1->result_array();

            if(!empty($res1)){
                $betId = $res1[0]['bet_id'];
            }
            $Sessionmodel = $this->model_load_model('Sessionmodel');
            $retData = $Sessionmodel->calculateUserScorePosition($userId, $matchId, $sessionId, $betId);

        }

        return true;
    }

    function updateOddsProfitLossOnBetVoid($betId, $marketId,$tableName = 'void_tblbets'){
        //Here we fetch deleted bet data from tblbets_cancelled table
        $query = "SELECT 
					`b`.`MstCode` AS `bet_id`, `a`.`matchId` AS `matchId`, `a`.`selectionId` AS `selectionId`, `a`.`marketId` AS `marketId`, `b`.`UserId` AS `userId`, 
					CASE
						WHEN (`b`.`SelectionId` = `a`.`selectionId`)
						THEN
							(CASE WHEN (`b`.`isBack` = 0) THEN `b`.`P_L` ELSE -(`b`.`P_L`) END)
						ELSE
							0
					END AS `winValue`,
					CASE
						WHEN (`b`.`SelectionId` = `a`.`selectionId`)
						THEN
							0
						ELSE
							(CASE WHEN (`b`.`isBack` = 0) THEN -(`b`.`Stack`) ELSE `b`.`Stack` END)
					END AS `lossValue`				 
				FROM 
					`tblselection` `a` 
					INNER JOIN $tableName `b` ON(`a`.`marketId` = `b`.`MarketId` AND `b`.`IsMatched` = 1)	
				WHERE 
					b.MstCode = $betId AND b.MarketId = $marketId;";


        $query =$this->db->query($query);
        $res = $query->result_array();
        if(!empty($res)){
            //update existing values with subtract values which deleted
            foreach ($res as $key => $value) {
                $this->db->set('winValue', 'winValue - ' . $value['winValue'], FALSE);
                $this->db->set('lossValue', 'lossValue - ' . $value['lossValue'], FALSE);
                $this->db->where('userId', $value['userId']);
                $this->db->where('marketId', $marketId);
                $this->db->where('selectionId', $value['selectionId']);
                $this->db->limit(1);
                $this->db->update('odds_profit_loss');
            }
        }
        return true;
    }

    function updateUserFancyLiabilityOnBetVoid($betId, $marketId,$tableName = 'void_bet_entry'){
        //Here we fetch deleted bet data from tblbets_cancelled table
        $query = "SELECT 
					`b`.`MstCode` AS `bet_id`, `a`.`matchId` AS `matchId`, `a`.`selectionId` AS `selectionId`, `a`.`marketId` AS `marketId`, `b`.`UserId` AS `userId`, 
					CASE
						WHEN (`b`.`SelectionId` = `a`.`selectionId`)
						THEN
							(CASE WHEN (`b`.`isBack` = 0) THEN `b`.`P_L` ELSE -(`b`.`P_L`) END)
						ELSE
							0
					END AS `winValue`,
					CASE
						WHEN (`b`.`SelectionId` = `a`.`selectionId`)
						THEN
							0
						ELSE
							(CASE WHEN (`b`.`isBack` = 0) THEN -(`b`.`Stack`) ELSE `b`.`Stack` END)
					END AS `lossValue`				 
				FROM 
					`tblselection` `a` 
					INNER JOIN $tableName `b` ON(`a`.`marketId` = `b`.`MarketId` AND `b`.`IsMatched` = 1)	
				WHERE 
					b.MstCode = $betId AND b.MarketId = $marketId;";


        $query =$this->db->query($query);
        $res = $query->result_array();
        if(!empty($res)){
            //update existing values with subtract values which deleted
            foreach ($res as $key => $value) {
                $this->db->set('winValue', 'winValue - ' . $value['winValue'], FALSE);
                $this->db->set('lossValue', 'lossValue - ' . $value['lossValue'], FALSE);
                $this->db->where('userId', $value['userId']);
                $this->db->where('marketId', $marketId);
                $this->db->where('selectionId', $value['selectionId']);
                $this->db->limit(1);
                $this->db->update('odds_profit_loss');
            }
        }
        return true;
    }

    function updateOddsProfitLossOnBetMoveUnmatchToMatch( $betId){

        $query = "SELECT 
				`b`.`MstCode` AS `bet_id`, `a`.`matchId` AS `matchId`, `a`.`selectionId` AS `selectionId`, `a`.`marketId` AS `marketId`, `a`.`selectionName` AS `selectionName`, `b`.`UserId` AS `userId`, `b`.`ParantId` AS `parentId`, `a`.`teamType` AS `teamType`, `b`.`Admin` AS `admin`, `b`.`Master` AS `master`, `b`.`Dealer` AS `dealer`, 
				CASE
					WHEN (`b`.`SelectionId` = `a`.`selectionId`)
					THEN
						(CASE WHEN (`b`.`isBack` = 0) THEN `b`.`P_L` ELSE -(`b`.`P_L`) END)
					ELSE
						0
				END AS `winValue`,
				CASE
					WHEN (`b`.`SelectionId` = `a`.`selectionId`)
					THEN
						0
					ELSE
						(CASE WHEN (`b`.`isBack` = 0) THEN -(`b`.`Stack`) ELSE `b`.`Stack` END)
				END AS `lossValue`				 
			FROM 
				`tblselection` `a` 
				INNER JOIN `tblbets` `b` ON(`a`.`marketId` = `b`.`MarketId`  and `b`.`MatchedDate` is null)	
			WHERE  b.MstCode = $betId;";

        $query =$this->db->query($query);
        $res = $query->result_array();

        if(!empty($res)){

            $this->db->select("id");
            $this->db->from('odds_profit_loss');
            $this->db->where('userId', $res[0]['userId']);
            $this->db->where('marketId', $res[0]['marketId']);
            $this->db->limit(1);
            $q = $this->db->get();
            $rowCount = $q->num_rows();

            if($rowCount > 0){
                //update existing values
                foreach ($res as $key => $value) {
                    $this->db->set('winValue', 'winValue + ' . $value['winValue'], FALSE);
                    $this->db->set('lossValue', 'lossValue + ' . $value['lossValue'], FALSE);
                    $this->db->where('userId', $res[0]['userId']);
                    $this->db->where('marketId', $res[0]['marketId']);
                    $this->db->where('selectionId', $value['selectionId']);
                    $this->db->limit(1);
                    $this->db->update('odds_profit_loss');
                }
            }else{
                //insert new values
                foreach ($res as $key => $value) {
                    $insertData = array(
                        'userId' => $value['userId'],
                        'parentId' => $value['parentId'],
                        'matchId' => $value['matchId'],
                        'selectionId' => $value['selectionId'],
                        'marketId' => $value['marketId'],
                        'selectionName' => $value['selectionName'],
                        'teamType' => $value['teamType'],
                        'winValue' => $value['winValue'],
                        'lossValue' => $value['lossValue'],
                        'admin' => $value['admin'],
                        'master' => $value['master'],
                        'dealer' => $value['dealer'],
                    );

                    $this->db->insert('odds_profit_loss', $insertData);
                }
            }
        }
        return true;
    }
}