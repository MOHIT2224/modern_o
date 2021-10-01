app.controller('Lstcontroller', function ($scope, $http, $timeout,$log) {
$scope.loading=true;
    $http.get('Lstsavemstrcontroller/').success(function (data, status, headers, config) {
	   
            $scope.users = data;
            $scope.loading=false;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 15; //max no of items to display in a page
	    $scope.maxSize=5;
            $scope.filteredItems = $scope.users.length; //Initially for no filter  
            $scope.totalItems = $scope.users.length;
            $scope.noPages = Math.ceil($scope.filteredItems /  $scope.entryLimit);

        });
 

  

    //start Pagination
    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };
  
    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    //end of Pagination Function         
    $scope.getStatus = function (user, status) {
        //alert(id+"hhh"+status);
	
	var id=user.usecode;
         $scope.loading = true;
        if (status == true) {
            var newStatus=1;
            var result = confirm("Are you sure want to Deactivate this User ?");
        } else {
            var result = confirm("Are you sure want to Activate this User ?");
            var newStatus=0;
        }

        if (result) {  $http.get('Lstsavemstrcontroller/updateMstr/' + id + '/' + newStatus ).success(function (data, status, headers, config) { 
		user.active=data.active;        
		$scope.loading = false; 
}); 
}
       else{
            $scope.loading = false;
        }
    }
    //chnage the stack limit sourabh 15-nov-2016
    $scope.changeStakeLimit = function (limit, usecode) {
        
        $http.get('Lstsavemstrcontroller/updateStakeLimit/' + limit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/')
                    .success(function (data, status, headers, config) {
                        $scope.users = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 20;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert('Limit Updated Successfully');
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the stack limit sourabh 15-nov-2016

 //chnage the changeCommission Manish 25-nov-2016
    $scope.changeCommission = function (commission, usecode) {
        alert('df');
        $http.get('Lstsavemstrcontroller/updateCommission/' + commission + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/')
                    .success(function (data, status, headers, config) {
                        $scope.users = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 20;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert('Commission Updated Successfully');
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };



    $scope.UpdateMaxProfit = function (profit, usecode) {
        
        $http.get('Lstsavemstrcontroller/UpdateMaxProfit/' + profit + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/')
                    .success(function (data, status, headers, config) {
                        $scope.users = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 20;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert(data.message);
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the stack limit Manish 30-nov-2016
     //chnage the Max UpdateMaxLoss Manish 30-nov-2016
    $scope.UpdateMaxLoss = function (loss, usecode) {
        
        $http.get('Lstsavemstrcontroller/UpdateMaxLoss/' + loss + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/')
                    .success(function (data, status, headers, config) {
                        $scope.users = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 20;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
               alert(data.message);
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the UpdateMaxLost Manish 30-nov-2016
     //chnage the Max UpdateMaxStake Manish 30-nov-2016
    $scope.UpdateMaxStake = function (stake, usecode) {
        $http.get('Lstsavemstrcontroller/UpdateMaxStake/' + stake + '/' + usecode + '/')
            .success(function (data, status, headers, config) {
                var s = 0;
                $http.get('Lstsavemstrcontroller/')
                    .success(function (data, status, headers, config) {
                        $scope.users = data;
                        $scope.currentPage = 1;
                        $scope.entryLimit = 20;
                        $scope.filteredItems = $scope.users.length;
                        $scope.totalItems = $scope.users.length;
                    });
                alert(data.message);
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data +
                    "<br />status: " + status +
                    "<br />headers: " + jsonFilter(header) +
                    "<br />config: " + jsonFilter(config);
            });
    };
    //End of change the UpdateMaxStake Manish 30-nov-2016



});
 
app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
