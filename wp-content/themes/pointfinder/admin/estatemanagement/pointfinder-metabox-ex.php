<?php
/**********************************************************************************************************************************
*
* Point Finder Item Add Page Metabox.
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

/**
*Start:Enqueue Styles
**/
function pointfinder_orders_styles_ex(){
	$screen = get_current_screen();
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	if ($screen->post_type == $setup3_pointposttype_pt1) {

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');	

		wp_register_style( 'jquery-ui-core', get_home_url()."/wp-content/themes/pointfinder" ."/admin/estatemanagement/metabox/css/jqueryui/jquery.ui.core.css", array(), '1.8.17' );
		wp_register_style( 'jquery-ui-theme', get_home_url()."/wp-content/themes/pointfinder" ."/admin/estatemanagement/metabox/css/jqueryui/jquery.ui.theme.css", array(), '1.8.17' );
		wp_enqueue_style( 'jquery-ui-datepicker', get_home_url()."/wp-content/themes/pointfinder" ."/admin/estatemanagement/metabox/css/jqueryui/jquery.ui.datepicker.css", array( 'jquery-ui-core', 'jquery-ui-theme' ), '1.8.17' );

		wp_register_script(
			'metabox-custom-cf-scriptspf', 
			get_home_url()."/wp-content/themes/pointfinder" . '/admin/core/js/metabox-scripts.js', 
			array('jquery'),
			'1.0.0',
			true
		); 
        wp_enqueue_script('metabox-custom-cf-scriptspf'); 

        wp_register_style('pfsearch-goldenforms-css', get_home_url()."/wp-content/themes/pointfinder" . '/css/golden-forms.css', array(), '1.0', 'all');
		wp_enqueue_style('pfsearch-goldenforms-css');


	}
}
add_action('admin_enqueue_scripts','pointfinder_orders_styles_ex' );
/**
*End:Enqueue Styles
**/



/**
*Start : Add Metaboxes
**/
	function pointfinder_orders_add_meta_box_ex($post_type) {
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

		if ($post_type == $setup3_pointposttype_pt1) {
			$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
			$setup3_pointposttype_pt6 = PFSAIssetControl('setup3_pointposttype_pt6','','Features');

			remove_meta_box( 'pointfinderltypesdiv', $setup3_pointposttype_pt1, 'side' );
			remove_meta_box( 'pointfinderconditionsdiv', $setup3_pointposttype_pt1, 'side' );
			remove_meta_box( 'pointfinderfeaturesdiv', $setup3_pointposttype_pt1, 'side' );
			
			add_meta_box(
				'pointfinder_itemdetailcf_process_lt',
				$setup3_pointposttype_pt7s,
				'pointfinder_itemdetailcf_process_lt_function',
				$setup3_pointposttype_pt1,
				'normal',
				'high'
			);

			add_meta_box(
				'pointfinder_itemdetailcf_process',
				esc_html__( 'Additional Details', 'pointfindert2d' ),
				'pointfinder_itemdetailcf_process_function',
				$setup3_pointposttype_pt1,
				'normal',
				'high'
			);
			$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
			if ($setup3_pointposttype_pt6_check ) {
				add_meta_box(
					'pointfinder_itemdetailcf_process_fe',
					$setup3_pointposttype_pt6,
					'pointfinder_itemdetailcf_process_fe_function',
					$setup3_pointposttype_pt1,
					'normal',
					'core'
				);
			}
			$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
			$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
			if ($setup3_modulessetup_openinghours == 1) {
				add_meta_box(
					'pointfinder_itemdetailoh_process_fe',
					esc_html__( 'Opening Hours', 'pointfindert2d' ).' <small>('.esc_html__('Leave blank to show closed','pointfindert2d' ).')</small>',
					'pointfinder_itemdetailoh_process_fe_function',
					$setup3_pointposttype_pt1,
					'normal',
					'high'
				);
			}


			$setup3_pt14_check = PFSAIssetControl('setup3_pt14_check','',0);
			$setup3_pt14s = PFSAIssetControl('setup3_pt14s','','Condition');
			if ($setup3_pt14_check ) {
				add_meta_box(
					'pointfinder_itemdetailcf_process_co',
					$setup3_pt14s,
					'pointfinder_itemdetailcf_process_co_function',
					$setup3_pointposttype_pt1,
					'side',
					'core'
				);
			}

		}

		
	}
	add_action( 'add_meta_boxes', 'pointfinder_orders_add_meta_box_ex', 9,1);
/**
*End : Add Metaboxes
**/



/**
*Start : Listing Type
**/
function pointfinder_itemdetailcf_process_lt_function( $post ) {
	
	/* Get admin panel defaults */
	$setup4_submitpage_listingtypes_title = PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
	$setup4_submitpage_sublistingtypes_title = PFSAIssetControl('setup4_submitpage_sublistingtypes_title','','Sub Listing Type');
	$setup4_submitpage_subsublistingtypes_title = PFSAIssetControl('setup4_submitpage_subsublistingtypes_title','','Sub Sub Listing Type');

    $st4_sp_med = PFSAIssetControl('st4_sp_med','','1');
	$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
	$setup4_submitpage_locationtypes_check = PFSAIssetControl('setup4_submitpage_locationtypes_check','','1');
	$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
	$setup4_submitpage_itemtypes_check = PFSAIssetControl('setup4_submitpage_itemtypes_check','','1');
	$setup4_submitpage_imageupload = PFSAIssetControl('setup4_submitpage_imageupload','','1');
	$stp4_fupl = PFSAIssetControl("stp4_fupl","","0");
	$stp4_err_st = PFSAIssetControl("stp4_err_st","","0");
	$stp4_err = PFSAIssetControl("stp4_err","",esc_html__('Please upload an attachment.', 'pointfindert2d'));


	/* WPML Check */
	if(function_exists('icl_object_id')) {$lang_custom = PF_current_language();}else{$lang_custom = '';}

	/* Get Limits */
	$cat_extra_opts = get_option('pointfinderltypes_covars');

	/* Get selected listing types */
    $item_defaultvalue = ($post->ID != '') ? wp_get_post_terms($post->ID, 'pointfinderltypes', array("fields" => "ids")) : '' ;
	$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';

	if (count($item_defaultvalue) > 1) {
		if (isset($item_defaultvalue[0])) {
			$item_defaultvalue_output_orj = $item_defaultvalue[0];
			$find_top_parent = pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

			$ci=1;
			foreach ($item_defaultvalue as $value) {
				$sub_level .= $value;
				if ($ci < count($item_defaultvalue)) {
					$sub_level .= ',';
				}
				$ci++;
			}
			$item_defaultvalue_output = $find_top_parent['parent'];
		}
	}else{
		if (isset($item_defaultvalue[0])) {
			$item_defaultvalue_output_orj = $item_defaultvalue[0];
			$find_top_parent = pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

			switch ($find_top_parent['level']) {
				case '1':
					$sub_level = $item_defaultvalue[0];
					break;
				
				case '2':
					$sub_sub_level = $item_defaultvalue[0];
					$sub_level = pf_get_term_top_parent($item_defaultvalue[0],'pointfinderltypes');
					break;
			}
			

			$item_defaultvalue_output = $find_top_parent['parent'];
		}
	}
    echo '<div class="form-field">';
    echo '<section>';
    
    $listingtype_values = get_terms('pointfinderltypes',array('hide_empty'=>false,'parent'=> 0)); 
												
	echo '<input type="hidden" name="pfupload_listingtypes" id="pfupload_listingtypes" value="'.$item_defaultvalue_output.'"/>';

	echo '<div class="pflistingtype-selector-main-top clearfix">';

	$subcatsarray = "var pfsubcatselect = [";
	$multiplesarray = "var pfmultipleselect = [";
		foreach ($listingtype_values as $listingtype_value) {
			
			/* Multiple select & Subcat Select */
			$multiple_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']:2;
			$subcat_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']:2;

			if ($multiple_select == 1) {$multiplesarray .= $listingtype_value->term_id.',';}
			if ($subcat_select == 1) {$subcatsarray .= $listingtype_value->term_id.',';}


			echo '<div class="pflistingtype-selector-main">';
			echo '<input type="radio" name="radio" id="pfltypeselector'.$listingtype_value->term_id.'" class="pflistingtypeselector" value="'.$listingtype_value->term_id.'" '.checked( $item_defaultvalue_output, $listingtype_value->term_id,0).'/>';
			echo '<label for="pfltypeselector'.$listingtype_value->term_id.'">'.$listingtype_value->name.'</label>';
			echo '</div>';

		}
	echo '</div>';

	$subcatsarray .= "];";
	$multiplesarray .= "];";

	echo '<div style="margin-left:10px" class="pf-sub-listingtypes-container"></div>';

    echo '</section>';

    echo '
    <script>
    (function($) {
  	"use strict";';
  	echo $subcatsarray.$multiplesarray;
  	echo "
	/* Start: Function for sub listing types */
		$.pf_get_sublistingtypes = function(itemid,defaultv){
			if ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1) {
				var multiple_ex = 1;
			}else{
				var multiple_ex = 0;
			}
			$.ajax({
		    	beforeSend:function(){
		    		$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'show',message: '".esc_html__('Loading fields...','pointfindert2d')."'});
		    	},
				url: '".get_home_url()."/wp-content/themes/pointfinder"."/admin/core/pfajaxhandler.php',
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_listingtype',
					id: itemid,
					default: defaultv,
					sname: 'pfupload_sublistingtypes',
					stext: '".$setup4_submitpage_sublistingtypes_title."',
					stype: 'listingtypes',
					stax: 'pointfinderltypes',
					lang: '".$lang_custom."',
					multiple: multiple_ex,
					security: '".wp_create_nonce('pfget_listingtype')."'
				},
			}).success(function(obj) {
				$('.pf-sub-listingtypes-container').append('<div class=\'pfsublistingtypes\'>'+obj+'</div>');
					
					if (obj != '') {
					$('#pfupload_sublistingtypes').select2({
						placeholder: '".esc_html__('Please select','pointfindert2d')."', 
						formatNoMatches:'".esc_html__('No match found','pointfindert2d')."',
						allowClear: true, 
						minimumResultsForSearch: 10
					});";

					if (empty($sub_sub_level)) {
					echo " if ($('#pfupload_sublistingtypes').val() != 0 && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
						$.pf_get_subsublistingtypes($('#pfupload_sublistingtypes').val(),'');
					}";
					}
					echo "

					$('#pfupload_sublistingtypes').change(function(){
						if($('#pfupload_sublistingtypes').val() != 0){
							if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
								$('#pfupload_listingtypes').val($(this).val()).trigger('change');
							}else{
								$('#pfupload_listingtypes').val($(this).val());
							}
							if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
								$.pf_get_subsublistingtypes($(this).val(),'');
							}
						}else{
							if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
								$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val());
							}else{
								$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val()).trigger('change');
							}
						}
						$('.pfsubsublistingtypes').remove();
					});
				}

			}).complete(function(obj,obj2){

				if (obj.responseText != '') {
				if (defaultv != '') {
					if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
						$('#pfupload_listingtypes').val(defaultv).trigger('change');
					}else{
						$('#pfupload_listingtypes').val(defaultv);
					}
				}else{
					
					if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
						$('#pfupload_listingtypes').val(itemid).trigger('change');
					}else{
						$('#pfupload_listingtypes').val(itemid);
					}
				}
				}
				setTimeout(function(){
					$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'hide'});
				},1000);
				";
				
				if (!empty($sub_sub_level)) {
					echo "
					if (".$sub_level." == $('#pfupload_sublistingtypes').val()) {
						$.pf_get_subsublistingtypes('".$sub_level."','".$sub_sub_level."');
					}
					";
				}
				echo "
			});
		}

		$.pf_get_subsublistingtypes = function(itemid,defaultv){
			$.ajax({
		    	beforeSend:function(){
		    		$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'show',message: '".esc_html__('Loading fields ...','pointfindert2d')."'});
		    	},
				url: '".get_home_url()."/wp-content/themes/pointfinder"."/admin/core/pfajaxhandler.php',
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_listingtype',
					id: itemid,
					default: defaultv,
					sname: 'pfupload_subsublistingtypes',
					stext: '".$setup4_submitpage_subsublistingtypes_title."',
					stype: 'listingtypes',
					stax: 'pointfinderltypes',
					lang: '".$lang_custom."',
					security: '".wp_create_nonce('pfget_listingtype')."'
				},
			}).success(function(obj) {
				$('.pf-sub-listingtypes-container').append('<div class=\'pfsubsublistingtypes\'>'+obj+'</div>');
					if (obj != '') {
					$('#pfupload_subsublistingtypes').select2({
						placeholder: '".esc_html__('Please select','pointfindert2d')."', 
						formatNoMatches:'".esc_html__('No match found','pointfindert2d')."',
						allowClear: true, 
						minimumResultsForSearch: 10
					});

					$('#pfupload_subsublistingtypes').change(function(){
						if($('#pfupload_subsublistingtypes').val() != 0){
							
							if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
								$('#pfupload_listingtypes').val($(this).val()).trigger('change');
							}else{
								$('#pfupload_listingtypes').val($(this).val());
							}

						}else{
							
							if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
								$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val()).trigger('change');
							}else{
								$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val());
							}
						}
					});
					}
			}).complete(function(obj,obj2){
				if (obj.responseText != '') {
				if (defaultv != '') {
					if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
						$('#pfupload_listingtypes').val(defaultv).trigger('change');
					}else{
						$('#pfupload_listingtypes').val(defaultv);
					}
				}else{
					
					if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
						$('#pfupload_listingtypes').val(itemid).trigger('change');
					}else{
						$('#pfupload_listingtypes').val(itemid);
					}
				}
				}
				setTimeout(function(){
					$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'hide'});
				},1000);
			});
		}
	/* End: Function for sub listing types */
	";
	echo "var pflimitarray = [";
		$pflimittext = '';
		/*Get Limits for Areas*/
		if ($st4_sp_med == 1) {
			if (!empty($pflimittext)) {$pflimittext .= ",";}
			$pflimittext .= "'pf_address_area'";
		}

		/*Get Limits for Image Area*/
		if($setup4_submitpage_imageupload == 1){
			if (!empty($pflimittext)) {$pflimittext .= ",";}
			$pflimittext .= "'pf_image_area'";
		}

		/*Get Limits for File Area*/
		if($stp4_fupl == 1){
			if (!empty($pflimittext)) {$pflimittext .= ",";}
			$pflimittext .= "'pf_file_area'";
		}

		/*Get Limits for File Area*/
		$setup3_pt14_check = PFSAIssetControl('setup3_pt14_check','',0);
		if($setup3_pt14_check == 1){
			if (!empty($pflimittext)) {$pflimittext .= ",";}
			$pflimittext .= "'pf_condition_area'";
		}

		echo $pflimittext;
		echo "];";


	echo "$(function(){";
		/* if this is edit */
		if ($post->ID != '') {
			echo "$.pf_get_checklimits('".$item_defaultvalue_output."',pflimitarray);";
			echo "$.pf_get_sublistingtypes($('#pfupload_listingtypes').val(),'".$sub_level."');";
			if (empty($sub_sub_level) && !empty($sub_level)) {
				echo "$('#pfupload_listingtypes').val('".$sub_level."');";
			}
		}
	echo "});";
	

	/* Address/Location Area AJAX */
	echo "
	$.pf_get_checklimits = function(itemid,limitvalue){
		$.ajax({
			url: '".get_home_url()."/wp-content/themes/pointfinder"."/admin/core/pfajaxhandler.php',
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'pfget_listingtypelimits',
				id: itemid,
				limit: limitvalue,
				lang: '".$lang_custom."',
				security: '".wp_create_nonce('pfget_listingtypelimits')."'
			},
		}).success(function(obj) {
				if (obj !== null) {
					if (obj.pf_address_area == 2) {
						$('#pointfinder_map').hide();
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_streetview').hide();
					}else{
						$('#pointfinder_map').show();
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_streetview').show();
					}
				

					if (obj.pf_image_area == 2) {
						$('#gallery').hide();
					}else{
						$('#gallery').show();
					}

					if (obj.pf_file_area == 2) {
						$('#attachment-upload').hide();
					}else{
						$('#attachment-upload').show();
					}

					if (obj.pf_condition_area == 2) {
						$('#pointfinder_itemdetailcf_process_co').hide();
					}else{
						$('#pointfinder_itemdetailcf_process_co').show();
					}
				}

		}).complete(function(){
			
		});
	}";

	echo "
	$('.pflistingtypeselector').change(function(){
		$('.pf-sub-listingtypes-container').html('');
		$('#pfupload_listingtypes').val($(this).val()).trigger('change');
		$.pf_get_sublistingtypes($(this).val(),'');
		$.pf_get_checklimits($(this).val(),pflimitarray);
	});
	";


    $setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
    
		/* Opening Hours show/hide by Listing Category Options*/
		$taxonomies = array( 
            'pointfinderltypes'
        );

        $args = array(
            'orderby'           => 'name', 
            'order'             => 'ASC',
            'hide_empty'        => false, 
            'parent'            => 0,
        ); 
		$pf_get_term_details = get_terms($taxonomies,$args); 
        $pfstart = (!empty($pf_get_term_details))? true:false;

        $cbox_term_arr = "["; $cbox_term_arr2 = "["; $cbox_term_arr3 = "[";$ohours_term_arr = "[";

        global $pfadvancedcontrol_options;

		if($pfstart){
			foreach ($pf_get_term_details as &$pf_get_term_detail) {


				if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_advanced_status','','0') == 1) {
					
					if ($setup3_modulessetup_openinghours == 1) {
					
						if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_ohoursmodule','',$setup3_modulessetup_openinghours) == 0) {

							$ohours_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
							$ohours_term_arr .= ",";

							$args2 = array(
					            'orderby'           => 'name', 
					            'order'             => 'ASC',
					            'hide_empty'        => false, 
					            'parent'            => $pf_get_term_detail->term_id,
					        ); 
							$pf_get_term_details2 = get_terms($taxonomies,$args2); 
					        $pfstart = (!empty($pf_get_term_details2))? true:false;
					        if($pfstart){
					        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
					        		$ohours_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
									$ohours_term_arr .= ",";
					        	}
					        }
						}
					}
					
		 			$setup42_itempagedetails_configuration = (isset($pfadvancedcontrol_options['setupadvancedconfig_'.$pf_get_term_detail->term_id.'_configuration']))? $pfadvancedcontrol_options['setupadvancedconfig_'.$pf_get_term_detail->term_id.'_configuration'] : array();

		 			if (isset($setup42_itempagedetails_configuration['customtab1']['status'])) {
		 				if ($setup42_itempagedetails_configuration['customtab1']['status'] != 0) {

							$cbox_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
							$cbox_term_arr .= ",";

							$args2 = array(
					            'orderby'           => 'name', 
					            'order'             => 'ASC',
					            'hide_empty'        => false, 
					            'parent'            => $pf_get_term_detail->term_id,
					        ); 
							$pf_get_term_details2 = get_terms($taxonomies,$args2); 
					        $pfstart = (!empty($pf_get_term_details2))? true:false;
					        if($pfstart){
					        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
					        		$cbox_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
									$cbox_term_arr .= ",";
					        	}
					        }

						}
		 			}

		 			if (isset($setup42_itempagedetails_configuration['customtab2']['status'])) {
		 				if ($setup42_itempagedetails_configuration['customtab2']['status'] != 0) {

							$cbox_term_arr2 .= '"'.$pf_get_term_detail->term_id.'"';
							$cbox_term_arr2 .= ",";

							$args2 = array(
					            'orderby'           => 'name', 
					            'order'             => 'ASC',
					            'hide_empty'        => false, 
					            'parent'            => $pf_get_term_detail->term_id,
					        ); 
							$pf_get_term_details2 = get_terms($taxonomies,$args2); 
					        $pfstart = (!empty($pf_get_term_details2))? true:false;
					        if($pfstart){
					        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
					        		$cbox_term_arr2 .= '"'.$pf_get_term_detail2->term_id.'"';
									$cbox_term_arr2 .= ",";
					        	}
					        }

						}
		 			}

		 			if (isset($setup42_itempagedetails_configuration['customtab3']['status'])) {
		 				if ($setup42_itempagedetails_configuration['customtab3']['status'] != 0) {

							$cbox_term_arr3 .= '"'.$pf_get_term_detail->term_id.'"';
							$cbox_term_arr3 .= ",";

							$args2 = array(
					            'orderby'           => 'name', 
					            'order'             => 'ASC',
					            'hide_empty'        => false, 
					            'parent'            => $pf_get_term_detail->term_id,
					        ); 
							$pf_get_term_details2 = get_terms($taxonomies,$args2); 
					        $pfstart = (!empty($pf_get_term_details2))? true:false;
					        if($pfstart){
					        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
					        		$cbox_term_arr3 .= '"'.$pf_get_term_detail2->term_id.'"';
									$cbox_term_arr3 .= ",";
					        	}
					        }

						}
		 			}

				}
			}
		}

		$ohours_term_arr .= "]";$cbox_term_arr .= "]";$cbox_term_arr2 .= "]";$cbox_term_arr3 .= "]";

		echo "
		var openingharr = ".$ohours_term_arr.";
		var cboxarr1 = ".$cbox_term_arr.";
		var cboxarr2 = ".$cbox_term_arr2.";
		var cboxarr3 = ".$cbox_term_arr3.";
		$(function(){
			if ($( '#pfupload_listingtypes' ).val() != '') {

				if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr1 ) == -1) {
					if (cboxarr1.length > 0) {
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').hide();
					}
				}else{
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').show();
				}

				if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr2 ) == -1) {
					if (cboxarr2.length > 0) {
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').hide();
					}
				}else{
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').show();
				}

				if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr3 ) == -1) {
					if (cboxarr3.length > 0) {
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').hide();
					}
				}else{
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').show();
				}

				";

				if ($setup3_modulessetup_openinghours == 1) {
				echo "
				if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
					$('#pointfinder_openinghours').hide();$('#pointfinder_itemdetailoh_process_fe').hide();
				}else{
					$('#pointfinder_openinghours').show();$('#pointfinder_itemdetailoh_process_fe').show();
				}
				";
				}

				echo "
			}
			
		});

		$( '#pfupload_listingtypes' ).change(function(){	

			if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr1 ) == -1) {
				if (cboxarr1.length > 0) {
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').hide();
				}
			}else{
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').show();
			}

			if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr2 ) == -1) {
				if (cboxarr2.length > 0) {
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').hide();
				}
			}else{
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').show();
			}

			if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr3 ) == -1) {
				if (cboxarr3.length > 0) {
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').hide();
				}
			}else{
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').show();
			}

			";
			if ($setup3_modulessetup_openinghours == 1) {
			echo "		
			if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
				if (openingharr.length > 0) {
					$('#pointfinder_openinghours').hide();$('#pointfinder_itemdetailoh_process_fe').hide();
				}
			}else{
				$('#pointfinder_openinghours').show();$('#pointfinder_itemdetailoh_process_fe').show();
			}
			";
			}
			echo "
		});
		";

		echo "})(jQuery);</script></div>";
	
}
/**
*End : Listing Type
**/



/**
*Start : Custom Fields Content
**/
function pointfinder_itemdetailcf_process_function( $post ) {
	echo "<div class='golden-forms'>";
	echo "<section class='pfsubmit-inner pfsubmit-inner-customfields'></section>";
	echo "</div>";
	$lang_custom = '';

	if(function_exists('icl_object_id')) {
		$lang_custom = PF_current_language();
	}

	echo "<script>";
		echo "
		(function($) {
  		'use strict';
			$.pf_getcustomfields_now = function(itemid){

				$.ajax({
			    	beforeSend:function(){
			    		$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'show'});
			    	},
					url: '".get_home_url()."/wp-content/themes/pointfinder"."/admin/core/pfajaxhandler.php',
					type: 'POST',
					dataType: 'html',
					data: {
						action: 'pfget_fieldsystem',
						id: itemid,
						place:'backend',
						lang: '".$lang_custom."',
						postid:'".get_the_id()."',
						security: '".wp_create_nonce('pfget_fieldsystem')."'
					},
				})
				.done(function(obj) {
					if (obj.length == 0) {
						$('.pfsubmit-inner-customfields').hide();
					}else{
						$('.pfsubmit-inner-customfields').show();
					}
					$('.pfsubmit-inner-customfields').html(obj);
					$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'hide'});
				});
			}

			$( '#pfupload_listingtypes' ).change(function(){
				$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
			});

			$(function(){
				$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
			});
		})(jQuery);
		";
	echo "</script>";
}
/**
*End : Custom Fields Content
**/


/**
*Start : Features
**/
function pointfinder_itemdetailcf_process_fe_function( $post ) {
	$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
	if ($setup3_pointposttype_pt6_check ) {

		echo "<a class='pfitemdetailcheckall'>";
		echo esc_html__('Check All','pointfindert2d');
		echo "</a>";
		echo " / ";
		echo "<a class='pfitemdetailuncheckall'>";
		echo esc_html__('Uncheck All','pointfindert2d');
		echo "</a>";
		echo "<section class='pfsubmit-inner pfsubmit-inner-features'></section>";
		
		$lang_custom = '';

		if(function_exists('icl_object_id')) {
			$lang_custom = PF_current_language();
		}

		echo "<script>";
			echo "
			(function($) {
	  		'use strict';
				$.pf_getfeatures_now = function(itemid){

					$.ajax({
				    	beforeSend:function(){
				    		$('.pfsubmit-inner-features').pfLoadingOverlay({action:'show'});
				    	},
						url: '".get_home_url()."/wp-content/themes/pointfinder"."/admin/core/pfajaxhandler.php',
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_featuresystem',
							id: itemid,
							place: 'backend',
							postid:'".get_the_id()."',
							lang: '".$lang_custom."',
							security: '".wp_create_nonce('pfget_featuresystem')."'
						},
					})
					.done(function(obj) {
						if (obj.length == 0) {
							$('.pfsubmit-inner-features').hide();
							$('.pfsubmit-inner-features-title').hide();
						}else{
							$('.pfsubmit-inner-features').show();
							$('.pfsubmit-inner-features-title').show();
						}
						$('.pfsubmit-inner-features').html(obj);
						$('.pfsubmit-inner-features').pfLoadingOverlay({action:'hide'});
					});
				}

				$( '#pfupload_listingtypes' ).change(function(){
					$.pf_getfeatures_now($('#pfupload_listingtypes').val());
				});

				$(function(){
					$.pf_getfeatures_now($('#pfupload_listingtypes').val());
				});
										
			})(jQuery);
			";
		echo "</script>";
	}
}
/**
*End : Features
**/


/**
*Start : Conditions
**/
function pointfinder_itemdetailcf_process_co_function( $post ) {

	$condition_term = get_terms( array('pointfinderconditions'), array('fields'=>'id=>name','hide_empty'=>0) );
	
	$item_defaultvalue = 0;

	$item_defaultvalue = ($post->ID != '') ? wp_get_post_terms($post->ID, 'pointfinderconditions', array("fields" => "ids")) : '' ;
	$item_defaultvalue_out = 0;
	if (!empty($item_defaultvalue)) {
		if (isset($item_defaultvalue[0])) {
			$item_defaultvalue_out = $item_defaultvalue[0];
		}
	}

	foreach ($condition_term as $key => $value) {
		
		echo '<input type="radio" name="conditionterms" id="conditionterms" value="'.$key.'" '.checked( $item_defaultvalue_out, $key, false ).' /> '.$value.'<br/>';
		
	}
}
/**
*End : Conditions
**/



/**
*Start : Opening Hours
**/
function pointfinder_itemdetailoh_process_fe_function( $post ) {
	$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
	$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
	$setup3_modulessetup_openinghours_ex2 = PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');

	
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-slider');
	wp_register_script('theme-timepicker', get_home_url()."/wp-content/themes/pointfinder" . '/js/jquery-ui-timepicker-addon.js', array('jquery','jquery-ui-datepicker'), '4.0',true); 
	wp_enqueue_script('theme-timepicker');
	wp_enqueue_style('jquery-ui-smoothnesspf3', get_home_url()."/wp-content/themes/pointfinder" . "/css/jquery-ui.min.css", false, null);
	wp_enqueue_style('jquery-ui-smoothnesspf2', get_home_url()."/wp-content/themes/pointfinder" . "/css/jquery-ui.structure.min.css", false, null);
	wp_enqueue_style('jquery-ui-smoothnesspf', get_home_url()."/wp-content/themes/pointfinder" . "/css/jquery-ui.theme.min.css", false, null);

	
	if ($setup3_modulessetup_openinghours_ex2 == 1) {
		$text_ohours1 = esc_html__('Monday','pointfindert2d');
		$text_ohours2 = esc_html__('Sunday','pointfindert2d');
	}else{
		$text_ohours1 = esc_html__('Sunday','pointfindert2d');
		$text_ohours2 = esc_html__('Monday','pointfindert2d');
	}

	echo '<section class="pfsubmit-inner pf-openinghours-div golden-forms">';									
	
	$oh_scriptoutput = '';

	if ($setup3_modulessetup_openinghours_ex == 1) {
		echo '
		<section>
        <label class="lbl-ui">
        <input type="text" name="o1" class="input" placeholder="Monday-Friday: 09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o1',true)).'" />
        </label>
        </section>
        ';
	}elseif ($setup3_modulessetup_openinghours_ex == 0) {

		$ohours_first = '<section>
			<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o1" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o1',true)).'" />
            </label>
            </section>';

        $ohours_last = '<section>
            <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o7" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o7',true)).'"/>
            </label>
            </section>';

        if ($setup3_modulessetup_openinghours_ex2 != 1) {
			$ohours_first = $ohours_last . $ohours_first;
			$ohours_last = '';
		}

		echo $ohours_first;
		echo '
            <section>
            <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o2" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o2',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o3" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o3',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o4" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o4',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o5" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o5',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o6" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o6',true)).'"/>
            </label>
            </section>
        ';
        echo $ohours_last;

	}elseif($setup3_modulessetup_openinghours_ex == 2){
		$general_rtlsupport = PFSAIssetControl('general_rtlsupport','','0');
		if ($general_rtlsupport == 1) {
			$rtltext_oh = 'true';
		}else{
			$rtltext_oh = 'false';
		}

		for ($i=0; $i < 7; $i++) { 
			$o_value[$i] = get_post_meta($post->ID,'webbupointfinder_items_o_o'.($i+1),true);
			if (!empty($o_value[$i])) {
				$o_value[$i] = explode("-", $o_value[$i]);
				if (count($o_value[$i]) < 1) {
					$o_value[$i] = array("","");
				}elseif (count($o_value[$i]) < 2) {
					$o_value[$i][1] = "";
				}
			}else{
				$o_value[$i] = array("","");
			}

			
			$oh_scriptoutput .= "
			$.timepicker.timeRange(
				$('input[name=\"o".($i+1)."_1\"]'),
				$('input[name=\"o".($i+1)."_2\"]'),
				{
					minInterval: (1000*60*60),
					timeFormat: 'HH:mm',
					start: {},
					end: {},
					timeOnly:true,
					showSecond:null,
					showMillisec:null,
					showMicrosec:null,
					timeOnlyTitle: '".esc_html__('Choose Time','pointfindert2d')."',
					timeText: '".esc_html__('Time','pointfindert2d')."',
					hourText: '".esc_html__('Hour','pointfindert2d')."',
					currentText: '".esc_html__('Now','pointfindert2d')."',
					isRTL: ".$rtltext_oh."
				}
			);
			";
		}



		$ohours_first = '
            <section>
			<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindert2d').':</label>
				<div class="row">
					<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o1_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[0][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o1_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[0][1].'" />
		            </label>
					</div>
				</div>
	        </section>';

        $ohours_last = '<section>
	        <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindert2d').':</label>
	        <div class="row">
	        	<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o7_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[6][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o7_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[6][1].'" />
		            </label>
					</div>
				</div>
	        </section>';

        if ($setup3_modulessetup_openinghours_ex2 != 1) {
			$ohours_first = $ohours_last . $ohours_first;
			$ohours_last = '';
		}
		
		echo $ohours_first;
		echo '
			
	        <section>
	        <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindert2d').':</label>
	        <div class="row">
	        	<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o2_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[1][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o2_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[1][1].'" />
		            </label>
					</div>
				</div>
	        </section>
	        <section>
	        <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindert2d').':</label>
	        <div class="row">
	        	<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o3_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[2][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o3_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[2][1].'" />
		            </label>
					</div>
				</div>
	        </section>
	        <section>
	        <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindert2d').':</label>
	        <div class="row">
	        	<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o4_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[3][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o4_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[3][1].'" />
		            </label>
					</div>
				</div>
	        </section>
	        <section>
	        <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindert2d').':</label>
	        <div class="row">
	        	<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o5_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[4][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o5_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[4][1].'" />
		            </label>
					</div>
				</div>
	        </section>
	        <section>
	        <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindert2d').':</label>
	        <div class="row">
	        	<div class="col6 first">
	                <label class="lbl-ui">
	                <input type="text" name="o6_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[5][0].'" />
		            </label>
					</div>
					<div class="col6 last">
	                <label class="lbl-ui">
	                <input type="text" name="o6_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[5][1].'" />
		            </label>
					</div>
				</div>
	        </section>
	        
	    ';
	    echo $ohours_last;
	}

    echo '</section>';

    echo '<script>
    (function($) {
  	"use strict";$(function(){';
  	echo $oh_scriptoutput;
  	echo '});})(jQuery);</script>';
	
}
/**
*End : Opening Hours
**/

/**
*Start : Save Metadata and other inputs
**/
function pointfinder_item_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	/* Make sure that it is set.*/
	if ( ! isset( $_POST['pfupload_listingtypes'] ) ) {
		return;
	}

	$pfupload_listingtypes = sanitize_text_field($_POST['pfupload_listingtypes']);

	if (function_exists('get_current_screen')) {
		$screen = get_current_screen();
	}
	
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	if (isset($screen)) {
		if ($screen->post_type == $setup3_pointposttype_pt1) {
			
			/*Listing Type*/
				if(isset($pfupload_listingtypes)){
					if(PFControlEmptyArr($pfupload_listingtypes)){
						$pftax_terms = $pfupload_listingtypes;
					}else if(!PFControlEmptyArr($pfupload_listingtypes) && isset($pfupload_listingtypes)){
						$pftax_terms = $pfupload_listingtypes;
						if (strpos($pftax_terms, ",") != false) {
							$pftax_terms = pfstring2BasicArray($pftax_terms);
						}else{
							$pftax_terms = array($pfupload_listingtypes);
						}
					}
					wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
				}

				$conditionterms = sanitize_text_field($_POST['conditionterms']);
			
			/*Conditions*/
				if (!empty($conditionterms)) {
					wp_set_post_terms( $post_id, array($conditionterms), 'pointfinderconditions');
				}

			/*Custom fields loop*/
				$pfstart = PFCheckStatusofVar('setup1_slides');
				$setup1_slides = PFSAIssetControl('setup1_slides','','');

				if($pfstart == true){

					foreach ($setup1_slides as &$value) {

			          $available_fields = array(1,2,3,4,5,7,8,9,14,15);
			          
			          if(in_array($value['select'], $available_fields)){

			           	if (isset($_POST[''.$value['url'].''])) {
				           	
				           	if (is_array($_POST[''.$value['url'].''])) {
				           		$post_value_url = PFCleanArrayAttr('PFCleanFilters',$_POST[''.$value['url'].'']);
				           	}else{
				           		$post_value_url = sanitize_text_field($_POST[''.$value['url'].'']);
				           	}

							if(isset($post_value_url)){
								
								if ($value['select'] == 15) {
									$setup4_membersettings_dateformat = PFSAIssetControl('setup4_membersettings_dateformat','','1');
									switch ($setup4_membersettings_dateformat) {
										case '1':$datetype = "d/m/Y";break;
										case '2':$datetype = "m/d/Y";break;
										case '3':$datetype = "Y/m/d";break;
										case '4':$datetype = "Y/d/m";break;
									}

									$pfvalue = date_parse_from_format($datetype, $post_value_url);
									$post_value_url = strtotime(date("Y-m-d", mktime(0, 0, 0, $pfvalue['month'], $pfvalue['day'], $pfvalue['year'])));
								}

								if(!is_array($post_value_url)){ 
									update_post_meta($post_id, 'webbupointfinder_item_'.$value['url'], $post_value_url);	
								}else{
									if(PFcheck_postmeta_exist('webbupointfinder_item_'.$value['url'],$post_id)){
										delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
									};
									
									foreach ($post_value_url as $val) {
										add_post_meta ($post_id, 'webbupointfinder_item_'.$value['url'], $val);
									};

								};
							}else{
								delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
							};
						};

			          };
			          
			        };
				};


			/*Features*/
				$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
				if ($setup3_pointposttype_pt6_check ) {
					if (!empty($_POST['pffeature'])) {
						$feature_values = PFCleanArrayAttr('PFCleanFilters',$_POST['pffeature']);
					
						if(isset($feature_values)){				
							if(PFControlEmptyArr($feature_values)){
								$pftax_terms = $feature_values;
							}else if(!PFControlEmptyArr($feature_values) && isset($feature_values)){
								$pftax_terms = array($feature_values);
							}
							wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderfeatures');
						}else{
							wp_set_post_terms( $post_id, '', 'pointfinderfeatures');
						}
					}
				}

			/*Opening Hours*/
				$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
				$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
				if ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 2) {
					$i = 1;
					while ( $i <= 7) {
						if(isset($_POST['o'.$i.'_1']) && isset($_POST['o'.$i.'_2'])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i.'_1']).'-'.sanitize_text_field($_POST['o'.$i.'_2']));	
						}
						$i++;
					}
				}elseif ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 0) {
					$i = 1;
					while ( $i <= 7) {
						if(isset($_POST['o'.$i])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i]));	 
						}
						$i++;
					}
				}elseif ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 1) {
					$i = 1;
					while ( $i <= 1) {
						if(isset($_POST['o'.$i])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i]));	 
						}
						$i++;
					}
				}


		}
	}
}
add_action( 'save_post', 'pointfinder_item_save_meta_box_data' );
/**
*End : Save Metadata and other inputs
**/
?>