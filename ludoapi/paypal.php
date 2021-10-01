<?php
require('config.php');
 $tx =$_GET['tx'];
 echo $orderid =$_GET['cm'];
 $amt =$_GET['amt'];
 $st =$_GET['st'];
 if($st=='Completed'){
	 $st='Success';
 }

//echo $st;
   
 $getde=mysqli_query($conn, "SELECT * FROM payment WHERE orderid='".$orderid."'");
 $gorder = mysqli_fetch_assoc($getde);
 
 //print_r($gorder); 
// print_r($myArray);
 
 //echo $orderid = $myArray[0]['order_id'];
	 $userid = $gorder['uid'];
	//echo '<br>'; 
	 $orderamount =  $amt;
	//echo '<br>'; 
 
$insertquery =mysqli_query($conn,"update payment set status='".$st."', amount='".$amt."', trnsid='".$tx."' WHERE orderid='".$orderid."'");
$insertquery;
		
		
 $sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	 
while($row = mysqli_fetch_assoc($sql)){ 

 $newcoins = $row['coins']+ $orderamount; 
//echo '<br>'; 
 $mobile = $row['mobile'];
//echo '<br>';
 $emailid = $row['emailid'];
//echo '<br>';
}
$insertquery =mysqli_query($conn,"update users set coins='".$newcoins."' WHERE id='".$userid."'");
	
 
$sqler=mysqli_query($conn, "SELECT * FROM payment WHERE uid='".$userid."' and status='Success'");
	$count = mysqli_num_rows($sqler);
	    if($count > 0){
			
			
			$sqls=mysqli_query($conn, "SELECT * FROM refered WHERE uid='".$userid."' and status='1'");
	$countss = mysqli_num_rows($sqls);
	    if($countss > 0){
			
			
		 $insert =mysqli_query($conn,"update refered set status='2' WHERE uid='".$userid."'");
$gatrcode = mysqli_fetch_assoc($sqls);		
	 $refercode = $gatrcode['rcode'] ;
		 
		 $gtrc=mysqli_query($conn, "SELECT * FROM users WHERE refercode='".$refercode."'");
		 
		
		$rowss = mysqli_fetch_assoc($gtrc);
		$bonus = $rowss['bonus'] + 50;		
		$insertquery =mysqli_query($conn,"update users set bonus='".$bonus."' WHERE refercode='".$refercode."'");
		
		
		
		
		$usersql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
		$rowuser = mysqli_fetch_assoc($usersql);
		$bonusu = $rowuser['bonus'] + 100;
		
	   $insert =mysqli_query($conn,"update users set bonus='".$bonusu."', refer='".$refercode."' WHERE id='".$userid."'");
	   
		
		
		}
			
			
			
		}




   // $html = "<p>Your payment was successful</p><p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
             
			// print_r($_POST);
		 

$admin_message = "Your payment ".$orderamount." was successful, with order number ".$orderid." ";
$admin_msg = urlencode($admin_message);	 

$admin_url="http://sms.bhashsms.com/api/sendmsg.php?user=hyikegame&pass=123456&sender=HYIKEG&phone=$mobile&text=$admin_msg&priority=ndnd&stype=normal";

//$admin_url="http://message.mrads.in/api/sendmsg.php?user=shakthitech&pass=123456&sender=SHAKTI&phone=$mobile&text=$admin_msg&priority=ndnd&stype=normal";
//if($notp!='0'){
//$admin_url="http://sms.smsmenow.in/sendsms.jsp?user=autows&password=3494dac476XX&mobiles=$mobile&sms=$admin_msg&senderid=AUTOWS";
$result = file($admin_url);
 

 if($st=='Success'){ ?>

<form method="post" name="redirect" action="success.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
		<?php
} 
			
			
		 