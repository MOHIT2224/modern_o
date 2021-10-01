<?php
include('config.php');
$uid = $_POST['uid'];

$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$uid."'");
	$count = mysqli_num_rows($sql);
	  if($count > 0){ 
		
		 
		$insertquery =mysqli_query($conn,"update users set status='1' WHERE id='".$uid."'");
		$insertquery;
		
				$rows['success'] = 1;
				$rows['message'] = "User Details"; 
	  } else {
		  
		  $rows['success'] = 0;
				$rows['message'] = "Credential Not Match"; 
	  }
	  
	  echo (json_encode($rows));