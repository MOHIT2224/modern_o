<?php

include("config.php");		

$email = $_POST['email'];

$name = $_POST['name'];

$profile = $_POST['profilepic'];

$Playstore = $_POST['Playstore'];

$otp = '1234'; 


//$otp = mt_rand(100000, 999999); 

$token = md5(uniqid(rand(), true));

$size = 8;

$refercode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size));

//$refercode =  preg_replace(array('/^\[/','/\]$/'), '',$refercode);  

$refercode = str_replace(array('i','o'), '',$refercode);

$lastlogin = date("Y-m-d H:i:s");

 if (!empty($otp) && !empty($email)){

/*	 

$admin_message = "Your HYIKE One time password (OTP):-".$otp." Don't share share this code with others.";

$admin_msg = urlencode($admin_message);	 



$admin_url="http://sms.smsmenow.in/sendsms.jsp?user=autows&password=3494dac476XX&mobiles=$mobile&sms=$admin_msg&senderid=AUTOWS";

$result = file($admin_url);

*/

 



$sql=mysqli_query($conn, "SELECT * FROM users WHERE email='".$email."'");

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

	

		$insertquery =mysqli_query($conn,"update users set lastlogin='".$lastlogin."', token='".$token."' WHERE email='".$email."'");

		$insertquery;

		}

		

		

		

		} else {



$size = 8;

$refercode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size));

 

//$profile = 'http://eyuktisolution.com/hyike/profile/profile.jpg';

  $coins ='0';
if($Playstore=='YES'){
  $coins ='1000';	
}

$sqlr="INSERT INTO `users` (`id`, `name`, `tournament`, `coins`, `points`, `bonus`, `email`, `mobile`, `password`, `profilepic`, `token`, `lastlogin`, `refer`, `status`, `registerd`, `refercode`) VALUES (NULL, '".$name."', '0', '".$coins."', '0', '0', '".$email."', '".$email."', NULL, '".$profile."', '".$token."','".$lastlogin."',  'NO', '1', CURRENT_TIMESTAMP, '".$refercode."')";



$subject = 'QoGold Ludo - OTP request';
$message = '<html><body>';
$message .= '<h3>Hello '.$name.',!</h3>';
$message .= "<p>Your OTP is ".$otp.".</p>";
$message .= "<p>Thanks and Regards</p>";
$message .= "<p>QoGold Support Team</p>";
$message .= '</body></html>';
 

// Always set content-type when sending HTML email
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@qasports.co>' . "\r\n";
//$headers .= 'Cc: autowaves@gmail.com' . "\r\n";
 
mail($email,$subject,$message,$headers);

  $result = mysqli_query($conn, $sqlr);

  $user_id = mysqli_insert_id($conn);		

		

  				$rows['message'] = "User Details";

			 	$rows['data']['userId'] = $user_id;   				

				$rows['success'] = 1; 

				$rows['data']['user_name'] = $name; 

				$rows['data']['user_image'] = $profile; 

				$rows['data']['token'] = $token; 

				$rows['data']['coins'] = $coins;

				$rows['data']['bonus'] = '0';

                $rows['data']['otp'] = $otp; 

				$rows['data']['email'] = $email;  

				$rows['data']['points'] = '0';

				$rows['data']['refercode'] = $refercode;						

				$rows['data']['firsttime'] = 'YES'; 

 

        

		}} else

        {

	  $rows['success'] = 0;

	  $rows['response'] = "Some error occurred. Please try again.";

 

        }

   

  echo (json_encode($rows)); 