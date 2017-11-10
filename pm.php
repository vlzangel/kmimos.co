<?php
	require("wp-content/themes/kmimos/lib/phpmailer/class.smtp.php");
	require("wp-content/themes/kmimos/lib/phpmailer/class.phpmailer.php");

	$mail = new PHPMailer();

/*	echo "<pre>";
		print_r($mail);
	echo "</pre>";*/

	//Luego tenemos que iniciar la validación por SMTP:
	//$mail->Mailer = "smtp";
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Host = "smtp.gmail.com"; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
	$mail->Username = "soporte.kmimos@gmail.com"; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente. 
	$mail->Password = "@km!m05@"; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
	$mail->SMTPSecure = "tls";
	$mail->Port = 587; // Puerto de conexión al servidor de envio. 
	$mail->From = "Angel@mail.com"; // A RELLENARDesde donde enviamos (Para mostrar). Puede ser el mismo que el email creado previamente.
	$mail->FromName = "Angel Veloz"; //A RELLENAR Nombre a mostrar del remitente. 
	$mail->AddAddress("vlzangel91@gmail.com"); // Esta es la dirección a donde enviamos 
	$mail->Subject = "Titulo"; // Este es el titulo del email. 
	$body = "Hola mundo. Esta es la primer línea "; 
	$body .= "Aquí continuamos el mensaje"; 
	$mail->Body = $body; // Mensaje a enviar. 

	$mail->smtpConnect([
		    'ssl' => [
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    ]
		]);

	$mail->IsHTML(true); // El correo se envía como HTML 

	$exito = $mail->Send(); // Envía el correo.
	if($exito){ 
		echo "El correo fue enviado correctamente."; 
	}else{ 
		echo "Hubo un problema. Contacta a un administrador."; 
	}
?>