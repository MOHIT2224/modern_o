
app.controller('CntrAdminIndianFancy', function($scope,$http,sessionService, $timeout,$stateParams,$document,Base64,$mdToast,$state){
	

var authdata = Base64.encode(sessionService.get('user') + ':'+ sessionService.get('lgPassword'));
$http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;

   

    $scope.GetIndianFancy=function()
    {

        if($state.current.name=="dashboard.CntrAdminIndianFancy")
        {
            $scope.callFancy= $timeout(function(){
                $http.get(BASE_URL+'Apicontroller/get_indian_session/'+$stateParams.matchId).success(function (data, status, headers, config) {

                    if(data.error)
                    {
                        $scope.Message = data.message;
                        $scope.GetindianfancyData=[];
                    }
                    else
                    {
                        $scope.GetindianfancyData = data.data;
                    }
                    $scope.GetIndianFancy();


                });
            },1000);
        }

    }
    $scope.GetIndianFancy();

 $scope.saveIndianSeries = function (indianseries) {

        var formData = { SelectionId: indianseries.SelectionId, RunnerName: indianseries.RunnerName, LayPrice1: indianseries.LayPrice1, LaySize1: indianseries.LaySize1,BackSize1:indianseries.BackSize1, BackPrice1: indianseries.BackPrice1, GameStatus: indianseries.GameStatus,FinalStatus:indianseries.FinalStatus,is_exists:indianseries.is_exists ,match_id:indianseries.match_id,super_admin_fancy_id:indianseries.id}
        var url = BASE_URL + "Apicontroller/save_indian_session";
        $http.post(url, formData).success(function (response) {
		if(!response.error){
    indianseries.is_exists=1;

}
 var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);
       
        });
    }




});


 
