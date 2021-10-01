<?php

error_reporting(0);

date_default_timezone_set('Asia/Kolkata');	


$base_url = "http://modernexch.com/ludoapi/admin/";

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

 

/*

$con = mysql_connect($servername, $username, $password)

        or die("Unable to connect to MySQL");

mysql_select_db($database, $con);*/