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
	
  $userid = $_GET['kycid'];
  $getwayto = $_GET['getway'];
 $sql=mysqli_query($conn, "SELECT * FROM kyc WHERE user_id='".$userid."'");
	  
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Ludo Money | KYC</title>

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

             KYC
            </h1>

            <ol class="breadcrumb">

                <li><a href="http://appdroidsolutions.com/ludomoney/admin/Welcome/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>

                <li><a href="#">User</a></li>

                <li class="active">KYC</li>

            </ol>

        </section>



        <!-- Main content -->

        <section class="content">

            <div class="row">

                <!-- right column -->

                <div class="col-md-12">

                    <!-- Horizontal Form -->

                    <div class="box box-info">

                        <div class="box-header with-border">

                            <h3 class="box-title">KYC </h3>

                            <div class="box-tools"><a href="list_user.php" class="btn btn-primary">User View</a></div>

                        </div>

                        <!-- /.box-header -->

                        <!-- form start -->
						
						

                        
                    

                        
                        <div class="form-horizontal">

                            <div class="box-body">
							
							
							
							
						
							  
              <div class="table-responsive">	
			  <?php if($getwayto && $userid){ ?>
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <?php $user = mysqli_fetch_assoc($sql);?>
 
              <tr > <th>Name</th> <td><?php echo $user['name']; ?></td> </tr>
			   <tr <?php if($getwayto=='bank'){ ?> class="btn-info" <?php }?>> <th>Bank  Name</th> <td><?php echo $user['bankname']; ?></td> </tr>
			   <tr> <th>Account Number</th> <td><?php echo $user['account']; ?></td> </tr>
			   <tr> <th>IFSC</th> <td><?php echo $user['ifsc']; ?></td> </tr>
			    <tr> <th>Pan Number</th> <td><?php echo $user['panno']; ?></td> </tr>
				
			 <tr <?php if($getwayto=='paypal'){ ?> class="btn-info" <?php }?>> <th>Paypal</th> <td><?php echo $user['paypal']; ?></td> </tr>	
			   
			   
                </table> <?php } else { ?>
 
 <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>
              <th>User</th>  <th> Name</th>
              <th> Bank Name</th>
              <th>Account</th>
              <th>IFSC</th>
              <th>PAN No.</th>              
              <th> Paypall</th>
              <th> Date </th>
                 
             
              </tr>
              </thead>
                <tbody>
				  <?php 
			   $kycinfo=mysqli_query($conn, "select * from kyc order by kid DESC");
 ?>
           <?php while($kycin = mysqli_fetch_assoc($kycinfo)) {  
		   $userid = $kycin['user_id'];
		    $uinfo=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");			
			$userinfo = mysqli_fetch_assoc($uinfo);
		   ?>
           
              <tr>
              <td><?php echo $userinfo['name']; ?> - <?php echo $userinfo['mobile']; ?></td>
              <td><?php echo $kycin['name']; ?></td>
              <td><?php echo $kycin['bankname']; ?></td>
			  <td><?php echo $kycin['account']; ?></td>
              <td><?php echo $kycin['ifsc']; ?></td>
			   <td><?php echo $kycin['panno']; ?></td>
			  <td><?php echo $kycin['paypal']; ?></td>
              <td><?php $date = $kycin['datetime'];

 $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y " , $timestamp);
			  ?></td>
			  
					</tr>		
							 <?php }  ?>   
							   </tbody>
</table>							 
							<?php }?>
							</div>

                            <!-- /.box-body -->

                        </div>

                            <div class="box-footer">

                            
                                <button type="submit" onclick="goBack()" id="submittrns"  class="btn btn-info">Back</button>

                          
                            </div>
<script>
function goBack() {
  window.history.back();
}
</script>
                            <!-- /.box-footer -->

                    
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

