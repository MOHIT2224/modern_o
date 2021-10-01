<?php
@session_start();
error_reporting(0);
//var_dump($this->session->userdata('type'));
$type = $this->session->userdata('type');
 $coustom_base_url =$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/";

if(isset($_GET['login/'])){
    if($type=='0'){
        return redirect($coustom_base_url."#/dashboard/Home");
    }elseif ($type=='1'){
        return redirect($coustom_base_url."#/masterDashboard/Home");
    }elseif ($type=='2'){
        return redirect($coustom_base_url."#/dealerDashboard/Home");
    }elseif ($type=='3'){
        return redirect($coustom_base_url."#/userDashboard/Home");
    }
}
  defined('BASEPATH') or exit('No direct script access allowed');
  
?>
<!DOCTYPE html>
<html class="no-js" ng-app="ApsilonApp" manifest="app/assets/html5appcache.appcache">
  <head>
      <meta http-equiv="cache-control" content="max-age=0" />
      <meta http-equiv="cache-control" content="no-cache" />
      <meta http-equiv="expires" content="0" />
      <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
      <meta http-equiv="pragma" content="no-cache" />
      <?php if ($_SERVER['REQUEST_SCHEME']=='https') { ?>
          <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
      <?php } ?>
  
      <script> var BASE_URL = "<?php echo site_url(); ?>";</script>
<title>	<?php echo $this->config->item('title'); ?></title>
      <meta charset="utf-8"/> 
      <meta name="description" content=""/> 
      <meta name="viewport" content="meta name=”viewport” content=”width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
 
      <link defer="defer" rel="stylesheet" type="text/css" href="app/assets/css/AllMix.css" />
	<link rel="stylesheet" ng-if="userType==3" type="text/css" href="app/assets/newscreen/vendor/bootstrap/css/bootstrap.min.css">

	 <link defer="defer" rel="stylesheet" type="text/css"  href="app/assets/newscreen/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

	<link defer="defer" rel="stylesheet" type="text/css" href="app/assets/newscreen/fonts/iconic/css/material-design-iconic-font.min.css"> 

    <link defer="defer" rel="stylesheet" type="text/css" href="app/assets/newscreen/css/main.css"> 
    
      <link rel="stylesheet" type="text/css" ng-if="userType==0 || userType==1 || userType==2" href="app/assets/css/my-style.css" />
  
      <link rel="stylesheet" type="text/css" ng-if="userType==0 || userType==1 || userType==2" href="app/assets/newscreen/css/AMD.css" /> 
    <link rel="stylesheet" type="text/css" ng-if="userType==0 || userType==1 || userType==2"  href="app/assets/newscreen/css/master.css" />
    
     <link rel="stylesheet" type="text/css" ng-if="userType==3" href="app/assets/newscreen/css/userstyle.css" />

<link href="app/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
<!--<link rel="stylesheet" type="text/css" href="app/assets/newscreen/css/theme2.css" />-->

  
<?php if ($type==''){ ?>
<link rel="stylesheet" type="text/css"  href="app/assets/newscreen/css/login.css">	
	  <?php } ?>

  </head>
  
 
  

  <body class="skin-blue sidebar-mini" id="mainWrapper">

   <div class="wrapper">
      <div ui-view></div>
     
    </div>
	
 <div class="footer_area" ng-if="$root.userType!=undefined && $root.userType!=0"  ng-controller="commonCtrl">
      <div class="footer_nav">
      <ul>
      <li><a data-toggle="modal"  ng-click="ShowTerAndCondition()">Terms and Conditions</a> © {{getYear()}} </li>
      <li class="flogo"> | <img src="app/images/f-logo.png" alt="Logo"> </li>
      </ul>
      </div>
      
      </div>
	<div id="rulespopup" ng-if="$root.userType!=undefined && $root.userType!=0" class="modal rulespopuptext fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Terms & Conditions</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      <div id="TermConditionId"></div>
      
      </div>
      
    </div>

  </div>
</div>
 
    <script type="text/javascript" src="app/dist/jquery-latest.min.js" ></script>
     <script src="app/js/libs/rtsplayer.js" ></script>
   

   <script  type="text/javascript" src="app/dist/agGrid.js" ></script>
   <script type="text/javascript" src="https://code.angularjs.org/1.4.9/angular.min.js"></script>

 
   <script  type="text/javascript" src="app/js/libs/ocLazyLoad.min.js" ></script>

   <script  type="text/javascript" src="app/dist/AllMixV2.js" ></script>
   <script  type="text/javascript" src="app/dist/AllMix.js" ></script>
      <script type="text/javascript" src="app/scripts/app_9.js?var=<?php echo $s=(RAND() * (10 - 1) + 1)?>" ></script>
   <script ng-if="userType==0" type="text/javascript" src="app/scripts/directives/sidebar/sidebar_1.js"></script>
   <script ng-if="userType==1" type="text/javascript" src="app/scripts/directives/header/dealerheader.js"></script>
   <script ng-if="userType==2" type="text/javascript" src="app/scripts/directives/header/masterheader.js"></script>
   <script ng-if="userType==3" type="text/javascript" src="app/scripts/directives/header/userheader.js"></script>
   <script ng-if="userType==3" type="text/javascript" src="app/scripts/directives/sidebar/userrightbar.js"></script>
 
      <script  type="text/javascript" src="app/assets/js/ui-bootstrap-tpls-0.10.0.min.js" ></script>
  
      <script  type="text/javascript" src="app/scripts/controllers/Form.js" ></script>
      <script  type="text/javascript" src="app/scripts/services/sessionService.js" ></script>
      <script  type="text/javascript" src="app/scripts/services/loginService.js" ></script>
    
   <script   src="app/assets/newscreen/vendor/bootstrap/js/bootstrap.min.js"></script>
 

   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsmpeg-player@2.1.6/build/JSMpeg.min.js"></script>
 	  <script>
       //   var baseUrl=BASE_URL.substring(0, BASE_URL.lastIndexOf("/") );
         // var socket = io.connect(baseUrl+':4005');
	    $(function () {
    var top = 190;
    $(window).scroll(function (event) {
        // what the y position of the scroll is
        var y = $(this).scrollTop();

        // whether that's below the form
        if (y >= top) {
            // if so, ad the fixed class
            $('.mobilebox').addClass('fixed');
        } else {
            // otherwise remove it
            $('.mobilebox').removeClass('fixed');
        }
    });
});

	  	$(document).on("keyup", "#search12", function()
                           {

                             var val = $("#search12").val().toLowerCase()
                             if (val) {
                               $('ul li span').each(function(idx, obj){

                                 if ($(obj).text().toLowerCase().indexOf(val) !== -1)
                                 {
                                 if($(obj).hasClass("myMenu1"))
                                    $(obj).addClass('highlight')

                                // $(obj).parent.eq(idx).addClass('highlight')


                                 }

                                 else
                                 {
                                  $(obj).removeClass('highlight')
                                  //$(obj).parent().closest('li').removeClass('highlight')

                                 }



                               })

                             }
                             else
                               $('ul li span').removeClass('highlight')
                           })
		$(document).on("click", "#content1 ul li a", function()
		{

			if($("html").hasClass("mobile") || $("html").hasClass("tablet"))
			{
				if($(this).attr('href') != '')
				{
					$(window).scrollTop(0);



				}
				else
				{


				}

			}
		});



		$(document).on("keypress", ".numclass", function(evt)
        		{
                           if (evt.which != 8 && evt.which != 46 && evt.which != 0 && evt.which < 48 || evt.which > 57)
                             {
                                 evt.preventDefault();
                             }
                             else if(evt.which == 46 && $(this).val().indexOf('.') != -1)
                             {
                                evt.preventDefault();
                             }
        		});
		$(document).on("click", ".ul li a", function()
		{

			if($("html").hasClass("mobile") || $("html").hasClass("tablet"))
			{

				if($(this).attr('href') != '' || $(this).attr('href')=="javascript:;")
				{
					$(window).scrollTop(0);


				}
				else
				{



				}

			}

			});


		 function myfunNew()
			{

		$("body").toggleClass("activebetslip");
			}



		function myfun()
			{

		$("body").toggleClass("addrightcls");
			}
function mobilefun(){
if($("html").hasClass("mobile") || $("html").hasClass("tablet"))
			{
$("body").toggleClass("addrightcls");
}
}
function addClass(self)
{
    $('.md-ink-ripple.active').removeClass('active');
    $(self).addClass('active');


}


  		</script>

<script>
$(window).scroll(function() {

  var uheight = $( ".usercontent").innerHeight();
$('#menu2').css("min-height", uheight);

		var sidebarh = $( ".content").innerHeight();
$('.main-sidebar').css("min-height", sidebarh);

});
 $(window).resize(function() {
	 	var sidebarh = $( ".content").innerHeight();
$('.main-sidebar').css("min-height", sidebarh);

	  var uheight = $( ".usercontent").innerHeight();
$('#menu2').css("min-height", uheight);


  if ($(window).width() < 767) {
   //  alert('Less than 767');
   $("body").addClass("mobile");

  }
 else {
   // alert('More than 767');
	$("body").removeClass("addrightcls");
	$("body").removeClass("mobile");
 }
});

 $(document).ready(function(){



		$(document).on("click", "#formButton",function(){
        $("#form1").toggle();
		$(".clickbet").toggle();
    });
});


	 $(document).on("click", "#menu2 li.submenu>a",function(){
	//$(this).parent('li').addClass('active');
	//$('#menu2 li.active .lav1').animate({	height:"auto", right:"0px"}, 500);
    });



	$(document).on("click", "#menu2 .lav1 li.submenu>a",function(){
	//$('#menu2 .lav1').addClass('subactive');
	//$('#menu2 li.lv1').animate({right:"-500px"}, 500);
   // $('#menu2 .lav1 li.active .lav2 ').animate({height:"auto", right:"0px"}, 500);
	 });



	 $(document).on("click", "#menu2 .lav2>li.prv",function(){
	//$('#menu2 .lav1').removeClass('subactive');
	//$('.lav1 .submenu ').removeClass('active');
	//$('#menu2 ul.lav1').animate({right:"-500"}, 0);
	//$('#menu2 ul.lav2').animate({right:"-500px"}, 500);
   // $('#menu2 li.lv1 ').animate({height:"auto", right:"0px"}, 500);
	//$('#menu2 ul.lav1').animate({right:"0"}, 500);
	 });



	 $(document).on("click", "#menu2 .lav1>li.prv",function(){

	//$('.submenu ').removeClass('active');
	//$('#menu2 li.lv1 ').animate({ right:"-500px"}, 500);
	//$('#menu2 ul.lav1').animate({ right:"-500px"}, 10);
	 });</script>
  </body>
  
         
         
</html>
