<?php
require('config.php');

$secretkey = $cashkey;
$orderId = $_POST["orderId"];
$orderAmount = $_POST["orderAmount"];  
$referenceId = $_POST["referenceId"];
$txStatus = $_POST["txStatus"];
$paymentMode = $_POST["paymentMode"];
$txMsg = $_POST["txMsg"];
$txTime = $_POST["txTime"];
$signature = $_POST["signature"];
$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
$hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
$computedSignature = base64_encode($hash_hmac);
if ($signature == $computedSignature) {
	if ($txStatus=='SUCCESS'){
		$html['success'] = 1;
        $html['message'] = "Your payment was successful";

        $order_id =  $orderId;

        $orderamount =  $orderAmount;

        $guid=mysqli_query($conn, "SELECT uid FROM payment WHERE orderid='".$order_id."'");
        $countd = mysqli_num_rows($guid);
        if($countd > 0){
		    $gaguid = mysqli_fetch_assoc($guid);		
            $userid = $gaguid['uid'] ;
	    }

    	$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
 
        while($row = mysqli_fetch_assoc($sql)){ 
            $newcoins = $row['coins']+ $orderamount;  
        }
        
        $insertquery =mysqli_query($conn,"update users set coins='".$newcoins."' WHERE id='".$userid."'");
        $insertq =mysqli_query($conn,"update payment set status='Success' WHERE uid='".$userid."' and orderid='".$order_id."' ");
        $sqler=mysqli_query($conn, "SELECT * FROM payment WHERE uid='".$userid."' and status='Success'");
        $count = mysqli_num_rows($sqler);
        if($count > 0){
			$sqls=mysqli_query($conn, "SELECT * FROM refered WHERE uid='".$userid."' and status='1'");
            $countss = mysqli_num_rows($sqls);
            if($countss > 0){
        		$insert =mysqli_query($conn,"update refered set status='2' WHERE uid='".$userid."'");
                $gatrcode = mysqli_fetch_assoc($sqls);		
                $refercode = $gatrcode['rcode'] ;
	            $gtrc=mysqli_query($conn, "SELECT * FROM users WHERE refercode='".$refercode."'");
        		$rowss = mysqli_fetch_assoc($gtrc);
	            $bonus = $rowss['bonus'] + 50;		
	            $insertquery =mysqli_query($conn,"update users set bonus='".$bonus."' WHERE refercode='".$refercode."'");
        		$usersql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	            $rowuser = mysqli_fetch_assoc($usersql);
	            $bonusu = $rowuser['bonus'] + 100;
        	   $insert =mysqli_query($conn,"update users set bonus='".$bonusu."', refer='".$refercode."' WHERE id='".$userid."'");
	        }
		}
		?>
		<form method="post" name="redirect" action="success.php">
            <input type="hidden" name="orderId" value='<?php echo $orderId; ?>'/>
	    </form>
	    </center>
	    <script language='javascript'>document.redirect.submit();</script>
<?php 	
	} else if ($txStatus=='FAILED'){
	    $html['success'] = 0;
        $html['message'] = "Payment Failed. Please check with your bank.";
    	?>
	    <form method="post" name="redirect" action="failed.php?message='Payment Failed. Please check with your bank.'">
	    </form>
	    </center>
	    <script language='javascript'>document.redirect.submit();</script>
<?php 
		 echo 'Payment Failed. Please check with your bank.';
	} else if ($txStatus=='CANCELLED'){
	    $html['success'] = 0;
        $html['message'] = "Payment Cancelled by the user.";
    	?>
	    <form method="post" name="redirect" action="failed.php?message='Payment Cancelled by the user.'">
	    </form>
	    </center>
	    <script language='javascript'>document.redirect.submit();</script>
<?php
        echo 'Payment Cancelled by the user.';
	}
} else {
	?>
	<form method="post" name="redirect" action="failed.php?message='Signature Verification failed'">
	</form>
	</center>
	<script language='javascript'>document.redirect.submit();</script>
<?php 
		 echo 'Signature Verification failed';
}