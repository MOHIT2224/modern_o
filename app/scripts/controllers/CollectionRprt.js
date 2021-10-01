app.controller('CollectionRprt', ['$scope', '$http', '$timeout', '$log', '$mdDialog','Base64','sessionService','$stateParams', function ($scope, $http, $timeout, $log, $mdDialog,Base64,sessionService,stateParams)
{

$scope.user_id=sessionService.get('user_id');
$scope.search={}
$scope.search.active='';
var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
    $scope.loading = true;
    $scope.ismeridian = false;
    $scope.toggleMode = function () {
        $scope.ismeridian = !$scope.ismeridian;
    };

	

	$scope.GetCollectionReport = function(){
		        $scope.loading = true;
		 $http.get(BASE_URL+'Geteventcntr/GetCollectionReport/' + $scope.user_id)
		    .success(function (data, status, headers, config) {
		        $scope.clear_data = data.CollectionReport.clear;
		        $scope.paidTo_data = data.CollectionReport.paidTo;
		        $scope.receivingFrom_data = data.CollectionReport.receivingFrom;
		        $scope.totalPaidTo = data.CollectionReport.totalPaidTo;
		        $scope.totalReceivingFrom = data.CollectionReport.totalReceivingFrom;
		        $scope.currentPage = 1;
		        $scope.entryLimit = 20;
		        $scope.loading = false;
		    });
	
	}

$scope.GetCollectionReport();
$scope.GetPreviousPage = function(){
window.history.back();

}

 $scope.export = function(){
        html2canvas(document.getElementById('exportthis'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 400,

                    }]
                };
                pdfMake.createPdf(docDefinition).download("collection_report.pdf");
            }
        });
     }

	$scope.fetchMatcheDetails = function(matchId){
		        $scope.loading = true;
		 $http.get(BASE_URL+'Geteventcntr/GetMatchStatementDetail/' + $scope.user_id + '/' + matchId)
		    .success(function (data, status, headers, config) {
		        $scope.marchDetails_data = data.matchStatement.matchData;
		        $scope.matchName = data.matchStatement.matchName;
		        $scope.matchDate = data.matchStatement.matchDate;
		        $scope.	totalNetAmout = data.matchStatement.totalNetAmout;
		        $scope.totalreceivePDC = data.matchStatement.totalReceivedPDC;
		        $scope.totalMatchAmout = data.matchStatement.totalMatchAmout;
		        $scope.totalSessionAmount = data.matchStatement.totalSessionAmount;
      			  $scope.totalAmount = data.matchStatement.totalAmount;
		        $scope.totalMatchComm = data.matchStatement.totalMatchComm;
		        $scope.totalSessionComm = data.matchStatement.totalSessionComm;
   			$scope.totalComm = data.matchStatement.totalComm;
   			$scope.totalFinalAmount = data.matchStatement.totalFinalAmount;
		        $scope.currentPage = 1;
		        $scope.entryLimit = 20;
		        $scope.loading = false;
		    });
	
	}
if(stateParams.matchId != angular.isUndefinedorNull)
$scope.fetchMatcheDetails(stateParams.matchId);

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

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
              '<div class="modal-content">' +
                '<div class="modal-header">' +
                  '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                  '<h4 class="modal-title">{{fancyHeaderName}} Fancy</h4>' +
                '</div>' +
                '<div class="modal-body" ng-transclude></div>' +
              '</div>' +
            '</div>' +
          '</div>',
        restrict: 'E',
        transclude: true,
        replace: true,
        scope: true,
        link: function postLink(scope, element, attrs) {
            scope.$watch(attrs.visible, function (value) {
                if (value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
            });
            $(element).on('shown.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = true;
                });
            });
            $(element).on('hidden.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
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
