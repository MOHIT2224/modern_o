'use strict';
var app = angular.module('ApsilonApp');
//app.directive('header' ,['$location','$timeout', function($window, $http,sessionService ,$timeout){
app.directive('dealerheader', ['$location','$http', 'sessionService', '$timeout','$interval','get_userser','$state','speech','$rootScope', function ($window, $http, sessionService, $timeout,$interval,get_userser,$state,speech,$rootScope) {
    return {
        templateUrl: 'directives/dealerheader',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: ['$scope', '$http', 'loginService', '$mdDialog', '$window', 'Dialog', 'sessionService','$interval','get_userser','$state','speech','Base64',function ($scope, $http, loginService, $mdDialog, $window, Dialog, sessionService,$interval,get_userser,$state,speech,Base64) {
            
            $scope.$on('$locationChangeStart', function (event, next, current) { 
                //event.preventDefault(); 
            });
            
             $scope.treeAcc=0;
            $scope.txtSearch={};
            var tempTree=[];
      $scope.refresh_tree = function () {
                $http.get( BASE_URL+'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
                   
			 $scope.treeNodes = data.tree;
                    tempTree=data.tree;
                });
            }

            $scope.changePassPopup = function () {

                $mdDialog.show({
                    controller: ChangePassCntr,
                    templateUrl: 'app/scripts/directives/timeline/changePassword.html',
                    clickOutsideToClose: false,
                    fullscreen: false,
                    escapeToClose: false
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

            var changePass = sessionService.get('ChangePas');

            var userType = sessionService.get('type');
            if (userType == 0) {
            } else if (userType != 0 && changePass == 0) {
                $scope.changePassPopup();
            } else {
                /* localStorage.getItem("$_") == null ? $scope.showTermAndCondition() : "";
                 localStorage.setItem("$_", true);*/
            }
            /////////////////// Tree Search ////////////////////////
            $scope.searchTree=function(uname)
            {

                if(tempTree.length==0)
                {
                    tempTree=$scope.treeNodes;
                }
                else {
                    $scope.treeNodes=tempTree;
                }
                var tempArray=[];
                uname=$scope.txtSearch.params;
                if(uname!=angular.isUndefinedOrNull && uname!="" && uname.length>2)
                {
                    $scope.isSearch=false;
                    if (angular.lowercase($scope.treeNodes[0].name).indexOf(angular.lowercase(uname)) >-1) {

                    }
                    else
                    {
                        var master = $scope.treeNodes[0].children;
                        ////master///
                        if(master!=angular.isUndefinedOrNull) {
                            for (var j = 0; j < master.length; j++) {
                                if (angular.lowercase(master[j].name).indexOf(angular.lowercase(uname)) >-1) {
                                    //master[j].collapsed=false;
                                    tempArray.push(master[j]);
                                    $scope.treeNodes = tempArray;
                                    $scope.isSearch=true;
                                    applycss();
                                    break;
                                }
                                else {
                                    var dealer = master[j].children;
                                    if (dealer != angular.isUndefinedOrNull) {
                                        for (var d = 0; d < dealer.length; d++) {
                                            if (dealer[d].name.indexOf(uname) >-1) {
                                                // dealer[d].collapsed=false;
                                                master[j].collapsed=false;
                                                tempArray.push(master[j]);
                                                $scope.treeNodes = tempArray;
                                                $scope.isSearch=true;
                                                applycss();
                                                break;
                                            }
                                            else {
                                                var users = dealer[d].children;
                                                if (users != angular.isUndefinedOrNull) {
                                                    for (var u = 0; u < users.length; u++) {
                                                        if (users[u].name.indexOf(uname) >-1) {
                                                            // users[u].collapsed=false;
                                                            master[j].collapsed=false;
                                                            dealer[d].collapsed=false;
                                                            tempArray.push(master[j]);
                                                            $scope.treeNodes = tempArray;
                                                            $scope.isSearch=true;
                                                            applycss();
                                                            break;
                                                        }

                                                    }
                                                }

                                            }
                                        }
                                    }

                                }
                            }
                        }
                        if(!$scope.isSearch)
                        {
                            $scope.treeNodes=[];
                        }
                    }

                }
                else
                {

                    $scope.treeNodes=tempTree;
                }
                //$scope.travers('anshul_client',$scope.treeNodes[0].children)
            }

            function applycss(){
                $timeout(function(){
                    var val = $("#search12").val().toLowerCase()
                    if (val) {
                        $("ul li span").each(function (idx, obj) {

                            if ($(obj).text().toLowerCase().indexOf(val) !== -1) {
                                if($(obj).hasClass("myMenu1"))
                                    $(obj).addClass('highlight')
                                // $(obj).parent.eq(idx).addClass('highlight')
                                // $('ul').animate({
                                //   scrollTop: $('span.highlight').position().top*parseInt($('span.highlight').height())
                                // }, 600);

                            }

                            else {
                                $(obj).removeClass('highlight')
                                //$(obj).parent().closest('li').removeClass('highlight')

                            }


                        })
                    }
                },500);
            }
            ///////////////////////////////////////////////////////
	    $scope.refresh_tree();
            $scope.upBal="";
            $scope.callbal=1;
          $scope.UpdateBalance = function()
		{
            $scope.upBal = $timeout(function(){
                if(sessionService.get('user_id')!=null)
                {
			      $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + sessionService.get('user_id')).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility;
                      if($scope.cipsData !=angular.isUndefinedOrNull) {
                          sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
                          sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
                          sessionService.set('Liability', $scope.cipsData[0].Liability);
                          sessionService.set('Balance', $scope.cipsData[0].Balance);
                          sessionService.set('P_L', $scope.cipsData[0].P_L);
                          sessionService.set('IsShowTv', $scope.cipsData[0].ShowVideoTv);
                          sessionService.set('IsSettlementBtn', $scope.cipsData[0].ShowSettlementButton);
                          sessionService.set('IsShowOtherBet', $scope.cipsData[0].ShowOtherBets);
                      }
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
                      $rootScope.IsShowTv= sessionService.get('IsShowTv');
                      $rootScope.IsSettlementBtn= sessionService.get('IsSettlementBtn');
                      $rootScope.IsShowOtherBet= sessionService.get('IsShowOtherBet');
                      $scope.callbal=2;
                      if(!data.is_login) {
                          loginService.logout();
                      }
                      else
                      {
                          $scope.UpdateBalance();
                      }
                  });
                }
                else {
                    loginService.logout();
                }
            },$scope.callbal==1 ? 0 : 5000)
		}
	    $scope.UpdateBalance();
            $(".myMenu1").click(function () { $(".dropdown123").show(); });
            $(document).click(function (e) {
                if (!$(e.target).hasClass("myMenu1") && $(e.target).parents(".dropdown").length === 0) { $(".dropdown123").hide(); }
                if (!$(e.target).hasClass("myMenu2") && $(e.target).parents(".dropdown").length === 0) { $scope.dropdown124 = false; }
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
                    $scope.setNodeToTable(root.node);
                    root.xPosi = evt.clientX;
                    root.yPosi = evt.clientY;
                }
            }
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
                    templateUrl: 'app/scripts/directives/popupform/add_account.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
      $scope.showViewSetting = function (node, currentScope1) {
                
                $scope.vcNode = node;
                $scope.vcCurrentScope1 = currentScope1;

                $scope.showViewAccountForm();
            };
  $scope.showViewAccountForm = function () {
               // //
                $mdDialog.show({
                    controller: showViewSettingController,
                    templateUrl: 'app/scripts/directives/popupform/view_account.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: $scope.vcNode, currentScope1: $scope.vcCurrentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            }
     $scope.showChangePwd = function (node, currentScope1) {
                $mdDialog.show({
                    controller: showChangePwdController,
                    templateUrl: 'app/scripts/directives/popupform/change_password.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };

 $scope.showLockBetting = function (node) {

                $mdDialog.show({
                    controller: showLockBettingController,
                    templateUrl: 'app/scripts/directives/popupform/lock_betting.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
  $scope.showFreeChips = function (node, currentScope1) {

                $mdDialog.show({
                    controller: showFreeChipsController,
                    templateUrl: 'app/scripts/directives/popupform/free_chips.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };

	$scope.msg_show = function () {
                $scope.msgShowHide = false;
            }
            $scope.changeUserPasswordSubmit = function (user, node,oldPassword1) {
               // //
                var userId = user.userId;
                if (user.userType_id==user.SltUsrType_id) {
                     var oldPassword = document.getElementById('old_pass').value;
                };
               
                var newPassword = user.newPassword;
                var cnfnewPassword = user.cnfnewPassword;
                var passwordData = {
                    userName: node.name,
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                    userId: userId,
                    userType_id: user.userType_id,
                    SltUsrType_id: user.SltUsrType_id,
                    HelperID: sessionService.get('HelperID')
                }
                if (newPassword == cnfnewPassword) {
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Createmastercontroller/changekPassword/',
                        data: passwordData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        if (data.error == 0) {
                            $scope.alertMessage = data.message;
                            $scope.msgShowHide = true;
                            $timeout(callAtTimeout, 6000);
                            function callAtTimeout() { $scope.modShHd == 0; $scope.msgShowHide = false; }
                            $mdDialog.hide();
                        }
                        else {
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
                }
                else {
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

      $scope.UpdateLockBatting = function (node) {
                var userId = node.id;
                var battinglockVal = node.lgnusrlckbtng;
                if (battinglockVal == 0) {
                    var lockbatting = 1;
                } else {
                    var lockbatting = 0;
                }
                var lockBatingData = {
                    userName: node.name,
                    userType: node.usetype,
                    userId: userId,
                    lockbettingVal: lockbatting,
                    HelperID: sessionService.get('HelperID')
                }
                $http({
                    method: 'POST',
                    url: BASE_URL+'Createmastercontroller/lockuserbetting/',
                    data: lockBatingData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    $scope.alertMessage = data.message;
                    $scope.msgShowHide = true;
                    $timeout(callAtTimeout, 1000);
                    function callAtTimeout() { $scope.msgShowHide = false; $scope.showModal = false; }
                    $scope.refresh_tree();
                    $scope.modShHd = 0;
                    $mdDialog.hide();
                });
            };
      
$scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType) {
		//
                var userName = sessionService.get('slctUseName');
                var ParantName = sessionService.get('slctParantName');
                var LoginId = sessionService.get('user_id');
                var AdminType = sessionService.get('type');
                var ChipData = {
                    UserID: UserID,
                    RefID: Chip.Ref,
                    CrDr: Type,
                    Chips: Chip.ChipVal,
                    IsFree: Free,
                    UserName: userName,
                    ParantName: ParantName,
                    LoginId: LoginId,
                    userType: userType,
                    HelperID: sessionService.get('HelperID')
                }
                var PrntChipVal = 0;
                if (Chip.pFreeChipVal != angular.isUndefinedOrNull)
                    PrntChipVal = parseInt(Chip.pFreeChipVal);
                var userChipVal = 0;
                if (Chip.UFreeChipVal != angular.isUndefinedOrNull)
                    userChipVal = parseInt(Chip.UFreeChipVal);
                if (AdminType == 0 && userType == 0) {
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
                        $mdDialog.hide();
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                    });
                }
                else if ((Type == 1) && (PrntChipVal >= Chip.ChipVal) && userType != 0) {
			var chipVal = Chip.ChipVal;
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
			$rootScope.$broadcast("callUserList",{});
			
			$rootScope.DealerFreeChip=parseInt($rootScope.DealerFreeChip) - parseInt(chipVal);
                        $mdDialog.hide();
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                    });
                }
                else if ((Type == 2) && (Chip.pChipBalance >= Chip.ChipVal) && userType != 0) {
			var chipVal = Chip.ChipVal;  //withdraw
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
			$rootScope.$broadcast("callUserList",{});
			$rootScope.DealerFreeChip=parseInt($rootScope.DealerFreeChip) + parseInt(chipVal);
                        $mdDialog.hide(); $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                    });
                }
                else {
                    $scope.msgShowHide = true;
                    $scope.alertMessage = "Insufficient Balance..";
                    $mdDialog.hide();
                    $timeout(callAtTimeout, 1000);
                    function callAtTimeout() { $scope.msgShowHide = false; }
                }
                Chip.ChipVal = 0;
            };
       $scope.UpdateViewAccount = function (useinfo, node) {
                
                var userId = useinfo.userId;
                var Name = useinfo.Name;
                // var partnership = 0;
              //  try { partnership = useinfo.partnership; } catch (e) { partnership = 0; }
                var Commission = useinfo.Commission;
                var maxProfit = useinfo.maxProfit;
                var maxLoss = useinfo.maxLoss;
                var maxStake = useinfo.maxStake;
                var remarks =  useinfo.remarks;
               
                var accountUserData = {
                        userId: userId,
                        name: Name,
                        // partnership: partnership,
                        maxProfit: maxProfit,
                        maxLoss: maxLoss,
                        maxStake: maxStake,
                        remarks: remarks,
                        InPlayStack: useinfo.InPlayStack,
                        set_timeout: useinfo.set_timeout

                    }
                $http({
                    method: 'POST', url: BASE_URL+'Createmastercontroller/updateUserAccountData/', data: useinfo, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                        alert(data.message);
                        //Dialog.show('UPDATE INFO',data.message,event);
                        //$scope.showViewAccountForm();
                        $scope.refresh_tree();
                        //$scope.modShHd = 0;
                    } else {
                        Dialog.autohide(data.message);
                        //$scope.showViewAccountForm();
                    }
                });
            };
            $scope.submitForm_Users = function (user, node) {
                //
                if (user.partnership == undefined) {
                    var partnership = 0;
                }
                else {
                    var partnership = user.partnership;
                }
                var typeId = document.getElementById('typeId').value;
                if (typeId==1 || typeId==2 ) {
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
                        session_delay: user.session_delay,
                        sessionCommission: user.sessionCommission,
                        otherCommission: user.otherCommission,
                        betDelay: user.betDelay,
                        PntPartenerShip: node.partner,
                        HelperID: sessionService.get('HelperID')
                       
                    }
                }else{
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
                        session_delay: user.session_delay,
                        sessionCommission: user.sessionCommission,
                        otherCommission: user.otherCommission,
                        betDelay: user.betDelay,
                        PntPartenerShip: node.partner,
                        HelperID: sessionService.get('HelperID'),
                        GngInPlayStake: user.gngInPlyStake
                    }
                }
                $http({
                    method: 'POST',
                    url: BASE_URL+'Createmastercontroller/submitCreateMasterData/',
                    data: formData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $timeout(callAtTimeout, 3000);
                        function callAtTimeout() {
                            $scope.msgShowHide = false;
                        }
                        $http.get(BASE_URL + 'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
                            $scope.treeNodes = data.tree;
                            tempTree=data.tree;
                            $scope.modShHd = false;
                            $scope.showModal = false;
                           /* document.getElementById('master_name').value = "";
                            document.getElementById('username').value = "";
                            document.getElementById('password').value = "";
                            document.getElementById('remarks').value = "";
                            document.getElementById('Commission').value = 0;
                            try { document.getElementById('partnership').value = 0; } catch (e) { }
                            document.getElementById('maxProfit').value = 0;
                            document.getElementById('maxLoss').value = 0;
                            document.getElementById('maxStake').value = 0;*/
                            $scope.modShHd = 0;
                            $mdDialog.hide();
                        }).error(function (data, status, header, config) {
                            $scope.ResponseDetails = "Data: " + data +
                            "<br />status: " + status +
                            "<br />headers: " + jsonFilter(header) +
                            "<br />config: " + jsonFilter(config);
                        });
                    }
                    else {
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
          $scope.setNodeToTable = function (node) {
                $rootScope.$broadcast('test_dir', { userData: node });
                var usertypename = '';
                $scope.tblNodeName = node.name;
                sessionService.set('set_timeout', node.set_timeout);
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {
                    $scope.cipsData = data.betLibility;
                    if ($scope.cipsData[0] != angular.isUndefinedOrNull) {
                      //  sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
                        sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
                        sessionService.set('Liability', $scope.cipsData[0].Liability);
                        sessionService.set('Balance', $scope.cipsData[0].Balance);
                    }
                    else {
                        sessionService.set('FreeChips', 0);
                        sessionService.set('ChipInOut', 0);
                        sessionService.set('Liability', 0);
                        sessionService.set('Balance', 0);
                    }
                    $scope.$watch('sessionService', function (newVal, oldVal) {
                      //  $scope.FreeChips = sessionService.get('FreeChips');
                        $scope.ChipInOut = sessionService.get('ChipInOut');
                        $scope.Liability = sessionService.get('Liability');
                        $scope.Balance = sessionService.get('Balance');
                    });
                    $rootScope.user = node.name;//sourabh 170105
                   // $rootScope.FreeChips = sessionService.get('FreeChips');//sourabh 170105
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
                    $http.get( BASE_URL+'Chipscntrl/getParentById/' + node.id).success(function (data, status, headers, config) {
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
            $scope.RedirectToFancy=function(fancyId,TypeID,MatchID,SportID,matchName){
                $scope.setValue=fancyId;
                
                $scope.showvalue = false;
                $scope.displayFicon=false;
                if (TypeID==1) {
                    //dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})
                    $state.go("dashboard.Evenoddfancy", { 'matchId': MatchID,'FancyID':fancyId,'TypeID':TypeID,'matchName':matchName,'sportId':SportID });
                }else if(TypeID==2){
                    $state.go("dashboard.Sessionfancy", { 'matchId': MatchID,'FancyID':fancyId,'TypeID':TypeID,'matchName':matchName,'sportId':SportID });
                }else if(TypeID==3){
                   $state.go("dashboard.Khaddalfancy", { 'matchId': MatchID,'FancyID':fancyId,'TypeID':TypeID,'matchName':matchName,'sportId':SportID });
                }else if(TypeID==4){
                   $state.go("dashboard.Lastdigit", { 'matchId': MatchID,'FancyID':fancyId,'TypeID':TypeID,'matchName':matchName,'sportId':SportID });
                }else if(TypeID==5){
                    $state.go("dashboard.Updown", { 'matchId': MatchID,'FancyID':fancyId,'TypeID':TypeID,'matchName':matchName,'sportId':SportID });
                }
            }
           
            //for Marque
            $scope.ShowMessageOnHeader = function(){
		var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
		 $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
                $http.get( BASE_URL+'Betentrycntr/DisplayMsgOnHeader/').success(function (data, status, headers, config) {
                        //
                        $scope.diplayMsg = data.marqueMsg[0].Marquee;
                        
                }).error(function(data, status, headers, config){
				if(status=="412")
					{
						loginService.logout();
					}
			});
            }
            var msgHeader=function check_Fancydisplay() {
                $scope.ShowMessageOnHeader();             
            }
           // var timerGo12 = $interval(msgHeader, 30000);
            // $scope.ShowMessageOnHeader();
            //for Marque BY Manish
           
            $("#mobileDemo").click(function() {
                if($(".mainSite").hasClass('activeSideNav')){
                    $(".mainSite").removeClass('activeSideNav');
                    $(".mainSite").addClass('deactiveSideNav');
                }
                else{
                    $(".mainSite").addClass('activeSideNav');
                    $(".mainSite").removeClass('deactiveSideNav');
                }
            });
            //$scope.name = sessionStorage.HelperName;
            $scope.name =sessionService.get('user');
            $scope.usertype = sessionService.get('type');
            //alert("hi"+$scope.usertype);
            $scope.logout = function () {
                loginService.logout();
            };
            function onResize() {
                //alert("go to fun");
                // uncomment for only fire when $window.innerWidth change   
                if (scope.width !== $window.innerWidth) {
                    if ($window.width() > 768) {
                        $push.addClass('pushmenu-push-toright');
                        $puslft.addClass('pushmenu-open');
                    } else {

                        $push.removeClass('pushmenu-push-toright');
                        $puslft.removeClass('pushmenu-open');
                    }
                }
            };
            
        }]
    }

  function showAddSettingController($scope, $mdDialog, prntScope, node, sessionService, currentScope1) {

                $scope.currentScope1 = currentScope1;
                $scope.errorMsg = "";
                $scope.errorMsg1 = "";
                $scope.FreeChips = sessionService.get('FreeChips');
                $scope.ChipInOut = sessionService.get('ChipInOut');
                $scope.Liability = sessionService.get('Liability');
                $scope.Balance = sessionService.get('Balance');
                $scope.loginType = sessionService.get('type');
                $scope.mid = prntScope.mid;
                $scope.fancyType = prntScope.fancyType;
                $scope.HeadingType = prntScope.HeadingType;
                $scope.HeadingName = prntScope.HeadingName;
                $scope.HeadingTypeId = prntScope.HeadingTypeId;
                $scope.node = node;
                $scope.UserComm=parseFloat(node.Commission);
                $scope.getDate = new Date();
                $scope.v=function(password){
                   // //
                    return test(password);
                };
                var tests = [/[0-9]/, /[a-z]/, /[A-Z]/, /[^A-Z-0-9]/i];
                $scope.regex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{6,}$/;
                function test(pass){
                  if(pass == null)
                    return -1;
                  var s = 0;
                  if(pass.length<6)
                    return 0;
                  for(var i in tests)
                    if(tests[i].test(pass))
                      s++
                  $scope.PasswordLength=s;
                  return s;
                }
                $scope.checkUserName = function (username) {
                    if (username!=angular.isUndefinedOrNull && username.length < 4) {
                        $scope.errorMsg = "Username must be greater than Four Character";
                    }
                    else {
                        $http({
                            method: 'POST',
                            url: BASE_URL+'Createmastercontroller/CheckUserName/' + username, data: username, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        }).success(function (data) {
                            if (data.error == 0) {
                                $scope.errorMsg = data.message;
                                $scope.chkUser=false;
                                $scope.errorMsg1 = "";
                            } else {
                                $scope.errorMsg1 = data.message;
                                $scope.chkUser=true;
                                $scope.errorMsg = "";
                            }
                        });
                    }
                };
                $scope.submitForm_Users = function (user, node) { 
                    if($scope.chkUser==false)
                       prntScope.submitForm_Users(user, node);
                    else{
                        alert("Invalid UserName")}
                    };
                $scope.hide = function () { $mdDialog.hide(); };
            }
          
        $scope.lockbetting = function (btnClicked, id, loc_value) {
                $scope.buttonClicked4 = btnClicked;
                $scope.lockbetting1 = !$scope.lockbetting1;
                $scope.modShHd = 7;
                if (loc_value == 1) {
                    $scope.btingcontent = "Would you like to lock Betting";
                }
                else {
                    $scope.btingcontent = "Would you like to Unlock Betting";
                }
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
       function showFreeChipsController($scope, $mdDialog, prntScope, node, currentScope1) //sourabh 
            {
                $scope.node = node;
                $scope.userType = node.usetype;
                if (node.usetype == 0) {
                    $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = 9999999;
                        $scope.userType = node.usetype;
                    });
                }
                else {
                    $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + currentScope1.id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = $scope.pFreeChipVal;
                        $scope.userType = node.usetype;
                    });
                    $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipBalance = $scope.cipsData[0].Balance;
                    });
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
   $scope.showChipsInOut = function (node) {
                $mdDialog.show({
                    controller: showChipsInOutController,
                    templateUrl: 'app/scripts/directives/popupform/chips_inout.html?var='+Math.random(),
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
            function showViewSettingController($scope, $mdDialog, prntScope, node, currentScope1, Dialog) {
		
                $http.get( BASE_URL+'Chipscntrl/GetChipDetectById/' + node.id).success(function (data, status, headers, config) {
                    
                    $scope.DetectedVal = data.jsonData;
                    if(data.jsonData.length==0){
                        $scope.DetectedVal=0;
                    }else{
                        $scope.DetectedVal = parseFloat(data.jsonData[0].value);
                    }
                    
                });
		
                $scope.sessionusetype = sessionService.get('type');
                $scope.currentScope1 = currentScope1;
                $scope.node = node;

		$scope.currentScope1.Commission1 = parseFloat(node.Commission);
                $scope.Commission = parseFloat(node.Commission);
                $scope.SessionComm = parseFloat(node.SessionComm);
                $scope.otherComm = parseFloat(node.OtherComm);
                $scope.InPlayStack = parseInt(node.InPlayStack);
                get_userser.getUserPartnerShip(node.id, function (response) {

                    $scope.tblParner = response.data.userPrtnrShip;
	       // if(response.data.userPrtnrShip.length > 1){
            
                    $scope.ID = response.data.userPrtnrShip[0].ID;
                    $scope.TypeID = response.data.userPrtnrShip[0].TypeID;
                    $scope.ParentID = response.data.userPrtnrShip[0].ParentID;
                    $scope.UserID = response.data.userPrtnrShip[0].UserID;
                    $scope.Admin = parseFloat(response.data.userPrtnrShip[0].Admin);
                   
                    $scope.Master = parseFloat(response.data.userPrtnrShip[0].Master);
                    $scope.Dealer = parseFloat(response.data.userPrtnrShip[0].Dealer);
                    $scope.Dealer_old = parseFloat(response.data.userPrtnrShip[0].Dealer);
//}
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
                $scope.changePartnerShipVal=function(admin,masterVal,Dealer){
                    $scope.Dealer;
                    
                     var masterVal = document.getElementById('Dealer_new').value;
                    
                    $scope.Master=$scope.Dealer_old-parseInt(masterVal);
                    //alert(''+masterVal+'||'+e.key);

                }
                $scope.updatePartnerShip = function (Admin, Master, Dealer, ID) {
                   
                    if ($scope.sessionusetype==1) {
                        var Master=$scope.Dealer_old-parseInt(Dealer);
                        var sumofVal = parseFloat(Admin) + parseFloat(Master) + parseFloat(Dealer)+Master;
                    }else{
                        var sumofVal = parseFloat(Admin) + parseFloat(Master) + parseFloat(Dealer);
                    }
                  
                   
                    if (sumofVal == 100 && Admin>=0 && Master >=0 && Dealer>=0) {
                        $http.get( BASE_URL+'Createmastercontroller/updatePartnerShip/' + Admin + '/' + Master + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                            Dialog.autohide(data1.message, 1000);

                            prntScope.showViewAccountForm();
                            prntScope.refresh_tree();
                        });
                    }
                    else {
                        Dialog.autohide("Invalid PartnerShip...", 1000);
                    }
                }
                $scope.updatePartnerShipMaster = function (Admin, Master, Dealer, ID) {
                     
                        if(parseInt($scope.Dealer_old)>parseInt(Dealer)){
                           var MasterRem=$scope.Dealer_old-parseInt(Dealer); 
                           var newMas=parseInt(Master)+MasterRem;
                       }else{
                           var MasterRem=parseInt(Dealer)-parseInt($scope.Dealer_old);
                           var newMas=parseInt(Master)-MasterRem;
                       }
                       
                        var sumofVal = parseFloat(Admin) + parseFloat(newMas) + parseFloat(Dealer);
                   
                   
                  
                    if (sumofVal == 100 && newMas>=0) {
                        $http.get( BASE_URL+'Createmastercontroller/updatePartnerShip/' + Admin + '/' + newMas + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                            Dialog.autohide(data1.message, 1000);

                            prntScope.showViewAccountForm();
                            prntScope.refresh_tree();
                        });
                    }
                    else {
                        Dialog.autohide("Invalid PartnerShip...", 1000);
                    }
                }
                // $scope.Commission='';
                $scope.updateCommission = function (oddsComm, sessionComm, otherComm,DetectedAmt, ID,Type) {
                    
			//alert(oddsComm)
                    if (oddsComm<=100 && sessionComm<=100 && otherComm<=100) {
                        $http.get( BASE_URL+'Createmastercontroller/updateCommission/' + oddsComm + '/' + sessionComm + '/' + otherComm + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                            alert(data1.message);
                            prntScope.refresh_tree();
                        });
                        if(Type==3){
                            

                            $http.get( BASE_URL+'Chipscntrl/updateUserDetection/' + DetectedAmt + '/' + ID+'/'+Type ).success(function (data1, status, headers, config) {
                               //alert(data1);
                            });  
                        }
                        
                    }else{
                        alert("Invalid Commition Value");
                    }
                   
                }
                $scope.UpdateViewAccount = function (user, node, currentScope1) {
                    ////
                    
                    if (node.usetype==1 || node.usetype==2 || node.usetype==0) {
                        var userId = document.getElementById('vewMod2ID').value;
                        var Name = document.getElementById('vewMod2Name').value;
                        var partnership = 0;
                        //partnership = document.getElementById('partnership1').value;
                        var maxProfit = document.getElementById('maxProfit1').value;
                        var maxLoss = document.getElementById('maxLoss1').value;
                        var maxStake = 0;
                        var remarks =  document.getElementById('remarks').value;
                        var InPlayStack = 0; 
                    }else{
                        var userId = document.getElementById('vewMod2ID').value;
                        var Name = document.getElementById('vewMod2Name').value;
                        var partnership = 0;
                        //try { partnership = document.getElementById('partnership1').value; } catch (e) { partnership = 0; }
                        var maxProfit = document.getElementById('maxProfit1').value;
                        var maxLoss = document.getElementById('maxLoss1').value;
                        var maxStake = document.getElementById('maxStake1').value;
                        var remarks =  document.getElementById('remarks').value;
                       
                        var InPlayStack = document.getElementById('InPlayStack').value; 
                    }
                    
                    var userInfo = {
                        id: node.id,
                        userId: userId,
                        Name: Name,
                        // partnership: 0,
                        maxProfit: maxProfit,
                        maxLoss: maxLoss,
                        maxStake: maxStake,
                        remarks:remarks,
                        userType: node.usetype,
                        // PntPartenerShip: currentScope1.partner,
                        set_timeout: user.set_timeout,
                        session_delay: user.session_delay,
                        parantId: currentScope1.id,
                        InPlayStack: InPlayStack,
                        HelperID: sessionService.get('HelperID')
                    }
                    prntScope.UpdateViewAccount(userInfo, node);
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }
       
            function showChangePwdController($scope, $mdDialog, prntScope, node, sessionService, currentScope1) //sourabh new 161222
            {
                $scope.currentScope1 = currentScope1;
                $scope.node = node;
                $scope.user ={};
                $scope.chngPgetType = sessionService.get('type');
                $scope.chngsltUType = sessionService.get('slctUseTypeID');
                $scope.ResetGloPass = function (userId) {
                    $http.get( BASE_URL+'Createmastercontroller/GetResetPasssword/')
                        .success(function (data, status, headers, config) {
                            $scope.gtPasWrd = data.gtCnfgPswrd;
                            $http.get( BASE_URL+'Createmastercontroller/UpdateRstPasssword/' + node.id + '/' + $scope.gtPasWrd + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                                $mdDialog.show({
                                    clickOutsideToClose: false,
                                    escapeToClose: true,
                                    template: "<md-dialog style='border: rgb(225, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content><br><br><h1>" + data1.message + "</h1></md-dialog-content></md-dialog>",
                                    fullscreen: false,
                                });
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
                        SltUsrType_id: $scope.chngsltUType
                    }
                    prntScope.changeUserPasswordSubmit(uesrInfo, node);
                }
                $scope.hide = function () { $mdDialog.hide(); };
            }


       
}]);

