<?php
/**********************************************************************************************************************************
*
* Custom Detail Fields Retrieve Value Class
* This class prepared for help to create auto config file.
* Author: Webbu Design
*
***********************************************************************************************************************************/
if ( ! class_exists( 'PF_CF_Val' ) ){
	class PF_CF_Val{
		
		public $FieldOutput;
		
		public function __construct(){}

		function ShortNameCheck($title,$slug){
			
			$ShortName = PFCFIssetControl('setupcustomfields_'.$slug.'_shortname','','');
			
			if($ShortName != ''){
				
				$output = $ShortName;
				
			}else{
				$output = $title;	
			}
			
			return $output;
		
		}
		
		function PriceValueCheck($slug,$FieldValue,$FieldTitle,$pfsys=NULL){
			
			if(PFCFIssetControl('setupcustomfields_'.$slug.'_currency_check','','0') == 1){

				$CFPrefix = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_prefix','','');
				$CFSuffix = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_suffix','','');
				$CFDecima = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decima','','0');
				$CFDecimp = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decimp','','.');
				$CFDecimt = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decimt','',',');
				
				if (!empty($CFSuffix)) {
					$CFSuffix = '<span class="pf-price-suffix">'.$CFSuffix.'</span>';
				}else{
					$CFSuffix = '';
				}
				/*Check field value empty? if yes write 0*/
				if($FieldValue == ''){ $FieldValue = 0;};
				
				$FieldValue = str_replace(',', $CFDecimp, $FieldValue);
				
				if($pfsys == NULL){
					return '<li class="pf-price">'.$CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) . $CFSuffix.'</li>';
				}elseif($pfsys == 1){
					return '<div class="pflistingitem-subelement pf-price">'.$CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) . $CFSuffix.'</div>';
				}elseif($pfsys == 2){
					return ''.$FieldTitle.'<span class="pfdetail-ftext pf-pricetext">'.$CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) . $CFSuffix.'</span></div>';
				}
			
			}
		
		}
		
		
		function SizeValueCheck($slug,$FieldValue,$FieldTitle,$pfsys=NULL){
			
			if(PFCFIssetControl('setupcustomfields_'.$slug.'_size_check','','0') == 1){

				$CFPrefix = PFCFIssetControl('setupcustomfields_'.$slug.'_size_prefix','','');
				$CFSuffix = PFCFIssetControl('setupcustomfields_'.$slug.'_size_suffix','','');
				$CFDecima = 0;
				$CFDecimp = PFCFIssetControl('setupcustomfields_'.$slug.'_size_decimp','','.');
				$CFDecimt = '.';
				
				//Check field value empty? if yes write 0
				if($FieldValue == ''){ $FieldValue = 0;};
				
				if($pfsys == NULL){
					return '<li>'.$FieldTitle . $CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) .''. $CFSuffix.'<span class="pf-fieldspace"></span></li>';
				}elseif($pfsys == 1){
					return ''.$FieldTitle .'<span class="pf-ftext">'. $CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) .''. $CFSuffix.'</span></div>';
				}elseif($pfsys == 2){
					return ''.$FieldTitle .'<span class="pfdetail-ftext">'. $CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) .''. $CFSuffix.'</span></div>';
				}
			
			}
		
		}
		
		function GetItemSelectedValue($slug,$FieldValue,$pfsys=NULL){
			

				$CFPrefix = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_prefix','','');
				$CFSuffix = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_suffix','','');
				$CFDecima = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decima','','0');
				$CFDecimp = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decimp','','.');
				$CFDecimt = PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decimt','',',');
				
				//Check field value empty? if yes write 0
				if($FieldValue == ''){ $FieldValue = 0;};
				
					return '<li class="pf-price clearfix">'.$CFPrefix .''. number_format($FieldValue, $CFDecima, $CFDecimp, $CFDecimt) .'<span class="pf-price-suffix">'. $CFSuffix.'</span></li>';
		
		}
		
		
		
		function GetValue($slug,$post_id,$ftype,$title,$pfsys=NULL){
					global $pfcustomfields_options;
					
					$this->FieldOutput = '';
					
					/*Get postid listing type first*/
					$FieldListingTax = wp_get_post_terms( $post_id, 'pointfinderltypes');
					
					$HideTitleValue = $pfcustomfields_options['setupcustomfields_'.$slug.'_sinfowindow_hidename'];
					
					if($HideTitleValue == 1){
						if($pfsys == NULL){
							$FieldTitle = '<span class="wpfdetailtitle">'.$this->ShortNameCheck($title,$slug).':</span> ';
						}elseif($pfsys == 1){
							$FieldTitle = '<div class="pflistingitem-subelement pf-onlyitem"><span class="pf-ftitle">'.$this->ShortNameCheck($title,$slug).': </span>';
						}elseif($pfsys == 2){
							$FieldTitle = '<div class="pfdetailitem-subelement pf-onlyitem clearfix"><span class="pf-ftitle">'.$this->ShortNameCheck($title,$slug).' : </span>';
						}
					}else{
						if($pfsys == NULL){
							$FieldTitle = '';
						}elseif($pfsys == 1){
							$FieldTitle = '<div class="pflistingitem-subelement pf-onlyitem"><span class="pf-ftitle"></span>';
						}elseif($pfsys == 2){
							$FieldTitle = '<div class="pfdetailitem-subelement pf-onlyitem clearfix"><span class="pf-ftitle"></span>';
						}
					}
						
					
						/*If not have a parent field*/
						$SourceFieldValue = rwmb_meta( 'webbupointfinder_item_'.$slug, '', $post_id);

						/* Select box get value */
						if($ftype == 8 || $ftype == 7 ){
							
							$SourceFieldArray = pfstring2KeyedArray($pfcustomfields_options['setupcustomfields_'.$slug.'_rvalues']);							
							$SourceFieldValue = (isset($SourceFieldArray[$SourceFieldValue])) ? $SourceFieldArray[$SourceFieldValue] : '' ;
						
						}elseif($ftype == 14 || $ftype == 9){
							
							$SourceFieldValue = get_post_meta( $post_id, 'webbupointfinder_item_'.$slug, false );
							$SourceFieldArray = pfstring2KeyedArray($pfcustomfields_options['setupcustomfields_'.$slug.'_rvalues']);
						
							
							if(count($SourceFieldValue) > 1){

								$SourceFieldValueOut = array();
								foreach($SourceFieldValue as $SourceFieldValueSingle){
									array_push($SourceFieldValueOut,$SourceFieldArray[$SourceFieldValueSingle]);
								}
								
								$SourceFieldValue = implode(", ", $SourceFieldValueOut);
								
							}else{
								$SourceFieldValue = get_post_meta( $post_id, 'webbupointfinder_item_'.$slug, true );
								$SourceFieldValue = (isset($SourceFieldArray[$SourceFieldValue])) ? $SourceFieldArray[$SourceFieldValue] : '' ;
							}

						}elseif ($ftype == 15) {
							$setup4_membersettings_dateformat = PFSAIssetControl('setup4_membersettings_dateformat','','1');

							switch ($setup4_membersettings_dateformat) {
								case '1':$date_field_format = 'dd/mm/yy';$date_field_format0 = 'd/m/Y';break;
								case '2':$date_field_format = 'mm/dd/yy';$date_field_format0 = 'm/d/Y';break;
								case '3':$date_field_format = 'yy/mm/dd';$date_field_format0 = 'Y/m/d';break;
								case '4':$date_field_format = 'yy/dd/mm';$date_field_format0 = 'Y/d/m';break;
								default:$date_field_format = 'dd/mm/yy';$date_field_format0 = 'd/m/Y';break;
							}	
							$SourceFieldValue = get_post_meta( $post_id, 'webbupointfinder_item_'.$slug, true );
							if (!empty($SourceFieldValue)) {
								$SourceFieldValue = date($date_field_format0,$SourceFieldValue);
							}
							

						}
					
						/*Check if element price*/
						$FieldValue = $this->PriceValueCheck($slug,$SourceFieldValue,$FieldTitle,$pfsys);
						/*Check if element size*/
						$FieldValue .= $this->SizeValueCheck($slug,$SourceFieldValue,$FieldTitle,$pfsys);
						
						$ParentItem = PFCFIssetControl('setupcustomfields_'.$slug.'_parent','','');
						

						/*Get link option*/
						$linkoption = (isset($pfcustomfields_options['setupcustomfields_'.$slug.'_linkoption']))? $pfcustomfields_options['setupcustomfields_'.$slug.'_linkoption']: 0;
						switch ($linkoption) {
							case 1:
								$link_addon = 'http://';$link_addon2 = 'https://';$link_target = "target='_blank'";
								break;
							case 2:
								$link_addon = 'mailto:';$link_target = "";
								break;
							case 3:
								$link_addon = 'tel:';$link_target = "";
								break;
							default:
								$link_addon = 'http://';$link_target = "target='_blank'";
								break;
						}

						/*Check http and https*/
						if ($linkoption == 1) {
							$pf_httpcheck = strpos($SourceFieldValue, 'http://');
							$pf_httpscheck = strpos($SourceFieldValue, 'https://');

							$pfweblink_field = $SourceFieldValue;

							if ($pf_httpcheck === false) {
								if ($pf_httpscheck !== false && $pf_httpcheck === false) {
									$pfweblink_field = $SourceFieldValue;
								}elseif ($pf_httpscheck === false && $pf_httpcheck !== false) {
									$pfweblink_field = $SourceFieldValue;
								}elseif ($pf_httpscheck === false && $pf_httpcheck === false) {
									$pfweblink_field = $link_addon.$SourceFieldValue;
								}
							}
						}else{
							$pfweblink_field = $link_addon. $SourceFieldValue;
						}

						/*If it have a parent element*/
						if(PFControlEmptyArr($ParentItem)){
							
							/*If that parent field = selected taxonomy show*/
							if(PFControlEmptyArr($FieldListingTax)){
								

								if(function_exists('icl_object_id')) {
									foreach ($ParentItem as $key => $value) {
										$ParentItem[$key] = icl_object_id($value,'pointfinderltypes',true,PF_current_language());
									}
								}
								
								/* Check if this field not need sub cat. */

								$term_choosen_id = $FieldListingTax['0']->term_id;

								if (isset($FieldListingTax['0']->term_id)) {
									$pointfinderltypes_covars = get_option('pointfinderltypes_covars');
									$top_term_id = pf_get_term_top_most_parent($FieldListingTax['0']->term_id,'pointfinderltypes');
									$top_term_idp = $top_term_id['parent'];
									
									if (isset($pointfinderltypes_covars[$top_term_idp]['pf_subcatselect'])) {
										if ($pointfinderltypes_covars[$top_term_idp]['pf_subcatselect'] == 1) {
											$term_choosen_id = $top_term_idp;
										}
									}
								
								}
								

								if(in_array($term_choosen_id, $ParentItem) ){					
									
									if($FieldValue == ''){

										if($pfsys == NULL){
											if ($linkoption == 0) {
												$FieldValue = '<li>'.$FieldTitle . $SourceFieldValue.'<span class="pf-fieldspace"></span></li>';
											}else{
												$FieldValue = '<li>'.$FieldTitle .'<a href="'.$pfweblink_field.'" '.$link_target.'>'. $SourceFieldValue.'</a><span class="pf-fieldspace"></span></li>';
											}
										}elseif($pfsys == 1){
											if ($linkoption == 0) {
												$FieldValue = ''.$FieldTitle .'<span class="pf-ftext">'. $SourceFieldValue.'</span></div> ';
											}else{
												$FieldValue = ''.$FieldTitle .'<span class="pf-ftext"><a href="'.$pfweblink_field.'" '.$link_target.'>'. $SourceFieldValue.'</a></span></div> ';
											}
										}elseif($pfsys == 2){
											if ($linkoption == 0) {
												$FieldValue = ''.$FieldTitle .'<span class="pfdetail-ftext">'. $SourceFieldValue.'</span></div> ';
											}else{
												$FieldValue = ''.$FieldTitle .'<span class="pfdetail-ftext"><a href="'.$pfweblink_field.'" '.$link_target.'>'. $SourceFieldValue.'</a></span></div> ';
											}
										}
									}
									
									$this->FieldOutput = $FieldValue;
							
								}
							}
							
						}else{
							
							
							if($FieldValue == ''){
								
								if($pfsys == NULL){
									if ($linkoption == 0) {
										$FieldValue = '<li>'.$FieldTitle . $SourceFieldValue.'<span class="pf-fieldspace"></span></li>';
									}else{
										$FieldValue = '<li>'.$FieldTitle .'<a href="'.$pfweblink_field.'" '.$link_target.'>'. $SourceFieldValue.'</a><span class="pf-fieldspace"></span></li>';	
									}
								}elseif($pfsys == 1){
									if ($linkoption == 0) {
										$FieldValue = ''.$FieldTitle .'<span class="pf-ftext">'. $SourceFieldValue.'</span></div> ';
									}else{
										$FieldValue = ''.$FieldTitle .'<span class="pf-ftext"><a href="'.$pfweblink_field.'" '.$link_target.'>'. $SourceFieldValue.'</a></span></div> ';
									}
								}elseif($pfsys == 2){
									if ($linkoption == 0) {
										$FieldValue = ''.$FieldTitle .'<span class="pfdetail-ftext">'. $SourceFieldValue.'</span></div> ';
									}else{
										$FieldValue = ''.$FieldTitle .'<span class="pfdetail-ftext"><a href="'.$pfweblink_field.'" '.$link_target.'>'. $SourceFieldValue.'</a></span></div> ';
									}
								}
							}
							
							$this->FieldOutput = $FieldValue;
						}
						
					if ($SourceFieldValue != '') {
						return $this->FieldOutput;
					} 
					
					 
		}
			
	}
}
?>