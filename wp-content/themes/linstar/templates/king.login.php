<?php
/**
 * (c) www.king-theme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $more, $king;

get_header();

?>

<div class="container-fluid breadcrumbs page_title2" id="breadcrumb">
    <div class="container">
        <div class="col-md-12">
            <div class="title">
                <h1><?php _e('Login Form', KING_DOMAIN ); ?> </h1>
			</div>
			<div class="pagenation">
				<div class="breadcrumbs"><a href="#"><?php _e('Home', KING_DOMAIN ); ?></a> / <?php _e('Login', KING_DOMAIN ); ?></div>
			</div>
        </div>
    </div>
</div>


<div id="primary" class="site-content">
	<div id="content" class="container">
		<div class="entry-content blog_postcontent">
			<div class="margin_top12"></div>
			<?php require_once 'login.php'; ?>
			<div class="margin_top8"></div>
		</div>
	</div>
</div>



<?php get_footer(); ?>   