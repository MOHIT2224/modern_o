app.controller('Profitlosscntr',['$scope', '$http', 'sessionService', '$location','$state','Dialog', function ($scope, $http, sessionService, $location,$state,Dialog) {
$scope.UserType=sessionService.get('type');
    var columnDefs = [
        { headerName: "Sno", width: 30, field: "SrNo" },
        { headerName: "EventName", width: 300, field: "EventName",cellStyle: { 'white-space': 'normal' } },
        { headerName: "MarketName",width: 85, field: "MarketName",cellStyle: { 'white-space': 'normal' }  },
        { headerName: "P_L", field: "PnL",width: 80,  cellStyle: { 'text-align': 'right', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' } },
        { headerName: "Comm", field: "Comm",width: 80, cellStyle: { color: 'BLACK', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' } },
        { headerName: "CreatedOn", field: "CreatedOn",width: 130,cellStyle: { 'white-space': 'normal' } },
        /*{ headerName: "Type", field: "Type", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Odds", field: "Odds", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); }, cellStyle: { color: 'BLACK', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px' } },
        { headerName: "Status", field: "Status", cellClass: 'col-right', cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },*/
        {headerName: 'Action', cellRenderer: ageCellRendererFunc,width: 90 }
    ];
    function ageCellRendererFunc(params) {
        //
        var eSpan = document.createElement('div');
        eSpan.innerHTML = '<h3 class="show-bet">Show Bet</h3>';
        eSpan.addEventListener('click', function () {
            $state.go("dashboard.PnlPlMiSheet", { 'MarketId': params.data });          
        });
        return eSpan;
    }
    /*function raiseevent(params) {
        var params;
        $scope.deleteUser(params.data.MstCode, params.data.UserId);
    }*/
    var gridOptions = { enableSorting: true, enableFilter: true, debug: true, rowSelection: 'multiple', enableColResize: true, paginationPageSize: 500, columnDefs: columnDefs, rowModelType: 'pagination' };
    function onPageSizeChanged(newPageSize) {
        this.gridOptions.paginationPageSize = new Number(newPageSize);
        createNewDatasource();
    }
    var allOfTheData;
    function createNewDatasource() {
        if (!allOfTheData) { return; }
        var dataSource = {
            getRows: function (params) {
                setTimeout(function () {
                    var rowsThisPage = allOfTheData.slice(params.startRow, params.endRow);
                    var lastRow = -1;
                    if (allOfTheData.length <= params.endRow) {
                        lastRow = allOfTheData.length;
                    }
                    params.successCallback(rowsThisPage, lastRow);
                }, 500);
            }
        };
        gridOptions.api.setDatasource(dataSource);
    }
    function setRowData(rowData) {
        allOfTheData = rowData;
        createNewDatasource();
    }
    var gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
    
    
   /* $scope.GetSportName=function(){
        $http.get( BASE_URL+'Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
            $scope.sprtData = data.sportData;
        }); 
    }*/
    //$scope.GetSportName();
    if(sessionService.get('abindProfit') != angular.isUndefinedOrNull)
    {

        $scope.FuserPL = JSON.parse(sessionService.get('abindProfit'));

        // $scope.bindCricket=[];
    }
    $scope.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "ProfitLoss.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }
    $scope.click_test=function(sprtid){
	 $scope.loading = true;
        $http.get( BASE_URL+'Betentrycntr/profit_loss/' + sessionService.get('user_id')+'/'+sprtid).success(function (data, status, headers, config) {
		 $scope.loading = false;           
		 $scope.userPL = data.userPL;
            
            setRowData(data.userPL);
            
        });
    }
    $scope.Allsport=function(sprtid){
        //
	// $scope.loading = true;
        $http.get( BASE_URL+'Betentrycntr/profit_lossAll/' + sessionService.get('user_id')+'/'+sprtid).success(function (data, status, headers, config) {
            //
		 $scope.loading = false;
            $scope.userPL = data.userPL;
            
            setRowData(data.userPL);
            
        });
    }
    $scope.updateProfitLoss = function(){
        if($scope.userPL!=angular.isUndefinedOrNull)
        {
            sessionService.set('abindProfit',JSON.stringify($scope.userPL));
            //alert('cart updated');
            $scope.FuserPL = JSON.parse(sessionService.get('abindProfit'));

        }

    };
    $scope.$watch('userPL', $scope.updateProfitLoss, true);
    $scope.deletePl=function(Mid){
		var isDelete = confirm("Are you sure to delete this record ?");
		if(isDelete)
		{
			 $scope.loading = true;
			$http.get( BASE_URL+"Apiadmincontroller/delete_match_profit_loss/"+Mid).success(function(data, status, headers, config){
			 $scope.loading = false;
				if(!data.error){
				 // Dialog.autohide(data.message);
				   $scope.Allsport(0);
				}
				 Dialog.autohide(data.message);
			}).error(function(){
				 $scope.loading = false;
			});
		}
	}

    function autoSizeAll() {
        
        var allColumnIds = [];
        columnDefs.forEach(function (columnDef) {
            allColumnIds.push(columnDef.field);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);
    }

    $scope.sum = function(items, prop){
        
        return items.reduce( function(a, b){
             
            return parseFloat(a) + parseFloat(b[prop]);
        }, 0);
    };
    $scope.showBet=function(pldata){
       $state.go("dashboard.PnlPlMiSheet", { 'MarketId': pldata });  
    };
$scope.CalSum=function(data,key)
{
	
	var temp=0;
	 if (angular.isUndefined(data) || angular.isUndefined(key))
        return 0;
      var sum = 0;

      angular.forEach(data, function(v, k) {
        sum = sum + parseFloat(v[key]);
        temp=parseFloat(sum).toFixed(2);
      });
      return temp;
}

}]);
app.filter('sumOfValue', function() {
    return function(data, key) {
     //
      if (angular.isUndefined(data) || angular.isUndefined(key))
        return 0;
      var sum = 0;

      angular.forEach(data, function(v, k) {
        sum = sum + parseFloat(v[key]);
        temp=parseFloat(sum).toFixed(2);
      });
      return temp;
    }
  });
/*app.filter("mysqlDateFormatToTimestamp", function(){
    return function(date){
        var date1 =  date2 = date3 = timestamp = hours = minutes = seconds = '';               
        date1 = date.split(':'); 
        date2 = date1[0].split(' '); 
        date3 = date2[0].split('-'); // Change it based on your format
        if( date1.length == 1 && date2.length == 1 ){
            hours = '00';
            minutes = '00';
            seconds = '00';
        }else{
            hours = parseInt(date2[1]);
            minutes = parseInt(date1[1]);
            seconds = parseInt(date1[2]);
        } 
        timestamp = new Date(parseInt(date3[0]), parseInt(date3[1])-1, parseInt(date3[2]), hours, minutes, seconds);
        return timestamp;
   } 
});*/
