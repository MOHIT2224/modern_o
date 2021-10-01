'use strict';
app.factory('SessionFancy', function ($http) {
    var response = [];
    return {
        GetSessionFancyData: function (matchId, FancyID, UserId, type, TypeID, $callback) {
            var $promise = $http.get(BASE_URL + 'Sessioncntr/getFancyData/' + matchId + '/' + FancyID + '/' + UserId + '/' + type + '/' + TypeID);
            $promise.then(function (response) {
                $callback(response);
            });
        }
    }
});