app.controller('Matchlstfrmapicntr', function ($scope,$timeout, $http, $stateParams, Dialog, $rootScope, sessionService,$filter,$mdToast) {
    $scope.loading = true;
    var sportId = $stateParams.sportId;
    $scope.seriesId = $stateParams.seriesId;

    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
     $scope.getSportList=function()
    {
    $http.get('Geteventcntr/getMatchOfSport/' + sportId + '/' + $scope.seriesId).success(function (data, status, headers, config) {
        
        if (data.matchfrmApi != angular.isUndefinedOrNull) {
            $scope.GetSeriesData = data;
            $scope.currentPage = 1;
                $scope.entryLimit = 40;
             $scope.onEnd();
        }
        else {
            Dialog.autohide("Record Not Found "+data,10000);
        }
        $scope.loading = false;
    });
}
      $scope.getSportList();


     var tempData=[];
   // $scope.selectAll=localStorage.getItem("selectAlldata");
    $scope.SelectorDeselectAll=function(url,tempdata)
    {
      $http.post(url, tempData).success(function (response) {
          $scope.getSportList();
          $scope.onEnd();
        $rootScope.$broadcast('changeSidebar_Match', {});
      })
    }
  
    $scope.allChk = function(sAll)
    {
  $scope.selectAll=sAll;
       $scope.loading = true;
       var url ="";
        if($scope.GetSeriesData!=null)
        {
       
            if($scope.selectAll)
            {
                tempData=[];
                for(var i=0; i<$scope.GetSeriesData.matchfrmApi.length;i++)
                {
                     var obj = {"seriesId":$scope.seriesId,"matchId":$scope.GetSeriesData.matchfrmApi[i].event.id,"matchName":$scope.GetSeriesData.matchfrmApi[i].event.name,"openDate":$scope.GetSeriesData.matchfrmApi[i].event.openDate,"countryCode":null,"marketCount":$scope.GetSeriesData.matchfrmApi[i].marketCount,"sportId":sportId,"HelperID":sessionService.get('HelperID')}
                    tempData.push(obj);
                }
    if($scope.seriesId != angular.isUndefinedOrNull)
    {
       url = BASE_URL + "Geteventcntr/saveSportMatchSelectAll";
    }
                $scope.SelectorDeselectAll(url,tempData);
               // localStorage.setItem("selectAlldata",true);
            }
            else
            {
             
          if($scope.seriesId != angular.isUndefinedOrNull)
    {
        url = BASE_URL + "Geteventcntr/saveSportMatchDeSelectAll";
    }
                  for(var i=0; i<$scope.GetSeriesData.matchfrmApi.length;i++)
                {
                    var obj = {"seriesId":$scope.seriesId,"matchId":$scope.GetSeriesData.matchfrmApi[i].event.id,"matchName":$scope.GetSeriesData.matchfrmApi[i].event.name,"openDate":$scope.GetSeriesData.matchfrmApi[i].event.openDate,"countryCode":null,"marketCount":$scope.GetSeriesData.matchfrmApi[i].marketCount,"sportId":sportId,"HelperID":sessionService.get('HelperID')}
                    tempData.push(obj);
                }
                $scope.SelectorDeselectAll(url,tempData);
                //localStorage.setItem("selectAlldata",false);
          
            
             
            }
            
        }
        else
        {
          $scope.loading = false;
        }
       
    }
    $scope.saveMatch = function (paramsd) {
       
        $scope.loading = true;
        var NewOpenDate = $filter('date')(paramsd.eventDate , 'yyyy-MM-dd HH:mm:ss');

        var formData = 
        { 
            seriesId: $scope.seriesId, 
            matchId: paramsd.eventId, 
            matchName: paramsd.eventName, 
            openDate: paramsd.eventDate,
            sportId: sportId, 
            HelperID: sessionService.get('HelperID'),
            marketId: paramsd.marketId,
            marketName: paramsd.marketName,
            selectionId1:  paramsd.selectionId1,
            runnerName1:  paramsd.runnerName1,
            selectionId2:  paramsd.selectionId2,
            runnerName2:  paramsd.runnerName2,
            selectionId3:  paramsd.selectionId3,
            runnerName3:  paramsd.runnerName3
         }
        var url = BASE_URL + "Geteventcntr/saveSportMatch";
        $http.post(url, formData).success(function (response) {
          
var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);
            //$mdToast.hide();
            $scope.loading = false;
            if(response.error==1)
            {
                
                $rootScope.$broadcast('changeSidebar_Match', {sportsId:sportId,seriesId:$scope.seriesId});
            }
           $rootScope.$broadcast('changeSidebar_Match', {sportsId:sportId,seriesId:$scope.seriesId});
       $scope.onEnd();
        });
    }
      $scope.onEnd = function(){
                $timeout(function(){
                    var len=$('#tblData1 tr:has(td)').find("input[type=checkbox]:checked").length;
             //alert(len);
              if($scope.GetSeriesData.matchfrmApi.length==len  && $scope.GetSeriesData.matchfrmApi.length >0)
                {
                    $scope.selectAll=true;
                    localStorage.setItem("selectAlldata",true);
                }
                else
                {
                    $scope.selectAll=false;
                    localStorage.setItem("selectAlldata",false);
                }
                }, 1);
            };

});

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
   
