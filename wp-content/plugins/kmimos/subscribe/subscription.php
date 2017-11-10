<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
header("Content-Type: application/json; charset=utf-8");

/*
//echo 'algo: '.$JSONstar;
$fo = fopen("suscripcion.csv","w"); 
fwrite($fo,$JSONvariable);
fclose($fo);
*/




function mail_validate($mail){
	if($mail!='' && strpos($mail,'@')!==FALSE){
		return true;

	}else{
		return false;

	}
}


$file='subscription.csv';

$mail=$_POST['mail'];
$section=$_POST['section'];

$mail_exist='';
$datos=array();

$return=array();
$return['result']=true;
$return['message']='';
$return['data']='';


if(mail_validate($mail)){
	$fo = fopen($file, "r");
	while($data = fgetcsv ($fo,0,";")) {
		$datos[]=$data[0];
		if($data[0]==$mail){
			$mail_exist='y';
			//break;
		}
	}
	fclose($fo);


	if($mail_exist==''){
		$datos[]=$mail;
	}

	$fo = fopen($file, "w");
	foreach($datos as $dato){
		fwrite($fo,$dato."\n");
	}
	fclose($fo);


	//BD
	include_once(__DIR__.'/subscribe.php');
	$table = $_subscribe->table;
	$result = $_subscribe->result("SELECT * FROM $table WHERE email = '$mail'");
	if(count($result)==0){
		$_subscribe->insert(array('name' => $name  ,'email' => $mail , 'source' => $section,'time' => date('Y-m-d H:i:s', time())));
		$return['message']='Registro Exitoso. Por favor revisa tu correo en la Bandeja de Entrada o en No Deseados';
		$coupon=true;

		if($coupon && !email_exists($mail)){
			$amount=50;
			$code = 'SUBSCRIBE'.$section.'-'.$mail;
			$type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
			$expiry= time()+((60*60*24)*30);
			$data = array(
				'post_title' => $code,
				'post_content' => '',
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_type' => 'shop_coupon'
			);

			$couponID = wp_insert_post($data);
			update_post_meta($couponID, 'discount_type', $type);
			update_post_meta($couponID, 'coupon_amount', $amount);
			update_post_meta($couponID, 'individual_use', 'no');
			update_post_meta($couponID, 'product_ids', '');
			update_post_meta($couponID, 'exclude_product_ids', '');
			update_post_meta($couponID, 'usage_limit', '');
			update_post_meta($couponID, 'expiry_date', date('Y-m-d', $expiry));
			update_post_meta($couponID, 'apply_before_tax', 'yes');
			update_post_meta($couponID, 'free_shipping', 'no');

			/*//MAIL
			$subjet='Gracias por tu registro';
			$message='ten tu cupon '.$code;
			$message=kmimos_get_email_html($asunto, $message, 'Saludos,', false, true);
			wp_mail($mail,  $subjet, $message);*/
		}

	}else{
		$return['message']='Este correo ya est&aacute; registrado. Por favor intenta con uno nuevo';
	}

}else{
	$return['result']=false;
	$return['message']='El email es incorrecto';
}



$return['result']=true;
$return['data']=$datos;
echo json_encode($return);
//echo 'SU CORREO ELECTRÓNICO HA SIDO GUARDADO';



?>