'use strict';
angular.module('ApsilonApp').controller('userListCntr',['$scope','$mdDialog', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$stateParams', function ($scope, $mdDialog,$http, sessionService, $timeout, deviceDetector,$filter,$stateParams) {
    $scope.UserName = sessionService.get('user');
    $scope.PID = sessionService.get('user_id');
    $scope.user={};
    $scope.getDate = new Date();
    $scope.display="true";
    $scope.GetUserList=function(){
            $scope.loading=true;
        $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId).then(function(response) {
             $scope.loading=false;
            $scope.TotalBal=0;
            $scope.TotalLiability=0;
            $scope.UserList=response.data;
            for (var i = 0; i< response.data.length; i++) {
                
                $scope.TotalBal=$scope.TotalBal+parseFloat(response.data[i].Balance);
                $scope.TotalLiability=$scope.TotalLiability+parseFloat(response.data[i].Liability);
            }
        });
    }
    $scope.GetUserList();
    /*$scope.refresh_tree = function () {
        $http.get('Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
            
            $scope.treeNodes = data.tree;
        });
    }
    $scope.refresh_tree();*/
   
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
    /*change user active and Inactive*/
    $scope.getStatus = function (user, status) {
        //alert(id+"hhh"+status);
	 $scope.loading = true;
	var id=user.usecode;
        if (status == true) {
            var newStatus=1;
            var result = confirm("Are you sure want to Deactivate this User ?");
        } else {
            var result = confirm("Are you sure want to Activate this User ?");
            var newStatus=0;
        }

        if (result) {  
            $http.get('Lstsavemstrcontroller/updateMstr/' + id + '/' + newStatus ).success(function (data, status, headers, config) {
                //$scope.GetUserList();
		
		user.active=data.active
 		$scope.loading = false;
            });
        }
	else
	{
	 $scope.loading = false;
	}
    }
    /*end of change the user status*/
    $scope.submitForm_Users = function (user) {
                
                if (user.partnership == undefined) {  var partnership = 0; }
                else { var partnership = user.partnership; }
                
                    var formData = {
                        username: user.username,
                        master_name: user.master_name,
                        password: user.password,
                        remarks: user.remarks,
                        typeId: 3,
                        FromDate: user.dt,
                        parantId: $stateParams.DealerId,
                        partner: 0,
                        Commission: user.Commission,
                        maxProfit: user.maxProfit,
                        maxLoss: user.maxLoss,
                        maxStake: user.maxStake,
                        maxLoss: user.maxLoss,
                        sessionCommission: user.sessionCommission,
                        otherCommission: user.otherCommission,
                        betDelay: user.betDelay,
                        PntPartenerShip: 0,
                        HelperID: sessionService.get('HelperID'),
                        GngInPlayStake: user.gngInPlyStake
                    }
              
                $http({
                    method: 'POST',
                    url: 'Createmastercontroller/submitCreateMasterData/',
                    data: formData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                    if (data.error == 0) {
                       alert(data.message);
                       $scope.user={};
                       $scope.GetUserList();

                    }
                    else {
                        alert(data.message);
                        $scope.user={};
                        $mdDialog.hide();
                        $scope.GetUserList();
                    }
                });
    };
    //chnage the stack limit sourabh 15-nov-2016
    $scope.changeStakeLimit = function (limit, usecode) {
       
        $http.get('Lstsavemstrcontroller/updateStakeLimit/' + limit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
                    .success(function (data, status, headers, config) {
                        $scope.loading=false;
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 50;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert('Limit Updated Successfully');
            })
            .error(function (data, status, header, config) {
                 $scope.loading=false;
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the stack limit sourabh 15-nov-2016
    $scope.UpdateMaxProfit = function (profit, usecode) {
        
        $http.get('Lstsavemstrcontroller/UpdateMaxProfit/' + profit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 50;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert(data.message);
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the stack limit Manish 30-nov-2016
     //chnage the Max UpdateMaxLoss Manish 30-nov-2016
    $scope.UpdateMaxLoss = function (loss, usecode) {
        
        $http.get('Lstsavemstrcontroller/UpdateMaxLoss/' + loss + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 50;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
               alert(data.message);
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the UpdateMaxLost Manish 30-nov-2016
     //chnage the Max UpdateMaxStake Manish 30-nov-2016
    $scope.UpdateMaxStake = function (stake, usecode) {
        $http.get('Lstsavemstrcontroller/UpdateMaxStake/' + stake + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 50;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert(data.message);
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the UpdateMaxStake Manish 30-nov-2016
     //chnage the changeCommission Manish 25-nov-2016
    $scope.changeCommission = function (commission, usecode) {
        
        $http.get('Lstsavemstrcontroller/updateCommission/' + commission + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 50;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert('Commission Updated Successfully');
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    /*display User Details*/
    $scope.displayUserInfo=function(UserArray){
        $mdDialog.show({
            controller: displayUserDeatailsCntr,
            templateUrl: 'app/scripts/directives/popupform/userDetails.html?var='+Math.random(),
            locals: { prntScope: $scope, UserData: UserArray },
            clickOutsideToClose: true,
            fullscreen: false,
        });
    }
    function displayUserDeatailsCntr($scope, $mdDialog, prntScope, UserData, sessionService) {
            $scope.UserData=UserData;
            $http.get('Chipscntrl/getChipDataById/' + UserData.usecode).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility[0];
            });
            $http.get('Geteventcntr/getUserMatchResult/' + UserData.usecode + '/' + 3).success(function (data, status, headers, config) {
                   $scope.TeamData = data.matchRslt;
            });
            $scope.styleColor=function(value){
                if(value < 0){
                    return "RED";
                }else{
                    return "GREEN";
                }

            }
            $scope.hide = function () { $mdDialog.hide(); };
    }
    /*end of user Deatails*/
     /*start code for free chips*/
    $scope.freechips = function (id) {
      //  
            $http.get('Chipscntrl/getChipDataById/' + $stateParams.DealerId).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility;
                $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                $scope.pChipINVal = $scope.cipsData[0].Chip;
                $scope.maxValu = $scope.pFreeChipVal;
                $scope.pWallet=$scope.cipsData[0].Balance;                   
            });
            $http.get('Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility;
                $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;
		 $scope.uWallet=$scope.cipsData[0].Balance;  
            });
    };
    /*end of the code Free chips*/
    /*start code for chip In Out*/
    $scope.showChipsInOut = function (node) {
        $mdDialog.show({
            controller: showChipsInOutController,
            templateUrl: 'app/scripts/directives/popupform/chips_inoutDeader.html?var='+Math.random(),
            locals: { prntScope: $scope, node: node },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    };
    function showChipsInOutController($scope, $mdDialog, prntScope, node) {
       // 
            /*start code for free chips*/
        $scope.freechips1 = function (id) {
          //  
                $http.get('Chipscntrl/getChipDataById/' + $stateParams.DealerId).success(function (data, status, headers, config) {
                    $scope.cipsData = data.betLibility;
                    $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                    $scope.pChipINVal = $scope.cipsData[0].Chip;
                    $scope.maxValu = $scope.pFreeChipVal;
                    $scope.pWallet=$scope.cipsData[0].Balance;                   
                });
                $http.get('Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
                    $scope.cipsData = data.betLibility;
                    $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;
                    $scope.uWallet=$scope.cipsData[0].Balance;     
                });
        };
        /*end of the code Free chips*/
        $scope.node = node;
        prntScope.freechips(node.usecode);
        $scope.freechips1(node.usecode);
        $scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType) {
         //  
            prntScope.FreeChipsSubmit(Chip, Type, UserID, Free, userType,node);
            Chip.ChipVal = "";
            Chip.Ref = "";
        }
        $scope.hide = function () { $mdDialog.hide(); };
    }
    $scope.FreeChipsSubmit = function (Chip, Type, UserID, Free, userType,node) {
              
                var userName = node.usename;
                var ParantName = sessionService.get('slctUseName');
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
                if ($scope.pFreeChipVal != angular.isUndefinedOrNull)
                    PrntChipVal = parseInt($scope.pFreeChipVal);
                var userChipVal = 0;
                if ($scope.UFreeChipVal != angular.isUndefinedOrNull)
                    userChipVal = parseInt($scope.UFreeChipVal);
                if (Type==1 && (PrntChipVal >= Chip.ChipVal)) { //deposit
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        //$scope.alertMessage = data.message;
                        alert(data.message);
			$scope.GetUserList();
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
                        $mdDialog.hide();
                        // $timeout(callAtTimeout, 1000);
                        //function callAtTimeout() { $scope.msgShowHide = false; }
                    });
                }
else if(Type==2 && ( $scope.uWallet >= Chip.ChipVal))
{
          $http({
                        method: 'POST',
                        url: 'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        //$scope.alertMessage = data.message;
                        alert(data.message);
			$scope.GetUserList();
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
                        $scope.ChipData = "";
                        $mdDialog.hide();
                        // $timeout(callAtTimeout, 1000);
                        //function callAtTimeout() { $scope.msgShowHide = false; }
                    });
}
                else {
                    alert("Insufficient Balance..");
                    $scope.msgShowHide = true;
                    $scope.alertMessage = "Insufficient Balance..";
                    $mdDialog.hide();
                    $timeout(callAtTimeout, 1000);
                    function callAtTimeout() { $scope.msgShowHide = false; }
                }
                Chip.ChipVal = 0;
    };
    /*End code of chips In out*/
    /*change User Password*/
    $scope.showChangePwd = function (node) {
        $mdDialog.show({
            controller: showChangePwdController,
            templateUrl: 'app/scripts/directives/popupform/changeUserPassword.html?var='+Math.random(),
            locals: { prntScope: $scope, node: node },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    };
    function showChangePwdController($scope, $mdDialog, prntScope, node, sessionService) {
        
        /*$scope.currentScope1 = currentScope1;*/
        $scope.node = node;
        $scope.chngPgetType = sessionService.get('type');
        $scope.chngsltUType = sessionService.get('user_id');
        $scope.changeUserPasswordSubmit = function (user, node) {
            var userId = node.usecode;
            var newPassword = user.newPassword;
            var cnfnewPassword = user.cnfnewPassword;
            var uesrInfo = {
                userName: node.usename,
                userId: userId,
                newPassword: newPassword,
                cnfnewPassword: cnfnewPassword,
                userType_id: sessionService.get('type'),
                SltUsrType_id: sessionService.get('user_id')
            }
            prntScope.changeUserPasswordSubmit(uesrInfo, node);
        }
        $scope.hide = function () { $mdDialog.hide(); };
    }
    /*end of change user Password*/
    $scope.changeUserPasswordSubmit = function (user, node) {
                
                var userId = user.userId;                
                var newPassword = user.newPassword;
                var cnfnewPassword = user.cnfnewPassword;
                var passwordData = {
                    userName: node.usename,
                    newPassword: user.newPassword,
                    userId: user.userId,
                    userType_id: user.userType_id,
                    SltUsrType_id: user.SltUsrType_id,
                    HelperID: sessionService.get('HelperID')
                }
                if (newPassword == cnfnewPassword) {
                    $http({
                        method: 'POST',
                        url: 'Createmastercontroller/changeUserPasswod/',
                        data: passwordData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        if (data.error == 0) {
                            alert(data.message);                           
                            $mdDialog.hide();
                        }
                        else {
                            alert(data.message); 
                            $mdDialog.hide();
                        }
                    });
                }
                else {
                   
                    alert("Confirm Password Not Match");
                    $mdDialog.hide();
                }
    };
    /*start code edit ac of user*/
    $scope.showViewAccountForm = function () {
               // //
        $mdDialog.show({
            controller: showViewSettingController,
            templateUrl: 'app/scripts/directives/popupform/view_accountUser.html?var='+Math.random(),
            locals: { prntScope: $scope, node: $scope.vcNode },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    }
    $scope.showViewSetting = function (node) {  //currentScope1
        $scope.vcNode = node;
        //$scope.vcCurrentScope1 = currentScope1;
        $scope.showViewAccountForm();
    };
    $scope.UpdateViewAccount = function (useinfo, node) {
                
                var userId = useinfo.userId;
                var Name = useinfo.Name;
                var partnership = 0;
                try { partnership = useinfo.partnership; } catch (e) { partnership = 0; }
                var Commission = useinfo.Commission;
                var maxProfit = useinfo.maxProfit;
                var maxLoss = useinfo.maxLoss;
                var remarks =useinfo.remarks;
                var maxStake = useinfo.maxStake;
                var accountUserData = {
                        userId: userId,
                        name: Name,
                        partnership: partnership,
                        maxProfit: maxProfit,
                        maxLoss: maxLoss,
                        remarks:remarks,
                        maxStake: maxStake,
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
                        $scope.GetUserList();
                        //$scope.modShHd = 0;
                    } else {
                        Dialog.autohide(data.message);
                        //$scope.showViewAccountForm();
                    }
                });
            };
    function showViewSettingController($scope, $mdDialog, prntScope, node, Dialog,get_userser,sessionService) {
         
        $http.get('Chipscntrl/GetChipDetectById/' + node.id).success(function (data, status, headers, config) {
                    

                    $scope.DetectedVal = data.jsonData;
                    if(data.jsonData.length==0){
                        $scope.DetectedVal=0;
                    }else{
                        $scope.DetectedVal = parseFloat(data.jsonData[0].value);
                    }
                    
                });
        
        $scope.sessionusetype = sessionService.get('type');
        //$scope.currentScope1 = currentScope1;
        $scope.node = node;
        $scope.Commission = parseFloat(node.Commission);
        $scope.SessionComm = parseFloat(node.SessionComm);
        $scope.otherComm = parseFloat(node.OtherComm);
        $scope.InPlayStack = parseInt(node.InPlayStack);
        get_userser.getUserPartnerShip(node.usecode, function (response) {
            $scope.tblParner = response.data.userPrtnrShip;
            $scope.ID = response.data.userPrtnrShip[0].ID;
            $scope.TypeID = response.data.userPrtnrShip[0].TypeID;
            $scope.ParentID = response.data.userPrtnrShip[0].ParentID;
            $scope.UserID = response.data.userPrtnrShip[0].UserID;
            $scope.Admin = parseFloat(response.data.userPrtnrShip[0].Admin);
            $scope.Master = parseFloat(response.data.userPrtnrShip[0].Master);
            $scope.Dealer = parseFloat(response.data.userPrtnrShip[0].Dealer);
            $scope.Dealer_old = parseFloat(response.data.userPrtnrShip[0].Dealer);
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
           // //
            if ($scope.sessionusetype==1) {
                var Master=$scope.Dealer_old-parseInt(Dealer);
                var sumofVal = parseFloat(Admin) + parseFloat(Master) + parseFloat(Dealer)+Master;
            }else{
                var sumofVal = parseFloat(Admin) + parseFloat(Master) + parseFloat(Dealer);
            }
          
           
            if (sumofVal == 100 && Admin>=0 && Master >=0 && Dealer>=0) {
                $http.get('Createmastercontroller/updatePartnerShip/' + Admin + '/' + Master + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                    Dialog.autohide(data1.message, 1000);

                    prntScope.showViewAccountForm();
                    prntScope.GetUserList();
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
                $http.get('Createmastercontroller/updatePartnerShip/' + Admin + '/' + newMas + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                    Dialog.autohide(data1.message, 1000);

                    prntScope.showViewAccountForm();
                    prntScope.GetUserList();
                });
            }
            else {
                Dialog.autohide("Invalid PartnerShip...", 1000);
            }
        }
        
        $scope.updateCommission = function (oddsComm, sessionComm, otherComm,ID) {
            
            if (oddsComm<=100 && sessionComm<=100 && otherComm<=100) {
                $http.get('Createmastercontroller/updateCommission/' + oddsComm + '/' + sessionComm + '/' + otherComm + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {

                    //Dialog.autohide(data1.message, 1000);
                    alert(data1.message);
                    //prntScope.showViewAccountForm();
                     prntScope.GetUserList();
                });
            }else{
                alert("Invalid Commition Value");
            }
           
        }
        $scope.UpdateViewAccount = function (user, node) {
            
            
          
                var userId = document.getElementById('vewMod2ID').value;
                var Name = document.getElementById('vewMod2Name').value;
                var partnership = 0;
                //try { partnership = document.getElementById('partnership1').value; } catch (e) { partnership = 0; }
                var maxProfit = document.getElementById('maxProfit1').value;
                var maxLoss = document.getElementById('maxLoss1').value;
                var maxStake = document.getElementById('maxStake1').value;
                var remarks = document.getElementById('remarks').value;
                var InPlayStack = document.getElementById('InPlayStack').value; 
                get_userser.partnerValue(node.parentId, function (response) {
                            
                            $scope.tblParner = response;
                            var userInfo = {
                                id: node.usecode,
                                userId: userId,
                                Name: Name,
                                partnership: 0,
                                maxProfit: maxProfit,
                                maxLoss: maxLoss,
                                maxStake: maxStake,
                                remarks :remarks,
                                userType: node.usetype,
                                PntPartenerShip: response,
                                set_timeout: node.set_timeout,
                                parantId: node.parentId,
                                InPlayStack: InPlayStack,
                                HelperID: sessionService.get('HelperID')
                            }
                            prntScope.UpdateViewAccount(userInfo, node);
                });
           
            
            
            
        }
        $scope.hide = function () { $mdDialog.hide(); };
    }
    /*end of edit ac of user*/

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
                if ((Type == 1) && (PrntChipVal >= Chip.ChipVal) && userType != 0) {
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/Save_main_chip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        alert(data.message);
                        $scope.GetUserList();
                        $mdDialog.hide(); 
                    });
                }
                else if ((Type == 2) && (Chip.pChipBalance >= Chip.ChipVal) && userType != 0) {
                    $http({
                        method: 'POST',
                        url: 'Chipscntrl/Save_main_chip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                      
                         alert(data.message);
                       $scope.GetUserList();
                       $mdDialog.hide(); 
                    });
                }
                else {
                    alert("Insufficient Balance..");
                    $scope.msgShowHide = true;
                    $scope.alertMessage = "Insufficient Balance..";
                    alert("Insufficient Balance..")
                    $scope.GetUserList();
                    $mdDialog.hide(); 
                }
                Chip.ChipVal = 0;
    };
    // Start satelmentUser code
     $scope.showSettlement = function (node) {
        
        $mdDialog.show({
            controller: showSettlementController,
            templateUrl: 'app/scripts/directives/popupform/satelmentUser.html?var='+Math.random(),
            locals: { prntScope: $scope, node: node },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    };
    function showSettlementController($scope, $mdDialog, prntScope, node){
        
        $scope.node = node;
        $scope.userType = 2;
        $http.get('Chipscntrl/getChipDataById/' + $stateParams.DealerId).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility;
            $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
            $scope.pChipINVal = $scope.cipsData[0].Chip;
            $scope.maxValu = $scope.pFreeChipVal;
            $scope.userType = node.usetype;
        });
        $http.get('Chipscntrl/getChipDataById/' + node.usecode).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility;
            $scope.UFreeChipVal = $scope.cipsData[0].FreeChip;
            $scope.pChipBalance = $scope.cipsData[0].Balance;
            $scope.pchips = $scope.cipsData[0].Chip;
        });        
        $scope.MainChipsSubmit = function (Chip, Type, UserID, Free, userType) {
            
            Chip.pFreeChipVal = $scope.pFreeChipVal;
            Chip.UFreeChipVal = $scope.UFreeChipVal;
            Chip.pChipBalance = $scope.pChipBalance;
            prntScope.MainChipsSubmit(Chip, Type, UserID, Free, userType,$scope.node);
            Chip.ChipVal = "";
            Chip.Ref = "";
        }
        $scope.hide = function () { $mdDialog.hide(); };
    }
    /*end the code of settlement*/
     /*start code for Lock and unlock the Users*/
    $scope.showLockUser1 = function (node) {
        $mdDialog.show({
            controller: showLockUser1Controller,
            templateUrl: 'app/scripts/directives/popupform/lock_user.html?var='+Math.random(),
            locals: { prntScope: $scope, node: node },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    };
    $scope.UpdateUserLock = function (node) {
                
                var userId = node.usecode;
                var lockVal = node.mstrlock;
                if (lockVal == 0) {
                    var lock = 1;
                }
                else {
                    var lock = 0;
                }
                var lockUserData = {
                    userName: node.mstruserid,
                    userType: 3,
                    userId: node.usecode,
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
                        $mdDialog.hide();
                        alert(data.message);
                        $scope.GetUserList();
                    }
                    else {
                        $scope.alertMessage = data.message;

                        $mdDialog.hide();
                        alert(data.message);
                        $scope.GetUserList();
                    }
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
    /*End the code of Lock and Unlock*/
    
}]);
