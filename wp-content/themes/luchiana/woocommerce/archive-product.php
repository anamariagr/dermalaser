<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @package       WooCommerce/Templates
 * @version        10.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$with_sidebar        = ideapark_mod( 'shop_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
$with_filter_desktop = ! ideapark_mod( 'shop_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
$with_filter_mobile  = is_active_sidebar( 'filter-sidebar' ) || ideapark_mod( 'single_sidebar' ) && is_active_sidebar( 'shop-sidebar' );

ideapark_mod_set_temp( '_with_sidebar', $with_sidebar );
ideapark_mod_set_temp( '_with_filter_desktop', $with_filter_desktop );
ideapark_mod_set_temp( '_with_filter', $with_filter_mobile );

global $ideapark_category_html_top, $ideapark_category_html_bottom, $ideapark_category_html_top_above;

$layout = ideapark_mod( 'product_grid_layout' );
if ( $layout == '3-per-row' && ideapark_mod( '_with_sidebar' ) ) {
	$layout = '4-per-row';
}
ideapark_mod_set_temp( '_product_layout', $layout );
ideapark_mod_set_temp( '_product_layout_class', ( $with_sidebar ? 'c-product-grid__item--sidebar ' : '' ) . 'c-product-grid__item--' . $layout . ( $layout != 'compact' ? ( ' c-product-grid__item--normal c-product-grid__item--' . ideapark_mod( 'atc_button_visibility' ) . ideapark_grid_class_mobile( $layout ) ) : '' ) );
?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'woocommerce_before_main_content' );
?>

<?php if ( $ideapark_category_html_top_above ) { ?>
	<?php echo ideapark_wrap( $ideapark_category_html_top, '<div class="c-category-html c-category-html--top">', '</div>' ); ?>
<?php } ?>

<div
	class="l-section l-section--container l-section--top-margin l-section--bottom-margin<?php if ( $with_sidebar ) { ?> l-section--with-sidebar<?php } ?>">
	<?php if ( $with_sidebar || $with_filter_mobile || $with_filter_desktop ) { ?>
		<div class="l-section__sidebar">
			<?php
			/**
			 * woocommerce_sidebar hook.
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			do_action( 'woocommerce_sidebar' );
			?>
		</div>
	<?php } ?>

	<div
		class="l-section__content<?php if ( $with_sidebar ) { ?> l-section__content--with-sidebar<?php } ?>">
		<div
			class="<?php ideapark_class( $with_sidebar && ideapark_mod( 'sticky_sidebar' ), 'js-sticky-sidebar-nearby' ); ?>">

			<?php if ( ! $ideapark_category_html_top_above ) { ?>
				<?php echo ideapark_wrap( $ideapark_category_html_top, '<div class="c-category-html c-category-html--top">', '</div>' ); ?>
			<?php } ?>

			<?php if ( ! ideapark_mod( 'show_subcat_in_header' ) && ( $subcategories = woocommerce_maybe_show_product_subcategories() ) ) { ?>
				<div class="c-sub-categories">
					<div class="c-sub-categories__list"><?php echo ideapark_wrap( $subcategories ); ?></div>
				</div>
			<?php } ?>

			<?php
			/**
			 * woocommerce_archive_description hook.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			if ( ideapark_mod( 'category_description_position' ) == 'above' ) {
				do_action( 'woocommerce_archive_description' );
			}
			?>
			<?php

			if ( woocommerce_product_loop() ) {

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked wc_print_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				?>
				<div class="c-product-grid"><?php
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
					?>
				</div>
				<?php
				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			}
			?>
		</div>
	</div>
</div>

<?php
/**
 * woocommerce_archive_description hook.
 *
 * @hooked woocommerce_taxonomy_archive_description - 10
 * @hooked woocommerce_product_archive_description - 10
 */
if ( ideapark_mod( 'category_description_position' ) == 'below' ) {
	do_action( 'woocommerce_archive_description' );
}
?>
<?php echo ideapark_wrap( $ideapark_category_html_bottom, '<div class="c-category-html c-category-html--bottom">', '</div>' ); ?>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
?>

<?php get_footer( 'shop' ); ?>
