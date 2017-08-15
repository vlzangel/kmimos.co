/***************************************************************************************************************
*
*
* METABOX FUNCTIONS
*
*
***************************************************************************************************************/
(function($) {
  "use strict";

	// LOADING --------------------------------------------------------------------------------------------
	$.fn.pfLoadingOverlay = function(args) {
		var defaults = {
	      action:'',
	      message:'',
	      opacity:1       
	    };
	    var settings = $.extend(defaults, args);

	    if (settings.action == 'show') {
	    	if(settings.message != ''){
	    		$(this).append("<div class='pfuserloading pfloadingimg' style='opacity:"+settings.opacity+"'><div class='pfloadingmessage'>"+settings.message+"</div></div>");
	    	}else{
	    		$(this).append("<div class='pfuserloading pfloadingimg' style='opacity:"+settings.opacity+"'></div>");
	    	}
	    } else if(settings.action == 'hide'){
	    	$(this).find('.pfuserloading').remove();
	    };
	}
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
	// LOADING --------------------------------------------------------------------------------------------
})(jQuery);