<?php

/**
 * @var $ideapark_var array
 */


$show_breadcrumbs     = empty( $ideapark_var['hide_breadcrumbs'] ) || $ideapark_var['hide_breadcrumbs'] != 'yes';
$show_title           = empty( $ideapark_var['hide_title'] ) || $ideapark_var['hide_title'] != 'yes';
$breadcrumbs_position = ! empty( $ideapark_var['breadcrumbs_position'] ) ? $ideapark_var['breadcrumbs_position'] : 'default';
$header_class_add     = ! empty( $ideapark_var['header_class_add'] ) ? $ideapark_var['header_class_add'] : '';
$is_h1                = true;
$image                = '';
$image_id             = '';
$title                = isset( $ideapark_var['title'] ) ? esc_html( $ideapark_var['title'] ) : '';
$bg_color             = '';
$header_class         = '';
$header_is_custom_bg  = '';
$header_bg_type       = '';
$header_bg_size       = '';
$header_height        = '';

extract( ideapark_header_params() );

if ( preg_match( '~margin-minus~', $header_class_add ) ) {
	$header_height = '';
}

if ( ideapark_is_shop() && ! $title ) {
	if ( is_search() ) {
		$title = esc_html__( 'Search:', 'luchiana' ) . ' ' . esc_html( get_search_query( false ) );
	} else {
		$shop_page_id = wc_get_page_id( 'shop' );
		$title        = get_the_title( $shop_page_id );
	}
} elseif ( ideapark_woocommerce_on() && is_woocommerce() && ! $title ) {
	if ( is_product() ) {
		$is_h1 = false;
		if ( ideapark_mod( 'header_narrow_product_page' ) ) {
			$header_height = 'narrow';
			$title         = '';
		} else {
			$title = esc_html( ideapark_mod( 'product_header' ) );
		}
	} else {
		$title = woocommerce_page_title( false );
	}
} elseif ( is_404() ) {
	$title = esc_html__( 'Page not found', 'luchiana' );
} elseif ( is_single() ) {
	if ( ! $title ) {
		if ( ideapark_woocommerce_on() && is_product() ) {
			$is_h1 = false;
			$title = esc_html( ideapark_mod( 'product_header' ) );
		} else {
			$title = ( is_sticky() ? '<i class="ip-sticky c-page-header__sticky"><!-- --></i>' : '' ) . get_the_title();
		}
	}
} elseif ( is_search() && ! $title ) {
	$found_posts = $wp_query->found_posts;
	if ( $found_posts ) {
		$title = esc_html__( 'Search:', 'luchiana' ) . ' ' . esc_html( get_search_query( false ) );
	} else {
		$title = esc_html__( 'No search results for:', 'luchiana' ) . ' ' . esc_html( get_search_query( false ) );
	}
} elseif ( is_archive() ) {
	if ( ! $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			the_post();
			$title = get_the_author();
			rewind_posts();
		} elseif ( is_day() ) {
			$title = get_the_date();
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} else {
			$queried_object = get_queried_object();
			$title          = esc_html__( 'Archives', 'luchiana' );
		}
	}
} elseif ( is_home() && get_option( 'page_for_posts' ) && 'page' == get_option( 'show_on_front' ) && ! $title ) {
	$title = get_the_title( get_option( 'page_for_posts' ) );
} elseif ( is_front_page() && get_option( 'page_on_front' ) && 'page' == get_option( 'show_on_front' ) && ! $title ) {
	$title = get_the_title( get_option( 'page_on_front' ) );
} elseif ( is_home() && ! $title ) {
	$title = esc_html__( 'Posts', 'luchiana' );
}

if ( $image_id ) {
	$image = wp_get_attachment_image_src( $image_id, 'full' );
	if ( is_array( $image ) ) {
		$image = $image[0];
	}
}
if ( ! $title && $is_h1 ) {
	$title = get_the_title();
}

ob_start();
$with_subcategories = ideapark_woocommerce_on() && ideapark_header_categories();
$subcategories      = ob_get_clean();
?>
<?php if ( $header_height != 'hide' ) { ?>
	<header
		class="l-section c-page-header c-page-header--<?php echo esc_attr( ideapark_mod( 'header_type' ) ); ?> c-page-header--default
<?php if ( $header_class ) { ?> c-page-header--<?php echo esc_attr( $header_class ); ?><?php } ?>
<?php if ( $header_class_add ) { ?> c-page-header--<?php echo esc_attr( $header_class_add ); ?><?php } ?>
<?php if ( $header_height ) { ?> c-page-header--<?php echo esc_attr( $header_height ); ?><?php } ?>
<?php if ( $header_is_custom_bg ) { ?> c-page-header--custom-bg<?php } ?>
<?php if ( $with_subcategories ) { ?> c-page-header--sub-cat<?php } ?>"
		<?php echo ideapark_bg( $bg_color, $image, '', $header_bg_type ? ( $header_bg_type == 'cover' ? 'background-size: cover; background-repeat: no-repeat; background-position: center;' : 'background: repeat; background-position: center; ' . ( $header_bg_size ? 'background-size:' . $header_bg_size : '' ) ) : '' ); ?>>

		<?php if ( $show_title && ( $title = trim( $title ) ) ) { ?>
			<div class="c-page-header__wrap">
				<?php if ( $is_h1 ) { ?>
					<h1 class="c-page-header__title<?php if ( ideapark_strlen( strip_tags( $title ) ) > 37 ) { ?> c-page-header__title--compact<?php } ?>"><?php echo ideapark_wrap( $title ); ?></h1>
				<?php } else { ?>
					<div
						class="c-page-header__title<?php if ( ideapark_strlen( strip_tags( $title ) ) > 37 ) { ?> c-page-header__title--compact<?php } ?>"><?php echo ideapark_wrap( $title ); ?></div>
				<?php } ?>
			</div>
		<?php } ?>
		<?php ob_start(); ?>
		<?php if ( ideapark_woocommerce_on() && is_account_page() && is_user_logged_in() ) { ?>
			<div class="c-page-header__login-info">
				<?php global $current_user; ?>
				<span class="c-page-header__login-text">
					<?php echo sprintf( esc_attr__( 'Logged in as %s%s%s', 'luchiana' ), '<span class="c-page-header__login-name">', esc_html( $current_user->display_name ), '</span>' ); ?>
				</span>
				<a class="c-page-header__logout"
				   href="<?php echo esc_url( function_exists( 'wc_logout_url' ) ? wc_logout_url() : wp_logout_url() ); ?>">
					<?php esc_html_e( 'Logout', 'luchiana' ); ?><i class="ip-menu-right c-page-header__logout-icon"></i>
				</a>
			</div>
		<?php } elseif ( $show_breadcrumbs ) { ?>
			<?php ideapark_get_template_part( 'templates/breadcrumbs', [ 'position' => $breadcrumbs_position ] ); ?>
		<?php }

		$content = trim( ob_get_clean() );

		if ( $with_subcategories ) {
			$content .= $subcategories;
		}

		if ( $content ) {
			echo ideapark_wrap( $content );
		} else { ?>
			<div class="c-page-header__spacer"></div>
		<?php } ?>
	</header>
<?php } ?>
