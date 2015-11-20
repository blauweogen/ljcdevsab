<?php
/*
 * List of included into theme files
 */

# Theme options panel
require_once locate_template('/options/options.php');

# Including theme settings class
require_once locate_template('/inc/settings.php');
DfdThemeSettings::getInstance();

# Include scripts ans styles
require_once locate_template('/inc/assets.php');
#Theme icons pack
require_once locate_template('/inc/icons/icons.php');

# Rewrite theme components
require_once locate_template('/inc/components.php');
# Theme Wrapper
require_once locate_template('/inc/wrapping.php');
# Resize images on the fly
require_once locate_template('/inc/aq_resizer.php');
# Cleanup - remove unused HTML and functions
require_once locate_template('/inc/cleanup.php');
# Add Framework additional functions
require_once locate_template('/inc/actions.php');
# Mega menu
require_once locate_template('/inc/menu.php');
# Site preloader
require_once locate_template('/inc/preloader.php');
# Envato updater init
require_once locate_template('/inc/envato.php');
# Enable SVG mime type
require_once locate_template('/inc/svg.php');

# Pre-defined post types
require_once locate_template('/inc/post-type.php');
# Widgets & Sidebars
require_once locate_template('/inc/widgets.php');
# Styled button shortcode
require_once locate_template('/styled-button/init.php');

# Shortcodes
require_once locate_template('/inc/shortcodes/shortcodes.php');
# Title allocate 
require_once locate_template('/inc/sb_title_allocate.php');
# Woocommerce support
require_once locate_template('/inc/woocommerce.php');

if(is_admin()) {
	# Custom boxes
    require_once locate_template('/inc/custom_metabox/include-boxes.php');
    require_once locate_template('/inc/lib/plugins.php');
}

