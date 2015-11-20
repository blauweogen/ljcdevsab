/* Portfolio Sorting */
(function($) {
	"use strict";
	
	jQuery(document).ready(function () {
	
		var run_isotope = function($container) {
			var $els = $('.project', $container);
			var width = $container.width();
			var columns;
			
			switch(true) {
				case (width < 400): columns = 1; break;
				case (width < 830): columns = 2; break;
				default: columns = 3;
			}
			
			var column_width = Math.floor( width / columns );
			$els.css({
				'width': column_width + 'px'
			});
			
			$container.isotope({ 
				itemSelector : '.project', 
				resizable : true,
				layoutMode : 'masonry'
			});
		};
		
		$('.works-list').each(function(){
			var $container = $(this);
			
			$container.imagesLoaded(function () { 
				run_isotope($container);
			});

			$('.sort-panel .filter a').click(function () { 
				var selector = $(this).attr('data-filter');

				$(this).parent().parent().find('> li.active').removeClass('active');
				$(this).parent().addClass('active');

				$container.isotope({
					filter : selector
				});

				return false;
			} );

			$(window).resize(function(){
				run_isotope($container);
			});
		});
	});
})(jQuery);
