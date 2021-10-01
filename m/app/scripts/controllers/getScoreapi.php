<?php
ob_start();
//$Url = 'http://synd.cricbuzz.com/j2me/1.0/livematches.xml';	
$str = file_get_contents('https://www.betfair.com/inplayservice/v1/scores?_ak=nzIFcwyWhrlwYMrh&alt=json&eventIds='.$_REQUEST["eventIds"].'&locale=en');
//$xml = simplexml_load_file($Url);
$json = json_encode($str);
echo $str;
ob_flush();
?>