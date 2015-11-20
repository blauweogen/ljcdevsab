<?php
if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="' . $effects . '" data-gen-offset="bottom-in-view';
} else {
    $cr_effect = '';
}

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
    $addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
    $addition_css_styles = 'style="' . $addition_css_styles . '"';
}

$icon_style = '';
?>

<div class="my_skills_widget module <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__) . '/_title.php'); ?>

    <?php
    if (isset($animate_skill) && $animate_skill) {
	$animate = 'cr-animate-gen';
    } else {
	$animate = '';
    }
    ?>
    <div class="wrap">

<?php foreach ($r_items as $skill): ?>
    	<div class="progress-wrap">
	    
			<div class="progress <?php echo $animate; ?>" data-gen="expand">
				<div class="meter" style="width: <?php echo $skill['skill_percent']; ?>%">
				<?php

				if (isset($skill['icon']) && !empty($skill['icon'])) :


					if (isset($skill['icon_color']) && $skill['icon_color'] != '') {
					$icon_style .= 'color: #' . $skill['icon_color'] . '; ';
					}
					?>

					<div class="icon-wrap">
					<i style="<?php if (isset($icon_style)) echo $icon_style; ?>" class="<?php echo $skill['icon'] ?>"></i>
					</div>

					<?php
					$progress_class = 'progress-with-icon';
				else:
					$progress_class = '';
				endif;
				?>

					<div class="<?php echo $progress_class; ?>">
						<label><?php echo $skill['skill_title']; ?></label>
						<span class="skill-percent"><?php echo $skill['skill_percent']; ?><span>%</span></span>
					</div>
				</div>
    	    </div>
    	</div>

<?php endforeach; ?>
    </div>

    <div class="me-wrap">
	<?php if (!empty($image)) { ?>
    	<div class="avatar">

		<?php
		$img = wp_get_attachment_url($image);
		$article_image = aq_resize($img, 80, 80, true);
		?>
    	    <img src="<?php echo $article_image ?>" alt="<?php echo $main_title; ?>">
    	</div>
	    <?php
	}
	if (!empty($name)) {
	    echo '<div class="block-title">' . $name . '</div>';
	}
	if (!empty($client_job)) {
	    echo '<div class="dopinfo">' . $client_job . '</div>';
	}
	if (!empty($description)) {
	    echo '<div class="text">' . mvb_parse_content($description) . '</div>';
	}
	?>
    </div>
</div>
