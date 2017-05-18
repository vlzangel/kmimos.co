$(window).load(function() {
	
	$('#section-1').height( $(window).height() - 1  );
	$('#section-6').height( $(window).height() - 1  );
	
});

#(window).resize(function(){
	$('iframe').width( ($(window).width() * 25) / 100 );
	$('iframe').height( ($(window).height() * 25) / 100 );
})