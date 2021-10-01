<?php
include('config.php');
$mobile = $_POST['mobile'];
$userid = $_POST['userid'];

 $sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
		
		
		$insertquery =mysqli_query($conn,"update users set mobile='".$mobile."' WHERE id='".$userid."'");
		$insertquery;
		 
		$rows['success'] = 1;
		$rows['message'] = "Mobile number has been changed successfully.";
		$rows['data']['mobile'] = $mobile; 
		
		
		}else {
			
		$rows['success'] = 0;
		$rows['message'] = "Something went wrong please try again later.";
			
		}
		
		echo (json_encode($rows));
?>
		