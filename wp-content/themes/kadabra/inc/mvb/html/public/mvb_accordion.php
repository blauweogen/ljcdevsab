<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="'.$effects.'" data-gen-offset="bottom-in-view';
} else {
    $cr_effect ='';
}

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

?>

<div class="accodion_module module <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect ?> " <?php echo $addition_css_styles; ?>>


    <?php include(dirname(__FILE__).'/_title.php');?>


    <ul class="accordion">

        <?php $i = 1; ?>

		<?php if (!empty($r_items)) : ?>
		
        <?php foreach ($r_items as $item): ?>
            <li <?php if($i=='1'): ?> class="active"<?php endif; ?>>
                <div class="title">
                    <?php if (isset($item['icon']) && !empty($item['icon'])) : ?>
			    <div class="icon-wrap">
				    <i class="<?php echo $item['icon'] ?>"></i>
			    </div>
		    <?php endif; ?>
                    <span class="tab-title">
                        <?php echo $item['panel_title'] ?>
                    </span>
		    <span class="icon"></span>
                </div>
                <div class="content">
                    <?php echo mvb_parse_content_html($item['content'], TRUE) ?>
                </div>
            </li>

            <?php $i++; ?>
        <?php endforeach; ?>
			
		<?php endif; ?>

    </ul>

</div>