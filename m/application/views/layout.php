<?php
@session_start();
error_reporting(0);
$type = $this->session->userdata('type');

//$coustom_base_url =$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/betdip.com/m/";
$coustom_base_url =$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/m/";

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
<html class="no-js" ng-app="ApsilonApp">
  <head>
	<!--<base href="/betdip/">-->
      <meta http-equiv="cache-control" content="max-age=0" />
      <meta http-equiv="cache-control" content="no-cache" />
      <meta http-equiv="expires" content="0" />
      <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
      <meta http-equiv="pragma" content="no-cache" />
	  
	  
      
	<title>	<?php echo $this->config->item('title'); ?> </title>
      <meta charset="utf-8"/> 
      <meta name="description" content=""/> 
      <meta name="viewport"  content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="HandheldFriendly" content="true">
      <?php if ($_SERVER['REQUEST_SCHEME']=='https') { ?>
          <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
      <?php } ?>
       <script> 
	   var BASE_URL = "<?php echo $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/"; ?>";
       </script>  
	 
<meta property="al:android:package" content="com.skaigold.ludo">
<meta property="al:android:app_name" content="SKAI LUDO">
 
<meta property="og:title" content="Skai Gold" />
<meta property="og:type" content="website" />
       


  <link rel="stylesheet"  type="text/css" href="app/assets/css/bootstrap.min.css"> 
  <?php 	if($type=='0' || $type=='1' || $type=='2' || $type=='3'){ ?>     
             <link rel="stylesheet" href="app/dist/angular-tree-widget.css"/>
          <link rel="stylesheet" href="app/assets/css/AdminLTE.min.css?ver=1.1"/>
      <link rel="stylesheet" href="app/assets/css/angular-material.css" />
   <link rel="stylesheet" href="app/assets/css/timepicker.css"/>
  <link rel="stylesheet" type="text/css" href="app/assets/newscreen/fonts/font-awesome-4.7.0/css/font-awesome.min.css">    
       <?php } ?>

	
<link rel="stylesheet" type="text/css" href="app/assets/newscreen/fonts/iconic/css/material-design-iconic-font.min.css">
<link rel="stylesheet" type="text/css"  href="app/assets/newscreen/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="app/assets/newscreen/vendor/animate/animate.css">

    <link rel="stylesheet" type="text/css" href="app/assets/newscreen/css/main.css"> 




 <?php 	if($type=='0' || $type=='1' || $type=='2'){ ?> 
     
         <link rel="stylesheet" type="text/css" href="app/assets/css/bootstrap.min.css" />
 
     <link rel="stylesheet" type="text/css"  href="app/assets/newscreen/css/admin.css" />
     
     <link rel="stylesheet" type="text/css" href="app/assets/css/my-style.css" />
  
    <link rel="stylesheet" type="text/css"  href="app/assets/newscreen/css/custom.css" />

 


<?php } ?>
 <?php 	if($type=='3'){ ?> 
 <link rel="stylesheet" type="text/css"  href="app/assets/newscreen/slick/slick.css" />
 <link rel="stylesheet" type="text/css"  href="app/assets/newscreen/slick/slick-theme.css" />

 <?php } ?>
	  


 
  <link rel="stylesheet" type="text/css" href="app/assets/newscreen/css/mobile.css">
   

<?php if ($type==''){ ?>
<link rel="stylesheet" type="text/css"  href="app/assets/newscreen/css/login.css">	
	  <?php } ?>
</head>
	<!--ng-class="{'betslip-active':$root.betslipshow}"-->
    <body class="skin-blue sidebar-mini <?php 	if($type=='0' || $type=='1' || $type=='2'){ ?>  notuser  loginpage<?php }?>" >
    <div class="wrapper">
 
      <div ui-view></div>
    </div>
    <div class="footer_area"  ng-controller="commonCtrl" style="display: none" ng-if="false">
      <div class="footer_nav">
      <ul>
     <li><a  ng-click="ShowTerAndCondition()">Terms and Conditions</a> © {{getYear()}} </li>
      <li class="flogo"> | <img src="app/images/f-logo.png" alt="Logo"> </li>
      </ul>
      </div>
      
      </div>
	<div id="rulespopup" class="modal rulespopuptext fade" role="dialog">
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




	   <script type="text/javascript"  src="app/dist/jquery-latest.min.js"></script>
 

    <script type="text/javascript" src="app/dist/agGrid.js" ></script>
    <script type="text/javascript" src="https://code.angularjs.org/1.4.9/angular.min.js"></script>


    <script type="text/javascript" src="../app/dist/AllMixV2.js" ></script>
    <script type="text/javascript" src="../app/dist/AllMix.js" ></script>
         <script type="text/javascript"  src="app/scripts/app_9.js?var=<?php echo $s=(RAND() * (10 - 1) + 1)?>"></script>
   
      <script type="text/javascript"  src="app/js/libs/ocLazyLoad.min.js"></script>

 
      
      <script  type="text/javascript" src="app/assets/js/ui-bootstrap-tpls-0.10.0.min.js"></script>
      <script  type="text/javascript" src="app/scripts/controllers/Form.js"></script>
      <script  type="text/javascript" src="app/scripts/services/sessionService.js"></script>
      <script  type="text/javascript" src="app/scripts/services/loginService.js"></script>
 

    <script   src="app/assets/newscreen/vendor/bootstrap/js/bootstrap.min.js"></script>
  

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsmpeg-player@2.1.6/build/JSMpeg.min.js"></script>
	
	

		
		
 	  <script>
         // var baseUrl=BASE_URL.substring(0, BASE_URL.lastIndexOf("/") );
         // var socket = io.connect(baseUrl+':4005');
document.addEventListener('gesturestart', function (e) {
    e.preventDefault();
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
		
		 function myfunNew()
			{
		
		$("body").toggleClass("activebetslip");	
		
		
			
			} 



 function closnotif()
			{
		
		$(".notifications").hide();		
			} 
			
			
		 function opensearch()
			{
		$("body").removeClass('addrightcls');
		$("body").removeClass('rightsidebar');
		$("body").toggleClass("opensearch");
			
			} 	
	 
	
			
		 function ShowLive(self)
			{
		$('#alltabs a').removeClass('active');
		$('#liveCls').addClass('active');				
		$("#livesmatch").show();
		$("#openmarkets").show();		
		$("#allbets").hide();	
		$("#livetv").hide();
			} 	
			
		
		function ShowOpenbets(self)
			{
				
		$('#alltabs a').removeClass('active');		
		$('#openbetcs').addClass('active');
		$("#livesmatch").hide();
		$("#openmarkets").hide();		
		$("#allbets").show();	
		$("#livetv").hide();
		
			} 	
			
		
		function ShowLivetv(self)
			{
		$('#alltabs a').removeClass('active');		
		$('#livetvcls').addClass('active');
		$("#livesmatch").hide();
		$("#openmarkets").hide();		
		$("#allbets").hide();	
		$("#livetv").show();
			} 	
			
			
		function ShowMarket(self)
			{
		$('#alltabs a').removeClass('active');		
		$('#marketCls').addClass('active');
		$("#livesmatch").hide();
		$("#openmarkets").show();		
		$("#allbets").hide();	
		$("#livetv").hide();
			}
				
			
			

		
		function myfun()
			{
		$("body").removeClass('rightsidebar');	
		$("body").toggleClass("addrightcls");		
			} 
			
			
		function myfunright()
			{
		$("body").removeClass('addrightcls');
		$("body").toggleClass("rightsidebar");	
		 			
		}	
		
		
		
		
		function hidebetslips()
			{
			
		$("body").toggleClass("fullscreen");
			
			} 
			
			
			
		function removefullscreen()
			{
		$("body").removeClass('fullscreen');	
		 			
		}	
		$(document).on("click", ".othermenu a.dropdown-item", function()
		{
			myfunright();
		});
		$(document).on("click", ".md-menu a.md-button", function()
		{
			myfunright(); //admin
		});

  




		$(document).on("click", "#menu2 ul li a", function()
		{
			  
			
				if($(this).attr('href') != '' && $(this).attr('href') !='javascript:void(0)')
				{
					myfun();
				 }
				else
				{
			
			
					
				}
				
			
	
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
$(window).scroll(function(){
  var sticky = $('.navbar-dark'),
      scroll = $(window).scrollTop();

  if (scroll >= 10) sticky.addClass('fixed');
  else sticky.removeClass('fixed');
});
		
		
			
			
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




 $(window).resize(function() {
  if ($(window).width() < 767) {
 
   $("body").addClass("mobile");
	 
  }
 else {
    
	$("body").removeClass("addrightcls");	
	$("body").removeClass("mobile");	
 }
});

/* $(document).ready(function(){  

	$(document).on("click", ".betstoshow",function(){
	   var divClone = $(".navbar-toggler .fa-user + span").clone();
	    $("div#betslips .actionbox").append("<div class='newbal'><h4>Balance</h4></div>");
		$("div#betslips .actionbox .newbal").append(divClone);
	
		});

 	$(document).on("click", ".bet_table table td.td_btn",function(){
	   var divClone = $(".navbar-toggler .fa-user + span").clone();
	    $("div#betslips .actionbox").append("<div class='newbal'><h4>Balance</h4></div>");
		$("div#betslips .actionbox .newbal").append(divClone);
		

		});

 
		$(document).on("click", "#formButton",function(){
        $("#form1").toggle();
		$(".clickbet").toggle();
    });
});*/


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
     
      <?php 	if($type=='3'){ ?> 


         <script defer="defer" src="app/assets/newscreen/slick/slick.min.js"></script>
 
    <script>
 
	
	 
 $(document).on("click", "#liveCls",function(){
         initSlider();
    });
	
		
	 


function initSlider(){
    $('.slidernew').slick({
        dots: true,
		adaptiveHeight: true,
		 arrows: false,
    });
}
 
    </script>





     <?php } ?>

  </body>
</html>
