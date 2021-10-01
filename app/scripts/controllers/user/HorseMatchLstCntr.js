'use strict';
angular.module('ApsilonApp').controller('HorseMatchLstCntr',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter) {
   
    
    $scope.getMatchDetail = function (matchId) {
        $scope.sportDetail = angular.isUndefinedOrNull;
        $scope.oddsDetail = angular.isUndefinedOrNull;
        $scope.sportid = matchId;//sourabh 170106
        $http.get('Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
            
            $scope.sportDetail = data.matchLst;
            $scope.oddsDetail = data.matchOdds;//sourabh 161227
            getDynamicOdds();
        });
    }
    var marketTimer;
    function getDynamicOdds() 
    {
        marketTimer = $timeout(function ()//sourabh 161229
        {
            if ($scope.oddsDetail != angular.isUndefinedOrNull && $scope.oddsDetail.length > 0) {
                $http.get('Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
                   //
                    var allRunner = data.matchOdds;//sourabh 161227
                    var sportDetail = data.matchLst;
                    if ($scope.oddsDetail != angular.isUndefinedOrNull)
                        $scope.oddsDetail.find(function (item, itemIndex) {
                            var selectedRunner = $filter('filter')(allRunner, { marketId: $scope.oddsDetail[itemIndex].marketId });
                            if (selectedRunner != angular.isUndefinedOrNull && selectedRunner.length > 0 && selectedRunner[0].status == "OPEN" && selectedRunner[0].totalMatched > $scope.oddsDetail[itemIndex].totalMatched) {
                                try { $scope.oddsDetail[itemIndex].runners[0].ex.availableToBack[0].price = selectedRunner[0].runners[0].ex.availableToBack[0].price; } catch (e) { try { $scope.oddsDetail.runners[0].ex.availableToBack[0].price = "" } catch (e) { } }
                                try { $scope.oddsDetail[itemIndex].runners[1].ex.availableToBack[0].price = selectedRunner[0].runners[1].ex.availableToBack[0].price; } catch (e) { try { $scope.oddsDetail.runners[1].ex.availableToBack[0].price = "" } catch (e) { } }
                                try { $scope.oddsDetail[itemIndex].runners[2].ex.availableToBack[0].price = selectedRunner[0].runners[2].ex.availableToBack[0].price; } catch (e) { try { $scope.oddsDetail.runners[2].ex.availableToBack[0].price = "" } catch (e) { } }
                                try { $scope.oddsDetail[itemIndex].runners[0].ex.availableToLay[0].price = selectedRunner[0].runners[0].ex.availableToLay[0].price; } catch (e) { try { $scope.oddsDetail.runners[0].ex.availableToLay[0].price = "" } catch (e) { } }
                                try { $scope.oddsDetail[itemIndex].runners[1].ex.availableToLay[0].price = selectedRunner[0].runners[1].ex.availableToLay[0].price; } catch (e) { try { $scope.oddsDetail.runners[1].ex.availableToLay[0].price = "" } catch (e) { } }
                                try { $scope.oddsDetail[itemIndex].runners[2].ex.availableToLay[0].price = selectedRunner[0].runners[2].ex.availableToLay[0].price; } catch (e) { try { $scope.oddsDetail.runners[2].ex.availableToLay[0].price = "" } catch (e) { } }
                            }
                            else {
                                //start for set Result
                                //
                                if (selectedRunner[0].status == "CLOSED") {
                                    var vSelectionID = $filter('filter')(selectedRunner[0].runners,{status : "WINNER"})[0].selectionId;
                                    var MatchId = $filter('filter')(sportDetail,{marketid : selectedRunner[0].marketId})[0].matchid;
                                    var sportName = $filter('filter')(sportDetail,{marketid : selectedRunner[0].marketId})[0].sportname;
                                    var MatchName = $filter('filter')(sportDetail,{marketid : selectedRunner[0].marketId})[0].matchName;
                                    
                                    var selectionName1 = "";
                                    $http.get('Geteventcntr/getSelectionName/' + selectedRunner[0].marketId + '/' + MatchId).success(function (data, status, headers, config) {                                                
                                               
                                               var sportId=$filter('filter')(data.RunnerValue,{selectionId : vSelectionID})[0].sportId;
                                               selectionName1=$filter('filter')(data.RunnerValue,{selectionId : vSelectionID})[0].selectionName;
                                                if(selectionName1!=""){

                                                // MatchId; sportId; selectedRunner[0].marketId; vSelectionID;sportName; MatchName;selectionName1;
                                                    $scope.saveMatchoddsResult(MatchId, sportId,selectedRunner[0].marketId, vSelectionID, 1, sportName, MatchName, 'match Odds', selectionName1);  
                                                                                              
                                                }
                                                   
                                    });
                                   
                                }
                                //end of set Result
                                try { $scope.oddsDetail.runners[0].ex.availableToBack[0].price = "" } catch (e) { }
                                try { $scope.oddsDetail.runners[1].ex.availableToBack[0].price = "" } catch (e) { }
                                try { $scope.oddsDetail.runners[2].ex.availableToBack[0].price = "" } catch (e) { }
                                try { $scope.oddsDetail.runners[0].ex.availableToLay[0].price = "" } catch (e) { }
                                try { $scope.oddsDetail.runners[1].ex.availableToLay[0].price = "" } catch (e) { }
                                try { $scope.oddsDetail.runners[2].ex.availableToLay[0].price = "" } catch (e) { }
                            }
                        });
                });
                getDynamicOdds();
            }
            else {
                $scope.oddsDetail = angular.isUndefinedOrNull;
                $timeout.cancel(marketTimer);
                marketTimer = angular.isUndefinedOrNull;
            }
        }, 3000);
    }
    $scope.$on("$destroy", function (event) {
               //alert("working123");
               //$timeout.cancel(getDynamicOdds);
               //$interval.cancel(getDynamicOdds);
               $timeout.cancel(marketTimer);
                //clearInterval(si_fancyData);
              // si_fancyData=angular.isUndefinedOrNull;
    });
    $scope.getMatchDetail(7);
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
        $http({ method: 'POST', url: 'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }).success(function (data) {
               try {  $scope.message = data.status.message;console.log("working "+data.status.message); }
               catch (e) { console.log(data.status.error); }
        });
        console.log("working ");
    }
    $scope.getUrl = function (type, matchid, marketid, matchname, matchdate,SportId)//sourabh 161231
    {
        switch (type) {
            case "0": return "Matchodds({MatchId: " + matchid + ",MarketId:" + marketid + ",matchName:'" + matchname.replace("'", "&quot;") + "',date:'" + matchdate + "'})"; break;
            case "1": return "Evenoddfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "2": return "Sessionfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",sportId:"+SportId+",matchName:'"+matchname.replace("'", "&quot;")+"'})"; break;
            case "3": return "Khaddalfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "4": return "Lastdigit({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "5": return "Updown({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
        }
    }
    $scope.getMatchResult = function () {
        $http.get('Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
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
