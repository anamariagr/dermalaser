<?php if ( get_option( 'woocommerce_myaccount_page_id' ) ) { ?>
	<li class="c-header__top-row-item c-header__top-row-item--login">
		<i class="ip-user_topbar c-header__top-row-icon c-header__top-row-icon--login"></i>
		<?php echo ideapark_wrap( is_user_logged_in() ? esc_html__( 'My Account', 'luchiana' ) : esc_html__( 'Login', 'luchiana' ), '<a href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" rel="nofollow">', '</a>' ); ?>
	</li>
<?php } ?>