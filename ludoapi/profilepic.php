<?php
include("config.php");    

$user_id = $_POST['user_id'];
 $img = $_POST['img']; 
   
 
 if (!empty($user_id) && !empty($img)) {

define('UPLOAD_DIR', 'uploads/');
	
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . uniqid() . '.png';
	$success = file_put_contents($file, $data); 
	 $image = 'http://'.$_SERVER['SERVER_NAME'].'/api/'.$file;
  
            
  $sql_update = "UPDATE `users` SET `profilepic` = '$image'  WHERE id='".$user_id."'";
            
            $result_update = mysqli_query($conn, $sql_update);
            if ($result_update) {
				  $rows['success'] = 1;
				$rows['message'] = "Profile pic uploaded successfully ";
				 $rows['image'] = $image;
				 echo (json_encode($rows)); 
				die();
            }
               
            } else {

               $rows['success'] = 0;
                $rows['message'] = "Authentication failed.";
          				
 		    echo (json_encode($rows)); 
			 
				die();
            } 

?>