<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @var $product WC_Product
 **/
global $product;

$separator  = ' • ';
$categories = [];
$brands     = [];

if ( ideapark_mod( 'shop_category' ) ) {
	$term_ids = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
	foreach ( $term_ids as $term_id ) {
		$categories[] = get_term_by( 'id', $term_id, 'product_cat' );
	}
}

if ( ideapark_mod( 'show_product_grid_brand' ) ) {
	if ( $terms = ideapark_brands() ) {
		foreach ( $terms as $term ) {
			$brands[] = '<a class="c-product-grid__brand" href="' . esc_url( get_term_link( $term->term_id, ideapark_mod( 'product_brand_attribute' ) ) ) . '">' . esc_html( $term->name ) . '</a>';
		}
		$brands = array_filter( $brands );
	}
}

if ( $categories || $brands ) { ?>
	<div class="c-product-grid__brands">
		<?php
		if ( $categories ) {
			ideapark_category( $separator, $categories, 'c-product-grid__brand c-product-grid__brand--category' );
		}
		if ( $brands ) {
			echo ideapark_wrap( $categories ? $separator : '' ) . implode( $separator, $brands );
		}
		?>
	</div>
<?php }