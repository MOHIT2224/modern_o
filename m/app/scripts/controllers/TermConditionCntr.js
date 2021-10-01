app.controller('TermConditionCntr',['$scope','$http', 'sessionService', '$timeout', '$filter','$stateParams','Dialog','Base64',"$sce", function ($scope,$http, sessionService, $timeout,$filter,$stateParams,Dialog,Base64,$sce) {
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;

    $scope.SaveTermCondition=function(value)
    {

        if(value.$valid) {
            var LinkUrl = "";
            var dataArray = {};
            $scope.loading = true;
            LinkUrl = "Apiadmincontroller/save_global_terms_conditions";
            dataArray = {"terms_conditions": $scope.term_text};
            $http.post(BASE_URL + LinkUrl, dataArray).success(function (data, status, headers, config) {
                Dialog.autohide(data.message);
                $scope.loading = false;
            }).error(function (data, status, headers, config) {
                $scope.loading = false;
            });
        }
    }
    $scope.ShowTerAndCondition=function()
    {

        $http.get('setting/index/').success(function (data, status, headers, config) {
            $timeout(function(){
                $scope.term_text=data[0].terms_conditions;

            },1000);
            $scope.ShowTerm=$sce.trustAsHtml(data[0].terms_conditions);
        })

    }
    $scope.ShowTerAndCondition();

}])
app.directive("ckEditor", function() {
    return {
        require: '?ngModel',
        link: function(scope, elm, attr, ngModel) {
            var ck = CKEDITOR.replace(elm[0]);

            if (!ngModel) return;

            ck.on('instanceReady', function() {
                ck.setData(ngModel.$viewValue);
            });

            function updateModel() {
                scope.$apply(function() {
                    ngModel.$setViewValue(ck.getData());
                });
            }

            ck.on('change', updateModel);
            ck.on('key', updateModel);
            ck.on('dataReady', updateModel);


            ngModel.$render = function(value) {
                ck.setData(ngModel.$viewValue);
            };
        }
    };
});