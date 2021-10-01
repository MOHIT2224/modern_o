'use strict';
var app = angular.module('ApsilonApp');
app.directive("limitToMax", function() {
  return {
restrict: 'A',
   //     replace: true,
    link: function(scope, element, attributes) {
       var oldVal = null;
      element.on("keydown keyup", function(e) {
    if (Number(element.val()) > Number(attributes.max) &&
          e.keyCode != 46 // delete
          &&
          e.keyCode != 8 // backspace
        ) {
          e.preventDefault();
          element.val(oldVal);
oldVal = null;
        } else {

          oldVal = Number(element.val());
oldVal = null;
        }
      });
    }
  };
});
app.directive('sidebar', ['$location', '$timeout', function ($window, $http, sessionService, $timeout, get_userser) {
    return {
        templateUrl: 'directives/sidebar',
        restrict: 'E',
        replace: true,
       // controller: Matchoddscntr,
        scope: {
           // test_dir: '&',
        },
        link: function (scope, element, attrs) {            
           // Matchoddscntr.test_dir();
            scope.$on('changeSidebar_Series', function (event, data) { scope.ShowHideAng(scope.sportsId); });
            scope.$on('changeSidebar_Match', function (event, data) { scope.getSeriesMatch(data.sportsId, data.seriesId); });
            scope.$on('changeSidebar_Market', function (event, data) { scope.getMatchMarket(scope.sportsId, scope.MatchId); });

        },
        controller: ['$scope', '$http', '$timeout', '$mdDialog', 'sessionService', '$rootScope', 'get_userser', 'Dialog',function ($scope, $http, $timeout, $mdDialog, sessionService, $rootScope, get_userser, Dialog) {
            $scope.chkMarketPP = false;
            $scope.chkMarketPPF = false;
            $scope.refresh_tree = function () {
                $http.get( BASE_URL+'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
                   
			 $scope.treeNodes = data.tree;
                });
            }
            $(".myMenu1").click(function () { $(".dropdown123").show(); });
            $(document).click(function (e) {
                if (!$(e.target).hasClass("myMenu1") && $(e.target).parents(".dropdown").length === 0) { $(".dropdown123").hide(); }
                if (!$(e.target).hasClass("myMenu2") && $(e.target).parents(".dropdown").length === 0) { $scope.dropdown124 = false; }
            });
            $scope.displayScorePosition=function(fancyId,FancyType,MatchId){
                /*alert("HI");*/
                 sessionService.set('fancyId', fancyId);
                 sessionService.set('FancyType', FancyType);
                 sessionService.set('MatchId', MatchId);
            }

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
                if(uname!=angular.isUndefinedOrNull && uname!="")
                {
                    var master = $scope.treeNodes[0].children;
                    ////master///
                    if(master!=angular.isUndefinedOrNull) {
                        for (var j = 0; j < master.length; j++) {
                            if (master[j].name.indexOf(uname) >-1) {
                                //master[j].collapsed=false;
                                tempArray.push(master[j]);
                                $scope.treeNodes = tempArray;
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
                }
                else
                {
                    $scope.treeNodes=tempTree;
                }
                //$scope.travers('anshul_client',$scope.treeNodes[0].children)
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

var t = false

$('ff').focus(function () {
    var $this = $(this)
    
    t = setInterval(

    function () {
        if (($this.val() < 1 || $this.val() > 2) && $this.val().length != 0) {
            if ($this.val() < 1) {
                $this.val(1)
            }

            if ($this.val() > 2) {
                $this.val(2)
            }
          
        }
    }, 50)
})


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
                    if (username.length < 4) {
                        $scope.errorMsg = "Username must be greater than Four Character";
                    }
                    else {
                        $http({
                            method: 'POST',
                            url: 'Createmastercontroller/CheckUserName/' + username, data: username, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
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
            $scope.showViewSetting = function (node, currentScope1) {

                $scope.vcNode = node;
                $scope.vcCurrentScope1 = currentScope1;

                $scope.showViewAccountForm();
            };
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
                        set_timeout: node.set_timeout,
                        parantId: currentScope1.id,
                        InPlayStack: InPlayStack,
                        HelperID: sessionService.get('HelperID')
                    }
                    prntScope.UpdateViewAccount(userInfo, node);
                }
                $scope.hide = function () { $mdDialog.hide(); };
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
            $scope.showFreeChips = function (node, currentScope1) {
                $mdDialog.show({
                    controller: showFreeChipsController,
                    templateUrl: 'app/scripts/directives/popupform/free_chips.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            $scope.showSettlement = function (node, currentScope1) {
                $mdDialog.show({
                    controller: showSettlementController,
                    templateUrl: 'app/scripts/directives/popupform/satelment.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showSettlementController($scope, $mdDialog, prntScope, node, currentScope1) //sourabh 
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
                        $scope.pchips = $scope.cipsData[0].Chip;
                    });
                }
                $scope.MainChipsSubmit = function (Chip, Type, UserID, Free, userType) {
                    Chip.pFreeChipVal = $scope.pFreeChipVal;
                    Chip.UFreeChipVal = $scope.UFreeChipVal;
                    Chip.pChipBalance = $scope.pChipBalance;
                    prntScope.MainChipsSubmit(Chip, Type, UserID, Free, userType);
                    Chip.ChipVal = "";
                    Chip.Ref = "";
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
            $scope.showLockUser1 = function (node) {
                $mdDialog.show({
                    controller: showLockUser1Controller,
                    templateUrl: 'app/scripts/directives/popupform/lock_user.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function showLockUser1Controller($scope, $mdDialog, prntScope, node) {
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
            $scope.showLockBetting = function (node) {
                $mdDialog.show({
                    controller: showLockBettingController,
                    templateUrl: 'app/scripts/directives/popupform/lock_betting.html?var='+Math.random(),
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
            $scope.showCloseAcc = function (node) {
                $mdDialog.show({
                    controller: showCloseAccController,
                    templateUrl: 'app/scripts/directives/popupform/close_account.html?var='+Math.random(),
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
		$scope.sessionusetype = sessionService.get('type');
                switch ($scope.sessionusetype) {
                    case "0": return "dashboard.Home"; break;
                    case "1": return "dashboard.Masterdashboard"; break;
                    case "2": return "dashboard.Dealerdashboard"; break;
                    case "3": return "dashboard.Userdashboard"; break;
                }
            }
            $http.get( BASE_URL+'Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
                $scope.sprtData = data.sportData;
            }).error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
            });
            $scope.message = "";
            $scope.setNodeToTable = function (node) {
                $rootScope.$broadcast('test_dir', { userData: node });
                var usertypename = '';
                $scope.tblNodeName = node.name;
                sessionService.set('set_timeout', node.set_timeout);
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + node.id).success(function (data, status, headers, config) {
                    $scope.cipsData = data.betLibility;
                    if ($scope.cipsData[0] != angular.isUndefinedOrNull) {
                        sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
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
            $scope.getValColor = function (val) {//7-oct-2016
                if (val > 0)
                    return 'color:#1ed61e';
                else
                    return 'color:#ff0000';
            }
            $scope.sumOfValue = function (ChipInOut, FreeChips, Liability) {//7-oct-2016
                $scope.wallet = parseFloat(ChipInOut) + parseFloat(FreeChips) + parseFloat(Liability);
            }
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
            $scope.UpdateUserLock = function (node) {
                var userId = node.id;
                var lockVal = node.mstrlock;
                if (lockVal == 0) {
                    var lock = 1;
                }
                else {
                    var lock = 0;
                }
                var lockUserData = {
                    userName: node.name,
                    userType: node.usetype,
                    userId: node.id,
                    lockVal: lock,
                    loginUserID: sessionService.get('user_id'),
                    HelperID: sessionService.get('HelperID')
                }
                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/lockuser/',
                    data: lockUserData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                        $scope.refresh_tree();
                        $scope.modShHd = 0;
                        $mdDialog.hide();
                    }
                    else {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $mdDialog.hide();
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                    }
                });
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
                    method: 'POST', url: 'Createmastercontroller/updateUserAccountData/', data: useinfo, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
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
            $scope.UpdateAccount = function (node) {
                var userId = node.id;
                var acval = node.lgnusrCloseAc;
                if (acval == 0) {
                    var accValue = 1;
                }
                else {
                    var accValue = 0;
                }
                var accountUserData = {
                    userName: node.name,
                    userType: node.usetype,
                    userId: userId,
                    accValue: accValue,
                    HelperID: sessionService.get('HelperID')
                }
                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/updateUserAccount/',
                    data: accountUserData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                        $scope.refresh_tree();
                        $scope.modShHd = 0;
                        $mdDialog.hide();
                    }
                    else {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $mdDialog.hide();
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() { $scope.msgShowHide = false; }
                    }
                });
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
                    url: 'Createmastercontroller/lockuserbetting/',
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
            $scope.lockUserModel = function (btnClicked, id, loc_value) {
                $scope.buttonClicked4 = btnClicked;
                $scope.lockUser = !$scope.lockUser;
                if (loc_value == 0) {
                    $scope.content = "Would you like to Unlock";
                }
                else {
                    $scope.content = "Would you like to Lock";
                }
                $scope.lockValue = loc_value;
                $scope.modShHd = 6;
            };
            $scope.buttonClicked = "";
            $scope.freechips = function (btnClicked, id, parantId, usetype) {
                $scope.buttonClicked4 = btnClicked;
                $scope.freechips1 = !$scope.freechips1;
                $scope.modShHd = 4;
                if (usetype == 0) {
                    $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = 9999999;
                        $scope.userType = usetype;
                    });
                }
                else {
                    $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + parantId).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                        $scope.pChipINVal = $scope.cipsData[0].Chip;
                        $scope.maxValu = $scope.pFreeChipVal;
                        $scope.userType = usetype;
                    });
                    $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
                        $scope.cipsData = data.betLibility;
                        $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;
                    });
                }
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
                        url: 'Chipscntrl/SaveChip/',
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
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
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
                else if ((Type == 2) && (Chip.pChipBalance >= Chip.ChipVal) && userType != 0) {
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
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
            $scope.MainChipsSubmit = function (Chip, Type, UserID, Free, userType) {
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
                        url: 'Chipscntrl/Save_main_chip/',
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
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/Save_main_chip/',
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
                else if ((Type == 2) && (Chip.pChipBalance >= Chip.ChipVal) && userType != 0) {
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/Save_main_chip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        $scope.alertMessage = data.message;
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
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
            $scope.buttonClicked = "";
            $scope.chipsin = function (btnClicked, id) {
                $scope.buttonClicked4 = btnClicked;
                $scope.chipsin1 = !$scope.chipsin1;
                $scope.modShHd = 5;
            };
            $scope.buttonClicked = "";
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
            $scope.buttonClicked = "";
            $scope.closeuser = function (btnClicked, id, acValue) {
                $scope.buttonClicked4 = btnClicked;
                $scope.closeuser1 = !$scope.closeuser1;
                $scope.modShHd = 8;
                if (acValue == 0) {
                    $scope.acContent = "Would you like to Open Acc";
                }
                else {
                    $scope.acContent = "Would you like to Close Acc";
                }
            };
            $scope.buttonClicked = "";
            $scope.changeUserPassword = function (btnClicked, id) {
                $scope.buttonClicked4 = btnClicked;
                $scope.changePasswordModel = !$scope.changePasswordModel;
                $scope.modShHd = 3;
            };
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
                        url: 'Createmastercontroller/changekPassword/',
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
            $scope.checkUserName = function (username) {
                if (username.length < 4) {
                    $scope.errorMsg = "Username must be greater than Four Character";
                }
                else {
                    $http({
                        method: 'POST',
                        url: 'Createmastercontroller/CheckUserName/' + username,
                        data: username,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        if (data.error == 0) {
                            $scope.errorMsg = data.message;
                        }
                        else {
                            $scope.errorMsg = data.message;
                        }
                    });
                }
            };
            $scope.checkpartnership = function (partnership) {
                if (partnership == "")
                { $scope.errorMsg1 = "Partnership should not be Blank"; }
                else if (partnership.length > 3) {
                    $scope.errorMsg1 = "Partnership should be less then 3 Character";
                }
                else if (parseFloat(partnership) > 100 || parseFloat(partnership) < 1) {
                    $scope.errorMsg1 = "Partnership should be between 1 and 100";
                }
            };
            $scope.msg_show = function () {
                $scope.msgShowHide = false;
            }
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
                        maxLoss: user.maxLoss,
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
                        maxLoss: user.maxLoss,
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
                    url: 'Createmastercontroller/submitCreateMasterData/',
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
            $(window).width(function () {
                if (window.innerWidth > 768) {
                    $('.pushmenu-push').addClass('pushmenu-push-toright');
                    $('.pushmenu-left').addClass('pushmenu-open');
                }
                else {
                    $('.pushmenu-push').removeClass('pushmenu-push-toright');
                    $('.pushmenu-left').removeClass('pushmenu-open');
                }
            });
            $(window).resize(function () {
                if (window.innerWidth > 768) {
                    $('.pushmenu-push').addClass('pushmenu-push-toright');
                    $('.pushmenu-left').addClass('pushmenu-open');
                }
                else {
                    $('.pushmenu-push').removeClass('pushmenu-push-toright');
                    $('.pushmenu-left').removeClass('pushmenu-open');
                }
            });
            $(window).click(function () { });
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
                $http.get( BASE_URL+'Createmastercontroller/viewUserAc/' + id).success(function (data, status, headers, config) {
                    $scope.viewUserAc1 = data.viewUserAc1;
                    $scope.username = $scope.viewUserAc1[0].mstruserid;
                    $scope.Name = $scope.viewUserAc1[0].mstrname;
                    $scope.partnership = $scope.viewUserAc1[0].partner;
                    $scope.Commission = $scope.viewUserAc1[0].Commission;
		    $scope.OddCommission = $scope.viewUserAc1[0].Commission;
                    $scope.maxProfit = $scope.viewUserAc1[0].lgnUserMaxProfit;
                    $scope.maxLoss = $scope.viewUserAc1[0].lgnUserMaxLoss;
                    $scope.maxStake = $scope.viewUserAc1[0].lgnUserMaxStake;
                });
            };
            $scope.getMatchMarket = function (sportsId, matchId) {

                $scope.accordion = sportsId;
                $scope.accordionLv1 = matchId;
                $scope.MatchId = matchId;
                $scope.sportsId = sportsId;
                var marketData = {
                    matchId: matchId,
                    sportsId: sportsId,
                    user_id: sessionService.get('user_id')
                }
                $http({ method: 'POST', url: 'Geteventcntr/matchMarketLst/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    ////
                    $scope.MatchMarket = data.MatchMarket;
                    $scope.getMatchFancy = data.getMatchFancy;
                });
            }
           $scope.ShowHideAng = function (sportsId) {
				
				

                $scope.accordion = sportsId;
                $scope.sportsId = sportsId;
                $scope.accordionLv2 = 0;
			 
				
				
				
                if ($scope.sessionusetype != 0 || sportsId == 7 ) {
                    $scope.getSeriesMatch(sportsId, 0);
				 
					
                }
                else {
                    $scope.GetSeriesData = angular.isUndefinedOrNull;
                    $http.get( BASE_URL+'Geteventcntr/getSeriesLst/' + sportsId).success(function (data, status, headers, config) {
                        $scope.GetSeriesData = data.seriesLst;
                        $rootScope.GetSeriesData = data.seriesLst.length;
					 
                    });
                }
            }
            $scope.getSeriesMatch = function (sportsId, seriesId) {
                ////
                $scope.inPlay = [];
                $scope.upComing = [];
                $scope.accordion = sportsId;
                $scope.accordionLv1 = 0;
                $scope.accordionLv2 = seriesId;
                $scope.seriesId = seriesId;
                $scope.GetMatchData = angular.isUndefinedOrNull;
                $http.get( BASE_URL+'Geteventcntr/getMatchLst/' + sportsId + '/' + seriesId).success(function (data, status, headers, config) {
                   // //
                    $scope.GetMatchData = data.matchLst;
				
                 var date = new Date();
            var d = date.getDate();
                            if(d<10)
                                d = 0+""+d;
                 var y = date.getFullYear();
            var m = date.getMonth();
            m= m+1;
                            if(m<10)
                                m = 0+""+m;
                    //  alert(""+y+"-"+m+"-"+d+" 00:00:00");
                    var hours = date.getHours();
        if(hours<10)
            hours = 0+""+hours;
        var min = date.getMinutes();
        min = min+1;
        if(min<10)
            min = 0+""+min;
        var sec = date.getSeconds();
        if(sec<10)
            sec = 0+""+sec;
        var currentTime = new Date(""+y+"-"+m+"-"+d+" "+hours+":"+min+":"+sec+"");
                                var date = new Date(""+y+"-"+m+"-"+d+" 00:00:00");
                                //console.log("newDate : " + newDate);
                    
                        angular.forEach($scope.GetMatchData, function(value, key) {
                              console.log(key + ': ' + value);
                              var d = new Date(value.MstDate);
                                
                    //          alert("n : " + n);
                                console.log("n " + d);
                                //change By Manish (&& d < currentTime) add in if()  
                                if(date < d || date > d ){
                                    $scope.inPlay.push(value);
                                }
else if(d>currentTime)
{$scope.upComing.push(value);
              }else{}
                                console.log($scope.inPlay);
                                console.log($scope.upComing);
                            });
                });
            }
            var UserId = sessionService.get('user_id');
            $scope.refresh_tree();
            $scope.showCreateFancy = function (ev, type) {
                $mdDialog.show({
                    controller: 'showCreateFancyCntr',
                    templateUrl: 'app/scripts/directives/popupform/create_fancy.html?var='+Math.random(),
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
                    $scope.loading=true;
                    $scope.SubmitBtnDis=false;
                });
            };
            $scope.sdMarketPP = function (sportId,matchId,MarketId,FancyId,IsPlay) {
                
                var user_id=sessionService.get("user_id");
                var user_type=sessionService.get("type");
                var $promise = $http.get(BASE_URL + 'Lstsavemstrcontroller/chaneMarketPPStatus/' +user_id+'/'+matchId+'/'+MarketId+'/'+FancyId+'/'+user_type+'/'+IsPlay);
                $promise.then(function (response) {
                   // //
                     Dialog.autohide(response.data.message);
                     $scope.getMatchMarket(sportId, matchId);
                   
                });
            };
        }]
    }
}]);
app.controller('showCreateFancyCntr',['$scope', '$mdDialog', 'prntScope', 'matchInfo', 'type', function ($scope, $mdDialog, prntScope, matchInfo, type) {
    $scope.SubmitBtnDis=false;
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
    $scope.SportID = matchInfo.SportID;
    $scope.fancyType = type;
    $scope.oddEvenFancy = function (formData) {
       // //
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
       // alert("test");
        var setFancyTime = document.getElementById('setFancyTime').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1, setFancyTime);
    };
    $scope.SessionFancyForm = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var setFancyTime = document.getElementById('setFancyTimeS').value;
        var inputYes = document.getElementById('inputYes').value;
        var inputNo = document.getElementById('inputNo').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, inputYes: inputYes, inputNo: inputNo, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.KhaddalFancyForm = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var setFancyTime = document.getElementById('setFancyTimeK').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, fancyRange: formData.range, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.LastDigitFancy = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var setFancyTime = document.getElementById('setFancyTimeL').value;
        var liabilityLstDigit = document.getElementById('liabilityLstDigit').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, liability: liabilityLstDigit, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.UpDownFancy = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var liability = document.getElementById('liability').value;
        var upDownHead = document.getElementById('upDownHead').value;
        var ratediffUpdwn = document.getElementById('ratediffUpdwn').value;
        var pointDiffUpdwn = document.getElementById('pointDiffUpdwn').value;
        var maxStakeUpdwn = document.getElementById('maxStakeUpdwn').value;
        var formData1 = { HeadName: upDownHead, mid: $scope.mid, remarks: formData.remarks, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.mytime, rateDiff: ratediffUpdwn, pointDiff: pointDiffUpdwn, MaxStake: maxStakeUpdwn, liability: liability, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.hide = function () { $mdDialog.hide(); };
}]);
