<?php
//error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
$servername = "207.180.225.243";
$username = "dev";
$password = "U7j9WrWcsRfSl64h";
$database = "gaminggaze";



// Create connection

$conn = mysqli_connect($servername, $username, $password, $database );
//$con=mysqli_connect("localhost","my_user","my_password","my_db");

// Check connection
if (mysqli_connect_errno() ){
    die("Connection failed: " . mysqli_connect_error());
}else{
//echo "connected";
}

?>