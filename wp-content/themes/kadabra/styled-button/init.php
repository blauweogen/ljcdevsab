<?php

if (!class_exists('StyledButton')) {

	class StyledButton {

		/**
		 *
		 * @var string
		 */
		public $dir;
		
		/**
		 *
		 * @var string
		 */
		public $url;
		
		/**
		 *
		 * @var string
		 */
		public $shortcode = 'styled_button';

		/**
		 * __construct
		 */
		function __construct() {
			// actions
			add_action('admin_init', array(&$this, 'tinymce_button'));
			foreach( array('post.php','post-new.php') as $hook ) {
				add_action("admin_head-$hook", array(&$this, 'admin_head'));
			}
			add_action('wp_ajax_styled_button_preview', array(&$this, 'preview'));
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_style'));

			// shortcodes
			if (!shortcode_exists($this->shortcode)) {
				add_shortcode($this->shortcode, array(&$this, 'button_shortcode'));
			}
		}
		
		/**
		 * 
		 */
		public function admin_head() {
				?>
			<!-- TinyMCE StyledButton Plugin -->
			<script type='text/javascript'>
			var styled_button_plugin = '<?php echo $this->url() . '/tinymce/styled-button.js'; ?>';
			</script>
			<!-- TinyMCE StyledButton Plugin -->
			<?php
		}

		/**
		 * Add button to wordpress tinymce
		 */
		public function tinymce_button() {
			// filters
			add_filter('mce_buttons', array(&$this, 'register_tinymce_buttons'));
			add_filter('mce_external_plugins', array(&$this, 'tinymce_external_plugins'));
		}

		/**
		 * 
		 * @param array $buttons
		 * @return array
		 */
		public function register_tinymce_buttons($buttons) {
			$buttons[] = 'styled_button';

			return $buttons;
		}

		/**
		 * 
		 * @param array $plugins
		 * @return array
		 */
		public function tinymce_external_plugins($plugins) {
			$plugins['styled_button'] = $this->url() . '/tinymce/styled-button.js';

			return $plugins;
		}

		/**
		 * 
		 * @param array $atts
		 * @param string $content
		 * @return string
		 */
		public function button_shortcode($atts, $content = '') {
			extract(shortcode_atts(array(
				'title' => '&nbsp;',
				'href' => '#',
				//'display' => '',
				'size' => '',
				'color' => '',
				'style' => ''), $atts, $this->shortcode));
			
			$class = array(
				//$display,
				$size,
				$color,
				$style,
			);
			
			return '<span class="around-button"><a data-hover="'.$title.'" href="'.$href.'" class="styled-button '.implode(' ', $class).'"><span data-hover="'.$title.'">'.$title.'</span></a></span>';
		}
		
		/**
		 * @return string
		 */
		public function preview() {
			
			if (isset($_POST['shortcode']) && has_shortcode($_POST['shortcode'], $this->shortcode)) {
				die(do_shortcode(stripslashes($_POST['shortcode'])));
			} else {
				die();
			}
			
		}
		
		/**
		 * 
		 */
		public function enqueue_style() {
			wp_register_style('styled-button', get_template_directory_uri() . '/assets/css/styled-button.css');
			wp_enqueue_style('styled-button');
		}

		/**
		 * 
		 * @return string
		 */
		public function dir() {
			if ($this->dir)
				return $this->dir;
			return $this->dir = untrailingslashit(dirname(__FILE__));
		}

		/**
		 * 
		 * @return string
		 */
		public function url() {
			if ($this->url)
				return $this->url;
			return $this->url = untrailingslashit(get_template_directory_uri() . '/styled-button');
		}

	}

	$GLOBALS['StyledButton'] = new StyledButton();
}