<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version        10.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout = ideapark_mod( '_product_layout' );

if ( in_array( $name = wc_get_loop_prop( 'name' ), [
	'related',
	'up-sells',
	'cross-sells',
	'recently'
] ) ) {
	$layout = ideapark_mod( $name == 'recently' ? 'recently_grid_layout' : 'related_product_layout' );
	ideapark_mod_set_temp( '_product_layout', $layout );
	ideapark_mod_set_temp( '_product_layout_class', 'c-product-grid__item--' . $layout . ( $layout != 'compact' ? ( ' c-product-grid__item--normal c-product-grid__item--' . ideapark_mod( 'atc_button_visibility' ) . ideapark_grid_class_mobile( $layout ) ) : '' ) );
	ideapark_mod_set_temp( '_product_layout_list_class', ( ideapark_mod( 'related_product_carousel' ) ? 'c-ip-woocommerce-carousel__list c-ip-woocommerce-carousel__list--' . $layout . ' c-ip-woocommerce-carousel__list--' . ideapark_mod( '_products_count' ) . ' js-woocommerce-carousel h-carousel h-carousel--default-dots h-carousel--flex h-carousel--round-light h-carousel--outside h-carousel--dots-hide' : '' ) );
}

if ( ! $layout ) {
	$layout = ideapark_mod( 'product_grid_layout' );
	if ( $layout == '3-per-row' && ideapark_mod( '_with_sidebar' ) ) {
		$layout = '4-per-row';
	}
	ideapark_mod_set_temp( '_product_layout', $layout );
	ideapark_mod_set_temp( '_product_layout_class', 'c-product-grid__item--' . $layout . ( $layout != 'compact' ? ( ' c-product-grid__item--normal c-product-grid__item--' . ideapark_mod( 'atc_button_visibility' ) . ideapark_grid_class_mobile( $layout ) ) : '' ) );
}

ideapark_mod_set_temp( '_is_product_loop', true );
?>
<?php if ( ! ideapark_mod( '_hide_grid_wrapper' ) ) { ?>
<div
	class="c-product-grid__wrap c-product-grid__wrap--<?php echo esc_attr( $layout ); ?> <?php echo ideapark_grid_class_mobile( $layout, 'c-product-grid__wrap' ); ?><?php ideapark_class( ideapark_mod( '_with_sidebar' ), 'c-product-grid__wrap--sidebar', '' ); ?>">
	<div
		class="c-product-grid__list c-product-grid__list--<?php echo esc_attr( $layout ); ?> <?php echo esc_attr( ideapark_mod( '_product_layout_list_class' ) ); ?>">
		<?php } ?>
<!-- grid-start -->