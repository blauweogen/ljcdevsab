(function($){
	"use strict";
	
	var $window = $(window);
	
	$(document).ready(function () {
		var $container = $('#grid-folio');
		var $items = $('article.project', $container);
		
		var columns = 3;
		var columnsWidth;

		var setColumns = function () {
			var width = $container.width();

			switch(true) {
				case (width > 1300): columns = 5; break;
				case (width > 1024): columns = 4; break;
				case (width > 980): columns = 3; break;
				case (width > 640): columns = 2; break;
				default: columns = 1;
			}

			columnsWidth = Math.floor($container.width() / columns);
			$items.width(columnsWidth);
		};

		var runIsotope = function() {
			setColumns();
			
			$container.isotope({
				layoutMode: 'masonry',
				masonry: {
					columnWidth: columnsWidth
				},
				itemSelector : 'article.project', 
				resizable : true
			});
		};

		runIsotope();
		$container.imagesLoaded(runIsotope);
		$window.resize(runIsotope);

		$('.sort-panel .filter a').click(function () { 
			var selector = $(this).attr('data-filter');

			$(this).parent().parent().find('> li.active').removeClass('active');
			$(this).parent().addClass('active');

			$container.isotope( { 
				filter : selector 
			});

			return false;
		});
	});
	
})(jQuery);
