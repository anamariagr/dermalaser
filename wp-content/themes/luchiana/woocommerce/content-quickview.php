<?php
/**
 *    The template for displaying quickview product content
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

ideapark_mod_set_temp( '_is_quickview', true );
ideapark_mod_set_temp( 'shop_product_modal', false );
?>

<div
	id="product-<?php the_ID(); ?>" <?php wc_product_class( 'c-product--quick-view' . ( ! $product->is_purchasable() ? ' c-product--not-purchasable' : '' ), $product ); ?>>
	<div class="c-product__quick-view-col-1">
		<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
	</div>
	<div class="c-product__quick-view-col-2 summary entry-summary">
		<div class="c-product__wrap c-product__wrap--quick-view">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
		</div>
	</div>
</div>
