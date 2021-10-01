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
	if($uid=='1'){
		
		 $limit = 500;  // Number of entries to show in a page. 
    // Look for a GET variable page if not found default is 1.      
    if (isset($_GET["page"])) {  
      $pn  = $_GET["page"];  
    }  
    else {  
      $pn=1;  
    };   
  
    $start_from = ($pn-1) * $limit;   
 


$sqlnew=mysqli_query($conn, "SELECT * FROM users order by id desc LIMIT $start_from, $limit");
$user= mysqli_fetch_assoc($sqlnew);
//print_r($user);*

	 
	} 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manages User's</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
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
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

   <?php include 'header.php'; ?>  
 

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Index
                <!--<small>advanced tables</small>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="admin/welcome/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">advertisment</a></li>
                <li class="active">Index</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!--<button type="button" class="btn btn-info"  name="export" id="export_to_excel" onClick="exportexcel();">Export To Excel</button>-->
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">&nbsp;</h3>
                            <div class="box-tools"><a href="adduser.php" class="btn btn-primary">Add User</a>  
                            
                            <div class="btn-group text-right"><a class="btn btn-default" href="add_coin.php">Transfer Coin</a></div></div>
                                 </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                          <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Coin</th>
                                     <th>Bonus</th>
									 <th>Refercode</th>
                                    <th>Last Login</th>
                                    <th>Registerd</th> 
                                     <th>View </th>
                                    <th>Edit</th>
                                    <th>Delete</th> 

                                </tr>
                                </thead>
                                <tbody>
                                <?php while($user= mysqli_fetch_assoc($sqlnew)) {
 								?>
                                        <tr>
                                            <td><?php echo $user['name']; ?></td>
                                            <td><?php echo $user['mobile']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo $user['coins']; ?></td>
											
                                            <td><?php echo $user['bonus']; ?></td>
											
											<td><a target='_blank' href='http://gaminggaze.com/chkrefer.php?code=<?php echo $user['refercode']; ?>'><?php echo $user['refercode']; ?></a></td>
											
											
                                           <td><?php $daten=  $user['lastlogin']; $date=  $user['registerd'];  $timestamp = strtotime($date);
			  
			  if($daten){			  
											$timestamps = strtotime($daten); 
										   echo   date("d M Y h:i:s A" , $timestamps); }else { echo  $slot_key = date("d M Y h:i:s A" , $timestamp);}?></td>
                                            <td><?php
											
			  
			  


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
											
											  ?></td>
                                              
											  <td> <div class="btn-group text-right"><a class="btn btn-default" href="viewhistory.php?userid=<?php echo $user['id']; ?>">View</a> </div>
											  </td>
                                            <td>
											
                                            <?php if($user['id'] !='1'){ ?>
                                            <div class="btn-group text-right"><a class="btn btn-default" href="edituser.php?userid=<?php echo $user['id']; ?>">Edit</a> </div><?php } ?>
                                            </td>
                                            <td><?php if($user['id'] !='1'){ ?>
                                            <div class="btn-group text-right"><a  class="btn btn-default deleteuser" href="deleteuser.php?userid=<?php echo $user['id']; ?>">Delete</a></div><?php } ?>
                                            </td>
                                        </tr>
<?php } ?>

                                    
                                        

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
						
						
						<ul class="pagination"> 
      <?php   
        $sqla = "SELECT COUNT(*) FROM users";   
        $rs_result = mysqli_query($conn, $sqla);   
        $row = mysqli_fetch_row($rs_result);   
        $total_records = $row[0];   
          
        // Number of pages required. 
        $total_pages = ceil($total_records / $limit);   
        $pagLink = "";                         
        for ($i=1; $i<=$total_pages; $i++) { 
          if ($i==$pn) { 
              $pagLink .= "<li class='active'><a href='list_user.php?page="
                                                .$i."'>".$i."</a></li>"; 
          }             
          else  { 
              $pagLink .= "<li><a href='list_user.php?page=".$i."'> 
                                                ".$i."</a></li>";   
          } 
        };   
        echo $pagLink;   
      ?> 
      </ul>
	  
	  
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->


    </div>

    <footer class="main-footer">

    <div class="pull-right hidden-xs">

        <b>Version</b> 2.0

    </div>

    <strong>Copyright &copy; 2017-2018 <a href="#">LudoGame</a>.</strong> All rights

    reserved.

</footer>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
 
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
</html>
