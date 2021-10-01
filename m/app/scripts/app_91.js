'use strict';
var app = angular.module('ApsilonApp', ['ui.router', 'oc.lazyLoad', 'ui.bootstrap', 'angularTreeview', 'ngMaterial', 'agGrid', 'ng.deviceDetector']);
 app.factory('http', ['$http',
      function(http) {
        return {
          get: function(getUrl) {
            return http.get(getUrl).then(function(response) {
		
              return response;
            }, function() {
		
              //handle errors here
            });
          },
          post: function(postUrl, data) {
              return http.post(postUrl, data).then(function(response) {
                return response;
              }, function() {
                //handle errors here
              });
            }
            // other $http wrappers
        };
      }]);
app.factory('authHttpResponseInterceptor',['$q','$location','$rootScope',function($q,$location,$rootScope){
	
	return {
		response: function(response){
			if (response.status === 412) {
				console.log("Response 412");
			}
			return response || $q.when(response);
		},
		responseError: function(rejection) {
			if (rejection.status === 412) {
				console.log("Response Error 412",rejection);
				localStorage.clear();
				$rootScope.isLogout=true;
				//$location.path('/login').search('returnTo', $location.path());
			}
		    else
			{
			 $rootScope.isLogout=false;
			}
			return $q.reject(rejection);
		}
	}
}])
app.factory('Base64', function () {
    /* jshint ignore:start */
 
    var keyStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
 
    return {
        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
 
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
 
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
 
                output = output +
                    keyStr.charAt(enc1) +
                    keyStr.charAt(enc2) +
                    keyStr.charAt(enc3) +
                    keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
 
            return output;
        },
 
        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
                window.alert("There were invalid base64 characters in the input text.\n" +
                    "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                    "Expect errors in decoding.");
            }
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
            do {
                enc1 = keyStr.indexOf(input.charAt(i++));
                enc2 = keyStr.indexOf(input.charAt(i++));
                enc3 = keyStr.indexOf(input.charAt(i++));
                enc4 = keyStr.indexOf(input.charAt(i++));
 
                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;
 
                output = output + String.fromCharCode(chr1);
 
                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }
 
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
 
            } while (i < input.length);
 
            return output;
        }
    };
});
app.config(['$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider','$httpProvider','$locationProvider',function ($stateProvider, $urlRouterProvider, $ocLazyLoadProvider,$httpProvider,$locationProvider) {
    $ocLazyLoadProvider.config({ debug: false, events: true, });
	$locationProvider.html5Mode(false);
    $urlRouterProvider.otherwise('/');
   /* document.addEventListener( "contextmenu", function(e) {
      //
      console.log(e);
      if (e.which == 3) e.preventDefault();
      e.preventDefault();
    /*  
      if( window.console && (console.firebug || console.table && /firebug/i.test(console.table()))) {
          alert('Firebug is running');
      } else {
          alert('Firebug is not found');
      } 
    });*/
    /*document.addEventListener( "keydown", function(e) {
      
      var button = e.keyCode;
      if ( button === 123 ) {
         e.preventDefault();
      }
      if (e.keyCode == 93) {
          e.preventDefault();
           
            return false;
        }
        else if (e.ctrlKey && e.shiftKey && e.keyCode == 67) e.preventDefault();
        else if (e.ctrlKey && e.shiftKey && e.keyCode == 73) e.preventDefault();
        else if (e.ctrlKey && e.shiftKey && event.keyCode == 74) e.preventDefault();
        else if (e.ctrlKey && e.keyCode == 83) e.preventDefault();
        else if (e.ctrlKey && e.keyCode == 85) e.preventDefault();
      
    });
    window.addEventListener('devtoolschange', function (e) {
      
        console.log('is DevTools open?', e.detail.open);
        alert("console is Open");
    });*/
    /*$(document).mousedown(function (event) {
        if (event.button == 2) {
            alert("you'll need to provide administrator permission to (do this action)");
            return false;
        }
    });
    $(document).keydown(function (event) {
        if (event.keyCode == 93) {
            alert("you'll need to provide administrator permission to (do this action)");
            return false;
        }
        else if (event.keyCode == 123) return false;
        else if (event.ctrlKey && event.shiftKey && event.keyCode == 67) return false;
        else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) return false;
        else if (event.ctrlKey && event.shiftKey && event.keyCode == 74) return false;
        else if (event.ctrlKey && event.keyCode == 83) return false;
        else if (event.ctrlKey && event.keyCode == 85) return false;
        //else if ((event.ctrlKey && event.keyCode == 116) || event.ctrlKey)return false;
    });*/
    $stateProvider
      .state('login', { templateUrl: 'login', url: '/', controller: 'Formctrl' })
      .state('dashboard', {
          url: '/dashboard',
	  abstract:true, 
          templateUrl: 'dashboard/main',
          resolve: {
              loadMyDirectives: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                        'app/scripts/services/get_userser.js',
                        'app/scripts/directives/header/header_0.js?ver='+Math.random(),
                        'app/scripts/directives/header/header-notification/header-notification_1.js?ver='+Math.random(),
                        'app/scripts/directives/sidebar/sidebar_1.js?ver='+Math.random(),
                        'app/scripts/directives/sidebar/sidebar-search/sidebar-search.js?ver='+Math.random()

                      ]
                  }),
                  $ocLazyLoad.load({ name: 'ngResource', files: ['app/js/libs/angular-resource.min.js'] })
                  $ocLazyLoad.load({ name: 'ngSanitize', files: ['app/js/libs/angular-sanitize.min.js'] })
                //  $ocLazyLoad.load({ name: 'ngAnimate', files: ['app/lib/angular-animate.min.js'] })
              }
          }
      })
      .state('dashboard.Home', {
          url: '/Home',
          controller: 'homedashboard',
          templateUrl: 'dashboard/home',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/services/get_userser.js',
                            'app/scripts/directives/dashboard/homedashboard.js?ver='+Math.random(),
                            'app/scripts/controllers/Lstcontroller.js?ver='+Math.random(),
                            'app/dist/agGrid.js?ignore=notused19',
                            'app/scripts/controllers/Main.js',
                            'app/assets/js/contextmenu.js',
                            'app/js/shortcutKey.js',
                           // 'app/lib/angular-animate.min.js'


                      ]
                  })
              }
          }
      })
      // user
      .state('userDashboard', {
          url: '/userDashboard',
          templateUrl: 'dashboard/usermain',
          resolve: {
              loadMyDirectives: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                        'app/scripts/directives/header/userheader.js?ver='+Math.random(),
                        'app/scripts/directives/header/header-notification/header-notification_user.js?ver='+Math.random(),
                        'app/scripts/directives/sidebar/usersidebar.js?ver='+Math.random(),
                        'app/scripts/directives/sidebar/sidebar-search/sidebar-search.js?ver='+Math.random(),
                        'app/scripts/services/get_userser.js',
                        'app/dist/jquery-latest.min.js'
                      ]
                  }),
                  $ocLazyLoad.load({ name: 'ngResource', files: ['app/js/libs/angular-resource.min.js'] })
                  $ocLazyLoad.load({ name: 'ngSanitize', files: ['app/js/libs/angular-sanitize.min.js'] })
                  //$ocLazyLoad.load({ name: 'ngAnimate', files: ['app/lib/angular-animate.min.js'] })

              }
          }
      })
      .state('userDashboard.Home', {
          url: '/Home',
          controller: 'homedashboard',
          templateUrl: 'dashboard/userDashboard',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/directives/dashboard/homedashboard.js?ver=1.5',
                            'app/scripts/controllers/Lstcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('userDashboard.Matchodds', {
          templateUrl: 'app/views/pages/user/UserMatchodds.html?ver='+Math.random(),
          controller: 'Matchoddscntr',
          url: '/matchOdds?MatchId&&MarketId&&matchName&&date&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/user/UserMatchoddscntr.js?ver='+Math.random(),
                                'app/dist/ng-device-detector.js',
                                'app/js/moment.min.js'
                               
                      ]
                  })
              }
          }
      })
      .state('userDashboard.Get_bethistryCntr', {
          templateUrl: 'app/views/pages/user/User_bethistry.html?ver='+Math.random(),
          controller: 'Get_bethistryCntr',
          url: '/Get_bethistryCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/user/User_bethistryCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('userDashboard.Liability', {
          templateUrl: 'app/views/pages/user/User_bethistry.html?ver='+Math.random(),
          controller: 'Get_bethistryCntr',
          url: '/Liability',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/user/User_bethistryCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('userDashboard.changePasswordUser', {
          url: '/changePasswordUser',
          controller: 'Chngpasscontroller',
          templateUrl: 'app/views/pages/changePassword.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Chngpasscontroller.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('userDashboard.anctStatementCntr', {
          templateUrl: 'app/views/pages/user/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })

      .state('userDashboard.myMarketCntr', {
          templateUrl: 'app/views/pages/user/myMarket.html?ver='+Math.random(),
          controller: 'myMarketCntr',
          url: '/myMarketCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                        'app/scripts/controllers/user/myMarketCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('userDashboard.SoccerMatchLst', {
          templateUrl: 'app/views/pages/user/SoccerMatchLst.html?ver='+Math.random(),
          controller: 'SoccerMatchLstCntr',
          url: '/SoccerMatchLst',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/user/SoccerMatchLstCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('userDashboard.TennisMatchLst', {
          templateUrl: 'app/views/pages/user/TenisMatchLst.html?ver='+Math.random(),
          controller: 'TenisMatchLstCntr',
          url: '/TenisMatchLstCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/user/TenisMatchLstCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('userDashboard.CricketMatchLst', {
          templateUrl: 'app/views/pages/user/CricketMatchLst.html?ver='+Math.random(),
          controller: 'CricketMatchLstCntr',
          url: '/CricketMatchLstCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/user/CricketMatchLstCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('userDashboard.HorseMatchLst', {
          templateUrl: 'app/views/pages/user/HorseMatchLst.html?ver='+Math.random(),
          controller: 'HorseMatchLstCntr',
          url: '/HorseMatchLstCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/user/HorseMatchLstCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('userDashboard.Profitlosscntr', {
          templateUrl: 'app/views/pages/user/Proftloss.html?ver='+Math.random(),
          controller: 'Profitlosscntr',
          url: '/Profitlosscntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                        'app/scripts/controllers/user/Profitlosscntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('userDashboard.PnlPlMiSheet', {
          templateUrl: 'app/views/pages/user/Proftlossbybetid.html?ver='+Math.random(),
          params:      {'MarketId': null},
          controller: 'PnlcntrBybId',
          url: '/PnlPlMiSheet',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/user/PnlcntrBybId.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('userDashboard.Chiphistorycntr', {
        templateUrl: 'app/views/pages/user/Chiphistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/Chiphistorycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/user/Chiphistorycntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })
      .state('userDashboard.Term&Condition', {
        templateUrl: 'app/views/pages/Term&Condition.html?ver='+Math.random(),
        url: '/Term&Condition',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              // 'app/dist/ag-grid-enterprise.js',
                              // 'app/scripts/controllers/user/Chiphistorycntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })
     // End user url
      // dealer

      .state('dealerDashboard', {
          url: '/dealerDashboard',
          templateUrl: 'dashboard/dealermain',
          resolve: {
              loadMyDirectives: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                        'app/scripts/services/get_userser.js',
                        'app/scripts/directives/header/dealerheader.js?ver='+Math.random(),
                       'app/scripts/directives/header/header-notification/header-notification_dealer.js?ver='+Math.random()
                        // 'app/scripts/directives/sidebar/usersidebar.js?ver='+Math.random(),
                        // 'app/scripts/directives/sidebar/sidebar-search/sidebar-search.js?ver='+Math.random()
                      ]
                  }),
                  $ocLazyLoad.load({ name: 'ngResource', files: ['app/js/libs/angular-resource.min.js'] })
                  $ocLazyLoad.load({ name: 'ngSanitize', files: ['app/js/libs/angular-sanitize.min.js'] })
                 // $ocLazyLoad.load({ name: 'ngAnimate', files: ['app/lib/angular-animate.min.js'] })

              }
          }
      })
      .state('dealerDashboard.Home', {
          url: '/Home',
          controller: 'dealerdashboard',
          templateUrl: 'dashboard/dealerDashboard',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/services/get_userser.js',
                            'app/scripts/directives/dashboard/dealerdashboard.js?ver='+Math.random(),
                            'app/scripts/controllers/Lstcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.ClientList', {
          url: '/ClientList',
          controller: 'ClientListcntr',
          templateUrl: 'app/views/pages/ClientList.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/controllers/ClientListcntr.js?ver=1.5',  
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.Get_bethistryCntr', {
          templateUrl: 'app/views/pages/user/User_bethistry.html?ver='+Math.random(),
          controller: 'Get_bethistryCntr',
          url: '/Get_bethistryCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                 'app/dist/ag-grid-enterprise.js',
                                'app/scripts/controllers/user/User_bethistryCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.Matchodds', {
          templateUrl: 'app/views/pages/dealer/DealerMatchodds.html?ver='+Math.random(),
          controller: 'Matchoddscntr',
          url: '/matchOdds?MatchId&&MarketId&&matchName&&date',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/dealer/DealerMatchoddscntr.js?ver='+Math.random(),
                               
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.changePasswordUser', {
          url: '/changePasswordUser',
          controller: 'Chngpasscontroller',
          templateUrl: 'app/views/pages/changePassword.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Chngpasscontroller.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('masterDashboard.clientanctStatementCntr', {
          templateUrl: 'app/views/pages/user/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr?userId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/dealer/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.anctStatementCntr', {
          templateUrl: 'app/views/pages/user/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr?userId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/dealer/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.anctStatementCntr1', {
          templateUrl: 'app/views/pages/user/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr1',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.ChipSummaryCntr', {
          url: '/ChipSummary',
          controller: 'ChipSummaryCntr',
          templateUrl: 'app/views/pages/dealer/ChipSummary.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/dealer/ChipSummaryCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dealerDashboard.PnlPlMiSheet', {
        templateUrl: 'app/views/pages/dealer/Proftlossbybetid.html?ver='+Math.random(),
        params:      {'MarketId': null},
        controller: 'PnlcntrBybId',
        url: '/PnlPlMiSheet',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                            'app/scripts/controllers/dealer/PnlcntrBybId.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })
      .state('dealerDashboard.ChiphistorycntrByuId', {
          templateUrl: 'app/views/pages/dealer/ChiphistoryByUser.html?ver='+Math.random(),
          controller: 'ChiphistorycntrByuId',
          url: '/ChiphistorycntrByuId?UserID&&usetype&userName&&parentId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/dealer/ChiphistorycntrByuId.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dealerDashboard.Profitlosscntr', {
        templateUrl: 'app/views/pages/Proftloss.html?ver='+Math.random(),
        controller: 'Profitlosscntr',
        url: '/Profitlosscntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/dealer/Profitlosscntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })
      .state('dealerDashboard.Chiphistorycntr', {
        templateUrl: 'app/views/pages/dealer/Chiphistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/Chiphistorycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/dealer/Chiphistorycntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
      .state('dealerDashboard.ProfitlossByUsercntr', {
        templateUrl: 'app/views/pages/dealer/ProftlossByUser.html?ver='+Math.random(),
        controller: 'ProfitlossByUsercntr',
        url: '/ProfitlossByUsercntr?UserID&&usetype',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/dealer/ProfitlossByUsercntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })
      .state('dealerDashboard.SettlementEntry', {
        templateUrl: 'app/views/pages/dealer/settlementHistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/SettlementEntry',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/dealer/SettlementHistory.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })

      // End dealer Dashboard
      //Start Master Panel New 
      .state('masterDashboard', {
          url: '/masterDashboard',
          templateUrl: 'dashboard/mastermain',
          resolve: {
              loadMyDirectives: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/services/get_userser.js',
                            'app/scripts/directives/header/masterheader.js?ver='+Math.random(),
                            'app/scripts/directives/header/header-notification/header-notification_master.js?ver='+Math.random()
                        // 'app/scripts/directives/sidebar/usersidebar.js?ver='+Math.random(),
                        // 'app/scripts/directives/sidebar/sidebar-search/sidebar-search.js?ver='+Math.random()
                      ]
                  }),
                  $ocLazyLoad.load({ name: 'ngResource', files: ['app/js/libs/angular-resource.min.js'] })
                  $ocLazyLoad.load({ name: 'ngSanitize', files: ['app/js/libs/angular-sanitize.min.js'] })
                 // $ocLazyLoad.load({ name: 'ngAnimate', files: ['app/lib/angular-animate.min.js'] })

              }
          }
      })
      .state('masterDashboard.Home', {
          url: '/Home',
          controller: 'dealerdashboard',
          templateUrl: 'dashboard/masterDashboard',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/services/get_userser.js',
                            'app/scripts/directives/dashboard/dealerdashboard.js?ver='+Math.random(),
                            'app/scripts/controllers/Lstcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.ClientList', {
          url: '/ClientList',
          controller: 'ClientListcntr',
          templateUrl: 'app/views/pages/master/ClientList.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/controllers/master/ClientListcntr.js?ver='+Math.random(),,  
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.userListByDealer', {
          url: '/DealerUserList?DealerId&&TypeId',
          controller: 'userListCntr',
          templateUrl: 'app/views/pages/master/userList.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/controllers/master/userListcntr.js?ver='+Math.random(),,  
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.AddNewDealer', {
          url: '/AddNewDealer',
          controller: 'AddNewDealerCntr',
          templateUrl: 'app/views/pages/master/AddNewDealer.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/controllers/master/AddNewDealerCntr.js?ver=1.5',  
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.Get_bethistryCntr', {
          templateUrl: 'app/views/pages/user/User_bethistry.html?ver='+Math.random(),
          controller: 'Get_bethistryCntr',
          url: '/Get_bethistryCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/user/User_bethistryCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.Matchodds', {
          templateUrl: 'app/views/pages/dealer/DealerMatchodds.html?ver='+Math.random(),
          controller: 'Matchoddscntr',
          url: '/matchOdds?MatchId&&MarketId&&matchName&&date',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/dealer/DealerMatchoddscntr.js?ver='+Math.random(),
                               
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.changePasswordUser', {
          url: '/changePasswordUser',
          controller: 'Chngpasscontroller',
          templateUrl: 'app/views/pages/changePassword.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Chngpasscontroller.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('masterDashboard.anctStatementCntr', {
          templateUrl: 'app/views/pages/user/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr?userId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/master/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.anctStatementCntr1', {
          templateUrl: 'app/views/pages/master/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr1',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.ChipSummaryCntr', {
          url: '/ChipSummary',
          controller: 'ChipSummaryCntr',
          templateUrl: 'app/views/pages/master/ChipSummary.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/master/ChipSummaryCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('masterDashboard.PnlPlMiSheet', {
        templateUrl: 'app/views/pages/master/Proftlossbybetid.html?ver='+Math.random(),
        params:      {'MarketId': null},
        controller: 'PnlcntrBybId',
        url: '/PnlPlMiSheet',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                            'app/scripts/controllers/master/PnlcntrBybId.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
      .state('masterDashboard.ChiphistorycntrByuId', {
          templateUrl: 'app/views/pages/master/ChiphistoryByUser.html?ver='+Math.random(),
          controller: 'ChiphistorycntrByuId',
          url: '/ChiphistorycntrByuId?UserID&&usetype&userName&&parentId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/master/ChiphistorycntrByuId.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('masterDashboard.Profitlosscntr', {
        templateUrl: 'app/views/pages/master/Proftloss.html?ver='+Math.random(),
        controller: 'Profitlosscntr',
        url: '/Profitlosscntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/master/Profitlosscntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('masterDashboard.ProfitlossByUsercntr', {
        templateUrl: 'app/views/pages/master/ProftlossByUser.html?ver='+Math.random(),
        controller: 'ProfitlossByUsercntr',
        url: '/ProfitlossByUsercntr?UserID&&usetype',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/master/ProfitlossByUsercntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })
    .state('masterDashboard.Chiphistorycntr', {
        templateUrl: 'app/views/pages/master/Chiphistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/Chiphistorycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/master/Chiphistorycntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('masterDashboard.Chiphistorycntr_dealer', {
        templateUrl: 'app/views/pages/master/Chiphistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/Chiphistorycntr_dealer?UserID&&usetype',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/master/Chiphistorycntr_dealer.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })

    .state('masterDashboard.SettlementEntry', {
        templateUrl: 'app/views/pages/master/settlementHistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/SettlementEntry',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/master/SettlementHistory.js?ver='+Math.random(),
                    ]
                })
            }
        }
      })

      //End of the Master Panel

   /*   .state('dashboard.Masterdashboard', {
         url: '/masterDashboard',
         controller: 'masterdashboard',
         templateUrl: 'dashboard/masterDashboard',
         resolve: {
             loadMyFiles: function ($ocLazyLoad) {
                 return $ocLazyLoad.load({
                     name: 'ApsilonApp',
                     files: [
                            'app/scripts/directives/dashboard/masterdashboard.js',
                            'app/js/jquery.bxslider.js',
                            'app/js/jquery.bxslider.min.js',
                            'app/styles/jquery.bxslider.css'
                     ]
                 })
             }
         }
     })
     .state('dashboard.Dealerdashboard', {
         url: '/dealerDashboard',
         controller: 'dealerdashboard',
         templateUrl: 'dashboard/dealerDashboard',
         resolve: {
             loadMyFiles: function ($ocLazyLoad) {
                 return $ocLazyLoad.load({
                     name: 'ApsilonApp',
                     files: [
                            'app/scripts/directives/dashboard/dealerdashboard.js',
                            'app/js/jquery.bxslider.js',
                            'app/js/jquery.bxslider.min.js',
                            'app/styles/jquery.bxslider.css'
                     ]
                 })
             }
         }
     })*/
      .state('dashboard.Createmaster', {
          templateUrl: 'app/views/pages/Createmaster.html?ver='+Math.random(),
          controller: 'Crtmstrcontroller',
          url: '/createMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Crtmstrcontroller.js?ver='+Math.random(),
                        'app/scripts/services/authService.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Crtdlercontroller', {
          templateUrl: 'app/views/pages/Createdealer.html?ver='+Math.random(),
          controller: 'Crtdlercontroller',
          url: '/Crtdlercontroller',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Crtdlercontroller.js?ver=1.1',
                             'app/scripts/directives/header/adminheader.js?ver=1.1',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Createuser', {
          templateUrl: 'app/views/pages/Createuser.html?ver='+Math.random(),
          controller: 'Createuser',
          url: '/Createuser',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Createuser.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Sportmst', {
          templateUrl: 'app/views/pages/Sportmst.html?ver='+Math.random(),
          controller: 'Sportmstcontroller',
          url: '/SportMst',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Sportmstcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Matchtypemaster', {
          templateUrl: 'app/views/pages/Matchtypemaster.html?ver='+Math.random(),
          controller: 'Sportstypecontroller',
          url: '/matchTypeMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Sportstypecontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Teammaster', {
          templateUrl: 'app/views/pages/Teammaster.html?ver='+Math.random(),
          controller: 'Teamcontroller',
          url: '/teamMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Teamcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Playermaster', {
          templateUrl: 'app/views/pages/Playermaster.html?ver='+Math.random(),
          controller: 'Playermstcontroller',
          url: '/playerMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Playermstcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Seriesmaster', {
          templateUrl: 'app/views/pages/Seriesmaster.html?ver='+Math.random(),
          controller: 'Seriesmstcontroller',
          url: '/seriesMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Seriesmstcontroller.js?ver=+Math.random()',
                      ]
                  })
              }
          }
      })
      .state('dashboard.ListOfMarket', {
          templateUrl: 'app/views/pages/ListOfMarket.html?ver='+Math.random(),
          controller: 'ListOfMarketCntr',
          url: '/listOfMarket',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/ListOfMarketCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Matchentry', {
          templateUrl: 'app/views/pages/Matchentry.html?ver='+Math.random(),
          controller: 'Matchentrycontroller',
          url: '/matchEntry',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Matchentrycontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Createfancy', {
          templateUrl: 'app/views/pages/Createfancy.html?ver='+Math.random(),
          controller: 'Fancycontroller',
          url: '/createFancy?bar',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/services/get_userser.js',
                          'app/scripts/controllers/Fancycontroller_.js?ver='+Math.random(),
                          'app/js/libs/ui-bootstrap-tpls.min.js?ver='+Math.random(),

                      ]
                  })
              }
          }
      })
      .state('dashboard.CntrAdminSesssionFancy', {
          templateUrl: 'app/views/pages/Adminsessionfancy.html?ver='+Math.random(),
          controller: 'CntrAdminSesssionFancy',
          url: '/CntrAdminSesssionFancy?matchId&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/CntrAdminSesssionFancy.js?ver='+Math.random(),
                               'app/js/libs/ui-bootstrap-tpls.min.js?ver='+Math.random(),
                                'app/js/shortcutKey.js'

                      ]
                  })
              }
          }
      })
      .state('dashboard.Editfancycntr', {
          templateUrl: 'app/views/pages/Editsessfancy.html?ver='+Math.random(),
          controller: 'Editfancycntr',
          url: '/Editfancycntr?FancyID&&TypeID&&MatchName&&SportID&&MatchID',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                           'app/js/shortcutKey.js',
                          'app/scripts/controllers/Editfancycntr.js?ver='+Math.random(),
                               'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.EditMultifancycntr', {
          templateUrl: 'app/views/pages/EditMultisessfancy.html?ver='+Math.random(),
          controller: 'Editfancycntr',
          url: '/EditMultifancycntr?MatchName&&MatchId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/js/shortcutKey.js',
                          'app/scripts/controllers/EditMultifancycntr.js?ver='+Math.random(),
                          'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.EditUpdown', {
          templateUrl: 'app/views/pages/updownFancy.html?ver='+Math.random(),
          controller: 'EditUpdowncntr',
          url: '/EditUpdown?FancyID&&TypeID&&MatchName&&SportID',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/EditUpdowncntr_1.js?ver='+Math.random(),
                          //'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Userlist', {
          templateUrl: 'app/views/pages/Userlist.html?ver='+Math.random(),
          controller: 'Lstcontroller',
          url: '/userList',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Lstcontroller.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Fancyheadmst', {
          templateUrl: 'app/views/pages/Fancyheadmst.html?ver='+Math.random(),
          controller: 'Fancyheadmstcntr',
          url: '/FancyHeadMst',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Fancyheadmstcntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Displayfancy', {
          templateUrl: 'app/views/pages/Displayfancy.html?ver=1.1?ver='+Math.random(),
          controller: 'Displayfancycntr',
          url: '/displayFancy',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/Displayfancycntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Evenoddfancy', {
          templateUrl: 'app/views/pages/Evenoddfancy.html?ver='+Math.random(),
          controller: 'Evenoddfancycntr',
          url: '/evenOddFancy?matchId&&FancyID&&TypeID&&matchName&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/Evenoddfancycntr_.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Sessionfancy', {
          templateUrl: 'app/views/pages/Sessionfancy.html?ver='+Math.random(),
          controller: 'Sessionfancycntr',
          url: '/sessionFancy?matchId&&FancyID&&TypeID&&matchName&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/js/shortcutKey.js',
                              'app/scripts/services/SessionFancy.js',
                              'app/scripts/controllers/Sessionfancycntr_1.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
     .state('dashboard.Khaddalfancy', {
         templateUrl: 'app/views/pages/Khaddalfancy.html?ver='+Math.random(),
         controller: 'Khaddalfancycntr',
         url: '/khaddalFancy?matchId&&FancyID&&TypeID&&matchName&&sportId',
         resolve: {
             loadMyFiles: function ($ocLazyLoad) {
                 return $ocLazyLoad.load({
                     name: 'ApsilonApp',
                     files: [
                             'app/scripts/controllers/Khaddalfancycntr_.js?ver='+Math.random(),
                     ]
                 })
             }
         }
     })
      .state('dashboard.Lastdigit', {
          templateUrl: 'app/views/pages/Lastdigit.html?ver='+Math.random(),
          controller: 'Lastdigitcntr',
          url: '/lastDigit?matchId&&FancyID&&TypeID&&matchName&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/Lastdigitcntr_.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.uploadImg', {
          templateUrl: 'app/views/pages/uploadImg.html?ver='+Math.random(),
          controller: 'Uploadimgcntr',
          url: '/uploadImg',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Uploadimgcntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Updown', {
          templateUrl: 'app/views/pages/Updown.html?ver='+Math.random(),
          url: '/upDown?matchId&&FancyID&&TypeID&&matchName&&sportId',
          controller: 'Updowncntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Updowncntr_.js?ver='+Math.random() ]
                  })
              }
          }

      })
      .state('dashboard.chart', {
          templateUrl: 'chart',
          url: '/chart',
          controller: 'ChartCtrl',
          resolve: {
              loadMyFile: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/dist/chart/d3.min.js',
                          'app/dist/chart/c3.min.js',
                          'app/dist/chart/c3.min.css',
                          'app/dist/chart/c3-angular.min.js'

                      ]
                  }),
                      $ocLazyLoad.load({
                          name: 'ApsilonApp',
                          files: ['app/scripts/controllers/Chartcontoller.js?ver='+Math.random()]
                      })
              }
          }
      })
       .state('dashboard.SeriesActDact', {
           templateUrl: 'app/views/pages/ActDeactSeries.html?ver='+Math.random(),
           controller: 'Seriescontroller',
           url: '/serieslst',
           resolve: {
               loadMyFiles: function ($ocLazyLoad) {
                   return $ocLazyLoad.load({
                       name: 'ApsilonApp',
                       files: ['app/scripts/controllers/Seriescontroller.js?ver='+Math.random(), ]
                   })
               }
           }
       })
     .state('dashboard.Fancylist', {
         templateUrl: 'app/views/pages/Fancylist.html?ver='+Math.random(),
         controller: 'Fancylistcntr',
         url: '/fancyList?matchId',
         resolve: {
             loadMyFiles: function ($ocLazyLoad) {
                 return $ocLazyLoad.load({
                     name: 'ApsilonApp',
                     files: [
                             'app/scripts/controllers/Fancylistcntr.js?ver='+Math.random(),
                     ]
                 })
             }
         }
     })
      .state('dashboard.Matchodds', {
          templateUrl: 'app/views/pages/Matchodds.html?ver='+Math.random(),
          controller: 'Matchoddscntr',
          url: '/matchOdds?MatchId&&MarketId&&matchName&&date',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Matchoddscntr_3.js?ver='+Math.random(),
                                "app/scripts/directives/sidebar/sidebar_1.js?ver="+Math.random(),
                                'app/dist/ng-device-detector.js',
                                'app/js/moment.min.js'
                      ]
                  })
              }
          }
      })
    .state('dashboard.Matchodds170203', {
        templateUrl: 'app/views/pages/Matchodds170203.html?ver='+Math.random(),
        controller: 'Matchoddscntr170203',
        url: '/matchOdds170203?MatchId&&MarketId&&matchName&&date',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                                'app/scripts/controllers/Matchoddscntr170203.js?ver='+Math.random(),
                                "app/scripts/directives/sidebar/sidebar_1.js?ver="+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Matchodds170206_t3', {
        templateUrl: 'app/views/pages/Matchodds170206_t3.html?ver=1.3',
        controller: 'Matchoddscntr170206_t3',
        url: '/matchOdds170206_t3?MatchId&&MarketId&&matchName&&date',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Matchoddscntr170206_t3.js?ver=1.3',
                              "app/scripts/directives/sidebar/sidebar_1.js?ver=1.3",
                    ]
                })
            }
        }
    })
    .state('dashboard.Unmatchedbet', {
        templateUrl: 'app/views/pages/Unmatchedbet.html?ver='+Math.random(),
        controller: 'Unmatchedbetcntr',
        url: '/Unmatchedbetcntr?matchId&&marketId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Unmatchedbetcntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Get_event', {
        templateUrl: 'app/views/pages/Get_eventlst.html?ver='+Math.random(),
        controller: 'Geteventlstcntr',
        url: '/get_event',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Geteventlstcntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Getmatchfrmapi', {
        templateUrl: 'app/views/pages/Getmatchfrmapi.html?ver='+Math.random(),
        controller: 'Matchlstfrmapicntr',
        url: '/Getmatchfrmapi?seriesId&&sportId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp', 
                    files: [
                              'app/scripts/controllers/Matchlstfrmapicntr_.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Getseriesfrmapi', {
        templateUrl: 'app/views/pages/Getseriesfrmapi.html?ver='+Math.random(),
        controller: 'Serieslstfrmapicntr',
        url: '/Getseriesfrmapi?sportId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/Serieslstfrmapicntr_.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.GetseriesApi', {
        templateUrl: 'app/views/pages/GetseriesApi.html?ver='+Math.random(),
        controller: 'GetseriesApicntr',
        url: '/Getseries?sportId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/GetseriesApicntr.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.Gethracingseriesfrmapi', {
        templateUrl: 'app/views/pages/Gethracingseriesfrmapi.html?ver='+Math.random(),
        controller: 'Serieshracingcontroller',
        url: '/Gethracingseriesfrmapi?sportId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/Serieshracingcontroller.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.InplayMatchList', {
        templateUrl: 'app/views/pages/InplayMatchList.html?ver='+Math.random(),
        controller: 'InplayMatchListCntr',
        url: '/InplayMatchListCntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/InplayMatchListCntr.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.CricketMatchLst', {
        templateUrl: 'app/views/pages/CricketMatchLst.html?ver='+Math.random(),
        controller: 'CricketMatchLstCntr',
        url: '/CricketMatchLstCntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/CricketMatchLstCntr.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.SoccerMatchLst', {
        templateUrl: 'app/views/pages/SoccerMatchLst.html?ver='+Math.random(),
        controller: 'SoccerMatchLstCntr',
        url: '/SoccerMatchLst',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/SoccerMatchLstCntr.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.TenisMatchLst', {
        templateUrl: 'app/views/pages/TenisMatchLst.html?ver='+Math.random(),
        controller: 'TenisMatchLstCntr',
        url: '/TenisMatchLstCntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/TenisMatchLstCntr.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.HorseMatchLst', {
        templateUrl: 'app/views/pages/HorseMatchLst.html?ver='+Math.random(),
        controller: 'HorseMatchLstCntr',
        url: '/HorseMatchLstCntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/HorseMatchLstCntr.js?ver='+Math.random(), ]
                })
            }
        }
    })
    .state('dashboard.Getmarketlstapi', {
        templateUrl: 'app/views/pages/Getmarketlstapi.html?ver='+Math.random(),
        controller: 'Marketlstapicntr',
        url: '/getMarketLstApi?MatchId&&sportId&&seriesId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Marketlstapicntr_.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Listmarkettype', {
        templateUrl: 'app/views/pages/Listmarkettype.html?ver='+Math.random(),
        controller: 'Listmarkettypecntr',
        url: '/listMarketType',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Listmarkettypecntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Chiphistorycntr', {
        templateUrl: 'app/views/pages/Chiphistory.html?ver='+Math.random(),
        controller: 'Chiphistorycntr',
        url: '/Chiphistorycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/Chiphistorycntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Chipsummerycntr', {
        templateUrl: 'app/views/pages/Chipsummery.html?ver='+Math.random(),
        controller: 'Chipsummerycntr',
        url: '/Chipsummerycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Chipsummerycntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Onlineusersctrl', {
        templateUrl: 'app/views/pages/Onlineusers.html?ver='+Math.random(),
        controller: 'Onlineusersctrl',
        url: '/Onlineusersctrl',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              //'http://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js',
                              'app/scripts/controllers/Onlineusersctrl.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
    .state('dashboard.Profitlosscntr', {
        templateUrl: 'app/views/pages/Proftloss.html?ver='+Math.random(),
        controller: 'Profitlosscntr',
        url: '/Profitlosscntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Profitlosscntr.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })
      .state('dashboard.match_result', {
          templateUrl: 'app/views/pages/match_result.html?ver='+Math.random(),
          controller: 'Matchresultcntr',
          url: '/match_result',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Matchresultcntr_1.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Get_actMtchUsersCntr', {
          templateUrl: 'app/views/pages/getActiveMatchUsers.html?ver='+Math.random(),
          controller: 'Get_actMtchUsersCntr',
          url: '/Get_actMtchUsersCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Get_actMtchUsersCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.Get_bethistryCntr', {
          templateUrl: 'app/views/pages/Get_bethistry.html?ver='+Math.random(),
          controller: 'Get_bethistryCntr',
          url: '/Get_bethistryCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Get_bethistryCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.userRightsCntr', {
          templateUrl: 'app/views/pages/userRights.html?ver='+Math.random(),
          controller: 'userRightsCntr',
          url: '/userRightsCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/userRightsCntr.js?ver='+Math.random(),

                      ]
                  })
              }
          }
      })
      .state('dashboard.SetAdminLimitCntr', {
          templateUrl: 'app/views/pages/SetAdminLimit.html?ver='+Math.random(),
          controller: 'SetAdminLimitCntr',
          url: '/SetAdminLimitCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/SetAdminLimitCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.CrtSubAdminCntr', {
          templateUrl: 'app/views/pages/CrtSubAdmin.html?ver='+Math.random(),
          controller: 'CrtSubAdminCntr',
          url: '/CrtSubAdminCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/CrtSubAdminCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
    .state('dashboard.Delete_old_data', {
          templateUrl: 'app/views/pages/delete_old_data.html?ver='+Math.random(),
          controller: 'Delete_old_data',
          url: '/Delete_old_data',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Delete_old_data.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
     .state('dashboard.anctStatementCntr', {
          templateUrl: 'app/views/pages/anctStatement.html?ver='+Math.random(),
          controller: 'anctStatementCntr',
          url: '/anctStatementCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/anctStatementCntr.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.SubAdminAssignCntr', {
          templateUrl: 'app/views/pages/subadminAssign.html?ver='+Math.random(),
          controller: 'SubAdminAssignCntr',
          url: '/SubAdminAssignCntr?userId&&userName',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/SubAdminAssignCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dashboard.Userdashboard', {
          url: '/userDashboard',
          controller: 'userdashboard',
          templateUrl: 'dashboard/userDashboard',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                            'app/scripts/directives/dashboard/userdashboard.js?ver=1.4',
                            'app/js/jquery.bxslider.js',
                            'app/js/jquery.bxslider.min.js',
                            'app/styles/jquery.bxslider.css'
                      ]
                  })
              }
          }
      })
      .state('dashboard.changePasswordUser', {
          url: '/changePasswordUser',
          controller: 'Chngpasscontroller',
          templateUrl: 'app/views/pages/changePassword.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Chngpasscontroller.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dashboard.matchResult', {
          url: '/matchResult',
          controller: 'matchResult',
          templateUrl: 'dashboard/matchResult',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/directives/dashboard/matchResult.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dashboard.MatchBetList', {
          templateUrl: 'app/views/pages/MatchBetList.html?ver='+Math.random(),
          controller: 'MatchBetListCntr',
          url: '/MatchBetList?MatchId&MarketId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/MatchBetListCntr.js?ver='+Math.random(),
                          'app/js/libs/ui-bootstrap-tpls.min.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
      .state('dashboard.myAccountCntr', {
          url: '/myAccountCntr',
          controller: 'myAccountCntr',
          templateUrl: 'app/views/pages/myAccount.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/myAccountCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dashboard.closeUserListCntr', {
          url: '/closeUserListCntr',
          controller: 'closeUserListCntr',
          templateUrl: 'app/views/pages/closeUserList.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/closeUserListCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dashboard.newChipHistory', {
          url: '/newChipHistory',
          controller: 'NewChipHistoryCntr',
          templateUrl: 'app/views/pages/Bethistory.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/NewChipHistoryCntr.js?ver='+Math.random(), ]
                  })
              }
          }
      })
      .state('dashboard.DelChipLst', {
          url: '/DelChipLst',
          controller: 'Delchipcntr',
          templateUrl: 'app/views/pages/Delchiplist.html?ver='+Math.random(),
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Delchipcntr.js?ver='+Math.random()]
                  })
              }
          }
      })
      .state('dashboard.ChiphistorycntrByuId', {
          templateUrl: 'app/views/pages/ChiphistoryByUser.html?ver='+Math.random(),
          controller: 'ChiphistorycntrByuId',
          url: '/ChiphistorycntrByuId?UserID&&usetype&userName&&parentId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/ChiphistorycntrByuId.js?ver='+Math.random(),
                      ]
                  })
              }
          }
      })
    .state('dashboard.PnlPlMiSheet', {
        templateUrl: 'app/views/pages/Proftlossbybetid.html?ver='+Math.random(),
        params:      {'MarketId': null},
        controller: 'PnlcntrBybId',
        url: '/PnlPlMiSheet',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                            'app/scripts/controllers/PnlcntrBybId.js?ver='+Math.random(),
                    ]
                })
            }
        }
    })

}
]);
/*app.run(function ($rootScope, $timeout, $document, $location, loginService, $mdDialog) {
    var TimeOutTimerValue = 600000000;
    var TimeOut_Thread = $timeout(function () { LogoutByTimer() }, TimeOutTimerValue);
    var bodyElement = angular.element($document);

    angular.forEach(['keydown', 'keyup', 'click', 'mousemove', 'DOMMouseScroll', 'mousewheel', 'mousedown', 'touchstart', 'touchmove', 'scroll', 'focus'],
    function (EventName) { bodyElement.bind(EventName, function (e) { TimeOut_Resetter(e) }); });

    function LogoutByTimer() {
        $location.path('/login');
        alert("session TimeOut Every 30 Minutes");
        loginService.logout();
    }
    function TimeOut_Resetter(e) {
        $timeout.cancel(TimeOut_Thread);
        TimeOut_Thread = $timeout(function () { if ($location.$$path == '/login') { LogoutByTimer() } }, TimeOutTimerValue);
    }
});*/
app.controller('SwitchDemoCtrl', function ($scope) {
    $scope.data = { cb1: true, cb4: true, cb5: false };
    $scope.message = 'false';
    $scope.onChange = function (cbState) { $scope.message = cbState; };
});
/*app.run(function ($rootScope, $templateCache, sessionService, loginService, $location) {
    $rootScope.HelperAllRights = sessionService.get('HelperAllRights');
    $rootScope.$on('$viewContentLoaded', function () {
        if (sessionService.get('user_id') == null) { $location.path('/login'); }
        $templateCache.removeAll();
    });
});*/
/*for Back Button*/
/*end of Back Button*/
app.run(function (loginService, sessionService,$location) {
  //
var url = $location.absUrl();
    if(url.indexOf('www') > -1){
       var temp=url.replace('www.', "" )
       window.location.href=temp;
    }
  
  if (sessionService.get('type') == "5") {//only for user type 3 //5 is not a type it is only temp
    var worker = function () {
        loginService.chkLoginStatus(function (response) {
            if (response.data.status[0] != angular.isUndefinedOrNull) {
              
                if (response.data.status[0].loginstatus == 'undefined') { }
                else {
                    if ((response.data.status[0].loginstatus == sessionService.get('lgnstatus') || response.data.status[0].loginstatus == null || sessionService.get('type') == '0' || sessionService.get('type') == '1' || sessionService.get('type') == '2') && ((response.data.status[0].lgnusrCloseAc == 1) && (response.data.status[0].mstrlock == 1))) {
                        if (response.data.status[0].loginstatus == null) { }
                    }
                    else {
                        loginService.logout(function (response) {
                            console.log("logout");
                           // localStorage.clear();
                        });
                    }
                }
            }
        })
    }
    worker();
  // setInterval(worker, 2000);
  }
});
app.directive('tooltip', function ($document, $compile) {
    return {
        restrict: 'A',
        scope: true,
        link: function ($scope, element, attrs) {
            var tip = $compile('<div ng-class="tipClass">{{ text }}<div class="tooltip-arrow"></div></div>')($scope),
                tipClassName = 'tooltip',
                tipActiveClassName = 'tooltip-show';
            $scope.tipClass = [tipClassName];
            $scope.text = attrs.tooltip;
            if (attrs.tooltipPosition) {
                $scope.tipClass.push('tooltip-' + attrs.tooltipPosition);
            }
            else {
                $scope.tipClass.push('tooltip-down');
            }
            $document.find('body').append(tip);
            element.bind('mouseover', function (e) {
                tip.addClass(tipActiveClassName);
                var pos = e.target.getBoundingClientRect(),
                    offset = $(tip).offset(),
                    tipHeight = $(tip).outerHeight(),
                    tipWidth = $(tip).outerWidth(),
                    elWidth = pos.width || pos.right - pos.left,
                    elHeight = pos.height || pos.bottom - pos.top,
                    tipOffset = 10;
                if (tip.hasClass('tooltip-right')) {
                    offset.top = pos.top - (tipHeight / 2) + (elHeight / 2);
                    offset.left = pos.right + tipOffset;
                }
                else if (tip.hasClass('tooltip-left')) {
                    offset.top = pos.top - (tipHeight / 2) + (elHeight / 2);
                    offset.left = pos.left - tipWidth - tipOffset;
                }
                else if (tip.hasClass('tooltip-down')) {
                    offset.top = pos.top + elHeight + tipOffset;
                    offset.left = pos.left - (tipWidth / 2) + (elWidth / 2);
                }
                else {
                    offset.top = pos.top - tipHeight - tipOffset;
                    offset.left = pos.left - (tipWidth / 2) + (elWidth / 2);
                }
                $(tip).offset(offset);
            });
            element.bind('mouseout', function () {
                tip.removeClass(tipActiveClassName);
            });
            tip.bind('mouseover', function () {
                tip.addClass(tipActiveClassName);
            });
            tip.bind('mouseout', function () {
                tip.removeClass(tipActiveClassName);
            });
        }
    }
});
app.factory('Dialog', ['$mdDialog', '$timeout', function ($mdDialog, $timeout) {
    return {
        autohide: function (message, timeout_time) {
            $mdDialog.show({
                clickOutsideToClose: false, escapeToClose: false,
                template: "<md-dialog style='max-width:100%;'><md-toolbar style='padding:20px 40px;background-color:#DFF0D8;color:#fff;'><div class='md-toolbar-tools'><h2 style='font-weight:700;font-size:19px;'> " + message + "  </h2></div></md-toolbar> <md-dialog-actions><md-button type='button' style='background-color:#367fa9;color:#fff;font-weight:bold' class='md-primary md-confirm-button' ng-click='hide()'>Close</md-button> </md-dialog-actions></md-dialog>",
                fullscreen: false, locals: { timeout_time: timeout_time },
                controller: function DialogController($mdDialog, $timeout,$scope, timeout_time) {
                    if (timeout_time == angular.isUndefinedOrNull) timeout_time = 5000;
                    $timeout(callAtTimeout1, timeout_time);
                    function callAtTimeout1() { $mdDialog.hide(); }
		    $scope.hide=function(){$mdDialog.hide();}
                }
            });
        },
        show: function (title, message, event) {
            $mdDialog.show(
          $mdDialog.alert()
                .title(title)
          .content(message)
          .ariaLabel('')
          .ok('Got it!')
          .targetEvent(event)
        );
        },
        Error: function (title, message, event) {
            $mdDialog.show(
          $mdDialog.alert()
                .title(title)
          .content(message)
          .ariaLabel('')
          .ok('Got it!')
          .targetEvent(event)
        );
        },
        SessionExpried: function (message, event) {
            $mdDialog.show(
          $mdDialog.alert()
                .title('Session Expired')
          .content(message)
          .ariaLabel('')
          .ok('Got it!')
          .targetEvent(event)
        );
        }
    }
}]);
app.run(function ($rootScope, sessionService,$location,$state,$templateCache,$http) {
    $rootScope.logo='app/images/logo.png';
    $rootScope.ProjectTitle = 'Br-betting';
    $rootScope.user = sessionService.get('user');
    $rootScope.Balance = sessionService.get('Balance');
    $rootScope.Liability = sessionService.get('Liability');
        $rootScope.$on('$stateChangeSuccess', 
function(event, toState, toParams, fromState, fromParams){ 
	 // event.preventDefault(); 
    $http({ method: 'GET', url: BASE_URL + 'Loginauthcontroller/is_logged_in_check'}).success(function (response) { 
	 if(response.is_login)
	 {
		var tempUrl=$location.absUrl();
   		//$rootScope.user =  response.data.user_name;
		 sessionService.set('type', response.data.type);
	 	 // sessionService.set('slctUseName',response.data.user_name);
	 	 // sessionService.set('user_id',response.data.user_id);

	 	  if (response.data.type == 0) {
	 		//sessionService.set('slctUseID', response.data.user_id);
       sessionService.set('slctUseTypeID', response.data.type); 
     //  sessionService.set('user', response.data.user_name);
			sessionService.set('Helperype', '4');
       sessionService.set('HelperID', '0');
			  if(tempUrl.indexOf('dashboard') == -1){
      				$location.path('/dashboard/Home');
   			 }
	 			
	 	  }
	 	else if(response.data.type == 1) { 
    		sessionService.set('Helperype', '4');
         sessionService.set('HelperID', '0');
   			sessionService.set('slctUseID', response.data.user_id);
   			sessionService.set('slctUseTypeID', response.data.type);  
         sessionService.set('user', response.data.user_name);
   			
  if(tempUrl.indexOf('masterDashboard') == -1){
      				$location.path('/masterDashboard/Home');
   			 }
}
	 	else if (response.data.type == 2) {  
            sessionService.set('Helperype', '4');
           sessionService.set('HelperID', '0');
           sessionService.set('slctUseID', response.data.user_id);
         	sessionService.set('slctUseTypeID', response.data.type);  
           sessionService.set('user', response.data.user_name);
	   if(tempUrl.indexOf('dealerDashboard') == -1){
      			
				 $location.path('/dealerDashboard/Home');
				
   			 }
        }
	 	else if (response.data.type == 3) {  
          sessionService.set('Helperype', '4');
         sessionService.set('HelperID', '0');
         sessionService.set('slctUseID', response.data.user_id);
         	sessionService.set('slctUseTypeID', response.data.type);  
           sessionService.set('user', response.data.user_name);
 if(tempUrl.indexOf('userDashboard') == -1){
      				$location.path('/userDashboard/Home');
   			 }
        }
	 	else if (response.data.type == 4) {
    
      sessionService.set('slctUseID', response.data.user_id);
                    	
             sessionService.set('slctUseTypeID', response.data.type);  
             sessionService.set('user', response.data.user_name); 
            if(tempUrl.indexOf('dashboard') == -1){
      				$location.path('/dashboard/Home');
   			 }
}
	 	else{ $location.path('/login');}
	
	 }
	
	 });

})
    if(localStorage.length == 0){
      $location.path('/login');
    }
 //    $http({ method: 'GET', url: BASE_URL + 'Loginauthcontroller/is_logged_in_check'}).success(function (response) { 
	// if(response.is_login)
	// {
 //   		$rootScope.user =  response.data.user_name;
	// 	 sessionService.set('type', response.data.type);
	// 	  sessionService.set('slctUseName',response.data.user_name);
	// 	  sessionService.set('user_id',response.data.user_id);

	// 	  if (response.data.type == 0) {
	// 		sessionService.set('slctUseID', response.data.user_id);
 //      sessionService.set('slctUseTypeID', response.data.type); 
 //      sessionService.set('user', response.data.user_name);
	// 		sessionService.set('Helperype', '4');
 //      sessionService.set('HelperID', '0');
	// 		$location.path('/dashboard/Home');	
	// 	  }
	// 	else if(response.data.type == 1) { 
 //    		sessionService.set('Helperype', '4');
 //        sessionService.set('HelperID', '0');
 //  			sessionService.set('slctUseID', response.data.user_id);
 //  			sessionService.set('slctUseTypeID', response.data.type);  
 //        sessionService.set('user', response.data.user_name);
 //  			$location.path('/masterDashboard/Home');}
	// 	else if (response.data.type == 2) {  
 //           sessionService.set('Helperype', '4');
 //          sessionService.set('HelperID', '0');
 //          sessionService.set('slctUseID', response.data.user_id);
 //        	sessionService.set('slctUseTypeID', response.data.type);  
 //          sessionService.set('user', response.data.user_name);
 //          $location.path('/dealerDashboard/Home');}
	// 	else if (response.data.type == 3) {  
 //         sessionService.set('Helperype', '4');
 //        sessionService.set('HelperID', '0');
 //        sessionService.set('slctUseID', response.data.user_id);
 //        	sessionService.set('slctUseTypeID', response.data.type);  
 //          sessionService.set('user', response.data.user_name);
 //           $location.path('/userDashboard/Home');}
	// 	else if (response.data.type == 4) {
     
 //      sessionService.set('slctUseID', response.data.user_id);
                    	
 //            sessionService.set('slctUseTypeID', response.data.type);  
 //            sessionService.set('user', response.data.user_name); 
 //            $location.path('/userDashboard/Home');}
	// 	else{ $location.path('/login');}
		
	// }
	
	// });

    //$rootScope.$on('$stateChangeSuccess', function() {
      //If you don't wanna create the service, you can directly write
      // your function here.
 
   // });

});
agGrid.initialiseAgGridWithAngular1(angular);

app.factory('speech', function () {
    if (window.speechSynthesis) { 
  var msg = new SpeechSynthesisUtterance(); 
  }
    function getVoices() { 
  window.speechSynthesis.getVoices(); 
  return " "; 
  }
    function sayIt(text) { 
  var voices = getVoices(); 
  var config = { voiceIndex: 0, rate: 0, pitch: 0, volume: 0
  }; 
  msg.voice = config && config.voiceIndex ? voices[config.voiceIndex] : voices[0]; msg.text = text; speechSynthesis.speak(msg); }
    return {  };
});



app.factory('focus', function ($timeout, $window) { return function (id) { $timeout(function () { var element = $window.document.getElementById(id); if (element) element.focus(); }); }; });
