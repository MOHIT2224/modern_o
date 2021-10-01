app.controller("Delete_old_data", ["$scope", "$http", function(e, r) { 
		 r.get(BASE_URL+"Createdealercontroller/getSubadmin").success(function(r, s, a, n) {
        e.subadmin=r.subadmin
    }
    ),
		e.submitForm=function() {
        var s= {
            name: e.name, type: 1
        }
        ;
        r( {
            method:"POST", url:BASE_URL+"Deletedata/delete_all/", data:s, headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }
        ).success(function(r) {
             0==r.message.error?(e.message=r.message, e.subadmin=r.subadmin): e.message=r.message
			
        }
        )
    }
}

]);
