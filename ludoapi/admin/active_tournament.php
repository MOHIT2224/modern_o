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
  $useridnew = $_GET['userid'];
 $sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	  
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

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

        <section class="content-header">

            <h1> Active Tournament </h1>

            <ol class="breadcrumb">

                <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li><a href="#">User</a></li>

                <li class="active">Active Tournament</li>

            </ol>

        </section>



        <!-- Main content -->

        <section class="content">

            <div class="row">

                <!-- right column -->

       
			
			
			
			   <div class="col-md-12">

                    <!-- Horizontal Form -->

                   

                    <!-- /.box -->
					
			 <h1 class="text-center">Active Tournament</h1>
                <div class="table-responsive table-bordered">
                    <table class="table table-striped table-bordered table-sm">
                        <thead class="text-center">
                            <tr class="text-center bg-primary border rounded-0">
                                <th>No</th>
                                <th>Tournament Name</th>
                                <th>Tournament ID</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
						
						
						<?php 
						$sql = "SELECT * FROM tbl_tournament WHERE tournament_status = 5 OR tournament_status = 0";
 $result = mysqli_query($conn, $sql);
 
 while($row = mysqli_fetch_assoc($result)) {   
         
     
						?>
						
						
                            <tr class="border rounded-0">

                                <td><?php echo $row["tournament_id"]; ?></td>
                                <td><?php echo $row["tournament_name"];?><br></td>
                                <td class="text-nowrap"><?php echo $row["tournament_id"];?><br></td>
                                <td><a href="active_tournament_details.php?t_id=<?php echo $row["tournament_id"];?>">Click</a><br></td>
                            </tr>
							<?php
							}
							?> 
							
                        </tbody>
                    </table>
                </div>
			
			
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


<script>

   jQuery("#submittrns").on('click',(function(e) {
	   alert();
	  e.preventDefault();
    var name = $('#name').val();	
    var email = $('#Email').val();
    var mobile = $('#Phone').val();
	 var password = $('#password').val();
	  var insert = $('#insert').val();
	 var userid = $('#userid').val();
   
        $.ajax({
        url: "ajax.php",
        type: "POST",
         data: { name: name , email: email,mobile: mobile,insert: insert,userid: userid,password:password},
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

 