<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!DOCTYPE html>
<html class="no-js">
  <head>
      <script> var BASE_URL = "<?php echo site_url(); ?>";</script>    

      <meta charset="utf-8"></meta>
      <meta name="description" content=""></meta>
      <meta name="viewport" content="width=device-width"></meta>
      <title>	<?php echo $this->config->item('title'); ?></title>
      <link href="app/assets/css/fonts_css.css" rel="stylesheet"> 
      <link href="app/dist/angular-tree-widget.css" rel="stylesheet" />
      <link href="app/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"></link>
      <link href="app/assets/css/accordion-angular.css" rel="stylesheet" type="text/css"></link>
      <link href="app/assets/css/my-style.css" rel="stylesheet" type="text/css"></link>
      <link rel="stylesheet" href="app/styles/libs/loading-bar.min.css"/>
      <link rel="stylesheet" href="app/dist/ng-slim-scroll.css"/>
      <link rel="stylesheet" href="app/assets/css/angular-material.css" />
      <link href="app/dist/chart/c3.min.css" rel="stylesheet" type="text/css"/>
      <script src="app/dist/jquery-latest.min.js" type="text/javascript"></script>
      <script src="app/dist/agGrid.js"></script>
      <!--chart js-->
      <script src="app/dist/chart/d3.min.js"></script>
      <script src="app/dist/chart/c3.min.js"></script>
      <script src="app/lib/angular.min.js"></script>
      <script src="app/dist/chart/c3-angular.min.js"></script>
      <!--end of Chart-->
      <script src="app/lib/angular-animate.min.js"></script>
      <script src="app/js/libs/angular-ui-router.min.js"></script>
      <script type="text/javascript" src="app/lib/angular-aria.min.js"></script>
      <script type="text/javascript" src="app/lib/angular-material.js"></script>
      <script src="app/scripts/app_1.js"></script>
      <script type="text/javascript" src="app/dist/re-tree.js"></script>
      <script type="text/javascript" src="app/dist/ng-device-detector.js"></script>
      <script src="app\js\shortcutKey.js"></script>
      <script src="app\js\moment.min.js"></script>
      <script src="app/js/libs/ocLazyLoad.min.js"></script>
      <script src="app/lib/angular-recursion.js"></script>
      <script src="app/dist/angular-tree-widget.js"></script>
      <script src="app/assets/js/contextmenu.js"></script>
      <!--<script src="app/js/libs/jquery.min.js"></script>-->
      <script src="app/js/libs/bootstrap.min.js"></script>
      <script src="app/js/libs/loading-bar.min.js"></script>
      <script src="app/js/libs/angular-sanitize.min.js"></script>
      <script src="app/assets/js/ui-bootstrap-tpls-0.10.0.min.js"></script>
      <script src="app/scripts/controllers/Form.js"></script>
      <script src="app/scripts/services/sessionService.js"></script>
      <script src="app/scripts/services/loginService.js"></script>
      <script src="app/scripts/services/get_userser.js"></script>
      <script src="app/scripts/services/SessionFancy.js"></script>
      <script src="app/dist/ng-slim-scroll.js"></script>
      <script src="app/dist/angular-slimscroll.js"></script>
      <script src="app/scripts/directives/header/header_.js" type="text/javascript"></script>
      <script src="app/scripts/directives/header/header-notification/header-notification.js"></script>
      <script src="app/scripts/directives/sidebar/sidebar_0.js" type="text/javascript"></script>

	  

  </head>
  <body>
    <div class="wrapper" ng-app="ApsilonApp">
      <div ui-view></div>
    </div>
  </body>
</html>