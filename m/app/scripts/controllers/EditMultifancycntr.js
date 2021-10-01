app.controller('Editfancycntr', function ($scope, $rootScope, $http, $stateParams, Dialog, $mdDialog, $location,get_userser,$interval) {
    if ($rootScope.HelperAllRights != angular.isUndefinedOrNull && $rootScope.HelperAllRights.EditFancy == 0) { $location.path('/dashboard/Home'); }
    //
    
    $scope.MatchName = $stateParams.MatchName;
    $scope.MatchId = $stateParams.MatchId;
    $scope.rangeLimit = 10;
    /*for testing code*/
       /* $scope.selectedPersonId = 1;
        $scope.Persons = [ 
            { 'id': 1, 'name': 'John', 'number': 5 }, 
            { 'id': 2, 'name': 'William', 'number': 1 },
            { 'id': 3, 'name': 'Pete', 'number': 7 }, 
            { 'id': 4, 'name': 'Irene', 'number': 9 }
        ];*/
        $scope.selectPerson = function(id){
            $scope.selectedPersonId = id;
        }
    /*end of for testing code*/
    $scope.setMessage = function (msgChk) {
        var TypeId = 2;
        var formData = { TypeId: TypeId, message: msgChk,MatchID: $stateParams.MatchId};
        var url = BASE_URL + "Lstsavemstrcontroller/setFancyMsg/";
            $http.post(url, formData).success(function (response) {
                var resp="Update Success "+msgChk;
               // Dialog.autohide(resp);
                $http.get( BASE_URL+'Lstsavemstrcontroller/EditMultipleFancy/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                    //
                    $scope.SessionData=data.FancyData;
                    //$scope.ArrayItem.items.find(function (item, itmeindex) {if (item.ItemID == $scope.Raw.aCode && item.typeId == $scope.Rawtype) { temp = 1; }});
                    $scope.SessionData.find(function (item, index) {
                        //
                        if(msgChk=='Ball start...'){
                            

                                $scope.SessionData[index].SessInptNo = "";
                                $scope.SessionData[index].SessInptYes = "";
                                 $('#inputNo0').focus();                   
                          
                        }else{
                            if(index == IndexVal && item.active==0) {

                                $scope.SessionData[index].SessInptNo = "";
                                $scope.SessionData[index].SessInptYes = "";
                                 $('#'+IndexVal).focus();                   
                            }  
                        }
                        
                    });
                    
                    $('#inputNo'+IndexVal).val("");
                    $('#inputNo'+IndexVal).focus();
                    $scope.NoValume = 100;
                    $scope.YesValume = 100;
                });
            });
    }
    /*$scope.PressEnter = function () {
        $scope.callActiveDeactive();
    }*/
    $scope.get_fancyData = function () {
        $http.get( BASE_URL+'Lstsavemstrcontroller/EditMultipleFancy/' + $stateParams.MatchId).success(function (data, status, headers, config) {            
            //
            $scope.SessionData = data.FancyData;
            $scope.selectedPersonId = parseInt(data.FancyData[0].ID);
            $scope.pointDiff = parseInt(data.FancyData[0].pointDiff);
            if (data.FancyData[0].rateDiff !=angular.isUndefinedOrNull) {
               $scope.rateDiff = parseInt(data.FancyData[0].rateDiff); 
           }else{
                $scope.rateDiff = 1; 
           }
        });
    }
    $scope.get_fancyData();
    var si_fancyData = $interval(callAtTimeout, 1000);

    function callAtTimeout() {
        $http.get( BASE_URL+'Lstsavemstrcontroller/EditMultipleFancy/' + $stateParams.MatchId).success(function (data, status, headers, config) {            
            //
            if($scope.SessionData.length==data.FancyData.length){

            }else{
                $scope.SessionData = data.FancyData;
                $scope.pointDiff = parseInt(data.FancyData[0].pointDiff);
                if (data.FancyData[0].rateDiff !=angular.isUndefinedOrNull) {
                    $scope.rateDiff = parseInt(data.FancyData[0].rateDiff); 
                }else{
                    $scope.rateDiff = 1; 
                } 
            }
            
        });  
    }
   

    $scope.callActiveDeactive = function (IndexVal,Fstatus,FancyData) {
        //
        $scope.vChkUserUpdate = angular.isUndefinedOrNull;
        if (Fstatus == 1) {
            var fStatus = 0;
        }
        else if (Fstatus == 4) {
            var fStatus = 1;
        }
        else if (Fstatus == 0) {
            var fStatus = 1;
        }
        var seNO = FancyData.SessInptNo;
        var seYes = parseInt(FancyData.SessInptNo)+parseInt(FancyData.rateDiff);
        //var seNO = document.getElementById('inputNo'+IndexVal).value;
        //var seYes = document.getElementById('inputYes'+IndexVal).value;
        if ((seNO=="")||(seYes=="")) {
            Dialog.show("Invalid Form submition");

        }else{
            var Fancy = {
                FancyId: FancyData.ID,
                example: seNO,
                example_1: seYes,
                MaxStake: FancyData.MaxStake,
                NoValume: 100,
                YesValume: 100,
                pointDiff: $scope.pointDiff,
                fStatus: fStatus,
                rateDiff:FancyData.rateDiff
            }
            //$scope.NormalFancy(FancyData);
            var formData = { status: Fancy.fStatus, FancyId: parseInt(Fancy.FancyId), NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff,rateDiff:FancyData.rateDiff };
            var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
            $http.post(url, formData).success(function (response) {
                //$scope.get_fancyData();
                $http.get( BASE_URL+'Lstsavemstrcontroller/EditMultipleFancy/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                    $scope.SessionData=data.FancyData;
                    //$scope.ArrayItem.items.find(function (item, itmeindex) {if (item.ItemID == $scope.Raw.aCode && item.typeId == $scope.Rawtype) { temp = 1; }});
                    $scope.SessionData.find(function (item, index) {
                        //
                       if(index == IndexVal && item.active==0) { //
                           
                            $scope.SessionData[index].SessInptNo = "";
                            $scope.SessionData[index].SessInptYes = "";
                            $('#inputNo'+IndexVal).focus();                     
                       }
                    });
                    
                    $('#inputNo'+IndexVal).val()="";
                    $('#inputNo'+IndexVal).focus();
                    $scope.NoValume = 100;
                    $scope.YesValume = 100;
                });                
            });
        }
    };
    shortcut.add("Enter", function (event) {
        ////$scope.callActiveDeactive();
        var temp=$scope.SessionData;
        var targetId=parseInt(event.target.id);
        $scope.SessionData.find(function (item, index) {
                        //
           if(index == targetId) {
                 $scope.callActiveDeactive(index,item.active,item);
           }
        });
    });
    /*shortcut.add("Alt+w", function (event) {
        var Message="Wicket Down";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+o", function (event) {
        var Message="Over";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+6", function (event) {
        var Message="SIX Runs";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+1", function (event) {
        var Message="ONE Run";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+2", function (event) {
        var Message="TWO Runs";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+3", function (event) {
        var Message="Three Runs";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+4", function (event) {
        var Message="Four Runs";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+n", function (event) {
        var Message="No Ball";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+d", function (event) {
        var Message="Wide Ball";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+f", function (event) {
        var Message="Free Hit";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+t", function (event) {
        var Message="Third Umpire";        
        $scope.setMessage(Message);
    });
    shortcut.add("Alt+l", function (event) {
        var Message="Ball Started...";        
        $scope.setMessage(Message);
    });*/
    $scope.nintyElevan = function (IndexVal,Fstatus,FancyData) {
        //
        $scope.vChkUserUpdate = angular.isUndefinedOrNull;//170304
        if (Fstatus == 1) {
            var fStatus = 0;
        }
        else if (Fstatus == 4) {
            var fStatus = 1;
        }
        else if (Fstatus == 0) {
            var fStatus = 1;
        }
        var NoValume1 = 100 + parseInt($scope.pointDiff);
        var YesValume1 = 100 - parseInt($scope.pointDiff);
        var seNO = FancyData.SessInptNo;
        var seYes = FancyData.SessInptNo;
        /*var seNO = document.getElementById('inputNo'+IndexVal).value;
       
        var seYes = document.getElementById('inputNo'+IndexVal).value;*/
        if ((seNO=="")||(seYes=="")) {
            Dialog.show("Invalid Form submition");

        }else{
            var Fancy = {
                FancyId: FancyData.ID,
                example: seNO,
                example_1: seYes,
                MaxStake: FancyData.MaxStake,
                NoValume: NoValume1,
                YesValume: YesValume1,
                pointDiff: FancyData.pointDiff,
                fStatus: fStatus,
                rateDiff:FancyData.rateDiff
            }
            //$scope.NormalFancy(FancyData);
            var formData = { status: Fancy.fStatus, FancyId: parseInt(Fancy.FancyId), NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff,rateDiff:$scope.rateDiff };
            var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
            $http.post(url, formData).success(function (response) {
                //$scope.get_fancyData();
                $http.get( BASE_URL+'Lstsavemstrcontroller/EditMultipleFancy/' + $stateParams.MatchId).success(function (data, status, headers, config) {
                    $scope.SessionData=data.FancyData;
                    //$scope.ArrayItem.items.find(function (item, itmeindex) {if (item.ItemID == $scope.Raw.aCode && item.typeId == $scope.Rawtype) { temp = 1; }});
                    $scope.SessionData.find(function (item, index) {
                        //
                       if(index == IndexVal && item.active==0) {
                           
                            $scope.SessionData[index].SessInptNo = "";
                            $scope.SessionData[index].SessInptYes = "";
                            $('#inputNo'+IndexVal).focus();                     
                       }
                    });
                    
                    $('#inputNo'+IndexVal).val()="";
                    $('#'+IndexVal).focus();
                    $scope.NoValume = parseInt(NoValume1);
                    $scope.YesValume = parseInt(YesValume1);
                });                
            });
        }
    };
    shortcut.add("Shift+Enter", function (event) {
        //
         var temp=$scope.SessionData;
        var targetId=parseInt(event.target.id);
        $scope.SessionData.find(function (item, index) {
                        //
           if(index == targetId) {
                 $scope.nintyElevan(index,item.active,item);
           }
        });
       /* if ($scope.active == 1) {
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
        $scope.nintyElevan(FancyData);*/
    });
    
    $scope.$on("$destroy", function (event) {
        shortcut.remove("Shift+Enter");
        shortcut.remove("Enter");
        shortcut.remove("Alt+w");
        shortcut.remove("Alt+o");
        shortcut.remove("Alt+1");
        shortcut.remove("Alt+2");
        shortcut.remove("Alt+3");
        shortcut.remove("Alt+4");
        shortcut.remove("Alt+6");
        shortcut.remove("Alt+d");
        shortcut.remove("Alt+n");
        shortcut.remove("Alt+t");
        shortcut.remove("Alt+f");
        shortcut.remove("Alt+l");
       
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
    $scope.getVal = function (value,index,rateDiff) {
        //
        if (value==angular.isUndefinedOrNull || value=="") {
             var sumVal=0;
        }else{
          var sumVal= parseInt(value, 10) + parseInt(rateDiff, 10);  
        }

      
        //$scope.example_1 =  parseInt(value, 10) + parseInt($scope.rateDiff, 10);
        $('#inputYes'+index).val(sumVal); 
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
                var formData = { status: Fancy.fStatus, FancyId: parseInt(Fancy.FancyId), NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff };
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
        var formData = { status: Fancy.fStatus, FancyId: Fancy.FancyId, NoVal: Fancy.example, YesVal: Fancy.example_1, MaxStake: Fancy.MaxStake, NoValume: Fancy.NoValume, YesValume: Fancy.YesValume, pointDiff: Fancy.pointDiff };
        var url = BASE_URL + "Lstsavemstrcontroller/NormalFancy";
        $http.post(url, formData).success(function (response) {
            //
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
            //$scope.$parent.vChkUserUpdate = setInterval($scope.$parent.ChkUserUpdate, 1000);
            $('#inputNo').focus();
            $scope.NoValume = 100;
            $scope.YesValume = 100;
        });
    }
});
app.directive('setFocus', function($timeout, $rootScope) {
    return {
        restrict: 'A',
        scope: {
            personId: '@',
            index: '@',
            selectedPersonId: '@'
        },
        link: function($scope, $element, attrs) {
            $scope.$watch("index", function(currentValue, previousValue) {
                if($scope.personId == $scope.selectedPersonId)
                {
                    $timeout(function(){
                        $element[0].focus();
                    });
                }
            })
        }
    }
});
app.directive('sessionlist', function () {
    return {
        templateUrl: 'app/scripts/directives/timeline/SessionLst.html',
        restrict: 'E',
        replace: true,
        scope: { },
        controller: function ($scope, $http, $stateParams, get_userser) {
            $scope.deleteRecord = function (betId, userId,MarketId) {
                var result = confirm("Are you sure want to delete Records ");
                if (result) {
                    $http.get( BASE_URL+'Betentrycntr/deleteGetbettingmat/' + betId + '/' + userId+'/'+MarketId).success(function (data, status, headers, config) {
                        alert(data.message);
                    });
                }

            }
            $scope.GetFancyBet=function(){
                var $promise = $http.get(BASE_URL + 'Sessioncntr/sessionBetList/'  + $stateParams.MatchId );
                $promise.then(function (response) {

                    $scope.sessionBet=response.data.sessionBet;
                });
            }
            $scope.GetFancyBet();
            $scope.vChkUserUpdate = setInterval($scope.GetFancyBet, 10000);
            $scope.$on("$destroy", function (event) {
                clearInterval($scope.vChkUserUpdate);
                $scope.vChkUserUpdate = angular.isUndefinedOrNull;
            });
        }
    }
});