<?php
// Create connection
include "connection.php";
include "session.php";
$tournament_id = $_POST["n_txt"];
$insertuserquery = "INSERT INTO tbl_notification_his(notification) VALUES ('".$tournament_id."');";
mysqli_query($conn, $insertuserquery) or die ("Values are not added");

echo("Values added successfully");

header("location: welcome.php");
$conn->close();

?>