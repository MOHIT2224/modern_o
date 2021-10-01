'use strict';
app.factory('sessionService',['$http',function($http,$rootScope){
	return {
		set: function(key,value){			
			return localStorage.setItem(key,value);
			//return sessionStorage.setItem(key);		
		},
		get: function(key){

			return localStorage.getItem(key);
			//return sessionStorage.getItem(key);
		},
		destroy: function(key){
			return localStorage.removeItem(key);
			//return sessionStorage.removeItem(key);
		}
	};
}])
