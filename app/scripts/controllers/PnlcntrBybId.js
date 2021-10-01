app.controller('PnlcntrBybId', function ($scope, $http, $filter, sessionService, $mdDialog, $rootScope, $location,$stateParams,$state) {
    /*code for Back Button*/
    $scope.backopti = false;
    $scope.UserTYPEID=sessionService.get('type');
    $scope.doTheBack = function (GTuserType, GTUseruserId, GTUserName) {
        $http.get('Lstsavemstrcontroller/getParentData/' + GTUseruserId).success(function (data, status, headers, config) {
           
            $scope.GetPlus_Minus_Ac(data.parentData[0].mstrid,$scope.Marketdata.matchId,$scope.Marketdata.MarketId,$scope.Marketdata.fancyId, data.parentData[0].mstruserid,data.parentData[0].usetype);
                if (sessionService.get('type') == data.parentData[0].usetype)
                    $scope.backopti = false;
                else
                    $scope.backopti = true;
        });
        //$scope.GetPlus_Minus_Ac($stateParams.MarketId.UserId,$stateParams.MarketId.matchId,$stateParams.MarketId.MarketId,$stateParams.MarketId.fancyId);
    };
if($stateParams.MarketId != null || $stateParams.MarketId != undefined ){
sessionService.set('Marketdata',JSON.stringify($stateParams.MarketId))};
if($stateParams.MarketId == null || $stateParams.MarketId != null ){

$scope.Marketdata=JSON.parse(sessionService.get("Marketdata"))};
    /*end of Back Button By Manish*/
    $scope.MarketId=$stateParams.MarketId;
     var columnDefs = [
        { headerName: "SNo.", width: 30,field: "SrNo",cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Description", field: "Description", width: 400, cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Selection Name", width: 100, field: "selectionName", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "userName", width: 70, field: "userName", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Type", width: 70, field: "Type", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Rate", width: 70, field: "Odds", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Amount", width: 70, field: "Stack", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Date", width: 130, field: "MstDate", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "P_L", width: 80, field: "P_L", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head col-right' : 'back-head col-right'); },cellStyle:function (params) { return (params.data.P_L>0 ? {color: 'green !important'} : {color: 'red !important'}); } },
        { headerName: "Profit", width: 80, field: "Profit", cellClass: 'col-right', cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); },cellStyle:function (params) { return (params.data.Profit>0 ? {color: 'green !important'} : {color: 'red !important'}); } },
        { headerName: "Liability", width: 80, field: "Liability", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head col-right' : 'back-head col-right'); },cellStyle:function (params) { return (params.data.Liability>0 ? {color: 'green !important'} : {color: 'red !important'}); } },
        { headerName: "Status", width: 80, field: "STATUS", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head  col-status' : 'back-head  col-status'); } },
        { headerName: "bet_id.", width: 50,field: "mstcode", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); }}
      ];  
   
    var gridOptions = { enableSorting: true, enableFilter: true, debug: true, rowSelection: 'multiple', enableColResize: true,   pagination: true,
    paginationAutoPageSize: true,columnDefs: columnDefs,   pagination: true, };
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
    
    
    $scope.GetSportName=function(){
        $http.get('Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
            $scope.sprtData = data.sportData;
        }); 
    }
    $scope.GetSportName();
    $scope.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "Bet_history.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }
    $scope.GetPnL=function(){
       $scope.loading=true;
       
        $http.get('Betentrycntr/BetHistoryPL/' + $scope.Marketdata.UserId+'/'+$scope.Marketdata.matchId+'/'+$scope.Marketdata.MarketId+'/'+$scope.Marketdata.fancyId).success(function (data, status, headers, config) {
           $scope.loading=false;
            $scope.BetHistory = data.getBetPl;
console.log($scope.BetHistory);
            gridOptions.api.setRowData(data.getBetPl);
            //gridOptions.api.sizeColumnsToFit();
            $scope.SumOfP_L = $scope.sum($scope.BetHistory, 'P_L');
            $scope.SumOfProfit = $scope.sum($scope.BetHistory, 'Profit');
            $scope.SumOfLiability = $scope.sum($scope.BetHistory, 'Liability'); 
        });
    }
     $scope.sum = function(items, prop){
        
        return items.reduce( function(a, b){
             
             if (b[prop]==null) b[prop]=0;            
                return parseFloat(a) + parseFloat(b[prop]);
        }, 0);
    };
    $scope.GetPlus_Minus_Ac=function(userId,matchId,MarketId,fancyId,mstruserid,usetype){
        
        $scope.GTUserName = mstruserid;
        $scope.GTUseruserId = userId;
        $scope.GTuserType = usetype;
        
        $http.get('Betentrycntr/GetPlus_Minus_Ac/'+ userId+'/'+matchId+'/'+MarketId+'/'+fancyId).success(function (data, status, headers, config) {
            $scope.getPlusAc = data.getPlusAc;
            $scope.getMiusAc = data.getMiusAc;
            $scope.totalSumP = 0;
            $scope.totalSumM = 0;
            
            for (var i = 0; i < $scope.getPlusAc.length; i++) {
                $scope.totalSumP = parseFloat($scope.totalSumP) + parseFloat($scope.getPlusAc[i].PUsum);
            }
            for (var i = 0; i < $scope.getMiusAc.length; i++) {
                $scope.totalSumM = parseFloat($scope.totalSumM) + parseFloat($scope.getMiusAc[i].Musum);
            }
        });
            
            if (sessionService.get('type') == usetype)
            $scope.backopti = false;
        else
            $scope.backopti = true;
    }
   
    $scope.GetPnL();
    $scope.GetPlus_Minus_Ac($scope.Marketdata.UserId,$scope.Marketdata.matchId,$scope.Marketdata.MarketId,$scope.Marketdata.fancyId,$scope.Marketdata.mstruserid,$scope.Marketdata.usetype);
    //$scope.GetMinusA_c($stateParams.MarketId.UserId,$stateParams.MarketId.matchId,$stateParams.MarketId.MarketId,$stateParams.MarketId.fancyId);
    $scope.GetPreviousPage=function(){
	if($stateParams.StateName==null)
	{
		$state.go("dashboard.Profitlosscntr");
	}
	else
	{
		
		$state.go($stateParams.StateName);
	}
        
    }
   
});
