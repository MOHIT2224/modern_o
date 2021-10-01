'use strict';
angular.module('ApsilonApp').controller('MatchListCntr',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$rootScope','$location','$state','Dialog','Base64','get_userser', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter,$rootScope,$location,$state,Dialog,Base64,get_userser) {
  var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
	 $("#main-menu").show();   
$scope.inplaytab =1;
    $scope.getMatchDetail = function (matchId) {
	$scope.loading = true;
        $scope.sportDetail = angular.isUndefinedOrNull;
        $scope.oddsDetail = angular.isUndefinedOrNull;
        $scope.sportid = matchId;//sourabh 170106
        $http.get( BASE_URL+'Apiadmincontroller/get_all_matches/' + $scope.sportid).success(function (data, status, headers, config) {
            //
            $scope.cricketSportDetail = data.data.cricket;
            $scope.soccerSportDetail = data.data.soccer;
            $scope.tennisSportDetail = data.data.tennis;
	    $scope.loading = false;
        }).error(function(err){
		$scope.loading = false;	
	});
    }

   $scope.getMatchDetail(0);

$scope.today = new Date();

$scope.tomorrow = new Date();
$scope.tomorrow.setDate($scope.today.getDate()+1);
$scope.inplaya = false;

$scope.countTodayTennis=0;
$scope.countTodaySoccer=0;
$scope.countTodayCricket=0;
$scope.calculateDateToday=function(d,id)
{

var tom=new Date(d);
tom=formatDate(tom);
var pre=$scope.today;
pre=formatDate(pre);
		if(tom != "NaN-NaN-NaN"){
		if(tom == pre){
			if(id == 2)
			$scope.countTodayTennis=$scope.countTodayTennis+1;
			else if(id == 1)
			$scope.countTodaySoccer=$scope.countTodaySoccer+1;
			else if(id == 4)
			$scope.countTodayCricket=$scope.countTodayCricket+1;

			return true;
}
		else{
			return false;
}
}
console.log($scope.countTodaySoccer)
}

$scope.countTomorrowTennis=0;
$scope.countTomorrowSoccer=0;
$scope.countTomorrowCricket=0;
$scope.calculateDateTomorrow=function(d,id)
{

var tom=new Date(d);
tom=formatDate(tom);
var pre=$scope.tomorrow;
pre=formatDate(pre);
	if(tom != "NaN-NaN-NaN"){
		if(tom == pre){
			if(id == 2)
			$scope.countTomorrowTennis=$scope.countTomorrowTennis+1;
			else if(id == 1)
			$scope.countTomorrowSoccer=$scope.countTomorrowSoccer+1;
			else if(id == 4)
			$scope.countTomorrowCricket=$scope.countTomorrowCricket+1;

			return true;
}
		else{
			return false;
}}
}
$scope.countFuturetenis=0;
$scope.countFutureCricket=0;
$scope.countFutureSoccer=0;
$scope.calculateDate=function(d,id)
{

var tom=new Date(d);
tom=formatDate(tom);
var pre=$scope.tomorrow;
pre=formatDate(pre);

	if(tom != "NaN-NaN-NaN"){
		if(tom > pre){
			if(id == 2)
			$scope.countFuturetenis=$scope.countFuturetenis+1;
			else if(id == 1)
			$scope.countFutureSoccer=$scope.countFutureSoccer+1;
			else if(id == 4)
			$scope.countFutureCricket=$scope.countFutureCricket+1;
			return true;
}
		else{
			return false;
}}

}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}



   $scope.saveMatch = function (paramsd) {
        
        $scope.loading = true;
        var NewOpenDate = $filter('date')(paramsd.eventDate , 'yyyy-MM-dd HH:mm:ss');


        var formData = 
        { 
            seriesId: paramsd.SeriesId, 
            matchId: paramsd.eventId, 
            matchName: paramsd.eventName, 
            openDate: paramsd.eventDate,
            sportId: paramsd.EventTypeId, 
            HelperID: sessionService.get('HelperID'),
            marketId: paramsd.marketId,
            marketName: paramsd.marketName,
            selectionId1:  paramsd.selectionId1,
            runnerName1:  paramsd.runnerName1,
            selectionId2:  paramsd.selectionId2,
            runnerName2:  paramsd.runnerName2,
            selectionId3:  paramsd.selectionId3,
            runnerName3:  paramsd.runnerName3,
            scoreboard_id:paramsd.scoreboard_id
         }
        var url = BASE_URL + "Geteventcntr/saveSportMatch";
        $http.post(url, formData).success(function (response) {
          
//var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
        //    $mdToast.show(toast);
		Dialog.autohide(response.message)
            $scope.loading = false;
         
           

        });
    }


}]);
