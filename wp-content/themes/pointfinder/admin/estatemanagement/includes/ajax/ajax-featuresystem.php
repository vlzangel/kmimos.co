<?php
/**********************************************************************************************************************************
*
* Ajax Feature System
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_featuresystem', 'pf_ajax_featuresystem' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_featuresystem', 'pf_ajax_featuresystem' );

function pf_ajax_featuresystem(){
  
	//Security
	check_ajax_referer( 'pfget_featuresystem', 'security');
  
	header('Content-Type: text/html; charset=UTF-8;');

	$id = $postid = $place = '';
	if(isset($_POST['id']) && $_POST['id']!=''){
		$id = sanitize_text_field($_POST['id']);
	}

	if(isset($_POST['postid']) && $_POST['postid']!=''){
		$postid = sanitize_text_field($_POST['postid']);
	}

	if(isset($_POST['place']) && $_POST['place']!=''){
		$place = sanitize_text_field($_POST['place']);
	}

	$lang_c = '';
	if(isset($_POST['lang']) && $_POST['lang']!=''){
		$lang_c = sanitize_text_field($_POST['lang']);
	}



	$output = '';
	$output_check = 'not';

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

	if(function_exists('icl_object_id')) {
		global $sitepress;
		if (isset($sitepress) && !empty($lang_c)) {
			$sitepress->switch_lang($lang_c);
		}
	}

	$terms = get_terms($taxonomies, $args);

	$output .= '<section class="pfsubmit-inner-sub"><div class="option-group">';
	if ($place != 'backend') {
		$output .= "<a class='pfitemdetailcheckall'>".esc_html__('Check All','pointfindert2d')."</a> / <a class='pfitemdetailuncheckall'>".esc_html__('Uncheck All','pointfindert2d')."</a><br/><br>";
	}
	
	$i = 0;
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

					$checked_text = '';

					if (!empty($postid)) {
						/*Is checked?*/
						$post_terms = wp_get_post_terms($postid, 'pointfinderfeatures', array("fields" => "ids"));
						
						if (is_array($post_terms)) {
							if (in_array($term->term_id, $post_terms)) {
								$checked_text = ' checked=""';
							}
						}
					}

					$output .= '<span class="goption">';
                    	$output .= '<label class="options">';
                            $output .= '<input type="checkbox" id="pffeature'.$term->term_id.'" name="pffeature[]" value="'.$term->term_id.'"'.$checked_text.'>';
                            $output .= '<span class="checkbox"></span>';
                        $output .= '</label>';
                    	$output .= '<label for="pffeature'.$term->term_id.'">'.$term->name.'</label>';
                    $output .= '</span>';
                    $i++;
				}

			}
			$output .= '<input name="pointfinderfeaturecount" type="hidden" value="'.$i.'"/>';
		}
	}
	$output .= '</div></section>';
	echo $output;
	die();
}

?>