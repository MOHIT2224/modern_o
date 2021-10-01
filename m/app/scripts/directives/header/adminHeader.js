'use strict';
angular.module('ApsilonApp')
	.directive('header',function(){
	    return {
	        templateUrl:'directives/adminHeader',
	       // controller:'Formctrl',
	        restrict: 'E',
	        replace: true,
	        scope: {

	        },
	        controller:function($scope,$http,loginService){
	            $scope.logout=function(){           
	                loginService.logout();
	            };
	        }
	    }
	});
	
