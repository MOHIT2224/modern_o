app.controller('ApkVersionCntr',['$scope','$http', 'sessionService', '$timeout', '$filter','$stateParams','Dialog','Base64', function ($scope,$http, sessionService, $timeout,$filter,$stateParams,Dialog,Base64) {
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;

    $scope.SaveApkVersion=function(value)
    {

        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        LinkUrl ="Apiadmincontroller/save_apk_version";
        dataArray={"version_code":value.Code,"version_name":value.name};
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }

    $scope.UpdateApkStatus=function(value)
    {

        var apkstatus=value==true ? 1 : 0;
        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        LinkUrl ="Apiadmincontroller/update_apk_status";
        dataArray={"status":apkstatus};
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
            $scope.GetApkInfo();
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
    $scope.apk={};
    $scope.GetApkInfo=function()
    {
        var LinkUrl = "";

        $scope.loading=true;
        LinkUrl ="Apimobilecontroller/chkAppVersion";
        $http.get(BASE_URL+LinkUrl).success(function(data, status, headers, config){

            if(data.error==0)
            {
                $scope.apk.Code=data.version_code;
                $scope.apk.name=data.version_name;
                $scope.apk.status=data.status;
            }
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
}])
