<?php
	/**
	*
	* @author king-theme.com
	*
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
get_header();
	
?>
<div class="clearfix"></div>
<div class="page_title1">
	<div class="container">
	    <h1><?php _e( 'History Timeline', KING_DOMAIN ); ?></h1>
	    <div class="pagenation">
	    	&nbsp;<a href="<?php echo SITE_URI; ?>"><?php _e('Home', KING_DOMAIN ); ?></a> 
	    	<i>/</i> <?php _e( 'History Timeline', KING_DOMAIN ); ?>
	    </div>
	</div>
</div>
<div class="clearfix"></div>
<div class="content_fullwidth less featured_section121 blog-timeline">
	<div class="features_sec65">
		<div class="container no-touch">
			<div id="cd-timeline" class="cd-container">
				<?php king_ajax_loadPostsTimeline(); ?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php get_footer(); ?>   