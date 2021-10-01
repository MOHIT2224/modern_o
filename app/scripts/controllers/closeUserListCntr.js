app.controller("closeUserListCntr",["$scope","$http","$timeout","sessionService",function(e,t,s,sessionService){e.loading = true;t.get("Lstsavemstrcontroller/closeUserList").success(function(t,s,r,n){e.loading = false;e.a='',e.closeUser=t.closeUser,console.log(e.closeUser),gridOptions.api.setRowData(e.closeUser),currentPage=1,e.entryLimit=50,e.filteredItems=e.closeUser.length,e.totalItems=e.closeUser.length}).error(function(t,s,r,n){e.ResponseDetails="Data: "+t+"<br />status: "+s+"<br />headers: "+jsonFilter(r)+"<br />config: "+jsonFilter(n)}),e.setPage=function(t){e.currentPage=t},e.filter=function(){s(function(){e.filteredItems=e.filtered.length},10)},e.sort_by=function(t){e.predicate=t,e.reverse=!e.reverse},e.getStatus=function(s,r,n){

	
var o=confirm("Are you sure want to Open "+n +" "+"Account ?");
		if(o){
		 var userId = s;
                var acval = r;
                if (acval == 0) {
                    var accValue = 0;
                }
                else {
                    var accValue = 1;
                }
                var accountUserData = {
                    userName: n,
                    userType: sessionService.get('type'),
                    userId: userId,
                    accValue: accValue,
                    HelperID: sessionService.get('HelperID')
                };t({
                    method: 'POST',
                    url: 'Createmastercontroller/updateUserAccount/',
                    data: accountUserData,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(s,r,n,o){e.loading = false;e.status=s.message,alert(s.message),t.get("Lstsavemstrcontroller/closeUserList").success(function(t,s,r,n){e.closeUser=t.closeUser,e.currentPage=1,e.entryLimit=50,e.filteredItems=e.closeUser.length,e.totalItems=e.closeUser.length}).error(function(t,s,r,n){e.ResponseDetails="Data: "+t+"<br />status: "+s+"<br />headers: "+jsonFilter(r)+"<br />config: "+jsonFilter(n)})}).error(function(t,s,r,n){e.ResponseDetails="Data: "+t+"<br />status: "+s+"<br />headers: "+jsonFilter(r)+"<br />config: "+jsonFilter(n)})}},e.Refresh_data=function(){t.get("Lstsavemstrcontroller/closeUserList").success(function(t,s,r,n){e.closeUser=t.closeUser,e.currentPage=1,e.entryLimit=50,e.filteredItems=e.closeUser.length,e.totalItems=e.closeUser.length})}
var columnDefs = [
        { headerName: "Sno", width: 300, field:"Sno",cellStyle: { 'white-space': 'normal' } },
        { headerName: "Name", width: 300, field: "mstrname",cellStyle: { 'white-space': 'normal' } },
        { headerName: "User Name",width: 300, field: "mstruserid",cellStyle: { 'white-space': 'normal' }  },
        { headerName: "Created Date",width: 300, field: "usecrdt",cellStyle: { 'white-space': 'normal' }  },
        { headerName: "User Type",width: 300, field: "UserType",cellStyle: { 'white-space': 'normal' }  }
       
       
        
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
e.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "CloseUserList.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }
}]),app.filter("startFrom",function(){return function(e,t){return e?(t=+t,e.slice(t)):[]}});

