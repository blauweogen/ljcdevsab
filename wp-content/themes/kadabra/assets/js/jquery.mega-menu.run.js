(function($){
	var $window = $(window);
	$.runMegaMenu = function() {
		$("nav.mega-menu")
			.accessibleMegaMenu({
				/* prefix for generated unique id attributes, which are required 
				 to indicate aria-owns, aria-controls and aria-labelledby */
				uuidPrefix: "accessible-megamenu",
				/* css class used to define the megamenu styling */
				menuClass: "nav-menu",
				/* css class for a top-level navigation item in the megamenu */
				topNavItemClass: "nav-item",
				/* css class for a megamenu panel */
				panelClass: "sub-nav",
				/* css class for a group of items within a megamenu panel */
				panelGroupClass: "sub-nav-group",
				/* css class for the hover state */
				hoverClass: "hover",
				/* css class for the focus state */
				focusClass: "focus",
				/* css class for the open state */
				openClass: "open"
			})
			.on('megamenu:open', function(e, el) {
				if ($window.width() <= screen_medium) return false;
		
				var $menu = $(this),
					$el = $(el),
					$sub_nav;

				if ($el.is('.main-menu-link.open') && $el.siblings('div.sub-nav').length>0) {
					$sub_nav = $el.siblings('div.sub-nav')
				} else if ($el.is('div.sub-nav')) {
					$sub_nav = $el;
					$el = $sub_nav.siblings('.main-menu-link');
				} else {
					return true;
				}
					
				$sub_nav.removeAttr('style').removeClass('sub-nav-onecol');

				var w_width = $window.width();
				var sub_nav_width = $sub_nav.width();
				var sub_nav_offset = $el.offset().left - $menu.offset().left;
				var left = 0, offset;
				var test = $menu.parent().offset().left -  $menu.offset().left;

				if (sub_nav_width > w_width) {
					$sub_nav
						.css({
							'max-width': w_width
						})
						.addClass('sub-nav-onecol');

					sub_nav_width = $sub_nav.width();
				}


				offset =  Math.floor( (sub_nav_width - $el.width()) / 2 );
				left = sub_nav_offset - offset;
				if ( left < test ) left = test;

				$sub_nav.css('left', left);
				
			});
	};
	
	$.cloneMenuItems = function() {
		var main_mega_menu = $('#main_mega_menu');
		var more_menu_item = $('<li id="nav-menu-item-more" class="menu-item-more mega-menu-item nav-item menu-item-depth-0 has-submenu" style="display: none">\n\
			<a class="menu-link main-menu-link" href="#"><span class="item-title">More</span></a>\n\
			<div class="sub-nav">\n\
				<ul class="menu-depth-1 sub-menu sub-nav-group"></ul>\n\
			</div></li>');
		
		$('> ul > li', main_mega_menu).each(function() {
			$('> div.sub-nav > ul', more_menu_item).append($(this).clone().hide().removeClass('current-menu-parent current-menu-ancestor current-menu-item'));
		});
		
		
		var depth_reg = /(menu-item-|menu-)depth-(\d+)/;
		$('ul, li', more_menu_item).each(function() {
			var depth = depth_reg.exec($(this).attr('class'));
			if (depth != null) {
				var old_depth = depth[2];
				var new_depth = parseInt(old_depth) + 1;
				
				$(this).removeClass(depth[0]).addClass(depth[0].replace(old_depth, new_depth));
			}
		});
		
		// 2-й уровень
		$('> div.sub-nav > ul > li', more_menu_item).addClass('sub-nav-item').children('a').removeClass('main-menu-link').addClass('sub-menu-link');
		
		// 3-й уровень
		$('> div.sub-nav > ul > li > div.sub-nav > ul', more_menu_item).unwrap().removeClass('sub-menu sub-nav-group').addClass('sub-sub-menu');
		
		$('> ul', main_mega_menu).append(more_menu_item);
	}
	
	$.hideShowMenuItems = function() {
		var main_mega_menu = $('#main_mega_menu').parent('.header-col');
		var main_mena_menu_width = main_mega_menu.width();
		var items_width = 0;
		var nav_menu_item_more = $('#nav-menu-item-more');
		
		$('nav > ul > li', main_mega_menu).each(function() {
			var menu_item = $(this);
			
			if (menu_item.hasClass('menu-item-more')) {
				return false;
			}
			
			if (menu_item.is(':visible')) {
				items_width += menu_item.outerWidth(true);
			}
			
			if (items_width > main_mena_menu_width) {
				menu_item.prev().hide().end().hide();
				
				nav_menu_item_more.find('#'+menu_item.prev().attr('id')).show();
				nav_menu_item_more.find('#'+menu_item.attr('id')).show();
				
				if (!nav_menu_item_more.is(':visible')) {
					nav_menu_item_more.show();
				}
			} else {
				var first_hidden_menu_item = $('nav > ul > li:hidden:first', main_mega_menu);
				if (main_mena_menu_width - items_width > first_hidden_menu_item.outerWidth(true)) {
					first_hidden_menu_item.show();
					nav_menu_item_more.find('#'+first_hidden_menu_item.attr('id')).hide();
				}
			}
		});
		
		if ($('nav > ul > li.mega-menu-item:hidden', main_mega_menu).length === 0) {
			nav_menu_item_more.hide();
		}
	}
})(jQuery);