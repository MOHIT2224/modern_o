<?php
include('config.php');
$mobile = $_POST['email'];
$otp = mt_rand(1000, 9999); 
		
	//$sql=mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."'");
	$sql=mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
$message = "Your Ludofame One Time Password(OTP) is- ".$otp." .";
	$message = rawurlencode($message); 
 
//$admin_url="http://india.jaipurbulksms.com/api/mt/SendSMS?user=kunalm&password=zapak123&senderid=SUPERS&channel=trans&DCS=0&flashsms=0&number=91$mobile&text=$message&route=3";
$admin_url="http://bulksms.anksms.com/api/mt/SendSMS?user=FOODGAZE&password=123456&senderid=AALERT&channel=TRANS&DCS=0&flashsms=0&number=91$mobile&text=$message&route=04";
$resultss = file($admin_url);

		while($row = mysqli_fetch_assoc($sql)){ 
	// Message details


	
$result = file($admin_url);			 						 
 $rows['message'] = "Otp Sent";
			 	$rows['userid'] = $row['id'];  				
				$rows['success'] = 1; 				 
                $rows['otp'] = $otp; 
				  	
		}
		
		
		}else {
				$rows['success'] = 0;
				$rows['message'] = "Inavlid User";
			}
		
	echo (json_encode($rows));
?>