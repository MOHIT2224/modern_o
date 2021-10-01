<?php
include('config.php');
$mobile = $_POST['mobile'];
$nootp = $_POST['otp'];
$ver = $_POST['ver'];
$email=$_POST['email'];
/*$email = $_POST['email'];
$name = $_POST['name'];
$profile = $_POST['profilepic'];
$social=$_POST['emaillogin'];
*/

 
if($ver < 9){	
	$rows['success'] = 2;
		$rows['message'] = "You are using old application, Please Download Our New App form our website (www.hyike.com)";
	//$rows['message'] = "GAME SERVER MAINTENANCE!!! HYIKE- LUDO GAME WILL BE RESUMED AFTER 24 HOURS";
	$rows['download'] = "http://hyike.com/hyike.apk";
	
	echo (json_encode($rows));
		exit;
} 
 
 
$otp = mt_rand(1000, 9999); 
//$otp = mt_rand(100000, 999999); 
$token = md5(uniqid(rand(), true));
$size = 8;
$refercode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size));
//$refercode =  preg_replace(array('/^\[/','/\]$/'), '',$refercode);  
$refercode = str_replace(array('i','o'), '',$refercode);
 
 if (!empty($mobile)){
	 
 if($nootp=='no'){	 
	 
 }else{
 
$admin_message = "Your HYIKE One Time Password(OTP) is- ".$otp." Don't share Your OTP to anyone.";
$admin_msg = urlencode($admin_message);

//$admin_url="http://sms.myctrlbox.com/API/WebSMS/api.php?username=HYIKEOTP&api_key=8c544f0f603b9a0ebf45cd638ad6ab15&sender=HYIKEO&to=$mobile&message=$admin_msg&flash=0";

$admin_url="http://sms.smsmenow.in/sendsms.jsp?user=hyikeo&password=c3f4475a65XX&&mobiles=$mobile&sms=$admin_msg&senderid=HYIKEO";
//$result = file($admin_url);


//$admin_url="http://sms.smsmenow.in/sendsms.jsp?user=autows&password=3494dac476XX&mobiles=$mobile&sms=$admin_msg&senderid=AUTOWS";
//$result = file($admin_url);


//$admin_url="http://148.251.80.111:5665/api/SendSMS?api_id=API3481665303&api_password=123456789&sms_type=T&encoding=T&sender_id=HYIKEG&phonenumber=$mobile&textmessage=$admin_msg";
$result = file($admin_url);	

//$admin_url="http://message.mrads.in/api/sendmsg.php?user=shakthitech&pass=123456&sender=SHAKTI&phone=$mobile&text=$admin_msg&priority=ndnd&stype=normal";
//$result = file($admin_url);

}
 $lastlogin = date("Y-m-d H:i:s");
$sql=mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."' and status=1");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
		 
		while($row = mysqli_fetch_assoc($sql)){ 		
				$rows['success'] = 1;
				$rows['message'] = "User Details";
			 	$rows['data']['userId'] = $row['id']; 
				$rows['data']['user_name'] = $row['name']; 
				$rows['data']['user_image'] = $row['profilepic']; 
				$rows['data']['coins'] = $row['coins']; 
				$rows['data']['email'] = $row['email']; 
				$rows['data']['bonus'] = $row['bonus']; 
				$rows['data']['points'] = $row['points'];
				$rows['data']['token'] = $token; 
				$rows['data']['otp'] = $otp; 
				$rows['data']['mobile'] = $row['mobile'];
				$rows['data']['refer'] = $row['refer'];
				$rows['data']['refercode'] = $row['refercode'];
				 
				
				$rows['data']['firsttime'] = 'NO'; 
			 
				
		
		$insertquery =mysqli_query($conn,"update users set lastlogin='".$lastlogin."', token='".$token."' WHERE mobile='".$mobile."'");
		$insertquery;
		
		
		}
		
		} else {



$name = 'login';
$profile = 'http://eyuktisolution.com/hyike/profile/profile.jpg';

$sqlr="INSERT INTO `users` (`id`, `name`, `tournament`, `coins`, `points`, `bonus`, `email`, `mobile`, `password`, `profilepic`, `token`, `lastlogin`, `refer`, `status`, `registerd`, `refercode`) VALUES (NULL, '".$name."', '0', '0', '0', '0', '".$email."', '".$mobile."',  '".$lastlogin."','".$profile."', '".$token."', NULL, 'NO', '1', CURRENT_TIMESTAMP, '".$refercode."')";

  $result = mysqli_query($conn, $sqlr);
  $user_id = mysqli_insert_id($conn);	
   $coins ='0';	
		
				$rows['message'] = "User Details";
			 	$rows['data']['userId'] = $user_id;   				
				$rows['success'] = 1;

				$rows['data']['user_name'] = $name; 
				$rows['data']['user_image'] = $profile; 
				$rows['data']['token'] = $token; 
				$rows['data']['coins'] = $coins;
				$rows['data']['bonus'] = '0';
                $rows['data']['otp'] = $otp; 
				$rows['data']['mobile'] = $mobile; 
				$rows['data']['points'] = '0';
				$rows['data']['refercode'] = $refercode;						
				$rows['data']['firsttime'] = 'YES'; 
        
		}
		
		echo (json_encode($rows));
		exit;		
		}
?>