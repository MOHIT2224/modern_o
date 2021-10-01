'use strict';
var app = angular.module('ApsilonApp');
//app.directive('header' ,['$location','$timeout', function($window, $http,sessionService ,$timeout){
app.directive('header', ['$location','$http', 'sessionService', '$timeout','$interval','get_userser','$state','speech', function ($window, $http, sessionService, $timeout,$interval,get_userser,$state,speech) {
    return {
        templateUrl: 'directives/adminheader',
        //controller:'Formctrl',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: ['$scope', '$http', 'loginService', '$mdDialog', '$window', 'Dialog', 'sessionService','$interval','get_userser','$state','speech','Base64','$rootScope',function ($scope, $http, loginService, $mdDialog, $window, Dialog, sessionService,$interval,get_userser,$state,speech,Base64,$rootScope) {
           
            $scope.$on('$locationChangeStart', function (event, next, current) {
             //event.preventDefault(); 
         });
            $scope.subAdmin= sessionService.get('subAdmin');
            $scope.IsShowMenu=function(type)
            {

                var subAdmin= sessionService.get('subAdmin');
                if(subAdmin!=angular.isUndefinedOrNull && subAdmin=="true" )
                {
                    if(sessionService.get('subAdminRole')!=angular.isUndefinedOrNull)
                    {
                        var groupRole= JSON.parse(sessionService.get('subAdminRole'));
                        if(groupRole!=angular.isUndefinedOrNull)
                        {

                            if(groupRole[type]!=angular.isUndefinedOrNull && groupRole[type]==1)
                            {
                                return true;
                            }
                            else
                            {
                                return false;
                            }
                        }
                        else
                        {
                            return true;
                        }
                    }
                }
                else
                {
                    return true;
                }


            }
            $scope.upBal="";
            $scope.callbal=1;
            $scope.callSubAdminRole=function()
            {
                $timeout(function(){
                    var subAdmin= sessionService.get('subAdmin');
                    if(subAdmin=="true")
                    {
                        $http.get(BASE_URL+'UserRightsCntr/getLoginUserRole').success(function (data, status, headers, config) {

                            sessionService.set('subAdminRole',JSON.stringify(data.data));
                            $scope.callSubAdminRole();
                        });
                    }

                },10000);


            }
            $scope.callSubAdminRole();
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
                      }
                $scope.$watch('sessionService', function (newVal, oldVal) {
                    if($scope.cipsData!=angular.isUndefinedOrNull) {
                        $scope.FreeChips = $scope.cipsData[0].FreeChip;
                        $scope.ChipInOut = $scope.cipsData[0].Chip;
                        $scope.Liability = $scope.cipsData[0].Liability;
                        $scope.Balance = $scope.cipsData[0].Balance;
                        $scope.P_L = $scope.cipsData[0].P_L;
                    }
                });
                $rootScope.user = sessionService.get('slctUseName');
                $rootScope.Balance = sessionService.get('Balance');
                $rootScope.Liability = sessionService.get('Liability');
                $rootScope.IsShowTv= sessionService.get('IsShowTv');
                $rootScope.IsSettlementBtn= sessionService.get('IsSettlementBtn');
                      $scope.callbal=2;
                      if(!data.is_login && data.is_login!=angular.isUndefinedOrNull) {

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
            /*start The code for blink the Fancy Icon*/
            $scope.setValue=0;
           /* var chkFancyDisplay=function check_Fancydisplay() {
                                    get_userser.GetFancyLength( function (response) {
                                        ////
                                           if ($scope.setValue==0) {
                                                $scope.setValue=response;
                                           }else if(response>$scope.setValue){
                                                $http.get( BASE_URL+'Createdealercontroller/getFancyInIcon/'+response).success(function (data, status, headers, config) {
                                                      // //
                                                        if (data.GetFancyIcon[0].TypeID==1)
                                                            speech.sayText("Odd even Fancy Created");
                                                        else if (data.GetFancyIcon[0].TypeID==2)
                                                            speech.sayText("Session Fancy Created");
                                                        else if (data.GetFancyIcon[0].TypeID==3)
                                                            speech.sayText("Khaddal Fancy Created");
                                                        else if (data.GetFancyIcon[0].TypeID==4)
                                                            speech.sayText("Last digit Fancy Created");
                                                        else if (data.GetFancyIcon[0].TypeID==5)
                                                            speech.sayText("Up down Fancy Created");
                                                        $scope.displayFicon=true;
                                                        $scope.FancyIcon = data.GetFancyIcon;
                                                        
                                                });
                                           }
                                    });
                                }
            var timerGo = $interval(chkFancyDisplay, 3000);*/
            /*end of the code of Fancy*/
            $scope.displayFicon=false;
            $scope.showvalue = false;
            $scope.closeBlink=function(fancyId){
                $scope.displayFicon=false;
                $scope.setValue=fancyId; 
            }
            $scope.RedirectToFancy=function(fancyId,TypeID,MatchID,SportID,matchName){
                $scope.setValue=fancyId;
              //  //
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
            $scope.displayFancyTest1 = function () {
                $scope.showvalue = true;
            }
            //for Marque BY Manish
            $scope.ShowMessageOnHeader = function(){
		var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
		 $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
                $http.get( BASE_URL+'Betentrycntr/DisplayMsgOnHeader/').success(function (data, status, headers, config) {
                        ////
			
		if(data.marqueMsg !=angular.isUndefinedOrNull)
		{
                        $scope.diplayMsg = data.marqueMsg[0].Marquee;
		}
                        
                }).error(function(data, status, headers, config){
				if(status=="412")
					{
						loginService.logout();
					}
			});
            }
           //  $scope.ShowMessageOnHeader();
            //for Marque BY Manish
            $scope.editFancy = function (fancyID, fancyType, matchName, sportId) {
                $scope.myMenu = false;
                $mdDialog.show({
                    controller: 'editFancy',// at last of header_.js
                    templateUrl: 'app/scripts/directives/popupform/edit_fancy.html',
                    locals: { prntScope: $scope, id: fancyID, Type: fancyType, MatchName: matchName, sportId: sportId },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            //****************START FANCY POPUP********
            $scope.editOddEvenFancy = function (fancyID, fancyType, matchName, sportId, HeadName) {
                
                $scope.myMenu = false;
                $mdDialog.show({
                    controller: OddEvenController,
                    templateUrl: 'app/scripts/directives/popupform/evenOddPopUp.html',
                    locals: { prntScope: $scope, id: fancyID, Type: fancyType, MatchName: matchName, sportId: sportId },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function OddEvenController($scope, $mdDialog, prntScope, id, Type, MatchName, sportId) {
                $scope.callFirstTime = function () {
                    $http.get( BASE_URL+'Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {
                        $scope.oddEven = data.FancyData;
                        $scope.HeadName = data.FancyData[0].HeadName;
                        $scope.ID = data.FancyData[0].ID;
                        $scope.MatchID = data.FancyData[0].MatchID;
                        $scope.TypeID = data.FancyData[0].TypeID;
                        $scope.date = data.FancyData[0].date;
                        $scope.Time = data.FancyData[0].time;
                        $scope.Remarks = data.FancyData[0].Remarks;
                        $scope.MatchName = MatchName;
                    });
                }
                $scope.oddEvenFancy = function () {
                    
                    var formData1 = { HeadName: $scope.HeadName, remarks: $scope.Remarks, mid: $scope.MatchID, fancyType: $scope.TypeID, date: $scope.date, time: $scope.Time, FancyId: $scope.ID, sid: 4 }
                    var url = BASE_URL + "Createmastercontroller/EditFancy";
                    $http.post(url, formData1).success(function (response) {
                        Dialog.autohide(response.message);
                    });
                };
                $scope.callFirstTime();
                $scope.hide = function () { $mdDialog.hide(); };
            }
            //****************END*********************
            //****************START Session Fancy********

            $scope.editSessionFancy = function (fancyID, fancyType) {
                $scope.myMenu = false;
                $mdDialog.show({
                    controller: SessionController,
                    templateUrl: 'app/scripts/directives/popupform/evenOddPopUp.html',
                    locals: { prntScope: $scope, id: fancyID, Type: fancyType },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function SessionController($scope, $mdDialog, prntScope, id, Type) {

                $http.get( BASE_URL+'Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {
                    $scope.oddEven = data.FancyData;
                });
            }
            //****************END*********************
            //****************START KHADDAL POPUP********

            $scope.khaddalFancy = function (fancyID, fancyType) {
                $scope.myMenu = false;
                $mdDialog.show({
                    controller: khaddalController,
                    templateUrl: 'app/scripts/directives/popupform/evenOddPopUp.html',
                    locals: { prntScope: $scope, id: fancyID, Type: fancyType },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            function khaddalController($scope, $mdDialog, prntScope, id, Type) {
                $http.get( BASE_URL+'Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {
                    $scope.oddEven = data.FancyData;
                });
            }
            //****************END*********************

            $scope.HideFancyDiv = function () {
                $scope.showvalue = false;
                $scope.displayFicon=false;
            }
            /*get Fancy List In admin Side*/
            /* $http.get( BASE_URL+'Lstsavemstrcontroller/GetFancyOnHeader/').success(function (data, status, headers, config) {
                 $scope.GetfancyList1=data.getFancy;
             });*/
            $scope.FancyListDisplay = function () {
                $http.get( BASE_URL+'Lstsavemstrcontroller/GetFancyOnHeader/').success(function (data, status, headers, config) {
                    $scope.GetfancyList1 = data.getFancy;
                    //alert("Get");
                });
            }
           // $scope.FancyListDisplay();
            /*Get Fancy Result*/
            $scope.getFancyResult = function (sportId, match_id, fancy_Id) {
                var result1 = confirm("Are You sure want to set the Result ...");
                if (result1) {
                    var result = document.getElementById('result_' + fancy_Id).value;
                    var formData = {
                        sportId: sportId,
                        match_id: match_id,
                        fancy_Id: fancy_Id,
                        result: result
                    }
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Lstsavemstrcontroller/updateFancyResult/',
                        data: formData, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function (data) {
                        if (data.error == 0) {
                            Dialog.autohide('|' + data.message + '|');
                            $scope.FancyListDisplay();
                        } else {
                            Dialog.autohide('|' + data.message + '|');
                            $scope.FancyListDisplay();
                        }

                    });
                }

            }
            /*End of Get fancy Result*/
            /*Get Fancy Result*/
            $scope.getFancyUpDown = function (sportId, match_id, fancy_Id, fancyType) {
                var result1 = confirm("Are You sure want to set the Result ...");
                if (result1) {
                    var result = document.getElementById('result_' + fancy_Id).value;
                    var formData = {
                        sportId: sportId,
                        match_id: match_id,
                        fancy_Id: fancy_Id,
                        fancyType: fancyType,
                        result: result
                        , HelperID: sessionService.get('HelperID')
                    }
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Lstsavemstrcontroller/updateKhaddalResult/',
                        data: formData, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function (data) {
                        $scope.FancyListDisplay();
                        Dialog.autohide('||' + data.message + '||');
                    });
                }

            }
            /*End of Get fancy Result*/
            /*Change The Fancy status*/
            $scope.getFancyStatus = function (fancyId, active) {
                var formData = { id: fancyId, active: active, HelperID: sessionService.get('HelperID') }
                $http({
                    method: 'POST',
                    url: BASE_URL+'Lstsavemstrcontroller/updateFancyHeaderSatatus/',
                    data: formData, //forms user object
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function (data) {

                });
            };
            /*End of Fancy Status*/
            /*End of Fancy List In admin Side*/
            //$scope.name = sessionStorage.HelperName;
            $scope.name = sessionService.get("user");
            $scope.usertype = sessionService.get("type");
            //alert("hi"+$scope.usertype);
            $scope.logout = function () {
                loginService.logout();
            };

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

        }]
    }
}]);
app.controller('editFancy',['$scope', '$http', '$mdDialog', 'Dialog', 'prntScope', 'id', 'Type', 'MatchName', 'sportId', function ($scope, $http, $mdDialog, Dialog, prntScope, id, Type, MatchName, sportId) {//matche have came match detail recently not came sourabh 170118

    $scope.getFancyInfo = function (id, Type) {
        $http.get( BASE_URL+'Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {

            $scope.oddEven = data.FancyData;
            $scope.fancyHeaderName = data.FancyData[0].HeadName;
            $scope.dt = data.FancyData[0].date;
            $scope.MatchID = data.FancyData[0].MatchID;
            $scope.ID = data.FancyData[0].ID;
            $scope.TypeID = data.FancyData[0].TypeID;
            $scope.fancyType = Type;
            $scope.Time = data.FancyData[0].time;
            $scope.MatchName = MatchName;
            $scope.PlayerId = 0;
            $scope.upDwnLimit = data.FancyData[0].upDwnLimit;
            if (Type == 5) {
                $scope.Back_size = parseInt(data.FancyData[0].upDwnBack);
                $scope.Back_lay = parseInt(data.FancyData[0].upDwnLay);
                $scope.PlayerId = data.FancyData[0].PlayerId;
                var x = "0";
                $scope.getplayer.find(function (a, b) { if (a.ID == $scope.PlayerId) x = b; });
                console.log(x);
                $scope.selectedOption = $scope.getplayer[x];
            }
            $scope.formData = {
                HeadName: data.FancyData[0].HeadName,
                remarks: data.FancyData[0].Remarks,
                dt: data.FancyData[0].date,
                mytime: data.FancyData[0].time,
                range: parseInt(data.FancyData[0].fancyRange),
                upDwnLimit: data.FancyData[0].upDwnLimit
            }
        });
    }
    $scope.getPlayerName = function (s) {
        $http.get( BASE_URL+'Geteventcntr/getMatchLst/' + s)
            .success(function (data, status, headers, config) {
                $scope.match_data = data.matchLst;
                $scope.getplayer = data.getplayer;
            })
            .error(function (data, status, header, config) { });
    }
    if (Type == 5) {
        var s = 0;
        $scope.getPlayerName(s);
        $scope.getFancyInfo(id, Type);
    }
    else {
        $scope.getFancyInfo(id, Type);
    }
    $scope.hide = function () { $mdDialog.hide(); };

    $scope.KhaddalFancyForm = function (formData) {
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.MatchID, fancyType: $scope.fancyType, date: formData.dt, time: $scope.Time, fancyRange: formData.range, FancyId: $scope.ID, sid: 4 }
        var url = BASE_URL + "Createmastercontroller/EditFancy";
        $http.post(url, formData1).success(function (response) {
            Dialog.autohide(response.message);
        });
    };
    $scope.oddEvenFancy = function (formData) {
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.MatchID, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.Time, FancyId: $scope.ID, sid: 4 }
        var url = BASE_URL + "Createmastercontroller/EditFancy";
        $http.post(url, formData1).success(function (response) {
            Dialog.autohide(response.message);
        });
    };
    $scope.LastDigitFancy = function (formData) {
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.MatchID, fancyType: $scope.fancyType, date: formData.dt, time: $scope.Time, FancyId: $scope.ID, sid: 4, upDwnLimit: formData.upDwnLimit }
        var url = BASE_URL + "Createmastercontroller/EditFancy";
        $http.post(url, formData1).success(function (response) {
           // //
            Dialog.autohide(response.message);
        });
    };
    $scope.UpDownFancy = function (formData, selectedOption) {
        var Back_size = document.getElementById('Back_size').value;
        var Lay_size = document.getElementById('Lay_size').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.MatchID, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.Time, PlayerId: selectedOption.ID, playerName: selectedOption.Name, Back_size: Back_size, Lay_size: Lay_size, FancyId: $scope.ID, sid: 4 }
        var url = BASE_URL + "Createmastercontroller/EditFancy";
        $http.post(url, formData1).success(function (response) {
            Dialog.autohide(response.message);
        });
    };
}]);
