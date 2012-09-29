
// Carousel
jQuery(document).ready (function(){
	
	jQuery("#tS2").thumbnailScroller({ 
		scrollerType:"clickButtons", 
		scrollerOrientation:"horizontal", 
		scrollSpeed:2, 
		scrollEasing:"linear", 
		scrollEasingAmount:600, 
		acceleration:4, 
		scrollSpeed:800, 
		noScrollCenterSpace:10, 
		autoScrolling:0, 
		autoScrollingSpeed:2000, 
		autoScrollingEasing:"linear", 
		autoScrollingDelay:500 
	});
	
	jQuery('.jTscrollerPrevButton').hide();
	jQuery('.jTscrollerNextButton').hide();

	jQuery('#home-rotator').hover(function  () {
		jQuery('.jTscrollerPrevButton, .jTscrollerNextButton').stop(true,true).fadeIn();
	}, function() {
		jQuery('.jTscrollerPrevButton, .jTscrollerNextButton').fadeOut();
	});



	
});

