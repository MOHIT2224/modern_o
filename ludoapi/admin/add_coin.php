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

<style> .box-info h3 {
    text-align: center;
    color: #868585;
}
.box-info h3 span {
    font-weight: 800;
    color: #00a65a;
} </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

<?php include 'header.php'; ?>


    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper" style="min-height: 837px;">

        <!-- Content Header (Page header) -->
		
		
		

        <section class="content-header">

            <h1>

              Tranfer Coins

            </h1>

            <ol class="breadcrumb">

                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li><a href="#">User</a></li>

                <li class="active">Tranfer Coins</li>

            </ol>

        </section>



        <!-- Main content -->

        <section class="content">

            <div class="row">

                <!-- right column -->

                <div class="col-md-12">

                    <!-- Horizontal Form -->

                    <div class="box box-info" style="
    min-height: 120px;
">

<?php 
 $totald=mysqli_query($conn, "SELECT sum(amount) as desposite FROM payment WHERE trnstype='desposite' AND status='Success'");
 $totaldopsit = mysqli_fetch_assoc($totald);
 $desposite=$totaldopsit['desposite'];
 
  $totaldirct=mysqli_query($conn, "SELECT sum(amount) as directdep FROM trans WHERE trntype='Coins Transfer from' AND crdr='CR'");
 $totaldirct = mysqli_fetch_assoc($totaldirct);
 $directdep=$totaldirct['directdep'];
 
 
 $totalwid=mysqli_query($conn, "SELECT sum(amount) as withdrawal FROM payment WHERE trnstype='withdrawal' AND status='Success'");
 $totalwidtral = mysqli_fetch_assoc($totalwid);
 $withdrawal=$totalwidtral['withdrawal']; 
 
 
 $totalavl=mysqli_query($conn, "SELECT sum(coins) as total FROM users WHERE role='0'");
 $totalavl = mysqli_fetch_assoc($totalavl);
 $Available=$totalavl['total']; 
 
 
 ?>

            <div class="col-sm-3">     <h3>  Withdrawal   <br><span> <?php echo $withdrawal; ?></span> </h2> </div>
			<div class="col-sm-3">	<h3> Desposite  <br><span>  O <?php echo $desposite; ?>, D <?php echo $directdep; ?></span> </h2> </div>
			<div class="col-sm-3">	<h3>  Available   <br><span> <?php echo $Available; ?></span> </h2> </div>
			<div class="col-sm-3">	<h3>  Profit & loss  <br><span> <?php echo $desposite + $directdep - $Available; ?></span> </h2> </div>
                        <!-- /.box-header -->

                        <!-- form start -->

                  </div>      
                            <section class="content">
      <!-- Info boxes -->
      
      
      <div class="box box-info">
        
          
    
    
           
      <form class="form-horizontal">
      <div class="box-body">
      <div class="form-group">
      
      
    <div class="col-sm-3">
                    <label for="fromuser" class="control-label">From User</label>
                    <select id="fromuser" name="fromuser" class="form-control select2 selectpicker2">
                                    	
                    <option value="<?php echo $uid; ?>"> <?php echo $uinfos['name']; ?> C - <?php echo $uinfos['coins']; ?> , B- <?php echo $uinfos['bonus']; ?> P- <?php echo $uinfos['points']; ?> </option>
                    
                    <?php while($user1 = mysqli_fetch_assoc($sql)) {  ?>
                      <option data-tokens="<?php echo $user1['email']; ?> <?php echo $user1['mobile']; ?>" value="<?php echo $user1['id']; ?>"> <?php echo $user1['name']; ?> <?php echo $user1['email']; ?> <?php echo $user1['mobile']; ?> C - <?php echo $user1['coins']; ?> , B- <?php echo $user1['bonus']; ?> P- <?php echo $uinfos['points']; ?></option>
                      
                      <?php } ?>
                      
                       </select>
                    
                  </div>  
                  
                  
                
                  <div class="col-sm-3">
                    <label for="touser" class="control-label">Tranfer To</label>
                    <select id="touser" name="fromuser" class="form-control select2 selectpicker" data-live-search="true">

					 <option value="<?php echo $uid; ?>"> <?php echo $uinfos['name']; ?> C - <?php echo $uinfos['coins']; ?> , B- <?php echo $uinfos['bonus']; ?> P- <?php echo $uinfos['points']; ?></option>
  					<?php while($user = mysqli_fetch_assoc($sqllist)) {  ?>
                      <option data-tokens="<?php echo $user['email']; ?> <?php echo $user['mobile']; ?>" value="<?php echo $user['id']; ?>"> <?php echo $user['name']; ?> <?php echo $user['email']; ?> <?php echo $user['mobile']; ?> C - <?php echo $user['coins']; ?> , B- <?php echo $user['bonus']; ?> P- <?php echo $uinfos['points']; ?></option>
                      
                      <?php } ?>
                                        </select>
                    
                  </div> 
                   <div class="col-sm-2"> 
                   <label for="toaccount" class="control-label">Select Account Type</label>
                    <select id="toaccount" name="toaccount" class="form-control select2">
                    <option value="1"> Main Account</option>
                    <option value="2"> Bonus Account</option>
					<option value="3"> Ponits Account</option>
                    </select>
                   </div>
                     
                  <div class="col-sm-2"> 
                  <label for="amount" class="control-label">Amount</label>                
                   <input id="amount" name="amount" type="text" placeholder="Enter Amount" class="form-control">                   
                  </div>
                  
                  <div class="col-sm-2"> 
                  <label for="amout" class="control-label col-sm-12" style="visibility:hidden">Amount</label>    
                  <input id="submittrns" type="submit" class="btn bg-green btn-primery" value="Transfer Now">
                  
                  </div>
                  
                                   
                   </div>
              
                     
                
      
      </div>
      
      </form>
          
      
      </div>
     
      <div class="box-body">
              <div class="table-responsive">
             <table id="example1" class="table table-bordered table-striped  table-hover">
               <thead>
              <tr>
              <th>Trans No.</th>
              <th> Type</th>
              <th>Amount</th>
              <th> Updated Balance</th>
              <th>Closing Balance</th>              
              <th> CR / DR</th>
              <th> IP Address</th>
              <th> Date and time</th>             
             
              </tr>
              </thead>
                <tbody>
              <?php 
			   $touserinfo=mysqli_query($conn, "select * from trans where uid='$uid' and ipaddress IS NOT NULL order by tid DESC");
 ?>
           <?php while($user = mysqli_fetch_assoc($touserinfo)) {  ?>
           
              <tr>
              <td><?php echo $user['tid']; ?></td>
              <td><?php echo $user['trntype']; ?></td>
              <td><?php echo $user['amount']; ?></td>
              <td><?php echo $user['updatedcoins']; ?></td>
              <td><?php echo $user['closingcoins']; ?></td>              
              <td><?php echo $user['crdr']; ?></td>
              <td><?php echo $user['ipaddress']; ?></td>
              <td> 
              
              <?php
											$date=  $user['datetime'];
			  
			   $timestamp = strtotime($date);


 echo  $slot_key = date("d M Y h:i:s A" , $timestamp);
											
											  ?></td>
              
             
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<script>

 $(function() {
  $('.selectpicker').selectpicker();
  $('.selectpicker2').selectpicker();
});

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

