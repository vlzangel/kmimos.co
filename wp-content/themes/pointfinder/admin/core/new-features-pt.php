<?php

/**********************************************************************************************************************************
*
* Add Listing Type Cat. Select to Features Taxonomy
* 
* Author: Webbu Design
* Please do not modify below functions.
***********************************************************************************************************************************/

$setup3_pointposttype_pt6p = 'pointfinderfeatures';


add_action( $setup3_pointposttype_pt6p.'_add_form_fields', 'pointfinder_category_form_custom_field_add', 10 );
add_action( $setup3_pointposttype_pt6p.'_edit_form_fields', 'pointfinder_category_form_custom_field_edit', 10, 2 );

function pointfinder_category_form_custom_field_add( $taxonomy ) {

    if ($taxonomy == 'pointfinderfeatures') {
        $setup4_submitpage_listingtypes_title = PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
        $setup4_submitpage_listingtypes_verror = PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');

        echo '<div class="form-field">';
        echo '<section>';  
        $fields_output_arr = array(
            'listname' => 'pfupload_listingtypes',
            'listtype' => 'listingtypes',
            'listtitle' => $setup4_submitpage_listingtypes_title,
            'listsubtype' => 'pointfinderltypes',
            'listdefault' => '',
            'listmultiple' => 1
        );
        echo PFGetList_forAdmin($fields_output_arr);
        echo '</section>';


        echo '
        <script>
        jQuery(function(){
            jQuery("#pfupload_listingtypes").select2({
                placeholder: "'.esc_html__("Please select","pointfindert2d").'", 
                formatNoMatches:"'.esc_html__("Nothing found.","pointfindert2d").'",
                allowClear: true, 
                minimumResultsForSearch: 10
            });
        });
        </script>
        </div>
        ';
    }
}

function pointfinder_category_form_custom_field_edit( $tag, $taxonomy ) {
    if ($taxonomy == 'pointfinderfeatures') {
        $option_name = 'pointfinder_features_customlisttype_' . $tag->term_id;
        $selected_value = get_option( $option_name );


        $setup4_submitpage_listingtypes_title = PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
        $setup4_submitpage_listingtypes_validation = 1;
        $setup4_submitpage_listingtypes_verror = PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');
        
        echo '<tr class="form-field"><th scope="row" valign="top"></th><td>';
        echo '<section>';  
        $fields_output_arr = array(
            'listname' => 'pfupload_listingtypes',
            'listtype' => 'listingtypes',
            'listtitle' => $setup4_submitpage_listingtypes_title,
            'listsubtype' => 'pointfinderltypes',
            'listdefault' => $selected_value,
            'listmultiple' => 1
        );
        echo PFGetList_forAdmin($fields_output_arr);
        echo '</section>';


        echo '
        <script>
        jQuery(function(){
            jQuery("#pfupload_listingtypes").select2({
                placeholder: "'.esc_html__("Please select","pointfindert2d").'", 
                formatNoMatches:"'.esc_html__("Nothing found.","pointfindert2d").'",
                allowClear: true, 
                minimumResultsForSearch: 10
            });
        });
        </script>
        </td>
        </tr>
        ';
    }
}

/** Save Custom Field Of Category Form */
add_action( 'created_'.$setup3_pointposttype_pt6p, 'pointfinder_category_form_custom_field_save', 10, 2 ); 
add_action( 'edited_'.$setup3_pointposttype_pt6p, 'pointfinder_category_form_custom_field_save', 10, 2 );

function pointfinder_category_form_custom_field_save( $term_id, $tt_id ) {

    if ( isset( $_POST['pfupload_listingtypes'] ) ) {           
        $option_name = 'pointfinder_features_customlisttype_' . $term_id;
        update_option( $option_name, $_POST['pfupload_listingtypes'] );
    }
}
?>