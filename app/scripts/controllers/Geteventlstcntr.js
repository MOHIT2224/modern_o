app.controller("Geteventlstcntr", ["$scope", "$http", function(e, t) {
    t.get("Geteventcntr/testapi").success(function(t, s, n, r) {
        e.events = t
    }).error(function(t, s, n, r) {
        e.ResponseDetails = "Data: " + t + "<br />status: " + s + "<br />headers: " + jsonFilter(n) + "<br />config: " + jsonFilter(r)
    }), e.savesport = function(s, n, r) {
        var o = {
                id: s,
                name: n,
                marketCount: r
            },
            a = BASE_URL + "Geteventcntr/saveSport";
        t.post(a, o).success(function(t) {
            e.message = t.message, alert(e.message)
        })
    }
}]);