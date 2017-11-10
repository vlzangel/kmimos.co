<?php

	// *******************************
	// Google Oauth
	// *******************************

/*	$HTML .= '

		<script>

			(function(d, s){
				api_google = d.createElement(s), e = d.getElementsByTagName(s)[0];
				api_google.async=!0;
				api_google.setAttribute("charset","utf-8");
				api_google.src="//apis.google.com/js/api:client.js";
				api_google.type="text/javascript";
				e.parentNode.insertBefore(api_google, e);
			})(document,"script");

			var googleUser = {};
			var startApp = function() {
				gapi.load("auth2", function(){
					auth2 = gapi.auth2.init({
						client_id: "119129240685-fhsdkrcqqcpac4r07at7ms5k2mko3s0g.apps.googleusercontent.com",
						cookiepolicy: "single_host_origin",
					});

				    var obj = document.getElementsByClassName("google_auth");
					if( obj.length > 0){
					  jQuery.each( obj, function(i, o){ 
					  	attachSignin(o);
					  });
					}

				    var obj = document.getElementsByClassName("google_login");
					if( obj.length > 0){
					  jQuery.each( obj, function(i, o){ 
					  	attachSignon(o);
					  });
					}

				});
			};

			function attachSignin(element) {
				auth2.attachClickHandler(element, {},
				    function(googleUser) {
				    	var valid = social_verificar( 
			    			"google", 
			    			googleUser.getBasicProfile().getId(), 
			    			googleUser.getBasicProfile().getEmail() 
				    	);

				    	if( valid ){
							jQuery(".social_google_id").val( googleUser.getBasicProfile().getId() );

					      	jQuery(".social_email").val( googleUser.getBasicProfile().getEmail() );
							jQuery(".social_email").parent("div").addClass("focused");
							
							var name = googleUser.getBasicProfile().getName().split(" ");
							if( name.length > 0 ){
						      	jQuery(".social_firstname").val( name[0] );
								jQuery(".social_firstname").parent("div").addClass("focused");
							}
							if( name.length > 1 ){
						      	jQuery(".social_lastname").val( name[1] );
								jQuery(".social_lastname").parent("div").addClass("focused");
						    }

						    jQuery(\'[data-target="social-next-step"]\').click();
						}else{
							jQuery(".social_google_id").val( "" );
					      	jQuery(".social_email").val( "" );
					      	jQuery(".social_firstname").val( "" );
					      	jQuery(".social_lastname").val( "" );
						}
				    }, function(error) {});
			}
			function attachSignon(element) {
				auth2.attachClickHandler(element, {},
				    function(googleUser) {

						jQuery(".social_google_id").val( googleUser.getBasicProfile().getId() );

				      	social_auth( googleUser.getBasicProfile().getId() );

				    }, function(error) {});
			}
		</script>		
';*/

	/***********************************************
	Funciones en [ googleUser.GetBasicProfile ]
	***********************************************/
	/*
	getBasicProfile().getId()
	getBasicProfile().getName()
	getBasicProfile().getGivenName()
	getBasicProfile().getFamilyName()
	getBasicProfile().getImageUrl()
	getBasicProfile().getEmail()
	*/
