<?php
    $noncefield = wp_create_nonce('pfget_updateuserprofile');
    $formaction = 'pfget_updateuserprofile';
    $buttonid = 'pf-ajax-profileupdate-button';
    $buttontext = esc_html__('ACTUALIZAR INFORMACIÓN','pointfindert2d');
    $current_user = get_user_by( 'id', $params['current_user'] );
    $user_id = $current_user->ID;
    $usermetaarr = get_user_meta($user_id);
    $setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

    $stp_prf_vat = PFSAIssetControl('stp_prf_vat','','1');
    $stp_prf_country = PFSAIssetControl('stp_prf_country','','1');
    $stp_prf_address = PFSAIssetControl('stp_prf_address','','1');

    if(!isset($usermetaarr['first_name'])){$usermetaarr['first_name'][0] = '';}
    if(!isset($usermetaarr['last_name'])){$usermetaarr['last_name'][0] = '';}
    if(!isset($usermetaarr['user_referred'])){$usermetaarr['user_referred'][0] = '';}
    if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
    if(!isset($usermetaarr['user_mobile'])){$usermetaarr['user_mobile'][0] = '';}
    if(!isset($usermetaarr['description'])){$usermetaarr['description'][0] = '';}
    if(!isset($usermetaarr['nickname'])){$usermetaarr['nickname'][0] = '';}
    if(!isset($usermetaarr['user_twitter'])){$usermetaarr['user_twitter'][0] = '';}
    if(!isset($usermetaarr['user_facebook'])){$usermetaarr['user_facebook'][0] = '';}
    if(!isset($usermetaarr['user_googleplus'])){$usermetaarr['user_googleplus'][0] = '';}
    if(!isset($usermetaarr['user_linkedin'])){$usermetaarr['user_linkedin'][0] = '';}
    if(!isset($usermetaarr['user_vatnumber'])){$usermetaarr['user_vatnumber'][0] = '';}
    if(!isset($usermetaarr['user_country'])){$usermetaarr['user_country'][0] = '';}
    if(!isset($usermetaarr['user_address'])){$usermetaarr['user_address'][0] = '';}

    if(!isset($usermetaarr['user_photo'])){
        $usermetaarr['user_photo'][0] = '0';
    }

    $this->ScriptOutput = "
        $.pfAjaxUserSystemVars4 = {};
        $.pfAjaxUserSystemVars4.email_err = '".esc_html__('Por favor, escriba un correo electrónico','pointfindert2d')."';
        $.pfAjaxUserSystemVars4.email_err2 = '".esc_html__('Su dirección de correo electrónico debe estar en el formato de nombre@dominio.com','pointfindert2d')."';
        $.pfAjaxUserSystemVars4.nickname_err = '".esc_html__('Por favor, escriba apodo','pointfindert2d')."';
        $.pfAjaxUserSystemVars4.nickname_err2 = '".esc_html__('Por favor, introduzca al menos 3 caracteres por el apodo.','pointfindert2d')."';
        $.pfAjaxUserSystemVars4.passwd_err = '".esc_html__('Introduzca al menos 7 caracteres','pointfindert2d')."';
        $.pfAjaxUserSystemVars4.passwd_err2 = '".esc_html__('Introduzca la misma contraseña que el anterior','pointfindert2d')."';
    ";

    $user = new WP_User( $user_id );

    $imagen = kmimos_get_foto($user_id);

    $referred = $usermetaarr['user_referred'][0];

    $opciones = get_referred_list_options(); $ref_str = "";
    foreach($opciones as $key=>$value){
        $selected = ($referred==$key)? ' selected':'';
        $ref_str .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
    }

    $this->FieldOutput .= '
        <style>
            .cell50 {width: 50%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
            .cell25 {width: 25%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
            .cell33 {width: 33.333333333%; margin-right: -5px !important; padding-right: 10px !important; display: inline-block !important;}
            .img_portada{ position: relative; height: 400px; overflow: hidden; border: solid 1px #777; background: #EEE; }
            .img_portada_fondo{ position: absolute; top: -1px; left: -1px; width: calc( 100% + 2px ); height: 402px; z-index: 50; background-size: cover; background-position: center; background-repeat: no-repeat; background-color: transparent; filter: blur(2px); transition: all .5s ease; }
            .img_portada_normal{ position: absolute; top: 0px; left: 0px; width: 100%; height: 380px; z-index: 100; background-size: contain; background-position: center; background-repeat: no-repeat; background-color: transparent; margin: 10px 0px; transition: all .5s ease; }
            .cambiar_portada{ position: absolute; bottom: 10px; right: 10px; width: auto; padding: 10px; font-size: 16px; color: #FFF; background: #000; border: solid 1px #777; z-index: 200; }
            .cambiar_portada input{ position: absolute; top: -24px; left: 0px; width: 100%; height: 167%; z-index: 100; opacity: 0; cursor: pointer; }
            .jj_dash_cel50{float: left; width: calc(50% - 9px);}
            .jj_dash2_cel50{float: right; width: calc(50% - 9px);}
            @media (max-width: 568px) {
                .jj_dash_cel50{float: left; width: calc(100% - 9px);}
                .jj_dash2_cel50{float: left; width: calc(100% - 9px);}
            }
        </style>

        <section>
            <input type="hidden" name="tipo_user" value="'.$user->roles[0].'" />
            <input type="hidden" name="user_tipo" value="'.$user_id_tipo.'" />
            <div class="img_portada">
                <div class="img_portada_fondo" style="background-image: url('.$imagen.');"></div>
                <div class="img_portada_normal" style="background-image: url('.$imagen.');"></div>
                <div class="cambiar_portada">
                    Cambiar Foto
                    <input type="file" id="portada" name="portada" accept="image/*" />
                </div>
            </div>
        </section>

        <div class="jj_dash_cel50">
            <section>
                <label for="firstname" class="lbl-text">'.esc_html__('Nombres','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <input type="text" name="firstname" class="input" value="'.$usermetaarr['first_name'][0].'" />
                </label>
            </section>
        </div>
        <div class="jj_dash2_cel50">
            <section>
                <label for="lastname" class="lbl-text">'.esc_html__('Apellidos','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <input type="text" name="lastname" class="input" value="'.$usermetaarr['last_name'][0].'" />
                </label>
            </section>
        </div>
        <div class="jj_dash_cel50">
        <!--<div class="col6 first">-->
            <section>
                <label for="nickname" class="lbl-text"><strong>'.esc_html__('Apodo (Nombre a mostrar)','pointfindert2d').'(*)</strong>:</label>
                <label class="lbl-ui">
                    <input  type="text" name="nickname" class="input" value="'.$usermetaarr['nickname'][0].'" />
                </label>
            </section>

            <section>
                <label for="phone" class="lbl-text">'.esc_html__('Teléfono','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <input type="tel" name="phone" maxlength="12" class="input" placeholder="" value="'.$usermetaarr['user_phone'][0].'" />
                </label>
            </section>
            <section>
                <label for="mobile" class="lbl-text">'.esc_html__('Móvil','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <input type="tel" name="mobile" maxlength="12" class="input" placeholder="" value="'.$usermetaarr['user_mobile'][0].'"/>
                </label>
            </section>

       </div>

       <div class="jj_dash2_cel50">
       <!--<div class="col6 last">-->
            <section>
                <label for="referred" class="lbl-text">'.esc_html__('¿Como nos conoció?','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <select name="referred" class="input">
                        <option value="">Por favor seleccione</option>
                        '.$ref_str.'
                    </select>
                </label>
            </section>

            <section>
                <label for="descr" class="lbl-text">'.esc_html__('Información biográfica','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <textarea name="descr" class="textarea mini" style="border:1px solid #848484; height: 112px;">'.$usermetaarr['description'][0].'</textarea>
                </label>
            </section>
        </div>


        <div class="jj_dash_cel50">
            <section>
                <label for="username" class="lbl-text"><strong>'.esc_html__('Usuario','pointfindert2d').'</strong>:</label>
                <label class="lbl-ui">
                    <input type="text" id="username" class="input" value="'.$current_user->user_login.'" disabled />
                </label>
           </section>
        </div>
        <div class="jj_dash2_cel50">
           <section>
                <label for="email" class="lbl-text"><strong>'.esc_html__('Email Address','pointfindert2d').'</strong>:</label>
                <label class="lbl-ui">
                    <input  type="email" id="email" class="input" value="'.$current_user->user_email.'" disabled />
                </label>
            </section>
        </div>
        <div class="jj_dash_cel50">
            <section>
                <label for="password" class="lbl-text">'.esc_html__('Nueva contraseña','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <input type="password"
                    placeholder="Contraseña"
                    autocomplete="off" name="password" id="password" class="input" />
                </label>
            </section>

        </div>
        <div class="jj_dash2_cel50">
            <section>
                <label for="password2" class="lbl-text">'.esc_html__('Repita la nueva contraseña','pointfindert2d').':</label>
                <label class="lbl-ui">
                    <input type="password" placeholder="Repita la Contraseña" autocomplete="off" name="password2" class="input" />
                </label>
            </section>

        </div>

        <script>
            function vista_previa(evt) {
                var files = evt.target.files;
                for (var i = 0, f; f = files[i]; i++) {
                    if (!f.type.match("image.*")) {
                        jQuery(\'#portada\').val(\'\');
                        alert("El archivo seleccionado no es una imagen.");
                        continue;
                    }
                    var reader = new FileReader();
                    reader.onload = (function(theFile) {
                       return function(e) {
                            jQuery(".img_portada_fondo").css("background-image", "url("+e.target.result+")");
                            jQuery(".img_portada_normal").css("background-image", "url("+e.target.result+")");
                       };
                   })(f);
                   reader.readAsDataURL(f);
                }
            }
            document.getElementById("portada").addEventListener("change", vista_previa, false);
        </script>
    ';
?>


