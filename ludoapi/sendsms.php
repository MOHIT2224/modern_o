<?php
$mobile = $_POST['mobile'];
$otp = mt_rand(1000, 9999); 

$message = "Your Ludofame One Time Password(OTP) is- ".$otp." .";
	$message = rawurlencode($message); 
 
$admin_url="http://india.jaipurbulksms.com/api/mt/SendSMS?user=kunalm&password=zapak123&senderid=SUPERS&channel=trans&DCS=0&flashsms=0&number=91$mobile&text=$message&route=3";
$resultss = file($admin_url);
  $rows['message'] = "Otp Sent";
			 			
				$rows['success'] = 1; 				 
                $rows['otp'] = $otp; 
					echo (json_encode($rows));
		exit;	
 
?>