app.controller("Marketlstapicntr", ["$scope", "$http", "$stateParams", "Dialog", "$rootScope", "sessionService","$timeout", function(e, t, a, r, s, o,tm) {
    e.loading = !0, e.sportId = a.sportId;
    var n = a.MatchId;
    e.seriesId = a.seriesId, 
    
      e.sort_by = function (predicate) {
       e.predicate = predicate;
        e.reverse = !e.reverse;
    };

         e.getSportList=function()
    {
    t.get(BASE_URL+"Geteventcntr/getMarketOfMatch/" + n).success(function(t, a, s, o) {
    e.onEnd();
        t.marketfrmApi != angular.isUndefinedOrNull ? e.GetMarketData = t : r.autohide("Record Not Found " + t, 1e4), e.loading = !1,e.currentPage=1,                e.entryLimit = 40
    });
    }
     e.getSportList();
    


    var tempData=[];
   // e.selectAll=localStorage.getItem("selectAlldata1");
    e.SelectorDeselectAll=function(url,tempdata)
    {
      t.post(url, tempData).success(function (response) {
          e.getSportList();
          s.$broadcast("changeSidebar_Market", {});
          e.onEnd();
      })
    }
  
    e.allChk = function(sAll)
    {
//
       e.selectAll=sAll;
       e.loading = true;
       var url ="";
        if(e.GetMarketData!=null)
        {
          
       
            if(e.selectAll==true)
            {
                tempData=[];
                for(var i=0; i<e.GetMarketData.marketfrmApi.length;i++)
                {
                     var obj = {"marketId":e.GetMarketData.marketfrmApi[i].marketId,"marketName":e.GetMarketData.marketfrmApi[i].marketName,"totalMatched":e.GetMarketData.marketfrmApi[i].totalMatched,"MatchId":n,"SportsId":e.sportId,"seriesId":e.seriesId,"HelperID":o.get('HelperID')}

                    tempData.push(obj);
                }
         url = BASE_URL + "Geteventcntr/saveMatchMarketSelectAll";
        
                e.SelectorDeselectAll(url,tempData);
                //localStorage.setItem("checkAll",true);
            }
            else
            {
          url = BASE_URL + "Geteventcntr/saveMatchMarketDeSelectAll";
    
                    for(var i=0; i<e.GetMarketData.marketfrmApi.length;i++)
                {
                     var obj = {"marketId":e.GetMarketData.marketfrmApi[i].marketId,"marketName":e.GetMarketData.marketfrmApi[i].marketName,"totalMatched":e.GetMarketData.marketfrmApi[i].totalMatched,"MatchId":n,"SportsId":e.sportId,"seriesId":e.seriesId,"HelperID":o.get('HelperID')}

                    tempData.push(obj);
                }
                e.SelectorDeselectAll(url,tempData);
               // localStorage.setItem("checkAll",false);
          
            
             
            }
            
        }
        else
        {
          e.loading = false;
        }
       
    }
    e.saveMarket = function(a, c, d) {
        e.loading = !0;
        var i = {
                marketId: a,
                marketName: c,
                totalMatched: d,
                MatchId: n,
                SportsId: e.sportId,
                seriesId: e.seriesId,
                HelperID: o.get("HelperID")
            },
            l = BASE_URL + "Geteventcntr/saveMatchMarket";
        t.post(l, i).success(function(o) {
            0 == o.error ? t.get(BASE_URL+"Geteventcntr/saveSelectionName/" + a + "/" + n + "/" + e.sportId).success(function(t, a, o, n) {
              e.onEnd();
                e.loading = !1, r.autohide(t.message), s.$broadcast("changeSidebar_Market", {})
            }) : (r.autohide(o.message), e.loading = !1, s.$broadcast("changeSidebar_Market", {})
            )
            e.onEnd();
        })
    }
    e.onEnd = function(){
                tm(function(){
                    var len=$('#tblDataMarket tr:has(td)').find("input[type=checkbox]:checked").length;
             //alert(len);
              if(e.GetMarketData.marketfrmApi.length==len)
                {
                    e.selectAll=true;
                    localStorage.setItem("selectAlldata1",true);
                }
                else
                {
                    e.selectAll=false;
                    localStorage.setItem("selectAlldata1",false);
                }
                }, 1);
            };
}]);

 app.directive("repeatEnd", function(){
            return {
                restrict: "A",
                link: function (scope, element, attrs) {
                    if (scope.$last) {
                        scope.$eval(attrs.repeatEnd);
                    }
                }
            };
        });
 app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    }
});
