<?php get_header();
ideapark_get_template_part( 'templates/page-header', [ 'hide_breadcrumbs' => true ] ); ?>

	<section
		class="l-section l-section--container l-section--top-margin-60 l-section--bottom-margin--60 l-section--white">

		<div class="c-cart-empty">
			<div class="c-cart-empty__image-wrap">
				<?php if ( ( $image_id = ideapark_mod( '404_empty_image__attachment_id' ) ) && ( $image_meta = ideapark_image_meta( $image_id, 'ideapark-product-thumbnail-compact' ) ) ) { ?>
					<?php echo ideapark_img( $image_meta, 'c-cart-empty__image' ); ?>
				<?php } else { ?>
					<i class="ip-404 c-cart-empty__icon c-cart-empty__icon--failed"></i>
				<?php } ?>
			</div>
			<h1 class="c-cart-empty__header"><?php esc_html_e( 'Oops! That page can’t be found', 'luchiana' ); ?></h1>
			<p class="c-cart-empty__try"><?php esc_html_e( 'The page you are trying to reach is not available. Maybe try a search?', 'luchiana' ); ?></p>
			<div class="c-cart-empty__search">
				<?php get_search_form(); ?>
			</div>

		</div>

	</section>

<?php get_footer(); ?>