<?php

function ideapark_theme_colors() {
	return [
		'text_color'       => $text_color = ideapark_mod_hex_color_norm( 'text_color', '#000000' ),
		'text_color_light' => ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.62 ),
		'background_color' => ideapark_mod_hex_color_norm( 'background_color', '#f3f3f3' ),
		'accent_color'     => ideapark_mod_hex_color_norm( 'accent_color', '#E4C1B1' ),
		'button_color'     => ideapark_mod_hex_color_norm( 'button_color', $text_color ),
	];
}

function ideapark_customize_css( $is_return_value = false ) {

	$custom_css = '';

	/**
	 * @var $text_color            string
	 * @var $text_color_light      string
	 * @var $background_color      string
	 * @var $accent_color          string
	 */
	extract( ideapark_theme_colors() );

	$page_header_bg_image = [
		'default'      => ideapark_mod( 'header_image__url' ),
		'page'         => ideapark_mod( 'header_bgimage_page__url' ),
		'post'         => ideapark_mod( 'header_bgimage_post__url' ),
		'wc'           => ideapark_mod( 'header_bgimage_wc__url' ),
		'product-page' => ideapark_mod( 'header_bgimage_product_page__url' ),
		'product-list' => ideapark_mod( 'header_bgimage_product_list__url' ),
	];

	$page_header_color = [
		'default'      => ideapark_mod( 'header_text_color' ),
		'page'         => ideapark_mod( 'header_color_page' ),
		'post'         => ideapark_mod( 'header_color_post' ),
		'wc'           => ideapark_mod( 'header_color_wc' ),
		'product-page' => ideapark_mod( 'header_color_product_page' ),
		'product-list' => ideapark_mod( 'header_color_product_list' ),
	];

	$page_header_bg_color = [
		'default'      => ideapark_mod( 'header_background_color' ),
		'page'         => ideapark_mod( 'header_bgcolor_page' ),
		'post'         => ideapark_mod( 'header_bgcolor_post' ),
		'wc'           => ideapark_mod( 'header_bgcolor_wc' ),
		'product-page' => ideapark_mod( 'header_bgcolor_product_page' ),
		'product-list' => ideapark_mod( 'header_bgcolor_product_list' ),
	];

	$page_header_bg_type = [
		'default'      => ideapark_mod( 'header_background_type' ),
		'page'         => ideapark_mod( 'header_bgtype_page' ),
		'post'         => ideapark_mod( 'header_bgtype_post' ),
		'wc'           => ideapark_mod( 'header_bgtype_wc' ),
		'product-page' => ideapark_mod( 'header_bgtype_product_page' ),
		'product-list' => ideapark_mod( 'header_bgtype_product_list' ),
	];

	$is_retina = ideapark_mod( 'header_background_retina' );

	$page_header_bg_retina_size = [
		'default'      => $is_retina && ideapark_mod( 'header_image__width' ) && ideapark_mod( 'header_image__height' ) ? ( round( (float) ideapark_mod( 'header_image__width' ) / 2 ) . 'px ' . round( (float) ideapark_mod( 'header_image__height' ) / 2 ) . 'px' ) : '',
		'page'         => $is_retina && ideapark_mod( 'header_bgimage_page__width' ) && ideapark_mod( 'header_bgimage_page__height' ) ? ( round( (float) ideapark_mod( 'header_bgimage_page__width' ) / 2 ) . 'px ' . round( (float) ideapark_mod( 'header_bgimage_page__height' ) / 2 ) . 'px' ) : '',
		'post'         => $is_retina && ideapark_mod( 'header_bgimage_post__width' ) && ideapark_mod( 'header_bgimage_post__height' ) ? ( round( (float) ideapark_mod( 'header_bgimage_post__width' ) / 2 ) . 'px ' . round( (float) ideapark_mod( 'header_bgimage_post__height' ) / 2 ) . 'px' ) : '',
		'wc'           => $is_retina && ideapark_mod( 'header_bgimage_wc__width' ) && ideapark_mod( 'header_bgimage_wc__height' ) ? ( round( (float) ideapark_mod( 'header_bgimage_wc__width' ) / 2 ) . 'px ' . round( (float) ideapark_mod( 'header_bgimage_wc__height' ) / 2 ) . 'px' ) : '',
		'product-page' => $is_retina && ideapark_mod( 'header_bgimage_product_page__width' ) && ideapark_mod( 'header_bgimage_product_page__height' ) ? ( round( (float) ideapark_mod( 'header_bgimage_product_page__width' ) / 2 ) . 'px ' . round( (float) ideapark_mod( 'header_bgimage_product_page__height' ) / 2 ) . 'px' ) : '',
		'product-list' => $is_retina && ideapark_mod( 'header_bgimage_product_list__width' ) && ideapark_mod( 'header_bgimage_product_list__height' ) ? ( round( (float) ideapark_mod( 'header_bgimage_product_list__width' ) / 2 ) . 'px ' . round( (float) ideapark_mod( 'header_bgimage_product_list__height' ) / 2 ) . 'px' ) : '',
	];

	$header_bg = '';

	foreach ( $page_header_bg_image as $key => $val_bg ) {
		$val_text_color = $page_header_color[ $key ];
		$val_color      = $page_header_bg_color[ $key ];
		$val_type       = $page_header_bg_type[ $key ];
		$val_size       = $page_header_bg_retina_size[ $key ];
		$is_val_size    = ( $val_bg && ( $val_type == 'repeat' || $val_type == '' && $page_header_bg_type['default'] == 'repeat' ) && $val_size );
		if ( $val_color || $val_bg || $val_type == 'cover' || $is_val_size ) {
			$header_bg .= '
.c-page-header--' . $key . ' {
	' . ( $val_text_color ? 'color: ' . esc_attr( $val_text_color ) . ';' : '' ) . '
	' . ( $val_color ? 'background-color: ' . esc_attr( $val_color ) . ';' : '' ) . '
	' . ( $val_bg ? 'background-image: url("' . esc_url( $val_bg ) . '");' : '' ) . '
	' . ( $val_type == 'cover' ? 'background-repeat: no-repeat; background-size: cover; background-position: center;' : '' ) . '
	' . ( $is_val_size ? ( 'background-repeat: repeat; background-position: center; background-size: ' . $val_size . ';' ) : '' ) . '
}';
		}
	}

	$grid_aspect_ratio                = ideapark_mod( 'grid_image_prop' ) == 0.9 || ideapark_mod( 'grid_image_fit' ) == 'cover' ? 0.884615384 : ideapark_mod( 'grid_image_prop' );
	$grid_aspect_ratio_compact        = ideapark_mod( 'grid_image_prop' ) == 0.9 || ideapark_mod( 'grid_image_fit' ) == 'cover' ? 1.074 : ideapark_mod( 'grid_image_prop' );
	$grid_aspect_ratio_compact_mobile = ideapark_mod( 'grid_image_prop' ) == 0.9 || ideapark_mod( 'grid_image_fit' ) == 'cover' ? 1.3888888889 : ideapark_mod( 'grid_image_prop' );
	if ( $grid_aspect_ratio_compact_mobile < 1 ) {
		$grid_aspect_ratio_compact_mobile = 1;
	}

	$product_aspect_ratio = ideapark_mod( 'product_image_prop' ) == 0.9 || ideapark_mod( 'product_image_fit' ) == 'cover' ? 0.88 : ideapark_mod( 'product_image_prop' );

	$text_color_lighting = ideapark_hex_lighting( $text_color );

	$lang_postfix = ideapark_get_lang_postfix();
	$font_text    = preg_replace( '~^(custom-|system-)~', '',  (string) ( ideapark_mod( 'theme_font_text' . $lang_postfix ) ?: ideapark_mod( 'theme_font_text' ) ) );
	$font_header  = preg_replace( '~^(custom-|system-)~', '',  (string) ( ideapark_mod( 'theme_font_header' . $lang_postfix ) ?: ideapark_mod( 'theme_font_header' ) ) );

	$custom_css .= '
	<style> 
		:root {
			--text-color: ' . esc_attr( $text_color ) . ';
			--text-color-light: ' . esc_attr( $text_color_light ) . ';
			--text-color-extra-light: ' . ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.13 ) . ';
			--text-color-tr: ' . ideapark_hex_to_rgba( $text_color, 0.15 ) . ';
			--button-color: ' . esc_attr( $button_color ) . ';
			--button-color-light: ' . ideapark_hex_to_rgb_overlay( $text_color_lighting > 128 ? '#000000' : '#FFFFFF', $button_color, 0.62 ) . ';
			--button-white-color: ' . esc_attr( $text_color_lighting > 128 ? '#000000' : '#FFFFFF' ) . ';
			--background-color: ' . esc_attr( $background_color ) . ';
			--background-color-light: ' . ideapark_hex_to_rgb_overlay( '#FFFFFF', $background_color, 0.5 ) . ';
			--background-color-dark: ' . ideapark_hex_to_rgb_overlay( $background_color, '#000000', 0.03 ) . ';
			--accent-color: ' . esc_attr( $accent_color ) . ';
			--star-rating-color: ' . ideapark_mod_hex_color_norm( 'star_rating_color', $text_color ) . ';
			--accent-color-dark: ' . ideapark_hex_to_rgb_overlay( $accent_color, '#000000', 0.1 ) . ';
			--font-text: "' . esc_attr( $font_text ) . '", sans-serif;
			--font-header: "' . esc_attr( $font_header ) . '", sans-serif;
			--font-icons: "theme-icons";
			--logo-size: ' . esc_attr( round( (float) ideapark_mod( 'logo_size' ) ) ) . 'px;
			--logo-size-sticky: ' . esc_attr( (int) ( (int) ideapark_mod( 'sticky_logo_desktop_size' ) ?: ideapark_mod( 'logo_size' ) ) ) . 'px;
			--logo-size-mobile: ' . esc_attr( round( (float) ideapark_mod( 'logo_size_mobile' ) ) ) . 'px;
			--shadow-color-desktop: ' . ideapark_hex_to_rgba( ideapark_mod_hex_color_norm( 'shadow_color_desktop', '#FFFFFF' ), 0.95 ) . ';
			--shadow-color-mobile: ' . ideapark_hex_to_rgba( ideapark_mod_hex_color_norm( 'shadow_color_mobile', $text_color ), 0.95 ) . ';
			--mobile-menu-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'menu_color_mobile', '#FFFFFF' ) ) . ';
			--mobile-menu-bg-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'shadow_color_mobile', $text_color ) ) . ';
			--badge-bgcolor-outofstock: ' . ideapark_mod_hex_color_norm( 'outofstock_badge_color', $text_color ) . ';
			--text-align-left: ' . ( ideapark_is_rtl() ? 'right' : 'left' ) . ';
			--text-align-right: ' . ( ideapark_is_rtl() ? 'left' : 'right' ) . ';
			--image-grid-compact-prop-k-mobile: ' . esc_attr( $grid_aspect_ratio_compact_mobile ) . ';
			--image-grid-compact-prop-k: ' . esc_attr( $grid_aspect_ratio_compact ) . ';
			--image-grid-prop-k: ' . esc_attr( $grid_aspect_ratio ) . ';
			--image-grid-prop: ' . esc_attr( $grid_aspect_ratio * 100 ) . '%;
			--image-product-prop-k: ' . esc_attr( $product_aspect_ratio ) . ';
			--image-product-prop: ' . esc_attr( $product_aspect_ratio * 100 ) . '%;
			--image-product-aspect-ratio: 100 / ' . (int) ( $product_aspect_ratio * 100 ) . ';
			--custom-transform-transition: visibility 0.5s cubic-bezier(0.86, 0, 0.07, 1), opacity 0.5s cubic-bezier(0.86, 0, 0.07, 1), transform 0.5s cubic-bezier(0.86, 0, 0.07, 1), box-shadow 0.5s cubic-bezier(0.86, 0, 0.07, 1);
			--opacity-transition: opacity 0.3s linear, visibility 0.3s linear;
			--opacity-transform-transition: opacity 0.3s linear, visibility 0.3s linear, transform 0.3s ease-out, box-shadow 0.3s ease-out;
			--hover-transition: opacity 0.3s linear, visibility 0.3s linear, color 0.15s linear, border-color 0.15s linear, background-color 0.15s linear, box-shadow 0.15s linear;
			--star-rating-image: url("data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="14" height="10" fill="' . ideapark_mod_hex_color_norm( 'star_rating_color', $text_color ) . '" xmlns="http://www.w3.org/2000/svg"><path d="M8.956 9.782c.05.153-.132.28-.27.186L5.5 7.798l-3.19 2.168c-.137.093-.32-.033-.269-.187l1.178-3.563L.07 3.99c-.135-.095-.065-.3.103-.302l3.916-.032L5.335.114c.053-.152.28-.152.333 0L6.91 3.658l3.916.035c.168.001.238.206.103.302L7.78 6.217l1.175 3.565z"/></svg>' ) . '");
			--select-image: url("data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="10" height="6" xmlns="http://www.w3.org/2000/svg"><path d="M.646.646A.5.5 0 0 1 1.284.59l.07.057L5 4.293 8.646.646A.5.5 0 0 1 9.284.59l.07.057a.5.5 0 0 1 .057.638l-.057.07L5 5.707.646 1.354a.5.5 0 0 1 0-.708z" fill="' . $text_color . '"/></svg>' ) . '");
			--icon-user: "\f13e" /* ip-user */;
			--icon-close-small: "\f10d" /* ip-close-small */;
			--icon-check: "\f10a" /* ip-check */;
			--icon-select: "\f112" /* ip-down_arrow */;
			--icon-select-bold: "\f14b" /* ip-y-down */;
			--icon-romb: "\f134" /* ip-romb */;
			--icon-calendar: "\f105" /* ip-calendar */;
			--icon-li: "\f111" /* ip-dot */;
			--icon-quote: "\f130" /* ip-quote */;
			--icon-submenu: "\f128" /* ip-menu-right */;
			--icon-depth: "\f14e" /* ip-z-depth */;
			--icon-eye-back: "\f115" /* ip-eye-back */;
			--icon-heart-back: "\f11e" /* ip-heart_hover */;
			--icon-before-after: "\f14d" /* ip-z-before-after */;
			
			--container-default-padding-block-start: 0px;
			--container-default-padding-block-end: 0px;
			--container-default-padding-inline-start: 0px;
			--container-default-padding-inline-end: 0px;
			
			--li-image: url("data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="7" height="8" viewBox="0 0 7 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.6.2a1 1 0 0 1 .2 1.4L3.052 6.597.241 3.317a1 1 0 0 1 1.518-1.301l1.189 1.387L5.2.4A1 1 0 0 1 6.6.2Z" fill="' . $accent_color . '"/></svg>' ) . '");
		}
		
		.woobt-wrap:before {
			content: "' . esc_html__( 'Frequently Bought Together', 'luchiana' ) . '";
		}
		
		.c-page-header__title {
			--font-size-desktop: ' . esc_attr( ideapark_mod( 'header_font_size_desktop' ) ) . 'px;
			--font-size-mobile: ' . esc_attr( ideapark_mod( 'header_font_size_mobile' ) ) . 'px;
		}
		
		.c-page-header__sub-cat-item {
			--subcat-font-size: ' . esc_attr( ideapark_mod( 'subcat_font_size' ) ) . 'px;
		}
		
		.c-badge__list {
			--badge-bgcolor-featured: ' . ideapark_mod_hex_color_norm( 'featured_badge_color', $text_color ) . ';
			--badge-bgcolor-new: ' . ideapark_mod_hex_color_norm( 'new_badge_color', $text_color ) . ';
			--badge-bgcolor-sale: ' . ideapark_mod_hex_color_norm( 'sale_badge_color', $text_color ) . ';
		}
		
		.c-to-top-button {
			--to-top-button-color: ' . ideapark_mod_hex_color_norm( 'to_top_button_color' ) . ';
		}
		
		.c-top-menu {
			--top-menu-submenu-color: ' . ideapark_mod_hex_color_norm( 'top_menu_submenu_color', $text_color ) . ';
			--top-menu-submenu-bg-color: ' . ideapark_mod_hex_color_norm( 'top_menu_submenu_bg_color', '#FFFFFF' ) . ';
			--top_menu_submenu_accent_color: ' . ideapark_mod_hex_color_norm( 'top_menu_submenu_accent_color', $accent_color ) . ';
			--top-menu-font-size: ' . esc_attr( (int) ideapark_mod( 'top_menu_font_size' ) ) . 'px;
			--top-menu-item-space: ' . esc_attr( (int) ideapark_mod( 'top_menu_item_space' ) ) . 'px;
		}
		
		.c-product-grid__item,
		.wc-block-grid__product {
			--font-size: ' . esc_attr( ideapark_mod( 'product_font_size' ) ) . 'px;
			--font-size-mobile: ' . esc_attr( round( (float) ideapark_mod( 'product_font_size' ) * 18 / 20 ) ) . 'px;
			--font-size-mobile-2-per-row: ' . esc_attr( round( (float) ideapark_mod( 'product_font_size' ) * 14 / 20 ) ) . 'px;
			--font-size-compact: ' . esc_attr( ideapark_mod( 'product_font_size_compact' ) ) . 'px;
			--font-letter-spacing: ' . esc_attr( ideapark_mod( 'product_font_letterspacing' ) ) . 'em;
			--color-variations-size: ' . ideapark_mod( 'color_variations_size' ) . 'px;
		}
		
		.c-product {
			--font-size-desktop: ' . esc_attr( ideapark_mod( 'product_page_font_size_desktop' ) ) . 'px;
			--font-size-desktop-qv: ' . esc_attr( round( (float) ideapark_mod( 'product_page_font_size_desktop' ) * 25 / 30 ) ) . 'px;
			--font-size-mobile: ' . esc_attr( ideapark_mod( 'product_page_font_size_mobile' ) ) . 'px;
		}
		
		#main-header {
			--top-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ) ) . ';
			--top-color-light: ' . ideapark_hex_to_rgb_overlay( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ), ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ), 0.62 ) . ';
			--top-accent-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'header_top_accent_color', '#E4C1B1' ) ) . ';
			--top-background-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ) ) . ';
			--top-color-hr: ' . ideapark_hex_to_rgb_overlay( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ), ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ), ideapark_hex_lighting( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ) ) > 200 ? 0.1 : 0.15 ) . '; 
			--top-color-input: ' . ideapark_hex_to_rgb_overlay( ideapark_mod_hex_color_norm( 'header_top_background_color', '#000000' ), ideapark_mod_hex_color_norm( 'header_top_color', '#FFFFFF' ), 0.4 ) . ';
			
			--header-color-light: ' . ideapark_mod_hex_color_norm( 'header_color_light', $background_color ) . ';
			--header-color-dark: ' . ideapark_mod_hex_color_norm( 'header_color_dark', $text_color ) . ';
			
			--header-color-menu: ' . ideapark_mod_hex_color_norm( 'header_color_menu', $text_color ) . ';
			--header-color-bg-menu: ' . ideapark_mod_hex_color_norm( 'header_bg_color_menu', '#FFFFFF' ) . ';
			
			--header-color-mobile: ' . ideapark_mod_hex_color_norm( 'mobile_header_color', $text_color ) . ';
			--header-color-mobile-tr: ' . ideapark_mod_hex_color_norm( 'mobile_header_color_tr', '#FFFFFF' ) . ';
			--header-color-mobile-tr-neg: ' . ( ideapark_hex_lighting( ideapark_mod_hex_color_norm( 'mobile_header_color_tr', '#FFFFFF' ) ) > 128 ? $text_color : '#FFFFFF' ) . ';
			--header-color-bg-mobile: ' . ideapark_mod_hex_color_norm( 'mobile_header_background_color', '#FFFFFF' ) . ';
			
			--header-height-mobile: ' . ideapark_mod( 'header_height_mobile' ) . 'px;
			--sticky-header-height-mobile: ' . ideapark_mod( 'sticky_header_height_mobile' ) . 'px;
			
			--sticky-menu-color: ' . ideapark_mod_hex_color_norm( 'sticky_menu_color', in_array( ideapark_mod( 'header_type' ), [
			'header-type-4',
			'header-type-5'
		] ) ? ideapark_mod_hex_color_norm( 'header_color_menu', $text_color ) : $text_color ) . ';
			--sticky-menu-bg-color: ' . ideapark_mod_hex_color_norm( 'sticky_menu_bg_color', in_array( ideapark_mod( 'header_type' ), [
			'header-type-4',
			'header-type-5'
		] ) ? ideapark_mod_hex_color_norm( 'header_bg_color_menu', '#FFFFFF' ) : '#FFFFFF' ) . ';
		}
		
		.c-product__slider-item {
			--image-background-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'product_image_background_color' ) ) . ';
		}
		
		.woocommerce-store-notice {
			--store-notice-color: ' . ideapark_mod_hex_color_norm( 'store_notice_color' ) . ';
			--store-notice-background-color: ' . ideapark_mod_hex_color_norm( 'store_notice_background_color' ) . ';
		}
		
		.c-product-features {
			--feature-text-color: ' . ideapark_mod_hex_color_norm( 'product_features_text_color', 'var(--text-color)' ) . ';
			--feature-description-color: ' . ideapark_mod_hex_color_norm( 'product_features_description_color', 'var(--text-color-light)' ) . ';
			--feature-background-color: ' . ideapark_mod_hex_color_norm( 'product_features_background_color', 'transparent' ) . ';
			--feature-border: ' . ( ideapark_mod( 'product_features_border' ) ? 'dashed 1px ' . ideapark_mod_hex_color_norm( 'product_features_border_color', ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.5 ) ) : 'none' ) . ';
			--feature-icon-color: ' . ideapark_mod_hex_color_norm( 'product_features_icon_color', 'var(--accent-color)' ) . ';
		}
		
		.c-product__slider-item--video .mejs-mediaelement .wp-video-shortcode,
		.c-product__slider-item--video .c-inline-video {
			object-fit: ' . ideapark_mod( 'product_image_fit' ) . ';
		}
		
		.c-product__custom-html {
			--custom-text-color: ' . ideapark_mod_hex_color_norm( 'product_html_text_color', $text_color_light ) . ';
			--custom-background-color: ' . ideapark_mod_hex_color_norm( 'product_html_background_color' ) . ';
			--custom-border: ' . ( ideapark_mod( 'product_html_border' ) ? 'dashed 1px ' . ideapark_mod_hex_color_norm( 'product_html_border_color', ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.5 ) ) : 'none' ) . ';
			--custom-columns: ' . ( ideapark_mod( 'product_html_2_col' ) ? 2 : 1 ) . ';
		}
		
		' . $header_bg . '
			
	</style>';

	$custom_css = preg_replace( '~[\r\n]~', '', preg_replace( '~[\t\s]+~', ' ', str_replace( [
		'<style>',
		'</style>'
	], [ '', '' ], $custom_css ) ) );

	if ( $custom_css ) {
		if ( $is_return_value ) {
			return $custom_css;
		} else {
			wp_add_inline_style( 'ideapark-core', $custom_css );
		}
	}
}

function ideapark_uniord( $u ) {
	$k  = mb_convert_encoding( $u, 'UCS-2LE', 'UTF-8' );
	$k1 = ord( substr( $k, 0, 1 ) );
	$k2 = ord( substr( $k, 1, 1 ) );

	return $k2 * 256 + $k1;
}

function ideapark_b64enc( $input ) {

	$keyStr = "ABCDEFGHIJKLMNOP" .
	          "QRSTUVWXYZabcdef" .
	          "ghijklmnopqrstuv" .
	          "wxyz0123456789+/" .
	          "=";

	$output = "";
	$i      = 0;

	do {
		$chr1 = ord( substr( $input, $i ++, 1 ) );
		$chr2 = $i < strlen( $input ) ? ord( substr( $input, $i ++, 1 ) ) : null;
		$chr3 = $i < strlen( $input ) ? ord( substr( $input, $i ++, 1 ) ) : null;

		$enc1 = $chr1 >> 2;
		$enc2 = ( ( $chr1 & 3 ) << 4 ) | ( $chr2 >> 4 );
		$enc3 = ( ( $chr2 & 15 ) << 2 ) | ( $chr3 >> 6 );
		$enc4 = $chr3 & 63;

		if ( $chr2 === null ) {
			$enc3 = $enc4 = 64;
		} else if ( $chr3 === null ) {
			$enc4 = 64;
		}

		$output = $output .
		          substr( $keyStr, $enc1, 1 ) .
		          substr( $keyStr, $enc2, 1 ) .
		          substr( $keyStr, $enc3, 1 ) .
		          substr( $keyStr, $enc4, 1 );
		$chr1   = $chr2 = $chr3 = "";
		$enc1   = $enc2 = $enc3 = $enc4 = "";
	} while ( $i < strlen( $input ) );

	return $output;
}