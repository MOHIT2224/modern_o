app.controller('sportSettingcntr', function($scope,$http, Dialog) {

    $scope.GetSport = function () {
        $http.get(BASE_URL + 'Geteventcntr/getAllSports').success(function (data, status, headers, config) {
            $scope.sprtData = data.sportData;
        }).error(function (data, status, header, config) {

        });
    }

    $scope.changeSportStatus = function(id,status)
    {

        var active=status==true ? 1 : 0;
        var formData={id:id,active:active}
        $scope.loading=true;
        $http.post(BASE_URL+'Geteventcntr/updateSportById',formData).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
            $scope.GetSport();
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
});