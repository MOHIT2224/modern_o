
app.controller('CntrBetfairFancy', function($scope,$http,sessionService, $timeout,$stateParams,$document,Base64,$mdToast,$rootScope){


    var authdata = Base64.encode(sessionService.get('user') + ':'+ sessionService.get('lgPassword'));
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;

    $scope.getBetfairSession=function()
    {
        var url = BASE_URL + "Apiadmincontroller/betfair_session/";
        if($rootScope.Provider=='betfair')
        {
            url = BASE_URL + "Apiadmincontroller/betfair_session/";
        }
        else
        {
            url = BASE_URL + "Apiadmincontroller/get_betfair_session/";
        }
        $http.get(url+$stateParams.matchId+'/'+$stateParams.sportId).success(function (data, status, headers, config) {

            if(data.error)
            {
                $scope.Message = data.message;
                $scope.GetbetfairData=[];
            }
            else
            {
                $scope.GetbetfairData = data.data;
            }



        });
    }
    $scope.getBetfairSession();

    $scope.saveBetfairSeries = function (betfair) {
        $scope.loading=true;
        var formData={"market_id":betfair.market_id,"market_name":betfair.market_name,"match_id":betfair.match_id,"sports_id":betfair.sports_id,"series_id":betfair.series_id,"market_runner_json":betfair.market_runner_json};
        var url = BASE_URL + "Apiadmincontroller/save_betfair_market";
        if($rootScope.Provider=='betfair')
        {
            url = BASE_URL + "Apiadmincontroller/save_betfair_market";
        }
        else
        {
            url = BASE_URL + "Apiadmincontroller/save_betfair_session";
        }

        $http.post(url, formData).success(function (response) {
            if(!response.error){
                betfair.is_exists=1;

            }

            var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);
            $scope.loading=false;
        }).error(function(err){
            $scope.loading=false;
        });
    }




});


 
