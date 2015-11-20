<?php
/**
 * Post type Icon
 */
if (has_post_format('video')) {
	# Video
	echo '<i class="icon-camera-symbol-3"></i>';
} elseif (has_post_format('audio')) {
	# Audio
	echo '<i class="icon-volume-medium-1"></i>';
} elseif (has_post_format('gallery')) {
	# Gallery
	echo '<i class="icon-camera-front"></i>';	
} elseif (has_post_format('quote')) {
	# Quote
	echo '<i class="icon-bubble-quote-1"></i>';	
} else {
	# Default
	echo '<i class="icon-align-left"></i>';
}
