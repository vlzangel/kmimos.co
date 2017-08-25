(function($) {
	"use strict";

	/***************************************************************************************************************
	*
	*
	* USER DASHBOARD ACTIONS
	*
	*
	***************************************************************************************************************/

	/* Map function STARTED */

		$.pf_submit_page_map = function(){

			var mapcontainer = $('#pfupload_map');
			var pf_lat = mapcontainer.data('pf-lat');
			var pf_lng = mapcontainer.data('pf-lng');
			var pf_type = mapcontainer.data('pf-type');
			var pf_zoom = mapcontainer.data('pf-zoom');

			mapcontainer.gmap3({
			  map:{
				  options:{
					center:[pf_lat,pf_lng],
					zoom: pf_zoom, 
					mapTypeId: google.maps.MapTypeId.pf_type,
					mapTypeControl: true,
					mapTypeControlOptions: {
			          style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			          position: google.maps.ControlPosition.RIGHT_BOTTOM
			        },
					zoomControl: true,
					zoomControlOptions: {
			          position: google.maps.ControlPosition.LEFT_BOTTOM
			        },
					panControl: false,
					scaleControl: false,
					navigationControl: false,
					draggable:true,
					scrollwheel: false,
					streetViewControl: false,
				  },
				  callback:function(){
				  	setTimeout(function(){
						$.pf_submit_page_map_fallback();
					},800);
				  }
			  },
			  marker:{
			    values:[{
			    	latLng:[pf_lat,pf_lng],
			    }],
			    options:{
			      draggable: true
			    },
			    events:{
			    	dragend: function(marker){
			    		$('#pfupload_lat_coordinate').val(marker.getPosition().lat());
			    		$('#pfupload_lng_coordinate').val(marker.getPosition().lng());
			    	},
			    },
			  }

			});
		};

		$.pf_submit_page_map_fallback = function(){

			if ($(".pf-search-locatemebut").length) {
				$(".pf-search-locatemebut").svgInject();
			};

			var pf_map = $('#pfupload_map').gmap3("get");
			var pf_input = document.getElementById("pfupload_address");
			$("#pfupload_address").bind("keypress", function(e) {
			  if (e.keyCode == 13) {               
			    e.preventDefault();
			    return false;
			  }
			});
				
			var autocomplete = new google.maps.places.Autocomplete(pf_input);
			autocomplete.bindTo("bounds", pf_map);
				
			google.maps.event.addListener(autocomplete, "place_changed", function() {
			    var place = autocomplete.getPlace();
			    if (!place.geometry) {
			      return;
			    }
			    
			    if (place.geometry.viewport) {
			      pf_map.fitBounds(place.geometry.viewport);
			    } else {
			      pf_map.setCenter(place.geometry.location);
			      pf_map.setZoom(17);
			    }
		    	var marker = $('#pfupload_map').gmap3({get:"marker"});
		    	marker.setPosition(place.geometry.location);
				$("#pfupload_lat_coordinate").val(marker.getPosition().lat());
			    $("#pfupload_lng_coordinate").val(marker.getPosition().lng());
			});

		}

		$("#pf_search_geolocateme").on("click",function(){
			$(".pf-search-locatemebut").hide("fast"); 
			$(".pf-search-locatemebutloading").show("fast");
			$('#pfupload_map').gmap3({
				getgeoloc:{
					callback : function(latLng){
					  if (latLng){
						var geocoder = new google.maps.Geocoder();
						geocoder.geocode({"latLng": latLng}, function(results, status) {
						    if (status == google.maps.GeocoderStatus.OK) {
						      if (results[0]) {
						      	var map = $('#pfupload_map').gmap3("get");
						        map.setCenter(latLng);
						        map.setZoom(17);
						    	var marker =  $('#pfupload_map').gmap3({get:"marker"});
						    	marker.setPosition(latLng);

						        $("#pfupload_address").val(results[0].formatted_address);
						        $("#pfupload_lat_coordinate").val(latLng.lat());
		    					$("#pfupload_lng_coordinate").val(latLng.lng());
						      } 
						    }
						});

					  }
					  $(".pf-search-locatemebut").show("fast");
					  $(".pf-search-locatemebutloading").hide("fast");
					}
				  },
			});
			return false;
		});

	/* Map Function END */


	/* MEMBERSHIP PACKAGES STARTED */
		$('.pf-membership-splan-button a').on('click', function() {
			var packageid = $(this).data('id');
			var ptype = $(this).data('ptype');
			$.pfmembershipgetp(packageid,ptype);
		});

		$.pfmembershipgetp = function(packageid,ptype){

			$('.pfsubmit-inner-membership').hide( "fade");
			$('.pfsubmit-inner-membership-payment').show("fade");
			$('input[name="selectedpackageid"]').val(packageid)

			$.ajax({
				beforeSend:function(){
					$("#pf-ajax-s-button").attr("disabled", true);
					$('.pfm-payment-plans').pfLoadingOverlay({action:'show',message: theme_scriptspfm.buttonwait});
				},
	            type: 'POST',
	            dataType: 'html',
	            url: theme_scriptspf.ajaxurl,
	            data: { 
	                'action': 'pfget_membershippaymentsystem',
	                'ptype':ptype,
	                'pid': packageid,
	                'security': theme_scriptspfm.pfget_membershipsystem
	            },
	            success:function(data){
	            	$('.pfm-payment-plans').html(data);
	            },
	            error: function (request, status, error) {
	            	
	            },
	            complete: function(){
	            	$("#pf-ajax-purchasepack-button").attr("disabled", false);
	            	$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwaitex2);
	            	$('.pfm-payment-plans').pfLoadingOverlay({action:'hide'});
	            },
	        });
			return false;
		};

		$('.pfsubmit-title-membershippack').on('click', function() {
			$('.pfsubmit-inner-membership').show("fade");
			$('.pfsubmit-inner-membership-payment').hide("fade");
		});

		$('.pfsubmit-title-membershippack-payment').on('click', function() {
			$('.pfsubmit-inner-membership').hide("fade");
			$('.pfsubmit-inner-membership-payment').show("fade");
		});

		// AJAX MEMBERSHIP PAYMENT PROCESS
		$("#pf-ajax-purchasepack-button").on("click touchstart",function(){

			var form = $("#pfuaprofileform");
			form.validate();
			
			if(!form.valid()){
				$.pfscrolltotop();
			}else{
				$("#pf-ajax-purchasepack-button").val(theme_scriptspfm.buttonwait);
				$.pfOpenMembershipModal('open','purchasepackage',form.serialize());
				return false;
			};
		});

		// AJAX MEMBERSHIP CANCEL RECURRING
		$('.pf-dash-cancelrecurring').live('click', function() {
			$.pfOpenMembershipModal('open','cancelrecurring','');
			return false;
		});
		
		$.pfOpenMembershipModal = function(status,modalname,formdata) {

			$.pfdialogstatus = '';
			
		    if(status == 'open'){
		    	
		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}

		    	if (modalname == 'purchasepackage' || modalname == 'cancelrecurring') {
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect2});
		    	};
		    	
	    		var minwidthofdialog = 380;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};
			
	    		$.ajax({
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: { 
		                'action': 'pfget_membershipsystem',
		                'formtype': modalname,
		                'dt': formdata,
		                'security': theme_scriptspfm.pfget_membershipsystem
		            },
		            success:function(data){
		            	
		            	var obj = [];
						$.each(data, function(index, element) {
							obj[index] = element;
						});

						

						if(obj.process == true){
							if (obj.processname == 'paypal'|| obj.processname == 'paypal2' ) {
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
								window.location = obj.returnurl;
							}else if(obj.processname == 'stripe'){
								var handler = StripeCheckout.configure({
									key: obj.key,
									token: function(token) {
										$.pfOpenMembershipModal('open','stripepay',token);
									}
								});

								
								handler.open({
								  name: obj.name,
								  description: obj.description,
								  amount: obj.amount,
								  email: obj.email,
								  currency: obj.currency,
								  allowRememberMe: false,
								  opened:function(){
								  	$.pfOpenMembershipModal('close');
								  }
								});
								

								$(window).on('popstate', function() {
									handler.close();
								});
							}else if(obj.processname == 'stripepay'){
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								window.location = theme_scriptspfm.dashurl;

							}else if(obj.processname == 'free' || obj.processname == 'trial'){

								if (obj.process == true) {
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect4});
									window.location = theme_scriptspfm.dashurl;
								}else{
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
									$("#pf-membersystem-dialog").html(obj.mes);

									var pfreviewoverlay = $("#pfmdcontainer-overlay");
									pfreviewoverlay.show("slide",{direction : "up"},100);
								};
							}else if(obj.processname == 'bank'){

								if (obj.process == true) {
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect4});
									window.location = obj.returnurl;
								}else{
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
									$("#pf-membersystem-dialog").html(obj.mes);

									var pfreviewoverlay = $("#pfmdcontainer-overlay");
									pfreviewoverlay.show("slide",{direction : "up"},100);
								};
							}else if(obj.processname == 'cancelrecurring'){
								if (obj.process == true) {
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect4});
									setTimeout(function(){
										window.location = theme_scriptspfm.dashurl;
									},2000);
								}else{
									$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
									$("#pf-membersystem-dialog").html(obj.mes);

									var pfreviewoverlay = $("#pfmdcontainer-overlay");
									pfreviewoverlay.show("slide",{direction : "up"},100);
								};
							};

						}else{

							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
							$("#pf-membersystem-dialog").html(obj.mes);

							var pfreviewoverlay = $("#pfmdcontainer-overlay");
							pfreviewoverlay.show("slide",{direction : "up"},100);
						}

						$('.pf-overlay-close').click(function(){
							$.pfOpenMembershipModal('close');
						});


		            },
		            error: function (request, status, error) {
		            	
	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);
		            	
		            },
		            complete: function(){
	            		$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});
		            	$('.pointfinder-dialog').center(true);
		            },
		        });
			
	        	if(modalname != ''){
			    $("#pf-membersystem-dialog").dialog({
			    	closeOnEscape: false,
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				        $('.ui-widget-overlay').click(function(e) {
						    e.preventDefault();
						    return false;
						});
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center",collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "close" );
				$.pfdialogstatus = '';
			}
		};
	/* MEMBERSHIP PACKAGES END */


	/* AJAX PAYMENT MODAL STARTED */
		$('.pfbuttonpaymentb').live('click',function(){
			var selectedval = $(this).parent().prev().find('select option:selected').val();
			var itemnum = $(this).data('pfitemnum');

			if(selectedval == 'creditcard'){

				$.pfOpenPaymentModal('open','creditcardstripe',itemnum,'');

			}else if(selectedval == 'paypal'){

				$.pfOpenPaymentModal('open','paypalrequest',itemnum,'');

			}else{

				window.location = selectedval;

			};
				

			return false;
		});

		$.pfOpenPaymentModal = function(status,modalname,itemid,token,otype) {


			$.pfdialogstatus = '';
			
		    if(status == 'open'){
		    	
		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}

		    	if (modalname == 'creditcardstripe') {
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect2});
		    	}else if(modalname == 'paypalrequest'){
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect});
		    	}else if(modalname == 'stripepayment'){
		    		$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect3});
		    	};
		    	
	    		var minwidthofdialog = 380;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};
			
	    		$.ajax({
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: { 
		                'action': 'pfget_paymentsystem',
		                'formtype': modalname,
		                'itemid': itemid,
		                'otype':otype,
		                'token': token,
		                'security': theme_scriptspfm.pfget_paymentsystem
		            },
		            success:function(data){
		            	
		            	var obj = [];
						$.each(data, function(index, element) {
							obj[index] = element;
						});

						

						if(obj.process == true){
							if (modalname == 'paypalrequest') {
								window.location = obj.returnurl;
							}else if(modalname == 'creditcardstripe'){
								var handler = StripeCheckout.configure({
									key: obj.key,
									token: function(token) {
										$.pfOpenPaymentModal('open','stripepayment',itemid,token,obj.otype);
									}
								});

								
								handler.open({
								  name: obj.name,
								  description: obj.description,
								  amount: obj.amount,
								  email: obj.email,
								  currency: obj.currency,
								  allowRememberMe: false,
								  opened:function(){
								  	$.pfOpenPaymentModal('close');
								  },
								  closed:function(){
								  	if ($('#pfupload_type').val() == 1) {
								  		window.location = theme_scriptspfm.dashurl2;
								  	};
								  }
								});
								

								$(window).on('popstate', function() {
									handler.close();
								});
							}else if(modalname == 'stripepayment'){
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								window.location = obj.returnurl;
							};

						}else{

							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
							$("#pf-membersystem-dialog").html(obj.mes);

							var pfreviewoverlay = $("#pfmdcontainer-overlay");
							pfreviewoverlay.show("slide",{direction : "up"},100);
						}

						$('.pf-overlay-close').click(function(){
							$.pfOpenPaymentModal('close');
						});


		            },
		            error: function (request, status, error) {
		            	
	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);
		            	
		            },
		            complete: function(){
		            	
	            		
	            		$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});
		            	$('.pointfinder-dialog').center(true);
		            },
		        });
			
	        	if(modalname != ''){
			    $("#pf-membersystem-dialog").dialog({
			    	closeOnEscape: false,
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				        $('.ui-widget-overlay').click(function(e) {
						    e.preventDefault();
						    return false;
						});
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center",collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "close" );
				$.pfdialogstatus = '';
			}
		};
	/* AJAX PAYMENT MODAL END */

	/* LISTING PACK PAYMENTS STARTED */
		$('.pfpackselector').change(function(){
			$.pf_get_priceoutput(1);
		});

		$('#featureditembox').on('change',function(){
			$.pf_get_priceoutput();
		});

		$.pf_get_priceoutput = function(pcs){
			if ($('#pfupload_type').val() == 1) {
				
				var listing_category = $('input.pflistingtypeselector:checked').val();
				var listing_pack = $('input.pfpackselector:checked').val();
				var listing_featured = ($('#featureditembox').attr('checked'))? 1:0;

				var status_c = $('#pfupload_c').val();
				var status_f = $('#pfupload_f').val();
				var status_p = $('#pfupload_p').val();
				var status_o = $('#pfupload_o').val();
				var status_px = $('#pfupload_px').val();

				if (status_c == 1) {listing_category = '';};
				if (status_f == 1) {listing_featured = '';};
				if (listing_pack == status_p) {listing_pack = '';};
				
				$.ajax({
			    	beforeSend:function(){	
			    		if (pcs == 1) {$('.pflistingtype-selector-main-top').pfLoadingOverlay({action:'show'})};
			    		$('.pfsubmit-inner-payment .pfsubmit-inner-sub').pfLoadingOverlay({action:'show'});
			    		$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwait);
						$("#pf-ajax-uploaditem-button").attr("disabled", true);
			    	},
					url: theme_scriptspf.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'pfget_listingpaymentsystem',
						c:listing_category,
						p:listing_pack,
						f:listing_featured,
						o:status_o,
						px:status_px,
						lang: theme_scriptspfm.pfcurlang,
						security: theme_scriptspfm.pfget_lprice
					},
				}).success(function(obj) {
					
					if (obj) {
						if (obj.totalpr != 0) {
							$('.pfsubmit-inner-totalcost-output').html(obj.html);
							$('.pfsubmit-inner-payment').show();
						}else{
							$('.pfsubmit-inner-totalcost-output').html(obj.html);
							$('.pfsubmit-inner-payment').hide();
						}
						
					};
					
				}).complete(function(){
					$("#pf-ajax-uploaditem-button").attr("disabled", false);
					$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwaitex2);
					$('.pfsubmit-inner-payment .pfsubmit-inner-sub').pfLoadingOverlay({action:'hide'});
					if (pcs == 1) {$('.pflistingtype-selector-main-top').pfLoadingOverlay({action:'hide'})};
				});
			};
		}
	/* LISTING PACK PAYMENTS END */


	/* PROFILE UPDATE FUNCTION STARTED */
		$('#pf-ajax-profileupdate-button').on('click touchstart',function(){

			var form = $('#pfuaprofileform');
			var pfsearchformerrors = form.find(".pfsearchformerrors");
			if ($.isEmptyObject($.pfAjaxUserSystemVars4)) {

				$.pfAjaxUserSystemVars4 = {};
				$.pfAjaxUserSystemVars4.email_err = 'Please write an email';
				$.pfAjaxUserSystemVars4.email_err2 = 'Your email address must be in the format of name@domain.com';
				$.pfAjaxUserSystemVars4.nickname_err = 'Please write nickname';
				$.pfAjaxUserSystemVars4.nickname_err2 = 'Please enter at least 3 characters for nickname.';
				$.pfAjaxUserSystemVars4.passwd_err = $.validator.format("Enter at least {0} characters");
				$.pfAjaxUserSystemVars4.passwd_err2 = "Enter the same password as above";
			}

			form.validate({
				  debug:false,
				  onfocus: false,
				  onfocusout: false,
				  onkeyup: false,
				  rules:{
				    nickname:{
				      required: true,
				      minlength: 3
				    },
				    password: {
						minlength: 7
					},
					password2: {
						minlength: 7,
						equalTo: "#password"
					},
				  	email:{
				  		required:true,
				  		email:true
				  	}
				  },
				  messages:{
				  	nickname:{
					  	required:$.pfAjaxUserSystemVars4.nickname_err,
					  	minlength:$.pfAjaxUserSystemVars4.nickname_err2
				  	},
				  	password: {
						rangelength: $.pfAjaxUserSystemVars4.passwd_err
					},
					password2: {
						minlength: $.pfAjaxUserSystemVars4.passwd_err,
						equalTo: $.pfAjaxUserSystemVars4.passwd_err2
					},
				  	email: {
					    required: $.pfAjaxUserSystemVars4.email_err,
					    email: $.pfAjaxUserSystemVars4.email_err2
				    }
				  },
				  validClass: "pfvalid",
				  errorClass: "pfnotvalid pfadmicon-glyph-858",
				  errorElement: "li",
				  errorContainer: pfsearchformerrors,
				  errorLabelContainer: $("ul", pfsearchformerrors),
				  invalidHandler: function(event, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						$.pfscrolltotop();
						pfsearchformerrors.show("slide",{direction : "up"},100);
						form.find(".pfsearch-err-button").click(function(){
							pfsearchformerrors.hide("slide",{direction : "up"},100);
							return false;
						});
					}else{
						pfsearchformerrors.hide("fade",300);
					}
				  }
			});
			
			
			if(!form.valid()){	
				$.pfscrolltotop();		
				return false;
			};
		});
	/* PROFILE UPDATE FUNCTION END */


	/* MOBILEDROPDOWNS  FUNCTION STARTED  */
		$(function(){
			if (theme_scriptspf.mobiledropdowns == 1 && !$.pf_tablet_check()) {
				$("#pfupload_itemtypes").select2("destroy");
				$("#pfupload_sublistingtypes").select2("destroy");
				$("#pfupload_subsublistingtypes").select2("destroy");
				$("#pflocationselector").select2("destroy");
				$("#pfupload_locations").select2("destroy");
			};
		});
	/* MOBILEDROPDOWNS  FUNCTION END  */



	/* FEATURES  FUNCTION STARTED  */
		$.pf_getfeatures_now = function(itemid){

			var postid = $('#pfupload_listingpid').val();

			$.ajax({
		    	beforeSend:function(){
		    		$('.pfsubmit-inner-features').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_featuresystem',
					id: itemid,
					postid:postid,
					lang: theme_scriptspfm.pfcurlang,
					security: theme_scriptspfm.pfget_featuresystem
				},
			}).done(function(obj) {
				if (obj.length == 0) {
					$('.pfsubmit-inner-features').hide();
					$('.pfsubmit-inner-features-title').hide();
				}else{
					$('.pfsubmit-inner-features').show();
					$('.pfsubmit-inner-features-title').show();
				}
				$('.pfsubmit-inner-features').html(obj);

				if ($('input[name="pointfinderfeaturecount"]').val() == 0) {
					$('.pfsubmit-inner-features').hide();
					$('.pfsubmit-inner-features-title').hide();
				}else{
					$('.pfsubmit-inner-features').show();
					$('.pfsubmit-inner-features-title').show();
				}


				$('.pfsubmit-inner-features').pfLoadingOverlay({action:'hide'});

				$('.pfitemdetailcheckall').on('click',function(event) {
					/* Act on the event */
					$.each($('[name="pffeature[]"]'), function(index, val) {
						 $(this).attr('checked', true);
					});
				});

				$('.pfitemdetailuncheckall').on('click',function(event) {
					/* Act on the event */
					$.each($('[name="pffeature[]"]'), function(index, val) {
						 $(this).attr('checked', false);
					});
				});

			});
		}
	/* FEATURES FUNCTION END  */


	/* CUSTOM FIELDS FUNCTION STARTED */
		$.pf_getcustomfields_now = function(itemid){

			var postid = $('#pfupload_listingpid').val();

			$.ajax({
		    	beforeSend:function(){
		    		$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'show'});	
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_fieldsystem',
					id: itemid,
					lang: theme_scriptspfm.pfcurlang,
					postid: postid,
					security: theme_scriptspfm.pfget_fieldsystem
				},
			}).done(function(obj) {
				if (obj.length == 0) {
					$('.pfsubmit-inner-customfields').hide();
					$('.pfsubmit-inner-customfields-title').hide();
				}else{
					$('.pfsubmit-inner-customfields').show();
					$('.pfsubmit-inner-customfields-title').show();
				}
				$('.pfsubmit-inner-customfields').html(obj);

				$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'hide'});
			});
		}
	/* CUSTOM FIELDS FUNCTION END  */


	/* ITEM ADD/UPDATE FUNCTION STARTED  */
		$("#pf-ajax-uploaditem-button").on("click touchstart",function(){

			var form = $("#pfuaprofileform");
			
			tinyMCE.triggerSave();

			form.validate();
			
			if(!form.valid()){
				/*Extra classes for image and listing type*/
					if ($('#pfupload_listingtypes').hasClass('pfnotvalid')) {
						$('.pfsubmit-inner-listingtype').addClass('pfnotvalid');
					}else{
						$('.pfsubmit-inner-listingtype').removeClass('pfnotvalid');
					};
					if ($('#pfupload_locations').hasClass('pfnotvalid')) {
						$('.pfsubmit-location-errc').addClass('pfnotvalid');
					}else{
						$('.pfsubmit-location-errc').removeClass('pfnotvalid');
					};
					if ($('.pfuploadimagesrc').hasClass('pfnotvalid')) {
						$('.pfitemimgcontainer').addClass('pfnotvalid');
					}else{
						$('.pfitemimgcontainer').removeClass('pfnotvalid');
					};
					if ($('#pfuploadfilesrc').hasClass('pfnotvalid')) {
						$('.pfitemfilecontainer').addClass('pfnotvalid');
					}else{
						$('.pfitemfilecontainer').removeClass('pfnotvalid');
					};

					if ($('#pfupload_sublistingtypes').hasClass('pfnotvalid')) {
						$('#s2id_pfupload_sublistingtypes input').addClass('pfnotvalid');
					}else{
						$('#s2id_pfupload_sublistingtypes input').removeClass('pfnotvalid');
					};

					if ($('#pfupload_subsublistingtypes').hasClass('pfnotvalid')) {
						$('#s2id_pfupload_subsublistingtypes input').addClass('pfnotvalid');
					}else{
						$('#s2id_pfupload_subsublistingtypes input').removeClass('pfnotvalid');
					};

					if ($('#pfupload_address').hasClass('pfnotvalid') || $('#pfupload_lat_coordinate').hasClass('pfnotvalid') || $('#pfupload_lng_coordinate').hasClass('pfnotvalid')) {
						$('.pfsubmit-address-errc').addClass('pfnotvalid');
						$('#pfupload_address').removeClass('pfnotvalid');
						$('#pfupload_lat_coordinate').removeClass('pfnotvalid');
						$('#pfupload_lng_coordinate').removeClass('pfnotvalid');
					}else{
						$('.pfsubmit-address-errc').removeClass('pfnotvalid');
					};

					if ($('#item_desc').hasClass('pfnotvalid')) {
						tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#F0D7D7';
					}else{
						tinymce.activeEditor.contentDocument.body.style.backgroundColor = '#ffffff'
					};


				$.pfscrolltotop();
				return false;
			}else{
				$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwait);
				$("#pf-ajax-uploaditem-button").attr("disabled", true);
				//form.submit();
				if ($("#pf-ajax-uploaditem-button").data('edit') > 0) {
					$.pfOpenItemUpEditModal('open','edit',form.serialize());
				}else{
					$.pfOpenItemUpEditModal('open','upload',form.serialize());
				};
				
				return false;
			};
		});

		/*Delete Item*/
		$(".pf-itemdelete-link").on("click touchstart",function(){
			if (confirm(theme_scriptspfm.delmsg)) {
				$.pfOpenItemUpEditModal('open','delete',$(this).data('pid'));
			};	
		});


		$.pfOpenItemUpEditModal = function(status,modalname,formdata) {
			$.pfdialogstatus = '';
			
		    if(status == 'open'){
		    	
		    	if ($.pfdialogstatus == 'true') {$( "#pf-membersystem-dialog" ).dialog( "close" );}

		    	$('#pf-membersystem-dialog').pfLoadingOverlay({action:'show',message: theme_scriptspfm.paypalredirect2});
		    	
	    		var minwidthofdialog = 380;

	    		if(!$.pf_mobile_check()){ minwidthofdialog = 320;};
			
	    		$.ajax({
		            type: 'POST',
		            dataType: 'json',
		            url: theme_scriptspf.ajaxurl,
		            data: { 
		                'action': 'pfget_itemsystem',
		                'formtype': modalname,
		                'dt': formdata,
		                'lang': theme_scriptspfm.pfcurlang,
		                'security': theme_scriptspfm.pfget_itemsystem
		            },
		            success:function(data){
		            	
		            	var obj = [];
						$.each(data, function(index, element) {
							obj[index] = element;
						});

						if(obj.process == true){

							if(obj.processname == 'upload' || obj.processname == 'edit'){
								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);
								
								if (obj.returnval.ppps != '') {

									if (obj.processname == 'edit' && obj.returnval.pppso == 1) {
										var otype = 1;
									}else{
										var otype = 0;
									}

									if (obj.returnval.ppps == 'paypal') {
										$.pfOpenPaymentModal('open','paypalrequest',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'stripe'){
										$.pfOpenPaymentModal('open','creditcardstripe',obj.returnval.post_id,'',otype);
									}else if(obj.returnval.ppps == 'bank'){
											setTimeout(function(){window.location = obj.returnval.pppsru;},2000);
									}else if(obj.returnval.ppps == 'free'){
										if (obj.processname == 'edit') {
											setTimeout(function(){window.location = obj.returnurl;},3500);
										}else{
											setTimeout(function(){window.location = obj.returnurl;},2000);
										};
									};
								}else{
									if (obj.processname == 'edit') {
										setTimeout(function(){window.location = obj.returnurl;},3500);
									}else{
										setTimeout(function(){window.location = obj.returnurl;},2000);
									};
								};
							}else if(obj.processname == 'delete'){

								$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
								$("#pf-membersystem-dialog").html(obj.mes);

								var pfreviewoverlay = $("#pfmdcontainer-overlay");
								pfreviewoverlay.show("slide",{direction : "up"},100);

								setTimeout(function(){window.location = obj.returnurl;},2000);
							};

						}else{

							$('#pf-membersystem-dialog').pfLoadingOverlay({action:'hide'});
							$("#pf-membersystem-dialog").html(obj.mes);

							var pfreviewoverlay = $("#pfmdcontainer-overlay");
							pfreviewoverlay.show("slide",{direction : "up"},100);

							$("#pf-ajax-uploaditem-button").val(theme_scriptspfm.buttonwaitex);
							$("#pf-ajax-uploaditem-button").attr("disabled", false);
						}

						$('.pf-overlay-close').click(function(){
							$.pfOpenMembershipModal('close');
						});


		            },
		            error: function (request, status, error) {
		            	
	                	$("#pf-membersystem-dialog").html('Error:'+request.responseText);
		            	
		            },
		            complete: function(){
	            		$("#pf-membersystem-dialog").dialog({position:{my: "center", at: "center",collision:"fit"}});
		            	$('.pointfinder-dialog').center(true);
		            },
		        });
			
	        	if(modalname != ''){
			    $("#pf-membersystem-dialog").dialog({
			    	closeOnEscape: false,
			        resizable: false,
			        modal: true,
			        minWidth: minwidthofdialog,
			        show: { effect: "fade", duration: 100 },
			        dialogClass: 'pointfinder-dialog',
			        open: function() {
				        $('.ui-widget-overlay').addClass('pf-membersystem-overlay');
				        $('.ui-widget-overlay').click(function(e) {
						    e.preventDefault();
						    return false;
						});
				    },
				    close: function() {
				        $('.ui-widget-overlay').removeClass('pf-membersystem-overlay');
				    },
				    position:{my: "center", at: "center",collision:"fit"}
			    });
			    $.pfdialogstatus = 'true';
				}

			}else{
				$( "#pf-membersystem-dialog" ).dialog( "close" );
				$.pfdialogstatus = '';
			}
		};
	/* ITEM ADD/UPDATE FUNCTION END  */

	/* IMAGE AND FILE UPLOAD STARTED */

		/* Delete Photo */
		$(".pf-delete-standartimg").live("click", function(){
			
			var deleting_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");
			
			if ($(this).data("pffeatured") == 'yes') {
				return alert("This is your cover photo and can not remove. Please change your cover photo first.");
			}else{
				if(confirm("Are you sure want to delete this item? (This action can not be rollback.")){
					/*Send ajax*/
					$.ajax({
						beforeSend:function(){
				    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
				    	},
						url: theme_scriptspf.ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_imagesystem',
							iid: deleting_item,
							id: post_id,
							process: 'd',
							security: theme_scriptspfm.pfget_imagesystem
						},
					})
					.done(function(obj) {
						$.pfitemdetail_listimages(post_id);
						
						$.drzoneuploadlimit = $.drzoneuploadlimit +1;
						$(".pfuploaddrzonenum").text($.drzoneuploadlimit);

						var myDropzone = Dropzone.forElement("div#pfdropzoneupload");
						myDropzone.options.maxFiles = $.drzoneuploadlimit;

					})
					
				}
			}
		    
		    
		    return false;
		});

		
		/* Change Cover Photo */
		$(".pf-change-standartimg").live("click", function(){
			
			var changing_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");

			/*Send ajax*/
		    $.ajax({
		    	beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					iid: changing_item,
					id: post_id,
					process: 'c',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$.pfitemdetail_listimages(post_id);
			})
		    
		    return false;
		});

		$.pfitemdetail_listimages = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					id: id,
					process: 'l',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedimages').html(obj);
			})
		};

		/* OLD Upload system */

		/* Delete Photo OLD */
		$(".pf-delete-standartimg-old").live("click", function(){
			
			var deleting_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");
			
			if ($(this).data("pffeatured") == 'yes') {
				return alert("This is your cover photo and can not remove. Please change your cover photo first.");
			}else{
				if(confirm("Are you sure want to delete this item? (This action can not be rollback.")){
					/*Send ajax*/
					$.ajax({
						beforeSend:function(){
				    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
				    	},
						url: theme_scriptspf.ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_imagesystem',
							iid: deleting_item,
							id: post_id,
							oldup:1,
							process: 'd',
							security: theme_scriptspfm.pfget_imagesystem
						},
					})
					.done(function(obj) {
						$.pfitemdetail_listimages_old(post_id);

						$.pfuploadimagelimit = $.pfuploadimagelimit +1;
						$('.pfmaxtext').text($.pfuploadimagelimit);

						if ($.pfuploadimagelimit > 0) {
							$('.pfuploadfeaturedimgupl-container').css('display','inline-block');
						}

					})
					
				}
			}
		    
		    
		    return false;
		});

		/*List images - OLD*/
		$.pfitemdetail_listimages_old = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					id: id,
					oldup:1,
					process: 'l',
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedimages').html(obj);
			})
		};


		/* Change Cover Photo - OLD */
		$(".pf-change-standartimg-old").live("click", function(){
			
			var changing_item = $(this).data("pfimgno");
			var post_id = $(this).data("pfpid");

			/*Send ajax*/
		    $.ajax({
		    	beforeSend:function(){
		    		$('.pfuploadedimages').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_imagesystem',
					iid: changing_item,
					id: post_id,
					process: 'c',
					oldup:1,
					security: theme_scriptspfm.pfget_imagesystem
				},
			})
			.done(function(obj) {
				$.pfitemdetail_listimages_old(post_id);
			})
		    
		    return false;
		});


		/* FILE Upload system */

		/* Delete File */
		$(".pf-delete-standartfile").live("click", function(){
			
			var deleting_item = $(this).data("pffileno");
			var post_id = $(this).data("pfpid");
			
			if ($(this).data("pffeatured") == 'yes') {
				return alert("This is your cover photo and can not remove. Please change your cover photo first.");
			}else{
				if(confirm("Are you sure want to delete this item? (This action can not be rollback.")){
					/*Send ajax*/
					$.ajax({
						beforeSend:function(){
				    		$('.pfuploadedfiles').pfLoadingOverlay({action:'show'});
				    	},
						url: theme_scriptspf.ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_filesystem',
							iid: deleting_item,
							id: post_id,
							process: 'd',
							security: theme_scriptspfm.pfget_filesystem
						},
					})
					.done(function(obj) {
						$.pfitemdetail_listfiles(post_id);

						$.pfuploadfilelimit = $.pfuploadfilelimit +1;
						$('.pfmaxtext2').text($.pfuploadfilelimit);

						if ($.pfuploadfilelimit > 0) {
							$('.pfuploadfeaturedfileupl-container').css('display','inline-block');
						}

					})
					
				}
			}
		    
		    
		    return false;
		});

		/*List images */
		$.pfitemdetail_listfiles = function(id){
			$.ajax({
				beforeSend:function(){
		    		$('.pfuploadedfiles').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_filesystem',
					id: id,

					process: 'l',
					security: theme_scriptspfm.pfget_filesystem
				},
			})
			.done(function(obj) {
				$('.pfuploadedfiles').html(obj);
			})
		};


		/* Change Cover Photo  */
		$(".pf-change-standartfile").live("click", function(){
			
			var changing_item = $(this).data("pffileno");
			var post_id = $(this).data("pfpid");

			/*Send ajax*/
		    $.ajax({
		    	beforeSend:function(){
		    		$('.pfuploadedfiles').pfLoadingOverlay({action:'show'});
		    	},
				url: theme_scriptspf.ajaxurl,
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'pfget_filesystem',
					iid: changing_item,
					id: post_id,
					process: 'c',
					security: theme_scriptspfm.pfget_filesystem
				},
			})
			.done(function(obj) {
				$.pfitemdetail_listimages_old(post_id);
			})
		    
		    return false;
		});
	/* IMAGE AND FILE UPLOAD END */



	$(".pfstatusbuttonaction").on('click touchstart','',function(event) {

		var pid = $(this).data('pfid');
		var thisitem = $(this);

		$.ajax({
		beforeSend:function(){
			$('.pfmu-itemlisting-inner'+pid).pfLoadingOverlay({action:'show'});
		},
        type: 'POST',
        dataType: 'json',
        url: theme_scriptspf.ajaxurl,
        data: { 
            'action': 'pfget_onoffsystem',
            'itemid': pid,
            'lang': theme_scriptspfm.pfcurlang,
            'security': theme_scriptspfm.pfget_onoffsystem
        }
    	})
		.done(function(obj) {

			if (obj == 1) {
				thisitem.switchClass('pfstatusbuttonactive','pfstatusbuttondeactive');
			}else{
				thisitem.switchClass('pfstatusbuttondeactive','pfstatusbuttonactive');
			};

			$('.pfmu-itemlisting-inner'+pid).pfLoadingOverlay({action:'hide'});
		})

	});

})(jQuery);