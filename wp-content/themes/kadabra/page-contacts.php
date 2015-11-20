<?php
/*
Template Name: Contacts page
*/

get_template_part('templates/top', 'page');
?>
<section id="layout" class="contact-page">
    <?php

	$map_address = DfdThemeSettings::get("map_address");
	
    if (is_array($map_address) && DfdThemeSettings::get("cont_m_disp")) {
        $zoom_level = (DfdThemeSettings::get("cont_m_zoom")) ? DfdThemeSettings::get("cont_m_zoom") : '14';

        $add_text = get_post_meta($post->ID, '_contacts_page_text', true);
        $qr_code = get_post_meta($post->ID, '_contacts_page_qr', true);

        ?>
		<!--<script typpe="text/javascript">
			jQuery(document).ready(function() {
				function mask_width() {
					var row_width = jQuery('.row').width();
					var window_width = jQuery('body').width();
					var left_mask = jQuery('.map-holder > .left-mask');
					var right_mask = jQuery('.map-holder > .right-mask');
					var mask_width = 0;
					
					if (window_width > row_width) {
						mask_width = Math.round((window_width - row_width) / 2);
						left_mask.width(mask_width);
						right_mask.width(mask_width);
					}
				}
				
				mask_width();
				
				jQuery(window).resize(function() {
					mask_width();
				});
			});
		</script>-->
		<script type="text/javascript">
				jQuery('#stuning-header').css('display','none');
		</script>
    
		
        <div class="map-holder">
			<div class="left-mask"></div>
            <div id="map"></div>
			<div class="right-mask"></div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("#map")<?php if (DfdThemeSettings::get("cont_m_height")) echo '.height("' . DfdThemeSettings::get("cont_m_height") . 'px")'; ?>.gmap3({
                    marker: {
                        values: [
                            <?php
                            foreach ($map_address as $k => $val) {
                                    $map_address[$k] = $val;
                                    echo '{address: " '. $map_address[$k] .'"},';
                            } ?>
                        ]
                    },
                    map: {
                        options: {
                            zoom: <?php echo $zoom_level; ?>,
                            navigationControl: false,
                            mapTypeControl: false,
                            scrollwheel: false,
                            streetViewControl: false,
							zoomControl: false,
							panControl: false,
							styles: [{featureType:'water',stylers:[{color:'#46bcec'},{visibility:'on'}]},{featureType:'landscape',stylers:[{color:'#f2f2f2'}]},{featureType:'road',	stylers:[{saturation:-100},{lightness:45}]},{featureType:'road.highway',stylers:[{visibility:'simplified'}]},{featureType:'road.arterial',elementType:'labels.icon',stylers:[{visibility:'off'}]},{featureType:'administrative',elementType:'labels.text.fill',stylers:[{color:'#444444'}]},{featureType:'transit',stylers:[{visibility:'off'}]	},{featureType:'poi',stylers:[{visibility:'off'}]}]
                        }
                    }
                });
            });
        </script>

    <?php } ?>
		
	<div class="row">
		<?php while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php dfd_link_pages(); ?>
		<?php endwhile; ?>
	</div>
</section>