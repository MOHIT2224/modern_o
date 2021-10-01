app.controller('OnePageRprtCntr',['$scope', '$http', 'sessionService', '$location','$state','Dialog','$filter','$stateParams', function ($scope, $http, sessionService, $location,$state,Dialog,$filter,$stateParams) {
$scope.UserType=sessionService.get('type');

$scope.formData={};
$scope.formData.transaction_type="";
$scope.formData.from_date =new Date();
$scope.formData.to_date=new Date();
$scope.formData.MaxDate1=new Date();
$scope.formData.MaxDate2=new Date();

$scope.formData.from_time='';
$scope.formData.to_time='';
$scope.hide=function()
{
	$scope.IsShowIndex=-1;
}
 $scope.expandAll = function(expanded) {
        // $scope is required here, hence the injection above, even though we're using "controller as" syntax
        $scope.$broadcast('onExpandAll', {
          expanded: expanded
        });
      };
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

 $(function() {
                    $('#from_time').timepicker({ 'timeFormat': 'H:i:s' });

                });
   $(function() {
                    $('#to_time').timepicker({ 'timeFormat': 'H:i:s' });
                });



 //$scope.formData.from_date = new Date();
 //$scope.formData.to_date = new Date();
  
 $scope.sum = function(items, prop){
        
        return items.reduce( function(a, b){
             
             if (b[prop]==null || b[prop]=='') b[prop]=0;            
                return parseFloat(a) + parseFloat(b[prop]);
        }, 0);
    };
   
$scope.ResetAll=function()
{
	$state.reload();
}
    $scope.GetDealer=function(MasterId,Type){

	$scope.selectedId=MasterId;

        if(MasterId != null ){
       $http.get('Betentrycntr/GetDealer/'+MasterId).success(function (data, status, headers, config) { 
          //  
 		 if (Type==0) {
                 $scope.MasterData = data.jsonData;
                 
            }
            else if (Type==1) {
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

    $scope.IsBtnActive=1;
    $scope.IsBtnTypes='M';
    $scope.FilterBetHistory=function(type,indx)
    {
        $scope.IsBtnActive=indx;
        $scope.IsBtnTypes=type;
        $scope.Allsport(1);
    }

$scope.GetReportId = function(id){
$scope.ReportType=id;
$scope.formData.ReportType=id;
sessionService.set('ReportType',id);
//$scope.Allsport(1);
}
$scope.SetType=function(type)
{
    $scope.formData.transaction_type=type;
}
if(sessionService.get('ReportType') == undefined){
 $scope.ReportType=1;
$scope.formData.ReportType='1';
//$scope.formData.MasterId=$stateParams.userId;
}else{
$scope.ReportType=sessionService.get('ReportType');
$scope.formData.ReportType=$scope.ReportType;
}

if($stateParams.typeId!=angular.isUndefinedOrNull){
$scope.ReportType=$stateParams.typeId;
$scope.formData.ReportType=$scope.ReportType;
}

$scope.GetDealer(sessionService.get('user_id'),$scope.UserType);

    $scope.Allsport=function(page){
	$scope.loading= true;
	$scope.currentPage=page;
	if($scope.formData.ReportType==3)
    {
        var ReportData = {
            user_id: $scope.selectedId,
            from_date:$filter('date')($scope.formData.from_date, 'yyyy-MM-dd'),
            to_date:$filter('date')($scope.formData.to_date, 'yyyy-MM-dd'),
            from_time:$scope.formData.from_time,
            to_time:$scope.formData.to_time,
            type: $scope.ReportType,
            page_no: page,
            sport_id:0,
            bet_type:$scope.IsBtnTypes,
            transaction_type:$scope.formData.transaction_type,
            ac_type:$scope.formData.Ac_type
        }
    }
	else
    {
        var ReportData = {
            user_id: $scope.selectedId,
            from_date:$filter('date')($scope.formData.from_date, 'yyyy-MM-dd'),
            to_date:$filter('date')($scope.formData.to_date, 'yyyy-MM-dd'),
            from_time:$scope.formData.from_time,
            to_time:$scope.formData.to_time,
            type: $scope.ReportType,
            page_no: page,
            sport_id:0,
            bet_type:$scope.IsBtnTypes
        }
    }

		$http({
                        method: 'POST',
                        url: 'Apiadmincontroller/one_page_report/',
                        data: ReportData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data, status, headers, config) {
		if(data.error==1)
        {
            Dialog.autohide(data.message);
            $scope.loading= false;
        }
        else {
            $scope.selectedtype=$scope.formData.ReportType;
            $scope.userPL = data.data;
            $scope.loading= false;
            $scope.selectedIndex=0;
            $scope.totalCount = data.count;
            $scope.entryLimit=15;
            $scope.maxSize=10;

            if($scope.selectedtype == '3'){

                $scope.SumOfCredit = data.tot_credit;
                $scope.SumOfDebit = data.tot_debit;
                $scope.SumOfBalance = data.tot_balance;
            }
            else if($scope.selectedtype == '2' || $scope.selectedtype == '1'){

                $scope.SumOfProfit = $scope.sum($scope.userPL,'Profit');
                $scope.SumOfP_L = $scope.sum($scope.userPL,'P_L');
                $scope.SumOfLiability = $scope.sum($scope.userPL,'Liability');
                $scope.GrandSumOfP_L = data.tot_p_l;
                $scope.GrandSumOfProfit = data.tot_profit;
                $scope.GrandSumOfLiability = data.tot_liability;
                $scope.Grandtot_Comm = data.tot_Comm;
                $scope.Grandtot_PnL = data.tot_PnL;

            }

        }

          
        });
$scope.ExportAllDate();
    }

	 
/////////////////////////////
$scope.ProfitLossByMatchId = function(matchs,index)
{
		$scope.loading= true;
		matchs.isShow=!matchs.isShow;
		$scope.IsShowIndex=index;
		if(true){
		var ReportData={'user_id':matchs.UserId,'match_id':matchs.matchId}
	$http({
                        method: 'POST',
                        url: BASE_URL + 'Betentrycntr/profitLossByMatchId',
                        data: ReportData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data, status, headers, config) {
					
					$scope.MatchPL=data.userPL;
					$scope.loading= false;
			}).error(function (data, status, headers, config) {
				$scope.loading= false;
			});
		}
		else
		{
			$scope.loading= false;
		}
}
/////////////////////////////	
$scope.ExportAllDate=function(){

    if($scope.formData.ReportType==3) {
        var ReportData = {
            user_id: $scope.selectedId,
            from_date: $filter('date')($scope.formData.from_date, 'yyyy-MM-dd'),
            to_date: $filter('date')($scope.formData.to_date, 'yyyy-MM-dd'),
            from_time: $scope.formData.from_time,
            to_time: $scope.formData.to_time,
            type: $scope.ReportType,
            bet_type: $scope.IsBtnTypes,
            transaction_type: $scope.formData.transaction_type,
            ac_type:$scope.formData.Ac_type

        }
    }
    else {
        var ReportData = {
            user_id: $scope.selectedId,
            from_date: $filter('date')($scope.formData.from_date, 'yyyy-MM-dd'),
            to_date: $filter('date')($scope.formData.to_date, 'yyyy-MM-dd'),
            from_time: $scope.formData.from_time,
            to_time: $scope.formData.to_time,
            type: $scope.ReportType,
            bet_type: $scope.IsBtnTypes


        }
    }
		$http({
                        method: 'POST',
                        url: 'Apiadmincontroller/one_page_report_export/',
                        data: ReportData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data, status, headers, config) {

		    $scope.selectedtype=$scope.formData.ReportType;
		    $scope.ExportuserPL = data.data;
		   
			if(  $scope.selectedtype == 1){
			// Prepare Excel data:
		$scope.fileName = "BetHistoryReport";
		$scope.exportData = [];

	  // Headers:
                if($scope.IsBtnTypes=='P')
                {
                    $scope.exportData.push(["Settled","Description", "Username","Type","Odds","Stack","Profit|Loss","Status"]);
                }
                else
                {
                    $scope.exportData.push(["Placed","Description", "Username","Type","Odds","Stack","Liability","Potential Profit"]);
                }

	  // Data:
		angular.forEach($scope.ExportuserPL, function(value, key) {
            if($scope.IsBtnTypes=='P')
            {
                var tempDescription=value.Description + " - " + value.selectionName + " - " +value.marketName+ " - " + "Bet Id :" +value.mstcode +" | Placed:" +value.MatchedDate;
                $scope.exportData.push([value.MstDate,tempDescription, value.UserNm, value.Type, value.Odds, value.Stack,  value.P_L,value.STATUS]);
            }
            else
            {

                var type=$scope.IsBtnTypes=='M' ? ' | Matched' : '';
                var tempDescription=value.Description + " - " + value.selectionName + " - " +value.marketName+ " - " + "Bet Id :" +value.mstcode + type + value.MatchedDate;
                $scope.exportData.push([value.MstDate,tempDescription, value.UserNm, value.Type, value.Odds, value.Stack, value.Liability,value.P_L]);
            }

		});
}
else if($scope.selectedtype == 2){
			// Prepare Excel data:
		$scope.fileName = "ProfitLossReport";
		$scope.exportData = [];
	  // Headers:
		$scope.exportData.push([ "EventName","P_L","Comm","CreatedOn"]);
	  // Data:
		angular.forEach($scope.ExportuserPL, function(value, key) {
	       $scope.exportData.push([ value.EventName,value.PnL, value.Comm, value.settle_date]);
		});
}
else if($scope.selectedtype == 3){
			// Prepare Excel data:
		$scope.fileName = "AccountStatementReport";
		$scope.exportData = [];
	  // Headers:
		$scope.exportData.push([ "Date", "UserName","Narration","Credit","Debit","Balance"]);
	  // Data:
		angular.forEach($scope.ExportuserPL, function(value, key) {
	    $scope.exportData.push([value.Sdate, value.mstrUserId, value.Narration, value.Credit, value.Debit, value.Balance]);
		});
}else{

	// Prepare Excel data:
		$scope.fileName = "LoginHistoryReport";
		$scope.exportData = [];
	  // Headers:
		$scope.exportData.push([ "Date","Ip Address", "UserName","Device Info","Browser Info"]);
	  // Data:
		angular.forEach($scope.ExportuserPL, function(value, key) {
	    $scope.exportData.push([value.logstdt, value.ipadress, value.mstruserid,value.device_info, value.browser_info,]);
		});
}
          
        });
    }
    
    


      
    
//$scope.Allsport(1);
//$scope.ExportAllDate();

    $scope.click_test=function(sprtid,page){
$scope.loading= true;
 var ReportData = {
		 user_id: $scope.selectedId,
                from_date: $scope.formData.from_date,
                to_date: $scope.formData.to_date,
		from_time:$scope.formData.from_time,
		to_time:$scope.formData.to_time,
                type: $scope.ReportType,
		page_no: page,
		sport_id:sprtid
             
            }
		$http({
                        method: 'POST',
                        url: 'Apiadmincontroller/one_page_report/',
                        data: ReportData,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data, status, headers, config) {

        
            $scope.userPL = data.data;
	    $scope.selectedtype=$scope.formData.ReportType;
	    $scope.loading= false;
            
        });
    }

$scope.showBet=function(pldata){
	
	if($scope.UserType == 0)
       $state.go("dashboard.PnlPlMiSheet", { 'MarketId': pldata,'StateName':'dashboard.OnePageRprt' });
	else if($scope.UserType == 1)  
       $state.go("masterDashboard.PnlPlMiSheet", { 'MarketId': pldata,'StateName':'masterDashboard.OnePageRprt' });
	else if($scope.UserType == 2)  
       $state.go("dealerDashboard.PnlPlMiSheet", { 'MarketId': pldata,'StateName':'dealerDashboard.OnePageRprt' });
	else
       $state.go("userDashboard.PnlPlMiSheet", { 'MarketId': pldata });
    };

    $scope.deletePl=function(Mid){
		var isDelete = confirm("Are you sure to delete this record ?");
		if(isDelete)
		{
			$http.get("Apiadmincontroller/delete_match_profit_loss/"+Mid).success(function(data, status, headers, config){
			
				if(!data.error){
				 // Dialog.autohide(data.message);
				   $scope.Allsport(0);
				}
				 Dialog.autohide(data.message);
			}).error(function(){
				
			});
		}
	}

$scope.CalculateSum=function(data,key)
{
		
	var temp="";
      if (angular.isUndefined(data) || angular.isUndefined(key))
        return 0;
      var sum = 0;

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
	var temp="";
      if (angular.isUndefined(data) || angular.isUndefined(key))
        return 0;
      var sum = 0;

      angular.forEach(data, function(v, k) {
        sum = sum + parseFloat(v[key]);
        temp=parseFloat(sum).toFixed(2);
      });
      return temp;
    }
  });


/* Directive */
app.directive('expand', function () {
            function link(scope, element, attrs) {
                scope.$on('onExpandAll', function (event, args) {
                    scope.expanded = args.expanded;
                });
            }
            return {
                link: link
            };
        });
  app
    .directive('excelExport',
      function () {
        return {
          restrict: 'A',
          scope: {
            fileName: "@",
              data: "&exportData"
          },
          replace: true,
          template: '<button class="btn btn-primary btn-ef btn-ef-3 btn-ef-3c mb-10" ng-click="download()">Export to Excel <i class="fa fa-download"></i></button>',
          link: function (scope, element) {

            scope.download = function() {

              function datenum(v, date1904) {
                  if(date1904) v+=1462;
                  var epoch = Date.parse(v);
                  return (epoch - new Date(Date.UTC(1899, 11, 30))) / (24 * 60 * 60 * 1000);
                };

                function getSheet(data, opts) {
                  var ws = {};
                  var range = {s: {c:10000000, r:10000000}, e: {c:0, r:0 }};
                  for(var R = 0; R != data.length; ++R) {
                    for(var C = 0; C != data[R].length; ++C) {
                      if(range.s.r > R) range.s.r = R;
                      if(range.s.c > C) range.s.c = C;
                      if(range.e.r < R) range.e.r = R;
                      if(range.e.c < C) range.e.c = C;
                      var cell = {v: data[R][C] };
                      if(cell.v == null) continue;
                      var cell_ref = XLSX.utils.encode_cell({c:C,r:R});

                      if(typeof cell.v === 'number') cell.t = 'n';
                      else if(typeof cell.v === 'boolean') cell.t = 'b';
                      else if(cell.v instanceof Date) {
                        cell.t = 'n'; cell.z = XLSX.SSF._table[14];
                        cell.v = datenum(cell.v);
                      }
                      else cell.t = 's';

                      ws[cell_ref] = cell;
                    }
                  }
                  if(range.s.c < 10000000) ws['!ref'] = XLSX.utils.encode_range(range);
                  return ws;
                };

                function Workbook() {
                  if(!(this instanceof Workbook)) return new Workbook();
                  this.SheetNames = [];
                  this.Sheets = {};
                }

                var wb = new Workbook(), ws = getSheet(scope.data());
                /* add worksheet to workbook */
                wb.SheetNames.push(scope.fileName);
                wb.Sheets[scope.fileName] = ws;
                var wbout = XLSX.write(wb, {bookType:'xlsx', bookSST:true, type: 'binary'});

                function s2ab(s) {
                  var buf = new ArrayBuffer(s.length);
                  var view = new Uint8Array(buf);
                  for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
                  return buf;
                }

              saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), scope.fileName+'.xlsx');

            };

          }
        };
      }
   );

