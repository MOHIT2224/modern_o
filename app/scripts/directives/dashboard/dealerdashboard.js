'use strict';
angular.module('ApsilonApp').controller('dealerdashboard',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$location','$state', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter,$location,$state) {
   $scope.getMatchResult = function () {
	//$scope.loading=true;
        $http.get('Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
            $scope.TmatchResult = data.matchRslt;
		$scope.loading=false;
            getDynamicOdds();
        }).error(function (data, status, header, config) {
            $scope.loading=false;
        });
        
    }
if(localStorage.length >1)
{
    $scope.getMatchResult();
    if(sessionService.get('TmatchResult')!=angular.isUndefinedOrNull)
    {

        $scope.TempArray = JSON.parse(sessionService.get('TmatchResult'));
        $scope.matchResult = $scope.TempArray;
    }
}

else
   $location.path('/login');

    
    $scope.marketTimer;
    function getDynamicOdds() 
    {
        $scope.marketTimer = $timeout(function () {
            if($state.current.name=='masterDashboard.Home')
            {
                $http.get('Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
                    $scope.TmatchResult = data.matchRslt;
                }).error(function (data, status, header, config) {
                    $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
                });
                getDynamicOdds();
            }

        }, 5000);
    }
    $scope.$on("$destroy", function (event) {
               
        $timeout.cancel($scope.marketTimer);
              
    });

    $scope.updateMatchResult = function(){

        if($scope.TmatchResult!=angular.isUndefinedOrNull)
        {
            sessionService.set('TmatchResult',JSON.stringify($scope.TmatchResult));
            //alert('cart updated');
            $scope.matchResult = JSON.parse(sessionService.get('TmatchResult'));

        }

    };
    $scope.$watch('TmatchResult', $scope.updateMatchResult, true);
    $scope.getUrl = function (type, matchid, marketid, matchname, matchdate,SportId)//sourabh 161231
    {
        switch (type) {
            case "0": return "Matchodds({MatchId: " + matchid + ",MarketId:" + marketid + ",matchName:'" + matchname.replace("'", "&quot;") + "',date:'" + matchdate + "',sportId:'"+SportId+"'})"; break;
            case "1": return "Evenoddfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "2": return "Sessionfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",sportId:"+SportId+",matchName:'"+matchname.replace("'", "&quot;")+"'})"; break;
            case "3": return "Khaddalfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "4": return "Lastdigit({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "5": return "Updown({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
        }
    }
    
    
    $scope.$on("$destroy", function (event) { $timeout.cancel($scope.marketTimer); $scope.marketTimer = angular.isUndefinedOrNull; });//sourabh 161229
}]);
