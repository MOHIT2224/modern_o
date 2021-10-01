app.controller("Serieshracingcontroller", ["$scope", "$http", "$stateParams", "Dialog", "$rootScope", "sessionService", function(e, t, a, s, o, r) {
    e.loading = !0;
    var n = a.sportId;
    e.seriesId = a.seriesId, t.get("Geteventcntr/getSeriesOfSport/" + n + "/" + e.seriesId).success(function(t, a, o, r) {
        t.seriesHorceracing != angular.isUndefinedOrNull ? e.GetSeriesData = t : s.autohide("Record Not Found " + t, 1e4), e.loading = !1
    }), e.saveMatch = function(a, c, i, d) {
        e.loading = !0, void 0 == d && (d = null);
        var p = {
                seriesId: e.seriesId,
                matchId: a,
                matchName: c,
                openDate: i,
                countryCode: d,
                sportId: n,
                HelperID: r.get("HelperID")
            },
            u = BASE_URL + "Geteventcntr/saveSportMatch";
        t.post(u, p).success(function(t) {
            s.autohide(t.message), e.loading = !1, o.$broadcast("changeSidebar_Match", {})
        })
    }
}]);

