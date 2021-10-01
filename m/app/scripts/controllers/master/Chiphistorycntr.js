app.controller('Chiphistorycntr', function ($scope, $http, $filter, sessionService, loginService, $rootScope, $location) {
    if ($rootScope.HelperAllRights != angular.isUndefinedOrNull && $rootScope.HelperAllRights.ChipHistory == 0) { $location.path('/dashboard/Home'); }//170213
    $scope.legershow = false;
    $scope.loading=true;
    $scope.hstshow = false;
    $scope.typeDropDown = [{ value: '0', label: 'Chip' },{ value: '1', label: 'Free Chip' },{ value: '2', label: 'Win' },{ value: '3', label: 'Lose' },{ value: '5', label: 'All' }];
    $scope.loginId = sessionService.get('user_id');
    $scope.type = sessionService.get('type');
    /*start The Chip History ag Grid*/
    /*start*/
$scope.from_date=0;
$scope.to_date=0;
$scope.MaxDate1=new Date();
$scope.MaxDate2=new Date();
$scope.setDateFun=function(type)
{
	
	if(type==2)
	{
 	 $scope.MaxDate1=$scope.to_date;
	}
	else
	{
	 $scope.minDate1=$scope.from_date;
	}
	
}
    var columnDefs = [
            { headerName: "SNo", width: 30,field: "SrNo" ,cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head');}},
            { headerName: "Narration", width: 420, field: "narration", cellStyle: { color: 'BLACK', 'text-align': 'center' } },          
            { headerName: "MstDate", field: "EDate", width: 180 },          
            { headerName: "Credit", field: "Credit", cellStyle: { color: 'GREEN', 'text-align': 'right',  'margin-right': '10px', 'padding-right': '15px' }, width: 100 },
            { headerName: "Debit", field: "Debit", cellStyle: { color: 'RED', 'text-align': 'right', 'margin-right': '10px', 'padding-right': '15px' }, width: 100 },
            { headerName: "Odds", field: "Odds", width: 100 },
            { headerName: "Stake", field: "Stack", width: 100 },
            { headerName: "Status", field: "Status_", width: 100 },
            { headerName: "Total", field: "Total", cellStyle: { color: 'BLUE', 'text-align': 'right', 'margin-right': '10px', 'padding-right': '15px' }, width: 100 },
            { headerName: "ID", width: 100,field: "Mstcode" },
    ];
    var columnDefsLgr = [
            { headerName: "Sno",width: 60, field: "SrNo"},
            
            { headerName: "Narration",width:600, field: "narration", cellRenderer: ageCellRendererFunc, cellStyle: { color: 'BLACK', 'text-align': 'left','white-space': 'normal' } },
            { headerName: "Date", field: "EDate", width: 180, cellStyle: { color: 'BLACK', 'text-align': 'center','white-space': 'normal' } },
               { headerName: "Credit", field: "Credit", width: 120, cellStyle:function (params) { return (params.data.Credit>=0 ? {color: 'green !important','text-align': 'right','white-space': 'normal' } : {color: 'red !important','text-align': 'right', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' }); } },
            { headerName: "Debit", field: "Debit", width: 120, cellStyle:function (params) { return (params.data.Debit>=0 ? {color: 'green !important','text-align': 'right','white-space': 'normal' } : {color: 'red !important','text-align': 'right', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' }); } },
            { headerName: "Balance", field: "Balance", width: 120, cellStyle:function (params) { return (params.data.Balance>=0 ? {color: 'green !important','text-align': 'right','white-space': 'normal' } : {color: 'red !important','text-align': 'right', 'margin-right': '10px', 'padding-right': '15px','white-space': 'normal' }); } }
            , { headerName: "id", width:100, field: "Mstcode",cellStyle: { 'white-space': 'normal'} },

    ];
    function ageCellRendererFunc(params) {
        
        var eSpan = document.createElement('a');
        console.log(params);
        eSpan.innerHTML = '' + params.data.narration;
        eSpan.addEventListener('click', function () {
            if (params.data.TypeID != 9 && params.data.TypeID != 6) {  $scope.raiseevent(params); } else { console.log("TYPEID IS" + params.data.TypeID); }
        });
        return eSpan;
    }
    function getSerialNo(params) { return  params.node.id;}
    $scope.raiseevent = function (params) {        
        var params;
        $scope.hstshow = true;
        $scope.legershow = false;

        //$scope.deleteUser(params.data.MstCode, params.data.UserId)

        //alert('code worked' + params.data.narration);
        $http.get( BASE_URL+'Betentrycntr/Chip_history/' + params.data.UserID + '/' + sessionService.get('type') + '/' + params.data.MatchId + '/' + params.data.MarketId + '/' + params.data.oppAcID).success(function (data, status, headers, config) {
            
            $scope.chip_data = data.chips_data;
           $scope.gridOptions.api.setRowData($scope.chip_data);
            //$scope.gridOptions.api.sizeColumnsToFit();
            $scope.SumOfCreadit = $scope.sum($scope.chip_data, 'Credit');
            $scope.SumOfDebit = $scope.sum($scope.chip_data, 'Debit'); 
        });

    }

    $scope.gridOptions = {
        // note - we do not set 'virtualPaging' here, so the grid knows we are doing standard paging
        enableSorting: true,
        enableFilter: true,
        debug: true,
        rowSelection: 'multiple',
        enableColResize: true,
        paginationPageSize: 500,
        columnDefs: columnDefs,
  pagination:true
    };
    $scope.gridOptionslgr = {
        // note - we do not set 'virtualPaging' here, so the grid knows we are doing standard paging
        enableSorting: true,
        enableFilter: true,
        debug: true,
        rowSelection: 'multiple',
        enableColResize: true,
        paginationPageSize: 500,
        columnDefs: columnDefsLgr,
  pagination:true
        
    };
    /*function numberFormatter(params) {
        if (params.value == 0) {
            return 'lay-head';
        } else {
            return 'back-head';
        }
    }*/

    function onPageSizeChanged(newPageSize) {
        this.gridOptions.paginationPageSize = new Number(newPageSize);
        createNewDatasource();
    }

    // when json gets loaded, it's put here, and  the datasource reads in from here.
    // in a real application, the page will be got from the server.
    var allOfTheData;

    function createNewDatasource(type) {
        if (!allOfTheData) {
            // in case user selected 'onPageSizeChanged()' before the json was loaded
            return;
        }

        var dataSource = {
            //rowCount: ???, - not setting the row count, infinite paging will be used
            getRows: function (params) {

                //console.log('asking for ' + params.startRow + ' to ' + params.endRow);
                setTimeout(function () {
                    // take a chunk of the array, matching the start and finish times
                    var rowsThisPage = allOfTheData.slice(params.startRow, params.endRow);

                    var lastRow = -1;
                    if (allOfTheData.length <= params.endRow) {
                        lastRow = allOfTheData.length;
                    }
                    params.successCallback(rowsThisPage, lastRow);
                }, 500);
            }
        };
        if (type == 'lgr') {
            $scope.gridOptionslgr.api.setDatasource(dataSource);
        } else {
            $scope.gridOptions.api.setDatasource(dataSource);
        }


    }

    function setRowData(rowData, type) {
        allOfTheData = rowData;
        createNewDatasource(type);
    }

    $scope.getChipHistoryData = function (from_date, to_date, slctType, withParent) {
        $scope.loading=true;
        /* $scope.legershow=false;
         $scope.hstshow=true;

         var from = null, to = null, type = null;//sourabh 161215
         if ($scope.from_date == "") {
             from = $scope.from_date;//sourabh 161215
             $filter('date')($scope.from_date, 'yyyy-MM-dd');
         }
         if ($scope.to_date == "") to = $scope.to_date;//sourabh 161215
         if ($scope.slctType != angular.isUndefinedOrNull && $scope.slctType.value != "") type = $scope.slctType.value;//sourabh 161215 + '/' + data.cb1*/
        $http.get( BASE_URL+'Betentrycntr/Chip_history/' + $scope.loginId + '/' + from + '/' + to + '/' + type + '/' + $scope.type).success(function (data, status, headers, config) {
            $scope.loading=false;
            $scope.chip_data = data.chips_data;
            $scope.gridOptions.api.setRowData($scope.chip_data );
            //gridOptions.api.sizeColumnsToFit();
            $scope.SumOfCreadit = $scope.sum($scope.chip_data, 'Credit');
            $scope.SumOfDebit = $scope.sum($scope.chip_data, 'Debit'); 
            
        });
        

    }

    function autoSizeAll() {
        var allColumnIds = [];
        columnDefs.forEach(function (columnDef) {
            allColumnIds.push(columnDef.field);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);
    }



    $scope.ChipLeger = function (from_date, to_date, slctType, withParent) {
        //alert("HI");
	  $scope.loading=true;
        $scope.legershow = true;
        $scope.hstshow = false;
        if (slctType == undefined) {
            var drpdwn = 1;
        } else {
            var drpdwn = slctType;
        }
        if (from_date == angular.isUndefinedOrNull || to_date == angular.isUndefinedOrNull) {
            var fromDate = "";
            var toDate = "";
        } else if (from_date != angular.isUndefinedOrNull && to_date != angular.isUndefinedOrNull) {
            if(from_date==0)
            {
                var fromDate = "";
            }
            else {
                var fromDate = $filter('date')(from_date, 'yyyy-MM-dd');
            }

            if(toDate==0)
            {
                var toDate = "";
            }
            else {
                var toDate = $filter('date')(to_date, 'yyyy-MM-dd');
            }
        }



        $http.get( BASE_URL+'Betentrycntr/Chip_leger/' + sessionService.get('user_id')+ '/' + sessionService.get('type') + '/' + drpdwn + '/' + fromDate + '/' + toDate).success(function (data, status, headers, config) {
            $scope.chips_lgr = data.chips_lgr;
             $scope.loading=false;
           $scope.gridOptionslgr.api.setRowData($scope.chips_lgr);
            //$scope.gridOptionslgr.api.sizeColumnsToFit();
            //var allColumnIds = [];
            //columnDefsLgr.forEach(function (columnDef) {
            //    allColumnIds.push(columnDef.field);
            //});
            //$scope.gridOptionslgr.columnApi.autoSizeColumns(allColumnIds);
            $scope.SumOfCreadit = $scope.sum($scope.chips_lgr, 'Credit');
            $scope.SumOfDebit = $scope.sum($scope.chips_lgr, 'Debit'); 
           // setWidthAndHeight('100%','100%');
        });
        


    }
    $scope.ChipLeger(angular.isUndefinedOrNull, angular.isUndefinedOrNull, angular.isUndefinedOrNull, angular.isUndefinedOrNull)
   /* $scope.SumOfCreadit=0;
    $scope.SumOfDebit=0;*/
    $scope.sum = function(items, prop){
        
        return items.reduce( function(a, b){
             
            return parseFloat(a) + parseFloat(b[prop]);
        }, 0);
    };
    $scope.onBtExport = function (hstshow) {
        if (hstshow == true){
             var params = {
                skipHeader: false,
                skipFooters: true,
                skipGroups: true,
                fileName: "ChipHistory.csv"
            };
            $scope.gridOptions.api.exportDataAsCsv(params);   
        }else{
            var params = {
                skipHeader: false,
                skipFooters: true,
                skipGroups: true,
                fileName: "ChipLeger.csv"
            };
            $scope.gridOptionslgr.api.exportDataAsCsv(params);
        }
            

    }
$scope.Reset=function(a, r, l, n)
	{
		
		$scope.from_date=0;
		$scope.to_date=0;
		$scope.MaxDate1=new Date();
		$scope.MaxDate2=new Date();
		$scope.ChipLeger(a, r, l, n);
	}
    /* $scope.onBtExportLeger=function() {
      
        $scope.gridOptionslgr.api.exportDataAsCsv();
     }*/

});




