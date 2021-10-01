<?php
include('config.php');

$user_id = $_POST['user_id'];
		
	$sql=mysqli_query($conn, "SELECT * FROM kyc WHERE user_id='".$user_id."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
  $i=0;
		while($row = mysqli_fetch_assoc($sql)){ 
		
				 $rows['success'] = 1;
				$rows['message'] = "KYC Details successfully";  			
			 	$rows['data'][$i] = $row;	
					$i++;
				
		}
		
		
		}else {
				$rows['success'] = 0;
				$rows['message'] = "user id not matched Or empty ";
			}
		
	echo (json_encode($rows));
?>