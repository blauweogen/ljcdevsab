<?php
/**
 * Author Box
 *
 * Displays author box with author description and thumbnail on single posts
 *
 * @package WordPress
 * @subpackage OneTouch theme, for WordPress
 * @since OneTouch theme 1.9
 */
?>

<?php
$author_info = get_the_author_meta('dfd_author_info');
?>

<div class="about-author">
    <div class="author-top-box">
	    <figure class="author-photo">
		    <?php echo get_avatar( get_the_author_meta('ID') , 80 ); ?>
	    </figure>
	    <div class="author-top-inner">
		    <h3 class="widget-title">
			    <?php 
					global $authordata;
					if ( is_object( $authordata ) ) {
						echo ($authordata->display_name) ? $authordata->display_name : $authordata->user_nicename;

						$read_more = DFD_HTML::read_more(get_author_posts_url($authordata->ID), __('Read all author posts', 'dfd'));
					}
			    ?>
		    </h3>
				<?php if (!empty($author_info)): ?>
					<h4 class="widget-sub-title"><?php echo $author_info; ?></h4>
				<?php endif; 
				if (!empty($read_more))
					echo $read_more;
				?>
	    </div>
    </div>
    <div class="clearfix">
        <div class="author-description">
            <p><?php the_author_meta('description'); ?></p>

			<?php echo author_social_networks(); ?>
        </div>
    </div>
</div>