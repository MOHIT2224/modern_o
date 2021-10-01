<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apicontroller extends CI_Controller {

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
//		       $allowAuth = array('chkLoginUser','getMarketListing','matchLstIndianSessionPublic');
        $allowAuth = array('chkLoginUser','matchLstIndianSessionPublic');
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

    function chkLoginUser(){

        $this->load->model('Modeltblconfig');
        $this->load->model('Modelcreatemaster');
        $this->load->model('Chip_model');

        $response = array();
        $user_data=$this->Modelchkuser->chkAuthName();

        if ($user_data['iType']==0) {

            if($user_data['mstrid']==1){
                $getToken=$this->getACookie();
                $this->Modelcreatemaster->saveBetfairToken($getToken);
            }else{
                $getToken = $this->Modelcreatemaster->findBetfairToken();
            }

            $this->session->set_userdata('TokenId', $getToken);

            $data['User']['type'] = $user_data['usetype'];

            $data['User']['user_name'] = $user_data['mstruserid'];

            $data['User']['user_id'] = $user_data['mstrid'];

            $data['User']['betfair_session_token'] = $this->Modelcreatemaster->findBetfairToken();

            $data['User']['betfair_app_key'] = BETFAIR_APP_KEY;

            //	$data['User']['error'] = $user_data['iType'];

            //	$data['User']['message'] = $user_data['Msg'];

            //	$data['User']['ChangePas'] = $user_data['ChangePas'];

            //	$data['User']['TokenId'] = "yPAFq7YCIi/nVwwwGe1vr2IM/v+LtGxRvEhmHyzTbx8=";

            //	$data['User']['set_timeout'] = $user_data['set_timeout'];

            //	$data['User']['lgnstatus'] = $this->session->userdata('session_id');

            //	$data['User']['last_login_id'] = $this->session->userdata('last_login_id');

            $data['User']['mstrpassword'] = $user_data['mstrpassword'];

            $terms = $this->Modeltblconfig->find();

            $data['User']['terms_conditions'] = $terms[0]['terms_conditions'];

            $balance = $this->Chip_model->getLiability($user_data['mstrid']);

            $data['UserBalance']['Liability'] = $balance[0]['Liability'];
            $data['UserBalance']['Balance'] = $balance[0]['Balance'];

            $userSetting = $this->Modelcreatemaster->viewUserAcData($user_data['mstrid']);
            $data['UserSetting'] = $userSetting[0];

            $response["code"] = 0;
            $response["error"] = false;
            $response["message"] = "successfully login";
            $response["data"] = $data;
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

        }else{

            $response["code"] = $user_data['iType'];
            $response["error"] = true;
            $response["message"] = $user_data['Msg'];
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }

    }

    function getChipDataById()
    {
        $response = array();
        $this->load->model('Chip_model');
        $this->load->model('Betentrymodel');

        $userId1 = $this->globalUserId;
        $balance = $this->Chip_model->getLiability($userId1);

        $data['Liability'] = $balance[0]['Liability'];
        $data['Balance'] = $balance[0]['Balance'];
        $data['marqueMsg'] = $this->Betentrymodel->DisplayMsgOnHeader();

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "user balance";
        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function changePassword(){
        if ($_POST['newpassword']==$_POST['Renewpassword']) {
            $userId = $this->globalUserId;
            $query=$this->Modelcreatemaster->changeClientPassword($userId);
            if ($query) {
                $passwordData = array('mstrpassword' => sha1($_POST['newpassword']));
                $response["code"] = 0;
                $response["error"] = false;
                $response["message"] = "Password Change Successfully";
                $response["data"] = $passwordData;
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }else{
                $response["code"] = ERROR_INVALID_PASSWORD;
                $response["error"] = true;
                $response["message"] = ERROR_INVALID_PASSWORD_MSG;
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }
        }else{
            $response["code"] = ERROR_PASSWORD_MATCH;
            $response["error"] = true;
            $response["message"] = ERROR_PASSWORD_MATCH_MSG;
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    function getUserMatchLst($sportId){

        $response = array();
        $this->load->model('Modeleventlst');

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "match listing";

        if($sportId == 0 || $sportId == 4){

            $dbMatches = $this->Modeleventlst->mobileGetUserMatchLst(BETFAIR_SPORT_CRICKET);

            /*	$sessionToken = $this->Modelcreatemaster->findBetfairToken();
                $MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
                $betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
                $betfairMatches = json_decode($betfairMatchesJson,true); */

            $cricketUrl = BR_LIVE_MATCHES_ODDS_URL . 'event_id='.$sportId;
            $matchesJson = $this->httpGet($cricketUrl);
            $matches = json_decode($matchesJson,true);

            $marketIds = array();
            foreach($matches['result'] as $match){
                //	if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
                $marketIds[] = $match['id'];
                //	}
            }

            $filterMatches = array();
            foreach($dbMatches as $dbMatch){
                $dbMarkets = explode(',', $dbMatch['marketid']);
                $matched = array_intersect($marketIds,$dbMarkets);
                if(!empty($matched)){
                    $filterMatches[] = $dbMatch;
                }
            }

            $cricket = array();
            $cricket['sportname'] = 'Cricket';
            $cricket['SportId'] = BETFAIR_SPORT_CRICKET;
            $cricket['values'] = $filterMatches;
            $response["data"][] = $cricket;
        }

        if($sportId == 0 || $sportId == 2){

            $dbMatches = $this->Modeleventlst->mobileGetUserMatchLst(BETFAIR_SPORT_TENNIS);
            $sessionToken = $this->Modelcreatemaster->findBetfairToken();
            $MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
            $betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
            $betfairMatches = json_decode($betfairMatchesJson,true);
            $marketIds = array();
            foreach($betfairMatches as $bfmatch){
                if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
                    $marketIds[] = $bfmatch['marketId'];
                }
            }

            $filterMatches = array();
            foreach($dbMatches as $dbMatch){
                $dbMarkets = explode(',', $dbMatch['marketid']);
                $matched = array_intersect($marketIds,$dbMarkets);
                if(!empty($matched)){
                    $filterMatches[] = $dbMatch;
                }
            }

            $tennis = array();
            $tennis['sportname'] = 'Tennis';
            $tennis['SportId'] = BETFAIR_SPORT_TENNIS;
            $tennis['values'] = $filterMatches;
            $response["data"][] = $tennis;
        }

        if($sportId == 0 || $sportId == 1){

            $dbMatches = $this->Modeleventlst->mobileGetUserMatchLst(BETFAIR_SPORT_SOCCER);
            $sessionToken = $this->Modelcreatemaster->findBetfairToken();
            $MarketId1 = $this->Modeleventlst->getUserMatchOdds($sportId);
            $betfairMatchesJson = $this->getMarketBookUser($this->APP_KEY, $sessionToken, $MarketId1[0]['ids']);
            $betfairMatches = json_decode($betfairMatchesJson,true);
            $marketIds = array();
            foreach($betfairMatches as $bfmatch){
                if(isset($bfmatch['status']) && $bfmatch['status'] == 'OPEN'){
                    $marketIds[] = $bfmatch['marketId'];
                }
            }

            $filterMatches = array();
            foreach($dbMatches as $dbMatch){
                $dbMarkets = explode(',', $dbMatch['marketid']);
                $matched = array_intersect($marketIds,$dbMarkets);
                if(!empty($matched)){
                    $filterMatches[] = $dbMatch;
                }
            }

            $soccer = array();
            $soccer['sportname'] = 'Soccer';
            $soccer['SportId'] = BETFAIR_SPORT_SOCCER;
            $soccer['values'] = $filterMatches;
            $response["data"][] = $soccer;
        }

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function getMarketLst($matchId){
        $response = array();
        $this->load->model('Modeleventlst');
        $this->load->model('Modelcreatemaster');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "market listing";
        $data = $this->Modeleventlst->mobileGetMrktByMatchId($matchId);

        if(!empty($data[0])){
            $temp  = array();
            $temp = array_merge($temp,$data[0]);
            $marketArr = explode(',', $data[0]['marketid']);
            $marketJson = json_encode($marketArr);
            $backLayOdds = '{"marketIds":'.$marketJson.',"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}';

            $sessionToken = $this->Modelcreatemaster->findBetfairToken();

            $matchArr = array($matchId);
            $volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchArr);
            $matchOdds = array();
            $marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $backLayOdds);

            $marketRunnerArr = json_decode($marketRunnerJson,true);
            $matchOdds['marketRunner'] = $marketRunnerArr;
            if(!empty($volumeLimit)){
                $matchOdds['volumeLimit'] = $volumeLimit;
            }
            $temp = array_merge($temp,$matchOdds);


            $selectionParams = '{"filter":{"marketIds":'.$marketJson.'},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}';
            $selection = array();
            $selectionJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $selectionParams);
            $selectionArr = json_decode($selectionJson,true);
            $selection['selection'] = $selectionArr;
            $temp = array_merge($temp,$selection);

            $response["data"] = $temp;
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }


    function getMarketListing($matchId){

        $userId = $this->globalUserId;
        $userType = $this->globalUserType;

        $response = array();
        $this->load->model('Modeleventlst');
        $this->load->model('Modelcreatemaster');
        $this->load->model('Modelmatchmst');

        $userSetting = $this->Modelcreatemaster->viewUserAcData($userId);

        if(!empty($userSetting[0]['match_stake'])){
            $match = $userSetting[0]['match_stake'];
            $session = $userSetting[0]['match_stake'];
        }else{
            $match = MATCH_STAKE_OPTION;
            $session = SESSION_STAKE_OPTION;
        }

        if(!empty($userSetting[0]['one_click_stake'])){
            $oneclick = $userSetting[0]['one_click_stake'];
        }else{
            $oneclick = ONE_CLICK_STAKE_OPTION;
        }

        $matchStacks = json_decode($match,true);
        $sessionStacks = json_decode($session,true);
        $oneClickStacks = json_decode($oneclick,true);

        $response["code"] = 0;
        $response["isManualMatchOdds"] = 0;
        $response["error"] = false;
        $response["message"] = "market listing";
        $response["match_stack"] = $matchStacks;
        $response["session_stack"] = $sessionStacks;
        $response["one_click_stack"] = $oneClickStacks;

        $data = $this->Modeleventlst->mobileGetFavMrktByMatchId($matchId,$userId,$userType);

        $resultDeclare = $this->Modelmatchmst->result($matchId);

		$scoreboard_id = "";
		$random_var = true;
        if(!empty($data)){

           $matchOddIndex =  array_search('Match Odds', array_column($data, 'market_name'));
            $tempData = $data[$matchOddIndex];
            unset($data[$matchOddIndex]);

            usort($data, function  ($x, $y) {
                return strcasecmp($x['market_name'], $y['market_name']);
            });

            $data =  array_merge(array($tempData),$data);

            foreach($data as $mdata){
				if($random_var){
					$scoreboard_id = $mdata['scoreboard_id'];
					$random_var = false;
				}
                $dbMatch = array('matchName'=>$mdata['matchName'],'scoreboard_id'=>$mdata['scoreboard_id'],'series_name'=>$mdata['series_name'],'matchid'=>$mdata['matchid'],'marketid'=>$mdata['marketid'],'visibility'=>$mdata['visibility'],'market_name'=>$mdata['market_name'],'matchdate'=>$mdata['MstDate'],'sportname'=>$mdata['sportname'],'is_favourite'=>$mdata['is_favourite'],'SportId'=>$mdata['SportId'],'max_bet_liability'=>$mdata['max_bet_liability'],'max_market_liability'=>$mdata['max_market_liability'],'is_manual'=>$mdata['is_manual'],'score_board_json'=>json_decode($mdata['score_board_json']),'isManualMatchOdds'=>$mdata['isManualMatchOdds'],'isBetAllowedOnManualMatchOdds'=>$mdata['isBetAllowedOnManualMatchOdds'],'day'=>$mdata['day'],'IsRs'=>(boolean)$mdata['IsRs']);

                if($mdata['SportId']==4){
                    $dbMatch['socket_url'] = $mdata['cricket_socket_url'];
                    $dbMatch['sport_image'] = $mdata['cricket_sport_image'];
                }elseif ($mdata['SportId']==2) {
                    $dbMatch['socket_url'] = $mdata['tennis_socket_url'];
                    $dbMatch['sport_image'] = $mdata['tennis_sport_image'];
                }elseif ($mdata['SportId']==1) {
                    $dbMatch['socket_url'] = $mdata['soccer_socket_url'];
                    $dbMatch['sport_image'] = $mdata['soccer_sport_image'];
                }
                $dbMatch['is_auto']='A';
                $temp  = array();
                $temp = array_merge($temp,$dbMatch);

                /*	$marketArr = explode(',', $mdata['marketid']);
                    $matchOddmarketId = $marketArr[0];
                    $marketJson = json_encode($marketArr); */

                $matchArr = array($matchId);
                $volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchArr);

                if(!empty($mdata['runner_json'])){
                    $runnerArr = json_decode($mdata['runner_json'],true);
                    $backArr = array();
                    $backArr = @$runnerArr[0]['back'];
                    if(@$backArr[0]['price']=='--'){
                        $matchOdds['runners'] = $runnerArr;
                        $matchOdds['IsMatchDisable'] = true;
                    }else{
                        $matchOdds['runners'] = $runnerArr;
                        $matchOdds['IsMatchDisable'] = true;
                    }
                }
                else{
                    $matchOdds['runners'] = array();
                    $matchOdds['IsMatchDisable'] = true;
                }

                if(!empty($volumeLimit)){
                    $matchOdds['volumeLimit'] = $volumeLimit;
                }
                $temp = array_merge($temp,$matchOdds);


                $selection['selection'] = array();
                $temp = array_merge($temp,$selection);

                $temp = array_merge($temp,array('result'=>$resultDeclare));


                if($dbMatch['is_manual'] ==0 and $mdata['isManualMatchOdds']==1 and $this->session->userdata('type') == 0){
                    $temp['manual_market_data']=$this->Modelcreatemaster->manualMatchOddsDetails($dbMatch['marketid']);

                }
                if($dbMatch['is_manual'] ==0 ){


                    $temp['id']=$temp['marketid'];
                    $response["data"][] =$temp ;
                }

                if($this->session->userdata('type') == 0 and $dbMatch['is_manual']){
                    $temp['manual_market_data']=$this->Modelcreatemaster->manualMatchOddsDetails($dbMatch['marketid']);
                    $runnerArray = json_decode($mdata['runner_json'], true);

                    $manualMatchOddsDetails = $temp['manual_market_data'];

                    foreach ($runnerArray as $key => $runnerRec) {
                        $key = $key + 1;
                        if ($key <= 2) {
                            $runner[] = ['id' => $runnerRec['id'], 'name' => @$runnerRec['name'], 'back' => [['price' => $manualMatchOddsDetails['team' . $key . '_back'] - 0, 'size' => '']], 'lay' => [['price' => $manualMatchOddsDetails['team' . $key . '_lay'] - 0, 'size' => '']], 'active' => $manualMatchOddsDetails['active_team' . $key]];
                        } else {
                            $runner[] = ['id' => $runnerRec['id'], 'name' => @$runnerRec['name'], 'back' => [['price' => $manualMatchOddsDetails['draw_back'] - 0, 'size' => '']], 'lay' => [['price' => $manualMatchOddsDetails['draw_lay'] - 0, 'size' => '']], 'active' => $manualMatchOddsDetails['active_draw']];
                        }


                    }

                    $temp['runners']=$runner;
                    $response["selection"] = $runnerArray;
                    $temp['id']=$temp['marketid'];
                    $response["data"][] =$temp ;
                }


                if ($this->session->userdata('type') != 0) {
                    if ($mdata['isManualMatchOdds'] == 1) {

                        $runner = [];
                        $runnerArray = json_decode($mdata['runner_json'], true);

                        $manualMatchOddsDetails = $this->Modelcreatemaster->manualMatchOddsDetails($mdata['marketid']);

                        if (!empty($runnerArray)) {
                            foreach ($runnerArray as $key => $runnerRec) {
                                $key = $key + 1;
                                if ($key <= 2) {
                                    $runner[] = ['id' => $runnerRec['id'], 'name' => @$runnerRec['name'], 'back' => [['price' => max(0,$manualMatchOddsDetails['team' . $key . '_back'] - 0), 'size' => '']], 'lay' => [['price' => max(0,$manualMatchOddsDetails['team' . $key . '_lay'] - 0), 'size' => '']], 'active' => $manualMatchOddsDetails['active_team' . $key]];
                                } else {
                                    $runner[] = ['id' => $runnerRec['id'], 'name' => @$runnerRec['name'], 'back' => [['price' => max(0,$manualMatchOddsDetails['draw_back'] - 0), 'size' => '']], 'lay' => [['price' => max(0,$manualMatchOddsDetails['draw_lay'] - 0), 'size' => '']], 'active' => $manualMatchOddsDetails['active_draw']];
                                }


                            }
                        }

                    $manualMatchOddsDetailsTemp = $temp;
                    $manualMatchOddsDetailsTemp['runners'] = $runner;
                    $manualMatchOddsDetailsTemp['is_auto'] = 'M';
                    $manualMatchOddsDetailsTemp['market_name'] = 'Book Maker '.$manualMatchOddsDetailsTemp['market_name'];
                    $manualMatchOddsDetailsTemp["isBetAllowedOnManualMatchOdds"] = $mdata['isBetAllowedOnManualMatchOdds'];
                    $manualMatchOddsDetailsTemp["dlay_time"] = $manualMatchOddsDetails['dlay_time'];
                    $manualMatchOddsDetailsTemp["isManualMatchOdds"] = 1;
                    $response["data"][] = $manualMatchOddsDetailsTemp;
                    $response["selection"] = $runnerArray;
                }
            }

            }
            $response["is_manual"] = $mdata['is_manual'];
            $response["scoreboard_id"] = $scoreboard_id;



        }else{
            $response["error"] = true;
            $response["message"] = "match deactive";
            $temp = array('marketid'=>'','volumeLimit'=>array(),'marketRunner'=>array(),'selection'=>array(),'result'=>$resultDeclare);



            $response["data"] = $temp;
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }



    function getBetfairSessionToken(){
        $response = array();
        $this->load->model('Modelcreatemaster');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "betfair session token";
        $getToken = $this->getACookie();
        $this->Modelcreatemaster->saveBetfairToken($getToken);
        $data = array('betfair_session_token' => $getToken);
        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * [getBackLaysOfMarketSelectionName description]
     * params {"filter":{"marketIds":["1.144156561","1.144156560"]},"maxResults":"100","marketProjection":["MARKET_START_TIME", "RUNNER_DESCRIPTION"]}
     * @return [type] [description]
     */
    function getBackLaysOfMarketSelectionName(){

        $this->load->model('Modelcreatemaster');

        if (!empty($_POST)){
            $sessionToken = $this->Modelcreatemaster->findBetfairToken();
            $params =  json_encode($_POST);
            $data = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketCatalogue', $params);
            $this->output->set_content_type('application/json')->set_output(($data));
        }

    }


    /**
     * [getBackLaysOfMarket description]
     * params json  {"marketIds":["1.144156561","1.144156560"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"}
     * @return [type] [description]
     */
    function getBackLaysOfMarket(){

        $this->load->model('Modelcreatemaster');

        if (!empty($_POST)){
            $sessionToken = $this->Modelcreatemaster->findBetfairToken();
            $params =  json_encode($_POST);
            $data = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $params);
            $this->output->set_content_type('application/json')->set_output(($data));
        }
    }

    function Save_bet(){

        $data = $_POST;
        $data['loginId'] = $this->globalUserId;
        $data['UserTypeId'] = $this->globalUserType;
        $data['ApiVal'] = 0;

        $this->load->model('Betentrymodel');

        $response = array();

        $validate = $this->Betentrymodel->mobileValidateSaveBet($data);

        if(!empty($validate)){
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($validate));
            return false;
        }

        $condition=$this->Betentrymodel->mobileSave_bet($data);

        if ($condition[0]['resultV']==0) {
            $rvData['RunnerValue']=$this->Betentrymodel->sumOfOdds($data['MarketId'],$data['loginId'],$data['UserTypeId'],$data['matchId']);
            $response["code"] = 0;
            $response["error"] = false;
            $response["message"] = $condition[0]['retMess'];
            $response["data"] = $rvData;
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }else {
            $response["code"] = $condition[0]['resultV'];
            $response["error"] = true;
            $response["message"] = $condition[0]['retMess'];
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    function marketBets($matchId,$marketId){

        $userId = $this->globalUserId;

        $response = array();
        $this->load->model('Betentrymodel');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Matched/Unmatched bets listing";

        $data = array();
        $data['matched'] = $this->Betentrymodel->mobileMarketMatchedBets($userId,$matchId,$marketId);
        $data['unmatched'] = $this->Betentrymodel->mobileMarketUnMatchedBets($userId,$matchId,$marketId);

        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function deleteGetbetting($betId){

        $this->load->model('Betentrymodel');

        $response = array();
        $userId = $this->globalUserId;

        $condition=$this->Betentrymodel->deleteGetbetting($betId,$userId);

        if (isset($condition[0]['resultV']) && $condition[0]['resultV']==0) {
            $response["code"] = 0;
            $response["error"] = false;
            $response["message"] = "Record deleted Successfully...";
        }else{
            $response["code"] = ERROR_DELETE;
            $response["error"] = true;
            $response["message"] = ERROR_DELETE_MSG;
        }

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

    }

    function updateUnMatchedData($betId){

        $response = array();
        $this->load->model('Betentrymodel');
        $loginId = $this->globalUserId;
        $userTypeId = $this->globalUserType;

        $condition=$this->Betentrymodel->updateUnMatchedData($betId);
        if ($condition) {
//					$data['betUserData']=$this->Betentrymodel->getBetEntry($marketId,$userTypeId,$loginId,$matchId);
            $response["code"] = 0;
            $response["error"] = false;
            $response["message"] = 'Bet matched successfully';
            //	$response["data"] = $data;
        }else{
            $response["code"] = ERROR_TRY_AGAIN;
            $response["error"] = true;
            $response["message"] = ERROR_TRY_AGAIN_MSG;
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function getUserSetting(){

        $response = array();
        $this->load->model('Modelcreatemaster');
        $userId1 = $this->globalUserId;

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "User settings";
        $userSetting = $this->Modelcreatemaster->viewUserAcData($userId1);
        $response["data"] = $userSetting[0];
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function allUnmatchedBets(){

        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Betentrymodel');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "All unmatched bets listing";
        $data = $this->Betentrymodel->mobileAllUnMatchedBets($userId);

        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function myBets($betType,$pageno,$limit){
        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Betentrymodel');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "My bets listing";
        $response["data"] = $this->Betentrymodel->myBetsPaging($userId,$betType,$pageno,$limit);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function acStatement($pageno,$limit){
        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Betentrymodel');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Acount statement listing";
        $response["data"] = $this->Betentrymodel->mbdip_acStatement($userId,$pageno,$limit);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function matchMarketLst($matchId){
        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Modeleventlst');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Session fancy listing";
        $response["data"] = $this->Modeleventlst->mbdip_matchFancyList($matchId,$userId);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function market_win_loss(){

        $post = $_POST;

        $post['loginId'] = $this->globalUserId;
        $post['UserTypeId'] = $this->globalUserType;
        $post['ApiVal'] = 0;

        $this->load->model('Betentrymodel');

        $data = array();
        $marketIds = $post['MarketId'];
        $matchId = $post['matchId'];
        $marketArr = explode(',', $marketIds);

        foreach($marketArr as $key=>$marketId){
            $temp = array();
            $temp['marketId'] = $marketId;
           // $runners = $this->Betentrymodel->sumOfOdds($marketId,$post['loginId'],$post['UserTypeId'],$matchId);
            $runners = $this->Betentrymodel->getOddsProfitLoss($marketId,$post['loginId'],$post['UserTypeId'],$matchId);
            $formatRunner = array();
            foreach($runners as $runner){
                $formatRunner[] = array('winValue'=>$runner['winValue'],'lossValue'=>$runner['lossValue'],'selectionId'=>$runner['SelectionId']);
            }
            $temp['runners'] = $formatRunner;
            $data[] = $temp;
        }

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = 'market profit and loss';
        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function update_market_win_loss(){

        $post = $_POST;

        $post['loginId'] = $this->globalUserId;
        $post['UserTypeId'] = $this->globalUserType;
        $post['ApiVal'] = 0;

        $this->load->model('Betentrymodel');
        $this->load->model('Modelcreatemaster');

        $this->Modelcreatemaster->updateUserBalLiablity($this->globalUserId);

        $data = array();
        $marketIds = $post['MarketId'];
        $matchId = $post['matchId'];
        $marketArr = explode(',', $marketIds);

        foreach($marketArr as $key=>$marketId){
            $temp = array();
            $temp['marketId'] = $marketId;
            //$runners = $this->Betentrymodel->sumOfOdds($marketId,$post['loginId'],$post['UserTypeId'],$matchId);
            $runners = $this->Betentrymodel->getOddsProfitLoss($marketId,$post['loginId'],$post['UserTypeId'],$matchId);
            $formatRunner = array();
            foreach($runners as $runner){
                $formatRunner[] = array('winValue'=>$runner['winValue'],'lossValue'=>$runner['lossValue'],'selectionId'=>$runner['SelectionId']);
            }
            $temp['runners'] = $formatRunner;
            $data[] = $temp;
        }

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = 'market profit and loss';
        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function save_session_bet(){

        $this->load->model('Modellstmaster');
        $post = $_POST;
        $post['loginId'] = $this->globalUserId;
        $chk=$this->Modellstmaster->ChkFancyOnBet($post['matchId'],$post['FancyID'],$post['SessInptYes'],$post['SessInptNo']);
        if ($chk[0]['resultV'] > 0) {
            $condition = $this->Modellstmaster->mbdip_save_session_bet($post);
            $response["code"] = 0;
            $response["error"] = false;
            $response["message"] = $condition[0]['retMess'];
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }else{
            $response["code"] = $chk[0]['resultV'];
            $response["error"] = true;
            $response["message"] = $chk[0]['retMess'];
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    function GetScoreApi($matchId){
        $str = file_get_contents('https://www.betfair.com/inplayservice/v1/scores?_ak=nzIFcwyWhrlwYMrh&alt=json&eventIds='.$matchId.'&locale=en');
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output($str);
    }

    /**
     * [getBackLaysOfMarketSetting description]
     * params json  {"betfair":{"marketIds":["1.144156561","1.144156560"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"},"matchId":"28763538"}
     * @return [type] [description]
     */
    function getBackLaysOfMarketSetting(){

        $this->load->model('Modelcreatemaster');

        if (!empty($_POST)){
            $sessionToken = $this->Modelcreatemaster->findBetfairToken();
            $betfairParams = json_encode($_POST['betfair']);
            $marketId = $_POST['betfair']['marketIds'][0];
            $matchId = $_POST['matchId'];
            $volumeLimit = $this->Modelcreatemaster->getMatchOddsLimit($matchId,$marketId);
            $data = array();
            /*	$marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $betfairParams);
                $marketRunnerArr = json_decode($marketRunnerJson,true);
                $data['marketRunner'] = $marketRunnerArr; */

            $data['marketRunner'] = $this->getMatchOdds($marketId);

            if(!empty($volumeLimit)){
                $data = array_merge($data,$volumeLimit[0]);
            }
            $this->output->set_content_type('application/json')->set_output((json_encode($data)));
        }
    }

    /**
     * [getBackLaysOfMarketSetting description]
     * params json  {"betfair":{"marketIds":["1.144156561","1.144156560"],"bspMarket":false, "priceProjection":{"priceData":["EX_BEST_OFFERS"],"virtualise":true},"orderProjection":"EXECUTABLE","matchProjection":"ROLLED_UP_BY_AVG_PRICE"},"matchId":["28763538"]}
     * @return [type] [description]
     */
    function getBackLaysOfMarketMatchVolume(){

        $this->load->model('Modelcreatemaster');

        if (!empty($_POST)){
            $sessionToken = $this->Modelcreatemaster->findBetfairToken();
            $betfairParams = json_encode($_POST['betfair']);
            $marketId = $_POST['betfair']['marketIds'][0];
            $matchId = $_POST['matchId'];
            $volumeLimit = $this->Modelcreatemaster->getMatchOddsLimitByMatches($matchId);
            $data = array();
            $marketRunnerJson = $this->sportsApingRequest($this->APP_KEY, $sessionToken, 'listMarketBook', $betfairParams);
            $marketRunnerArr = json_decode($marketRunnerJson,true);
            $data['marketRunner'] = $marketRunnerArr;
            if(!empty($volumeLimit)){
                $data['volumeLimit'] = $volumeLimit;
            }
            $this->output->set_content_type('application/json')->set_output((json_encode($data)));
        }
    }

    /**
     * [sessionBets session user bets listing]
     * @param  [int] $fancyId [session fancy id]
     * @return [json]          [resposne]
     */
    function sessionBets($fancyId){

        $userId = $this->globalUserId;

        $response = array();
        $this->load->model('Betentrymodel');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Matched session fancy bets listing";

        $data = array();
        $data['matched'] = $this->Betentrymodel->mobileSessionMatchedBets($userId,$fancyId);
        $data['unmatched'] = array();

        $response["data"] = $data;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * [GatBetData latest bet listing]
     * @param [int] $matchId [match id]
     */
    function GatBetData($matchId=null){
        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Betentrymodel');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Latest bet listing";
        $response["data"] = $this->Betentrymodel->mbdip_getBetEntry($userId,$matchId);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * [SetResult set match result]
     */
    function SetResult(){

        $this->load->model('Modelmarket');
        $this->load->model('Modeltblselection');
        $this->load->model('Modeleventlst');
        $this->load->model('Modeltblbets');

        /*			$post = array(array('marketId'=>1.144844337,'selectionId'=>266950),array('marketId'=>1.144844337,'selectionId'=>266950));

                    echo json_encode($post);
                    die; */

        $post = $_POST;

        foreach($post as $data){

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

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Match result saved";

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }
/**
     * [get_indian_session get indian session]
     * @return [json] [response]
     */
	 
	 
	 function get_indian_session($matchId=null){
			
			$this->load->model('Modelmarket');
			$this->load->model('Modelmatchfancy');

			$response = array();
			
			$validateMatchMarket = $this->Modelmarket->checkMarketId($matchId);

			if(!empty($validateMatchMarket)){

				$marketId = $validateMatchMarket['marketId'];
				$getSessionOdds = $this->getIndianSessionOdds($marketId);
				$sessOddArr = json_decode($getSessionOdds,true);
				
				//print_r($sessOddArr);

				$activeSess = $this->Modelmatchfancy->addedSession($matchId);



				$checkActive = array();

				$indianSession = array();
				foreach($activeSess as $aSess){
					$checkActive[] = $aSess['ind_fancy_selection_id'];		
					$tempFancy = json_decode($aSess['betfair_session_json'],true);
				//	$indianSession[] = array_merge($tempFancy,array('is_exists'=>1,'match_id'=>$matchId));	
				}
$bddf= $sessOddArr['session'][0]['value']['session'];
			 //	print_r($indianSession);
				
				if(!empty($sessOddArr['session'][0]['value']['session'])){
					foreach($sessOddArr['session'][0]['value']['session']as $sessOdd){
						if(in_array($sessOdd['SelectionId'], $checkActive)){
						//	$sessOdd = array_merge($sessOdd,array('is_exists'=>1,'match_id'=>$matchId));	
						}else{
							$sessOdd = array_merge($sessOdd,array('is_exists'=>0,'match_id'=>$matchId));	
							$indianSession[] = $sessOdd;
						}
					}
					$response["code"] = 0;
					$response["error"] = false;
	        		$response["message"] = "Session fancy listing";
					$response["data"] = $indianSession;
				}else{
					$response["code"] = 1;
					$response["error"] = true;
        			$response["message"] = 'Session fancy has not been created for this match';
				}
			}else{
				$response["code"] = 1;
				$response["error"] = true;
        		$response["message"] = 'Please actived match odd market';
			}

			$this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
			
		}
    function get_indian_session_old($matchId=null){

        $this->load->model('Modelmarket');
        $this->load->model('Modelmatchfancy');

        $response = array();
        //$matchData = $this->Modelmarket->getMatchByMarketId($matchId);

      //  print_r($matchId);die();

        $matchMarket = $this->Modelmarket->findMarketIdByMatch($matchId);

        if(!empty($matchId)){

            $marketId = $matchMarket['marketId'];

            //	$getSessionOdds = $this->getIndFancyByMatchId($matchId);

            $sessOddArr = $this->getIndFancyAdmin($marketId);

            //	print_r($sessOddArr);die;

            //	$sessOddArr = json_decode($getSessionOdds,true);

            $activeSess = $this->Modelmatchfancy->addedSession($matchId);
           // echo "<pre>"; print_r($activeSess);die;
            $checkActive = array();
            $decliredSessionIds = [];

            foreach($activeSess as $aSess){
                $checkActive[] = $aSess['ind_fancy_selection_id'];
                if($aSess['result']!=''){
                    $decliredSessionIds[] = $aSess['ind_fancy_selection_id'];
                }
            }

            //	print_r($checkActive);
            $indianSession = array();
            if(!empty($sessOddArr['data']['session'])){
                foreach($sessOddArr['data']['session'] as $sessOdd){
                    if(!in_array($sessOdd['SelectionId'], $decliredSessionIds)){
                        if(in_array($sessOdd['SelectionId'], $checkActive)){
                            $sessOdd = array_merge($sessOdd,array('is_exists'=>1,'match_id'=>$matchId));
                        }else{
                            $sessOdd = array_merge($sessOdd,array('is_exists'=>0,'match_id'=>$matchId));
                        }
                        $indianSession[] = $sessOdd;
                    }

                }
                $response["code"] = 0;
                $response["error"] = false;
                $response["message"] = "Session fancy listing";
                $response["data"] = $indianSession;
            }else{
                $response["code"] = 1;
                $response["error"] = true;
                $response["message"] = 'Session fancy has not been created for this match';
            }
        }else{
            $response["code"] = 1;
            $response["error"] = true;
            $response["message"] = 'Please actived match odd market';
        }

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));

    }

    /**
     * [save_indian_session save indian session]
     * params : {"SelectionId":"27","RunnerName":"FALL OF 1ST WKT HAMP ADV","LayPrice1":"125","LaySize1":"110","BackPrice1":"125","BackSize1":"95","GameStatus":"","FinalStatus":"OPEN","is_exists":0,"match_id":"28772000"}
     */
    function save_indian_session(){

        $post = $_POST;
        $data = array('super_admin_fancy_id'=>$post['SelectionId'],'HeadName'=>$post['RunnerName'],'remarks'=>'INDIAN_SESSION_FANCY','mid'=>$post['match_id'],'fancyType'=>2,'date'=>date('Y-m-d'),'time'=>date('H:i'),'inputYes'=> $post['BackPrice1'],'inputNo'=> $post['LayPrice1'],'sid'=>4,'NoLayRange'=>$post['LaySize1'],'YesLayRange'=>$post['BackSize1'],'RateDiff'=>1,'MaxStake'=>10000000000000000,'PointDiff'=>10,'ind_fancy_selection_id'=>$post['SelectionId']);
//print_r($data);
        $this->load->model('Modelcreatemaster');
        $this->load->model('Modelmatchfancy');


        $chkSession = $this->Modelmatchfancy->checkSessionStatus($data['mid'],$data['ind_fancy_selection_id']);

        if(empty($chkSession)){

            $id = $this->Modelcreatemaster->mb_saveFancy($data);

            try {
                $redis = new Redis();
                $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                $result = $this->Modelmatchfancy->selectFancyById($id);
                $redis->set($this->db->database.'ind_' . $post['match_id'] . '_' . $id, json_encode($result));
                $redis->close();
            } catch (Exception $e) {
            }
            if (!empty($result)) {
                $response["code"] = 0;
                $response["error"] = false;
                $response["message"] = 'Indian session saved successfully';
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }else{
                $response["code"] = 1;
                $response["error"] = true;
                $response["message"] = 'Error';
                $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
            }

        }else{
            $response["code"] = 1;
            $response["error"] = true;
            $response["message"] = 'Already exists';
            $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
        }

    }

    /**
     * [matchLstIndianSessionPublic indian session match listing]
     * @param  [int] $matchId [match id]
     * @return [json]          [response]
     */
    function matchLstIndianSessionPublic($matchId=0,$marketId=0){
        $response = array();
        $this->load->model('Modeleventlst');
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Session fancy listing";
        $sessionOdds = $this->Modeleventlst->matchFancyListPublicByMatchId($matchId);
        $response["data"] = $sessionOdds;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function userScorePosition($fancyId){
        $userId =$this->globalUserId;

        $fancy_positions = $this->Sessionmodel->userScorePosition($userId,$fancyId);

        $max_exposure=0;


        $OddsNumbers=[];
        $ResultValues=[];
        if(!empty($fancy_positions)){
            foreach ($fancy_positions as $fancy_position){
                $OddsNumbers[]= $fancy_position['OddsNumber']-1;
            }
            $OddsNumbers[]=end($fancy_positions)['OddsNumber'];
            $OddsNumbersOrg = $OddsNumbers;
            $OddsNumbers =  array_unique($OddsNumbers);
            $lastPosition = 0;
            foreach ($OddsNumbers as $OddsNumberKey=>$OddsNumber){
                $tempTotal = 0;
                foreach ($fancy_positions as $fancy_position){
                    if($fancy_position['OddValue']==1){
                        if($fancy_position['OddsNumber'] <=$OddsNumber){

                            $tempTotal-= $fancy_position['bet_price']*($fancy_position['session_no_size']/100);
                        }else{
                            $tempTotal+=$fancy_position['bet_price'];
                        }

                    }else{

                        if($fancy_position['OddsNumber'] >$OddsNumber){

                            $tempTotal-=$fancy_position['bet_price'];

                        }else{
                            $tempTotal+=$fancy_position['bet_price']*($fancy_position['session_yes_size']/100);
                        }

                    }

                }
                if(count($OddsNumbersOrg)-1 == $OddsNumberKey){
                    $ResultValues[]=array('SessInptYes'=>$lastPosition.'+','ResultValue'=>$tempTotal);
                }else{
                    if($lastPosition==$OddsNumber){
                        $ResultValues[]=array('SessInptYes'=>$lastPosition,'ResultValue'=>$tempTotal);
                    }else{
                        $ResultValues[]=array('SessInptYes'=>$lastPosition.'-'.$OddsNumber,'ResultValue'=>$tempTotal);
                    }

                }

                $lastPosition = $OddsNumber+1;
                if($max_exposure> $tempTotal){
                    $max_exposure= $tempTotal;
                }
            }

        }

        $sessionOdd['fancy_position'] = $ResultValues;
        $sessionOdd['max_exposure'] = $max_exposure;

        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($sessionOdd));
    }

    /**
     * [matchLstIndianSession indian session match listing]
     * @param  [int] $matchId [match id]
     * @return [json]          [response]
     */
    function matchLstIndianSession($matchId=0,$marketId=0){


        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Modeleventlst');
        $this->load->model('Modelmatchfancy');
        //	$this->load->model('Modelmarket');
        $devicetype = $this->input->request_headers();
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Session fancy listing";

        //$sessionOdds = $this->Modelmatchfancy->getUserFancyList($userId,$matchId);
        try {
            $redis = new Redis();
            $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
           $key = $this->db->database.'ind_' . $matchId . '*';
           $sessionOdds = $redis->mget($redis->keys($key));

            $redis->close();
        } catch (Exception $e) {
        }
        $temp = [];
        if(!empty($sessionOdds)){

            foreach ($sessionOdds as $sessionOdd){
                $sessionOdd = json_decode($sessionOdd,true);
                if(!empty($sessionOdd)){
                    if(!empty($devicetype['devicetype']) && $devicetype['devicetype']=='A') {
                        $fancy_positions = $this->Sessionmodel->userScorePosition($userId, $sessionOdd['ID']);
                        $max_exposure = 0;


                        $OddsNumbers = [];
                        $ResultValues = [];
                        if (!empty($fancy_positions)) {
                            foreach ($fancy_positions as $fancy_position) {
                                $OddsNumbers[] = $fancy_position['OddsNumber'] - 1;
                            }
                            $OddsNumbers[] = end($fancy_positions)['OddsNumber'];
                            $OddsNumbersOrg = $OddsNumbers;
                            $OddsNumbers = array_unique($OddsNumbers);
                            $lastPosition = 0;
                            foreach ($OddsNumbers as $OddsNumberKey => $OddsNumber) {
                                $tempTotal = 0;
                                foreach ($fancy_positions as $fancy_position) {
                                    if ($fancy_position['OddValue'] == 1) {
                                        if ($fancy_position['OddsNumber'] <= $OddsNumber) {

                                            $tempTotal -= $fancy_position['bet_price'] * ($fancy_position['session_no_size'] / 100);
                                        } else {
                                            $tempTotal += $fancy_position['bet_price'];
                                        }

                                    }
                                    else {

                                        if ($fancy_position['OddsNumber'] > $OddsNumber) {

                                            $tempTotal -= $fancy_position['bet_price'];

                                        } else {
                                            $tempTotal += $fancy_position['bet_price'] * ($fancy_position['session_yes_size'] / 100);
                                        }

                                    }

                                }
                                if (count($OddsNumbersOrg) - 1 == $OddsNumberKey) {
                                    $ResultValues[] = array('SessInptYes' => $lastPosition . '+', 'ResultValue' => $tempTotal);
                                } else {
                                    if ($lastPosition == $OddsNumber) {
                                        $ResultValues[] = array('SessInptYes' => $lastPosition, 'ResultValue' => $tempTotal);
                                    } else {
                                        $ResultValues[] = array('SessInptYes' => $lastPosition . '-' . $OddsNumber, 'ResultValue' => $tempTotal);
                                    }

                                }

                                $lastPosition = $OddsNumber + 1;
                                if ($max_exposure > $tempTotal) {
                                    $max_exposure = $tempTotal;
                                }
                            }

                        }
                        $sessionOdd['fancy_position'] = $ResultValues;
                        $sessionOdd['max_exposure'] = $max_exposure;
                    }

                    $temp[] = $sessionOdd;
                }

            }
        }
        $response["data"] = $temp;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * [matchLstAdminSession indian session match listing]
     * @param  [int] $matchId [match id]
     * @return [json]          [response]
     */
    function matchLstAdminSession($matchId=0,$marketId=0){
        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Modeleventlst');

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Admin Session fancy listing";

        //	echo $matchId;
        //	echo 'UserID'.$userId; die;
        $sessionOdds = $this->Modeleventlst->mbdip_adminFancyList($matchId,$userId);

        $response["data"] = $sessionOdds;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * [adminIndianSession indian session match listing]
     * @param  [int] $matchId [match id]
     * @return [json]          [response]
     */
    function adminIndianSession($mFancyId,$marketId){
        $userId = $this->globalUserId;
        $response = array();
        $this->load->model('Modeleventlst');
        $this->load->model('Modelmarket');

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Session fancy listing";


        //$getSessionOdds = $this->getIndianSessionOdds($marketId);
        //$getSessionOdds = $this->getSuperAdminFancyOdds($marketId);
        //$sessOddArr = json_decode($getSessionOdds,true);


        $redisUrl = EXCH_ODDS_BY_MARKETS_URL.'?market_id='.$marketId.'_s';
        $redisJson = $this->httpGet($redisUrl);
        $sessOddArr = json_decode($redisJson,true);

        $getSessPrice = array();
        if(!empty($sessOddArr[0]['value']['session'])){
            foreach($sessOddArr[0]['value']['session'] as $sessOdd){
                if($sessOdd['SelectionId'] == $mFancyId){
                    $getSessPrice = array('HeadName'=>$sessOdd['RunnerName'],'SessInptYes'=> $sessOdd['BackPrice1'] ,'SessInptNo'=>$sessOdd['LayPrice1'],'GameStatus'=>$sessOdd['GameStatus'],'NoValume'=>$sessOdd['LaySize1'],'YesValume'=>$sessOdd['BackSize1'],'active'=>1);
                    if(!empty($sessOdd['GameStatus'])){
                        $getSessPrice['active'] = 4;
                    }
                }
            }
        }

        $response["data"] = $getSessPrice;
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * [updateFancyMode update fancy mode]
     * @param [json] {"FancyId":"10","fancy_mode":"A"}
     * @return [json]
     */
    function updateFancyMode(){

        $post = $_POST;
        $betId = $post['FancyId'];
        $MatchID = $post['MatchID'];
        $udpateArr = array('fancy_mode'=>$post['fancy_mode']);

        $response = array();
        $this->load->model('Modelmatchfancy');

        $condition=$this->Modelmatchfancy->update($betId,$udpateArr,$MatchID);
        if ($condition) {
            $response["code"] = 0;
            $response["error"] = false;
            $response["message"] = 'fancy mode updated successfully';
        }else{
            $response["code"] = ERROR_TRY_AGAIN;
            $response["error"] = true;
            $response["message"] = ERROR_TRY_AGAIN_MSG;
        }
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

    function getSettleMatchList(){

        $this->load->model('ModelUserRights');
        $userRole = $this->ModelUserRights->hasRole('ViewTrashBets');
        if($userRole['status']){
            return $this->output->set_content_type('application/json')->set_output( json_encode(array('error' => 1 ,'message' => $userRole['message'])));
        }
        $sportId = $_POST['sport_id'];
        $matchName = $_POST['match_name'];
        $betdeleted = $_POST['bet_deleted'];
        $toDate = isset($_POST['to_date'])?$_POST['to_date']:'';
        $fromDate = isset($_POST['from_date'])? $_POST['from_date']:'';
        $limit = isset($_POST['limit'])? $_POST['limit']:15;
        $page = isset($_POST['page_no']) ? $_POST['page_no']: 1;
        $this->load->model('Modelmatchmst');

        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Session fancy listing";


        $response["data"] = $this->Modelmatchmst->getSettleMatchList($sportId,$matchName,$betdeleted,$fromDate,$toDate,$limit,$page);
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
    }

}
