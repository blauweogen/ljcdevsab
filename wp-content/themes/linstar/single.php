<?php
/**
 * (c) king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $king;

get_header();

?>

	<?php $king->breadcrumb(); ?>

	<div id="primary" class="site-content container-content content ">
		<div id="content" class="row row-content container">
			<div class="col-md-9">
			
			<?php			

				while ( have_posts() ) : the_post(); 
					
					include THEME_PATH.DS.'content.php'; 
					
					if( $king->cfg['showShareBox'] == 1 ){
					
					$link =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$escaped_link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
					
					?>
					
					<div class="sharepost">
					    <h4><?php _e('Share this Post',KING_DOMAIN); ?></h4>
					    <ul>
					    <?php if( $king->cfg['showShareFacebook'] == 1 ){ ?>
					      <li>
					      	<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $escaped_link ); ?>">
					      		&nbsp;<i class="fa fa-facebook fa-lg"></i>&nbsp;
					      	</a>
					      </li>
					      <?php } ?>
					      <?php if( $king->cfg['showShareTwitter'] == 1 ){ ?>
					      <li>
					      	<a href="https://twitter.com/home?status=<?php echo esc_url( $escaped_link ); ?>">
					      		<i class="fa fa-twitter fa-lg"></i>
					      	</a>
					      </li>
					      <?php } ?>
					      <?php if( $king->cfg['showShareGoogle'] == 1 ){ ?>
					      <li>
					      	<a href="https://plus.google.com/share?url=<?php echo esc_url( $escaped_link ); ?>">
					      		<i class="fa fa-google-plus fa-lg"></i>	
					      	</a>
					      </li>
					      <?php } ?>
					      <?php if( $king->cfg['showShareLinkedin'] == 1 ){ ?>
					      <li>
					      	<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title=&amp;summary=&amp;source=<?php echo esc_url( $escaped_link ); ?>">
					      		<i class="fa fa-linkedin fa-lg"></i>
					      	</a>
					      </li>
					      <?php } ?>
					      <?php if( $king->cfg['showSharePinterest'] == 1 ){ ?>
					      <li>
					      	<a href="https://pinterest.com/pin/create/button/?url=&amp;media=&amp;description=<?php echo esc_url( $escaped_link ); ?>">
					      		<i class="fa fa-pinterest fa-lg"></i>
					      	</a>
					      </li>
					      <?php } ?>
					    </ul>
					</div>
					
					
					<?php
					
					}
					
					if( $king->cfg['navArticle'] == 1 ):
					
					?>
						<nav id="nav-single">
							<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous Article', KING_DOMAIN ) ); ?></span>
							<span class="nav-next"><?php next_post_link( '%link', __( 'Next Article<span class="meta-nav">&rarr;</span>', KING_DOMAIN ) ); ?></span>
						</nav><!-- #nav-single -->
					<?php 
					
					endif;
					
					
					if( $king->cfg['archiveAboutAuthor'] == 1 ){
					?>
					
					<!--About author-->
					<div class="clearfix"></div>
					<h4>About the Author</h4>
					<div class="about_author">
				        <?php echo get_avatar(get_the_author_meta( 'ID' )); ?>
				        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" target="_blank">
				        	<?php echo get_the_author(); ?>
				        </a>
				        <br>
						<?php the_author_meta( 'description' ); ?>
				    </div>
				
					<?php
					}
					
					
					
					if( $king->cfg['archiveRelatedPosts'] == 1 ){
						include THEME_PATH.DS.'post-related.php'; 
					}
				
					if( is_single() ){
						comments_template( '', true ); 
					}
					 
				endwhile; // end of the loop. ?>
			</div>
			<div class="col-md-3">
				<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
					<div id="sidebar" class="widget-area king-sidebar">
						<?php dynamic_sidebar( 'sidebar' ); ?>
					</div><!-- #secondary -->
				<?php endif; ?>
			</div>
		</div>
	</div>
				
<?php get_footer(); ?>	