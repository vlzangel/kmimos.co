<?php
/**********************************************************************************************************************************
*
* Ajax Field System
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_fieldsystem', 'pf_ajax_fieldsystem' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_fieldsystem', 'pf_ajax_fieldsystem' );

function pf_ajax_fieldsystem(){
  
	//Security
	check_ajax_referer( 'pfget_fieldsystem', 'security');
  
	header('Content-Type: text/html; charset=UTF-8;');

	$id = $output = $post_id = $place = $scriptoutput = '';
	
	if(isset($_POST['id']) && $_POST['id']!=''){
		$id = sanitize_text_field($_POST['id']);
	}

	if(isset($_POST['postid']) && $_POST['postid']!=''){
		$post_id = sanitize_text_field($_POST['postid']);
	}
	

	if(isset($_POST['place']) && $_POST['place']!=''){
		$place = sanitize_text_field($_POST['place']);
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

	function PFgetfield($params = array())
	{
	    $defaults = array( 
	        'fieldname' => '',
	        'fieldtype' => '',
	        'fieldtitle' => '',
	        'fieldsubtype' => '',
	        'fieldparent' => '',
	        'fieldtooltip' => '',
	        'fieldoptions' => '',
	        'fielddefault' => ''
	    );

	    $params = array_merge($defaults, $params);
	    	$output = '';

	    	if(PFControlEmptyArr($params['fieldparent'])){
   				$output .= '<div class="pf_fr_inner" data-pf-parent="'.implode(',', $params['fieldparent']).'">';
   			}else{
   				$output .= '<div class="pf_fr_inner" data-pf-parent="">';
   			}
			
	   		switch ($params['fieldtype']) {
	   			/**
	   			*Text
	   			**/
		   			case 'text':
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].'</label>
			                <label class="lbl-ui">';
			            if (is_array($params['fielddefault'])) {
			            	if(isset($params['fielddefault'][0])){
			            		$checkvalue = ($params['fielddefault'][0]!='') ? ' value="'.$params['fielddefault'][0].'"' : '' ;
			            	}else{
			            		$checkvalue = '';
			            	}
			            }else{
			            	$checkvalue = ($params['fielddefault']!='') ? ' value="'.$params['fielddefault'].'"' : '' ;
			            }
			            if($params['fieldsubtype'] == 4){
			            	$output .= '<input type="text" name="'.$params['fieldname'].'"  class="input"'.$checkvalue.' onKeyPress="return numbersonly(this, event,",")" />';
				        }else{
				        	$output .= '<input type="text" name="'.$params['fieldname'].'" class="input"'.$checkvalue.' />';
				        }
				        if ($params['fieldtooltip']!='') {
				        	$output .= '<b class="tooltip left-bottom"><em>'.$params['fieldtooltip'].'</em></b>';
				        } 			        
			            $output .= '</label>';
		   			break;
	   			/**
	   			*TextArea
	   			**/
		   			case 'textarea':
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].'</label>
			                <label class="lbl-ui">';
			            if (is_array($params['fielddefault'])) {
			            	if(isset($params['fielddefault'][0])){
			            		$checkvalue = ($params['fielddefault'][0]!='') ? $params['fielddefault'][0] : '' ;
			            	}else{
			            		$checkvalue = '';
			            	}
			            }else{
			            	$checkvalue = ($params['fielddefault']!='') ? $params['fielddefault'] : '' ;
			            }
				        $output .= '<textarea id="desc" name="'.$params['fieldname'].'" class="textarea mini" >'.$checkvalue.'</textarea>';
				        
				        if ($params['fieldtooltip']!='') {
				        	$output .= '<b class="tooltip left-bottom"><em>'.$params['fieldtooltip'].'</em></b>';
				        } 			        
			            $output .= '</label>';
		   			break;
	   			/**
	   			*Select
	   			**/
		   			case 'select':
		   			$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].' '.$description.'</label>
			                <label class="lbl-ui select">';
			            
				        $output .= '<select name="'.$params['fieldname'].'">';

				        $output .= '<option value="">'.esc_html__('Please select','pointfindert2d').'</option>';

				        $ikk = 0;

				        foreach (pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {
				        	

				        	if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' selected' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' selected' : '' ;
				            }

				        	/*$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';*/

				        	if (function_exists('icl_t')) {
				            	 $exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $key.'='.$value));
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$exvalue[1].'</option>';
				            	 }else{
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            	 }
				            }else{
				            	 $output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            }

				            $ikk++;
				        }
			            $output .= '</select>';
			            $output .= '</label>';
		   			break;
	   			/**
	   			*Select Multiple
	   			**/
		   			case 'selectmulti':
		   			
		   			$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip"> ? <span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].' '.$description.'</label>
			                <label class="lbl-ui select-multiple">';
			            
				        $output .= '<select name="'.$params['fieldname'].'[]" multiple="multiple" size="6">';

				        $ikk = 0;

				        foreach (pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {
				        	
				        	if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' selected' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' selected' : '' ;
				            }

				        	/*$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';*/

				        	if (function_exists('icl_t')) {
				            	 $exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $key.'='.$value));
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$exvalue[1].'</option>';
				            	 }else{
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            	 }
				            }else{
				            	 $output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            }

				            $ikk++;
				        }
			            $output .= '</select>';
			            $output .= '</label>';
		   			break;
	   			/**
	   			*Radio
	   			**/
		   			case 'radio':
		   			$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
		   				$output .= '<label class="lbl-text ext">'.$params['fieldtitle'].' '.$description.'</label>';
		   				$output .= '<div class="option-group">';

		   				$ikk = 0;

		   				foreach (pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {
		   					$output .= '<span class="goption">';
				   			$output .= '<label class="options">';
				   			if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' checked' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' checked' : '' ;
				            }
					        $output .= '<input type="radio" name="'.$params['fieldname'].'" value="'.$key.'"'.$checkvalue.' />';
					        $output .= '<span class="radio"></span>';
				            $output .= '</label>';
				            if (function_exists('icl_t')) {
				            	 $exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $value));
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$exvalue[1].'</label>';
				            	 }else{
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            	 }
				            }else{
				            	 $output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            }
				            $output .= '</span>';

				            $ikk++;
						}

			            $output .= '</div>';
		   			break;
	   			/**
	   			*Checkbox
	   			**/
		   			case 'checkbox':
		   				$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
		   				$output .= '<label class="lbl-text ext">'.$params['fieldtitle'].' '.$description.'</label>';
		   				$output .= '<div class="option-group">';

		   				$ikk = 0;

		   				foreach (pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {
		   					$output .= '<span class="goption">';
				   			$output .= '<label class="options">';
				   			if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' checked' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' checked' : '' ;
				            }
					        $output .= '<input type="checkbox" name="'.$params['fieldname'].'[]" value="'.$key.'"'.$checkvalue.' />';
					        $output .= '<span class="checkbox"></span>';
				            $output .= '</label>';
				            if (function_exists('icl_t')) {
				            	 $exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $value));
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$exvalue[1].'</label>';
				            	 }else{
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            	 }
				            }else{
				            	 $output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            }
				            $output .= '</span>';
				            $ikk++;
						}

			            $output .= '</div>';
		   			break;
		   		/**
	   			*Date
	   			**/
		   			case 'date':


			            $setup4_membersettings_dateformat = PFSAIssetControl('setup4_membersettings_dateformat','','1');
						$setup3_modulessetup_openinghours_ex2 = PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');
						$general_rtlsupport = PFSAIssetControl('general_rtlsupport','','0');
						
						$date_field_rtl = (empty($general_rtlsupport))? 'false':'true';
						$date_field_ys = 'true';

						switch ($setup4_membersettings_dateformat) {
							case '1':$date_field_format = 'dd/mm/yy';$date_field_format0 = 'd/m/Y';break;
							case '2':$date_field_format = 'mm/dd/yy';$date_field_format0 = 'm/d/Y';break;
							case '3':$date_field_format = 'yy/mm/dd';$date_field_format0 = 'Y/m/d';break;
							case '4':$date_field_format = 'yy/dd/mm';$date_field_format0 = 'Y/d/m';break;
							default:$date_field_format = 'dd/mm/yy';$date_field_format0 = 'd/m/Y';break;
						}	

			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].'</label>
			                <label class="lbl-ui">';
			           			            
			            if (is_array($params['fielddefault'])) {
			            	if(isset($params['fielddefault'][0])){
			            		$checkvalue = ($params['fielddefault'][0]!='') ? ' value="'.date($date_field_format0,$params['fielddefault'][0]).'"' : '' ;
			            	}else{
			            		$checkvalue = '';
			            	}
			            }else{
			            	$checkvalue = ($params['fielddefault']!='') ? ' value="'.date($date_field_format0,$params['fielddefault']).'"' : '' ;
			            }

			            if($params['fieldsubtype'] == 4){
			            	$output .= '<input type="text" id="'.$params['fieldname'].'" name="'.$params['fieldname'].'"  class="input"'.$checkvalue.' />';
				        }else{
				        	$output .= '<input type="text" id="'.$params['fieldname'].'" name="'.$params['fieldname'].'" class="input"'.$checkvalue.' />';
				        }
				        if ($params['fieldtooltip']!='') {
				        	$output .= '<b class="tooltip left-bottom"><em>'.$params['fieldtooltip'].'</em></b>';
				        } 			        
			            $output .= '</label>';

			            $yearrange1 = PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_yearrange1','','2000');
						$yearrange2 = PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_yearrange2','',date("Y"));

						if (!empty($yearrange1) && !empty($yearrange2)) {
							$yearrangesetting = 'yearRange:"'.$yearrange1.':'.$yearrange2.'",';
						}elseif (!empty($yearrange1) && empty($yearrange2)) {
							$yearrangesetting = 'yearRange:"'.$yearrange1.':'.date("Y").'",';
						}else{
							$yearrangesetting = '';
						}
							

			            $output .= "
						<script>
						(function($) {
							'use strict';
							$(function(){
								$( '#".$params['fieldname']."' ).datepicker({
							      changeMonth: $date_field_ys,
							      changeYear: $date_field_ys,
							      isRTL: $date_field_rtl,
							      dateFormat: '$date_field_format',
							      $yearrangesetting
							      firstDay: $setup3_modulessetup_openinghours_ex2,/* 0 Sunday 1 monday*/
							      
							    });
							});
						})(jQuery);
						</script>
			            ";
		   			break;

	   		}

	   		$output .= '</div>';



        return $output;

	}
	
	function PFValidationCheckWriteEx($field_validation_check,$field_validation_text,$itemid){
				
		$itemname = (string)trim($itemid);
		$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

		if($field_validation_check == 1){
			return '$("[name=\''.$itemname.'\']").rules( "add", {
			  required: true,
			  messages: {
			    required: "'.$field_validation_text.'",
			  }
			});';
		}
	}

	/** 
	*Start : Field foreach
	**/
		$setup1_slides = PFSAIssetControl('setup1_slides','','');
		$fields_output_arr = array();
		$pfstart = PFCheckStatusofVar('setup1_slides');

		if(is_array($setup1_slides) && $pfstart == true){

			foreach ($setup1_slides as &$value) {

	          $customfield_statuscheck = PFCFIssetControl('setupcustomfields_'.$value['url'].'_frontupload','','0');
	          if ($place == 'backend') {
	          	$customfield_statuscheck = 1;
	          }
	          $available_fields = array(1,2,3,4,5,7,8,9,14,15);
	        
	          if(in_array($value['select'], $available_fields) && $customfield_statuscheck != 0){


				$fieldtitle = (PFCFIssetControl('setupcustomfields_'.$value['url'].'_frontendname','','') == '') ? $value['title'] : PFCFIssetControl('setupcustomfields_'.$value['url'].'_frontendname','','') ;
				$fieldarr = array(
					'fieldname' => $value['url'],
					'fieldtitle' => $fieldtitle
				);

				/***
				Check parent item 
				***/
				$ParentItem = PFCFIssetControl('setupcustomfields_'.$value['url'].'_parent','','');
				if(PFControlEmptyArr($ParentItem)){$fieldarr['fieldparent'] = $ParentItem;}
				/***
				End :
				***/

				$status = 'not';
		
				if (!empty($id)) {
					if (isset($fieldarr['fieldparent'])) {
					if (is_array($fieldarr['fieldparent'])) {
						if (in_array($id, $fieldarr['fieldparent'])) {
							$status = 'ok';
						}
					}
					}
				}else{
					if (empty($fieldarr['fieldparent'])) {
						$status = 'ok';
					}
				}

				if ($status == 'ok' || empty($fieldarr['fieldparent'])) {
				
					switch ($value['select']) {
						case '1':/*Text*/
						case '2':/*URL*/
						case '3':/*Email*/
						case '4':/*Number*/
							$fieldarr['fieldtype'] = 'text';
							$fieldarr['fieldsubtype'] = $value['select'];
							break;
						case '5':
							$fieldarr['fieldtype'] = 'textarea';
							break;
						case '7':
							$fieldarr['fieldtype'] = 'radio';
							$fieldarr['fieldoptions'] = PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
							break;
						case '9':
							$fieldarr['fieldtype'] = 'checkbox';
							$fieldarr['fieldoptions'] = PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
							break;
						case '8':
							$fieldarr['fieldtype'] = 'select';
							$fieldarr['fieldoptions'] = PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
							break;
						case '14':
							$fieldarr['fieldtype'] = 'selectmulti';
							$fieldarr['fieldoptions'] = PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
							break;
						case '15':
							$fieldarr['fieldtype'] = 'date';
					}


					/*** 
					Field Options from Admin Panel
					***/
					$field_description = PFCFIssetControl('setupcustomfields_'.$value['url'].'_descriptionfront','','');
					$field_validation_check = PFCFIssetControl('setupcustomfields_'.$value['url'].'_validation_required','','0');
					$fieldarr['fieldtooltip'] = $field_description;
					/***
					End :
					***/



					/***
					*Validation check and add rules. 
					***/

					if($place != 'backend'){
						if($fieldarr['fieldtype'] == 'selectmulti' || $fieldarr['fieldtype'] == 'checkbox'){
							$field_validation_text = PFCFIssetControl('setupcustomfields_'.$value['url'].'_message','','');
							$scriptoutput .= PFValidationCheckWriteEx($field_validation_check,$field_validation_text,$value['url'].'[]');
						}else{
							$field_validation_text = PFCFIssetControl('setupcustomfields_'.$value['url'].'_message','','');
							$scriptoutput .= PFValidationCheckWriteEx($field_validation_check,$field_validation_text,$value['url']);
						}
					}
					/***
					*End :
					***/

					$fullwidth = PFCFIssetControl('setupcustomfields_'.$value['url'].'_fwr','','0');
					if ($fullwidth == 1) {
						$fwtext = ' pf-cf-inner-fullwidth';
					}elseif ($fullwidth == 2) {
						$fwtext = ' pf-cf-inner-halfwidth';
					}
					else{$fwtext = '';}
					$output .= '<section class="pf-cf-inner-elements'.$fwtext.'">';
					
					/** Default Value **/
					$fieldarr['fielddefault'] = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_'.$value['url'],false) : PFCFIssetControl('setupcustomfields_'.$value['url'].'_defaultvalue','','') ;


					$output .= PFgetfield($fieldarr);

					$output .= '</section>';
				}

	          }
	          
	        }

		}
	/** 
	*End : Field foreach
	**/
	echo $output;
	if($place != 'backend' && !empty($output)){
		echo "<script>";
		echo "(function($) {'use strict'; $(function(){";
		echo $scriptoutput;
		echo "});})(jQuery);";
		echo "</script>";
	}

	die();
}

?>