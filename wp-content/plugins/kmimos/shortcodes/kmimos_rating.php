<?php

global $current_user;

global $wpdb;

global $redirect_to;



date_default_timezone_set('America/Mexico_City');

$user_id = $current_user->ID;
    if($user_id==0) {
        echo '<h1>Debes iniciar sesión</h1>';
        wp_redirect(get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    }

    if($_POST['funcion'] =='rate'){
        $post_id = $_POST['id'];
        $time = current_time('mysql');
        $agent = ($_SERVER[‘HTTP_USER_AGENT’]!='')? $_SERVER[‘HTTP_USER_AGENT’] : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)';
        $servicio = get_post(get_post_meta($post_id,'_booking_product_id',true));
        $petsitter_id = $servicio->post_parent;
        $data = array(
            'comment_post_ID' => $petsitter_id,
            'comment_author' => $current_user->display_name,
            'comment_author_email' => $current_user->user_email,
            'comment_content' => sanitize_text_field($_POST['comentarios']),
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_author_IP' => $_SERVER[‘REMOTE_ADDR’],
            'comment_agent' => $agent,
            'comment_date' => $time,
            'comment_approved' => 1,
        );
        $comment_id = wp_insert_comment($data);
        update_post_meta($post_id, 'customer_comment', $comment_id);
        update_comment_meta( $comment_id, 'care', $_POST['cuidado'] );
        update_comment_meta( $comment_id, 'punctuality', $_POST['puntualidad'] );
        update_comment_meta( $comment_id, 'cleanliness', $_POST['limpieza'] );
        update_comment_meta( $comment_id, 'trust', $_POST['confianza'] );
        vlz_actualizar_ratings($petsitter_id);
        echo '<h1>Valoración Enviada</h1>';
        echo "<p>Gracias por regalarnos tu evaluación, es sumamente importante para Kmimos y todos los que somos parte de esta comunidad saber lo que opinas del servicio que has recibido.</p>";
        echo '<a class="kmi_link" href="'.get_home_url().'/perfil-usuario/?ua=invoices">Volver a mis reservas</a>';
    
    }elseif(isset($_GET['id'])){
        $post_id = $_GET['id'];
        if($post_id==''){
            echo "Selecciona el cuidador que deseas valorar";
            return false;
        }else{
           $reserva = get_post($post_id);
            if(get_post_meta($post_id, 'customer_comment', true) != '') {
                echo '<h1>La reserva ya ha sido valorada</h1>';
                echo '<a class="kmi_link" href="'.get_home_url().'/perfil-usuario/?ua=invoices">Volver a mis reservas</a>';
            }else{
                if($reserva->post_author != $user_id) {
                    echo '<h1>La reserva seleccionada no perteneca al usuario</h1>';
                    echo '<a class="kmi_link" href="'.get_home_url().'/perfil-usuario/?ua=invoices">Volver a mis reservas</a>';
                }else{
                    $desde = get_post_meta($post_id,'_booking_start',true);
                    $hasta = get_post_meta($post_id,'_booking_end',true);
                    $lleno = get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png';
                    $vacio = '<img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/vacio.png">';
                    $petsitter = get_post( $post_id );
                    ?>
                        <style>
                            .fila_rate div { display: inline-block; text-align: center; height: 30px; vertical-align: top; height: 100px;}
                            .fila_rate div img { width: 30px; margin-top: -30px; }
                            .fila_rate div input.huesito { display: none; }
                            .reset_rate { margin-right: 40px; }
                        </style>
                        <h1>Valoración del servicio:</h1>
                        <h2><?php echo $servicio->post_title; ?></h2>
                        <h4><?php 
                            echo "Desde: ".substr($desde,6,2)."/".substr($desde,4,2)."/".substr($desde,0,4); 
                            echo ", Hasta: ".substr($hasta,6,2)."/".substr($hasta,4,2)."/".substr($hasta,0,4); 
                        ?>
                        </h4>
                        <p>Te pedimos evalúes del 1 al 5 con huesitos, los siguientes rubros. Considerando que 1 es la calificación más baja y 5 la más alta.</p>
                        <form id="request_form" method="post" action="<?php echo get_home_url(); ?>/valorar-cuidador/">
                            <h3>Sección de cuidado:</h3>
                            <p><strong>¿Cómo consideras que fue el cuidado que recibió tu peludo?</strong></p>
                            <div class="fila_rate" data-section="cuidado">
                                <div class="reset_rate cuidado" data-section="cuidado"><label for="cuidado_0"><h4>No Aplica</h4></label><br><input type="radio" id="cuidado_0" name="cuidado" value="0" checked></div>
                                <?php 
                                    for($i=1;$i<=5;$i++){ ?>
                                    <div class="select_rate cuidado" data-rate="<?php echo $i;?>" data-section="cuidado">
                                        <label for="cuidado_<?php echo $i;?>"><h4><?php echo $i;?></h4></label><br>
                                        <input type="radio" id="cuidado_<?php echo $i;?>" name="cuidado" value="<?php echo $i;?>" class="huesito"><br><?php echo $vacio;?>
                                    </div>
                                <?php    
                                } ?>
                            </div>
                            <hr>
                            <h3>Sección de Puntualidad</h3>
                            <p><strong>¿Cómo calificarías la puntualidad de tu cuidador a la hora de recoger o entregar a tu mascota?</strong></p>
                            <div class="fila_rate" data-section="puntualidad">
                                <div class="reset_rate puntualidad" data-section="puntualidad">
                                    <label for="puntualidad_0">
                                    <h4>No Aplica</h4></label><br>
                                    <input type="radio" id="puntualidad_0" name="puntualidad" value="0" checked>
                                </div>
                                <?php 
                                for($i=1;$i<=5;$i++){ ?>
                                    <div class="select_rate puntualidad" data-rate="<?php echo $i;?>" data-section="puntualidad">
                                        <label for="puntualidad_<?php echo $i;?>"><h4><?php echo $i;?></h4></label><br>
                                        <input type="radio" id="puntualidad_<?php echo $i;?>" name="puntualidad" value="<?php echo $i;?>" class="huesito"><br>
                                        <?php echo $vacio;?>
                                    </div>
                                <?php    
                                } ?>
                            </div><hr>
                            <h3>Sección de Limpieza</h3>
                            <p><strong>¿Cuál es el nivel de higiene y seguridad que consideras que tiene el hogar del cuidador?</strong></p> 
                            <div class="fila_rate" data-section="limpieza">
                                <div class="reset_rate limpieza" data-section="limpieza">
                                    <label for="limpieza_0"><h4>No Aplica</h4></label><br>
                                    <input type="radio" id="limpieza_0" name="limpieza" value="0" checked>
                                </div>
                                <?php 
                                for($i=1;$i<=5;$i++){ ?>
                                    <div class="select_rate limpieza" data-rate="<?php echo $i;?>" data-section="limpieza">
                                        <label for="limpieza_<?php echo $i;?>"><h4><?php echo $i;?></h4></label><br>
                                        <input type="radio" id="limpieza_<?php echo $i;?>" name="limpieza" value="<?php echo $i;?>" class="huesito"><br>
                                        <?php echo $vacio;?>
                                    </div>
                                <?php    
                                } ?>
                            </div><hr>
                            <h3>Sección de Confianza</h3>
                            <p><strong>¿Qué tan confiable consideras que tu cuidador es?</strong></p>
                            <div class="fila_rate" data-section="confianza">
                                <div class="reset_rate confianza" data-section="confianza">
                                    <label for="confianza_0"><h4>No Aplica</h4></label><br>
                                    <input type="radio" id="confianza_0" name="confianza" value="0" checked>
                                </div>
                                <?php 
                                for($i=1;$i<=5;$i++){ ?>
                                    <div class="select_rate confianza" data-rate="<?php echo $i;?>" data-section="confianza">
                                        <label for="confianza_<?php echo $i;?>"><h4><?php echo $i;?></h4></label><br>
                                        <input type="radio" id="confianza_<?php echo $i;?>" name="confianza" value="<?php echo $i;?>" class="huesito"><br>
                                        <?php echo $vacio;?>
                                    </div>
                                <?php    
                                } ?>
                            </div><hr>
                            <h3>Cuéntale a todos lo que más te gusto de tu cuidador y si lo recomendarías</h3>
                            <p><strong>Comentarios:</strong></p>
                            <textarea id="comentarios" name="comentarios" style="width: 100%; max-width: 600px; height: 120px;"></textarea>
                            <br>
                            <input type="hidden" name="funcion" value="rate">
                            <input type="hidden" name="id" value="<?php echo $post_id; ?>">
                            <input type="submit" id="request-button" class="boton_aplicar_filtros" value="Enviar valoración">
                        </form>
                    <?php

                }

            } 
        }   
    
            
    }
?>
<script>
    jQuery.noConflict(); 
    jQuery(document).ready(document).ready(function() {
        var values = {cuidado:'0', puntualidad:'0',limpieza:'0', confianza:'0'};
        jQuery(".select_rate").on("mouseout",function(){
            var section = jQuery(this).attr("data-section");
            if(values[section]=='0'){
                jQuery(".select_rate."+section+" > img").each(function(index){
                    jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/vacio.png");
                });
            }
        });

        jQuery(".fila_rate").on("mouseout",function(){
            var section = jQuery(this).attr("data-section");
            if(values[section]=='0'){
                jQuery(".select_rate."+section+" > img").each(function(index){
                    jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/vacio.png");
                });
            }
        });

        jQuery(".reset_rate").on("click",function(){
            var section = jQuery(this).attr("data-section");
            jQuery(".select_rate."+section+" > img").each(function(index){
                jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/vacio.png");
            });
            values[section]='0';
        });

        jQuery(".select_rate").on("click",function(){
            var rate = jQuery(this).attr("data-rate");
            var section = jQuery(this).attr("data-section");
            console.log('Seleccionando '+rate+' en la seccion '+section);
            jQuery(".select_rate."+section+" > img").each(function(index){
                jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/vacio.png");
            });
            jQuery("#"+section+"_"+rate).prop("checked",true);
            values[section]=rate;
            jQuery(".select_rate."+section+" > img").each(function(index){
                if(index < rate) jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/100.png");
                else jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/vacio.png");
            });
        });

        jQuery(".select_rate").on("mouseover",function(){
            var rate = jQuery(this).attr("data-rate");
            var section = jQuery(this).attr("data-section");

            if(values[section]=='0'){
                console.log('Activando hasta '+rate+' en la seccion '+section+', seleccionado '+values[section]);
                jQuery(".select_rate."+section+" > img").each(function(index){
                    if(index < rate) jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/100.png");
                    else jQuery(this).attr("src","<?php echo get_home_url(); ?>/wp-content/plugins/kmimos/assets/rating/vacio.png");
                });
            }
        });
    });
</script>

