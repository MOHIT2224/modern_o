<?php
/*
- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.
*/
define('PAYTM_ENVIRONMENT', 'TEST'); // PROD
define('PAYTM_MERCHANT_KEY', '8ERud6DU6YpcUMsh'); //Change this constant's value with Merchant key downloaded from portal
define('PAYTM_MERCHANT_MID', 'Gaming72044093245133'); //Change this constant's value with MID (Merchant ID) received from Paytm
define('PAYTM_MERCHANT_GUID', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); //Change this constant's value with MGUID (Merchant Guid) received from Paytm
define('PAYTM_SALES_WALLET_GUID', 'bddd5963-b499-11ea-8708-fa163e429e83'); //Change this constant's value with Sales Wallet Guid received from Paytm
define('PAYTM_MERCHANT_WEBSITE', 'xxxxxxx'); //Change this constant's value with Website name received from Paytm

$PAYTM_DOMAIN = "pguat.paytm.com";
$PAYTM_WALLET_DOMAIN = "trust-uat.paytm.in";
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
	$PAYTM_WALLET_DOMAIN = "trust.paytm.in"
}

define('PAYTM_REFUND_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND');
define('PAYTM_STATUS_QUERY_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS');
define('PAYTM_TXN_URL', 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction');
define('PAYTM_GRATIFICATION_URL', 'https://'.$PAYTM_WALLET_DOMAIN.'/wallet-web/salesToUserCredit');

?>