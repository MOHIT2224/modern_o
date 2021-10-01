<?php
include('config.php');
$userid = $_POST['userid'];
$betamount =  $_POST['betamount'];
$tabletype =  $_POST['tabletype'];
$gameid =  $_POST['gameid'];

if (!empty($userid) && !empty($betamount) && !empty($tabletype) && !empty($gameid)){

$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	$count = mysqli_num_rows($sql);
    if($count > 0){ 
				
		
   $row = mysqli_fetch_assoc($sql);	
 $mainbonus = $row['bonus'];   

   $bonus = $row['bonus'] - $betamount;	 
	
 function thefunction($number){
  if ($number < 0)
    return 0;
  return $number; 
} 


	  $newbonus = thefunction($bonus);
 	 $remaining = ltrim($bonus, '-');
	 
	 $coins = $row['coins'];
	
	
	 $remcoin = $coins - $remaining;
	
	
		 
	 $remcoins = ltrim($remcoin, '-');
	 
		 
		  if($coins < $remaining){
			$rows['success'] = 0;
	        $rows['response'] = "Sorry your balance is not sufficient.";  
			  echo (json_encode($rows)); 
			exit;
			  
		  } else {
			  
			  
		if($mainbonus < $betamount){	  
	 	  
	  $newcoins = $coins - $remaining;	
	
		} else {
			
		$newcoins = $coins;	
			
		}
				
		 /*Update coins after bet*/	
		  $insertquery =mysqli_query($conn,"update users set bonus='".$newbonus."', coins='".$newcoins."' WHERE id='".$userid."'");
		  $insertquery;
		 
		 
		 /*Update transactions after bet*/
		 
		  $updattrns ="INSERT INTO `trans` (`tid`, `trntype`, `uid`, `amount`,`closingcoins`, `updatedcoins`,`crdr`, `datetime`) VALUES (NULL, 'BET', '".$userid."', '".$betamount."', '".$coins."','".$newcoins."', 'Dr', CURRENT_TIMESTAMP)";		 
		  $runqury = mysqli_query($conn, $updattrns);
		
	/*MARK BET*/	
		
  $bet="INSERT INTO `gamebet` (`id`, `tabletype`, `amount`, `gameid`, `userid`, `gtime`, `status`) VALUES (NULL, '".$tabletype."', '".$betamount."', '".$gameid."', '".$userid."', CURRENT_TIMESTAMP, 'running')";
  $result = mysqli_query($conn, $bet);
  $betid = mysqli_insert_id($conn);	
  $rows['success'] = 1;
  $rows['message'] = "Bet successfully marked";
  $rows['data']['betid'] = $betid;  
  $rows['data']['coins'] = $newcoins;
  $rows['data']['bonus'] = $newbonus;
   
		  }
		  
		  
		}


 
  
  } else{
	  
	  
	$rows['success'] = 0;
	$rows['response'] = "Bet Not marked, Some error occurred. Please try again. ";
 
   }
   
  echo (json_encode($rows)); 