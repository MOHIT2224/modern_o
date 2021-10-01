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
  $mobile = $_GET['mobile'];
 $sql=mysqli_query($conn, "SELECT * FROM users WHERE (id='".$useridnew."' OR  mobile='".$mobile."')");
 $usersss = mysqli_fetch_assoc($sql);
 if($usersss!=''){
	 $useridnew = $usersss['id'];
	 
 }
 //echo "SELECT * FROM users WHERE (id='".$userid."' OR  mobile='".$mobile."')";
  
 
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Ludo  | Add User</title>

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

            <h1> User History </h1> 
            <ol class="breadcrumb">

                <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li><a href="#">User</a></li>

                <li class="active">History</li>

            </ol>

        </section>







        <!-- Main content -->

        <section class="content">

            <div class="row">
			<div class="box-body table-responsive">
			
			 <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                <tr>
								<th>Customer id</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                   
                                    <th>Coin</th>
                                     <th>Bonus</th>
									 <th>Refercode</th>
                                    <th>Last Login</th>
                                    <th>Registerd</th> 
                                  
                                    <th>Edit</th>
                                    <th>Delete</th> 

                                </tr>
                                </thead>
                                <tbody>
                               
                                        <tr>
										<td><?php echo $usersss['id']; ?></td>
                                            <td><?php echo $usersss['name']; ?></td>
                                            <td><?php echo $usersss['mobile']; ?></td>
                                           
                                            <td><?php echo $usersss['coins']; ?></td>
											
                                            <td><?php echo $usersss['bonus']; ?></td>
											
											<td><a target='_blank' href='http://gaminggaze.com/chkrefer.php?code=<?php echo $usersss['refercode']; ?>'><?php echo $usersss['refercode']; ?></a></td>
											
											
                                           <td><?php $daten=  $usersss['lastlogin']; $date=  $usersss['registerd'];  $timestamp = strtotime($date);
			  
			  if($daten){			  
											$timestamps = strtotime($daten); 
										   echo   date("d M Y h:i:s A" , $timestamps); }else { echo  $slot_key = date("d M Y h:i:s A" , $timestamp);}?></td>
                                            <td><?php
											
			  
			  


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
											
											  ?></td>
                                              
											 
                                            <td>
											
                                            <?php if($usersss['id'] !='1'){ ?>
                                            <div class="btn-group text-right"><a class="btn btn-default" href="edituser.php?userid=<?php echo $usersss['id']; ?>">Edit</a> </div><?php } ?>
                                            </td>
                                            <td><?php if($usersss['id'] !='1'){ ?>
                                            <div class="btn-group text-right"><a  class="btn btn-default deleteuser" href="deleteuser.php?userid=<?php echo $usersss['id']; ?>">Delete</a></div><?php } ?>
                                            </td>
 

                                    
                                        

                                                                    </tbody>
                                <!-- <tfoot>
                                 <tr>
                                   <th>Rendering engine</th>
                                   <th>Browser</th>
                                   <th>Platform(s)</th>
                                   <th>Engine version</th>
                                   <th>CSS grade</th>
                                 </tr>
                                 </tfoot>-->
                            </table>
                        </div>
						
						
						
						<div class="col-md-12">
						
						
						 <?php 	 
						 
	$refer=mysqli_query($conn, " SELECT  users.id, users.name, users.mobile, refered.rcode  FROM `refered` INNER JOIN users ON users.refercode = refered.rcode and refered.uid='".$useridnew."'");
 $refered = mysqli_fetch_assoc($refer); 

 
$wins=mysqli_query($conn, "SELECT  count(*) as count, sum(amount) as loos, sum(winmount) as total  FROM `gamebet` where userid='".$useridnew."' ");
 $totalwin = mysqli_fetch_assoc($wins); 
 
 $wincount=mysqli_query($conn, "SELECT  count(*) as count  FROM `gamebet` where userid='".$useridnew."' and losewin='winner'");
 $cot = mysqli_fetch_assoc($wincount); 
  
  $payment=mysqli_query($conn, "SELECT  count(*) as count , sum(amount) as deposite FROM `payment` where uid='".$useridnew."' and trnstype='desposite' and status='Success'");
 $pay = mysqli_fetch_assoc($payment); 
 

 ?>
 <h3>
 Referd By <?php echo $refered['name'].' -'.$refered['mobile']; ?> <br>
 Desposite -<?php echo  $pay['count'];?> ,
  Total Amount  -<?php echo  $pay['deposite'];?> ,
 Games -<?php echo  $totalwin['count'];?> ,   Winner = <?php echo  $cot['count'];?> , 
 Total Wining =<?php echo  $totalwin['total'];?> ,  Total Bet =<?php echo $totalwin['loos'];?>  


 </h3>
 	
						
						
						<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Payment</a></li>
  <li><a data-toggle="tab" href="#menu1">Game History</a></li>
  <li><a data-toggle="tab" href="#menu2">Refered</a></li>
  <li><a data-toggle="tab" href="#menu3"> Bot</a></li>
  <li><a data-toggle="tab" href="#menu4"> Real</a></li>
   <li><a data-toggle="tab" href="#menu5"> 2 Player</a></li>
   <li><a data-toggle="tab" href="#menu6"> 4 Player</a></li>
    <li><a data-toggle="tab" href="#menu7"> Private </a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
     
    
			   <div class="col-md-12">

                    <!-- Horizontal Form -->

                    <div class="box box-info">

                        <div class="box-header with-border">

                            <h3 class="box-title">User Payment History </h3> 

                        </div>

                      
                    </div>

                    <!-- /.box -->
					
				  <div class="table-responsive">
				    <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>
            
              <th> Order Id</th>
              <th>Amount</th>
			  <th>Withdrawal/ Deposit</th>
              <th>Request Date</th>
              <th>Trns Into</th>              
              <th> Status</th>
              <th>  </th>
                 
             
              </tr>
              </thead>
                <tbody>
              <?php 
			   $touserinfo=mysqli_query($conn, "select * from payment where uid='".$useridnew."' order by pid DESC");
 ?>
           <?php while($user = mysqli_fetch_assoc($touserinfo)) {  
		   $userid = $user['uid'];
		    $pid = $user['pid'];
		  
		   ?>
           
              <tr>
               
              <td><?php echo $user['orderid']; ?></td>
              <td><?php echo $user['amount']; ?></td>
			  
			  <td><?php if($user['trnstype']=='withdrawal'){ echo 'withdrawal';} else { echo 'Deposit'; } ?></td>
			  
			  
               <td> 
              
              <?php
											$date=  $user['datetime'];
			  
			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
											
											  ?></td>
              <td><a href="kyc.php?kycid=<?php echo $userid; ?>&getway=<?php echo $user['getway']; ?>"><?php echo $user['getway']; ?></a></td>              
              <td><?php echo $status = $user['status']; ?></td>
			  
              <td> 
			  <?php if($user['trnstype']=='withdrawal'){ ?>
			  <div class="btn-group">

			  <?php if( $status=='approved'){ ?>
 <button type="button" class="btn btn-success">Completed</button>
			  <?php } else { ?>                                                    
			  <button type="button" class="btn btn-warning">Pending</button>
                                                                                                      
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
												
												   <?php }  ?> 
												
												
												
												
												 

                                                   
                                               
			  </td>
              
              
             
              </tr>
               <?php } ?>
               </tbody>
              </table>
               
				  
				  </div>
			
			
			  </div>
  </div>
  <div id="menu1" class="tab-pane fade">
   
    <!-- right column -->

                <div class="col-md-12">

                    <!-- Horizontal Form -->

                    <div class="box box-info">

                        <div class="box-header with-border">

                            <h3 class="box-title">User Game History </h3> 
							
                        </div>

                      
                      </div>

                    <!-- /.box -->
					
					
					            
	
				  <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>
              
               
                 <th>Amount</th>
                <th> Date and time</th>
              <th> Table</th>
               <th> Win / Lose</th>
              <th> Gameid</th>
              </tr>
              </thead>
                <tbody>
              <?php 
			  
			   
			   $touserinfo=mysqli_query($conn, "select * from gamebet where userid='".$useridnew."' ORDER BY id DESC");
			   

 ?>
           <?php while($user = mysqli_fetch_assoc($touserinfo)) { 
		 
		    ?>
           
              <tr>
            
          
               <td><?php echo $user['amount']; ?></td>  
              <td><?php 
			$date=  $user['gtime'];

			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
			  
			  
			    ?></td>
              <td><?php $table = $user['tabletype']; if($table=='1'){ echo '2 Player'; } if($table=='3'){ echo 'Private Table'; } if($table=='2'){ echo '4 Player'; } ?></td>                                  
               
              <td><?php $losewin = $user['losewin']; 
			  if($losewin=='winner'){ echo 'Winner';} else { echo 'Loser';} ?></td>
			  
			   <td><?php echo $user['gameid']; ?></td>
			  
              </tr>
               <?php } ?>
               </tbody>
              </table>
               
              
              
                 
            
                   
 
                  </div>

                <!--/.col (right) -->

            </div>
  </div>
  <div id="menu2" class="tab-pane fade">
    
	<?php $code=$usersss['refercode'];
 $sql=mysqli_query($conn, "SELECT * FROM refered WHERE rcode='".$code."' order by status desc");
 $count = mysqli_num_rows($sql);
 echo '<h2>Total  '; echo $count;
  echo ' |---| ';
  
   $sql3=mysqli_query($conn, "SELECT * FROM refered WHERE rcode='".$code."' and status='1'");
 $counts = mysqli_num_rows($sql3);
 echo ' Done  '; echo $counts;
  echo '</h2>';
  
  
  
  ?>
  
  
  <div class="box-body table-responsive">
                          <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Referdate</th>
                                    <th>Refered</th>                                   

                                </tr>
                                </thead>
                                <tbody> 
								<?php
while($rows = mysqli_fetch_assoc($sql)) {  
$userid = $rows['uid'];
$sqls=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
$row = mysqli_fetch_assoc($sqls);
?>
<tr>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['mobile']; ?></td>
                                            <td><?php echo $rows['referdate']; ?></td>
                                            <td><?php if($rows['status']=='0'){ echo 'Not deposite yet';} else { echo 'Refered Done !';} ?></td>
											</tr>
<?php
 
}

?>
</tbody>
                                <!-- <tfoot>
                                 <tr>
                                   <th>Rendering engine</th>
                                   <th>Browser</th>
                                   <th>Platform(s)</th>
                                   <th>Engine version</th>
                                   <th>CSS grade</th>
                                 </tr>
                                 </tfoot>-->
                            </table>
  </div>
</div>

 <div id="menu3" class="tab-pane fade">
 </div>

 <div id="menu4" class="tab-pane fade">
 </div>
 
 <div id="menu5" class="tab-pane fade">
              <?php 		  
			   
			   $touserinfo=mysqli_query($conn, "select * from gamebet where userid='".$useridnew."' and tabletype='1' ORDER BY id DESC");
 $counst = mysqli_num_rows($touserinfo);			   
 
  

 ?>

 	  <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>             
               
                 <th>Amount</th>
                <th> Date and time</th>             
               <th> Win / Lose</th>
              <th> Gameid</th>
              </tr>
              </thead>
                <tbody>
 
           <?php while($user = mysqli_fetch_assoc($touserinfo)) { 		 
		    ?>
           
              <tr>          
          
               <td><?php echo $user['amount']; ?></td>  
              <td><?php 
			$date=  $user['gtime'];

			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
			  
			  
			    ?></td>
                                           
               
              <td><?php $losewin = $user['losewin']; 
			  if($losewin=='winner'){ echo 'Winner';} else { echo 'Loser';} ?></td>
			  
			   <td><?php echo $user['gameid']; ?></td>
			  
              </tr>
               <?php } ?>
               </tbody>
              </table> 
			  </div>
 
 </div>
 
  <div id="menu6" class="tab-pane fade">
  
  <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>             
               
                 <th>Amount</th>
                <th> Date and time</th>             
               <th> Win / Lose</th>
              <th> Gameid</th>
              </tr>
              </thead>
                <tbody>
              <?php 		  
			   
			   $touserinfo=mysqli_query($conn, "select * from gamebet where userid='".$useridnew."' and tabletype='2' ORDER BY id DESC");			   

 ?>
           <?php while($user = mysqli_fetch_assoc($touserinfo)) { 		 
		    ?>
           
              <tr>          
          
               <td><?php echo $user['amount']; ?></td>  
              <td><?php 
			$date=  $user['gtime'];

			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
			  
			  
			    ?></td>
                                           
               
              <td><?php $losewin = $user['losewin']; 
			  if($losewin=='winner'){ echo 'Winner';} else { echo 'Loser';} ?></td>
			  
			   <td><?php echo $user['gameid']; ?></td>
			  
              </tr>
               <?php } ?>
               </tbody>
              </table> 
			  </div>
 </div>
 
 
  <div id="menu7" class="tab-pane fade">
  
  <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>             
               
                 <th>Amount</th>
                <th> Date and time</th>             
               <th> Win / Lose</th>
              <th> Gameid</th>
              </tr>
              </thead>
                <tbody>
              <?php 		  
			   
			   $touserinfo=mysqli_query($conn, "select * from gamebet where userid='".$useridnew."' and tabletype='3' ORDER BY id DESC");			   

 ?>
           <?php while($user = mysqli_fetch_assoc($touserinfo)) { 		 
		    ?>
           
              <tr>          
          
               <td><?php echo $user['amount']; ?></td>  
              <td><?php 
			$date=  $user['gtime'];

			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
			  
			  
			    ?></td>
                                           
               
              <td><?php $losewin = $user['losewin']; 
			  if($losewin=='winner'){ echo 'Winner';} else { echo 'Loser';} ?></td>
			  
			   <td><?php echo $user['gameid']; ?></td>
			  
              </tr>
               <?php } ?>
               </tbody>
              </table> 
			  </div>
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

    <script type="text/javascript">
       
		jQuery(".deleteuser").on('click',(function(e) {
var checkstr =  confirm('are you sure you want to delete this?');
if(checkstr == true){
  // do your code
}else{
e.preventDefault();
}
}));


$(document).ready(function() {
    var table = $('#example').DataTable( {
        responsive: true,
		"pageLength": 50
    } );
 
    new $.fn.dataTable.FixedHeader( table );
} );
 
    </script>
</body>



<!-- Mirrored from adminlte.io/themes/AdminLTE/pages/forms/general.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 23 Jan 2018 05:23:40 GMT -->

</html>

