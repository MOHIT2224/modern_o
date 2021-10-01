app.controller("CrtSubAdminCntr", ["$scope", "$http","$q","$timeout", function(e, r,q,$timeout) {


    r.get(BASE_URL+"Createdealercontroller/getSubadmin").success(function(r, s, a, n) {
        e.subadmin=r.subadmin,
	
	//alert(e.subadmin)
        setRowData(e.subadmin);
    }
    ), e.submitForm=function() {
        var s= {
            name: e.name, username: e.username, password: e.password, type: 4
        }
        ;
        r( {
            method:"POST", url:BASE_URL+"Createdealercontroller/SaveSubAdmin/", data:s, headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }
        ).success(function(r) {
			
            0==r.message.error?(e.message=r.message.message, e.subadmin=r.subadmin): e.message=r.message.message,
                // e.message = true;
		    $timeout(function() {
		      e.message = false;
		    }, 3000);  
e.errorMsg=false;
               
                e.name=""
                e.username=""
		e.password=""
        }
        )
    }
    , e.checkUserName=function() {
        var s=e.username;
        var deferred = q.defer();
        s==angular.isUndefinedOrNull||s.length<4?e.errorMsg=r:r( {
            method:"POST", url:"Createmastercontroller/CheckUserName/"+s, data:s, headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }
        ).success(function(r) {
            deferred.resolve({
                title:  0==r.error?e.errorMsg=r.message: e.errorMsg1=r.message,
	

             });
                if(r.error == 0){ e.errorMsg1 = false;
		}
		else if(r.error == 1){  e.errorMsg = false;

		}


		

                 
           
        }).error(function(msg, code) {
            deferred.reject(msg);
            $log.error(msg, code);
         });
         return deferred.promise;
    
}
 var columnDefs = [
        { headerName: "Sno", width: 300, field: "usecode",cellStyle: { 'white-space': 'normal' } },
        { headerName: "Name", width: 300, field: "mstrname",cellStyle: { 'white-space': 'normal' } },
        { headerName: "User Name",width: 300, field: "usename",cellStyle: { 'white-space': 'normal' }  }
       
       
        
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
e.onBtExport = function () {
        var params = {
            skipHeader: false,
            skipFooters: true,
            skipGroups: true,
            fileName: "SubAdminList.csv"
        };
        gridOptions.api.exportDataAsCsv(params);
    }
}


]);
