app.controller('Matchoddscntr', ['$scope', '$http', '$stateParams', 'sessionService', '$timeout', 'Dialog', '$rootScope', 'deviceDetector', 'get_userser', '$mdDialog', 'speech', '$filter', '$location', '$state', '$interval','$window','Base64', function($scope, $http, $stateParams, sessionService, $timeout, Dialog, $rootScope, deviceDetector, get_userser, $mdDialog, speech, $filter, $location, $state, $interval,$window,Base64) {
    $scope.$on('test_dir', function(event, data) { $scope.getNameFunc(); });
    $scope.PLAYPAUSE=0;
    var marketTimer;
    $scope.loading = false;
    $scope.dateForm = new Date($stateParams.date);
    $scope.sportId = $stateParams.sportId;
    $scope.stopped;
    var currentdate = new Date();
    $scope.btnPlaceDis = false;
    $scope.netConn = true;
    $scope.gtTypeId = sessionService.get('type');
    $scope.matchName = $stateParams.matchName;
    $scope.MatchId = $stateParams.MatchId;
    $scope.MarketId = $stateParams.MarketId;
    $scope.date = $stateParams.date;
    $scope.UserTypeId = sessionService.get('slctUseTypeID');
    $scope.UserId = sessionService.get('slctUseID');
    $scope.displayTable = false;
    $scope.logInTypeId = sessionService.get('slctUseTypeID');
    $scope.logInId = sessionService.get('slctUseID');
    var MarketId = $stateParams.MarketId;
    var matchStatus = "OPEN";
    $scope.ajaxTimer="";
    $scope.FinalArray=[];
    var urlIp=$rootScope.gurlIp;
    var urlArray=$rootScope.gUrlArray;
    var scorePosition;
    $scope.scorePosition1=[];
    $scope.result=[];
    var OldFanyID=0;
    var oldFancyTypeId=0;
    var callscorepostype=0;
    $scope.formData = {};
    $scope.UserData=[];
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $scope.fetchAllbet=null;
    $scope.alllMatchBetData=[];
    var old_fancy_id = 0;
    var scoreOddPosition;
    var callscoreOddpostype=0;
    $scope.UserSessionData = [];
    $scope.FancyData=[];
    $scope.FancyDataTemp=[];
    $scope.counter = 0;
    var totalMatch = 0;
    var selectedRunner = null;
    var lsMarketVal=null;
    var lstmarketoldval=0
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
  /*  get_userser.userChipSetting(function(response) {
        $rootScope.userPlcBtn = response;
        $rootScope.MyLenth = response.length;
    });*/
    $scope.OddReturn=function(odds)
    {

        var value= (odds+"");
        value = value.toString();


        if (value.indexOf('.') === -1) {
            return value;
        }

        // as long as the last character is a 0 or a dot, remove it
        while((value.slice(-1) === '0' || value.slice(-1) === '.') && value.indexOf('.') !== -1) {
            value = value.substr(0, value.length - 1);
        }
        return value;
    }
    $scope.scorePosition=function(FancyId,FancyTypeId){
	if(FancyId!=OldFanyID || FancyTypeId != oldFancyTypeId){
	OldFanyID = FancyId;
	oldFancyTypeId=FancyTypeId;
	     $scope.scorePosition1= [];       
	$timeout.cancel(scorePosition);

	}
if(FancyId!=angular.isUndefinedOrNull){
       scorePosition = $timeout(function(){
            var $promise = $http.get(BASE_URL + 'Sessioncntr/FancyScorePosition/'  + $scope.formData.FancyVal + '/' + sessionService.get('slctUseID') + '/' + FancyTypeId);
            $promise.then(function (response) {

		if(callscorepostype == 0){
 		$scope.scorePosition1=[];
$scope.result=[];

            $scope.scorePosition1=response.data.scorePosition;
		callscorepostype=1;
		}
		else{
			$scope.result=response.data.scorePosition;

		}

		for(var i= 0;i< $scope.result.length;i++){

                         $scope.scorePosition1[i] =($scope.result[i]);
				

		}

                
  $scope.scorePosition(FancyId,FancyTypeId);
            });

},1000);
       }
    }
    $scope.styleColor=function(value){
        if(value < 0){
            return "RED";
        }else{
            return "GREEN";
        }

    }
    $scope.countdown = function() {
var utype=sessionService.get('type');
	var sName="";	
		var cName=$state.current.name;
	    if(utype==1)	
		{
			sName="masterDashboard.Matchodds";
		}
	    else
		{
			sName="dealerDashboard.Matchodds";
		}
        $scope.stopped = $timeout(function() {
if(cName==sName){
            $http.get(BASE_URL +'Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                $scope.UserData = data.betUserData;
		
		if(true){
			 $scope.UserData = data.betUserData;
		}
		else if($scope.UserData.length!=data.betUserData.length)
		{
			$scope.UserData = data.betUserData;
		}
		 $scope.countdown();
            });
           /* currentdate = new Date();
            $scope.sysDateTime = currentdate.getDate() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getFullYear() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
            if (moment($scope.dateForm) > moment(currentdate))
                $scope.sysDateTimeDiff = moment.utc(moment($scope.dateForm).diff(moment(currentdate))).format("D [Days] hh:mm:ss");*/
           }
        }, 3000);
    };
    //$scope.countdown();
    $scope.fetchAllbetdata = function() {
var utype=sessionService.get('type');
		var sName="";	
		var cName=$state.current.name;
	    if(utype==1)	
		{
			sName="masterDashboard.Matchodds";
		}
	    else
		{
			sName="dealerDashboard.Matchodds";
		}
        $scope.fetchAllbet = $timeout(function() {
if(cName==sName){
            $http.get(BASE_URL +'Betentrycntr/GatBetDataAll/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                	if(true){
			 $scope.alllMatchBetData = data.betUserDataAll;
		}
		else if($scope.alllMatchBetData.length<data.betUserDataAll.length)
		{
			$scope.alllMatchBetData = data.betUserDataAll;
		}
		$scope.fetchAllbetdata();
            });
           /* currentdate = new Date();
            $scope.sysDateTime = currentdate.getDate() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getFullYear() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
            if (moment($scope.dateForm) > moment(currentdate))
                $scope.sysDateTimeDiff = moment.utc(moment($scope.dateForm).diff(moment(currentdate))).format("D [Days] hh:mm:ss");*/
            }
        }, 3000);
    };
    //$scope.fetchAllbetdata();
	
    $scope.getOddValue = function(item, priceVal,oddsLimit, matchId, back_layStatus, placeName, selectionId,index,MarketId) {
        oldval=0;oldIndex=0;
                    //$scope.btnPlaceDis = true;
	priceVal = parseFloat(priceVal) + parseFloat(oddsLimit);
        $scope.SessionbtnPlaceDis = true;
        $scope.UserTypeId = sessionService.get('type');
        $scope.UserId = sessionService.get('slctUseID');
        $scope.ParantId = sessionService.get('slctParantID');
        $scope.loginId = sessionService.get('user_id');
        $scope.slctUseTypeID = sessionService.get('slctUseTypeID');
        $scope.stake = 0;
	
	//$scope.comStake=0;
	
	
	//$scope.ResetCalculateWinAmt1(index);
	 var isback = document.getElementById('isback'+index).value;
	if(isback!="")
	{
	//$scope.stakeVal(priceVal, selectionId, $scope.stake1['field_'+index],index,item);
		//$scope.stake1['field_'+index]=0;
	       
	}
	else
	{
		
		//$scope.stake1['field_'+index]=0;
	}
        $scope.priceVal =priceVal != angular.isUndefinedOrNull ?  parseFloat(priceVal.toFixed(2)) : 0;
	$scope.priceVal1['field_' + index]=parseFloat($scope.priceVal.toFixed(2));
        $scope.MatchId = $scope.MatchId;
        $scope.displayTable = true;
        $scope.acc = 1;
        $scope.formStatus['field_' + index] = back_layStatus;
	//$scope.ResetCalculateWinAmt(index,selectionId);
	// $scope.CalculateWinAmt(index);
        $scope.placeName = placeName;
	$scope.placeName1['field_'+index]=placeName;
        $scope.selectionId['field_' + index] = selectionId;
        var s = item.currentTarget.getAttribute("data-id");
        $scope.testModel = parseFloat(priceVal);
        var oldPnLValue1 = 0;
        $scope.slctProfit = 0;
	$scope.RunnerValue = $scope.MarketWinLossByMId(MarketId);
        if ($scope.RunnerValue != angular.isUndefinedOrNull && $scope.RunnerValue.length != angular.isUndefinedOrNull) {
            if ($scope.formStatus['field_' + index] == '1') {
               if(oldPnLValue1 != angular.isUndinedorNull){
                oldPnLValue1 = $filter('filter')($scope.RunnerValue, { selectionId: $scope.selectionId })[0]; //170316
		if(oldPnLValue1!=angular.isUndefinedOrNull){
                $scope.oldPnLValue = $scope.getSumValPnL(oldPnLValue1.winValue, oldPnLValue1.lossValue);
                $scope.slctProfit = $scope.getSumValPnL(oldPnLValue1.winValue, oldPnLValue1.lossValue);}}
            } else {
                var minVal = 0;
                if ($scope.RunnerValue.length == 2) {
                    // oldPnLValue1 =$filter('filter')($scope.RunnerValue, { SelectionId: $scope.selectionId })[0];
                    $scope.RunnerValue.find(function(item, j) {
                        if (item.SelectionId != $scope.selectionId) {
                            minVal = (parseFloat(item.winValue) + parseFloat(item.lossValue));
                            if (minVal >= 0) {

                            } else {
                                minVal = 0;
                            }
                            // alert(minVal);
                        }
                    });
                } else if ($scope.RunnerValue.length > 2) {
                    $scope.arrayText = [];
                    //oldPnLValue1 =$filter('filter')($scope.RunnerValue, { SelectionId: $scope.selectionId })[0];
                    $scope.RunnerValue.find(function(item, j) {
                        var selectionVal = (parseFloat(item.winValue) + parseFloat(item.lossValue));
                        if (item.SelectionId != $scope.selectionId) {
                            //var t=(parseFloat(item.winValue) + parseFloat(item.lossValue));
                            var t1 = (parseFloat(item.winValue) + parseFloat(item.lossValue));
                            $scope.arrayText.push(t1);
                            console.log("Push+===" + $scope.arrayText);
                        }
                    });
                    minVal = Math.min.apply(Math, $scope.arrayText.map(function(item) { return item; }));
                    if (minVal < 0) {
                        minVal = 0;
                    };
                }
                $scope.oldPnLValue = minVal;
            }
        } else {
            $scope.oldPnLValue = 0;
        }

    };
    $scope.reset_all_selection = function() {
        $scope.acc = 0;
        $scope.stake = 0;
    };
    $scope.GetUserData=function(){

        $scope.FancyBet = $timeout(function(){
            if($state.current.name=="dealerDashboard.Matchodds" || $state.current.name=="masterDashboard.Matchodds") {
                $http.get(BASE_URL +'Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {

                    if($scope.UserData==angular.isUndefinedOrNull || $scope.UserData.length==0)
                    {

                        $scope.UserData = data.betUserData;
                        if($scope.UserData.length==1)
                            $scope.MarketWinLoss($scope.MarketLst);

                    }
                    else
                    {
                        if($scope.UserData.length != data.betUserData.length)
                        {
                            //  audio.play();
                            $scope.UserData = data.betUserData;
                            $scope.MarketWinLoss($scope.MarketLst);


                        }
                        else
                        {
                            for(var i=0;i<data.betUserData.length;i++)
                            {
                                var ind=$scope.UserData.findIndex(x=>x.MstCode==data.betUserData[i].MstCode && (x.IsMatched!=data.betUserData[i].IsMatched || x.void!=data.betUserData[i].void));
                                if(ind>-1)
                                {
                                    $scope.UserData[ind]=data.betUserData[i];
                                    $scope.MarketWinLoss($scope.MarketLst);


                                }
                            }
                        }
                    }

                    $scope.GetUserData();
                });
            }
        },3000);
    }
    $scope.GetUserData();

$scope.Clean=function()
{
	$timeout.cancel(scoreOddPosition);
     $scope.UserSessionData = [];     
callscoreOddpostype=0;  
}    
  $scope.GetSeesionBetData=function(fancy_id){
	var abc=$scope.FancyVal1;

if($scope.FancyVal1!=old_fancy_id ){
old_fancy_id = $scope.FancyVal1;
$timeout.cancel(scoreOddPosition);
     $scope.UserSessionData = [];     
callscoreOddpostype=0;  
}
       scoreOddPosition = $timeout(function(){
        $http.get(BASE_URL +'Betentrycntr/GatBetFancyData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId + '/' + $scope.FancyVal1).success(function(data, status, headers, config) {


if(callscoreOddpostype == 0){
	$scope.UserSessionData = [];
        $scope.UserSessionData = data.betUserData;
		callscoreOddpostype=1;
	}
		else{
			var result=data.betUserData;

		}
	if(data.betUserData != angular.isUndefinedOrNull)
	{
		if($scope.UserSessionData.length > 0)
		{
			if(data.betUserData[0].selectionName!=$scope.UserSessionData[0].selectionName)
			{
				$scope.UserSessionData=result;
			}
		}
	}
		for(var i in result){

		var ind = $scope.UserSessionData.findIndex(x=>x.SrNo==result[i].SrNo);
						    if(ind==-1)
						      {
                                    $scope.UserSessionData.push(result[i]);
					}
					else
					{
						  $scope.UserSessionData[i]=result[i];
					}

		}
		 $scope.GetSeesionBetData($scope.FancyVal1);
        });

},1000);
    }
 // $scope.GetSeesionBetData();
    $scope.getApiFrom = function(MarketId, MatchId) {
       //
        $scope.btnPlaceDis = true;
        $scope.MarketId = $stateParams.MarketId;
        $scope.UserTypeId = sessionService.get('type');
        var userId = document.getElementById('userId').value;
        var ParantId = document.getElementById('ParantId').value;
        var loginId = document.getElementById('loginId').value;
        var selectionId = document.getElementById('selectionId').value;
        var matchId = document.getElementById('matchId').value;
        var isback = document.getElementById('isback').value;
        var MarketId = document.getElementById('MarketId').value;
        var priceVal = $scope.priceVal;
        var stake = $scope.stake;
        var placeName = document.getElementById('placeName').value;
        get_userser.get_OddsFromApi($stateParams.MarketId, selectionId, MatchId, isback, function(response) {            
            $scope.ApiOddsValue = response.oddsValue;
            var chkValPrice = $scope.ApiOddsValue;
            var P_and_l = 0,
                P_and_lLB = 0;
            if (isback == 0) {
                if (priceVal <= $scope.ApiOddsValue) { //1<=1.11 and place at 1.11
                    isMatched = 1; //match
                    priceVal = $scope.ApiOddsValue;
                } else { //1.31<=1.11 and place at 1.31
                    isMatched = 0; //unmatch
                    priceVal = priceVal;
                    $scope.oldPnLValue = 0; //04_04_2017 0 to -1
                }
            } else { //lay
                if (priceVal >= $scope.ApiOddsValue) { //2>=1.12 and place bet at 1.12
                    isMatched = 1; //match
                    priceVal = $scope.ApiOddsValue;
                } else { //1.01>=1.12 and place bet at 1.01
                    isMatched = 0; //unmatch
                    priceVal = priceVal;
                    $scope.oldPnLValue = 0; //04_04_2017 0 to -1
                }
            }
            if (deviceDetector.device == 'unknown') {
                var DIVICE = 'Desktop';
            } else {
                var DIVICE = deviceDetector.device;
            }
           //
            P_and_l = (priceVal * stake) - stake;
            var deviceInformation = " browser: " + deviceDetector.browser + " browser_version :" + deviceDetector.browser_version + "  device: " + DIVICE + "  OS : " + deviceDetector.os + " os_version: " + deviceDetector.os_version;
            $scope.formData = {
                userId: sessionService.get('slctUseID'),
                ParantId: ParantId,
                loginId: loginId,
                selectionId: selectionId,
                matchId: $stateParams.MatchId,
                isback: isback,
                stake: stake,
                priceVal: priceVal,
                p_l: P_and_l,
                MarketId: MarketId,
                isMatched: isMatched,
                UserTypeId: $scope.UserTypeId,
                placeName: placeName,
                MatchName: $stateParams.matchName,
                deviceInfo: deviceInformation,
                inplay: response.inplay,
                ApiVal: 0
            }
            $scope.getCheckLimitorVal($scope.formData);
                if ($scope.gtTypeId == 3) {
                    ////                  
                        //if ($scope.cValid) {
                    $http({
                        method: 'POST',
                        url: BASE_URL +'Betentrycntr/Save_bet/',
                        data: $scope.formData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function(data) {
                        $scope.GetUserData();
                        $scope.loadingM = false;
                        if (data.error >= 0) {
                                $scope.priceVal = 0;
                                $scope.stake = 0;
                                $scope.acc = false;
                                $scope.btnPlaceDis = false;
                                if(isMatched==0){
                                    Dialog.show("Unmatched"+data.message);
                                }else{
                                    Dialog.autohide(data.message);
                                }
                                
                                $scope.loading = false;
                                $rootScope.$broadcast('changeText', {});
                                $scope.RunnerValue = data.RunnerValue;
                                if (chkUserType == 3) {
                                    $scope.UserId = sessionService.get('slctUseID');
                                    get_userser.GetWALLibiInfo($scope.UserId);
                                } else {
                                    $scope.UserId = sessionService.get('user_id');
                                    get_userser.GetWALLibiInfo($scope.UserId);
                                }
                            } else if (data.error < 0) {
                                alert('' + data.message);
                                $scope.btnPlaceDis = false;
                                $scope.loading = false;
                                $scope.loadingM = false;
                            }
                    });
                    $scope.GetUserData();
                }else{
                    alert("Invalid user");
                    $scope.btnPlaceDis = false;
                    $scope.loading = false;
                    $scope.loadingM = false; 
                } 
            });
    }
   /* $scope.place_bet = function() {
        $scope.loadingM = true;
        $http.get(BASE_URL +'Chipscntrl/checkDeduction/' + sessionService.get('slctUseID')+"/"+$stateParams.MatchId).then(function(articles){
            //
            return parseInt(articles.data.chkcnt[0].numb);
        }).then(function(cnt){
            //
            if(cnt==0){
                $http.get(BASE_URL +'Chipscntrl/getChipDataById/' + sessionService.get('slctUseID')).success(function(data, status, headers, config) {
              
                        var cipsData = data.betLibility;
                        $scope.sessionLiability = -1*parseFloat(cipsData[0].sessionLiability).toFixed(2);
                        $scope.GET_BALANCE=parseFloat(cipsData[0].Balance).toFixed(2);
                        /!*start code cut balance*!/
                        $http.get(BASE_URL +'Betentrycntr/PointValue/').success(function (data, status, headers, config) { 
                            //
                               $scope.PointValue = parseInt(data.GetPoint[0].value);
                                //$scope.message=data.message;
                                if($scope.GET_BALANCE>=$scope.PointValue){
                                    var userID = sessionService.get('user_id');
                                    var userType = sessionService.get('type');
                                    
                                    var ChipData={
                                        UserName: sessionService.get('slctUseName'),
                                        UserId: userID,
                                        userType: userType,
                                        ChpsVal: $scope.PointValue,
                                        MatchID: $stateParams.MatchId,
                                        MatchName: $stateParams.matchName,
                                    };
                                    $http({
                                        method: 'POST',
                                        url: BASE_URL +'Chipscntrl/SaveChipAc/',
                                        data: ChipData,
                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                                    }).success(function (data) {
                                        $scope.TOTAL_LIABILITY=-1*(parseFloat(cipsData[0].sessionLiability)+parseFloat(cipsData[0].unmatchliability));
                                            
                                    });

                                }else{
                                    Dialog.show('Insufficient Balance1234');
                                    $scope.stake = 0;
                                    $scope.btnPlaceDis = false;
                                    
                                    $scope.loadingM = false;
                                    return;

                                }
                        });
                        /!*End of code to cut Balance*!/
                });

            }
            get_userser.getBetDelay(sessionService.get('slctUseID'), function(data) {
                var BetDelay = (parseInt(data) * 1000);
                if ($scope.GetMarketBackLayData.status == "OPEN" && $scope.stake >= 50) {
                    $timeout(function() { $scope.getApiFrom($stateParams.MarketId, $stateParams.MatchId) }, BetDelay);
                } else if ($scope.stake <= 50) {
                    Dialog.autohide('Please Enter Min 50 Stake');
                    $scope.loadingM = false;
                } else if ($scope.GetMarketBackLayData.status != "OPEN") {
                    Dialog.autohide('Match Closed');
                    $scope.loadingM = false;
                }
            });
        });
    };
    
    $scope.saveMatchoddsResult = function(Match_id, Sport_id, market_id, selectionId, model_result, spartName, matchName, MarketName, selectionName) {
        var marketData = {
            Sport_id: Sport_id,
            Match_id: Match_id,
            market_id: market_id,
            selectionId: selectionId,
            result: model_result,
            isFancy: 1,
            sportName: spartName,
            matchName: matchName,
            MarketName: MarketName,
            selectionName: selectionName
        }
        $timeout.cancel(marketTimer);
        marketTimer = angular.isUndefinedOrNull;
        $http({ method: 'POST', url: BASE_URL +'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
            .success(function(data) {
                try {
                    $scope.message = data.status.message;
                   //
                    //$rootScope.$broadcast('changeSidebar_Market', {});
                     $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;                                   
                    // if (sessionService.get('type') == "1")
                    //     $state.go('dashboard.Masterdashboard');
                    // else if (sessionService.get('type') == "2")
                    //     $state.go('dashboard.Dealerdashboard');
                    // else if (sessionService.get('type') == "3")
                    //     $state.go('dashboard.Userdashboard');
                } catch (e) { console.log(data.status.error); }
            });
    }*/
    $scope.getNameFunc = function() {
        var user_id = sessionService.get('slctUseID');
        var user_type = sessionService.get('slctUseTypeID');
        //
        $http.get(BASE_URL +'Geteventcntr/getBackLaysOfMarketSelectionName/' + $scope.MarketId + '/' + user_id + '/' + user_type + '/' + $scope.MatchId).success(function(data, status, headers, config) ///sourabh 161226 change
            {
                //
                if (data.runnerSlName != angular.isUndefinedOrNull && data.runnerSlName.length > 0)
                    $scope.GetMarketBackLayDataSelectionName = data.runnerSlName[0].runners;
                if (data.RunnerValue != angular.isUndefinedOrNull && data.RunnerValue.length != 0)
                    $scope.RunnerValue = data.RunnerValue;
                else
                    $scope.RunnerValue = [{}];

                if (data.MarketData != angular.isUndefinedOrNull && data.MarketData.length != 0)
                    $scope.GetMarketInfo = data.MarketData[0];
            });
    }
    $scope.getSumValPnL = function(a, b) {
        if(a==angular.isUndefinedOrNull && b==angular.isUndefinedOrNull){
            a=0;
            b=0;
        }

        return (parseFloat(a) + parseFloat(b));
    }

    $scope.$on("$destroy", function(event) {
$timeout.cancel(scoreOddPosition);
	$timeout.cancel($scope.stopped);
	$timeout.cancel($scope.fetchAllbet);
    $interval.cancel($scope.stopScore);
        $timeout.cancel($scope.callOddsFunc);
        $scope.callOddsFunc = angular.isUndefinedOrNull;
    });
    /*$scope.callOddsFunc = function() {
        var maxloop = 0;
        if (sessionService.get('slctUseTypeID') == 3) {
            $scope.UserId = sessionService.get('slctUseID');
            get_userser.GetWALLibiInfo($scope.UserId);
        } else {
            $scope.UserId = sessionService.get('user_id');
            get_userser.GetWALLibiInfo($scope.UserId);
        }
        var $promise = $http.get(BASE_URL +'Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + $stateParams.MatchId);
        $promise.then(function(response) {
            //For Play Pause start
            if (sessionService.get('type') != "0") {
                $http({
                    method: 'POST',
                    url: BASE_URL +'Geteventcntr/matchMarketLst/',
                    data: {
                        matchId: $stateParams.MatchId,
                        sportsId: 4,
                        user_id: sessionService.get('user_id')
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    try {
                      // 
                       
                       $scope.FancyLength=data.getMatchFancy.length;
                       if($scope.FancyLength > 0){
                            for (var i = 0; i < data.getMatchFancy.length; i++) {
                                if($scope.FancyData[i].SessInptYes==data.getMatchFancy[i].SessInptYes && $scope.FancyData[i].SessInptNo==data.getMatchFancy[i].SessInptNo && $scope.FancyData[i].active==data.getMatchFancy[i].active && $scope.FancyData[i].DisplayMsg==data.getMatchFancy[i].DisplayMsg){

                                }else{
                                     $scope.FancyData=data.getMatchFancy;
                                }
                            }
                        }
                        if ($filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay == "1") {

                            $rootScope.$broadcast('changeSidebar_Market', {});
                           

                        }
                         $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;
                    } catch (e) {}
                });
            }
            //For Play Pause end
            if (response.data.MatchOddsVolVal != angular.isUndefinedOrNull) {
                if (response.data.MatchOddsVolVal[0].oddsLimit != angular.isUndefinedOrNull)
                    $scope.oddsLimit = parseFloat(response.data.MatchOddsVolVal[0].oddsLimit);
                else
                    $scope.oddsLimit = 0;
                if (response.data.MatchOddsVolVal[0].volumeLimit != angular.isUndefinedOrNull && response.data.MatchOddsVolVal[0].volumeLimit != 0)
                    $scope.volumeLimit = parseFloat(response.data.MatchOddsVolVal[0].volumeLimit);
                else
                    $scope.volumeLimit = 1;
                if (response.data.MatchOddsVolVal[0].result != "0")
                    $scope.MatchResult = "CLOSED";
                else
                    $scope.MatchResult = "OPEN";
            }
            if ($scope.GetMarketBackLayData == angular.isUndefinedOrNull) {
                $scope.GetMarketBackLayData = response.data.MarketRunner;
                if (response.data.MarketRunner == angular.isUndefinedOrNull) {
                    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                        console.log(response.data.MarketRunner); }
                    $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    totalMatch = response.data.MarketRunner.totalMatched;
                    $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                    if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED") {
                        $scope.callOddsCloseMatch();
                    }
                }
            } else if (MarketId == $scope.GetMarketBackLayData.marketId) {
                selectedRunner = null;
                if (response.data.MarketRunner != angular.isUndefinedOrNull ) {//&& response.data.MarketRunner.totalMatched > totalMatch
                    selectedRunner = response.data.MarketRunner.runners;
                    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                        console.log(response.data.MarketRunner); }
                    $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    //$scope.GetMarketBackLayData.IsActive = data.IsActive;
                    totalMatch = response.data.MarketRunner.totalMatched;
                    $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                    if ($scope.MatchResult == "OPEN" && $scope.GetMarketBackLayData.status == "OPEN" && $scope.GetMarketBackLayData.runners != angular.isUndefinedOrNull && $scope.GetMarketBackLayData.runners.length > 0) { //&& selectedRunner != angular.isUndefinedOrNull
                        try {
                            if ($scope.GetMarketBackLayData.runners.length < selectedRunner.length) //170204
                                maxloop = selectedRunner.length;
                            else
                                maxloop = $scope.GetMarketBackLayData.runners.length;
                            for (var j = 0; j < maxloop; j++) { //170204 $scope.GetMarketBackLayData.runners.length
                                if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack.length == selectedRunner[j].ex.availableToBack.length) {
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price != selectedRunner[j].ex.availableToBack[0].price || $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].size != selectedRunner[j].ex.availableToBack[0].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = selectedRunner[j].ex.availableToBack[0].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].size = selectedRunner[j].ex.availableToBack[0].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[0] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price != selectedRunner[j].ex.availableToBack[1].price || $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].size != selectedRunner[j].ex.availableToBack[1].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = selectedRunner[j].ex.availableToBack[1].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].size = selectedRunner[j].ex.availableToBack[1].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[1] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price != selectedRunner[j].ex.availableToBack[2].price || $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].size != selectedRunner[j].ex.availableToBack[2].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = selectedRunner[j].ex.availableToBack[2].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].size = selectedRunner[j].ex.availableToBack[2].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = "";
                                        }
                                    }
                                } else {

                                    $scope.GetMarketBackLayData.runners[j].ex.availableToBack = selectedRunner[j].ex.availableToBack;
                                }
                                if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay.length == selectedRunner[j].ex.availableToLay.length) {
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price != selectedRunner[j].ex.availableToLay[0].price || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].size != selectedRunner[j].ex.availableToLay[0].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = selectedRunner[j].ex.availableToLay[0].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].size = selectedRunner[j].ex.availableToLay[0].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[0] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price != selectedRunner[j].ex.availableToLay[1].price || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].size != selectedRunner[j].ex.availableToLay[0].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = selectedRunner[j].ex.availableToLay[1].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].size = selectedRunner[j].ex.availableToLay[1].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[1] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price != selectedRunner[j].ex.availableToLay[2].price || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].size != selectedRunner[j].ex.availableToLay[2].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = selectedRunner[j].ex.availableToLay[2].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].size = selectedRunner[j].ex.availableToLay[2].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = "";
                                        }
                                    }
                                } else {

                                    $scope.GetMarketBackLayData.runners[j].ex.availableToLay = selectedRunner[j].ex.availableToLay;
                                }
                            }
                        } catch (e) {

                            $scope.GetMarketBackLayData = angular.isUndefinedOrNull;
                        }
                        $scope.counter = $scope.counter + 1;
                    } else if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED") //170201
                    {
                        $scope.GetMarketBackLayData = response.data.MarketRunner;
                        $scope.callOddsCloseMatch();
                    }
                } else if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED") //170201
                {
                    $scope.GetMarketBackLayData = response.data.MarketRunner;
                    $scope.callOddsCloseMatch();

                }
            } else {
                $scope.GetMarketBackLayData = response.data.MarketRunner;
                try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                    console.log(response.data.MarketRunner); }
                $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                matchStatus = response.data.MarketRunner.status;
                $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
            }
            marketTimer = $timeout(function() {
                //
                if ($scope.GetMarketBackLayData != angular.isUndefinedOrNull) { //sourabh 170107
                    for (var j = 0; j < maxloop; j++) { // $scope.GetMarketBackLayData.runners.length 170204
                        //for (var i = 0; i < 3; i++) {//$scope.GetMarketBackLayData.runners[j].ex.availableToBack.length
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = false; } catch (e) {}
                        //}
                    }
                    if ($scope.GetMarketBackLayData.Status != 3) {
                        if ($scope.GetMarketBackLayData.marketId != null) {
                            $scope.callOddsFunc();
                            $scope.getNameFunc();
                        }
                    }
                } else {
                    $scope.callOddsFunc();
                    $scope.getNameFunc();
                }
            }, 500);
            /!*{aakash 161226*!/
            var OnlineStatus = $interval(OnlineStatusChanged, 1000)
            var updatedOnline = function() {
                //console.log("akash2", navigator.onLine)
                if (navigator.onLine) {
                    //clearInterval(Changed);
                    $interval.cancel(Changed);
                    Changed = angular.isUndefinedOrNull;
                    location.reload();
                }
            }
            var Changed;

            function OnlineStatusChanged() {
                if (navigator.onLine) {
                    if (!$scope.netConn) {
                        $mdDialog.hide();
                        $scope.netConn = true;
                        $scope.callOddsFunc();
                        $scope.getNameFunc();
                    }
                } else {
                    Changed = $interval(updatedOnline, 1000)
                    if ($scope.netConn) {
                        $mdDialog.show({
                            clickOutsideToClose: false,
                            escapeToClose: false,
                            template: "<md-dialog style='border: rgb(255, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content>Internet Connection is Disconnect... Please Wait...</md-dialog-content></md-dialog>",
                            locals: { prntScope: $scope },
                            fullscreen: false,
                            controller: function DialogController(prntScope) {
                                prntScope.netConn = false;
                            }
                        });
                    }
                }
            }
            /!*}aakash 161226*!/
        });
        //}
    }
    $scope.callOddsCloseMatch = function() { //sourabh 15-nov-2016
       ////
        if ($scope.GetMarketBackLayData.status == "CLOSED") {
            var vSelectionID = $filter('filter')($scope.GetMarketBackLayData.runners, { status: "WINNER" })[0].selectionId;
            var selectionName1 = "";
            //for (var j = 0; j < $scope.GetMarketBackLayData.runners.length; j++) {
            //if ($scope.GetMarketBackLayData.runners[j].status == "WINNER") {

            if ($scope.RunnerValue != angular.isUndefinedOrNull && $scope.RunnerValue.length > 0 && $scope.RunnerValue[0].length > 0) //sourabh 170131
            {
                //
                selectionName1 = $filter('filter')($scope.RunnerValue, { SelectionId: vSelectionID })[0].selectionName;
                //for (var i = 0; i < $scope.RunnerValue.length; i++) {
                //if ($scope.RunnerValue[i].SelectionId == $scope.GetMarketBackLayData.runners[j].selectionId || $scope.RunnerValue[i].selectionId == $scope.GetMarketBackLayData.runners[j].selectionId) {
                //selectionName1 = $scope.RunnerValue[i].selectionName;
                if (selectionName1 != "")
                    $scope.saveMatchoddsResult($scope.MatchId, $scope.GetMarketInfo.SportID, $scope.MarketId, vSelectionID, 1, $scope.GetMarketInfo.SportName, $stateParams.matchName, $scope.GetMarketInfo.MarketName, selectionName1);
                //}
                //}
            } else {
                $http.get(BASE_URL +'Geteventcntr/getSelectionName/' + $scope.MarketId + '/' + $scope.MatchId).success(function(data, status, headers, config) {
                    //$scope.RunnerValue = data.RunnerValue;
                    selectionName1 = $filter('filter')(data.RunnerValue, { selectionId: vSelectionID })[0].selectionName;
                    if (selectionName1 != "")
                        //
                        $scope.saveMatchoddsResult($scope.MatchId, $scope.GetMarketInfo.SportID, $scope.MarketId, vSelectionID, 1, $scope.GetMarketInfo.SportName, $stateParams.matchName, $scope.GetMarketInfo.MarketName, selectionName1);
                });
            }
            //}
            //}
        } else if ($scope.MatchResult == "CLOSED") {
            $scope.GetMarketBackLayData.status = "CLOSED";
            $timeout.cancel(marketTimer);
            marketTimer = angular.isUndefinedOrNull;
            $rootScope.$broadcast('changeSidebar_Market', {});
            /!*if (sessionService.get('type') == "1")
                $state.go('dashboard.Masterdashboard');
            else if (sessionService.get('type') == "2")
                $state.go('dashboard.Dealerdashboard');
            else if (sessionService.get('type') == "3")
                $state.go('dashboard.Userdashboard');*!/

        }

    };*/
    $scope.stakeValReset = function(val) { //sourabh 15-nov-2016
        $scope.stake = parseInt(val);
    };
    $scope.getCalculation = function(priceVal, stake) {
       //
        if (stake == angular.isUndefinedOrNull) {
            stake = 0;
        } else {
            $scope.sumOfVal = parseFloat(priceVal) * parseInt(stake) - parseInt(stake);
            $scope.sumOfVal = $scope.sumOfVal.toFixed(2);
        }

    }
    $scope.stakeVal = function(val, selectionId, stake) { //sourabh 15-nov-2016

        if (stake == angular.isUndefinedOrNull) {
            stake = 0;
        }
        if (stake == 0) {}
        $scope.sumOfVal = parseFloat(val) * parseInt(stake) - parseInt(stake);
        $scope.sumOfVal = $scope.sumOfVal.toFixed(2);
        $scope.stake = $scope.stake + parseInt(val);
        $scope.MarketId = $stateParams.MarketId;
        $scope.UserTypeId = sessionService.get('type');
        var userId = document.getElementById('userId').value;
        var ParantId = document.getElementById('ParantId').value;
        var loginId = document.getElementById('loginId').value;
        var selectionId = document.getElementById('selectionId').value;
        var matchId = document.getElementById('matchId').value;
        var isback = document.getElementById('isback').value;
        var MarketId = document.getElementById('MarketId').value;
        var priceVal = $scope.priceVal;
        var stake = $scope.stake;
        var placeName = document.getElementById('placeName').value;
        var chkValPrice = document.getElementById('chkValPrice').value;
        chkValPrice = parseFloat(chkValPrice);
        if (chkValPrice == priceVal) {
            var isMatched = 1;
        } else {
            var isMatched = 0;
        }
        var P_and_l = (priceVal * stake) - stake;
        $scope.formData = {
            userId: sessionService.get('slctUseID'),
            ParantId: ParantId,
            loginId: loginId,
            selectionId: selectionId,
            matchId: $stateParams.MatchId,
            isback: isback,
            stake: stake,
            priceVal: priceVal,
            p_l: P_and_l,
            MarketId: MarketId,
            isMatched: isMatched,
            UserTypeId: $scope.UserTypeId,
            placeName: placeName,
            MatchName: $stateParams.matchName
        }
        $scope.getCheckLimitorVal($scope.formData);
    }
    $scope.getCheckLimitorVal = function(formdata) {
        get_userser.getCheckLimitOfPlaceBet(sessionService.get('slctUseID'), $stateParams.MatchId, $stateParams.MarketId, function(data) {

            $scope.viewUserAc1 = data.viewUserAc2[0];
            $scope.checkStakeLimit($scope.formData);
        });
        if ($scope.RunnerValue != angular.isUndefinedOrNull && $scope.RunnerValue.length > 0) {
            var vMaxProfit = 0,
                vMaxLoss = 0;
            $scope.RunnerValue.find(function(item, j) {
                if ($scope.formData.selectionId == item.SelectionId) {
                    if ($scope.formStatus == 0) {
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (($scope.formData.priceVal * $scope.formData.stake) - $scope.formData.stake)) < vMaxLoss) {
                            vMaxLoss = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (($scope.formData.priceVal * $scope.formData.stake) - $scope.formData.stake));
                        }
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (($scope.formData.priceVal * $scope.formData.stake) - $scope.formData.stake)) > vMaxProfit) {
                            vMaxProfit = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (($scope.formData.priceVal * $scope.formData.stake) - $scope.formData.stake));
                        }

                    } else {
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake - ($scope.formData.priceVal * $scope.formData.stake))) < vMaxLoss) {
                            vMaxLoss = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake - ($scope.formData.priceVal * $scope.formData.stake)));
                        }
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake - ($scope.formData.priceVal * $scope.formData.stake))) > vMaxProfit) {
                            vMaxProfit = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake - ($scope.formData.priceVal * $scope.formData.stake)));
                        }
                    }
                } else {
                    if ($scope.formStatus == 0) {
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (-$scope.formData.stake)) < vMaxLoss) {
                            vMaxLoss = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (-$scope.formData.stake));
                        }
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (-$scope.formData.stake)) > vMaxProfit) {
                            vMaxProfit = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + (-$scope.formData.stake));
                        }
                    } else {
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake)) < vMaxLoss) {
                            vMaxLoss = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake));
                        }
                        if (((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake)) > vMaxProfit) {
                            vMaxProfit = ((parseFloat(item.winValue) + parseFloat(item.lossValue)) + ($scope.formData.stake));
                        }
                    }
                }
            });
            $scope.SlMaxProfit = vMaxProfit;
            $scope.SlMaxLoss = vMaxLoss;
            console.log("" + $scope.SlMaxProfit + "|||||" + $scope.SlMaxLoss);
        }
    }
    $scope.checkStakeLimit = function(formdata) {
        if ($scope.viewUserAc1 == angular.isUndefinedOrNull) {
            $scope.cValid = false;
            return false;
        } else if ($scope.viewUserAc1.lgnusrCloseAc == 0) {
            Dialog.autohide('Your Account is Closed...');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if ($scope.viewUserAc1.mstrlock == 0) {
            Dialog.autohide('Your Account is InActive...');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if ($scope.viewUserAc1.lgnusrlckbtng == 0) {
            Dialog.autohide('Your Betting is Locked...');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if (parseInt($scope.viewUserAc1.stakeLimit) != 0 && parseInt($scope.viewUserAc1.stakeLimit) < $scope.stake) {
            Dialog.autohide('Your Stake Limit is Over...');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if (-parseInt($scope.viewUserAc1.lgnUserMaxLoss) != 0 && -parseInt($scope.viewUserAc1.lgnUserMaxLoss) > $scope.SlMaxLoss) { //ye market wise aayegi n ki overall par
            Dialog.autohide('Your Max Loss is Over.....');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if (parseFloat($scope.viewUserAc1.lgnUserMaxProfit) != 0 && parseFloat($scope.viewUserAc1.lgnUserMaxProfit) < $scope.SlMaxProfit) //sourabh 170102 new
        {
            Dialog.autohide('Your Max Profit is Over.....');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if ($scope.GetMarketBackLayData.inplay == 'false' && parseInt($scope.viewUserAc1.GoingInplayStakeLimit) != 0 && parseInt($scope.viewUserAc1.GoingInplayStakeLimit) < $scope.stake) {
            Dialog.autohide('Going Inplay Stake Limit is Over...');
            $scope.stake = 0;
            $scope.cValid = false;
            $scope.btnPlaceDis = false;
            return false;
        } else if ($scope.viewUserAc1 != angular.isUndefinedOrNull && $scope.viewUserAc1.lgnusrCloseAc == 1 && $scope.viewUserAc1.mstrlock == 1 && $scope.viewUserAc1.lgnusrlckbtng == 1 && (parseInt($scope.viewUserAc1.stakeLimit) >= $scope.stake || parseInt($scope.viewUserAc1.stakeLimit) == 0)) {
            $scope.cValid = true;
            $scope.btnPlaceDis = false;
            return true;
        } else {
            alert("Problem Occered");
        }
    }
    $scope.getValColor = function(val) { //20-dec-2016 asha
        if (val == angular.isUndefinedOrNull || val == 0)
            return 'color:#000000';
        else if (val > 0)
            return 'color:#1ed61e !important';
        else
            return 'color:#ff0000 !important';
    }
         $scope.getOddCalcVal = function(a, ovType,oddsLimit,volumeLimit) { //sourabh 161229
	$scope.oddsLimit=oddsLimit;
	$scope.volumeLimit=volumeLimit;
        var x = 0,
            y = 0,
            z = 0;
            
            if(isNaN(a) && a != angular.isUndefinedOrNull)
            {
                var ind = a.indexOf('$');
                if(ind>-1)
                {
                    a=a.replace('$','');
                }
                
            }
        switch (ovType) {
            case 1:
                if (a != angular.isUndefinedOrNull) {
                    x = a;
                    if ($scope.oddsLimit != angular.isUndefinedOrNull) y = $scope.oddsLimit;
                }
                //z = parseFloat((parseFloat(x) + parseFloat(y)).toFixed(2));
                z=isInt_number(a) ? parseFloat((parseFloat(x) + parseFloat(y))) : parseFloat((parseFloat(x) + parseFloat(y))).toFixed(2);
                break;
            case 2:
                if (a != angular.isUndefinedOrNull) {
                    x = a;
                    if ($scope.volumeLimit != angular.isUndefinedOrNull) y = $scope.volumeLimit;
                }
                z = parseFloat((parseFloat(x) * parseFloat(y)).toFixed(0));
                break;
        }
        if (z > 0) return z;
        else return "";
    }
    function isInt_number(num) {
        return num % 1 === 0;
    }
    $scope.getNameFunc();
  //  $scope.callOddsFunc();
    /*$scope.countdown();*/

    $scope.$on("$destroy", function(event) {
        $timeout.cancel(marketTimer);
        marketTimer = angular.isUndefinedOrNull;
    });
    /*start code for Fancy*/
    $scope.$on("$destroy", function(event) {
                //clearInterval($scope.si_getMatchUnmatchData);
                //clearInterval(si_fancyData);
                $interval.cancel($scope.si_getMatchUnmatchData);
              //  $interval.cancel(si_fancyData);

                $scope.si_getMatchUnmatchData = angular.isUndefinedOrNull;
               // si_fancyData = angular.isUndefinedOrNull;
            }); //sourabh 170124

            $scope.showSessionFancy = function(fanctTypeId, fanctId) {
                $scope.sessionFancy = fanctId;
                $scope.sessionFancyType = fanctTypeId;
                get_userser.GetFancyData($stateParams.MatchId, $scope.sessionFancy, sessionService.get('user_id'), sessionService.get('type'), $scope.sessionFancyType, function(response) { //sourabh 170125_1
                    $scope.FancyData = response.data.fancyForm;
                    $scope.showOdd1 = false;
                    $scope.GetFancyBal();
                });
            }
            $scope.checkValidation = function(sessionData) { //sourabh 170125
                if (sessionData.betValue == "" || sessionData.betValue <= 0) {
                    Dialog.autohide('You cannot play at zero Stake...');
                    focus('betValue');
                    return false;
                }
                return true;
            }
            $scope.openfancy = {};
            $scope.display_Yesfancy = function(sessionValue, id) { //sourabh 170125
                if (!$scope.openfancy) {
                    $scope.openfancy = {};
                }
                if(!$scope.betValue) {
                    $scope.betValue = {};
                }
                $scope.openfancy[id] = {yes: true, open: true};
                if (sessionService.get('slctUseTypeID') == "3") {
                    $scope.isBackYes = 1;
                    $scope.showOdd1 = true;
                    $scope.betValue[id] = 0;
                    //$scope.sessionValue = parseInt(sessionValue);
                    $scope.userType = sessionService.get('type');
                    $scope.UserTypeId = sessionService.get('slctUseTypeID');
                    focus('betValueLay');

                } else
                    Dialog.autohide('Please Select Valid User');
            }
            $scope.display_Nofancy = function(sessionValue, id) { //sourabh 170125
                if (!$scope.openfancy) {
                    $scope.openfancy = {};
                }
                if(!$scope.betValue) {
                    $scope.betValue = {};
                }
                $scope.openfancy[id] = {yes: false, open: true};
                if (sessionService.get('slctUseTypeID') == "3") {
                    $scope.isBackYes = 0;
                    $scope.showOdd1 = true;
                    $scope.betValue[id] = 0;
                    //$scope.sessionValue = parseInt(sessionValue);
                    $scope.userType = sessionService.get('type');
                    $scope.UserTypeId = sessionService.get('slctUseTypeID');
                    focus('betValueLay');
                } else
                    Dialog.autohide('Please Select Valid User');
            }
            $scope.GetFancyBal = function() { //sourabh 170125
                ////
                get_userser.GetFancyBal($scope.FancyData[0].ID, function(response1) {
                    ////
                    if (response1 == null) {
                        $scope.TotalBet = 0;
                    } else {
                        $scope.TotalBet = response1;
                    }


                });
            }
            $scope.GetBetValueReset = function(Value1, hideOdd, id) {
                if (!$scope.openfancy) {
                    $scope.openfancy = {};
                }
                if(!$scope.betValue) {
                    $scope.betValue = {};
                }
                $scope.openfancy[id] = {open: false};
                $scope.betValue[id] = parseInt(Value1);
                if (hideOdd) $scope.showOdd1 = !hideOdd;
            }
            $scope.GetBetValue = function(Value1, id) {
                if(!$scope.betValue) {
                    $scope.betValue = {};
                }
                $scope.betValue[id] = parseInt($scope.betValue[id]) + parseInt(Value1);
            }
            $scope.saveSessionBet = function(pointDiff,FancyData,IndexVal) { 
                
                var HeadName = FancyData.HeadName;
                var SessInptNo = FancyData.SessInptNo;
                var SessInptYes = FancyData.SessInptYes;
                var FncyId = FancyData.FncyId;
                var sportId = FancyData.SprtId;
                var UserTypeId = sessionService.get('slctUseTypeID');
                var UserId = sessionService.get('slctUseID');
                var loginId = sessionService.get('user_id');
                var ParantId = sessionService.get('slctParantID');
                var amount = document.getElementById('betValueLay'+IndexVal).value;
                if ($scope.isBackYes == 0) {
                    OddsNumber = SessInptYes;
                } else {
                    OddsNumber = SessInptNo;
                }
                if (deviceDetector.device == 'unknown') {
                    var DIVICE = 'Desktop';
                } else {
                    var DIVICE = deviceDetector.device;
                }
                var deviceInformation = '"' + " browser: " + deviceDetector.browser + " browser_version :" + deviceDetector.browser_version + "  device: " + DIVICE + "  OS : " + deviceDetector.os + " os_version: " + deviceDetector.os_version + '"';
                var sessionData = {
                    userId: UserId,
                    ParantId: ParantId,
                    loginId: loginId,
                    betValue: amount,
                    FancyID: FancyData.ID,
                    matchId: $stateParams.MatchId,
                    OddValue: $scope.isBackYes,
                    type: sessionService.get('type'),
                    OddsNumber: OddsNumber,
                    TypeID: FancyData.TypeID,
                    HeadName: HeadName,
                    SessInptNo: SessInptNo,
                    SessInptYes: SessInptYes,
                    sportId: sportId,
                    FancyId: FncyId,
                    pointDiff: pointDiff,
                    deviceInformation: deviceInformation
                }
                if ($scope.checkValidation(sessionData)) {
                    $http({
                            method: 'POST',
                            url: BASE_URL + 'Lstsavemstrcontroller/save_session_bet',
                            data: sessionData,
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function(data) {
                        
                        $scope.showOdd1 = false;
                        if(data.error>=0){
                            get_userser.GetWALLibiInfo(sessionService.get('slctUseID'));
                            Dialog.show(data.message);
                            $scope.GetUserData();
                        }else if(data.error < 0){
                            Dialog.show(data.message);
                        }
                        
                    });
                }
                
            };
           
    /*end of the code Fancy*/
 var callType1="1";
    $scope.GetMarketListId = function()
	{
	$scope.getMarketlstTimer=$timeout(function(){	
	
		var utype=sessionService.get('type');
		var sName="";	
		var cName=$state.current.name;
	    if(utype==1)	
		{
			sName="masterDashboard.Matchodds";
		}
	    else
		{
			sName="dealerDashboard.Matchodds";
		}
	if(cName==sName){
	    if(callType1==1)
		{
	   $scope.loading = true;
		}
else{$scope.loading = false;}
	   
	 //  socket.emit('CallGetMarketListId', {auth:Bauthdata,MatchId:$scope.MatchId,UserId:$scope.UserId});
		 $http({
                    method: 'GET',
                    url: BASE_URL +'Apicontroller/getMarketListing/'+$scope.MatchId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    // 
		 $scope.type = sessionService.get('type');
		 if(data.error)
			{
			$scope.ClearAllTime();
			Dialog.autohide(data.message);
                        $scope.loading = false;
                        $scope.IsShowPage=false;
                          if($scope.type==2)
			{
				$state.go("dealerDashboard.Home");
			}
			else if($scope.type==1)
			{
				$state.go("masterDashboard.Home");
			}
			}
		else{
			  $scope.isManualMatchOdds=data.isManualMatchOdds;

                        $scope.isManualMatch = data.is_manual;
                        $scope.BindManulalOdds(data.runners_manual);
		 if(data.data.result==0 || data.data.marketid=="")
                    {
			$scope.ClearAllTime();
                        Dialog.autohide("Match Closed.");
                        $scope.loading = false;
                        $scope.IsShowPage=false;
                         if($scope.type==2)
			{
				$state.go("dealerDashboard.Home");
			}
			else if($scope.type==1)
			{
				$state.go("masterDashboard.Home");
			}
                    }
                  
   		else if(callType1==1){
		     var tResult = data;
                    //  $scope.oddsLimit=parseFloat(tResult.data.volumeLimit[0].oddsLimit);
                    // $scope.volumeLimit=parseFloat(tResult.data.volumeLimit[0].volumeLimit);
		     $timeout.cancel($scope.ajaxTimer);
		     $scope.ajaxTimer=null;
		      if(tResult.error)
                     {
                           
                        $scope.BindSoketMarket(tResult);
                     }
                     else
                     {
                        $scope.IsShowPage=true;
                        $scope.GetScore();
                        $scope.BindSoketMarket(tResult);
                     }
                }
         else if(callType1==2)
         {
             $scope.BindSoketMarketSecondStep(data);
         }
		}
			 $scope.GetMarketListId();
			 callType1=2;
			}).error(function(err){
			 $scope.loading = false;
        });	
}
        },1000);
    }
    $scope.BindSoketMarketSecondStep=function(result)
    {

        if(result.data != angular.isUndefinedOrNull)
        {
            if(result.data.length!=$scope.FinalArray.length)
            {
                $scope.loading = true;
                $scope.FinalArray=[];
                for(var i=0;i<result.data.length;i++){
                    $scope.FinalArray.push(result.data[i]);
                }
                $scope.BindSoketMarket(result)
                $scope.loading = false;
            }
            else
            {
                for(var i=0;i<result.data.length;i++){
                    var ind=$scope.FinalArray.findIndex(x=>x.marketid==result.data[i].marketid);
                    if(ind>-1)
                    {
                        $scope.FinalArray[ind].visibility=result.data[i].visibility;
                        $scope.FinalArray[ind].isBetAllowedOnManualMatchOdds=result.data[i].isBetAllowedOnManualMatchOdds;
                        $scope.FinalArray[ind].id=result.data[i].marketid;
                        if($scope.FinalArray[ind].is_manual==1 && $scope.isUpdateRunner)
                        {
                            $scope.FinalArray[ind].runners=result.data[i].runners;
                            $scope.isUpdateRunner=false;
                        }
                    }
                    $scope.FinalArray[ind].name=result.data[i].market_name;


                }
            }
        }
    }
    $scope.BindManulalOdds = function (result) {
        if (result != angular.isUndefinedOrNull) {
            $scope.Manualdata = result;
        }
    }
    $scope.GetScore=function(){      
$scope.GetScoreTimer=$timeout(function(){
            var eventIds = $stateParams.MatchId;
         //var eventIds = '28448035';
        $http.get(BASE_URL+'Geteventcntr/GetScoreApi/'+eventIds).then(function(result) {
            
            if (result.data.length!= 0) {
                $scope.Documents=result.data[0];
                $scope.displayScore=true;
                if($scope.Documents.eventTypeId==2){
                    $scope.Home=result.data[0].score.home.gameSequence;
                    $scope.away=result.data[0].score.away.gameSequence;
                }
            }else{
                $scope.displayScore=false;
                $interval.cancel($scope.stopScore);
            }
        });
		},1000);
    }
    $scope.stopScore = $interval(function () {
                    //Display the current time.
      $scope.GetScore();
    }, 5000);

    $interval.cancel($scope.stopScore);
$scope.CommonFun=function(msg)
{
	websocket.onclose();
	Dialog.autohide(msg);
	$scope.loading = false;
	$scope.IsShowPage=false;
	//$state.go("dealerDashboard.Home");
}
    $scope.GetMarketListId();
//step 1
$scope.BindSoketMarket=function(result)
    {
	
	$scope.marketIdLst=[];
        if(result.data != angular.isUndefinedOrNull)
            {

		  for(var i=0;i<result.data.length;i++){
			 $scope.marketIdLst.push(result.data[i].marketid);
           		
			if(callType1==1)
			{
	 	   	  $scope.FinalArray.push(result.data[i]);
			}


		    }
                if($scope.sportId == 4){
                    $scope.getFancyList(result.data[0].marketid);

                }
            //var callbackResult=$scope.SetResult($scope.marketIdLst);
         
            $scope.MarketLst=$scope.marketIdLst.join(',');
            $scope.MarketWinLoss($scope.MarketLst);
	    $scope.MarketId= $scope.MarketLst;
            $scope.AllMarket =  $scope.MarketLst;
		 $scope.GetScore();
		if(true){
}
                get_userser.getSocketDataApiDetail($scope.MarketLst,function(data) {
                    try{
                    $scope.market_sel_id = [];
                    //console.log(data);
                    var dataResult = data;
                    var tempResult = [];

                    if (dataResult != angular.isUndefinedOrNull && dataResult['cricket'].length > 0 && dataResult['cricket'][0] != angular.isUndefinedOrNull) {
                        for (var j = 0; j < $scope.FinalArray.length; j++) {
                            var ind = dataResult['cricket'].findIndex(x => x.id == $scope.FinalArray[j].marketid);
                            if (ind > -1) {
                                tempResult.data = dataResult['cricket'][ind];
                                var tempArray = $scope.FinalArray[j];
                                $scope.FinalArray[j].volumeLimit = tempArray.volumeLimit;
                                $scope.FinalArray[j].IsMatchDisable = tempArray.IsMatchDisable;
                                $scope.FinalArray[j].is_favourite = tempArray.is_favourite;
                                $scope.FinalArray[j].id = tempResult.data.id;
                                $scope.FinalArray[j].mtype = tempResult.data.mtype;
                                $scope.FinalArray[j].btype = tempResult.data.btype;
                                $scope.FinalArray[j].name = tempArray.market_name;
                                $scope.FinalArray[j].status = tempResult.data.status;
                                $scope.FinalArray[j].inPlay = tempResult.data.inPlay;
                                $scope.FinalArray[j].groupById = tempResult.data.matchid;
                                $scope.FinalArray[j].isBetAllowedOnManualMatchOdds = tempArray.isBetAllowedOnManualMatchOdds;
                                if ($scope.FinalArray[j].name == "Match Odds") {
                                    $scope.GetMarketBackLayDataSelectionName = $scope.FinalArray[j].runners;
                                }
                                //data base runners exist
                                if ($scope.FinalArray[j].runners.length > 0) {

                                    for (var r = 0; r < $scope.FinalArray[j].runners.length; r++) {
                                        if ($scope.FinalArray[j].runners[r].selectionId != angular.isUndefinedOrNull) {
                                            $scope.FinalArray[j].runners[r].id = $scope.FinalArray[j].runners[r].selectionId;
                                        }

                                        if ($scope.FinalArray[j].runners[r].name == "" || tempArray.runners[r].name == angular.isUndefinedOrNull) {
                                            var sId = $scope.FinalArray[j].id + "-" + $scope.FinalArray[j].runners[r].id;
                                            $scope.FinalArray[j].runners[r].selection_id = sId;

                                            $scope.market_sel_id.push(sId);
                                        } else {
                                            var rindx = $scope.FinalArray[j].runners.findIndex(x => x.id == tempArray.runners[r].id);
                                            if (rindx > -1) {
                                                $scope.FinalArray[j].runners[rindx].name = tempArray.runners[r].name;
                                            }
                                            if ($scope.FinalArray[j].mtype == "MATCH_ODDS" || $scope.FinalArray[j].btype == "ODDS") {
                                                $scope.BindIndianFancy(dataResult['session'], $scope.FinalArray[j].id);
                                            }
                                            //   $scope.CallSocketMarket();
                                        }
                                    }


                                } else if ($scope.FinalArray[j].runners.length == 0) {
                                    $scope.FinalArray[j].runners = tempResult.data.runners;
                                    for (var r = 0; r < $scope.FinalArray[j].runners.length; r++) {
                                        if ($scope.FinalArray[j].runners[r].name == "" || $scope.FinalArray[j].runners[r].name == angular.isUndefinedOrNull || true) {
                                            var sId = $scope.FinalArray[j].id + "-" + $scope.FinalArray[j].runners[r].id;
                                            $scope.FinalArray[j].runners[r].selection_id = sId;
                                            $scope.market_sel_id.push(sId);
                                        }

                                    }


                                }

                                $scope.loading = false;
                            } else {
                                //Dialog.autohide("Odds not comming from api.");
                                $scope.loading = false;
                                //$scope.CallSocketMarket();
                                //$scope.IsShowPage=false;
                                //$timeout.cancel($scope.ajaxTimer);
                                //$state.go("userDashboard.Home");
                            }
                        }

                        if ($scope.market_sel_id.length > 0) {
                            $scope.SelectionName = [];
                            var selection_id = $scope.market_sel_id.join(',');

                            get_userser.getSelectionList(selection_id, function (data) {

                                $scope.SelectionName = data;
                                for (var j = 0; j < $scope.FinalArray.length; j++) {

                                    if ($scope.FinalArray[j].mtype == "MATCH_ODDS" || $scope.FinalArray[j].btype == "ODDS") {
                                        $scope.BindIndianFancy(dataResult['session'], $scope.FinalArray[j].id);
                                    }
                                    for (var s = 0; s < $scope.SelectionName.length; s++) {
                                        var indx = $scope.FinalArray[j].runners.findIndex(x => x.selection_id == $scope.SelectionName[s].selection_id)
                                        if (indx > -1) {
                                            $scope.FinalArray[j].runners[indx].name = $scope.SelectionName[s].runnername;
                                        }
                                    }
                                    // $scope.FinalArray[j].runners=tempArray.runners;
                                    if ($scope.FinalArray[j].name == "Match Odds") {
                                        $scope.GetMarketBackLayDataSelectionName = $scope.FinalArray[j].runners
                                    }
                                    $scope.CallSocketMarket();
                                }

                            });
                        }


                    } else {
                        if ($scope.SPORTID == 4 && dataResult['session'].length == 0 && dataResult['session'][0] == angular.isUndefinedOrNull) {
                            $scope.isSessionNull = true;
                        }
                        $scope.loading = false;

                    }
                    $scope.CallSocketMarket();
                }
                catch(ex)
                {
                    $scope.CallSocketMarket();
                }
                });
      

            }

    }

 

$scope.$on('$destroy', function () {
    $timeout.cancel($scope.ajaxTimer);
});

//step 2
    $scope.CallSocketMarket=function(){
$scope.ajaxTimer = $timeout(function(){
	var utype=sessionService.get('type');
		var sName="";
		var cName=$state.current.name;
	   if(utype==1)	
		{
			sName="masterDashboard.Matchodds";
		}
	    else
		{
			sName="dealerDashboard.Matchodds";
		}
	if(cName==sName){
callType1="2";
 //$scope.GetMarketListId();
		   get_userser.getSocketDataApiDetail($scope.MarketLst,function(data) {
		       try{


	 			 var dataResult = data;
		                     var tempResult =[];
		                
		                         if(dataResult !=angular.isUndefinedOrNull && dataResult['cricket'].length>0 && dataResult['cricket'][0] !=angular.isUndefinedOrNull)
		                         {
		                            for(var j=0;j<$scope.FinalArray.length;j++)
						{
					             var ind = dataResult['cricket'].findIndex(x=>x.id==$scope.FinalArray[j].id);
				                     if(ind>-1)
				                     {
		                                        tempResult.data=dataResult['cricket'][ind];
                                         $scope.FinalArray[j].status=tempResult.data.status;
                                         $scope.FinalArray[j].inPlay=tempResult.data.inPlay;
							if($scope.FinalArray[j].mtype=="MATCH_ODDS"){
		                                         $scope.BindIndianFancy(dataResult['session'],$scope.FinalArray[j].id);
							}
							 $scope.SocketMarket(tempResult);
			                                 $scope.loading = false;
						
						     }
						}
					}
					$timeout.cancel($scope.ajaxTimer);
                   $scope.CallSocketMarket();

               }
               catch (e) {
                   $scope.CallSocketMarket();
               }
				});

if($scope.SPORTID==4)
{
	//$scope.getFancyList($scope.MarketId);
	
	//$scope.BindIndianFancy($scope.MarketId);
}	

// $scope.GetScore();
}
},1000);
	}
    $scope.BindIndianFancy=function(data,mId)
    {


            // var temp  =  JSON.parse(event.data);

            var dataResult=data;

            // dataResult.push(data.message);
            var tempResult =[];
            if(dataResult!=angular.isUndefinedOrNull)
            {

                if(dataResult.length>0 && dataResult[0]!=null)
                {
                    var ind = dataResult.findIndex(x=>x.market_id==mId);
                    if(ind>-1)
                    {
                        tempResult.data=dataResult[ind].value.session;
                        $scope.FancyLiveData=tempResult.data;
                        $scope.loading = false;
                        //$scope.SocketMarket(tempResult);
                        if($scope.FancyData!=angular.isUndefinedOrNull)
                        {

                            for(var i=0;i<$scope.FancyData.length;i++)
                            {

                                if($scope.FancyData[i].is_indian_fancy==1 && $scope.FancyData[i].fancy_mode=="A")
                                {
                                    if($scope.FancyLiveData!=angular.isUndefinedOrNull)
                                    {
                                        var inde = $scope.FancyLiveData.findIndex(img => img.SelectionId === $scope.FancyData[i].ind_fancy_selection_id);
                                        if(inde > -1)
                                        {
                                            var obj = $scope.FancyLiveData[inde];
                                            if(obj!=angular.isUndefinedOrNull)
                                            {
                                                $scope.FancyData[i].SessInptNo=obj.LayPrice1;
                                                $scope.FancyData[i].NoValume=obj.LaySize1;
                                                $scope.FancyData[i].SessInptYes=obj.BackPrice1;
                                                $scope.FancyData[i].YesValume=obj.BackSize1;
                                                if(obj.GameStatus=='SUSPENDED1')
                                                {
                                                    $scope.FancyData[i].DisplayMsg='Ball Running';
                                                }
                                                else{
                                                    $scope.FancyData[i].DisplayMsg=obj.GameStatus;
                                                }
                                                if($scope.FancyDataTemp.length>0 && $scope.FancyDataTemp[i].active==0)
                                                {
                                                    $scope.FancyData[i].active=$scope.FancyDataTemp[i].active;
                                                }
                                                else{
                                                    $scope.FancyData[i].active=(obj.GameStatus =="" || obj.GameStatus=='ACTIVE') ? 1 : 4;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $scope.FancyData[i].SessInptNo='';
                                            $scope.FancyData[i].SessInptYes='';
                                            $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                            $scope.FancyData[i].active= 4;
                                        }


                                    }
                                }

                            }

                        }
                        else{
                            // sessionsocket.onclose();
                            //  Dialog.autohide("Betting not allow.");
                            $scope.loading = false;
                            //   $scope.IsShowPage=false;
                            //  $state.go("userDashboard.Home");


                        }

                    }
                    else
                    {

                        //sessionsocket.onclose();
                        //Dialog.autohide("Record not found.");
                        $scope.loading = false;
                        //$scope.IsShowPage=false;
                        if($scope.FancyData!=angular.isUndefinedOrNull)
                        {

                            for(var i=0;i<$scope.FancyData.length;i++)
                            {
                                if($scope.FancyData[i].is_indian_fancy==1 && $scope.FancyData[i].fancy_mode=="A" && $scope.FancyData[i].market_id==$scope.MarketId)
                                {
                                    $scope.FancyData[i].SessInptNo='';
                                    $scope.FancyData[i].SessInptYes='';
                                    $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                    $scope.FancyData[i].active= 4;

                                }
                            }
                        }


                    }
                }
                else{
                    // sessionsocket.onclose();
                    // Dialog.autohide("Betting not allow.");
                    $scope.loading = false;
                    // $scope.IsShowPage=false;
                    //$state.go("userDashboard.Home");

                }

                // $('#odds').html(JSON.stringify(event.data));
                //var Data = JSON.parse(event.data);
                //   alert(JSON.stringify(event.data));
                //console.log(JSON.stringify(event.data));
                //showMessage("<div class='"+Data.buy+"'>"+Data.sell+Data.average+"</div>");
                //$('#chat-message').val('');
            }

    }
// step 4

     $scope.UpdateAdminFancyList = function(marketId) {
var utype=sessionService.get('type');
		var sName="";	
		var cName=$state.current.name;
	    if(utype==1)	
		{
			sName="masterDashboard.Matchodds";
		}
	    else
		{
			sName="dealerDashboard.Matchodds";
		}
$scope.UpdateAdminFancyTimer=$timeout(function(){
if(cName==sName){
      $http({
                    method: 'GET',
                    url: BASE_URL +'Apicontroller/matchLstAdminSession/'+$stateParams.MatchId+'/'+marketId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(result) {

$scope.AdminFancyLiveData=result.data;
if($scope.AdminFancyLiveData.length>0)
{
				       for(var i=0;i<$scope.FancyData.length;i++)
                        {
                            
                            if($scope.FancyData[i].fancy_mode=="M")
                            {
			
				var  inde = $scope.AdminFancyLiveData.findIndex(img => img.ID ===$scope.FancyData[i].ID);
                                if(inde > -1)
                                {
                                var obj = $scope.AdminFancyLiveData[inde];
                                if(obj!=angular.isUndefinedOrNull)
                                {
                                $scope.FancyData[i].SessInptNo=obj.SessInptNo;
                                $scope.FancyData[i].SessInptYes=obj.SessInptYes;
				$scope.FancyData[i].NoValume=obj.NoValume;
                                $scope.FancyData[i].YesValume=obj.YesValume;
				$scope.FancyData[i].MaxStake=obj.MaxStake;
                                $scope.FancyData[i].pointDiff=obj.pointDiff;
				$scope.FancyData[i].rateDiff=obj.rateDiff;
                                $scope.FancyData[i].fancyRange=obj.fancyRange;
                                $scope.FancyData[i].DisplayMsg=obj.DisplayMsg;
                                  $scope.FancyData[i].active=obj.active;
                                }
    
                                }
                                else
                                {
                                $scope.FancyData[i].SessInptNo='';
                                $scope.FancyData[i].SessInptYes='';
                                $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                    $scope.FancyData[i].active= 4;	
                                }
    
                            
                            }
                        }
}
		 $scope.UpdateAdminFancyList(marketId);

			}).error(function(err){
			 $scope.loading = false;
          $scope.UpdateAdminFancyList(marketId);
		});	
}
},1000);
    }

//step5
$scope.SocketMarket = function(result)
{
	
    
    //$scope.FancyLiveData = result.session;
    var market = result.data.runners;
    var j = $scope.FinalArray.findIndex(x=>x.id==result.data.id);
    if(market!=angular.isUndefinedOrNull)
    {
     
        for(var m=0;m<market.length;m++)
        {
         //   ;
	 
		if(j>-1){
           var inde = $scope.FinalArray[j].runners.findIndex(img => img.id ===market[m].id);
           if(inde>-1)
           {
	       $scope.FinalArray[j].IsMatchDisable=false;
               for(var b=0;b<$scope.FinalArray[j].runners[inde].back.length;b++)
               {
                var count = b+1;  
                try{
                    $scope.FinalArray[j].runners[inde].back[b].selected =  $scope.CallColor($scope.FinalArray[j].runners[inde].back[b].price,$scope.FinalArray[j].runners[inde].back[b].size, market[m].back[b].price,market[m].back[b].size);                 
                }
                catch(e)
                {

                }
               try{
                $scope.FinalArray[j].runners[inde].back[b].price = market[m].back[b].price;
               }
               catch(e)
               {
                if($scope.FinalArray[j].runners[inde].back[b]!=angular.isUndefinedOrNull)
                {
                    $scope.FinalArray[j].runners[inde].back[b].price="";
                }
               }
               try{
                $scope.FinalArray[j].runners[inde].back[b].size = market[m].back[b].size;
                 }
                catch(e)
                {
                    
                }
                
              
               }
               for(var b=0;b<$scope.FinalArray[j].runners[inde].lay.length;b++)
               {
                var count = b+1;  
		try{
                $scope.FinalArray[j].runners[inde].lay[b].selected =  $scope.CallColor($scope.FinalArray[j].runners[inde].lay[b].price,$scope.FinalArray[j].runners[inde].lay[b].size, market[m].lay[b].price,market[m].lay[b].size);    
		}
		catch(e)
                {
		}             
                try{
                    $scope.FinalArray[j].runners[inde].lay[b].price = market[m].lay[b].price;
                }
                catch(e)
                {
                        if($scope.FinalArray[j].runners[inde].lay[b]!=angular.isUndefinedOrNull)
                    {
                        $scope.FinalArray[j].runners[inde].lay[b].price="";
                    }
                }

                   try{
                       $scope.FinalArray[j].runners[inde].lay[b].size = market[m].lay[b].size;
                   }
                   catch (e) {

                   }
               }
      

           }
	   else
		{
			$scope.FinalArray[j].IsMatchDisable=true;
		}
        }
    }

                   
}   

}
   /* $scope.BindMarket=function(result)
{

	 if(result.data != angular.isUndefinedOrNull && result.data.selection.result!=angular.isUndefinedOrNull)
		{
		$scope.marketIdLst = result.data.marketid;
		$scope.MarketWinLoss(result.data.marketid);
                $scope.MarketLst=result.data.marketid.split(',');
        $scope.AllMarket =  $scope.MarketLst;
        $scope.FinalArray[0] = result.data.selection.result[0];
        $scope.GetMarketBackLayData =result.data.selection;
		if(result.data.selection.result[0]!=null && result.data.selection.result.length > 0)
		{
			$scope.CallType='first';
            $scope.MarketId=result.data.marketid
            $scope.getFancyList(result.data.marketid);
          
            
		}
		else
		{
			
			$scope.MarketRunnerLst = result.data.marketRunner == null ? [] : result.data.marketRunner;
			$scope.TempArray =[];
			for(var j=0;j<$scope.MarketRunnerLst.length;j++)
			{
				if ($scope.MarketRunnerLst[j] != angular.isUndefinedOrNull && $scope.MarketRunnerLst[j].status == "CLOSED") 
				{
					  var vSelectionID = $filter('filter')($scope.MarketRunnerLst[j].runners, { status: "WINNER" })[0].selectionId;
					 var obj ={"marketId":$scope.MarketRunnerLst[j].marketId,"selectionId":vSelectionID};
					$scope.TempArray.push(obj);
					
				}
			}
			if($scope.TempArray.length>0)
			{
				$scope.saveMatchoddsResultAutoMatic($scope.TempArray);
			}
		}
		}
		else{$scope.loading = false;}
}*/

       $scope.getFancyList = function(marketId) {
	$scope.getFancyTimer=$timeout(function(){
      $http({
                    method: 'GET',
                    url: BASE_URL +'Apicontroller/matchLstIndianSession/'+$scope.MatchId+'/'+marketId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(result) {
		if(result.data!=angular.isUndefinedOrNull && result.data.length!=$scope.FancyData.length)
		{
		
		 $scope.FancyData = result.data;
		}
		else
		{
            if(result.data!=angular.isUndefinedOrNull){
                $scope.FancyDataTemp= result.data;
                for(var i=0;i<$scope.FancyData.length;i++) {
                    var inde = $scope.FancyDataTemp.findIndex(x=>x.ID==$scope.FancyData[i].ID);
                    if (inde > -1) {
                        /*      if($scope.FancyDataTemp[inde].fancy_position.length>0)
                              {
                                  $scope.FancyData[i].fancy_position=$scope.FancyDataTemp[inde].fancy_position;
                                  $scope.FancyData[i].max_exposure=$scope.FancyDataTemp[inde].max_exposure;
                              }*/
                        $scope.FancyData[i].max_session_bet_liability=$scope.FancyDataTemp[inde].max_session_bet_liability;
                        $scope.FancyData[i].max_session_liability=$scope.FancyDataTemp[inde].max_session_liability;
                        $scope.FancyData[i].HeadName=$scope.FancyDataTemp[inde].HeadName;
                        $scope.FancyData[i].MaxStake=$scope.FancyDataTemp[inde].MaxStake;
                        $scope.FancyData[i].fancy_mode=$scope.FancyDataTemp[inde].fancy_mode;
                        if($scope.FancyDataTemp[inde].fancy_mode=='M')
                        {


                            $scope.FancyData[i].SessInptNo=$scope.FancyDataTemp[inde].SessInptNo;
                            $scope.FancyData[i].NoValume=$scope.FancyDataTemp[inde].NoValume;
                            $scope.FancyData[i].SessInptYes=$scope.FancyDataTemp[inde].SessInptYes;
                            $scope.FancyData[i].YesValume=$scope.FancyDataTemp[inde].YesValume;
                            $scope.FancyData[i].isIndianShow = true;
                            $scope.FancyData[i].pointDiff=$scope.FancyDataTemp[inde].pointDiff;
                            $scope.FancyData[i].rateDiff=$scope.FancyDataTemp[inde].rateDiff;
                            $scope.FancyData[i].fancyRange=$scope.FancyDataTemp[inde].fancyRange;
                            $scope.FancyData[i].DisplayMsg=$scope.FancyDataTemp[inde].DisplayMsg;
                            $scope.FancyData[i].active=$scope.FancyDataTemp[inde].active;
                        }
                    }

                }
            }
		}
$scope.getFancyList(marketId);	
}).error(function(err){
			 $scope.loading = false;
          $scope.getFancyList(marketId);
		});
		
	},1000);	

    }



    $scope.MarketWinLoss = function(lstMarket)
	{
       // $scope.WinLossTimeOut = $timeout(function(){
           // if($state.current.name=="dealerDashboard.Matchodds" || $state.current.name=="masterDashboard.Matchodds") {
		$http({
			method:"POST",
			url:BASE_URL +'Apicontroller/market_win_loss',
			data: {"matchId":$scope.MatchId,"MarketId":lstMarket},
			headers:{'Content-Type':'application/x-www-form-urlencoded'}
			}).success(function(data){
                		$scope.TRunnerValue = data.data;
            //$scope.MarketWinLoss(lstMarket);

			})
            //}
        //},3000);
		
	}
    $scope.MarketWinLossByMId = function(MarketId)
	{

		
		var obj = null;
		if($scope.TRunnerValue != angular.isUndefinedOrNull)
		{
			if($filter('filter')($scope.TRunnerValue, { marketId: MarketId })[0]!= angular.isUndefinedOrNull){
				  obj = $filter('filter')($scope.TRunnerValue, { marketId: MarketId })[0].runners;
			}
		  
		  
		}
		return obj;
	
	}

            $scope.getLiveFancyList = function() {
                $timeout(function() {
               // socket.emit('CallLiveFancy', {auth:Bauthdata,MarketId:$scope.MarketId,UserId:$scope.UserId});
                   /*get_userser.getBothSessionFancy($stateParams.MatchId,function(response) {
                   $scope.loading=false;
                       $scope.FancyData = response.data;            
                   });*/
                   $.ajax({
                       url:'http://139.162.52.34/customer/v1/market.php?market_id='+$scope.MarketId,
                       type:'GET',
                       dataType:'JSON',
                       success:function(result){
                        $scope.loading=false;
                        //result = JSON.parse(result);
                        $scope.FancyLiveData = result.session;
                        var market = result.market[0].events;
                        if(market!=angular.isUndefinedOrNull)
                        {
                            for(var m=0;m<market.length;m++)
                            {
                             //   ;
                               var inde = $scope.FinalArray[0].runners.findIndex(img => img.id ===market[m].SelectionId);
                               if(inde>-1)
                               {
                                  // try{
                                       for(var b=0;b<$scope.FinalArray[0].runners[inde].back.length;b++)
                                       {
                                        var count = b+1;  
                                        try{
                                            $scope.FinalArray[0].runners[inde].back[b].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[b].price,$scope.FinalArray[0].runners[inde].back[b].size, market[m]["BackPrice"+count],market[m]["BackSize"+count]);                 
                                        }
                                        catch(e)
                                        {

                                        }
                                       try{
                                        $scope.FinalArray[0].runners[inde].back[b].price = market[m]["BackPrice"+count];
                                       }
                                       catch(e)
                                       {
                                        if($scope.FinalArray[0].runners[inde].back[b]!=angular.isUndefinedOrNull)
                                        {
                                            $scope.FinalArray[0].runners[inde].back[b].price="";
                                        }
                                       }
                                       try{
                                        $scope.FinalArray[0].runners[inde].back[b].size = market[m]["BackSize"+count];
                                         }
                                        catch(e)
                                        {
                                            
                                        }
                                        
                                      
                                       }
                                       for(var b=0;b<$scope.FinalArray[0].runners[inde].lay.length;b++)
                                       {
                                        var count = b+1;  
                                        $scope.FinalArray[0].runners[inde].lay[b].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[b].price,$scope.FinalArray[0].runners[inde].lay[b].size, market[m]["LayPrice"+count],market[m]["LaySize"+count]);                 
                                        try{
                                            $scope.FinalArray[0].runners[inde].lay[b].price = market[m]["LayPrice"+count];
                                        }
                                        catch(e)
                                        {
                                                if($scope.FinalArray[0].runners[inde].lay[b]!=angular.isUndefinedOrNull)
                                            {
                                                $scope.FinalArray[0].runners[inde].lay[b].price="";
                                            }
                                        }
                                        
                                        $scope.FinalArray[0].runners[inde].lay[b].size = market[m]["LaySize"+count];
                                       }
                                 
                               }
                            }
                        }
                           
                   for(var i=0;i<$scope.FancyData.length;i++)
                                   {
                                       
                                       if($scope.FancyData[i].is_indian_fancy==1 && $scope.FancyData[i].fancy_mode=="A")
                                       {
                                           var inde = $scope.FancyLiveData.findIndex(img => img.SelectionId === 											$scope.FancyData[i].ind_fancy_selection_id);
                                           if(inde > -1)
                                           {
                                           var obj = $scope.FancyLiveData[inde];
                                           if(obj!=angular.isUndefinedOrNull)
                                           {
                                           $scope.FancyData[i].SessInptNo=obj.LayPrice1;
                                           $scope.FancyData[i].SessInptYes=obj.BackPrice1;
                                           $scope.FancyData[i].DisplayMsg=obj.GameStatus;
                                             $scope.FancyData[i].active=obj.GameStatus =="" ? 1 : 4;
                                           }
               
                                           }
                                           else
                                           {
                                           $scope.FancyData[i].SessInptNo='';
                                           $scope.FancyData[i].SessInptYes='';
                                           $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                               $scope.FancyData[i].active= 4;	
                                           }
               
                                       
                                       }
                                   }
                           
                      $scope.getLiveFancyList();
                       },
                       error:function(err){
                        $scope.loading = false;
                       }
                       
                   })
           },1000);
               }
               $scope.CallColor=function(Oldprice,Oldsize,NPrice,NSize)
               {
                   if(Oldprice!=NPrice || Oldsize!=NSize)
                               {
                                   return true;
                               }
                               else{
                                   return false;
                               }
               }
  
    $scope.$on('$destroy', function () {
	    $timeout.cancel($scope.ajaxTimer);
	    $timeout.cancel($scope.getMarketlstTimer);
	    $timeout.cancel($scope.getFancyTimer);
	    $timeout.cancel($scope.GetScoreTimer);
	    $timeout.cancel($scope.UpdateAdminFancyTimer);
	    
		
   });
$scope.ClearAllTime=function()
{
	 $timeout.cancel($scope.ajaxTimer);
	    $timeout.cancel($scope.getMarketlstTimer);
	    $timeout.cancel($scope.getFancyTimer);
	    $timeout.cancel($scope.GetScoreTimer);
	    $timeout.cancel($scope.UpdateAdminFancyTimer);
	    $timeout.cancel($scope.fetchAllbet);
		
}
}]);
app.directive('crntusrpsn', function() { //sourabh 170118
    return {
        templateUrl: 'app/scripts/directives/timeline/Matchodds_crntusr_psn.html',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: ['$scope', '$http', '$stateParams', 'sessionService', '$interval','$timeout', function($scope, $http, $stateParams, sessionService, $interval,$timeout) {
	var callType=1;
	var timeDelay=0;
	var oldUId=0;var oldUType="";
            $scope.getUserPosition = function(userId, userType) {
	if(userId!=oldUId || userType != oldUType){
	oldUType = userType;
	oldUId=userId;
	$scope.loading1=true;
	$timeout.cancel($scope.si_getCrntUserPosition);
        callType=1;
	}
$scope.si_getCrntUserPosition = $timeout(function(){
                $scope.crntusep_userId = userId;
                $scope.crntusep_userType = userType;
                if (userType != "3") {
                    $http.get(BASE_URL + 'Usercurrntposicntr/getUserPosition/' + userId + '/' + userType + '/' + $stateParams.MatchId + '/' + $stateParams.MarketId).success(function(data, status, headers, config) {
                        $scope.loading1=false;
                        $scope.totalTeamA = 0;
                        $scope.totalTeamB = 0;
                        $scope.totaltheDraw = 0;
                        $scope.userPosition = data.userPosition;
                        $scope.userOwnPosition = data.userOwnPosition;
                        if ($scope.userPosition != angular.isUndefinedOrNull) //sourabh 170107
                            for (var i = 0; i < $scope.userPosition.result_array.length; i++) {
                                $scope.totalTeamA = parseFloat($scope.totalTeamA) + parseFloat($scope.userPosition.result_array[i].TeamA);
                                $scope.totalTeamB = parseFloat($scope.totalTeamB) + parseFloat($scope.userPosition.result_array[i].TeamB);
                                $scope.totaltheDraw = parseFloat($scope.totaltheDraw) + parseFloat($scope.userPosition.result_array[i].theDraw);
                            }
                        /*console.log($scope.totalTeamA);
                         //
                         alert($scope.totalTeamA);*/
$scope.getUserPosition(userId, userType);
callType=2;
                    });
                }
},callType==1 ? 0 : 5000);
            }
            $scope.getCrntUserPosition_Back = function() {
                $scope.crntusep_userId = sessionService.get('user_id');
                $scope.crntusep_userType = sessionService.get('type');
                $scope.getCrntUserPosition();
            }
            $scope.getUserPosition(sessionService.get('user_id'), sessionService.get('type'));
            $scope.getCrntUserPosition = function() {
                $scope.getUserPosition($scope.crntusep_userId, $scope.crntusep_userType);
            } //sourabh 170127
           // $scope.si_getCrntUserPosition = $timeout($scope.getCrntUserPosition, 5000);
            $scope.$on("$destroy", function(event) {
		//$timeout.cancel(lsMarketVal);
                $timeout.cancel($scope.si_getCrntUserPosition);
                //clearInterval($scope.si_getCrntUserPosition);
            }); //sourabh 170124
        }]
    }
});
