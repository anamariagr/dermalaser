<?php
/**
 * @var $is_product    bool
 * @var $is_page       bool
 * @var $format        string
 * @var $video_url     string
 * @var $image_gallery array
 * @var $has_thumb     bool
 */
global $product, $post;
extract( ideapark_post_params() );
$with_sidebar = ideapark_mod( 'sidebar_blog' ) && is_active_sidebar( 'post-sidebar' );
$post_type    = get_post_type();
?>
<article
	id="post-<?php the_ID(); ?>" <?php post_class( 'c-post-list  c-post-list--' . esc_attr( $format ) . ' c-post-list--' . ideapark_mod( 'post_layout' ) . ( $with_sidebar ? ' c-post-list--sidebar' : ' c-post-list--no-sidebar' ) . ( $has_thumb ? ' c-post-list--with-thumb' : ' c-post-list--no-thumb' ) . ( get_post_type() == 'post' ? ' c-post-list--post' : ' c-post-list--page' ) . ' js-post-item' ); ?>>

	<?php if ( $has_thumb ) { ?>
		<div
			class="c-post-list__thumb c-post-list__thumb--<?php echo esc_attr( $format ); ?> c-post-list__thumb--<?php echo esc_attr( ideapark_mod( 'post_layout' ) ); ?>">
			<?php if ( $format == 'gallery' && ! empty( $image_gallery ) ) { ?>
				<?php if ( ideapark_mod( '_disable_post_gallery' ) ) { ?>
					<div
						class="c-post-list__thumb-inner c-post-list__thumb-inner--<?php echo esc_attr( ideapark_mod( 'post_layout' ) ); ?>">
						<?php
						if ( $image_meta = ideapark_image_meta( $image_gallery[0], 'medium_large' ) ) { ?>
							<a href="<?php echo get_permalink() ?>">
								<?php echo ideapark_img( $image_meta, 'c-post-list__img' ); ?>
							</a>
						<?php } ?>
					</div>
				<?php } else { ?>
					<div
						class="c-post-list__carousel-list js-post-image-carousel h-carousel h-carousel--inner h-carousel--flex h-carousel--dots-hide">
						<?php $index = 0; ?>
						<?php foreach ( $image_gallery as $image_id ) {
							$image_wrap_open  = '<div class="c-post-list__thumb-inner c-post-list__thumb-inner--' . ideapark_mod( 'post_layout' ) . '"></div><a href="' . esc_url( get_permalink() ) . '" class="c-post-list__carousel-link">';
							$image_wrap_close = '</a>';

							if ( $image_meta = ideapark_image_meta( $image_id, 'medium_large' ) ) {
								$image_code = ideapark_img( $image_meta, 'c-post-list__carousel-img' );
							} else {
								$image_code = '';
							}
							echo sprintf( '<div class="c-post-list__carousel-item">%s%s%s</div>', $image_wrap_open, $image_code, $image_wrap_close );
						} ?>
					</div>
				<?php } ?>

			<?php } elseif ( $format == 'video' ) { ?>
				<div
					class="c-post-list__thumb-inner c-post-list__thumb-inner--<?php echo esc_attr( ideapark_mod( 'post_layout' ) ); ?>">

					<a href="<?php echo esc_url( $video_url ); ?>" class="js-video c-post-list__video"
					   onclick="return false;"
					   data-autoplay="true"
					   data-vbtype="video">
						<?php if ( has_post_thumbnail() ) { ?>
							<?php the_post_thumbnail( 'medium_large', [ 'class' => 'c-post-list__img' ] ); ?>
						<?php } else {
							$pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
							if ( preg_match( $pattern, $video_url, $match ) ) {
								$image_url = 'https://img.youtube.com/vi/' . $match[1] . '/maxresdefault.jpg';
								echo '<img class="c-post-list__img" src="' . esc_url( $image_url ) . '" ' . ( ideapark_mod( 'lazyload' ) ? 'loading="lazy"' : '' ) . ' alt="' . esc_attr( get_the_title() ) . '">';
							}
						} ?>
						<i class="c-play c-play--large c-play--disabled"></i>
					</a>
				</div>
			<?php } else { ?>
				<div
					class="c-post-list__thumb-inner c-post-list__thumb-inner--<?php echo esc_attr( ideapark_mod( 'post_layout' ) ); ?>">
					<?php if ( has_post_thumbnail() ) { ?>
						<a href="<?php echo get_permalink() ?>">
							<?php the_post_thumbnail( 'medium_large', [ 'class' => 'c-post-list__img' ] ); ?>
						</a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	<?php } ?>

	<div
		class="c-post-list__wrap c-post-list__wrap--<?php echo esc_attr( $format ); ?> c-post-list__wrap--<?php echo esc_attr( ideapark_mod( 'post_layout' ) ); ?> <?php ideapark_class( $has_thumb, '', 'c-post-list__wrap--no-thumb' ); ?> <?php ideapark_class( $with_sidebar, 'c-post-list__wrap--sidebar', 'c-post-list__wrap--no-sidebar' ); ?>">

		<?php if ( ! ideapark_mod( 'post_grid_list_hide_date' ) && ( ! $is_product && ! $is_page ) ) { ?>
			<div
				class="c-post-list__meta-date <?php ideapark_class( $has_thumb, 'c-post-list__meta-date--with-thumb', 'c-post-list__meta-date--no-thumb' ); ?> <?php ideapark_class( $with_sidebar, 'c-post-list__meta-date--sidebar', 'c-post-list__meta-date--no-sidebar' ); ?> c-post-list__meta-date--<?php echo esc_attr( ideapark_mod( 'post_layout' ) ); ?>">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</div>
		<?php } ?>

		<?php
		$title = '';
		ob_start();
		the_title();
		$title = trim( ob_get_clean() );
		if ( is_sticky() ) {
			$title .= '&nbsp;<i class="ip-sticky c-post-list__header-sticky"><!-- --></i>';
		}
		?>

		<?php echo ideapark_wrap( $title, '<a class="c-post-list__header-link" href="' . get_permalink() . '"><h2 class="c-post-list__header">', '</h2></a>' ) ?>

		<?php if ( ! ideapark_mod( 'post_grid_list_hide_excerpt' ) ) { ?>
			<div class="c-post-list__except">
				<?php if ( empty( $post->post_title ) ) { ?><a href="<?php echo get_permalink() ?>"><?php } ?>
					<?php the_excerpt() ?>
					<?php if ( empty( $post->post_title ) ) { ?></a><?php } ?>
			</div>
		<?php } ?>

		<?php if ( ! $is_page && ! ideapark_mod( 'post_grid_list_hide_category' ) ) { ?>
			<?php if ( $is_product ) {
				$product_categories = [];
				$term_ids           = wc_get_product_term_ids( get_the_ID(), 'product_cat' );
				foreach ( $term_ids as $term_id ) {
					$product_categories[] = get_term_by( 'id', $term_id, 'product_cat' );
				}
			} ?>
			<div class="c-post-list__meta-category">
				<?php ideapark_category( '<span class="h-bullet"></span>', ( $is_product ) && ! empty( $product_categories ) ? $product_categories : null, 'c-post-list__categories-item-link' ); ?>
			</div>
		<?php } ?>

		<?php if ( $post_type == 'product' ) { ?>
			<?php echo ideapark_wrap( $product->get_price_html(), '<div class="c-post-list__price">', '</div>' ); ?>
		<?php } ?>

		<a class="c-button c-button--outline c-post-list__continue"
		   href="<?php echo get_permalink(); ?>"><?php if ( $is_product ) { ?><?php esc_html_e( 'Details', 'luchiana' ); ?><?php } else { ?><?php esc_html_e( 'Read More', 'luchiana' ); ?><?php } ?></a>
	</div>

</article>