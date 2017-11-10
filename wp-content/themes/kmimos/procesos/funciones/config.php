<?php
	$OPENPAY_PRUEBAS = 1;

	$OPENPAY_URL = ( $OPENPAY_PRUEBAS == 1 ) ? "https://sandbox-dashboard.openpay.mx" : "https://dashboard.openpay.mx";
	
	$MERCHANT_ID = "mbagfbv0xahlop5kxrui";
	$OPENPAY_KEY_SECRET = "sk_b485a174f8d34df3b52e05c7a9d8cb22";
	$OPENPAY_KEY_PUBLIC = "pk_dacadd3820984bf494e0f5c08f361022";
	
	if( $OPENPAY_PRUEBAS == 1 ){
		$MERCHANT_ID = "mej4n9f1fsisxcpiyfsz";
		$OPENPAY_KEY_SECRET = "sk_684a7f8598784911a42ce52fb9df936f";
		$OPENPAY_KEY_PUBLIC = "pk_3b4f570da912439fab89303ab9f787a1";
	}
?>