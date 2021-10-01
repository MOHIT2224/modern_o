<?php
include 'config.php';
include 'functions.php';
ob_start();
$session =session_start();
 $uid = $_SESSION['uid'];
 $uinfo=mysqli_query($conn, "SELECT * FROM users WHERE id='".$uid."'");
 $uinfos = mysqli_fetch_assoc($uinfo);
 $rcode=$uinfos['refercode'];
 
 if($uid=='1'){
	$sql=mysqli_query($conn, "SELECT * FROM users"); 
	$sqllist=mysqli_query($conn, "SELECT * FROM users");
 }else{
 $sql=mysqli_query($conn, "SELECT * FROM users WHERE pid='".$uid."' OR refer='".$rcode."'");
 $sqllist=mysqli_query($conn, "SELECT * FROM users");
 }
 
	  if($uid==''){
	echo "<script type='text/javascript'>window.location.href = ' index.php';</script>";
	exit;
	}
?>
<!DOCTYPE html>

<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Ludo  | Withdraw</title>

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

<?php include 'header.php'; ?>


    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper" style="min-height: 837px;">

        <!-- Content Header (Page header) -->

        <section class="content-header">

            <h1>

              Withdraw Request

            </h1>

            <ol class="breadcrumb">

                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li><a href="#">User</a></li>

                <li class="active">Withdraw Request</li>

            </ol>

        </section>



        <!-- Main content -->

        <section class="content">

            <div class="row">

                <!-- right column -->

                <div class="col-md-12">

                    <!-- Horizontal Form -->

                    <div class="box box-info">

                 

                        <!-- /.box-header -->

                        <!-- form start -->

                        
                            <section class="content">
      <!-- Info boxes -->
        <!-- Info boxes -->
      <?php
	   $from =$_POST["from"];
	    $to =$_POST["to"];
		
		?>
      <form method = "POST">
         
		 <div class='col-sm-6'> 

		 <div class="input-group date" data-date-format="yyyy.mm.dd">
            <input name='from'  type="text" class="form-control" placeholder="From Date">
              </div>
          </div>
		  
		  <div class='col-sm-6'> 
		   <div class="input-group date" data-date-format="yyyy.mm.dd">
            <input name='to' type="text" class="form-control" placeholder="To Date">
            
          </div>
		   </div>
		  
       
         <input type = "submit" />
      </form>
   
   
   <?php
   if( $_POST["from"] || $_POST["to"] ) {
	   
	   $from =$_POST["from"];
	    $to =$_POST["to"];
 
	   
  $touserinfo=mysqli_query($conn, "SELECT * FROM payment 
WHERE datetime >= '".$from."' 
AND datetime <= '".$to."' and trnstype='withdrawal' and status='In Process' order by pid DESC"); 
			  
    $toutotal=mysqli_query($conn, "select sum(amount) as total from payment
WHERE datetime >= '".$from."' 
AND datetime <= '".$to."' and trnstype='withdrawal' and status='approved' order by pid DESC");

  
   } else {
	   $toutotal=mysqli_query($conn, "select sum(amount) as total from payment where trnstype='withdrawal' and status='approved' order by pid DESC");
	    $touserinfo=mysqli_query($conn, "select * from payment where trnstype='withdrawal' and status='In Process' order by pid DESC");
   }
    
   
   $ttotal = mysqli_fetch_assoc($toutotal);
   
     
   ?>
   <h2> Total Withdraw Success  : <?php echo $ttotal['total'];?></h2>
      
   
     
      <div class="box-body">
              <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>
              <th>User</th>
              <th> Order Id</th>
              <th>Amount</th>
              <th>Request Date</th>
              <th>Withdrawal Into</th>              
              <th> Status</th>
              <th>  </th>
                 
             
              </tr>
              </thead>
                <tbody>
   
           <?php while($user = mysqli_fetch_assoc($touserinfo)) {  
		   $userid = $user['uid'];
		    $pid = $user['pid'];
		    $uinfo=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");			
			$userinfo = mysqli_fetch_assoc($uinfo);
		   ?>
           
              <tr>
              <td><a href='viewhistory.php?userid=<?php echo $userid;?>'><?php echo $userinfo['name']; ?> - <?php echo $userinfo['mobile']; ?></a></td>
              <td><?php echo $user['orderid']; ?></td>
			  
              <td><?php echo $user['amount']; ?></td>
               <td> 
              
              <?php
											$date=  $user['datetime'];
			  
			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
											
											  ?></td>
              <td><a href="kyc.php?kycid=<?php echo $userid; ?>&getway=<?php echo $user['getway']; ?>"><?php echo $user['getway']; ?></a></td>              
              <td><?php echo $status = $user['status']; ?></td>
			  
              <td> 
			  <div class="btn-group">

			  <?php if( $status=='Success'){ ?>
 <button type="button" class="btn btn-success">Completed</button>
			  <?php } else { ?>                                                    <button type="button" class="btn btn-warning">Pending</button>
                                                                                                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">

                                                        <span class="caret"></span>

                                                        <span class="sr-only">Toggle Dropdown</span>

                                                    </button>
                                                               <ul class="dropdown-menu" role="menu">

                                                                <li><a href="withdrawstatus.php?orderid=<?php echo $user['orderid']; ?>&userid=<?php echo $userid; ?>&status=approved">approved</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="withdrawstatus.php?orderid=<?php echo $user['orderid']; ?>&userid=<?php echo $userid; ?>&status=Declined&amount=<?php echo $user['amount']; ?>">Declined</a></li>
																<li class="divider"></li>
                                                                <li><a href="withdrawstatus.php?orderid=<?php echo $user['orderid']; ?>&userid=<?php echo $userid; ?>&status=Remove&pid=<?php echo $pid; ?>">Remove</a></li>
                                                            </ul>

                                                <?php }  ?>     
                                                </div>
												
												
												
												
												
												
												 

                                                   
                                               
			  </td>
              
              
             
              </tr>
               <?php } ?>
               </tbody>
              </table>
               
              
              
                 
            
                   
 
                  </div>
                  </div>
 
  

      </section>
                    </div>

                    <!-- /.box -->

                </div>

                <!--/.col (right) -->

            </div>

            <!-- /.row -->

        </section>

        <!-- /.content -->

    </div>

    <!-- /.content-wrapper -->

    <footer class="main-footer">

    <div class="pull-right hidden-xs">

        <b>Version</b> 2.0

    </div>

    <strong>Copyright &copy; 2017-2018 <a href="#">LudoGame</a>.</strong> All rights

    reserved.

</footer>


    <!-- Control Sidebar -->



    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

    <div class="control-sidebar-bg"></div>

</div>

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



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>

<script src="bower_components/ckeditor/ckeditor.js"></script>


<script>

 
$('.date').datepicker({ format: "yyyy-mm-dd" });
 
 $('.date').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
 
    </script>
<script>

   jQuery("#submittrns").on('click',(function(e) {
	  e.preventDefault();
    var touser = $('#touser').val();	
    var fromuser = $('#fromuser').val();
    var toaccount = $('#toaccount').val();
	 var amount = $('#amount').val();
   
   
   if(parseInt(amount) > <?php echo $uinfos['coins']; ?>) {
    /*if it is*/
	alert("You do not have sufficient balance for this transaction");
	exit;
}
   
        $.ajax({
        url: "ajax.php",
        type: "POST",
         data: { fromuser: fromuser , toaccount: toaccount,amount: amount,touser:touser},
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

