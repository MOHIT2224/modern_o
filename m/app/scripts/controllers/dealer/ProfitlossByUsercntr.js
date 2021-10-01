app.controller('ProfitlossByUsercntr',['$scope', '$http', 'sessionService', '$location','$state','$stateParams', function ($scope, $http, sessionService, $location,$state,$stateParams) {
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
            $state.go("dealerDashboard.PnlPlMiSheet", { 'MarketId': params.data });          
        });
        return eSpan;
    }
    /*function raiseevent(params) {
        var params;
        $scope.deleteUser(params.data.MstCode, params.data.UserId);
    }*/
    var gridOptions = { enableSorting: true, enableFilter: true, debug: true, rowSelection: 'multiple', enableColResize: true, paginationPageSize: 500, columnDefs: columnDefs, pagination: 'true' };
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
	$scope.loading=true;
        $http.get( BASE_URL+'Betentrycntr/profit_loss/' + $stateParams.UserID+'/'+sprtid).success(function (data, status, headers, config) {
            $scope.userPL = data.userPL;
            
            gridOptions.api.setRowData(data.userPL);
            
        $scope.loading=false;
        }).error(function(err){
		$scope.loading=false;
	});
    }
    $scope.Allsport=function(sprtid){
        //
	$scope.loading=true;
        $http.get( BASE_URL+'Betentrycntr/profit_lossAll/' + $stateParams.UserID+'/'+sprtid).success(function (data, status, headers, config) {
            //
            $scope.userPL = data.userPL;
            
            gridOptions.api.setRowData(data.userPL);
            $scope.loading=false;
        }).error(function(err){
		$scope.loading=false;
	});
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
       $state.go("dealerDashboard.PnlPlMiSheet", { 'MarketId': pldata });  
    };

$scope.CalSum=function(data,key)
{
	 if (angular.isUndefined(data) || angular.isUndefined(key))
        return 0;
      var sum = 0;
var temp=0;

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
var temp=0;

      angular.forEach(data, function(v, k) {
        sum = sum + parseFloat(v[key]);
        temp=parseFloat(sum).toFixed(2);
      });
      return temp;
    }
  });
/*app.filter('totalSumPriceQty', function() {
    return function(data, key1, key2) {
      if (angular.isUndefined(data) || angular.isUndefined(key1) || angular.isUndefined(key2))
        return 0;

      var sum = 0;
      angular.forEach(data, function(v, k) {
        sum = sum + (parseInt(v[key1]) * parseInt(v[key2]));
      });
      return sum;
    }
  });*/
