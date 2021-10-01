<?php
// Create connection
include('config.php');
$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$user_id = $_POST['user_id'];
$t_id = $_POST['tournament_id'];
$points = $_POST['points'];
$id=$t_id."_player";
$tt=$t_id."_match";
$match=0;
$newpoints=0;
$match1=0;
$newpoints1=0;




if(!empty($user_id) && !empty($t_id)){	
	$res=mysqli_query($conn, "SELECT * FROM ".$id." WHERE user_id='".$user_id."'");
	$count = mysqli_num_rows($res);
    if($count > 0){
		 while($row = mysqli_fetch_assoc($res)){ 
		 $match=$row['total_match']+1;
		 $newpoints=$row['total_score']+$points;
		 }
			$sql = "UPDATE ".$id." SET total_match='".$match."', total_score='".$newpoints."' WHERE user_id ='".$user_id."';";
			if (mysqli_query($conn, $sql)) {
					$sql1 = "INSERT INTO ".$tt."(`id`, `user_id`, `score`, `match_no`) VALUES (NULL,'".$user_id."','".$points."','".$match."');";
					if (mysqli_query($conn, $sql1)) {
						
						$rows['success'] = 1;
						$rows['message'] = "Match Wining Updates successfully";  
					}
					else{
						$rows['success'] = 0;
						$rows['message'] = "Match Wining not Updates successfully";
						
					}
				}
				else{
					$rows['success'] = 2;
					$rows['message'] = "Match records not found";
				}
	}
	else{
			$rows['success'] = 4;
			$rows['message'] = "player is not registerted for tournament";
		}
}
else{
			$rows['success'] = 5;
			$rows['message'] = "fileds are empty";
}

  echo (json_encode($rows));
?>