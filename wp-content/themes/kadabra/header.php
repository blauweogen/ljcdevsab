<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js ie lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js ie lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js ie lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js ie lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-ie" <?php language_attributes(); ?>> <!--<![endif]-->

<head>

    <meta charset="utf-8">

    <title>

        <?php bloginfo('name'); ?> <?php wp_title('|'); ?>

    </title>

<?php if(DfdThemeSettings::get("custom_favicon")) : ?>
    <link rel="icon" type="image/png" href="<?php echo DfdThemeSettings::get("custom_favicon") ?>" />
<?php endif; ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--[if lte IE 9]>
        <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]>
        <script src="<?php echo get_template_directory_uri(); ?>/assets/js/excanvas.compiled.js"></script>
    <![endif]-->

	<?php dfd_custom_page_style(); ?>

    <?php wp_head(); ?>

</head>

