<?php

/**********************************************************************************************************************************
*
* PointFinder Functions
* 
* Author: Webbu Design
*

***********************************************************************************************************************************/

/*add_filter( 'woocommerce_checkout_fields' , 'set_input_attrs' );
function set_input_attrs( $fields ) {
	$fields['billing']['billing_first_name'] = array('required'  => false);
	$fields['billing']['billing_last_name'] = array('required'  => false);
	$fields['billing']['billing_email'] = array('required'  => false);
	$fields['billing']['billing_phone'] = array('required'  => false);
	$fields['billing']['billing_address_1'] = array('required'  => false);
	$fields['billing']['billing_address_2'] = array('required'  => false);
	$fields['billing']['billing_city'] = array('required'  => false);
	$fields['billing']['billing_state'] = array('required'  => false);
	$fields['billing']['billing_postcode'] = array('required'  => false);
   	return $fields;
}*/

// add_filter( 'show_admin_bar', '__return_false' );

add_filter( 'woocommerce_checkout_fields' , 'set_input_attrs' );
function set_input_attrs( $fields ) {
	$fields['billing']['billing_address_2']['maxlength'] = 50;

	$fields['billing']['billing_postcode']['maxlength'] = 12;

	$fields['billing']['billing_country']['class'][] = "hide";
/*
	echo "<pre>";
		print_r($fields);
	echo "</pre>";*/

   	return $fields;
}

// function wpdm_filter_siteurl($content) {
// 	$current_server = $_SERVER['SERVER_NAME'];
//    	return "http://".$current_server."/";
// }

// function wpdm_filter_home($content) {
// 	$current_server = $_SERVER['SERVER_NAME'];
//    	return "http://".$current_server."/";
// }

// function wpdm_conv_tag($content) {
// 	$search = "/\[dmWpAddr\]/";
// 	if (preg_match($search, $content)){
// 		$replace = get_option('siteurl');
// 		$content = preg_replace ($search, $replace, $content);
// 	}
// 	$search = "/\[dmBlogAddr\]/";
// 	if (preg_match($search, $content)){
// 		$replace = get_option('home');
// 		$content = preg_replace ($search, $replace, $content);
// 	}
// 	$search = "/\[dmBlogTitle\]/";
// 	if (preg_match($search, $content)){
// 		$replace = get_option('blogname');
// 		$content = preg_replace ($search, $replace, $content);
// 	}
// 	$search = "/\[dmTagLine\]/";
// 	if (preg_match($search, $content)){
// 		$replace = get_option('blogdescription');
// 		$content = preg_replace ($search, $replace, $content);
// 	}
// 	return $content;
// }

// // Add the hooks:
// add_filter('option_siteurl', 'wpdm_filter_siteurl', 1);
// add_filter('option_home', 'wpdm_filter_home', 1);


// function vlz_plugins_url($path = '', $plugin = '') {
// 	$new_path = explode("/", $path);
// 	$new_path[2] = $_SERVER['SERVER_NAME'];
// 	return implode("/", $new_path);
// }
// add_filter('plugins_url', 'vlz_plugins_url', -10);


// add_filter('the_content', 'wpdm_conv_tag'); 
// add_filter('the_excerpt', 'wpdm_conv_tag'); 


load_theme_textdomain( 'pointfindert2d',get_template_directory() . '/languages');

add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98);
function sb_woo_remove_reviews_tab($tabs) {
	unset($tabs['reviews']);
	return $tabs;
}

/**
 * Removes the "Buy" button from list of products (ex. category pages).
 */
add_action( 'woocommerce_after_shop_loop_item', 'mycode_remove_add_to_cart_buttons', 1 );
function mycode_remove_add_to_cart_buttons() {
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
}

add_action( 'woocommerce_after_shop_loop_item', 'mycode_add_more_info_buttons', 1 );
function mycode_add_more_info_buttons() {
    add_action( 'woocommerce_after_shop_loop_item', 'mycode_more_info_button' );
}
function mycode_more_info_button() {
	global $product;
	echo '<a href="' . get_permalink( $product->id ) . '" class="button add_to_cart_button product_type_external">Reservar</a>';
}

//Woocommerce only 1 product in the cart
add_filter( 'woocommerce_add_cart_item_data', '_empty_cart' );
function _empty_cart( $cart_item_data ){
	WC()->cart->empty_cart();
	echo "<pre>";
		print_r($cart_item_data);
	echo "</pre>";
	return $cart_item_data;
}


/*------------------------------------

	Theme Support

------------------------------------*/

function is_cuidador(){
	$user = wp_get_current_user();
	if( $user->roles[0] == 'vendor' ){
		return 1;
	}
	$user_id = $user->ID;
	if( $user_id != 0 ){
		$query_postulaciones = new WP_Query( 
			array( 
				'author' => $user_id,
				'post_type' => 'postulation' 
			) 
		);
		if ( count( $query_postulaciones->posts ) > 0) {
			return 2;
		}else{
			return 3;
		}
	}else{
		return 0;
	}
	/*
		0: No esta logeado
		1: Es cuidador
		2: Es cliente con postulacion
		3: Es cliente sin postulacion
	*/
}

if ( ! function_exists( 'pointfinder_setup' ) ){

	function pointfinder_setup() {

		if (!isset($content_width)){$content_width = 1170;}

		

		

		add_theme_support( 'automatic-feed-links' );

		add_theme_support('menus');

		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	    add_theme_support('post-thumbnails');



	    add_image_size('large', 700, '', true); 

	    add_image_size('medium', 250, '', true);

	    add_image_size('small', 120, '', true);

	    add_image_size('ItemSize2x', 880, 660, true);

	    add_image_size('ItemSizex', 440, 330, true);



	    add_theme_support( 'bbpress' );

	    add_theme_support( 'woocommerce' );

		add_theme_support( 'html5', array(

			'search-form', 'comment-form', 'comment-list',

		) );



		if ( ! function_exists( '_wp_render_title_tag' ) ) {

		    add_action( 'wp_head', 'pe_theme_render_title');

		}

		add_theme_support( 'title-tag' );



		register_nav_menus(array( 

			'pointfinder-main-menu' => esc_html__('Point Finder Main Menu', 'pointfindert2d'),

			'pointfinder-footer-menu' => esc_html__('Point Finder Footer Menu', 'pointfindert2d')

	    ));









	}

};

add_action('after_setup_theme', 'pointfinder_setup');





/*------------------------------------

	Ultimate Addon Fixes

------------------------------------*/

if ( function_exists( 'vc_set_as_theme' ) ) {
	vc_manager()->setIsAsTheme( true );
	vc_manager()->disableUpdater();
}



$pf_ultimate_constants = array(
	'ULTIMATE_NO_UPDATE_CHECK' => true,
	'ULTIMATE_NO_EDIT_PAGE_NOTICE' => false,
	'ULTIMATE_NO_PLUGIN_PAGE_NOTICE' => false
);

update_option('ultimate_constants',$pf_ultimate_constants);
update_option('ultimate_theme_support','enable');
update_option('ultimate_updater','disabled');
update_option('ultimate_vc_addons_redirect',false);

function pointfinder_ultimate_fix(){
	echo '<style>';
		echo '
			/*.bsf-update-nag{display: none!important}*/
			
            .update-nag{
                display: none !important;
            }
            #setting-error-tgmpa{
                display: none !important;
        	}
        	
		';
	echo '</style>';
}

add_action('admin_head','pointfinder_ultimate_fix');



define('BSF_PRODUCT_NAGS', false);



/*------------------------------------

  Options Panel & Common functions

------------------------------------*/ 

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

remove_action( 'wp_print_styles', 'print_emoji_styles' );



require_once( get_template_directory(). '/admin/core/admin-welcomepf.php' );

require_once( get_template_directory(). '/admin/core/update-notice.php' );

require_once( get_template_directory(). '/admin/core/ajax-nagsystem.php' );



/* Quick Install */

require_once( get_template_directory(). '/admin/quick_setup/init.php' );

require_once( get_template_directory(). '/admin/estatemanagement/includes/functions/common-functions.php' );







include_once( ABSPATH . 'wp-admin/includes/plugin.php' );



if ( is_plugin_active('redux-framework/redux-framework.php') ) {

	

	function pointfinder_removeDemoModeLink() { 

	    if ( class_exists('ReduxFrameworkPlugin') ) {

	        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );

	    }

	    if ( class_exists('ReduxFrameworkPlugin') ) {

	        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    

	    }

	}

	add_action('init', 'pointfinder_removeDemoModeLink');



	



	require_once( get_template_directory(). '/admin/options/PointfinderOptions.config.php' ); 

	require_once( get_template_directory(). '/admin/options/Metabox.config.php' );

	require_once( get_template_directory(). '/admin/options/PointfinderOptionsFMB.config.php' );

	require_once( get_template_directory(). '/admin/options/PFASControl.config.php' );

	require_once( get_template_directory(). '/admin/options/PFPBControl.config.php' );

	require_once( get_template_directory(). '/admin/options/CustomFields.config.php' );

	require_once( get_template_directory(). '/admin/options/SearchFields.config.php' );

	function PF_CustomPointsFilter(){

		require_once( get_template_directory(). '/admin/options/CustomPoints.config.php' );

		require_once( get_template_directory(). '/admin/options/PFAdvancedControl.config.php' );

	}

	add_action('init', 'PF_CustomPointsFilter',10);

	

	require_once( get_template_directory(). '/admin/options/MailSystem.config.php' );

	require_once( get_template_directory(). '/admin/options/SidebarGenerator.config.php' );

	require_once( get_template_directory(). '/admin/options/TwitterWidget.config.php' );

	require_once( get_template_directory(). '/admin/options/rCaptcha.config.php' );

	require_once( get_template_directory(). '/admin/options/ReviewSystem.config.php' );

	require_once( get_template_directory(). '/admin/options/PFSizeControl.config.php' );

	require_once( get_template_directory(). '/admin/options/PFPGControl.config.php' );





	/* 

	*	v1.6.4.5 New Payment Gateway System

	*	Move Paypal Settings to New Admin Panel

	*/

		$orj_paypal_api_user = PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');

		$new_paypal_api_user = PFPGIssetControl('setup20_paypalsettings_paypal_api_user','','');



		$paypal_move = get_option('pointfinder_paypal_move',0);

		if (!empty($orj_paypal_api_user) && empty($new_paypal_api_user) && $paypal_move != 1) {

			

			global $pointfinder_pg_options;

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_api_user', $orj_paypal_api_user);



	    	$setup20_paypalsettings_paypal_status = PFSAIssetControl('setup20_paypalsettings_paypal_status','','');

	    	$setup20_paypalsettings_paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','');

	    	$setup31_userpayments_recurringoption = PFSAIssetControl('setup31_userpayments_recurringoption','','');

	    	$setup20_paypalsettings_paypal_verified = PFSAIssetControl('setup20_paypalsettings_paypal_verified','','');

	    	$setup20_paypalsettings_paypal_price_unit = PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','');

	    	$setup20_paypalsettings_paypal_api_pwd = PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');

	    	$setup20_paypalsettings_paypal_api_signature = PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','');





	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_status', $setup20_paypalsettings_paypal_status);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_sandbox', $setup20_paypalsettings_paypal_sandbox);

	    	$pointfinder_pg_options->ReduxFramework->set('setup31_userpayments_recurringoption', $setup31_userpayments_recurringoption);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_verified', $setup20_paypalsettings_paypal_verified);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_price_unit', $setup20_paypalsettings_paypal_price_unit);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_api_pwd', $setup20_paypalsettings_paypal_api_pwd);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_paypalsettings_paypal_api_signature', $setup20_paypalsettings_paypal_api_signature);



	    	update_option('pointfinder_paypal_move', 1 );

		}

	/* 

	*	v1.6.4.5 New Payment Gateway System

	*	Move Stripe Settings to New Admin Panel

	*/



		$orj_stripe_api_secretkey = PFSAIssetControl('setup20_stripesettings_secretkey','','');

		$new_stripe_api_secretkey = PFPGIssetControl('setup20_stripesettings_secretkey','','');



		$stripe_move = get_option('pointfinder_stripe_move',0);

		if (!empty($orj_stripe_api_secretkey) && empty($new_stripe_api_secretkey) && $stripe_move != 1) {

			

			global $pointfinder_pg_options;

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_stripesettings_secretkey', $orj_stripe_api_secretkey);



	    	$setup20_stripesettings_status = PFSAIssetControl('setup20_stripesettings_status','','');

	    	$setup20_stripesettings_publishkey = PFSAIssetControl('setup20_stripesettings_publishkey','','');

	    	$setup20_stripesettings_sitename = PFSAIssetControl('setup20_stripesettings_sitename','','');

	    	$setup20_stripesettings_currency = PFSAIssetControl('setup20_stripesettings_currency','','');

	    	$setup20_stripesettings_decimals = PFSAIssetControl('setup20_stripesettings_decimals','','');





	    	$pointfinder_pg_options->ReduxFramework->set('setup20_stripesettings_status', $setup20_stripesettings_status);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_stripesettings_publishkey', $setup20_stripesettings_publishkey);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_stripesettings_sitename', $setup20_stripesettings_sitename);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_stripesettings_currency', $setup20_stripesettings_currency);

	    	$pointfinder_pg_options->ReduxFramework->set('setup20_stripesettings_decimals', $setup20_stripesettings_decimals);



	    	update_option('pointfinder_stripe_move', 1 );

		}



}





/* VC includes */

if(function_exists('vc_set_as_theme')){

	

	require_once( get_template_directory().'/admin/includes/vcextend/pfvisualcomposeraddons.php');

	require_once( get_template_directory().'/admin/includes/vcextend/pfvisualcomposertemplates.php');

	require_once( get_template_directory().'/admin/includes/vcextend/vc_customfields.php');

	require_once( get_template_directory().'/admin/includes/vcextend/vc_extend.php');



	$setup3_pointposttype_pt6_status = PFSAIssetControl('setup3_pointposttype_pt6_status','','1');

	if ($setup3_pointposttype_pt6_status == 1) {

		require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-list-agents.php');

	}

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf_text_separator.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf_pfitem_carousel.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-grid-shortcodes.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-grid-shortcodes-static.php');

	//require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-map-shortcodes.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-cmap-shortcodes.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-cform-shortcodes.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-directorylist.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-itemslider-shortcodes.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-search.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/vc_client_carousel.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/vc_testimonials.php');

	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/vc_pfinfobox.php');

}



	

/*WPML Custom Strings*/

if(function_exists('icl_object_id')) {

	define('ICL_DONT_LOAD_NAVIGATION_CSS', true);

	define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);

	define('ICL_DONT_LOAD_LANGUAGES_JS', true);

	require_once( get_template_directory(). '/admin/core/pf-wpml.php' );

}

/*------------------------------------

	External Modules/Files

------------------------------------*/

$username = PFASSIssetControl('setup_autoupdate_username','','');

$apikey   = PFASSIssetControl('setup_autoupdate_apikey','','');

if (!empty($username) && !empty($apikey)) {

	load_template( trailingslashit( get_template_directory() ) . '/admin/core/updates/envato-wp-theme-updater.php' );

	Envato_WP_Theme_Updater::init( $username, $apikey, 'Webbu (@webbudesign)');

}



//Taxonomies & CPT Metaboxes

require_once( get_template_directory().'/admin/estatemanagement/taxonomymeta/taxonomy-meta.php');

require_once( get_template_directory().'/admin/estatemanagement/metabox/meta-box.php');

require_once( get_template_directory().'/admin/estatemanagement/pointfinder-metabox.php');

require_once( get_template_directory().'/admin/estatemanagement/pointfinder-metabox-ex.php');

require_once( get_template_directory().'/admin/estatemanagement/taxonomymeta/locations.php');



$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

if($setup3_pointposttype_pt6_check == 1){

	require_once( get_template_directory().'/admin/core/new-features-pt.php');

}





$redirect_check = get_option('pointfinder_do_redirect');

if ($redirect_check != true) {

	add_action('init','pointfinder_do_redirect');

}

function pointfinder_do_redirect() {

	update_option('pointfinder_do_redirect',true);

    wp_redirect( admin_url( 'admin.php?page=pointfinder_demo_installer' ) );

    exit();

}





//Estate management files

require_once( get_template_directory().'/admin/estatemanagement/post-types.php');

require_once( get_template_directory().'/admin/core/aq_resizer.php');

require_once( get_template_directory().'/admin/tgm/plugins.php');



if ( is_active_widget( false, '', 'pf_twitter_w', true ) ) {

	require_once( get_template_directory().'/admin/core/ajax-grabtweets.php');

}



//Membership Packages

$setup4_membersettings_paymentsystem = PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

if ($setup4_membersettings_paymentsystem == 2) {

	require_once( get_template_directory(). '/admin/estatemanagement/membership_packages.php' );

}else{

	require_once( get_template_directory(). '/admin/estatemanagement/ppp_packages.php' );

}







//Scripts

require_once( get_template_directory().'/admin/core/scripts.php');





//Hooks

require_once( get_template_directory().'/admin/core/hooks.php');





//Filters

require_once( get_template_directory().'/admin/core/filters.php');



//Mega Menu

require_once( get_template_directory().'/admin/core/megamenu.php');





//Dynamic Css Styles

require_once( get_template_directory().'/admin/core/dynamic_css.php');





//reCaptcha

if (PFRECIssetControl('setupreCaptcha_general_status','','0') == 1) {

	require_once( get_template_directory().'/admin/core/recaptchalib.php');

}





//Admin Dashboard Widget

require_once( get_template_directory().'/admin/core/admin-dash.php');









//Field creation classes

require_once( get_template_directory().'/admin/includes/pfgetcustomfields.php');

require_once( get_template_directory().'/admin/includes/pfgetsearchfields.php');

require_once( get_template_directory().'/admin/includes/pfgetsubsearchfields.php');

require_once( get_template_directory().'/admin/includes/pfcustomwidgets.php');





//Ajax include files

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-infowindow.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-taxpoint.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-poidata.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-listdata.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-modalsystem.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-modalsystemhandler.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-reportsystem.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-searchitems.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-featuresfilter.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-claimsystem.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-autocomplete.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-ownerworks.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-ctabsystem.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-listingtypelimits.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-listingtypes.php');





require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-featuresystem.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-fieldsystem.php');

if($setup4_membersettings_paymentsystem == 2){

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-membershipsystem.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-membershippaymentsystem.php');

}



if (PFREVSIssetControl('setup11_reviewsystem_check','','0') == 1) {

	require_once( get_template_directory().'/admin/estatemanagement/review-metabox.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/functions/modified-review-functions.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-revflagsystem.php');

}



require_once( get_template_directory().'/admin/estatemanagement/includes/functions/additional-cpt-statusses.php');

require_once( get_template_directory().'/admin/estatemanagement/includes/functions/mailsender-functions.php');



if(PFSAIssetControl('setup4_membersettings_loginregister','','1') == 1){

	//session_start();



	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-imageupload.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-imagesystem.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-filesystem.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-fileupload.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-usersystem.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-paymentsystem.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-usersystemhandler.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-favoritesystem.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/user-profilemods.php');

	require_once( get_template_directory().'/admin/estatemanagement/includes/modules/social-logins.php');



	if(PFSAIssetControl('setup4_membersettings_frontend','','1') == 1){

		require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-onoffsystem.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-listingpaymentsystem.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-posttag.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/functions/common-user-functions.php' );

		require_once( get_template_directory().'/admin/estatemanagement/includes/modules/paypall-class.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/functions/modified-functions.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/functions/statuschange-functions.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/schedule-config.php');

		require_once( get_template_directory().'/admin/estatemanagement/payment-metabox.php');

		require_once( get_template_directory().'/admin/estatemanagement/invoices-metabox.php');



		require_once( get_template_directory().'/admin/estatemanagement/includes/pages/dashboard/dashboard-functions.php');

		require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-itemsystem.php');

	}    





}





// Add Actions

add_action('wp_enqueue_scripts', 'pf_styleandscripts'); 

add_action('get_header', 'enable_threaded_comments'); 

add_action('widgets_init', 'pointfinder_remove_recent_comments_style'); 

add_action('widgets_init', 'pointfinder_widgets_init',0 );

add_action('admin_enqueue_scripts','pf_admin_styleandscripts',10);





// Add Filters

add_filter('avatar_defaults', 'pointfindert2dgravatar'); 

add_filter('body_class', 'add_slug_to_body_class');

add_filter('widget_text', 'do_shortcode'); 

add_filter('widget_text', 'shortcode_unautop');

add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args');

add_filter('the_category', 'remove_category_rel_from_category_list'); 

add_filter('the_excerpt', 'shortcode_unautop'); 

add_filter('the_excerpt', 'do_shortcode'); 

add_filter('style_loader_tag', 'pointfinderh_style_remove');

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); 

add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);



add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

?>