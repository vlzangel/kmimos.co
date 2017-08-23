<script type="text/javascript">
    function jau_ver_municipios(CB){
		var id =  jQuery("#estados").val();
		var txt = jQuery("#estados option:selected").text();
        jQuery.ajax( {
            method: "POST",
                data: { estado: id },
            url: "<?php echo get_home_url()."/wp-content/themes/pointfinder"; ?>/vlz/ajax_municipios.php",
            beforeSend: function( xhr ) {
		    	jQuery("#municipios").html("<option value=''>Cargando Localidades</option>");
		    	console.log(jQuery('#municipios'))
            }
        }).done(function(data){
			jQuery("#municipios").html("<option value=''>Seleccione una Localidad</option>"+data);
            if( CB != undefined) {
                CB();
            }
        });
    }
</script>

<?php
/**********************************************************************************************************************************
*
* Custom Detail Fields Frontend Class
* 
* Author: Webbu Design
*
* Location: /public_html/wp-content/themes/pointfinder/admin/estatemanagement/includes/pages/dashboard/
*
***********************************************************************************************************************************/

get_template_part('vlz/admin/frontend/functions');


if ( ! class_exists( 'PF_Frontend_Fields' ) ){
	class PF_Frontend_Fields{

		public $FieldOutput;
		public $ScriptOutput;
		public $ScriptOutputDocReady;
		public $VSORules;
		public $VSOMessages;
		public $PFHalf = 1;
		private $itemrecurringstatus = 0;

		function __construct($params = array()){	

			$defaults = array( 
		        'fields' => '',
		        'formtype' => '',
		        'sccval' => '',
				'errorval' => '',
				'post_id' => '',
				'sheader' => '',
				'sheadermes' => '',
				'current_user' => '',
				'dontshowpage' => 0,
				'redirect' => false
		    );

		    $params = array_merge($defaults, $params);

		    $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = PFPermalinkCheck();

			$lang_custom = '';

			if(function_exists('icl_object_id')) {
				$lang_custom = PF_current_language();
			}

				$this->FieldOutput = '<div class="golden-forms">';
				$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="POST" action="">';
				$this->FieldOutput .= '<div class="pfsearchformerrors"><ul></ul><a class="button pfsearch-err-button">'.esc_html__('CERRAR','pointfindert2d').'</a></div>';
				if($params['sccval'] != ''){
					$this->FieldOutput .= '<div class="notification success" id="pfuaprofileform-notify"><div class="row"><p>'.$params['sccval'].'<br>'.$params['sheadermes'].'</p></div></div>';
					$this->ScriptOutput .= '$(document).ready(function(){$.pfmessagehide();});';
				}
				if($params['errorval'] != ''){
					$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><p>'.$params['errorval'].'</p></div>';
					$this->ScriptOutput .= '$(document).ready(function(){$.pfmessagehide();});';
				}
				$this->FieldOutput .= '<div class="">';
				$this->FieldOutput .= '<div class="">';
				$this->FieldOutput .= '<div class="row">';

				$main_submit_permission = true;
				$main_package_purchase_permission = false;
				$main_package_renew_permission = false;
				$main_package_limit_permission = false;
				$main_package_upgrade_permission = false;
				$main_package_expire_problem = false;

				$hide_button = false;

				switch ($params['formtype']) {
					
					case 'myshop':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_myshop.php");
					break;

					case 'mypets':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_mypets.php");
					break;
    
					case 'mypet':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_mypet.php");
					break;
                        
					case 'dirpets':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_dirpets.php");
					break;
                        
					case 'delpet':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_delpet.php");
					break;

					case 'myissues':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_myissues.php");
					break;

					case 'myservices':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_myservices.php");
					break;

					case 'mypictures':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_mypictures.php");
					break;
    
					case 'mypicture':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_mypicture.php");
					break;
  
					case 'cancelreq':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_cancelreq.php");
					break;

					case 'profile':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_profile.php");
					break;

					case 'mybookings':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_bookings.php");
					break;

					case 'favorites':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_favorites.php");
					break;

					case 'invoices':
						include("./wp-content/themes/pointfinder/vlz/admin/frontend_invoices.php");
					break;

					case 'caregiver':
						//include("./wp-content/themes/pointfinder/vlz/admin/frontend/caregiver.php");
					break;

				}

				$this->FieldOutput .= '</div>';/*row*/
				$this->FieldOutput .= '</div>';/*form-section*/
				$this->FieldOutput .= '</div>';/*form-enclose*/

				if($params['formtype'] != 'myitems' && $params['formtype'] != 'myissues' && $params['formtype'] != 'mybookings' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews'){$xtext = '';}else{$xtext = 'style="background:transparent;background-color:transparent;display:none!important"';}
				 
				$this->FieldOutput .= '<div class="pfalign-right" '.$xtext.' style="text-align: right !important;">';
				if($params['formtype'] != 'errorview' && $params['formtype'] != 'banktransfer'){
					if($params['formtype'] != 'myitems' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews' && $params['formtype'] != 'invoices' && $params['dontshowpage'] != 1 && $main_package_expire_problem != true){
			            
	                	$this->FieldOutput .='    
		                <section '.$xtext.'> ';
		                if($params['formtype'] == 'upload'){
			                $setup31_userpayments_recurringoption = PFSAIssetControl('setup31_userpayments_recurringoption','','1');
			         
		                }elseif ($params['formtype'] == 'edititem') {
		                	
		                	$this->FieldOutput .='
			                   <input type="hidden" name="edit_pid" value="'.$params['post_id'].'">';
		                }
		                if ($main_package_purchase_permission == true || $main_package_upgrade_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="">';
		                }elseif ($main_package_renew_permission == true && !empty($membership_user_package_id)) {
		                	$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="'.$membership_user_package_id.'">';
		                }
		                if ($main_package_renew_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="subaction" value="r">';
		                }elseif ($main_package_purchase_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="subaction" value="n">';
		                }elseif ($main_package_upgrade_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="subaction" value="u">';
		                }

		                $this->FieldOutput .= '
		                   <input type="hidden" value="'.$formaction.'" name="action" />
		                   <input type="hidden" value="'.$noncefield.'" name="security" />
		                   ';
		                if (!$hide_button) {
		                	$this->FieldOutput .= '
			                   <input type="submit" value="'.$buttontext.'" id="'.$buttonid.'" class="button blue pfmyitempagebuttonsex vlz_repeat_action" data-edit="'.$params['post_id'].'" />
		                   ';
		                }

		                $this->FieldOutput .= '</section>';
 
		         	}else{
		       			$this->FieldOutput .='    
			                <section  '.$xtext.'> 
			                   <input type="hidden" value="'.$formaction.'" name="action" />
			                   <input type="hidden" value="'.$noncefield.'" name="security" />
			                </section>  
			            ';
		       		}
		       	}
	        
	            $this->FieldOutput.='</div>';
				$this->FieldOutput .= '</form>';
				$this->FieldOutput .= '</div>';
		}

		/**
		*Start: Class Functions
		**/
			public function PFGetList($params = array())
			{
			    $defaults = array( 
			        'listname' => '',
			        'listtype' => '',
			        'listtitle' => '',
			        'listsubtype' => '',
			        'listdefault' => '',
			        'listgroup' => 0,
			        'listgroup_ex' => 1,
			        'listmultiple' => 0,
			        'parentonly' => 0
			    );
				
			    $params = array_merge($defaults, $params);
			    	
			    	$output_options = '';
			    	if($params['listmultiple'] == 1){ $multiplevar = ' multiple';$multipletag = '[]';}else{$multiplevar = '';$multipletag = '';};

			    	if ($params['parentonly'] == 1) {
			    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false,'parent'=>0));
			    	}else{
			    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false));
			    	}
					 

					
					if($params['listgroup'] == 1){
						foreach( $fieldvalues as $parentfieldvalue){
							if($parentfieldvalue->parent == 0){
								$output_options .=  '<optgroup label="'.$parentfieldvalue->name.'">';
								
									if ($params['listgroup_ex'] == 1) {
								
										if(is_array($params['listdefault'])){
											if(in_array($parentfieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValuex = 1;}else{ $fieldtaxSelectedValuex = 0;}
										}else{
											if(strcmp($params['listdefault'],$parentfieldvalue->term_id) == 0){ $fieldtaxSelectedValuex = 1;}else{ $fieldtaxSelectedValuex = 0;}
										}
										if($fieldtaxSelectedValuex == 1){
											$output_options .= '<option value="'.$parentfieldvalue->term_id.'" selected>'.$parentfieldvalue->name.' ('.esc_html__('Todos','pointfindert2d').')</option>';
										}else{
											$output_options .= '<option value="'.$parentfieldvalue->term_id.'">'.$parentfieldvalue->name.' ('.esc_html__('Todos','pointfindert2d').')</option>';
										}
									}
									foreach( $fieldvalues as $fieldvalue){
										if($fieldvalue->parent == $parentfieldvalue->term_id){
											if($params['listdefault'] != ''){
												if(is_array($params['listdefault'])){
													if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
												}else{
													if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
												}
											}else{
												$fieldtaxSelectedValue = 0;
											}
											
											if($fieldtaxSelectedValue == 1){
												$output_options .= '	<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>';
											}else{
												$output_options .= '	<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';
											}
										}
									}
									
								$output_options .= '</optgroup>';
							
							}
						}
					}else{
						foreach( $fieldvalues as $fieldvalue){
							if($fieldvalue->parent != 0){$hasparentitem = ' ';}else{$hasparentitem = '';}
							if($params['listdefault'] != ''){
								if(is_array($params['listdefault'])){
									if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
								}else{
									if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
								}
							}else{
								$fieldtaxSelectedValue = 0;
							}
							
							if($fieldtaxSelectedValue == 1){
								$output_options .= '	<option value="'.$fieldvalue->term_id.'" selected>'.$hasparentitem.$fieldvalue->name.'</option>';
							}else{
								$output_options .= '	<option value="'.$fieldvalue->term_id.'">'.$hasparentitem.$fieldvalue->name.'</option>';
							}
									
						}
					}
					


			    	$output = '';
					$output .= '<div class="pf_fr_inner" data-pf-parent="">';
		   			
			   		switch ($params['listtype']) {
			   			/**
			   			*Listing Types,Item Types,Locations,Features
			   			**/
			   			case 'listingtypes':
			   			case 'itemtypes':
			   			case 'locations':
			   			case 'features':
			   			case 'conditions':
			   				if (!empty($params['listtitle'])) {
				   				$output .= '<label for="'.$params['listname'].'" class="lbl-text">'.$params['listtitle'].':</label>';
			   				}

			   				$as_mobile_dropdowns = PFASSIssetControl('as_mobile_dropdowns','','0');

							if ($as_mobile_dropdowns == 1) {
								$as_mobile_dropdowns_text = 'class="pf-special-selectbox"';
							} else {
								$as_mobile_dropdowns_text = '';
							}
							
			   				$output .= '
			                <label class="lbl-ui select">
			                <select'.$multiplevar.' name="'.$params['listname'].$multipletag.'" id="'.$params['listname'].'" '.$as_mobile_dropdowns_text.'>';
			                $output .= '<option></option>';
			                $output .= $output_options.'
			                </select>
			                </label>';
			   			break;
			   		}

			   		$output .= '</div>';

	            return $output;
			}

			private function PFValidationCheckWrite($field_validation_check,$field_validation_text,$itemid){
				
				$itemname = (string)trim($itemid);
				$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

				if($field_validation_check == 1){
					if($this->VSOMessages != ''){
						$this->VSOMessages .= ','.$itemname.':"'.$field_validation_text.'"';
					}else{
						$this->VSOMessages = $itemname.':"'.$field_validation_text.'"';
					}

					if($this->VSORules != ''){
						$this->VSORules .= ','.$itemname.':"required"';
					}else{
						$this->VSORules = $itemname.':"required"';
					}
				}
			}

			private function PF_UserLimit_Check($action,$post_status){
	
				switch ($post_status) {
					case 'publish':
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
					
					case 'pendingpayment':
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'rejected':
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_rejected','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_rejected','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'pendingapproval':
							switch ($action) {
								case 'edit':
									$output = 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_pendingapproval','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
				}

				return $output;
			}
	    /**
		*End: Class Functions
		**/


	   function __destruct() {
		  $this->FieldOutput = '';
		  $this->ScriptOutput = '';
	    }
	}
}

?>