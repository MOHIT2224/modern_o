'use strict';


//app.directive('header' ,['$location','$timeout', function($window, $http,sessionService ,$timeout){
(function() {
    app.directive('userheader', ['$location', '$http', 'sessionService', '$timeout', '$interval', 'get_userser', '$state', 'speech', 'Base64', '$q', function ($window, $http, sessionService, $timeout, $interval, get_userser, $state, speech, Base64, $q) {
        return {
            templateUrl: 'directives/userheader',
            restrict: 'E',
            replace: true,
            scope: {},
            controller: ['$scope', '$http', 'loginService', '$mdDialog', '$window', 'Dialog', 'sessionService', '$interval', 'get_userser', '$state', 'speech', '$rootScope', '$document', '$location', function ($scope, $http, loginService, $mdDialog, $window, Dialog, sessionService, $interval, get_userser, $state, speech, $rootScope, $document, $location) {

                $scope.$on('$locationChangeStart', function (event, next, current) {
                    //event.preventDefault();
                });
                var tick = function () {
                    $scope.Currentdate = Date.now();
                    $timeout(tick, 1000);
                }
                tick();
                $scope.apkDownloadUrl= sessionService.get('apkDownloadUrl');
                $scope.selected = undefined;
                var suggestionsContainer = angular.element(document).find('.md-autocomplete-suggestions-container');
                var virtualRepeaterContainer = angular.element(document).find('.md-virtual-repeat-sizer');
                $scope.suggestionsContainer = suggestionsContainer;
                $scope.virtualRepeaterContainer = virtualRepeaterContainer;
                $scope.lst = [];
////////////////////////////////////////
                $scope.ShowTerAndCondition = function () {
                    //alert("aaya");
                };
                $scope.upBal = "";
                $scope.callbal = 1;
                $scope.updateBal = function () {

                    $scope.upBal = $timeout(function () {
                        $http.get(BASE_URL + 'Chipscntrl/getChipDataById/' + sessionService.get('user_id')).success(function (data, status, headers, config) {
                            $scope.cipsData = data.betLibility;
                            if ($scope.cipsData != angular.isUndefinedOrNull) {
                                sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
                                sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
                                sessionService.set('Liability', $scope.cipsData[0].Liability);
                                sessionService.set('Balance', $scope.cipsData[0].Balance);
                                sessionService.set('P_L', $scope.cipsData[0].P_L);
                                sessionService.set('IsShowTv', $scope.cipsData[0].ShowVideoTv);
                                sessionService.set('IsSettlementBtn', $scope.cipsData[0].ShowSettlementButton);
                                $scope.$watch('sessionService', function (newVal, oldVal) {
                                    $scope.FreeChips = $scope.cipsData[0].FreeChip;
                                    $scope.ChipInOut = $scope.cipsData[0].Chip;
                                    $scope.Liability = $scope.cipsData[0].Liability;
                                    $scope.Balance = $scope.cipsData[0].Balance;
                                    $scope.P_L = $scope.cipsData[0].P_L;
                                });
                            }
                            $rootScope.user = sessionService.get('slctUseName');
                            $rootScope.Balance = sessionService.get('Balance');
                            $rootScope.Liability = sessionService.get('Liability');
                            $rootScope.P_L = sessionService.get('P_L');
                            $rootScope.FreeChips = sessionService.get('FreeChips');
                            $rootScope.IsShowTv = sessionService.get('IsShowTv');
                            $rootScope.IsSettlementBtn = sessionService.get('IsSettlementBtn');

                            $scope.callbal = 2;
                            if (!data.is_login) {
                                loginService.logout();
                            } else {
                                $scope.updateBal();
                            }


                        }).error(function (data, status, headers, config){
                            $scope.updateBal();
                        });

                    }, $scope.callbal == 1 ? 0 : 5000)
                }
                $scope.updateBal();
                $scope.changePassPopup = function () {

                    $mdDialog.show({
                        controller: ChangePassCntr,
                        templateUrl: 'app/scripts/directives/timeline/changePassword.html',
                        clickOutsideToClose: false,
                        fullscreen: false,
                        escapeToClose: false
                    });
                };
                var changePass = sessionService.get('ChangePas');

                var userType = sessionService.get('type');
                if (userType == 0) {
                } else if (userType != 0 && changePass == 0) {
                    if ($state.current.name != "login") {
                        $scope.changePassPopup();
                    }

                } else {
                    /* localStorage.getItem("$_") == null ? $scope.showTermAndCondition() : "";
                     localStorage.setItem("$_", true);*/
                }


                function ChangePassCntr($scope, get_userser) {
                    $scope.updatePassword = function (oldPassword, newPassword, cnfnewPassword) {
                        if (oldPassword != "" && oldPassword != angular.isUndefinedOrNull && newPassword != "" && newPassword != angular.isUndefinedOrNull && cnfnewPassword != "" && cnfnewPassword != angular.isUndefinedOrNull) {
                            if (newPassword == cnfnewPassword) {
                                get_userser.changePassword(oldPassword, newPassword, sessionService.get('user_id'), function (response) {
                                    if (response.error == 0) {
                                        Dialog.autohide(response.message);
                                        sessionService.set('ChangePas', '1');
                                        sessionService.set('lgPassword', response.data.mstrpassword);
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

///////////////////////////////
                $scope.getMatches = function (searchText) {
                    var deferred = $q.defer();

                    $timeout(function () {

                        // if ($scope.suggestionsContainer[0].style.height === '0px') {
                        $scope.suggestionsContainer[0].style.height = '0px';
                        // }
                        var formdata = {"search": searchText};
                        var url = BASE_URL + "Apiusercontroller/match_autocomplete";
                        $http({
                            url: url,
                            method: 'POST',
                            data: formdata
                        }).success(function (response) {

                            var result = response.data;
                            var states = result.filter(function (state) {
                                return (state.matchName.toUpperCase().indexOf(searchText.toUpperCase()) !== -1);

                            });
                            if (states != angular.isUndefinedOrNull) {
                                if (states.length == 0) {
                                    $scope.suggestionsContainer[0].style.height = '50px';
                                }

                            } else {
                                $scope.suggestionsContainer[0].style.height = '20px';
                            }
                            deferred.resolve(states);

                        }).error(function (err) {

                        })

                    }, 0);
                    return deferred.promise;

                }
//////////////////Testing Autocomplete////////////////////

                $scope.fetchUsers = function () {

                    $scope.searchText_len = $scope.searchText.trim().length;
                    var formdata = {"search": $scope.searchText};
                    var url1 = BASE_URL + "Apiusercontroller/match_autocomplete";
                    // Check search text length
                    if ($scope.searchText_len > 0) {
                        $scope.IsDDLShow = true;
                        $http({
                            method: 'post',
                            url: url1,
                            data: formdata
                        }).then(function successCallback(response) {
                            $scope.searchResult = response.data.data;
                        });
                    } else {
                        $scope.searchResult = {};
                    }

                }

                // Set value to search box
                $scope.setValue = function (index, $event) {

                    $scope.searchText = $scope.searchResult[index].matchName;
                    $scope.CallSelectedItem($scope.searchResult[index]);
                    $scope.searchResult = {};
                    $event.stopPropagation();
                }

                $scope.searchboxClicked = function ($event) {
                    $event.stopPropagation();
                }

                $scope.containerClicked = function () {
                    $scope.searchResult = {};
                }
                $document.on("click", function (event) {

                    $scope.searchResult = {};
                    $scope.searchText = "";
                });
/////////////////////////////////////
                $scope.CallSelectedItem = function (series) {

                    if (series != angular.isUndefinedOrNull) {
                        $scope.searchText = '';
                        $state.go('userDashboard.Matchodds', {
                            'MatchId': series.matchid,
                            'matchName': series.matchName,
                            'date': series.MstDate,
                            'sportId': series.SportId
                        })
                    }
                }
                $scope.getMatchList = function (searchText) {
                    var deferred = $q.defer();
                    var formdata = {"search": searchText};
                    var url = BASE_URL + "Apiusercontroller/match_autocomplete";
                    return $http
                        .post(url, formdata)
                        .then(function (response) {

                            // Map the response object to the data object.
                            var result = response.data.data;

                            return result;
                        });
                }

                $scope.LastLoginDate = sessionService.get('last_login_time');
                $scope.RedirectToFancy = function (fancyId, TypeID, MatchID, SportID, matchName) {
                    $scope.setValue = fancyId;

                    $scope.showvalue = false;
                    $scope.displayFicon = false;
                    if (TypeID == 1) {
                        //dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})
                        $state.go("dashboard.Evenoddfancy", {
                            'matchId': MatchID,
                            'FancyID': fancyId,
                            'TypeID': TypeID,
                            'matchName': matchName,
                            'sportId': SportID
                        });
                    } else if (TypeID == 2) {
                        $state.go("dashboard.Sessionfancy", {
                            'matchId': MatchID,
                            'FancyID': fancyId,
                            'TypeID': TypeID,
                            'matchName': matchName,
                            'sportId': SportID
                        });
                    } else if (TypeID == 3) {
                        $state.go("dashboard.Khaddalfancy", {
                            'matchId': MatchID,
                            'FancyID': fancyId,
                            'TypeID': TypeID,
                            'matchName': matchName,
                            'sportId': SportID
                        });
                    } else if (TypeID == 4) {
                        $state.go("dashboard.Lastdigit", {
                            'matchId': MatchID,
                            'FancyID': fancyId,
                            'TypeID': TypeID,
                            'matchName': matchName,
                            'sportId': SportID
                        });
                    } else if (TypeID == 5) {
                        $state.go("dashboard.Updown", {
                            'matchId': MatchID,
                            'FancyID': fancyId,
                            'TypeID': TypeID,
                            'matchName': matchName,
                            'sportId': SportID
                        });
                    }
                }

                //for Marque BY Manish
                var callmsg=1;
                $scope.ShowMessageOnHeader = function () {
                    $scope.timerGo12 = $timeout(function(){
                        var tempUrl = $location.absUrl();
                        if (tempUrl.indexOf('userDashboard') > -1) {
                            var authdata = Base64.encode(sessionService.get('user') + ':' + sessionService.get('lgPassword'));
                            $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
                            $http.get(BASE_URL+'Betentrycntr/DisplayMsgOnHeader/').success(function (data, status, headers, config) {
                                //
                                if (data.marqueMsg != angular.isUndefinedOrNull) {
                                    $scope.diplayMsg = data.marqueMsg[0].Marquee;
                                }
                                callmsg=2;
                                $scope.ShowMessageOnHeader();
                            }).error(function (data, status, headers, config) {
                                $scope.ShowMessageOnHeader();
                                if (status == "412") {
                                    loginService.logout();
                                }
                            });
                        }
                    },callmsg==1 ? 0 : 10000)

                }
                var msgHeader = function check_Fancydisplay() {
                    $scope.ShowMessageOnHeader();
                }
                var tempUrl = $location.absUrl();
                if (tempUrl.indexOf('userDashboard') > -1) {
                   // $scope.timerGo12 = $interval(msgHeader, 10000);
                    $scope.ShowMessageOnHeader();
                }
                //for Marque BY Manish

                $("#mobileDemo").click(function () {
                    if ($(".mainSite").hasClass('activeSideNav')) {
                        $(".mainSite").removeClass('activeSideNav');
                        $(".mainSite").addClass('deactiveSideNav');
                    } else {
                        $(".mainSite").addClass('activeSideNav');
                        $(".mainSite").removeClass('deactiveSideNav');
                    }
                });
                //$scope.name = sessionStorage.HelperName;
                $scope.name = sessionService.get('user');
                $scope.usertype = sessionService.get('type');
                //alert("hi"+$scope.usertype);
                $scope.logout = function () {
                    $scope.loading = true;
                    $rootScope.$broadcast('clearTimeOut', {});
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
    }]);
})();

