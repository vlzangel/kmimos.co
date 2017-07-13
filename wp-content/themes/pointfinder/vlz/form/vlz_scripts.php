
<script type="text/javascript">

	function vlz_select(id){
		if( jQuery("#"+id+" input").prop("checked") ){
			jQuery("#"+id+" input").prop("checked", false);
			jQuery("#"+id).removeClass("vlz_check_select");
		}else{

			jQuery("#"+id+" input").prop("checked", true);
			jQuery("#"+id).addClass("vlz_check_select");
		}
	}
	
	jQuery(".vlz_sub_seccion_titulo").on("click", 
		function (){

			var con = jQuery(jQuery(this)[0].nextElementSibling);

			if( con.css("display") == "none" ){
				con.slideDown( "slow", function() { });
			}else{
				con.slideUp( "slow", function() { });
			}
			
		}
	);

	function vlz_top(){
		jQuery('html, body').animate({
	        scrollTop: 0
	    }, 500);
	}

	jQuery(".pficon-imageclick").on("click", function(){
		if(jQuery(this).attr('data-pf-link')){
			jQuery.prettyPhoto.open(jQuery(this).attr('data-pf-link'));
		}
	});
</script>
