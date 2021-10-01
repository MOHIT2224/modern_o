app.controller('Onlineusersctrl', function($scope,$http,$filter,sessionService, loginService,$interval){
   $scope.loading=true;
$scope.formData = {};
  $scope.loginId = sessionService.get('user_id');
$scope.userId= sessionService.get('user_id');
  $scope.type = sessionService.get('type');
$scope.UType=sessionService.get('type');
$scope.MId=0;
$scope.DId=0;
$scope.UId=0;

$scope.MType=0;
$scope.DType=0;
$scope.UType=0;

    $scope.GetMaster=function(){
        $http.get(BASE_URL+'Betentrycntr/GetMasterList/').success(function (data, status, headers, config) { 
          //         
            $scope.MasterList = data.jsonData;
        });  
    }

    $scope.GetDealer=function(MasterId,Type){
        if(MasterId != null ){
       $http.get(BASE_URL+'Betentrycntr/GetDealer/'+MasterId).success(function (data, status, headers, config) { 
          //  
            if (Type==1) {
                 $scope.DealerData = data.jsonData;
		  $scope.userData=[];
		  $scope.type=1;
		  $scope.MId=MasterId;
		  $scope.MType=1;
                // console.log($scope.DealerData);
            }else if(Type==2){
                 $scope.userData = data.jsonData;
		 $scope.type=2;
		  $scope.DId=MasterId;
		  $scope.DType=2;
            }
	    else if(Type==3)
		{
			$scope.type=3;
			 $scope.UId=MasterId;
		 	 $scope.UType=3;
		}
	    else
		{
			$scope.type=0;
		}       
        }); 
   }
   else{
       $scope.DealerData='';
        $scope.userData='';
	
	if(Type==1)
	{
	$scope.userId= sessionService.get('user_id');
 	 $scope.type = sessionService.get('type');
	$scope.UType= sessionService.get('type');
	}
   }
    }
    $scope.GetMaster();
   $scope.setTypeAndId=function()
	{
		if($scope.UId!=0)
		{
			$scope.UType=3;	
			$scope.userId=$scope.UId;
		}
		else if($scope.DId!=0)
		{
			$scope.UType=2;	
			$scope.userId=$scope.DId;
		}
		else if($scope.MI!=0)
		{
			$scope.UType=1;	
			$scope.userId=$scope.MId;
		}
	}
   $scope.GetSearch=function(){
        $scope.loading=true;
	var MasterID=$scope.formData.MasterID=="" ? null : $scope.formData.MasterID,DealerId=$scope.formData.DealerId=="" ? null : $scope.formData.DealerId,UserId=$scope.formData.UserId=="" ? null : $scope.formData.UserId;
        if(MasterID != angular.isUndefinedOrNull  && DealerId != angular.isUndefinedOrNull && UserId != angular.isUndefinedOrNull){
             $scope.userId=UserId;
		$scope.UType=3;
        }else if(MasterID != angular.isUndefinedOrNull && DealerId != angular.isUndefinedOrNull){
             $scope.userId=DealerId;
		$scope.UType=2;
        }else if(MasterID != angular.isUndefinedOrNull){
             $scope.userId=MasterID;
	     $scope.UType=1;
        }else {
           $scope.userId= sessionService.get('user_id'); 
		$scope.UType=0;
        }
        
        $http.get(BASE_URL+'Betentrycntr/online_users/'+$scope.UType+'/'+ $scope.userId).success(function (data, status, headers, config) {   
            $scope.loading=false;     
            $scope.online_user = data.online_user;
            gridOptions.api.setRowData(data.online_user); 
//$scope.userId='';
MasterID='';
DealerId='';
UserId='';
        }); 
    }
       
$scope.Reset=function()
{
		$scope.formData = {};
$scope.userId= sessionService.get('user_id');
  $scope.type = sessionService.get('type');
$scope.UType= sessionService.get('type');
	 $http.get(BASE_URL+'Betentrycntr/online_users/'+sessionService.get('type')+'/'+sessionService.get('user_id')).success(function (data, status, headers, config) { 
            $scope.loading=false;
            $scope.online_user = data.online_user;
            gridOptions.api.setRowData(data.online_user); 
		
        });
}
$scope.Reset();
        //$timeout(get_onlineusers(), 1000);
        $scope.onBtExport = function () {
            var params = {
                skipHeader: false,
                skipFooters: true,
                skipGroups: true,
                fileName: "online_users.csv"
            };
            gridOptions.api.exportDataAsCsv(params);
        }
        
       $scope.stop= $interval( function(){ $scope.get_onlineusers(); }, 4000);
                  /*strat*/
                $scope.get_onlineusers=function(){
                    $http.get(BASE_URL+'Betentrycntr/online_users/'+$scope.UType+'/'+ $scope.userId).success(function (data, status, headers, config) {
                                                      
                        if ($scope.online_user.length == data.online_user.length) {

                             $scope.online_user = data.online_user;
                        }else{
                            $scope.online_user = data.online_user;
                            gridOptions.api.setRowData(data.online_user);   
                        }
                    });
                }
                       
             
                /*end*/
   
          /*start*/
            var columnDefs = [
                // this row just shows the row index, doesn't use any data from the row
                {headerName: "#", width: 30, cellRenderer: function(params) {
                    return ++params.node.id;
                } },
                
                {headerName: "User ID", field: "mstruserid", width: 350},
                {headerName: "Login Time", field: "logstdt", width: 350},
                {headerName: "IP Address", field: "ipadress", width: 350},
               
            ];

            var gridOptions = {
                // note - we do not set 'virtualPaging' here, so the grid knows we are doing standard paging
                enableSorting: true,
                enableFilter: true,
                debug: true,
                rowSelection: 'multiple',
                enableColResize: true,
                paginationPageSize: 500,
                columnDefs: columnDefs,
                pagination: 'true'
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

            function createNewDatasource() {
                if (!allOfTheData) {
                    // in case user selected 'onPageSizeChanged()' before the json was loaded
                    return;
                }

                var dataSource = {
                    //rowCount: ???, - not setting the row count, infinite paging will be used
                    getRows: function (params) {
                       
                        //console.log('asking for ' + params.startRow + ' to ' + params.endRow);
                        setTimeout( function() {
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

                gridOptions.api.setDatasource(dataSource);
            }

            function setRowData(rowData) {
                allOfTheData = rowData;
                createNewDatasource();
            }

            // setup the grid after the page has finished loading
            //document.addEventListener('DOMContentLoaded', function() {
                var gridDiv = document.querySelector('#myGrid');
                new agGrid.Grid(gridDiv, gridOptions);

               
                $scope.$on('$destroy', function() {
          // Make sure that the interval is destroyed too
         $interval.cancel($scope.stop);
        });
                
            //});

              /*End */
        /*End of AG_GRID*/
        /*End of Ag Grid*/
    });

  //end
   
