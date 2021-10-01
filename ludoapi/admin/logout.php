<?php include 'config.php'; 
session_start();
session_unset();
session_destroy();
unset($_SESSION['uid']);
$_SESSION = array(); 
 
header('location:index.php');
?>
