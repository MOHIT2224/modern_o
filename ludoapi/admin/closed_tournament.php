<!DOCTYPE html>
<?php
include('config.php');


$sql = "SELECT * FROM tbl_tournament WHERE tournament_status = 2";
$result = $conn->query($sql);
$i=0;
while($row = $result->fetch_assoc()) {
        $list[$i]=$row;
		$i=$i+1;
    }

?>





<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Closed Tournament</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Data-Table-1.css">
    <link rel="stylesheet" href="assets/css/Data-Table.css">
    <link rel="stylesheet" href="assets/css/Features-Boxed.css">
    <link rel="stylesheet" href="assets/css/Fern-Login-Form.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search.css">
    <link rel="stylesheet" href="assets/css/PHP-Contact-Form-dark-1.css">
    <link rel="stylesheet" href="assets/css/PHP-Contact-Form-dark.css">
    <link rel="stylesheet" href="assets/css/Pretty-Registration-Form.css">
    <link rel="stylesheet" href="assets/css/Pretty-Search-Form.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu-1.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"> <a class="text-left text-primary" href="welcome.php">Home </a></li>
                <li> <a href="user.php">User</a></li>
                <li> <a href="s_transaction.php">Transaction Successful<br></a></li>
                <li> <a href="c_transaction.php">Transaction Cancel<br></a></li>
                <li> <a href="p_transaction.php">Transaction Pending<br></a></li>
                <li> <a href="withdrawal_request.php">Withdrawal Request<br></a></li>
                <li> <a href="withdrawal_success.php">Withdrawal Successful<br></a></li>
                <li> <a href="withdrawal_cancelled.php">Withdrawal Cancel<br></a></li>
                <li> <a href="settings.php">Settings<br></a></li>
                <li> <a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <div class="container-fluid"><a class="btn btn-link" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a>
                <h1 class="text-center">Closed Tournament</h1>
                <div class="table-responsive table-bordered">
                    <table class="table table-striped table-bordered table-sm">
                        <thead class="text-center">
                            <tr class="text-center bg-primary border rounded-0">
                                <th>No</th>
                                <th>Tournament Name</th>
                                <th>Tournament ID</th>
                                <th>Details</th>
                                <th>Delete</th>
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
                                <td><a href="score.php?t_id=<?php echo $list[$j]["tournament_id"];?>">Click<br></td>
                                <td><a href="delete.php?t_id=<?php echo $list[$j]["tournament_id"];?>">Delete<br></td>
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/PHP-Contact-Form-dark-1.js"></script>
    <script src="assets/js/PHP-Contact-Form-dark.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>

</html>