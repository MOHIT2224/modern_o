<?php
include('config.php');

$user_id = $_POST['user_id'];
		
	$sql=mysqli_query($conn, "SELECT * FROM payment WHERE uid='".$user_id."' order by pid DESC");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
  $i=0;
		while($row = mysqli_fetch_assoc($sql)){ 
		
				 $rows['success'] = 1;
				$rows['message'] = "Transactions History successfully";  			
			 	$rows['data'][$i] = $row;
$i++;				
		}
		
		
		}else {
				$rows['success'] = 0;
				$rows['message'] = "user id not matched Or empty ";
			}
		
	echo (json_encode($rows));
?>