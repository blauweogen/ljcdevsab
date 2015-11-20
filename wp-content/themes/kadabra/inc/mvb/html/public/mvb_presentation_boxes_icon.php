<?php

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

?>
<div class="features_module module <?php echo (!empty($css)) ? $css : ''; ?>" <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>
	
	<?php 
		$_link = '';
	?>
	
    <div class="row">
        <?php if (!empty($r_items) && count($r_items) > 0): ?>

            <?php foreach ($r_items as $panel): ?>

                <?php if (!empty($panel['effects'])) {
                    $cr_effect = ' cr-animate-gen"  data-gen="' . $panel['effects'] . '" data-gen-offset="bottom-in-view';
                } else {
                    $cr_effect = '';
                } ?>

                <?php

                if (isset($panel['link_url'])) {

                    $_link = $panel['link_url'];

                } elseif (isset($panel['page_id']) && is_numeric($panel['page_id']) && $panel['page_id'] > 0) {

                    $_link = get_page_link($panel['page_id']);

                }

                if (isset($panel['icon_size'])) {
                    $size = 'font-size:' . $panel['icon_size'] . 'px; ';
                }

                if (isset($panel['icon'])) {
                    $icon = '<i class = "' . $panel['icon'] . '"> </i>';
                } else {
                    $icon = '';
                }  ?>


                <div class="<?php echo (!empty($column_number)) ? $column_number : '' ?> columns <?php echo (!empty($cr_effect)) ? $cr_effect : ''; ?>">

                    <div class="feature-box">
                        <?php  if ($_link) {
                            echo '<a href="' . $_link . '">';
                        } ?>

                        <i class="single-icon <?php echo (isset($panel['icon'])) ? $panel['icon'] : ''; ?>" style="<?php if (isset($panel['icon_size'])) { echo $size; } ?> <?php if (isset($panel['icon_color'])) { echo 'color:#' . $panel['icon_color'] . ';'; } ?>"
                            <?php if ($panel['icon_hover_color']) { ?> onmouseover="this.style.color='#<?php echo $panel['icon_hover_color']; ?>'" <?php } ?>
                            <?php if ($panel['icon_color']) { ?> onmouseout="this.style.color='#<?php echo $panel['icon_color']; ?>'" <?php } ?>></i>
                        <?php
                        if ($_link) {
                            echo '</a>';
                        } ?>
                        <div class="block-title">
                            <?php  if ($_link) {
                                echo '<a href="' . $_link . '">';
                            }
                            echo $panel['main_title'];

                            if ($_link) {
                                echo '</a>';
                            } ?>
                        </div>

                        <div class="subtitle"><?php echo (isset($panel['sub_title'])) ? $panel['sub_title'] : ''; ?></div>

                        <?php if (isset($panel['content'])): ?>
                            <div class="feat-block-content">
                                <?php echo mvb_parse_content($panel['content'], TRUE); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($panel['read_more']) && $_link) { ?>
							<?php echo DFD_HTML::read_more($_link, (isset($panel['read_more_text']))?$panel['read_more_text']:''); ?>
                        <?php } ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>