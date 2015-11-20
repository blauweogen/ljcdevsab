<?php
/*
 * Pages layout select function
 */

function set_layout($page, $open = true) {
    $page = DfdThemeSettings::get($page . "_layout");

	switch($page) {
		case '3c-l-fixed':
			$cr_layout = 'sidebar-left2';
			$cr_width = 'six';
			break;
		case '3c-r-fixed':
			$cr_layout = 'sidebar-right2';
			$cr_width = 'six';
			break;
		case '2c-l-fixed':
			$cr_layout = 'sidebar-left';
			$cr_width = 'nine';
			break;
		case '2c-r-fixed':
			$cr_layout = 'sidebar-right';
			$cr_width = 'nine';
			break;
		case '3c-fixed':
			$cr_layout = 'sidebar-both';
			$cr_width = 'six';
			break;
		case '1col-fixed':
		default:
			$cr_layout = '';
			$cr_width = 'twelve';
	}
	
    if ($open) {

        // Open content wrapper


        echo '<div class="blog-section ' . $cr_layout . '">';
        echo '<section id="main-content" role="main" class="' . $cr_width . ' columns">';


    } else {

        // Close content wrapper

        echo ' </section>';

        if (($page == "2c-l-fixed") || ($page == "3c-fixed")) {
            get_template_part('templates/sidebar', 'left');
            echo ' </div>';
        }
        if (($page == "3c-l-fixed")){
            get_template_part('templates/sidebar', 'right');
            echo ' </div>';
            get_template_part('templates/sidebar', 'left');
        }
        if ($page == "3c-r-fixed"){
            get_template_part('templates/sidebar', 'left');
            echo ' </div>';
        }
        if (($page == "2c-r-fixed") || ($page == "3c-fixed") || ($page == "3c-r-fixed") ) {
            get_template_part('templates/sidebar', 'right');
        }
    }
}


/**
 * Add the RSS feed link in the <head> if there's posts
 */
function crum_feed_link() {
  $count = wp_count_posts('post'); if ($count->publish > 0) {
    echo "\n\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"". get_bloginfo('name') ." Feed\" href=\"". home_url() ."/feed/\">\n";
  }
}

add_action('wp_head', 'crum_feed_link', -2);


/**
 * Customization of login page
 */

function crum_custom_login_logo() {
    if(DfdThemeSettings::get("custom_logo_image")){
        $custom_logo = DfdThemeSettings::get("custom_logo_image");
    } else {
        $custom_logo = get_template_directory_uri() .'/assets/img/logo.png';
    }

    echo '<style type="text/css">
    body.login{background:#fff;}
    h1 a { background-image:url('. $custom_logo .') !important; height: auto !important; min-height: 70px !important; width: 160px !important; background-size: contain !important;} </style>';
}

add_action('login_head', 'crum_custom_login_logo');

function crum_home_link() {
    return site_url();
}
add_filter('login_headerurl','crum_home_link');

function change_title_on_logo() {
    return get_bloginfo( 'name' );
}
add_filter('login_headertitle', 'change_title_on_logo');


// Add/Remove Contact Methods
function add_remove_contactmethods( $contactmethods ) {
	$contacts = author_contact_methods();
	
	foreach($contacts as $k=>$v) {
		$contactmethods[$k] = $v;
	}

    // Remove Contact Methods
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);

    return $contactmethods;
}
add_filter('user_contactmethods','add_remove_contactmethods',10,1);


/**
 * Create pagination
 */

function crumin_pagination() {

    global $wp_query;

    $big = 999999999;

    $links = paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'prev_next' => true,
            'prev_text' =>  __('Prev', 'dfd'), //text of the "Previous page" link
            'next_text' =>  __('Next', 'dfd'), //text of the "Next page" link

            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
            'type' => 'list'
        )
    );

    $pagination = str_replace('page-numbers','pagination',$links);

    echo $pagination;

}
/*
 *
 *
 */

function crumina_breadcrumbs() {

    /* === OPTIONS === */
    $text['home']     = __('Home', 'dfd'); // text for the 'Home' link
    $text['category'] = __('Archive by Category "%s"', 'dfd'); // text for a category page
    $text['search']   = __('Search Results for "%s" Query', 'dfd'); // text for a search results page
    $text['tag']      = __('Posts Tagged "%s"', 'dfd'); // text for a tag page
    $text['author']   = __('Articles Posted by %s', 'dfd'); // text for an author page
    $text['404']      = __('Error 404', 'dfd'); // text for the 404 page

    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = ' <span class="del"></span> '; // delimiter between crumbs
    $before      = '<span class="current">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb
    /* === END OF OPTIONS === */

    global $post;
    $homeLink = home_url() . '/';
    $linkBefore = '<span typeof="v:Breadcrumb">';
    $linkAfter = '</span>';
    $linkAttr = ' rel="v:url" property="v:title"';
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

    if (is_home() || is_front_page()) {

        if ($showOnHome == 1) echo '<nav id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></nav>';

    } else {

        echo '<nav id="crumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

        if ( is_category() ) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
            }
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;


        }
        elseif (is_singular('topic') ){
            $post_type = get_post_type_object(get_post_type());
            printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
        }
        /* in forum, add link to support forum page template */
        elseif (is_singular('forum')){
            $post_type = get_post_type_object(get_post_type());
            printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
        }
        elseif (is_tax('topic-tag')){
            $post_type = get_post_type_object(get_post_type());
            printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
        }




        elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
                if ($showCurrent == 1) echo $before . get_the_title() . $after;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
			if(!empty($post_type) && is_object($post_type) && is_object($post_type->labels)) {
				echo $before . $post_type->labels->singular_name . $after;
			}

        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
            echo $cats;
            printf($link, get_permalink($parent), $parent->post_title);
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_page() && !$post->post_parent ) {
            if ($showCurrent == 1) echo $before . get_the_title() . $after;

        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) echo $delimiter;
            }
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page', 'dfd') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }

        echo '</nav>';

    }
}


/*
 * Seo additions
 */

/**
 * Add Google+ meta tags to header
 *
 * @uses	get_the_ID()  Get post ID
 * @uses	setup_postdata()  setup postdata to get the excerpt
 * @uses	wp_get_attachment_image_src()  Get thumbnail src
 * @uses	get_post_thumbnail_id  Get thumbnail ID
 * @uses	the_title()  Display the post title
 *
 * @author c.bavota
 */
add_action( 'wp_head', 'add_google_plus_meta' );

function add_google_plus_meta() {

    if( is_single() ) {

        global $post;

        $post_id = get_the_ID();
        setup_postdata( $post );

        $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );
        $thumbnail = empty( $thumbnail ) ? '' : '<meta itemprop="image" content="' . esc_url( $thumbnail[0] ) . '">';
        ?>

    <!-- Google+ meta tags -->
    <meta itemprop="name" content="<?php esc_attr( the_title() ); ?>">
    <meta itemprop="description" content="<?php echo esc_attr( get_the_excerpt() ); ?>">
    <?php echo $thumbnail . "\n"; ?>

    <!-- eof Google+ meta tags -->
    <?php

    }

}



/*-----------------------------------------------------------------------------------*/
# Get Social Counter
/*-----------------------------------------------------------------------------------*/

$cachetime = (DfdThemeSettings::get('cachetime')) ? ((int) DfdThemeSettings::get('cachetime') * 60) : (60 * 60 * 1);

function tie_curl_subscribers_text_counter( $xml_url ) {
	$data_buf = wp_remote_get($xml_url, array('sslverify' => false));
	if (!is_wp_error($data_buf) && isset($data_buf['body'])) {
		return $data_buf['body'];
	}
}

function tie_rss_count( $fb_id ) {
    $feedburner['rss_count'] = get_option( 'rss_count');
    return $feedburner;
}

function tie_followers_count() {
	$twitter_username = DfdThemeSettings::get("username");
	
	$r['page_url'] = 'http://www.twitter.com/'.$twitter_username;
	
    try {
		require_once locate_template('/inc/lib/twitteroauth.php');
		$twitter = new DFDTwitter();
		$r['followers_count'] = $twitter->getFollowersCount();
    } catch (Exception $e) {
        $r['followers_count'] = 0;
    }

    return $r;
}

function tie_facebook_fans( $page_link ){
    $face_link = @parse_url($page_link);
	$fans = 0;
	
	if ( false === ( $fans = get_transient( 'facebook_fans_cache' ) ) ) {
		
		if( $face_link['host'] == 'www.facebook.com' || $face_link['host']  == 'facebook.com' ){
			try {
				$page_name = substr(@parse_url($page_link, PHP_URL_PATH), 1);
				$data = @json_decode(tie_curl_subscribers_text_counter("https://graph.facebook.com/".$page_name));
				if ($data && isset($data->likes)) {
					$fans = intval($data->likes);
				}
			} catch (Exception $e) {
				$fans = 0;
			}
			
			set_transient( 'facebook_fans_cache', $fans, $cachetime );
		}
		
	}
	
	return $fans;
}


function tie_youtube_subs( $channel_link ){
    $youtube_link = @parse_url($channel_link);
	$subs = 0;
	
	if ( false === ( $subs = get_transient( 'youtube_subs_cache' ) ) ) {
		if( $youtube_link['host'] == 'www.youtube.com' || $youtube_link['host']  == 'youtube.com' ){
			try {
				$youtube_name = substr(@parse_url($channel_link, PHP_URL_PATH), 6);
				$json = @tie_curl_subscribers_text_counter("http://gdata.youtube.com/feeds/api/users/".$youtube_name."?alt=json");
				$data = json_decode($json, true);
				
				$subs = intval($data['entry']['yt$statistics']['subscriberCount']);
			} catch (Exception $e) {
				$subs = 0;
			}

			set_transient( 'youtube_subs_cache', $subs, $cachetime );
		}
	}
	
    return $subs;
}


function tie_vimeo_count( $page_link ) {
    $face_link = @parse_url($page_link);

    if( $face_link['host'] == 'www.vimeo.com' || $face_link['host']  == 'vimeo.com' ){
        try {
            $page_name = substr(@parse_url($page_link, PHP_URL_PATH), 10);
            @$data = @json_decode(tie_curl_subscribers_text_counter( 'http://vimeo.com/api/v2/channel/' . $page_name  .'/info.json'));

            $vimeo = $data->total_subscribers;
        } catch (Exception $e) {
            $vimeo = 0;
        }

        if( !empty($vimeo) && get_option( 'vimeo_count') != $vimeo )
            update_option( 'vimeo_count' , $vimeo );

        if( $vimeo == 0 && get_option( 'vimeo_count') )
            $vimeo = get_option( 'vimeo_count');

        elseif( $vimeo == 0 && !get_option( 'vimeo_count') )
            $vimeo = 0;

        return $vimeo;
    }

}

function tie_dribbble_count( $page_link ) {
    $face_link = @parse_url($page_link);

    if( $face_link['host'] == 'www.dribbble.com' || $face_link['host']  == 'dribbble.com' ){
        try {
            $page_name = substr(@parse_url($page_link, PHP_URL_PATH), 1);
            @$data = @json_decode(tie_curl_subscribers_text_counter( 'http://api.dribbble.com/' . $page_name));

            $dribbble = $data->followers_count;
        } catch (Exception $e) {
            $dribbble = 0;
        }

        if( !empty($dribbble) && get_option( 'dribbble_count') != $dribbble )
            update_option( 'dribbble_count' , $dribbble );

        if( $dribbble == 0 && get_option( 'dribbble_count') )
            $dribbble = get_option( 'dribbble_count');

        elseif( $dribbble == 0 && !get_option( 'dribbble_count') )
            $dribbble = 0;

        return $dribbble;
    }
}


/*
 * WooCommerce Actions
 */

// меняем местами цену и рейтинг
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 11 );


// добавляем вывод рейтинга на странице товара

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'sb_woocommerce_template_single_price_rating_wrap_start', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 11 );
add_action( 'woocommerce_single_product_summary', 'sb_woocommerce_template_single_rating', 12 );
add_action( 'woocommerce_single_product_summary', 'sb_woocommerce_template_single_price_rating_wrap_end', 13 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 50 );

function sb_woocommerce_template_single_price_rating_wrap_start() {
	echo '<div class="price-wrap">';
}

function sb_woocommerce_template_single_price_rating_wrap_end() {
	echo '</div>';
}

function sb_woocommerce_template_single_rating() {
	if ( function_exists('woocommerce_get_template') ) {
		woocommerce_get_template( 'loop/rating.php' );
	}
}

if (!function_exists('dfd_kadabra_use_default_gallery_style_filter')) {
	function dfd_kadabra_use_default_gallery_style_filter($existing_code) {
		return false; //return $existing_code;
	}
}

// удаляем вывод related_products на странице товара

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'sb_woocommerce_output_best_selling_products', 20 );

function sb_woocommerce_output_best_selling_products() {
	echo do_shortcode('[sb_best_selling_products columns="3" show_title="true"]');
}

add_filter('woocommerce_page_title', 'sb_woocommerce_page_title');

function sb_woocommerce_page_title($page_title) {
	if (empty($page_title) && DfdThemeSettings::get('shop_title')) {
		$page_title = DfdThemeSettings::get('shop_title');
	}
	
	return $page_title;
}

/* * *
 * PHP Less
 */

function sb_auto_compile_less_init() {
	if ( !file_exists(get_template_directory() . '/inc/lessc.inc.php') )
		return false;

	require_once( get_template_directory() . '/inc/lessc.inc.php' );
	
	$less_files = array(
		'admin-panel' => array(
			'src' => get_template_directory() . '/assets/less/admin-panel.less',
			'out' => get_template_directory() . '/assets/css/admin-panel.css',
		),
		
		'animate-custom' => array(
			'src' => get_template_directory() . '/assets/less/animate-custom.less',
			'out' => get_template_directory() . '/assets/css/animate-custom.css',
		),
		
		'app' => array(
			'src' => get_template_directory() . '/assets/less/app.less',
			'out' => get_template_directory() . '/assets/css/app.css',
			'autocompile' => true,
		),
		
		'bbpress' => array(
			'src' => get_template_directory() . '/assets/less/bbpress.less',
			'out' => get_template_directory() . '/assets/css/bbpress.css',
		),
		
		'flexslider' => array(
			'src' => get_template_directory() . '/assets/less/flexslider.less',
			'out' => get_template_directory() . '/assets/css/flexslider.css',
		),
		
		'jquery.isotope' => array(
			'src' => get_template_directory() . '/assets/less/jquery.isotope.less',
			'out' => get_template_directory() . '/assets/css/jquery.isotope.css',
		),
		
		'mobile-responsive' => array(
			'src' => get_template_directory() . '/assets/less/mobile-responsive.less',
			'out' => get_template_directory() . '/assets/css/mobile-responsive.css',
			'autocompile' => true,
		),
		
		'preloader' => array(
			'src' => get_template_directory() .'/assets/less/preloader.less',
			'out' => get_template_directory() . '/assets/css/preloader.css',
		),
		
		'prettyPhoto' => array(
			'src' => get_template_directory() . '/assets/less/prettyPhoto.less',
			'out' => get_template_directory() . '/assets/css/prettyPhoto.css',
		),
		
		'site-preloader' => array(
			'src' => get_template_directory() .'/assets/less/site-preloader.less',
			'out' => get_template_directory() . '/assets/css/site-preloader.css',
		),

		'styled-button' => array(
			'src' => get_template_directory() .'/assets/less/styled-button.less', 
			'out' => get_template_directory() . '/assets/css/styled-button.css',
		),

		'woocommerce' => array(
			'src' => get_template_directory() . '/assets/less/woocommerce.less',
			'out' => get_template_directory() . '/assets/css/woocommerce.css',
			'autocompile' => true,
		),
	);

	$less_files = apply_filters('dfd_less_filter', $less_files);

	if (!empty($less_files) && is_array($less_files)) {
		foreach ($less_files as $less_file) {
			if (!is_file($less_file['out']) 
					|| (!empty($less_file['autocompile']) 
						&& $less_file['autocompile'] === true 
						&& defined('DFD_PHP_LESS') 
						&& DFD_PHP_LESS === true)) {
				sb_auto_compile_less($less_file['src'], $less_file['out']);
			}
		}
	}
}

function sb_auto_compile_less($inputFile, $outputFile) {
	if (!class_exists('lessc'))
		return false;

	$less = new lessc();
	try {
		$less->setFormatter('compressed');
		$less->compileFile($inputFile, $outputFile);
		unset($less);
	} catch (Exception $ex) {
		wp_die('Less compile error: '.$ex->getMessage());
	}
}

add_action('wp', 'sb_auto_compile_less_init');

/*
 * Saved theme options
 */

function sb_updated_theme_option($option, $old_value, $value) {
	if ($option === DFD_THEME_SETTINGS_NAME) {
		DfdThemeSettings::reloadInstance();
		WP_Filesystem();
		global $wp_filesystem;

		/** Capture variables.less output **/
		ob_start();
		require locate_template('/options/options/variables_less.php');
		$variables_less = ob_get_clean();

		$variables_less_uploads_file = locate_template('/assets/less.lib/_generated/variables.less');

		if (!$wp_filesystem->put_contents($variables_less_uploads_file, $variables_less, 0644)) {
			file_put_contents($variables_less_uploads_file, $variables_less);
		}
		
		$colors_old_value = array(
			'main_site_color' => ( isset($old_value['main_site_color']) ) ? $old_value['main_site_color'] : '',
			'secondary_site_color' => ( isset($old_value['secondary_site_color']) ) ? $old_value['secondary_site_color'] : '',
			'font_site_color' => ( isset($old_value['font_site_color']) ) ? $old_value['font_site_color'] : '',
			'link_site_color' => ( isset($old_value['link_site_color']) ) ? $old_value['link_site_color'] : '',
			'third_site_color' => ( isset($old_value['third_site_color']) ) ? $old_value['third_site_color'] : '',
			'header_background_color' => ( isset($old_value['header_background_color']) ) ? $old_value['header_background_color'] : '',
			'fixed_header_background_color' => ( isset($old_value['fixed_header_background_color']) ) ? $old_value['fixed_header_background_color'] : '',
			'fixed_header_background_opacity' => ( isset($old_value['fixed_header_background_opacity']) ) ? $old_value['fixed_header_background_opacity'] : '',
			'news_page_slider_background_hover' => ( isset($old_value['news_page_slider_background_hover']) ) ? $old_value['news_page_slider_background_hover'] : '',
			'news_page_slider_opacity_hover' => ( isset($old_value['news_page_slider_opacity_hover']) ) ? $old_value['news_page_slider_opacity_hover'] : '',
			'read_more_color' => ( isset($old_value['read_more_color']) ) ? $old_value['read_more_color'] : '',
			'button_bg_color' => ( isset($old_value['button_bg_color']) ) ? $old_value['button_bg_color'] : '',
		);

		$colors_new_value = array(
			'main_site_color' => ( isset($value['main_site_color']) ) ? $value['main_site_color'] : '',
			'secondary_site_color' => ( isset($value['secondary_site_color']) ) ? $value['secondary_site_color'] : '',
			'font_site_color' => ( isset($value['font_site_color']) ) ? $value['font_site_color'] : '',
			'link_site_color' => ( isset($value['link_site_color']) ) ? $value['link_site_color'] : '',
			'third_site_color' => ( isset($value['third_site_color']) ) ? $value['third_site_color'] : '',
			'header_background_color' => ( isset($value['header_background_color']) ) ? $value['header_background_color'] : '',
			'fixed_header_background_color' => ( isset($value['fixed_header_background_color']) ) ? $value['fixed_header_background_color'] : '',
			'fixed_header_background_opacity' => ( isset($value['fixed_header_background_opacity']) ) ? $value['fixed_header_background_opacity'] : '',
			'news_page_slider_background_hover' => ( isset($value['news_page_slider_background_hover']) ) ? $value['news_page_slider_background_hover'] : '',
			'news_page_slider_opacity_hover' => ( isset($value['news_page_slider_opacity_hover']) ) ? $value['news_page_slider_opacity_hover'] : '',
			'read_more_color' => ( isset($value['read_more_color']) ) ? $value['read_more_color'] : '',
			'button_bg_color' => ( isset($value['button_bg_color']) ) ? $value['button_bg_color'] : '',
		);

		if ($colors_old_value !== $colors_new_value) {
			if ( !class_exists('lessc') ) {
				if ( !file_exists(get_template_directory() . '/inc/lessc.inc.php') ) {
					return false;
				}
				
				require( get_template_directory() . '/inc/lessc.inc.php' );
			}
			
			try {
				$less = new lessc();
				$less->setFormatter('compressed');
				$less->compileFile( get_template_directory() . '/assets/less/app.less', 
						get_template_directory() . '/assets/css/app.css' );
				
				unset($less);
			} catch (Exception $ex) {
				set_transient( 'redux-opts-exceptions-kadabra', array('Less compile error: ' . $ex->getMessage()), 1000 );
			}
			
			try {
				$less = new lessc();
				$less->setFormatter('compressed');
				if (is_plugin_active('woocommerce/woocommerce.php')) {
					$less->compileFile( get_template_directory() . '/assets/less/woocommerce.less', 
							get_template_directory() . '/assets/css/woocommerce.css' );
				}
				
				unset($less);
			} catch (Exception $ex) {
				set_transient( 'redux-opts-exceptions-kadabra', array('Less compile error: ' . $ex->getMessage()), 1000 );
			}
		}
	}
}

add_action('updated_option', 'sb_updated_theme_option', 10, 3);

function dfd_stylecharger_return_header() {
    ?>
    <div id="header-container" class="<?php echo dfd_get_header_style(); ?>">
            <?php get_template_part('templates/header/style', dfd_get_header_style_option()); ?>    
        </div>
    <?php
    exit;
}
