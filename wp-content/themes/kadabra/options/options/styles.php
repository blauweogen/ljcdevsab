<?php
// Function parses header styles

/*
 * Backgrounds
 */
if (DfdThemeSettings::get("wrapper_bg_color")) {
    echo '#change_wrap_div{ background-color: '.DfdThemeSettings::get("wrapper_bg_color").' !important; }';
}
if (DfdThemeSettings::get("wrapper_bg_image")) {
    echo '#change_wrap_div{ background-image: url("'.DfdThemeSettings::get("wrapper_bg_image").'") !important; } ';
}
if (DfdThemeSettings::get("wrapper_custom_repeat")) {
    echo '#change_wrap_div{ background-repeat: '.DfdThemeSettings::get("wrapper_custom_repeat").' !important; }';
}

// body
if (DfdThemeSettings::get("body_bg_color")) {
    echo 'body{ background-color: '.DfdThemeSettings::get("body_bg_color").' !important; }';
}
if (DfdThemeSettings::get("body_bg_image")) {
    echo 'body{ background-image: url("'.DfdThemeSettings::get("body_bg_image").'") !important; }';
}
if (DfdThemeSettings::get("body_custom_repeat")) {
    echo 'body{ background-repeat: '.DfdThemeSettings::get("body_custom_repeat").' !important; }';
}
if (DfdThemeSettings::get("body_bg_fixed")) {
    echo 'body{ background-attachment: fixed !important; } ';
}

// footer
if (DfdThemeSettings::get("footer_font_color")) {
    echo '#footer, #footer .contacts-widget p{ color: '.DfdThemeSettings::get("footer_font_color").'} ';
}
if (DfdThemeSettings::get("footer_bg_color")) {
    echo '#footer{ background-color: '.DfdThemeSettings::get("footer_bg_color").'} ';
}
if (DfdThemeSettings::get("footer_bg_image")) {
    echo '#footer{ background-image: url("'.DfdThemeSettings::get("footer_bg_image").'")} ';
}
if (DfdThemeSettings::get("footer_custom_repeat")) {
    echo '#footer{ background-repeat: '.DfdThemeSettings::get("footer_custom_repeat").'} ';
}

// sub footer
if (DfdThemeSettings::get("sub_footer_font_color")){
    echo '#sub-footer, #sub-footer a, #sub-footer .footer-menu { color: '.DfdThemeSettings::get("sub_footer_font_color").' !important; } ';
}
if (DfdThemeSettings::get("sub_footer_bg_color")){
    echo '#sub-footer { background-color: '.DfdThemeSettings::get("sub_footer_bg_color").' !important; } ';
}
if (DfdThemeSettings::get("sub_footer_bg_image")){
    echo '#sub-footer { background-image: url("'.DfdThemeSettings::get("sub_footer_bg_image").'") !important; } ';
}

if (!DfdThemeSettings::get("sub_footer_bg_image") && !DfdThemeSettings::get("sub_footer_bg_color")) {
	echo '#sub-footer { background-image: url("../assets/img/sub.jpg") !important; background-color: rgba(0, 0, 0, 0); } ';
}
if (DfdThemeSettings::get("sub_footer_custom_repeat")){
    echo '#sub-footer { background-repeat: '.DfdThemeSettings::get("sub_footer_custom_repeat").' !important; } ';
}

/*
 * Custom CSS
 */
echo DfdThemeSettings::get("custom_css");
