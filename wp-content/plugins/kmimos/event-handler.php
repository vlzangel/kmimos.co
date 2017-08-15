<?php
	

    define('WP_USE_THEMES', false);
    require('../../../wp-blog-header.php');

    extract($_GET);

    $order = new WC_Order($o);
    if($s == "0"){
		$order->update_status('cancelled');
		$msg = "Orden Cancelada Exitosamente!";
    }else{
		$order->update_status('completed');
		$msg = "Orden Completada Exitosamente!";
    }

    $url = get_home_url();

	echo "
		<script>
			alert('$msg');
			location.href = '$url';
		</script>
	";

?>