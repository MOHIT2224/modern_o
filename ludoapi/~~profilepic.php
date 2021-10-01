<?php
include("config.php");    

$user_id = $_POST['user_id'];
       
 if (!empty($user_id) && !empty($_FILES)) {
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

   

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {

    $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
			                
				$rows['success'] = 0;
				$rows['message'] = "Image upload failed due to format.";
								
 		 echo (json_encode($rows)); 
				
				die();
// if everything is ok, try to upload file
} else { 
 
$target_file = $target_dir .  date('d_H_i_s') . '_'. $_FILES["file"]["name"];
 
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $image = 'http://'.$_SERVER['SERVER_NAME'].'/api/'.$target_file;
    } else {
			  $rows['success'] = 0;
                $rows['message'] = "Image upload failed due to unable to save picture.";
          				
 		    echo (json_encode($rows)); 
				
				die();
    }
}
            
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