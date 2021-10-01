'use strict';
app.factory('loginService', function ($http, $location, sessionService, $rootScope,$timeout) {
    var response = [];
    return {
        login: function (user, $scope, $callback) {
            var $promise = $http.post(BASE_URL + 'Loginauthcontroller/chkLoginUser', user);
            $promise.then(function (response) {


                if (response.data.type == 0) {
		   // $rootScope.userType=response.data.type;
                    sessionService.set('HelperName', '0');
                    sessionService.set('Helperype', '4');
                    sessionService.set('HelperID', '0');
                    sessionService.set('user', response.data.user_name);
                    sessionService.set('type', response.data.type);
                    sessionService.set('user_id', response.data.user_id);
                    sessionService.set('slctUseID', response.data.user_id);
                    sessionService.set('slctUseTypeID', response.data.type);
                    sessionService.set('slctUseName', response.data.user_name);
                    sessionService.set('ChangePas', response.data.lgnstatus);
                    sessionService.set('set_timeout', response.data.set_timeout);
                    sessionService.set('last_login_id', response.data.last_login_id);
                    sessionService.set('subAdmin', response.data.subAdmin);
                    sessionService.set('makeBatUrl', response.data.makeBatUrl);

		    sessionService.set('lgPassword', response.data.mstrpassword);
   sessionService.set('last_login_time', response.data.last_login_time);
			   $http.defaults.headers.common['UserName'] = sessionService.get('slctUseName');
    		    $http.defaults.headers.common['Password'] = sessionService.get('lgPassword');
                    $location.path('/dashboard/Home');
                    //window.close();
                    //window.open(window.location, '_self').close();
                    //window.open(BASE_URL + '#/dashboard/Home', '_blank');//its working but old is not close
                }
                else if (response.data.type == 1) {
			// $rootScope.userType=response.data.type;
                    sessionService.set('HelperName', '0');
                    sessionService.set('Helperype', '4');
                    sessionService.set('HelperID', '0');
                    sessionService.set('user', response.data.user_name);
                    sessionService.set('type', response.data.type);
                    sessionService.set('user_id', response.data.user_id);
                    sessionService.set('slctUseID', response.data.user_id);
                    sessionService.set('slctUseTypeID', response.data.type);
                    sessionService.set('slctUseName', response.data.user_name);
                    sessionService.set('lgnstatus', response.data.ChangePas);
                    sessionService.set('lgnstatus', response.data.lgnstatus);
                    sessionService.set('ChangePas', response.data.ChangePas);
                    sessionService.set('set_timeout', response.data.set_timeout);
                    sessionService.set('last_login_id', response.data.last_login_id);
   sessionService.set('last_login_time', response.data.last_login_time);
	   $http.defaults.headers.common['UserName'] = sessionService.get('slctUseName');
    		    $http.defaults.headers.common['Password'] = sessionService.get('lgPassword');
			    sessionService.set('lgPassword', response.data.mstrpassword);
                    $location.path('/masterDashboard/Home');

                } else if (response.data.type == 2) {
		// $rootScope.userType=response.data.type;
                    sessionService.set('HelperName', '0');
                    sessionService.set('Helperype', '4');
                    sessionService.set('HelperID', '0');
                    sessionService.set('user', response.data.user_name);
                    sessionService.set('type', response.data.type);
                    sessionService.set('user_id', response.data.user_id);
                    sessionService.set('slctUseID', response.data.user_id);
                    sessionService.set('slctUseTypeID', response.data.type);
                    sessionService.set('slctUseName', response.data.user_name);
                    sessionService.set('lgnstatus', response.data.lgnstatus);
                    sessionService.set('ChangePas', response.data.ChangePas);
                    sessionService.set('set_timeout', response.data.set_timeout);
                    sessionService.set('last_login_id', response.data.last_login_id);
			   sessionService.set('last_login_time', response.data.last_login_time);
			    sessionService.set('lgPassword', response.data.mstrpassword);
			   $http.defaults.headers.common['UserName'] = sessionService.get('slctUseName');
    		    $http.defaults.headers.common['Password'] = sessionService.get('lgPassword');
                    //$location.path('/dashboard/dealerDashboard');
                    $location.path('/dealerDashboard/Home');
                } else if (response.data.type == 3) {
			 $rootScope.userType=response.data.type;
                    sessionService.set('HelperName', '0');
                    sessionService.set('Helperype', '4');
                    sessionService.set('HelperID', '0');
                    sessionService.set('user', response.data.user_name);
                    sessionService.set('type', response.data.type);
                    sessionService.set('user_id', response.data.user_id);
                    sessionService.set('slctUseID', response.data.user_id);
                    sessionService.set('slctUseTypeID', response.data.type);
                    sessionService.set('slctUseName', response.data.user_name);
                    sessionService.set('lgnstatus', response.data.lgnstatus);
                    sessionService.set('ChangePas', response.data.ChangePas);
                    sessionService.set('set_timeout', response.data.set_timeout);
                    sessionService.set('last_login_id', response.data.last_login_id);
		    sessionService.set('last_login_time', response.data.last_login_time);
		    sessionService.set('config_unmatched', response.data.config_unmatched);
		    sessionService.set('config_max_odd_limit', response.data.config_max_odd_limit);
                    sessionService.set('apkDownloadUrl', response.data.apkDownloadUrl);
                    sessionService.set('isMultiBet',response.data.isMultiBet);

			    sessionService.set('lgPassword', response.data.mstrpassword);
 	   $http.defaults.headers.common['UserName'] = sessionService.get('slctUseName');
    		    $http.defaults.headers.common['Password'] = sessionService.get('lgPassword');
                    //$location.path('/dashboard/userDashboard');
                    $location.path('/userDashboard/Home');
                }
                else if (response.data.type == 4) {
                    sessionService.set('HelperName', response.data.user_name);
                    sessionService.set('Helperype', response.data.type);
                    sessionService.set('HelperID', response.data.user_id);
                    var $promise1 = $http.get('Chipscntrl/getParentById/' + response.data.user_id);
                    $promise1.then(function (response1) {
                        sessionService.set('user', response1.data.parentData[0].mstrname);
                        sessionService.set('type', response1.data.parentData[0].usetype);
                        sessionService.set('user_id', response1.data.parentData[0].mstrid);
                        sessionService.set('slctUseName', response1.data.parentData[0].mstrname);
                        sessionService.set('slctUseTypeID', response1.data.parentData[0].usetype);
                        sessionService.set('slctUseID', response1.data.parentData[0].mstrid);
                        sessionService.set('ChangePas', response.data.ChangePas);
                        sessionService.set('last_login_id', response.data.last_login_id);
			    sessionService.set('lgPassword', response.data.mstrpassword);
	   $http.defaults.headers.common['UserName'] = sessionService.get('slctUseName');
    		    $http.defaults.headers.common['Password'] = sessionService.get('lgPassword');
                    });
                    var $promise2 = $http.get('Createdealercontroller/get_HelperRights/' + response.data.user_id);
                    $promise2.then(function (response2) {

                        if (response2.data.menuList != angular.isUndefinedOrNull && response2.data.menuList.length > 0) {
                            $rootScope.HelperAllRights = response2.data.menuList[0];
                            sessionService.set('HelperAllRights', response2.data.menuList[0]);
                        }
                    });
                    sessionService.set('lgnstatus', response.data.lgnstatus);
                    $location.path('/dashboard/Home');
                }
                else {
                    $scope.message = response.data.message;

			   if($scope.message)
		    $scope.showGreeting = true;
$scope.loading = false;
		    $timeout(function() {
		      $scope.showGreeting = false;
$scope.loading = false;
		    }, 3000);

                    $location.path('/login');
                }
                $callback(response);
            });
        },
        sublogin: function (user, $scope, $callback) {
            var $promise = $http.post(BASE_URL + 'Loginauthcontroller/chkSubLoginUser', user);
            $promise.then(function (response) {

                if(response.data.error==0)
                {
                    sessionService.set('HelperName', '0');
                    sessionService.set('Helperype', '4');
                    sessionService.set('HelperID', '0');
                    sessionService.set('user', response.data.user_name);
                    sessionService.set('type', response.data.type);
                    sessionService.set('user_id', response.data.user_id);
                    sessionService.set('slctUseID', response.data.user_id);
                    sessionService.set('slctUseTypeID', response.data.type);
                    sessionService.set('slctUseName', response.data.user_name);
                    sessionService.set('ChangePas', response.data.lgnstatus);
                    sessionService.set('set_timeout', response.data.set_timeout);
                    sessionService.set('last_login_id', response.data.last_login_id);
                    sessionService.set('subAdmin', response.data.subAdmin);
                    sessionService.set('subAdminId', response.data.subAdminId);
                    sessionService.set('lgPassword', response.data.mstrpassword);
                    sessionService.set('last_login_time', response.data.last_login_time);
                    $http.defaults.headers.common['UserName'] = sessionService.get('slctUseName');
                    $http.defaults.headers.common['Password'] = sessionService.get('lgPassword');
                    $location.path('/dashboard/Home');
                }
                else {
                    $scope.message = response.data.message;

                    if($scope.message)
                        $scope.showGreeting = true;
                    $scope.loading = false;
                    $timeout(function() {
                        $scope.showGreeting = false;
                        $scope.loading = false;
                    }, 3000);

                    $location.path('/subadmin');
                }

                $callback(response);
            })
        },

        logout: function ($callback) {
            var Data = { userId: sessionService.get('user_id'), sessionId: sessionService.get('lgnstatus'), lastLogin: sessionService.get('last_login_id') };

            var $promise = $http.post(BASE_URL + 'Loginauthcontroller/logout/', Data);
            //var $promise = $http({ method: 'POST', url: 'Lstsavemstrcontroller/changeLgnPassword/', Data: Data });
            $promise.then(function (response) {

                var subAdmin= sessionService.get('subAdmin');
                sessionService.destroy('user_id');
                sessionService.destroy('user');

                sessionService.destroy('slctParantID');
                sessionService.destroy('slctUseID');//sourabh 7-oct-2016
                sessionService.destroy('slctUseName');//sourabh 7-oct-2016
                sessionService.destroy('slctUseTypeID');//sourabh 7-oct-2016
                sessionService.destroy('slctUseType');//sourabh 7-oct-2016 
                sessionService.destroy('lgnstatus');
                sessionService.destroy('set_timeout');
                sessionService.destroy('ChangePas');
                sessionService.destroy('last_login_id');

               // window.localStorage.clear();
               // window.location = "" + BASE_URL;

                if(subAdmin!="true")
                {
                    localStorage.clear();
                    sessionService.destroy('type');
                    window.location = "" + BASE_URL;
                }
                else
                {
                    window.location.href = "" + BASE_URL + '#/subadmin';
                    window.location.reload();
                }

            });
                  if(response.data != angular.isUndefinedOrNull)
		{
            $callback(response.data.message);
			}
            //$http.post(BASE_URL + 'Loginauthcontroller/logout/' + sessionService.get('user_id'));

        },
        islogged: function ($scope, $callback) {
            $http({ method: 'POST', url: BASE_URL + 'Loginauthcontroller/is_logged_in', headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }).success(function (response) { $callback(response); });
        },

        chkLoginStatus: function ($callback) {
            var $promise = $http.post(BASE_URL + 'Loginauthcontroller/chkLoginStatus/' + sessionService.get('user_id'));
            $promise.then(function (response) {
                $callback(response);
            });
        }
    }
});
