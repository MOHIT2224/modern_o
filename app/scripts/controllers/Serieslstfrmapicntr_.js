app.controller('Serieslstfrmapicntr', function ($scope, $http,$window, $stateParams, Dialog, $rootScope, sessionService,$mdToast, $timeout) {
     
    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
   
    var sportId = $stateParams.sportId;
    $scope.sportsId=sportId;
    var tempData=[];
   

   // $scope.selectAll=localStorage.getItem("checkAll");

    $scope.SelectorDeselectAll=function(url,tempdata)
    {

      $http.post(url, tempData).success(function (response) {
          $scope.getSportList();
          $scope.onEnd();
          
	 $rootScope.$broadcast('changeSidebar_Series', {});
      })
    }
  
    $scope.allChk = function()
    {
       $scope.loading = true;
        if($scope.GetSeriesData!=null)
        {
          
       
            if($scope.selectAll)
            {
                tempData=[];
                for(var i=0; i<$scope.GetSeriesData.seriesfrmApi.length;i++)
                {
                    var obj = {"matchId":$scope.GetSeriesData.seriesfrmApi[i].competition.id,"matchName":$scope.GetSeriesData.seriesfrmApi[i].competition.name,"marketCount":$scope.GetSeriesData.seriesfrmApi[i].marketCount,"region":$scope.GetSeriesData.seriesfrmApi[i].competitionRegion,"sportId":sportId,"HelperID":sessionService.get('HelperID')}
                    tempData.push(obj);
                }
                var url = BASE_URL + "Geteventcntr/saveAllCheckboxSportSeries";
                $scope.SelectorDeselectAll(url,tempData);
                //localStorage.setItem("checkAll",true);
            }
            else
            {
              var url = BASE_URL + "Geteventcntr/saveSportSeriesDeSelectAll";
              if(tempData.length>0)
              {
                  $scope.SelectorDeselectAll(url,tempData);
                  //localStorage.setItem("checkAll",false);
              }
              else
              {
                  for(var i=0; i<$scope.GetSeriesData.seriesfrmApi.length;i++)
                {
                    var obj = {"matchId":$scope.GetSeriesData.seriesfrmApi[i].competition.id,"matchName":$scope.GetSeriesData.seriesfrmApi[i].competition.name,"marketCount":$scope.GetSeriesData.seriesfrmApi[i].marketCount,"region":$scope.GetSeriesData.seriesfrmApi[i].competitionRegion,"sportId":sportId,"HelperID":sessionService.get('HelperID')}
                    tempData.push(obj);
                }
                $scope.SelectorDeselectAll(url,tempData);
               // localStorage.setItem("checkAll",false);
              }
            
             
            }
            
        }
        else
        {
          $scope.loading = false;
        }
       
    }

  
    $scope.getSportList=function()
    {
      $scope.loading = true;
      $http.get('Geteventcntr/getSeriesOfSport/' + sportId).success(function (data, status, headers, config) {
          
        if (data.seriesfrmApi != angular.isUndefinedOrNull) {
              $scope.GetSeriesData = data;
	      $scope.totalitem=$scope.GetSeriesData.seriesfrmApi.length;

               $scope.currentPage = 1;
                $scope.entryLimit = 40;
             // console.log($scope.GetSeriesData.seriesfrmApi.length);
		$scope.onEnd();
             
          }
          else {
              Dialog.autohide("Record Not Found.", 10000);
          }
          $scope.loading = false;
      });
    }
     $scope.getSportList();
      
    $scope.saveSeries = function (id, name) {
        
        $scope.loading = true;
        var formData = { matchId: id, matchName: name,sportId: sportId, HelperID: sessionService.get('HelperID') }
        var url = BASE_URL + "Geteventcntr/saveSportSeries";
        $http.post(url, formData).success(function (response) {
           
            var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);


            $scope.loading = false;
            $rootScope.$broadcast('changeSidebar_Series', {});
            $scope.onEnd();
        });
    }
      $scope.onEnd = function(){
		
                $timeout(function(){
                    var len=$('#tblData tr:has(td)').find("input[type=checkbox]:checked").length;
   			 //alert(len);
                          //$scope.getSportList();
			  if($scope.GetSeriesData.seriesfrmApi.length==len && $scope.GetSeriesData.seriesfrmApi.length >0)
				{
					$scope.selectAll=true;
					localStorage.setItem("checkAll",true);
				}
				else
				{
					$scope.selectAll=false;
					localStorage.setItem("checkAll",false);
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
   
