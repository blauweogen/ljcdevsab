<?php

$thumbnail_size = array(380, 275);

$column_number = (!empty($column_number)) ? $column_number : '';
$panel['main_title'] = (isset($panel['main_title'])) ? $panel['main_title'] : '';
$panel['sub_title'] = (isset($panel['sub_title'])) ? $panel['sub_title'] : '';

$icons = '';

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

$social_networks = array(
	"tw" => "Twitter",
	"fb" => "Facebook",
	"li" => "LinkedIN",
	"gp" => "Google +",
	"in" => "Instagram",
	"vi" => "Vimeo",
	"lf" => "Last FM",
	"vk" => "Vkontakte",
	"yt" => "YouTube",
	"de" => "Devianart",
	"pi" => "Picasa",
	"pt" => "Pinterest",
	"wp" => "Wordpress",
	"db" => "Dropbox",
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

$unique_id = uniqid('mvb_team_module_');

?>
<div class="team_module module row <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>
	
	 <?php if (!empty($r_items) && count($r_items) > 0): ?>
	
	<div class="<?php if (count($r_items) > 4) echo 'team_module_slider'; ?>" id="<?php echo $unique_id; ?>">
		<ul class="row">
        <?php foreach ($r_items as $panel): ?>

           <?php if (!empty($panel['effects'])) {
                $cr_effect = ' cr-animate-gen"  data-gen="' . $panel['effects'] . '" data-gen-offset="bottom-in-view';
            } else {
                $cr_effect = '';
            } ?>

            <?php
            $url = (isset($panel['image'])) ? wp_get_attachment_url($panel['image']) : '';
            $main_image = aq_resize($url, $thumbnail_size[0], $thumbnail_size[1], true);

            $url_flip = (isset($panel['image_flip'])) ? wp_get_attachment_url($panel['image_flip']) : '';
            $second_image = aq_resize($url_flip, $thumbnail_size[0], $thumbnail_size[1], true);
			
			if (isset($panel['link_url']) && $panel['link_url'] != '') {
				$_link = $panel['link_url'];
			} elseif (isset($panel['page_id']) && is_numeric($panel['page_id']) && $panel['page_id'] > 0) {
				$_link = get_page_link($panel['page_id']);
			} else {
				$_link = null;
			}
			
			if (isset($panel['link_title']) && $panel['link_title'] != '') {
				$_link_title = $panel['link_title'];
			} else {
				$_link_title = 'Read more';
			}

            ?>

            <li class="columns <?php echo $column_number; ?> <?php echo $cr_effect; ?>">

                <div class="team_member_box">

                    <?php if ($main_image): ?>

						<div class="member-image"> 
							<img src="<?php echo $main_image; ?>" alt="<?php echo $panel['main_title'] ?>"/>
							<div class="member-bg"></div>
							<div class="member-name">
								<div class="member-name-valign">
									<div class="block-title"><?php echo $panel['main_title'] ?></div>
									<span class="dopinfo"><?php echo $panel['sub_title'] ?></span>
								</div>
							</div>
							<div class="member-info">
								<div class="member-info-vailgn"><div class="member-info-vailgn-cell">
									
									<?php if (isset($panel['content'])): ?>
										<div class="text">
											<?php echo mvb_parse_content($panel['content'], TRUE); ?>
										</div>
									<?php endif; ?>

									<?php if(!empty($_link)): ?>
									<p class="text-left">
										<?php echo DFD_HTML::read_more($_link, $_link_title); ?>
									</p>
									<?php endif; ?>

									<?php
									$icons ='';

									foreach ($social_networks as $short => $original) {

										$link = (isset($panel[$short . "_link"])) ? $panel[$short . "_link"] : '';
										$icon = (isset($social_icons[$short])) ? $social_icons[$short] : '';

										if ($link != '') {
											$icons .= '<a href="' . $link . '" class="' . $icon . '" title="' . $original . '"></a>';
										}
									}

									if ($icons) {
										echo '<div class="widget soc-icons">'.$icons.'</div>';
									}
									?>
								</div></div>
							</div>
						</div>

                    <?php endif; ?>

                    <div class="block-title"><?php echo $panel['main_title'] ?></div>
                    <span class="dopinfo"><?php echo $panel['sub_title'] ?></span>

                </div>
            </li>
        <?php endforeach; ?>
		</ul>
		<?php if (count($r_items) > 4) : ?>
		<a href="#prev" class="jcarousel-control jcarousel-control-prev">&lsaquo;</a>
		<a href="#next" class="jcarousel-control jcarousel-control-next">&rsaquo;</a>
		<?php endif; ?>
	</div>
    <?php endif; ?>
</div>

<?php if (count($r_items) > 4) : ?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		var $slider = $('#<?php echo $unique_id; ?>');

		var eqSlides = function(){
			var width = $slider.width();
			
			$('> ul > li', $slider).equalHeights({container: 'ul'});
			
			if (width < 800) {
				if ($slider.data('jcarousel') != undefined) {
					$slider.removeClass('team_module_slider');
					$slider.find('ul').css({top: 0, left: 0, height: 'auto', 'min-height': 0});
					$slider.find('li').css({width: 'auto', height: 'auto', 'min-height': 0});
					$slider.jcarousel('destroy');
					$slider.find('a.jcarousel-control').hide();
				}
			} else {
				initSlider();
			}
		};
		
		var initSlider = function() {
			if ($slider.data('jcarousel') == undefined) {
				$slider.addClass('team_module_slider');
				$slider.find('a.jcarousel-control').show();
				$slider
					.on('jcarousel:reload jcarousel:create', function () {
						if ($slider.data('jcarousel') != undefined) {
							var width = Math.floor( ($slider.innerWidth() + 20) / 4 );
							$slider.jcarousel('items').css('width', width + 'px');
						}
					})
					.jcarousel({
						wrap: 'circular'
					});
					
				if (typeof($slider.touch) === 'function') {
					$slider.touch();
				}
			}
		}

		eqSlides(); 
		$(window).load(eqSlides).resize(eqSlides);
		
		initSlider();

		$('.jcarousel-control-prev', $slider)
			.on('jcarouselcontrol:active', function() {
				$(this).removeClass('inactive');
			})
			.on('jcarouselcontrol:inactive', function() {
				$(this).addClass('inactive');
			})
			.jcarouselControl({
				carousel: $slider,
				target: '-=1'
			});
		$('.jcarousel-control-next', $slider)
			.jcarouselControl({
				carousel: $slider,
				target: '+=1'
			});
	});
</script>
<?php endif; ?>