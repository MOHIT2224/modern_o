<?php
error_reporting(2);
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");


$data = array(  "request" => array( "requestType" => null,
                                    "merchantGuid" => PAYTM_MERCHANT_GUID,
                                    "merchantOrderId" => "Order00000001",
                                    "salesWalletName"=> null,
                                    "salesWalletGuid"=>PAYTM_SALES_WALLET_GUID,
                                    "payeeEmailId"=>null,
                                    "payeePhoneNumber"=>"9001111198",
                                    "payeeSsoId"=>"",
                                    "appliedToNewUsers"=>"Y",
                                    "amount"=>"1",
                                    "currencyCode"=>"INR"
                                ),
                "metadata"=>"Testing Data",
                "ipAddress"=>"127.0.0.1",
                "platformName"=>"PayTM",
                "operationType"=>"SALES_TO_USER_CREDIT");

$requestData=json_encode($data);


$Checksumhash = getChecksumFromString($requestData,PAYTM_MERCHANT_KEY);
$headerValue = array('Content-Type:application/json','mid:'.PAYTM_MERCHANT_GUID,'checksumhash:'.$Checksumhash);
echo "<pre>";
print_r( $headerValue);

$ch = curl_init(PAYTM_GRATIFICATION_URL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);   
curl_setopt($ch, CURLOPT_HTTPHEADER, $headerValue);
$info = curl_getinfo($ch);
$result = curl_exec($ch);

echo '<br/><br/><b>INFO :</b>';
print_r($info);
echo '<br/><br/><b>RESULT :</b> ' .$result;

?>