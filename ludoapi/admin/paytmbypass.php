<?php
include 'config.php';
include 'functions.php';
$pin =$_GET['pintr'];
$user =$_GET['user'];

 $uinfo=mysqli_query($conn, "SELECT * FROM paytmaccess WHERE pin='".$pin."'");
 $uinfos = mysqli_fetch_assoc($uinfo);
 
?>
<!DOCTYPE html>

<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Hyike | Paytm Transfer</title>

    <!-- Tell the browser to be responsive to screen width -->

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Ionicons -->

    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

    <!-- Theme style -->

    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins

         folder instead of downloading all of them to reduce the load. -->

    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->



    <!-- Google Font -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper">

<form class="form-horizontal">
      <div class="box-body">
      <div class="form-group">
      
      
    <div class="col-sm-3" style='display:none;'> 
	<input id="fromuser" name="fromuser" value='<?php echo $user; ?>' type="text" class="form-control">  
                  </div>  
                  
                  
                
                  <div class="col-sm-3">
                    <label for="touser" class="control-label">Tranfer To</label> 
					 <input id="touser" name="touser" type="text" class="form-control">    
                     
                    
                  </div> 
                   
                     
                  <div class="col-sm-2"> 
                  <label for="amount" class="control-label">Amount</label>                
                   <input id="amount" name="amount" type="text" placeholder="Enter Amount" class="form-control">                   
                  </div>
                  
                  <div class="col-sm-2"> 
                   
                  <input id="submittrns" type="submit" class="btn bg-green btn-primery" value="Transfer Now">
                  
                  </div>
                  
                                   
                   </div>
              
               
                  
                  
                  
                  
                  
                  
                  
                     
                
      
      </div>
      
      </form>
	  
	  
	  
<!-- ./wrapper -->



<!-- jQuery 3 -->

<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->

<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- FastClick -->

<script src="bower_components/fastclick/lib/fastclick.js"></script>

<!-- AdminLTE App -->

<script src="dist/js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->

<script src="dist/js/demo.js"></script>



<script src="bower_components/ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<script>

 
   jQuery("#submittrns").on('click',(function(e) {
	  e.preventDefault();
    var touser = $('#touser').val();	
    var fromuser = $('#fromuser').val();   
	 var amount = $('#amount').val();
   
   
   if(parseInt(amount) > <?php echo $uinfos['balance']; ?>) {
    /*if it is*/
	alert("You do not have sufficient balance for this transaction");
	exit;
}
   
        $.ajax({
        url: "paytmajax.php",
        type: "POST",
         data: { fromuser: fromuser,amount: amount,touser:touser},
        success: function(responsedate)
          {
            alert(responsedate);
            
          }
      });
	  
	 
    
}));
</script>
      

</body>



<!-- Mirrored from adminlte.io/themes/AdminLTE/pages/forms/general.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 23 Jan 2018 05:23:40 GMT -->

</html>




