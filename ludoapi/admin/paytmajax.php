<?php 
include('config.php');
$fromuser =$_POST['fromuser'];
$touser =$_POST['touser'];
$amount =$_POST['amount'];

if($fromuser!='' &&  $amount!=''){
 
 $oldbalnce=mysqli_query($conn, "select * from paytmaccess where user='$fromuser' ");
 $temp=mysqli_fetch_assoc($oldbalnce);
 
 if($amount > $tot=$temp['balance']){
	 
	 echo "You do not have sufficient balance for this transaction"	;
	 exit;
	 
	 }
 
 
 
 
 $touserinfo=mysqli_query($conn, "select * from users where mobile='$touser' ");
 $toinfo=mysqli_fetch_assoc($touserinfo);
 
 
	
	$touname = $toinfo['name'];
$trnstype='Coins Transfer to '.$touname.' ';
$ipdaress = $_SERVER['REMOTE_ADDR'];
$coins=$temp['balance'];
$newcoins = $coins -$amount;

	$updattrns ="INSERT INTO `paytmtrans` (`tid`, `trntype`, `uid`,`toid`, `amount`,`closingcoins`, `updatedcoins`,`crdr`, `ipaddress`,`datetime`) VALUES (NULL, '".$trnstype."', '".$fromuser."', '".$amount."', '".$coins."','".$newcoins."', 'Dr','".$ipdaress."', CURRENT_TIMESTAMP)";		 
 $runqury = mysqli_query($conn, $updattrns);
	
	
	$frmuname = $temp['user'];
	$trnstype='Coins Transfer from '.$frmuname.' ';
	$coins=$toinfo['coins'];
    $newcoins = $coins +$amount;
	
	$totrnsfer ="INSERT INTO `paytmtrans` (`tid`, `trntype`, `uid`, `amount`,`closingcoins`, `updatedcoins`,`crdr`, `ipaddress`,`datetime`) VALUES (NULL, '".$trnstype."', '".$touser."', '".$amount."', '".$coins."','".$newcoins."', 'Cr','".$ipdaress."', CURRENT_TIMESTAMP)";		 
 $runq = mysqli_query($conn, $totrnsfer);
 
 
  $tot=$toinfo['coins']+$amount;
  
 $sql = "UPDATE `users` SET coins = '$tot'  where mobile='$touser'"; 
	
	$oldadmin=mysqli_query($conn, "select * from paytmaccess where user='$fromuser' ");
 $temp3=mysqli_fetch_assoc($oldadmin);

 $totmew=$temp3['balance']-$amount;
	$newsql = 	"UPDATE `paytmaccess` SET balance = '$totmew'  where user='$fromuser'";
	$runq =mysqli_query($conn,$newsql);
	
	
		if(mysqli_query($conn,$sql))
		{ 
	        echo "Successfully Transfered to Main Account..."	;
		}
  
} 
