<!DOCTYPE html>

<?php
include('config.php');

$t_id=$_GET['t_id'];
$sql = "SELECT * FROM tbl_tournament WHERE tournament_id = '".$t_id."'";



$result = $conn->query($sql);
$i=0;
while($row = $result->fetch_assoc()) {
        $list[$i]=$row;
		$i=$i+1;
    }


$tbl=$t_id."_player";
$sql1 = "SELECT * FROM ".$tbl." ORDER BY  total_score DESC";

$result1 = $conn->query($sql1);
$k=0;
while($row1 = $result1->fetch_assoc()) {
        $list1[$k]=$row1;
		$k=$k+1;
    }

?>





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

<body>
     
     
         <div class="wrapper">

   <?php include 'header.php'; ?>
   
           <div class="content-wrapper" style="min-height: 837px;">
            <div class="container-fluid"><a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a>
                <h1 class="text-center" style="margin-bottom: 20px;">Active Tournament Details</h1>
                <div class="table-responsive table-bordered">
                    <table class="table table-striped table-bordered table-sm">
                        <thead class="text-center">
                            <tr class="text-center bg-primary border rounded-0">
                                <th>No</th>
                                <th>Tournament Name</th>
                                <th>Tournament ID</th>
                                <th>No of match</th>
                                <th>Team size</th>
                                <th>Entry coin</th>
                                <th>Joining last date</th>
                                <th>Total prize</th>
                                <th>Starting date</th>
                                <th>Closed date</th>
                                <th>Details</th>
                                <th>Type</th>
                                <th>Total entry</th>
                                <th>Joining status</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
						
						<?php 
					    for($j=0;$j<=$i-1;$j++){
						?>
						
						
						
                            <tr class="border rounded-0">
                                <td><?php echo $j+1; ?></td>
                                <td><?php echo $list[$j]["tournament_name"];?><br></td>
                                <td class="text-nowrap"><?php echo $list[$j]["tournament_id"];?><br></td>
                                <td><?php echo $list[$j]["no_of_match"];?><br></td>
                                <td><?php echo $list[$j]["team_size"];?><br></td>
                                <td><?php echo $list[$j]["entry_coin"];?><br></td>
                                <td><?php echo $list[$j]["joining_last_date"];?><br></td>
                                <td><?php echo $list[$j]["total_prize"];?><br></td>
                                <td><?php echo $list[$j]["starting_date"];?><br></td>
                                <td><?php echo $list[$j]["closed_date"];?><br></td>
                                <td><?php echo $list[$j]["details"];?><br></td>
                                <td><?php echo $list[$j]["type"];?><br></td>
                                <td><?php echo $list[$j]["total_entry"];?><br></td>
								
								           <?php
											  $j_status;
											  if($list[$j]["tournament_status"]=="1"){
												  $j_status="CLOSED";
											  }
											  else{
												  $j_status="OPEN";
											  }
											  ?>
											  
							    <td><?php echo $j_status; ?><br></td>
                            </tr>
							
							<?php
							}
							?> 
							
					</tbody>
                    </table>
                </div><button class="btn btn-primary text-justify" type="button" style="margin-right: 5px;margin-top: 10px;background-color: rgb(225,32,32);" onclick="window.location.href='create_users.php?t_id=<?php echo $t_id;?>'">Create User</button><button class="btn btn-primary" type="button" style="margin-top: 10px;margin-right: 5px;margin-left: 10px;background-color: rgb(225,32,32);" onclick="window.location.href='closed_button.php?t_id=<?php echo $t_id;?>'">Closed</button>
                <button
                    class="btn btn-primary" type="button" style="margin-top: 10px;margin-right: 5px;margin-left: 10px;background-color: rgb(225,32,32);" onclick="window.location.href='join_closed_button.php?t_id=<?php echo $t_id;?>'">Joining Closed</button><button class="btn btn-primary" type="button" style="margin-top: 10px;margin-right: 5px;margin-left: 10px;background-color: rgb(225,32,32);" onclick="window.location.href='Increment_form.php?t_id=<?php echo $t_id;?>'">Increment</button>
                    <h1 class="text-center" style="margin-top: 40px;margin-bottom: 20px;">Tournament User Details</h1>
                    <div class="table-responsive table-bordered">
                        <table class="table table-striped table-bordered table-sm">
                            <thead class="text-center">
                                <tr class="text-center bg-primary border rounded-0">
                                    <th>No</th>
                                    <th>User id</th>
                                    <th>User name</th>
                                    <th>Total match</th>
                                    <th>Total score</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
							
							<?php 
							for($m=0;$m<=$k-1;$m++){
							?>
							
							
                                <tr class="border rounded-0">
                                    <td><?php echo $m+1; ?></td>
                                    <td><?php echo $list1[$m]["user_id"];?><br></td>
                                    <td class="text-nowrap"><?php echo $list1[$m]["user_name"];?><br></td>
                                    <td><?php echo $list1[$m]["total_match"];?><br></td>
                                    <td><?php echo $list1[$m]["total_score"];?><br></td>
                                </tr>
								
								<?php
								}
								?> 
								
								
								
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
   <!-- jQuery 3 -->

<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->

<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- FastClick -->

<script src="bower_components/fastclick/lib/fastclick.js"></script>

<!-- AdminLTE App -->

<script src="dist/js/adminlte.min.js"></script>

<script src="dist/js/demo.js"></script>
</body>

</html>