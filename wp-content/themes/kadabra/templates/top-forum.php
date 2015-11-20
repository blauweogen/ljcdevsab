<?php

global $post;
$custom_head_img = get_post_meta($post->ID, 'crum_headers_bg_img', true);
$custom_head_color = get_post_meta($post->ID, 'crum_headers_bg_color', true);
$custom_head_subtitle = get_post_meta($post->ID, 'crum_headers_subtitle', true);


if (DfdThemeSettings::get('stan_header')) {
    echo '<div id="stuning-header" style="';
    if ($custom_head_color && ($custom_head_color != '#ffffff') && ($custom_head_color != '#')) {
        echo ' background-color: ' . $custom_head_color . '; ';
    } elseif
    (DfdThemeSettings::get('stan_header_color')
    ) {
        echo ' background-color: ' . DfdThemeSettings::get('stan_header_color') . '; ';
    }
    if ($custom_head_img) {
        echo 'background-image: url(' . $custom_head_img . ');  background-position: center;';
    } elseif
    (DfdThemeSettings::get('stan_header_image')
    ) {
        echo 'background-image: url(' . DfdThemeSettings::get('stan_header_image') . ');  background-position: center;';
    }

    if (DfdThemeSettings::get('stan_header_fixed')) {
        echo 'background-attachment: fixed; background-position:  center -10%;';
    }

    echo '">';
} ?>

    <div class="row">
        <div class="twelve columns">
            <div id="page-title">
                <a href="javascript:history.back()" class="back"></a>

                <div class="page-title-inner">
                    <h1 class="page-title">
                        <?php

                        the_title();

                        ?>
                    </h1>

                    <div class="page-title-inner-subtitle">
                        <?php if ($custom_head_subtitle) {
                            echo $custom_head_subtitle;
                        } else {
                            bloginfo('description');
                        }
                        ?>
                    </div>


                    <?php

                    if (function_exists('bbp_breadcrumb')) {

                        echo '<div class="breadcrumbs mobile-hide">';

                        function custom_bbp_breadcrumb() {
                            $args['before'] = '<nav id="crumbs">';
                            $args['after'] = '</nav>';
                            $args['sep'] = '<span class="del"></span>';
                            $args['home_text'] = __('Home', 'dfd');
                            return $args;
                        }

                        add_filter('bbp_before_get_breadcrumb_parse_args', 'custom_bbp_breadcrumb');

                        bbp_breadcrumb();

                        echo '</div>';
                    }
                    ?>


                </div>


            </div>
        </div>
    </div>
<?php if (DfdThemeSettings::get('stan_header')) {
    echo '</div>';
} ?>