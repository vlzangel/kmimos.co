<?php
    $current_user = get_user_by( 'id', $params['current_user'] );
    $formaction = 'pfadd_new_picture';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-add-picture-button';
    $buttontext = esc_html__('AGREGAR FOTO','pointfindert2d');
    $user_id = $current_user->ID;
    $pics_count = ($params['count']!='')? $params['count']: 0;

    global $wpdb;
    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");

    $this->FieldOutput .= '<ul class="pfitemlists-content-elements pf3col" data-layout-mode="fitRows" style="position: relative; margin: 20px -15px 20px 0px;">';
    $exist_file = false;
    $tmp_user_id = ($cuidador->id) - 5000;
    $path_galeria = "wp-content/uploads/cuidadores/galerias/".$tmp_user_id."/";
    $count_picture =0;
    if( is_dir($path_galeria) ){
        if ($dh = opendir($path_galeria)) {
            $imagenes = array();
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){
                    $exist_file = true;
                    $count_picture++;
                    /*$this->FieldOutput .= '
                    <li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item">
                        <div class="pflist-item" style="background-color:#ffffff;">
                            <div class="pflist-item-inners">
                                <div class="pflist-imagecontainer pflist-subitem" style="
                                    background-image: url('.get_option('siteurl').'/'.$path_galeria.$file.')!important;
                                    background-size:contain;
                                    background-repeat:no-repeat;
                                    background-position:center;
                                ">
                                    <div class="vlz_postada_cuidador" style="height:160px;width:100%;background-color:transparent;"></div>
                                </div>
                                <a href="'.get_option('siteurl').'/perfil-usuario/?ua=mypicturesdel&p='.$file.'" style="color:red;">Eliminar</a>
                            </div>
                        </div>
                    </li>';*/
                    $this->FieldOutput .= '
                    <li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item">
                        <div class="pflist-item" style="background-color:#ffffff;">  
                            <div class="pflist-item-inners">
                                <div class="pflist-imagecontainer pflist-subitem" style="
                                    background-image: url('.get_option('siteurl').'/'.$path_galeria.$file.')!important;
                                    background-size:contain;
                                    background-repeat:no-repeat;
                                    background-position:center;
                                ">
                                    <div class="vlz_postada_cuidador" style="height:160px;width:100%;background-color:transparent;"></div>
                                </div>
                                <a href="'.get_option('siteurl').'/wp-content/themes/pointfinder/vlz/admin/process/eliminar_foto_galeria.php?p='.$file.'&user_id='.$tmp_user_id.'" style="color:red;">Eliminar</a>
                            </div>
                        </div>
                    </li>';
                }
            }
            closedir($dh);
        }
    }
    if(!$exist_file)
    {
        $this->FieldOutput .=  '<li><p>No tienes ninguna foto cargada</p></li>';
    }
    $this->FieldOutput .= '</ul>';
    if($count_picture >= 10){
        $hide_button = true;
    }

    $this->ScriptOutput .= "
        jQuery('#pf-ajax-add-picture-button').on('click',function(e){
            e.preventDefault();
            jQuery('#pfuaprofileform').attr('action','?ua=newpicture');
            jQuery('#pfuaprofileform').submit();
        });
        jQuery('.img_gallery').on('click',function(e){
            e.preventDefault();
            var id = jQuery(this).attr('data-id');
            var num = jQuery(this).attr('title');
            jQuery('#pfuaprofileform').attr('action','?ua=mypicture&id='+id+'&num='+num);
            jQuery('#pfuaprofileform').submit();
        });
        ";
?>