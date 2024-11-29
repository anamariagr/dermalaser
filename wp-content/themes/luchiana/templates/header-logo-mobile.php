<div
	class="c-header__logo c-header__logo--mobile<?php if ( ideapark_mod( 'sticky_logo_mobile_hide' ) ) { ?> c-header__logo--mobile-sticky-hide<?php } ?><?php if ( ideapark_mod( 'logo_mobile' ) && ( ideapark_mod( 'logo_sticky' ) || ideapark_mod( 'logo_mobile_sticky' ) ) ) { ?> c-header__logo--sticky<?php } ?>">
	<?php if ( ! is_front_page() || ! ideapark_mod( 'remove_frontpage_logo_link' ) ) { ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
																							class="c-header__logo-link" aria-label="<?php esc_attr_e( 'Logo', 'luchiana' ); ?>"><?php } ?>
		<?php
		$dimension = ideapark_mod_image_size( 'logo' );
		$logo_url  = ideapark_mod( 'logo' );

		/**
		 * @var string $custom_logo_id
		 */
		extract( ideapark_header_params() );

		if ( ! empty( $custom_logo_id ) ) {
			$params    = wp_get_attachment_image_src( $custom_logo_id, 'full' );
			$logo_url  = $params[0];
			$dimension = ' width="' . $params[1] . '" height="' . $params[2] . '" ';
		}
		?>
		<?php if ( ideapark_mod( 'logo_mobile' ) && $logo_url ) { ?>
			<img <?php echo ideapark_mod_image_size( 'logo_mobile' ); ?>
				src="<?php echo esc_url( ideapark_mod( 'logo_mobile' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--mobile <?php ideapark_svg_logo_class( ideapark_mod( 'logo_mobile' ) ); ?>"/>
		<?php } elseif ( $logo_url ) { ?>
			<img <?php echo ideapark_wrap( $dimension ); ?>
				src="<?php echo esc_url( $logo_url ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--all <?php ideapark_svg_logo_class( $logo_url ); ?>"/>
		<?php } else { ?>
			<span
				class="c-header__logo-empty"><?php echo ideapark_truncate_logo_placeholder(); ?></span>
		<?php } ?>

		<?php if ( ideapark_mod( 'logo_mobile_sticky' ) && ideapark_mod( 'logo_mobile' ) && $logo_url ) { ?>
			<img <?php echo ideapark_mod_image_size( 'logo_mobile_sticky' ); ?>
				src="<?php echo esc_url( ideapark_mod( 'logo_mobile_sticky' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--sticky <?php ideapark_svg_logo_class( ideapark_mod( 'logo_mobile_sticky' ) ); ?>"/>
		<?php } elseif ( ideapark_mod( 'logo_sticky' ) && $logo_url ) { ?>
			<img <?php echo ideapark_mod_image_size( 'logo_sticky' ); ?>
				src="<?php echo esc_url( ideapark_mod( 'logo_sticky' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="c-header__logo-img c-header__logo-img--sticky <?php ideapark_svg_logo_class( ideapark_mod( 'logo_sticky' ) ); ?>"/>
		<?php } ?>

		<?php if ( ! is_front_page() || ! ideapark_mod( 'remove_frontpage_logo_link' ) ) { ?></a><?php } ?>
</div>
