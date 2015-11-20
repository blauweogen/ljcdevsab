;
(function ($, window, undefined) {
    'use strict';
	
	var slide_parallax = {
		$container: null,
		$handler: null,
		$pointer: null,
		$image: {
			left: null,
			right: null,
			containers: null
		},
		size: {
			width: null,
			height: null,
			pointer_height: null,
		},
		_offset_x: 0.5,
		
		init: function(o) {
			this.$container = $(o);
			
			this.getObjects();
			this.init_sizes();
			this.bind();
			
			var tapp = this;
			
			$(window)
				.on("load resize", function(){
					tapp.init_sizes();
					tapp.reinit_offset_x();
				});
		},
		
		init_sizes: function() {
			this.resetSizes();
			this.getSizes();
			this.setSizes();
		},
		
		getObjects: function() {
			this.$handler = this.$container.find('.handler');
			this.$pointer = this.$handler.find('.pointer');
			this.$image.left = this.$container.find('.image-left img');
			this.$image.right = this.$container.find('.image-right img');
			this.$image.containers = this.$container.find('.image-left, .image-right');
		},
		
		resetSizes: function() {
			// Reset styles to auto
			this.$container
					.find('.image-left img, .image-right img') // ???? .image-left, .image-right, img
					.andSelf()
						.css({
							'width': 'auto',
							'height': 'auto'
						});
						
		},
		
		getSizes: function() {
			this.size.pointer_height = this.$pointer.height();

			var container_w = this.$container.width();
			var left_image_w = this.$image.left.width();
			var left_image_h = this.$image.left.height();

			var right_image_w = this.$image.right.width();
			var right_image_h = this.$image.right.height();

			this.size.width = Math.min(left_image_w, right_image_w, container_w);

			var new_left_image_h = Math.floor(this.size.width*left_image_h / left_image_w);
			var new_right_image_h = Math.floor(this.size.width*right_image_h / right_image_w);

			this.size.height = Math.min(new_left_image_h, new_right_image_h);
		},
		
		setSizes: function() {
			this.$container.find('img')
					.css({
						display: 'block',
						position: 'absolute',
						top: '0'
					})
				.andSelf()
					.css({
						width: this.size.width,
						height: this.size.height
					});
					
			var half_width = Math.round(this.size.width / 2);
			
			this.$image.containers.css({
				width: half_width,
				height: this.size.height
			});
		},
		
		bind: function() {
			var tapp = this;
			
			this.$container
					.unbind('mousedown touchstart')
					.bind('mousedown touchstart', function(e) {
						tapp.update_position(e);

						$(document).bind('mousemove touchmove', function(e) {
							tapp.update_position(e);
						});
					});
			
			this.$container
					.unbind('mouseup touchend')
					.bind('mouseup touchend', function() {
						$(document).unbind('mousemove touchmove');
					});
		},
		
		update_position: function(e) {
			var vector = this._cursor_position(e);

			this._update_offset_y(vector.y);
			this._update_offset_x(vector.x);
		},
		_update_offset_y: function(y) {
			this.$pointer.css('top', y);
		},
		_update_offset_x: function(x) {
			this.$handler.css('left', x);
			this.$image.left.parent().css('width', x);
			this.$image.right.parent().css('width', this.size.width - x);
			
			this._offset_x = x / this.size.width;
		},
		
		reinit_offset_x: function() {			
			this._update_offset_x(Math.floor(this._offset_x * this.size.width));
		},
		
		_cursor_position: function(e) {
			var vector = {x: null, y: null};
			var event;
			
			if (e.type == 'touchmove') {
				e.stopImmediatePropagation();
				event = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
			} else {
				event = e;
			}
			
			vector.x = event.pageX - this.$container.offset().left;
			vector.y = event.pageY - this.$handler.offset().top;
			
			var setY = null;
			var minY = 0;
			var maxY = (this.$handler.height() - this.size.pointer_height) - 4;
			
			if ( (vector.y > minY) && (vector.y < maxY) ) {
				setY = vector.y;
			} else if (vector.y <= minY) {
				setY = minY;
			} else if (vector.y >= maxY) {
				setY = maxY;
			}
			
			var setX = null;
			var minX = parseInt(this.$handler.width() / 2) + 1;
			var maxX = this.$container.width() - parseInt(this.$handler.width() / 2);
			
			if (vector.x > minX && vector.x < maxX) {
				setX = vector.x;
			} else if (vector.x <= minX) {
				setX = minX;
			} else if (vector.x >= maxX) {
				setX = maxX;
			}
			
			return {x: setX, y: setY};
		}
	};
	
	$.fn.slideParallax = function() {
		return this.each(function() {
			slide_parallax.init(this);
		});
	};
})(jQuery, window);
