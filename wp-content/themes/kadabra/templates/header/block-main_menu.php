<nav class="mega-menu" id="main_mega_menu">
<?php
	wp_nav_menu(array(
		'theme_location' => 'primary_navigation', 
		'menu_class' => 'nav-menu menu-primary-navigation', 
		'fallback_cb' => 'top_menu_fallback'
	));
?>
</nav>
