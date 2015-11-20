;
var screen_medium = 800;
(function ($, window, undefined) {
    'use strict';

    var $doc = $(document),
        Modernizr = window.Modernizr;
	
	$.fn.sbAccordion = function() {
		var settings = {
			speed: 300
		};
		
		return this.each(function(){
			var $accordion = $(this);
			var $lis = $accordion.children('li');
			
			$accordion.find('.title').click(function(){
				var $this = $(this);
				var $li = $this.parent('li');
				
				if ($li.hasClass('active')) {
					return false;
				}
				
				$this.siblings('.content').slideDown(settings.speed);
				$lis.filter('.active').removeClass('active')
					.children('.content').slideUp(settings.speed);
				$li.addClass('active');
				
				return false;
			});
		});
	};

    $(document).ready(function () {
		$('ul.accordion').sbAccordion();
        $.fn.foundationAlerts ? $doc.foundationAlerts() : null;
        $.fn.foundationButtons ? $doc.foundationButtons() : null;
//        $.fn.foundationAccordion ? $doc.foundationAccordion() : null;
        $.fn.foundationNavigation ? $doc.foundationNavigation() : null;
        $.fn.foundationTopBar ? $doc.foundationTopBar() : null;
        $.fn.foundationCustomForms ? $doc.foundationCustomForms() : null;
        $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
        $.fn.foundationTabs ? $doc.foundationTabs({callback: $.foundation.customForms.appendCustomMarkup}) : null;
        $.fn.foundationTooltips ? $doc.foundationTooltips() : null;
        $.fn.foundationMagellan ? $doc.foundationMagellan() : null;
        $.fn.foundationClearing ? $doc.foundationClearing() : null;

        $.fn.placeholder ? $('input, textarea').placeholder() : null;
    });

    // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
    if (Modernizr.touch && !window.location.hash) {
        $(window).load(function () {
            setTimeout(function () {
                window.scrollTo(0, 1);
            }, 0);
        });
    }
	
})(jQuery, this);


/*---------------------------------
 Correct OS & Browser Check
 -----------------------------------*/

var ua = navigator.userAgent,
    checker = {
        os: {
            iphone: ua.match(/iPhone/),
            ipod: ua.match(/iPod/),
            ipad: ua.match(/iPad/),
            blackberry: ua.match(/BlackBerry/),
            android: ua.match(/(Android|Linux armv6l|Linux armv7l)/),
            linux: ua.match(/Linux/),
            win: ua.match(/Windows/),
            mac: ua.match(/Macintosh/)
        },
        ua: {
            ie: ua.match(/MSIE/),
            ie6: ua.match(/MSIE 6.0/),
            ie7: ua.match(/MSIE 7.0/),
            ie8: ua.match(/MSIE 8.0/),
            ie9: ua.match(/MSIE 9.0/),
            ie10: ua.match(/MSIE 10.0/),
            opera: ua.match(/Opera/),
            firefox: ua.match(/Firefox/),
            chrome: ua.match(/Chrome/),
            safari: ua.match(/(Safari|BlackBerry)/)
        }
    };


/*---------------------------------
 Navigation dropdown
 -----------------------------------*/
(function ($) {
	$.bindMobileMenu = function() {
		var $mobileMenu = $('ul.menu-primary-navigation').clone();
		$mobileMenu
				.removeAttr('id')
			.find('ul, li, a').addBack()
				.removeAttr('class');
	
		$mobileMenu
				.attr('class', 'dl-menu')
			.find('ul')
				.attr('class', 'dl-submenu');
		
		$mobileMenu.find('.sub-nav').each(function(){
			$(this).after( $(this).html() ).remove();
		});
		
		$mobileMenu.children('li').each(function(){
			var $ul = $(this).find("ul.dl-submenu:first");
			var $els = $ul.siblings('ul.dl-submenu');
			
			if ($els.length===0) return true;
			
			var $lis = $els.children('li').detach()
			
			$lis.appendTo($ul);
			$els.remove();
			
			return true;
		});
		
		$('.dl-menuwrapper').each(function(){
			var $wrapper = $(this);
			
			$wrapper.append($mobileMenu);
			$wrapper.dlmenu({
				animationClasses: {
					classin : 'dl-animate-in-1',
					classout : 'dl-animate-out-1'
				}
			});
		});
	};
})(jQuery);

(function($){
	$.fn.dfdSocTooltips = function() {
		
		return this.each(function() {
			var $soc_icons = $(this);
			var className = $('.module-soc-icons').attr('class');

			$('a', $soc_icons).each(function(){
				var $this = $(this);
				
				// If already binded
				if ($this.next('span.soc-tooltip').length > 0) {
					return false;
				}
				
				var title = $this.attr('title');
				$this.removeAttr('title');

				if (title && title.length==0) {
					return true;
				}
				var $tooltip = $(['<span class="soc-tooltip">', title, '</span>'].join(''));
				
				if($this.parent().parent().attr('class') != className) {
					$tooltip.insertAfter($this);
					var w = $tooltip.width();
					$tooltip.css({
						'margin-left': -Math.floor(w/2),
					});
				}else{
					$this.append($tooltip);
					$tooltip.css({
						'margin-left': 0
					});
				}
			    
			}).hover(function(){
				if($(this).parent().parent().attr('class') != className) $(this).next('.soc-tooltip')
					.css({
						'left': $(this).offset().left - $(this).parent().offset().left
					});
			});
		});
		
	};
})(jQuery);

(function($){
	$.fn.dfdClientsTooltips = function() {
		
		return this.each(function() {
			var $clients = $(this);

			$($clients).mousemove(function(event){
				$(this).next('.clients-tooltip')
					.css({
						"opacity" : 1,
						"display" : "block",
						"z-index" : 3,
						"top" : event.pageY - $(this).parent().offset().top + 25,
						"left" : event.pageX - $(this).parent().offset().left + 15
					});
			}).mouseout(function(){
				$(this).next('.clients-tooltip')
					.css({
						"display" : "none",
						"opacity" : 0,
						"z-index" : -3,
						"top" : 0,
						"left" : 0
					});
			});
		});
		
	};
})(jQuery);

(function($){
	/* Pricing table columns width */
	$.fn.pricingTableEqColumns = function() {
		var $columns = $(this);
		var width = (100 / $columns.length);
		$columns.css('width', width+'%');
		
		return this;
	};
})(jQuery);

(function($){
	// Cache the Window object
	var $window = $(window), windowScrollTop, windowHeight, windowWidth;
	
	var recalcWindowOffset = function() {
		windowScrollTop = $window.scrollTop();
	};

	var recalcWindowInitHeight = function() {
		windowHeight = $window.height();
		windowWidth = $window.width();

		recalcWindowOffset();
	};

	recalcWindowOffset();
	$window
			.on("resize load", recalcWindowInitHeight)
			.on("scroll", recalcWindowOffset);
	
	var $top_menu, $header_container, $header, fixed_height, $logotype;
	var crum_drop_menu, dfd_hide_drop_menu;
	
	$.loadRetinaLogo = function() {
		if (('devicePixelRatio' in window) && (window.devicePixelRatio > 1)) {
			$('#logo img').each(function(){
				var $logo = $(this);
				var retina_src = $logo.attr('data-retina');

				if (!retina_src || retina_src.legth===0) {
					return;
				}

				var w = $logo.attr('data-retina_w');
				var h = $logo.attr('data-retina_h');

				var max = {w: 164, h: 104};

				$logo.attr('src', retina_src);

				if (w<max.w && h<max.h) {
					$logo.css({
						width: Math.round(w/2) + 'px',
						height: Math.round(h/2) + 'px'
					});
				}
			});
		}
	};
	
	$.bindHeaderEvents = function() {
		$top_menu = jQuery('.header-wrap');
		$header_container = jQuery('#header-container');
		$header = jQuery('#header');
		fixed_height = $('.fixed', $header).height();
		$logotype = $('#logo').find('img');
		var hcH = $header_container.outerHeight();
		
		if (window.Modernizr.touch && !window.location.hash) {
			hcH += 20;
		}

		dfd_hide_drop_menu = function(row_fullheight) {
			if (!$top_menu.hasClass('fixed')) {
				return;
			}

			$top_menu.removeClass('fixed');
			$header.css('height', 'auto');
			if (row_fullheight != undefined && row_fullheight === true) {
				$top_menu.addClass('header-hide');
			}
			
			if (typeof $.hideShowMenuItems === 'function' && typeof $.runMegaMenu === 'function') {
				$.hideShowMenuItems();
			}
		};

		crum_drop_menu = function(row_fullheight) {
			if (row_fullheight != undefined && row_fullheight === true && windowHeight != undefined) {
				hcH = windowHeight - 100;
			}
			
			if (windowScrollTop > hcH) {
				if ($top_menu.hasClass('fixed')) {
					return;
				}

				var hH = $header.height();
				$header.css('height', hH);
				$top_menu.addClass('fixed');
				if (row_fullheight != undefined && row_fullheight === true) {
					$top_menu.removeClass('header-hide');
				}
				$logotype.css('height', fixed_height)
				
				if (typeof $.hideShowMenuItems === 'function' && typeof $.runMegaMenu === 'function') {
					$.hideShowMenuItems();
				}
			} else {		
				dfd_hide_drop_menu(row_fullheight);
			}
		};

		/*---------------------------------
			Mega Menu (if enabled)
		-----------------------------------*/
		if (typeof $.cloneMenuItems === 'function') {
			$.cloneMenuItems();
		}
		
		if (typeof $.hideShowMenuItems === 'function') {
			$.hideShowMenuItems();
		}
		
		if (typeof $.runMegaMenu === 'function') {
			$.runMegaMenu();
		}

		/*---------------------------------
			Lang drop-down
		-----------------------------------*/
		$('.lang-sel').unbind('hover').hover(function(){
			jQuery(this).addClass("hovered");
		}, function(){
			jQuery(this).removeClass("hovered");
		});

		/*---------------------------------
			Menu animation
		-----------------------------------*/
		jQuery(".nav-item.has-submenu > a span").on('click', function() {
			var parent = $(this).parent();
			if (parent.attr('href') != '#' && parent.attr('href') != '' && parent.hasClass('open')) {
				window.location.href = parent.attr('href');
			}
			
			return false;
		});
		
		jQuery(".nav-item.has-submenu > a").on('click', function(){
			if($window.width()>screen_medium) return true;

			var $this = $(this).parent();

			if ($this.hasClass("hovered")) {
				$this
					.removeClass("hovered")
					.find(".sub-nav").stop().slideUp(200);
			} else {
				if ($this.siblings().length>0) {
				$this.siblings('.hovered')
					.removeClass("hovered")
					.find(".sub-nav").stop().slideUp(200);
				}

				$this
					.addClass("hovered")
					.find(".sub-nav").stop().slideDown(200);
			}

			return false;
		});

		$('.top-menu-button').on('click', function(){
			var $this = $(this);
			var $menu = $($this.attr('data-href')).parent('.mega-menu');

			$menu.slideToggle(200, function(){
				if ($menu.is(':visible')) {
					$this.removeClass("inactive");
				} else {
					$this.addClass("inactive");
				}
			});

			return false;
		});

		/*---------------------------------
			Search Form
		-----------------------------------*/
		(function(){
			var search_show = function($this) {
				var $search = $this.find('.search-query');
				var $button = $('#searchsubmit');

				$button.attr('disabled', true);

				if ($search.is(':focus')) {
					return;
				}
				$('.form-search', $this).addClass('open');
				if (!$search.attr('data-width')) {
					$search.attr('data-width', parseInt($search.css('width')));
				}
				var search_width = parseInt($search.attr('data-width'));

				$search.stop()
					.css({
						width: 0
					})
					.show()
					.animate({
						width: search_width
					}, function() {
						$button.attr('disabled', false);
					});
			};

			var search_hide = function($this, hide) {
				var $search = $this.find('.search-query');
				var $button = $('#searchsubmit');

				if (hide !== true) {
					$button.attr('disabled', true);
				}

				if ($search.is(':focus') && hide == undefined) {
					return;
				}
				if (!$search.attr('data-width')) {
					$search.attr('data-width', parseInt($search.css('width')));
				}
				$('.form-search', $this).removeClass('open');
				var search_width = parseInt($search.attr('data-width'));

				$search.stop()
					.css({
						width: search_width
					})
					.animate({
						width: 0
					}, function() {
						$(this).hide();
						$button.attr('disabled', false);
					});
			};

			$('.form-search-wrap .search-query').unbind('blur').blur(function(){
				search_hide($(this).parents('.form-search-wrap'), true);
			});

			if (Modernizr.touch === false) {
				$('.form-search-wrap')
					.unbind('hover').hover(function(){
						search_show($(this));
					}, function(){
						search_hide($(this));
					});
			} else {
				$('#searchsubmit').unbind('click').on('click touchend', function(){
					if (!$('.form-search-wrap .search-query').is(':visible')) {
						search_show($(this).parents('.form-search-wrap'));
						return false;
					}
				});
			}
		})(jQuery);

		$.loadRetinaLogo();

		/*---------------------------------
		 Bind Mobile Menu
		 -----------------------------------*/
		$.bindMobileMenu();
	};
	
	jQuery(document).ready(function($) {
		$.bindHeaderEvents();
		
		// Bind Soc Tooltips
		$('.soc-icons').dfdSocTooltips();
		$('.client-tile').dfdClientsTooltips();

		$window.on("resize", function () {
			var $tiled_menu = $('.mega-menu, .sub-nav', '#header');
			if (windowWidth >= screen_medium) {
				$tiled_menu.each(function(){
					if (!$(this).is(':visible')) {
						$(this).removeAttr('style');
					}
				});
			}
		});

		//@TODO: Remove copypaste (mvb main.js)
		var row_fullheight = ($('.mvb_container .row-wrapper:first').hasClass('mvb-row-fullheight')) ? true : false;
		$window.on("load resize scroll", function () {
			if (windowWidth >= screen_medium) {
				crum_drop_menu(row_fullheight);
			} else {
				dfd_hide_drop_menu(row_fullheight);
			}
		});
		
		/*---------------------------------
		 Scroll To Top
		 -----------------------------------*/
		var $back_to_top = jQuery('.body-back-to-top');
		$window.on('scroll', function() {
			if ($back_to_top.length>0) {
				if(windowScrollTop > 80) {
					$back_to_top.stop().animate({bottom: 10, opacity: 1}, 300);
				} else {
					$back_to_top.stop().animate({bottom: -40, opacity: 0}, 300);
				}
			}
		});

		var duration = 800;
		jQuery('.back-to-top, .body-back-to-top').click(function (event) {
			event.preventDefault();
			jQuery('html, body').animate({scrollTop: 0}, duration);
			return false;
		});

		/*
		 * MVB: Facts
		 */
		$('.fact-num').not('.circle').each(function(){
			var $this = $(this);
			var eq = function() {
				var diff = $this.find('.val').width() + 20;
				$this.find('.line').css('left', diff);
			};

			eq(); $this.bind('dfd-update', eq);
		});

		$('.fact-num.circle').each(function(){
			var $this = $(this);
			var eq = function() {
				var diff = $this.find('canvas').width() + 20;
				$this.find('.line').css('left', diff);
			};

			eq();  $this.bind('dfd-update', eq);
		});

		/* Pricing table columns width */
		$('.pricetable-column').pricingTableEqColumns();

		/*---------------------------------
		 Zoom images
		 -----------------------------------*/
		jQuery('.entry-content a').has('img').addClass('prettyPhoto');

		jQuery('.entry-content a img').click(function () {
			var desc = jQuery(this).attr('title');
			jQuery('.entry-content a').has('img').attr('title', desc);
		});

		jQuery("a[data-rel^='prettyPhoto'], a.zoom-link, a.thumbnail, a[class^='prettyPhoto']").prettyPhoto({hook: 'data-rel'});
		jQuery("a[rel^='prettyPhoto']").prettyPhoto(); // 
	});
	
	$(window).resize(function() {
		if (typeof $.hideShowMenuItems === 'function' && typeof $.runMegaMenu === 'function') {
			$.hideShowMenuItems();
		}
	});
	
	$.fn.dfdParallax = function() {
		return this.each(function() {
			// Store some variables based on where we are
			var $self = $(this), offsetCoords, topOffset, selfHeight;
			
			var recalcInitValues = function() {
				offsetCoords = $self.offset();
				selfHeight = $self.height();
				topOffset = offsetCoords.top;
			};
			
			recalcInitValues();
			$window.on("resize load", recalcInitValues);
			
			$self.addClass('row-wrapper-animate-background');
			
			// When the window is scrolled...
			$window.on("scroll", function() {
				// If this section is in view
				if (
						((windowScrollTop + windowHeight) > (topOffset)) &&
						((topOffset + selfHeight) > windowScrollTop)
				) {
					// Scroll the background at var speed
					// the yPos is a negative value because we're scrolling it UP!
					var diff = (topOffset - windowScrollTop) / 3;
					var speed = parseFloat($self.data('speed'));
					var yPos = -(diff / speed);

					// If this element has a Y offset then add it on
					if ($self.data('offsetY')) {
						yPos += $self.data('offsetY');
					}

					// Put together our final background position
					var coords = '50% ' + yPos + 'px';

					// Move the background
					$self.css({backgroundPosition: coords});

					// Check for other sprites in this section
					$('[data-type="sprite"]', $self).each(function() {

						// Cache the sprite
						var $sprite = $(this);

						// Use the same calculation to work out how far to scroll the sprite
						var yPos = -(windowScrollTop / $sprite.data('speed'));
						var coords = $sprite.data('Xposition') + ' ' + (yPos + $sprite.data('offsetY')) + 'px';

						$sprite.css({backgroundPosition: coords});

					}); // sprites

					// Check for any Videos that need scrolling
					$('[data-type="video"]', $self).each(function() {

						// Cache the video
						var $video = $(this);

						// There's some repetition going on here, so
						// feel free to tidy this section up.
						var yPos = -(windowScrollTop / $video.data('speed'));
						var coords = (yPos + $video.data('offsetY')) + 'px';

						$video.css({top: coords});

					}); // video

				}; // in view

			}); // window scroll

		});
	};

	$(document).ready(function(){
		// Cache the Y offset and the speed of each sprite
		$('[data-type]').each(function() {
			$(this).data('offsetY', parseInt($(this).attr('data-offsetY')));
			$(this).data('Xposition', $(this).attr('data-Xposition'));
			$(this).data('speed', $(this).attr('data-speed'));
		});

		// For each element that has a data-type attribute
		$('section[data-type="background"]').dfdParallax();
	});

	var eqHeightInit = function() {
		var w = jQuery(window).width();
		$('.features_module-eq-height-simple .row').each(function(){
			if (w>800) {
				$(this).find('.feature-box-wrap, .columns').equalHeights();
			} else {
				$(this).find('.feature-box-wrap, .columns').equalHeightsDestroy();
			}
		});
	};
	
	$window.on("load resize", eqHeightInit);
	
	$window.load(function () {
		$('.features_module-eq-height .row').each(function(){
			$.equalHeightsAdvanced({
				container: '.features_module-eq-height .row',
				cell: '.columns-with-border',
				equalHeight: false
			});

			$(this).find('.columns-with-border .feature-box').equalHeights();
		});
	});

})(jQuery);

/*---------------------------------
 Custom share buttons
 -----------------------------------*/
jQuery(document).ready(function ($) {   
    var  $share_container = jQuery('.entry-share-popup');

    if (jQuery($share_container).length  > 0) {
		jQuery('.entry-share > a').each(function(){
			var $label = $(this).find('.entry-share-label');
			
			$(this).click(function(){
				var $popup = $(this).siblings('.entry-share-popup');
				
				if ($popup.is(':visible')) {
					$popup.animate({width: 0}, 200, function(){
						$(this).hide();
						$label.show();
					});
				} else {
					$label.hide();
					$popup.show().animate({width: 130}, 200);
				}

				return false;
			});
		});

        jQuery('.entry-share-link-facebook', $share_container).sharrre({
            share: {
                facebook: true
            },
            template: '<a href="#"><i class="soc_icon-facebook"></i></a>',
            enableHover: false,
			enableCounter: false,
            urlCurl: $share_container.data('directory') + '/inc' + '/sharrre.php',

            click: function (api, options) {
                api.simulateClick();
                api.openPopup('facebook');
            }
        });


        jQuery('.entry-share-link-twitter', $share_container).sharrre({
            share: {
                twitter: true
            },
            template: '<a href="#" class="twitter"><i class="soc_icon-twitter-3"></i></a>',
            enableHover: false,
			enableCounter: false,
            urlCurl: $share_container.data('directory') + '/inc' + '/sharrre.php',
            click: function (api, options) {
                api.simulateClick();
                api.openPopup('twitter');
            }
        });



        jQuery('.entry-share-link-googleplus', $share_container).sharrre({
            share: {
                googlePlus: true
            },
            template: '<a href="#"><i class="soc_icon-google__x2B_"></i></a>',
            enableHover: false,
			enableCounter: false,
            urlCurl: $share_container.data('directory') + '/inc' + '/sharrre.php',

            click: function (api, options) {
                api.simulateClick();
                api.openPopup('googlePlus');
            }
        });

        jQuery('.entry-share-link-pinterest', $share_container).sharrre({
            share: {
                pinterest: true
            },
			buttons: { 
				pinterest: {
					url: jQuery('.entry-share-link-pinterest', $share_container).attr("data-url"), 
					media: jQuery('.entry-share-link-pinterest', $share_container).attr("data-media"), 
				}
			},
            template: '<a href="#"><i class="soc_icon-pinterest"></i></a>',//<span class="total">{total}</span>
            enableHover: false,
			enableCounter: false,
            urlCurl: $share_container.data('directory') + '/inc' + '/sharrre.php',

            click: function (api, options) {
                api.simulateClick();
                api.openPopup('pinterest');
            }
        });

    }

	/* MVB: Fan facts animation */
	$('.fact-num:not(.circle) .val.call-on-waypoint').each(function() {
		var $number = $(this);
		var start = $number.attr('data-start');
		var end = $number.attr('data-end');
		var speed = parseInt($number.attr('data-speed'));
		
		$number.on('on-waypoin', function () {	
			$({value: start}).animate({value: end}, {
					duration: speed,
					easing: 'linear',
					step: function() {
						$number.text(Math.floor(this.value)).trigger('dfd-update');
					},
					complete: function() {
						$number.text(Math.floor(this.value)).trigger('dfd-update');
					}
				});
		});

	});
	
	$('.fact-num.circle.call-on-waypoint').each(function() {
		if ($(window).width() <= screen_medium) return false;
		
		var $number = $(this);
		var start = $number.attr('data-start');
		var end = $number.attr('data-end');
		var speed = parseInt($number.attr('data-speed'));
		
		var $input = $number.find($number.attr('data-knob'));
		$input.val(Math.ceil(start)).trigger('change');
		
		$number.on('on-waypoin', function () {	
			$({value: start}).animate({value: end}, {
				duration: speed,
				easing: 'swing',
				step: function() {
					$input.val(Math.ceil(this.value)).trigger('change');
				},
				complete: function() {
					$input.val(Math.ceil(this.value)).trigger('change');
				}
			});
		});

	});

}); // document ready

/*---------------------------------
 Video controls
 -----------------------------------*/
(function($){
	
	$(document).ready(function() {
		$('.row-video-controls a.video').click(function(e) {
			var $this = $(this);
			var video = $(this).parents('section.row-wrapper').find('.row-video-container > video').get(0);
			
			if (video.paused) {
				video.play();
				$this.removeClass('video-off').addClass('video-on');
			} else {
				video.pause();
				$this.removeClass('video-on').addClass('video-off');
			}
			
			e.preventDefault();
		});
		
		$('.row-video-controls a.sound').click(function(e) {
			var $this = $(this);
			var video = $(this).parents('section.row-wrapper').find('.row-video-container > video').get(0);
			
			if (video.muted) {
				video.muted = false;
				$this.removeClass('sound-off').addClass('sound-on');
			} else {
				video.muted = true;
				$this.removeClass('sound-on').addClass('sound-off');
			}
			
			e.preventDefault();
		});
	});
	
})(jQuery);
// end video controls

/*---------------------------------
 Portfolio hide categories
 -----------------------------------*/
(function($){
	
	var hide_show_isotope_category = function (item_container, scan_hidden, new_item) {
		var $filter_item = (scan_hidden != undefined && scan_hidden === true) ? $('.sort-panel a:hidden') : $('.sort-panel a');
		
		$filter_item.each(function() {
			var $this = $(this);
			var filter = ($this.data('filter') != undefined) ? $this.data('filter') : false;
			if (filter === false) {
				return true;
			}
			var filter_match = (new_item != undefined && typeof(new_item) === 'object' && scan_hidden === true) 
				? (new_item.is(filter)) ? 1 : 0 
				: $(filter).length;
			
			if (filter_match == 0) {
				$this.hide();
			} else if (filter_match > 0 && $this.is(':hidden')) {
				$this.show();
			}
		});
	};
	
	$(document).ready(function() {
		hide_show_isotope_category('div.works-list');
		
		$('body').bind('isotope-add-item', function(e, item) {
			hide_show_isotope_category('div.works-list', true, $(item));
		});
	});
	
})(jQuery);


/*! Fluidvids v2.2.0 | (c) 2014 @toddmotto | github.com/toddmotto/fluidvids */
!function(a,b){"function"==typeof define&&define.amd?define(b):"object"==typeof exports?module.exports=b:a.fluidvids=b()}(this,function(){"use strict";var a={selector:"iframe",players:["www.youtube.com","player.vimeo.com"]},b=document.head||document.getElementsByTagName("head")[0],c=".fluidvids{width:100%;position:relative;}.fluidvids iframe{position:absolute;top:0px;left:0px;width:100%;height:100%;}",d=function(b){var c=new RegExp("^(https?:)?//(?:"+a.players.join("|")+").*$","i");return c.test(b)},e=function(a){if(!a.getAttribute("data-fluidvids")){var b=document.createElement("div"),c=parseInt(a.height?a.height:a.offsetHeight,10)/parseInt(a.width?a.width:a.offsetWidth,10)*100;a.parentNode.insertBefore(b,a),a.setAttribute("data-fluidvids","loaded"),b.className+="fluidvids",b.style.paddingTop=c+"%",b.appendChild(a)}},f=function(){var a=document.createElement("div");a.innerHTML="<p>x</p><style>"+c+"</style>",b.appendChild(a.childNodes[1])};return a.apply=function(){for(var b=document.querySelectorAll(a.selector),c=0;c<b.length;c++){var f=b[c];d(f.src)&&e(f)}},a.init=function(b){for(var c in b)a[c]=b[c];a.apply(),f()},a});
(function($){ $(document).on('ready', function(){fluidvids.init({selector: 'iframe', players: ['www.youtube.com', 'player.vimeo.com']})}); })(jQuery);
