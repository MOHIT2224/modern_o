<?php
include('config.php');
$userid = $_POST['user_id'];


$date =  date('Y-m-d');

$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."' ");
	$count = mysqli_num_rows($sql);
    if($count > 0){ 
				
				
	$sqlct=mysqli_query($conn, "SELECT * FROM transfree WHERE date='".$date."' and uid='".$userid."' and trntype='SPINNER'");			
		$numgame = mysqli_num_rows($sqlct);	
$gametotal='1';		
			  
		if($numgame < $gametotal){	   
		 
		  $updattrns ="INSERT INTO `transfree` (`tid`, `trntype`, `uid`, `date`,`amount`,`closingcoins`, `updatedcoins`,`crdr`, `datetime`) VALUES (NULL, 'SPINNER', '".$userid."','".$date."', '0', '0','0', 'Dr', CURRENT_TIMESTAMP)";		 
		  $runqury = mysqli_query($conn, $updattrns);
		  
		  $rows['success'] = 1;
	$rows['response'] = "You can play Games ";
	$rows['played']=$numgame;		
  }
  else {
			
		   
	$rows['success'] = 0;
	$rows['response'] = "Your free game play has been completed for today ";
	$rows['played']=$numgame;		
		}
	

} else{
	  
	  
	$rows['success'] = 0;
	$rows['response'] = "Bet Not marked, Some error occurred. Please try again. ";
 
   }
   
  echo (json_encode($rows)); 