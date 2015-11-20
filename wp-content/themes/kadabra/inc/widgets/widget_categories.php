<?php
/**
 * Duplicated and tweaked WP core Categories widget class
 */
class Crum_Cat_And_Archives extends WP_Widget
{

    function __construct()
    {
        $widget_ops = array('description' => __('In one widget', 'dfd'));
        parent::__construct('crum_cat_arch', __('Widget: Categories + Archives', 'dfd'), $widget_ops);
    }



    function widget( $args, $instance ) {
        extract( $args );

        $title_cat = apply_filters('widget_title', empty( $instance['title_cat'] ) ? __( 'Categories', 'dfd' ) : $instance['title_cat'], $instance, $this->id_base);

        $title_arch = apply_filters('widget_title', empty($instance['title_arch']) ? __('Archives', 'dfd') : $instance['title_arch'], $instance, $this->id_base);

        $c = ! empty( $instance['count'] ) ? '1' : '0';
        $h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
        $d = ! empty( $instance['dropdown'] ) ? '1' : '0';

        echo $before_widget;

?>
<div class="row">
    <div class="six columns mobile-two widget">

        <?php if ( $title_cat ) {
            echo $before_title . $title_cat . $after_title;
        }

        $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

        if ( $d ) {
            $cat_args['show_option_none'] = __('Select Category', 'dfd');
            wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
            ?>

            <script type='text/javascript'>
                /* <![CDATA[ */
                var dropdown = document.getElementById("cat");
                function onCatChange() {
                    if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
                        location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
                    }
                }
                dropdown.onchange = onCatChange;
                /* ]]> */
            </script>

        <?php  } else { ?>

            <ul>
                <?php
                $cat_args['title_li'] = '';
                wp_list_categories(apply_filters('widget_categories_args', $cat_args));
                ?>
            </ul>

        <?php } ?>

    </div>
    <div class="six columns  mobile-two widget">
        <?php if ( $title_arch ) {
            echo $before_title . $title_arch . $after_title;
        }

        if ($d) { ?>

            <select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
                <option value=""><?php echo esc_attr(__('Select Month', 'dfd')); ?></option> <?php wp_get_archives(apply_filters('widget_archives_dropdown_args', array('type' => 'monthly', 'format' => 'option', 'show_post_count' => $c))); ?>
            </select>

        <?php } else { ?>

            <ul>
                <?php wp_get_archives(apply_filters('widget_archives_args', array('type' => 'monthly', 'show_post_count' => $c))); ?>
            </ul>

        <?php } ?>
    </div>
</div>

    <?php
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title_cat'] = strip_tags($new_instance['title_cat']);
        $instance['title_arch'] = strip_tags($new_instance['title_arch']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
        $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

        return $instance;
    }

    function form( $instance ) {
        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );

        $title_cat = esc_attr( $instance['title_cat'] );
        $title_arch = esc_attr( $instance['title_arch'] );

        $count = isset($instance['count']) ? (bool) $instance['count'] :false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
        $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title_cat'); ?>"><?php _e( 'Categories', 'dfd' ); _e( 'Title:', 'dfd' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title_cat'); ?>" name="<?php echo $this->get_field_name('title_cat'); ?>" type="text" value="<?php echo $title_cat; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('title_arch'); ?>"><?php _e( 'Archives', 'dfd' ); _e( 'Title:', 'dfd' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title_arch'); ?>" name="<?php echo $this->get_field_name('title_arch'); ?>" type="text" value="<?php echo $title_arch; ?>" /></p>


        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
            <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown', 'dfd' ); ?></label><br />

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts', 'dfd' ); ?></label><br />

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
            <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy', 'dfd' ); ?></label></p>
    <?php
    }
}

add_action( 'widgets_init', create_function( '', 'register_widget("Crum_Cat_And_Archives");' ) );

