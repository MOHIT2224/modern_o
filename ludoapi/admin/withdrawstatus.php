<?php
include 'config.php';
include 'functions.php';
ob_start();
$session =session_start();
 $uid = $_SESSION['uid'];
 $uinfo=mysqli_query($conn, "SELECT * FROM users WHERE id='".$uid."'");
 $uinfos = mysqli_fetch_assoc($uinfo);
 $rcode=$uinfos['refercode'];
 
 if($uid=='1'){
	$sql=mysqli_query($conn, "SELECT * FROM users"); 
	$sqllist=mysqli_query($conn, "SELECT * FROM users");
 }else{
 $sql=mysqli_query($conn, "SELECT * FROM users WHERE pid='".$uid."' OR refer='".$rcode."'");
 $sqllist=mysqli_query($conn, "SELECT * FROM users");
 }
 
	  if($uid==''){
	echo "<script type='text/javascript'>window.location.href = ' index.php';</script>";
	exit;
	}
	
	
	$orderid = $_GET['orderid'];
	$userid = $_GET['userid'];
	$status = $_GET['status'];
	$amount = $_GET['amount'];
	$pid = $_GET['pid'];
	if($status=='approved'){
	
	 $sqlr = "UPDATE `payment` SET status = '$status' where orderid='$orderid' and uid='$userid' "; 
	 
	 if(mysqli_query($conn,$sqlr))
		{ 
	        echo "Updated Successfully..."	;
		}
?>
	<form method="post" name="redirect" action="withdraw.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
	
	
	<?php
		
	}
	
	if($status=='Declined'){
		
		 $sqlr = "UPDATE `payment` SET status = '$status' where orderid='$orderid' and uid='$userid' "; 
		  if(mysqli_query($conn,$sqlr))
		{ 
	        echo "Updated Successfully..."	;
			
			$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
			 $row = mysqli_fetch_assoc($sql);		
			 $coins = $row['coins'];
	$newcoins = $coins + $amount;
	 $insertquery =mysqli_query($conn,"update users set coins='".$newcoins."' WHERE id='".$userid."'");
	 $insertquery;
	 
	 
	 
$admin_message = "Your withdrawal request has been declined order number ".$order_id." and amount ".$amount." has been return into HYIKE Balance .";
$admin_msg = urlencode($admin_message);	 

$admin_url="http://sms.bhashsms.com/api/sendmsg.php?user=hyikegame&pass=123456&sender=HYIKEG&phone=$mobile&text=$admin_msg&priority=ndnd&stype=normal";
	
	?>
	<form method="post" name="redirect" action="withdraw.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
	
	
	<?php
		}
		
	}


if($status=='Remove'){
	
	 $sqlr = "DELETE FROM payment where orderid='$orderid' and uid='$userid'"; 
	 
	 if(mysqli_query($conn,$sqlr))
		{ 
	        echo "Deleted Successfully..."	;
			 
		}
?>
	 <form method="post" name="redirect" action="withdraw.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
	
	
	<?php
		
	}
	
	
?>