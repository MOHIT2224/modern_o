<!DOCTYPE html>
<meta charset="utf-8"/>
<HTML>
<head>

<script src="https://suite.simplify360.com/bootstrapV3_3_5/assets/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script> 	
 
<script>

$( document ).ready(function() {
    loadCategory();
	
});



var jsondata="";
var back="";
var lay="";
function loadCategory(){

     
     var i=0;
	$.ajax({
		url: "http://allies247.com/TestoddsCtrl/getBackLaysOfMarket",
		type: "POST",
		data: {},
		dataType:"json",
		
		success: function(json, textStatus, xhr) {
		jsondata=json.MarketRunner.runners;
              // console.log("json==>"+jsondata)

		$.each(json.MarketRunner.runners, function(key,value) {
        back = value.ex.availableToBack;
	    lay   =value.ex.availableToLay;
		
	
			       
		   if(i==0)
			{
			console.log(lay[0])
              if(back[0]!=null )
			  {
			   $("#back1").html(back[0].price);
			  }
			   if(lay[0]!=null )
			  {
			   $("#lay1").html(lay[0].price);
			
			  }
         	
			
			}
			
			if(i==1)
			{
				//console.log(back[0])
                           //   $("#back2").html(back[0].price);
			   //   $("#lay2").html(lay[0].price);
			
			
			 if(back[0]!=null )
			  {
			   $("#back2").html(back[0].price);
			  }
			   if(lay[0]!=null )
			  {
			   $("#lay2").html(lay[0].price);
			
			  }
			}
			
			 i++;
		});
               
				
				

		},
		complete: function(xhr, textStatus) {
			//console.log(xhr.status);


 
		} 
	});




		}


window.setInterval(function(){
  loadCategory();
}, 120);

</script>
</head>
 <body>
  <input type ="text" id= "name" />
  </body>
</HTML>





<div>
  <span  id="back1" style="background-color:red;width: 29px; "> </span>
  <span  id="lay1" style="background-color:yellow; width: 29px;">
  </span>
<div>
</br>
<div>
  <span  id="back2" style="background-color:red;width: 29px; "> </span>
  <span  id="lay2" style="background-color:yellow; width: 29px;">
  </span>
<div>




<!--{"MarketId":"1.134956992","TokenId":"7IRobhhquLR869k0uASekMmh1tdrvlXPw7eBoq5koQY=","userType":"3","userId":"1350","matchId":"28413038"}


 



-->