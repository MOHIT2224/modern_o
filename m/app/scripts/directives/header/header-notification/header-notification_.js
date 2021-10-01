'use strict';
angular.module('ApsilonApp').directive('headerNotification', ['$timeout', function ($timeout) {
    return {
        templateUrl: 'app/scripts/directives/header/header-notification/header-notification.html',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: function ($scope, $http, loginService, $mdDialog, sessionService, $rootScope, get_userser, Dialog,$state) {
            $scope.$on('$locationChangeStart', function (event, next, current) { event.preventDefault(); });
            $scope.Redirection=function(redirectLoc){
                
                $state.go(redirectLoc);
                alert(redirectLoc);

            }
            $scope.myPOPUP = true;
            if (sessionService.get('HelperID') == '0')
                $scope.name = sessionService.get('user');
            else 
                $scope.name = sessionService.get('HelperName');
            $scope.usertype = sessionService.get('type');
            $scope.slctUseName = sessionService.get('slctUseName');
            var chkBalStatus = function () { get_userser.updateUserBal(sessionService.get('slctUseID'), function (response) {	/*console.log("service Called"+response);*/ }); }
            setInterval(chkBalStatus, 5000);
            $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + sessionService.get('user_id')).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility;
                sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
                sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
                sessionService.set('Liability', $scope.cipsData[0].Liability);
                sessionService.set('Balance', $scope.cipsData[0].Balance);
                sessionService.set('P_L', $scope.cipsData[0].P_L);
                $scope.$watch('sessionService', function (newVal, oldVal) {
                    $scope.FreeChips = $scope.cipsData[0].FreeChip;
                    $scope.ChipInOut = $scope.cipsData[0].Chip;
                    $scope.Liability = $scope.cipsData[0].Liability;
                    $scope.Balance = $scope.cipsData[0].Balance;
                    $scope.P_L = $scope.cipsData[0].P_L;
                });
                $rootScope.user = sessionService.get('slctUseName');
                $rootScope.Balance = sessionService.get('Balance');
                $rootScope.Liability = sessionService.get('Liability');
            });
            $scope.showMenuChip = function () {
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + sessionService.get('user_id')).success(function (data, status, headers, config) {
                    if (data.betLibility != angular.isUndefinedOrNull) {
                        $scope.cipsData = data.betLibility;
                        $scope.FreeChips = $scope.cipsData[0].FreeChip;
                        $scope.ChipInOut = $scope.cipsData[0].Chip;
                        $scope.Liability = $scope.cipsData[0].Liability;
                        $scope.Balance = $scope.cipsData[0].Balance;
                        $scope.P_L = $scope.cipsData[0].P_L;
                    }
                });
            }
            $scope.logout = function () {
                loginService.logout(function (response) {
                    //console.log("LOGOUT SUCCESS");
                });
                localStorage.user;
                localStorage.clear();
            };
            $scope.ClosePopup = function (btnClicked, id) {

                $scope.buttonClicked4 = btnClicked;
                $scope.addacc1 = !$scope.addacc1;
                $scope.myPOPUP = false;
            };

          /*  $scope.showTermAndCondition = function () {
if(localStorage.length > 1){
                $mdDialog.show({
                    controller: DialogController,
                    templateUrl: 'app/scripts/directives/header/header-notification/termandconditionDialog.html',
                    clickOutsideToClose: false,
                    fullscreen: false
                });
}
            };*/
            $scope.changePassPopup = function () {

                $mdDialog.show({
                    controller: ChangePassCntr,
                    templateUrl: 'app/scripts/directives/timeline/changePassword.html',
                    clickOutsideToClose: false,
                    fullscreen: false
                });
            };
            function ChangePassCntr($scope, get_userser) {
                $scope.updatePassword = function (oldPassword, newPassword, cnfnewPassword) {
                    if (oldPassword != "" && oldPassword != angular.isUndefinedOrNull && newPassword != "" && newPassword != angular.isUndefinedOrNull && cnfnewPassword != "" && cnfnewPassword != angular.isUndefinedOrNull) {
                        if (newPassword == cnfnewPassword) {
                            get_userser.changePassword(oldPassword, newPassword, sessionService.get('user_id'), function (response) {
                                if (response.error == 0) {
                                    Dialog.autohide(response.message);
                                    sessionService.set('ChangePas', '1');
                                } else {
                                    alert(response.message);
                                }
                            });
                        } else {
                            alert("New Password and Comfirm Password not Match");
                        }
                    } else {
                        alert("All Field Required...");
                    }
                }
            }
            $scope.showChipSetting = function () {
                $mdDialog.show({
                    controller: DialogController,
                    templateUrl: 'app/scripts/directives/header/header-notification/chipSetting.html',
                    clickOutsideToClose: true,
                    fullscreen: false
                });
            };
            $scope.showSetPassword = function () {
                $mdDialog.show({
                    controller: SetPasswrdCntr,
                    templateUrl: 'app/scripts/directives/header/header-notification/set-password.html',
                    locals: { prntScope: $scope },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function SetPasswrdCntr(scope, $mdDialog) {
                scope.SetDefaltPaswrd = function (setDftPswrd) {
                    $http.get( BASE_URL+'Createmastercontroller/UpdateCnfgPasssword/' + scope.setDftPswrd).success(function (data, status, headers, config) {
                        $mdDialog.show({
                            clickOutsideToClose: false,
                            escapeToClose: true,
                            template: "<md-dialog style='border: rgb(225, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content><br><br><h1>" + data.message + "</h1></md-dialog-content></md-dialog>",
                            fullscreen: false
                        });
                        $timeout(callAtTimeout, 2000);
                        function callAtTimeout() { $mdDialog.hide(); }
                    });
                }
                scope.hide = function () { $mdDialog.hide(); };
            }
            function showChipsInOutController($scope, $mdDialog, prntScope, node) {
                $scope.hide = function () { $mdDialog.hide(); };
                $scope.node = node;
                $scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType) {
                    prntScope.FreeChipsSubmit(Chip, Type, UserID, Free, userType);
                    Chip.ChipVal = "";
                    Chip.Ref = "";
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            var changePass = sessionService.get('ChangePas');

            var userType = sessionService.get('type');
            if (userType == 0) {
            } else if (userType != 0 && changePass == 0) {
                $scope.changePassPopup();
            } else {
              //  localStorage.getItem("$_") == null ? $scope.showTermAndCondition() : "";
              //  localStorage.setItem("$_", true);
            }

            function DialogController($scope, $mdDialog) {
                $scope.hide = function () { $mdDialog.hide(); };
                get_userser.userChipSetting(function (response) {
                    if (response != angular.isUndefinedOrNull && response.length > 0) {
                        $scope.Name1 = response[0].Name1;
                        $scope.Name2 = response[0].Name2;
                        $scope.Name3 = response[0].Name3;
                        $scope.Name4 = response[0].Name4;
                        $scope.Name5 = response[0].Name5;
                        $scope.Name6 = response[0].Name6;
                        $scope.Value1 = parseInt(response[0].Value1);
                        $scope.Value2 = parseInt(response[0].Value2);
                        $scope.Value3 = parseInt(response[0].Value3);
                        $scope.Value4 = parseInt(response[0].Value4);
                        $scope.Value5 = parseInt(response[0].Value5);
                        $scope.Value6 = parseInt(response[0].Value6);
                        $scope.UserID = parseInt(response[0].UserID);
                        $scope.ID = parseInt(response[0].ID);
                    }
                });
                $scope.submitForm_chipSetting = function () {
                    if ($scope.Name1 == angular.isUndefinedOrNull && ($scope.Value1 == 0 || $scope.Value1 == angular.isUndefinedOrNull)) {
                        $scope.Name1 = "";
                        $scope.Value1 = 0;
                    } else {
                        $scope.Name1 = $scope.Name1;
                        $scope.Value1 = $scope.Value1;
                    }
                    if ($scope.Name2 == angular.isUndefinedOrNull && ($scope.Value2 == 0 || $scope.Value2 == angular.isUndefinedOrNull)) {
                        $scope.Name2 = "";
                        $scope.Value2 = 0;
                    } else {
                        $scope.Name2 = $scope.Name2;
                        $scope.Value2 = $scope.Value2;
                    }
                    if ($scope.Name3 == angular.isUndefinedOrNull && ($scope.Value3 == 0 || $scope.Value3 == angular.isUndefinedOrNull)) {
                        $scope.Name3 = "";
                        $scope.Value3 = 0;
                    } else {
                        $scope.Name3 = $scope.Name3;
                        $scope.Value3 = $scope.Value3;
                    }
                    if ($scope.Name4 == angular.isUndefinedOrNull && ($scope.Value4 == 0 || $scope.Value4 == angular.isUndefinedOrNull)) {
                        $scope.Name4 = "";
                        $scope.Value4 = 0;
                    } else {
                        $scope.Name4 = $scope.Name4;
                        $scope.Value4 = $scope.Value4;
                    }
                    if ($scope.Name5 == angular.isUndefinedOrNull && ($scope.Value5 == 0 || $scope.Value5 == angular.isUndefinedOrNull)) {
                        $scope.Name5 = "";
                        $scope.Value5 = 0;
                    } else {
                        $scope.Name5 = $scope.Name5;
                        $scope.Value5 = $scope.Value5;
                    }
                    if ($scope.Name6 == angular.isUndefinedOrNull && ($scope.Value6 == 0 || $scope.Value6 == angular.isUndefinedOrNull)) {
                        $scope.Name6 = "";
                        $scope.Value6 = 0;
                    } else {
                        $scope.Name6 = $scope.Name6;
                        $scope.Value6 = $scope.Value6;
                    }
                    var ChipData = { Name1: $scope.Name1, Name2: $scope.Name2, Name3: $scope.Name3, Name4: $scope.Name4, Name5: $scope.Name5, Name6: $scope.Name6, Value1: parseInt($scope.Value1), Value2: parseInt($scope.Value2), Value3: parseInt($scope.Value3), Value4: parseInt($scope.Value4), Value5: parseInt($scope.Value5), Value6: parseInt($scope.Value6), id: parseInt($scope.ID), UserID: parseInt(sessionService.get('user_id')) };

                    get_userser.updateUserChipSetting(ChipData, function (response) {
                        if (response.error == 0) {
                            Dialog.autohide(response.message);
                        } else {
                            Dialog.autohide(response.message);
                        }
                    });
                }
            }

        }
    }
}]);




