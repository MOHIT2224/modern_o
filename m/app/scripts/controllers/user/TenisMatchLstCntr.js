'use strict';
angular.module('ApsilonApp').controller('TenisMatchLstCntr',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter) {
   
    
    $scope.getMatchDetail = function (matchId) {
        $scope.sportDetail = angular.isUndefinedOrNull;
        $scope.oddsDetail = angular.isUndefinedOrNull;
        $scope.sportid = matchId;//sourabh 170106
        $http.get( BASE_URL+'Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
            $scope.sportDetail = data.matchLst;
            $scope.oddsDetail = data.matchOdds;//sourabh 161227
            getDynamicOdds();
        });
    }
    var marketTimer;
    function getDynamicOdds() 
    {
       console.log("VVVV");
    }
    $scope.$on("$destroy", function (event) {
               //alert("working123");
               //$timeout.cancel(getDynamicOdds);
               //$interval.cancel(getDynamicOdds);
               $timeout.cancel(marketTimer);
                //clearInterval(si_fancyData);
              // si_fancyData=angular.isUndefinedOrNull;
    });
    $scope.getMatchDetail(2);
    $scope.saveMatchoddsResult=function(MatchId, sportId,marketId, vSelectionID,model_result, sportName, MatchName, matchodds, selectionName1){
       //
         var marketData = {
            Sport_id: sportId,
            Match_id: MatchId,
            market_id: marketId,
            selectionId: vSelectionID,
            result: model_result,
            isFancy: 1,
            sportName: sportName,
            matchName: MatchName,
            MarketName: matchodds,
            selectionName: selectionName1
        }
        //$timeout.cancel(marketTimer);
        //marketTimer = angular.isUndefinedOrNull;
        $http({ method: 'POST', url: BASE_URL+'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }).success(function (data) {
               try {  $scope.message = data.status.message;console.log("working "+data.status.message); }
               catch (e) { console.log(data.status.error); }
        });
        console.log("working ");
    }
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
    $scope.getMatchResult = function () {
        $http.get( BASE_URL+'Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
            $scope.matchResult = data.matchRslt;
            $scope.datapoints = data.matchRslt;
        }).error(function (data, status, header, config) {
            //$scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
        });
    }
    $scope.getMatchResult();
    $scope.getOddCalcVal = function (a, b)//sourabh 161231
    {
        var x = 0, y = 0, z = 0;
        if (a != angular.isUndefinedOrNull) {
            x = a;
            if (b != angular.isUndefinedOrNull) y = b;
        }
        z = parseFloat((parseFloat(x) + parseFloat(y)).toFixed(2));
        if (z > 0) return z; else return "-";
    }
    $scope.$on("$destroy", function (event) { $timeout.cancel(marketTimer); marketTimer = angular.isUndefinedOrNull; });//sourabh 161229
}]);
