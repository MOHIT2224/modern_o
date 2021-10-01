<?php
include('config.php');
require('razorpay-php/Razorpay.php');
session_start();
 $userid = $_GET['userid'];
$amountn = $_GET['amount'];
$email = 'norply@gaminggaze.com';
$name = $_GET['name'];
$contact = $_GET['contact'];
//$contact = '8003621369';
$getaway = $_GET['getaway']; 

if($amountn < 100){ ?>
	

<form method="post" name="redirect" action="failed.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>	
	<?php	die;
exit;		
		
	}


?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0" />
    <title>Payment</title>
	<link rel="stylesheet" href="css/style.css"> 
	<body style="backgro">
	 <img src="http://gaminggaze.com/api/css/logo.png" style='max-width:250px'> 
	<?php
echo '<h2>'.$amountn.' INR </h2>';
      function generateRandomString($length = 8) {
                $characters = '0123456789';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
			
			
 $orderid = generateRandomString();
 
 $orderData = [
    'receipt'         => 3456,
    'amount'          => $amountn * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];



// Create the Razorpay Order
 
use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

 
$orderData = [
    'receipt'         => 3456,
    'amount'          => $amountn * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

 
 
$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
} 
 
$trnsid='0';
$status='In Process';
$trnstype='desposite';
$newcoins = $amountn; 
//$getaway='razorpay';

//$insertquery =mysqli_query($conn,"update users set coins='".$newcoins."' WHERE id='".$userid."'");

 $sqlr="INSERT INTO `payment` (`pid`, `uid`, `orderid`, `trnsid`, `status`, `trnstype`, `amount`, `getway`) VALUES (NULL, '".$userid."','".$orderid."','".$orderid."', '".$status."','".$trnstype."','".$newcoins."','".$getaway."')";
 $result = mysqli_query($conn, $sqlr);
 //echo $sqlr;

$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "Coins",
    "description"       => "Coins",
    "image"             => "http://ludofame.com/img/logo.png",
    "prefill"           => [
    "name"              => $name,
    "email"             => $email,
    "contact"           => $contact,
    ],
    "notes"             => [
    "address"           => "Hello World",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
	"userid"             => $userid,
	"horderid"             => $orderid,
	"orderamount"           => $amountn,
	"mobile"           => $contact,
	"emailid"           => $email,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

if($getaway=='razorpay2'){
 
require("checkout/cashfree.php");

}



 if($getaway=='PayU'){
require("checkout/payu.php");
 }

 if($getaway=='PAYTM'){
require("checkout/paytm.php");
 }

 if($getaway=='razorpay'){
require("checkout/automatic.php");
 }
 if($getaway=='paypal'){
	 $paypaldata = [
	
	"userid"             => $userid,
	"order_id"             => $orderid,
	"orderamount"           => $amountn,
	"mobile"           => $contact,
	"emailid"           => $email,
	 ];
	 $json = json_encode($paypaldata);
	 
require("checkout/paypal.php");
 }
?> 

<a href="failed.php" style="
    margin-top: 20px;
    display: block;
    border-radius: 0;
    background: #b7d416;
" class='razorpay-payment-button'>BACK TO APP </a> 

 </body>
</html>