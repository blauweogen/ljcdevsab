<?php require_once( '../../../../../wp-load.php' ); ?>
<?php require_once '../config.php'; ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Insert styled button</title>
	
	<link type="text/css" rel="stylesheet" href="<?php echo admin_url('css/wp-admin.css'); ?>" />
	<link type="text/css" rel="stylesheet" href="css/popup-style.css?<?php echo date('YmdHis'); ?>" />
	<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/icons/css/icon-font-style.css?<?php echo date('YmdHis'); ?>" />
	<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/styled-button.css?<?php echo date('YmdHis'); ?>" />
	
	<script type="text/javascript" src="<?php echo includes_url('/js/jquery/jquery.js') ?>"></script>
	<script type="text/javascript" src="<?php echo includes_url('/js/tinymce/tiny_mce_popup.js') ?>"></script>
	<script type="text/javascript" src="js/popup.js?<?php echo date('YmdHis'); ?>"></script>
	
	<script type="text/javascript">
		var button_shortcode_name = '<?php echo $button_shortcode_name; ?>';
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
</head>
<body class="wp-admin styled-button-body">
	<form method="post" action="" onsubmit="return StyledButtonPopup.insert(jQuery(this));">
		<div class="mceContentBody">
			<?php if (!empty($button_settings)) : ?>
				<?php foreach ($button_settings as $setting) : ?>
				<p>
					<label><?php echo $setting['label']; ?></label>
					
					<?php if ($setting['type'] === 'text') : ?>
						<input id="<?php echo $setting['id']; ?>" type="text" name="<?php echo $setting['name']; ?>" value="" />
					<?php endif; ?>
					
					<?php if ($setting['type'] === 'select') : ?>
					<select id="<?php echo $setting['id']; ?>" name="<?php echo $setting['name']; ?>">
						<?php foreach ($setting['options'] as $option) : ?>
							<option value="<?php echo $option['class']; ?>" 
								<?php if (isset($option['default']) && $option['default']===true) echo 'selected="selected"'?>>
									<?php echo $option['label'] ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php endif; ?>
				</p>
				<?php endforeach; ?>
			<?php endif; ?>
			<div id="preview" style="text-align: center; min-height: 50px;"></div>
		</div>
		
		<div class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
			<input type="button" id="apply" name="apply" value="{#preview.preview_desc}" onclick="return StyledButtonPopup.insert(jQuery('body.styled-button-body>form'), true);" />
		</div>
	</form>
</body>
</html>
