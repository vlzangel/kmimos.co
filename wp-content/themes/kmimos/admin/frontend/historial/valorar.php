<?php
    global $current_user;
    $user_id = $current_user->ID;
    if($user_id==0) {
        $CONTENIDO .= "  <h1>Debes iniciar sesión</h1>";
        wp_redirect( get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']) );
    }

    $post_id = vlz_get_page();

    $reserva = get_post($post_id);
    if(get_post_meta($post_id, 'customer_comment', true) != '') {

        $CONTENIDO .= "
            <h1>La reserva ya ha sido valorada</h1>
            <div class='container_btn'>
                <a class='km-btn-primary' href='".get_home_url()."/perfil-usuario/historial/'>Volver a mis reservas</a>
            </div>
        ";

        $mostrar_btn = false;

    }else{
        
        if($reserva->post_author != $user_id) {
            $CONTENIDO .= "
                <h1>La reserva seleccionada no pertenece a tu usuario</h1>;
                <div class='container_btn'><a class='km-btn-primary' href='".get_home_url()."/perfil-usuario/?ua=invoices'>Volver a mis reservas</a></div>
            ";
        }else{

            $desde = get_post_meta($post_id,'_booking_start',true);
            $hasta = get_post_meta($post_id,'_booking_end',true);
            $lleno = get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg";
            $vacio = "<img src='".get_home_url()."/wp-content/themes/kmimos/images/new/icon/icon-hueso.svg'>";
            
            $servicio = get_post( get_post_meta($post_id, '_booking_product_id', true) );
            $petsitter_id = $servicio->post_parent;
            
            $CUIDADO = "";
            for( $i=1; $i<=5; $i++ ){ 
                $CUIDADO .= "
                    <div class='select_rate cuidado' data-rate='$i' data-section='cuidado'>
                        <label for='cuidado_$i'><h5> $i</h5></label><br>
                        <input type='radio' id='cuidado_$i' name='cuidado' value='$i' class='huesito'><br>
                        $vacio
                    </div>
                ";
            } 
            
            $PUNTUALIDAD = "";
            for( $i=1; $i<=5; $i++ ){ 
                $PUNTUALIDAD .= "
                    <div class='select_rate puntualidad' data-rate='$i' data-section='puntualidad'>
                        <label for='puntualidad_$i'><h5> $i</h5></label><br>
                        <input type='radio' id='puntualidad_$i' name='puntualidad' value='$i' class='huesito'><br>
                        $vacio
                    </div>
                ";
            } 
            
            $LIMPIEZA = "";
            for( $i=1; $i<=5; $i++ ){ 
                $LIMPIEZA .= "
                    <div class='select_rate limpieza' data-rate='$i' data-section='limpieza'>
                        <label for='limpieza_$i'><h5> $i</h5></label><br>
                        <input type='radio' id='limpieza_$i' name='limpieza' value='$i' class='huesito'><br>
                        $vacio
                    </div>
                ";
            } 
            
            $CONFIANZA = "";
            for( $i=1; $i<=5; $i++ ){ 
                $CONFIANZA .= "
                    <div class='select_rate confianza' data-rate='$i' data-section='confianza'>
                        <label for='confianza_$i'><h5> $i</h5></label><br>
                        <input type='radio' id='confianza_$i' name='confianza' value='$i' class='huesito'><br>
                        $vacio
                    </div>
                ";
            } 

            $CONTENIDO .= "  
                <div style='width: 100%; max-width: 600px; text-align: left;'>
                    <h1>Valoración del servicio:</h1>
                    <h2>".$servicio->post_title."</h2>
                    <h5> 
                        Desde: ".substr($desde,6,2)."/".substr($desde,4,2)."/".substr($desde,0,4). ", 
                        Hasta: ".substr($hasta,6,2)."/".substr($hasta,4,2)."/".substr($hasta,0,4)."
                    </h5>
                    
                    <p>Te pedimos evalúes del 1 al 5 con huesitos, los siguientes rubros. Considerando que 1 es la calificación más baja y 5 la más alta.</p>
                    
                        <h3>Sección de cuidado:</h3>
                        <p><strong>¿Cómo consideras que fue el cuidado que recibió tu peludo?</strong></p>
                        <div class='fila_rate' data-section='cuidado'>
                            {$CUIDADO}
                        </div>   
                        <hr>

                        <h3>Sección de Puntualidad</h3>
                        <p><strong>¿Cómo calificarías la puntualidad de tu cuidador a la hora de recoger o entregar a tu mascota?</strong></p>
                        <div class='fila_rate' data-section='puntualidad'>
                            {$PUNTUALIDAD}
                        </div>
                        <hr>

                        <h3>Sección de Limpieza</h3>
                        <p><strong>¿Cuál es el nivel de higiene y seguridad que consideras que tiene el hogar del cuidador?</strong></p> 
                        <div class='fila_rate' data-section='limpieza'>
                            {$LIMPIEZA}
                        </div>
                        <hr>

                        <h3>Sección de Confianza</h3>
                        <p><strong>¿Qué tan confiable consideras que tu cuidador es?</strong></p>
                        <div class='fila_rate' data-section='confianza'>
                            {$CONFIANZA}
                        </div>
                        <hr>

                        <h3>Cuéntale a todos lo que más te gusto de tu cuidador y si lo recomendarías</h3>
                        <p><strong>Comentarios:</strong></p>
                        <textarea id='comentarios' name='comentarios' style='width: 100%; max-width: 600px; height: 120px;'></textarea>
                        <br>
                        <input type='hidden' name='accion' value='valorar'>
                        <input type='hidden' name='user_id' value='{$user_id}'>
                        <input type='hidden' name='post_id' value='{$post_id}'>
                        <input type='hidden' name='petsitter_id' value='{$petsitter_id}'>
                             
                </div>";
                  
        }
   
    }
?>