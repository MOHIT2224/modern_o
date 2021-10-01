<?php   
include('config.php');
$sport_id = $_GET['sport_id'];
 
$sql=mysqli_query($conn, "SELECT seriesId,Name,is_online FROM seriesmst WHERE SportID='".$sport_id."' and active = 1");
	$count = mysqli_num_rows($sql); 
 	    if($count > 0){ 
  $i=0;
		while($row = mysqli_fetch_assoc($sql)){ 
		 				 
				$rows[$i]['seriesId'] = $row['seriesId'];	
				$rows[$i]['SeriesName'] = $row['Name'];
				$rows[$i]['is_online'] = $row['is_online'];
				 
					$i++;				
		}		
		
		}else {
				$rows['success'] = 0;
				$rows['message'] = "user id not matched Or empty ";
			}
				echo (json_encode($rows));
?>