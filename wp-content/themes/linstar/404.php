<?php
/**
 #		(c) king-theme.com
 */
get_header(); ?>
	
<?php $king->breadcrumb(); ?>

<div id="primary" class="site-content">
	<div id="content" class="container">
	
		<div class="error_pagenotfound">
	    	
	        <strong><?php _e('404', KING_DOMAIN); ?></strong>
	        <br>
	    	<b><?php _e('Oops... Page Not Found!', KING_DOMAIN); ?></b>
	        
	        <em><?php _e('Sorry the Page Could not be Found here.', KING_DOMAIN); ?></em>
	
	        <p><?php _e('Try using the button below to go to main page of the site', KING_DOMAIN); ?></p>
	        
	        <div class="clearfix margin_top3"></div>
	    	
	        <a href="<?php echo SITE_URI; ?>" class="but_medium1">
	        	<i class="fa fa-arrow-circle-left fa-lg"></i>&nbsp; <?php _e('Go to Back', KING_DOMAIN); ?>
	        </a>

	    </div>
	    
	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>		    
