<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @package  WooCommerce/Templates
 * @version  10.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs        = apply_filters( 'woocommerce_product_tabs', [] );
$is_expand_on_mobile = ideapark_mod( 'expand_product_tabs' ) && in_array( ideapark_mod( 'product_page_layout' ), [
		'layout-1',
		'layout-2'
	] );
if ( ! empty( $product_tabs ) ) { ?>

	<div
		class="c-product__tabs woocommerce-tabs wc-tabs-wrapper <?php if ( ideapark_mod( 'product_page_layout' ) == 'layout-1' ) { ?><?php if ( ideapark_mod( 'wide_tabs_area' ) ) { ?> c-product__tabs--wide<?php } else { ?> c-product__tabs--desktop<?php } ?><?php } ?>">
		<div
			class="c-product__tabs-wrap<?php if ( $is_expand_on_mobile ) { ?> c-product__tabs-wrap--expand-mobile<?php } ?>">
			<ul class="c-product__tabs-list tabs wc-tabs js-tabs-list h-carousel h-carousel-small h-carousel--hover h-carousel--dots-hide"
			    role="tablist">
				<?php $index = 0; ?>
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<li class="c-product__tabs-item <?php echo esc_attr( $key ); ?>_tab"
					    id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab"
					    aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a data-index="<?php echo esc_attr( $index ++ ); ?>"
						   class="c-product__tabs-item-link js-tabs-item-link"
						   href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo wp_kses( apply_filters( 'woocommerce_product_' . $key . '_tab_title', preg_replace( '~\((\d+)\)~', '<sup class="c-product__rev-counter">\\1</sup>', trim( preg_replace( '~\(0\)~', '', $product_tab['title'] ) ) ), $key ), [ 'sup' => [ 'class' => true ] ] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php $is_first = true; ?>
		<?php foreach ( $product_tabs as $key => $product_tab ) {
			if ( isset( $product_tab['callback'] ) ) {
				ob_start();
				call_user_func( $product_tab['callback'], $key, $product_tab );
				$tab_content = trim( ob_get_clean() );
				if ( ! $tab_content ) {
					continue;
				}
			} else {
				continue;
			}
			?>
			<?php if ( $is_expand_on_mobile ) { ?>
				<div
					class="c-product__tabs-title"><?php echo wp_kses( apply_filters( 'woocommerce_product_' . $key . '_tab_title', preg_replace( '~\((\d+)\)~', '<sup class="c-product__rev-counter">\\1</sup>', $product_tab['title'] ), $key ), [ 'sup' => [ 'class' => true ] ] ); ?></div>
			<?php } ?>
			<div
				class="c-product__tabs-panel <?php if ( $is_expand_on_mobile ) { ?>c-product__tabs-panel--expand-mobile<?php } ?> woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel <?php if ( $key == 'description' ) { ?>entry-content<?php } ?> wc-tab <?php if ( $is_first ) { ?> current visible<?php $is_first = false;
				} ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
				aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<?php echo ideapark_wrap( $tab_content ); ?>
			</div>
		<?php } ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>
<?php }