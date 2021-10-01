angular.module('ApsilonApp').controller('masterdashboard', function ($scope, $http, sessionService, $timeout, $filter,$interval,$location) {
   
    /*end of slider*/
    $scope.datacolumns = [{ "id": "TeamA", "type": "bar", "name": "Team A" }, { "id": "TeamB", "type": "bar", "name": "Team B" }, { "id": "theDraw", "type": "bar", "name": "Draw" }];
    $scope.datapoints = [{}];
    $scope.datax = { "id": "matchName" };
    $scope.getMatchDetail = function (sportid) {
	$scope.loading=true;
        $scope.sportDetail = angular.isUndefinedOrNull;
        $scope.oddsDetail = angular.isUndefinedOrNull;
        $scope.sportid = sportid;//sourabh 170106
        $http.get('Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
            $scope.sportDetail = data.matchLst;
            $scope.oddsDetail = data.matchOdds;
		$scope.loading=false;
            getDynamicOdds();
        }).error(function (data, status, header, config) {
            $scope.loading=false;
        });
    }
    $scope.marketTimer;
    function getDynamicOdds() {
        $scope.marketTimer = $timeout(function ()//sourabh 161229
        {
            if ($scope.oddsDetail != angular.isUndefinedOrNull && $scope.oddsDetail.length > 0) {
                $http.get('Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
                    var allRunner = data.matchOdds;//sourabh 161227
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
                getDynamicOdds();

		$http.get('Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
			    //
			    $scope.sportDetail = data.matchLst;
			    $scope.oddsDetail = data.matchOdds;//sourabh 161227
			    getDynamicOdds();
			});
              //  $timeout.cancel(marketTimer);
                $scope.marketTimer = angular.isUndefinedOrNull;
            }
        }, 3000);
    }
if(localStorage.length >1)
    $scope.getMatchResult(0);
else
   $location.path('/login');

    $scope.$on("$destroy", function (event) {
               //alert("working123");
               $timeout.cancel(getDynamicOdds);
               $timeout.cancel($scope.marketTimer);
                //clearInterval(si_fancyData);
              // si_fancyData=angular.isUndefinedOrNull;
    });
    $scope.getUrl = function (type, matchid, marketid, matchname, matchdate, SportId)//sourabh 161231
    {
        switch (type) {
            case "0": return "Matchodds({MatchId: " + matchid + ",MarketId:" + marketid + ",matchName:'" + matchname.replace("'", "&quot;") + "',date:'" + matchdate + "'})"; break;
            case "1": return "Evenoddfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'" + matchname.replace("'", "&quot;") + "',sportId:4 })"; break;
            case "2": return "Sessionfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",sportId:" + SportId + ",matchName:'" + matchname.replace("'", "&quot;") + "'})"; break;
            case "3": return "Khaddalfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'" + matchname.replace("'", "&quot;") + "',sportId:4 })"; break;
            case "4": return "Lastdigit({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'" + matchname.replace("'", "&quot;") + "',sportId:4 })"; break;
            case "5": return "Updown({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'" + matchname.replace("'", "&quot;") + "',sportId:4 })"; break;
        }
    }
    $scope.accBet = function (val) {
        switch (val) {
            case 1: $scope.acc = !$scope.acc; break;
            case 2: $scope.acc1 = !$scope.acc1; break;
        }
    };

    $scope.getUserPosition = function () {

        $scope.userPosition = null;
        $scope.userOwnPosition = null;

    }
    $scope.getUserPosition();
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
    $scope.$on("$destroy", function (event) {
        $timeout.cancel($scope.marketTimer);
        $scope.marketTimer = angular.isUndefinedOrNull;
    });//sourabh 161229
    $scope.getMatchResult = function () {
        $http.get('Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
            $scope.matchResult = data.matchRslt;
            $scope.datapoints = data.matchRslt;
        }).error(function (data, status, header, config) {
            //$scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
        });
    }
    $scope.getMatchResult();
});
