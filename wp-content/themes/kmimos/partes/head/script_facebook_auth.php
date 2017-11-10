<?php
/*$HTML .= '

  <script>
    function statusChangeCallback(response) {}

    function checkLoginState() {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    }

    window.fbAsyncInit = function() {
    	FB.init({
    	  appId    : "264829233920818",
    	  cookie   : true,
    	  xfbml    : true,
    	  version  : "v2.8"
    	});

    	FB.getLoginStatus(function(response) {
    	  statusChangeCallback(response);
    	});
    };

    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));


    function login(){
      FB.login(function(response) {
        if (response.authResponse) {
          KmimosAPI();
        }else{
          KmimosAPI();
        }
      }, {scope: "public_profile,email"});
    }

    function KmimosAPI() {
      FB.getLoginStatus(function(response) {
        if (response.status == "connected") {
          FB.api("/me", {fields: "first_name,last_name,email,name,id"}, function(response) {
            
            var valid = social_verificar( "facebook", response.id, response.email );

            if( valid ){
              jQuery( ".social_facebook_id" ).val( response.id );
              jQuery( ".social_firstname" ).val( response.first_name );
              jQuery( ".social_firstname").parent("div").addClass("focused");
              jQuery( ".social_lastname" ).val( response.last_name );
              jQuery( ".social_lastname").parent("div").addClass("focused");
              jQuery( ".social_email" ).val( response.email );
              jQuery( ".social_email").parent("div").addClass("focused");
              jQuery( \'[data-target="social-next-step"]\' ).click();
            }else{
              jQuery(".social_google_id").val( "" );
              jQuery(".social_email").val( "" );
              jQuery(".social_firstname").val( "" );
              jQuery(".social_lastname").val( "" );
            }

          });
        }
      });

    }

    function login_facebook(){
      login();
    }
    
    function auth_facebook(){
      FB.login(function(response) {
        if (response.authResponse) {
          FB.api("/me", {fields: "first_name, last_name, email, name, id"}, function(response) {
            social_auth( response.id );
          });
        }
      }, {scope: "public_profile,email"});
    }

  </script>
';*/