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
	$addition_css_styles = 'style="'.$addition_css_styles.'"';
}

# Divider Style
$dividers = array_keys(MVB_Dividers::dividers());
$divider_style = (!empty($divider) && in_array($divider, $dividers)) ? $divider : $dividers[0];

# Link
if (isset($link_url) && $link_url != '') {
	$_link_url = $link_url;
} elseif( is_numeric($page_id) AND $page_id > 0 ){
	$_link_url = get_page_link($page_id);
}

if (!empty($_link_url)) {
	$_fields_options = MVB_Dividers::fields();
	$icon = '';
	if (!empty($link_icon)) {
		$icon = '<i class="'.$link_icon.'"></i>';
	}
	
	if (empty($link_text)) {
		$link_text = $_fields_options['link_text']['default'];
	}
	
	$link_html = '<a href="'.$_link_url.'" class="read-more"><span>'.$icon.' '.$link_text.'</span></a>';
} else {
	$link_html = '';
}

?>
<div class="module module-dividers <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>" <?php echo $addition_css_styles; ?>>
    <?php include(dirname(__FILE__).'/_title.php'); ?>

    <div class="divider-hr divider-hr-<?php echo $divider_style; ?>"><?php echo $link_html; ?></div>
</div>
