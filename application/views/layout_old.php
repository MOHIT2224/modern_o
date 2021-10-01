<?php
  defined('BASEPATH') or exit('No direct script access allowed');
 /* header("Cache-Control: no-cache, no-store, must-revalidate");
  header("Pragma: no-cache");
  header("Expires: 0");*/
  //$s=RAND() * (10 - 1) + 1
?>
<!DOCTYPE html>
<html class="no-js">
  <head>
     <!--  <meta http-equiv="Cache-Control" content="no-cache" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="0" /> -->


      <meta http-equiv="cache-control" content="max-age=0" />
      <meta http-equiv="cache-control" content="no-cache" />
      <meta http-equiv="expires" content="0" />
      <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
      <meta http-equiv="pragma" content="no-cache" />


      <script> var BASE_URL = "<?php echo site_url(); ?>";</script>    

      <meta charset="utf-8"></meta>
      <meta name="description" content=""></meta>
      <meta name="viewport" content="width=device-width"></meta>
      <title>	<?php echo $this->config->item('title'); ?></title>
      <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!--  <script src="https://use.fontawesome.com/3bf12dcdf0.js"></script> -->
      <link rel="stylesheet" href="app/assets/css/fonts_css.css"/>
      <link rel="stylesheet" href="app/dist/angular-tree-widget.css"/>
      <link rel="stylesheet" href="app/assets/css/bootstrap.min.css"/>
      <link rel="stylesheet" href="app/assets/css/customStyle.css?ver=1.1"/>
      <link rel="stylesheet" href="app/assets/css/accordion-angular.css"/>
      <link rel="stylesheet" href="app/assets/css/my-style.css?ver=1.1"/>
      <link rel="stylesheet" href="app/styles/libs/loading-bar.min.css"/>
      <link rel="stylesheet" href="app/dist/ng-slim-scroll.css"/>
      <link rel="stylesheet" href="app/assets/css/angular-material.css" />
      <link rel="stylesheet" href="app/dist/chart/c3.min.css"/>
      <script src="https://use.fontawesome.com/3bf12dcdf0.js"></script>
     <script type="text/javascript" src="app/dist/jquery-latest.min.js"></script>
      <script type="text/javascript" src="app/dist/agGrid.js"></script>
      <script type="text/javascript" src="app/dist/chart/d3.min.js"></script>
      <script type="text/javascript" src="app/dist/chart/c3.min.js"></script>
      <script type="text/javascript" src="app/lib/angular.min.js"></script>
      <script type="text/javascript" src="app/dist/chart/c3-angular.min.js"></script>
     
      <script type="text/javascript" src="app/js/libs/angular-ui-router.min.js"></script>
      <script type="text/javascript" src="app/lib/angular-aria.min.js"></script>
      <script type="text/javascript" src="app/lib/angular-material.js"></script>
       <script type="text/javascript" src="app/lib/angular-animate.min.js"></script>
      <script type="text/javascript" src="app/scripts/app_9.js?var=<?php echo $s=(RAND() * (10 - 1) + 1)?>"></script>
      <script type="text/javascript" src="app/dist/re-tree.js"></script>
      <script type="text/javascript" src="app/dist/ng-device-detector.js"></script>
      <script type="text/javascript" src="app\js\shortcutKey.js"></script>
      <script type="text/javascript" src="app\js\moment.min.js"></script>
      <script type="text/javascript" src="app/js/libs/ocLazyLoad.min.js"></script>
      <script type="text/javascript" src="app/lib/angular-recursion.js"></script>
      <script type="text/javascript" src="app/dist/angular-tree-widget.js"></script>
      <script type="text/javascript" src="app/assets/js/contextmenu.js"></script>
      <script type="text/javascript" src="app/js/libs/bootstrap.min.js"></script>
      <script type="text/javascript" src="app/js/libs/loading-bar.min.js"></script>
      <script type="text/javascript" src="app/js/libs/angular-sanitize.min.js"></script>
      <script type="text/javascript" src="app/assets/js/ui-bootstrap-tpls-0.10.0.min.js"></script>
      <script type="text/javascript" src="app/scripts/controllers/Form.js"></script>
      <script type="text/javascript" src="app/scripts/services/sessionService.js"></script>
      <script type="text/javascript" src="app/scripts/services/loginService.js"></script>
      <script type="text/javascript" src="app/scripts/services/get_userser.js"></script>
      <script type="text/javascript" src="app/scripts/services/SessionFancy.js"></script>
      
      <script type="text/javascript" src="app/dist/ng-slim-scroll.js"></script>
      <script type="text/javascript" src="app/dist/angular-slimscroll.js"></script>
      <script type="text/javascript" src="app/scripts/directives/header/header_0.js"></script>
      <script type="text/javascript" src="app/scripts/directives/header/header-notification/header-notification_1.js"></script>
      <script type="text/javascript" src="app/scripts/directives/sidebar/sidebar_1.js"></script>
     
  </head>
  <body>
    <div class="wrapper mainSite activeSideNav" ng-app="ApsilonApp">
      <div ui-view></div>
    </div>
    
      <script type="text/javascript">
        

          /*  function reloadIt() {
                  //alert("reload");
                var clocktime = new Date();
                var utchours = clocktime.getUTCHours();
                var utcminutes = clocktime.getUTCMinutes();
                var utcseconds = clocktime.getUTCSeconds();
                var utcyear = clocktime.getUTCFullYear();
                var utcmonth = clocktime.getUTCMonth()+1;
                var utcday = clocktime.getUTCDate();

                if (utchours <10) { utchours = "0" + utchours }
                if (utcminutes <10) { utcminutes = "0" + utcminutes }
                if (utcseconds <10) { utcseconds = "0" + utcseconds }
                if (utcmonth <10) { utcmonth = "0" + utcmonth }
                if (utcday <10) { utcday = "0" + utcday }

                var utctime = utcyear + utcmonth + utcday;
                utctime += utchours + utcminutes + utcseconds;
                x = utctime

                isNew = self.location.href
                if(!isNew.match('#','x')) {
                self.location.replace(isNew + '#' + x)
                }
            }

            //-->*/
      </script>

  </body>
</html>