app.controller('Sessionfancycntr', function ($scope, $http, $rootScope, $stateParams, sessionService, $interval, Dialog, get_userser, deviceDetector, speech, focus,SessionFancy) {
    
    get_userser.userChipSetting(function (response) {
        $rootScope.userPlcBtn = response;
        $rootScope.MyLenth = response.length;
    });
    $scope.GetBetValue = function (betValue, Value1) {
        $scope.betValue = parseInt(betValue) + parseInt(Value1);
        $scope.GetFancyBal();
    }
    shortcut.add("Enter", function (event) {
        $scope.saveSessionBet($scope.FancyData[0].pointDiff);
    });
    $scope.allData = deviceDetector;
    $scope.matchId = $stateParams.matchId;
    $scope.FancyID = $stateParams.FancyID;
    $scope.TypeID = $stateParams.TypeID;
    $scope.userType = sessionStorage.type;
    $scope.MatchName = $stateParams.matchName;
    $scope.sportId = $stateParams.sportId;
    $scope.display_Yesfancy = function (sessionValue) {
        if (sessionService.get('slctUseTypeID') == "3") {
            $scope.isBackYes = 1;
            $scope.showOdd1 = true;
            $scope.betValue = 0;
            $scope.sessionValue = parseInt(sessionValue);;
            $scope.userType = sessionStorage.type;
            $scope.UserTypeId = sessionService.get('slctUseTypeID');
            focus('betValueLay');
        }
        else
            Dialog.autohide('Please Select Valid User');
    }
    $scope.display_Nofancy = function (sessionValue) {
        if (sessionService.get('slctUseTypeID') == "3") {
            $scope.isBackYes = 0;
            $scope.showOdd1 = true;
            $scope.betValue = 0;
            $scope.sessionValue = parseInt(sessionValue);
            $scope.userType = sessionStorage.type;
            $scope.UserTypeId = sessionService.get('slctUseTypeID');
            focus('betValueLay');
        }
        else
            Dialog.autohide('Please Select Valid User');
    }
    $scope.checkValidation = function (sessionData) {
        if (sessionData.betValue == "" || sessionData.betValue <= 0) {
            Dialog.autohide('You cannot play at zero Stake...');
            $('#betValue').focus();
            return false;
        }
        return true;
    }
    $scope.saveSessionBet = function (pointDiff) {
        
        var HeadName = $scope.FancyData[0].HeadName;
        var SessInptNo = $scope.FancyData[0].SessInptNo;
        var SessInptYes = $scope.FancyData[0].SessInptYes;
        var FncyId = $scope.FancyData[0].FncyId;
        var sportId = $stateParams.sportId;
        var UserTypeId = sessionService.get('slctUseTypeID');
        var UserId = sessionService.get('slctUseID');
        var loginId = sessionStorage.user_id;
        var ParantId = sessionService.get('slctParantID');
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
            userId: UserId,ParantId: ParantId,loginId: loginId,betValue: amount,FancyID: $scope.FancyID,matchId: $scope.matchId,OddValue: $scope.isBackYes,type: sessionStorage.type,OddsNumber: OddsNumber,TypeID: $scope.TypeID,HeadName: HeadName,SessInptNo: SessInptNo,SessInptYes: SessInptYes,sportId: sportId,FancyId: FncyId,pointDiff: pointDiff,deviceInformation: deviceInformation
        }
       /* 
        if($scope.GetFancyBal());*/
        get_userser.GetFancyBal($stateParams.FancyID, function (response1) { 
           
            if (response1 == null) {
             $scope.TotalBet = 0;
              } 
              else {
               $scope.TotalBet = response1; 
           }


                
        
        var GetSumVal=0;
        GetSumVal = parseInt($scope.TotalBet) + parseInt(amount);
        var chkuBaL = 0;
        //if(parseInt($rootScope.Liability)>0)
            chkuBaL=parseInt($rootScope.Balance);// + parseInt($rootScope.Liability);
        //else
            //chkuBaL=parseInt($rootScope.Balance);        
        //var MaxStake = parseInt($scope.FancyData[0].MaxStake) * parseInt($scope.FancyData[0].RateChange);
        var MaxStake = parseInt($scope.FancyData[0].MaxStake);
        var $promise = $http.post(BASE_URL + 'Lstsavemstrcontroller/getUserInfo/' + sessionService.get('slctUseID'));
        $promise.then(function (response) {
            //new code for check Balance 04-apr-2017 by ajay sir
            /*$http.get( BASE_URL+'Sessioncntr/GetSumOfBet/'+UserId+'/'+$stateParams.matchId+'/'+$stateParams.FancyID+'/'+$stateParams.TypeID+'/'+SessInptYes+'/'+SessInptNo).success(function (data, status, headers, config) {
            
            console.log(data.betSum);
           
        }).error(function (data, status, header, config) {
            $scope.ResponseDetails = "Data: " + data +
                "<br />status: " + status +
                "<br />headers: " + jsonFilter(header) +
                "<br />config: " + jsonFilter(config);
        });*/
            //end of new condition

            var userData = response.data.userInfo;
            var mstrlock = parseInt(response.data.userInfo[0].mstrlock);
            var lgnusrlckbtng = parseInt(response.data.userInfo[0].lgnusrlckbtng);
            var lgnusrCloseAc = parseInt(response.data.userInfo[0].lgnusrCloseAc);
            var stakeLimit = parseInt(response.data.userInfo[0].stakeLimit);
            var active = parseInt(response.data.userInfo[0].active);
            var usetype = parseInt(response.data.userInfo[0].usetype);
            if (mstrlock == 1 && lgnusrlckbtng == 1 && lgnusrCloseAc == 1 && active == 1 && usetype == 3 && (stakeLimit == 0 || stakeLimit >= amount)) {
                
                if ((chkuBaL >= amount)) {
                    if ((parseInt($scope.TotalBet) <= MaxStake-500 && GetSumVal <= MaxStake+500)) {
                        if ($scope.checkValidation(sessionData)) {
                            $http({
                                method: 'POST',
                                url: BASE_URL + 'Lstsavemstrcontroller/saveUserFancy',
                                data: sessionData,
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                            }).success(function (data) {
                                $scope.UserBetData = data.UserBetData;
                                $scope.scorePosition = data.scorePosition;
                                //gridOptions.api.sizeColumnsToFit();
                                setRowData($scope.UserBetData);
                                speech.sayText("b");
                                $scope.showOdd1 = false;
                                get_userser.GetWALLibiInfo(sessionService.get('slctUseID'));
                                Dialog.autohide(data.message);
                            });
                        }
                    }
                    else { 
                            //$scope.tmpClose=true;

                            Dialog.autohide('Rate Change...');
                    }
                }
                else { Dialog.autohide('Insufficient Balance...'); }
            }
            else if (mstrlock == 0) { Dialog.autohide('user Lock', '3000'); }
            else if (lgnusrlckbtng == 0) { Dialog.autohide('user batting is Lock ', '3000'); }
            else if (lgnusrCloseAc == 0) { Dialog.autohide('user account closed', '3000'); }
            else if (active == 0) { Dialog.autohide('user Inactive', '3000'); }
            else if (usetype != 3) { Dialog.autohide('Please select Valid user', '3000'); }
            else if (stakeLimit != 0 || stakeLimit <= amount) { Dialog.autohide('Invalid Stake Limit', '3000'); }
        });
});
    };
    if (sessionStorage.type == 0) {
        var columnDefs = [
              { headerName: "SNo", width: 40, field: "SrNo", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              
              { headerName: "UserName", width: 110, field: "userName", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "Master", width: 110, field: "MasterName", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "amount", width: 90, field: "bet_value", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "Score", width: 90, field: "OddsNumber", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "No/Yes", width: 80, valueGetter: GetOddsName, cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "Time", width: 140, field: "dateTime", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
              { headerName: "id", width: 60, field: "bet_id", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
        ];
    } else if (sessionStorage.type == 1) {
        var columnDefs = [
                { headerName: "SNo", width: 40, field: "SrNo", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                
                { headerName: "UserName", width: 110, field: "userName", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "Dealer", width: 110, field: "ParantName", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "amount", width: 90, field: "bet_value", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "Score", width: 90, field: "OddsNumber", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "No/Yes", width: 80, valueGetter: GetOddsName, cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "Time", width: 140, field: "dateTime", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "id", width: 60, field: "bet_id", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
        ];
    } else if (sessionStorage.type == 2) {
        var columnDefs = [
            { headerName: "SNO", width: 40, field: "SrNo", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
            
            { headerName: "UserName", width: 110, field: "userName", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
            { headerName: "amount", width: 90, field: "bet_value", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
            { headerName: "Score", width: 90, field: "OddsNumber", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
            { headerName: "No/Yes", width: 80, valueGetter: GetOddsName, cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
            { headerName: "Time", width: 140, field: "dateTime", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
            { headerName: "id", width: 60, field: "bet_id", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
        ];
    } else if (sessionStorage.type == 3) {
        var columnDefs = [
                { headerName: "SNo", width: 40, field: "SrNo", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                
                { headerName: "amount", width: 90, field: "bet_value", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "Score", width: 90, field: "OddsNumber", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "No/Yes", width: 80, valueGetter: GetOddsName, cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "Time", width: 140, field: "dateTime", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
                { headerName: "id", width: 60, field: "bet_id", cellClass: function (params) { return (params.data.OddValue == 1 ? 'lay-head' : 'back-head'); } },
        ];
    }
    function ageCellRendererFunc(params) {
        var eSpan = document.createElement('button');
        console.log(params);
        eSpan.innerHTML = 'Delete';
        eSpan.addEventListener('click', function () {
            raiseevent(params);
        });
        return eSpan;
    }
    function raiseevent(params) {
        var params;
        alert('code worked' + params.data.bet_id);
    }
    function GetOddsName(params) {
        if (params.data.OddValue == 0) {
            return "Back[Yes]";
        } else {
            return "Lay[NO]";
        }
    }
    var gridOptions = {
        enableSorting: true,
        enableFilter: true,
        debug: true,
        rowSelection: 'multiple',
        enableColResize: true,
        paginationPageSize: 500,
        columnDefs: columnDefs,
        rowModelType: 'pagination',
        onGridReady: function () {
            //gridOptions.api.sizeColumnsToFit()
        }
    };
    function onPageSizeChanged(newPageSize) {
        this.gridOptions.paginationPageSize = new Number(newPageSize);
        createNewDatasource();
    }

    var allOfTheData;

    function createNewDatasource() {
        if (!allOfTheData) {
            return;
        }
        var dataSource = {
            getRows: function (params) {
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
        gridOptions.api.setDatasource(dataSource);
    }
    function setRowData(rowData) {
        allOfTheData = rowData;
        createNewDatasource();
    }
    var gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    $scope.GetFancyData = function () {
        get_userser.GetFancyData($stateParams.matchId, $stateParams.FancyID, sessionService.get('user_id'), sessionService.get('type'), $stateParams.TypeID, function (response) {
            
            $scope.FancyData = response.data.fancyForm;
            $scope.scorePosition = response.data.scorePosition;
            $scope.showOdd1 = false;

           /* if (sessionService.get('type') != "3") {*/
                $scope.UserBetData = response.data.UserBetData;
                //gridOptions.api.sizeColumnsToFit();
                setRowData(response.data.UserBetData);
            /*}*/
            $scope.GetFancyBal();
        });
    }
     $scope.RefreshData = function () {
        //alert("set");
        get_userser.GetFancyData($stateParams.matchId, $stateParams.FancyID, sessionService.get('user_id'), sessionService.get('type'), $stateParams.TypeID, function (response) {
            $scope.scorePosition = response.data.scorePosition;            
        });
    }
    $scope.GetFancyData();
    $scope.GetFancyBal = function () { get_userser.GetFancyBal($stateParams.FancyID, function (response1) { if (response1 == null) { $scope.TotalBet = 0; } else { $scope.TotalBet = response1; } }); }
    var promise;
    promise = $interval(callAtTimeout, 5000);
    $scope.$on("$destroy", function(event) {
        
        $interval.cancel(promise);
        
        //callAtTimeout = angular.isUndefinedOrNull;
    });

    function callAtTimeout() {
        
        SessionFancy.GetSessionFancyData($stateParams.matchId, $stateParams.FancyID, sessionService.get('user_id'), sessionService.get('type'), $stateParams.TypeID, function (response) {
            if (($scope.FancyData[0].SessInptYes == response.data.fancyForm[0].SessInptYes) && ($scope.FancyData[0].SessInptNo == response.data.fancyForm[0].SessInptNo) && ($scope.FancyData[0].active == response.data.fancyForm[0].active) && ($scope.FancyData[0].pointDiff = response.data.fancyForm[0].pointDiff) && ($scope.FancyData[0].DisplayMsg == response.data.fancyForm[0].DisplayMsg)) {
                if (sessionService.get('type') != "3" && $scope.UserBetData.length != response.data.UserBetData.length) {
                    $scope.UserBetData = response.data.UserBetData;
                    $scope.scorePosition = response.data.scorePosition;
                    //gridOptions.api.sizeColumnsToFit();
                    //speech.sayText("b");
                    setRowData(response.data.UserBetData);
                }
            }
            else {
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
                        //speech.sayText(response.data.fancyForm[0].SessInptNo);
                        //speech.sayText(response.data.fancyForm[0].SessInptYes);
                    }
                }
                else if (response.data.fancyForm[0].active == 4 || response.data.fancyForm[0].active == 2) {
                    speech.sayText(response.data.fancyForm[0].DisplayMsg);
                }
                else if (response.data.fancyForm[0].active == 0) {
                    var msg = "Ball started..."
                    //speech.sayText(msg);
                }
                //$scope.GetFancyBal();
                if (sessionService.get('type') != "3" && $scope.UserBetData.length == response.data.UserBetData.length) {
                    $scope.UserBetData = response.data.UserBetData;
                    $scope.scorePosition = response.data.scorePosition;
                    //gridOptions.api.sizeColumnsToFit();
                    setRowData(response.data.UserBetData);
                }
            }
        });
    }
});