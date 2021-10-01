'use strict';
angular.module('ApsilonApp').controller('AddSubAdminCntr',['$scope','$mdDialog', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$stateParams','Dialog','Base64', function ($scope, $mdDialog,$http, sessionService, $timeout, deviceDetector,$filter,$stateParams,Dialog,Base64) {
    $scope.UserName = sessionService.get('user');
    $scope.PID = sessionService.get('user_id');
    $scope.user={};
    $scope.options={};
    $scope.options1={};
    $scope.user.ID=0;
    $scope.getDate = new Date();
    $scope.display="true";
    $scope.currentPage = 1;
    $scope.entryLimit = 40;
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
    $scope.GetUserList=function(){
        $scope.loading=true;
        $http.get(BASE_URL+'CreateSubAdmincontroller/getSubadmin').then(function(response) {
            $scope.loading=false;

            $scope.UserList=response.data.data;

        });
    }
    //$scope.GetUserList();
    $scope.RoleList=[];
    $scope.RoleTypeList=[];
    $scope.Reset=function()
    {
        $scope.SubAdminRole=[];
        $scope.user={};
        $scope.options1={};
        $scope.options={};
        $scope.RoleList=$scope.RoleList;
        $scope.RoleTypeList=$scope.RoleTypeList;

    }
    $scope.GetUserRole=function()
    {
        $http.get(BASE_URL+'UserRightsCntr/roleList').then(function(response)        {


            if(response.data.error==0){
                $scope.RoleList=response.data.data;
            }


        });
    }
    $scope.IsEdit=false;
    $scope.showRoleSetting=function(role)
    {

        $scope.loading = true;
        $http.get(BASE_URL+'UserRightsCntr/getHelperRightsById/'+role.ID).then(function(response)        {
            $scope.loading = false;

            if(response.data.error==0){
                if(response.data.data.roleList.length>0)
                {
                    $scope.SubAdminRole=[];
                    for(var i=0;i<response.data.data.roleList.length;i++)
                    {
                        //var groupInd=$scope.RoleTypeList.findIndex(x=>x.fields.findIndex(x=>x==response.data.data.roleList[i]));
                        var groupInd = $scope.RoleTypeList.findIndex(function(o) {
                            return o.fields.some(function(e) {
                                return e.key == response.data.data.roleList[i];
                            })
                        });
                        if(groupInd>-1)
                        {
                            var group=$scope.RoleTypeList[groupInd];
                        }
                        $scope.toggleRole(response.data.data.roleList[i],true,group.fields,group.label);
                        $scope.options[response.data.data.roleList[i]]=true;
                    }
                    $scope.user.RoleName=response.data.data.name;
                    $scope.IsEdit=true;
                    $scope.RoleList=$scope.RoleList;
                    $scope.user.ID=response.data.data.ID;
                }
            }


        });
    }

    $scope.IsRoleChecked=function(name)
    {
        var indx= $scope.SubAdminRole.findIndex(x=>x==name);
        var ind=$scope.RoleTypeList.findIndex(x=>x==name);
        if(indx>-1)
        {
            $scope.options[ind]=true;
            return true;

        }
        else
        {
            // $scope.options[ind]=false;
            return false;
        }
    }

    $scope.GetUserRoleType=function()
    {
        $http.get(BASE_URL+'UserRightsCntr/listHelperRights').then(function(response)        {


            if(response.data!=angular.isUndefinedOrNull){
                $scope.RoleTypeList=response.data;
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

    $scope.deleteUser = function (node) {

        var result = confirm("Are you sure want to delete this Super Admin ?");


        if (result) {
            var lockUserData = {

                userId: node.mstrid,

            }
            $http({
                method: 'POST',
                url: BASE_URL+'CreateSubAdmincontroller/closeUserAccount',
                data: lockUserData,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function (data) {
                if (data.error == 0) {
                    $mdDialog.hide();
                    Dialog.autohide(data.message);
                    $scope.GetUserList();
                }
                else {
                    $scope.alertMessage = data.message;

                    $mdDialog.hide();
                    Dialog.autohide(data.message);
                    $scope.GetUserList();
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
        var formData = {
            username: user.mstruserid,
            name: user.mstrname,
            remarks: user.mstrremarks,
            mobileno:0,//user.mobileno,
            role_id:user.HelperID,
            user_id:user.mstrid,
            active:!status
        }
        if (result) {
            $http({
                method: 'POST', url: BASE_URL+'CreateSubAdmincontroller/updateAccount', data: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function (data) {
                if (data.error == 0) {
                    Dialog.autohide(data.message);
                    $scope.GetUserList();

                } else {
                    Dialog.autohide(data.message);

                }

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


        $scope.loading = true;
        var formData = {
            username: user.username,
            name: user.master_name,
            password: user.password,
            remarks: user.remarks,
            mobileno:0,//user.mobileno,
            role_id:user.role_id
        }

        $http({
            method: 'POST',
            url: BASE_URL+'CreateSubAdmincontroller/SaveSubAdmin',
            data: formData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (data) {
            if (data.error == 0) {
                Dialog.autohide(data.message);
                $scope.user.username='';
                $scope.user.master_name='';
                $scope.user.password='';
                $scope.user.remarks='';
                $scope.user.role_id=null;
                $scope.errorMsg='';
                $scope.errorMsg1='';
                $scope.GetUserList();

            }
            else {
                Dialog.autohide(data.message);

            }
            $scope.loading = false;
        });
    };
    $scope.SubAdminRole=[];
    $scope.toggleRole=function(role,isTrue,group,pindx)
    {

        if(isTrue)
        {
            $scope.SubAdminRole.push(role);
            $scope.CheckFirstItem(group,pindx);
            $scope.CheckHeading(group,pindx);
        }
        else
        {
            var indx=$scope.SubAdminRole.findIndex(x=>x==role);
            if(indx>-1){
                $scope.SubAdminRole.splice(indx,1);
                $scope.RemoveFirstItem(group,pindx);
            }
        }

    }

    $scope.CheckHeading=function(group,pindx)
    {
        var count=0;
        for(var i=0;i<group.length;i++)
        {
            var indx1=$scope.SubAdminRole.findIndex(x=>x==group[i].key);
            if(indx1>-1)
            {
                count++;
            }
        }
        if(count==group.length && count!=0)
        {
            $scope.options1[pindx]=true;
        }
    }
    $scope.toggleRoleAll=function(role,isTrue)
    {

        if(isTrue)
        {
            for(var i=0;i<role.length;i++)
            {
                var indx=$scope.SubAdminRole.findIndex(x=>x==role[i].key);
                if(indx==-1)
                {
                    $scope.SubAdminRole.push(role[i].key);
                    $scope.options[role[i].key]=true;
                }

            }


        }
        else
        {
            for(var i=0;i<role.length;i++) {
                var indx = $scope.SubAdminRole.findIndex(x => x == role[i].key);
                if (indx > -1) {
                    $scope.SubAdminRole.splice(indx, 1);
                    $scope.options[role[i].key]=false;
                }
            }
        }
    }
    $scope.CheckFirstItem=function(group,pindx)
    {

        for(var i=0;i<$scope.SubAdminRole.length;i++)
        {
            if(true)
            {
                var indx=group.findIndex(x=>x.key==$scope.SubAdminRole[i]);
                if(indx>-1)
                {
                    var indx1=$scope.SubAdminRole.findIndex(x=>x==group[0].key);
                    if(indx1==-1) {
                        $scope.SubAdminRole.push(group[0].key);
                        break;
                    }

                }

            }


        }
    }

    $scope.RemoveFirstItem=function(group,pindx)
    {
        var count=0;
        for(var i=0;i<group.length;i++)
        {
            var indx1=$scope.SubAdminRole.findIndex(x=>x==group[i].key);
            if(indx1>-1)
            {
                count++;
            }
        }
        if(count==1)
        {
            var indx=$scope.SubAdminRole.findIndex(x=>x==group[0].key);
            if(indx>-1){
                $scope.SubAdminRole.splice(indx,1);
                $scope.options1[pindx]=false;
            }
        }
        if(count!=group.length)
        {
            $scope.options1[pindx]=false;
        }
    }

    $scope.RolePakList=function()
    {

    }
    $scope.submitForm_Role = function (user) {


        $scope.loading = true;
        var formData = {"name":user.RoleName,"UserRole":$scope.SubAdminRole,"ID":user.ID}
        $http({
            method: 'POST',
            url: BASE_URL+'UserRightsCntr/saveHelperRights',
            data: formData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (data) {
            if (data.error == 0) {
                Dialog.autohide(data.message);
                $scope.GetUserRole();
                if(user.ID==0)
                {
                    $scope.Reset();
                }

                //$scope.user={};


            }
            else {
                Dialog.autohide(data.message);
                // $scope.user={};
                $mdDialog.hide();

            }
            $scope.loading = false;
        });
    };
    //chnage the stack limit sourabh 15-nov-2016
    $scope.changeStakeLimit = function (limit, usecode) {

        $http.get(BASE_URL+'Lstsavemstrcontroller/updateStakeLimit/' + limit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
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

        $http.get(BASE_URL+'Lstsavemstrcontroller/UpdateMaxProfit/' + profit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
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

        $http.get(BASE_URL+'Lstsavemstrcontroller/UpdateMaxLoss/' + loss + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
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
        $http.get(BASE_URL+'Lstsavemstrcontroller/UpdateMaxStake/' + stake + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
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

        $http.get(BASE_URL+'Lstsavemstrcontroller/updateCommission/' + commission + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get(BASE_URL+'Lstsavemstrcontroller/getDataById/'+$stateParams.DealerId+'/'+$stateParams.TypeId)
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
        $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + UserData.usecode).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility[0];
        });
        $http.get(BASE_URL+'Geteventcntr/getUserMatchResult/' + UserData.usecode + '/' + 3).success(function (data, status, headers, config) {
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
        $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + $stateParams.DealerId).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility;
            $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
            $scope.pChipINVal = $scope.cipsData[0].Chip;
            $scope.maxValu = $scope.pFreeChipVal;
            $scope.pWallet=$scope.cipsData[0].Balance;
        });
        $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
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
            $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + $stateParams.DealerId).success(function (data, status, headers, config) {
                $scope.cipsData = data.betLibility;
                $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
                $scope.pChipINVal = $scope.cipsData[0].Chip;
                $scope.maxValu = $scope.pFreeChipVal;
                $scope.pWallet=$scope.cipsData[0].Balance;
            });
            $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + id).success(function (data, status, headers, config) {
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
                url: BASE_URL+'Chipscntrl/SaveChip/',
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
                url: BASE_URL+'Chipscntrl/SaveChip/',
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

        }
        Chip.ChipVal = 0;
    };
    function callAtTimeout() { $scope.msgShowHide = false; }
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
    /*start code edit ac of user*/
    $scope.showViewAccountForm = function () {
        // //
        $mdDialog.show({
            controller: showViewSettingController,
            templateUrl: 'app/scripts/directives/popupform/view_accountSubadmin.html?var='+Math.random(),
            locals: { prntScope: $scope, node: $scope.vcNode },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    }
    $scope.showViewSetting = function (node) {  //currentScope1

        $scope.vcNode = node;
        $scope.vcNode.RoleList=$scope.RoleList;
        //$scope.vcCurrentScope1 = currentScope1;
        $scope.showViewAccountForm();
    };

    function showViewSettingController($scope, $mdDialog, prntScope, node, Dialog,get_userser,sessionService) {

        $http.get(BASE_URL+'Chipscntrl/GetChipDetectById/' + node.id).success(function (data, status, headers, config) {


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
                $http.get(BASE_URL+'Createmastercontroller/updatePartnerShip/' + Admin + '/' + Master + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
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
                $http.get(BASE_URL+'Createmastercontroller/updatePartnerShip/' + Admin + '/' + newMas + '/' + Dealer + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {
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
                $http.get(BASE_URL+'Createmastercontroller/updateCommission/' + oddsComm + '/' + sessionComm + '/' + otherComm + '/' + ID + '/' + sessionService.get('HelperID')).success(function (data1, status, headers, config) {

                    //Dialog.autohide(data1.message, 1000);
                    alert(data1.message);
                    //prntScope.showViewAccountForm();
                    prntScope.GetUserList();
                });
            }else{
                alert("Invalid Commition Value");
            }

        }

        $scope.hide = function () { $mdDialog.hide(); };

        $scope.UpdateViewAccount = function (user) {

            var formData = {
                username: user.mstruserid,
                name: user.mstrname,
                remarks: user.mstrremarks,
                mobileno:0,//user.mobileno,
                role_id:user.HelperID,
                user_id:user.mstrid
            }
            $http({
                method: 'POST', url: BASE_URL+'CreateSubAdmincontroller/updateAccount', data: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function (data) {
                if (data.error == 0) {
                    Dialog.autohide(data.message);
                    prntScope.GetUserList();

                } else {
                    Dialog.autohide(data.message);

                }
            });
        };
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
        $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + $stateParams.DealerId).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility;
            $scope.pFreeChipVal = $scope.cipsData[0].FreeChip;
            $scope.pChipINVal = $scope.cipsData[0].Chip;
            $scope.maxValu = $scope.pFreeChipVal;
            $scope.userType = node.usetype;
        });
        $http.get(BASE_URL+'Chipscntrl/getChipDataById/' + node.usecode).success(function (data, status, headers, config) {
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

}]);
