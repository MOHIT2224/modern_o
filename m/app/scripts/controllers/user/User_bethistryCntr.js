app.controller('Get_bethistryCntr', function ($scope, $http, $filter, sessionService, loginService, $interval,$state,Base64,$timeout) {
$scope.UserType=sessionService.get('type');    
$scope.userId=sessionService.get('user_id');
var DealerId='';
var UserId='';
$scope.formData={};

$scope.formData.from_date =0;
$scope.formData.to_date=0;
$scope.formData.MaxDate1=new Date();
$scope.formData.MaxDate2=new Date();
  
$scope.setDateFun=function(type)
{
	
	if(type==2)
	{
  $scope.formData.MaxDate1=$scope.formData.to_date;
	}
	else
	{
	 $scope.formData.minDate1=$scope.formData.from_date;
	}
	
}
    $scope.GetDealer=function(MasterId,Type){



        if(MasterId != null ){
       $http.get( BASE_URL+'Betentrycntr/GetDealer/'+MasterId).success(function (data, status, headers, config) { 
          //  
            if (Type==1) {
                 $scope.DealerData = data.jsonData;
                 
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
$scope.sum = function(items, prop){
        
        return items.reduce( function(a, b){
             
             if (b[prop]==null) b[prop]=0;            
                return parseFloat(a) + parseFloat(b[prop]);
        }, 0);
    };
 $scope.BetHistory=[];
 $scope.GetBetHistory=function(){

	$scope.loading = true;


var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 

if($scope.formData.from_date == "1970-01-01" || $scope.formData.from_date ==0){
		$scope.from_date1 =0;}
	else{
		$scope.from_date1 = $filter('date')($scope.formData.from_date, 'yyyy-MM-dd'); 
		}
	if($scope.formData.to_date == "1970-01-01" || $scope.formData.to_date ==0){
		$scope.to_date1 =0;}
	else
	{
		$scope.to_date1 = $filter('date')($scope.formData.to_date, 'yyyy-MM-dd'); 
	}
        $http.get( BASE_URL+'Betentrycntr/BetHistoryFilters/' + $scope.userId + '/' + $scope.from_date1 + '/' + $scope.to_date1).success(function (data, status, headers, config) {   
	     $scope.loading = false; 
        	 $scope.BetHistory = data.BetHistory;   	
	   
	
           
//console.log($scope.BetHistory );     
           gridOptions.api.setRowData($scope.BetHistory);
            //gridOptions.api.sizeColumnsToFit();
            $scope.SumOfP_L = $scope.sum($scope.BetHistory, 'P_L');
            $scope.SumOfProfit = $scope.sum($scope.BetHistory, 'Profit');
            $scope.SumOfLiability = $scope.sum($scope.BetHistory, 'Liability'); 
	   // $scope.userId='';
         	DealerId='';
		UserId='';
 //setRowData(data.BetHistory);

        }); 

    }
 $scope.GetSearch=function(){

DealerId=$scope.formData.DealerId=="" ? null : $scope.formData.DealerId,UserId=$scope.formData.UserId=="" ? null : $scope.formData.UserId;
        if(DealerId != angular.isUndefinedOrNull && UserId != angular.isUndefinedOrNull){
             $scope.userId=UserId;
        }else if(DealerId != angular.isUndefinedOrNull){
             $scope.userId=DealerId;
        }else if(UserId != angular.isUndefinedOrNull){
             $scope.userId=UserId;
        }else {
           $scope.userId= sessionService.get('user_id'); 
        }
	$scope.GetBetHistory();
}

    $scope.GetBetHistory();


$scope.Reset = function(){
		$state.reload();
		Master.reset();
 	 	$scope.formData = {};
	}
$scope.GetDealer(sessionService.get('user_id'),$scope.UserType);

var columnDefs = [
        { headerName: "SNo.", width: 30,field: "SrNo",cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },       
        { headerName: "Description", field: "Description", width: 400, cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Selection Name", width: 100, field: "selectionName", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "userName", width: 70, field: "UserNm", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Type", width: 70, field: "Type", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Odds", width: 70, field: "Odds", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Stack", width: 70, field: "Stack", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Date", width: 130, field: "MstDate", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
          { headerName: "P_L", width: 80, field: "P_L", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head col-right' : 'back-head col-right'); },cellStyle:function (e) { return (e.data.P_L>=0 ? {color: 'green !important',"text-align":"right","margin-right":"10px","padding-right":"15px"} : {color: 'red !important',"text-align":"right","margin-right":"10px","padding-right":"15px"}); } },
        { headerName: "Profit", width: 80, field: "Profit", cellClass: 'col-right', cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); },cellStyle:function (e) { return (e.data.Profit>=0 ? {color: 'green !important',"text-align":"right","margin-right":"10px","padding-right":"15px"} : {color: 'red !important',"text-align":"right","margin-right":"10px","padding-right":"15px"}); } },
        { headerName: "Liability", width: 80, field: "Liability", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head col-right' : 'back-head col-right'); },cellStyle:function (e) { return (e.data.Liability>=0 ? {color: 'green !important',"text-align":"right","margin-right":"10px","padding-right":"15px"} : {color: 'red !important',"text-align":"right","margin-right":"10px","padding-right":"15px"}); } },
        { headerName: "Status", width: 80, field: "STATUS", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head  col-status' : 'back-head  col-status'); } }
      //  { headerName: "bet_id.", width: 50,field: "mstcode", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); }}
      ];  
    function ageCellRendererFunc(params) {
        //
        var eSpan = document.createElement('div');
        eSpan.innerHTML = '<h3 class="show-bet">Show Bet</h3>';
        eSpan.addEventListener('click', function () {
            $state.go("userDashboard.PnlPlMiSheet", { 'MarketId': params.data });          
        });
        return eSpan;
    }
   
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
    
  
    $scope.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "BetHistory.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }
 function autoSizeAll() {
        
        var allColumnIds = [];
        columnDefs.forEach(function (columnDef) {
            allColumnIds.push(columnDef.field);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);
    }
   
});
