<?php
include('config.php');	
	$sql=mysqli_query($conn, "SELECT * FROM game_mode WHERE is_active='1'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
  $rows = mysqli_fetch_assoc($sql);
  $row['success'] = 1;
  $row['message'] = "successfully"; 
  $row['type'] = (int)$rows['id']; 
          
      }else {
				$row['success'] = 0;
				$row['message'] = "empty ";
			}
		
	echo (json_encode($row));
?>