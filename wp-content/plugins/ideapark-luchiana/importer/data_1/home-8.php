<?php
defined( 'ABSPATH' ) || exit;

global $theme_home;

$footer_page_id = ( $page = ideapark_get_page_by_title( 'Footer (home 8)', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$home_page_id   = ( $page = ideapark_get_page_by_title( 'Natural Home' ) ) ? $page->ID : 0;

$mods = [];

$mods['header_color_dark'] = '#FFFFFF';
$mods['accent_color']      = '#FFD796';
$mods['logo']              = '';
$mods['theme_font_header'] = 'custom-Clash Display Regular';

if ( $footer_page_id ) {
	$mods['footer_page'] = $footer_page_id;
}

$options = [];
if ( $home_page_id ) {
	$options['page_on_front'] = $home_page_id;
}

$theme_home = [
	'title'      => __( 'Natural', 'ideapark-luchiana' ),
	'screenshot' => 'home-8.jpg',
	'url'        => 'https://parkofideas.com/luchiana/demo/home-8/',
	'mods'       => $mods,
	'options'    => $options,
];