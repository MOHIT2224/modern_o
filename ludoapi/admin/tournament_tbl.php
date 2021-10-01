<?php
// Create connection
include "connection.php";
include "session.php";
$tournament_id = $_POST["Tournament_id"];
$tournament_name = $_POST["Tournament_Name"];
$TYPE = $_POST["TYPE"];
$DETAILS = $_POST["DETAILS"];
$entry_coin = $_POST["Entry_Coin"];
$joining_last_date = $_POST["Joining_Last_Date"];
$starting_date = $_POST["Starting_Date"];
$closed_date = $_POST["Closed_Date"];
$Prize= $_POST["Prize"];

$insertuserquery = "INSERT INTO tbl_tournament(tournament_id, tournament_name, TYPE, DETAILS, entry_coin, joining_last_date, starting_date, closed_date,total_prize) VALUES ('".$tournament_id."','".$tournament_name."','".$TYPE."','".$DETAILS."','".$entry_coin."','".$joining_last_date."','".$starting_date."','".$closed_date."','".$Prize."');";
mysqli_query($conn, $insertuserquery) or die ("Values are not added");

echo("Values added successfully");

$sql = "CREATE TABLE ".$tournament_id."_player (
id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
user_id VARCHAR(50) NOT NULL,
user_name VARCHAR(50) NOT NULL,
total_match VARCHAR(50) NOT NULL,
total_score INT(100) NOT NULL)";
$conn->query($sql);

$sql = "CREATE TABLE ".$tournament_id."_match (
id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
user_id VARCHAR(50) NOT NULL,
score VARCHAR(50) NOT NULL,
match_no VARCHAR(50) NOT NULL)";


if ($conn->query($sql) === TRUE) 
{
    header("location: active_tournament.php");
} 
else 
{
    echo "Error creating table ";
}

$conn->close();

?>