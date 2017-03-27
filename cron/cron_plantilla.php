<?php
	
	echo "<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto' media='all' />";

	if($request->post_type == 'request'){
        $title = 'Kmimos Colombia - Cancelación de Solicitud de Conocer Cuidador.';
        $tipo = 'para que te conozcan';
    }else {
        $title = 'Kmimos Colombia - Cancelación de Solicitud de Reserva.';
        $tipo = 'confirmación de reserva';
    }

    $message = '<p><strong>Hola '.$cliente.',</strong></p>';
    $message .= '<p align="justify">Aún no hemos recibido confirmación por parte del cuidador <strong>'.$cuidador_post->post_title.'</strong> sobre tu solicitud de <strong>'.$tipo;
    $message .= '</strong>, por lo que entendemos que no estaría disponible para atenderte. La solicitud está siendo cancelada por el sistema.  Sabemos lo importante que es para ti encontrar el lugar adecuado para que cuiden a tu peludo, por lo que te compartimos estas opciones con características similares a tu búsqueda original. Solo debes seguir los siguientes pasos:</p>';
 
    $message .=  $str_sugeridos;


?>