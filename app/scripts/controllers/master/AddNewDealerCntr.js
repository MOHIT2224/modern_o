'use strict';
angular.module('ApsilonApp').controller('AddNewDealerCntr',['$scope','$mdDialog', '$http', 'sessionService','$filter','$stateParams', function ($scope, $mdDialog,$http, sessionService,$filter,$stateParams) {
   
	$scope.user={};
	$scope.getDate = new Date();
	$scope.submitForm_Users = function (user) {
		
		if (user.partnership == undefined) {  var partnership = 0; } else { var partnership = user.partnership; }

		var formData = {
			username: user.username,
			master_name: user.master_name,
			password: user.password,
			remarks: 'N_A',
			typeId: 2,
			FromDate: user.dt,
			parantId: $stateParams.ParentId,
			partner: 0,
			Commission: user.Commission,
			maxProfit: 0,
			maxLoss: 0,
			maxStake: 0,                        
			sessionCommission: user.sessionCommission,
			otherCommission: user.otherCommission,
			betDelay: 0,
			PntPartenerShip: 0,
			HelperID: sessionService.get('HelperID'),
			GngInPlayStake: 0
		}
		$scope.loading= false;
		$http({
			method: 'POST',
			url: 'Createmastercontroller/submitCreateMasterData/',
			data: formData,
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		}).success(function (data) {
            $scope.loading= true;
			if (data.error == 0) {
				alert(data.message);
			}
			else {
				alert(data.message);
				$mdDialog.hide();
			}
		});
	};
	$scope.goBack=function(){
		window.history.back();
	}
}]);
