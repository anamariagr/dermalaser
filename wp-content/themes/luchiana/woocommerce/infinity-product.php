<?php
defined( 'ABSPATH' ) || exit;
ideapark_mod_set_temp( '_hide_grid_wrapper', true );
if ( is_product_taxonomy() ) {
	ideapark_mod_set_temp( '_archive_attribute_id', get_queried_object_id() );
}
$with_sidebar        = ideapark_mod( 'shop_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
$with_filter_desktop = ! ideapark_mod( 'shop_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
$with_filter_mobile  = is_active_sidebar( 'filter-sidebar' ) || ideapark_mod( 'single_sidebar' ) && is_active_sidebar( 'shop-sidebar' );

ideapark_mod_set_temp( '_with_sidebar', $with_sidebar );
ideapark_mod_set_temp( '_with_filter_desktop', $with_filter_desktop );
ideapark_mod_set_temp( '_with_filter', $with_filter_mobile );

$layout = ideapark_mod( 'product_grid_layout' );
if ( $layout == '3-per-row' && ideapark_mod( '_with_sidebar' ) ) {
	$layout = '4-per-row';
}
ideapark_mod_set_temp( '_product_layout', $layout );
ideapark_mod_set_temp( '_product_layout_class', ( $with_sidebar ? 'c-product-grid__item--sidebar ' : '' ) . 'c-product-grid__item--' . $layout . ( $layout != 'compact' ? ( ' c-product-grid__item--normal c-product-grid__item--' . ideapark_mod( 'atc_button_visibility' ) . ideapark_grid_class_mobile( $layout ) ) : '' ) );

ob_start();
if ( woocommerce_product_loop() ) {
	woocommerce_product_loop_start();
	if ( ! function_exists( 'wc_get_loop_prop' ) || wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 *
			 * @hooked WC_Structured_Data::generate_product_data() - 10
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}
	woocommerce_product_loop_end();
}
$products = ob_get_clean();

ideapark_infinity_paging();
wp_send_json( [ 'products' => $products, 'paging' => ideapark_mod( '_infinity_paging' ) ] );
