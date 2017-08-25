///EFECTO SCROLL SUAVISAR
if (window.addEventListener) window.addEventListener('DOMMouseScroll', wheel, false);
	window.onmousewheel = document.onmousewheel = wheel;

	function wheel(event) {
		var delta = 0;
		if (event.wheelDelta) delta = event.wheelDelta/120;
		else if (event.detail) delta = -event.detail/4;

		handle(delta);
		if (event.preventDefault) event.preventDefault();
		event.returnValue = false;
	}

	function handle(delta) {
		var time = 200;
		var distance = 300;

		jQuery('html, body').stop().animate({scrollTop: jQuery(window).scrollTop() - (distance * delta)}, time );
	}