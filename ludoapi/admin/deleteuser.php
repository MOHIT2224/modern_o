<?php
include 'config.php';
include 'functions.php';
ob_start();
$session =session_start();
 $uid = $_SESSION['uid'];
   if($uid==''){
	echo "<script type='text/javascript'>window.location.href = ' index.php';</script>";
	exit;
	}
  $userid = $_GET['userid'];
 $sql=mysqli_query($conn, "DELETE FROM users WHERE id='".$userid."'");
 
  echo "<script type='text/javascript'>window.location.href = ' list_user.php';</script>";
				exit();
	  
?>