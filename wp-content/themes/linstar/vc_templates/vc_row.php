<?php

$output = $king_id = $king_bg_image = $king_bg_repeat = $king_class = $king_class_container = $king_row_type = '';
extract(shortcode_atts(array(
    'king_id'        		=> '',
    'bg_image'       	=> '',
    'king_bg_repeat'      => '',
    'bg_color' 			=> '',
    'king_padding_top'    => '',
    'king_padding_bottom' => '',
    'king_class'   		=> '',
    'king_class_container'   => '',
    'king_row_type'       => ''
), $atts));

wp_enqueue_script( 'wpb_composer_front_js' );


$king_class = $this->getExtraClass($king_class);

	$style = '';
    // BG Image
    $has_image = false;
    if((int)$bg_image > 0 && ($image_url = wp_get_attachment_url( $bg_image, 'large' )) !== false) {
        $has_image = true;
        $style .= "background-image: url(".$image_url.");";
    }
    if(!empty($king_bg_repeat) && $has_image) {
        if($king_bg_repeat === 'no-repeat') {
        } elseif($king_bg_repeat === 'repeat-x') {
            $style .= "background-repeat:repeat-x;";
        } elseif($king_bg_repeat === 'repeat-y') {
            $style .= 'background-repeat: repeat-y;';
        } elseif($king_bg_repeat === 'repeat') {
            $style .= 'background-repeat: repeat;';
        }
    }

    // Padding
    $padding = '';
    if(!empty($king_padding_top)) {
        $padding .= 'padding-top: '.$king_padding_top.'px;';
    }
    if(!empty($king_padding_bottom)) {
        $padding .= 'padding-bottom: '.$king_padding_bottom.'px;';
    }

if ($king_id=='') {
    $king_id_rand = rand(100000,900000);
    $king_id = 'king-'.$king_id_rand;
}

$css_class =  apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
							 'wpb_row '.get_row_css_class().$king_class.' '.
							  ($king_row_type!='container'?' '.$king_row_type:''), $this->settings['base']);	

if( $this->settings( 'base' ) === 'vc_row_inner' ){
	$css_class .= 'king-innerRow-container';
}
						 
$output .= '<div id="'.$king_id.'" class="'.$css_class.'"';
if( !empty($style) )$output .= ' style="'.$style.'"';
$output .= '>';
	
	if( $this->settings( 'base' ) !== 'vc_row_inner' ){
		$output .= '<div class="'.($king_row_type!='container_full'?'container':'').' '.$king_class_container.'" style="'.$padding.'">';
			   $output .= wpb_js_remove_wpautop($content);
		$output .= '<div class="clear"></div></div>';
	}else{
		$output .= wpb_js_remove_wpautop($content);
	}	

$output .= '</div>'.$this->endBlockComment('row');

print( $output );