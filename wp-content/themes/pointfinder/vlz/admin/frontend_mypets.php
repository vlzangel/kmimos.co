<?php
    $formaction = 'pfadd_new_pet';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-add-pet-button';
    $buttontext = esc_html__('AGREGAR MASCOTA','pointfindert2d');
    $user_id = $current_user->ID;
    $count = ($params['count']!='')? $params['count']: 0;

    // BEGIN New style Italo
    if($count>0) {
        $pets = explode(",",$params['list']);

        $this->FieldOutput .= '<ul class="pfitemlists-content-elements pf3col" data-layout-mode="fitRows" style="position: relative; margin: 20px -15px 20px 0px;">';
        foreach($pets as $pet){
            $pet_detail = kmimos_get_pet_info($pet);

            $photo = (!empty($pet_detail['photo']))? get_option('siteurl').'/'.$pet_detail['photo'] : get_option('siteurl').'/wp-content/themes/pointfinder/images/default.jpg';
            $this->FieldOutput .= '
                                    <li class="col-lg-4 col-md-6 col-sm-6 col-xs-12 wpfitemlistdata isotope-item text-center">
                                        <div class="pflist-item" style="background-color:#ffffff;">
                                            <div class="pflist-item-inners">
                                                <div class="pflist-imagecontainer pflist-subitem" style="
                                                    background-image: url('.$photo.')!important;
                                                    background-size:contain;
                                                    background-repeat:no-repeat;
                                                    background-position:center;">
                                                <a href="'.$params['detail_url'].$pet.'">
                                                    <div class="vlz_postada_cuidador" style="height:160px;width:100%;background-color:transparent;"></div>
                                                    </div>
                                                    <h3 class="kmi_link">'. get_the_title($pet).'</h3>
                                                </a>
                                                <br>
                                                <img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png" width="50px">
                                            </div>
                                        </div>
                                    </li>';
        }
        $this->FieldOutput .= '</ul>';
    }else{
        $this->FieldOutput .=  '
                                <p>
                                    <img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png" width="50px">
                                    No tienes ninguna mascota cargada
                                </p>';
    }

    $this->ScriptOutput .= "
                            jQuery('#pf-ajax-add-pet-button').on('click',function(e){
                                e.preventDefault();
                                jQuery('#pfuaprofileform').attr('action','?ua=newpet');
                                jQuery('#pfuaprofileform').submit();
                            });
                            ";

?>