app.controller('MasterFancycontroller', ['$scope', '$http', '$timeout', '$log', '$mdDialog','Base64','sessionService','$stateParams', function ($scope, $http, $timeout, $log, $mdDialog,Base64,sessionService,stateParams)//sourabh 170103
{

var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
    $scope.loading = true;
    $scope.ismeridian = false;
    $scope.toggleMode = function () {
        $scope.ismeridian = !$scope.ismeridian;
    };

$scope.GetPreviousPage = function(){
window.history.back();

}

	$http.get('Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
		        $scope.sprtData = data.sportData;
		    }).error(function (data, status, header, config) {
		        $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
		    });

	$scope.fetchSessionList = function(){
		 $http.get('Apimastercontroller/get_match_session/' + stateParams.MatchId)
		    .success(function (data, status, headers, config) {
		        $scope.session_data = data.data;
		     //   $scope.getplayer = data.getplayer;
		        $scope.currentPage = 1;
		        $scope.entryLimit = 20;
		        $scope.filteredItems = $scope.session_data.length;
		        $scope.totalItems = $scope.session_data.length;
		        $scope.loading = false;
		    });
	
	}


    $scope.changeMatchStatus = function (matchid, status) {
        $scope.loading = true;
        if (status == true) {
            var newStatus = 'D';
            var result = confirm("Are you sure want to Deactivate this Match ?");
        }
        else {
            var newStatus = 'A';
            var result = confirm("Are you sure want to Activate this Match ?");
        }
        if (result) {
		$http({
                    method: 'POST',
                    url: 'Apimastercontroller/match_status/',
                    data: {
                       match_id:matchid,
		      status:newStatus
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {

 var s = 0;
    $http.get('Apimastercontroller/get_matches/')
            .success(function (data, status, headers, config) {
                $scope.match_data = data.data;
             //   $scope.getplayer = data.getplayer;
                $scope.currentPage = 1;
                $scope.entryLimit = 20;
                $scope.filteredItems = $scope.match_data.length;
                $scope.MatchtotalItems = $scope.match_data.length;
                $scope.loading = false;
            });
            });
        }
        else{
            $scope.loading = false;
        }
    };

$scope.changeSessionStatus = function (fancyid,status) {

        $scope.loading = true;
        if (status == true) {
            var newStatus = 'D';
            var result = confirm("Are you sure want to Deactivate this Session ?");
        }
        else {
            var newStatus = 'A';
            var result = confirm("Are you sure want to Activate this Session ?");
        }
        if (result) {
		$http({
                    method: 'POST',
                    url: 'Apimastercontroller/match_session_status/',
                    data: {
                       fancy_id:fancyid,
		       status:newStatus
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {

                var s = 0;
                $http.get('Apimastercontroller/get_match_session/' + stateParams.MatchId).success(function (data, status, headers, config) {
                    
                    $scope.session_data = data.data;
		     //   $scope.getplayer = data.getplayer;
		        $scope.currentPage = 1;
		        $scope.entryLimit = 20;
		        $scope.filteredItems = $scope.session_data.length;
		        $scope.totalItems = $scope.session_data.length;
		        $scope.loading = false;
		   
                });
            });
        }
        else{
            $scope.loading = false;
        }
    };

    var s = 0;
    $http.get('Apimastercontroller/get_matches/')
            .success(function (data, status, headers, config) {
                $scope.match_data = data.data;
             //   $scope.getplayer = data.getplayer;
                $scope.currentPage = 1;
                $scope.entryLimit = 20;
                $scope.filteredItems = $scope.match_data.length;
                $scope.MatchtotalItems = $scope.match_data.length;
                $scope.loading = false;
            });
    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function () {
        $timeout(function () {
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
}]);
app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    }
});



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
          matches = matches && (key=='marketCount' ? item[key] === value : angular.lowercase(item[key]).match(angular.lowercase(value)));  
        }
      });
      if(matches){
        matching.push(item);  
      }
    });
    return matching;
  }
});
