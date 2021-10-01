app.controller('Chiphistorycntr', function ($scope, $http, $filter, sessionService, loginService, $rootScope, $location) {
    if ($rootScope.HelperAllRights != angular.isUndefinedOrNull && $rootScope.HelperAllRights.ChipHistory == 0) { $location.path('/dashboard/Home'); }//170213
    $scope.legershow = false;
    $scope.hstshow = false;
    $scope.typeDropDown = [
        { value: '0', label: 'Chip' },
        { value: '1', label: 'Free Chip' },
        { value: '2', label: 'Win' },
        { value: '3', label: 'Lose' },
        { value: '5', label: 'All' }
    ];
    $scope.loginId = sessionStorage.user_id;
    $scope.type = sessionStorage.type;
    /*start The Chip History ag Grid*/
    /*start*/
    var columnDefs = [
            { headerName: "SNo", width: 30,field: "SrNo" ,cellClass: function (params) { return (params.data.isBack == 1 ? 'lay-head' : 'back-head');}},
            
            { headerName: "Narration", width: 250, field: "narration", cellStyle: { color: 'BLACK', 'text-align': 'center', 'font-weight': 'bold' } },          
            { headerName: "MstDate", field: "EDate", width: 180 },          
            { headerName: "Credit", field: "Credit", cellStyle: { color: 'GREEN', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px' }, width: 100 },
            { headerName: "Debit", field: "Debit", cellStyle: { color: 'RED', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px' }, width: 100 },
            { headerName: "Odds", field: "Odds", width: 100 },
            { headerName: "Stake", field: "Stack", width: 100 },
            { headerName: "Status", field: "Status_", width: 100 },
            { headerName: "Total", field: "Total", cellStyle: { color: 'BLUE', 'text-align': 'right', 'font-weight': 'bold', 'margin-right': '10px', 'padding-right': '15px' }, width: 100 },
            { headerName: "ID", width: 30,field: "Mstcode" },
    ];
    var columnDefsLgr = [
            { headerName: "Sno",width: 30, field: "SrNo"},
            
            { headerName: "Narration", width: 460, field: "narration", cellRenderer: ageCellRendererFunc, cellStyle: { color: 'BLACK', 'text-align': 'left' } },
            { headerName: "Date", field: "EDate", width: 220, cellStyle: { color: 'BLACK', 'text-align': 'center' } },
            { headerName: "Credit", field: "Credit", width: 100, cellStyle: { color: 'GREEN', 'text-align': 'right' } },
            { headerName: "Debit", field: "Debit", width: 100, cellStyle: { color: 'RED', 'text-align': 'right', 'margin-right': '10px', 'padding-right': '15px' } },
            { headerName: "Balance", field: "Balance", width: 120, cellStyle: { color: 'BLUE', 'text-align': 'right', 'margin-right': '10px', 'padding-right': '15px' } }
            , { headerName: "id", width: 55, field: "Mstcode" },

    ];
    function ageCellRendererFunc(params) {
        //debugger;
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
        $http.get('Betentrycntr/Chip_history/' + params.data.UserID + '/' + sessionStorage.type + '/' + params.data.MatchId + '/' + params.data.MarketId + '/' + params.data.oppAcID).success(function (data, status, headers, config) {
            //debugger;
            $scope.chip_data = data.chips_data;
            setRowData(data.chips_data, '');
            this.gridOptions.api.sizeColumnsToFit();
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
        rowModelType: 'pagination'
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
        rowModelType: 'pagination'
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

        /* $scope.legershow=false;
         $scope.hstshow=true;

         var from = null, to = null, type = null;//sourabh 161215
         if ($scope.from_date == "") {
             from = $scope.from_date;//sourabh 161215
             $filter('date')($scope.from_date, 'yyyy-MM-dd');
         }
         if ($scope.to_date == "") to = $scope.to_date;//sourabh 161215
         if ($scope.slctType != angular.isUndefinedOrNull && $scope.slctType.value != "") type = $scope.slctType.value;//sourabh 161215 + '/' + data.cb1*/
        $http.get('Betentrycntr/Chip_history/' + $scope.loginId + '/' + from + '/' + to + '/' + type + '/' + $scope.type).success(function (data, status, headers, config) {
            $scope.chip_data = data.chips_data;
            setRowData(data.chips_data, '');
            gridOptions.api.sizeColumnsToFit();
        });
    }

    $scope.ChipLeger = function (from_date, to_date, slctType, withParent) {
        //alert("HI");

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
            var fromDate = $filter('date')(from_date, 'yyyy-MM-dd');
            var toDate = $filter('date')(to_date, 'yyyy-MM-dd');
        }



        $http.get('Betentrycntr/Chip_leger/' + sessionStorage.user_id + '/' + sessionStorage.type + '/' + drpdwn + '/' + fromDate + '/' + toDate).success(function (data, status, headers, config) {
            $scope.chips_lgr = data.chips_lgr;
            //debugger;
            setRowData(data.chips_lgr, 'lgr');
            $scope.gridOptionslgr.api.sizeColumnsToFit();
            //var allColumnIds = [];
            //columnDefsLgr.forEach(function (columnDef) {
            //    allColumnIds.push(columnDef.field);
            //});
            //$scope.gridOptionslgr.columnApi.autoSizeColumns(allColumnIds);
        });

    }

    $scope.onBtExport = function (hstshow) {
        if (hstshow == true)
            $scope.gridOptions.api.exportDataAsCsv();
        else
            $scope.gridOptionslgr.api.exportDataAsCsv();

    }
    /* $scope.onBtExportLeger=function() {
      
        $scope.gridOptionslgr.api.exportDataAsCsv();
     }*/

});




