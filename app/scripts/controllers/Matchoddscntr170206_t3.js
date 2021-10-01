app.controller('Matchoddscntr170206_t3', function ($scope, $http, $stateParams, sessionService, $timeout, Dialog, $rootScope, deviceDetector, get_userser, $mdDialog, $q) {
    
    $scope.loading = false;
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
    $scope.logInTypeId = sessionService.get('type');
    $scope.logInId = sessionService.get('user_id');
    var MarketId = $stateParams.MarketId;
    var matchStatus = "OPEN";

    get_userser.userChipSetting(function (response) {
        $rootScope.userPlcBtn = response;
        $rootScope.MyLenth = response.length;
    });
    $scope.countdown = function () {
        stopped = $timeout(function () {
            currentdate = new Date();
            $scope.sysDateTime = currentdate.getDate() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getFullYear() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
            if (moment($scope.dateForm) > moment(currentdate))
                $scope.sysDateTimeDiff = moment.utc(moment($scope.dateForm).diff(moment(currentdate))).format("D [Days] hh:mm:ss");
            $scope.countdown();
        }, 1000);
    };
    $scope.getOddValue = function (item, priceVal, matchId, back_layStatus, placeName, selectionId) {
        //
        //$scope.ngMessage = "";
        $scope.UserTypeId = sessionService.get('type');
        $scope.UserId = sessionService.get('slctUseID');
        $scope.ParantId = sessionService.get('slctParantID');
        $scope.loginId = sessionStorage.user_id;
        $scope.slctUseTypeID = sessionService.get('slctUseTypeID');
        //
        //alert($scope.UserTypeId);
        $scope.stake = 0;
        //$scope.priceVal = priceVal;
        $scope.priceVal = parseFloat(priceVal.toFixed(2));//sourabh 161228
        $scope.MatchId = $scope.MatchId;
        $scope.displayTable = true;
        $scope.acc = 1;
        $scope.formStatus = back_layStatus;
        $scope.placeName = placeName;
        $scope.selectionId = selectionId;
        //console.log(item.currentTarget);
        var s = item.currentTarget.getAttribute("data-id");

        $scope.testModel = parseFloat(priceVal);


    };
    $scope.reset_all_selection = function () {
        $scope.acc = 0;
    };
    $scope.getApiFrom = function (MarketId, MatchId) {

        $scope.btnPlaceDis = true;//sourabh 161224
        /*end of user balance*/

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
        /*var chkValPrice = document.getElementById('chkValPrice').value;*/
        //chkValPrice = parseFloat(chkValPrice);

        get_userser.get_OddsFromApi($stateParams.MarketId, selectionId, MatchId, isback, function (response) {
            $scope.ApiOddsValue = response;
            var chkValPrice = $scope.ApiOddsValue;
            // if ($scope.ApiOddsValue == priceVal) {170204 not use
            // var isMatched = 1;
            // } else {
            // var isMatched = 0;
            // }
            var P_and_l = 0;//var P_and_l = (priceVal * stake) - stake;sourabh 170124 change by zoeb sir
            //{sourabh 170124 change by zoeb sir
            if (isback == 0)//Back
            {
                if (priceVal <= $scope.ApiOddsValue) {//1<=1.11 and place at 1.11
                    isMatched = 1;//match
                    priceVal = $scope.ApiOddsValue;
                }
                else {//170204 1.31<=1.11 and place at 1.31
                    isMatched = 0;//unmatch
                    priceVal = priceVal;
                }
            }
            else {//lay
                if (priceVal >= $scope.ApiOddsValue) {//2>=1.12 and place bet at 1.12
                    isMatched = 1;//match
                    priceVal = $scope.ApiOddsValue;
                }
                else {//1.01>=1.12 and place bet at 1.01
                    isMatched = 0;//unmatch
                    priceVal = priceVal;
                }
            }
            P_and_l = (priceVal * stake) - stake;
            //}sourabh 170124 change by zoeb sir


            //{sourabh 161228 check wallet

            var bal = sessionService.get('Balance');
            var balValCheck = false;
            if (isback == 0)//Back
            {
                if (bal < stake) balValCheck = true;
            }
            else//Lay
            {
                if (bal < P_and_l) balValCheck = true;
            }
            if (balValCheck) {
                Dialog.autohide('Your Account Has Insufficient Balance...');
                //$scope.ngMessage = 'Your Account Has Insufficient Balance...';
                $scope.stake = 0;
                $scope.btnPlaceDis = false;
                //}
                return;
            }
            if (deviceDetector.device == 'unknown') {
                var DIVICE = 'Desktop';
            } else {
                var DIVICE = deviceDetector.device;
            }
            var deviceInformation = " browser: " + deviceDetector.browser + " browser_version :" + deviceDetector.browser_version + "  device: " + DIVICE + "  OS : " + deviceDetector.os + " os_version: " + deviceDetector.os_version;
            //}sourabh 161228 check wallet
            $scope.formData = {
                userId: sessionService.get('slctUseID'),
                ParantId: ParantId,
                loginId: loginId,
                selectionId: selectionId,
                matchId: $stateParams.MatchId,
                isback: isback,
                stake: stake,
                priceVal: priceVal,//170204
                p_l: P_and_l,
                MarketId: MarketId,
                isMatched: isMatched,
                UserTypeId: $scope.UserTypeId,
                placeName: placeName,
                MatchName: $stateParams.matchName,
                deviceInfo: deviceInformation
            }//sourabh 161226 change
            //
            $scope.getCheckLimitorVal($scope.formData);//sourabh 170128
            /*Check The user balance*/
            $http.get('Chipscntrl/getChipDataById/' + sessionService.get('slctUseID')).success(function (data, status, headers, config) {
                var cipsData = data.betLibility;
                $scope.UserBal = cipsData[0].Balance;

                var chkUserType = sessionService.get('slctUseTypeID');
                //
                if (($scope.UserBal >= $scope.stake) && (chkUserType == 3)) {

                    if ($scope.cValid)//sourabh 170128if ($scope.checkStakeLimit($scope.formData))//sourabh 170102
                    {
                        $http({
                            method: 'POST',
                            url: 'Betentrycntr/Save_bet/',
                            data: $scope.formData,
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        }).success(function (data) {
                            if (data.error == 0) {
                                //$scope.getUserPosition(sessionService.get('user_id'),sessionService.get('type')); sourabh 170118 interval added
                                //$scope.UserData = data.betUserData;//sourabh 170109//sourabh 170109  sourabh 170118 interval added
                                //$scope.getBetsData();//sourabh 170109 to show in grid//sourabh 170109 to show in grid sourabh 170118 interval added
                                //$scope.RunnerValue = data.RunnerValue;
                                $scope.priceVal = 0;
                                $scope.stake = 0;
                                $scope.acc = false;
                                $scope.btnPlaceDis = false;
                                Dialog.autohide("Bet Placed SuccessFully");
                                $scope.loading = false;
                                $rootScope.$broadcast('changeText', {});//sourabh 170120 newcode
                                //$scope.ngMessage = ''+data.message;
                                //$scope.ngMsgVal = true;
                                //$timeout($scope.callAtTimeout1, 2000);
                                $scope.RunnerValue = data.RunnerValue;
                                if (chkUserType == 3) {

                                    $scope.UserId = sessionService.get('slctUseID');
                                    get_userser.GetWALLibiInfo($scope.UserId);

                                } else {

                                    $scope.UserId = sessionService.get('user_id');
                                    get_userser.GetWALLibiInfo($scope.UserId);
                                }

                            } else if (data.error == 1) {
                                alert('' + data.message);
                                $scope.btnPlaceDis = false;
                            }


                        });
                    }
                } else if (sessionService.get('slctUseTypeID') == 3) {

                    //alert("Insufficient Balance...");
                    Dialog.autohide('Your Account Has Insufficient Balance...');
                    //$scope.ngMessage = 'Your Account Has Insufficient Balance...';//sourabh 11-nov-2016
                    $scope.stake = 0;
                    //$scope.ngMsgVal = true;
                    //$timeout($scope.callAtTimeout1, 1000);//sourabh 170104
                    $scope.btnPlaceDis = false;

                    return false;

                } else {
                    Dialog.autohide('Please Select Valid User...');
                    //$scope.ngMessage = 'Please Select Valid User...';//sourabh 11-nov-2016
                    //$scope.ngMsgVal = true;
                    $scope.stake = 0;
                    $scope.btnPlaceDis = false;
                    //$timeout($scope.callAtTimeout1, 1000);//sourabh 170104
                    return false;
                }
                //end code
            });


            /*End of Check balance*/


        });
    }
    $scope.place_bet = function () {
        $scope.loading = true;

        if ($scope.GetMarketBackLayData.status == "OPEN" && $scope.stake >= 50) {
            $timeout($scope.getApiFrom($stateParams.MarketId, $stateParams.MatchId), 3000);
            //$scope.getApiFrom($stateParams.MarketId,$stateParams.MatchId);
        }
        else if ($scope.stake <= 50) {
            Dialog.autohide('Please Enter Min 50 Stake');

        } else if ($scope.GetMarketBackLayData.status != "OPEN") {
            Dialog.autohide('Match Closed');
        }
    };
    $scope.accBet = function (val) {
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
                break;//sourabh 170103 new
        }
    };
    $scope.saveMatchoddsResult = function (Match_id, Sport_id, market_id, selectionId, model_result, spartName, matchName, MarketName, selectionName) {
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
        $timeout.cancel($scope.marketTimer);
        $http({
            method: 'POST',
            url: 'Geteventcntr/SetResult/',
            data: marketData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (data) {
            try {
                $scope.message = data.status.message;
            } catch (e) {
                console.log(data.status.error);
            }


        });
    }
    $scope.getNameFunc = function () {

        $http.get('Geteventcntr/getBackLaysOfMarketSelectionName/' + $scope.MarketId + '/' + $scope.logInId + '/' + $scope.logInTypeId + '/' + $scope.MatchId).success(function (data, status, headers, config)///sourabh 161226 change
        {
            //
            if (data.runnerSlName != angular.isUndefinedOrNull)//sourabh new 161224
                $scope.GetMarketBackLayDataSelectionName = data.runnerSlName[0].runners;
            //
            if (data.RunnerValue != angular.isUndefinedOrNull && data.RunnerValue.length != 0)//sourabh 170206
                $scope.RunnerValue = data.RunnerValue;
            $scope.GetMarketInfo = data.MarketData[0];//sourabh 7-dec-2016
        });
        //{sourabh 161228 170131
        //if ($scope.RunnerValue == angular.isUndefinedOrNull) {
        //    $http.get('Geteventcntr/getSelectionName/' + $scope.MarketId + '/' + $scope.MatchId).success(function (data, status, headers, config) {
        //        $scope.RunnerValue = data.RunnerValue;
        //    });
        //}

        //}sourabh 161228
    }
    $scope.getSumValPnL = function (a, b)//sourabh 11-dec-2016
    {
        return (parseFloat(a) + parseFloat(b));
    }
    $scope.counter = 0;
    var vTemp1 = null, vTemp2 = null, vTemp3 = null;
    var selectedRunner = null;
    $scope.callOddsFunc = function () {
        var maxloop = 0;//170204
        if (sessionService.get('slctUseTypeID') == 3) {
            $scope.UserId = sessionService.get('slctUseID');

            get_userser.GetWALLibiInfo($scope.UserId);

        } else {
            $scope.UserId = sessionService.get('user_id');
            get_userser.GetWALLibiInfo($scope.UserId);
        }
        //if (matchStatus == "OPEN") {170131


        var $promise = $http.get('Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + $stateParams.MatchId);
        $promise.then(function (response) {

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
                if (vTemp1 == angular.isUndefinedOrNull) {
                    vTemp1 = response.data.MarketRunner;
                    $scope.GetMarketBackLayData = vTemp1;
                }
                if (response.data.MarketRunner != angular.isUndefinedOrNull) {
                    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) {  }
                    $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                }
            }
            else if (MarketId == $scope.GetMarketBackLayData.marketId) {
                selectedRunner = null;
                if (response.data.MarketRunner != angular.isUndefinedOrNull) {
                    if (vTemp1 == angular.isUndefinedOrNull) {
                        
                        vTemp1 = response.data.MarketRunner;
                        selectedRunner = response.data.MarketRunner.runners;
                    }
                    else if (vTemp2 == angular.isUndefinedOrNull) {
                        vTemp2 = response.data.MarketRunner;
                    }
                    else if (vTemp3 == angular.isUndefinedOrNull) {
                        vTemp3 = response.data.MarketRunner;

                        if (vTemp1 == vTemp2 && vTemp2 == vTemp3) {
                            
                            selectedRunner = response.data.MarketRunner.runners;
                            vTemp1 = null, vTemp2 = null, vTemp3 = null
                        }
                        else {
                            vTemp1 = null, vTemp2 = null, vTemp3 = null;
                        }
                    }


                    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) {  }
                    $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    //$scope.GetMarketBackLayData.IsActive = data.IsActive;
                    $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;

                    //var selectedRunner = response.data.MarketRunner.runners;
                    //
                    if ($scope.GetMarketBackLayData.status == "OPEN" && selectedRunner != angular.isUndefinedOrNull && $scope.GetMarketBackLayData.runners != angular.isUndefinedOrNull && $scope.GetMarketBackLayData.runners.length > 0) {
                        
                        try {
                            if ($scope.GetMarketBackLayData.runners.length < selectedRunner.length)//170204
                                maxloop = selectedRunner.length;
                            else
                                maxloop = $scope.GetMarketBackLayData.runners.length;
                            for (var j = 0; j < maxloop; j++) {//170204 $scope.GetMarketBackLayData.runners.length

                                if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack.length == selectedRunner[j].ex.availableToBack.length) {
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = selectedRunner[j].ex.availableToBack[0].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].size = selectedRunner[j].ex.availableToBack[0].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[0] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = selectedRunner[j].ex.availableToBack[1].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].size = selectedRunner[j].ex.availableToBack[1].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[1] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = selectedRunner[j].ex.availableToBack[2].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].size = selectedRunner[j].ex.availableToBack[2].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = "";
                                        }
                                    }
                                }
                                else {
                                    
                                    $scope.GetMarketBackLayData.runners[j].ex.availableToBack = selectedRunner[j].ex.availableToBack;
                                }
                                if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay.length == selectedRunner[j].ex.availableToLay.length) {
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = selectedRunner[j].ex.availableToLay[0].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].size = selectedRunner[j].ex.availableToLay[0].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[0] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = selectedRunner[j].ex.availableToLay[1].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].size = selectedRunner[j].ex.availableToLay[1].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[1] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = selectedRunner[j].ex.availableToLay[2].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].size = selectedRunner[j].ex.availableToLay[2].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = "";
                                        }
                                    }
                                }
                                else {
                                    
                                    $scope.GetMarketBackLayData.runners[j].ex.availableToLay = selectedRunner[j].ex.availableToLay;
                                }
                            }
                        } catch (e) {
                            
                            $scope.GetMarketBackLayData = angular.isUndefinedOrNull;//sourabh 170128 if price came undefine
                        }
                        $scope.counter = $scope.counter + 1;
                    }
                    //else if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED")//170201
                    //{
                    //    $scope.GetMarketBackLayData = data.MarketRunner;
                    //    for (var j = 0; j < $scope.GetMarketBackLayData.runners.length; j++) {
                    //        if ($scope.GetMarketBackLayData.runners[j].status == "WINNER") {
                    //            var selectionName1 = "";
                    //            if ($scope.RunnerValue != angular.isUndefinedOrNull && $scope.RunnerValue.length > 0)//sourabh 170131
                    //            {
                    //                for (var i = 0; i < $scope.RunnerValue.length; i++) {
                    //                    if ($scope.RunnerValue[i].SelectionId == $scope.GetMarketBackLayData.runners[j].selectionId || $scope.RunnerValue[i].selectionId == $scope.GetMarketBackLayData.runners[j].selectionId) {
                    //                        selectionName1 = $scope.RunnerValue[i].selectionName;
                    //                    }
                    //                }
                    //            }
                    //            else {
                    //                if ($scope.RunnerValue == angular.isUndefinedOrNull) {
                    //                    $http.get('Geteventcntr/getSelectionName/' + $scope.MarketId + '/' + $scope.MatchId).success(function (data, status, headers, config) {
                    //                        $scope.RunnerValue = data.RunnerValue;
                    //                    });
                    //                }
                    //            }
                    //            if (selectionName1 != "")//sourabh 161228
                    //            {
                    //                $scope.saveMatchoddsResult($scope.MatchId, $scope.GetMarketInfo.SportID, $scope.MarketId, $scope.GetMarketBackLayData.runners[j].selectionId, 1, $scope.GetMarketInfo.SportName, $stateParams.matchName, $scope.GetMarketInfo.MarketName, selectionName1);
                    //            }
                    //        }
                    //    }
                    //}
                    //else {
                    //    
                    //    $scope.GetMarketBackLayData = response.data.MarketRunner;
                    //    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) {  }
                    //    $scope.GetMarketBackLayData.status = data.MarketRunner.status;
                    //    matchStatus = data.MarketRunner.status;
                    //    $scope.GetMarketBackLayData.totalMatched = data.MarketRunner.totalMatched;
                    //}
                }
            }
            else {
                
                $scope.GetMarketBackLayData = response.data.MarketRunner;
                try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) {  }
                $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                matchStatus = response.data.MarketRunner.status;
                $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
            }
            $scope.marketTimer = $timeout(function () {
                if ($scope.GetMarketBackLayData != angular.isUndefinedOrNull) {//sourabh 170107
                    for (var j = 0; j < maxloop; j++) {// $scope.GetMarketBackLayData.runners.length 170204
                        for (var i = 0; i < 3; i++) {//$scope.GetMarketBackLayData.runners[j].ex.availableToBack.length
                            try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[i].SELECTED = false; } catch (e) { }
                            //}
                            //for (var i = 0; i < 3; i++) {//$scope.GetMarketBackLayData.runners[j].ex.availableToLay.length
                            try {
                                $scope.GetMarketBackLayData.runners[j].ex.availableToLay[i].SELECTED = false;
                            } catch (e) { }
                        }
                    }
                    if ($scope.GetMarketBackLayData.Status != 3) {
                        if ($scope.GetMarketBackLayData.marketId != null) {
                            $scope.callOddsFunc();
                            $scope.getNameFunc();
                        }
                    }
                }
                else {
                    $scope.callOddsFunc();
                    $scope.getNameFunc();
                }
            }, 3000);
            /*{aakash 161226*/
            var OnlineStatus = setInterval(OnlineStatusChanged, 500)
            var updatedOnline = function () {
                //console.log("akash2", navigator.onLine)
                if (navigator.onLine) {
                    clearInterval(Changed);
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
                }
                else {
                    Changed = setInterval(updatedOnline, 100)
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
    $scope.stakeValReset = function (val) { //sourabh 15-nov-2016
        $scope.stake = parseInt(val);
    };
    $scope.getCalculation = function (priceVal, stake) {
        $scope;
        //alert("HI");
        
        if (stake == angular.isUndefinedOrNull) {
            stake = 0;
        }
        $scope.sumOfVal = parseFloat(priceVal) * parseInt(stake) - parseInt(stake);
        $scope.sumOfVal = $scope.sumOfVal.toFixed(2);

    }
    $scope.stakeVal = function (val, selectionId, stake) { //sourabh 15-nov-2016
        
        if (stake == angular.isUndefinedOrNull) {
            stake = 0;
        }
        if (stake == 0) {

        }
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
        }
        else {
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
        //}sourabh 170102
        $scope.getCheckLimitorVal($scope.formData);
        //$scope.checkStakeLimit($scope.formData);//sourabh 170102
    }
    $scope.getCheckLimitorVal = function (formdata) {
        get_userser.getCheckLimitOfPlaceBet(sessionService.get('slctUseID'), $stateParams.MatchId, $stateParams.MarketId, function (data) {

            $scope.viewUserAc1 = data.viewUserAc2[0];
            $scope.maxProfit = parseFloat(data.maxProfitData[0].maxProfit);
            if (formdata.isback == 0)
                $scope.maxProfit = $scope.maxProfit + formdata.p_l;
            else
                $scope.maxProfit = $scope.maxProfit + formdata.stake;

            $scope.checkStakeLimit($scope.formData);//sourabh 170102

        });
    }
    $scope.checkStakeLimit = function (formdata) {

        //$scope.ngMessage = '';
        //$scope.ngMsgVal = false;
        //{sourabh 170102

        var maxLoss = parseFloat(sessionService.get('Liability'));
        if (formdata.isback == 0)
            maxLoss = maxLoss - formdata.stake;
        else
            maxLoss = maxLoss - formdata.p_l;
        //}sourabh 170102
        var maxProfit = 0;//sourabh 170102 new
        //$scope.getCheckLimitorVal(formdata);
        if ($scope.viewUserAc1 == angular.isUndefinedOrNull) {
            $scope.cValid = false;//sourabh 170128
            return false;
        } else if ($scope.viewUserAc1.lgnusrCloseAc == 0) {
            Dialog.autohide('Your Account is Closed...');
            //$scope.ngMessage = 'Your Account is Closed...';
            $scope.stake = 0;
            //$scope.ngMsgVal = true;
            //$timeout(callAtTimeout, 1000);
            //$timeout($scope.callAtTimeout1, 1000);
            $scope.cValid = false;//sourabh 170128
            $scope.btnPlaceDis = false;
            return false;
        }
        else if ($scope.viewUserAc1.mstrlock == 0) {
            Dialog.autohide('Your Account is InActive...');
            //$scope.ngMessage = 'Your Account is InActive...';
            $scope.stake = 0;
            //$scope.ngMsgVal = true;
            //$timeout(callAtTimeout, 1000);
            //$timeout($scope.callAtTimeout1, 1000);
            $scope.cValid = false;//sourabh 170128
            $scope.btnPlaceDis = false;
            return false;
        }
        else if ($scope.viewUserAc1.lgnusrlckbtng == 0) {
            Dialog.autohide('Your Betting is Locked...');
            // $scope.ngMessage = 'Your Betting is Locked...';
            $scope.stake = 0;
            //$scope.ngMsgVal = true;
            //$timeout(callAtTimeout, 1000);
            //$timeout($scope.callAtTimeout1, 1000);
            $scope.cValid = false;//sourabh 170128
            $scope.btnPlaceDis = false;
            return false;
        }
        else if (parseInt($scope.viewUserAc1.stakeLimit) < $scope.stake) {
            Dialog.autohide('Your Stake Limit is out of range...');
            //$scope.ngMessage = 'Your Stake Limit is out of range...';
            $scope.stake = 0;
            //$scope.ngMsgVal = true;
            //$timeout(callAtTimeout, 1000);
            //$timeout($scope.callAtTimeout1, 1000);
            $scope.cValid = false;//sourabh 170128
            $scope.btnPlaceDis = false;
            return false;
        }
            //sourabh 170102 check max loss
        else if (-parseInt($scope.viewUserAc1.lgnUserMaxLoss) > maxLoss) {
            Dialog.autohide('Your Liability is more less so You Cannot Play This Bet.....');
            //$scope.ngMessage = 'Your Liability is more less so You Cannot Play This Bet.....';
            $scope.stake = 0;
            //$scope.ngMsgVal = true;
            //$timeout($scope.callAtTimeout1, 1000);
            $scope.cValid = false;//sourabh 170128
            $scope.btnPlaceDis = false;
            return false;
        }
        else if (parseFloat($scope.viewUserAc1.lgnUserMaxProfit) < $scope.maxProfit)//sourabh 170102 new
        {

            Dialog.autohide('Your Profit is more high so You Cannot Play This Bet.....');
            //$scope.ngMessage = 'Your Profit is more high so You Cannot Play This Bet.....';
            $scope.stake = 0;
            //$scope.ngMsgVal = true;
            //$timeout($scope.callAtTimeout1, 1000);
            $scope.cValid = false;//sourabh 170128
            $scope.btnPlaceDis = false;
            return false;
        }
        else if ($scope.viewUserAc1 != angular.isUndefinedOrNull && $scope.viewUserAc1.lgnusrCloseAc == 1 && $scope.viewUserAc1.mstrlock == 1 && $scope.viewUserAc1.lgnusrlckbtng == 1 && (parseInt($scope.viewUserAc1.stakeLimit) >= $scope.stake || parseInt($scope.viewUserAc1.stakeLimit) == 0)) {
            $scope.cValid = true;//sourabh 170128
            return true;
        }
    }
    $scope.getValColor = function (val) {//20-dec-2016 asha
        if (val == angular.isUndefinedOrNull || val == 0)
            return 'color:#000000';
        else if (val > 0)
            return 'color:#007c0e';
        else
            return 'color:#ff0000';
    }
    $scope.getOddCalcVal = function (a, ovType) {  //sourabh 161229

        var x = 0, y = 0, z = 0;
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
        if (z > 0) return z; else return "";

    }

    $scope.getNameFunc();
    $scope.callOddsFunc();
    $scope.countdown();
    $scope.$on("$destroy", function (event) {
        $timeout.cancel($scope.marketTimer);
    });
});
app.directive('crntusrpsn', function () {//sourabh 170118
    return {
        templateUrl: 'app/scripts/directives/timeline/Matchodds_crntusr_psn.html',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: function ($scope, $http, $stateParams, sessionService, $interval) {
            $scope.getUserPosition = function (userId, userType) {
                $scope.crntusep_userId = userId;
                $scope.crntusep_userType = userType;
                if (userType != "3") {
                    $http.get(BASE_URL + 'Usercurrntposicntr/getUserPosition/' + userId + '/' + userType + '/' + $stateParams.MatchId + '/' + $stateParams.MarketId).success(function (data, status, headers, config) {
                        //                
                        $scope.totalTeamA = 0;
                        $scope.totalTeamB = 0;
                        $scope.totaltheDraw = 0;
                        $scope.userPosition = data.userPosition;
                        $scope.userOwnPosition = data.userOwnPosition;
                        if ($scope.userPosition != angular.isUndefinedOrNull)//sourabh 170107
                            for (var i = 0; i < $scope.userPosition.result_array.length; i++) {
                                // 
                                $scope.totalTeamA = parseFloat($scope.totalTeamA) + parseFloat($scope.userPosition.result_array[i].TeamA);
                                $scope.totalTeamB = parseFloat($scope.totalTeamB) + parseFloat($scope.userPosition.result_array[i].TeamB);
                                $scope.totaltheDraw = parseFloat($scope.totaltheDraw) + parseFloat($scope.userPosition.result_array[i].theDraw);
                                //                   
                            }
                        /*console.log($scope.totalTeamA);
                         //
                         alert($scope.totalTeamA);*/

                    });
                }
            }
            $scope.getCrntUserPosition_Back = function () {
                $scope.crntusep_userId = sessionService.get('user_id');
                $scope.crntusep_userType = sessionService.get('type');
                $scope.getCrntUserPosition();
            }
            $scope.getUserPosition(sessionService.get('user_id'), sessionService.get('type'));
            $scope.getCrntUserPosition = function () {
                $scope.getUserPosition($scope.crntusep_userId, $scope.crntusep_userType);
            }//sourabh 170127
            $scope.si_getCrntUserPosition = setInterval($scope.getCrntUserPosition, 1000);
            $scope.$on("$destroy", function (event) {
                clearInterval($scope.si_getCrntUserPosition);
            });//sourabh 170124
        }
    }
});
app.directive('betslist', function () {//sourabh 170118
    return {
        templateUrl: 'app/scripts/directives/timeline/Matchodds_betslist.html',
        restrict: 'E',
        replace: true,
        scope: {},
        link: function (scope, element, attrs) {//sourabh 170120
            scope.$on('changeText', function (event, data) {
                scope.getBetsData1();
            });
            scope.$on('getFancyList', function (event, data) {//sourabh 170127
                scope.getFancyList();
            });
        },
        controller: function ($scope, $http, $stateParams, sessionService, $interval, Dialog, get_userser, deviceDetector, speech, focus, $rootScope) {//sourabh 170125
            $scope.getFancyList = function () {//sourabh 170127

                var marketData = { matchId: $stateParams.MatchId, sportsId: 4 }
                $http({ method: 'POST', url: 'Geteventcntr/SessionFancyData/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }).success(function (data) {
                    $scope.SessionFancyData = data.SessionFancyData;
                    $scope.getFancyUrl();
                });
            }
            $scope.getFancyList();
            $scope.getFancyUrl = function (type, matchid, FancyID, sportId) {
                switch (type) {
                    case "1":
                        return "Evenoddfancy({matchId: " + matchid + ",FancyID:" + FancyID + ",TypeID:" + type + ",matchName:'" + $stateParams.matchName + "',sportId:" + sportId + "})";
                        break;
                    case "2":
                        return "Sessionfancy({matchId: " + matchid + ",FancyID:" + FancyID + ",TypeID:" + type + ",matchName:'" + $stateParams.matchName + "',sportId:" + sportId + "})";
                        break;
                    case "3":
                        return "Khaddalfancy({matchId: " + matchid + ",FancyID:" + FancyID + ",TypeID:" + type + ",matchName:'" + $stateParams.matchName + "',sportId:" + sportId + "})";
                        break;
                    case "4":
                        return "Lastdigit({matchId: " + matchid + ",FancyID:" + FancyID + ",TypeID:" + type + ",matchName:'" + $stateParams.matchName + "',sportId:" + sportId + "})";
                        break;
                    case "5":
                        return "Updown({matchId: " + matchid + ",FancyID:" + FancyID + ",TypeID:" + type + ",matchName:'" + $stateParams.matchName + "',sportId:" + sportId + "})";
                        break;
                }
            }

            $scope.betMaUn = 1;//sourabh 170110

            $scope.getMatchUnmatchData = function () {
                /*Get Matched and Unmatched Data Check From API*/
                $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                    $scope.UserData = data.betUserData;
                    //$scope.getBetsData();//sourabh 170119 newly added
                    try {
                        for (var i = 0; i < $scope.UserData.length; i++) {

                            if ($scope.$parent.GetMarketBackLayData != angular.isUndefinedOrNull) {
                                $scope.$parent.GetMarketBackLayData.runners.find(function (item, j) {
                                    //console.log(item.selectionId == $scope.UserData[i].SelectionId && ($scope.$parent.GetMarketBackLayData.marketId == $scope.UserData[i].MarketId) && ($scope.UserData[i].MatchId == $stateParams.MatchId));
                                    if (item.selectionId == $scope.UserData[i].SelectionId && ($scope.$parent.GetMarketBackLayData.marketId == $scope.UserData[i].MarketId) && ($scope.UserData[i].MatchId == $stateParams.MatchId) && ($scope.UserData[i].IsMatched == 0)) {

                                        if ($scope.UserData[i].isBack == 0) {
                                            if (item.ex.availableToBack.length != 0 && $scope.UserData[i].Odds <= (item.ex.availableToBack[0].price + $scope.$parent.oddsLimit).toFixed(2)) {
                                                $http.get('Betentrycntr/updateUnMatchedData/' + $scope.UserData[i].MstCode + '/' + 0 + '/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {

                                                    $scope.UserData = data.betUserData;//sourabh 170109
                                                    $scope.getBetsData();//to show in aggrid

                                                });
                                            }
                                        }
                                        else {
                                            if (item.ex.availableToLay.length != 0 && $scope.UserData[i].Odds >= (item.ex.availableToLay[0].price + $scope.$parent.oddsLimit).toFixed(2))//sourabh 170120 new
                                            {
                                                $http.get('Betentrycntr/updateUnMatchedData/' + $scope.UserData[i].MstCode + '/' + 1 + '/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {

                                                    $scope.UserData = data.betUserData;//sourabh 170109
                                                    $scope.getBetsData();//to show in aggrid
                                                });
                                            }
                                        }
                                    }
                                });
                            }
                        }
                    } catch (e) { /*Dialog.autohide('Unmatched to Match Have issue occurs..'+e.error());*/
                    }
                });
                /* End Of Get Matched and Unmatched Data Check From API*/
            }

            var columnDefs = [
                // this row just shows the row index, doesn't use any data from the row
                {
                    headerName: "#", cellRenderer: function (params) {
                        return ++params.node.id;
                    }, cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Runner", field: "selectionName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Odds", field: "Odds", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Stake", field: "Stack", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "P&L", field: "P_L", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: 'Time', field: "MstDate", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
            ];
            var columnDefsUn = [
                // this row just shows the row index, doesn't use any data from the row
                {
                    headerName: "#", cellRenderer: function (params) {
                        return ++params.node.id;
                    }, cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Runner", field: "selectionName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Odds", field: "Odds", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "Stake", field: "Stack", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: "P&L", field: "P_L", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: 'Time', field: "MstDate", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                },
                {
                    headerName: 'Action', cellRenderer: ageCellRendererFunc, cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                }
            ];
            if (sessionService.get('type') == 0) {
                columnDefs.splice(1, 0, {
                    headerName: "Client", field: "userName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefs.splice(1, 0, {
                    headerName: "Dealer", field: "ParantName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefs.splice(1, 0, {
                    headerName: "Master", field: "MasterName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefsUn.splice(1, 0, {
                    headerName: "Client", field: "userName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefsUn.splice(1, 0, {
                    headerName: "Dealer", field: "ParantName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefsUn.splice(1, 0, {
                    headerName: "Master", field: "MasterName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
            }
            else if (sessionService.get('type') == 1) {
                columnDefs.splice(1, 0, {
                    headerName: "Dealer", field: "ParantName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefs.splice(1, 0, {
                    headerName: "Master", field: "MasterName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefsUn.splice(1, 0, {
                    headerName: "Dealer", field: "ParantName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefsUn.splice(1, 0, {
                    headerName: "Master", field: "MasterName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
            }
            else if (sessionService.get('type') == 2) {
                columnDefs.splice(1, 0, {
                    headerName: "Client", field: "userName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
                columnDefsUn.splice(1, 0, {
                    headerName: "Client", field: "userName", cellClass: function (params) {
                        return (params.data.isBack == 1 ? 'lay-head' : 'back-head');
                    }
                });
            }
            function ageCellRendererFunc(params) {
                var eSpan = document.createElement('button');
                //console.log(params);
                eSpan.innerHTML = 'Delete';
                eSpan.addEventListener('click', function () {
                    raiseevent(params);
                });
                return eSpan;
            }

            $scope.deleteUser = function (betId, userId) {

                var result = confirm("Are you sure want to delete Records");
                if (result) {

                    /*start the Delete The Record*/
                    $http.get('Betentrycntr/deleteGetbetting/' + betId + '/' + userId).success(function (data, status, headers, config) {

                        Dialog.autohide(data.message);
                        $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                            $scope.UserData = data.betUserData;//sourabh 170109
                            $scope.getBetsData();
                        });

                    });
                    /*End of Delete The Record*/
                }
            }
            function raiseevent(params) {
                var params;

                $scope.deleteUser(params.data.MstCode, params.data.UserId);

                //alert('code worked');
            }


            $scope.CurrentAllBets = {
                enableSorting: true,
                enableFilter: true,
                debug: true,
                rowSelection: 'multiple',
                enableColResize: true,
                paginationPageSize: 100,
                columnDefs: columnDefs,
                rowModelType: 'pagination',
            };
            $scope.CurrentAllBetsUn = {
                enableSorting: true,
                enableFilter: true,
                debug: true,
                rowSelection: 'multiple',
                enableColResize: true,
                paginationPageSize: 100,
                columnDefs: columnDefsUn,
                rowModelType: 'pagination',
            };
            var allOfTheData;

            function createNewDatasource(type) {
                if (!allOfTheData) {
                    return;
                }
                var dataSource = {
                    getRows: function (params) {
                        //console.log('asking for ' + params.startRow + ' to ' + params.endRow);
                        setTimeout(function () {
                            var rowsThisPage = allOfTheData.slice(params.startRow, params.endRow);
                            var lastRow = -1;
                            if (allOfTheData.length <= params.endRow) {
                                lastRow = allOfTheData.length;
                            }
                            params.successCallback(rowsThisPage, lastRow);
                        }, 500);
                    }
                };
                if (type == 'Un')
                    $scope.CurrentAllBetsUn.api.setDatasource(dataSource);
                else
                    $scope.CurrentAllBets.api.setDatasource(dataSource);
            }

            function setRowData(rowData, type) {
                allOfTheData = rowData;
                createNewDatasource(type);
            }

            $scope.getBetsData = function () {

                $scope.MatchedBets = [];
                $scope.UnmatchedBets = [];
                angular.forEach($scope.UserData, function (value, index) {
                    if (value.IsMatched == "1")
                        $scope.MatchedBets.push(value);
                    else
                        $scope.UnmatchedBets.push(value);
                });
                $scope.callBetsData($scope.betMaUn);//sourabh 170110
            }
            $scope.callBetsData = function (isMatched) {

                $scope.betMaUn = isMatched;//sourabh 170110
                if (isMatched == "1") {
                    if ($scope.MatchedBets.length > 0)
                        $scope.showBetsData = true;
                    else
                        $scope.showBetsData = false;
                    $scope.CurrentAllBets.api.sizeColumnsToFit();
                    setRowData($scope.MatchedBets, 'Ma');

                }
                else {
                    if ($scope.UnmatchedBets.length > 0)
                        $scope.showBetsData = true;
                    else
                        $scope.showBetsData = false;
                    $scope.CurrentAllBetsUn.api.sizeColumnsToFit();
                    setRowData($scope.UnmatchedBets, 'Un');

                }
            }
            $scope.getBetsData1 = function () {
                $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                    $scope.UserData = data.betUserData;
                    $scope.getBetsData();
                });
            }
            $scope.getBetsData1();
            $scope.si_getMatchUnmatchData = setInterval($scope.getMatchUnmatchData, 1000);
            $scope.$on("$destroy", function (event) {
                clearInterval($scope.si_getMatchUnmatchData);
                clearInterval(si_fancyData);
            });//sourabh 170124
            $scope.showSessionFancy = function (fanctTypeId, fanctId) {
                //sourabh 170125

                if ($scope.sessionFancy == fanctId)
                    $scope.sessionFancy = 0;
                else {

                    $scope.sessionFancy = fanctId;
                    $scope.sessionFancyType = fanctTypeId;
                    get_userser.GetFancyData($stateParams.MatchId, $scope.sessionFancy, sessionService.get('user_id'), sessionService.get('type'), $scope.sessionFancyType, function (response) {//sourabh 170125_1
                        $scope.FancyData = response.data.fancyForm;
                        $scope.showOdd1 = false;
                        $scope.GetFancyBal();
                    });
                }
            }
            $scope.checkValidation = function (sessionData) {//sourabh 170125
                if (sessionData.betValue == "" || sessionData.betValue <= 0) {
                    Dialog.autohide('You cannot play at zero Stake...');
                    focus('betValue');
                    return false;
                }
                return true;
            }
            $scope.display_Yesfancy = function (sessionValue) {//sourabh 170125
                // alert("Enter");
                $scope.isBackYes = 1;
                $scope.showOdd1 = true;
                $scope.betValue = 0;
                $scope.sessionValue = sessionValue;
                $scope.userType = sessionStorage.type;
                $scope.UserTypeId = sessionService.get('slctUseTypeID');
                focus('betValueLay');
            }
            $scope.display_Nofancy = function (sessionValue) {//sourabh 170125
                $scope.isBackYes = 0;
                $scope.showOdd1 = true;
                $scope.betValue = 0;
                $scope.sessionValue = sessionValue;
                $scope.userType = sessionStorage.type;
                $scope.UserTypeId = sessionService.get('slctUseTypeID');
                focus('betValueLay');
            }
            $scope.GetFancyBal = function () {//sourabh 170125
                get_userser.GetFancyBal($stateParams.FancyID, function (response1) {

                    if (response1 == null) {
                        $scope.TotalBet = 0;
                    } else {
                        $scope.TotalBet = response1;
                    }
                });
            }
            $scope.GetBetValueReset = function (Value1, hideOdd) {
                $scope.betValue = parseInt(Value1);
                if (hideOdd) $scope.showOdd1 = !hideOdd;
            }
            $scope.GetBetValue = function (Value1) {
                $scope.betValue = parseInt($scope.betValue) + parseInt(Value1);
            }
            $scope.saveSessionBet = function (pointDiff) {//sourabh 170125

                var HeadName = $scope.FancyData[0].HeadName;
                var SessInptNo = $scope.FancyData[0].SessInptNo;
                var SessInptYes = $scope.FancyData[0].SessInptYes;
                var FncyId = $scope.FancyData[0].FncyId;
                var sportId = $scope.$parent.sportId;
                var UserTypeId = sessionService.get('slctUseTypeID');
                var UserId = sessionService.get('slctUseID');
                var loginId = sessionStorage.user_id;
                var ParantId = sessionService.get('slctParantID');
                //
                var amount = document.getElementById('betValueLay').value;
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
                    FancyID: $scope.sessionFancy,
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
                    deviceInformation: deviceInformation
                }
                $scope.GetFancyBal();
                var GetSumVal = parseInt($scope.TotalBet) + parseInt(amount);
                var chkuBaL = parseInt($rootScope.Balance) + parseInt($rootScope.Liability);
                var MaxStake = parseInt($scope.FancyData[0].MaxStake) * parseInt($scope.FancyData[0].RateChange);
                if ((chkuBaL >= amount)) {
                    if ((GetSumVal <= MaxStake)) {
                        if ($scope.checkValidation(sessionData)) {
                            $http({
                                method: 'POST',
                                url: BASE_URL + 'Lstsavemstrcontroller/saveUserFancy',
                                data: sessionData,
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                            }).success(function (data) {
                                //$scope.UserBetData = data.UserBetData;
                                //$scope.scorePosition = data.scorePosition;
                                //gridOptions.api.sizeColumnsToFit();
                                //setRowData(data.UserBetData);
                                $scope.showOdd1 = false;
                                get_userser.GetWALLibiInfo(sessionService.get('slctUseID'));
                                Dialog.autohide('Place Bet Successfully...');    //sourabh 170104 change
                                $scope.getBetsData1();
                            });
                        }
                    } else {
                        Dialog.autohide('Rate Change...');
                    }
                } else {
                    Dialog.autohide('Insufficient Balance...');
                }
            };
            var si_fancyData = $interval(callAtTimeout, 500);//sourabh 170125_1{
            function callAtTimeout() {
                if ($scope.sessionFancyType != angular.isUndefinedOrNull) {
                    get_userser.GetFancyData($stateParams.MatchId, $scope.sessionFancy, sessionService.get('user_id'), sessionService.get('type'), $scope.sessionFancyType, function (response) {
                        if (($scope.FancyData[0].SessInptYes == response.data.fancyForm[0].SessInptYes) && ($scope.FancyData[0].SessInptNo == response.data.fancyForm[0].SessInptNo) && ($scope.FancyData[0].active == response.data.fancyForm[0].active) && ($scope.FancyData[0].pointDiff = response.data.fancyForm[0].pointDiff) && ($scope.FancyData[0].DisplayMsg == response.data.fancyForm[0].DisplayMsg)) {
                            //if ($scope.UserBetData.length == response.data.UserBetData.length) {
                            //} else {
                            //    $scope.UserBetData = response.data.UserBetData;
                            //    $scope.scorePosition = response.data.scorePosition;
                            //    gridOptions.api.sizeColumnsToFit();
                            //    setRowData(response.data.UserBetData);
                            //}
                        } else {
                            $scope.FancyData[0].SessInptYes = response.data.fancyForm[0].SessInptYes;
                            $scope.FancyData[0].SessInptNo = response.data.fancyForm[0].SessInptNo;
                            $scope.FancyData[0].active = response.data.fancyForm[0].active;
                            $scope.FancyData[0].FncyId = response.data.fancyForm[0].FncyId;
                            $scope.FancyData[0].pointDiff = response.data.fancyForm[0].pointDiff;
                            $scope.FancyData[0].MaxStake = response.data.fancyForm[0].MaxStake;
                            $scope.FancyData[0].NoValume = response.data.fancyForm[0].NoValume;
                            $scope.FancyData[0].YesValume = response.data.fancyForm[0].YesValume;
                            $scope.FancyData[0].DisplayMsg = response.data.fancyForm[0].DisplayMsg;
                            $scope.FancyData[0].RateChange = response.data.fancyForm[0].RateChange;
                            if (response.data.fancyForm[0].active == 1) {
                                if (response.data.fancyForm[0].YesValume != "100") {
                                    speech.sayText("90 11");
                                }
                                else {
                                    speech.sayText(response.data.fancyForm[0].SessInptNo);
                                    speech.sayText(response.data.fancyForm[0].SessInptYes);
                                }
                            }
                            else if (response.data.fancyForm[0].active == 4 || response.data.fancyForm[0].active == 2) {
                                speech.sayText(response.data.fancyForm[0].DisplayMsg);
                            }
                            else if (response.data.fancyForm[0].active == 0) {
                                var msg = "Ball started..."
                                speech.sayText(msg);
                            }
                            $scope.GetFancyBal();
                            //if ($scope.UserBetData.length == response.data.UserBetData.length) {
                            //
                            //} else {
                            //    $scope.UserBetData = response.data.UserBetData;
                            //    $scope.scorePosition = response.data.scorePosition;
                            //    gridOptions.api.sizeColumnsToFit();
                            //    setRowData(response.data.UserBetData);
                            //}
                        }
                    });
                }
            }
        }
    }
});