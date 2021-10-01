app.controller('Matchoddscntr', ['$scope', '$http', '$stateParams', 'sessionService', '$timeout', 'Dialog', '$rootScope', 'deviceDetector', 'get_userser', '$mdDialog', 'speech', '$filter', '$location', '$state', '$interval','Base64', function($scope, $http, $stateParams, sessionService, $timeout, Dialog, $rootScope, deviceDetector, get_userser, $mdDialog, speech, $filter, $location, $state, $interval,Base64) {
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
    $scope.FinalArray = [];
    var MarketId = $stateParams.MarketId;
    $scope.PLAYPAUSE=0;
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
    var matchStatus = "OPEN";
    $scope.GetUserData=function(){
        $http.get( BASE_URL+'Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
            $scope.UserData = data.betUserData;
           // 
        });
    }

    $scope.FancyBet = $interval($scope.GetUserData, 3000);
    $scope.$on("$destroy", function(event) {
        $interval.cancel($scope.FancyBet);
        $scope.FancyBet = angular.isUndefinedOrNull;
    });
    
    $scope.styleColor=function(value){
        if(value < 0){ return "RED"; }else{ return "GREEN";  }
    }   
    if($scope.gtTypeId !=3){       
        var scorePosition = $interval(function () {        
        if($stateParams.MatchId == sessionService.get('MatchId') && $scope.gtTypeId !=3){
            var $promise = $http.get(BASE_URL + 'Sessioncntr/FancyScorePosition/'  + sessionService.get('fancyId') + '/' + sessionService.get('slctUseID') + '/' + sessionService.get('FancyType'));
            $promise.then(function (response) {               
                $scope.scorePosition=response.data.scorePosition;
            });
        } 
        }, 2000);
    }
    $scope.deleteUser = function (betId, userId) {
                var result = confirm("Are you sure want to delete Unmatched Records");
                if (result) {
                    $http.get( BASE_URL+'Betentrycntr/deleteGetbetting/' + betId + '/' + userId).success(function (data, status, headers, config) {
                        
                        Dialog.autohide("Record Deleted Successfully...");
                    });

                }

            }
    $scope.deleteUser1 = function (betId, userId,MarketId) {
        var result = confirm("Are you sure want to delete Matched Records?");
        if (result) {
            $http.get( BASE_URL+'Betentrycntr/deleteGetbettingmat/' + betId + '/' + userId+'/'+MarketId).success(function (data, status, headers, config) {
                
                 Dialog.autohide("Record Deleted Successfully...");
               
            });

        }

    }
    $scope.sum = function (items, prop) {
            return items.reduce(function (a, b) {
                var t = parseFloat(a) + parseFloat(b[prop]);
                return parseFloat(a) + parseFloat(b[prop]);
            }, 0);
    };
    
    $scope.GetUserData();
    /*end of new function*/
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
        $http({ method: 'POST', url: BASE_URL+'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
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
        
        $http.get( BASE_URL+'Geteventcntr/getBackLaysOfMarketSelectionName/' + $scope.MarketId + '/' + user_id + '/' + user_type + '/' + $scope.MatchId).success(function(data, status, headers, config)
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
        if(a==angular.isUndefinedOrNull && b==angular.isUndefinedOrNull){ a=0;b=0; }
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
        var $promise = $http.get( BASE_URL+'Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + $stateParams.MatchId);
        $promise.then(function(response) {
            //For Play Pause start
            if (sessionService.get('type') == "0") {
                $http({
                    method: 'POST',
                    url: BASE_URL+'Geteventcntr/matchMarketLst/',
                    data: {
                        matchId: $stateParams.MatchId,
                        sportsId: 4,
                        user_id: sessionService.get('user_id')
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    try {
                        $scope.FancyLength=data.getMatchFancy.length;
                        if($scope.FancyLength > 0 && $scope.FancyData != angular.isUndefinedOrNull && $scope.FancyData.length>0){
                            if($scope.FancyData.length == data.getMatchFancy.length){
                                for (var i = 0; i < data.getMatchFancy.length; i++) {
                                    if($scope.FancyData[i].SessInptYes==data.getMatchFancy[i].SessInptYes && $scope.FancyData[i].SessInptNo==data.getMatchFancy[i].SessInptNo && $scope.FancyData[i].active==data.getMatchFancy[i].active && $scope.FancyData[i].DisplayMsg==data.getMatchFancy[i].DisplayMsg){ 

                                    }else{
                                         $scope.FancyData=data.getMatchFancy;
                                    }
                                } 
                            }else{
                               $scope.FancyData=data.getMatchFancy; 
                            }
                            
                        }else{
                            $scope.FancyData=data.getMatchFancy;
                        }
                        if ($filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay == "1") {                            
                            $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;
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
                                    } catch (e) {g
                                        if ($scope.GetMarketBackLayData.rgunners[j].ex.availableToBack[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.rgunners[j].ex.availableToBack[2].price = "";
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
                                    }g
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
    $scope.callOddsCloseMatch = function() { 
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
                $http.get( BASE_URL+'Geteventcntr/getSelectionName/' + $scope.MarketId + '/' + $scope.MatchId).success(function(data, status, headers, config) {
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
    $scope.stakeValReset = function(val) {
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
    $scope.stakeVal = function(val, selectionId, stake) { 

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
    $scope.getValColor = function(val) {
        if (val == angular.isUndefinedOrNull || val == 0)
            return 'color:#000000';
        else if (val > 0)
            return 'color:#1ed61e';
        else
            return 'color:#ff0000';
    }
    $scope.GetMarketListId = function()
	{
		
	   $scope.loading = true;
	   $scope.IsShowPage=true;
	 //  socket.emit('CallGetMarketListId', {auth:Bauthdata,MatchId:$scope.MatchId,UserId:$scope.UserId});
		 $http({
                    method: 'GET',
                    url: BASE_URL+'Apicontroller/getMarketListing/'+$scope.MatchId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    // 
                     $scope.oddsLimit=parseFloat(data.data.volumeLimit[0].oddsLimit);
                     $scope.volumeLimit=parseFloat(data.data.volumeLimit[0].volumeLimit);
                     if(data.error)
                     {
                            
                            Dialog.autohide(data.message);
                            $scope.loading = false;
                            $scope.IsShowPage=false;
                            $state.go("dashboard.Home");
                     }
                     else
                     {
                        $scope.IsShowPage=true;
                        $scope.BindMarket(data);
                     }
                     
			}).error(function(err){
			 $scope.loading = false;
        });	
        
    }
    $scope.BindMarket=function(result)
{

	 if(result.data != angular.isUndefinedOrNull)
		{
		$scope.marketIdLst = result.data.marketid;
		//$scope.MarketWinLoss(result.data.marketid);
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
}
    $scope.getFancyList = function(marketId) {
        // stopped = $timeout(function() {
              $http({
                            method: 'GET',
                            url: BASE_URL+'Apicontroller/matchLstIndianSession/'+$scope.MatchId+'/'+marketId,
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        }).success(function(result) {
                //	
                
                     $scope.loading=false;
                 $scope.FancyData = result.data;
                 $scope.getLiveFancyList();
                    }).error(function(err){
                     $scope.loading = false;
                });	
         //$scope.getFancyList(marketId);
        //},10000);
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
                                  /* try{
                                   $scope.FinalArray[0].runners[inde].back[0].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[0].price,$scope.FinalArray[0].runners[inde].back[0].size, market[m].BackPrice1,market[m].BackSize1);                 
                                   $scope.FinalArray[0].runners[inde].back[0].price = market[m].BackPrice1;
                                   $scope.FinalArray[0].runners[inde].back[1].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[1].price,$scope.FinalArray[0].runners[inde].back[1].size, market[m].BackPrice2,market[m].BackSize2);  
                                   $scope.FinalArray[0].runners[inde].back[1].price = market[m].BackPrice2;
                                   $scope.FinalArray[0].runners[inde].back[2].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[2].price,$scope.FinalArray[0].runners[inde].back[2].size, market[m].BackPrice3,market[m].BackSize3); 
                                   $scope.FinalArray[0].runners[inde].back[2].price = market[m].BackPrice3;
                                   $scope.FinalArray[0].runners[inde].lay[0].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[0].price,$scope.FinalArray[0].runners[inde].lay[0].size, market[m].LayPrice1,market[m].LaySize1); 
                                   $scope.FinalArray[0].runners[inde].lay[0].price = market[m].LayPrice1;
                                   $scope.FinalArray[0].runners[inde].lay[1].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[1].price,$scope.FinalArray[0].runners[inde].lay[1].size, market[m].LayPrice2,market[m].LaySize2); 
                                   $scope.FinalArray[0].runners[inde].lay[1].price = market[m].LayPrice2;
                                   $scope.FinalArray[0].runners[inde].lay[2].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[2].price,$scope.FinalArray[0].runners[inde].lay[2].size, market[m].LayPrice3,market[m].LaySize3); 
                                   $scope.FinalArray[0].runners[inde].lay[2].price = market[m].LayPrice3;
               
                                   $scope.FinalArray[0].runners[inde].back[0].size = market[m].BackSize1;
                                   $scope.FinalArray[0].runners[inde].back[1].size = market[m].BackSize2;
                                   $scope.FinalArray[0].runners[inde].back[2].size = market[m].BackSize3;
                                   $scope.FinalArray[0].runners[inde].lay[0].size = market[m].LaySize1;
                                   $scope.FinalArray[0].runners[inde].lay[1].size = market[m].LaySize2;
                                   $scope.FinalArray[0].runners[inde].lay[2].size = market[m].LaySize3;
                               }
                               catch(e)
                               {
                                       if($scope.FinalArray[0].runners[inde].back[0]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].back[0].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].back[1]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].back[1].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].back[2]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].back[2].price="";
                                       }
               
                                       if($scope.FinalArray[0].runners[inde].lay[0]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].lay[0].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].lay[1]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].lay[1].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].lay[2]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].lay[2].price="";
                                       }
                               }*/
               
                               }
                            }
                        }
                           /*for(var i=0;i<$scope.FancyLiveData.length;i++)
                                   {
                                       if($scope.FancyLiveData!=null)
                                       {
                                           var inde = $scope.FancyData.findIndex(img => img.ind_fancy_selection_id === 											$scope.FancyLiveData[i].SelectionId);
                                           if(inde > -1)
                                           {
                                           var obj = $scope.FancyLiveData[i];
                                           $scope.FancyData[inde].SessInptNo=obj.LayPrice1;
                                           $scope.FancyData[inde].SessInptYes=obj.BackPrice1;
                                           $scope.FancyData[inde].DisplayMsg=obj.GameStatus;
                                             $scope.FancyData[inde].active=obj.GameStatus ==" " ? 1 : 4;
               
                                           }
               
                                       
                                       }
                                   }*/
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
                   $scope.getNameFunc();
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
    $scope.getOddCalcVal = function(a, ovType) {
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
  //  $scope.callOddsFunc();
    $scope.GetMarketListId();
    $scope.$on("$destroy", function(event) {
        $timeout.cancel(marketTimer);
        marketTimer = angular.isUndefinedOrNull;
    });
}]);
app.directive('crntusrpsn', function() { 
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

