<?php
include('config.php');
$user_id = $_POST['user_id'];
$token =  $_POST['token'];
$ver = $_POST['ver'];

if($ver < 11){
	$rows['success'] = 0;
				$rows['message'] = "Token or user id not matched.";
				echo (json_encode($rows));
				exit;
	
}
/*$insertquery =mysqli_query($conn,"update users set refercode='".$user_id."', token='".$token."' WHERE id='1'");
		$insertquery;*/
		
	$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'");
	//$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."' AND token='".$token."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){  
		while($row = mysqli_fetch_assoc($sql)){ 
		
				$rows['success'] = 1;			
			 	$rows['coins'] = $row['coins'];
				$rows['bonus'] = $row['bonus'];
				$rows['points'] = $row['points'];
				$rows['tournament'] = $row['tournament'];
				$rows['firsttime'] = 'NO'; 				
		}		
	
		}else {
				$rows['success'] = 0;
				$rows['message'] = "Token or user id not matched.";
			}
		
	echo (json_encode($rows));
?>