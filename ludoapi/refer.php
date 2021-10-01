<?php
include("config.php");		
$userid = $_POST['userid'];

 

$name = $_POST['name'];
//$mobile = $_POST['mobile'];
$email = $_POST['email']; 

if($userid && $name){		
	if($name){
		$insertname =mysqli_query($conn,"update users set name='".$name."' WHERE id='".$userid."'");
		$insertname;
	}

	if($mobile){
		$insertname1 =mysqli_query($conn,"update users set mobile='".$mobile."' WHERE id='".$userid."'");
		$insertname1;			
	}

	if($email){
		$insertname2 =mysqli_query($conn,"update users set email='".$email."' WHERE id='".$userid."'");
		$insertname3;	
		//echo "update users set email='".$email."' WHERE id='".$userid."'";		
	}


	/*if($profile){
		$insertname =mysqli_query($conn,"update users set profilepic='".$profile."' WHERE id='".$userid."'");
		$insertname;			
	}
*/
	$rows['success'] = 1;
	$rows['message'] = "User info updated ";
	
}else {
	
	$rows['success'] = 0;
	$rows['message'] = "Somthing went wrong";
} 

	

echo (json_encode($rows));

?>