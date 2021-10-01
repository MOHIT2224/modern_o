
// Home Slider 
$('.banner_slider').slick({
  infinite: true,
  slidesToShow: 1,
  autoplay:true,
  arrows:false,
  dots:true,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        slidesToShow: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: false,
        slidesToShow: 1
      }
    }
  ]
});
				
 	  
 
 $(document).ready(function() {
	 
	 
	  $('#accordion .accordion-toggle').addClass('collapsed');
	  
	   });