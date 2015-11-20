<?php
require_once( dirname(__FILE__).'/top_stan_header.php' );

?>

<div class="row">
    <div class="twelve columns">
        <div id="page-title">
            <div class="page-title-inner">
                <h1 class="page-title">
                    <?php
                    if (is_home()) {
                        if (get_option('page_for_posts', true)) {
                            echo get_the_title(get_option('page_for_posts', true));
                        } else {
                            _e('Latest Posts', 'dfd');
                        }

                    } elseif (is_archive()) {
                    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                    if ($term) {
                        echo $term->name;
                    } elseif (is_post_type_archive()) {
                        echo get_queried_object()->labels->name;
                    } elseif (is_day()) {
                        printf(__('Daily Archives: %s', 'dfd'), get_the_date());
                    } elseif (is_month()) {
                        printf(__('Monthly Archives: %s', 'dfd'), get_the_date('F Y'));
                    } elseif (is_year()) {
                        printf(__('Yearly Archives: %s', 'dfd'), get_the_date('Y'));
                    }
                    elseif (is_author()) {
                    global $post;
					if(!empty($post) && is_object($post)) {
						$author_id = $post->post_author;
					}

                    $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
                    $google_profile = get_the_author_meta('google_profile', $curauth->ID);
                    if ($google_profile) {
                    printf(__('Author Archives:', 'dfd'));
                    echo '<a href="' . esc_url($google_profile) . '" rel="me">' . $curauth->display_name . '</a>'; ?></a>
                    <?php
                    } else {
                        printf(__('Author Archives: %s', 'dfd'), get_the_author_meta('display_name', $author_id));
                    }

                    } else {
                        single_cat_title();
                    }
                    } elseif (is_search()) {
                        printf(__('Search Results for %s', 'dfd'), get_search_query());
                    } elseif (is_404()) {
                        _e('File Not Found', 'dfd');
                    } elseif (is_single()) {
                        global $post;
                        $category = get_the_category($post->ID);
						if (isset($category[0])) {
							# first category name
							echo $category[0]->name;
						}
                    } else {
                        the_title();
                    }
                    ?>
                </h1>

                <div class="page-title-inner-subtitle">
                    <?php if ($custom_head_subtitle) {
                        echo $custom_head_subtitle;
                    } else {
                        bloginfo( 'description' );
                    } ?>
                </div>

                <div class="breadcrumbs mobile-hide">
                    <?php if (function_exists('crumina_breadcrumbs')) crumina_breadcrumbs(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php if (DfdThemeSettings::get('stan_header')) {
    echo '</div>';
} ?>
