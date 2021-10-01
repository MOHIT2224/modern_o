
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus input ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
  
  
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    /*==================================================================
    [ Show pass ]*/
    var showPass = 0;
    $('.btn-show-pass').on('click', function(){
        if(showPass == 0) {
            $(this).next('input').attr('type','text');
            $(this).addClass('active');
            showPass = 1;
        }
        else {
            $(this).next('input').attr('type','password');
            $(this).removeClass('active');
            showPass = 0;
        }
        
    });


 
	/*
    $('#menu2 li.submenu>a').on('click', function(){
			alert('RAJ');
	$(this).parent('li').addClass('active');		
	$('#menu2 li.active .lav1').animate({	height:"auto", right:"0px"}, 500); 	
    });
   
   
    $('#menu2 .lav1 li.submenu>a').on('click', function(){
	$('#menu2 .lav1').addClass('subactive');		
	$('#menu2 li.lv1').animate({right:"-500px"}, 500); 		
    $('#menu2 .lav1 li.active .lav2 ').animate({	height:"auto", right:"0px"}, 500); 
	 });
	 
	 
	 
	 $('#menu2 .lav2>li.prv').on('click', function(){
	$('#menu2 .lav1').removeClass('subactive');	
	$('.lav1 .submenu ').removeClass('active');	
	$('#menu2 ul.lav1').animate({right:"-500"}, 0); 		
	$('#menu2 ul.lav2').animate({right:"-500px"}, 500); 		
    $('#menu2 li.lv1 ').animate({	height:"auto", right:"0px"}, 500); 
	$('#menu2 ul.lav1').animate({right:"0"}, 500);
	 });
	 
	 
	 
	 $('#menu2 .lav1>li.prv').on('click', function(){	
	$('.submenu ').removeClass('active');	
	$('#menu2 li.lv1 ').animate({ right:"-500px"}, 500); 
	$('#menu2 ul.lav1').animate({ right:"-500px"}, 10); 			 
	 });
	  
	  */


})(jQuery);