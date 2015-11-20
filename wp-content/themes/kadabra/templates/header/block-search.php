<?php if (strcmp(DfdThemeSettings::get('show_search_form'),'1')===0) : ?>
<div class="form-search-wrap">
	<?php echo get_template_part('templates/searchform', 'mini'); ?>
</div>
<?php endif; ?>