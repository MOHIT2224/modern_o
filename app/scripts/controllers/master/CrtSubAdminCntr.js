app.controller("CrtSubAdminCntr", ["$scope", "$http", function(e, r) {
    r.get("Createdealercontroller/getSubadmin").success(function(r, s, a, n) {
        e.subadmin=r.subadmin
    }
    ), e.submitForm=function() {
        var s= {
            name: e.name, username: e.username, password: e.password, type: 4
        }
        ;
        r( {
            method:"POST", url:"Createdealercontroller/SaveSubAdmin/", data:s, headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }
        ).success(function(r) {
            // 0==r.message.error?(e.message=r.message.message, e.subadmin=r.subadmin): e.message="r.message.message"
             0==r.error?(r.message.message , e.subadmin=r.subadmin): e.errorMsg=r.message.message
            e.subadmin=r.subadmin
            e.name=""
            e.username=""
            e.password=""
             
        }
        )
    }
    , e.checkUserName=function() {
        var s=e.username;
        s==angular.isUndefinedOrNull||s.length<4?e.errorMsg="":r( {
            method:"POST", url:"Createmastercontroller/CheckUserName/"+s, data:s, headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }
        ).success(function(r) {
            0==r.error?e.errorMsg=r.message: e.errorMsg=r.message
        }
        )
    }
}

]);
