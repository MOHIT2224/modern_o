app.controller("Matchresultcntr",["$scope","$http","Dialog","$timeout","sessionService",function(t,e,s,c,n){t.Fancy="MatchOdds",e.get("Geteventcntr/GetSportFrmDatabase").success(function(e,s,c,n){t.sportLst=e.sportData}),t.getResult=function(){t.loading = true,e.get("Geteventcntr/GetMatchOddsResult").success(function(e,s,c,n){t.loading = false,t.MatchOddsResult=e.MatchOddsResult,setRowData(e.MatchOddsResult),t.currentPage=1,t.entryLimit=50,t.filteredItems=t.MatchOddsResult.length,t.totalItems=t.MatchOddsResult.length})},t.getResult(),t.GetMatch=function(s){e.get("Geteventcntr/getDeclareMatchResult/"+s.id).success(function(e,s,c,n){t.matchLst=e.matchLst})}

,t.deleteRecord=function(c,n,a,r,u,o){var d=confirm("Are you sure want to Roll Back Result...");d&&e.get("Geteventcntr/DeleteMatchResult/"+c+"/"+n+"/"+a+"/"+r+"/"+o+"/"+u).success(function(e,c,n,a){s.autohide(e.status.message),t.getResult()})},t.GetMarket=function(s,c,a){t.loading=true;var r={matchId:s?s:0,sportsId:c,user_id:n.get("user_id")};e({method:"POST",url:"Geteventcntr/matchMarketLst/",data:r,headers:{"Content-Type":"application/x-www-form-urlencoded"}}).success(function(e){"fancy"==a?t.getMatchFancy=e.getMatchFancy:t.MatchMarket=e.MatchMarket,t.loading=false;})},t.Getodds=function(s,m){e.get("Geteventcntr/get_drpdwnSelectionName/"+s).success(function(e,s,c,n){t.runner=e.runner;
if(m == 'Match Odds' && t.MatchMarket.length > 1 ){
alert("Please delcare other market before closing match oddd.");
t.SelectionMarket=true;
t.runner=null;
//t.SelectionMarket=true;
//var d=confirm("Are you sure you want to settle match odd, Other markets still open. ");d
//if(d){
//t.SelectionMarket=false;
//}
//else{
//t.SelectionMarket=true;
//t.runner=null;
//}
}else{t.SelectionMarket=false;}})},t.saveResult=function(t,e,s,c){alert("MatchOdds")},t.saveMatchoddsResult=function(c,n,a,r,u,o,d,i){
    t.loading=true;
    var l={Sport_id:n,Match_id:c,market_id:a,selectionId:r,isFancy:1,sportName:u,matchName:o,MarketName:d,selectionName:i};e({method:"POST",url:"Geteventcntr/SetResult/",data:l,headers:{"Content-Type":"application/x-www-form-urlencoded"}}).success(function(e){
        t.loading=false;
        s.autohide(e.status.message),t.message=e.status.message,t.MatchOddsResult=e.MatchOddsResult,t.currentPage=1,t.entryLimit=50,t.filteredItems=t.MatchOddsResult.length,t.totalItems=t.MatchOddsResult.length})},t.setPage=function(e){t.currentPage=e},t.filter=function(){c(function(){t.filteredItems=t.filtered.length},10)},t.sort_by=function(e){t.predicate=e,t.reverse=!t.reverse}
var columnDefs = [
        { headerName: "Sno", width: 30, field: "resId" },
        { headerName: "MatchName", width: 300, field: "MatchName",cellStyle: { 'white-space': 'normal' } },
        { headerName: "MarketName",width: 85, field: "MarketName",cellStyle: { 'white-space': 'normal' }  },
        { headerName: "sportName", field: "sportName",width: 80,  cellStyle: { 'text-align': 'right', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' } },
        { headerName: "SelectionName", field: "SelectionName",width: 80, cellStyle: { color: 'BLACK', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' } },
        { headerName: "Date", field: "date",width: 130,cellStyle: { 'white-space': 'normal' } },
        /*{ headerName: "Type", field: "Type", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },
        { headerName: "Odds", field: "Odds", cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); }, cellStyle: { color: 'BLACK', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px' } },
        { headerName: "Status", field: "Status", cellClass: 'col-right', cellClass: function (params) { return (params.data.Type == 'Lay' ? 'lay-head' : 'back-head'); } },*/
        {headerName: 'Result',field:"result", cellRenderer: ageCellRendererFunc,width: 90 }
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
    new agGrid.Grid(gridDiv, gridOptions)
    
    


t.onBtExportExcel = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "MatchResult.xls"
        };
        gridOptions.api.exportDataAsExcel(params);
    }

t.declareresult = function () {
   //
    t.loading=true;
     e.get('Apiadmincontroller/declareresult/').success(function (data, status, headers, config) {
      t.error = data.error;
      if (t.error==true)
      {
      s.autohide(data.message);
      }
      else
     {
      s.autohide(data.message);
     }
         t.loading=false;
  })
    }


t.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "MatchResult.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }

t.exportPdf = function() {
  var grid = e.gridApi.grid;
  var rowTypes = uiGridExporterConstants.ALL;
  var colTypes = uiGridExporterConstants.ALL;
  uiGridExporterService.pdfExport(grid, rowTypes, colTypes);
};
}]),app.filter("startFrom",function(){return function(t,e){return t?(e=+e,t.slice(e)):[]}});
