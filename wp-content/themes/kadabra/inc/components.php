<?php
/**
 * DFD kadabra Components
 */

function dfd_kadabra_excerpt_length( $length ) {
    return 30;
}

function dfd_kadabra_posts_link_attributes_1() {
    return 'class="older"';
}

function dfd_kadabra_posts_link_attributes_2() {
    return 'class="newer"';
}

function dfd_next_page_button($buttons) {
	if (in_array('wp_page', $buttons)) {
		return $buttons;
	}
	
	$pos = array_search('wp_more',$buttons,true);
    if ($pos !== false) {
        $tmp_buttons = array_slice($buttons, 0, $pos+1);
        $tmp_buttons[] = 'wp_page';
        $buttons = array_merge($tmp_buttons, array_slice($buttons, $pos+1));
    }
    return $buttons;
}

/*---------------------------------------------------------
 * Paginate Archive Index Page Links
 ---------------------------------------------------------*/
function dfd_kadabra_pagination() {
    global $wp_query;

    $big = 999999999; // This needs to be an unlikely integer

    // For more options and info view the docs for paginate_links()
    // http://codex.wordpress.org/Function_Reference/paginate_links
    $paginate_links = paginate_links( array(
        'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
        'current' => max( 1, get_query_var('paged') ),
        'total' => $wp_query->max_num_pages,
        'mid_size' => 5,
        'prev_next' => True,
        'prev_text' => __('Previous', 'dfd'),
        'next_text' => __('Next', 'dfd'),
        'type' => 'list'
    ) );

    // Display the pagination if more than one page is found
    if ( $paginate_links ) {
        echo '<div class="pagination">';
        echo $paginate_links;
        echo '</div><!--// end .pagination -->';
    }
}

function dfd_kadabra_prettyadd ($content) {
	$content = preg_replace("/<a/","<a data-rel=\"prettyPhoto[slides]\"",$content,1);
	return $content;
}

/*---------------------------------------------------------
 * Paginate
 ---------------------------------------------------------*/
function dfd_link_pages() {
	wp_link_pages(array('before' => '<nav class="post-pagination">', 'after' => '</nav>'));
}

/* ----------------------------------------------------------
 *  Search form
 ----------------------------------------------------------*/

function crum_search_form($form) {
	ob_start();
	get_template_part('templates/searchform');
	$form = ob_get_clean();
	
    return $form;
}

add_filter('get_search_form', 'crum_search_form');

/* ----------------------------------------------------------
 *  Login form
 ----------------------------------------------------------*/

function crum_login_form($redirect)
{
    $args = array(
        'redirect' => $redirect, //Your url here
        'form_id' => 'loginform-custom',
		'label_username' => '',
		'label_password' => '',
    );
	
	add_filter('login_form_top', 'crum_login_form_top');
	
	if (class_exists('crum_login_widget')) {
		$args = array(
			'label_log_in' => 'Login on site',
			'label_lost_password' => 'I lost my password',
		);
		
		$crum_login_widget = new crum_login_widget();
		
		$crum_login_widget->wp_login_form($args);
	} else {
		wp_login_form($args);
	}
}

function crum_login_form_top() {
	echo '<h3 class="login_form_title">'.__('Login on site', 'dfd').'</h3>';
}
/* ----------------------------------------------------------
 *  Social networks icons for header and footer
 ----------------------------------------------------------*/

function crum_social_networks($only_show_in_footer = false){

    $social_networks = array(
        "tw"=>"Twitter",
        "fb"=>"Facebook",
        "li"=>"LinkedIN",
        "lf"=>"Last FM",
        "in"=>"Instagram",
        "gp"=>"Google +",
        "vi"=>"Vimeo",
        "vk"=>"Vkontakte",
        "yt"=>"YouTube",
        "de"=>"Devianart",
        "pi"=>"Picasa",
        "pt"=>"Pinterest",
        "wp"=>"Wordpress",
        "db"=>"Dropbox",
        "rss"=>"RSS",
    );
    $social_icons = array(
        "fb" => "soc_icon-facebook",
        "gp" => "soc_icon-google__x2B_",
        "tw" => "soc_icon-twitter-3",
        "in" => "soc_icon-instagram",
        "vi" => "soc_icon-vimeo",
        "lf" => "soc_icon-last_fm",
        "vk" => "soc_icon-rus-vk-01",
        "yt" => "soc_icon-youtube",
        "de" => "soc_icon-deviantart",
        "li" => "soc_icon-linkedin",
        "pi" => "soc_icon-picasa",
        "pt" => "soc_icon-pinterest",
        "wp" => "soc_icon-wordpress",
        "db" => "soc_icon-dropbox",
        "rss" => "soc_icon-rss",
    );

    if ($only_show_in_footer){
        foreach($social_networks as $short=>$original) {

            $icon = $social_icons[$short];

            if (DfdThemeSettings::get($short."_link")) {
                $link = DfdThemeSettings::get($short."_link");
            } else {
				$link = false;
			}

            if (DfdThemeSettings::get($short."_show")) {
                $show = DfdThemeSettings::get($short."_show");
            } else {
				$show = false;
			}
			
            if ( $link && $link!='http://' && $show ) {
                echo '<a href="'.$link .'" class="'.$short . ' ' . $icon . '" title="'.$original.'"></a>';
			}
        }

    } else {
        foreach($social_networks as $short=>$original){
            $link = DfdThemeSettings::get($short."_link");
            $icon = $social_icons[$short];
            if( $link  !='' && $link  !='http://' )
                echo '<a href="'.$link .'" class="'.$icon.'" title="'.$original.'"></a>';
        }
    }
}

function author_contact_methods() {
	$contactmethods = array();
	$contactmethods['dfd_author_info'] = 'Author Info';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['googleplus'] = 'Google Plus';
    $contactmethods['linkedin'] = 'Linked In';
    $contactmethods['youtube'] = 'YouTube';
    $contactmethods['vimeo'] = 'Vimeo';
    $contactmethods['lastfm'] = 'LastFM';
    $contactmethods['tumblr'] = 'Tumblr';
    $contactmethods['skype'] = 'Skype';
    $contactmethods['cr_facebook'] = 'Facebook';
    $contactmethods['deviantart'] = 'Deviantart';
    $contactmethods['vkontakte'] = 'Vkontakte';
    $contactmethods['picasa'] = 'Picasa';
    $contactmethods['pinterest'] = 'Pinterest';
    $contactmethods['wordpress'] = 'Wordpress';
    $contactmethods['instagram'] = 'Instagram';
    $contactmethods['dropbox'] = 'Dropbox';
    $contactmethods['rss'] = 'RSS';
	
	return $contactmethods;
}

function author_social_networks() {
	$options = author_contact_methods();
	
	$social_icons = array(
        "cr_facebook" => "soc_icon-facebook",
        "googleplus" => "soc_icon-google__x2B_",
        "twitter" => "soc_icon-twitter-3",
        "instagram" => "soc_icon-instagram",
        "vimeo" => "soc_icon-vimeo",
        "lastfm" => "soc_icon-last_fm",
        "vkontakte" => "soc_icon-rus-vk-01",
        "youtube" => "soc_icon-youtube",
        "deviantart" => "soc_icon-deviantart",
        "linkedin" => "soc_icon-linkedin",
        "picasa" => "soc_icon-picasa",
        "pinterest" => "soc_icon-pinterest",
        "wordpress" => "soc_icon-wordpress",
        "dropbox" => "soc_icon-dropbox",
        "rss" => "soc_icon-rss",
    );
	
	ob_start();
	
	echo '<div class="widget soc-icons inline-block">';
	
	foreach($social_icons as $option=>$class) {
		$title = $options[$option];
		$link = get_the_author_meta($option);
		
		if (empty($link)) {
			continue;
		}
		
		echo '<a href="'.$link .'" class="'.$class.'" title="'.$title.'"></a>';
	}
	
	echo '</div>';
	
	return ob_get_clean();
}

/* ----------------------------------------------------------
 *  Post vote counter for portfolio items
 ----------------------------------------------------------*/

add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');

function post_like() {
    // Check for nonce security
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');

    if(isset($_POST['post_like']))
    {
        // Retrieve user IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];

        // Get voters'IPs for the current post
        $meta_IP = get_post_meta($post_id, "_voted_IP");
        $voted_IP = (isset($meta_IP[0]))?$meta_IP[0]:false;

        if(!is_array($voted_IP))
            $voted_IP = array();

        // Get votes count for the current post
        $meta_count = get_post_meta($post_id, "_votes_count", true);

        // Use has already voted ?
        if(!hasAlreadyVoted($post_id))
        {
            $voted_IP[$ip] = time();

            // Save IP and increase votes count
            update_post_meta($post_id, "_voted_IP", $voted_IP);
            update_post_meta($post_id, "_votes_count", ++$meta_count);

            // Display count (ie jQuery return value)
            echo $meta_count;
        }
        else
            echo "already";
    }
    exit;
}

function hasAlreadyVoted($post_id)
{
    $timebeforerevote = 60*60;

    // Retrieve post votes IPs
    $meta_IP = get_post_meta($post_id, "_voted_IP");
    $voted_IP = (isset($meta_IP[0])) ? $meta_IP[0] : '';

    if(!is_array($voted_IP))
        $voted_IP = array();

    // Retrieve current user IP
    $ip = $_SERVER['REMOTE_ADDR'];

    // If user has already voted
    if(in_array($ip, array_keys($voted_IP)))
    {
        $time = $voted_IP[$ip];
        $now = time();

        // Compare between current time and vote time
        if(round(($now - $time) / 60) > $timebeforerevote)
            return false;

        return true;
    }

    return false;
}

/**
 * Post Like. Social Share
 * @param integer $post_id Post ID
 * @return string Post like code
 */
function getPostLikeLink($post_id=null) {
	if (!$post_id) {
		global $post;
		
		$post_id = $post->ID;
	}
	
    $vote_count = get_post_meta($post_id, "_votes_count", true);

    $output = '<span class="post-like">';

    if(hasAlreadyVoted($post_id)) {
        $output .= ' <span title="'.__('I like this article', 'dfd').'" class="like alreadyvoted"><i class="icon-add-1"></i></span>';
	} else {
        $output .= '<a href="#" data-post_id="'.$post_id.'">
					<i class="icon-add-1"></i>
                    <span  title="'.__('I like this article', 'dfd').'" class="qtip like"></span>
                </a>';
	}
	
    $output .= '<span class="count">'.$vote_count.'</span></span>';

    return $output;
}

/* ----------------------------------------------------------
 *  
 ----------------------------------------------------------*/
function dfd_get_folio_inside_template() {
	$value = get_post_meta(get_the_id(), 'folio_inside_template', true);
	if (empty($value)) {
		//@TODO: make global foli templates list
		$value = 'folio_inside_1';
	}
	
	return $value;
}

function dfd_get_header_style_option() {
	global $post;

	$headers_avail = array_keys(dfd_headers_type());
	
	if (isset($_POST['header_type']) && !empty($_POST['header_type'])) {
		if ( in_array($_POST['header_type'], $headers_avail) ) {
			return $_POST['header_type'];
		}
	}
	
	if (!empty($post) && is_object($post)) {
		$page_id = $post->ID;
		$selected_header = get_post_meta($page_id, 'dfd_headers_header_style', true);

		if ($selected_header && in_array($selected_header, $headers_avail)) {
			return $selected_header;
		}
	}

	$layouts = array('pages', 'archive', 'single', 'search', '404',);

	switch (true) {
		case is_404(): $layout = '404';
			break;
		case is_search(): $layout = 'search';
			break;
		case is_single(): $layout = 'single';
			break;
		case is_archive(): $layout = 'archive';
			break;
		case is_page(): $layout = 'pages';
			break;
		default:
			$layout = false;
	}

	if (!$layout || !in_array($layout, $layouts)) {
		$layout = $layouts[0];
	}

	if (!DfdThemeSettings::get("{$layout}_head_type") || !in_array(DfdThemeSettings::get("{$layout}_head_type"), $headers_avail)) {
		return false;
	}

	return DfdThemeSettings::get("{$layout}_head_type");
}

function dfd_get_header_layout() {
	$available = dfd_header_layouts();
	
	$header_layout = DfdThemeSettings::get('header_layout');
	
	if (empty($header_layout) || !isset($available[$header_layout])) {
		$available_keys = array_keys($available);
		$header_layout = array_shift($available_keys);
	}
	
	return $header_layout;
}

function dfd_get_header_style() {
	$head_type = dfd_get_header_style_option();
	$header_layout = dfd_get_header_layout();

	return "header-style-{$head_type} header-layout-{$header_layout}";
}

if (!function_exists('post_like_scripts')) {
	/**
	 * Post Like scripts
	 */
	function post_like_scripts() {
		wp_register_script('like_post', get_template_directory_uri().'/assets/js/post-like.js', array('jquery'), '1.0', true );
		wp_localize_script('like_post', 'ajax_var', array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce')
		));
		wp_enqueue_script('like_post');
	}
}

/**
 * Portfolio Sort panel
 * @param array $categories
 */
function dfd_folio_sort_panel($categories) {
?>
<div class="sort-panel twelve columns">
	<ul class="filter filter-buttons">
		<li class="active">
			<a data-filter=".project" href="#"><?php echo __('All', 'dfd'); ?></a>
		</li>
		<?php foreach ($categories as $category): ?>
			<li>
				<a href="#" data-filter=".project[data-category~='<?php echo strtolower(preg_replace('/\s+/', '-', $category->slug)); ?>']">
					<?php echo $category->name; ?>
				</a>
			</li>
		<?php endforeach; ?>

	</ul>
</div>
<?php
}