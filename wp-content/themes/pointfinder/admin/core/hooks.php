<?php
/**********************************************************************************************************************************
*
* Hooks & Sidebars & Menu
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


/* Main Menu Walker Class */
class pointfinder_walker_nav_menu extends Walker_Nav_Menu {
  	
  	private $megamenu_status = "";
  	private $megamenu_column = "";
  	private $megamenu_hide_menu = "";
  	private $megamenu_icon = "";

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ($this->megamenu_status == 1) {
			$megamenu_css_text = ' pfnav-megasubmenu pfnav-megasubmenu-col'.$this->megamenu_column;
		}else{
			$megamenu_css_text = '';
		}

	    $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); 
	    $display_depth = ( $depth + 1); 
	    $classes = array(
	        'sub-menu'.$megamenu_css_text,
	        ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
	        ( $display_depth ==1 ? 'pfnavsub-menu' : '' ),
	        ( $display_depth >=2 ? 'pfnavsub-menu' : '' ),
	        ( $display_depth >=2 && $this->megamenu_hide_menu == 1 ? 'pf-megamenu-unhide' : '' ),
	        'menu-depth-' . $display_depth
	        );
	    $class_names = implode( ' ', $classes );

	    $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	  

	function start_el(  &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		$this->megamenu_status = $item->megamenu;
		$this->megamenu_hide_menu = $item->megamenu_hide_menu;
		$this->megamenu_column = $item->columnvalue;
		$this->megamenu_icon = $item->icon;
		
	    $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); 

	    $depth_classes = array(
	        ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
	        ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
	        ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
	        'menu-item-depth-' . $depth
	    );

	    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
	  
	    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

	   	if (in_array('menu-item-has-children', $classes)) {
	   		if($this->megamenu_status == 1){$classes[] = 'pf-megamenu-main';}
	   	}

	    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
	  	
	  	

	    $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
	  
	    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
	   	if ($this->megamenu_hide_menu != 1) {
	    	$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
		}else{
			$attributes .= ' class="menu-link pf-megamenu-hidedesktop ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
		}
	  	
	  	$args_before = (isset($args->before))? $args->before: '';
	  	$args_link_before = (isset($args->link_before))? $args->link_before: '';
	  	$args_link_after = (isset($args->link_after))? $args->link_after: '';
	  	$args_after = (isset($args->after))? $args->after: '';
	  	
	  

  		 $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
	        $args_link_before,
	        $attributes,
	        $args_link_before,
	        (!empty($this->megamenu_icon))?'<i class="'.$this->megamenu_icon.'"></i> '.apply_filters( 'the_title', $item->title, $item->ID ):apply_filters( 'the_title', $item->title, $item->ID ),
	        $args_link_after,
	        $args_after
	    );
	  
	   
	  
	    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

function pointfinder_navigation_menu(){
	$defaults = array(
	    'theme_location'  => 'pointfinder-main-menu',
	    'menu'            => '',
	    'container'       => '',
	    'container_class' => '',
	    'container_id'    => '',
	    'menu_class'      => '',
	    'menu_id'         => '',
	    'echo'            => true,
	    'fallback_cb'     => 'wp_page_menu',
	    'before'          => '',
	    'after'           => '',
	    'link_before'     => '',
	    'link_after'      => '',
	    'items_wrap'      => '%3$s',
	    'depth'           => 0,
	    'walker'          => new pointfinder_walker_nav_menu()
	);
	if (has_nav_menu( 'pointfinder-main-menu' )) {
		wp_nav_menu( $defaults ); 
	}
}


function pointfinder_footer_navigation_menu(){
	$defaults = array(
	    'theme_location'  => 'pointfinder-footer-menu',
	    'menu'            => '',
	    'container'       => 'div',
	    'container_class' => 'pf-footer-menu',
	    'container_id'    => '',
	    'menu_class'      => '',
	    'menu_id'         => '',
	    'echo'            => true,
	    'fallback_cb'     => 'wp_page_menu',
	    'before'          => '',
	    'after'           => '',
	    'link_before'     => '',
	    'link_after'      => '',
	    'items_wrap'      => '%3$s',
	    'depth'           => 0,
	    'walker'          => ''
	);
	if (has_nav_menu( 'pointfinder-footer-menu' )) {
		wp_nav_menu( $defaults ); 
	}
}




function pointfinder_widgets_init() {
	global $pointfindertheme_option;

	if (function_exists('register_sidebar'))
	{
		
	    register_sidebar(array(
	        'name' => esc_html__('PF Default Widget Area', 'pointfindert2d'),
	        'description' => esc_html__('PF  Default Widget Area', 'pointfindert2d'),
	        'id' => 'pointfinder-widget-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    register_sidebar(array(
	        'name' => esc_html__('PF Item Page Widget', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for item detail page.', 'pointfindert2d'),
	        'id' => 'pointfinder-itempage-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    register_sidebar(array(
	        'name' => esc_html__('PF Author Page Widget', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for author detail page.', 'pointfindert2d'),
	        'id' => 'pointfinder-authorpage-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    if (function_exists('is_bbpress')) {
	    	register_sidebar(array(
		        'name' => esc_html__('PF bbPress Sidebar', 'pointfindert2d'),
		        'description' => esc_html__('Widget area for inner bbPress pages.', 'pointfindert2d'),
		        'id' => 'pointfinder-bbpress-area',
		        'before_widget' => '<div id="%1$s" class="%2$s">',
		        'after_widget' => '</div></div>',
		        'before_title' => '',
		        'after_title' => ''
		    ));
	    }

	    if (function_exists('is_woocommerce')) {
	    	register_sidebar(array(
		        'name' => esc_html__('PF WooCommerce Sidebar', 'pointfindert2d'),
		        'description' => esc_html__('Widget area for inner WooCommerce pages.', 'pointfindert2d'),
		        'id' => 'pointfinder-woocom-area',
		        'before_widget' => '<div id="%1$s" class="%2$s">',
		        'after_widget' => '</div></div>',
		        'before_title' => '',
		        'after_title' => ''
		    ));
	    }
	    
	    if (function_exists('dsidxpress_InitWidgets')) {
	    	register_sidebar(array(
		        'name' => esc_html__('PF dsIdxpress Sidebar', 'pointfindert2d'),
		        'description' => esc_html__('Widget area for inner dsIdxpress pages.', 'pointfindert2d'),
		        'id' => 'pointfinder-dsidxpress-area',
		        'before_widget' => '<div id="%1$s" class="%2$s">',
		        'after_widget' => '</div></div>',
		        'before_title' => '',
		        'after_title' => ''
		    ));
	    }
	    register_sidebar(array(
	        'name' => esc_html__('PF Category Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for Item Category Page.', 'pointfindert2d'),
	        'id' => 'pointfinder-itemcatpage-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    register_sidebar(array(
	        'name' => esc_html__('PF Search Results Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for Item Search Results Page.', 'pointfindert2d'),
	        'id' => 'pointfinder-itemsearchres-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));


	    register_sidebar(array(
	        'name' => esc_html__('PF Blog Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for single blog page.', 'pointfindert2d'),
	        'id' => 'pointfinder-blogpages-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
			'after_title' => ''
	    ));


	    register_sidebar(array(
	        'name' => esc_html__('PF Blog Category Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for blog category page.', 'pointfindert2d'),
	        'id' => 'pointfinder-blogcatpages-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
			'after_title' => ''
	    ));

	    
	}

	/*------------------------------------
		Unlimited Sidebar
	------------------------------------*/
	global $pfsidebargenerator_options;
	$setup25_sidebargenerator_sidebars = (isset($pfsidebargenerator_options['setup25_sidebargenerator_sidebars']))?$pfsidebargenerator_options['setup25_sidebargenerator_sidebars']:'';

	if(PFControlEmptyArr($setup25_sidebargenerator_sidebars)){
		if(count($setup25_sidebargenerator_sidebars) > 0){
			foreach($setup25_sidebargenerator_sidebars as $itemvalue){
				if (function_exists('register_sidebar') && !empty($itemvalue['title']))
				{
					// Define Sidebar Widget Area 2
					register_sidebar(array(
						'name' => $itemvalue['title'],
						'id' => $itemvalue['url'],
						'before_widget' => '<div id="%1$s" class="%2$s">',
				        'after_widget' => '</div></div>',
				        'before_title' => '',
				        'after_title' => ''
					));
				
				}
			}
		}
	}
}

function pfedit_my_widget_title($title = '', $instance = array(), $id_base = '') {

	if (!empty($id_base)) {
		if (empty($instance['title'])) {
			if ($id_base != 'search') {
				return '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
			} else {
				return '<div class="pfwidgettitle pfemptytitle"><div class="widgetheader"></div></div><div class="pfwidgetinner pfemptytitle">';
			}
			
		}else{
			return '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
		}
	}else{
		if (!empty($title)) {
			return '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
		}else{
			return '<div class="pfwidgettitle pfemptytitle"><div class="widgetheader"></div></div><div class="pfwidgetinner pfemptytitle">';
		}
		
	}
}
 
add_filter ( 'widget_title' , 'pfedit_my_widget_title', 10, 3);


/*------------------------------------*\
  FEATURED MARKER FIX HOOK
\*------------------------------------*/
function PF_SAVE_FEATURED_MARKER_DATA( $post_id,$post,$update ) {

    $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

    if ( $setup3_pointposttype_pt1 == get_post_type($post_id)) {
	    
	    $key = 'webbupointfinder_item_featuredmarker';

	    if ($update) {
	    	$featured_status = get_post_meta( $post_id, $key, true );
	    	if (empty($featured_status)) {
	    		update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);
	    	}
	    }else{
	    	update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);

	    	if (isset($_POST['pfget_uploaditem'])) {
		    	if(isset($_POST['featureditembox'])){
		    		if ($_POST['featureditembox'] == "on") {
						update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 1);						
		    		}
		    	}
		    }
	    }

    }

}
add_action( 'wp_insert_post', 'PF_SAVE_FEATURED_MARKER_DATA',0,3);





/*------------------------------------*\
  CONTACT FORM 7
\*------------------------------------*/	
if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
	add_filter( 'wpcf7_form_class_attr', 'pointfinder_form_class_attr' );

	function pointfinder_form_class_attr( $class ) {
		$class .= ' golden-forms';
		return $class;
	}

	add_filter( 'wpcf7_form_elements', 'pointfinder_wpcf7_form_elements' );
	function pointfinder_wpcf7_form_elements( $content ) {
		
		$rl_pfind = '/<p>/';
		$rl_preplace = '<p class="wpcf7-form-text">';
		$content = preg_replace( $rl_pfind, $rl_preplace, $content, 20 );
	 	
		return $content;	
	}
}



/*------------------------------------*\
  ITEM PAGE COMMENTS
\*------------------------------------*/
$setup3_modulessetup_allow_comments = PFSAIssetControl('setup3_modulessetup_allow_comments','','0');
if($setup3_modulessetup_allow_comments == 1){
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	add_post_type_support( $setup3_pointposttype_pt1, 'comments' );
	add_post_type_support( $setup3_pointposttype_pt1, 'author' );

	function pf_default_comments_on( $data ) {
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	    if( $data['post_type'] == $setup3_pointposttype_pt1 ) {
	        $data['comment_status'] = "open";     
	    }

	    return $data;
	}
	add_filter( 'wp_insert_post_data', 'pf_default_comments_on' );
}


/*------------------------------------*\
	HIDE ADMIN BAR
\*------------------------------------*/
$setup4_membersettings_hideadminbar = PFSAIssetControl('setup4_membersettings_hideadminbar','','1');
$general_hideadminbar = PFSAIssetControl('general_hideadminbar','','0');

if (  current_user_can( 'manage_options' ) && $general_hideadminbar == 0) {//This is for admin
    show_admin_bar( false );
}

if (  !current_user_can( 'manage_options' ) && $setup4_membersettings_hideadminbar == 0) {//This is for users
    show_admin_bar( false );
}

/*------------------------------------
	Fix for taxonomy paging
------------------------------------*/
function pointfinder_alter_query_for_fix_default_taxorder($qry) {
   if ( $qry->is_main_query() && is_tax(array('pointfinderltypes','pointfinderitypes','pointfinderlocations','pointfinderfeatures')) ) {
     $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
     $setup42_authorpagedetails_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
     $qry->set('post_type',$setup3_pointposttype_pt1);
     $qry->set('posts_per_page',$setup42_authorpagedetails_defaultppptype);
   }
}
add_action('pre_get_posts','pointfinder_alter_query_for_fix_default_taxorder');


/*------------------------------------*\
	Google Analytic
\*------------------------------------*/
function add_analytic_code () {
	global $pointfindertheme_option;
	$googleanalytics_code = isset($pointfindertheme_option['googleanalytics_code'])? $pointfindertheme_option['googleanalytics_code']:'';
	$googleanalytics_code = str_replace("<script>", "", $googleanalytics_code);
	$googleanalytics_code = str_replace("</script>", "", $googleanalytics_code);
	if( $googleanalytics_code != "" ){
		echo '<script>';
		echo $googleanalytics_code;
		echo '</script>';
	}
}
add_action('wp_head', 'add_analytic_code',80);


/*------------------------------------*\
	Invoice Post Type Fix
\*------------------------------------*/
function pf_invoices_mainfix(){
	global $post_type;
	if ($post_type == 'pointfinderinvoices') {
		echo '<style>html{height:100%!important}</style>';
	}
}
add_action('wp_head','pf_invoices_mainfix');


/*------------------------------------*\
	WP Editor Fix
\*------------------------------------*/
function pf_newwp_editor_action($item_desc){
	add_editor_style();
	$ed_settings = array(
		'media_buttons' => false,
		'teeny' => true,
		'editor_class' => 'textarea mini',
		'textarea_name' => 'item_desc',
		'quicktags' => false,
		'drag_drop_upload' => false
	);
	ob_start();
	wp_editor( $item_desc, 'item_desc', $ed_settings );
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_action('pf_desc_editor_hook','pf_newwp_editor_action');


/*------------------------------------*\
	pre_get_posts filters for feed
\*------------------------------------*/
add_action( 'pre_get_posts', 'pointfinder_modify_query_notin_list' );
function pointfinder_modify_query_notin_list( $query ) {

	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


    if ( isset($query->query_vars['post_type'])) {
        if ($query->query_vars['post_type'] == $setup3_pointposttype_pt1) {

	
			if ($query->is_feed) {
				 $query->set('post_status', array('publish'));

				 /* On/Off filter for items*/
	        	$meta_query = $query->get('meta_query');
				$meta_query[] = array('relation' => 'OR',array(
				                    'key'=>'pointfinder_item_onoffstatus',
				                    'value'=> 0,
				                    'compare'=>'=',
				                    'type' => 'NUMERIC'
				                ),
								array(
				                    'key'=>'pointfinder_item_onoffstatus',
				                    'compare' => 'NOT EXISTS'
				                )
				);
				
				$query->set('meta_query',$meta_query);
			}


        	

        }
    }

}

?>