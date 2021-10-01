<?php
error_reporting(0);
 
date_default_timezone_set('Asia/Kolkata');

$servername = "localhost";
$username = "dev";
$password = "5LWvgk<>e*{:L~Y";
$database = "ludoapp";




// Create connection

$conn = mysqli_connect($servername, $username, $password, $database );
//$con=mysqli_connect("localhost","my_user","my_password","my_db");

// Check connection
if (mysqli_connect_errno() ){
    die("Connection failed: " . mysqli_connect_error());
}else{
//echo "connected";
}

//TEST
//$keyId = 'rzp_test_3d16PeU7YIyubs';
//$keySecret = 'OiE1fKKXZlBHBG004tvMQDbW';
//$displayCurrency = 'INR';

//LIVE


$keyId = 'rzp_live_BY3AtbnuWcCp02';
$keySecret = 'wfL8p0rec8W8T1I2xVbstIaT';

$displayCurrency = 'INR';



$cofingmode = "PROD"; //<------------ Change to TEST for test server, PROD for production

//cashfree Payment getaway////
//TEST
//$cashid='6616028cbf79a4e7e97f793a6166';
//$cashkey='49aa77bb4e57ac86ffa8306eeb87d660190d0e2f';

//LIVE

/*
$cashid='1587335869c4a4938e99648da37851';
$cashkey='3aa0592a0e41ff5252ffc70d9a910448bc7871f3';
*/

$cashid='231094243af1d93a0f7a4921b90132';
$cashkey='f87ff049ec75f7bf6ec69dbdf0ca6c9905a0e215';

//These should be commented out in production
// This is for error reporting
// Add it to config.php to report any errors
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
?>