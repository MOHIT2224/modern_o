<?php
//error_reporting(0);
//date_default_timezone_set('Asia/Kolkata');
$servername = "13.234.27.127";
$username = "devloper";
$password = "Mysql@Dev*Loper$321!";
$database = "super_admin";
 

// Create connection

$conn = mysqli_connect($servername, $username, $password, $database );
 

// Check connection
if (mysqli_connect_errno() ){
    die("Connection failed: " . mysqli_connect_error());
}else{
// echo "connected";
}

// 	
