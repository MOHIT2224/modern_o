app.controller("ListOfMarketCntr",["$scope","$http","$timeout","$log","$mdDialog","Dialog",function(t,e,n,r,a,Dialog){t.loading=!0,t.GetSeriesData=function(){e.get("Geteventcntr/getMarketLst/").success(function(e,n,r,a){t.match_data=e.getMarketLst,t.currentPage=1,t.entryLimit=20,t.filteredItems=t.match_data.length,t.totalItems=t.match_data.length,t.loading=!1}).error(function(e,n,r,a){t.ResponseDetails="Data: "+e+"<br />status: "+n+"<br />headers: "+jsonFilter(r)+"<br />config: "+jsonFilter(a)})},t.GetSeriesData(),t.changeMarketStake=function(n,r){t.loading=!0,e.get("Lstsavemstrcontroller/updateMarketStake/"+n+"/"+r).success(function(e,n,r,a){
    if(e.error==0)
    {
        Dialog.autohide(e.message);
    }
    else
    {
        Dialog.autohide(e.message);
    }
    t.loading=!1})},t.changeLiability=function(n,r,type){
    t.loading=!0;
    var LinkUrl = "";
    var dataArray={};
     if(type=="bet")
    {
        LinkUrl ="Apiadmincontroller/save_match_bet_liablity";
        dataArray={"max_bet_liability":r,"MarketId":n};
    }
    else
    {
        LinkUrl ="Apiadmincontroller/save_match_market_liablity";
        dataArray={"max_market_liability":r,"MarketId":n};
    }
        e.post(BASE_URL+LinkUrl,dataArray).success(function(e,n,r,a){
            if(e.error==0)
            {
                Dialog.autohide(e.message);
            }
            else
            {
                Dialog.autohide(e.message);
            }
            t.loading=!1})},
    t.changeMarketStatus = function(id,status)
    {

        var active=status==true ? 1 : 0;
        var formData={id:id,visibility:active}
        t.loading=true;
        e.post(BASE_URL+'Geteventcntr/updateMarketById',formData).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            t.loading=false;
            t.GetSeriesData();
        }).error(function(data, status, headers, config){
            t.loading=false;
        });
    },
    t.changeProfit=function(n,r,type){
    t.loading=!0;
    var LinkUrl = "";
    var dataArray={};
    LinkUrl ="Apiadmincontroller/save_match_market_profit";
    dataArray={"max_market_profit":r,"MarketId":n};


    e.post(BASE_URL+LinkUrl,dataArray).success(function(e,n,r,a){
        if(e.error==0)
        {
            Dialog.autohide(e.message);
        }
        else
        {
            Dialog.autohide(e.message);
        }
        t.loading=!1})},t.setPage=function(e){t.currentPage=e},t.filter=function(){n(function(){t.filteredItems=t.filtered.length},10)},t.sort_by=function(e){t.predicate=e,t.reverse=!t.reverse}}]),app.filter("startFrom",function(){return function(t,e){return t?(e=+e,t.slice(e)):[]}});