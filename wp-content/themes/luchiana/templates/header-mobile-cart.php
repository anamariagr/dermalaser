<?php
if ( ideapark_woocommerce_on() ) { ?>
	<div
		class="c-shop-sidebar <?php ideapark_class( ideapark_mod( 'popup_cart_layout' ) == 'sidebar', 'c-shop-sidebar--desktop-filter', 'c-shop-sidebar--mobile-only' ); ?> js-cart-sidebar">
		<div class="c-shop-sidebar__wrap js-cart-sidebar-wrap">
			<div class="c-shop-sidebar__buttons">
				<button type="button" class="h-cb h-cb--svg c-shop-sidebar__close js-cart-sidebar-close"><i
						class="ip-close-small c-header__menu-close-svg"></i></button>
			</div>
			<div
				class="c-shop-sidebar__content <?php ideapark_class( ideapark_mod( 'popup_cart_layout' ) == 'sidebar', 'c-shop-sidebar__content--popup', 'c-shop-sidebar__content--mobile' ); ?> c-shop-sidebar__content--cart">
				<div class="widget_shopping_cart_content"></div>
			</div>
		</div>
	</div>
	<?php if ( ideapark_mod( 'popup_cart_modal' ) ) { ?>
		<div class="c-shop-sidebar__shadow js-cart-sidebar-shadow"></div>
	<?php } ?>
<?php } ?>
