<?php if ( ideapark_woocommerce_on() && ideapark_mod( 'wishlist_page' ) ) { ?>
	<div class="c-header__wishlist">
		<a class="c-header__button-link" aria-label="<?php esc_attr_e('Wishlist', 'luchiana'); ?>" title="<?php esc_attr_e( 'Wishlist', 'luchiana' ); ?>"
		   href="<?php echo esc_url( get_permalink( apply_filters( 'wpml_object_id', ideapark_mod( 'wishlist_page' ), 'any' ) ) ); ?>"><i class="<?php echo ( ideapark_mod( 'custom_header_icon_wishlist' ) ?: 'ip-wishlist' ); ?> c-header__wishlist-icon"></i><?php echo ideapark_wishlist_info(); ?></a>
	</div>
<?php } ?>