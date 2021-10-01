app.controller("Seriescontroller",["$scope","$http","$timeout","$log","$mdDialog","Dialog",function(e,t,a,i,n,Dialog){e.loading=!0,e.GetSeriesData=function(a){t.get(BASE_URL+"Geteventcntr/getSeriesLst/"+a).success(function(t,a,i,n){e.match_data=t.seriesLst,e.currentPage=1,e.entryLimit=20,e.maxSize=5;e.filteredItems=e.match_data.length,e.totalItems=e.match_data.length,e.loading=!1}).error(function(t,a,i,n){e.ResponseDetails="Data: "+t+"<br />status: "+a+"<br />headers: "+jsonFilter(i)+"<br />config: "+jsonFilter(n)})},e.GetSeriesData(0),e.changeMatchStatus=function(a,i){if(1==i)var n=1;else var n=0;if(e.loading=!0,1==n)var s=confirm("Are you sure want to Activate this Series ?");else var s=confirm("Are you sure want to Deactivate this Series ?");s?t.get(BASE_URL+"Lstsavemstrcontroller/updateSeriesSatatus/"+a+"/"+n+"/").success(function(t,a,i,n){
    Dialog.autohide(t.message);e.GetSeriesData(0)}).error(function(t,a,i,n){e.ResponseDetails="Data: "+t+"<br />status: "+a+"<br />headers: "+jsonFilter(i)+"<br />config: "+jsonFilter(n)}):e.loading=!1},
  e.viewby = 20;
  e.entryLimit = e.viewby;
e.setItemsPerPage = function(num) {
  e.entryLimit = num;
  e.currentPage = 1; //reset to first page
},e.setPage=function(t){e.currentPage=10},
e.filter = function () {
        a(function () {
            e.filteredItems = e.filtered.length;
        }, 10);
    };

t.get(BASE_URL+'Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
                e.sprtData = data.sportData;
            }).error(function (data, status, header, config) {
                e.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
            });
e.sort_by=function(t){e.predicate=t,e.reverse=!e.reverse}}]),app.filter("startFrom",function(){return function(e,t){return e?(t=+t,e.slice(t)):[]}}),app.directive("modal",function(){return{template:'<div class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">{{fancyHeaderName}} Fancy</h4></div><div class="modal-body" ng-transclude></div></div></div></div>',restrict:"E",transclude:!0,replace:!0,scope:!0,link:function(e,t,a){e.$watch(a.visible,function(e){1==e?$(t).modal("show"):$(t).modal("hide")}),$(t).on("shown.bs.modal",function(){e.$apply(function(){e.$parent[a.visible]=!0})}),$(t).on("hidden.bs.modal",function(){e.$apply(function(){e.$parent[a.visible]=!1})})}}});

app.filter('exact', function(){
  return function(items, match){
    var matching = [], matches, falsely = true;

    // Return the items unchanged if all filtering attributes are falsy
    angular.forEach(match, function(value, key){
      falsely = falsely && !value;
    });

    if(falsely){
      return items;
    }

    angular.forEach(items, function(item){ // e.g. { title: "ball" }
      matches = true;
      angular.forEach(match, function(value, key){ // e.g. 'all', 'title'
        if(!!value){ // do not compare if value is empty
          matches = matches && (key=='mCount' ? item[key] === value : angular.lowercase(item[key]).match(angular.lowercase(value)));  
        }
      });
      if(matches){
        matching.push(item);  
      }
    });
    return matching;
  }
});
