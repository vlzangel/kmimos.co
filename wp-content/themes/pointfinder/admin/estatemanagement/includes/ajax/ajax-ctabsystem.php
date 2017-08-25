<?php
/**********************************************************************************************************************************
*
* Ajax Custom Tab System
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_customtabsystem', 'pf_ajax_customtabsystem' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_customtabsystem', 'pf_ajax_customtabsystem' );

function pf_ajax_customtabsystem(){
  
	//Security
	check_ajax_referer( 'pfget_customtabsystem', 'security');
  
	header('Content-Type: text/html; charset=UTF-8;');

	$ltid = $postid = '';

	if(isset($_POST['ltid']) && $_POST['ltid']!=''){
		$ltid = sanitize_text_field($_POST['ltid']);
	}

	if(isset($_POST['postid']) && $_POST['postid']!=''){
		$postid = sanitize_text_field($_POST['postid']);
	}

	$lang_c = '';
	if(isset($_POST['lang']) && $_POST['lang']!=''){
		$lang_c = sanitize_text_field($_POST['lang']);
	}


	if(function_exists('icl_object_id')) {
		global $sitepress;
		if (isset($sitepress) && !empty($lang_c)) {
			$sitepress->switch_lang($lang_c);
		}
	}

	/**
	*Custom Tabs
	**/

	
	global $pointfindertheme_option;
	global $pfadvancedcontrol_options;

	$stp4_ctt1 = PFSAIssetControl('stp4_ctt1','',0);
	$stp4_ctt2 = PFSAIssetControl('stp4_ctt2','',0);
	$stp4_ctt3 = PFSAIssetControl('stp4_ctt3','',0);
	//$stp4_ctth = PFSAIssetControl('stp4_ctth','','0');

	if (empty($ltid)) {
		$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
	}elseif (PFADVIssetControl('setupadvancedconfig_'.$ltid.'_advanced_status','','0') == 1 && !empty($ltid)) {
		$setup42_itempagedetails_configuration = (isset($pfadvancedcontrol_options['setupadvancedconfig_'.$ltid.'_configuration']))? $pfadvancedcontrol_options['setupadvancedconfig_'.$ltid.'_configuration'] : array();
	}else{
		$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
	}
		
		/* Start: Custom Tab 1 */
			if(array_key_exists('customtab1', $setup42_itempagedetails_configuration) && $stp4_ctt1 == 1){
				if ($setup42_itempagedetails_configuration['customtab1']['status'] == 1) {
					echo '<div class="pfsubmit-title">'.$setup42_itempagedetails_configuration['customtab1']['title'].'</div>';
					echo '<section class="pfsubmit-inner">';
						$ct1_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox1',true) : '' ;

						echo '
						<section class="pfsubmit-inner-sub">
	                        <label class="lbl-ui">';
	                        /*if ($stp4_ctth == 1) {
	                        	do_action( 'pf_ct1_editor_hook',$ct1_content);
	                        }*/
	                        echo '<textarea id="webbupointfinder_item_custombox1" name="webbupointfinder_item_custombox1" class="textarea mini">'.$ct1_content.'</textarea>';
	                    echo '</label></section>';
					echo '</section>';
				}
			}
		/* End: Custom Tab 1 */

		/* Start: Custom Tab 2 */
			if(array_key_exists('customtab2', $setup42_itempagedetails_configuration) && $stp4_ctt2 == 1){
				if ($setup42_itempagedetails_configuration['customtab2']['status'] == 1) {
					echo '<div class="pfsubmit-title">'.$setup42_itempagedetails_configuration['customtab2']['title'].'</div>';
					echo '<section class="pfsubmit-inner">';
						$ct2_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox2',true) : '' ;

						echo '
						<section class="pfsubmit-inner-sub">
	                        <label class="lbl-ui">';
	                        
	                        echo '<textarea id="webbupointfinder_item_custombox2" name="webbupointfinder_item_custombox2" class="textarea mini">'.$ct2_content.'</textarea>';
	                    echo '</label></section>';
					echo '</section>';
				}
			}
		/* End: Custom Tab 2 */

		/* Start: Custom Tab 3 */
			if(array_key_exists('customtab3', $setup42_itempagedetails_configuration) && $stp4_ctt3 == 1){
				if ($setup42_itempagedetails_configuration['customtab3']['status'] == 1) {
					echo '<div class="pfsubmit-title">'.$setup42_itempagedetails_configuration['customtab3']['title'].'</div>';
					echo '<section class="pfsubmit-inner">';
						$ct3_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox3',true) : '' ;

						echo '
						<section class="pfsubmit-inner-sub">
	                        <label class="lbl-ui">';
	                        
	                        echo '<textarea id="webbupointfinder_item_custombox3" name="webbupointfinder_item_custombox3" class="textarea mini">'.$ct3_content.'</textarea>';
	                    echo '</label></section>';
					echo '</section>';
				}
			}
		/* End: Custom Tab 3 */
	
	/**
	*Custom Tabs
	**/

	die();
}

?>