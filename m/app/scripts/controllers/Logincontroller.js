app.controller("Logincontroller", ["$scope", "$http", function(o, e) {

    o.user, o.submitForm = function() {
        e({
            method: "POST",
            url: BASE_URL+"Loginauthcontroller/chkLoginUser",
            data: o.user,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }).success(function(e) {
			
            1 == e.error ? o.message = e.message : 0 == e.type ? (o.message = e.message, window.location = "#/adminDashboard", location.reload()) : (window.location = "#/userDashboard", location.reload())
        })
    }
}]);
