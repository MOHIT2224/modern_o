app.controller('Matchoddscntr', ['$scope', '$http', '$stateParams', 'sessionService', '$timeout', 'Dialog', '$rootScope', 'deviceDetector', 'get_userser', '$mdDialog', 'speech', '$filter', '$location', '$state', '$interval', function($scope, $http, $stateParams, sessionService, $timeout, Dialog, $rootScope, deviceDetector, get_userser, $mdDialog, speech, $filter, $location, $state, $interval) {
    $scope.$on('test_dir', function(event, data) { $scope.getNameFunc(); });

    var marketTimer;
    $scope.loading = false;
    $scope.loadingM = false;
    $scope.dateForm = new Date($stateParams.date);
    $scope.sportId = 0;
    var stopped;
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
    $scope.PLAYPAUSE=0;
    var matchStatus = "OPEN";
    get_userser.userChipSetting(function(response) {
        $rootScope.userPlcBtn = response;
        $rootScope.MyLenth = response.length;
    });
    $scope.test_dir = function() {
        // alert("XXXX");
    }
    $scope.scorePosition=function(FancyId,FancyTypeId){
        
       
            var $promise = $http.get(BASE_URL + 'Sessioncntr/FancyScorePosition/'  + FancyId + '/' + sessionService.get('slctUseID') + '/' + FancyTypeId);
            $promise.then(function (response) {
                
                $scope.scorePosition1=response.data.scorePosition;
            });
       
    }
    $scope.styleColor=function(value){
        if(value < 0){
            return "RED";
        }else{
            return "GREEN";
        }

    }
   
        if($scope.gtTypeId !=3){
            //
            var scorePosition = $interval(function () {
            //$scope.scorePosition();
            if($stateParams.MatchId == sessionService.get('MatchId') && $scope.gtTypeId !=3){
                var $promise = $http.get(BASE_URL + 'Sessioncntr/FancyScorePosition/'  + sessionService.get('fancyId') + '/' + sessionService.get('slctUseID') + '/' + sessionService.get('FancyType'));
                $promise.then(function (response) {
                    //
                    $scope.scorePosition=response.data.scorePosition;
                });
            } 
            }, 2000);
        }
 

            
   
   // $scope.GetScore=function(){
        
   //          var eventIds = $stateParams.MatchId;
        
   //        $http.get(BASE_URL+'Geteventcntr/GetScoreApi/'+eventIds).then(function(result) {
            

   //            if (result.data.length!= 0) {
   //                  $scope.Documents=result.data[0];
   //                  $scope.displayScore=true;
   //                  if($scope.Documents.eventTypeId==2){
   //                      $scope.Home=result.data[0].score.home.gameSequence;
   //                      $scope.away=result.data[0].score.away.gameSequence;
   //                  }
   //            }else{
   //                  $scope.displayScore=false;
   //                  $interval.cancel($scope.stopScore);
   //            }
              
              
              
   //        });
   //  }
   //  $scope.stopScore = $interval(function () {
   //                  //Display the current time.
   //                 $scope.GetScore();
   //              }, 5000);

    $scope.countdown = function() {
        stopped = $timeout(function() {
            currentdate = new Date();
            $scope.sysDateTime = currentdate.getDate() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getFullYear() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
            if (moment($scope.dateForm) > moment(currentdate))
                $scope.sysDateTimeDiff = moment.utc(moment($scope.dateForm).diff(moment(currentdate))).format("D [Days] hh:mm:ss");
            $scope.countdown();
        }, 5000);
    };
    $scope.getOddValue = function(item, priceVal, matchId, back_layStatus, placeName, selectionId) {
        $scope.UserTypeId = sessionService.get('type');
        $scope.UserId = sessionService.get('slctUseID');
        $scope.ParantId = sessionService.get('slctParantID');
        $scope.loginId = sessionStorage.user_id;
        $scope.slctUseTypeID = sessionService.get('slctUseTypeID');
        $scope.stake = 0;
        $scope.priceVal = parseFloat(priceVal.toFixed(2));
        $scope.MatchId = $scope.MatchId;
        $scope.displayTable = true;
        $scope.acc = 1;
        $scope.formStatus = back_layStatus;
        $scope.placeName = placeName;
        $scope.selectionId = selectionId;
        var s = item.currentTarget.getAttribute("data-id");
        $scope.testModel = parseFloat(priceVal);
        var oldPnLValue1 = 0;
        $scope.slctProfit = 0;
        if ($scope.RunnerValue != angular.isUndefinedOrNull && $scope.RunnerValue.length != angular.isUndefinedOrNull) {
            if ($scope.formStatus == '1') {
                oldPnLValue1 = $filter('filter')($scope.RunnerValue, { SelectionId: $scope.selectionId })[0]; //170316
                $scope.oldPnLValue = $scope.getSumValPnL(oldPnLValue1.winValue, oldPnLValue1.lossValue);
                $scope.slctProfit = $scope.getSumValPnL(oldPnLValue1.winValue, oldPnLValue1.lossValue);
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
    //Add new Function 27-11-2017
    $scope.sum = function (items, prop) {
            return items.reduce(function (a, b) {
                var t = parseFloat(a) + parseFloat(b[prop]);
                return parseFloat(a) + parseFloat(b[prop]);
            }, 0);
    };
    $scope.GetUserData=function(){
        $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
            $scope.UserData = data.betUserData;
        });
    }
   // $scope.GetUserData();
    /*end of new function*/
   $scope.getApiFrom = function(MarketId, MatchId) {
        ////
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
            //////
            P_and_l = (priceVal * stake) - stake;
            var bal = sessionService.get('Balance');
            var balValCheck = false;
            $scope.arrayText1 = [];
            $scope.GetMarketBackLayDataSelectionName.find(function(item, index) {
                ////////            
                var t1 =document.getElementById('NewLiability_'+index).value;
                $scope.arrayText1.push(parseFloat(t1));
                console.log("New Pnl Value" + $scope.arrayText1);
                
               
            });
           // //////
            var minVal1 = Math.min.apply(Math, $scope.arrayText1.map(function(item) { return item; }));
            
            var vWalletLiabilityTotal = parseFloat($rootScope.Balance) - parseFloat($rootScope.Liability);
            var vWalletTeamProfitTotal = parseFloat(sessionService.get('Balance')) + parseFloat($scope.oldPnLValue);
            bal = vWalletLiabilityTotal;
            if(minVal1<0){
                minVal1=-minVal1+$scope.TOTAL_LIABILITY-$scope.TotalGetVal;
           
            }else if(minVal1>=0){
                minVal1=minVal1+$scope.TOTAL_LIABILITY-$scope.TotalGetVal;
               

            }
            if (bal < minVal1) balValCheck = true;
            /*if (balValCheck) {
                Dialog.show('Insufficient Balance');
                $scope.stake = 0;
                $scope.btnPlaceDis = false;
                $scope.loading = false;
                $scope.loadingM=false;
                return;
            }*/
            if (deviceDetector.device == 'unknown') {
                var DIVICE = 'Desktop';
            } else {
                var DIVICE = deviceDetector.device;
            }
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
           
                if ($scope.oldPnLValue >= 0) //170316
                    
                var chkUserType = sessionService.get('type');
                if (isback == 0 || isback == 1) {
                    ////////                  
                        //if ($scope.cValid) {
                            $http({
                                method: 'POST',
                                url: 'Betentrycntr/Save_bet/',
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
                                            Dialog.show(data.message);
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
                                        Dialog.show(data.message);
                                        $scope.btnPlaceDis = false;
                                        $scope.loading = false;
                                         $scope.loadingM = false;
                                    }
                                
                            });
                       // } else {
                       //     $scope.loading = false;
                       //     $scope.stake = 0;
                        //    $scope.btnPlaceDis = false;
                         //   alert("something Missing Plz try again...");
                     //   }
                   

                } 

            
        });
    }
    $scope.place_bet = function() {
        ////
        $scope.loadingM = true;
        $http.get('Chipscntrl/checkDeduction/' + sessionService.get('slctUseID')+"/"+$stateParams.MatchId).then(function(articles){
            //////
            return parseInt(articles.data.chkcnt[0].numb);
        }).then(function(cnt){
            //////
            if(cnt==0){
                $http.get('Chipscntrl/getChipDataById/' + sessionService.get('slctUseID')).success(function(data, status, headers, config) {
              
                        var cipsData = data.betLibility;
                        $scope.sessionLiability = -1*parseFloat(cipsData[0].sessionLiability).toFixed(2);
                        $scope.GET_BALANCE=parseFloat(cipsData[0].Balance).toFixed(2);
                        /*start code cut balance*/
                        $http.get('Betentrycntr/PointValue/').success(function (data, status, headers, config) { 
                            //////
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
                                        url: 'Chipscntrl/SaveChipAc/',
                                        data: ChipData,
                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                                    }).success(function (data) {
                                        $scope.TOTAL_LIABILITY=-1*(parseFloat(cipsData[0].sessionLiability)+parseFloat(cipsData[0].unmatchliability));
                                            
                                    });

                                }else{
                                    Dialog.show('Insufficient Balance');
                                    $scope.stake = 0;
                                    $scope.btnPlaceDis = false;
                                    
                                    $scope.loadingM = false;
                                    return;

                                }
                        });
                        /*End of code to cut Balance*/
                });

            }else{
                $http.get('Chipscntrl/getChipDataById/' + sessionService.get('slctUseID')).success(function(data, status, headers, config) {
                    ////
                        var cipsData = data.betLibility;
                        $scope.sessionLiability = -1*parseFloat(cipsData[0].sessionLiability).toFixed(2);
                        $scope.GET_BALANCE=parseFloat(cipsData[0].Balance).toFixed(2);
                        $scope.TOTAL_LIABILITY=-1*(parseFloat(cipsData[0].sessionLiability)+parseFloat(cipsData[0].unmatchliability));
                       
                });

            }
            $scope.TotalGetVal=0;
            if ($scope.priceVal != angular.isUndefinedOrNull) {
                $http.get('Geteventcntr/getUserMatchResult/' + sessionService.get('user_id') + '/' + sessionService.get('type')).success(function (data, status, headers, config) {
                   ////////
                   var TeamData = data.matchRslt;
                   if(data.matchRslt != angular.isUndefinedOrNull){
                      // //////
                        for (var i = 0; i <TeamData.length; i++) {
                            if(TeamData[i].matchId != $stateParams.MatchId){
                                if((TeamData[i].TeamA<TeamData[i].TeamB)&&((TeamData[i].TeamA<TeamData[i].theDraw))){
                                    if(TeamData[i].TeamA>=0){

                                    }else{
                                        $scope.TotalGetVal=$scope.getSumValPnL($scope.TotalGetVal,parseFloat(TeamData[i].TeamA).toFixed(2));
                                    }
                                }else if((TeamData[i].TeamB<TeamData[i].TeamA)&&((TeamData[i].TeamB<TeamData[i].theDraw))){
                                    if(TeamData[i].TeamB>=0){

                                    }else{
                                        $scope.TotalGetVal=$scope.getSumValPnL($scope.TotalGetVal,parseFloat(TeamData[i].TeamB).toFixed(2));
                                    }
                                }else{
                                    if(TeamData[i].theDraw>=0){

                                    }else{
                                        $scope.TotalGetVal=$scope.getSumValPnL($scope.TotalGetVal,parseFloat(TeamData[i].theDraw).toFixed(2));
                                    }
                                }
                            }
                        }
                       
                        //minVal = Math.min.apply(Math, $scope.arrayText.map(function(item) { return item; })); 
                   }else{
                    //set 0 after calculation
                   }
                });
                $scope.GetUserData();
                
                get_userser.getBetDelay(sessionService.get('slctUseID'), function(data) {
                    ////
                    var BetDelay = (parseInt(data) * 1000);
                    if ($scope.GetMarketBackLayData.status == "OPEN" && $scope.stake >= 1) {
                        $timeout(function() { $scope.getApiFrom($stateParams.MarketId, $stateParams.MatchId) }, BetDelay);
                    } else if ($scope.stake <= 50) {
                        Dialog.autohide('Please Enter Min 1 Stake');
                        $scope.loadingM = false;
                    } else if ($scope.GetMarketBackLayData.status != "OPEN") {
                        Dialog.autohide('Match Closed');
                        $scope.loadingM = false;
                    }
                });
            } else {
                alert("Inavalid Bet");
            }
            return true;

        }).then(function(temp){
            if(temp==true){
                $http.get('Chipscntrl/getChipDataById/' + sessionService.get('slctUseID')).success(function(data, status, headers, config) {
                                   //
                                    $scope.arrayText=[];
                                    var cipsData = data.betLibility;
                                    var userBal = cipsData[0].Balance;
                                    if(userBal<0){
                                         $scope.UserData.find(function(item, j) {
                                        //////                                    
                                            
                                            var t1 = parseInt(item.MstCode);
                                            $scope.arrayText.push(t1);
                                            console.log("Push+===" + $scope.arrayText);
                                       
                                    });
                                    var MaxId = Math.max.apply(Math, $scope.arrayText.map(function(item) { return item; }));
                                   // alert("MaxId is"+MaxId);
                                        if(isMatched==1){
                                            $http.get('Betentrycntr/deleteGetbettingmat/' + MaxId + '/' + sessionService.get('user_id')+'/'+$stateParams.MarketId).success(function(data, status, headers, config) {
                                                Dialog.autohide(data.message);
                                                $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                                                    $scope.UserData = data.betUserData;
                                                    $scope.getBetsData();
                                                });
                                            });
                                        }else{
                                            $http.get('Betentrycntr/deleteGetbetting/' + MaxId + '/' + sessionService.get('user_id')).success(function(data, status, headers, config) {
                                                Dialog.autohide(data.message);
                                                $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                                                    $scope.UserData = data.betUserData;
                                                    $scope.getBetsData();
                                                });
                                            });
                                        }
                                    }
                                   
                                });
            }

        });
    };
    
    $scope.accBet = function(val) {
        switch (val) {
            case 1:
                $scope.acc = !$scope.acc;
                break;
            case 2:
                $scope.acc1 = !$scope.acc1;
                break;
            case 3:
                $scope.acc2 = !$scope.acc2;
                if ($scope.acc2) $rootScope.$broadcast('getFancyList', {});
                break;
        }
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
        $http({ method: 'POST', url: 'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
            .success(function(data) {
                try {
                    $scope.message = data.status.message;
                    $rootScope.$broadcast('changeSidebar_Market', {});

                    if (sessionService.get('type') == "1")
                        $state.go('dashboard.Masterdashboard');
                    else if (sessionService.get('type') == "2")
                        $state.go('dashboard.Dealerdashboard');
                    else if (sessionService.get('type') == "3")
                        $state.go('dashboard.Userdashboard');
                } catch (e) { console.log(data.status.error); }
            });
    }
    $scope.getNameFunc = function() {
        var user_id = sessionService.get('slctUseID');
        var user_type = sessionService.get('slctUseTypeID');
        
        $http.get('Geteventcntr/getBackLaysOfMarketSelectionName/' + $scope.MarketId + '/' + user_id + '/' + user_type + '/' + $scope.MatchId).success(function(data, status, headers, config)
            {
                
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
    $scope.counter = 0;
    var totalMatch = 0;
    var selectedRunner = null;
    $scope.$on("$destroy", function(event) {
    $interval.cancel($scope.stopScore);
        $timeout.cancel($scope.callOddsFunc);
        $scope.callOddsFunc = angular.isUndefinedOrNull;
    });
    $scope.callOddsFunc = function() {
        var maxloop = 0;
        if (sessionService.get('slctUseTypeID') == 3) {
            $scope.UserId = sessionService.get('slctUseID');
            get_userser.GetWALLibiInfo($scope.UserId);
        } else {
            $scope.UserId = sessionService.get('user_id');
            get_userser.GetWALLibiInfo($scope.UserId);
        }
        var $promise = $http.get('Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + $stateParams.MatchId);
        $promise.then(function(response) {
            //For Play Pause start
            if (sessionService.get('type') != "0") {
                $http({
                    method: 'POST',
                    url: 'Geteventcntr/matchMarketLst/',
                    data: {
                        matchId: $stateParams.MatchId,
                        sportsId: 4,
                        user_id: sessionService.get('user_id')
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    try {
                        //////// 
                        if ($filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay == "1") {
                            //////// ;
                            $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;
                           /* $rootScope.$broadcast('changeSidebar_Market', {});
                            if (sessionService.get('type') == "1")
                                $state.go('dashboard.Masterdashboard');
                            else if (sessionService.get('type') == "2")
                                $state.go('dashboard.Dealerdashboard');
                            else if (sessionService.get('type') == "3")
                                $state.go('dashboard.Userdashboard');*/

                        }else{
                          $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;  
                        }
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
                if (response.data.MarketRunner != angular.isUndefinedOrNull && response.data.MarketRunner.totalMatched > totalMatch) {
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
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price != selectedRunner[j].ex.availableToLay[1].price  || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].size != selectedRunner[j].ex.availableToLay[1].size) {
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
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price != selectedRunner[j].ex.availableToLay[2].price  || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].size != selectedRunner[j].ex.availableToLay[2].size) {
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
            }, 1000);
            /*{aakash 161226*/
            var OnlineStatus = $interval(OnlineStatusChanged, 10000)
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
                    Changed = $interval(updatedOnline, 100)
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
            /*}aakash 161226*/
        });
        //}
    }
    $scope.callOddsCloseMatch = function() { //sourabh 15-nov-2016
       // 
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
                $http.get('Geteventcntr/getSelectionName/' + $scope.MarketId + '/' + $scope.MatchId).success(function(data, status, headers, config) {
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
            if (sessionService.get('type') == "1")
                $state.go('dashboard.Masterdashboard');
            else if (sessionService.get('type') == "2")
                $state.go('dashboard.Dealerdashboard');
            else if (sessionService.get('type') == "3")
                $state.go('dashboard.Userdashboard');

        }

    };
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
        $scope.stake = parseInt(val);//$scope.stake + 
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
            //////
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

        //var maxLoss = parseFloat($scope.maxLossU);
        //if (formdata.isback == 0)
        //    maxLoss = maxLoss - formdata.stake;
        //else
        //    maxLoss = maxLoss - formdata.p_l;
        //var maxProfit = 0;
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
            return 'color:#1ed61e';
        else
            return 'color:#ff0000';
    }
    $scope.getOddCalcVal = function(a, ovType) { //sourabh 161229
        var x = 0,
            y = 0,
            z = 0;
        switch (ovType) {
            case 1:
                if (a != angular.isUndefinedOrNull) {
                    x = a;
                    if ($scope.oddsLimit != angular.isUndefinedOrNull) y = $scope.oddsLimit;
                }
                z = parseFloat((parseFloat(x) + parseFloat(y)).toFixed(2));
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
    $scope.getNameFunc();
    $scope.callOddsFunc();
    $scope.countdown();

    $scope.$on("$destroy", function(event) {
        $timeout.cancel(marketTimer);
        marketTimer = angular.isUndefinedOrNull;
    });

    $scope.getFancyList = function() {
        get_userser.getSessionFancy($stateParams.MatchId, 4, function(response) {
            $scope.FancyData = response;
            /* response[0].TypeID;*/
            //
            if (response != angular.isUndefinedOrNull && response.length > 0) {
                $scope.sessionFancy = response[0].ID;
                $scope.sessionFancyType = response[0].TypeID;
            } else {
                /* $scope.sessionFancy=response[0].ID;
                $scope.sessionFancyType=response[0].TypeID;*/
            }
        });
    }
    $scope.getFancyList();

}]);
app.directive('crntusrpsn', function() { //sourabh 170118
    return {
        templateUrl: 'app/scripts/directives/timeline/Matchodds_crntusr_psn.html',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: ['$scope', '$http', '$stateParams', 'sessionService', '$interval','$timeout', function($scope, $http, $stateParams, sessionService, $interval,$timeout) {
            $scope.getUserPosition = function(userId, userType) {
                $scope.crntusep_userId = userId;
                $scope.crntusep_userType = userType;
                if (userType != "3") {
                    $http.get(BASE_URL + 'Usercurrntposicntr/getUserPosition/' + userId + '/' + userType + '/' + $stateParams.MatchId + '/' + $stateParams.MarketId).success(function(data, status, headers, config) {
                        
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
                    });
                }
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
            $scope.si_getCrntUserPosition = $timeout($scope.getCrntUserPosition, 5000);
            $scope.$on("$destroy", function(event) {
                $timeout.cancel($scope.si_getCrntUserPosition);
                //clearInterval($scope.si_getCrntUserPosition);
            }); //sourabh 170124
        }]
    }
});
app.directive('betslist', function() { //sourabh 170118
    return {
        templateUrl: 'app/scripts/directives/timeline/Matchodds_betslist.html',
        restrict: 'AE',
        replace: true,
        scope: {
            showFancy: "=fancy",
            showBetSlip: "=betslip",
            acc2: "@acc2"
        },
        link: function(scope, element, attrs) { //sourabh 170120
            console.log('attrs>>', scope);
            scope.$on('changeText', function(event, data) {
                scope.getBetsData1();
            });
            scope.$on('getFancyList', function(event, data) { //sourabh 170127
                scope.getFancyList();
            });
        },
        controller: ['$scope', '$http', '$stateParams', 'sessionService', '$interval', 'Dialog', 'get_userser', 'deviceDetector', 'speech', 'focus', '$rootScope','$timeout', function($scope, $http, $stateParams, sessionService, $interval, Dialog, get_userser, deviceDetector, speech, focus, $rootScope,$timeout) { //sourabh 170125
            $scope.USERTYPE = sessionService.get('type');
            $scope.loading=false;
            $scope.getFancyList = function() {
                get_userser.getSessionFancy($stateParams.MatchId, 4, function(response) {
                    $scope.FancyData = response;
                    /* response[0].TypeID;*/
                    //
                    if (response != angular.isUndefinedOrNull && response.length > 0) {
                        $scope.sessionFancy = response[0].ID;
                        $scope.sessionFancyType = response[0].TypeID;
                    } else {
                        /* $scope.sessionFancy=response[0].ID;
                        $scope.sessionFancyType=response[0].TypeID;*/
                    }
                });
            }
           /* var disbleFancy = $interval(function () {
               
            
            
                var $promise = $http.get(BASE_URL + 'Sessioncntr/DisableAllFancy/'+ $stateParams.MatchId);
                $promise.then(function (response) {
                    
                });

          
            }, 350000);*/
            
            $scope.getFancyList();
            $scope.betMaUn = 1;
            // code commented by manish  12_10_2017

            //comment the interval by Manish  12_10_2017
            $scope.si_getMatchUnmatchData = $timeout($scope.getMatchUnmatchData, 2000);
            $scope.$on("$destroy", function(event) {
                //clearInterval($scope.si_getMatchUnmatchData);
                //clearInterval(si_fancyData);
                $interval.cancel(disbleFancy);// for disable Fancy
                $timeout.cancel(si_fancyData);

                $scope.si_getMatchUnmatchData = angular.isUndefinedOrNull;
                si_fancyData = angular.isUndefinedOrNull;
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
                    $scope.loading=false;
                    return false;
                }else if( $scope.FancyData[sessionData.Index].SessInptYes==sessionData.SessInptYes && $scope.FancyData[sessionData.Index].SessInptNo==sessionData.SessInptNo && $scope.FancyData[sessionData.Index].active==sessionData.Active){
                    return true;
                }else{
                    Dialog.show('Fancy is Inactive Or Fancy Value Not Match');
                    $scope.loading=false;
                   return false; 
                }

                
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
                    $scope.userType = sessionStorage.type;
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
                    $scope.userType = sessionStorage.type;
                    $scope.UserTypeId = sessionService.get('slctUseTypeID');
                    focus('betValueLay');
                } else
                    Dialog.autohide('Please Select Valid User');
            }
            $scope.GetFancyBal = function() { //sourabh 170125
                // ////////
                get_userser.GetFancyBal($scope.FancyData[0].ID, function(response1) {
                    // ////////
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
                $scope.betValue[id] = parseInt(Value1);//parseInt($scope.betValue[id]) + chnage By Manish
            }
             $scope.saveSessionBet = function(pointDiff,FancyData,IndexVal) { 
                //
                $scope.loading=true;
                var HeadName = FancyData.HeadName;
                var SessInptNo = FancyData.SessInptNo;
                var SessInptYes = FancyData.SessInptYes;
                var FncyId = FancyData.FncyId;
                var sportId = $scope.$parent.sportId;
                var UserTypeId = sessionService.get('slctUseTypeID');
                var UserId = sessionService.get('slctUseID');
                var loginId = sessionStorage.user_id;
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
                    type: sessionStorage.type,
                    OddsNumber: OddsNumber,
                    TypeID: $scope.sessionFancyType,
                    HeadName: HeadName,
                    SessInptNo: SessInptNo,
                    SessInptYes: SessInptYes,
                    sportId: sportId,
                    FancyId: FncyId,
                    pointDiff: pointDiff,
                    deviceInformation: deviceInformation,
                    Index :IndexVal,
                    Active: FancyData.active
                }
                //$scope.GetFancyBal();
                get_userser.GetFancyBal($scope.FancyData[0].ID, function(response1) {
                    if (response1 == null) {
                        $scope.TotalBet = 0;
                    } else {
                        $scope.TotalBet = response1;
                    }
                    var GetSumVal = parseInt($scope.TotalBet) + parseInt(amount);
                    var chkuBaL = parseInt($rootScope.Balance); /* + parseInt($rootScope.Liability)*/
                    var MaxStake = parseInt($scope.FancyData[0].MaxStake); /** parseInt($scope.FancyData[0].RateChange)*/
                    if ((chkuBaL >= amount)) {
                        $timeout(function() {
                        var check=$scope.checkValidation(sessionData);
                        if (check) {
                                $http({
                                    method: 'POST',
                                    url: BASE_URL + 'Lstsavemstrcontroller/saveUserFancy',
                                    data: sessionData,
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                                }).success(function(data) {
                                    $scope.showOdd1 = false;
                                     $scope.loading=false;
                                    get_userser.GetWALLibiInfo(sessionService.get('slctUseID'));
                                    Dialog.autohide('Place Bet Successfully...');
                                    $scope.getBetsData1();
                                });
                            }
                        }, 4000);
                        //if ((parseInt($scope.TotalBet) <= MaxStake - 500 && GetSumVal <= MaxStake + 500)) { //if ((GetSumVal <= MaxStake)) {
                            
                        /*} else {
                            Dialog.autohide('Rate Change...');
                        }*/
                    } else {
                       $scope.loading=false;
                        Dialog.show('Insufficient Balance...ssss');
                    }
                });
                
            };
            var si_fancyData = $interval(callAtTimeout, 1000);

            function callAtTimeout() {
                if ($scope.FancyData != angular.isUndefinedOrNull && $scope.FancyData.length > 0 ) { //&& $scope.FancyData[0].active != 2
                    if ($scope.sessionFancyType != angular.isUndefinedOrNull) {
                        get_userser.GetFancyData1($stateParams.MatchId, $scope.sessionFancy, sessionService.get('user_id'), sessionService.get('type'), $scope.sessionFancyType, function(response) {
                            //
                            var GetFancyLength=response.data.fancyForm.length;
                            for (var i = 0; i < GetFancyLength; i++) {
                                if (response.data != angular.isUndefinedOrNull && response.data.fancyForm.length <= 0 || (($scope.FancyData[i].SessInptYes == response.data.fancyForm[i].SessInptYes) && ($scope.FancyData[i].SessInptNo == response.data.fancyForm[i].SessInptNo) && ($scope.FancyData[i].active == response.data.fancyForm[i].active) && ($scope.FancyData[i].pointDiff = response.data.fancyForm[i].pointDiff) && ($scope.FancyData[i].DisplayMsg == response.data.fancyForm[i].DisplayMsg) && ($scope.FancyData.length == GetFancyLength)))
                                {}
                                else if (response.data != angular.isUndefinedOrNull) {
                                    $scope.showOdd1 = false;
                                    $scope.FancyData=response.data.fancyForm;
                                    $scope.FancyMessage=response.data.fancyForm[0].DisplayMsg
                                    /*$scope.FancyData[i].SessInptYes = response.data.fancyForm[i].SessInptYes;
                                    $scope.FancyData[i].SessInptNo = response.data.fancyForm[i].SessInptNo;
                                    $scope.FancyData[i].active = response.data.fancyForm[i].active;
                                    $scope.FancyData[i].FncyId = response.data.fancyForm[i].FncyId;
                                    $scope.FancyData[i].pointDiff = response.data.fancyForm[i].pointDiff;
                                    $scope.FancyData[i].MaxStake = response.data.fancyForm[i].MaxStake;
                                    $scope.FancyData[i].NoValume = response.data.fancyForm[i].NoValume;
                                    $scope.FancyData[i].YesValume = response.data.fancyForm[i].YesValume;
                                    $scope.FancyData[i].DisplayMsg = response.data.fancyForm[i].DisplayMsg;
                                    $scope.FancyData[i].RateChange = response.data.fancyForm[i].RateChange;*/
                                    if (response.data.fancyForm[i].active == 1) {
                                        if (response.data.fancyForm[i].YesValume != "1") {
                                        } else {
                                        }
                                    } else if (response.data.fancyForm[i].active == 4 || response.data.fancyForm[i].active == 2) {
                                    } else if (response.data.fancyForm[i].active == 0) { var msg = "Ball started..." }
                                //$scope.GetFancyBal(); //Comment The Function In Match Fancy
                                } //else $scope.GetFancyBal();  //Comment The Function In Match Fancy  Checking Max Stack
                            }                            
                        });
                    } else {  $scope.getFancyList(); }
                } else { $scope.getFancyList(); }
            }
        }]
    }
});
app.directive('matchunmatchlist', function() { //sourabh 170118
    return {
        templateUrl: 'app/scripts/directives/timeline/matchUnmatch.html',
        restrict: 'AE',
        replace: true,
        scope: {
            showFancy: "=fancy",
            showBetSlip: "=betslip",
            acc2: "@acc2"
        },
        link: function(scope, element, attrs) { //sourabh 170120
            console.log('attrs>>', scope);
            scope.$on('changeText', function(event, data) {
                scope.getBetsData1();
            });
            
        },
        controller: ['$scope', '$http', '$stateParams', 'sessionService', '$interval', 'Dialog', 'get_userser', 'deviceDetector', 'speech', 'focus', '$rootScope','$timeout','$filter', function($scope, $http, $stateParams, sessionService, $interval, Dialog, get_userser, deviceDetector, speech, focus, $rootScope,$timeout,$filter) { //sourabh 170125
            $scope.USERTYPE = sessionService.get('type');
            $scope.UnmatchedBets={};
             $scope.MatchId = $stateParams.MatchId;
            $scope.MarketId = $stateParams.MarketId;
            ////////
            $scope.betMaUn = 1;
            $scope.SetFValue=0;
            $scope.showBetsData1=false;
            // code commented by manish  12_10_2017
            $scope.getMatchUnmatchData = function() {
                //////
                if ($scope.$parent.GetMarketBackLayData != angular.isUndefinedOrNull && $scope.$parent.GetMarketBackLayData.status != "CLOSED" && $scope.$parent.MatchResult != "CLOSED") {
                    $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                            //////
                        var oldUserData = 0;
                        if ($scope.UserData != angular.isUndefinedOrNull) oldUserData = $scope.UserData.length;
                        $scope.UserData = data.betUserData;
                        // console.log('8**************************',$scope.UserData,'.......................',data.betUserData.length,'-----------------------',oldUserData);
                        if (oldUserData != data.betUserData.length ) 
                            $scope.getBetsData();
                            try {
                                for (var i = 0; i < $scope.UserData.length; i++) {
                                    if ($scope.$parent.GetMarketBackLayData != angular.isUndefinedOrNull) {
                                        $scope.$parent.GetMarketBackLayData.runners.find(function(item, j) {
                                            if (item.selectionId == $scope.UserData[i].SelectionId && ($scope.$parent.GetMarketBackLayData.marketId == $scope.UserData[i].MarketId) && ($scope.UserData[i].MatchId == $stateParams.MatchId) && ($scope.UserData[i].IsMatched == 0)) {
                                                if ($scope.UserData[i].isBack == 0) {
                                                    if (item.ex.availableToBack.length != 0 && $scope.UserData[i].Odds <= (item.ex.availableToBack[0].price + $scope.$parent.oddsLimit)) {
                                                        $http.get('Betentrycntr/updateUnMatchedData/' + $scope.UserData[i].MstCode + '/' + 0 + '/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                                                            $scope.UserData = data.betUserData;
                                                            $scope.getBetsData();
                                                        });
                                                    }
                                                } else {
                                                    if (item.ex.availableToLay.length != 0 && $scope.UserData[i].Odds >= (item.ex.availableToLay[0].price + $scope.$parent.oddsLimit)) {
                                                        $http.get('Betentrycntr/updateUnMatchedData/' + $scope.UserData[i].MstCode + '/' + 1 + '/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                                                            $scope.UserData = data.betUserData;
                                                            $scope.getBetsData();
                                                        });
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }
                            } catch (e) {}
                    });
                } else if ($scope.$parent.GetMarketBackLayData != angular.isUndefinedOrNull) {
                    $interval.cancel($scope.si_getMatchUnmatchData);
                    $interval.cancel(si_fancyData);
                    $scope.si_getMatchUnmatchData = angular.isUndefinedOrNull;
                    si_fancyData = angular.isUndefinedOrNull;
                }
            }
            var columnDefs = [
                {
                    headerName: "Sno", width: 50, field: "SrNo",suppressMenu:true, cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head'); }
                },

                {
                    headerName: "Runner", width: 150, field: "selectionName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Odds", width: 80, field: "Odds", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Stake", width: 80, field: "Stack", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "P&L", width: 80, field: "P_L", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: 'Time', width: 140, field: "MstDate", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Id", width: 60, field: "MstCode", cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head'); }
                },
            ];
            var columnDefsUn = [
                // this row just shows the row index, doesn't use any data from the row
                { headerName: "Sno", width: 30, field: "SrNo", cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head'); } },
                
                

               
                {
                    headerName: "Runner", width: 100, field: "selectionName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: "Odds", width: 80, field: "Odds", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: "Stake", width: 80, field: "Stack", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: "P&L", width: 80, field: "P_L", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: 'Time', width: 140, field: "MstDate", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },
                {
                    headerName: "Id", width: 60, field: "MstCode", cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head'); }
                }

            ];
            var columnDefsSess = [
                // this row just shows the row index, doesn't use any data from the row
                { headerName: "Sno", width: 30, field: "SrNo", cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head'); } },
               
                {
                    headerName: "Runner", width: 110, field: "selectionName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: "Odds", width: 100, field: "Odds", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: "Stake", width: 80, field: "Stack", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: "P&L", width: 80, field: "P_L", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },

                {

                    headerName: 'Time', width: 140, field: "MstDate", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                },
                {
                    headerName: "Id", width: 60, field: "MstCode", cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head'); }
                }

            ];
            
            if (sessionService.get('type') == 0) {

                columnDefs.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefs.splice(2, 0, {

                    headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefs.splice(2, 0, {

                    headerName: "Master", width: 110, field: "MasterName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                columnDefs.splice(8, 0, {

                    headerName: "IP ADDRESS", width: 110, field: "ip", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                

                columnDefsUn.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsUn.splice(2, 0, {

                    headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsUn.splice(2, 0, {

                    headerName: "Master", width: 110, field: "MasterName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                // columnDefsUn.splice(1, 0, {

                //     headerName: "MatchedTest", width: 100, cellRenderer: ageCellRendererFunc2, cellClass: function (params) {

                //         return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                //     }

                // });
                columnDefsUn.splice(1, 0, {

                    headerName: "Action", width: 50,suppressMenu:true, cellRenderer: ageCellRendererFunc, cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                columnDefsUn.splice(8, 0, {

                    headerName: "IP ADDRESS", width: 110, field: "ip", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                
                columnDefsSess.splice(2, 0, {

                    headerName: "Client", width: 100, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsSess.splice(2, 0, {

                    headerName: "Dealer", width: 100, field: "ParantName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsSess.splice(2, 0, {

                    headerName: "Master", width: 160, field: "MasterName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                columnDefsSess.splice(8, 0, {

                    headerName: "IP ADDRESS", width: 110, field: "ip", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
               /* columnDefsUn.push({

                    headerName: 'Matched',
                    width: 100,
                    cellRenderer: ageCellRendererFunc2,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });*/
                // add New button for Unmatched To Matched
                columnDefs.push({

                    headerName: 'Action',
                    width: 120,
                    cellRenderer: ageCellRendererFunc1,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });
                // End  Of add New button for Unmatched To Matched
                columnDefsSess.push({

                    headerName: 'Action',
                    width: 100,
                    cellRenderer: ageCellRendererFunc1,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });

            }
            else if (sessionService.get('type') == 1) {

                columnDefs.splice(2, 0, {

                    headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefs.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsUn.splice(2, 0, {

                    headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsUn.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                 columnDefsSess.splice(2, 0, {

                    headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsSess.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                /*columnDefs.push({

                    headerName: 'Action',
                    width: 100,
                    cellRenderer: ageCellRendererFunc1,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });
                columnDefsSess.push({

                    headerName: 'Action',
                    width: 100,
                    cellRenderer: ageCellRendererFunc1,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });*/

            }
            else if (sessionService.get('type') == 2) {

                columnDefs.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });

                columnDefsUn.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                columnDefsSess.splice(2, 0, {

                    headerName: "Client", width: 110, field: "userName", cellClass: function (params) {

                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');

                    }

                });
                /*columnDefs.push({

                    headerName: 'Action',
                    width: 100,
                    cellRenderer: ageCellRendererFunc1,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });
                columnDefsSess.push({

                    headerName: 'Action',
                    width: 100,
                    cellRenderer: ageCellRendererFunc1,
                    cellClass: function(params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }

                });*/
            }else if (sessionService.get('type') == 3 || sessionService.get('type') == 0) {
                {
                    columnDefsUn.push({

                        headerName: 'Action', width: 100, cellRenderer: ageCellRendererFunc, cellClass: function (params) {
                            return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                        }
                    });
                }
               
            }
            function ageCellRendererFunc(params) {

                var eSpan = document.createElement('button');

                eSpan.className = "del-btn";

                //eSpan.innerHTML = '<img src="http://www.google.com/intl/en_com/images/logo_plain.png" style="width: 50px;height: 20px;">';

                eSpan.innerHTML = '<span class="glyphicon glyphicon-trash"></span>';

                eSpan.addEventListener('click', function () {

                    raiseevent(params);

                });

                return eSpan;

            }
            function ageCellRendererFunc2(params) {

                var eSpan = document.createElement('button');

                eSpan.className = "del-btn";

                //eSpan.innerHTML = '<img src="http://www.google.com/intl/en_com/images/logo_plain.png" style="width: 50px;height: 20px;">';

                eSpan.innerHTML = '<span class="glyphicon glyphicon-ok-sign"></span>';

                eSpan.addEventListener('click', function () {

                    raiseevent2(params);

                });

                return eSpan;

            }
            function ageCellRendererFunc1(params) {

                var eSpan = document.createElement('button');

                eSpan.className = "del-btn";

                //eSpan.innerHTML = '<img src="http://www.google.com/intl/en_com/images/logo_plain.png" style="width: 50px;height: 20px;">';

                eSpan.innerHTML = '<span class="glyphicon glyphicon-trash"></span>';

                eSpan.addEventListener('click', function() {

                    raiseevent1(params);

                });

                return eSpan;

            }
            $scope.deleteUser = function (betId, userId) {
                var result = confirm("Are you sure want to delete Records Unmatched");
                if (result) {
                    $http.get('Betentrycntr/deleteGetbetting/' + betId + '/' + userId).success(function (data, status, headers, config) {
                        /*Dialog.autohide(data.message);*/
                        Dialog.autohide("Record Deleted Successfully...");
                        $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                            $scope.UserData = data.betUserData;
                            $scope.getBetsData();
                        });



                    });

                }

            }
            $scope.deleteUser1 = function (betId, userId,MarketId) {
                var result = confirm("Are you sure want to delete Records Matched"+MarketId);
                if (result) {
                    $http.get('Betentrycntr/deleteGetbettingmat/' + betId + '/' + userId+'/'+MarketId).success(function (data, status, headers, config) {
                        //Dialog.autohide(data.message);
                         Dialog.autohide("Record Deleted Successfully...");
                        //////
                        $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                            $scope.UserData = data.betUserData;
                            $scope.getBetsData();
                        });
                    });

                }

            }
            $scope.UnMatchToMatchedData=function(Parametr){
                //////
                $http.get('Betentrycntr/updateUnMatchedData/' + Parametr.MstCode + '/' + Parametr.isBack + '/' + Parametr.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + Parametr.MatchId).success(function(data, status, headers, config) {
                    $scope.UserData = data.betUserData;
                    $scope.getBetsData();
                });
            }
            function raiseevent(params) {
                //////

                var params;

                $scope.deleteUser(params.data.MstCode, params.data.UserId);

            }
            function raiseevent2(params) {
                //////

                var params;

                $scope.UnMatchToMatchedData(params.data);

            }
            function raiseevent1(params) {
                //////

                var params;

                $scope.deleteUser1(params.data.MstCode, params.data.UserId,params.data.MarketId);

            }
            $scope.CurrentAllBets = {

                enableSorting: true,

                enableFilter: true,

                debug: false,

                rowSelection: 'multiple',
                pagination: false,
                enableColResize: true,

                paginationPageSize: 100,

                columnDefs: columnDefs,

                rowModelType: 'virtual',

            };
            $scope.CurrentAllBetsUn = {

                enableSorting: true,

                enableFilter: true,

                debug: true,

                rowSelection: 'multiple',
                pagination: false,
                
                enableColResize: true,

                paginationPageSize: 100,

                columnDefs: columnDefsUn,

                rowModelType: 'virtual',
               

            };
            $scope.CurrentAllBetsSe = {

                enableSorting: true,

                enableFilter: true,

                debug: true,

                rowSelection: 'multiple',
                pagination: false,
                enableColResize: true,

                paginationPageSize: 100,

                columnDefs: columnDefsSess,

                rowModelType: 'virtual',
                

            };
            var allOfTheData;

            function createNewDatasource(type) {
                if (!allOfTheData) { return; }
                var dataSource = {
                    getRows: function(params) {
                        setTimeout(function() {
                            var rowsThisPage = allOfTheData.slice(params.startRow, params.endRow);
                            var lastRow = -1;
                            if (allOfTheData.length <= params.endRow) {
                                lastRow = allOfTheData.length;
                            }
                            params.successCallback(rowsThisPage, lastRow);
                        }, 1000);
                    }
                };
                if (type == 'Un')
                    $scope.CurrentAllBetsUn.api.setDatasource(dataSource);
                else if(type == 'Ma' && $scope.SetFValue==0){

                    $scope.CurrentAllBets.api.setDatasource(dataSource);
                }else{
                    $scope.CurrentAllBetsSe.api.setDatasource(dataSource);
                }
            }

            function setRowData(rowData, type) {
                allOfTheData = rowData;
                createNewDatasource(type);
            }
            $scope.getBetsData = function () {
                ////////
                //if (sessionService.get('type') != "3")
                   // speech.sayText("b");
                $scope.MatchedBets = [];
                $scope.SessiondBets = [];
                $scope.UnmatchedBets = [];
                angular.forEach($scope.UserData, function (value, index) {
                    ////////
                    if (value.IsMatched == "1" && value.MarketId =="2"){
                            $scope.SessiondBets.push(value);
                    }else if(value.IsMatched == "1" && value.MarketId !="2"){
                            $scope.MatchedBets.push(value);
                    }
                    else if(value.IsMatched == "0"){
                        $scope.UnmatchedBets.push(value);
                    }
                });
                $scope.callBetsData($scope.betMaUn,$scope.SetFValue);//sourabh 170110
            }
            $scope.callBetsData = function (isMatched,setFancy) {
                //////
                $scope.betMaUn = isMatched;//sourabh 170110
                if (isMatched == "1") {
                    if ($scope.MatchedBets.length > 0){
                        $scope.showBetsData = true;
                        $scope.showBetsData0 = false;
                        $scope.showBetsData1 = false;
                    }
                    else{
                        $scope.showBetsData = false;
                        $scope.showBetsData0 = false;
                        $scope.showBetsData1 = true;
                    }
                    //$scope.CurrentAllBets.api.sizeColumnsToFit();
                    if (setFancy==1) {
                        $scope.showBetsData = false;
                        $scope.showBetsData0 = false;
                        $scope.showBetsData1 = true;
                        setRowData($scope.SessiondBets, 'Ma');
                    }else{
                        $scope.showBetsData = true;
                        $scope.showBetsData0 = false;
                        $scope.showBetsData1 = false;
                        setRowData($scope.MatchedBets, 'Ma');
                    }
                    
                }
                else {
                    if ($scope.UnmatchedBets.length > 0){
                        $scope.showBetsData = false;
                        $scope.showBetsData0 = true;
                        $scope.showBetsData1 = false;
                    }
                    else{
                        $scope.showBetsData = false;
                        $scope.showBetsData0 = false;
                        $scope.showBetsData1 = false;
                    }
                   // $scope.CurrentAllBetsUn.api.sizeColumnsToFit();
                    setRowData($scope.UnmatchedBets, 'Un');
                }
            }
             $scope.setFancyData=function(setValue){
                if (setValue==1) {
                    //$scope.showBetsData1=true;
                    $scope.SetFValue=setValue;
                }else{
                    //$scope.showBetsData1=false;
                    $scope.SetFValue=setValue;
                }
               
            }
            $scope.getBetsData1 = function() {
                $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
                    ////
                    var UnMatchedLength = $filter('filter')(data.betUserData, {IsMatched: 0}).length;
                    if($scope.UnmatchedBets.length==angular.isUndefinedOrNull){
                       $scope.UnmatchedBets.length=0; 
                    }
                    if ($scope.UserData.length==data.betUserData.length && UnMatchedLength==$scope.UnmatchedBets.length) {

                    }else{
                        $scope.UserData = data.betUserData;

                        $scope.getBetsData();
                        $scope.getBetsData1();
                    }
                    
                });
            }
            $scope.getBetsData1();
            //comment the interval by Manish  12_10_2017
            var getGrid1 = $interval($scope.getBetsData1, 2000);
            //var getGrid2 = $interval($scope.getBetsData, 2000);
            $scope.si_getMatchUnmatchData = $interval($scope.getMatchUnmatchData, 2000);
            $scope.$on("$destroy", function(event) {
                $interval.cancel(getGrid2);
                $interval.cancel(getGrid1);
                $timeout.cancel(si_fancyData);
                $interval.cancel($scope.si_getMatchUnmatchData);

                $scope.si_getMatchUnmatchData = angular.isUndefinedOrNull;
               // si_fancyData = angular.isUndefinedOrNull;
            }); //sourabh 170124

           
            

            
        }]
    }
});