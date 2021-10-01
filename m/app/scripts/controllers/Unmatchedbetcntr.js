'use strict';//sourabh 13-dec-2016
app.controller('Unmatchedbetcntr', ['$scope', '$http', '$stateParams', 'sessionService', '$timeout', 'Dialog', '$rootScope', 'deviceDetector', 'get_userser', '$mdDialog', 'speech', '$filter', '$location', '$state', '$interval', function($scope, $http, $stateParams, sessionService, $timeout, Dialog, $rootScope, deviceDetector, get_userser, $mdDialog, speech, $filter, $location, $state, $interval) {
  
    $scope.GetUserData=function(){
        $http.get( BASE_URL+'Betentrycntr/GatBetData/' + $stateParams.marketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.matchId).success(function(data, status, headers, config) {
            $scope.UserData = data.betUserData;
        });
    }
    $scope.GetUserData();
    $scope.deleteUser = function (betId, userId) {
        var result = confirm("Are you sure want to delete Records Unmatched");
        if (result) {
            $http.get( BASE_URL+'Betentrycntr/deleteGetbetting/' + betId + '/' + userId).success(function (data, status, headers, config) {
                Dialog.autohide(data.message);
                //Dialog.autohide("Record Deleted Successfully...");
               $scope.GetUserData();
            });
        }
    }
    var Timer = $interval($scope.GetUserData, 2000);
    $scope.$on("$destroy", function(event) {
        $interval.cancel(Timer);        
    });
}]);
