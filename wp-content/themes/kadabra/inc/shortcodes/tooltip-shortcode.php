<?php
/**
 * Plugin Name: r+ Tooltip Shortcode
 * Plugin URI: http://themes.required.ch/
 * Description: A [tooltip] shortcode plugin for the required+ Foundation parent theme and child themes, see <a href="http://foundation.zurb.com/docs/elements.php#tipsEx">Foundation Docs</a> for more info.
 * Version: 0.1.1
 * Author: required+ Team
 * Author URI: http://required.ch
 *
 * @package   required+ Foundation
 * @version   0.1.1
 * @author    Silvan Hagen <silvan@required.ch>
 * @copyright Copyright (c) 2012, Silvan Hagen
 * @link      http://themes.required.ch/theme-features/shortcodes/
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * REQ_Tooltip Shortcode Class
 *
 * @version 0.1.0
 */
class REQ_Tooltip {

    /**
     * Sets up our actions/filters.
     *
     * @since 0.1.0
     * @access public
     * @return void
     */
    public function __construct() {

        /* Register shortcodes on 'init'. */
        add_action( 'init', array( &$this, 'register_shortcode' ) );

        add_action('wp_head', array( &$this, 'admin_bar_fix' ), 5);

        /* Apply filters to the tooltip content. */
        add_filter( 'req_tooltip_content', 'shortcode_unautop' );
        add_filter( 'req_tooltip_content', 'do_shortcode' );
    }

    /**
     * Registers the [tooltip] shortcode.
     *
     * @since  0.1.0
     * @access public
     * @return void
     */
    public function register_shortcode() {
        add_shortcode( 'tooltip', array( &$this, 'do_shortcode' ) );
    }

    /**
     * Fixes the position error for logged in users
     *
     * @since  0.1.0
     * @return strint CSS Styles
     */
    public function admin_bar_fix() {
        if( !is_admin() && is_admin_bar_showing() ) {
            remove_action( 'wp_head', '_admin_bar_bump_cb' );
            $output  = '<style type="text/css">'."\n\t";
            //$output .= 'body.admin-bar { padding-top: 28px; }'."\n";
            $output .= 'body.admin-bar .top-bar { margin-top: 28px; }'."\n";
            $output .= '</style>'."\n";
            echo $output;
        }
    }

    /**
     * Returns the content of the tooltip shortcode.
     *
     * @since  0.1.0
     * @access public
     * @param  array  $attr The user-inputted arguments.
     * @param  string $content The content to wrap in a shortcode.
     * @return string
     */
    public function do_shortcode( $attr, $content = null ) {

        /* If there's no content, just return back what we got. */
        if ( is_null( $content ) )
            return $content;

        /* Set up the default variables. */
        $output = '';
        $tooltip_classes = array();
        $title = '';
        $width = '';

        /* Set up the default arguments. */
        $defaults = apply_filters(
            'req_tooltip_defaults',
            array(
                'position'  => 'bottom',
                'width'  => '',
                'class' => '',
                'title' => ''
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        /* Allow devs to filter the arguments. */
        $attr = apply_filters( 'req_tooltip_args', $attr );

        /* Make the title (tip text) ready */
        if ( empty( $attr['title'] ) ) {
            return $content;
        } else {
            $title = ' title="' . esc_attr( $attr['title'] ) . '"';
        }

        /* Assign default class */
        $tooltip_classes[] = 'has-tip';

        /* Switch on position attr */
        switch ( $attr['position'] ) {
            case 'top':
                $tooltip_classes[] = 'tip-top';
                break;

            case 'left':
                $tooltip_classes[] = 'tip-left';
                break;

            case 'right':
                $tooltip_classes[] = 'tip-right';
                break;

            case 'bottom':
            default:
                $tooltip_classes[] = 'tip-bottom';
                break;
        }

        /* Add user-input custom class(es). */
        if ( !empty( $attr['class'] ) ) {
            if ( !is_array( $attr['class'] ) )
                $attr['class'] = preg_split( '#\s+#', $attr['class'] );
            $tooltip_classes = array_merge( $tooltip_classes, $attr['class'] );
        }

        /* Sanitize and join all classes. */
        $tooltip_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $tooltip_classes ) ) );

        if ( !empty( $attr['width'] ) ) {
            $width = ' data-width="' . esc_attr( $attr['width'] ) . '"';
        }

        /* Create our output */
        $output = '<span class="' . $tooltip_class . '"' . $title . $width . '>' . apply_filters('req_tooltip_content', $content ) . '</span>';

        /* Return the output of the tooltip. */
        return apply_filters( 'req_tooltips', $output );
    }
}

new REQ_Tooltip();