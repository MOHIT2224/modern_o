app.controller('MatchBetListCntr', ['$scope', '$http','$stateParams','sessionService', '$timeout', '$log', '$mdDialog', function ($scope, $http,$stateParams,sessionService, $timeout, $log, $mdDialog){
    $scope.loading=true;
    $scope.GetUserData=function(){
        $http.get( BASE_URL+'Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function(data, status, headers, config) {
            $scope.loading=false;
            $scope.UserData = data.betUserData;
        });
    }
    $scope.GetUserData();
    $scope.DeleteBets=function(MstCode,IsMatched,MatchId,MarketId,UserId){
           $scope.loading=true;

        var result = confirm("Are you sure want to delete Records Unmatched");
       if(IsMatched==1 && result){
            $http.get( BASE_URL+'Betentrycntr/deleteGetbettingmat/' + MstCode + '/' + UserId+'/'+MarketId).success(function(data, status, headers, config) {
                  $scope.loading=false;
                $scope.GetUserData();
               
            });
        }else if(IsMatched==0 && result){
            $http.get( BASE_URL+'Betentrycntr/deleteGetbetting/' + MstCode + '/' + UserId).success(function(data, status, headers, config) {
                   $scope.loading=false;
                $scope.GetUserData();
               
            });
        } 
    }
    
}]);
