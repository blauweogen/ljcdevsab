<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="' . $effects . '" data-gen-offset="bottom-in-view';
} else {
    $cr_effect = '';
} ?>

<?php 

$_link = false;

$image_align = (!empty($image_align)) ? $image_align : '';

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

?>

<div class="features_module_vertical module <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>

    <?php if (count($r_items) > 0): ?>

            <?php foreach ($r_items as $panel): ?>

                <?php

                if ($panel['image']) {

                    $img = wp_get_attachment_url($panel['image']);

                    $tile_image = aq_resize($img, 134, 134, true);
                } else {
                    $tile_image = false;
                }

                if (isset($panel['link_url']) && !empty($panel['link_url'])) {

                    $_link = $panel['link_url'];

                } elseif (isset($panel['page_id']) && is_numeric($panel['page_id']) AND $panel['page_id'] > 0) {
					$_page_ = get_page($panel['page_id']);
					if (isset($_page_->ID)) {
						$_link = get_page_link($panel['page_id']);
					}
                }
				?>

                <div class="row feature-box <?php echo $cr_effect; ?>">
						
					<div class="columns two <?php if(strcmp($image_align, 'right')===0) { ?>push-ten text-left <?php }else{ ?> text-right <?php } ?> mobile-one mobile-text-center feat-block-icon">
					<?php if ($tile_image) {
						echo '<img class="single-image" src = "' . $tile_image . '" alt ="' . $panel['main_title'] . '" />';
					} elseif ($panel['icon']) {
						?>
						<i class="single-icon <?php echo $panel['icon']; ?>" style="<?php
							if ($panel['icon_size']) {  echo 'font-size:' . $panel['icon_size'] . 'px; '; } 
							?>"></i>
					<?php } ?>
					</div>

					<div class="columns ten <?php if(strcmp($image_align, 'right')===0): ?>pull-two text-right mobile-text-left<?php endif; ?> mobile-three feat-block-content">

						<?php if (isset($panel['link_in_main_title']) && $panel['link_in_main_title']) : ?>
							<h3><?php echo $panel['main_title'] ?>
							<?php if ($_link) {
								echo DFD_HTML::read_more($_link, ($panel['link_title'] != '') ? $panel['link_title'] : __('Read more', 'dfd'));
							}
							?>
							</h3>
						<?php else : ?>
							<?php if ($_link) {
							echo '<a href="' . $_link . '">';
							} ?>

							<h3><?php echo $panel['main_title'] ?></h3>

							<?php if ($_link) {
								echo '</a>';
							} ?>
						<?php endif; ?>

						<?php if (isset($panel['sub_title']) && $panel['sub_title'] != '') : ?>
						<h4 class="widget-sub-title"><?php echo $panel['sub_title'] ?></h4>
						<?php endif; ?>

						<?php if (isset($panel['content']) && $panel['content'] != ''): ?>
							<?php echo mvb_parse_content($panel['content'], TRUE); ?>
						<?php endif; ?>

					</div>

                </div>

            <?php endforeach; ?>
        
    <?php endif; ?>
</div>
