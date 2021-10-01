app.controller("Get_bethistryCntr",["$scope","$http","$filter","sessionService","loginService","$interval","$state","Base64","$timeout",function(e,a,t,i,l,r,$state,Base64,$timeout){
    e.GetDealer=function(MasterId,Type){
       e.loading=true;
        if(MasterId != null ){
       a.get(BASE_URL+'Betentrycntr/GetDealer/'+MasterId).success(function (data, status, headers, config) { 
          //  
             e.loading=false;
            if (Type==1) {
                  e.loading=false;
                 e.DealerData = data.jsonData;
                 console.log(e.DealerData);
            }else if(Type==2){
                   e.loading=false;
                 e.userData = data.jsonData;
            }       
        }); 
   }
   else{
         e.loading=false;
       e.DealerData='';
        e.userData='';
   }
    }

   e.GetMaster=function(){
      e.loading=true;
        a.get(BASE_URL+'Betentrycntr/GetMasterList/').success(function (data, status, headers, config) { 
          //         
               e.loading=false;
            e.MasterList = data.jsonData;
        });  
    }
e.GetMaster();
e.formData={};
e.from_date=0;
e.to_date=0;
e.MaxDate1=new Date();
e.MaxDate2=new Date();

e.setDateFun=function(type)
{
	
	if(type==2)
	{
 	 e.MaxDate1=e.to_date;
	}
	else
	{
	 e.minDate1=e.from_date;
	}
	
}
var MasterID;
e.callfirst=0;
e.BetHistory=[];
e.callHistory=function(){
   e.loading=true;
a.get(BASE_URL+"Betentrycntr/BetHistory/"+i.get("user_id")).success(function(a,t,i,l){
   e.loading=false;
e.BetHistory=a.BetHistory,


o.api.setRowData(e.BetHistory),n(angular.forEach(e.BetHistory,function(item){item.Type=='Back' ? item.Type='Back' : item.Type='Lay' ; })),e.SumOfP_L=e.sum(e.BetHistory,"P_L"),e.SumOfProfit=e.sum(e.BetHistory,"Profit"),e.SumOfLiability=e.sum(e.BetHistory,"Liability")}),e.sum=function(e,a){return e.reduce(function(e,t){return(null==t[a]||""==t[a])&&(t[a]=0),parseFloat(e)+parseFloat(t[a])},0)

}
//e.callfirst=1;


}
e.callFirstHistory=function(){

}
function d(){if(c){var e={getRows:function(e){setTimeout(function(){var a=c.slice(e.startRow,e.endRow),t=-1;c.length<=e.endRow&&(t=c.length),e.successCallback(a,t)},500)}};o.api.setDatasource(e)}}function n(e){c=e,d()}var s=[{headerName:"SNo.",width:30,field:"SrNo"},{headerName:"Description",width:400,field:"Description",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}},{headerName:"Selection Name",width:100,field:"selectionName",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}},{headerName:"Type",width:70,field:"Type",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}},{headerName:"Odds",width:70,field:"Odds",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}},{headerName:"Stack",width:70,field:"Stack",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}},{headerName:"Date",width:130,field:"MstDate",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}},{headerName:"P_L",width:80,field:"P_L",cellClass:function(e){return"Lay"==e.data.Type?"lay-head col-right":"back-head col-right"},cellStyle:function (e) { return (e.data.P_L>=0 ? {color: 'green !important',"text-align":"right","margin-right":"10px","padding-right":"15px"} : {color: 'red !important',"text-align":"right","margin-right":"10px","padding-right":"15px"}); }},{headerName:"Profit",width:80,field:"Profit",cellClass:"col-right",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"},cellStyle:function (e) { return (e.data.Profit>=0 ? {color: 'green !important',"text-align":"right","margin-right":"10px","padding-right":"15px"} : {color: 'red !important',"text-align":"right","margin-right":"10px","padding-right":"15px"}); }},{headerName:"Liability",width:80,field:"Liability",cellClass:function(e){return"Lay"==e.data.Type?"lay-head col-right":"back-head col-right"},cellStyle:function (e) { return (e.data.Liability>=0 ? {color: 'green !important'} : {color: 'red !important'}); }},{headerName:"Status",width:80,field:"Status",cellClass:function(e){return"Lay"==e.data.Type?"lay-head  col-status":"back-head  col-status"}},{headerName:"Id.",width:50,field:"mstcode"}];0==i.get("type")||1==i.get("type")?(s.splice(3,0,{headerName:"Client",width:100,field:"UserNm",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}}),s.splice(3,0,{headerName:"Dealer",width:100,field:"DealerNm",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}})):2==i.get("type")&&s.splice(3,0,{headerName:"Client",width:100,field:"UserNm",cellClass:function(e){return"Lay"==e.data.Type?"lay-head":"back-head"}});var c,o={ enableSorting: true,
    enableFilter: true,
    suppressRowClickSelection: true,
    groupSelectsChildren: true,
    debug: true,
   rowSelection: 'multiple',
   paginationPageSize:50,
    enableColResize: true,
    
    rowGroupPanelShow: 'always',
   pivotPanelShow: 'always',
   enableRangeSelection: true,
    columnDefs: s,
    pagination: true,

onGridReady:function(){}},h=document.querySelector("#myGrid");new agGrid.Grid(h,o),e.callHistory(),e.onBtExport=function(){var e={skipHeader:!1,skipFooters:!0,skipGroups:!0,fileName:"BetHistory.csv"};o.api.exportDataAsCsv(e)}
    e.GetSearch=function(){

  e.loading=true;
	 MasterID=e.formData.MasterID=="" ? null : e.formData.MasterID,DealerId=e.formData.DealerId=="" ? null : e.formData.DealerId,UserId=e.formData.UserId=="" ? null : e.formData.UserId;
        if(MasterID != angular.isUndefinedOrNull  && DealerId != angular.isUndefinedOrNull && UserId != angular.isUndefinedOrNull){
               e.loading=false;
             e.userId=UserId;
        }else if(MasterID != angular.isUndefinedOrNull && DealerId != angular.isUndefinedOrNull){
              e.loading=false;
             e.userId=DealerId;
        }else if(MasterID != angular.isUndefinedOrNull){
              e.loading=false;
             e.userId=MasterID;
        }else {
            e.loading=false;
           e.userId= i.get('user_id'); 
        }
        var authdata = Base64.encode(i.get('user') + ':' +    i.get('lgPassword'));
	//md-datepicker must be a Date instance. Currently the model is a: string resolved 
	if(e.from_date == "1970-01-01" || e.from_date==0){
		e.from_date1 =0;}
	else{
		e.from_date1 = t('date')(e.from_date, 'yyyy-MM-dd'); 
		}
	if(e.to_date == "1970-01-01" || e.to_date==0){
		e.to_date1 =0;}
	else
	{
		e.to_date1 = t('date')(e.to_date, 'yyyy-MM-dd'); 
	}
    var Bauthdata='Basic ' + authdata;
    a.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
        a.get(BASE_URL+'Betentrycntr/BetHistoryFilters/' +  e.userId + '/' + e.from_date1 + '/' + e.to_date1).success(function (data, status, headers, config) {        
            e.BetHistory = data.BetHistory;            
             o.api.setRowData(data.BetHistory);
            o.api.sizeColumnsToFit();
            e.SumOfP_L = e.sum(e.BetHistory, 'Chips');
            e.SumOfProfit = e.sum(e.BetHistory, 'Credit');
            e.SumOfLiability = e.sum(e.BetHistory, 'Debit'); 
e.userId='';
MasterID='';
DealerId='';
UserId='';
        }); 
    }

	e.Reset = function(){
		$state.reload();
		Master.reset();
 	 	e.formData = {};
	}

}]);

