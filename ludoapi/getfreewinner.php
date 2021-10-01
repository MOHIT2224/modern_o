<?php
include('config.php');
$userid = $_POST['userid'];
$betamount =  $_POST['winningamount'];
$tabletype =  $_POST['tabletype'];
$gameid =  $_POST['gameid'];


if (!empty($userid) && !empty($betamount) && !empty($tabletype) && !empty($gameid)){
$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	$count = mysqli_num_rows($sql);
    if($count > 0){ 
				
		
   $row = mysqli_fetch_assoc($sql);						 
	$coins = $row['points'];
	$totalwinning = $coins + $betamount;
		  /*Update coins after bet*/	
		 $insertquery =mysqli_query($conn,"update users set points='".$newcoins."' WHERE id='".$userid."'");
		 $insertquery;
   
 
  $rows['success'] = 1;
  $rows['message'] = "Winner coins successfully transferred";  
  $rows['data']['points'] = $newcoins;
		  
		}

  
  } else{
	  
	  
	$rows['success'] = 0;
	$rows['response'] = "Some error occurred. Please try again. ";
 
   }
   
  echo (json_encode($rows)); 