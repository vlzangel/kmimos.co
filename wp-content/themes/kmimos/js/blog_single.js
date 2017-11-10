jQuery(document).ready(function(e){
	jQuery(document).on('click','#featured .show .icon', function(e){
	    featured_show(this);
	});

	function featured_show(element){
	    var featured = jQuery(element).closest('#featured');
	    if(featured.hasClass('show')){
	        featured.removeClass('show');
	        jQuery('#featured').css({'right':''});
	    }else{
	        featured.addClass('show');
	        var width= jQuery('#featured.show').width();
	        jQuery('#featured.show').css({'right':'calc(100% - '+width+'px)'});
	    }
	}

	function divide_content(column, separator){
	    var content = jQuery('#single .single .content');
	    var sections = content.children(separator).length;
	    var text = content.html();
	    content.html('');

	    for(var i=0; i<column; i++){
	        content.append('<div class="column column'+i+'">'+text+'</div>');
	        text='';

	        if(i>0){
	            var iprev =  (i-1);
	            var divide = Math.ceil(sections/(column-iprev));
	            content.find('.column'+iprev).children(separator).slice(divide).appendTo(content.find('.column'+i));
	        }
	    }
	}
	divide_content(4, 'p, h1, h2, h3, ul');




	function insert_element(element, after,  separator){
	    var content = jQuery('#single .single .content');
	    var sections = content.find('.column').length;
	    var text = content.html();

	    if(sections>0){
	        content.find('.column').eq(after).after(jQuery(element).addClass('central'));
	    }
	}

    insert_element('#featured', 0, 'p');
});
