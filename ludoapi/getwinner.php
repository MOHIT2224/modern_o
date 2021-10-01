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
 //  $bonus = $row['bonus'] - $betamount;	
 $percentage = 10;
 
 //$winam = ($percentage / 100) * $betamount; 
 $winam = ($betamount / 100) * $percentage; 
   
   		 
	$coins = $row['coins'];
	$newcoins = $coins + $betamount - $winam;
  $totalwinning = $betamount - $winam;
   
   
   	 /*Update transactions after bet*/
		 
		 $updattrns ="INSERT INTO `trans` (`tid`, `trntype`, `uid`, `amount`,`closingcoins`, `updatedcoins`,`crdr`, `datetime`) VALUES (NULL, 'Game Winner', '".$userid."', '".$betamount."', '".$coins."','".$newcoins."', 'Cr', CURRENT_TIMESTAMP)";		 
		 $runqury = mysqli_query($conn, $updattrns);
		 $inssy =mysqli_query($conn,"update gamebet set gamecomplete=CURRENT_TIMESTAMP, losewin='looser', status='completed' WHERE gameid='".$gameid."' ");		$inssy;
		 
		 $insertquery =mysqli_query($conn,"update gamebet set winmount='".$totalwinning."', gamecomplete=CURRENT_TIMESTAMP, losewin='winner', status='completed' WHERE gameid='".$gameid."' and userid='".$userid."'");
		$insertquery;
 
		 
		 
		 
		  /*Update coins after bet*/	
		 $insertquery =mysqli_query($conn,"update users set coins='".$newcoins."' WHERE id='".$userid."'");
		 $insertquery;
		 
   
 
  $rows['success'] = 1;
  $rows['message'] = "Winner coins successfully transferred";  
  $rows['data']['coins'] = $newcoins;
		  
		}

  
  } else{
	  
	  
	$rows['success'] = 0;
	$rows['response'] = "Some error occurred. Please try again. ";
 
   }
   
  echo (json_encode($rows)); 