<?php
defined( 'ABSPATH' ) || exit;

global $theme_home;

$footer_page_id = ( $page = ideapark_get_page_by_title( 'Footer (white)', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$home_page_id   = ( $page = ideapark_get_page_by_title( 'Wide Home' ) ) ? $page->ID : 0;

$mods                                = [];
$mods['header_type']                 = 'header-type-4';
$mods['header_top_background_color'] = '#E4C1B1';
$mods['header_top_color']            = '#000000';
$mods['header_top_accent_color']     = '#FFFFFF';
if ( $footer_page_id ) {
	$mods['footer_page'] = $footer_page_id;
}

$options = [];
if ( $home_page_id ) {
	$options['page_on_front'] = $home_page_id;
}

$theme_home = [
	'title'      => __( 'Wide', 'ideapark-luchiana' ),
	'screenshot' => 'home-3.jpg',
	'url'        => 'https://parkofideas.com/luchiana/demo/home-3/',
	'mods'       => $mods,
	'options'    => $options,
];