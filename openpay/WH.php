<?php
	include("openpay/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( ($OPENPAY_PRUEBAS == false) );

	$webhook = array(
	    'url' => 'http://kmimosmx.sytes.net/QA2/openpay/RWH.php',
	    'event_types' => array(
	      	'charge.refunded',
	      	'charge.failed',
	      	'charge.cancelled',
	      	'charge.created',
	      	'chargeback.accepted'
	    )
	);

	$webhook = $openpay->webhooks->create($webhook);
?>