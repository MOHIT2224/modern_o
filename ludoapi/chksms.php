<?php 
include('config.php');
$otp = mt_rand(1000, 9999); 
//$mobile='8800772047';
$mobile='9001111198';
$message = "Your Ludofame One Time Password(OTP) is- ".$otp." .";
	$message = rawurlencode($message); 
 //$admin_url="http://india.jaipurbulksms.com/api/mt/SendSMS?user=kunalm&password=zapak123&senderid=SUPERS&channel=trans&DCS=0&flashsms=0&number=91$mobile&text=$message&route=3";
$admin_url="http://bulksms.anksms.com/api/mt/SendSMS?user=FOODGAZE&password=123456&senderid=AALERT&channel=TRANS&DCS=0&flashsms=0&number=91$mobile&text=$message&route=04";


$resultss = file($admin_url);
//echo '<pre>';
//print_r($resultss);
