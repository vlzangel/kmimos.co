<?php

/**********************************************************************************************************************************
*
* Taxonomy meta boxes for Pointfinder
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

global $pf_extra_taxonomyfields;
$pf_extra_taxonomyfields = array();

/*For locations*/
	$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
	if ($setup3_pointposttype_pt5_check == 1) {
		$pf_extra_taxonomyfields[] = array(
			'title' => esc_html__('Coordinates for This Location','pointfindert2d'),			
			'taxonomies' => array('pointfinderlocations'),			
			'id' => 'pointfinderlocations_vars',					
			
			'fields' => array(							
				array(
					'name' => esc_html__('Lat Coordinate','pointfindert2d'),
					'desc' => sprintf(esc_html__('This coordinate for lat point. %sPlease click here for find your coordinates','pointfindert2d'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
					'id' => 'pf_lat_of_location',
					'type' => 'text'						
				),
				
				
				array(
					'name' => esc_html__('Lng Coordinate','pointfindert2d'),
					'desc' => sprintf(esc_html__('This coordinate for lat point. %sPlease click here for find your coordinates','pointfindert2d'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
					'id' => 'pf_lng_of_location',
					'type' => 'text'						
				),
				
			)
		);
	}


/*For listing Types*/
	$pf_extra_taxonomyfields[] = array(
		'title' => esc_html__('Category Additional Settings','pointfindert2d'),			
		'taxonomies' => array('pointfinderltypes'),			
		'id' => 'pointfinderltypesas_vars',
		'parentonly' => true,				
		'fields' => array(	
			array(
				'name' => esc_html__('Header Type','pointfindert2d'),
				'desc' => sprintf(esc_html__("If this option enabled, Pointfinder will change default header to your selection.",'pointfindert2d'),'<br/>'),
				'id'   => 'pf_cat_imagebg',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__("Image Background",'pointfindert2d'),
					'2' => esc_html__("Standart Header",'pointfindert2d'),
					'3' => esc_html__("No Header",'pointfindert2d')
					),
				'std'  => 2,
			),
			array(
				'name' => esc_html__("Header Height",'pointfindert2d'),
				'desc' => esc_html__("Only numeric! Ex: 100",'pointfindert2d'),
				'id'   => 'pf_cat_headerheight',
				'type' => 'text',
				'std'  => 140,
			),
			array(
				'name' => esc_html__("Category Text Color",'pointfindert2d'),
				'id'   => 'pf_cat_textcolor',
				'type' => 'color',
			),
			array(
				'name' => esc_html__("Category Background Color",'pointfindert2d'),
				'id'   => 'pf_cat_backcolor',
				'type' => 'color',
			),
			array(
				'name' => esc_html__("Background Image",'pointfindert2d'),
				'id'   => 'pf_cat_bgimg',
				'type' => 'image',
			),
			array(
				'name'    => esc_html__("Background Repeat",'pointfindert2d'),
				'id'      => 'pf_cat_bgrepeat',
				'type'    => 'select',
				'options' => array(
					'repeat' => esc_html__("Repeat",'pointfindert2d'),
					'no-repeat' => esc_html__("No Repeat",'pointfindert2d')
				),
			),
			array(
				'name'    => esc_html__("Background Size",'pointfindert2d'),
				'id'      => 'pf_cat_bgsize',
				'type'    => 'select',
				'options' => array(
					'cover' => esc_html__("Cover",'pointfindert2d'),
					'contain' => esc_html__("Contain",'pointfindert2d'),
					'inherit' => esc_html__("Inherit",'pointfindert2d')
				),
			),
			array(
				'name'    => esc_html__("Background Position",'pointfindert2d'),
				'id'      => 'pf_cat_bgpos',
				'type'    => 'select',
				'options' => array(
					'left top' => esc_html__("Left Top",'pointfindert2d'),
					'left center' => esc_html__("Left center",'pointfindert2d'),
					'left bottom' => esc_html__("Left Bottom",'pointfindert2d'),
					'center top' => esc_html__("Center Top",'pointfindert2d'),
					'center center' => esc_html__("Center Center",'pointfindert2d'),
					'center bottom' => esc_html__("Center Bottom",'pointfindert2d'),
					'right top' => esc_html__("Right Top",'pointfindert2d'),
					'right center' => esc_html__("Right center",'pointfindert2d'),
					'right bottom' => esc_html__("Right Bottom",'pointfindert2d')
				),
			),
		)
	);

	$pf_extra_taxonomyfields[] = array(
		'title' => esc_html__('Visual Composer : Directory List Specifications','pointfindert2d'),			
		'taxonomies' => array('pointfinderltypes'),			
		'id' => 'pointfinderltypes_vars',
		'parentonly' => true,				
		'fields' => array(	
			array(
				'name' => esc_html__('Icon Image','pointfindert2d'),
				'id'   => 'pf_icon_of_listing',
				'type' => 'image',
			),
			array(
				'name' => esc_html__('Icon Width','pointfindert2d'),
				'desc' => esc_html__('Please write only number.','pointfindert2d'),
				'id'   => 'pf_iconwidth_of_listing',
				'type' => 'text',
				'std'  => 20,
			),
			array(
				'name' => esc_html__('Category Background Color','pointfindert2d'),
				'id'   => 'pf_catbg_of_listing',
				'type' => 'color',
			),
			array(
				'name' => esc_html__('Category Text Color','pointfindert2d'),
				'id'   => 'pf_cattext_of_listing',
				'type' => 'color',
			),
			array(
				'name' => esc_html__('Category Text Hover Color','pointfindert2d'),
				'id'   => 'pf_cattext2_of_listing',
				'type' => 'color',
			)
		)
	);

	$pf_extra_taxonomyfields[] = array(
		'title' => esc_html__('Frontend Form','pointfindert2d'),			
		'taxonomies' => array('pointfinderltypes'),			
		'id' => 'pointfinderltypes_fevars',
		'parentonly' => true,			
		'fields' => array(	
			array(
				'name' => esc_html__('Address Area','pointfindert2d'),
				'desc' => esc_html__('Hide Address Area from frontend form.','pointfindert2d'),
				'id'   => 'pf_address_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 1,
			),
			array(
				'name' => esc_html__('Location Area','pointfindert2d'),
				'desc' => esc_html__('Hide Location Area from frontend form.','pointfindert2d'),
				'id'   => 'pf_location_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 1,
			),
			array(
				'name' => esc_html__('Item Type Area','pointfindert2d'),
				'desc' => esc_html__('Hide Item Type Area from frontend form.','pointfindert2d'),
				'id'   => 'pf_itype_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 1,
			),
			array(
				'name' => esc_html__('Conditions Area','pointfindert2d'),
				'desc' => esc_html__('Hide Conditions Area from frontend form.','pointfindert2d'),
				'id'   => 'pf_condition_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 1,
			),
			array(
				'name' => esc_html__('Image Upload Area','pointfindert2d'),
				'desc' => esc_html__('Hide Image Upload Area from frontend form.','pointfindert2d'),
				'id'   => 'pf_image_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 1,
			),
			array(
				'name' => esc_html__('File Upload Area','pointfindert2d'),
				'desc' => esc_html__('Hide File Upload Area from frontend form.','pointfindert2d'),
				'id'   => 'pf_file_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 1,
			),
			array(
				'name' => esc_html__('Custom Tab 1 Area','pointfindert2d'),
				'desc' => esc_html__('Show Custom Tab Area at frontend form.','pointfindert2d'),
				'id'   => 'pf_customtab1_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 2,
			),
			array(
				'name' => esc_html__('Custom Tab 2 Area','pointfindert2d'),
				'desc' => esc_html__('Show Custom Tab Area at frontend form.','pointfindert2d'),
				'id'   => 'pf_customtab2_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 2,
			),
			array(
				'name' => esc_html__('Custom Tab 3 Area','pointfindert2d'),
				'desc' => esc_html__('Show Custom Tab Area at frontend form.','pointfindert2d'),
				'id'   => 'pf_customtab3_area',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Show','pointfindert2d'),
					'2' => esc_html__('Hide','pointfindert2d')
					),
				'std'  => 2,
			)
		)
	);

	$pf_extra_taxonomyfields[] = array(
		'title' => esc_html__('Category Options','pointfindert2d'),			
		'taxonomies' => array('pointfinderltypes'),			
		'id' => 'pointfinderltypes_covars',	
		'parentonly' => true,					
		'fields' => array(	
			array(
				'name' => esc_html__('Price','pointfindert2d'),
				'desc' => sprintf(esc_html__('This value using for category pricing feature. %s You can add only %s numeric %s values inside of this box. (Only for Pay per post system)','pointfindert2d'),'<br/>','<strong>','</strong>'),
				'id'   => 'pf_categoryprice',
				'type' => 'text',
				'std'  => 0,
			),
			array(
				'name' => esc_html__('Multiple Sub Category Select','pointfindert2d'),
				'desc' => sprintf(esc_html__('If this option enabled, then user can select more than one sub listing type. %s Warning: If this feature enabled, you can not use third level for this sub listing type.','pointfindert2d'),'<br/>'),
				'id'   => 'pf_multipleselect',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Enable','pointfindert2d'),
					'2' => esc_html__('Disable','pointfindert2d')
					),
				'std'  => 2,
			),
			array(
				'name' => esc_html__('Sub Category Fields','pointfindert2d'),
				'desc' => sprintf(esc_html__('If this option enabled, you do not need to define custom fields for sub categories of this listing type . %s Warning: If this feature enabled, you can not use third level for this sub listing type.','pointfindert2d'),'<br/>'),
				'id'   => 'pf_subcatselect',
				'type' => 'radio',
				'options' => array(
					'1' => esc_html__('Enable','pointfindert2d'),
					'2' => esc_html__('Disable','pointfindert2d')
					),
				'std'  => 2,
			)
		)
	);


/* For Conditions */

	$pf_extra_taxonomyfields[] = array(
		'title' => esc_html__('Settings','pointfindert2d'),			
		'taxonomies' => array('pointfinderconditions'),			
		'id' => 'pointfindercondition_vars',
		'parentonly' => true,				
		'fields' => array(	
			array(
				'name' => esc_html__('Background Color','pointfindert2d'),
				'id'   => 'pf_condition_bg',
				'type' => 'color',
			),
			array(
				'name' => esc_html__('Text Color','pointfindert2d'),
				'id'   => 'pf_condition_text',
				'type' => 'color',
			)
		)
	);
	




/**
 * Register meta boxes
 *
 * @return void
 */
function pointfinder2_TAX_register_taxonomy_meta_boxes()
{
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Taxonomy_Meta' ) )
		return;

	global $pf_extra_taxonomyfields;
	foreach ( $pf_extra_taxonomyfields as $pf_extra_taxonomyfield )
	{
		new RW_Taxonomy_Meta( $pf_extra_taxonomyfield );
	}
}

// Hook to 'admin_init' to make sure the class is loaded before
// (in case using the class in another plugin)
add_action( 'admin_init', 'pointfinder2_TAX_register_taxonomy_meta_boxes' );


/********************* END DEFINITION OF META SECTIONS FOR LOCATION ***********************/

?>
