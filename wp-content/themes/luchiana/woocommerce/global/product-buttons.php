<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $product;
/**
 * @var $product WC_Product
 **/
if ( ideapark_mod( 'shop_modal' ) || ideapark_mod( 'wishlist_page' ) && ideapark_mod( 'wishlist_grid_button' ) ) { ?>
	<div class="c-product-grid__thumb-button-list <?php if ( ! ideapark_mod( 'show_add_to_cart' ) ) { ?>c-product-grid__thumb-button-list--no-atc<?php } ?>">
		<?php 
		$is_single = ( ideapark_mod( 'shop_modal' ) ? 1 : 0 ) + ( ideapark_mod( 'wishlist_page' ) && ideapark_mod( 'wishlist_grid_button' ) ? 1 : 0 ) == 1;
		
		// Obtener el permalink del producto
		$permalink = get_permalink( $product->get_id() );

		// Extraer el slug del producto (última parte del permalink)
		$path = parse_url( $permalink, PHP_URL_PATH );
		$product_slug = trim( basename( $path ) );

		// URL base de Shopify
		$url_shophy = 'https://dermalaser-co.myshopify.com/products';

		// Construir la nueva URL para Shopify
		$new_url = $url_shophy . '/' . $product_slug;
		?>

		<?php if ( ideapark_mod( 'shop_modal' ) ) { ?>
			<button
				class="h-cb c-product-grid__thumb-button<?php if ( $is_single ) { ?> c-product-grid__thumb-button--single<?php } ?> js-grid-zoom"
				type="button"
				data-lang="<?php echo esc_attr( ideapark_current_language() ); ?>"
				data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
				aria-label="<?php esc_attr_e('Quick View', 'luchiana'); ?>"
				onclick="window.location.href='<?php echo esc_url( $new_url ); ?>'">
				<i class="ip-eye c-product-grid__icon c-product-grid__icon--normal c-product-grid__icon--quickview"></i><i
					class="ip-eye_hover c-product-grid__icon c-product-grid__icon--hover"></i>
			</button>
		<?php } ?>

		<?php if ( ideapark_mod( 'wishlist_page' ) && ideapark_mod( 'wishlist_grid_button' ) ) { ?>
			<?php ideapark_wishlist()->ideapark__button( 'h-cb c-product-grid__thumb-button' . ( $is_single ? ' c-product-grid__thumb-button--single' : '' ), 'c-product-grid__icon c-product-grid__icon--wishlist' ); ?>
		<?php } ?>
	</div>
<?php }
