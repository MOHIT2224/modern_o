app.controller('Editfancycntr', function ($scope, $rootScope, $http, $stateParams, Dialog, $mdDialog, $location,get_userser,sessionService,Base64,$timeout,$interval,$state) {
    if ($rootScope.HelperAllRights != angular.isUndefinedOrNull && $rootScope.HelperAllRights.EditFancy == 0) { $location.path('/dashboard/Home'); }
    $scope.MatchName = $stateParams.MatchName;
    $scope.IsIndianVal = $stateParams.is_indian_fancy;


    $scope.rangeLimit = 10;
    $scope.IsIndian = $stateParams.is_indian_fancy == 1 ? true : false;
    $scope.IsToggle=$stateParams.fancy_mode == 'M' ? false : true;
    $scope.ToggleTitle = $stateParams.fancy_mode == 'M' ? 'Manual' : 'Auto';

var authdata = Base64.encode(sessionService.get('user') + ':'+ sessionService.get('lgPassword'));
$http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;


    $scope.getadminIndianSession = function(){
        $scope.fancyTimeOut=$timeout(function(){
            if($state.current.name=="dashboard.Editfancycntr")
            {
                if($scope.IsIndianVal == 1){
                    $http.get( BASE_URL+'Apicontroller/adminIndianSession/'+$stateParams.mFancyId + '/' +$stateParams.MarketId ).success(function (data, status, headers, config) {
                        $scope.GetindianfancysessionData = data.data;
                        $scope.getadminIndianSession();
                    });
                }

            }
        },2000)


    }
var interval;
if($scope.IsIndianVal == 1)
$scope.getadminIndianSession();


    // $scope.setMessage = function (msgChk) {
    //     if (msgChk == 1) {
    //         var message = "Wicket Down";
    //     }
    //     else if (msgChk == 2) {
    //         var message = "Decision Pending";
    //     }
    //     else if (msgChk == 3) {
    //         var message = $scope.otherTxtMsg;
    //     }
    //     var FancyID = parseInt($stateParams.FancyID);
    //     var TypeId = parseInt($stateParams.TypeID);
    //     var formData = { FancyId: FancyID, TypeId: TypeId, message: message };
    //     var url = BASE_URL + "Lstsavemstrcontroller/setFancyMsg/";
    //     $http.post(url, formData).success(function (response) {
    //         $scope.get_fancyData();
    //     });
    // }


    $scope.callToggle = function(){
		$scope.IsToggle = !$scope.IsToggle;
		$scope.ToggleTitle = $scope.IsToggle == false ? 'Manual' : 'Auto';
		if($scope.IsToggle)
		{	$scope.fancy_mode = 'A';
			$scope.UpdateFancyMode();
			
		}
		else {
            $scope.fancy_mode = 'M';
            $scope.UpdateFancyMode();
        }
	}
    $scope.UpdateSessionLiability=function(values,type)
    {

        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        if(type=="bet")
        {
            LinkUrl ="Apiadmincontroller/save_session_bet_liablity";
            dataArray={"max_session_bet_liability":values,"ID":$stateParams.FancyID};
        }
        else
        {
            LinkUrl ="Apiadmincontroller/save_session_liablity";
            dataArray={"max_session_liability":values,"ID":$stateParams.FancyID};
        }
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
    $scope.UpdateFancyMode = function()
	{
		
		 var formData = {"FancyId":$stateParams.FancyID,"fancy_mode":$scope.fancy_mode,"MatchID":$stateParams.MatchID};
        var url = BASE_URL + "Apicontroller/updateFancyMode";
            $http.post(url, formData).success(function (response) {
			if(!response.error){}
		});
	}
    $scope.setMessage = function (msgChk) {
        
        var TypeId = 2;
        var formData = { TypeId: TypeId, message: msgChk,MatchID: $stateParams.MatchID,FancyID: $stateParams.FancyID};
        var url = BASE_URL + "Lstsavemstrcontroller/setFancyMsg/";
            $http.post(url, formData).success(function (response) {
                
                var resp="Update Success "+msgChk;
                Dialog.autohide(resp);
                $http.get('Lstsavemstrcontroller/EditMultipleFancy/' + $stateParams.MatchID).success(function (data, status, headers, config) {
                    
                    $scope.SessionData=data.FancyData;
                    //$scope.ArrayItem.items.find(function (item, itmeindex) {if (item.ItemID == $scope.Raw.aCode && item.typeId == $scope.Rawtype) { temp = 1; }});
                    $scope.SessionData.find(function (item, index) {
                        
                       if(index == IndexVal && item.active==0) {
                           
                            $scope.SessionData[index].SessInptNo = "";
                            $scope.SessionData[index].SessInptYes = "";
                            $('#inputNo'+IndexVal).focus();                     
                       }
                    });
                    
                    $('#inputNo'+IndexVal).val("");
                    $('#inputNo'+IndexVal).focus();
                    $scope.NoValume = 100;
                    $scope.YesValume = 100;
                });
            });
    }
    $scope.PressEnter = function () {
        $scope.callActiveDeactive();
    }
    $scope.get_fancyData = function () {
        $http.get('Lstsavemstrcontroller/getFancyByEdit/' + $stateParams.FancyID + '/' + $stateParams.TypeID + '/' + $stateParams.MatchID).success(function (data, status, headers, config) {
            

            $scope.SessionData = data.FancyData;
            $scope.ID = data.FancyData[0].ID;
            $scope.NoValume = parseInt(data.FancyData[0].NoValume);
            $scope.YesValume = parseInt(data.FancyData[0].YesValume);
            $scope.pointDiff = parseInt(data.FancyData[0].pointDiff);
            if (data.FancyData[0].rateDiff==angular.isUndefinedOrNull) {
            	$scope.rateDiff = 1;
            }else{
            	$scope.rateDiff = parseInt(data.FancyData[0].rateDiff);
            }
            $scope.fancy_mode=data.FancyData[0].fancy_mode;
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
            $scope.max_session_bet_liability=parseInt(data.FancyData[0].max_session_bet_liability);
            $scope.max_session_liability=parseInt(data.FancyData[0].max_session_liability);
            $scope.IsToggle = $scope.fancy_mode == 'A' ? true : false;
            $scope.ToggleTitle = $scope.fancy_mode == 'A' ? 'Auto' : 'Manual';
            if ($rootScope.active == 1) {
               // document.getElementById('inputNo').disabled = true;
              //  document.getElementById('inputNo').readOnly = true;
                $('#inputYes').focus();
            }
            else {
                $scope.example_1 = "";
                $scope.example = "";
                $rootScope.disableVal = "";
               // document.getElementById('inputNo').disabled = false;
              //  document.getElementById('inputNo').readOnly = false;
                $('#inputNo').focus();
            }
        });
    }
   // $scope.get_fancyData();
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
            NoValume: $scope.NoValume,
            YesValume: $scope.YesValume,
            pointDiff: $scope.pointDiff,
            fStatus: fStatus,
	    fancy_mode:$scope.IsToggle == false ? 'M' : 'A'
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
        $interval.cancel(interval);
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
                var formData = { status: Fancy.fStatus, matchId: $stateParams.MatchID, FancyId: parseInt(Fancy.FancyId), fancyType: $scope.TypeID, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff };
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
        var formData = { status: Fancy.fStatus, matchId: $scope.MatchID, FancyId: Fancy.FancyId, fancyType: $scope.TypeID, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff };
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
                var formData = { status: Fancy.fStatus, FancyId: parseInt(Fancy.FancyId), NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff,rateDiff:$scope.rateDiff,
                    matchId:$stateParams.MatchID,fancy_mode :Fancy.fancy_mode };
                var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
                $http.post(url, formData).success(function (response) {
                    $scope.get_fancyData();
                    $('#inputNo').focus();
                   // $scope.NoValume = 100;
                   // $scope.YesValume = 100;
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
        var formData = { status: Fancy.fStatus, FancyId: Fancy.FancyId, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff };
        var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
        $http.post(url, formData).success(function (response) {
            
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
            
            $scope.get_fancyData();
            $scope.$parent.vChkUserUpdate = $scope.$parent.ChkUserUpdate();
            $('#inputNo').focus();
            $scope.NoValume = 100;
            $scope.YesValume = 100;
        });
    }
    $scope.IsEditFancy=false;
    $scope.EditFancy=function(type)
    {
        $scope.IsEditFancy=type;
    }

    $scope.FormFancy={};
    $scope.SaveFancy=function()
    {
        if($scope.FormFancy.FancyName!=angular.isUndefinedOrNull && $scope.FormFancy.FancyName!='')
        {
            $scope.loading=true;
            var FancyID = parseInt($stateParams.FancyID);
            var formData = { HeadName: $scope.FormFancy.FancyName, id: FancyID,'MatchID':$stateParams.MatchID };
            var url = BASE_URL + "Lstsavemstrcontroller/updateFancyHeader";
            $http.post(url, formData).success(function (response) {
                if(response.error==0)
                {
                    $scope.headName=$scope.FormFancy.FancyName;
                    Dialog.autohide(response.message);
                    $scope.IsEditFancy=false;

                }
                else
                {
                    Dialog.autohide(response.message);
                }
                $scope.loading=false;
            });
        }

    }

});
app.directive('userlist', function () {
    return {
        templateUrl: 'app/scripts/directives/timeline/Session_usr_lst.html',
        restrict: 'E',
        replace: true,
        scope: { myVar: '=' },
        controller: function ($scope, $http, $stateParams, get_userser,$state,$timeout) {

            get_userser.GetAllSessFancyBet($stateParams.FancyID, function (response) {
                $scope.myVar = 0
                $scope.GetSesFancyUserLst = response;
                get_userser.GetFancyBal($stateParams.FancyID, function (response1) {
                    
                    $scope.myVar =parseInt(response1);
                });
                /*for (var i = 0; i < $scope.GetSesFancyUserLst.length; i++) {
                    $scope.myVar = parseInt($scope.myVar) + parseInt($scope.GetSesFancyUserLst[i].bet_value);
                };*/
            });
            $scope.$parent.myVar = $scope.myVar;
            $scope.$parent.ChkUserUpdate = function () {


                $scope.GetAllSessFancyBet=function(){

                    $scope.allsfbetTime=$timeout(function(){

                        if($state.current.name=="dashboard.Editfancycntr")
                        {
                            get_userser.GetAllSessFancyBet($stateParams.FancyID, function (response) {

                                $scope.GetSesFancyUserLst = response;
                                var s = $scope.GetSesFancyUserLst.length;
                                var t = response.length;
                                $scope.GetAllSessFancyBet();
                                /* if (s == t) {

                                 } else {
                                     $scope.GetSesFancyUserLst = response;
                                     $scope.myVar = 0;
                                     for (var i = 0; i < $scope.GetSesFancyUserLst.length; i++) {
                                         $scope.myVar = parseInt($scope.myVar) + parseInt($scope.GetSesFancyUserLst[i].bet_value);
                                     };
                                 }*/
                                /*  if ($scope.myVar == $scope.$parent.totalStke) {
                                      if ($scope.$parent.active != 4) {
                                          $http.get( BASE_URL+'Lstsavemstrcontroller/updateRateChangeMsg/' + $stateParams.FancyID + '/' + $stateParams.TypeID).success(function (data, status, headers, config) {
                                          });
                                      }
                                  }*/
                            });
                        }
                    },2000)

                }
                $scope.getFancyBal=function(){
                    $scope.fancyBalTime=$timeout(function() {
                        if ($state.current.name == "dashboard.Editfancycntr") {
                            get_userser.GetFancyBal($stateParams.FancyID, function (response1) {

                                $scope.myVar =parseInt(response1);
                                $scope.getFancyBal();
                            });

                        }
                    },1000)

                }
                $scope.GetAllSessFancyBet();
                $scope.getFancyBal();
                $scope.deleteRecord = function (betId, userId,MarketId) {
                    var result = confirm("Are you sure want to delete Records ");
                    if (result) {
                        $http.get('Betentrycntr/deleteGetbettingmat/' + betId + '/' + userId+'/'+MarketId).success(function (data, status, headers, config) {
                            alert(data.message);
                        });

                    }

                }
                get_userser.GetFancyBal($stateParams.FancyID, function (response1) {
                   
                    $scope.myVar =parseInt(response1);
                });
            }
            $scope.$parent.vChkUserUpdate = $scope.$parent.ChkUserUpdate();//170304
            $scope.$on("$destroy", function (event) {
                clearInterval($scope.$parent.vChkUserUpdate);
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
