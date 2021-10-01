'use strict';
angular.module('ApsilonApp').controller('ClientListcntr',['$scope','$mdDialog', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$rootScope','$window','$state','Dialog', function ($scope, $mdDialog,$http, sessionService, $timeout, deviceDetector,$filter,$rootScope,$window,$state,Dialog) {
    $scope.UserName = sessionService.get('user');
    $scope.PID = sessionService.get('user_id');
    $scope.user={};
    $scope.getDate = new Date();
    $scope.display="true";
    $scope.DealerFreeChip = sessionService.get('FreeChips');
 $rootScope.DealerFreeChip= sessionService.get('FreeChips');
    $scope.MasterAC=0;
    $scope.Reload=function(){
       //
       $window.location.reload(); 
    }
    $scope.sumOfBal=function(a,b,c){
        
        var a1=parseInt(a);
        var b1=parseInt(b);
        var c1=parseInt(c);
        return a1+b1;

    }
    var typeCall = 1;
    $scope.$on("callUserList",function(){
		$scope.GetUserList();
		typeCall=2;
	});
    $scope.calType = 1;
    $scope.GetMasterList = function () {
        if ($scope.calType == 1) {
            $scope.loading = true;
        }


        //  $scope.mstList=$timeout(function () {


        $http.get(BASE_URL + 'Lstsavemstrcontroller/getMasterById/' + sessionService.get('user_id') + '/' + sessionService.get('type')).then(function (response) {
            // $scope.loading=true;
            $scope.TotalBal = 0;
            $scope.TotalLiability = 0;
            $scope.CreditLimit = 0;
            $scope.TotalP_L = 0;
            $scope.availBal = 0;
            $scope.MasterList = response.data;
            $scope.totalitem = $scope.MasterList.length;
            $scope.currentPage = 1;
            $scope.entryLimit = 10;
            for (var i = 0; i < response.data.length; i++) {
                //
                // 	$scope.TotalBal=$scope.TotalBal+parseFloat(response.data[i].Balance);
                $scope.TotalLiability = $scope.TotalLiability + parseFloat(response.data[i].Liability);
                $scope.CreditLimit = $scope.CreditLimit + parseFloat(response.data[i].FreeChips);
                $scope.TotalP_L = $scope.TotalP_L + parseFloat(response.data[i].P_L);
                $scope.availBal = $scope.availBal + (parseFloat(response.data[i].availBal));
            }

            if ($scope.settlement != angular.isUndifinedorNull) {
                $scope.MasterAC = ($scope.TotalP_L - parseFloat($scope.settlement[0].userSettling)) + parseFloat($scope.settlement[0].parentSettling);
            }
            $scope.loading = false;
            $scope.calType = 2;
            //$scope.GetMasterList();
        });
        // },1000)

    }
    $scope.GetUserList=function(){
        if($state.current.name!='dashboard.MasterList') {
            $scope.loading = true;
            $http.get(BASE_URL + 'Lstsavemstrcontroller/GetSettlementAmt/' + sessionService.get('user_id')).then(function (response) {
                //
                $scope.settlement = response.data.settlement;
                $scope.SETTLEing = parseInt(response.data.settlement[0].userSettling) - parseInt(response.data.settlement[0].parentSettling);

            });

            $http.get(BASE_URL + 'Lstsavemstrcontroller/getDataById/' + sessionService.get('user_id') + '/' + sessionService.get('type')).then(function (response) {
                $scope.loading = false;
                $scope.TotalBal = 0;
                $scope.TotalLiability = 0;
                $scope.CreditLimit = 0;
                $scope.TotalP_L = 0;
                $scope.availBal = 0;
                $scope.UserList = response.data;
                $scope.currentPage = 1;
                $scope.entryLimit = 10;
                $scope.filteredItems = $scope.UserList.length;
                $scope.totalItems = $scope.UserList.length;
                $scope.pageChanged = function () {
                    console.log('Page changed to: ' + $scope.currentPage);
                };
                for (var i = 0; i < response.data.length; i++) {

                    $scope.TotalBal = $scope.TotalBal + parseFloat(response.data[i].Balance);
                    console.log($scope.TotalBal);
                    $scope.TotalLiability = $scope.TotalLiability + parseFloat(response.data[i].Liability);
                    $scope.CreditLimit = $scope.CreditLimit + parseFloat(response.data[i].FreeChips);
                    $scope.TotalP_L = $scope.TotalP_L + parseFloat(response.data[i].P_L);
                    $scope.availBal = $scope.availBal + (parseFloat(response.data[i].FreeChips) + parseFloat(response.data[i].P_L));
                }
                //
                if ($scope.settlement != angular.isUndefinedorNull)
                    $scope.MasterAC = ($scope.TotalP_L - (-1 * parseFloat($scope.settlement[0].userSettling))) + parseFloat($scope.settlement[0].parentSettling);
            });
            $http.get(BASE_URL + 'Lstsavemstrcontroller/GetTree/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).then(function (response) {
                response.data.tree.find(function (value, index) {
                    //
                    if (sessionService.get('user_id') == value.mstrid) {
                        $scope.OddsComm = parseInt(value.Commission);
                        $scope.user.Commission = parseInt(value.Commission);

                        //$scope.DealerFreeChip = sessionService.get('FreeChips');
                    }

                });
            });
        }
	 $rootScope.$broadcast('updateDealerBal');
        if($state.current.name=='dashboard.MasterList')
        {
            $scope.GetMasterList();
        }
    }

    $scope.GetUserList();



    /*$scope.refresh_tree = function () {
        $http.get( BASE_URL+'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {

            $scope.treeNodes = data.tree;
        });
    }
    $scope.refresh_tree();*/
   $scope.GetLiabilityById = function(id)
	{
		 $mdDialog.show({
                            clickOutsideToClose: false,
                            escapeToClose: false,
                            templateUrl: "app/views/pages/user/User_bethistry.html?var='+Math.random()",
                            locals: { prntScope: $scope },
                            fullscreen: false,
                            controller: function DialogController(prntScope) {
				
                                   var url='Betentrycntr/LiablityHistory/' + sessionService.get('user_id');
        $http.get(url).success(function (data, status, headers, config) {        
            prntScope.BetHistory = data.BetHistory;   
//console.log($scope.BetHistory );     
          // gridOptions.api.setRowData(data.BetHistory);
            //gridOptions.api.sizeColumnsToFit();
            prntScope.SumOfP_L = prntScope.sum($scope.BetHistory, 'P_L');
            prntScope.SumOfProfit = prntScope.sum($scope.BetHistory, 'Profit');
            prntScope.SumOfLiability = prntScope.sum($scope.BetHistory, 'Liability'); 
 //setRowData(data.BetHistory);
        }); 
    
                            }
                        });
	}
    $scope.checkUserName = function (username) {
        if (username.length < 4) {
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
    /*change user active and Inactive*/
    $scope.getStatus = function (user, status) {
        $scope.loading = true;
	var id=user.usecode;
        //alert(id+"hhh"+status);
        if (status == true) {
            var newStatus=1;
            var result = confirm("Are you sure want to Deactivate this User ?");
        } else {
            var result = confirm("Are you sure want to Activate this User ?");
            var newStatus=0;
        }

        if (result) {  
            $http.get( BASE_URL+'Lstsavemstrcontroller/updateMstr/' + id + '/' + newStatus ).success(function (data, status, headers, config) {
               // $scope.GetUserList();
		user.active=data.active; 
                $scope.loading = false;
            });
        }
      else{
             $scope.loading = false;
        }
    }
    /*end of change the user status*/
    $scope.submitForm_Users = function (user) {
            //
              $scope.loading=true;
                if (user.partnership == undefined) {  var partnership = 0; }
                else { var partnership = user.partnership; }
                
                    var formData = {
                        username: user.username,
                        master_name: user.master_name,
                        password: user.password,
                        remarks: user.remarks,
                        typeId: 3,
                        FromDate: user.dt,
                        parantId: sessionService.get('user_id'),
                        partner: 0,
                        Commission: user.Commission,
                        maxProfit: user.maxProfit,
                        //maxLoss: user.maxLoss,
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
                    url: BASE_URL+'Createmastercontroller/submitCreateMasterData/',
                    data: formData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function (data) {
                //
              $scope.loading=false;
                    if (data.error == 0) {
                       alert(data.message);
                       $scope.user={
                            master_name: "",
                            username: "",
                            password:"",
                            Commission: 0,
                            sessionCommission:0,
                            otherCommission:0,
                            partnership:0,
                            dt: $filter('date')($scope.getDate, "yyyy-MM-dd")
                        };
                       $scope.GetUserList();
                    }
                    else {
                        alert(data.message);
                       $scope.user={
                            master_name: "",
                            username: "",
                            password:"",
                            Commission: 0,
                            sessionCommission:0,
                            otherCommission:0,
                            partnership:0,
                            dt: $filter('date')($scope.getDate, "yyyy-MM-dd")
                        };
                       $scope.GetUserList();
                    }
                });
    };
    //chnage the stack limit sourabh 15-nov-2016
    $scope.changeStakeLimit = function (limit, usecode) {
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/updateStakeLimit/' + limit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 10;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert('Limit Updated Successfully');
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the stack limit sourabh 15-nov-2016
    $scope.UpdateMaxProfit = function (profit, usecode) {
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/UpdateMaxProfit/' + profit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 10;
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
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/UpdateMaxLoss/' + loss + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 10;
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
        $http.get( BASE_URL+'Lstsavemstrcontroller/UpdateMaxStake/' + stake + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 10;
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
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/updateCommission/' + commission + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 10;
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
            $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + UserData.usecode).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility[0];
            });
            $http.get( BASE_URL+'Geteventcntr/getUserMatchResult/' + UserData.usecode + '/' + 3).success(function (data, status, headers, config) {
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
        
            $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + $scope.PID).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility;
                $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                $scope.pChipINVal = $scope.cipsData[0].Chip;
                $scope.maxValu = $scope.pFreeChipVal;
                $scope.pWallet=$scope.cipsData[0].Balance;
                                  
            });
            $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
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
            
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + sessionService.get('user_id')).success(function (data, status, headers, config) {
                    $scope.cipsData = data.betLibility;
                    $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                    $scope.pChipINVal = $scope.cipsData[0].Chip;
                    $scope.maxValu = $scope.pFreeChipVal;
                    $scope.pWallet=$scope.cipsData[0].Balance;                   
                });
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
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
                if (Type==1 && (PrntChipVal >= Chip.ChipVal)) {
                    var fChip= parseInt(Chip.ChipVal);
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        //$scope.alertMessage = data.message;
                        alert(data.message);
			$scope.GetUserList();
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
             $scope.DealerFreeChip=$scope.DealerFreeChip - fChip;
	     $rootScope.DealerFreeChip=parseInt($rootScope.DealerFreeChip) - parseInt(fChip);
                        $scope.ChipData = "";
                        $mdDialog.hide();
                        // $timeout(callAtTimeout, 1000);
                        //function callAtTimeout() { $scope.msgShowHide = false; }
$scope.GetUserList();
                    });
                }else if (Type==2 && ( $scope.uWallet >= Chip.ChipVal)) {
                    var fChip= parseInt(Chip.ChipVal);
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Chipscntrl/SaveChip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        //$scope.alertMessage = data.message;
                        alert(data.message);
                        $scope.msgShowHide = true;
                        $scope.modShHd = false;
            $scope.DealerFreeChip=$scope.DealerFreeChip + fChip;
	    $rootScope.DealerFreeChip=parseInt($rootScope.DealerFreeChip) + parseInt(fChip);
                        $scope.ChipData = "";
                        $mdDialog.hide();
$scope.GetUserList();
                        // $timeout(callAtTimeout, 1000);
                        //function callAtTimeout() { $scope.msgShowHide = false; }
                    });
                }
                else {
                    $scope.msgShowHide = true;
                    $scope.alertMessage = "Insufficient Balance..";
                    alert("Insufficient Balance...")
                    $mdDialog.hide();
                   
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
        //
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
                        url: BASE_URL+'Createmastercontroller/changeUserPasswod/',
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
        if(node.create_no_of_child!=angular.isUndefinedOrNull)
        {
            node.create_no_of_child=parseInt(node.create_no_of_child);
        }
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
                var maxStake = useinfo.maxStake;
                var remarks = useinfo.remarks;
        useinfo.create_no_of_child=node.create_no_of_child;
                var accountUserData = {
                        userId: userId,
                        name: Name,
                        partnership: partnership,
                        maxProfit: maxProfit,
                        maxLoss: maxLoss,
                        maxStake: maxStake,
                        remarks:remarks,
                        InPlayStack: useinfo.InPlayStack,
                        set_timeout: useinfo.set_timeout,
                        create_no_of_child:useinfo.create_no_of_child
                    }
                $http({
                    method: 'POST', url: BASE_URL+'Createmastercontroller/updateUserAccountData/', data: useinfo, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
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
                $http.get( BASE_URL+'Createmastercontroller/updatePartnerShip/' + Admin + '/' + Master + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
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
                $http.get( BASE_URL+'Createmastercontroller/updatePartnerShip/' + Admin + '/' + newMas + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
                    Dialog.autohide(data1.message, 1000);

                    prntScope.showViewAccountForm();
                    prntScope.GetUserList();
                });
            }
            else {
                Dialog.autohide("Invalid PartnerShip...", 1000);
            }
        }
        
        $scope.updateCommission = function (oddsComm, sessionComm, otherComm, ID) {
            
            if (oddsComm<=100 && sessionComm<=100 && otherComm<=100) {
                $http.get( BASE_URL+'Createmastercontroller/updateCommission/' + oddsComm + '/' + sessionComm + '/' + otherComm + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {

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
              //  var maxProfit = document.getElementById('maxProfit1').value;
              ///  var maxLoss = document.getElementById('maxLoss1').value;
                var maxStake = document.getElementById('maxStake1').value;
                var remarks =  document.getElementById('remarks').value;
                var InPlayStack = document.getElementById('InPlayStack').value; 
                get_userser.partnerValue(node.parentId, function (response) {

                    $scope.tblParner = response;
                    var userInfo = {
                        id: node.usecode,
                        userId: userId,
                        Name: Name,
                        partnership: 0,
                       // maxProfit: maxProfit,
                       // maxLoss: maxLoss,
                        maxStake: maxStake,
                        userType: node.usetype,
                        PntPartenerShip: response,
                        set_timeout: node.set_timeout,
                        parantId: node.parentId,
                        remarks: remarks,
                        InPlayStack: InPlayStack,
                        HelperID: sessionService.get('HelperID'),
                        create_no_of_child:node.create_no_of_child
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
                        url: BASE_URL+'Chipscntrl/Save_main_chip/',
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
                        url: BASE_URL+'Chipscntrl/Save_main_chip/',
                        data: ChipData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                      
                         alert(data.message);
                       $scope.GetUserList();
                       $mdDialog.hide(); 
                    });
                }
                else {
                    $scope.msgShowHide = true;
                    // $scope.alertMessage = "Insufficient Balance..";
                    alert("Insufficient Balance..")
                    $scope.GetUserList();
                    $mdDialog.hide(); 
                }
                Chip.ChipVal = 0;
    };
    // Start satelmentUser code
     $scope.showSettlement = function (node,DealerFreeChip) {
        
        $mdDialog.show({
            controller: showSettlementController,
            templateUrl: 'app/scripts/directives/popupform/clear_chip_Dealer.html?var='+Math.random(),
            locals: { prntScope: $scope, node: node,DealerBal : DealerFreeChip },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    };
    function showSettlementController($scope, $mdDialog, prntScope, node,DealerBal){
        
        $scope.node = node;
        $scope.userType = 2;
        
        if(parseInt(node.P_L)>0){
            $scope.Amount=node.P_L;
            $scope.txtChip=node.P_L;
            $scope.txtType=1;
        }else{
            $scope.Amount=-1*node.P_L;
            $scope.txtChip=-1*node.P_L;
            $scope.txtType=2;
        }
      
        $scope.MainChipsSubmit = function () {
            
            if($scope.node.P_L < 0){
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + sessionService.get('user_id')).success(function (data, status, headers, config) {
                    
                    var pWallet=data.betLibility[0].Balance; 
                    if(pWallet>=$scope.txtChip){
                        var formData = {
                            UserID: $scope.node.usecode,
                            LoginID: $scope.node.parentId,
                            CrDr: $scope.txtType,
                            Chips: $scope.txtChip,
                            IsFree: 2,
                            Narration: $scope.remark==undefined ? "" : $scope.remark,
                            HelperID: sessionService.get('HelperID')
                        }
                        $http({
                            method: 'POST',
                            url: BASE_URL+'Createmastercontroller/submitClearChip/',
                            data: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        }).success(function (data) {
                            $mdDialog.hide();
                            alert(data.message);
                            prntScope.GetUserList();                        
                        });
                    }else{                
                        alert("Insufficient Balance...");
                        $mdDialog.hide();
                    }                   
                });
                
            }else{
                    var formData = {
                        UserID: $scope.node.usecode,
                        LoginID: $scope.node.parentId,
                        CrDr: $scope.txtType,
                        Chips: $scope.txtChip,
                        IsFree: 2,
                        Narration: $scope.remark==undefined ? "" : $scope.remark,
                        HelperID: sessionService.get('HelperID')
                    }
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Createmastercontroller/submitClearChip/',
                        data: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        $mdDialog.hide();
                        alert(data.message);
                        prntScope.GetUserList();                        
                    });
            }
            
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
                    url: BASE_URL+'Createmastercontroller/lockuser/',
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
    /*start code for lock Betting and unlock Betting*/
        $scope.UpdateLockBatting = function (node) {
            
            var userId = node.usecode;
            var battinglockVal = node.lgnusrlckbtng;
            if (battinglockVal == 0) {
                var lockbatting = 1;
            } else {
                var lockbatting = 0;
            }
            var lockBatingData = {
                userName: node.mstruserid,
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
                $mdDialog.hide();
                alert(data.message);
                $scope.GetUserList();
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
        function showLockBettingController($scope, $mdDialog, prntScope, node){
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
    /*end the code of lock and Unlock Betting*/
    
}]).filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            // alert(start);
            return input.slice(start);
        }
        return [];
}
    });
