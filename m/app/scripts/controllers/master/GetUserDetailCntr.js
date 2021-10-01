'use strict';
angular.module('ApsilonApp').controller('GetDetailsCntr',['$scope','$mdDialog', '$http', 'sessionService','get_userser', '$timeout', 'deviceDetector','$filter', function ($scope, $mdDialog,$http, sessionService,get_userser, $timeout, deviceDetector,$filter) {
    $scope.UserName = sessionService.get('user');
    $scope.PID = sessionService.get('user_id');
    $scope.userType=sessionService.get('type');
    $scope.user={};
    $scope.getDate = new Date();
    $scope.display="true";
    $scope.GetUserList=function(){
        $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type')).then(function(response) {
            
            $scope.UserList=response.data;
        });
    }
    $scope.GetUserList();
    /*change user active and Inactive*/
    $scope.getStatus = function (id, status) {
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
                $scope.GetUserList();
            });
        }
    }
    /*end of change the user status*/
   
    //chnage the stack limit sourabh 15-nov-2016
    $scope.changeStakeLimit = function (limit, usecode) {
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/updateStakeLimit/' + limit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
                    .success(function (data, status, headers, config) {
                        $scope.UserList = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 50;
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
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/UpdateMaxLoss/' + loss + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
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
        $http.get( BASE_URL+'Lstsavemstrcontroller/UpdateMaxStake/' + stake + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
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
        
        $http.get( BASE_URL+'Lstsavemstrcontroller/updateCommission/' + commission + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get( BASE_URL+'Lstsavemstrcontroller/getDataById/'+sessionService.get('user_id')+'/'+sessionService.get('type'))
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
            $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + UserData.usecode).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility[0];
            });
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
                if (($scope.pWallet >= Chip.ChipVal)) {
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
                        $scope.ChipData = "";
                        $mdDialog.hide();
                        // $timeout(callAtTimeout, 1000);
                        //function callAtTimeout() { $scope.msgShowHide = false; }
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
}]);
