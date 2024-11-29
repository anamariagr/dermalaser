<?php
defined( 'ABSPATH' ) || exit;

global $theme_home;

$footer_page_id = ( $page = ideapark_get_page_by_title( 'Footer (white)', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$home_page_id   = ( $page = ideapark_get_page_by_title( 'Boxed Home' ) ) ? $page->ID : 0;

$mods                                = [];
$mods['header_type']                 = 'header-type-2';
$mods['ajax_search_limit']           = 4;
$mods['header_top_background_color'] = '#FFFFFF';
$mods['header_top_color']            = '#000000';
if ( $footer_page_id ) {
	$mods['footer_page'] = $footer_page_id;
}

$options = [];
if ( $home_page_id ) {
	$options['page_on_front'] = $home_page_id;
}

$theme_home = [
	'title'      => __( 'Boxed', 'ideapark-luchiana' ),
	'screenshot' => 'home-2.jpg',
	'url'        => 'https://parkofideas.com/luchiana/demo/home-2/',
	'mods'       => $mods,
	'options'    => $options,
];