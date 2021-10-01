<?php
include('config.php');

$mobile = $_GET['email'];
$delete = $_GET['delete'];
if($mobile){
$sql=mysqli_query($conn, "SELECT * FROM users WHERE email='".$mobile."' and status=1");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
		
		while($row = mysqli_fetch_assoc($sql)){ 
echo '<pre>';		
		print_r($row);
		echo '</pre>';
		}
		}
		
}


if($delete){
$sql=mysqli_query($conn, "delete FROM users WHERE email='".$delete."'");
	$count = mysqli_num_rows($sql);
	    if($count > 0){ 
		
		while($row = mysqli_fetch_assoc($sql)){ 
echo '<pre>';		
		print_r($row);
		echo '</pre>';
		}
		}
		
}