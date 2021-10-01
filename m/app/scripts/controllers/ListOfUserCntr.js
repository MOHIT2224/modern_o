'use strict';
angular.module('ApsilonApp').controller('ListOfUserCntr',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter) {
        
        $scope.refresh_tree = function () {
            $http.get( BASE_URL+'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
                //
                $scope.UserData = data.tree;
            });
        }
        $scope.refresh_tree();   
}]);