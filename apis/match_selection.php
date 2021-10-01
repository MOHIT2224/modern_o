<?php   
include('config.php');
$market_id = $_GET['market_id'];
$url='http://185.3.95.140:1616/api/v1/result/?market_id='.$market_id.'';
	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
       $items = json_decode($result, true);
	    echo (json_encode($items)); 