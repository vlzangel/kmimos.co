<?php
/**********************************************************************************************************************************
*
* Ajax Features Filter
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_featuresfilter', 'pf_ajax_featuresfilter' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_featuresfilter', 'pf_ajax_featuresfilter' );

function pf_ajax_featuresfilter(){
  
	//Security
	check_ajax_referer( 'pfget_searchitems', 'security');
  
	header('Content-Type: text/html; charset=UTF-8;');

    /* WPML - Current language fix */
    if(isset($_POST['cl']) && $_POST['cl']!=''){
        $pflang = esc_attr($_POST['cl']);
        global $sitepress;
        $sitepress->switch_lang($pflang);
    }


	if(isset($_POST['pfcat']) && $_POST['pfcat']!=''){
		$id = sanitize_text_field($_POST['pfcat']);
	}


    $output = '';

    $taxonomies = array('pointfinderfeatures');

    $args = array(
        'orderby'           => 'name', 
        'order'             => 'ASC',
        'hide_empty'        => false, 
        'exclude'           => array(), 
        'exclude_tree'      => array(), 
        'include'           => array(),
        'number'            => '', 
        'fields'            => 'all', 
        'slug'              => '',
        'parent'            => '',
        'hierarchical'      => true, 
        'child_of'          => 0, 
        'get'               => '', 
        'name__like'        => '',
        'description__like' => '',
        'pad_counts'        => false, 
        'offset'            => '', 
        'search'            => '', 
        'cache_domain'      => 'core'
    ); 

    $terms = get_terms($taxonomies, $args);


    $setup4_sbf_c1 = PFSAIssetControl('setup4_sbf_c1','','1');
    if (isset($terms)) {
        if (is_array($terms)) {
            foreach ($terms as $term) {

                $term_parent_name = 'pointfinder_features_customlisttype_' . $term->term_id;
                $term_parent = get_option( $term_parent_name );
            
                /* Check taxonomy output */
                if (!empty($term_parent) && !empty($id)) {

                    if (is_array($term_parent)) {
                        if (in_array($id, $term_parent)) {$output_check = 'ok';}else{$output_check = 'not';}
                    }else{
                        if ($id == $term_parent) {$output_check = 'ok';}else{$output_check = 'not';}
                    }
                }elseif (empty($term_parent) && empty($id)) {
                    $output_check = 'ok';
                }elseif (empty($term_parent) && !empty($id)) {
                    if ($setup4_sbf_c1 == 1) {
                        $output_check = 'ok';
                    }else{
                        $output_check = 'not';
                    }
                    
                }elseif (!empty($term_parent) && empty($id)) {
                    $output_check = 'not';
                }



                if ($output_check == 'ok') {
                    $output .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
                }

            }
        }
    }
    echo $output;
die();
}

?>