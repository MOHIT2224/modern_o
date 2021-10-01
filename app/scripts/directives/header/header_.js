'use strict';
var app = angular.module('ApsilonApp');
//app.directive('header' ,['$location','$timeout', function($window, $http,sessionService ,$timeout){
app.directive('header', ['$location', '$timeout', function ($window, $http, sessionService, $timeout) {
    return {
        templateUrl: 'directives/adminheader',
        //controller:'Formctrl',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: function ($scope, $http, loginService, $mdDialog, $window, Dialog, sessionService) {
            $scope.$on('$locationChangeStart', function (event, next, current) { event.preventDefault(); });
            $scope.showvalue = false;
            $scope.displayFancyTest1 = function () {
                $scope.showvalue = true;
            }


            /*   $scope.checkboxValue = false;
            $scope.handler = function(){
                $scope.checkboxValue = true;
            }*/
            //{sourabh 170109
            $scope.editFancy = function (fancyID, fancyType, matchName, sportId) {//soruabh 170110   

                $scope.myMenu = false;
                $mdDialog.show({
                    controller: 'editFancy',// at last of header_.js
                    templateUrl: 'app/scripts/directives/popupform/edit_fancy.html',
                    locals: { prntScope: $scope, id: fancyID, Type: fancyType, MatchName: matchName, sportId: sportId },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };
            //}sourabh 170109
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
                    $http.get('Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {
                        //
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
                    var formData1 = { HeadName: $scope.HeadName, remarks: $scope.remarks, mid: $scope.MatchID, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.Time, FancyId: $scope.ID, sid: 4 }
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

                $http.get('Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {
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
                $http.get('Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {
                    $scope.oddEven = data.FancyData;
                });
            }
            //****************END*********************

            $scope.HideFancyDiv = function () {
                $scope.showvalue = false;
            }
            /*get Fancy List In admin Side*/
            /* $http.get('Lstsavemstrcontroller/GetFancyOnHeader/').success(function (data, status, headers, config) {
                 $scope.GetfancyList1=data.getFancy;
             });*/
            $scope.FancyListDisplay = function () {
                $http.get('Lstsavemstrcontroller/GetFancyOnHeader/').success(function (data, status, headers, config) {
                    $scope.GetfancyList1 = data.getFancy;
                    //alert("Get");
                });
            }
            $scope.FancyListDisplay();
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
                        url: 'Lstsavemstrcontroller/updateFancyResult/',
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
                        url: 'Lstsavemstrcontroller/updateKhaddalResult/',
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
                    url: 'Lstsavemstrcontroller/updateFancyHeaderSatatus/',
                    data: formData, //forms user object
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function (data) {

                });
            };
            /*End of Fancy Status*/
            /*End of Fancy List In admin Side*/
            //$scope.name = sessionStorage.HelperName;
            $scope.name = sessionService.get('user');
            $scope.usertype = ssessionService.get('type');
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
			
            $scope.showHideSidebar = function () {
                $scope.menuLeft = $('.pushmenu-left');
                $scope.nav_list = $('#nav_list');
                $(this).toggleClass('active');
                $('.pushmenu-push').toggleClass('pushmenu-push-toright');
                $scope.menuLeft.toggleClass('pushmenu-open');
                var $window = $(window),
                $push = $('.pushmenu-push');
                //$puslft = $('.pushmenu-left');
                angular.element($window).on('resize', $scope.onResize);


            };
			$scope.showHideSidebar();
        }
    }


}]);
app.controller('editFancy', function ($scope, $http, $mdDialog, Dialog, prntScope, id, Type, MatchName, sportId) {//matche have came match detail recently not came sourabh 170118

    $scope.getFancyInfo = function (id, Type) {
        $http.get('Lstsavemstrcontroller/getFancyByEdit/' + id + '/' + Type).success(function (data, status, headers, config) {

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
        $http.get('Geteventcntr/getMatchLst/' + s)
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
});
