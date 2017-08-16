<?php

/**********************************************************************************************************************************
*
* Ajax Auto Complete
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


	add_action( 'PF_AJAX_HANDLER_pfget_autocomplete', 'pf_ajax_autocomplete' );
	add_action( 'PF_AJAX_HANDLER_nopriv_pfget_autocomplete', 'pf_ajax_autocomplete' );
	
	
function pf_ajax_autocomplete(){
	//Security
	check_ajax_referer( 'pfget_autocomplete', 'security' );
	header('Content-Type: application/javascript; charset=UTF-8;');
	
	//Get form type 
	if(isset($_GET['ftype']) && $_GET['ftype']!=''){
		$ftype = sanitize_text_field($_GET['ftype']);
	}

	//Get search key
	if(isset($_GET['q']) && $_GET['q']!=''){
		$searchword = sanitize_text_field($_GET['q']);
	}

	//Get search key
	if(isset($_GET['callback']) && $_GET['callback']!=''){
		$callback = sanitize_text_field($_GET['callback']);
	}

	/* Get admin values */
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish','posts_per_page' => 5);

	if ($ftype == 'title') {
		$args['orderby'] = 'title';
		$args['order'] = 'ASC';
		$args['search_prod_title'] = $searchword;
		function title_filter( $where, &$wp_query ){
			global $wpdb;
			if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
				if($search_term != ''){
					$search_term = $wpdb->esc_like( $search_term );
					$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(  $search_term ) . '%\'';
				}
			}
			return $where;
		}
  		add_filter( 'posts_where', 'title_filter', 10, 2 );
	}elseif ($ftype == 'address') {
		$pfcomptype = 'CHAR';

		if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
			$args['meta_query'] = array();
		}
										
		
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key' => 'webbupointfinder_items_address',
				'value' => $searchword,
				'compare' => 'LIKE',
				'type' => $pfcomptype
			)
		);	
											
	}else{

		$pfcomptype = 'CHAR';

		if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
			$args['meta_query'] = array();
		}
										
		
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key' => 'webbupointfinder_item_'.$ftype,
				'value' => $searchword,
				'compare' => 'LIKE',
				'type' => $pfcomptype
			)
		);	

	}

	$output_arr = array();

	// the query
	$the_query = new WP_Query( $args );
	
	if ( $the_query->have_posts() ) :
		
		while ( $the_query->have_posts() ) : $the_query->the_post();
			if ($ftype == 'title') {
				$output_arr[] = html_entity_decode(get_the_title());
			}else if($ftype == 'address'){
				$output_arr[] = html_entity_decode(get_post_meta(get_the_id(),'webbupointfinder_items_address',true ));
			}else{
				$output_arr[] = html_entity_decode(get_post_meta(get_the_id(),'webbupointfinder_item_'.$ftype,true ));
			}
	 	endwhile;

	 	wp_reset_postdata();
		
	endif;

	echo $callback.'('.json_encode($output_arr).');';
		
	die();
}

?>