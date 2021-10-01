app.controller('NewChipHistoryCntr', function ($scope, $http, $filter, sessionService, $mdDialog, $rootScope, $location,Dialog) {
    $scope.backopti = false;
    if ($rootScope.HelperAllRights != angular.isUndefinedOrNull && $rootScope.HelperAllRights.ChipSummary == 0) { $location.path('/dashboard/Home'); }//170213
    $scope.doTheBack = function (GTuserType, GTUseruserId, GTUserName) {
        $http.get( BASE_URL+'Lstsavemstrcontroller/getParentData/' + GTUseruserId).success(function (data, status, headers, config) {
            
            //$scope.userData1=data.parentData;
            //$scope.Get_chipSummery(data.parentData[0].usetype,data.parentData[0].mstrid,data.parentData[0].mstruserid);
            $scope.Get_chipSummery(data.parentData[0].usetype, data.parentData[0].mstrid, data.parentData[0].mstruserid);
            if (sessionService.get('type') == data.parentData[0].usetype)
                $scope.backopti = false;
            else
                $scope.backopti = true;
        });
    };
    $scope.Get_chipSummery = function (userType, userId, userName) {
	$scope.loading=true;
        $scope.GTUserName = userName;
        $scope.GTUseruserId = userId;
        $scope.GTuserType = userType;
        $scope.totalSumP = 0;
        $scope.totalSumM = 0;
        $http.get( BASE_URL+'Betentrycntr/NewChip_history/' + userId + '/' + userType).success(function (data, status, headers, config) {
            $scope.user_chipsP = data.user_chipsP;
            $scope.user_chipsM = data.user_chipsM;
            for (var i = 0; i < $scope.user_chipsP.result_array.length; i++) {
                $scope.totalSumP = parseFloat($scope.totalSumP) + parseFloat($scope.user_chipsP.result_array[i].PUsum);
            }
            for (var i = 0; i < $scope.user_chipsM.result_array.length; i++) {
                $scope.totalSumM = parseFloat($scope.totalSumM) + parseFloat($scope.user_chipsM.result_array[i].Musum);
            }
		$scope.loading=false;
        }).error(function(err){
		$scope.loading=false;
	});
        if (sessionService.get('type') == userType)
            $scope.backopti = false;
        else
            $scope.backopti = true;
    }

    $scope.CompanySettlement = function(userId)
    {
        var isTrue=confirm("Are you sure you want to do company settlement?");
        if(isTrue)
        {
            $scope.loading=true;
            $http.get(BASE_URL+'Apiadmincontroller/reset_user_settlement/' + userId).success(function (data, status, headers, config) {
                $scope.loading=false;
                $scope.Get_chipSummery(sessionService.get('type'), sessionService.get('user_id'), sessionService.get('user'));
                Dialog.autohide(data.message);
            })
        }

    }
    $scope.Get_chipSummery(sessionService.get('type'), sessionService.get('user_id'), sessionService.get('user'));
    $scope.BackPrntId = sessionService.get('user_id');
    $scope.BackPrnttype = sessionService.get('type');
    $scope.BackPrntname = sessionService.get('user');
    $scope.showClearchip = function (node, type) {
        $mdDialog.show({
            controller: showClearChipController,
            templateUrl: 'app/scripts/directives/popupform/clear_chip.html',
            locals: { prntScope: $scope, node: node, type: type },
            clickOutsideToClose: false,
            fullscreen: false,
        });
    }
    function showClearChipController($scope, $mdDialog, prntScope, node, type, Dialog) {
        $scope.node = node;
        if (type == 1) {
            if (node.PUsum != angular.isUndefinedOrNull) {
                $scope.txtChip = parseFloat(node.PUsum);
                $scope.Amount = parseFloat(node.PUsum);
                $scope.txtAmount = parseFloat(node.PUsum);
            }
        }
        else {
            if (node.Musum != angular.isUndefinedOrNull) {
                $scope.txtChip = parseFloat(node.Musum);
                $scope.Amount = parseFloat(node.Musum);
                $scope.txtAmount = parseFloat(node.Musum);
            }
        }
        $scope.txtType = type;
        $scope.txtUserid = node.UserID;
        $scope.txtMstrname = node.mstrname;
        $scope.insClearChip = function () {
            
            var formData = {
                UserID: $scope.txtUserid,
                LoginID: prntScope.GTUseruserId,
                CrDr: $scope.txtType,
                Chips: $scope.txtChip,
                IsFree: 2,
                Narration: $scope.remark==undefined ? '' :$scope.remark,
                HelperID: sessionService.get('HelperID')
            }
            $http({
                method: 'POST',
                url: BASE_URL+'Createmastercontroller/submitClearChip/',
                data: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function (data) {
                Dialog.autohide(data.message);
                /* prntScope.GTUseruserId;
                 prntScope.GTuserType;
                 prntScope.GTUserName;*/
                prntScope.Get_chipSummery(prntScope.GTuserType, prntScope.GTUseruserId, prntScope.GTUserName);
            });
        }
        $scope.hide = function () { $mdDialog.hide(); };
    }
});
