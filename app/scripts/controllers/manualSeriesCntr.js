app = angular.module('ApsilonApp',["ui.bootstrap.datetimepicker"]);
app.controller('manualSeriesCntr', ['$scope', '$http', 'sessionService', '$location', '$state', 'Dialog', '$filter', '$stateParams', function ($scope, $http, sessionService, $location, $state, Dialog, $filter, $stateParams) {
    $scope.UserType = sessionService.get('type');
    $scope.SportID = '4';

   var that = this;
  
  $scope.date = {
    value: new Date(),
    showFlag: false
  };

	  $scope.minDate = new Date();

    $scope.fetchSportName = function () {


        $http.get(BASE_URL+'Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
            $scope.sprtData = data.sportData;
        }).error(function (data, status, header, config) {
            $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
        });
    }

    $scope.fetchSportName();

    $scope.getSportList = function (id) {
        $scope.GetSeriesData = [];
        $scope.SeriesID="";
        //  $scope.selectedSeries="Please Select Series";
        $scope.loading = true;
        $http.get(BASE_URL + 'Geteventcntr/getSeriesOfSport/' + id).success(function (data, status, headers, config) {

            if (data.seriesfrmApi != angular.isUndefinedOrNull) {
                $scope.GetSeriesData = data.seriesfrmDataBase;

            }
            else {
                Dialog.autohide("Record Not Found " + data, 10000);
            }
            $scope.loading = false;
        });
    }

    $scope.getSportList($scope.SportID);

    $scope.createSeries = function () {


        var seriesData = {
            sportId: $scope.SportID,
            matchName: $scope.manualSeriesName,
	    HelperID:0,
            is_manual:1,
	    openDate:1,

        }
        $http({
            method: 'POST',
            url: BASE_URL+'Geteventcntr/saveSportSeries',
            data: seriesData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            if (data.error == 0) {
                alert(data.message);

            } else {
                Dialog.autohide(data.message);
            }
        });
    };

    $scope.createMatch = function () {
	var tempDate=$("#manaualDateTime").val();

        var matchData = {
            sportId: $scope.SportID,
            seriesId: $scope.SeriesID,
            matchName: $scope.manualMatchName,
	    HelperID:0,
            is_manual:1,
	    openDate:new Date(tempDate),
            runnerName1:$scope.runnerName1,
            runnerName2:$scope.runnerName2,
            runnerName3:$scope.runnerName3,


        }
        $http({
            method: 'POST',
            url: BASE_URL+'Geteventcntr/saveSportMatch',
            data: matchData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (data) {
            if (data.error == 0) {
                alert(data.message);

            } else {
                Dialog.autohide(data.message);
            }
        });
    };


}]);

