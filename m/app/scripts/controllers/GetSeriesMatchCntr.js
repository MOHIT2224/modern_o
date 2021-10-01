'use strict';
angular.module('ApsilonApp').controller('SeriesMatchCntr',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$rootScope','$location','$state','Dialog','Base64','get_userser','$mdToast', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter,$rootScope,$location,$state,Dialog,Base64,get_userser,$mdToast) {
 var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
    $scope.inplaytab =4; 
    $scope.SportName="Cricket";

 $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    $scope.currentPage = 1;
    $scope.entryLimit = 30;
 $scope.getSportList=function(id,sport)
    {
      $scope.inplaytab=id;
      $scope.SportName=sport;
      $scope.loading = true;
      $http.get(BASE_URL+'Geteventcntr/getSeriesOfSport/' + $scope.inplaytab).success(function (data, status, headers, config) {
          
        if (data.seriesfrmApi != angular.isUndefinedOrNull) {
              $scope.GetSeriesData = data;
	      $scope.totalitem=$scope.GetSeriesData.seriesfrmApi.length;


             // console.log($scope.GetSeriesData.seriesfrmApi.length);
		//$scope.onEnd();
             
          }
          else {
              Dialog.autohide("Record Not Found " + data, 10000);
          }
          $scope.loading = false;
      });
    }
    $scope.currentPage1 = 1;
    $scope.entryLimit = 30;
     $scope.getMarketList=function(seriesId,index)
    {
		 $scope.loading = true;
		$scope.IsShowIndex=index;
	$scope.seriesId=seriesId;
	 $scope.GetMatchData=[];
    $http.get(BASE_URL+'Geteventcntr/getMatchOfSport/' + $scope.inplaytab + '/' + seriesId).success(function (data, status, headers, config) {
        
        if (data.matchfrmApi != angular.isUndefinedOrNull) {
            $scope.GetMatchData = data;

             //$scope.onEnd();
        }
        else {
            Dialog.autohide("Record Not Found "+data,10000);
        }
        $scope.loading = false;
    });
	}
   $scope.saveSeries = function (id, name) {
        
        $scope.loading = true;
        var formData = { matchId: id, matchName: name,sportId: $scope.inplaytab, HelperID: sessionService.get('HelperID') }
        var url = BASE_URL + "Geteventcntr/saveSportSeries";
        $http.post(url, formData).success(function (response) {
           
            var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);
           
	    $scope.getSportList($scope.inplaytab,$scope.SportName);
            $scope.loading = false;
            $rootScope.$broadcast('changeSidebar_Series', {});
            //$scope.onEnd();
        });
    }
  $scope.saveMatch = function (paramsd) {
       
        $scope.loading = true;
        var NewOpenDate = $filter('date')(paramsd.eventDate , 'yyyy-MM-dd HH:mm:ss');

        var formData = 
        { 
            seriesId: $scope.seriesId, 
            matchId: paramsd.eventId, 
            matchName: paramsd.eventName, 
            openDate: paramsd.eventDate,
            sportId: $scope.inplaytab, 
            HelperID: sessionService.get('HelperID'),
            marketId: paramsd.marketId,
            marketName: paramsd.marketName,
            selectionId1:  paramsd.selectionId1,
            runnerName1:  paramsd.runnerName1,
            selectionId2:  paramsd.selectionId2,
            runnerName2:  paramsd.runnerName2,
            selectionId3:  paramsd.selectionId3,
            runnerName3:  paramsd.runnerName3,
            startDate:paramsd.startDate,
            market_runner_json:paramsd.market_runner_json,
            scoreboard_id:paramsd.scoreboard_id
         }
        var url = BASE_URL + "Geteventcntr/saveSportMatch";
        $http.post(url, formData).success(function (response) {
          
var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);
            //$mdToast.hide();
            $scope.loading = false;
            if(response.error==1)
            {
                
                $rootScope.$broadcast('changeSidebar_Match', {sportsId:$scope.inplaytab,seriesId:$scope.seriesId});
            }
           $rootScope.$broadcast('changeSidebar_Match', {sportsId:$scope.inplaytab,seriesId:$scope.seriesId});
           //$scope.onEnd();
        });
    }

    	  $scope.dropdownInplay={};
          $scope.printParent2 = function ($event, matchInfo,type,ind) {
	
                var root = $scope;
                var evt = $event;
                $scope.CreateFancyMatchInfo = matchInfo;
		$scope.InplayClick=ind;
		if(type==2)
		{
			for(var j=0;j<$scope.GetMatchData.matchfrmApi.length;j++)
			{
				if(ind!=j+'u'){
				$scope.dropdownInplay['field_'+j+'u']=false;
				}
			}
			$scope.dropdownInplay['field_'+ind]=!$scope.dropdownInplay['field_'+ind];
		}
		else
		{
			for(var j=0;j<$scope.GetMatchData.matchfrmApi.length;j++)
			{
				if(ind!=j){
				$scope.dropdownInplay['field_'+j]=false;
				}
			}
			$scope.dropdownInplay['field_'+ind]=!$scope.dropdownInplay['field_'+ind];
		}
		
                root.xPosi = evt.clientX;
                root.yPosi = evt.clientY;
            }
 $scope.expandAll = function(expanded) {
        // $scope is required here, hence the injection above, even though we're using "controller as" syntax
        $scope.$broadcast('onExpandAll', {
          expanded: expanded
        });
      };
}]);
app.directive('expand', function () {
            function link(scope, element, attrs) {
                scope.$on('onExpandAll', function (event, args) {
                    scope.expanded = args.expanded;
                });
            }
            return {
                link: link
            };
        });
 app.directive("repeatEnd", function(){
            return {
                restrict: "A",
                link: function (scope, element, attrs) {
                    if (scope.$last) {
                        scope.$eval(attrs.repeatEnd);
                    }
                }
            };
        });
 app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    }
});
