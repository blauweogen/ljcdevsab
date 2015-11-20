<?php
	/**
	*
	* @author king-theme.com
	*
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	global $post, $more, $king;

	function king_masonry_assets() {
		wp_enqueue_style('king-masonry');
		wp_enqueue_script('king-masonry');
	}
	add_action('wp_print_styles', 'king_masonry_assets');

	get_header();
	
?>

<?php  $king->breadcrumb(); ?>

<div id="primary" class="container">
	<div id="grid-container" class="cbp-l-grid-masonry"> 
		 <ul>
		        
		<?php 
		
			while ( have_posts() ) : the_post();
			
				$height = 1030;
				$cap = 'two';
				$heighClass = ' cbp-l-grid-masonry-height4';
				if( rand(0,10) >= 5 ){
					$height = 734;
					$heighClass = ' cbp-l-grid-masonry-height3';
					$cap = 'three';
				}
		
		 ?>
		        
			    <li class="cbp-item<?php echo esc_attr( $heighClass ); ?>">
			        <a href="<?php echo get_permalink( $post->ID ); ?>" class="cbp-caption">
			            <div class="cbp-caption-defaultWrap two">
			            <?php
			                
							$img = $king->get_featured_image( $post, true );
							if( !empty( $img ) )
							{
								if( strpos( $img , 'youtube') !== false )
								{
									$img = THEME_URI.'/assets/images/default.jpg';
								}
								$img = king_createLinkImage( $img, '570x'.$height.'xc' );
									
								echo '<img alt="'.get_the_title().'" class="featured-image" src="'.$img.'" />';
							}
		
						?> 
			            </div>
			            <div class="cbp-caption-activeWrap <?php echo esc_attr( $cap ); ?>">
			                <div class="cbp-l-caption-alignCenter">
			                    <div class="cbp-l-caption-body">
			                        <div class="cbp-l-caption-title"><?php the_title(); ?></div>
			                        <div class="cbp-l-caption-desc">
			                        	<i class="fa fa-calendar"></i> 
			                        	<strong><?php echo esc_html( get_the_date('d F Y') ); ?></strong> 
			                        	<i class="fa fa-comment"></i> <?php echo esc_html( $post->comment_count ); ?> Comments
			                        </div>
			                    </div>
			                </div>
			            </div>
			        </a>
			    </li><!-- end item -->
		            
		<?php endwhile; ?>
		
		   </ul>
	  </div><!-- #grid-container -->
</div><!-- #primary -->

<?php get_footer(); ?>   