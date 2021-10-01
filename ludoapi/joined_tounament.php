<?php
include('config.php');
$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$user_id = $params["user_id"];

$sql = "SELECT * FROM tbl_tournament WHERE tournament_status=5;";
$result = mysqli_query($conn, $sql);

$list=0;
$emty=array();
if (mysqli_num_rows($result) > 0) {
    $i=0;
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
		$id=$row["tournament_id"]."_player";
		$sql1 = "SELECT * FROM ".$id." WHERE user_id ='".$user_id."';";
		$result1 = mysqli_query($conn, $sql1);
		if (mysqli_num_rows($result1) > 0) {
         
         
              $rows['success'] = 1;
               $rows['message'] = "User Details";
                $rows['data'][$i] = $row;
		
			$list=$list+1;
            $i++;
		}else {
               $rows['success'] = 0;
              $rows['message'] = "Details";
              $rows['data'] =$emty;
 
}
		
        
    }
	
} else {
     $rows['success'] = 0;
    $rows['message'] = "Details";
    $rows['data'] =$emty;
    // echo "*0";
}
echo (json_encode($rows));
?>