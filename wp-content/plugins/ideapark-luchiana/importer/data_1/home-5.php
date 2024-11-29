<?php
defined( 'ABSPATH' ) || exit;

global $theme_home;

$footer_page_id     = ( $page = ideapark_get_page_by_title( 'Footer (green)', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$advert_bar_page_id = ( $page = ideapark_get_page_by_title( 'Special Offers', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$home_page_id       = ( $page = ideapark_get_page_by_title( 'Organic Home' ) ) ? $page->ID : 0;

$mods = [];

$mods['header_type']                    = 'header-type-5';
$mods['accent_color']                   = '#80AA27';
$mods['header_top_background_color']    = '#FFFFFF';
$mods['header_top_color']               = '#000000';
$mods['header_top_accent_color']        = '#80AA27';
$mods['header_color_menu']              = '#FFFFFF';
$mods['header_bg_color_menu']           = '#000000';
$mods['mobile_header_color']            = '#FFFFFF';
$mods['mobile_header_background_color'] = '#000000';
$mods['shadow_color_mobile']            = '#182508';
$mods['header_blocks_3']                = 'social=0|phone=1|email=1|address=0|hours=1|other=0|menu=1';
$mods['header_blocks_layout']           = 'blocks-last';
$mods['logo']                           = '';

if ( $footer_page_id ) {
	$mods['footer_page'] = $footer_page_id;
}


if ( $advert_bar_page_id ) {
	$mods['header_advert_bar_page'] = $advert_bar_page_id;
}

$options = [];
if ( $home_page_id ) {
	$options['page_on_front'] = $home_page_id;
}

if ( $term = get_term_by( 'name', 'Categories', 'nav_menu' ) ) {
	$mods['nav_menu_locations']['primary'] = $term->term_id;
}


$theme_home = [
	'title'      => __( 'Organic', 'ideapark-luchiana' ),
	'screenshot' => 'home-5.jpg',
	'url'        => 'https://parkofideas.com/luchiana/demo/home-5/',
	'mods'       => $mods,
	'options'    => $options,
];