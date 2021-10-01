'use strict';
var app = angular.module('ApsilonApp');
app.directive('sidebar', ['$location', '$timeout', function ($window, $http, sessionService, $timeout, get_userser) {
    return {
        templateUrl: 'directives/sidebar',
        restrict: 'E',
        replace: true,
        scope: {},
        link: function (scope, element, attrs) {//sourabh 170120
            scope.$on('changeSidebar_Series', function (event, data) {
                scope.ShowHideAng(scope.sportsId);
            });
            scope.$on('changeSidebar_Match', function (event, data) {
                scope.getSeriesMatch(scope.sportsId, scope.seriesId);
            });
            scope.$on('changeSidebar_Market', function (event, data) {
                scope.getMatchMarket(scope.sportsId, scope.MatchId);
            });
        },
        controller: function ($scope, $http, $timeout, $mdDialog, sessionService, $rootScope, get_userser, Dialog) {
            $scope.chkMarketPP = false;
            $scope.refresh_tree = function () {
                $http.get('Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype'))
                     .success(function (data, status, headers, config) {

                         $scope.treeNodes = data.tree;
                     });
            }
            //end of refresh Button
            //asha  popup start 18 dec
            $(".myMenu1").click(function () {
                $(".dropdown123").show();
            });
            $(document).click(function (e) {
                if (!$(e.target).hasClass("myMenu1") && $(e.target).parents(".dropdown").length === 0) {
                    $(".dropdown123").hide();
                }
                if (!$(e.target).hasClass("myMenu2") && $(e.target).parents(".dropdown").length === 0) {
                    $scope.dropdown124 = false;
                }
            });
            //Add Account
            $scope.showAddSetting = function (node, currentScope1) {
                $scope.mid = node.id;
                $scope.fancyType = node.usetype;
                if (node.usetype == 1) {
                    $scope.HeadingType = "Create Dealer";
                    $scope.HeadingName = "Dealer Name";
                    $scope.HeadingTypeId = 2;
                }
                else if (node.usetype == 2) {
                    $scope.HeadingType = "Create User";
                    $scope.HeadingName = "User Name";
                    $scope.HeadingTypeId = 3;
                }
                else if (node.usetype == 0) {
                    $scope.HeadingType = "Create Master";
                    $scope.HeadingName = "Master Name";
                    $scope.HeadingTypeId = 1;
                }
                $mdDialog.show({
                    controller: showAddSettingController,
                    templateUrl: 'app/scripts/directives/popupform/add_account.html',
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showAddSettingController($scope, $mdDialog, prntScope, node, sessionService, currentScope1) {
                $scope.currentScope1 = currentScope1;
                $scope.errorMsg = "";
                $scope.errorMsg1 = "";
                $scope.FreeChips = sessionService.get('FreeChips');
                $scope.ChipInOut = sessionService.get('ChipInOut');
                $scope.Liability = sessionService.get('Liability');
                $scope.Balance = sessionService.get('Balance');
                $scope.mid = prntScope.mid;
                $scope.fancyType = prntScope.fancyType;
                $scope.HeadingType = prntScope.HeadingType;
                $scope.HeadingName = prntScope.HeadingName;
                $scope.HeadingTypeId = prntScope.HeadingTypeId;
                $scope.node = node;
                $scope.getDate = new Date();
                $scope.checkUserName = function (username) {
                    if (username.length < 4) {
                        $scope.errorMsg = "Username must be greater than Four Character";
                    } else {
                        $http({
                            method: 'POST',
                            url: 'Createmastercontroller/CheckUserName/' + username, data: username, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                            .success(function (data) {
                                if (data.error == 0) {
                                    $scope.errorMsg = data.message;
                                    $scope.errorMsg1 = "";
                                } else {
                                    $scope.errorMsg1 = data.message;
                                    $scope.errorMsg = "";
                                }
                            });
                    }
                };
                $scope.submitForm_Users = function (user, node) { prntScope.submitForm_Users(user, node); };
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //View Account        

            $scope.showViewAccountForm = function () {
                //debugger;
                $mdDialog.show({
                    controller: showViewSettingController,
                    templateUrl: 'app/scripts/directives/popupform/view_account.html',
                    locals: { prntScope: $scope, node: $scope.vcNode, currentScope1: $scope.vcCurrentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            }
            $scope.showViewSetting = function (node, currentScope1) {
                $scope.vcNode = node;
                $scope.vcCurrentScope1 = currentScope1;
                $scope.showViewAccountForm();
            };
            function showViewSettingController($scope, $mdDialog, prntScope, node, currentScope1, Dialog) {
                $scope.sessionusetype = sessionService.get('type');
                $scope.currentScope1 = currentScope1;
                $scope.node = node;
                $scope.Commission = parseFloat(node.Commission);
                $scope.SessionComm = parseFloat(node.SessionComm);
                $scope.otherComm = parseFloat(node.OtherComm);
                get_userser.getUserPartnerShip(node.id, function (response) {
                    $scope.tblParner = response.data.userPrtnrShip;
                    $scope.ID = response.data.userPrtnrShip[0].ID;
                    $scope.TypeID = response.data.userPrtnrShip[0].TypeID;
                    $scope.ParentID = response.data.userPrtnrShip[0].ParentID;
                    $scope.UserID = response.data.userPrtnrShip[0].UserID;
                    $scope.Admin = parseFloat(response.data.userPrtnrShip[0].Admin);
                    $scope.Master = parseFloat(response.data.userPrtnrShip[0].Master);
                    $scope.Dealer = parseFloat(response.data.userPrtnrShip[0].Dealer);
                });
                $scope.checkInput = function (e) {
                    if (!e) { e = window.event; }
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
                $scope.updatePartnerShip = function (Admin, Master, Dealer, ID) {
                    var sumofVal = parseFloat(Admin) + parseFloat(Master) + parseFloat(Dealer);
                    if (sumofVal == 100) {
                        $http.get('Createmastercontroller/updatePartnerShip/' + Admin + '/' + Master + '/' + Dealer + '/' + ID)
                            .success(function (data1, status, headers, config) {
                                Dialog.autohide(data1.message, 1000);
                                //debugger;
                                prntScope.showViewAccountForm();
                                prntScope.refresh_tree();
                            });
                    }
                    else {
                        //alert("Invalid PartnerShip...");
                        Dialog.autohide("Invalid PartnerShip...", 1000);

                    }
                }
                $scope.updateCommission = function (oddsComm, sessionComm, otherComm, ID) {
                    $http.get('Createmastercontroller/updateCommission/' + oddsComm + '/' + sessionComm + '/' + otherComm + '/' + ID).success(function (data1, status, headers, config) {
                        Dialog.autohide(data1.message, 1000);
                        prntScope.showViewAccountForm();
                        prntScope.refresh_tree();
                    });
                }

                $scope.UpdateViewAccount = function (user, node, currentScope1) {

                    var userId = document.getElementById('vewMod2ID').value;
                    var Name = document.getElementById('vewMod2Name').value;
                    var partnership = 0;
                    try { partnership = document.getElementById('partnership1').value; } catch (e) { partnership = 0; }
                    // var Commission = document.getElementById('Commission1').value;
                    var maxProfit = document.getElementById('maxProfit1').value;
                    var maxLoss = document.getElementById('maxLoss1').value;
                    var maxStake = document.getElementById('maxStake1').value;
                    //debugger;
                    var userInfo = {
                        id: node.id,
                        userId: userId,
                        Name: Name,
                        partnership: partnership,
                        maxProfit: maxProfit,
                        maxLoss: maxLoss,
                        maxStake: maxStake,
                        userType: node.usetype,
                        PntPartenerShip: currentScope1.partner,
                        set_timeout: node.set_timeout,
                        parantId: currentScope1.id
                    }

                    prntScope.UpdateViewAccount(userInfo, node);
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //Change Password       
            $scope.showChangePwd = function (node, currentScope1)//sourabh new 161222
            {

                $mdDialog.show({
                    controller: showChangePwdController,
                    templateUrl: 'app/scripts/directives/popupform/change_password.html',
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showChangePwdController($scope, $mdDialog, prntScope, node, sessionService, currentScope1) //sourabh new 161222
            {

                $scope.currentScope1 = currentScope1;
                $scope.node = node;
                $scope.chngPgetType = sessionService.get('type');
                $scope.chngsltUType = sessionService.get('slctUseTypeID');
                $scope.ResetGloPass = function (userId) {
                    $http.get('Createmastercontroller/GetResetPasssword/')
                        .success(function (data, status, headers, config) {
                            $scope.gtPasWrd = data.gtCnfgPswrd;
                            $http.get('Createmastercontroller/UpdateRstPasssword/' + node.id + '/' + $scope.gtPasWrd).success(function (data1, status, headers, config) {
                                if (data1.error == 0) {
                                    //alert(data1.message);
                                    $mdDialog.show({
                                        clickOutsideToClose: false,
                                        escapeToClose: true,
                                        template: "<md-dialog style='border: rgb(225, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content><br><br><h1>" + data1.message + "</h1></md-dialog-content></md-dialog>",

                                        fullscreen: false,
                                    });
                                } else {
                                    $mdDialog.show({
                                        clickOutsideToClose: false,
                                        escapeToClose: true,
                                        template: "<md-dialog style='border: rgb(225, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content><br><br><h1>" + data1.message + "</h1></md-dialog-content></md-dialog>",

                                        fullscreen: false,
                                    });
                                }
                                $timeout(callAtTimeout, 2000);
                                function callAtTimeout() { $mdDialog.hide(); }
                            });
                        });
                }

                $scope.changeUserPasswordSubmit = function (user, node) {

                    var userId = node.id;
                    var oldPassword = user.oldPassword;
                    var newPassword = user.newPassword;
                    var cnfnewPassword = user.cnfnewPassword;

                    var uesrInfo = {
                        userName: node.name,
                        userId: userId,
                        oldPassword: oldPassword,
                        newPassword: newPassword,
                        cnfnewPassword: cnfnewPassword,
                        userType_id: $scope.chngPgetType,
                        SltUsrType_id: $scope.chngsltUType,
                    }

                    prntScope.changeUserPasswordSubmit(uesrInfo, node);

                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //Free Chips      
            $scope.showFreeChips = function (node, currentScope1)//sourabh 161223
            {


                //$scope.buttonClicked4 = btnClicked;
                //$scope.freechips1 = !$scope.freechips1;
                //$scope.modShHd = 4;
                /*start The Parant ChipData*/
                $mdDialog.show({
                    controller: showFreeChipsController,
                    templateUrl: 'app/scripts/directives/popupform/free_chips.html',
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showFreeChipsController($scope, $mdDialog, prntScope, node, currentScope1) //sourabh 
            {
                $scope.node = node;
                $scope.userType = node.usetype;

                if (node.usetype == 0) {
                    $http.get('Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {

                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = 9999999;
                        $scope.userType = node.usetype;
                    });
                }
                else {
                    $http.get('Chipscntrl/getChipDataById/' + currentScope1.id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = $scope.pFreeChipVal;
                        $scope.userType = node.usetype;
                    });
                    //Get User Value
                    $http.get('Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipBalance = $scope.cipsData[0].Balance;
                    });
                    //End of User Value
                }
                $scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType) {

                    Chip.pFreeChipVal = $scope.pFreeChipVal;
                    Chip.UFreeChipVal = $scope.UFreeChipVal;
                    Chip.pChipBalance = $scope.pChipBalance;
                    prntScope.FreeChipsSubmit(Chip, Type, UserID, Free, userType);
                    Chip.ChipVal = "";
                    Chip.Ref = "";
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //Chips Inout      
            $scope.showChipsInOut = function (node)//sourabh 161224
            {


                $mdDialog.show({
                    controller: showChipsInOutController,
                    templateUrl: 'app/scripts/directives/popupform/chips_inout.html',
                    locals: { prntScope: $scope, node: node },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showChipsInOutController($scope, $mdDialog, prntScope, node) {
                $scope.node = node;
                $scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType) {
                    prntScope.FreeChipsSubmit(Chip, Type, UserID, Free, userType);
                    Chip.ChipVal = "";
                    Chip.Ref = "";
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //Lock User      
            $scope.showLockUser1 = function (node)//sourabh 161223
            {
                $mdDialog.show({
                    controller: showLockUser1Controller,
                    templateUrl: 'app/scripts/directives/popupform/lock_user.html',
                    locals: { prntScope: $scope, node: node },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showLockUser1Controller($scope, $mdDialog, prntScope, node) //sourabh 161223
            {
                $scope.hide = function () { $mdDialog.hide(); }
                $scope.node = node;
                if (node.mstrlock == 0) {
                    $scope.content = "Would you like to Unlock";
                } else {
                    $scope.content = "Would you like to Lock";
                }
                $scope.lockValue = node.mstrlock;

                $scope.UpdateUserLock = function (user) {
                    prntScope.UpdateUserLock(node);
                }
            }
            //Lock Betting      
            $scope.showLockBetting = function (node)//sourabh 161224
            {

                //$scope.buttonClicked4 = btnClicked;
                //$scope.lockbetting1 = !$scope.lockbetting1;
                //$scope.modShHd = 7;

                $mdDialog.show({
                    controller: showLockBettingController,
                    templateUrl: 'app/scripts/directives/popupform/lock_betting.html',
                    locals: { prntScope: $scope, node: node },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showLockBettingController($scope, $mdDialog, prntScope, node)//sourabh 161224
            {
                $scope.node = node;
                if (node.lgnusrlckbtng == 1) {
                    $scope.btingcontent = "Would you like to lock Betting";
                } else {
                    $scope.btingcontent = "Would you like to Unlock Betting";
                }
                $scope.UpdateLockBatting = function (user) {
                    prntScope.UpdateLockBatting(node);
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //Close Acc      
            $scope.showCloseAcc = function (node) {


                $mdDialog.show({
                    controller: showCloseAccController,
                    templateUrl: 'app/scripts/directives/popupform/close_account.html',
                    locals: { prntScope: $scope, node: node },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showCloseAccController($scope, $mdDialog, prntScope, node) //sourabh 161224
            {
                $scope.node = node;
                if (node.lgnusrCloseAc == 0) {
                    $scope.acContent = "Would you like to Open Acc";
                } else {
                    $scope.acContent = "Would you like to Close Acc";
                }
                $scope.UpdateAccount = function (user) {
                    prntScope.UpdateAccount(node);
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //asha  popup end 18 dec
            $scope.getDate = new Date();
            $scope.$watch('sessionService', function (newVal, oldVal) {

                $scope.FreeChips = sessionService.get('FreeChips');
                $scope.ChipInOut = sessionService.get('ChipInOut');
                $scope.Liability = sessionService.get('Liability');
                $scope.Balance = sessionService.get('Balance');

            });
            $scope.sessionusetype = sessionService.get('type');
            $scope.sessionuser_id = sessionService.get('user_id');
            $scope.getDashboardurl = function () {
                //
                switch ($scope.sessionusetype) {
                    case "0": return "dashboard.Home"; break;
                    case "1": return "dashboard.Masterdashboard"; break;
                    case "2": return "dashboard.Dealerdashboard"; break;
                    case "3": return "dashboard.Userdashboard"; break;

                }

            }
            //Get The sports from DataBase
            $http.get('Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
                $scope.sprtData = data.sportData;


            })
             .error(function (data, status, header, config) {
                 $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
             });
            //End Of Get Sport From Database
            $scope.message = "";
            /*start Createuser Controller Code Here*/
            //{sourabh 7-oct-2016 
            $scope.setNodeToTable = function (node) {

                var usertypename = '';
                $scope.tblNodeName = node.name;
                sessionService.set('set_timeout', node.set_timeout);
                $http.get('Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {
                    $scope.cipsData = data.betLibility;
                    if ($scope.cipsData[0] != angular.isUndefinedOrNull) {
                        sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
                        sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
                        sessionService.set('Liability', $scope.cipsData[0].Liability);
                        sessionService.set('Balance', $scope.cipsData[0].Balance);
                    } else {
                        sessionService.set('FreeChips', 0);
                        sessionService.set('ChipInOut', 0);
                        sessionService.set('Liability', 0);
                        sessionService.set('Balance', 0);
                    }
                    $scope.$watch('sessionService', function (newVal, oldVal) {
                        $scope.FreeChips = sessionService.get('FreeChips');
                        $scope.ChipInOut = sessionService.get('ChipInOut');
                        $scope.Liability = sessionService.get('Liability');
                        $scope.Balance = sessionService.get('Balance');
                    });
                    $rootScope.user = node.name;//sourabh 170105
                    $rootScope.FreeChips = sessionService.get('FreeChips');//sourabh 170105
                    $rootScope.Balance = sessionService.get('Balance');//sourabh 170105
                    $rootScope.Liability = sessionService.get('Liability');//sourabh 170105
                    $rootScope.NodeCntUsrDetails = node;

                });
                $scope.tblNodeRisk = 0;
                sessionService.set('slctUseID', node.id);
                sessionService.set('slctUseName', node.name);
                if ($scope.currentScope1 != null) {
                    sessionService.set('slctParantID', $scope.currentScope1.id);
                    sessionService.set('slctParantName', $scope.currentScope1.name);
                }
                else {
                    $http.get('Chipscntrl/getParentById/' + node.id).success(function (data, status, headers, config) {
                        sessionService.set('slctParantID', data.parentData[0].mstrid);
                        sessionService.set('slctParantName', data.parentData[0].mstrname);
                    });
                }
                sessionService.set('slctUseTypeID', node.usetype);
                switch (node.usetype) {
                    case '0': usertypename = 'Admin'; break;//sourabh 161219
                    case '1': usertypename = 'Master'; break;
                    case '2': usertypename = 'Dealer'; break;
                    case '3': usertypename = 'User'; break;
                }
                sessionService.set('slctUseType', usertypename);

            }
            $scope.getValColor = function (val) {//7-oct-2016
                if (val > 0)
                    return 'color:#007c0e';
                else
                    return 'color:#ff0000';
            }
            $scope.sumOfValue = function (ChipInOut, FreeChips, Liability) {//7-oct-2016
                $scope.wallet = parseFloat(ChipInOut) + parseFloat(FreeChips) + parseFloat(Liability);
            }
            //}sourabh 7-oct-2016
            $scope.open = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.opened = true;
            };
            $scope.dateOptions = {
                formatYear: 'yy',
                startingDay: 1
            };
            $scope.HideLockUserFrm = function () {
                $scope.modShHd = false;
            };
            $scope.formats = ['dd-MMMM-yyyy', 'yyyy-MM-dd', 'dd.MM.yyyy', 'shortDate'];
            $scope.format = $scope.formats[1];
            /*lock user*/
            $scope.UpdateUserLock = function (node) {

                /*var userId = document.getElementById('userId').value;
                var lockVal = node.mstrlocksourabh 161223;*/
                var userId = node.id;//sourabh 161223
                var lockVal = node.mstrlock;//sourabh 161223
                if (lockVal == 0) {
                    var lock = 1;
                } else {
                    var lock = 0;
                }

                var lockUserData = {//sourabh 161224
                    userName: node.name,
                    userType: node.usetype,
                    userId: node.id,
                    lockVal: lock,
                    loginUserID: sessionService.get('user_id')
                }
                //***************
                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/lockuser/',
                    data: lockUserData, //forms user object
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() {
                            $scope.msgShowHide = false;//$scope.showModal=false;
                        }
                        $scope.refresh_tree();
                        $scope.modShHd = 0;
                        $mdDialog.hide();//sourabh 161223
                    } else {
                        //$scope.errorMsg = data.message;sourabh 161223
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $mdDialog.hide();//sourabh 161223
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                    }
                });
                //***************  


            };
            /*end*/
            /*update user*/
            $scope.UpdateViewAccount = function (useinfo, node) {

                var userId = useinfo.userId;
                var Name = useinfo.Name;
                var partnership = 0;//  useinfo.partnership;
                try { partnership = useinfo.partnership; } catch (e) { partnership = 0; }
                var Commission = useinfo.Commission;
                var maxProfit = useinfo.maxProfit;
                var maxLoss = useinfo.maxLoss;
                var maxStake = useinfo.maxStake;
                //
                var accountUserData = {
                    userId: userId,
                    name: Name,
                    partnership: partnership,
                    maxProfit: maxProfit,
                    maxLoss: maxLoss,
                    maxStake: maxStake,
                    set_timeout: useinfo.set_timeout
                }
                $http({
                    method: 'POST', url: 'Createmastercontroller/updateUserAccountData/', data: useinfo, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                        //$scope.alertMessage = data.message;
                        Dialog.autohide(data.message);
                        $scope.showViewAccountForm();
                        //$scope.msgShowHide = true;
                        //$timeout(callAtTimeout, 1000);
                        //function callAtTimeout() {
                        //    $scope.msgShowHide = false;
                        //}
                        $scope.refresh_tree();
                        $scope.modShHd = 0;
                    } else {
                        //$scope.errorMsg = data.message;
                        Dialog.autohide(data.message);
                        $scope.showViewAccountForm();
                    }
                });
                //***************  
                //$mdDialog.hide(); for not on update click
            };
            /*end*/
            /*lock user*/
            $scope.UpdateAccount = function (node) {
                //var userId = document.getElementById('userId').value;sourabh 161224
                //var acval = document.getElementById('acval').value;sourabh 161224

                var userId = node.id;//sourabh 161224
                var acval = node.lgnusrCloseAc;//sourabh 161224
                if (acval == 0) {
                    var accValue = 1;
                } else {
                    var accValue = 0;
                }

                var accountUserData = {
                    userName: node.name,
                    userType: node.usetype,
                    userId: userId,
                    accValue: accValue
                }
                //***************
                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/updateUserAccount/',
                    data: accountUserData, //forms user object
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                    .success(function (data) {
                        if (data.error == 0) {
                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $timeout(callAtTimeout, 1000);
                            function callAtTimeout() {
                                $scope.msgShowHide = false;
                                //$scope.showModal=false;sourabh 161224
                            }

                            /* $http.get('Lstsavemstrcontroller/lstSaveMaster/'+sessionService.get('user_id'))
                                 .success(function (data, status, headers, config) {
                                     $scope.treeNodes = data.tree;
                             });*/
                            $scope.refresh_tree();
                            $scope.modShHd = 0;
                            $mdDialog.hide();//sourabh 161224
                        } else {

                            //$scope.errorMsg = data.message;sourabh 161224
                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $mdDialog.hide();
                            $timeout(callAtTimeout, 1000);
                            function callAtTimeout()
                            { $scope.msgShowHide = false; }
                        }
                    });
                //***************  

            };
            /*end*/
            /*start Lock and Unlock Batting*/
            $scope.UpdateLockBatting = function (node)//sourabh 161224
            {
                /*var userId = document.getElementById('userId').value;
                var battinglockVal = document.getElementById('battinglockVal').value;*/
                var userId = node.id;//sourabh 161224
                var battinglockVal = node.lgnusrlckbtng;//sourabh 161224
                if (battinglockVal == 0) {
                    var lockbatting = 1;
                } else {
                    var lockbatting = 0;
                }
                var lockBatingData = {
                    userName: node.name,
                    userType: node.usetype,
                    userId: userId,
                    lockbettingVal: lockbatting
                }
                //***************

                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/lockuserbetting/',
                    data: lockBatingData, //forms user object
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    /* if (data.error == 0) {
                    */                                  $scope.alertMessage = data.message;
                    $scope.msgShowHide = true;//sourabh 161224
                    $timeout(callAtTimeout, 1000);//sourabh 161224
                    function callAtTimeout() { $scope.msgShowHide = false; $scope.showModal = false; }

                    /* $http.get('Lstsavemstrcontroller/lstSaveMaster/'+sessionService.get('user_id'))
                         .success(function (data, status, headers, config) {
                             $scope.treeNodes = data.tree;
                             
                         });*/
                    $scope.refresh_tree();
                    $scope.modShHd = 0;
                    $mdDialog.hide();//sourabh 161224
                    /* } else {
                         $scope.alertMessage = data.message;

                     }*/
                });
                //***************  
                /*}*/

            };
            /*end of lock and unlock Batting*/
            $scope.lockUserModel = function (btnClicked, id, loc_value) {

                $scope.buttonClicked4 = btnClicked;
                $scope.lockUser = !$scope.lockUser;
                if (loc_value == 0) {
                    $scope.content = "Would you like to Unlock";
                } else {
                    $scope.content = "Would you like to Lock";
                }

                $scope.lockValue = loc_value;
                $scope.modShHd = 6;
            };
            /*Bharti 8-Octo start*/
            //Free chips function
            //$scope.freechips1 = false;
            $scope.buttonClicked = "";
            $scope.freechips = function (btnClicked, id, parantId, usetype)//sourabh 161223 not in use
            {


                $scope.buttonClicked4 = btnClicked;
                $scope.freechips1 = !$scope.freechips1;
                $scope.modShHd = 4;
                /*start The Parant ChipData*/
                if (usetype == 0) {
                    $http.get('Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {

                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = 9999999;
                        $scope.userType = usetype;

                    });
                } else {
                    $http.get('Chipscntrl/getChipDataById/' + parantId).success(function (data, status, headers, config) {

                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = $scope.pFreeChipVal;
                        $scope.userType = usetype;

                    });
                    //Get User Value
                    $http.get('Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {

                        $scope.cipsData = data.betLibility;
                        $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;

                    });
                    //End of User Value
                }


                /*End of Get Chip Value*/

            };
            //End Free chipse function
            $scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType) {

                var userName = sessionService.get('slctUseName');
                var ParantName = sessionService.get('slctParantName');
                var LoginId = sessionService.get('user_id');
                var AdminType = sessionService.get('type');
                //
                var ChipData = {
                    UserID: UserID,
                    RefID: Chip.Ref,
                    CrDr: Type,
                    Chips: Chip.ChipVal,
                    IsFree: Free,
                    UserName: userName,
                    ParantName: ParantName,
                    LoginId: LoginId,
                    userType: userType

                }
                var PrntChipVal = 0;//sourabh 161224
                if (Chip.pFreeChipVal != angular.isUndefinedOrNull)//sourabh 161224
                    PrntChipVal = parseInt(Chip.pFreeChipVal);//sourabh 161223
                var userChipVal = 0;//sourabh 161224
                if (Chip.UFreeChipVal != angular.isUndefinedOrNull)//sourabh 161224
                    userChipVal = parseInt(Chip.UFreeChipVal);//sourabh 161223
                if (AdminType == 0 && userType == 0) {
                    //Start Save Data
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                        .success(function (data) {
                            //$scope.inptfrchp="";sourabh 161219
                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $scope.modShHd = false;
                            $scope.ChipData = "";
                            $mdDialog.hide();//sourabh 161223
                            /*document.getElementById('Free_chip').value=0;
                            document.getElementById('Free_chip_Reference').value="";*/
                            $timeout(callAtTimeout, 1000);
                            function callAtTimeout() { $scope.msgShowHide = false; }

                        });
                    //End 
                }
                else if ((Type == 1) && (PrntChipVal >= Chip.ChipVal) && userType != 0) {
                    //Start Save Data
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                        .success(function (data) {

                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $scope.modShHd = false;
                            $scope.ChipData = "";
                            $mdDialog.hide();//sourabh 161223
                            /*document.getElementById('Free_chip').value="";
                            document.getElementById('Free_chip_Reference').value="";
                            */$timeout(callAtTimeout, 1000);
                            function callAtTimeout() { $scope.msgShowHide = false; }

                        });
                    //End

                } else if ((Type == 2) && (Chip.pChipBalance >= Chip.ChipVal) && userType != 0) {//userChipVal
                    //Start Save Data
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                        .success(function (data) {

                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $scope.modShHd = false;
                            $scope.ChipData = "";
                            $mdDialog.hide();//sourabh 161223
                            /*document.getElementById('Free_chip').value="";
                            document.getElementById('Free_chip_Reference').value="";
                            */$timeout(callAtTimeout, 1000);
                            function callAtTimeout() { $scope.msgShowHide = false; }

                        });
                    //End 

                } else {
                    $scope.msgShowHide = true;
                    $scope.alertMessage = "Insufficient Balance..";
                    $mdDialog.hide(); //sourabh 161224
                    $timeout(callAtTimeout, 1000);
                    function callAtTimeout() { $scope.msgShowHide = false; }
                    //alert("Please check the Balance..");  
                }

                Chip.ChipVal = 0;

                // 



            };
            /*Start*/
            //chips in function
            //$scope.chipsin1 = false;
            $scope.buttonClicked = "";
            $scope.chipsin = function (btnClicked, id) //sourabh 161224 not in use add angular js
            {

                $scope.buttonClicked4 = btnClicked;
                $scope.chipsin1 = !$scope.chipsin1;
                $scope.modShHd = 5;

            };
            //End of chipse in function
            //lock user in function
            //$scope.lockbetting1 = false;
            $scope.buttonClicked = "";
            $scope.lockbetting = function (btnClicked, id, loc_value) //sourabh 161224 not in use add angular popup
            {

                $scope.buttonClicked4 = btnClicked;
                $scope.lockbetting1 = !$scope.lockbetting1;
                $scope.modShHd = 7;
                if (loc_value == 1) {
                    $scope.btingcontent = "Would you like to lock Betting";
                } else {
                    $scope.btingcontent = "Would you like to Unlock Betting";
                }

            };
            //End Of lock user in function
            /*End*/
            //Close User  in function
            //$scope.closeuser1 = false;
            $scope.buttonClicked = "";
            $scope.closeuser = function (btnClicked, id, acValue)//sourabh 161224 not in use add angular popup
            {

                $scope.buttonClicked4 = btnClicked;
                $scope.closeuser1 = !$scope.closeuser1;
                $scope.modShHd = 8;
                if (acValue == 0) {
                    $scope.acContent = "Would you like to Open Acc";
                } else {
                    $scope.acContent = "Would you like to Close Acc";
                }

            };
            ///*End*/Close User  in function
            /*change Password Model*/
            //$scope.changePasswordModel = false;
            $scope.buttonClicked = "";
            $scope.changeUserPassword = function (btnClicked, id)//sourabh new 161222 not in use
            {

                $scope.buttonClicked4 = btnClicked;
                $scope.changePasswordModel = !$scope.changePasswordModel;
                $scope.modShHd = 3;

            };
            /*End of Change Password*/
            ///*Start*/  Add Acc  in function
            //$scope.addacc1 = false;
            $scope.getCssVal = function (val) {
                switch (val) {
                    case "1": case "6423": return "footb-ic"; break;
                    case "2": return "tennis-ic"; break;
                    case "7": return "horse-ic"; break;
                    case "4": return "crick-ic"; break;
                    default: return "game-ic"; break;
                }
            }
            $scope.buttonClicked = "";
            $scope.addacc = function (btnClicked, id) {

                $scope.buttonClicked4 = btnClicked;
                $scope.addacc1 = !$scope.addacc1;
                $scope.modShHd = 9;

            };
            ///*End*/Add Acc  in function
            /*Bharti 8-Octo END*/
            /*End Of Lock User*/
            /*Change User Dealer and Master Password*/
            $scope.changeUserPasswordSubmit = function (user, node) {


                var userId = user.userId;//sourabh new 161222
                var oldPassword = user.oldPassword;//sourabh new 161222
                var newPassword = user.newPassword;//sourabh new 161222
                var cnfnewPassword = user.cnfnewPassword;//sourabh new 161222
                /*var  userType_id = user.userType_id,
                var  SltUsrType_id = user.SltUsrType_id,*/
                var passwordData = {
                    userName: node.name,
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                    userId: userId,
                    userType_id: user.userType_id,
                    SltUsrType_id: user.SltUsrType_id,
                }

                if (newPassword == cnfnewPassword) {
                    //***************
                    $http({
                        method: 'POST',
                        url: 'Createmastercontroller/changekPassword/',
                        data: passwordData, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function (data) {
                        if (data.error == 0) {

                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $timeout(callAtTimeout, 6000);
                            function callAtTimeout() { $scope.modShHd == 0; $scope.msgShowHide = false; }
                            $mdDialog.hide();
                        } else {
                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $timeout(callAtTimeout, 3000);
                            function callAtTimeout() {
                                $scope.modShHd == 0;
                                $scope.msgShowHide = false;
                            }
                            $mdDialog.hide();

                        }
                    });
                    //***************  
                } else {
                    //$scope.validatePass = "Confirm Password Not Match";
                    $scope.alertMessage = "Confirm Password Not Match";
                    $scope.msgShowHide = true;
                    $timeout(callAtTimeout, 3000);
                    function callAtTimeout() {
                        $scope.modShHd == 0;
                        $scope.msgShowHide = false;
                    }
                    $mdDialog.hide();
                }



            };
            /*End of Master Dealer and User Chnage Password*/
            // Check UserName data to php file
            $scope.checkUserName = function (username) {

                //var username=$scope.username;
                //alert(username);
                if (username.length < 4) {
                    $scope.errorMsg = "Username must be greater than Four Character";
                } else {
                    //***************
                    $http({
                        method: 'POST',
                        url: 'Createmastercontroller/CheckUserName/' + username,
                        data: username, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                        .success(function (data) {
                            if (data.error == 0) {
                                //alert('kk');
                                $scope.errorMsg = data.message;
                            } else {
                                $scope.errorMsg = data.message;
                                // alert('www');
                            }
                        });
                    //***************  
                }

            };
            // End Check UserName data to php file
            // Check partnership to php file
            $scope.checkpartnership = function (partnership) {

                //var username=$scope.username;
                //alert(username);
                if (partnership == "")
                { $scope.errorMsg1 = "Partnership should not be Blank"; }
                else if (partnership.length > 3) {
                    $scope.errorMsg1 = "Partnership should be less then 3 Character";
                } else if (parseFloat(partnership) > 100 || parseFloat(partnership) < 1) {
                    $scope.errorMsg1 = "Partnership should be between 1 and 100";
                }

            };
            // End Check partnership data to php file
            $scope.msg_show = function () {
                $scope.msgShowHide = false;
            }
            $scope.submitForm_Users = function (user, node) {
                if (user.partnership == undefined) {
                    var partnership = 0;

                } else {
                    var partnership = user.partnership;
                }
                var typeId = document.getElementById('typeId').value;
                var formData = {
                    username: user.username,
                    master_name: user.master_name,
                    password: user.password,
                    remarks: user.remarks,
                    typeId: typeId,
                    FromDate: user.dt,
                    parantId: $scope.node.id,
                    partner: user.partnership,
                    Commission: user.Commission,
                    maxProfit: user.maxProfit,
                    maxLoss: user.maxLoss,
                    maxStake: user.maxStake,
                    maxLoss: user.maxLoss,
                    sessionCommission: user.sessionCommission,
                    otherCommission: user.otherCommission,
                    betDelay: user.betDelay,
                    PntPartenerShip: node.partner
                }
                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/submitCreateMasterData/',
                    data: formData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function (data) {
                    if (data.error == 0) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $timeout(callAtTimeout, 3000);
                        function callAtTimeout() {
                            $scope.msgShowHide = false;
                        }
                        $http.get(BASE_URL + 'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
                            $scope.treeNodes = data.tree;
                            $scope.modShHd = false;
                            $scope.showModal = false;
                            document.getElementById('master_name').value = "";
                            /*document.getElementById('reg_date').value="";*/
                            document.getElementById('username').value = "";
                            document.getElementById('password').value = "";
                            document.getElementById('remarks').value = "";
                            document.getElementById('Commission').value = "";
                            try { document.getElementById('partnership').value = 0; } catch (e) { }//in user partnership not came
                            document.getElementById('maxProfit').value = 0;
                            document.getElementById('maxLoss').value = 0;
                            document.getElementById('maxStake').value = 0;
                            $scope.modShHd = 0;
                            $mdDialog.hide();
                            //return GtUserId;
                        })
                      .error(function (data, status, header, config) {
                          //alert('ddfd');
                          $scope.ResponseDetails = "Data: " + data +
                          "<br />status: " + status +
                          "<br />headers: " + jsonFilter(header) +
                          "<br />config: " + jsonFilter(config);
                      });

                    } else {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $timeout(callAtTimeout, 3000);
                        function callAtTimeout() {
                            $scope.msgShowHide = false;
                        }
                        $scope.modShHd = 0;
                        $mdDialog.hide();
                    }


                });
            };
            $(window).width(function () {
                if (window.innerWidth > 768) {
                    //alert(window.innerWidth );
                    $('.pushmenu-push').addClass('pushmenu-push-toright');
                    $('.pushmenu-left').addClass('pushmenu-open');
                }
                else {
                    //alert("In width fff");
                    $('.pushmenu-push').removeClass('pushmenu-push-toright');
                    $('.pushmenu-left').removeClass('pushmenu-open');
                }
            });
            $(window).resize(function () {
                //alert(window.innerWidth);
                if (window.innerWidth > 768) {
                    //alert("In width");
                    $('.pushmenu-push').addClass('pushmenu-push-toright');
                    $('.pushmenu-left').addClass('pushmenu-open');
                }

                else {
                    //alert("In width fff");
                    $('.pushmenu-push').removeClass('pushmenu-push-toright');
                    $('.pushmenu-left').removeClass('pushmenu-open');
                }
            });
            $(window).click(function () {
            });
            $scope.printParent = function ($event) {
                if ($event.target.localName == "span") {
                    var root = $scope;
                    var evt = $event;
                    var currentScope = angular.element($event.target).scope();
                    root.node = currentScope.node;
                    currentScope = currentScope.$parent;
                    if (root.node.id != currentScope.node.id)
                        root.currentScope1 = currentScope.node;
                    else
                        root.currentScope1 = null;
                    while (currentScope.$id !== root.$id) { currentScope = currentScope.$parent; }
                    /* */
                    $scope.setNodeToTable(root.node);
                    //root.myMenu =true;// !root.myMenu;
                    root.xPosi = evt.clientX;
                    root.yPosi = evt.clientY;

                }
            }
            $scope.dropdown124 = false;
            $scope.printParent2 = function ($event, matchInfo) {
                var root = $scope;
                var evt = $event;
                $scope.CreateFancyMatchInfo = matchInfo;
                $scope.dropdown124 = !$scope.dropdown124;
                root.xPosi = evt.clientX;
                root.yPosi = evt.clientY;
            }
            $scope.buttonClicked = "";
            $scope.viewModelToggle = function (btnClicked, id) {


                $scope.buttonClicked2 = btnClicked;
                $scope.displayViewModel = !$scope.displayViewModel;
                $scope.modShHd = 2;
                $http.get('Createmastercontroller/viewUserAc/' + id)
                    .success(function (data, status, headers, config) {

                        $scope.viewUserAc1 = data.viewUserAc1;
                        $scope.username = $scope.viewUserAc1[0].mstruserid;
                        $scope.Name = $scope.viewUserAc1[0].mstrname;
                        $scope.partnership = $scope.viewUserAc1[0].partner;
                        $scope.Commission = $scope.viewUserAc1[0].Commission;
                        $scope.maxProfit = $scope.viewUserAc1[0].lgnUserMaxProfit;
                        $scope.maxLoss = $scope.viewUserAc1[0].lgnUserMaxLoss;
                        $scope.maxStake = $scope.viewUserAc1[0].lgnUserMaxStake;
                    });


            };
            $scope.getMatchMarket = function (sportsId, matchId) {
                //alert("HI"+matchId+"/"+sportsId);
                $scope.accordion = sportsId;//sourabh 10-dec-2016
                $scope.accordionLv1 = matchId;
                $scope.MatchId = matchId;
                $scope.sportsId = sportsId;//sourabh 10-dec-2016
                //$scope.accordionLv2=0;//sourabh 10-dec-2016
                var marketData = {
                    matchId: matchId,
                    sportsId: sportsId

                }
                /*  $http.get('Geteventcntr/matchMarketLst').success(function (data, status, headers, config) {
                         $scope.treeNodes = data.getMarket;
                 });*/
                $http({ method: 'POST', url: 'Geteventcntr/matchMarketLst/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    $scope.MatchMarket = data.MatchMarket;
                    $scope.getMatchFancy = data.getMatchFancy;
                });
            }
            $scope.ShowHideAng = function (sportsId) {
                $scope.accordion = sportsId;
                $scope.sportsId = sportsId;
                $scope.accordionLv2 = 0;
                if ($scope.sessionusetype != 0) {
                    $scope.getSeriesMatch(sportsId, 0);
                    //$http.get('Geteventcntr/getMatchLst/'+sportsId+'/0')
                    //.success(function (data, status, headers, config) {
                    //    $scope.GetSeriesData = data.matchLst;
                    //});
                } else {
                    $scope.GetSeriesData = angular.isUndefinedOrNull;
                    $http.get('Geteventcntr/getSeriesLst/' + sportsId)
                    .success(function (data, status, headers, config) {
                        $scope.GetSeriesData = data.seriesLst;
                    });
                }
            }
            $scope.getSeriesMatch = function (sportsId, seriesId) {
                $scope.accordion = sportsId;
                $scope.accordionLv1 = 0;//sourabh 9-dec-2016
                $scope.accordionLv2 = seriesId;//sourabh 9-dec-2016
                $scope.seriesId = seriesId;//sourabh 9-dec-2016
                $scope.GetMatchData = angular.isUndefinedOrNull;
                $http.get('Geteventcntr/getMatchLst/' + sportsId + '/' + seriesId).success(function (data, status, headers, config) {
                    $scope.GetMatchData = data.matchLst;
                });
            }
            var UserId = sessionService.get('user_id');
            /* $http.get(BASE_URL+'Lstsavemstrcontroller/lstSaveMaster/'+sessionService.get('user_id'))
 
                 .success(function (data, status, headers, config) {
                     //$scope.tree=data.tree;sourabh 24-sep-2016
                     $scope.treeNodes = data.tree;
                     
                 })
                 .error(function (data, status, header, config) {
                     //alert('ddfd');
                     $scope.ResponseDetails = "Data: " + data +
                     "<br />status: " + status +
                     "<br />headers: " + jsonFilter(header) +
                     "<br />config: " + jsonFilter(config);
                 });*/
            $scope.refresh_tree();
            //For create shortcut to create fancy
            $scope.showCreateFancy = function (ev, type) {
                $mdDialog.show({
                    controller: 'showCreateFancyCntr',
                    templateUrl: 'app/scripts/directives/popupform/create_fancy.html',
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose: false,
                    locals: { prntScope: $scope, matchInfo: $scope.CreateFancyMatchInfo, type: type },
                })
              .then(function () {
              }, function () {
              });
            };
            $scope.createAllTypeFancy = function (formData) {
                var url = BASE_URL + "Createmastercontroller/SaveFancy";
                $http.post(url, formData).success(function (response) {
                    Dialog.autohide(response.message);
                });
            };
            $scope.sdMarketPP = function () {
                var $promise = $http.get(BASE_URL + 'Lstsavemstrcontroller/chaneMarketPPStatus/' + $scope.chkMarketPP);
                $promise.then(function (response) {
                    $scope.chkMarketPP = !$scope.chkMarketPP;
                });
            };
        }
    }
}]);
app.controller('showCreateFancyCntr', function ($scope, $mdDialog, prntScope, matchInfo, type) {
    $scope.dt = null;
    if (type == 1) {
        $scope.fancyHeaderName = "Odd Even";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 2) {
        $scope.fancyHeaderName = "Session";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 3) {
        $scope.fancyHeaderName = "Khaddal";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 4) {
        $scope.fancyHeaderName = "Last Digit";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 5) {
        $scope.fancyHeaderName = "Up Down";
        $scope.ratediff = 1;
        $scope.maxStake = 10000;
        $scope.pointDiff = 10;
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    $scope.mid = matchInfo.MstCode;
    $scope.fancyType = type;
    $scope.oddEvenFancy = function (formData) {
        var setFancyTime = document.getElementById('setFancyTime').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime }
        prntScope.createAllTypeFancy(formData1, setFancyTime);
    };
    $scope.SessionFancyForm = function (formData) {
        var setFancyTime = document.getElementById('setFancyTimeS').value;
        var inputYes = document.getElementById('inputYes').value;
        var inputNo = document.getElementById('inputNo').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, inputYes: inputYes, inputNo: inputNo }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.KhaddalFancyForm = function (formData) {
        var setFancyTime = document.getElementById('setFancyTimeK').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, fancyRange: formData.range, }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.LastDigitFancy = function (formData) {
        var setFancyTime = document.getElementById('setFancyTimeL').value;
        var liabilityLstDigit = document.getElementById('liabilityLstDigit').value;
        //debugger;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, liability: liabilityLstDigit }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.UpDownFancy = function (formData) {
        //debugger;
        var liability = document.getElementById('liability').value;
        var upDownHead = document.getElementById('upDownHead').value;
        var ratediffUpdwn = document.getElementById('ratediffUpdwn').value;
        var pointDiffUpdwn = document.getElementById('pointDiffUpdwn').value;
        var maxStakeUpdwn = document.getElementById('maxStakeUpdwn').value;
        var formData1 = { HeadName: upDownHead, mid: $scope.mid, remarks: formData.remarks, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.mytime, rateDiff: ratediffUpdwn, pointDiff: pointDiffUpdwn, MaxStake: maxStakeUpdwn, liability: liability }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.hide = function () { $mdDialog.hide(); };
});