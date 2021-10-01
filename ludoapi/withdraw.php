<?php
include('config.php');
$userid = $_POST['userid'];
$amount = $_POST['amount'];
$getaway = $_POST['getaway'];

if($getaway=='bank'){
	
	if($amount < 499){
		
		$rows['success'] = 0;
		$rows['message'] = "The minimum withdrawal amount should be INR 500";
		echo (json_encode($rows));
		exit;	
		
	}
	
}


if($getaway=='PAYTM'){
	
	if($amount < 499){
		
		$rows['success'] = 0;
		$rows['message'] = "The minimum withdrawal amount should be INR 500";
		echo (json_encode($rows));
		exit;	
		
	}
	
}



if($getaway=='UPI'){
	
	if($amount < 499){
		
		$rows['success'] = 0;
		$rows['message'] = "The minimum withdrawal amount should be INR 500";
		echo (json_encode($rows));
		exit;	
		
	}
	
}


if($userid!=''){

      function generateRandomString($length = 8) {
                $characters = '0123456789';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
			
			
			$orderid = generateRandomString();

$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
while($row = mysqli_fetch_assoc($sql)){ 

$trnsid='0';
$status='In Process';
$trnstype='withdrawal';
$coins = $row['coins'];

$newcoins = $row['coins']- $amount; 

if($amount <= $coins){
	  $newcoins;
	 
}else{
	
	$rows['success'] = 0;
	$rows['message'] = "Your withdraw request failed";
	echo (json_encode($rows));
exit;	
	
}
 
$insertquery =mysqli_query($conn,"update users set coins='".$newcoins."' WHERE id='".$userid."'");

 $sqlr="INSERT INTO `payment` (`pid`, `uid`, `orderid`, `trnsid`, `status`, `trnstype`, `amount`, `getway`) VALUES (NULL, '".$userid."','".$orderid."','".$trnsid."', '".$status."','".$trnstype."','".$amount."','".$getaway."')";
 $result = mysqli_query($conn, $sqlr); 

$rows['success'] = 1;
$rows['message'] = "We have received withdraw request successfully";
$rows['coins'] = $newcoins;
}


}else {
	
	 		$rows['success'] = 0;
			$rows['message'] = "Something went wrong";
			}
 
		
	echo (json_encode($rows));
?>