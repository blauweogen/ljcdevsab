(function($){
	$.fn.sbIsotopeRecentWorks = function() {
		var $window = $(window);
		var windowWidth;

		var getItemsCount = function(containerWidth) {
			switch(true) {
				case (containerWidth<=440): return 1; break;
				case (containerWidth<=840): return 2; break;
				case (containerWidth<=1180): return 2; break;
				case (containerWidth<=1540): return 3; break;
				default: return 4;
			}
		};

		var initIsotope = function($container, $containerHidden) {
			var containerWidth, itemWidth, itemsInRowCount;
			var maxRows = 2, maxItems, i, n;
			
			windowWidth = $window.width();
			containerWidth = $container.width();
			itemsInRowCount = getItemsCount(containerWidth);
			itemWidth = Math.round(containerWidth / itemsInRowCount) - 1;
			maxItems = itemsInRowCount * maxRows;
			
			if (containerWidth > windowWidth) {
				$container.css('margin-left', '-'+Math.ceil((containerWidth - windowWidth)/2)+'px');
			} else {
				$container.css('margin-left', 0);
			}

			var $items = $container.find('.project');

			if ($items.length > maxItems) {
				for(i=($items.length-1); i>(maxItems-1); i--) {
					$($items[i]).detach().appendTo($containerHidden);
				}
				
			} else {
				var $hidden_items = $containerHidden.find('.project');
				$hidden_items.width(itemWidth);
				
				var shortfall = maxItems - $items.length;
				n = Math.min($hidden_items.length, shortfall);
				
				for(i=n-1; i>=0; i--) {
					$($hidden_items[i]).detach().appendTo($container);
					shortfall--;
				}
			}
			
			$items.width(itemWidth);
		};
		
		var init = function() {
			var $this = $(this);
			var $container = $('.recent-works-list', $this);
			var $containerHidden = $('.recent-works-list-hidden', $this);
			
			initIsotope($container, $containerHidden);
			
			$window.load(function() {
				initIsotope($container, $containerHidden);
			}).resize(function() {
				initIsotope($container, $containerHidden);
			});
			
			$('.sort-panel .filter a', $this).click(function (){
				var selector = $(this).attr('data-filter');
				$(this).parent().parent().find('> li.active').removeClass('active');
				$(this).parent().addClass('active');
				
				$container.isotope({
					filter : selector 
				});

				return false;
			});
		};
		
		return this.each(init);
	};
})(jQuery);
