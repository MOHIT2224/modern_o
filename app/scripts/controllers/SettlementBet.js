app.controller('SettlementBetCtr',['$scope', '$http', 'sessionService', '$location','$state','Dialog','$filter','$stateParams','Base64', function ($scope, $http, sessionService, $location,$state,Dialog,$filter,$stateParams,Base64) {

 var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
$http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
$scope.lstSettlementBet=[];
 $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

$scope.search={};
$scope.search.active="";
$scope.search.SportID="";
$scope.search.Name="";
$scope.entryLimit=15;
$scope.maxSize=10;
$scope.search.from_date =0;
$scope.search.to_date=0;
$scope.MaxDate1=new Date();
$scope.MaxDate2=new Date();
$scope.GetSettlementBetList = function(page)
{
	
	$scope.currentPage=page;
	var fromDate=0;
	var toDate=0;
	if($scope.search.from_date == "1970-01-01" || $scope.search.from_date==0)
	{
		fromDate =0;
	}
	else
	{
		fromDate=$filter('date')($scope.search.from_date, 'yyyy-MM-dd'); 
	}
	if($scope.search.to_date == "1970-01-01" || $scope.search.to_date==0)
	{
		toDate =0;
	}
	else
	{
		toDate=$filter('date')($scope.search.to_date, 'yyyy-MM-dd'); 
	}
	var ReportData = {
		match_name: $scope.search.Name,
		bet_deleted:$scope.search.active,
                sport_id: $scope.search.SportID,
		page_no: page,
 		limit:$scope.entryLimit,
		from_date:fromDate,
		to_date:toDate
             
            }
	$scope.loading=true;
	$http({
                        method: 'POST',
                        url: BASE_URL+ 'Apicontroller/getSettleMatchList/',
			data:ReportData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data, status, headers, config) {

			if(data.error==0)
			{
                $scope.lstSettlementBet=data.data.data;
                $scope.loading=false;
                $scope.totalCount =data.data.total;
			}
			else
			{
                Dialog.autohide(data.message);
                $scope.loading=false;

			}

		});
}

$scope.Reset=function()
{
$scope.search.active="";
$scope.search.SportID="";
$scope.search.Name="";
$scope.search.from_date =0;
$scope.search.to_date=0;
$scope.MaxDate1=new Date();
$scope.MaxDate2=new Date();
$scope.GetSettlementBetList(1);	
}
$scope.GetSport =function()
{
$http.get(BASE_URL+'Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
                $scope.sprtData = data.sportData;
            }).error(function (data, status, header, config) {
               
            });
}
$scope.setDateFun=function(type)
{
	
	if(type==2)
	{
  $scope.MaxDate1=$scope.search.to_date;
	}
	else
	{
	 $scope.minDate1=$scope.search.from_date;
	}
	
}
$scope.GetSport();
   $scope.deleteBet = function (matchs,type) {
	var msg="";
	if(type==0)
		{
			msg="Are you sure want to delete bet Records?";
		}
		else
		{
			msg="Are you sure want to delete bet records permanently?";
		}
        var result = confirm(msg);
        if (result) {
		$scope.loading=true;
		var durl="";
		if(type==0)
		{
			durl="Betentrycntr/deleteBetByMatchId/";
		}
		else
		{
			durl="Betentrycntr/hardDeleteBetByMatchId/";
		}
            $http.get(BASE_URL+durl + matchs.MstCode).success(function (data, status, headers, config) {
                /*Dialog.autohide(data.message);*/
		
		if(data.error==0)
		{
			if(type==0){
			matchs.bet_deleted=1;
			}
			else	
			{
			matchs.hard_bet_deleted=1;
			}
		}
                Dialog.autohide(data.message);
      $scope.loading=false;
	});
        }

    }
   $scope.undoBet = function (matchs) {
	
        var result = confirm("Are you sure want to undo Records?");
        if (result) {
		$scope.loading=true;
            $http.get(BASE_URL+'Betentrycntr/undoBetByMatchId/' + matchs.MstCode).success(function (data, status, headers, config) {
                /*Dialog.autohide(data.message);*/
		
		if(data.error==0)
		{
			matchs.bet_deleted=0;
		}
                Dialog.autohide(data.message);
      $scope.loading=false;
	});
        }

    }
}])
