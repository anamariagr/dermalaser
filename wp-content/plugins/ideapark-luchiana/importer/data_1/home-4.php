<?php
defined( 'ABSPATH' ) || exit;

global $theme_home;

$footer_page_id = ( $page = ideapark_get_page_by_title( 'Footer (violet)', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$advert_bar_page_id = ( $page = ideapark_get_page_by_title( 'Features', OBJECT, 'html_block' ) ) ? $page->ID : 0;
$home_page_id   = ( $page = ideapark_get_page_by_title( 'Branded Home' ) ) ? $page->ID : 0;

$mods                                = [];

$mods['header_type']                    = 'header-type-4';
$mods['background_color']               = '#F6EEFF';
$mods['accent_color']                   = '#622299';
$mods['header_top_background_color']    = '#FFFFFF';
$mods['header_top_color']               = '#000000';
$mods['header_top_accent_color']        = '#622299';
$mods['header_color_menu']              = '#FFFFFF';
$mods['header_bg_color_menu']           = '#471D6B';
$mods['featured_badge_color']           = '#AD7CD7';
$mods['sale_badge_color']               = '#622299';
$mods['new_badge_color']                = '#913BDB';
$mods['mobile_header_color']            = '#FFFFFF';
$mods['mobile_header_background_color'] = '#471D6B';
$mods['shadow_color_mobile']            = '#250C3C';
$mods['header_blocks_3']                = 'menu=1|social=0|phone=1|email=1|address=0|hours=1|other=0';
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

if ( $term = get_term_by('name', 'Catalog', 'nav_menu') ) {
	$mods['nav_menu_locations']['primary'] = $term->term_id;
}


$theme_home = [
	'title'      => __( 'Branded', 'ideapark-luchiana' ),
	'screenshot' => 'home-4.jpg',
	'url'        => 'https://parkofideas.com/luchiana/demo/home-4/',
	'mods'       => $mods,
	'options'    => $options,
];