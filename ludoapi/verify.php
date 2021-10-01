<?php
require('config.php');

session_start();

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}





if ($success === true)
{
	
	$html['success'] = 1;
    $html['message'] = "Your payment was successful";
	$order_id = $_POST['shopping_order_id'];
	$payid = $_POST['razorpay_payment_id'];
	$userid = $_POST['userid'];
	$orderamount = $_POST['orderamount'];
	$mobile = $_POST['mobile'];
	$emailid = $_POST['emailid'];
	$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	 
while($row = mysqli_fetch_assoc($sql)){ 

$newcoins = $row['coins']+ $orderamount;  
$bonus = $row['bonus']+ 10;

}
$insertquery =mysqli_query($conn,"update users set coins='".$newcoins."', bonus='".$bonus."' WHERE id='".$userid."'");
	
	
$insertq =mysqli_query($conn,"update payment set status='Success',trnsid='".$payid."' WHERE uid='".$userid."' and orderid='".$order_id."' ");

$sqler=mysqli_query($conn, "SELECT * FROM payment WHERE uid='".$userid."' and status='Success'");
	$count = mysqli_num_rows($sqler);
	    if($count > 0){
			
			
			$sqls=mysqli_query($conn, "SELECT * FROM refered WHERE uid='".$userid."' and status='0'");
	$countss = mysqli_num_rows($sqls);
	    if($countss > 0){
			
			
		 $insert =mysqli_query($conn,"update refered set status='1' WHERE uid='".$userid."'");
$gatrcode = mysqli_fetch_assoc($sqls);		
	 $refercode = $gatrcode['rcode'] ;
		 
		 $gtrc=mysqli_query($conn, "SELECT * FROM users WHERE refercode='".$refercode."'");
		 
		
		$rowss = mysqli_fetch_assoc($gtrc);
		$bonus = $rowss['bonus'] + 15;		
		$insertquery =mysqli_query($conn,"update users set bonus='".$bonus."' WHERE refercode='".$refercode."'");
		
		
		
		
		$usersql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
		$rowuser = mysqli_fetch_assoc($usersql);
		$bonusu = $rowuser['bonus'] + 30;
		
	   $insert =mysqli_query($conn,"update users set bonus='".$bonusu."', refer='".$refercode."' WHERE id='".$userid."'");
	   
		
		
		}
			
			
			
		} 
 
			?>
			
			
			
			
			<form method="post" name="redirect" action="success.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
<?php 	}
else
{
	 
		$html['success'] = 0;
		$html['message'] = $error; ?>
		
		<form method="post" name="redirect" action="failed.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
 <?php
 
}

 

$json = json_encode($html);
?>