<?php
// Create connection
include('config.php');
$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$user_id = $params["user_id"];
$t_id = $params["tournament_id"];
$user_name=$params["user_name"];
$update_coin=$params["coin"];
/* $pre_coin=$params["pre_coin"]; */
$total_score=0;
$total_match=0;
$PrevCoin=0;
$newCoin=0;
$id=$t_id."_player";

$sql0="SELECT * FROM ".$id." WHERE user_id ='".$user_id."'";
$res=mysqli_query($conn, $sql0);
if(mysqli_num_rows($res)>0){
	$rows['success'] = 1;
	$rows['message'] = "You are allready joined /n this tournament";
}else{
	$sql1="SELECT * FROM users WHERE id ='".$user_id."'";
 $result=mysqli_query($conn, $sql1);
 if (mysqli_num_rows($result) > 0) {
		while($row=mysqli_fetch_assoc($result)){
				$PrevCoin=$row['coins']; 
				if($PrevCoin==0){
					$PrevCoin=$row['coins'];
					if($PrevCoin==0){
						$rows['success'] = 0;
						$rows['message'] = "Insufficient balance";
						echo (json_encode($rows));
						exit;
					}else{
						
					$newCoin=$PrevCoin-$update_coin;
					$sql = "UPDATE users SET coins='".$newCoin."' WHERE id='".$user_id."'";

					if (mysqli_query($conn, $sql)) {
						$insertuserquery = "INSERT INTO ".$id."(user_id,total_match,total_score,user_name) VALUES ('".$user_id."','".$total_match."','".$total_score."', '".$user_name."');";
					if(mysqli_query($conn, $insertuserquery)){
						$sql23 = "UPDATE users SET tournament='1' WHERE id='".$user_id."'";
						if(mysqli_query($conn, $sql23)){
							$rows['success'] = 1;
							$rows['message'] = "User inserted in tournament sucessfully";
						}else{
							$rows['success'] = 0;
							$rows['message'] = "User tirnamnet faileed  failed";
						}
						}
						else{
						$rows['success'] = 0;
						$rows['message'] = "User inserted in tournament  failed";
						}	
						} else {
						$rows['success'] = 2;
						$rows['message'] = "New Coins Is UPDATE Failed";
						}
						}
					}else{
						
						
						
						$bonuscon=$row['bonus']; 	
						
						 function thefunction($number){
  if ($number < 0)
    return 0;
  return $number; 
} 
$newCoin=$Prevbonus-$update_coin;

	  $newbonus = thefunction($Prevbonus);
 	 $remaining = ltrim($newCoin, '-');
						
						
					
						
						if($bonuscon < $remaining){						
						 
						$rows['success'] = 0;
						$rows['message'] = "Insufficient balance";
						echo (json_encode($rows));
						exit;
					}
						
					
					$sql = "UPDATE users SET bonus='".$newCoin."' WHERE id='".$user_id."'";

					if (mysqli_query($conn, $sql)) {
						$insertuserquery = "INSERT INTO ".$id."(user_id,total_match,total_score,user_name) VALUES ('".$user_id."','".$total_match."','".$total_score."', '".$user_name."');";
					if(mysqli_query($conn, $insertuserquery)){
						$sql23 = "UPDATE users SET tournament='1' WHERE id='".$user_id."'";
						if(mysqli_query($conn, $sql23)){
							$rows['success'] = 1;
							$rows['message'] = "User inserted in tournament sucessfully";
						}else{
							$rows['success'] = 0;
							$rows['message'] = "User tirnamnet faileed  failed";
						}
						}
						else{
						$rows['success'] = 0;
						$rows['message'] = "User inserted in tournament  failed";
						}	
						} else {
						$rows['success'] = 2;
						$rows['message'] = "New Coins Is UPDATE Failed";
						}
					
					
					
					}				
		}
			
	 


 }
 else {
		$rows['success'] = 3;
		$rows['message'] = "wrong user details";
}
}
echo (json_encode($rows));
?>