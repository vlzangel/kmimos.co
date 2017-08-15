            </div>
        </div>

        <div id="pf-membersystem-dialog"></div>
        <a title="<?php esc_html_e('Back to Top','pointfindert2d'); ?>" class="pf-up-but"><i class="pfadmicon-glyph-859"></i></a>
    </div>

    <?php $info = kmimos_get_info_syte(); ?>
    <footer class="wpf-footer">            
        <div class="container" style="overflow: hidden;">
            <div class="row">
                <div class="col-xs-12 jj-xs-offiset-2 col-sm-4 col-md-3 col-lg-3 col-lg-offset-2 left">
                    <h2>Contáctanos</h2>
                    <p>
                    <strong>Tlf: </strong> <?php echo $info["telefono"]; ?><br>
                    <strong>Email: </strong>  <?php echo $info["email"]; ?>
                </div>
                <div class="col-sm-4 jj-xs-offiset-2 col-md-3 center col-lg-3 center">
                    <h2>Navega</h2>
                    <ul>
                        <li><a href="#">Nosotros</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Preguntas y Respuestas</a></li>
                        <li><a href="#">Cobertura Veterinaria</a></li>
                        <li><a href="#">Comunicados de prensa</a></li>
                        <li><a href="<?php echo get_home_url(); ?>/terminos-y-condiciones/">Términos y Condiciones</a></li>
                        <li><a href="#">Nuestros Aliados</a></li>
                        <li><a href="<?php echo get_home_url();?>/contacto/">Contáctanos</a></li>
                    </ul>
                </div>

                <div class="hidden-xs col-sm-4  col-md-3 col-lg-3 right">
                    <h2>¡B&uacute;scanos en nuestra redes sociales!</h2>
                    <div class="socialBtns">
                        <a href="https://www.facebook.com/<?php echo $info["facebook"]; ?>/" target="_blank" class="facebookBtn socialBtn" title="kmimos"></a>
                        <a href="https://twitter.com/<?php echo $info["twitter"]; ?>" target="_blank"class="twitterBtn socialBtn" title="@<?php echo $info["twitter"]; ?>"></a>
                        <a href="#" target="_blank" class="instagramBtn socialBtn" title="@<?php echo $info["instagram"]; ?>"></a>
                        <img src="<?php bloginfo( 'template_directory' ); ?>/images/dog.png" alt="">
                    </div>
                </div>
            </div> 
        </div>
        <div class="jj-xs-offiset-2 col-md-offset-1 col-md-offset-3 jj-offset-2">
            <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=c5u9pjdoyKXQ6dRtmwnDmY0bV6KVBrdZGPEAnPkeSt7ZRCetPjIUzVK0bnHa"></script></span>   
        </div>
    </footer>
    <?php wp_footer(); ?>

        <style type="text/css">
            .wcvendors_sold_by_in_loop{
                display: none !important;
            }
            .wc-bookings-booking-form .wc-bookings-booking-cost{
                margin: 0px 0px 10px !important;
            }
            .wc-bookings-booking-cost{
                position: relative !important;
                left: initial;
                margin-left: 0px !important;
                top: 0px !important;
            }

            .product .related{
                clear: both !important;
            }
            
            .switch-candy span {
                color: #000000 !important;
            }

            .woocommerce .cart .button, .woocommerce .cart input.button {
                float: none;
                color: #000 !important;
            }
            .vc-image-carousel .vc-carousel-slideline-inner .vc-inner img {
                -webkit-filter: grayscale(0%) !important;
                filter: grayscale(0%) !important;
                opacity: 1 !important;
            }
        </style>

        <style>
            .kmi_link{
                font-size: initial; 
                color: #54c8a7;
                text-transform: capitalize;
                font-weight: bold;
            }

            a.kmi_link:hover{
                color:#138675!important;
            }
            .kmi_link:hover{
                color:#138675!important;
            }
            .wpmenucartli{
                display: none !important;
            }

            @media (min-width: 1200px){
                .jj-offset-2 {
                    margin-left: 16.66666667%!important;
                }
            }
            @media (min-width: 994px){
                .jj-patica-menu{
                    display: none;
                }
            }
            @media (max-width: 120px) and (min-width: 962px){
                .socialBtns{
                     padding-left: 6px!important;
                }
            }    
            @media (max-width: 962px){
                .socialBtns{
                    padding-left: 0px;
                }
            }
            @media screen and (max-width: 750px){
                .vlz_modal_ventana{
                    width: 90% !important;
                }
                .jj-xs-offiset-2{
                    margin-left: 20%;
                }
            }
            @media (max-width: 520px){
                .vlz_modal_contenido{
                    height: 300px!important;
                }
            }           

        </style>

        <script>
            function ocultarModal(){
                jQuery('#jj_modal_finalizar_compra').fadeOut();
                jQuery('#jj_modal_finalizar_compra').css('display', 'none');
            }
        </script>

        <?php

            global $wpdb;
            
            if( isset( $_GET['r'] ) ){

                $xuser = $wpdb->get_row("SELECT * FROM wp_users WHERE md5(ID) = '{$_GET['r']}'");

                $sql = "SELECT meta_value FROM wp_usermeta WHERE meta_key = 'clave_temp' AND user_id = ".$xuser->ID;
                $clave_temp = $wpdb->get_var($sql);

                if( $clave_temp != "" ){
                    $sql = "UPDATE wp_users SET user_pass = '".md5($clave_temp)."' WHERE ID = ".$xuser->ID;
                    $wpdb->query($sql);

                    $sql = "UPDATE wp_usermeta SET meta_value = '' WHERE meta_key = 'clave_temp' AND user_id = ".$xuser->ID;
                    $wpdb->query($sql);
                }

                echo "
                    <script>
                        (function($) {
                            'use strict';
                            $(function(){
                                $.pfOpenLogin('open','login');
                            })
                           })(jQuery);
                    </script>
                ";

            }else{
            
                if( isset( $_GET['a'] ) ){

                    echo "
                        <script>
                            (function($) {
                                'use strict';
                                $(function(){
                                    $.pfOpenLogin('open','login');
                                })
                               })(jQuery);
                        </script>
                    ";

                }else{
                    if( isset( $_GET['home'] ) ){

                    }else{

                       // if( is_home() ){
                            echo "
                                <script>
                                    setTimeout(function(){
                                        jQuery('#jj_modal_bienvenida_xxx').css('display', 'table');
                                    }, 100);
                                </script>
                            ";
                       // }
                    }

                }

            }

            if( $post->post_name == "carro" ){

                echo "
                    <script>
                        function nobackbutton(){
                            window.location.hash='no-back-button';
                            window.location.hash='Again-No-back-button';
                            window.onhashchange=function(){window.location.hash='no-back-button';}
                        }
                        jQuery('body').attr('onload', 'nobackbutton();');

                        jQuery('.woocommerce-message>a.button.wc-forward').css('display', 'none');
                        jQuery('.variation-Duracin').css('display', 'none');
                        jQuery('.variation-Ofrecidopor').css('display', 'none');
                    </script>
                ";

                echo "
                    <style>
                        .woocommerce-message>a.button.wc-forward{
                            display; none;
                        }
                        .shop_table_responsive{
                            
                        }
                        input[name=coupon_code]{color: #000!important;}
                        input[name=update_cart]{display: none!important;}
                    </style>
                    <script>
                        jQuery( document ).ready(function() {
                            jQuery('.woocommerce-message>a.button.wc-forward').css('display', 'none');
                        });
                    </script>
                ";
            }           

            if( $post->post_name == "perfil-usuario" ){

                echo "
                    <script>
                        var es_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                        if(es_firefox){
                            if (jQuery(window).width() > 550) {
                                jQuery('input[name=pet_birthdate]').datepicker();
                            }
                        }
                    </script>
                    <style>
                        @media (max-width: 568px){ 
                            .cell50{width:100%!important;}
                            .cell25{width:50%!important;}
                           
                    </style>
                ";
            }
            if( $post->post_name == "conocer-al-cuidador" ){

                echo "
                    <script>
                        var es_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                        if(es_firefox){
                            if (jQuery(window).width() > 550) {
                                jQuery('input[name=meeting_when]').datepicker();
                                jQuery('input[name=service_start]').datepicker();
                                jQuery('input[name=service_end]').datepicker();
                            }
                        }
                    </script>
                ";
            }

        ?>

        <script type="text/javascript">
            jQuery( document ).ready(function() {
                jQuery( ".reservar" ).unbind();
                jQuery( ".reservar" ).off();

                jQuery( ".conocer-cuidador" ).unbind();
                jQuery( ".conocer-cuidador" ).off();
                <?php
                    if( $post->post_name == "finalizar-comprar" ){
                        echo '
                            jQuery(".order_details tr:nth-child(3) th").html("Total del Servicio:");
                            jQuery(".payment_method_wc-booking-gateway").css("display", "none");
                        ';
                    }
                ?>
            });

            <?php if( $post->post_name == "finalizar-comprar" && $_GET['key'] == "" ){ ?>

                var abrir = true;
                jQuery(window).scroll(function() {

                    if (jQuery(document).scrollTop() > 10) {
                        jQuery('#vlz_modal_popup').fadeOut();
                    }

                });
            <?php } ?>
            
        </script>

        <?php
            if(  $_SESSION['admin_sub_login'] == 'YES' ){
                echo "
                    <a href='".get_home_url()."/?i=".md5($_SESSION['id_admin'])."&admin=YES' style='
                        position: fixed;
                        display: inline-block;
                        left: 50px;
                        bottom: 50px;
                        padding: 20px;
                        font-size: 48px;
                        font-family: Roboto;
                        background: #CCC;
                        border: solid 2px #BBB;
                        z-index: 999999999999999999;
                    '>
                        X
                    </a>
                ";
            }
        ?>

    </body>
</html>