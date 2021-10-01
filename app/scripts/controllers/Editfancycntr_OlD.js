app.controller('Editfancycntr', function ($scope, $rootScope, $http, $stateParams, Dialog, $mdDialog, $location,get_userser,$interval) {
    if ($rootScope.HelperAllRights != angular.isUndefinedOrNull && $rootScope.HelperAllRights.EditFancy == 0) { $location.path('/dashboard/Home'); }
    $scope.MatchName = $stateParams.MatchName;
    $scope.rangeLimit = 10;
    $scope.setMessage = function (msgChk) {
        if (msgChk == 1) {
            var message = "Wicket Down";
        }
        else if (msgChk == 2) {
            var message = "Decision Pending";
        }
        else if (msgChk == 3) {
            var message = $scope.otherTxtMsg;
        }
        var FancyID = parseInt($stateParams.FancyID);
        var TypeId = parseInt($stateParams.TypeID);
        var formData = { FancyId: FancyID, TypeId: TypeId, message: message };
        var url = BASE_URL + "Lstsavemstrcontroller/setFancyMsg/";
        $http.post(url, formData).success(function (response) {
            $scope.get_fancyData();
        });
    }
    $scope.PressEnter = function () {
        $scope.callActiveDeactive();
    }
    $scope.get_fancyData = function () {
        $http.get('Lstsavemstrcontroller/getFancyByEdit/' + $stateParams.FancyID + '/' + $stateParams.TypeID).success(function (data, status, headers, config) {
            
            ////
            $scope.SessionData = data.FancyData;
            $scope.ID = data.FancyData[0].ID;
            $scope.NoValume = parseInt(data.FancyData[0].NoValume);
            $scope.YesValume = parseInt(data.FancyData[0].YesValume);
            $scope.pointDiff = parseInt(data.FancyData[0].pointDiff);
            $scope.rateDiff = parseInt(data.FancyData[0].rateDiff);
            $scope.MaxStake = parseInt(data.FancyData[0].MaxStake);
            $scope.headName = data.FancyData[0].HeadName;
            $scope.MatchID = data.FancyData[0].MatchID;
            $scope.TypeID = data.FancyData[0].TypeID;
            $scope.Remarks = data.FancyData[0].Remarks;
            $scope.example_1 = parseInt(data.FancyData[0].SessInptYes);
            $scope.example = parseInt(data.FancyData[0].SessInptNo);
            $scope.active = parseInt(data.FancyData[0].active);
            $rootScope.active = parseInt(data.FancyData[0].active);
            $scope.otherMsgVal = false;
            $scope.DisplayMsg = data.FancyData[0].DisplayMsg;
            $scope.RateChange = data.FancyData[0].RateChange;
            $scope.NoLayRange = parseInt(data.FancyData[0].SessInptNo) - $scope.rangeLimit;
            $scope.YesLayRange = parseInt(data.FancyData[0].SessInptYes) + $scope.rangeLimit;
            if ($rootScope.active == 1) {
                document.getElementById('inputNo').disabled = true;
                document.getElementById('inputNo').readOnly = true;
                $('#inputYes').focus();
            }
            else {
                $scope.example_1 = "";
                $scope.example = "";
                $rootScope.disableVal = "";
                document.getElementById('inputNo').disabled = false;
                document.getElementById('inputNo').readOnly = false;
                $('#inputNo').focus();
            }
        });
    }
    $scope.get_fancyData();
    shortcut.add("Enter", function (event) {
        $scope.callActiveDeactive();
    });
    $scope.callActiveDeactive = function () {
        $scope.vChkUserUpdate = angular.isUndefinedOrNull;//170304
        if ($scope.active == 1) {
            var fStatus = 0;
        }
        else if ($scope.active == 4) {
            var fStatus = 1;
        }
        else if ($scope.active == 0) {
            var fStatus = 1;
        }
        var FancyData = {
            FancyId: $scope.ID,
            example: $scope.example,
            example_1: $scope.example_1,
            MaxStake: $scope.MaxStake,
            NoValume: 100,
            YesValume: 100,
            pointDiff: $scope.pointDiff,
            fStatus: fStatus,
            rateDiff: $scope.rateDiff
        }
        $scope.NormalFancy(FancyData);
    };
    shortcut.add("Alt+w", function (event) {
        var fStatus = 4;
        var FancyData = {
            FancyId: $scope.ID,
            example: $scope.example,
            example_1: $scope.example_1,
            MaxStake: $scope.MaxStake,
            NoValume: 100,
            YesValume: 100,
            pointDiff: $scope.pointDiff,
            fStatus: fStatus
        }
        $scope.NormalFancy(FancyData);
    });
    shortcut.add("Shift+Enter", function (event) {
        if ($scope.active == 1) {
            var fStatus = 0;
        } else if ($scope.active == 4) {
            var fStatus = 1;
        } else if ($scope.active == 0) {
            var fStatus = 1;
        }
        var NoValume = 100 + $scope.pointDiff;
        var YesValume = 100 - $scope.pointDiff;
        var FancyData = {
            FancyId: $scope.ID,
            example: $scope.example,
            example_1: $scope.example,
            MaxStake: $scope.MaxStake,
            NoValume: NoValume,
            YesValume: YesValume,
            pointDiff: $scope.pointDiff,
            fStatus: fStatus
        }
        $scope.ActivateFancy(FancyData);
    });
    $scope.$on("$destroy", function (event) {
        shortcut.remove("Shift+Enter");
        shortcut.remove("Enter");
        shortcut.remove("Alt+w");
    });
    $scope.checkInput = function (e) {
        if (!e) {
            e = window.event;
        }
        var code = e.keyCode || e.which;
        if (!e.ctrlKey) {
            if (code == 8 || code == 13 || code == 9 || code == 27 || code == 46)
                return true;
            if (code >= 35 && code <= 39)
                return true;
            if (code >= 96 && code <= 105)
                return true;
            if (isNaN(parseInt(String.fromCharCode(code), 10))) {
                e.preventDefault();
                return false;
            }
        }
        return true;
    };
    $scope.RateDiffence = function (e) {
        if (!e) {
            e = window.event;
        }
        var code = e.keyCode || e.which;
        if (e.keyCode == 43) {
            $scope.example_1 = $scope.example_1 + 1;
            e.preventDefault();
            return false;
        };
        if (e.keyCode == 45) {
            $scope.example_1 = $scope.example_1 - 1;
            e.preventDefault();
            return false;
        };
    };
    $scope.PointDifference = function (e) {
        if (!e) {
            e = window.event;
        }
        var code = e.keyCode || e.which;
        if (e.keyCode == 43) {
            $scope.pointDiff = $scope.pointDiff + 5;
            e.preventDefault();
            return false;
        };
        if (e.keyCode == 45) {
            $scope.pointDiff = $scope.pointDiff - 5;
            e.preventDefault();
            return false;
        };
    };
    $scope.getVal = function (value) {
        $scope.example_1 = value + $scope.rateDiff;
    };
    $scope.ActivateFancy = function (Fancy) {
        if (($scope.userForm.$valid == true)) {
            if (($scope.NoLayRange == undefined || $scope.NoLayRange < Fancy.example) && ($scope.YesLayRange == undefined || $scope.YesLayRange > Fancy.example_1)) {
                var formData = { status: Fancy.fStatus, matchId: $scope.MatchID, FancyId: parseInt(Fancy.FancyId), fancyType: $scope.TypeID, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff };
                var url = BASE_URL + "Lstsavemstrcontroller/suspendFancy";
                $http.post(url, formData).success(function (response) {
                    $scope.get_fancyData();
                    $('#inputNo').focus();
                    $scope.NoValume = 100;
                    $scope.YesValume = 100;
                    $scope.PointDiff = 10;
                });
            }
            else {
                $mdDialog.show({
                    controller: 'ActivateFancyController',
                    locals: { prntScope: $scope, Fancy: Fancy, message: 'You are out of Range Do you want to continue ...' },
                    templateUrl: 'app/scripts/directives/popupform/confirm_dialog.html',
                    clickOutsideToClose: false,
                    escapeToClose: false,
                    fullscreen: false
                });
            }
        }
        else if (($scope.userForm.$valid == false)) {
            alert('Inavlid Form submition..');
            $('#inputNo').focus();
            return false;
        }
    };
    $scope.ActivateFancyConfirm = function (Fancy) {
        var formData = { status: Fancy.fStatus, matchId: $scope.MatchID, FancyId: Fancy.FancyId, fancyType: $scope.TypeID, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff,rateDiff:Fancy.rateDiff };
        var url = BASE_URL + "Lstsavemstrcontroller/suspendFancy";
        $http.post(url, formData).success(function (response) {
            $scope.get_fancyData();
            $('#inputNo').focus();
            $scope.NoValume = 100;
            $scope.YesValume = 100;
            $scope.PointDiff = 10;
            $mdDialog.hide();
        });
    };
    $scope.NormalFancy = function (Fancy) {
        if (($scope.userForm.$valid == true)) {
            if (($scope.NoLayRange == undefined || $scope.NoLayRange < Fancy.example) && ($scope.YesLayRange == undefined || $scope.YesLayRange > Fancy.example_1)) {
                var formData = { status: Fancy.fStatus, FancyId: parseInt(Fancy.FancyId), NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff,rateDiff:$scope.rateDiff };
                var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
                $http.post(url, formData).success(function (response) {
                    $scope.get_fancyData();
                    $('#inputNo').focus();
                    $scope.NoValume = 100;
                    $scope.YesValume = 100;
                    //$scope.$parent.vChkUserUpdate = setInterval($scope.$parent.ChkUserUpdate, 1000);
                });
            } else {
                $mdDialog.show({
                    controller: 'NormalFancyController',
                    locals: { prntScope: $scope, Fancy: Fancy, message: 'You are out of Range Do you want to continue ...' },
                    templateUrl: 'app/scripts/directives/popupform/confirm_dialog.html',
                    clickOutsideToClose: false,
                    escapeToClose: false,
                    fullscreen: false
                });
            }
        }
        else if (($scope.userForm.$valid == false)) {
            Dialog.autohide('Inavlid Form submition..');
            $('#inputNo').focus();
        }
    };
    $scope.NormalFancyConfirm = function (Fancy) {
        var formData = { status: Fancy.fStatus, FancyId: Fancy.FancyId, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff,rateDiff:$scope.rateDiff };
        var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
        $http.post(url, formData).success(function (response) {
           // //
            $scope.get_fancyData();
            $('#inputNo').focus();
            $scope.NoValume = 100;
            $scope.YesValume = 100;
            $mdDialog.hide();
        });
    };
    $scope.chnageRate = function (rateChangeVal) {
        var FancyID = parseInt($stateParams.FancyID);
        var formData = { RateChange: rateChangeVal, FancyId: FancyID };
        var url = BASE_URL + "Lstsavemstrcontroller/chnageRate";
        $http.post(url, formData).success(function (response) {
            $scope.myVar=0;
            ////
            $scope.get_fancyData();
            $scope.$parent.vChkUserUpdate = setInterval($scope.$parent.ChkUserUpdate, 1000);
            $('#inputNo').focus();
            $scope.NoValume = 100;
            $scope.YesValume = 100;
        });
    }
});
app.directive('userlist', function () {
    return {
        templateUrl: 'app/scripts/directives/timeline/Session_usr_lst.html',
        restrict: 'E',
        replace: true,
        scope: { myVar: '=' },
        controller: function ($scope, $http, $stateParams, get_userser,$interval) {
            get_userser.GetAllSessFancyBet($stateParams.FancyID, function (response) {
                $scope.myVar = 0
                $scope.$parent.GetSesFancyUserLst = response;
                 var s = $scope.$parent.GetSesFancyUserLst.length;
                    var t = response.length;
                   
                    get_userser.GetFancyBal($stateParams.FancyID, function (response1) {
                    
                        $scope.myVar =parseInt(response1);
                    });
                    if (s == t) {

                    } else {
                        $scope.GetSesFancyUserLst = response;
                    }
                get_userser.GetFancyBal($stateParams.FancyID, function (response1) {
                    ////
                    $scope.myVar =parseInt(response1);
                });
                /*for (var i = 0; i < $scope.GetSesFancyUserLst.length; i++) {
                    $scope.myVar = parseInt($scope.myVar) + parseInt($scope.GetSesFancyUserLst[i].bet_value);
                };*/
            });
            $scope.$parent.myVar = $scope.myVar;
            $scope.$parent.ChkUserUpdate = function () {
                get_userser.GetAllSessFancyBet($stateParams.FancyID, function (response) {
                    var s = $scope.$parent.GetSesFancyUserLst.length;
                    var t = response.length;
                   
                    get_userser.GetFancyBal($stateParams.FancyID, function (response1) {
                    
                        $scope.myVar =parseInt(response1);
                    });
                    if (s == t) {

                    } else {
                        $scope.GetSesFancyUserLst = response;
                    }
                  /*  if ($scope.myVar == $scope.$parent.totalStke) {
                        if ($scope.$parent.active != 4) {
                            $http.get('Lstsavemstrcontroller/updateRateChangeMsg/' + $stateParams.FancyID + '/' + $stateParams.TypeID).success(function (data, status, headers, config) {
                            });
                        }
                    }*/
                });
                get_userser.GetFancyBal($stateParams.FancyID, function (response1) {
                   
                    $scope.myVar =parseInt(response1);
                });
            }
            $scope.$parent.vChkUserUpdate = $interval($scope.$parent.ChkUserUpdate, 1000);//170304
            $scope.$on("$destroy", function (event) {
                $interval.cancel($scope.$parent.vChkUserUpdate);
                $scope.$parent.vChkUserUpdate = angular.isUndefinedOrNull;//170304
            });
        }
    }
});
app.controller('NormalFancyController', function ($scope, $mdDialog, focus, prntScope, Fancy, message) {//sourabh 170123
    $scope.HeaderMessage = message;
    $scope.yesClick = function () { prntScope.NormalFancyConfirm(Fancy); }
    $scope.noClick = function () { $mdDialog.hide(); }
    shortcut.remove("Enter");
    shortcut.add("y", function (event) { focus('btnYes'); });
    shortcut.add("Left", function (event) { focus('btnYes'); });
    shortcut.add("n", function (event) { focus('btnNo'); });
    shortcut.add("Right", function (event) { focus('btnNo'); });
    $scope.$on("$destroy", function (event) {
        shortcut.remove("y");
        shortcut.remove("Left");
        shortcut.remove("n");
        shortcut.remove("Right");
        shortcut.add("Enter", function (event) {
            prntScope.callActiveDeactive();
        });
    });
});
app.controller('ActivateFancyController', function ($scope, $mdDialog, focus, prntScope, Fancy, message) {//sourabh 170123
    $scope.HeaderMessage = message;
    $scope.yesClick = function () { prntScope.ActivateFancyConfirm(Fancy); }
    $scope.noClick = function () { $mdDialog.hide(); }
    shortcut.remove("Enter");
    shortcut.add("y", function (event) { focus('btnYes'); });
    shortcut.add("Left", function (event) { focus('btnYes'); });
    shortcut.add("n", function (event) { focus('btnNo'); });
    shortcut.add("Right", function (event) { focus('btnNo'); });
    $scope.$on("$destroy", function (event) {
        shortcut.remove("y");
        shortcut.remove("Left");
        shortcut.remove("n");
        shortcut.remove("Right");
        shortcut.add("Enter", function (event) {
            prntScope.callActiveDeactive();
        });
    });
});