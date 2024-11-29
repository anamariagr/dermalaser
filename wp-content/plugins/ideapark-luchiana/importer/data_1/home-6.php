<?php
defined( 'ABSPATH' ) || exit;

global $theme_home;

$footer_page_id = ( $page = ideapark_get_page_by_title( 'Footer (white)', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$home_page_id   = ( $page = ideapark_get_page_by_title( 'Salon Home' ) ) ? $page->ID : 0;

$mods                                = [];
$mods['header_color_light']     = '#000000';
$mods['mobile_header_color_tr'] = '#000000';
if ( $footer_page_id ) {
	$mods['footer_page'] = $footer_page_id;
}

$options = [];
if ( $home_page_id ) {
	$options['page_on_front'] = $home_page_id;
}

$theme_home = [
	'title'      => __( 'Salon', 'ideapark-luchiana' ),
	'screenshot' => 'home-6.jpg',
	'url'        => 'https://parkofideas.com/luchiana/demo/home-6/',
	'mods'       => $mods,
	'options'    => $options,
];