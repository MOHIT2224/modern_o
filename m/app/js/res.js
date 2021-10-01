(function( $ ){

  $(document).ready(function(){

    var current_width = $(window).width();
    //do something with the width value here!
    if(current_width < 481)
      $('html').addClass("mobile").removeClass("desktop").removeClass("tablet");

    else if(current_width < 739)
      $('html').addClass("mobile").removeClass("desktop").removeClass("tablet");

    else if (current_width < 970)
      $('html').addClass("tablet").removeClass("desktop").removeClass("mobile");

    else if (current_width > 971)
      $('html').addClass("desktop").removeClass("mobile").removeClass("tablet");

     

     


  });

  //update the width value when the browser is resized (useful for devices which switch from portrait to landscape)
  $(window).resize(function(){
    var current_width = $(window).width();
    //do something with the width value here!
    if(current_width < 481)
      $('html').addClass("mobile").removeClass("desktop").removeClass("tablet");

    else if(current_width < 669)
      $('html').addClass("mobile").removeClass("desktop").removeClass("mobile").removeClass("tablet");

    else if (current_width < 970)
      $('html').addClass("tablet").removeClass("desktop").removeClass("mobile");

    else if (current_width > 971)
      $('html').addClass("desktop").removeClass("mobile").removeClass("tablet");

    

  });

})( jQuery );





