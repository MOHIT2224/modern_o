app.controller('Fancycontroller', ['$scope', '$http', '$timeout', '$log', '$mdDialog','Dialog', function ($scope, $http, $timeout, $log, $mdDialog,Dialog)//sourabh 170103
{
    $scope.loading = true;
    $scope.ismeridian = false;
    $scope.toggleMode = function () {
        $scope.ismeridian = !$scope.ismeridian;
    };
    $scope.update = function () {
        var d = new Date();
        d.setHours(14);
        d.setMinutes(0);
        $scope.mytime = d;
    };
    $scope.addScorekey = function(matchid,key){
        debugger
            if(key != undefined && key !=''){
                $http.get('Geteventcntr/updateScoreBoardId/'+matchid+'/'+key).success(function (data, status, headers, config) {
                    debugger
                   // $scope.scoredata = data;
                    Dialog.autohide(data.message);

                });
            }else {
                Dialog.autohide('Please enter key');
            }

    }
	$scope.fetchscoredata = function () {
		$http.get('https://score.crakex.in:3290/matchlist').success(function (data, status, headers, config) {
			debugger
			$scope.scoredata = data;
		});
	}
	$scope.fetchscoredata();
    $scope.changed = function () { $log.log('Time changed to: ' + $scope.mytime); };
    $scope.clear = function () { $scope.mytime = null; };
    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.formats = ['dd-MMMM-yyyy', 'yyyy-MM-dd', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[1];
    $scope.selectedItemvalue = "1";
    $scope.showModal = false;
    $scope.buttonClicked = "";
    $scope.toggleModal = function (btnClicked, id, type, matchDate) {
        $scope.dt = null;
        $scope.buttonClicked = btnClicked;
        $scope.showModal = !$scope.showModal;
        if (type == 1) {
            $scope.fancyHeaderName = "Odd Even";
            var t = matchDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matchDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 2) {
            $scope.fancyHeaderName = "Session";
            var t = matchDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matchDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 3) {
            $scope.fancyHeaderName = "Khaddal";
            var t = matchDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matchDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 4) {
            $scope.fancyHeaderName = "Last Digit";
            var t = matchDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matchDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 5) {
            $scope.fancyHeaderName = "Up Down";
            var t = matchDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matchDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        $scope.mid = id;
        $scope.fancyType = type;
    };
    $scope.showCreateFancy = function (ev, matche, type) {
        $mdDialog.show({
            controller: showCreateFancyController,
            templateUrl: 'app/scripts/directives/popupform/create_fancy.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: false,
            locals: { prntScope: $scope, matche: matche, type: type },
        })
      .then(function () {
      }, function () {
      });
    };
    function showCreateFancyController($scope, $mdDialog, prntScope, matche, type) {
        $scope.dt = null;
        if (type == 1) {
            $scope.fancyHeaderName = "Odd Even";
            var t = matche.MstDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matche.MstDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 2) {
            $scope.fancyHeaderName = "Session";
            var t = matche.MstDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matche.MstDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 3) {
            $scope.fancyHeaderName = "Khaddal";
            var t = matche.MstDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matche.MstDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 4) {
            $scope.fancyHeaderName = "Last Digit";
            var t = matche.MstDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matche.MstDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        else if (type == 5) {
            $scope.fancyHeaderName = "Up Down";
            $scope.ratediff = 1;
            $scope.maxStake = 10000;
            $scope.pointDiff = 10;
            var t = matche.MstDate.split(/[- :]/);
            var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            $scope.mytime = new Date(matche.MstDate);
            $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
        }
        $scope.mid = matche.MstCode;
        $scope.SportID = matche.SportID;
        $scope.fancyType = type;
        $scope.oddEvenFancy = function (formData) {
            var setFancyTime = document.getElementById('setFancyTime').value;
            var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime ,sid:$scope.SportID}
            prntScope.oddEvenFancy(formData1, setFancyTime);
        };
        $scope.SessionFancyForm = function (formData) {

            var setFancyTime = document.getElementById('setFancyTimeS').value;
            var inputYes = document.getElementById('inputYes').value;
            var inputNo = document.getElementById('inputNo').value;
            var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, inputYes: inputYes, inputNo: inputNo ,sid:$scope.SportID}
            prntScope.SessionFancyForm(formData1);
        };
        $scope.KhaddalFancyForm = function (formData) {
            var setFancyTime = document.getElementById('setFancyTimeK').value;
            var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, fancyRange: formData.range,sid:$scope.SportID }
            prntScope.KhaddalFancyForm(formData1);
        };
        $scope.LastDigitFancy = function (formData) {
            var setFancyTime = document.getElementById('setFancyTimeL').value;
            var liabilityLstDigit = document.getElementById('liabilityLstDigit').value;
            var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, liability: liabilityLstDigit ,sid:$scope.SportID}
            prntScope.LastDigitFancy(formData1);
        };
        $scope.UpDownFancy = function (formData) {
            var liability = document.getElementById('liability').value;
            var upDownHead = document.getElementById('upDownHead').value;
            var ratediffUpdwn = document.getElementById('ratediffUpdwn').value;
            var pointDiffUpdwn = document.getElementById('pointDiffUpdwn').value;
            var maxStakeUpdwn = document.getElementById('maxStakeUpdwn').value;
            var formData1 = { HeadName: upDownHead, mid: $scope.mid, remarks: formData.remarks, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.mytime, rateDiff: ratediffUpdwn, pointDiff: pointDiffUpdwn, MaxStake: maxStakeUpdwn, liability: liability ,sid:$scope.SportID}
            prntScope.UpDownFancy(formData1);
        };

        $scope.hide = function () { $mdDialog.hide(); };
    }
    $scope.formData = "";
    $scope.oddEvenFancy = function (formData)//sourabh 170103
    {
        //var setFancyTime = document.getElementById('setFancyTime').value;sourabh 170103
        //var formData = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime }sourabh 170103
        var url = BASE_URL + "Createmastercontroller/SaveFancy";
        $http.post(url, formData).success(function (response) {
            $scope.oddEvenFancymessage = response.message;
            $scope.oddEvenFancyMsg = true;
            $scope.formData = "";
            $mdDialog.hide();//sourabh 170103
            $timeout(callAtTimeout, 1000);
            function callAtTimeout() { $scope.oddEvenFancyMsg = false; $scope.showModal = false; }
        });
    };
    $scope.SessionFancyForm = function (formData) {

        //var setFancyTime = document.getElementById('setFancyTime').value;sourabh 170103
        //var inputYes = document.getElementById('inputYes').value;sourabh 170103
        //var inputNo = document.getElementById('inputNo').value;sourabh 170103
        //var formData = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, inputYes: inputYes, inputNo: inputNo }sourabh 170103
        var url = BASE_URL + "Createmastercontroller/SaveFancy";
        $http.post(url, formData).success(function (response) {
            $scope.SessionFancyForm = response.message;
            $scope.SessionFancyMsg = true;
            $mdDialog.hide();//sourabh 170103
            $timeout(callAtTimeout, 1000);
            function callAtTimeout() {
                $scope.SessionFancyMsg = false;
                $scope.showModal = false;
            }
        });
    };
    $scope.KhaddalFancyForm = function (formData) {

        //var setFancyTime = document.getElementById('setFancyTime').value;sourabh 170103
        //var formData = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, fancyRange: formData.range, }sourabh 170103

        var url = BASE_URL + "Createmastercontroller/SaveFancy";
        $http.post(url, formData).success(function (response) {

            $scope.KhaddalFancyMsg = true;
            $mdDialog.hide();//sourabh 170103
            $timeout(callAtTimeout, 1000);
            function callAtTimeout() {
                $scope.KhaddalFancyMsg = false;
                $scope.showModal = false;
                //document.getElementById('range').value="";sourabh 170103
            }
            $scope.KhaddalFancyForm = response.message;

        });

    };
    $scope.LastDigitFancy = function (formData) {

        //var setFancyTime = document.getElementById('setFancyTime').value;sourabh 170103
        //var formData = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime }sourabh 170103
        var url = BASE_URL + "Createmastercontroller/SaveFancy";
        $http.post(url, formData).success(function (response) {
            $scope.LastDigitFancyMsg = true;
            $mdDialog.hide();//sourabh 170103
            $timeout(callAtTimeout, 1000);
            function callAtTimeout() { $scope.LastDigitFancyMsg = false; $scope.showModal = false; }
            $scope.LastDigitFancymessage = response.message;
            $scope.formData = "";
        });
    };
    $scope.UpDownFancy = function (formData) {


        var url = BASE_URL + "Createmastercontroller/SaveFancy";
        $http.post(url, formData).success(function (response) {

            $scope.UpDownFancyMsg = true;
            $mdDialog.hide();//sourabh 170103
            $timeout(callAtTimeout, 1000);
            function callAtTimeout() {
                $scope.UpDownFancyMsg = false;
                $scope.showModal = false;
            }
            $scope.UpDownMessage = response.message;
            $scope.formData = null;
        });
    };
    $scope.addPlusOne = function (value) {
        $scope.example_1 = value + 1;
    };
    $scope.addPlusTwo = function (value) {
        $scope.example_1 = value + 2;
    };
    $scope.addPlusThree = function (value) {
        $scope.example_1 = value + 3;
    };
    $scope.addPlusFour = function (value) {
        $scope.example_1 = value + 4;
    };
    $scope.addPlusFive = function (value) {
        $scope.example_1 = value + 5;
    };
    $scope.getVal = function (value) {
        $scope.example_1 = value + 1;
    };
    $scope.changeMatchStatus = function (matchid, status) {
        $scope.loading = true;
        if (status == true) {
            var newStatus = 0;
            var result = confirm("Are you sure want to Deactivate this Match ?");
        }
        else {
            var newStatus = 1;
            var result = confirm("Are you sure want to Activate this Match ?");
        }
        if (result) {
            $http.get('Lstsavemstrcontroller/updateFancySatatus/' + matchid + '/' + newStatus).success(function (data, status, headers, config) {
                if(data.error==0)
                {
                    var s = 0;
                    $http.get('Geteventcntr/getMatchLst/' + s).success(function (data, status, headers, config) {

                        $scope.match_data = data.matchLst;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 20;
                        $scope.filteredItems = $scope.match_data.length;
                        $scope.totalItems = $scope.match_data.length;
                        $scope.loading = false;

                    });
                }
                else {
                    alert(data.message);
                    $scope.loading = false;
                }


            });
        }
        else{
            $scope.loading = false;
        }
    };
    $scope.changeVolumeLimit = function (limit, matchid) {
        $scope.loading = true;
        $http.get('Lstsavemstrcontroller/updateVolumeLimit/' + limit + '/' + matchid + '/').success(function (data, status, headers, config) {

            if(data.error==0)
            {
                var s = 0;
                $http.get('Geteventcntr/getMatchLst/' + s).success(function (data, status, headers, config) {
                    $scope.match_data = data.matchLst;
                    $scope.currentPage = 1;
                    $scope.entryLimit = 20;
                    $scope.filteredItems = $scope.match_data.length;
                    $scope.totalItems = $scope.match_data.length;
                });
                alert('Limit Updated Successfully');
                $scope.loading = false;
            }
            else {
                alert(data.message);
                $scope.loading = false;
            }

        }).error(function (data, status, header, config) {
            $scope.ResponseDetails = "Data: " + data +
                "<br />status: " + status +
                "<br />headers: " + jsonFilter(header) +
                "<br />config: " + jsonFilter(config);
        });
    };
    $scope.changeStake = function (stake, matchid,type) {

        $scope.loading = true;

        if(type=="min")
        {
            var data={
                "value":stake,
                "key":'minStack',
                "match_id":matchid
            };
        }
        else {
            var data={
                "value":stake,
                "key":'maxStack',
                "match_id":matchid
            };
        }
        $http.post(BASE_URL+'Lstsavemstrcontroller/updateMinAndMaxStackLimit/',data).success(function (data, status, headers, config) {

            if(data.error==0)
            {
                var s = 0;
                $http.get(BASE_URL+'Geteventcntr/getMatchLst/' + s).success(function (data, status, headers, config) {
                    $scope.match_data = data.matchLst;
                    $scope.currentPage = 1;
                    $scope.entryLimit = 20;
                    $scope.filteredItems = $scope.match_data.length;
                    $scope.totalItems = $scope.match_data.length;
                });
                alert('Updated Successfully');
                $scope.loading = false;
            }
            else {
                alert(data.message);
                $scope.loading = false;
            }

        }).error(function (data, status, header, config) {
            $scope.ResponseDetails = "Data: " + data +
                "<br />status: " + status +
                "<br />headers: " + jsonFilter(header) +
                "<br />config: " + jsonFilter(config);
        });
    };
    $scope.changeOddsLimit = function (limit, matchid) {
        $scope.loading = true;
        $http.get('Lstsavemstrcontroller/updateOddsLimit/' + limit + '/' + matchid + '/').success(function (data, status, headers, config) {
            var s = 0;
            if(data.error==0)
            {
                $http.get('Geteventcntr/getMatchLst/' + s).success(function (data, status, headers, config) {
                    $scope.match_data = data.matchLst;
                    $scope.currentPage = 1;
                    $scope.entryLimit = 20;
                    $scope.filteredItems = $scope.match_data.length;
                    $scope.totalItems = $scope.match_data.length;
                    $scope.loading = false;
                });
            }
            else {
                alert(data.message);
                $scope.loading = false;
            }

        }).error(function (data, status, header, config) {
            $scope.ResponseDetails = "Data: " + data +
                "<br />status: " + status +
                "<br />headers: " + jsonFilter(header) +
                "<br />config: " + jsonFilter(config);
        });
    };
    var s = 0;
    $http.get('Geteventcntr/getMatchLst/' + s)
            .success(function (data, status, headers, config) {
                $scope.match_data = data.matchLst;
                $scope.getplayer = data.getplayer;
                $scope.currentPage = 1;
                $scope.entryLimit = 20;
                $scope.filteredItems = $scope.match_data.length;
                $scope.totalItems = $scope.match_data.length;
                $scope.loading = false;
            });
    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function () {
        $timeout(function () {
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
}]);
app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    }
});

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
              '<div class="modal-content">' +
                '<div class="modal-header">' +
                  '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                  '<h4 class="modal-title">{{fancyHeaderName}} Fancy</h4>' +
                '</div>' +
                '<div class="modal-body" ng-transclude></div>' +
              '</div>' +
            '</div>' +
          '</div>',
        restrict: 'E',
        transclude: true,
        replace: true,
        scope: true,
        link: function postLink(scope, element, attrs) {
            scope.$watch(attrs.visible, function (value) {
                if (value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
            });
            $(element).on('shown.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = true;
                });
            });
            $(element).on('hidden.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
});

app.filter('exact', function(){
  return function(items, match){
    var matching = [], matches, falsely = true;

    // Return the items unchanged if all filtering attributes are falsy
    angular.forEach(match, function(value, key){
      falsely = falsely && !value;
    });

    if(falsely){
      return items;
    }

    angular.forEach(items, function(item){ // e.g. { title: "ball" }
      matches = true;
      angular.forEach(match, function(value, key){ // e.g. 'all', 'title'
        if(!!value){ // do not compare if value is empty
          matches = matches && (key=='marketCount' ? item[key] === value : angular.lowercase(item[key]).match(angular.lowercase(value)));  
        }
      });
      if(matches){
        matching.push(item);  
      }
    });
    return matching;
  }
});
