<?php
/*
Template Name: Blog with user layout selection
*/
?>

<?php

$layouts = array(
    "right-1"     => "right-1",
    "left-1"      => "left-1",
    "left-2"      => "left-2",
    "right-2"     => "right-2",
    "both"        => "both",
    "no-sidebars" => "no-sidebars"
);

if (isset($_GET["page_layout"])) {
    $layout = $_GET["page_layout"];
    $_SESSION["page_layout" . $post->ID] = $_GET["page_layout"];
} elseif (isset($_SESSION["page_layout" . $post->ID])) {
    $layout = $_SESSION["page_layout" . $post->ID];
} else {
    $layout ='';
}

if (isset($layouts[$layout])){
    switch ($layout) {
        case "no-sidebars":
            $page_lay = "1col-fixed";
            break;
        case "right-1":
            $page_lay = "2c-r-fixed";
            break;
        case "left-1":
            $page_lay = "2c-l-fixed";
            break;
        case "right-2":
            $page_lay = "3c-r-fixed";
            break;
        case "left-2":
            $page_lay = "3c-l-fixed";
            break;
        case "both":
            $page_lay = "3c-fixed";
            break;
        default:
            $page_lay = "3c-fixed";
            break;
    }
} elseif(get_post_meta($post->ID, 'blog_layout_select', true)) {
    $page_lay = get_post_meta($post->ID, 'blog_layout_select', true);
}
else {
    $page_lay = DfdThemeSettings::get("archive_layout");
}
?>

<?php get_template_part('templates/top', 'page'); ?>


<section id="layout">
    <div class="row">

        <?php if ($page_lay == "1col-fixed") {
            $cr_layout = '';
            $cr_width = 'twelve';
        }
        if ($page_lay == "3c-l-fixed") {
            $cr_layout = 'sidebar-left2';
            $cr_width = 'six';
        }
        if ($page_lay == "3c-r-fixed") {
            $cr_layout = 'sidebar-right2';
            $cr_width = 'six';
        }
        if ($page_lay == "2c-l-fixed") {
            $cr_layout = 'sidebar-left';
            $cr_width = 'nine';
        }
        if ($page_lay == "2c-r-fixed") {
            $cr_layout = 'sidebar-right';
            $cr_width = 'nine';
        }
        if ($page_lay == "3c-fixed") {
            $cr_layout = 'sidebar-both';
            $cr_width = 'six';
        }


        echo '<div class="blog-section ' . $cr_layout . '">';
        echo '<section id="main-content" role="main" class="' . $cr_width . ' columns">';


        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        $number_per_page = get_post_meta($post->ID, 'blog_number_to_display', true);
        $number_per_page = ($number_per_page) ? $number_per_page : '12';


        $selected_custom_categories = wp_get_object_terms($post->ID, 'category');
        if(!empty($selected_custom_categories)){
            if(!is_wp_error( $selected_custom_categories )){
                foreach($selected_custom_categories as $term){
                    $blog_cut_array[] = $term->term_id;
                }
            }
        }

        $blog_custom_categories = ( get_post_meta(get_the_ID(), 'blog_sort_category',true)) ?  $blog_cut_array : '';

        if ($blog_custom_categories){$blog_custom_categories = implode(",", $blog_custom_categories);}


        $args = array('post_type' => 'post',
            'posts_per_page' => $number_per_page,
            'paged' => $paged,
            'cat' => $blog_custom_categories
        );


        $temp = $wp_query;
        $wp_query = null;
        $wp_query = new WP_Query($args);

        if (!have_posts()) : ?>

            <article id="post-0" class="post no-results not-found">
                <header class="entry-header">
                    <h1><?php _e('Nothing Found', 'dfd'); ?></h1>
                </header>
                <!-- .entry-header -->

                <div class="entry-content">
                    <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'dfd'); ?></p>
                    <?php get_search_form(); ?>
                </div>
                <!-- .entry-content -->


                <header class="entry-header">
                    <h2><?php _e('Tags also can be used', 'dfd'); ?></h2>
                </header>
                <!-- .entry-header -->

                <div class="tags-widget">
                    <?php wp_tag_cloud('smallest=10&largest=10&number=30'); ?>
                </div>

            </article><!-- #post-0 -->
        <?php endif; ?>

        <?php while (have_posts()) : the_post();

            get_template_part('templates/loop', 'content');

        endwhile; ?>

        <?php if ($wp_query->max_num_pages > 1) : ?>

            <nav class="page-nav">

                <?php echo dfd_kadabra_pagination(); ?>

            </nav>

        <?php endif; ?>

        <?php
        $wp_query = null;
        $wp_query = $temp;

        echo ' </section>';

        if (($page_lay == "2c-l-fixed") || ($page_lay == "3c-fixed")) {
            get_template_part('templates/sidebar', 'left');
            echo ' </div>';
        }
        if (($page_lay == "3c-l-fixed")) {
            get_template_part('templates/sidebar', 'right');
            echo ' </div>';
            get_template_part('templates/sidebar', 'left');
        }
        if ($page_lay == "3c-r-fixed") {
            get_template_part('templates/sidebar', 'left');
            echo ' </div>';
        }
        if (($page_lay == "2c-r-fixed") || ($page_lay == "3c-fixed") || ($page_lay == "3c-r-fixed")) {
            get_template_part('templates/sidebar', 'right');
        }
        ?>

    </div>
</section>