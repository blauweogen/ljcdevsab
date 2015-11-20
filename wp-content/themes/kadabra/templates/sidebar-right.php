<aside class="three columns" id="right-sidebar">

    <?php

    if(is_single()){
        global $post;
        $page_id = $post->ID;
    } else {
        $page_id     = get_queried_object_id();
    }

    $selected_sidebar = get_post_meta($page_id, 'crum_sidebars_sidebar_2', true);

    if ($selected_sidebar && (function_exists('smk_sidebar'))) {

        smk_sidebar($selected_sidebar);

    } elseif (is_active_sidebar('sidebar-right')) {

        dynamic_sidebar('sidebar-right');

    } else {

        the_widget( 'WP_Widget_Search', 'title=');

        the_widget( 'Crum_Cat_And_Archives', 'title=Follow us on twitter&cachetime=60&username=crumina&tweetstoshow=1', 'before_title=<h3 class="widget-title">&after_title=</h3>');

        the_widget( 'crum_tags_widget', 'title=Tags&number=15', 'before_title=<h3 class="widget-title">&after_title=</h3>');

    }

    ?>


  </aside>
