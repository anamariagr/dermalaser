<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
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
	exit; // Exit if accessed directly
}
?>

<?php if ( is_product() ? is_active_sidebar( 'product-sidebar' ) : ( is_active_sidebar( 'shop-sidebar' ) || is_active_sidebar( 'filter-sidebar' ) ) ) { ?>
	<?php if ( ! is_product() ) {
		$with_sidebar        = ideapark_mod( 'shop_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
		$with_filter_desktop = ! ideapark_mod( 'shop_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
		$with_filter_mobile  = is_active_sidebar( 'filter-sidebar' ) || ideapark_mod( 'single_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
		?>
		<div
			class="c-sidebar c-shop-sidebar<?php if ( ideapark_mod( 'single_sidebar' ) ) { ?> c-shop-sidebar--single<?php } ?> <?php if ( $with_filter_desktop ) { ?> c-shop-sidebar--desktop-filter<?php } ?> js-shop-sidebar <?php ideapark_class( $with_sidebar && ideapark_mod( 'sticky_sidebar' ), 'js-sticky-sidebar' ); ?>">
			<div class="c-shop-sidebar__wrap js-shop-sidebar-wrap">
				<div class="c-shop-sidebar__buttons">
					<button type="button" class="h-cb h-cb--svg c-shop-sidebar__close js-filter-close-button"><i
							class="ip-close-small c-header__menu-close-svg"></i></button>
				</div>
				<?php if ( $with_sidebar ) { ?>
					<div class="c-shop-sidebar__content c-shop-sidebar__content--desktop">
						<?php dynamic_sidebar( 'shop-sidebar' ); ?>
					</div>
				<?php } elseif ( $with_filter_desktop ) { ?>
					<div
						class="c-shop-sidebar__content c-shop-sidebar__content--desktop-filter js-shop-sidebar-content-desktop">
						<?php dynamic_sidebar( 'shop-sidebar' ); ?>
					</div>
				<?php } ?>
				<?php if ( $with_filter_mobile && ! ideapark_mod( 'single_sidebar' ) ) { ?>
					<div class="c-shop-sidebar__content c-shop-sidebar__content--mobile js-shop-sidebar-content">
						<?php dynamic_sidebar( 'filter-sidebar' ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php if ( ideapark_mod( 'shop_sidebar_modal' ) ) { ?>
			<div class="c-shop-sidebar__shadow js-filter-shadow"></div>
		<?php } ?>
	<?php } else { ?>
		<div class="c-sidebar c-product__sidebar-wrap">
			<?php dynamic_sidebar( 'product-sidebar' ); ?>
		</div>
	<?php } ?>
<?php } ?>