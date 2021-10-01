'use strict';//sourabh 13-dec-2016
angular.module('ApsilonApp').controller('userdashboard', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter) {
    /*start slider In Master dashboard */
    var slider = $('.bxslider').bxSlider({
        mode: 'horizontal', //mode: 'fade',
        speed: 500,
        auto: true,
        minSlides: 1,
        maxSlides: 1,
        adaptiveHeight: true,
        randomStart: true,
        slideWidth: 1200,
        slideMargin: 2,
        infiniteLoop: true,
        hideControlOnEnd: true,
        useCSS: false
    });
    $(".bx-pager-link").click(function () {
        //console.log('bla');
        $('.bx-pager-item a').click(function(e){
            var i = $(this).index();
            slider.goToSlide(i);
            slider.stopAuto();
            var restart=setTimeout(function(){
                slider.startAuto();
            },500);

            return false;
        });
    });

    /*end of slider*/
    $scope.datacolumns = [{ "id": "TeamA", "type": "bar", "name": "Team A" }, { "id": "TeamB", "type": "bar", "name": "Team B" }, { "id": "theDraw", "type": "bar", "name": "Draw" }];
    $scope.datapoints = [{}];   
    $scope.datax = { "id": "matchName" };
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
        marketTimer = $timeout(function ()//sourabh 161229
        {
            if ($scope.oddsDetail != angular.isUndefinedOrNull && $scope.oddsDetail.length > 0) {
                $http.get( BASE_URL+'Geteventcntr/getUserMatchLst/' + $scope.sportid).success(function (data, status, headers, config) {
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
    $scope.getMatchDetail(0);
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
});