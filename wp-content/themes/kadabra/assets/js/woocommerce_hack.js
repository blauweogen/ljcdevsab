var add_to_cart_button;

(function($){
	var supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );
	if (supports_html5_storage) { sessionStorage.clear(); }
	
    $.fn.sbWooScaleImage = function(options) {
		var settings = $.extend({
			factor: 1.1,
			duration: 200
		}, options);

		return this.each(function() {
			var $this = $(this);
			var init = {
				width: $this.width(),
				'max-width': $this.css('max-width')
			};
			
			$this.css({
					'position': 'relative',
					'max-width': init.width
				});
			$this.parents('.woo-entry-thumb').hover(
					function() {
						$this.stop().animate({
							top: -(init.width * ((settings.factor - 1) / 2)),
							left: -(init.width * ((settings.factor - 1) / 2)),
							width: init.width * settings.factor,
							'max-width': init.width * settings.factor
						}, settings.duration);
					}, function() {
						$this.stop().animate({
							top: 0,
							left: 0,
							width: init.width,
							'max-width': init.width
						}, settings.duration);
					}
				);
		});
	};
	
	$(document).ready(function(){
		jQuery('ul.products.row>li').equalHeights();
		
		// Wrap All
		$('.prod-image-wrap.woo-entry-thumb').each(function(){
			var $this = $(this);
			var $wrap = $('<div class="entry-thumb-hover-wrap"></div>').css({
				width: $this.find('img').width()
			});
			
			$this.find('img, a').wrapAll($wrap);
			$this.css({
				width: $this.find('img').width()
			});
		});
		
		if ($('.ul-dropdown-toggle').length>0)
			$('.ul-dropdown-toggle').dropdown();
		if ($('.variations .value select').length>0)
			$('.variations .value select').dropkick();
		
		if (!('ontouchstart' in window)) {
			$('.products-slider ul.products .product').hover(function() {
				var $parent = $(this).parents('.products-slider');

				$parent.css({
					'padding-bottom': '160px',
					'margin-bottom': '-160px'
				});
			}, function() {
				var $parent = $(this).parents('.products-slider');

				$parent.css({
					'padding-bottom': '0',
					'margin-bottom': '0'
				});
			});
		}

		var prod_wrap_slideDown = function($el) {
			var h = Math.round($el.find('.woo-entry-thumb').outerHeight());
			h += Math.round($el.find('h3').outerHeight());

			var $produt_subtitle = $el.find('.produt-subtitle');

			if ($produt_subtitle.length > 0) {
				h += Math.round($produt_subtitle.outerHeight());
			}

			h += Math.round($el.find('.price').outerHeight());

			var $star_rating = $el.find('.star-rating')
			if ($star_rating.length > 0) {
				h += Math.round($star_rating.outerHeight());
			}

			h += Math.round($el.find('.add-info').outerHeight());

			$el.css({
					height: (50 + h) + 'px'
				})
				.parent().addClass('open');
		};
		
		var prod_wrap_slideUp = function($el) {
			$el.css({
					height: '100%'
				})
				.parent().removeClass('open');
		};
		
		if (!!('ontouchstart' in window)) {
			$('.prod-wrap').bind('touchmove click', function(event) {

				if(event.type == 'touchmove') {
					return true;
				}
								
				if ($(event.target).is('a, i')) {
					return true;
				}
				
				var $li = $(this).parents('.product');
				
				if ($(this).parent().hasClass('open')) {
					prod_wrap_slideUp($(this));
					
					if ($li.length > 0) {
						$li.parents('.products-slider').css({
							'padding-bottom': '0',
							'margin-bottom': '0'
						});
					}
				} else {
					$(this).parent().siblings().find('.prod-wrap')
								.css('height', '100%')
								.parent().removeClass('open');
					prod_wrap_slideDown($(this));
					
					
					if ($li.length > 0) {
						$li.parents('.products-slider').css({
							'padding-bottom': '160px',
							'margin-bottom': '-160px'
						});
					}
				}
			});
		} else {
			$('.prod-wrap')
				.on('mouseover ', function(){ prod_wrap_slideDown($(this)); })
				.on('mouseout', function(){ prod_wrap_slideUp($(this)); });
		}
		
		$('body').on('adding_to_cart', function(trigger, button) {
			add_to_cart_button = button;
		});
		
		$('body').on('added_to_cart', function (trigger) {
			if (add_to_cart_button != undefined) {
				var $woo_entry_thumb = $(add_to_cart_button).parents('li.product').find('div.woo-entry-thumb');
				var $added_to_cart_notice = $('<div class="added-to-cart-notice moon-checkmark">Added to cart</div>');
				
				if ($woo_entry_thumb.length > 0) {
					$woo_entry_thumb.append($added_to_cart_notice);
					$added_to_cart_notice.stop().animate({opacity: 1}, 800).delay( 1800 ).animate({opacity: 0}, 800, function() {$(this).remove()});
				}
				add_to_cart_button = null;
			}
		});
		
		(function(){
			var woo_get_products_class = function($this, init_class) {
				var w = $this.width();
				var el_class;
				switch(true) {
					case (w < 860): el_class = 'six'; break;
					default: el_class = init_class;
				}
				
				return el_class;
			};
			
			$('.woocommerce > ul.products.row').each(function(){
				var $this = $(this);
				var $els = $this.children('li.columns');
				if ($els.length == 0) {
					return true;
				}
				
				var init_class = 'four';
				if ($els.hasClass('three')) {
					init_class = 'three'
				}
				
				var woo_resize_products = function() {
					var c = woo_get_products_class($this, init_class);
					$els.removeClass('three four six').addClass(c);
				};
				
				$(window).on('load resize', woo_resize_products);
			});
			var quantity = $('form.cart .quantity');
			var inputNumber, min, max;
			if(quantity.length > 0) {
				quantity.prepend('<input type="button" value="-" class="minus">').append('<input type="button" value="+" class="plus">');
				quantity.find('.minus').on('click touchend', function() {
					inputNumber = $(this).siblings('.qty');
					min = inputNumber.attr('min');
					max = inputNumber.attr('max');
					var beforeVal = +inputNumber.val();
					var newVal = (beforeVal > min || !min) ? +beforeVal - 1 : min;
					inputNumber.val(newVal);
				});
				quantity.find('.plus').on('click touchend', function() {
					inputNumber = $(this).siblings('.qty');
					min = inputNumber.attr('min');
					max = inputNumber.attr('max');
					var beforeVal = +inputNumber.val();
					var newVal = (beforeVal < max || !max) ? +beforeVal + 1 : max;
					inputNumber.val(newVal);
				});
			}
		})();
	});
	
	$(window).load(function(){
		$('.woo-entry-thumb img').sbWooScaleImage();
	});
})(jQuery);
