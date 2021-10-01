<?php
include('config.php');
$user_id = $_POST['user_id'];
$name = $_POST['name'];
$bankname = $_POST['bankname'];
$account = $_POST['account'];
$ifsc=$_POST['ifsc'];
$panno=$_POST['panno'];
$skrill=$_POST['paypal'];
$neteller=$_POST['neteller'];

 if($panno==''){$panno='NULL';}  if($skrill==''){$skrill='NULL';} if($neteller==''){$neteller='NULL';}	


$sql=mysqli_query($conn, "SELECT * FROM kyc WHERE user_id='".$user_id."'");
	$count = mysqli_num_rows($sql);
	 if($count > 0){ 	
		
$insertquery =mysqli_query($conn,"update kyc set name='".$name."', bankname='".$bankname."', account='".$account."', ifsc='".$ifsc."', panno='".$panno."', paypal='".$skrill."', neteller='".$neteller."' WHERE user_id='".$user_id."'");
$insertquery;

$rows['success'] = 1;
$rows['message'] = "User KYC Details Saved";
echo (json_encode($rows));
exit;
		
		
		} else {



if($user_id!=''){

 $sqlr="INSERT INTO `kyc` (`kid`, `user_id`, `name`, `bankname`, `account`, `ifsc`, `panno`, `paypal`, `neteller`) VALUES (NULL, '".$user_id."','".$name."','".$bankname."', '".$account."', '".$ifsc."','".$panno."','".$skrill."','".$neteller."')";
 $result = mysqli_query($conn, $sqlr);
  $lastid = mysqli_insert_id($conn);  
 


$rows['success'] = 1;
$rows['message'] = "User KYC Details Saved";


}else {
	
	
		
				$rows['success'] = 0;
				$rows['message'] = "user id not matched Or empty ";
			}
			
			
		}
		
	echo (json_encode($rows));
?>