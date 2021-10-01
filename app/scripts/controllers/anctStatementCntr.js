app.controller('anctStatementCntr', function ($scope, $http, $filter, sessionService, loginService, $interval) {
    $scope.PageSize=50;
    $scope.formData = {};
    $scope.UserType=sessionService.get('type');
    $scope.loading=true; 
    $scope.GetLeger=function(){
        $http.get('Betentrycntr/AcStatement/' + sessionService.get('slctUseID')).success(function (data, status, headers, config) {  
           $scope.loading=false;      
            $scope.BetHistory = data.BetHistory;
		console.log($scope.BetHistory);            
            gridOptions.api.setRowData(data.BetHistory);
            //gridOptions.api.sizeColumnsToFit();
            $scope.SumOfP_L = $scope.sum($scope.BetHistory, 'Chips');
            $scope.SumOfProfit = $scope.sum($scope.BetHistory, 'Credit');
            $scope.SumOfLiability = $scope.sum($scope.BetHistory, 'Debit');
           
        }); 
    }

    $scope.GetLeger();
	$scope.Reset = function(){
		$scope.GetLeger();
		Master.reset();
 	 	$scope.formData = {};
	}

    $scope.GetSearch=function(){
        $scope.loading=true;
	var MasterID=$scope.formData.MasterID=="" ? null : $scope.formData.MasterID,DealerId=$scope.formData.DealerId=="" ? null : $scope.formData.DealerId,UserId=$scope.formData.UserId=="" ? null : $scope.formData.UserId;
        if(MasterID != angular.isUndefinedOrNull  && DealerId != angular.isUndefinedOrNull && UserId != angular.isUndefinedOrNull){
             $scope.userId=UserId;
        }else if(MasterID != angular.isUndefinedOrNull && DealerId != angular.isUndefinedOrNull){
             $scope.userId=DealerId;
        }else if(MasterID != angular.isUndefinedOrNull){
             $scope.userId=MasterID;
        }else {
           $scope.userId= sessionService.get('user_id'); 
        }
        
        $http.get('Betentrycntr/AcStatement/' +  $scope.userId).success(function (data, status, headers, config) {   
            $scope.loading=false;     
            $scope.BetHistory = data.BetHistory;            
             gridOptions.api.setRowData(data.BetHistory);
            gridOptions.api.sizeColumnsToFit();
            $scope.SumOfP_L = $scope.sum($scope.BetHistory, 'Chips');
            $scope.SumOfProfit = $scope.sum($scope.BetHistory, 'Credit');
            $scope.SumOfLiability = $scope.sum($scope.BetHistory, 'Debit'); 
$scope.userId='';
MasterID='';
DealerId='';
UserId='';
        }); 
    }

    $scope.GetMaster=function(){
        $http.get('Betentrycntr/GetMasterList/').success(function (data, status, headers, config) { 
          //
            $scope.MasterList = data.jsonData;
        });  
    }

    $scope.GetDealer=function(MasterId,Type){
        if(MasterId != null ){
       $http.get('Betentrycntr/GetDealer/'+MasterId).success(function (data, status, headers, config) { 
          //
            if (Type==1) {
                 $scope.DealerData = data.jsonData;
                 console.log($scope.DealerData);
            }else if(Type==2){
                 $scope.userData = data.jsonData;
            }       
        }); 
   }
   else{
       $scope.DealerData='';
        $scope.userData='';
   }
    }
    $scope.GetMaster();
    var columnDefs = [
             
        { headerName: "Date", width: 100, field: "Sdate", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); } },
        { headerName: "UserName", width: 100, field: "mstrUserId", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); } },
        { headerName: "Chips", width: 100, field: "Chips", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); },cellStyle:function (params) { return (params.data.Chips>=0 ? {color: 'green !important'} : {color: 'red !important'}); }},
        { headerName: "Narration", width: 500, field: "Narration", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); } },
        { headerName: "Credit", width: 100, field: "Credit", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); },cellStyle:function (params) { return (params.data.Credit>=0 ? {color: 'green !important'} : {color: 'red !important'}); } },
        { headerName: "Debit", width: 130, field: "Debit", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); },cellStyle:function (params) { return (params.data.Debit>=0 ? {color: 'green !important'} : {color: 'red !important'}); } },
        { headerName: "Balance", width: 130, field: "Balance", cellClass: function (params) { return (params.data.isBack == '1' ? 'lay-head' : 'back-head'); },cellStyle:function (params) { return (params.data.Balance>=0 ? {color: 'green !important'} : {color: 'red !important'}); } },
        
    ];
   
    /*function GetOddsName(params) {
        if (params.data.OddValue==0) {
             return "DOWN[Back]";
        }else{

             return "UP[Lay]";
        }
       // return thisYear - params.data.OddValue + params.data.age;
    }*/
    var gridOptions = {
        // note - we do not set 'virtualPaging' here, so the grid knows we are doing standard paging
        enableSorting: true,
        enableFilter: true,
        debug: true,
        rowSelection: 'multiple',
        enableColResize: true,
	pagination: true,
        paginationPageSize: 50,
        columnDefs: columnDefs,
pagination: true,
        onGridReady: function () {
            //gridOptions.api.sizeColumnsToFit();
        }
    };
    function onPageSizeChanged(newPageSize) {
        this.gridOptions.paginationPageSize = new Number(newPageSize);
        createNewDatasource();
    }

    var allOfTheData;
    function createNewDatasource() {
        if (!allOfTheData) {return;}
        var dataSource = {
            //rowCount: ???, - not setting the row count, infinite paging will be used
            getRows: function (params) {
                // this code should contact the server for rows. however for the purposes of the demo,
                // the data is generated locally, a timer is used to give the experience of
                // an asynchronous call
                //console.log('asking for ' + params.startRow + ' to ' + params.endRow);
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
        var s = gridOptions.api.setDatasource(dataSource);
        //gridOptions.api.sizeColumnsToFit(s);
    }

    function autoSizeAll() {
        var allColumnIds = [];
        columnDefs.forEach(function (columnDef) {
            allColumnIds.push(columnDef.field);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);
    }

    function setRowData(rowData) {
        allOfTheData = rowData;
        createNewDatasource();
    }
    var gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);

    $scope.sum = function(items, prop){
        
        return items.reduce( function(a, b){
             
             if (b[prop]==null || b[prop]=="") b[prop]=0;            
                return parseFloat(a) + parseFloat(b[prop]);
        }, 0);
    };
    $scope.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "AccountStatement.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }
    $scope.onPageSize=function()
	{

		var value = document.getElementById('page-size').value;
        	gridOptions.api.gridOptionsWrapper.gridOptions.paginationPageSize=Number(value);
		 createNewDatasource();
	}
});
