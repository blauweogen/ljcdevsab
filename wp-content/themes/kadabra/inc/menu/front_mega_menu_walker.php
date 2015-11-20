<?php
class DFD_Nav_Menu_Walker extends Walker_Nav_Menu {
	private $_last_ul = '';
	
	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		$id_field = $this->db_fields['id'];

		if (is_object($args[0])) {
			$args[0]->has_children = !empty($children_elements[$element->$id_field]);

			$args[0]->is_bordered = ($element->_dfd_mega_menu_bordered==1);
		}

		if (
			$depth==0 &&
			$element->_dfd_mega_menu_enabled==1 && 
			!empty($children_elements[$element->$id_field])
		) {
			$columns = $element->_dfd_mega_menu_columns;
			$cnt = count($children_elements[$element->$id_field]);
			
			if ($columns>1 && $cnt >1 ) {
				$delim_step = ceil($cnt/$columns);
				
				for ($i=0; $i<$cnt; $i++) {

					if ($i == ($cnt-1)  && $cnt%$delim_step!==0) {

						$children_elements[$element->$id_field][$i]->is_mega_unlast = true;
					}

					if ($i==0 || $i%$delim_step!==0) {
						continue;
					}
					$children_elements[$element->$id_field][$i]->is_mega_delim = true;



				}
			}
		}

		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

	function start_lvl(&$output, $depth = 0, $args = array()) {
		// depth dependent classes
		$indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		
		$classes = array(
			'menu-depth-' . $display_depth
		);

		if ($display_depth==1) {
			$classes[] = 'sub-menu';
		} else if ($display_depth >= 2) {
			$classes[] = 'sub-sub-menu';
		}
		
		$prefix = '';
		if ($depth==0) {
			if ( $args->is_bordered ) { $prefix = '<div class="sub-nav sub-nav-bordered">'; } else { $prefix = '<div class="sub-nav">'; }
			$classes[] = 'sub-nav-group';

		}
		
		$class_names = implode(' ', $classes);
		
		// build html
		$ul = '<ul class="' . $class_names . '">';
		$output .= "\n" . $indent . $prefix . $ul . "\n";
		
		if ($display_depth==1) {
			$this->_last_ul = $ul;
		}
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$postfix = '';
		if ($depth==0) {
			$postfix = '</div>';
		}
		
		$output .= "{$indent}</ul>{$postfix}\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;

		// code indent
		$indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' );
//		if ($item->ID==4798) {var_dump($item->classes);}
		if (isset($item->is_mega_delim) && $item->is_mega_delim) {
			$output .= '</ul>'.$this->_last_ul;
		}
		
		// depth dependent classes
		$depth_classes = array(
			( $depth == 0 ? 'nav-item' : 'sub-nav-item' ),
			'menu-item-depth-' . $depth,
		);
		if (isset($item->is_mega_unlast) && $item->is_mega_unlast) {
			$depth_classes[] = 'unlast';
		}

		if (!empty($item->_dfd_mega_menu_subtitle)  && ($depth>0)) {
			$depth_classes[] = 'mega-menu-item-has-subtitle';
		}
		
		if( in_array("current-menu-ancestor", $item->classes)) {
			$depth_classes[] = 'current-menu-ancestor';
		}
		
		if( in_array("current-menu-item", $item->classes)) {
			$depth_classes[] = 'current-menu-item';
		}
		
		if( in_array("current-menu-parent", $item->classes) ) {
			$depth_classes[] = 'current-menu-parent';
		}
		
		// build html
		if ($args->has_children) {
			$depth_classes[] = 'has-submenu';
		}

		$depth_class_names = esc_attr(implode(' ', $depth_classes));

		$output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="mega-menu-item ' . $depth_class_names . '">';

		// link attributes
		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
		
		$icon = !empty($item->_dfd_mega_menu_icon) ? '<i class="'.$item->_dfd_mega_menu_icon.'"></i>' : '';
		if (!empty($item->_dfd_mega_menu_subtitle) && ($depth>0)) {
			$subtitle = !empty($icon) ? '<span class="subtitle has-icon">'.$item->_dfd_mega_menu_subtitle.'</span>' : '<span class="subtitle">'.$item->_dfd_mega_menu_subtitle.'</span>';
		} else {
			$subtitle = '';
		}

		if ($depth == 0) {
			$item_output = sprintf('%1$s<a%2$s>',
					$args->before, $attributes
				);

			$item_output .= sprintf('<span class="item-title">%3s</span>'.$subtitle,
					apply_filters('the_title', $icon.$item->title, $item->ID)
				);

			$item_output .= sprintf('</a>%1$s', 
					$args->after
				);
			
		} else {
			$item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
					$args->before, 
					$attributes, 
					$args->link_before,
					apply_filters('the_title', $icon.$item->title, $item->ID).$subtitle,
					$args->link_after, 
					$args->after
				);
		}

		// build html
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
	
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}

/*
class DFD_Nav_Menu_Walker_Old extends Walker_Nav_Menu {

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		$id_field = $this->db_fields['id'];

		if (is_object($args[0])) {
			$args[0]->has_children = !empty($children_elements[$element->$id_field]);
		}

		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

	function start_lvl(&$output, $depth = 0, $args = array()) {
		// depth dependent classes
		$indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'sub-menu',
			( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
			( $display_depth >= 2 ? 'sub-sub-menu' : '' ),
			'menu-depth-' . $display_depth
		);
		$class_names = implode(' ', $classes);

		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;

		// code indent
		$indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' );
		
		// depth dependent classes
		$depth_classes = array(
			( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
			( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
			( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr(implode(' ', $depth_classes));

		// build html
		if ($args->has_children) {
			$class_names = 'has-submenu';
		} else {
			$class_names = "";
		}
		
		$output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="metro-menu-item ' . $depth_class_names . ' ' . $class_names . '">';

		// link attributes
		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

		if ($depth == 0) {
			$item_output = sprintf('%1$s<a%2$s>',
					$args->before, $attributes
				);

			$item_output .= sprintf('<span class="item-title">%3s</span>', 
					apply_filters('the_title', $item->title, $item->ID)
				);

			$item_output .= sprintf('</a>%1$s', 
					$args->after
				);
		} else {
			$item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
					$args->before, 
					$attributes, 
					$args->link_before,
					apply_filters('the_title', $item->title, $item->ID), 
					$args->link_after, 
					$args->after
				);
		}

		// build html
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
}
*/
