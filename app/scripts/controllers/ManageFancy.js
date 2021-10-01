app.controller('Managefancycntr', function ($scope, $rootScope, $http, $stateParams, Dialog, $mdDialog, $location,get_userser,sessionService,Base64,$timeout,$interval,$state) {

//////////////////// Manage Fancy ////////////////////////////
   $scope.FancyListDisplay = function () {
		$scope.loading=true;
                $http.get('Lstsavemstrcontroller/GetFancyOnHeader/').success(function (data, status, headers, config) {
                    $scope.GetfancyList = data.getFancy;
                    //alert("Get");
			$scope.loading=false;
                }).error(function (data, status, header, config) { 
			$scope.loading=false;		
			});
            }
$timeout(function(){
       $scope.FancyListDisplay();
    },500);
		
	
         $scope.getFancyStatus = function (fancyId, active,MatchID) {
                var formData = { id: fancyId, active: active, HelperID: sessionService.get('HelperID'),'MatchID':MatchID }
                $http({
                    method: 'POST',
                    url: 'Lstsavemstrcontroller/updateFancyHeaderSatatus/',
                    data: formData, //forms user object
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function (data) {

                });
            };
  /*Get Fancy Result*/
            $scope.getFancyResult = function (sportId, match_id, fancy_Id) {
	
                var result1 = confirm("Are You sure want to set the Result ...");
                if (result1) {
			$scope.loading=true;
                    var result = document.getElementById('result_' + fancy_Id).value;
                    var formData = {
                        sportId: sportId,
                        match_id: match_id,
                        fancy_Id: fancy_Id,
                        result: result
                    }
		if(result!=""){
                    $http({
                        method: 'POST',
                        url: 'Lstsavemstrcontroller/updateFancyResult/',
                        data: formData, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function (data) {
                        if (data.error == 0) {
                            Dialog.autohide('|' + data.message + '|');
                            $scope.FancyListDisplay();
                        } else {
                            Dialog.autohide('|' + data.message + '|');
                            $scope.FancyListDisplay();
                        }
			$scope.loading=false;
                    });
			}
			else
			{
				 Dialog.autohide("Please enter run.");
				$scope.loading=false;
			}
                }

            }
            /*End of Get fancy Result*/

 /*Get Fancy Result*/
            $scope.getFancyUpDown = function (sportId, match_id, fancy_Id, fancyType) {
                var result1 = confirm("Are You sure want to set the Result ...");
                if (result1) {
                    var result = document.getElementById('result_' + fancy_Id).value;
                    var formData = {
                        sportId: sportId,
                        match_id: match_id,
                        fancy_Id: fancy_Id,
                        fancyType: fancyType,
                        result: result
                        , HelperID: sessionService.get('HelperID')
                    }
                    $http({
                        method: 'POST',
                        url: 'Lstsavemstrcontroller/updateKhaddalResult/',
                        data: formData, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function (data) {
                        $scope.FancyListDisplay();
                        Dialog.autohide('||' + data.message + '||');
                    });
                }

            }

            $scope.AbendendFancy = function(sportId, match_id, fancy_Id,)
            {

                var result1 = confirm("Are You sure want to declare Abandoned fancy?");
                if (result1) {
                    var result = document.getElementById('result_' + fancy_Id).value;
                    var formData = {
                        sportId: sportId,
                        match_id: match_id,
                        fancy_Id: fancy_Id,
                    }
                    $http({
                        method: 'POST',
                        url: BASE_URL+'Lstsavemstrcontroller/updateAbandonedFancyResult',
                        data: formData, //forms user object
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                        .success(function (data) {
                            $scope.FancyListDisplay();
                            Dialog.autohide('||' + data.message + '||');
                        });
                }
            }
            /*End of Get fancy Result*/
//////////////////////////////////////////////////////////////
});
