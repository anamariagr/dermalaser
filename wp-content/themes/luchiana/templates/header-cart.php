<?php
$is_desktop = empty( $ideapark_var['device'] ) || $ideapark_var['device'] != 'mobile';
if ( ideapark_woocommerce_on() ) { ?>
	<div class="c-header__cart c-header__cart--<?php echo ideapark_mod( 'popup_cart_layout' ); ?> js-cart">
		<a class="c-header__button-link <?php if ( ! ( $is_desktop && ideapark_mod( 'popup_cart_layout' ) == 'default' ) ) { ?>js-cart-sidebar-open<?php } ?>"
		   href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'luchiana' ); ?>" title="<?php esc_attr_e( 'Cart', 'luchiana' ); ?>">
			<i class="<?php echo( ideapark_mod( 'custom_header_icon_cart' ) ?: 'ip-cart' ); ?> c-header__cart-icon"><!-- --></i><?php echo ideapark_cart_info(); ?>
		</a>
		<?php if ( $is_desktop && ideapark_mod( 'popup_cart_layout' ) == 'default' ) { ?>
			<div class="widget_shopping_cart_content"></div>
		<?php } ?>
	</div>
	<?php if ( ideapark_mod( 'popup_cart_modal' ) ) { ?>
		<div class="c-shop-sidebar__shadow js-cart-sidebar-shadow"></div>
	<?php } ?>
<?php } ?>