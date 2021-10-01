<?php
include('config.php');

$email = $_POST['email'];
$password =  $_POST['password'];
$ver = $_POST['ver'];


if($ver < 10){
	
	$rows['success'] = 2;
	$rows['message'] = "You are using old application, Please Download Our New App form  http://ludofame.com.";
	//$rows['message'] = "GAME SERVER MAINTENANCE!!! HYIKE- LUDO GAME WILL BE //RESUMED AFTER 24 HOURS";
	$rows['download'] = "http://ludofame.com/ludofame.apk";
	
	echo (json_encode($rows));
		exit;
	
} 
 

$token = md5(uniqid(rand(), true));
		
	$sql=mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$email."' AND password='".$password."'");
	$count = mysqli_num_rows($sql);
	
	$sqln = "SELECT * FROM tbl_tournament WHERE tournament_status=5;";
$result = mysqli_query($conn, $sqln);

$list=0;
$emty=array();

if (mysqli_num_rows($result) > 0) {
	
	 $j=0;
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
		$id=$row["tournament_id"]."_player";
		$sql1 = "SELECT * FROM ".$id." WHERE user_id ='".$user_id."';";
		$result1 = mysqli_query($conn, $sql1);
		if (mysqli_num_rows($result1) > 0) {
              
                $rows['data']['tdata'][$j] = $row;
		
			$list=$list+1;
            $j++;
		}else {
               
           //   $rows['data']['tdata'] =$emty;
 
}
		
        
    }
	
	
}
	
	
	
	    if($count > 0){ 
		
		$lastlogin = date("Y-m-d H:i:s");
		$insertquery =mysqli_query($conn,"update users set lastlogin='".$lastlogin."', token='".$token."' WHERE mobile='".$email."'");
		$insertquery;
 
		while($row = mysqli_fetch_assoc($sql)){ 
		
				$rows['success'] = 1;
				$rows['message'] = "User Details";
			 	$rows['data']['userId'] = $row['id']; 
				$rows['data']['user_name'] = $row['name']; 
				$rows['data']['user_image'] = $row['profilepic']; 
				$rows['data']['coins'] = $row['coins']; 
				$rows['data']['email'] = $row['email']; 
				$rows['data']['bonus'] = $row['bonus']; 
				$rows['data']['points'] = $row['points'];
				$rows['data']['token'] = $token; 	
				$rows['data']['tournament'] = $row['tournament'];				
				$rows['data']['mobile'] = $row['mobile'];				
				$rows['data']['refer'] = $row['refer'];
				$rows['data']['refercode'] = $row['refercode']; 
				$rows['data']['firsttime'] = 'NO';
		}
		
		
		}else {
				$rows['success'] = 0;
				$rows['message'] = "Credential Not Match";
			}
		
	echo (json_encode($rows));
?>