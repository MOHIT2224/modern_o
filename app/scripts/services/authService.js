'use strict';
	app.factory('AuthService',function($http,$location){
		return{
				 checkUniqueValue: function(id, property, value) {
							          var data = {
							            id: id,
							            property: property,
							            value: value
							          };
							          return $http.post(BASE_URL+"Createmastercontroller/CheckUserName", data).then( function(res) {
							            //alert("hi");
							            return res.data.error;
							          });
						        }
		}
	});