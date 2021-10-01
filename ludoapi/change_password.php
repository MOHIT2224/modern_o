<?php
include('config.php');
$user_id = $_POST['user_id'];
$password =  $_POST['password'];
$mobile =  $_POST['mobile'];


$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
		
		 $insertquery =mysqli_query($conn,"update users set password='".$password."' WHERE id='".$user_id."'");
		$insertquery;
		$rows['message'] = "Password has changed successfully";			    				
				$rows['success'] = 1;
		
		}else {
			
				$rows['success'] = 0;
				$rows['message'] = "Inavlid Details";
			
		}	
		
		
		echo (json_encode($rows));
		exit;	