'use strict';
var app = angular.module('ApsilonApp', ['ui.router', 'oc.lazyLoad', 'ui.bootstrap', 'ngSlimScroll', 'angularTreeview', 'ngMaterial', 'agGrid', 'ng.deviceDetector', 'ngSanitize', 'gridshore.c3js.chart']);
app.config(['$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ($stateProvider, $urlRouterProvider, $ocLazyLoadProvider, $window) {
    $ocLazyLoadProvider.config({ debug: false, events: true, });
    $urlRouterProvider.otherwise('/login');

    $(document).mousedown(function (event) {
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
    });
    $stateProvider
      .state('login', { templateUrl: 'login', url: '/login', controller: 'Formctrl' })
      .state('dashboard', {
          url: '/dashboard',
          templateUrl: 'dashboard/main',
          resolve: {
              loadMyDirectives: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                        'app/scripts/directives/header/header_.js',
                        'app/scripts/directives/header/header-notification/header-notification.js',
                        'app/scripts/directives/sidebar/sidebar_0.js',
                        'app/scripts/directives/sidebar/sidebar-search/sidebar-search.js'
                      ]
                  }),
                  $ocLazyLoad.load({ name: 'ngResource', files: ['app/js/libs/angular-resource.min.js'] })
                  $ocLazyLoad.load({ name: 'ngSanitize', files: ['app/js/libs/angular-sanitize.min.js'] })
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
                            'app/scripts/directives/dashboard/homedashboard.js',
                            'app/scripts/controllers/Lstcontroller.js',
                            'app/dist/agGrid.js?ignore=notused19',
                            'app/scripts/controllers/Main.js',
                            'app/assets/js/contextmenu.js',
                            'app/js/jquery.bxslider.js',
                            'app/js/jquery.bxslider.min.js',
                            'app/styles/jquery.bxslider.css'
                      ]
                  })
              }
          }
      })
     .state('dashboard.Masterdashboard', {
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
     })
      .state('dashboard.Createmaster', {
          templateUrl: 'app/views/pages/Createmaster.html',
          controller: 'Crtmstrcontroller',
          url: '/createMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Crtmstrcontroller.js',
                        'app/scripts/services/authService.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Crtdlercontroller', {
          templateUrl: 'app/views/pages/Createdealer.html',
          controller: 'Crtdlercontroller',
          url: '/Crtdlercontroller',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Crtdlercontroller.js',
                             'app/scripts/directives/header/adminheader_.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Createuser', {
          templateUrl: 'app/views/pages/Createuser.html',
          controller: 'Createuser',
          url: '/Createuser',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Createuser.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Sportmst', {
          templateUrl: 'app/views/pages/Sportmst.html',
          controller: 'Sportmstcontroller',
          url: '/SportMst',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Sportmstcontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Matchtypemaster', {
          templateUrl: 'app/views/pages/Matchtypemaster.html',
          controller: 'Sportstypecontroller',
          url: '/matchTypeMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Sportstypecontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Teammaster', {
          templateUrl: 'app/views/pages/Teammaster.html',
          controller: 'Teamcontroller',
          url: '/teamMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Teamcontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Playermaster', {
          templateUrl: 'app/views/pages/Playermaster.html',
          controller: 'Playermstcontroller',
          url: '/playerMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Playermstcontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Seriesmaster', {
          templateUrl: 'app/views/pages/Seriesmaster.html',
          controller: 'Seriesmstcontroller',
          url: '/seriesMaster',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Seriesmstcontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Matchentry', {
          templateUrl: 'app/views/pages/Matchentry.html',
          controller: 'Matchentrycontroller',
          url: '/matchEntry',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Matchentrycontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Createfancy', {
          templateUrl: 'app/views/pages/Createfancy.html',
          controller: 'Fancycontroller',
          url: '/createFancy?bar',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Fancycontroller.js',
                               'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.CntrAdminSesssionFancy', {
          templateUrl: 'app/views/pages/Adminsessionfancy.html',
          controller: 'CntrAdminSesssionFancy',
          url: '/CntrAdminSesssionFancy?matchId&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/CntrAdminSesssionFancy.js',
                               'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Editfancycntr', {
          templateUrl: 'app/views/pages/Editsessfancy.html',
          controller: 'Editfancycntr',
          url: '/Editfancycntr?FancyID&&TypeID&&MatchName&&SportID',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Editfancycntr.js',
                               'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.EditUpdown', {
          templateUrl: 'app/views/pages/updownFancy.html',
          controller: 'EditUpdowncntr',
          url: '/EditUpdown?FancyID&&TypeID&&MatchName&&SportID',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/EditUpdowncntr.js',
                          'app/js/libs/ui-bootstrap-tpls.min.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Userlist', {
          templateUrl: 'app/views/pages/Userlist.html',
          controller: 'Lstcontroller',
          url: '/userList',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Lstcontroller.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Fancyheadmst', {
          templateUrl: 'app/views/pages/Fancyheadmst.html',
          controller: 'Fancyheadmstcntr',
          url: '/FancyHeadMst',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Fancyheadmstcntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Displayfancy', {
          templateUrl: 'app/views/pages/Displayfancy.html',
          controller: 'Displayfancycntr',
          url: '/displayFancy',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/Displayfancycntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Evenoddfancy', {
          templateUrl: 'app/views/pages/Evenoddfancy.html',
          controller: 'Evenoddfancycntr',
          url: '/evenOddFancy?matchId&&FancyID&&TypeID&&matchName&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/Evenoddfancycntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Sessionfancy', {
          templateUrl: 'app/views/pages/Sessionfancy.html',
          controller: 'Sessionfancycntr',
          url: '/sessionFancy?matchId&&FancyID&&TypeID&&matchName&&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              //'app/js/responsivevoice.js',
                              'app/scripts/controllers/Sessionfancycntr.js',
                      ]
                  })
              }
          }
      })
     .state('dashboard.Khaddalfancy', {
         templateUrl: 'app/views/pages/Khaddalfancy.html',
         controller: 'Khaddalfancycntr',
         url: '/khaddalFancy?matchId&&FancyID&&TypeID&&matchName&&sportId',
         resolve: {
             loadMyFiles: function ($ocLazyLoad) {
                 return $ocLazyLoad.load({
                     name: 'ApsilonApp',
                     files: [
                             'app/scripts/controllers/Khaddalfancycntr.js',
                     ]
                 })
             }
         }
     })
      .state('dashboard.Lastdigit', {
          templateUrl: 'app/views/pages/Lastdigit.html',
          controller: 'Lastdigitcntr',
          url: '/lastDigit?matchId&&FancyID&&TypeID&&matchName&sportId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/scripts/controllers/Lastdigitcntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.uploadImg', {
          templateUrl: 'app/views/pages/uploadImg.html',
          controller: 'Uploadimgcntr',
          url: '/uploadImg',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                          'app/scripts/controllers/Uploadimgcntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Updown', {
          templateUrl: 'app/views/pages/Updown.html',
          url: '/upDown?matchId&&FancyID&&TypeID&&matchName&&sportId',
          controller: 'Updowncntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Updowncntr.js']
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
                          files: ['app/scripts/controllers/Chartcontoller.js']
                      })
              }
          }
      })
       .state('dashboard.SeriesActDact', {
           templateUrl: 'app/views/pages/ActDeactSeries.html',
           controller: 'Seriescontroller',
           url: '/serieslst',
           resolve: {
               loadMyFiles: function ($ocLazyLoad) {
                   return $ocLazyLoad.load({
                       name: 'ApsilonApp',
                       files: ['app/scripts/controllers/Seriescontroller.js', ]
                   })
               }
           }
       })
     .state('dashboard.Fancylist', {
         templateUrl: 'app/views/pages/Fancylist.html',
         controller: 'Fancylistcntr',
         url: '/fancyList?matchId',
         resolve: {
             loadMyFiles: function ($ocLazyLoad) {
                 return $ocLazyLoad.load({
                     name: 'ApsilonApp',
                     files: [
                             'app/scripts/controllers/Fancylistcntr.js',
                     ]
                 })
             }
         }
     })
      .state('dashboard.Matchodds', {
          templateUrl: 'app/views/pages/Matchodds.html',
          controller: 'Matchoddscntr',
          url: '/matchOdds?MatchId&&MarketId&&matchName&&date',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Matchoddscntr.js',
                                "app/scripts/directives/sidebar/sidebar_0.js",
                      ]
                  })
              }
          }
      })
	  .state('dashboard.Matchodds170203', {
	      templateUrl: 'app/views/pages/Matchodds170203.html',
	      controller: 'Matchoddscntr170203',
	      url: '/matchOdds170203?MatchId&&MarketId&&matchName&&date',
	      resolve: {
	          loadMyFiles: function ($ocLazyLoad) {
	              return $ocLazyLoad.load({
	                  name: 'ApsilonApp',
	                  files: [
                                'app/scripts/controllers/Matchoddscntr170203.js',
                                "app/scripts/directives/sidebar/sidebar_0.js",
	                  ]
	              })
	          }
	      }
	  })
    .state('dashboard.Matchodds170206_t3', {
        templateUrl: 'app/views/pages/Matchodds170206_t3.html',
        controller: 'Matchoddscntr170206_t3',
        url: '/matchOdds170206_t3?MatchId&&MarketId&&matchName&&date',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Matchoddscntr170206_t3.js',
                              "app/scripts/directives/sidebar/sidebar_0.js",
                    ]
                })
            }
        }
    })
    .state('dashboard.Get_event', {
        templateUrl: 'app/views/pages/Get_eventlst.html',
        controller: 'Geteventlstcntr',
        url: '/get_event',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Geteventlstcntr.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Getmatchfrmapi', {
        templateUrl: 'app/views/pages/Getmatchfrmapi.html',
        controller: 'Matchlstfrmapicntr',
        url: '/Getmatchfrmapi?seriesId&&sportId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Matchlstfrmapicntr.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Getseriesfrmapi', {
        templateUrl: 'app/views/pages/Getseriesfrmapi.html',
        controller: 'Serieslstfrmapicntr',
        url: '/Getseriesfrmapi?sportId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: ['app/scripts/controllers/Serieslstfrmapicntr.js', ]
                })
            }
        }
    })
    .state('dashboard.Getmarketlstapi', {
        templateUrl: 'app/views/pages/Getmarketlstapi.html',
        controller: 'Marketlstapicntr',
        url: '/getMarketLstApi?MatchId&&sportId&&seriesId',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Marketlstapicntr.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Listmarkettype', {
        templateUrl: 'app/views/pages/Listmarkettype.html',
        controller: 'Listmarkettypecntr',
        url: '/listMarketType',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Listmarkettypecntr.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Chiphistorycntr', {
        templateUrl: 'app/views/pages/Chiphistory.html',
        controller: 'Chiphistorycntr',
        url: '/Chiphistorycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/Chiphistorycntr.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Chipsummerycntr', {
        templateUrl: 'app/views/pages/Chipsummery.html',
        controller: 'Chipsummerycntr',
        url: '/Chipsummerycntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Chipsummerycntr.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Onlineusersctrl', {
        templateUrl: 'app/views/pages/Onlineusers.html',
        controller: 'Onlineusersctrl',
        url: '/Onlineusersctrl',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              //'http://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js',
                              'app/scripts/controllers/Onlineusersctrl.js',
                    ]
                })
            }
        }
    })
    .state('dashboard.Profitlosscntr', {
        templateUrl: 'app/views/pages/Proftloss.html',
        controller: 'Profitlosscntr',
        url: '/Profitlosscntr',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                              'app/scripts/controllers/Profitlosscntr.js',
                    ]
                })
            }
        }
    })
      .state('dashboard.match_result', {
          templateUrl: 'app/views/pages/match_result.html',
          controller: 'Matchresultcntr',
          url: '/match_result',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Matchresultcntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Get_actMtchUsersCntr', {
          templateUrl: 'app/views/pages/getActiveMatchUsers.html',
          controller: 'Get_actMtchUsersCntr',
          url: '/Get_actMtchUsersCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Get_actMtchUsersCntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.Get_bethistryCntr', {
          templateUrl: 'app/views/pages/Get_bethistry.html',
          controller: 'Get_bethistryCntr',
          url: '/Get_bethistryCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/Get_bethistryCntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.userRightsCntr', {
          templateUrl: 'app/views/pages/userRights.html',
          controller: 'userRightsCntr',
          url: '/userRightsCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/userRightsCntr.js',

                      ]
                  })
              }
          }
      })
      .state('dashboard.SetAdminLimitCntr', {
          templateUrl: 'app/views/pages/SetAdminLimit.html',
          controller: 'SetAdminLimitCntr',
          url: '/SetAdminLimitCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/SetAdminLimitCntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.CrtSubAdminCntr', {
          templateUrl: 'app/views/pages/CrtSubAdmin.html',
          controller: 'CrtSubAdminCntr',
          url: '/CrtSubAdminCntr',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                                'app/scripts/controllers/CrtSubAdminCntr.js',
                      ]
                  })
              }
          }
      })
      .state('dashboard.SubAdminAssignCntr', {
          templateUrl: 'app/views/pages/subadminAssign.html',
          controller: 'SubAdminAssignCntr',
          url: '/SubAdminAssignCntr?userId&&userName',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/SubAdminAssignCntr.js', ]
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
                            'app/scripts/directives/dashboard/userdashboard.js',
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
          templateUrl: 'app/views/pages/changePassword.html',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Chngpasscontroller.js', ]
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
                      files: ['app/scripts/directives/dashboard/matchResult.js', ]
                  })
              }
          }
      })
      .state('dashboard.myAccountCntr', {
          url: '/myAccountCntr',
          controller: 'myAccountCntr',
          templateUrl: 'app/views/pages/myAccount.html',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/myAccountCntr.js', ]
                  })
              }
          }
      })
      .state('dashboard.closeUserListCntr', {
          url: '/closeUserListCntr',
          controller: 'closeUserListCntr',
          templateUrl: 'app/views/pages/closeUserList.html',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/closeUserListCntr.js', ]
                  })
              }
          }
      })
      .state('dashboard.newChipHistory', {
          url: '/newChipHistory',
          controller: 'NewChipHistoryCntr',
          templateUrl: 'app/views/pages/Bethistory.html',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/NewChipHistoryCntr.js', ]
                  })
              }
          }
      })
      .state('dashboard.DelChipLst', {
          url: '/DelChipLst',
          controller: 'Delchipcntr',
          templateUrl: 'app/views/pages/Delchiplist.html',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: ['app/scripts/controllers/Delchipcntr.js']
                  })
              }
          }
      })
      .state('dashboard.ChiphistorycntrByuId', {
          templateUrl: 'app/views/pages/ChiphistoryByUser.html',
          controller: 'ChiphistorycntrByuId',
          url: '/ChiphistorycntrByuId?UserID&&usetype&userName&&parentId',
          resolve: {
              loadMyFiles: function ($ocLazyLoad) {
                  return $ocLazyLoad.load({
                      name: 'ApsilonApp',
                      files: [
                              'app/dist/ag-grid-enterprise.js',
                              'app/scripts/controllers/ChiphistorycntrByuId.js',
                      ]
                  })
              }
          }
      })
    .state('dashboard.PnlPlMiSheet', {
        templateUrl: 'app/views/pages/Proftlossbybetid.html',
        controller: 'PnlcntrBybId',
        url: '/PnlcntrBybId?BetID',
        resolve: {
            loadMyFiles: function ($ocLazyLoad) {
                return $ocLazyLoad.load({
                    name: 'ApsilonApp',
                    files: [
                            'app/scripts/controllers/PnlcntrBybId.js',
                    ]
                })
            }
        }
    })
}
]);
app.run(function ($rootScope, $timeout, $document, $location, loginService, $mdDialog) {
    var TimeOutTimerValue = 300000000;
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
});
app.controller('SwitchDemoCtrl', function ($scope) {
    $scope.data = { cb1: true, cb4: true, cb5: false };
    $scope.message = 'false';
    $scope.onChange = function (cbState) { $scope.message = cbState; };
});
app.run(function ($rootScope, $templateCache, sessionService, loginService, $location) {
    $rootScope.HelperAllRights = sessionService.get('HelperAllRights');
    $rootScope.$on('$viewContentLoaded', function () {
        if (sessionService.get('user_id') == null) { $location.path('/login'); }
        $templateCache.removeAll();
    });
});
/*for Back Button*/
/*end of Back Button*/
app.run(function (loginService, sessionService) {
    // if (sessionService.get('type') == "3") {//only for user
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
                        });
                    }
                }
            }
        })
    }
    worker();
    setInterval(worker, 5000);
    // }
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
                template: "<md-dialog style='max-width:100%;'><md-toolbar style='padding:20px 40px;background-color:#DFF0D8;color:#4f8a10;'><div class='md-toolbar-tools'><h2 style='font-weight:700;font-size:19px;'> " + message + "  </h2></div></md-toolbar></md-dialog>",
                fullscreen: false, locals: { timeout_time: timeout_time },
                controller: function DialogController($mdDialog, $timeout, timeout_time) {
                    if (timeout_time == angular.isUndefinedOrNull) timeout_time = 3000;
                    $timeout(callAtTimeout1, timeout_time);
                    function callAtTimeout1() { $mdDialog.hide(); }
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
app.run(function ($rootScope, sessionService) {
    $rootScope.user = sessionService.get('user');
    $rootScope.Balance = sessionService.get('Balance');
    $rootScope.Liability = sessionService.get('Liability');
});
agGrid.initialiseAgGridWithAngular1(angular);
app.factory('speech', function () {
    if (window.speechSynthesis) { var msg = new SpeechSynthesisUtterance(); }
    function getVoices() { window.speechSynthesis.getVoices(); return window.speechSynthesis.getVoices(); }
    function sayIt(text) { var voices = getVoices(); var config = { voiceIndex: 8, rate: 1, pitch: 1, volume: 1 }; msg.voice = config && config.voiceIndex ? voices[config.voiceIndex] : voices[0]; msg.text = text; speechSynthesis.speak(msg); }
    return { sayText: sayIt, getVoices: getVoices };
});
app.factory('focus', function ($timeout, $window) { return function (id) { $timeout(function () { var element = $window.document.getElementById(id); if (element) element.focus(); }); }; });