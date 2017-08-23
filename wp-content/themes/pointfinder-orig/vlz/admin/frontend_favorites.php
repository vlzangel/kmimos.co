<?php
    $formaction = 'pf_refinefavlist';
    $noncefield = wp_create_nonce($formaction);
    $buttonid = 'pf-ajax-itemrefine-button';

    $user_favorites_arr = get_user_meta( $params['current_user'], 'user_favorites', true );

    if (!empty($user_favorites_arr)) {
        $user_favorites_arr = json_decode($user_favorites_arr,true);
    }else{
        $user_favorites_arr = array();
    }


    $output_arr = '';
    $countarr = count($user_favorites_arr);

    if($countarr>0){
        // Contenedor de los favoritos del usuario
        $this->FieldOutput .= '<div class="pfmu-itemlisting-container">';

        if ($params['fields']!= '') {
            $fieldvars = $params['fields'];
        }else{
            $fieldvars = '';
        }

        $selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

        $paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
        if (empty($paged)) {
            $paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
        }

        $setup3_pointposttype_pt1 = 'petsitters';
        $setup3_pointposttype_pt7 = PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

        if (PFControlEmptyArr($fieldvars)) {

            if(isset($fieldvars['listing-filter-ltype'])){
                if ($fieldvars['listing-filter-ltype'] != '') {
                    $selected_lfl = $fieldvars['listing-filter-ltype'];
                }
            }

            if(isset($fieldvars['listing-filter-orderby'])){
                if ($fieldvars['listing-filter-orderby'] != '') {
                    $selected_lfo = $fieldvars['listing-filter-orderby'];
                }
            }

            if(isset($fieldvars['listing-filter-order'])){
                if ($fieldvars['listing-filter-order'] != '') {
                    $selected_lfo2 = $fieldvars['listing-filter-order'];
                }
            }

        }

        $user_id = $params['current_user'];

        $output_args = array(
            'post_type'	=> $setup3_pointposttype_pt1,
            'posts_per_page' => 10,
            'paged' => $paged,
            'order'	=> 'ASC',
            'orderby' => 'Title',
            'post__in' => $user_favorites_arr
        );

        if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
        if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
        if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
        if($selected_lfl != ''){
            $output_args['tax_query']=
                array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'pointfinderltypes',
                        'field' => 'id',
                        'terms' => $selected_lfl,
                        'operator' => 'IN'
                    )
                );
        }

        if($params['post_id'] != ''){
            $output_args['p'] = $params['post_id'];
        }

        $output_loop = new WP_Query( $output_args );

        if ( $output_loop->have_posts() ) {

            $this->FieldOutput .= '<section>';
            $this->FieldOutput .= '<div class="pfhtitle pf-row clearfix hidden-xs" style="border-top-width: 0px !important;">';

            $setup3_pointposttype_pt4 = PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
            $setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
            $setup3_pointposttype_pt5 = PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
            $setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
            $setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');

            $this->FieldOutput .= '</div>';

            while ( $output_loop->have_posts() ) {
                $output_loop->the_post();

                $author_post_id = get_the_ID();

                $this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';

                $permalink_item = get_permalink($author_post_id);
                $name_photo = get_user_meta($xuser_id, "name_photo", true);
                $idUser = $xuser_id - 5000;
                if( $name_photo == "" ){ $name_photo = "0"; }
                if( file_exists("./wp-content/uploads/avatares/".$idUser."/{$name_photo}") ){
                    $user_photo_field_output = get_home_url()."/wp-content/uploads/avatares/".$idUser."/{$name_photo}";
                }elseif( file_exists("./wp-content/uploads/avatares/".$idUser."/{$name_photo}.jpg") ){
                    $user_photo_field_output = get_home_url()."/wp-content/uploads/avatares/".$idUser."/{$name_photo}.jpg";
                }else{
                    $user_photo_field_output = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
                }


                /*Item Photo Area*/
                $this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-2 hidden-xs">';

                global $wpdb;
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = ".$author_post_id);

                $name_photo = get_user_meta($author_post_id, "name_photo", true);
                $cuidador_id = $cuidador->id;

                if( empty($name_photo)  ){ $name_photo = "0"; }
                if( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}") ){
                    $foto = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/{$name_photo}";
                }elseif( file_exists("wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg") ){
                    $foto = get_home_url()."/wp-content/uploads/cuidadores/avatares/".$cuidador_id."/0.jpg";
                }else{
                    $foto = get_home_url()."/wp-content/themes/pointfinder".'/images/noimg.png';
                }

                $this->FieldOutput .= '<a href="' . $foto . '" title="' . the_title_attribute('echo=0') . '" rel="prettyPhoto">';
                $this->FieldOutput .= '<img src="'.aq_resize($foto,60,60,true).'" alt="" />';
                $this->FieldOutput .= '</a>';

                $this->FieldOutput .= '</div>';



                /* Item Title */
                $this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
                $this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
                $this->FieldOutput .= '<a href="'.$permalink_item.'">'.get_the_title().'</a>';
                $this->FieldOutput .= '</div>';


                /*Other Infos*/
                $output_data = PFIF_DetailText_ld($author_post_id);
                $rl_pfind = '/pflistingitem-subelement pf-price/';
                $rl_pfind2 = '/pflistingitem-subelement pf-onlyitem/';
                $rl_preplace = 'pf-fav-listing-price';
                $rl_preplace2 = 'pf-fav-listing-item';
                $mcontent = preg_replace( $rl_pfind, $rl_preplace, $output_data);
                $mcontent = preg_replace( $rl_pfind2, $rl_preplace2, $mcontent );

                if (isset($mcontent['content'])) {
                    $this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
                    $this->FieldOutput .= $mcontent['content'];
                    $this->FieldOutput .= '</div>';
                }

                if (isset($mcontent['priceval'])) {
                    $this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
                    $this->FieldOutput .= $mcontent['priceval'];
                    $this->FieldOutput .= '</div>';
                }

                $this->FieldOutput .= '</div>';




                /*Type of item*/
                $this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
                $this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
                $this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderltypes').'</li>';
                $this->FieldOutput .= '</ul>';
                $this->FieldOutput .= '</div>';

                /*Location*/
                $this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-3 col-md-3 col-sm-2 hidden-xs">';
                $this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
                if($setup3_pointposttype_pt5_check == 1){
                    $this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderlocations').'</li>';
                }else{
                    if($setup3_pointposttype_pt4_check == 1){
                        $this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderitypes').'</li>';
                    }
                }
                $this->FieldOutput .= '</ul>';
                $this->FieldOutput .= '</div>';






                /*Item Footer*/


                $fav_check = 'true';
                $favtitle_text = esc_html__('Remover de favoritos','pointfindert2d');



                $this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-2 col-sm-2 col-xs-12">';
                $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';
                $this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all pf-favorites-link" data-pf-num="'.$author_post_id.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="'.$favtitle_text.'"><i class="pfadmicon-glyph-644"></i></a></li>';
                $this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" target="_blank" title="'.esc_html__('Vista','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
                $this->FieldOutput .= '</ul>';

                $this->FieldOutput .= '</div>';


                $this->FieldOutput .= '</div>';


            }

            $this->FieldOutput .= '</section>';
        }else{
            $this->FieldOutput .= '<section>';
            $this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
            if (PFControlEmptyArr($fieldvars)) {
                $this->FieldOutput .= '<strong>'.esc_html__('No se encontró el registro!','pointfindert2d').'</strong><br>'.esc_html__('Por favor, revise sus criterios de búsqueda y tratar de comprobar de nuevo. O puede presionar el botón <strong>Reiniciar</ strong> para ver todas.','pointfindert2d').'</p></div>';
            }else{
                $this->FieldOutput .= '<strong>'.esc_html__('No se encontró el registro!','pointfindert2d').'</strong></p></div>';
            }
            $this->FieldOutput .= '</section>';
        }
        $this->FieldOutput .= '<div class="pfstatic_paginate" >';
        $big = 999999999;
        $this->FieldOutput .= paginate_links(array(
            //'base' => @add_query_arg('page','%#%'),
            //'format' => '?page=%#%',
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $output_loop->max_num_pages,
            'type' => 'list',
        ));
        $this->FieldOutput .= '</div>';


        $this->FieldOutput .= '</div>';
    }else{
        $this->FieldOutput .= '<section>';
        $this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>'.esc_html__('No se encontró el registro!','pointfindert2d').'</p></div>';
        $this->FieldOutput .= '</section>';
    }
?>
