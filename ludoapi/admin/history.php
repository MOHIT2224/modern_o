<?php
include 'config.php';
include 'functions.php';
ob_start();
$session =session_start();
 $uid = $_SESSION['uid'];
 
   if($uid==''){
	echo "<script type='text/javascript'>window.location.href = ' index.php';</script>";
	exit;
	}
?>
<!DOCTYPE html>

<html>



<!-- Mirrored from adminlte.io/themes/AdminLTE/pages/forms/general.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 23 Jan 2018 05:23:40 GMT -->

<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Ludo Money | Add User</title>

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
      
      
      <div class="box box-info">
        
          
    
    
           
      
          
      
      </div>
     
      <div class="box-body">
              <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>
              <th>Trans No.</th>
                <th> User</th>
                 <th>Amount</th>
                <th> Date and time</th>
              <th> Table</th>
              <th>Game Id</th>  
             <th>Game Status</th>  
              </tr>
              </thead>
                <tbody>
              <?php 
			  
			   $limit = 500;
			   $start_from =0;
			   if($uid=='1'){
			   $touserinfo=mysqli_query($conn, "select * from  gamebet ORDER BY id DESC LIMIT $start_from, $limit");
			   }

 ?>
           <?php while($user = mysqli_fetch_assoc($touserinfo)) { 
		   $userid = $user['userid'];
		    $uinfo=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");			
			$userinfo = mysqli_fetch_assoc($uinfo);
		    ?>
           
              <tr>
              <td><?php echo $user['id']; ?></td>
              <td> <a href="viewhistory.php?userid=<?php echo $userid; ?>"><?php echo $userinfo['name']; ?> - <?php echo $userinfo['mobile']; ?></a></td> 
               <td><?php echo $user['amount']; ?></td>  
              <td><?php 
			$date=  $user['gtime'];
			  
			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
			  
			  
			    ?></td>
              <td><?php $table = $user['tabletype']; if($table=='1'){ echo '2 Player'; } if($table=='3'){ echo 'Private Table'; } if($table=='2'){ echo '4 Player'; } ?></td>                                  
              <td><?php echo $user['gameid']; ?></td>
              <td><?php echo $user['losewin']; ?></td>
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



<script src="bower_components/ckeditor/ckeditor.js"></script>
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

