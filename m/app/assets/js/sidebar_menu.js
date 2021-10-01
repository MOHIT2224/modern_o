
$('.try').on('click', function(){
   $(this).show().siblings(".try").hide();
});

$('.all-sport').on('click', function(){
   $(".try").show();
   $('.try input:checkbox').attr('checked',false);
});
