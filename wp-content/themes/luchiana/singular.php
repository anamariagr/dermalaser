<?php global $post;
$with_sidebar = ! is_page() && ideapark_mod( 'sidebar_post' ) && is_active_sidebar( 'post-sidebar' ) && ! ( ideapark_woocommerce_on() && ( is_cart() || is_checkout() || is_account_page() ) );
$class        = [ 'l-section', 'l-section--container', 'l-section--bottom-margin' ];
$hide_title   = false;
if ( $with_sidebar ) {
	$class[] = 'l-section--with-sidebar';
} else {
	$class[] = 'l-section--no-sidebar';
}

if ( ideapark_is_wishlist_page() ) {
	if ( ideapark_wishlist()->ids() ) {
		$class[] = 'l-section--top-margin';
	} else {
		$class[] = 'l-section--top-margin-60 l-section--bottom-margin--60 l-section--white';
	}
} elseif ( ideapark_woocommerce_on() && ( is_checkout() || is_account_page() ) ) {
	$class[] = 'l-section--top-margin-60';
	$class[] = 'l-section--white';
	if ( is_checkout() ) {
		$class[] = 'l-section--checkout';
	}
} elseif ( ideapark_woocommerce_on() && ( is_cart() || is_account_page() ) ) {
	if ( is_cart() && WC()->cart->is_empty() ) {
		$class[] = 'l-section--top-margin-60';
		$class[] = 'l-section--white';
	} else {
		$class[] = 'l-section--top-margin';
	}
} elseif ( $with_sidebar ) {
	$class[] = 'l-section--top-margin-80';
} else {
	if ( is_page() ) {
		$class[] = 'l-section--top-margin-60';
	} else {
		$class[]    = 'l-section--top-margin-minus';
		$hide_title = true;
	}
}
?>
<?php get_header(); ?>
<?php ideapark_get_template_part( 'templates/page-header', [
	'hide_title'           => $hide_title,
	'breadcrumbs_position' => ! $hide_title ? 'default' : 'top',
	'header_class_add'     => $hide_title ? 'margin-minus' : ''
] ); ?>

<?php if ( have_posts() ): ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="<?php echo implode( ' ', $class ); ?>">
			<div
				class="l-section__content<?php if ( $with_sidebar ) { ?> l-section__content--with-sidebar<?php } ?>">
				<?php if ( $with_sidebar && ideapark_mod( 'sticky_sidebar' ) ) { ?>
				<div class="js-sticky-sidebar-nearby"><?php } ?>
					<?php
					if ( ideapark_is_wishlist_page() ) {
						ideapark_get_template_part( 'woocommerce/wishlist' );
					} elseif ( ideapark_woocommerce_on() && ( is_cart() || is_checkout() || is_account_page() ) ) {
						the_content();
					} else {
						ideapark_get_template_part( 'templates/content', [ 'with_sidebar' => $with_sidebar ] );
					}
					?>
					<?php if ( $with_sidebar && ideapark_mod( 'sticky_sidebar' ) ) { ?>
				</div><?php } ?>
			</div>
			<?php if ( $with_sidebar ) { ?>
				<div class="l-section__sidebar l-section__sidebar--right">
					<?php get_sidebar( 'post' ); ?>
				</div>
			<?php } ?>
		</div>
	<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>











