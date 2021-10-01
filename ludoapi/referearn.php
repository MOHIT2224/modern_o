<?php
include("config.php");	
$sql=mysqli_query($conn, "SELECT * FROM referral");
$sqlnew=mysqli_query($conn, "SELECT img FROM referralimg");
$row = mysqli_fetch_assoc($sql);
		$title = $row['title'];	
		$description = $row['description'];
		 $i=0;
while($rownew = mysqli_fetch_assoc($sqlnew)){ 		
		$rows['success'] = 1;
		$rows['message'] = "Success";	
		$rows['title'] = $title;
		$rows['description'] = $description;
		$rows['data'][$i] = $rownew;
$i++;			

}

		
		
		echo (json_encode($rows));