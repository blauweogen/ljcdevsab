<?php

$button_shortcode_name = 'styled_button';

$button_settings = array(
	// Title
	array(
		'id' => 'title',
		'label' => 'Title',
		'name' => 'title',
		'type' => 'text',
	),	
	// Href
	array(
		'id' => 'href',
		'label' => 'Href',
		'name' => 'href',
		'type' => 'text',
	),
	// Display
	/*array(
		'id' => 'display',
		'label' => 'Display',
		'name' => 'display',
		'type' => 'select',
		'options' => array(
			array(
				'label' => 'Inline',
				'default' => true,
				'class' => 'inline',
			),
			array(
				'label' => 'Fullwidth',
				'class' => 'block',
			),
		)
	),*/
	// Size
//	14px, 16px, 20px, 24px, 26px, 28px, 30px
	array(
		'id' => 'size',
		'label' => 'Size',
		'name' => 'size',
		'type' => 'select',
		'options' => array(
			array(
				'label' => '14',
				'default' => true,
				'class' => 'size-14',
			),
			array(
				'label' => '16',
				'class' => 'size-16',
			),
			array(
				'label' => '20',
				'class' => 'size-20',
			),
			array(
				'label' => '24',
				'class' => 'size-24',
			),
			array(
				'label' => '26',
				'class' => 'size-26',
			),
			array(
				'label' => '28',
				'class' => 'size-28',
			),
			array(
				'label' => '30',
				'class' => 'size-30',
			),
		)
	),
	// Color
	array(
		'id' => 'color',
		'label' => 'Color',
		'name' => 'color',
		'type' => 'select',
		'options' => array(
			array(
				'label' => 'Yellow',
				'default' => true,
				'class' => 'color-yellow',
			),
			array(
				'label' => 'Blue',
				'class' => 'color-blue',
			),
			array(
				'label' => 'White',
				'class' => 'color-white',
			),
			array(
				'label' => 'Gray',
				'class' => 'color-gray',
			),
		)
	),
	// Style
	array(
		'id' => 'style',
		'label' => 'Style',
		'name' => 'style',
		'type' => 'select',
		'options' => array(
			array(
				'label' => 'Style 1',
				'class' => 'style-1',
			),
			array(
				'label' => 'Style 2',
				'default' => true,
				'class' => 'style-2',
			),
			array(
				'label' => 'Style 3',
				'class' => 'style-3',
			),
			array(
				'label' => 'Style 4',
				'class' => 'style-4',
			),
			array(
				'label' => 'Style 5',
				'class' => 'style-5',
			),
			array(
				'label' => 'Style 6',
				'class' => 'style-6',
			),
			array(
				'label' => 'Style 7',
				'class' => 'style-7',
			),
			array(
				'label' => 'Style 8',
				'class' => 'style-8',
			),
			// http://tympanus.net/Development/CreativeLinkEffects/
			array(
				'label' => 'Style 9',
				'class' => 'style-9',
			),
			array(
				'label' => 'Style 10',
				'class' => 'style-10',
			),
			array(
				'label' => 'Style 11',
				'class' => 'style-11',
			),
			array(
				'label' => 'Style 12',
				'class' => 'style-12',
			),
			array(
				'label' => 'Style 13',
				'class' => 'style-13',
			),
			array(
				'label' => 'Style 14',
				'class' => 'style-14',
			)
		)
	),
);
