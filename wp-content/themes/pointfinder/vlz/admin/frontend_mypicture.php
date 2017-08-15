<?php

    $formaction = 'pfupdate_my_picture';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-update-picture-button';
    $buttontext = esc_html__('ACTUALIZAR FOTO','pointfindert2d');
    $picture_id = $params['picture_id'];
    $pictures = $params['pictures_count'];

    $this->FieldOutput .= '
                                <section>
                                      <div class="photo-container" style="text-align: center;">
                                      '.wp_get_attachment_image($picture_id, array(800,600)).'
                                      </div>
                                </section>';

    if($_GET['ua']=='newpicture') $this->FieldOutput .= '
                                    <style>
                                        .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                        .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                        .cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
                                        .img_portada_picture{ position: relative; height: 400px; overflow: hidden; border: solid 1px #777; background: #EEE; }
                                        .img_portada_picture_fondo{ position: absolute; top: -1px; left: -1px; width: calc( 100% + 2px ); height: 402px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
                                        .img_portada_picture_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 380px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
                                        .cambiar_portada_picture{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #000; border: solid 1px #777; z-index: 200; }
                                        .cambiar_portada_picture input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }

                                        .jj_dash_cel50{float: left; width: calc(50% - 9px);}
                                        .jj_dash2_cel50{float: right; width: calc(50% - 9px);}

                                        @media (max-width: 568px) {
                                            .jj_dash_cel50{float: left; width: calc(100% - 9px);}
                                            .jj_dash2_cel50{float: left; width: calc(100% - 9px);}
                                        }
                                    </style>
                                    <section>
                                        <div class="img_portada_picture">
                                            <div class="img_portada_picture_fondo" style="background-image: url('.$photo_picture.');"></div>
                                            <div class="img_portada_picture_normal" style="background-image: url('.$photo_picture.');"></div>
                                            <div class="cambiar_portada_picture">
                                                Cargar Foto
                                                <input type="file" id="portada_picture" name="petsitter_photo" accept="image/*" />
                                            </div>
                                        </div>
                                    </section>
                                    <script>
                                    function vista_previa_picture(evt) {
                                        var files = evt.target.files;
                                        for (var i = 0, f; f = files[i]; i++) {
                                        if (!f.type.match("image.*")) {
                                            continue;
                                        }
                                        var reader = new FileReader();
                                        reader.onload = (function(theFile) {
                                           return function(e) {
                                                jQuery(".img_portada_picture_fondo").css("background-image", "url("+e.target.result+")");
                                                jQuery(".img_portada_picture_normal").css("background-image", "url("+e.target.result+")");
                                                jQuery("#portada_picture_base64").val(e.target.result);
                                           };
                                       })(f);
                                       reader.readAsDataURL(f);
                                        }
                                    }
                                    document.getElementById("portada_picture").addEventListener("change", vista_previa_picture, false);
                                    </script>
                                    ';

    else $this->FieldOutput .= '
                                           <section>
                                                <label for="delete_picture" class="lbl-text" style="float: right;">
                                                <input type="checkbox" name="delete_picture" value="1">
                                                <strong>'.esc_html__('Eliminar esta foto','pointfindert2d').'</strong>.</label>
                                           </section>';
?>