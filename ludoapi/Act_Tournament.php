<?php
include('config.php');
	$emty=array();
	$sql = "SELECT * FROM tbl_tournament WHERE tournament_status ='5';";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$i=0;
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
        $rows['success'] = 1;
		$rows['message'] = "User Details";
		$rows['data'][$i] = $row;   
		$i++;
		}
	} 
	else {
        $rows['success'] = 0;
		$rows['message'] = "Details";
		$rows['data'] =$emty;
}
echo (json_encode($rows));
?>