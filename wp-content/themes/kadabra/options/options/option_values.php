<?php

function dfd_page_layouts() {
	//Must provide key => value(array:title|img) pairs for radio options
	return array(
		'1col-fixed' => array(
			'title' => 'No sidebars',
			'img' => Redux_OPTIONS_URL . 'img/1col.png'
		),
		'2c-l-fixed' => array(
			'title' => 'Sidebar on left',
			'img' => Redux_OPTIONS_URL . 'img/2cl.png'
		),
		'2c-r-fixed' => array(
			'title' => 'Sidebar on right',
			'img' => Redux_OPTIONS_URL . 'img/2cr.png'
		),
		'3c-l-fixed' => array(
			'title' => '2 left sidebars',
			'img' => Redux_OPTIONS_URL . 'img/3cl.png'
		),
		'3c-fixed' => array(
			'title' => 'Sidebar on either side',
			'img' => Redux_OPTIONS_URL . 'img/3cc.png'
		),
		'3c-r-fixed' => array(
			'title' => '2 right sidebars',
			'img' => Redux_OPTIONS_URL . 'img/3cr.png'
		),
	);
}

function dfd_headers_type() {
	return array(
		'1'			=> __('Header 1' , 'dfd'),
		'1a'		=> __('Header 1a' , 'dfd'),
		'1a1stlogo'		=> __('Header 1a with 1st logo' , 'dfd'),
		'1b'		=> __('Header 1b' , 'dfd'),
		'1b1stlogo'		=> __('Header 1b with 1st logo' , 'dfd'),
	);
}

function dfd_header_layouts() {
	return array(
		'fullwidth' => __('Fullwidth', 'dfd'),
		'boxed' => __('Boxed', 'dfd'),
	);
}
