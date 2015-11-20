<?php
require_once( dirname(__FILE__).'/top_stan_header.php' );

?>

<div class="row">
    <div class="twelve columns">
        <div id="page-title">
            <div class="page-title-inner">

                <h1 class="page-title">
                    <?php $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
					
					if (DfdThemeSettings::get('portfolio_page_select')) {
						$page = DfdThemeSettings::get('portfolio_page_select');

						$title = get_the_title($page);

						$slug = get_permalink($page);
					} else {
						$page = '';
						$title = '';
						$slug = '';
					}

                    echo $title;

                    ?>
                </h1>

                <div class="page-title-inner-subtitle">
                    <?php
                    if (empty($custom_head_subtitle) && !empty($page)) {
						$custom_head_subtitle = get_post_meta($page, 'crum_headers_subtitle', true);
					}
                    if (!empty($custom_head_subtitle)) {
                        echo $custom_head_subtitle;
                    } else {
						the_title();
					}
					?>
                </div>

                <div class="breadcrumbs mobile-hide">
                    <nav id="crumbs">
						<span><a href="<?php echo home_url(); ?>/"><?php _e('Home', 'dfd') ?></a></span>
						<span class="del"></span> 
						<span><a href="<?php echo $slug; ?>"><?php echo $title; ?></a></span>
						<span class="del"></span>
						<?php the_title(); ?>
					</nav>
                </div>

            </div>
        </div>
    </div>
</div>
<?php if (DfdThemeSettings::get('stan_header')) {echo '</div>';} ?>