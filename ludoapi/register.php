<?php
include('config.php');
$size = 8;
$refercode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size));
//$refercode =  preg_replace(array('/^\[/','/\]$/'), '',$refercode);  
$refercode = str_replace(array('i','o'), '',$refercode);
$otp = mt_rand(1000, 9999); //$otp ='1234'; 
$ucode = mt_rand(1000, 9999);
$mobile = $_POST['mobile'];
$email = $_POST['email'];
$name = $_POST['name'];
$Playstore = $_POST['Playstore'];
$password = $_POST['password'];
 $profile = 'http://ludofame.com/profile.jpg';
$token = md5(uniqid(rand(), true));$refer = $_POST['refercode'];if($refer=='0'){	
}else { 					$sql=mysqli_query($conn, "SELECT * FROM users WHERE refercode='".$refer."'");	$counts = mysqli_num_rows($sql);	if($counts > 0){ 	 		$sqlr="INSERT INTO `refered` (`rid`, `rcode`, `uid`, `status`, `referdate`,`ucode`, `date`) VALUES (NULL, '".$refer."', '".$userid."', '0',  CURRENT_TIMESTAMP,'".$ucode."',  NULL)";		$result = mysqli_query($conn, $sqlr);		$rows['success'] = 1;		$rows['message'] = "Refercode matched";			 	} 	else {		$rows['success'] = 0;		$rows['message'] = "Refer Code does not exist. Please try again.";		 			echo (json_encode($rows));			die;	} 
}	 
    $sql=mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
		
		 $rows['success'] = 0;
		 $rows['message'] = "this mobile already exists";
		
		} else {
  $coins ='10';
if($Playstore=='YES'){
  $coins ='1000';	
}
//$message = "Your Ludofame One Time Password(OTP) is- ".$otp." Don't share Your OTP to anyone.";$message = "Your Ludofame One Time Password(OTP) is- ".$otp." .";	$message = rawurlencode($message);  //$admin_url="http://india.jaipurbulksms.com/api/mt/SendSMS?user=kunalm&password=zapak123&senderid=SUPERS&channel=trans&DCS=0&flashsms=0&number=91$mobile&text=$message&route=3";$admin_url="http://bulksms.anksms.com/api/mt/SendSMS?user=FOODGAZE&password=123456&senderid=AALERT&channel=TRANS&DCS=0&flashsms=0&number=91$mobile&text=$message&route=04";$resultss = file($admin_url); 
$sqlr="INSERT INTO `users` (`id`, `name`, `tournament`, `pid`,`role`, `coins`, `points`, `bonus`, `email`, `mobile`, `password`, `profilepic`, `token`, `lastlogin`, `refer`, `status`, `registerd`, `refercode`) VALUES (NULL, '".$name."', '0', NULL,'0','".$coins."', '200', '0', '".$mobile."', '".$mobile."', '".$password."', '".$profile."', '".$token."', NULL, 'NO', '0', CURRENT_TIMESTAMP, '".$refercode."')";

  $result = mysqli_query($conn, $sqlr);
  $user_id = mysqli_insert_id($conn);     if($counts > 0){  $insertquery =mysqli_query($conn,"update refered set uid='".$user_id."' WHERE ucode='".$ucode."'");  $insertquery;   }		
  	$rows['success'] = 1;
				$rows['message'] = "User Details";
			 	$rows['data']['userId'] = $user_id; 
				$rows['data']['user_name'] = $name; 
				$rows['data']['user_image'] = $profile; 
				$rows['data']['token'] = $token; 
				$rows['data']['mobile'] = $mobile;
				$rows['data']['email'] = $mobile;
				$rows['data']['coins'] = '10';
				$rows['data']['otp'] = $otp; 
				$rows['data']['bonus'] = '0'; 
				$rows['data']['points'] ='200';
				$rows['data']['refercode'] = $refercode;						
				$rows['data']['firsttime'] = 'YES'; 
        
		}
		
		echo (json_encode($rows));