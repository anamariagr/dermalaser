<?php
/**
 * Luchiana-Child functions and definitions
 *
 * @package luchiana-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** Enqueue the child theme stylesheet **/
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'luchiana-child-style', get_stylesheet_directory_uri() . '/style.css', PHP_INT_MAX );
}, PHP_INT_MAX );
