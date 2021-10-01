app.controller("Get_actMtchUsersCntr", ["$scope", "$http", "$filter", "sessionService", "loginService", "$interval","$state","$timeout", function (e, t, s, c, n, r,$state,$timeout) {

    e.activeUserTime=""
    e.tempVar='';
    e.Get_activeUsers=function(s){
        var calltype=1;
        if(calltype == 1){
            e.tempVar=s;
            calltype=2;
        }

        //$scope.loading=true;
        e.activeUserTime=$timeout(function(){
            if($state.current.name=="agentDashboard.Get_actMtchUsersCntr" || $state.current.name=="dealerDashboard.Get_actMtchUsersCntr" || $state.current.name=="masterDashboard.Get_actMtchUsersCntr" || $state.current.name=="dashboard.Get_actMtchUsersCntr")
            {
                t.get("Betentrycntr/ActiveMatchUsers/" + e.tempVar).success(function (t, s, c, n) {
                    e.loading = false;
                    e.activeUsers = t.activeUsers, e.currentPage = 1, e.entryLimit = 50, e.filteredItems = e.activeUsers.length, e.totalItems = e.activeUsers.length;
                    e.Get_activeUsers(e.tempVar);
                });
            }

        },calltype == 1 ? 0 : 7000);

    }
    //e.Get_activeUsers();
    /*
        e.Get_activeUsers = function (s) {
            e.loading = true;
            t.get("Betentrycntr/ActiveMatchUsers/" + s).success(function (t, s, c, n) {
                e.loading = false;
                e.activeUsers = t.activeUsers, e.currentPage = 1, e.entryLimit = 50, e.filteredItems = e.activeUsers.length, e.totalItems = e.activeUsers.length
            }), e.setPage = function (t) {
                e.currentPage = t
            }, e.filter = function () {
                $timeout(function () {
                    e.filteredItems = e.filtered.length
                }, 10)
            }, e.sort_by = function (t) {
                e.predicate = t, e.reverse = !e.reverse
            }, r(function () {
                if($state.current.name == "dashboard.Get_actMtchUsersCntr")
                e.get_onlineusers()
            }, 1e3), e.get_onlineusers = function () {
                t.get("Betentrycntr/ActiveMatchUsers/" + s).success(function (t, s, c, n) {
                    e.activeUsers.length == t.activeUsers.length ? e.activeUsers = t.activeUsers : e.activeUsers = t.activeUsers
                })
            }
        },
    */

    e.loading = true, t.get("Betentrycntr/getActiveMatches/").success(function (t, s, c, n) {
        e.loading = false;
        e.getActiveMatches = t.getActiveMatches
    })
}]), app.filter("startFrom", function () {
    return function (e, t) {
        return e ? (t = +t, e.slice(t)) : []
    }
});


