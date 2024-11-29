<?php

global $ideapark_customize_custom_css, $ideapark_customize, $ideapark_customize_mods, $ideapark_customize_mods_def, $ideapark_customize_mods_names, $ideapark_customize_mods_images;

$ideapark_customize_custom_css  = [];
$ideapark_customize             = [];
$ideapark_customize_mods        = [];
$ideapark_customize_mods_def    = [];
$ideapark_customize_mods_names  = [];
$ideapark_customize_mods_images = [];

if ( ! function_exists( 'ideapark_init_theme_customize' ) ) {
	function ideapark_init_theme_customize() {
		global $ideapark_customize, $ideapark_customize_mods_def, $ideapark_customize_mods_names, $ideapark_customize_mods_images;

		if ( $ideapark_customize ) {
			return;
		}

		$version = md5( ideapark_mtime( __FILE__ ) . '-' . IDEAPARK_VERSION );
		if ( $languages = ideapark_active_languages() ) {
			$version .= '_' . implode( '_', array_keys( $languages ) );
		}

		if ( ideapark_is_file( $fn = IDEAPARK_UPLOAD_DIR . 'customizer_vars.php' ) ) {
			try {
				include( $fn );
			} catch ( \ParseError $e ) {
				unlink( $fn );
				$ideapark_customize = [];
			} catch ( \Throwable $e ) {
				unlink( $fn );
				$ideapark_customize = [];
			}
			if ( ! empty( $ideapark_customize_mods_ver ) && $ideapark_customize_mods_ver == $version && $ideapark_customize ) {
				return;
			}
		}

		if ( ( $data = get_option( 'ideapark_customize' ) ) && ! empty( $data['version'] ) && ! empty( $data['settings'] ) ) {
			if ( $data['version'] == $version ) {
				$ideapark_customize             = $data['settings'];
				$ideapark_customize_mods_def    = get_option( 'ideapark_customize_mods_def', [] );
				$ideapark_customize_mods_names  = get_option( 'ideapark_customize_mods_names', [] );
				$ideapark_customize_mods_images = get_option( 'ideapark_customize_mods_images', [] );

				return;
			} else {
				ideapark_delete_file( IDEAPARK_UPLOAD_DIR . 'customizer_vars.php' );
				delete_option( 'ideapark_customize' );
				delete_option( 'ideapark_customize_mods_def' );
				delete_option( 'ideapark_customize_mods_names' );
				delete_option( 'ideapark_customize_mods_images' );
			}
		}

		$default_logo_size = 205;

		$ideapark_customize = [
			[
				'section'  => 'title_tagline',
				'controls' => [
					'header_desktop_logo_info'  => [
						'label'             => __( 'Desktop Logo Settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 90,
					],
					'logo'                      => [
						'label'             => __( 'Logo', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 91,
						'refresh'           => true,
					],
					'truncate_logo_placeholder' => [
						'label'             => __( 'Truncate logo placeholder', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'priority'          => 91,
						'dependency'        => [
							'logo' => [ 'is_empty' ],
						],
					],
					'logo_sticky'               => [
						'label'             => __( 'Sticky Menu Logo', 'luchiana' ),
						'description'       => __( 'Leave empty for using main Logo', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 92,
						'refresh'           => true,
						'dependency'        => [
							'logo' => [ 'not_empty' ],
						],
					],
					'logo_size'                 => [
						'label'             => __( 'Logo max width (Desktop)', 'luchiana' ),
						'default'           => $default_logo_size,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 70,
						'max'               => 300,
						'step'              => 1,
						'priority'          => 93,
						'refresh_css'       => '.c-header__logo--desktop',
						'refresh'           => false,
						'dependency'        => [
							'logo' => [ 'not_empty' ],
						],
					],
					'header_mobile_logo_info'   => [
						'label'             => __( 'Mobile Logo Settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
						'dependency'        => [
							'logo' => [ 'not_empty' ],
						],
					],
					'logo_mobile'               => [
						'label'             => __( 'Logo', 'luchiana' ),
						'description'       => __( 'Leave empty for using desktop Logo', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 101,
						'refresh'           => true,
						'dependency'        => [
							'logo' => [ 'not_empty' ],
						],
					],
					'logo_mobile_sticky'        => [
						'label'             => __( 'Sticky Menu Logo', 'luchiana' ),
						'description'       => __( 'Leave empty for using desktop sticky Logo', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 102,
						'refresh'           => true,
						'dependency'        => [
							'logo'        => [ 'not_empty' ],
							'logo_mobile' => [ 'not_empty' ],
						],
					],
					'logo_size_mobile'          => [
						'label'             => __( 'Logo max width (Mobile)', 'luchiana' ),
						'default'           => 205,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 70,
						'max'               => 300,
						'step'              => 1,
						'priority'          => 103,
						'refresh_css'       => '.c-header__logo--mobile',
						'refresh'           => false,
						'dependency'        => [
							'logo' => [ 'not_empty' ],
						],
					],
					'header_height_mobile_link' => [
						'html'              => sprintf( __( 'Header height you can change %s here %s', 'luchiana' ), '<a href="#" class="ideapark-control-focus" data-control="header_height_mobile">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 104,
						'dependency'        => [
							'logo' => [ 'not_empty' ],
						],
					],
				],
			],
			[
				'section'  => 'background_image',
				'controls' => [
					'hide_inner_background' => [
						'label'             => __( 'Hide background on inner pages', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

				],
			],
			[
				'title'    => __( 'General Theme Settings', 'luchiana' ),
				'priority' => 5,
				'controls' => [

					'disable_block_editor' => [
						'label'             => __( 'Disable block editor', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'sticky_sidebar'       => [
						'label'             => __( 'Sticky sidebar', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'404_empty_image'      => [
						'label'             => __( 'Custom icon for 404 page', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'cart_empty_image'     => [
						'label'             => __( 'Custom icon for empty cart page', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'wishlist_empty_image' => [
						'label'             => __( 'Custom icon for empty wishlist page', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'ajax_search_post_type' => [
						'label'             => __( 'Search type', 'luchiana' ),
						'default'           => 'products',
						'type'              => 'radio',
						'choices'           => [
							'products' => __( 'Products only', 'luchiana' ),
							'whole'    => __( 'Whole site', 'luchiana' ),
						],
						'sanitize_callback' => 'sanitize_text_field',
					],

					'ajax_search' => [
						'label'             => __( 'Live search (ajax)', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'ajax_search_limit' => [
						'label'             => __( 'Number of products in the live search', 'luchiana' ),
						'default'           => 8,
						'min'               => 1,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'ajax_search' => [ 'not_empty' ],
						],
					],

					'to_top_button'       => [
						'label'             => __( 'To Top Button Enable', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'to_top_button_color' => [
						'label'             => __( 'To Top Button color', 'luchiana' ),
						'description'       => __( 'Default color if empty', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#E4C1B1',
						'dependency'        => [
							'to_top_button' => [ 'not_empty' ],
						],
					],

					'share_buttons' => [
						'label'             => __( 'Share buttons on the product and post pages', 'luchiana' ),
						'description'       => __( 'Enable or disable button, and then drag and drop tabs below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'facebook=1|twitter=1|pinterest=1|whatsapp=1|telegram=0|linkedin=0',
						'choices'           => ideapark_social_networks( [ 'facebook', 'twitter', 'pinterest', 'whatsapp', 'telegram', 'linkedin' ] ),
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			],
			[
				'panel'    => 'header_and_menu_settings',
				'title'    => __( 'Header', 'luchiana' ),
				'controls' => [
					'header_desktop_settings_info' => [
						'label'             => __( 'Desktop Header Settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'header_type' => [
						'label'             => __( 'Desktop header type', 'luchiana' ),
						'default'           => 'header-type-1',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'header-type-1' => IDEAPARK_URI . '/assets/img/header-01.png',
							'header-type-2' => IDEAPARK_URI . '/assets/img/header-02.png',
							'header-type-3' => IDEAPARK_URI . '/assets/img/header-03.png',
							'header-type-4' => IDEAPARK_URI . '/assets/img/header-04.png',
							'header-type-5' => IDEAPARK_URI . '/assets/img/header-05.png',
						],
					],

					'header_buttons'       => [
						'label'             => __( 'Header buttons', 'luchiana' ),
						'description'       => __( 'Enable or disable button, and then drag and drop blocks below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'search=1|auth=1|wishlist=1|cart=1|custom=0',
						'choices'           => [
							'search'   => __( 'Search', 'luchiana' ),
							'auth'     => __( 'Auth', 'luchiana' ),
							'wishlist' => __( 'Wishlist', 'luchiana' ),
							'cart'     => __( 'Cart', 'luchiana' ),
							'custom'   => __( 'Custom', 'luchiana' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header__buttons',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-buttons',
					],
					'custom_button_info_1' => [
						'html'              => sprintf( '%s' . __( 'Custom button setting', 'luchiana' ) . '%s', '<a href="#" class="ideapark-control-focus" data-control="custom_header_button_icon">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'header_buttons' => [ 'search=custom=1' ],
						],
					],
					'wishlist_is_disabled' => [
						'label'             => wp_kses( __( 'Wishlist button is not shown because Wishlist Page is not set ', 'luchiana' ) . '<a href="#" class="ideapark-control-focus" data-control="wishlist_page">' . __( 'here', 'luchiana' ) . '</a>', [
							'a' => [
								'href'         => true,
								'data-control' => true,
								'class'        => true
							]
						] ),
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'wishlist_page' => [ 0, '' ],
						],
					],

					'header_color_dark'        => [
						'label'             => __( 'Header buttons color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#000000',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-1', 'header-type-3' ],
						],
					],
					'sticky_menu_desktop'      => [
						'label'             => __( 'Sticky menu (desktop)', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'sticky_logo_desktop_size' => [
						'label'             => __( 'Logo width in sticky menu (Desktop)', 'luchiana' ),
						'default'           => get_theme_mod( 'logo_size', $default_logo_size ),
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 70,
						'max'               => 300,
						'step'              => 1,
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'logo'                => [ 'not_empty' ],
							'sticky_menu_desktop' => [ 'not_empty' ],
						],
					],
					'sticky_menu_color'        => [
						'label'             => __( 'Sticky menu text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'dependency'        => [
							'sticky_menu_desktop' => [ 'not_empty' ],
						],
					],
					'sticky_menu_bg_color'     => [
						'label'             => __( 'Sticky menu background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'dependency'        => [
							'sticky_menu_desktop' => [ 'not_empty' ],
						],
					],

					'header_top_bar_settings_info' => [
						'label'             => __( 'Top Bar Settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_blocks_2' => [
						'label'             => __( 'Text blocks', 'luchiana' ),
						'description'       => __( 'Enable or disable blocks, and then drag and drop blocks below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'other=1|phone=1|email=1|address=1|hours=1|menu=0',
						'choices'           => [
							'other'   => __( 'Other', 'luchiana' ),
							'phone'   => __( 'Phone', 'luchiana' ),
							'email'   => __( 'Email', 'luchiana' ),
							'address' => __( 'Address', 'luchiana' ),
							'hours'   => __( 'Working Hours', 'luchiana' ),
							'login'   => __( 'Login', 'luchiana' ),
							'menu'    => __( 'Top Bar Menu', 'luchiana' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'header_type' => [ 'header-type-2' ],
						],
					],

					'header_blocks_3' => [
						'label'             => __( 'Text blocks', 'luchiana' ),
						'description'       => __( 'Enable or disable blocks, and then drag and drop blocks below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'social=1|phone=1|email=1|address=1|hours=1|other=1|menu=0',
						'choices'           => [
							'social'  => __( 'Social Media', 'luchiana' ),
							'phone'   => __( 'Phone', 'luchiana' ),
							'email'   => __( 'Email', 'luchiana' ),
							'address' => __( 'Address', 'luchiana' ),
							'hours'   => __( 'Working Hours', 'luchiana' ),
							'other'   => __( 'Other', 'luchiana' ),
							'login'   => __( 'Login', 'luchiana' ),
							'menu'    => __( 'Top Bar Menu', 'luchiana' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'header_type' => [ 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_blocks_layout' => [
						'label'             => __( 'Text blocks layout', 'luchiana' ),
						'default'           => 'blocks-first',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'blocks-center' => IDEAPARK_URI . '/assets/img/blocks-center.png',
							'blocks-first'  => IDEAPARK_URI . '/assets/img/blocks-first.png',
							'blocks-last'   => IDEAPARK_URI . '/assets/img/blocks-last.png',
						],
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_phone'   => [
						'label'             => __( 'Phone', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header--desktop .c-header__top-row-item--phone',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-phone',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],
					'header_email'   => [
						'label'             => __( 'Email', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header--desktop .c-header__top-row-item--email',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-email',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],
					'header_address' => [
						'label'             => __( 'Address', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header--desktop .c-header__top-row-item--address',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-address',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],
					'header_hours'   => [
						'label'             => __( 'Working hours', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header--desktop .c-header__top-row-item--hours',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-hours',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],
					'header_other'   => [
						'label'             => __( 'Other', 'luchiana' ),
						'description'       => __( 'You can use some HTML tags and various shortcodes, for example, the WPML language selection shortcode', 'luchiana' ),
						'type'              => 'textarea',
						'sanitize_callback' => 'wp_kses_post',
						'default'           => '',
						'refresh'           => '.c-header--desktop .c-header__top-row-item--other',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-other',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'wpml_style' => [
						'label'             => __( 'Styling the WPML language selector', 'luchiana' ),
						'description'       => __( 'Use the shortcode [wpml_language_selector_widget] to insert the selector', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_top_bar_menu_depth' => [
						'label'             => __( 'Top bar menu depth', 'luchiana' ),
						'default'           => 'unlim',
						'type'              => 'radio',
						'choices'           => [
							1       => __( '1 level', 'luchiana' ),
							2       => __( '2 level', 'luchiana' ),
							3       => __( '3 level', 'luchiana' ),
							'unlim' => __( 'Unlimited', 'luchiana' ),
						],
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'header_blocks_2' => [ 'search!=menu=0' ],
							'header_blocks_3' => [ 'search!=menu=0' ],
						],

					],

					'header_top_background_color' => [
						'label'             => __( 'Top bar background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#000000',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_top_color' => [
						'label'             => __( 'Top bar text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_top_accent_color' => [
						'label'             => __( 'Top bar accent color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#E4C1B1',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-3', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_advert_bar_settings_info' => [
						'label'             => __( 'Advert Bar Settings', 'luchiana' ),
						'description'       => __( 'Below the main menu', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'header_advert_bar_page' => [
						'label'             => __( 'HTML block to display in the Advert Bar', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_HTML_Block_Control',
						'sanitize_callback' => 'absint',
						'refresh'           => '.c-header__advert_bar',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-advert-bar',
					],

					'header_advert_bar_placement' => [
						'label'             => __( 'Advert Bar placement', 'luchiana' ),
						'default'           => 'below',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'above' => __( 'Above the header', 'luchiana' ),
							'below' => __( 'Below the header', 'luchiana' ),
						],
						'dependency'        => [
							'header_type' => [ 'header-type-2', 'header-type-4', 'header-type-5' ],
						],
					],

					'header_mobile_settings_info' => [
						'label'             => __( 'Mobile Header Settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'header_type_mobile'              => [
						'label'             => __( 'Mobile header type', 'luchiana' ),
						'default'           => 'header-type-mobile-1',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'header-type-mobile-1' => IDEAPARK_URI . '/assets/img/header-mobile-01.png',
							'header-type-mobile-2' => IDEAPARK_URI . '/assets/img/header-mobile-02.png',
						],
					],
					'header_logo_centered_mobile'     => [
						'label'             => __( 'Centered logo', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'header_type_mobile' => [ 'header-type-mobile-2' ],
						],
					],
					'header_buttons_mobile'           => [
						'label'             => __( 'Header buttons', 'luchiana' ),
						'description'       => __( 'Enable or disable button, and then drag and drop blocks below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'search=1|auth=1|wishlist=1|cart=1|custom=0',
						'choices'           => [
							'search'   => __( 'Search', 'luchiana' ),
							'auth'     => __( 'Auth', 'luchiana' ),
							'wishlist' => __( 'Wishlist', 'luchiana' ),
							'cart'     => __( 'Cart', 'luchiana' ),
							'custom'   => __( 'Custom', 'luchiana' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header__col-mobile-buttons',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-buttons-mobile',
						'dependency'        => [
							'header_type_mobile' => [ 'header-type-mobile-2' ],
						],
					],
					'custom_button_info_2' => [
						'html'              => sprintf( '%s' . __( 'Custom button setting', 'luchiana' ) . '%s', '<a href="#" class="ideapark-control-focus" data-control="custom_header_button_icon">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'header_buttons_mobile' => [ 'search=custom=1' ],
						],
					],
					'bottom_buttons_mobile_locations' => [
						'label'             => __( 'Bottom buttons location', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'menu',
						'choices'           => [
							'menu'   => __( 'Menu bottom', 'luchiana' ),
							'screen' => __( 'Screen bottom', 'luchiana' ),
						],
					],
					'bottom_buttons_mobile'           => [
						'label'             => __( 'Bottom buttons', 'luchiana' ),
						'description'       => __( 'Enable or disable button, and then drag and drop blocks below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'search=1|auth=1|wishlist=1|cart=1|custom=0',
						'choices'           => [
							'search'   => __( 'Search', 'luchiana' ),
							'auth'     => __( 'Auth', 'luchiana' ),
							'wishlist' => __( 'Wishlist', 'luchiana' ),
							'cart'     => __( 'Cart', 'luchiana' ),
							'custom'   => __( 'Custom', 'luchiana' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-header__menu-bottom',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'header-buttons-mobile-bottom',
					],
					'custom_button_info_3' => [
						'html'              => sprintf( '%s' . __( 'Custom button setting', 'luchiana' ) . '%s', '<a href="#" class="ideapark-control-focus" data-control="custom_header_button_icon">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'bottom_buttons_mobile' => [ 'search=custom=1' ],
						],
					],
					'mobile_header_color_tr'          => [
						'label'             => __( 'Header text color (without background)', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-1', 'header-type-3' ],
						],
					],

					'header_height_mobile' => [
						'label'             => __( 'Header height', 'luchiana' ),
						'default'           => 60,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 60,
						'max'               => 120,
						'step'              => 1,
						'refresh'           => false,
						'refresh_css'       => true,
					],

					'sticky_menu_mobile' => [
						'label'             => __( 'Sticky menu (mobile)', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'sticky_header_height_mobile' => [
						'label'             => __( 'Sticky header height', 'luchiana' ),
						'default'           => 60,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 60,
						'max'               => 120,
						'step'              => 1,
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'sticky_menu_mobile' => [ 'not_empty' ],
						],
					],

					'sticky_logo_mobile_hide' => [
						'label'             => __( 'Hide Logo in sticky menu', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'sticky_menu_mobile' => [ 'not_empty' ],
						],
					],

					'mobile_header_color' => [
						'label'             => __( 'Sticky menu text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'sticky_menu_mobile' => [ 'not_empty' ],
						],
					],

					'mobile_header_background_color' => [
						'label'             => __( 'Sticky menu background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'sticky_menu_mobile' => [ 'not_empty' ],
						],
					],
					'custom_header_button_info' => [
						'label'             => __( 'Custom Header Button', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'custom_header_icon_desc_0'      => [
						'html'              => sprintf( __( 'You can upload font with custom icons %s here %s', 'luchiana' ), '<a target="_blank" href="' . esc_url( admin_url( 'themes.php?page=ideapark_fonts' ) ) . '" >', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],

					'custom_header_button_icon' => [
						'label'             => __( 'Icon', 'luchiana' ),
						'class'             => 'WP_Customize_Font_Icons_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'custom_header_button_title'  => [
						'label'             => __( 'Title', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'custom_header_button_link'  => [
						'label'             => __( 'Link', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'custom_header_button_new_window' => [
						'label'             => __( 'Open in new window', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'custom_header_button_link' => [ 'not_empty' ],
						],
					],
					'custom_header_button_no_follow' => [
						'label'             => __( 'Add nofollow', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'custom_header_button_link' => [ 'not_empty' ],
						],
					],

					'header_other_settings_info' => [
						'label'             => __( 'Other Header Settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'remove_frontpage_logo_link' => [
						'label'             => __( 'Remove the link from the logo on the front page', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'custom_header_icons_info'     => [
						'label'             => __( 'Custom Header Icons', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],
					'custom_header_icon_desc'      => [
						'html'              => sprintf( __( 'You can upload font with custom icons %s here %s', 'luchiana' ), '<a target="_blank" href="' . esc_url( admin_url( 'themes.php?page=ideapark_fonts' ) ) . '" >', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],
					'custom_header_icon_search'    => [
						'label'             => __( 'Search icon', 'luchiana' ),
						'class'             => 'WP_Customize_Font_Icons_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],
					'custom_header_icon_auth'      => [
						'label'             => __( 'Auth icon', 'luchiana' ),
						'class'             => 'WP_Customize_Font_Icons_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],
					'custom_header_icon_wishlist'  => [
						'label'             => __( 'Wishlist icon', 'luchiana' ),
						'class'             => 'WP_Customize_Font_Icons_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],
					'custom_header_icon_cart'      => [
						'label'             => __( 'Cart icon', 'luchiana' ),
						'class'             => 'WP_Customize_Font_Icons_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],
					'custom_header_icon_hamburger' => [
						'label'             => __( 'Hamburger icon', 'luchiana' ),
						'class'             => 'WP_Customize_Font_Icons_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],

					'is_font_icons_loader_on' => [
						'label'             => '',
						'description'       => '',
						'type'              => 'hidden',
						'default'           => 'ideapark_font_icons_loader_plugin_on',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'class'             => 'WP_Customize_Hidden_Control',
					],
				],
			],
			[
				'panel'    => 'header_and_menu_settings',
				'title'    => __( 'Main Menu (Desktop)', 'luchiana' ),
				'controls' => [

					'header_color_light' => [
						'label'             => __( 'Menu text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-1', 'header-type-3' ],
						],
					],

					'header_color_menu' => [
						'label'             => __( 'Menu text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#000000',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-4', 'header-type-5' ],
						],
					],

					'header_bg_color_menu' => [
						'label'             => __( 'Menu background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh'           => false,
						'refresh_css'       => true,
						'dependency'        => [
							'header_type' => [ 'header-type-4', 'header-type-5' ],
						],
					],

					'top_menu_depth'                => [
						'label'             => __( 'Main menu depth', 'luchiana' ),
						'default'           => 'unlim',
						'type'              => 'radio',
						'choices'           => [
							1       => __( '1 level', 'luchiana' ),
							2       => __( '2 level', 'luchiana' ),
							3       => __( '3 level', 'luchiana' ),
							'unlim' => __( 'Unlimited', 'luchiana' ),
						],
						'sanitize_callback' => 'sanitize_text_field',
					],
					'top_menu_arrow'                => [
						'label'             => __( 'Show drop down arrow if the item contains child items', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'top_menu_submenu_color'        => [
						'label'             => __( 'Main menu popup text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#000000',
						'refresh_css'       => true,
						'refresh'           => false,
					],
					'top_menu_submenu_bg_color'     => [
						'label'             => __( 'Main menu popup background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh_css'       => true,
						'refresh'           => false,
					],
					'top_menu_submenu_accent_color' => [
						'label'             => __( 'Main menu popup accent color', 'luchiana' ),
						'description'       => __( 'Main accent color if empty', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh_css'       => true,
						'refresh'           => false,
					],
					'top_menu_font_size'            => [
						'label'             => __( 'Font size of top-level menu items (px)', 'luchiana' ),
						'default'           => 14,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 20,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],
					'top_menu_item_space'           => [
						'label'             => __( 'Space between top-level menu items (px)', 'luchiana' ),
						'default'           => 22,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 10,
						'max'               => 30,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],

				]
			],
			[
				'panel'    => 'header_and_menu_settings',
				'title'    => __( 'Page Header', 'luchiana' ),
				'controls' => [
					'header_breadcrumbs'       => [
						'label'             => __( 'Show Breadcrumbs', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'header_font_size_desktop' => [
						'label'             => __( 'Header Font Size (px)', 'luchiana' ),
						'description'       => __( 'Desktop', 'luchiana' ),
						'default'           => 54,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 70,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],
					'header_font_size_mobile'  => [
						'label'             => __( 'Header Font Size (px)', 'luchiana' ),
						'description'       => __( 'Tablet & Mobile', 'luchiana' ),
						'default'           => 28,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 70,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],
					'header_text_color'        => [
						'label'             => __( 'Default header text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_background_color'  => [
						'label'             => __( 'Default header background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#88BAB5',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_image'             => [
						'label'             => __( 'Default header background image', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => true,
					],
					'header_background_type'   => [
						'label'             => __( 'Default header background type', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'cover',
						'choices'           => [
							'cover'  => __( 'Cover', 'luchiana' ),
							'repeat' => __( 'Repeat', 'luchiana' ),
						],
					],
					'header_background_retina' => [
						'label'             => __( 'Retina background image', 'luchiana' ),
						'description'       => __( 'When using a repeating image in the background', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'header_height' => [
						'label'             => __( 'Default header height', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default (different on different pages)', 'luchiana' ),
							'hide'   => __( 'Hide', 'luchiana' ),
							'low'    => __( 'Low', 'luchiana' ),
							'medium' => __( 'Medium', 'luchiana' ),
							'high'   => __( 'High', 'luchiana' ),
						],
					],

					'header_bg_page'      => [
						'label'             => __( 'Page header background', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'header_color_page'   => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgcolor_page' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgimage_page' => [
						'label'             => __( 'Background image', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => true,
					],
					'header_bgtype_page'  => [
						'label'             => __( 'Background type', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'cover'  => __( 'Cover', 'luchiana' ),
							'repeat' => __( 'Repeat', 'luchiana' ),
						],
						'dependency'        => [
							'header_bgimage_page' => [ 'not_empty' ],
						],
					],
					'header_height_page'  => [
						'label'             => __( 'Header height', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'hide'   => __( 'Hide', 'luchiana' ),
							'low'    => __( 'Low', 'luchiana' ),
							'medium' => __( 'Medium', 'luchiana' ),
							'high'   => __( 'High', 'luchiana' ),
						],
					],

					'header_bg_post'      => [
						'label'             => __( 'Blog and Post header background', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'header_color_post'   => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgcolor_post' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgimage_post' => [
						'label'             => __( 'Background image', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => true,
					],
					'header_bgtype_post'  => [
						'label'             => __( 'Background type', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'cover'  => __( 'Cover', 'luchiana' ),
							'repeat' => __( 'Repeat', 'luchiana' ),
						],
						'dependency'        => [
							'header_bgimage_post' => [ 'not_empty' ],
						],
					],
					'header_height_post'  => [
						'label'             => __( 'Header height', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'hide'   => __( 'Hide', 'luchiana' ),
							'low'    => __( 'Low', 'luchiana' ),
							'medium' => __( 'Medium', 'luchiana' ),
							'high'   => __( 'High', 'luchiana' ),
						],
					],

					'header_bg_wc'      => [
						'label'             => __( 'Woocommerce pages header background', 'luchiana' ),
						'description'       => __( 'Cart, Checkout, Account', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'header_color_wc'   => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgcolor_wc' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgimage_wc' => [
						'label'             => __( 'Background image', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => true,
					],
					'header_bgtype_wc'  => [
						'label'             => __( 'Background type', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'cover'  => __( 'Cover', 'luchiana' ),
							'repeat' => __( 'Repeat', 'luchiana' ),
						],
						'dependency'        => [
							'header_bgimage_wc' => [ 'not_empty' ],
						],
					],
					'header_height_wc'  => [
						'label'             => __( 'Header height', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'hide'   => __( 'Hide', 'luchiana' ),
							'low'    => __( 'Low', 'luchiana' ),
							'medium' => __( 'Medium', 'luchiana' ),
							'high'   => __( 'High', 'luchiana' ),
						],
					],

					'header_bg_product_list'      => [
						'label'             => __( 'Product list/grid header background', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'header_color_product_list'   => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgcolor_product_list' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgimage_product_list' => [
						'label'             => __( 'Background image', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => true,
					],
					'header_bgtype_product_list'  => [
						'label'             => __( 'Background type', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'cover'  => __( 'Cover', 'luchiana' ),
							'repeat' => __( 'Repeat', 'luchiana' ),
						],
						'dependency'        => [
							'header_bgimage_product_list' => [ 'not_empty' ],
						],
					],
					'header_height_product_list'  => [
						'label'             => __( 'Header height', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'hide'   => __( 'Hide', 'luchiana' ),
							'low'    => __( 'Low', 'luchiana' ),
							'medium' => __( 'Medium', 'luchiana' ),
							'high'   => __( 'High', 'luchiana' ),
						],
					],

					'header_bg_product_page'      => [
						'label'             => __( 'Product page header background', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'header_color_product_page'   => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgcolor_product_page' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => false,
						'refresh_css'       => '.c-page-header'
					],
					'header_bgimage_product_page' => [
						'label'             => __( 'Background image', 'luchiana' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => true,
					],
					'header_bgtype_product_page'  => [
						'label'             => __( 'Background type', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'cover'  => __( 'Cover', 'luchiana' ),
							'repeat' => __( 'Repeat', 'luchiana' ),
						],
						'dependency'        => [
							'header_bgimage_product_page' => [ 'not_empty' ],
						],
					],
					'header_narrow_product_page'  => [
						'label'             => __( 'Narrow header without title', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'header_height_product_page'  => [
						'label'             => __( 'Header height', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'choices'           => [
							''       => __( 'Default', 'luchiana' ),
							'hide'   => __( 'Hide', 'luchiana' ),
							'low'    => __( 'Low', 'luchiana' ),
							'medium' => __( 'Medium', 'luchiana' ),
							'high'   => __( 'High', 'luchiana' ),
						],
						'dependency'        => [
							'header_narrow_product_page' => [ 'is_empty' ],
						],
					],
					'product_header'              => [
						'label'             => __( 'Product page title', 'luchiana' ),
						'type'              => 'text',
						'default'           => __( 'Shop', 'luchiana' ),
						'sanitize_callback' => 'sanitize_textarea_field',
						'dependency'        => [
							'header_narrow_product_page' => [ 'is_empty' ],
						],
					],
				]
			],
			[
				'title'    => __( 'Footer', 'luchiana' ),
				'priority' => 105,
				'controls' => [
					'footer_page'      => [
						'label'             => __( 'HTML block to display in the footer', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_HTML_Block_Control',
						'sanitize_callback' => 'absint',
					],
					'footer_copyright' => [
						'label'             => __( 'Copyright', 'luchiana' ),
						'type'              => 'text',
						'default'           => '&copy; Copyright 2022, Luchiana WordPress Theme',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '.c-footer__copyright',
						'refresh_id'        => 'footer-copyright',
						'refresh_wrapper'   => true,
						'dependency'        => [
							'footer_page' => [ 0, '' ],
						],
					],
				],
			],
			[
				'title'           => __( 'Social Media Links', 'luchiana' ),
				'description'     => __( 'Add the full url of your social media page e.g http://twitter.com/yoursite', 'luchiana' ),
				'refresh'         => '.c-soc',
				'refresh_wrapper' => true,
				'refresh_id'      => 'soc',
				'priority'        => 130,
				'controls'        => ideapark_customizer_social_links()
			],

			[
				'title'      => __( 'Fonts', 'luchiana' ),
				'priority'   => 45,
				'section_id' => 'fonts',
				'controls'   => [
					'theme_font_header'          => [
						'label'             => __( 'Header Font (Google Font)', 'luchiana' ),
						'default'           => 'Marcellus',
						'description'       => __( 'Default font: Marcellus', 'luchiana' ),
						'sanitize_callback' => 'ideapark_sanitize_font_choice',
						'type'              => 'select',
						'choices'           => 'ideapark_get_font_choices',
					],
					'theme_font_text'            => [
						'label'             => __( 'Content Font (Google Font)', 'luchiana' ),
						'default'           => 'Inter',
						'description'       => __( 'Default font: Inter', 'luchiana' ),
						'sanitize_callback' => 'ideapark_sanitize_font_choice',
						'type'              => 'select',
						'choices'           => 'ideapark_get_font_choices',
					],
					'theme_font_subsets'         => [
						'label'             => __( 'Fonts subset (if available)', 'luchiana' ),
						'default'           => 'latin-ext',
						'description'       => __( 'Default: Latin Extended', 'luchiana' ),
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => 'ideapark_get_google_font_subsets',
					],
					'header_custom_fonts_info'   => [
						'label'             => __( 'Custom Fonts', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_metabox_on' => [ 'not_empty' ],
						],
					],
					'header_custom_fonts_reload' => [
						'html'              => ideapark_wrap( esc_html__( 'Reload the page to see the added custom fonts at the top of the font list above', 'luchiana' ), '<div class="ideapark-reload-block"><button type="button" data-href="' . esc_url( admin_url( 'customize.php?autofocus[control]=header_custom_fonts_info' ) ) . '" class="button-primary button ideapark-customizer-reload">' . esc_html__( 'Reload', 'luchiana' ) . '</button>', '</div>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'priority'          => 100,
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_metabox_on' => [ 'not_empty' ],
						],
					],
					'is_metabox_on'              => [
						'label'             => '',
						'description'       => '',
						'type'              => 'hidden',
						'default'           => 'ideapark_metabox_plugin_on',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'class'             => 'WP_Customize_Hidden_Control',
					],
				],
			],
			[
				'title'    => __( 'Post / Page', 'luchiana' ),
				'priority' => 107,
				'controls' => [

					'sidebar_settings' => [
						'label'             => __( 'Sidebar', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'sidebar_blog' => [
						'label'             => __( 'Sidebar in Blog and Archive', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'sidebar_post' => [
						'label'             => __( 'Sidebar in Post', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'post_grid_list_settings' => [
						'label'             => __( 'Post Grid / List settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'post_layout' => [
						'label'             => __( 'Blog Layout', 'luchiana' ),
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'list',
						'is_option'         => true,
						'choices'           => [
							'grid' => __( 'Grid', 'luchiana' ),
							'list' => __( 'List', 'luchiana' ),
						],
					],

					'post_grid_list_hide_category' => [
						'label'             => __( 'Hide Category', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'post_grid_list_hide_date' => [
						'label'             => __( 'Hide Date', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'post_grid_list_hide_excerpt' => [
						'label'             => __( 'Hide Excerpt', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'post_page_settings' => [
						'label'             => __( 'Post settings', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'remove_post_breadcrumbs' => [
						'label'             => __( 'Remove post title from breadcrumb', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'post_hide_category' => [
						'label'             => __( 'Hide Category', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_date'     => [
						'label'             => __( 'Hide Date', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_share'    => [
						'label'             => __( 'Hide Share Buttons', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_tags'     => [
						'label'             => __( 'Hide Tags', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_comment'  => [
						'label'             => __( 'Hide Comments Info', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_author'   => [
						'label'             => __( 'Hide Author Info', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_postnav'  => [
						'label'             => __( 'Hide Post Navigation (Prev / Next)', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],

			[
				'section'  => 'colors',
				'controls' => [

					'text_color' => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#000000',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'accent_color' => [
						'label'             => __( 'Accent color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#E4C1B1',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'button_color' => [
						'label'             => __( 'Button color', 'luchiana' ),
						'description'       => __( 'Text color if empty', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'shadow_color_desktop' => [
						'label'             => __( 'Modal window overlay color (Desktop)', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#FFFFFF',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'shadow_color_mobile' => [
						'label'             => __( 'Modal window overlay color (Mobile)', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#25201E',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'menu_color_mobile' => [
						'label'             => __( 'Main menu text color (Mobile)', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#FFFFFF',
						'sanitize_callback' => 'sanitize_text_field',
					],
				]
			],

			[
				'panel'    => 'woocommerce',
				'section'  => 'woocommerce_store_notice',
				'controls' => [
					'store_notice_info'             => [
						'html'              => sprintf( __( 'Use %s Advert Bar %s  instead of Store Notice to place ads created in Elementor above or below the header.', 'luchiana' ), '<a href="#" class="ideapark-control-focus" data-control="header_advert_bar_page">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 5,
					],
					'store_notice'                  => [
						'label'             => __( 'Store notice placement', 'luchiana' ),
						'default'           => 'top',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'priority'          => 50,
						'choices'           => [
							'top'    => __( 'At the top of the page', 'luchiana' ),
							'bottom' => __( 'At the bottom of the screen (fixed)', 'luchiana' ),
						],
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
					'store_notice_button_hide'      => [
						'label'             => __( 'Hide button', 'luchiana' ),
						'priority'          => 51,
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
					'store_notice_button_text'      => [
						'label'             => __( 'Store notice custom button text', 'luchiana' ),
						'description'       => __( 'Default if empty', 'luchiana' ),
						'type'              => 'text',
						'priority'          => 51,
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'store_notice_button_hide' => [ 'is_empty' ],
							'woocommerce_demo_store'   => [ 'not_empty' ]
						],
					],
					'store_notice_color'            => [
						'label'             => __( 'Store notice text color ', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'priority'          => 52,
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
					'store_notice_background_color' => [
						'label'             => __( 'Store notice background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#CF3540',
						'priority'          => 53,
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
				]
			],

			[
				'panel'    => 'woocommerce',
				'section'  => 'woocommerce_product_images',
				'controls' => [
					'grid_image_fit' => [
						'label'             => __( 'Grid Image fit', 'luchiana' ),
						'default'           => 'cover',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'cover'   => IDEAPARK_URI . '/assets/img/thumb-cover.png',
							'contain' => IDEAPARK_URI . '/assets/img/thumb-contain.png',
						],
					],

					'grid_image_prop' => [
						'label'             => __( 'Image aspect ratio (height / width)', 'luchiana' ),
						'default'           => 0.9,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0.4,
						'max'               => 2,
						'step'              => 0.01,
						'dependency'        => [
							'grid_image_fit' => [ 'contain' ]
						],
					],

					'product_image_fit' => [
						'label'             => __( 'Product Page Image fit', 'luchiana' ),
						'default'           => 'cover',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'cover'   => IDEAPARK_URI . '/assets/img/thumb-cover.png',
							'contain' => IDEAPARK_URI . '/assets/img/thumb-contain.png',
						],
					],

					'product_image_prop' => [
						'label'             => __( 'Image aspect ratio (height / width)', 'luchiana' ),
						'default'           => 0.9,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0.4,
						'max'               => 2,
						'step'              => 0.01,
						'dependency'        => [
							'product_image_fit' => [ 'contain' ]
						],
					],

					'product_image_background_color' => [
						'label'             => __( 'Image block background color', 'luchiana' ),
						'description'       => __( 'Transparent if empty', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
					],
				]
			],

			[
				'panel'      => 'woocommerce',
				'title'      => __( 'Luchiana Settings', 'luchiana' ),
				'priority'   => 0,
				'section_id' => 'woocommerce',
				'controls'   => [

					'disable_purchase' => [
						'label'             => __( 'Disable purchase', 'luchiana' ),
						'description'       => __( 'Completely disables the ability to order products, turning the site into a showcase of goods.', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hide_prices' => [
						'label'             => __( 'Hide prices', 'luchiana' ),
						'description'       => __( 'Hide prices in the grid and on the product page.', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'popup_cart_layout' => [
						'label'             => __( 'Pop-up Cart layout (Desktop)', 'luchiana' ),
						'default'           => 'default',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'default' => __( 'Default', 'luchiana' ),
							'sidebar' => __( 'Sidebar', 'luchiana' ),
						],
					],

					'popup_cart_auto_open_desktop' => [
						'label'             => __( 'Open the pop-up cart after adding the product (Desktop)', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'popup_cart_layout' => [ 'sidebar' ]
						],
					],

					'popup_cart_auto_open_mobile' => [
						'label'             => __( 'Open the pop-up cart after adding the product (Mobile)', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'popup_cart_modal' => [
						'label'             => __( 'Clicking outside the sidebar closes the cart', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'cart_auto_update' => [
						'label'             => __( 'Auto update cart when quantity changed on the cart page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'expand_coupon' => [
						'label'             => __( 'Expand Coupon Code block', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_grid_info' => [
						'label'             => __( 'Product Grid', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'products_per_page' => [
						'label'             => __( 'Products per page', 'luchiana' ),
						'default'           => 12,
						'min'               => 1,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'product_grid_pagination' => [
						'label'             => __( 'Paging', 'luchiana' ),
						'default'           => 'pagination',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'pagination' => __( 'Pagination', 'luchiana' ),
							'loadmore'   => __( 'Load More', 'luchiana' ),
							'infinity'   => __( 'Infinity', 'luchiana' ),
						],
					],

					'product_grid_load_more_text' => [
						'label'             => __( 'Load More button text', 'luchiana' ),
						'type'              => 'text',
						'default'           => __( 'Load More', 'luchiana' ),
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_grid_pagination' => [ 'loadmore' ],
						],
					],

					'product_grid_layout' => [
						'label'             => __( 'Product Grid Layout', 'luchiana' ),
						'default'           => '4-per-row',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'3-per-row' => __( '3 per row', 'luchiana' ),
							'4-per-row' => __( '4 per row (3 with sidebar)', 'luchiana' ),
							'compact'   => __( 'compact', 'luchiana' ),
						],
					],

					'two_per_row_mobile' => [
						'label'             => __( 'Two products per row (Mobile)', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_grid_layout' => [ '3-per-row', '4-per-row' ],
						],
					],

					'atc_button_visibility_mobile' => [
						'label'             => __( 'Buttons visibility (Mobile)', 'luchiana' ),
						'default'           => 'click',
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'choices'           => [
							'click'  => __( 'when tapping an image', 'luchiana' ),
							'always' => __( 'always', 'luchiana' ),
						],
						'dependency'        => [
							'product_grid_layout' => [ '3-per-row', '4-per-row' ],
							'show_add_to_cart'    => [ 'not_empty' ],
							'two_per_row_mobile'  => [ 'not_empty' ],
						],
					],

					'wishlist_grid_button' => [
						'label'             => __( 'Show "Add to wishlist" button', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'shop_modal' => [
						'label'             => __( 'Show "Quick view" button', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'shop_category' => [
						'label'             => __( 'Show categories', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_short_description' => [
						'label'             => __( 'Show product short description', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'show_add_to_cart' => [
						'label'             => __( 'Show "Add to cart" button', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hide_add_to_cart_mobile' => [
						'label'             => __( 'Hide "Add to cart", "Quick view" and "Add to wishlist" buttons on mobile for "2 per row" and "compact" layouts', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'atc_button_visibility' => [
						'label'             => __( 'Add to cart button visibility (Desktop)', 'luchiana' ),
						'default'           => 'hover',
						'type'              => 'radio',
						'sanitize_callback' => 'sanitize_text_field',
						'choices'           => [
							'hover'  => __( 'on hover', 'luchiana' ),
							'always' => __( 'always', 'luchiana' ),
						],
						'dependency'        => [
							'product_grid_layout' => [ '3-per-row', '4-per-row' ],
							'show_add_to_cart'    => [ 'not_empty' ]
						],
					],

					'show_subcat_in_header' => [
						'label'             => __( 'Show subcategories in Page header', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'show_subcat_count' => [
						'label'             => __( 'Show subcategory product counts', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'show_subcat_in_header' => [ 'is_empty' ],
						],
					],

					'subcat_font_size' => [
						'label'             => __( 'Subcategories Font Size (px)', 'luchiana' ),
						'default'           => 16,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 16,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
						'dependency'        => [
							'show_subcat_in_header' => [ 'not_empty' ],
						],
					],

					'switch_image_on_hover' => [
						'label'             => __( 'Switch image on hover', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_grid_layout' => [ '3-per-row', '4-per-row' ],
						],
					],

					'quickview_product_zoom' => [
						'label'             => __( 'Images zoom on touch or mouseover in Quick view', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'shop_modal' => [ 'not_empty' ],
						],
					],

					'quickview_product_zoom_mobile_hide' => [
						'label'             => __( 'Hide zoom on mobile (quick view)', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'quickview_product_zoom' => [ 'not_empty' ],
						],
					],

					'shop_sidebar' => [
						'label'             => __( 'Show sidebar', 'luchiana' ),
						'description'       => __( 'If this option is disabled, the sidebar is displayed as a popup filter.', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'single_sidebar' => [
						'label'             => __( 'Single sidebar for mobile and desktop versions', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'is_option'         => true,
					],

					'shop_sidebar_modal' => [
						'label'             => __( 'Clicking outside the popup filter closes it', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hide_uncategorized' => [
						'label'             => __( 'Hide Uncategorized (default) category', 'luchiana' ),
						'description'       => __( 'Products will be available only by direct link', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hidden_product_category' => [
						'label'             => __( 'Hide category with products', 'luchiana' ),
						'description'       => __( 'Products will be available only by direct link', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Product_Categories_Control',
						'sanitize_callback' => 'absint',
					],

					'search_by_sku' => [
						'label'             => __( 'Search by SKU', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'search_by_category' => [
						'label'             => __( 'Search by Category name', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'search_by_tag' => [
						'label'             => __( 'Search by Tag name', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'search_by_brand_info' => [
						'html'              => sprintf( __( '%s Search by Brand name %s', 'luchiana' ), '<a href="#" class="ideapark-control-focus" data-control="search_by_brand">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'show_color_variations' => [
						'label'             => __( 'Show color variations', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'is_swatches_on' => [ 'not_empty' ],
						],
					],

					'hide_single_color_variations' => [
						'label'             => __( 'Hide color variations if only one color', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'is_swatches_on'        => [ 'not_empty' ],
							'show_color_variations' => [ 'not_empty' ],
						],
					],

					'product_color_attribute' => [
						'label'             => __( 'Color attribute', 'luchiana' ),
						'description'       => __( 'Select the attribute of the product with the type `Color` or `Image`', 'luchiana' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'choices'           => 'ideapark_get_color_attributes',
						'dependency'        => [
							'show_color_variations' => [ 'not_empty' ],
							'is_swatches_on'        => [ 'not_empty' ],
						],
					],

					'color_variations_size' => [
						'label'             => __( 'Color variations size', 'luchiana' ),
						'default'           => 16,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 50,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
						'dependency'        => [
							'is_swatches_on'        => [ 'not_empty' ],
							'show_color_variations' => [ 'not_empty' ],
						],
					],

					'is_swatches_on' => [
						'label'             => '',
						'description'       => '',
						'type'              => 'hidden',
						'default'           => 'ideapark_swatches_plugin_on',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'class'             => 'WP_Customize_Hidden_Control',
					],

					'product_font_size' => [
						'label'             => __( 'Title Font Size (px)', 'luchiana' ),
						'description'       => __( '3 and 4 per row layout', 'luchiana' ),
						'default'           => 20,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 22,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],

					'product_font_size_compact' => [
						'label'             => __( 'Title Font Size (px)', 'luchiana' ),
						'description'       => __( 'Compact layout', 'luchiana' ),
						'default'           => 15,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 12,
						'max'               => 17,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],

					'product_font_letterspacing' => [
						'label'             => __( 'Title Letter Spacing (em)', 'luchiana' ),
						'description'       => __( '3 and 4 per row layout', 'luchiana' ),
						'default'           => 0.25,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0,
						'max'               => 0.30,
						'step'              => 0.01,
						'refresh_css'       => true,
						'refresh'           => false,
					],

					'short_description_link' => [
						'label'             => __( 'Link to the product card in the short description', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'category_description_position' => [
						'label'             => __( 'Position of the category description', 'luchiana' ),
						'default'           => 'below',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'above' => __( 'Above the product grid', 'luchiana' ),
							'below' => __( 'Below the product grid', 'luchiana' ),
						],
					],

					'shop_page_info' => [
						'label'             => __( 'Shop Page', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'is_shop_configured' => [
						'label'             => '',
						'description'       => '',
						'type'              => 'hidden',
						'default'           => 'ideapark_is_shop_configured',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'class'             => 'WP_Customize_Hidden_Control',
					],

					'shop_is_not_configured' => [
						'label'             => wp_kses( __( 'Shop Page is not configured ', 'luchiana' ) . '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=products' ) ) . '" >' . __( 'here', 'luchiana' ) . '</a>', [
							'a' => [
								'href'         => true,
								'data-control' => true,
								'class'        => true
							]
						] ),
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_shop_configured' => [ 0, '' ],
						],
					],

					'shop_html_block_top' => [
						'label'             => __( 'HTML block (top)', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_HTML_Block_Control',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'is_shop_configured' => [ 'not_empty' ],
						],
					],

					'shop_html_block_bottom' => [
						'label'             => __( 'HTML block (bottom)', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_HTML_Block_Control',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'is_shop_configured' => [ 'not_empty' ],
						],
					],

					'shop_html_block_first_page' => [
						'label'             => __( 'Show HTML blocks only on the first page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'is_shop_configured' => [ 'not_empty' ],
						],
					],

					'shop_top_block_above' => [
						'label'             => __( 'Show top block above the sidebar', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'is_shop_configured' => [ 'not_empty' ],
						],
					],

					'product_page_info' => [
						'label'             => __( 'Product Page', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'product_page_layout' => [
						'label'             => __( 'Product Page Layout', 'luchiana' ),
						'default'           => 'layout-1',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'layout-1' => __( 'Layout 1', 'luchiana' ),
							'layout-2' => __( 'Layout 2', 'luchiana' ),
							'layout-3' => __( 'Layout 3', 'luchiana' ),
							'layout-4' => __( 'Layout 4', 'luchiana' ),
						],
					],

					'wide_tabs_area' => [
						'label'             => __( 'Wide tab area', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_page_layout' => [ 'layout-1' ],
						],
					],

					'expand_product_tabs' => [
						'label'             => __( 'Expand product tabs on mobile', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_page_layout' => [ 'layout-1', 'layout-2' ],
						],
					],

					'product_tabs' => [
						'label'             => __( 'Product Tabs (Default)', 'luchiana' ),
						'description'       => __( 'Enable or disable tab, and then drag and drop tabs below to set up their order', 'luchiana' ),
						'type'              => 'checklist',
						'default'           => 'description=1|additional_information=1|reviews=1',
						'choices'           => [
							'description'            => __( 'Description', 'woocommerce' ),
							'additional_information' => __( 'Additional information', 'woocommerce' ),
							'reviews'                => __( 'Reviews', 'woocommerce' ),
							'html_block'             => __( 'HTML block', 'luchiana' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'description_tab_header' => [
						'label'             => __( 'Custom header of the "Description" tab', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_tabs' => [ 'search=description=1' ],
						],
					],

					'additional_information_tab_header' => [
						'label'             => __( 'Custom header of the "Additional information" tab', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_tabs' => [ 'search=additional_information=1' ],
						],
					],

					'shop_product_modal' => [
						'label'             => __( 'Images modal gallery', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_inline_video' => [
						'label'             => __( 'Inline video', 'luchiana' ),
						'description'       => __( 'Video playback on the product page. Supported formats: mp4, m4v, webm, ogv, flv. Autoplay, looped, muted, without controls.', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'shop_product_zoom' => [
						'label'             => __( 'Images zoom on touch or mouseover on product page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'shop_product_zoom_mobile_hide' => [
						'label'             => __( 'Hide zoom on mobile (product page)', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'shop_product_zoom' => [ 'not_empty' ],
						],
					],

					'product_share' => [
						'label'             => __( 'Show share buttons', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hide_variable_price_range' => [
						'label'             => __( 'Hide price range in the variable product', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hide_stock_info' => [
						'label'             => __( 'Hide stock quantity', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'hide_sku' => [
						'label'             => __( 'Hide SKU', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'sticky_add_to_cart' => [
						'label'             => __( 'Sticky Add To Cart (Mobile)', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'remove_product_breadcrumbs' => [
						'label'             => __( 'Remove product name from breadcrumb', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'buy_now' => [
						'label'             => __( 'Buy now', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'is_woocommerce_ajax_on' => [ 'not_empty' ],
						],
					],

					'product_page_ajax_add_to_cart' => [
						'label'             => __( 'Ajax Add to Cart', 'luchiana' ),
						'description'       => __( 'This option will enable the Ajax add to cart functionality on a product page. WooCommerce doesn`t have this option built-in, so theme implementation might not be compatible with a certain plugin you`re using, so it would be best to keep it disabled.', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_video_position' => [
						'label'             => __( 'Position of the product video in thumbnails', 'luchiana' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 4,
						'choices'           => [
							1 => '1',
							2 => '2',
							3 => '3',
							4 => '4',
						],
					],

					'product_page_font_size_desktop' => [
						'label'             => __( 'Title Font Size (Desktop)', 'luchiana' ),
						'default'           => 30,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 16,
						'max'               => 40,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],

					'product_page_font_size_mobile' => [
						'label'             => __( 'Title Font Size (Mobile)', 'luchiana' ),
						'default'           => 22,
						'type'              => 'slider',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 16,
						'max'               => 40,
						'step'              => 1,
						'refresh_css'       => true,
						'refresh'           => false,
					],

					'product_page_custom_html_info' => [
						'label'             => __( 'Custom HTML (Product Page)', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_page_custom_html' => [
						'label'             => __( 'Content', 'luchiana' ),
						'type'              => 'text_editor',
						'default'           => '',
						'sanitize_callback' => 'wp_kses_post',
						'class'             => 'WP_Customize_Text_Editor_Control',
						'dependency'        => [
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_html_text_color' => [
						'label'             => __( 'Text color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_html_background_color' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_html_border' => [
						'label'             => __( 'Show border', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_html_border_color' => [
						'label'             => __( 'Border color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_html_border' => [ 'not_empty' ],
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_html_2_col' => [
						'label'             => __( 'Two-column lists', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_page_blocks' => [ 'search!=ideapark_product_custom_html=0' ],
						],
					],

					'product_html_priority' => [
						'label'             => __( 'Section Priority', 'luchiana' ),
						'description'       => __( '.', 'luchiana' ),
						'default'           => 33,
						'min'               => 1,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'product_brand_info' => [
						'label'             => __( 'Product Brand', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'product_brand_attribute' => [
						'label'             => __( 'Brand attribute', 'luchiana' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'choices'           => 'ideapark_get_all_attributes',
						'is_option'         => true,
					],

					'show_product_grid_brand' => [
						'label'             => __( 'Show Brand in grid', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'show_product_page_brand' => [
						'label'             => __( 'Show Brand on product page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'hide_brand_additional_tab' => [
						'label'             => __( 'Hide Brand in "Additional information" tab of the product page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'show_product_page_brand_logo' => [
						'label'             => __( 'Show Brand logo on product page', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
							'show_product_page_brand' => [ 'not_empty' ]
						],
					],

					'show_cart_page_brand' => [
						'label'             => __( 'Show Brand on cart page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'search_by_brand' => [
						'label'             => __( 'Search by Brand name', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'brands_page' => [
						'label'             => __( 'Brands page', 'luchiana' ),
						'description'       => __( 'Page with a list of brands. Used in breadcrumbs', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Page_Control',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'product_rating_info'    => [
						'label'             => __( 'Product Star Rating', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'product_preview_rating' => [
						'label'             => __( 'Show star rating in the product list', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'product_preview_number' => [
						'label'             => __( 'Show number of comments', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_preview_rating' => [ 'not_empty' ],
						],
					],
					'star_rating_color'      => [
						'label'             => __( 'Star rating color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#FFAA8F',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'product_badges_info'    => [
						'label'             => __( 'Product Badges', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'outofstock_badge_color' => [
						'label'             => __( 'Out of stock badge color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'outofstock_badge_text'  => [
						'label'             => __( 'Out of stock text', 'luchiana' ),
						'description'       => __( 'Disabled if empty', 'luchiana' ),
						'type'              => 'text',
						'default'           => __( 'Out of stock', 'luchiana' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'featured_badge_color'   => [
						'label'             => __( 'Featured badge color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'featured_badge_text'    => [
						'label'             => __( 'Featured badge text', 'luchiana' ),
						'description'       => __( 'Disabled if empty', 'luchiana' ),
						'type'              => 'text',
						'default'           => __( 'Featured', 'luchiana' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sale_badge_color'       => [
						'label'             => __( 'Sale badge color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sale_badge_text'        => [
						'label'             => __( 'Sale badge text', 'luchiana' ),
						'description'       => __( 'Disabled if empty', 'luchiana' ),
						'type'              => 'text',
						'default'           => __( 'Sale', 'luchiana' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sale_badge_layout'      => [
						'label'             => __( 'Show', 'luchiana' ),
						'default'           => 'percentage',
						'type'              => 'radio',
						'choices'           => [
							'percentage' => __( 'Percentage', 'luchiana' ),
							'text'       => __( 'Text', 'luchiana' ),
						],
						'sanitize_callback' => 'sanitize_text_field',
					],
					'new_badge_color'        => [
						'label'             => __( 'New badge color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'new_badge_text'         => [
						'label'             => __( 'New badge text', 'luchiana' ),
						'description'       => __( 'Disabled if empty', 'luchiana' ),
						'type'              => 'text',
						'default'           => __( 'New', 'luchiana' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'product_newness'        => [
						'label'             => __( 'Product newness', 'luchiana' ),
						'description'       => __( 'Display the New badge for how many days? Set 0 for disable `NEW` badge.', 'luchiana' ),
						'default'           => 5,
						'min'               => 1,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'wishlist_settings_info' => [
						'label'             => __( 'Wishlist', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'wishlist_page' => [
						'label'             => __( 'Wishlist page', 'luchiana' ),
						'description'       => __( 'Deselect to disable wishlist', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Page_Control',
						'sanitize_callback' => 'absint',
					],

					'wishlist_share' => [
						'label'             => __( 'Wishlist share buttons', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'wishlist_page' => [ 'not_empty' ],
						],
					],

					'related_settings_info' => [
						'label'             => __( 'Related products, Upsells, Cross-sells', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'related_product_layout' => [
						'label'             => __( 'Grid Layout (Desktop)', 'luchiana' ),
						'default'           => '4-per-row',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							'3-per-row' => __( '3 per row', 'luchiana' ),
							'4-per-row' => __( '4 per row', 'luchiana' ),
							'compact'   => __( 'compact', 'luchiana' ),
						],
					],

					'related_product_carousel' => [
						'label'             => __( 'Carousel', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'related_product_number' => [
						'label'             => __( 'Number of related products', 'luchiana' ),
						'default'           => 4,
						'min'               => 0,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'related_product_header' => [
						'label'             => __( 'Related products custom header', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'upsells_product_header' => [
						'label'             => __( 'Upsells custom header', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'cross_sells_product_header' => [
						'label'             => __( 'Cross-sells custom header', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'recently_settings_info' => [
						'label'             => __( 'Recently viewed products', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'recently_enabled' => [
						'label'             => __( 'Enable recently viewed products', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'recently_grid_layout' => [
						'label'             => __( 'Grid layout (Desktop)', 'luchiana' ),
						'default'           => '4-per-row',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							'3-per-row' => __( '3 per row', 'luchiana' ),
							'4-per-row' => __( '4 per row', 'luchiana' ),
							'compact'   => __( 'compact', 'luchiana' ),
						],
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_product_number' => [
						'label'             => __( 'Number of products', 'luchiana' ),
						'default'           => 4,
						'min'               => 0,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_product_show' => [
						'label'             => __( 'Show on Product page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_shop_show' => [
						'label'             => __( 'Show on Shop page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_cart_show' => [
						'label'             => __( 'Show on Cart page', 'luchiana' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_product_header' => [
						'label'             => __( 'Custom header', 'luchiana' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_position' => [
						'label'             => __( 'Position', 'luchiana' ),
						'default'           => 'above',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							'above' => __( 'Above other sections', 'luchiana' ),
							'below' => __( 'Below other sections', 'luchiana' ),
						],
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'account_settings_info' => [
						'label'             => __( 'Account', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'register_page' => [
						'label'             => __( 'Registration page', 'luchiana' ),
						'description'       => __( 'Select a page with a custom registration form, if you use it instead of the standard registration form.', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Page_Control',
						'sanitize_callback' => 'absint',
					],

					'maintenance_mode_page' => [
						'label'             => __( 'Coming soon page', 'luchiana' ),
						'description'       => __( 'Select the page that will be displayed when “Coming soon” is enabled in the WooCommerce settings.', 'luchiana' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Page_Control',
						'sanitize_callback' => 'absint',
					],

					'product_features_info' => [
						'label'             => __( 'Features', 'luchiana' ),
						'description'       => __( 'Displayed on the product page', 'luchiana' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_metabox_on' => [ 'not_empty' ],
						],
					],

					'product_features_icon_desc' => [
						'html'              => sprintf( __( 'You can upload font with custom icons %s here %s', 'luchiana' ), '<a target="_blank" href="' . esc_url( admin_url( 'themes.php?page=ideapark_fonts' ) ) . '" >', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_font_icons_loader_on' => [ 'not_empty' ],
						],
					],

					'product_features_icon_color' => [
						'label'             => __( 'Icon color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
					],

					'product_features_text_color' => [
						'label'             => __( 'Title color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
					],

					'product_features_description_color' => [
						'label'             => __( 'Description color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
					],

					'product_features_background_color' => [
						'label'             => __( 'Background color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
					],

					'product_features_border' => [
						'label'             => __( 'Show border', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'priority'          => 100,
					],

					'product_features_border_color' => [
						'label'             => __( 'Border color', 'luchiana' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
						'dependency'        => [
							'product_features_border' => [ 'not_empty' ],
						],
					],

					'is_woocommerce_on' => [
						'label'             => '',
						'description'       => '',
						'type'              => 'hidden',
						'default'           => 'ideapark_woocommerce_on',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'class'             => 'WP_Customize_Hidden_Control',
					],
				],
			],

			[
				'title'       => __( 'Performance', 'luchiana' ),
				'description' => __( 'Use these options to put your theme to a high speed as well as save your server resources!', 'luchiana' ),
				'priority'    => 130,
				'controls'    => [
					'use_minified_css'          => [
						'label'             => __( 'Use minified CSS', 'luchiana' ),
						'description'       => __( 'Load all theme css files combined and minified into a single file', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'use_minified_js'           => [
						'label'             => __( 'Use minified JS', 'luchiana' ),
						'description'       => __( 'Load all theme js files combined and minified into a single file', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'load_jquery_in_footer'     => [
						'label'             => __( 'Load jQuery in footer', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'lazyload'                  => [
						'label'             => __( 'Lazy load images', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'google_fonts_display_swap' => [
						'label'             => __( 'Use parameter display=swap for Google Fonts', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'js_delay'                  => [
						'label'             => __( 'Delay JavaScript execution', 'luchiana' ),
						'description'       => __( 'Improves performance by delaying the execution of JavaScript until user interaction (e.g. scroll, click). ', 'luchiana' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
		];

		ideapark_parse_added_blocks();

		ideapark_add_last_control();

		$ideapark_customize_mods_def    = [];
		$ideapark_customize_mods_names  = [];
		$ideapark_customize_mods_images = [];
		foreach ( $ideapark_customize as $section ) {
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					if ( isset( $control['default'] ) ) {
						$ideapark_customize_mods_def[ $control_name ] = $control['default'];
					}
					$ideapark_customize_mods_names[] = $control_name;
					if ( isset( $control['class'] ) && $control['class'] == 'WP_Customize_Image_Control' ) {
						$ideapark_customize_mods_images[] = $control_name;
					}
				}
			}
		}

		$code = "<?php\n";
		$code .= '$ideapark_customize_mods_ver = "' . $version . '";' . "\n";
		foreach ( [ 'ideapark_customize', 'ideapark_customize_mods_def', 'ideapark_customize_mods_names', 'ideapark_customize_mods_images' ] as $var_name ) {
			$code .= 'global $' . $var_name . ";\n";
			$code .= '$' . $var_name . ' = ' . ideapark_array_to_php_code( $$var_name ) . ";\n\n";
		}
		ideapark_fpc( $fn = IDEAPARK_UPLOAD_DIR . 'customizer_vars.php', $code );
		if ( ! ideapark_is_file( $fn ) ) {
			update_option( 'ideapark_customize', [
				'version'  => $version,
				'settings' => $ideapark_customize
			], false );
			update_option( 'ideapark_customize_mods_def', $ideapark_customize_mods_def, false );
			update_option( 'ideapark_customize_mods_names', $ideapark_customize_mods_names, false );
			update_option( 'ideapark_customize_mods_images', $ideapark_customize_mods_images, false );
		}
	}
}

if ( ! function_exists( 'ideapark_array_to_php_code' ) ) {
	function ideapark_array_to_php_code( $array ) {
		if ( ! is_array( $array ) ) {
			return '';
		}
		$code = '[' . "\n";
		foreach ( $array as $key => $value ) {
			$code .= '"' . $key . '" => ' . ( is_array( $value ) ? ideapark_array_to_php_code( $value ) : ( ( is_string( $value ) ? '"' : '' ) . addslashes( is_bool( $value ) ? ( $value ? 'true' : 'false' ) : $value ) . ( is_string( $value ) ? '"' : '' ) ) ) . ',' . "\n";
		}
		$code .= ']';

		return $code;
	}
}

if ( ! function_exists( 'ideapark_reset_theme_mods' ) ) {
	function ideapark_reset_theme_mods() {
		global $ideapark_customize;

		if ( ! empty( $ideapark_customize ) ) {
			foreach ( $ideapark_customize as $section ) {
				if ( ! empty( $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( isset( $control['default'] ) ) {
							set_theme_mod( $control_name, $control['default'] );
							ideapark_mod_set_temp( $control_name, $control['default'] );
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'ideapark_fix_theme_mods' ) ) {
	function ideapark_fix_theme_mods( $old_version = '', $new_version = '' ) {
		if ( $old_version && $new_version && version_compare( $old_version, '4.14', '<=' ) && version_compare( $new_version, '4.14', '>' ) ) {
			if ( $languages = ideapark_active_languages() ) {
				foreach ( $languages as $lang_code => $lang_name ) {
					if ( $font = ideapark_mod( $old_mod_name = 'theme_font_text_' . preg_replace( '~_.+$~', '', $lang_code ) ) ) {
						set_theme_mod( 'theme_font_text_' . $lang_code, $font );
						remove_theme_mod( $old_mod_name );
					}
				}
			}
		}
		if ( $old_version && $new_version && version_compare( $old_version, '5.1', '<' ) && version_compare( $new_version, '5.1', '>=' ) ) {
			$header_buttons        = ideapark_mod( 'header_buttons' );
			$header_buttons_mobile = ideapark_mod( 'header_buttons_mobile' );
			if ( ( $v = get_theme_mod( 'auth_enabled', null ) ) !== null ) {
				if ( ! $v ) {
					$header_buttons        = str_replace( 'auth=1', 'auth=0', $header_buttons );
					$header_buttons_mobile = str_replace( 'auth=1', 'auth=0', $header_buttons_mobile );
					remove_theme_mod( 'auth_enabled' );
				}
			}
			if ( ( $v = get_theme_mod( 'search_enabled', null ) ) !== null ) {
				if ( ! $v ) {
					$header_buttons        = str_replace( 'search=1', 'search=0', $header_buttons );
					$header_buttons_mobile = str_replace( 'search=1', 'search=0', $header_buttons_mobile );
					remove_theme_mod( 'search_enabled' );
				}
			}
			if ( ( $v = get_theme_mod( 'wishlist_enabled', null ) ) !== null ) {
				if ( ! $v ) {
					$header_buttons        = str_replace( 'wishlist=1', 'wishlist=0', $header_buttons );
					$header_buttons_mobile = str_replace( 'wishlist=1', 'wishlist=0', $header_buttons_mobile );
					remove_theme_mod( 'wishlist_enabled' );
				}
			}
			if ( ( $v = get_theme_mod( 'cart_enabled', null ) ) !== null ) {
				if ( ! $v ) {
					$header_buttons        = str_replace( 'cart=1', 'cart=0', $header_buttons );
					$header_buttons_mobile = str_replace( 'cart=1', 'cart=0', $header_buttons_mobile );
					remove_theme_mod( 'cart_enabled' );
				}
			}

			if ( ideapark_mod( 'auth_disabled_mobile' ) ) {
				$header_buttons_mobile = str_replace( 'auth=1', 'auth=0', $header_buttons_mobile );
				remove_theme_mod( 'auth_disabled_mobile' );
			}
			if ( ideapark_mod( 'search_disabled_mobile' ) ) {
				$header_buttons_mobile = str_replace( 'search=1', 'search=0', $header_buttons_mobile );
				remove_theme_mod( 'search_disabled_mobile' );
			}
			if ( ideapark_mod( 'wishlist_disabled_mobile' ) ) {
				$header_buttons_mobile = str_replace( 'wishlist=1', 'wishlist=0', $header_buttons_mobile );
				remove_theme_mod( 'wishlist_disabled_mobile' );
			}
			if ( ideapark_mod( 'cart_disabled_mobile' ) ) {
				$header_buttons_mobile = str_replace( 'cart=1', 'cart=0', $header_buttons_mobile );
				remove_theme_mod( 'cart_disabled_mobile' );
			}
			if ( $header_buttons ) {
				set_theme_mod( 'header_buttons', $header_buttons );
			}
			if ( $header_buttons_mobile ) {
				set_theme_mod( 'header_buttons_mobile', $header_buttons_mobile );
			}
		}
		if ( $old_version && $new_version && version_compare( $old_version, '4.28', '<=' ) && version_compare( $new_version, '4.28', '>' ) ) {
			delete_option( 'ideapark_customize' );
			delete_option( 'ideapark_google_fonts' );
		}
	}
}

if ( ! function_exists( 'ideapark_fix_products_per_page' ) ) {
	function ideapark_fix_products_per_page( $old_version = '', $new_version = '' ) {
		if ( $old_version && $new_version && version_compare( $old_version, '3.18', '<=' ) && version_compare( $new_version, '3.18', '>' ) ) {
			$products_per_page = (int) get_option( 'woocommerce_catalog_columns', 0 ) * (int) get_option( 'woocommerce_catalog_rows', 0 );
			if ( ! $products_per_page ) {
				$products_per_page = 12;
			}
			set_theme_mod( 'products_per_page', $products_per_page );
		}
	}
}

if ( ! function_exists( 'ideapark_fix_woo_variation_swatches_shape' ) ) {
	function ideapark_fix_woo_variation_swatches_shape( $old_version = '', $new_version = '' ) {
		if ( $old_version && $new_version && version_compare( $old_version, '3.18', '<=' ) && version_compare( $new_version, '3.18', '>' ) ) {
			if ( $options = get_option( 'woo_variation_swatches' ) ) {
				if ( ! empty( $options['style'] ) ) {
					$options['shape_style'] = $options['style'];
					update_option( 'woo_variation_swatches', $options );
				}
			}
		}
	}
}

if ( ! function_exists( 'ideapark_init_theme_mods' ) ) {
	function ideapark_init_theme_mods() {
		global $ideapark_customize_mods, $ideapark_customize_mods_def, $ideapark_customize_mods_names, $ideapark_customize_mods_images;

		$ideapark_customize_mods = get_theme_mods();

		foreach ( $ideapark_customize_mods_names as $name ) {
			if ( ! is_array( $ideapark_customize_mods ) || ! array_key_exists( $name, $ideapark_customize_mods ) ) {
				$ideapark_customize_mods[ $name ] = apply_filters( "theme_mod_{$name}", array_key_exists( $name, $ideapark_customize_mods_def ) ? $ideapark_customize_mods_def[ $name ] : null );
			} else {
				$ideapark_customize_mods[ $name ] = apply_filters( "theme_mod_{$name}", $ideapark_customize_mods[ $name ] );
			}
		}

		if ( is_customize_preview() && $ideapark_customize_mods_images && ! IDEAPARK_IS_AJAX_HEARTBEAT ) {
			foreach ( $ideapark_customize_mods_images as $control_name ) {
				if ( ( $url = get_theme_mod( $control_name ) ) && ( $attachment_id = attachment_url_to_postid( $url ) ) ) {
					$params = wp_get_attachment_image_src( $attachment_id, 'full' );

					$ideapark_customize_mods[ $control_name . '__url' ]           = $params[0];
					$ideapark_customize_mods[ $control_name . '__attachment_id' ] = $attachment_id;
					$ideapark_customize_mods[ $control_name . '__width' ]         = $params[1];
					$ideapark_customize_mods[ $control_name . '__height' ]        = $params[2];
				} else {
					$ideapark_customize_mods[ $control_name . '__url' ]           = null;
					$ideapark_customize_mods[ $control_name . '__attachment_id' ] = null;
					$ideapark_customize_mods[ $control_name . '__width' ]         = null;
					$ideapark_customize_mods[ $control_name . '__height' ]        = null;
				}
			}
		}

		if ( is_customize_preview() && ! IDEAPARK_IS_AJAX_HEARTBEAT ) {
			if ( ideapark_is_elementor() && isset( $_POST['customized'] ) && ( $customized = json_decode( wp_unslash( $_POST['customized'] ), true ) ) ) {
				foreach ( $customized as $key => $val ) {
					if ( preg_match( '~color~', $key ) ) {
						$elementor_instance = Elementor\Plugin::instance();
						$elementor_instance->files_manager->clear_cache();
						break;
					}
				}
			}
		}

		do_action( 'ideapark_init_theme_mods' );
	}
}

if ( ! function_exists( 'ideapark_mod' ) ) {
	function ideapark_mod( $mod_name ) {
		global $ideapark_customize_mods;

		if ( array_key_exists( $mod_name, $ideapark_customize_mods ) ) {
			return $ideapark_customize_mods[ $mod_name ];
		} else {
			return get_option( 'luchiana_mod_' . $mod_name, null );
		}
	}
}

if ( ! function_exists( 'ideapark_mod_default' ) ) {
	function ideapark_mod_default( $mod_name ) {
		global $ideapark_customize_mods_def;

		if ( array_key_exists( $mod_name, $ideapark_customize_mods_def ) ) {
			return $ideapark_customize_mods_def[ $mod_name ];
		} else {
			return null;
		}
	}
}

if ( ! function_exists( 'ideapark_mod_set_temp' ) ) {
	function ideapark_mod_set_temp( $mod_name, $value ) {
		global $ideapark_customize_mods;
		if ( $value === null && isset( $ideapark_customize_mods[ $mod_name ] ) ) {
			unset( $ideapark_customize_mods[ $mod_name ] );
		} else {
			$ideapark_customize_mods[ $mod_name ] = $value;
		}
	}
}

if ( ! function_exists( 'ideapark_register_theme_customize' ) ) {
	function ideapark_register_theme_customize( $wp_customize ) {
		global $ideapark_customize_custom_css, $ideapark_customize;

		/**
		 * @var  WP_Customize_Manager $wp_customize
		 **/

		if ( class_exists( 'WP_Customize_Control' ) ) {

			class WP_Customize_Image_Radio_Control extends WP_Customize_Control {
				public $type = 'image-radio';

				public function render_content() {
					$input_id         = '_customize-input-' . $this->id;
					$description_id   = '_customize-description-' . $this->id;
					$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

					if ( empty( $this->choices ) ) {
						return;
					}

					$name = '_customize-radio-' . $this->id;
					?>
					<?php if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span id="<?php echo esc_attr( $description_id ); ?>"
							  class="description customize-control-description"><?php echo ideapark_wrap( $this->description ); ?></span>
					<?php endif; ?>

					<?php foreach ( $this->choices as $value => $label ) { ?>
						<span class="customize-inside-control-row">
						<label>
						<input
							id="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"
							type="radio"
							<?php echo ideapark_wrap( $describedby_attr ); ?>
							value="<?php echo esc_attr( $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
							/>
						<?php echo( substr( $label, 0, 4 ) == 'http' ? '<img class="ideapark-radio-img" src="' . esc_url( $label ) . '">' : esc_html( $label ) ); ?></label>
						</span><?php
					}
				}
			}

			class WP_Customize_Number_Control extends WP_Customize_Control {
				public $type = 'number';

				public function render_content() {
					?>
					<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<input type="number" name="quantity" <?php $this->link(); ?>
						       <?php if ( ! empty( $this->input_attrs['pattern'] ) ) { ?>pattern="<?php echo esc_attr( $this->input_attrs['pattern'] ); ?>"<?php } ?>
						       <?php if ( isset( $this->input_attrs['min'] ) ) { ?>min="<?php echo esc_attr( $this->input_attrs['min'] ); ?>"<?php } ?>
						       <?php if ( isset( $this->input_attrs['max'] ) ) { ?>max="<?php echo esc_attr( $this->input_attrs['max'] ); ?>"<?php } ?>
						       <?php if ( isset( $this->input_attrs['step'] ) ) { ?>step="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"<?php } ?>
							   value="<?php echo esc_textarea( $this->value() ); ?>" style="width:70px;">
					</label>
					<?php
				}
			}

			class WP_Customize_Category_Control extends WP_Customize_Control {

				public function render_content() {
					$dropdown = wp_dropdown_categories(
						[
							'name'              => '_customize-dropdown-categories-' . $this->id,
							'echo'              => 0,
							'show_option_none'  => '&mdash; ' . esc_html__( 'Select', 'luchiana' ) . ' &mdash;',
							'option_none_value' => '0',
							'selected'          => $this->value(),
						]
					);

					$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_Page_Control extends WP_Customize_Control {

				public function render_content() {
					$dropdown = wp_dropdown_pages(
						[
							'name'              => '_customize-dropdown-pages-' . $this->id,
							'echo'              => 0,
							'show_option_none'  => '&mdash; ' . esc_html__( 'Select', 'luchiana' ) . ' &mdash;',
							'option_none_value' => '0',
							'selected'          => $this->value(),
							'post_status'       => [ 'publish', 'draft' ],

						]
					);

					$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_Font_Icons_Control extends WP_Customize_Control {

				public function render_content() {
					$fonts_info = get_option( 'ideapark_fonts_info' );
					$icons      = [];

					if ( ! empty( $fonts_info['fonts'] ) ) {

						foreach ( $fonts_info['fonts'] as $_font_name => $_font ) {
							foreach ( $_font['unicodes'] as $class_name => $code ) {
								$icons[ $class_name ] = $class_name;
							}
						}
					}

					if ( $icons ) {
						$dropdown = '<select ' . $this->get_link() . ' class="customize-control-font-icons" data-placeholder="' . '&mdash; ' . esc_attr__( 'Select Icon', 'luchiana' ) . ' &mdash;' . '"><option></option>';

						foreach ( $icons as $icon_val => $icon_name ) {
							$dropdown .= '<option value="' . esc_attr( $icon_val ) . '" ' . selected( $this->value(), $icon_val, false ) . '>' . esc_html( $icon_name ) . '</option>';
						}

						printf(
							'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
							$this->label,
							$dropdown
						);
					}
				}
			}

			class WP_Customize_Product_Categories_Control extends WP_Customize_Control {

				public function render_content() {
					$list = [ 0 => '&mdash; ' . esc_html__( 'Select', 'luchiana' ) . ' &mdash;', ];

					$args = [
						'taxonomy'     => 'product_cat',
						'orderby'      => 'term_group',
						'show_count'   => 0,
						'pad_counts'   => 0,
						'hierarchical' => 1,
						'title_li'     => '',
						'hide_empty'   => 0,
						'exclude'      => ideapark_mod( 'hide_uncategorized' ) ? get_option( 'default_product_cat' ) : null,
					];
					if ( $all_categories = get_categories( $args ) ) {

						$category_name   = [];
						$category_parent = [];
						foreach ( $all_categories as $cat ) {
							$category_name[ $cat->term_id ]    = esc_html( $cat->name );
							$category_parent[ $cat->parent ][] = $cat->term_id;
						}

						$get_category = function ( $parent = 0, $prefix = ' - ' ) use ( &$list, &$category_parent, &$category_name, &$get_category ) {
							if ( array_key_exists( $parent, $category_parent ) ) {
								$categories = $category_parent[ $parent ];
								foreach ( $categories as $category_id ) {
									$list[ $category_id ] = $prefix . $category_name[ $category_id ];
									$get_category( $category_id, $prefix . ' - ' );
								}
							}
						};

						$get_category();
					}

					$dropdown = '<select ' . $this->get_link() . '>';
					foreach ( $list as $category_id => $category_name ) {
						$dropdown .= '<option value="' . esc_attr( $category_id ) . '" ' . selected( $category_id, $this->value(), false ) . '>' . esc_html( $category_name ) . '</option>';
					}

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_HTML_Block_Control extends WP_Customize_Control {

				public function render_content() {
					$dropdown = wp_dropdown_pages(
						[
							'name'              => '_customize-dropdown-pages-' . $this->id,
							'echo'              => 0,
							'show_option_none'  => '&mdash; ' . esc_html__( 'Select', 'luchiana' ) . ' &mdash;',
							'option_none_value' => '0',
							'selected'          => $this->value(),
							'post_type'         => 'html_block',
							'post_status'       => [ 'publish' ],
						]
					);

					$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>
						<div class="ideapark-manage-blocks"><a href="' . esc_url( admin_url( 'edit.php?post_type=html_block' ) ) . '">' . esc_html__( 'Manage html blocks', 'luchiana' ) . '</a></div>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_Info_Control extends WP_Customize_Control {
				public $type = 'info';

				public function render_content() {
					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="ideapark-customizer-subheader__title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="ideapark-customizer-subheader__description">', '</span>' ),
						'<div class="ideapark-customizer-subheader">',
						'</div>'
					);
				}
			}

			class WP_Customize_HTML_Control extends WP_Customize_Control {
				public $type = 'html';

				public function render_content() {
					echo isset( $this->input_attrs['html'] ) ? ideapark_wrap( $this->input_attrs['html'], '<div class="customize-control-wrap">', '</div>' ) : '';
				}
			}

			class WP_Customize_Warning_Control extends WP_Customize_Control {
				public $type = 'warning';

				public function render_content() {
					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="notification-message">', '</span>' ),
						'<div class="ideapark-notice ideapark-notice--warning">',
						'</div>'
					);
				}
			}

			class WP_Customize_Text_Editor_Control extends WP_Customize_Control {
				public $type = 'text_editor';

				public function render_content() {

					if ( function_exists( 'wp_enqueue_editor' ) ) {
						wp_enqueue_editor();
					}
					ob_start();
					wp_editor(
						$this->value(), '_customize-text-editor-' . esc_attr( $this->id ), [
							'default_editor' => 'tmce',
							'wpautop'        => isset( $this->input_attrs['wpautop'] ) ? $this->input_attrs['wpautop'] : false,
							'teeny'          => isset( $this->input_attrs['teeny'] ) ? $this->input_attrs['teeny'] : false,
							'textarea_rows'  => isset( $this->input_attrs['rows'] ) && $this->input_attrs['rows'] > 1 ? $this->input_attrs['rows'] : 10,
							'editor_height'  => 16 * ( isset( $this->input_attrs['rows'] ) && $this->input_attrs['rows'] > 1 ? (int) $this->input_attrs['rows'] : 10 ),
							'tinymce'        => [
								'resize'             => false,
								'wp_autoresize_on'   => false,
								'add_unload_trigger' => false,
							],
						]
					);
					$editor_html = ob_get_contents();
					ob_end_clean();

					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'<span class="customize-control-field-wrap">
							<input type="hidden"' . $this->get_link() .
						( ! empty( $this->input_attrs['var_name'] ) ? ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"' : '' ) .
						' value="' . esc_textarea( $this->value() ) . '" />' .

						ideapark_wrap( $editor_html, '<div class="ideapark_text_editor">', '</div>' ) . ' 
					</span></div>'
					);

					ideapark_mod_set_temp( 'need_footer_scripts', true );
				}
			}

			class WP_Customize_Select_Control extends WP_Customize_Control {
				public $type = 'select';

				public function render_content() {
					$input_id         = '_customize-input-' . $this->id;
					$description_id   = '_customize-description-' . $this->id;
					$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
					if ( empty( $this->choices ) ) {
						return;
					}

					?>
					<?php if ( ! empty( $this->label ) ) : ?>
						<label for="<?php echo esc_attr( $input_id ); ?>"
							   class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
					<?php endif; ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span id="<?php echo esc_attr( $description_id ); ?>"
							  class="description customize-control-description"><?php echo ideapark_wrap( $this->description ); ?></span>
					<?php endif; ?>

					<select
						id="<?php echo esc_attr( $input_id ); ?>" <?php echo ideapark_wrap( $describedby_attr ); ?> <?php $this->link(); ?>>
						<?php
						$is_option_group = false;
						foreach ( $this->choices as $value => $label ) {
							if ( strpos( $value, '*' ) === 0 ) {
								if ( $is_option_group ) {
									echo ideapark_wrap( '</optgroup>' );
								}
								echo ideapark_wrap( '<optgroup label="' . $label . '">' );
								$is_option_group = true;
							} else {
								echo ideapark_wrap( '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>' );
							}

						}
						if ( $is_option_group ) {
							echo ideapark_wrap( '</optgroup>' );
						}
						?>
					</select>
					<?php
				}
			}

			class WP_Customize_Hidden_Control extends WP_Customize_Control {
				public $type = 'hidden';

				public function render_content() {
					?>
					<input type="hidden" name="_customize-hidden-<?php echo esc_attr( $this->id ); ?>" value=""
						<?php
						$this->link();
						if ( ! empty( $this->input_attrs['var_name'] ) ) {
							echo ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"';
						}
						?>
					>
					<?php
					if ( 'last_option' == $this->id && ideapark_mod( 'need_footer_scripts' ) ) {
						ideapark_mod_set_temp( 'need_footer_scripts', false );
						do_action( 'admin_print_footer_scripts' );
					}
				}
			}

			class WP_Customize_Range_Control extends WP_Customize_Control {
				public $type = 'range';

				public function render_content() {
					$show_value = ! isset( $this->input_attrs['show_value'] ) || $this->input_attrs['show_value'];
					$output     = '';

					wp_enqueue_script( 'jquery-ui-slider', false, [ 'jquery', 'jquery-ui-core' ], null, true );
					$is_range   = 'range' == $this->input_attrs['type'];
					$field_min  = ! empty( $this->input_attrs['min'] ) ? $this->input_attrs['min'] : 0;
					$field_max  = ! empty( $this->input_attrs['max'] ) ? $this->input_attrs['max'] : 100;
					$field_step = ! empty( $this->input_attrs['step'] ) ? $this->input_attrs['step'] : 1;
					$field_val  = ! empty( $value )
						? ( $value . ( $is_range && strpos( $value, ',' ) === false ? ',' . $field_max : '' ) )
						: ( $is_range ? $field_min . ',' . $field_max : $field_min );
					$output     .= '<div id="' . esc_attr( '_customize-range-' . esc_attr( $this->id ) ) . '"'
					               . ' class="ideapark_range_slider"'
					               . ' data-range="' . esc_attr( $is_range ? 'true' : 'min' ) . '"'
					               . ' data-min="' . esc_attr( $field_min ) . '"'
					               . ' data-max="' . esc_attr( $field_max ) . '"'
					               . ' data-step="' . esc_attr( $field_step ) . '"'
					               . '>'
					               . '<span class="ideapark_range_slider_label ideapark_range_slider_label_min">'
					               . esc_html( $field_min )
					               . '</span>'
					               . '<span class="ideapark_range_slider_label ideapark_range_slider_label_max">'
					               . esc_html( $field_max )
					               . '</span>';
					$values     = explode( ',', $field_val );
					for ( $i = 0; $i < count( $values ); $i ++ ) {
						$output .= '<span class="ideapark_range_slider_label ideapark_range_slider_label_cur">'
						           . esc_html( $values[ $i ] )
						           . '</span>';
					}
					$output .= '</div>';

					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'<span class="customize-control-field-wrap">
							<input type="' . ( ! $show_value ? 'hidden' : 'text' ) . '"' . $this->get_link() .
						( $show_value ? ' class="ideapark_range_slider_value"' : '' ) .
						( ! empty( $this->input_attrs['var_name'] ) ? ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"' : '' ) . '" />' .
						$output . ' 
					</span></div>'
					);

				}
			}

			class WP_Customize_Checklist_Control extends WP_Customize_Control {
				public $type = 'checklist';

				public function render_content() {
					$output = '';
					$value  = $this->value();

					if ( ! empty( $this->input_attrs['sortable'] ) ) {
						wp_enqueue_script( 'jquery-ui-sortable', false, [
							'jquery',
							'jquery-ui-core'
						], null, true );
					}
					$output .= '<div class="ideapark_checklist ' . ( ! empty( $this->input_attrs['max-height'] ) ? 'ideapark_checklist_scroll' : '' ) . ' ideapark_checklist_' . esc_attr( ! empty( $this->input_attrs['dir'] ) ? $this->input_attrs['dir'] : 'vertical' )
					           . ( ! empty( $this->input_attrs['sortable'] ) ? ' ideapark_sortable' : '' )
					           . '"' . ( ! empty( $this->input_attrs['max-height'] ) ? ' style="max-height: ' . trim( esc_attr( $this->input_attrs['max-height'] ) ) . 'px"' : '' )
					           . ( ! empty( $this->input_attrs['add_ajax_action'] ) ? ' data-add-ajax-action="' . esc_attr( $this->input_attrs['add_ajax_action'] ) . '"' : '' )
					           . ( ! empty( $this->input_attrs['delete_ajax_action'] ) ? ' data-delete-ajax-action="' . esc_attr( $this->input_attrs['delete_ajax_action'] ) . '"' : '' )
					           . '>';
					if ( ! is_array( $value ) ) {
						if ( ! empty( $value ) ) {
							parse_str( str_replace( '|', '&', $value ), $value );
						} else {
							$value = [];
						}
					}

					if ( ! empty( $this->input_attrs['choices_add'] ) ) {
						$choices = array_filter( $this->input_attrs['choices_add'], function ( $key ) use ( $value ) {
							return isset( $value[ $key ] );
						}, ARRAY_FILTER_USE_KEY );

						$choices = ideapark_array_merge( $value, $choices );
					} else {
						if ( ! empty( $this->input_attrs['sortable'] ) && is_array( $value ) ) {
							$value = array_filter( $value, function ( $key ) {
								return array_key_exists( $key, $this->input_attrs['choices'] );
							}, ARRAY_FILTER_USE_KEY );

							$this->input_attrs['choices'] = ideapark_array_merge( $value, $this->input_attrs['choices'] );
						}
						$choices = $this->input_attrs['choices'];
					}

					foreach ( $choices as $k => $v ) {
						$output .= '<div class="ideapark_checklist_item_label'
						           . ( ! empty( $this->input_attrs['sortable'] ) ? ' ideapark_sortable_item' : '' )
						           . '"><label>'
						           . '<input type="checkbox" value="1" data-name="' . $k . '"'
						           . ( isset( $value[ $k ] ) && 1 == (int) $value[ $k ] ? ' checked="checked"' : '' )
						           . ' />'
						           . ( substr( $v, 0, 4 ) == 'http' ? '<img src="' . esc_url( $v ) . '">' : esc_html( preg_replace( '~^[ \-]+~u', '', $v ) ) )
						           . '</label>'
						           . ( ! empty( $this->input_attrs['choices_edit'][ $k ] ) ? '<button type="button" class="ideapark_checklist_item_edit" data-control="' . esc_attr( $this->input_attrs['choices_edit'][ $k ] ) . '"><span class="dashicons dashicons-admin-generic"></span></button>' : '' )
						           . ( ! empty( $this->input_attrs['choices_delete'] ) && in_array( $k, $this->input_attrs['choices_delete'] ) || ! empty( $this->input_attrs['choices_add'] ) ? '<button type="button" class="ideapark_checklist_item_delete" data-section="' . esc_attr( $k ) . '"><span class="dashicons dashicons-no-alt"></span></button>' : '' )
						           . '</div>';
					}
					$output .= '</div>';

					$output_add = '';

					if ( ! empty( $this->input_attrs['can_add_block'] ) ) {
						$output_add .= ideapark_wrap(
							ideapark_wrap( esc_html__( 'Please reload the page to see the settings of the new blocks', 'luchiana' ), '<span class="notification-message">', '<br><button type="button" data-id="' . esc_attr( $this->id ) . '" class="button-primary button ideapark-customizer-reload">' . esc_html__( 'Reload', 'luchiana' ) . '</button></span>' ),
							'<div class="notice notice-warning ideapark-notice ideapark_checklist_add_notice">',
							'</div>'
						);
						$output_add .= '<div class="ideapark_checklist_add_wrap">';
						$output_add .= esc_html__( 'Add new block', 'luchiana' );
						$output_add .= '<div class="ideapark_checklist_add_inline"><select class="ideapark_checklist_add_select">';
						$output_add .= '<option value="">' . esc_html__( '- select block -', 'luchiana' ) . '</option>';
						foreach ( $this->input_attrs['can_add_block'] as $section_id ) {
							$output_add .= '<option value="' . esc_attr( $section_id ) . '">' . $this->input_attrs['choices'][ $section_id ] . '</option>';
						}
						$output_add .= '</select><button class="button ideapark_checklist_add_button" type="button">' . esc_html__( 'Add', 'luchiana' ) . '</button></div>';
						$output_add .= '</div>';
					} elseif ( ! empty( $this->input_attrs['choices_add'] ) ) {
						$output_add      .= '<div class="ideapark_checklist_add_wrap">';
						$output_add      .= esc_html__( 'Add new', 'luchiana' );
						$output_add      .= '<div class="ideapark_checklist_add_inline"><select class="ideapark_checklist_add_select">';
						$output_add      .= '<option value="">' . esc_html__( '- select -', 'luchiana' ) . '</option>';
						$is_option_group = false;
						foreach ( $this->input_attrs['choices_add'] as $section_id => $section_name ) {
							if ( strpos( $section_id, '*' ) === 0 ) {
								if ( $is_option_group ) {
									$output_add .= '</optgroup>';
								}
								$output_add      .= '<optgroup label="' . $section_name . '">';
								$is_option_group = true;
							} else {
								$output_add .= '<option value="' . esc_attr( $section_id ) . '">' . $section_name . '</option>';
							}
						}
						if ( $is_option_group ) {
							$output_add .= '</optgroup>';
						}
						$output_add .= '</select><button class="button ideapark_checklist_add_button" type="button">' . esc_html__( 'Add', 'luchiana' ) . '</button></div>';
						$output_add .= '</div>';
					}


					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'<span class="customize-control-field-wrap">
							<input type="hidden" ' . $this->get_link() .
						( ! empty( $this->input_attrs['var_name'] ) ? ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"' : '' ) . ' />' .
						$output . '</span>' . $output_add . '</div>'
					);
				}
			}
		}

		$panel_priority = 1;

		$ideapark_customize_panels = [
			'header_and_menu_settings' => [
				'priority'    => 90,
				'title'       => __( 'Header & Menu Settings', 'luchiana' ),
				'description' => '',
			]
		];

		foreach ( $ideapark_customize_panels as $panel_name => $panel ) {
			$wp_customize->add_panel( $panel_name, [
				'capability'  => 'edit_theme_options',
				'title'       => ! empty( $panel['title'] ) ? $panel['title'] : '',
				'description' => ! empty( $panel['description'] ) ? $panel['description'] : '',
				'priority'    => isset( $panel['priority'] ) ? $panel['priority'] : $panel_priority ++,
			] );
		}

		foreach ( $ideapark_customize as $i_section => $section ) {
			if ( ! empty( $section['controls'] ) ) {

				$panel_name = ! empty( $section['panel'] ) ? $section['panel'] : '';

				if ( ! array_key_exists( 'section', $section ) ) {
					$wp_customize->add_section( $section_name = 'ideapark_section_' . ( ! empty( $section['section_id'] ) ? $section['section_id'] : $i_section ), [
						'panel'       => $panel_name,
						'title'       => ! empty( $section['title'] ) ? $section['title'] : '',
						'description' => ! empty( $section['description'] ) ? $section['description'] : '',
						'priority'    => isset( $section['priority'] ) ? $section['priority'] : 160 + $i_section,
					] );
				} else {
					$section_name = $section['section'];
				}

				$control_priority = 1;
				$control_ids      = [];
				$first_control    = '';
				foreach ( $section['controls'] as $control_name => $control ) {

					if ( ! empty( $control['type'] ) || ! empty( $control['class'] ) ) {

						if ( ! $first_control ) {
							$first_control = $control_name;
						}

						$a = [
							'transport' => isset( $control['transport'] ) ? $control['transport'] : ( ( isset( $section['refresh'] ) && ! isset( $control['refresh'] ) && true !== $section['refresh'] ) || ( isset( $control['refresh'] ) && true !== $control['refresh'] ) ? 'postMessage' : 'refresh' )
						];
						if ( isset( $control['default'] ) ) {
							if ( is_string( $control['default'] ) && ! empty( $control['type'] ) && $control['type'] == 'hidden' && function_exists( $control['default'] ) ) {
								$a['default'] = call_user_func( $control['default'] );
							} else {
								$a['default'] = $control['default'];
							}
						}
						if ( isset( $control['sanitize_callback'] ) ) {
							$a['sanitize_callback'] = $control['sanitize_callback'];
						} else {
							die( 'No sanitize_callback found!' . print_r( $control, true ) );
						}

						call_user_func( [ $wp_customize, 'add_setting' ], $control_name, $a );

						if ( ! IDEAPARK_IS_AJAX_HEARTBEAT ) {

							if ( ! empty( $control['choices'] ) && is_string( $control['choices'] ) ) {
								if ( function_exists( $control['choices'] ) ) {
									$control['choices'] = call_user_func( $control['choices'] );
								} else {
									$control['choices'] = [];
								}
							}

							if ( ! empty( $control['choices_add'] ) && is_string( $control['choices_add'] ) ) {
								if ( function_exists( $control['choices_add'] ) ) {
									$control['choices_add'] = call_user_func( $control['choices_add'] );
								} else {
									$control['choices_add'] = [];
								}
							}
						}

						if ( empty( $control['class'] ) ) {
							$wp_customize->add_control(
								new WP_Customize_Control(
									$wp_customize,
									$control_name,
									[
										'label'    => $control['label'],
										'section'  => $section_name,
										'settings' => ! empty( $control['settings'] ) ? $control['settings'] : $control_name,
										'type'     => $control['type'],
										'priority' => ! empty( $control['priority'] ) ? $control['priority'] : $control_priority + 1,
										'choices'  => ! empty( $control['choices'] ) ? $control['choices'] : null,
									]
								)
							);
						} else {

							if ( class_exists( $control['class'] ) ) {
								$wp_customize->add_control(
									new $control['class'](
										$wp_customize,
										$control_name,
										[
											'label'           => ! empty( $control['label'] ) ? $control['label'] : '',
											'section'         => $section_name,
											'settings'        => ! empty( $control['settings'] ) ? $control['settings'] : $control_name,
											'type'            => ! empty( $control['type'] ) ? $control['type'] : null,
											'priority'        => ! empty( $control['priority'] ) ? $control['priority'] : $control_priority + 1,
											'choices'         => ! empty( $control['choices'] ) ? $control['choices'] : null,
											'active_callback' => ! empty( $control['active_callback'] ) ? $control['active_callback'] : '',
											'input_attrs'     => array_merge(
												$control, [
													'value'    => ideapark_mod( $control_name ),
													'var_name' => ! empty( $control['customizer'] ) ? $control['customizer'] : '',
												]
											),
										]
									)
								);
							}
						}

						if ( ! empty( $control['description'] ) ) {
							$ideapark_customize_custom_css[ '#customize-control-' . $control_name . ( ! empty( $control['type'] ) && in_array( $control['type'], [
								'radio',
								'checkbox'
							] ) ? '' : ' .customize-control-title' ) ] = $control['description'];
						}

						$f = false;
						if ( isset( $control['refresh'] ) && is_string( $control['refresh'] )
						     &&
						     (
							     ( $is_auto_load = isset( $control['refresh_id'] ) && ideapark_customizer_check_template_part( $control['refresh_id'] ) )
							     ||
							     function_exists( $f = "ideapark_customizer_partial_refresh_" . ( isset( $control['refresh_id'] ) ? $control['refresh_id'] : $control_name ) )
						     )
						     && isset( $wp_customize->selective_refresh ) ) {
							$wp_customize->selective_refresh->add_partial(
								$control_name, [
									'selector'            => $control['refresh'],
									'settings'            => $control_name,
									'render_callback'     => $is_auto_load ? 'ideapark_customizer_load_template_part' : $f,
									'container_inclusive' => ! empty( $control['refresh_wrapper'] ),
								]
							);
						} elseif ( ! isset( $control['refresh'] ) ) {
							$control_ids[] = $control_name;
						}
					}
				}

				if ( isset( $section['refresh_id'] ) && isset( $section['refresh'] ) && is_string( $section['refresh'] )
				     &&
				     (
					     ( $is_auto_load = ideapark_customizer_check_template_part( $section['refresh_id'] ) )
					     ||
					     function_exists( "ideapark_customizer_partial_refresh_{$section['refresh_id']}" )
				     )
				     && isset( $wp_customize->selective_refresh ) ) {
					$wp_customize->selective_refresh->add_partial(
						$first_control /* first control from this section*/, [
							'selector'            => $section['refresh'],
							'settings'            => $control_ids,
							'render_callback'     => $is_auto_load ? 'ideapark_customizer_load_template_part' : "ideapark_customizer_partial_refresh_{$section['refresh_id']}",
							'container_inclusive' => ! empty( $section['refresh_wrapper'] ),
						]
					);
				}
			}
		}

		$sec = $wp_customize->get_section( 'static_front_page' );
		if ( is_object( $sec ) ) {
			$sec->priority = 87;
		}

		$sec = $wp_customize->get_panel( 'woocommerce' );
		if ( is_object( $sec ) ) {
			$sec->priority = 110;
		}

		if ( ideapark_woocommerce_on() ) {
			$wp_customize->remove_setting( 'woocommerce_catalog_columns' );
			$wp_customize->remove_control( 'woocommerce_catalog_columns' );
			$wp_customize->remove_setting( 'woocommerce_catalog_rows' );
			$wp_customize->remove_control( 'woocommerce_catalog_rows' );

			$wp_customize->remove_setting( 'woocommerce_thumbnail_cropping' );
			$wp_customize->remove_setting( 'woocommerce_thumbnail_cropping_custom_width' );
			$wp_customize->remove_setting( 'woocommerce_thumbnail_cropping_custom_height' );
			$wp_customize->remove_control( 'woocommerce_thumbnail_cropping' );

			$wp_customize->get_section( 'woocommerce_product_images' )->description = '';
		}
	}
}

if ( ! function_exists( 'ideapark_get_theme_dependencies' ) ) {
	function ideapark_get_theme_dependencies() {
		global $ideapark_customize;
		$result              = [
			'refresh_css'          => [],
			'dependency'           => [],
			'refresh_callback'     => [],
			'refresh_pre_callback' => []
		];
		$partial_refresh     = [];
		$css_refresh         = [];
		$css_refresh_control = [];
		foreach ( $ideapark_customize as $i_section => $section ) {
			$first_control_name = '';
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					if ( ! $first_control_name ) {
						$first_control_name = $control_name;
					}
					if ( ! empty( $control['refresh_css'] ) ) {
						$result['refresh_css'][] = $control_name;
					}
					if ( ! empty( $control['refresh'] ) && is_string( $control['refresh'] ) ) {
						$result['refresh'][ $control_name ] = $control['refresh'];
						$partial_refresh[]                  = trim( $control['refresh'] );
					} elseif ( ! empty( $control['refresh_css'] ) && is_string( $control['refresh_css'] ) ) {
						$result['refresh'][ $control_name ] = $control['refresh_css'];
					}

					if ( ! empty( $control['refresh_css'] ) && is_string( $control['refresh_css'] ) ) {
						$css_refresh[] = $selector = trim( $control['refresh_css'] );
						if ( ! array_key_exists( $selector, $css_refresh_control ) ) {
							$css_refresh_control[ $selector ] = $control_name;
						}
					}

					if ( ! empty( $control['refresh_callback'] ) && is_string( $control['refresh_callback'] ) ) {
						$result['refresh_callback'][ $control_name ] = $control['refresh_callback'];
					}

					if ( ! empty( $control['refresh_pre_callback'] ) && is_string( $control['refresh_pre_callback'] ) ) {
						$result['refresh_pre_callback'][ $control_name ] = $control['refresh_pre_callback'];
					}

					if ( ! empty( $control['dependency'] ) && is_array( $control['dependency'] ) ) {
						$result['dependency'][ $control_name ] = $control['dependency'];
					}
				}
			}

			if ( ! empty( $section['refresh'] ) && is_string( $section['refresh'] ) && $first_control_name ) {
				$result['refresh'][ $first_control_name ] = $section['refresh'];
				$partial_refresh[]                        = trim( $section['refresh'] );
			}

			if ( ! empty( $section['refresh_css'] ) && is_string( $section['refresh_css'] ) && $first_control_name ) {
				$css_refresh[] = $selector = trim( $section['refresh_css'] );
				if ( ! array_key_exists( $selector, $css_refresh_control ) ) {
					$css_refresh_control[ $selector ] = $first_control_name;
				}
			}

			if ( ! empty( $section['refresh_callback'] ) && is_string( $section['refresh_callback'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					$result['refresh_callback'][ $control_name ] = $section['refresh_callback'];
				}
			}

			if ( ! empty( $section['refresh_pre_callback'] ) && is_string( $section['refresh_pre_callback'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					$result['refresh_pre_callback'][ $control_name ] = $section['refresh_pre_callback'];
				}
			}
		}

		$refresh_only_css = array_diff( array_unique( $css_refresh ), array_unique( $partial_refresh ) );

		$result['refresh_only_css'] = [];
		foreach ( $refresh_only_css as $selector ) {
			$result['refresh_only_css'][ $selector ] = $css_refresh_control[ $selector ];
		}

		return $result;
	}
}

if ( ! function_exists( 'ideapark_customizer_check_template_part' ) ) {
	function ideapark_customizer_check_template_part( $template ) {
		return ideapark_is_file( IDEAPARK_DIR . '/templates/' . $template . '.php' ) || ideapark_is_file( IDEAPARK_DIR . '/' . $template . '.php' );
	}
}

if ( ! function_exists( 'ideapark_customizer_load_template_part' ) ) {
	function ideapark_customizer_load_template_part( $_control ) {
		global $ideapark_customize;
		$is_found = false;
		foreach ( $ideapark_customize as $i_section => $section ) {
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					$is_found = $control_name == $_control->id;
					if ( $is_found && ! empty( $control['refresh_id'] ) ) {
						ob_start();
						if ( ideapark_is_file( IDEAPARK_DIR . '/templates/' . $control['refresh_id'] . '.php' ) ) {
							ideapark_get_template_part( 'templates/' . $control['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
						}
						if ( ideapark_is_file( IDEAPARK_DIR . '/' . $control['refresh_id'] . '.php' ) ) {
							ideapark_get_template_part( $control['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
						}
						$output = ob_get_contents();
						ob_end_clean();

						return $output;
					}
					if ( $is_found ) {
						break;
					}
				}
			}
			if ( $is_found && ! empty( $section['refresh_id'] ) ) {
				ob_start();
				if ( ideapark_is_file( IDEAPARK_DIR . '/templates/' . $section['refresh_id'] . '.php' ) ) {
					ideapark_get_template_part( 'templates/' . $section['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
				}
				if ( ideapark_is_file( IDEAPARK_DIR . '/' . $section['refresh_id'] . '.php' ) ) {
					ideapark_get_template_part( $section['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
				}
				$output = ob_get_contents();
				ob_end_clean();

				return $output;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_customizer_get_template_part' ) ) {
	function ideapark_customizer_get_template_part( $template ) {
		ob_start();
		get_template_part( $template );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_top_menu' ) ) {
	function ideapark_customizer_partial_refresh_top_menu() {
		return ideapark_customizer_get_template_part( 'templates/home-top-menu' );
	}
}

if ( ! function_exists( 'ideapark_parse_checklist' ) ) {
	function ideapark_parse_checklist( $str ) {
		$values = [];
		if ( ! empty( $str ) ) {
			parse_str( str_replace( '|', '&', $str ), $values );
		}

		return $values;
	}
}

if ( ! function_exists( 'ideapark_sanitize_checkbox' ) ) {
	function ideapark_sanitize_checkbox( $input ) {
		if ( $input ):
			$output = true;
		else:
			$output = false;
		endif;

		return $output;
	}
}

if ( ! function_exists( 'ideapark_customize_admin_style' ) ) {
	function ideapark_customize_admin_style() {
		global $ideapark_customize_custom_css;
		if ( ! empty( $ideapark_customize_custom_css ) && is_array( $ideapark_customize_custom_css ) ) {
			echo '<style>';
			foreach ( $ideapark_customize_custom_css as $style_name => $text ) {
				echo esc_attr( $style_name ); ?>:after {content: "<?php echo esc_attr( $text ) ?>";}
			<?php }
			echo '</style>';
		}
	}
}

if ( ! function_exists( 'ideapark_customizer_preview_js' ) ) {
	add_action( 'customize_preview_init', 'ideapark_customizer_preview_js' );
	function ideapark_customizer_preview_js() {
		wp_enqueue_script(
			'ideapark-customizer-preview',
			IDEAPARK_URI . '/assets/js/admin-customizer-preview.js',
			[ 'customize-preview' ], null, true
		);
	}
}

if ( ! function_exists( 'ideapark_get_all_attributes' ) ) {
	function ideapark_get_all_attributes() {
		$attribute_array = [ '' => '' ];

		if ( ideapark_woocommerce_on() ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( $tax->attribute_public && taxonomy_exists( $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[ $taxonomy ] = $tax->attribute_name;
					}
				}
			}
		}

		return $attribute_array;
	}
}

if ( ! function_exists( 'ideapark_get_color_attributes' ) ) {
	function ideapark_get_color_attributes() {

		$attribute_array = [ '' => '' ];
		if ( ideapark_swatches_plugin_on() && ideapark_woocommerce_on() ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( taxonomy_exists( $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						if ( in_array( ideapark_get_taxonomy_type( $taxonomy ), [ 'color', 'image' ] ) ) {
							$attribute_array[ $taxonomy ] = $tax->attribute_name;
						}
					}
				}
			}

			return $attribute_array;
		}
	}
}

if ( ! function_exists( 'ideapark_get_font_choices' ) ) {
	function ideapark_get_font_choices() {
		static $choices;
		if ( $choices !== null ) {
			return $choices;
		}
		$fonts   = &ideapark_get_google_fonts();
		$choices = [];

		if ( $custom_fonts = get_theme_mod( 'custom_fonts' ) ) {
			foreach ( $custom_fonts as $custom_font ) {
				if ( ! empty( $custom_font['name'] ) ) {
					$choices[ 'custom-' . $custom_font['name'] ] = __( 'Custom Font:', 'luchiana' ) . ' ' . $custom_font['name'];
				}
			}
		}

		foreach (
			[
				'Arial',
				'Tahoma',
				'Verdana',
				'Helvetica',
				'Times New Roman',
				'Trebuchet MS',
				'Georgia'
			] as $system_font
		) {
			$choices[ 'system-' . $system_font ] = __( 'System Font:', 'luchiana' ) . ' ' . $system_font;
		}

		// Repackage the fonts into value/label pairs
		foreach ( $fonts as $key => $font ) {
			$choices[ $key ] = $font['label'];
		}

		return $choices;
	}
}

if ( ! function_exists( 'ideapark_get_lang_postfix' ) ) {
	function ideapark_get_lang_postfix() {
		$lang_postfix = '';
		if ( $languages = ideapark_active_languages() ) {
			if ( ideapark_current_language() != ideapark_default_language() ) {
				$lang_postfix = '_' . ideapark_current_language();
			}
		}

		return $lang_postfix;
	}
}

if ( ! function_exists( 'ideapark_get_google_font_uri' ) ) {
	function ideapark_get_google_font_uri( $fonts ) {

		if ( ! $fonts || ! is_array( $fonts ) ) {
			return '';
		}
		$fonts = array_filter( array_unique( array_filter( $fonts, function ( $item ) {
			return ! preg_match( '~^(custom-|system-)~', $item ?: '' );
		} ) ) );
		if ( ! $fonts ) {
			return '';
		}
		$hash = md5( implode( ',', $fonts ) . '--' . IDEAPARK_VERSION );

		$lang_postfix = ideapark_get_lang_postfix();

		if ( ( $data = get_option( 'ideapark_google_font_uri' . $lang_postfix ) ) && ! empty( $data['version'] ) && ! empty( $data['uri'] ) ) {
			if ( $data['version'] == $hash ) {
				return $data['uri'];
			} else {
				delete_option( 'ideapark_google_font_uri' . $lang_postfix );
			}
		}

		$allowed_fonts = &ideapark_get_google_fonts();
		$family        = [];

		foreach ( $fonts as $font ) {
			$font = trim( $font ?: '' );

			if ( array_key_exists( $font, $allowed_fonts ) ) {
				$filter   = [ '200', 'regular', 'italic', '500', '600', '700', '900' ];
				$family[] = urlencode( $font . ':' . join( ',', ideapark_choose_google_font_variants( $font, $allowed_fonts[ $font ]['variants'], $filter ) ) );
			}
		}

		if ( empty( $family ) ) {
			return '';
		} else {
			$request = '//fonts.googleapis.com/css?family=' . implode( rawurlencode( '|' ), $family );
		}

		$subset = ideapark_mod( 'theme_font_subsets' . $lang_postfix );

		if ( 'all' === $subset ) {
			$subsets_available = ideapark_get_google_font_subsets();

			unset( $subsets_available['all'] );

			$subsets = array_keys( $subsets_available );
		} else {
			$subsets = [
				'latin',
				$subset,
			];
		}

		if ( ! empty( $subsets ) ) {
			$request .= urlencode( '&subset=' . join( ',', $subsets ) );
		}

		if ( ideapark_mod( 'google_fonts_display_swap' ) ) {
			$request .= '&display=swap';
		}

		add_option( 'ideapark_google_font_uri' . $lang_postfix, [
			'version' => $hash,
			'uri'     => esc_url( $request )
		], '', 'yes' );

		return esc_url( $request );
	}
}

if ( ! function_exists( 'ideapark_get_google_font_subsets' ) ) {
	function ideapark_get_google_font_subsets() {
		global $_ideapark_google_fonts_subsets;

		$list = [
			'all' => esc_html__( 'All', 'luchiana' ),
		];

		foreach ( $_ideapark_google_fonts_subsets as $subset ) {
			$name = ucfirst( trim( $subset ) );
			if ( preg_match( '~-ext$~', $name ) ) {
				$name = preg_replace( '~-ext$~', ' ' . esc_html__( 'Extended', 'luchiana' ), $name );
			}
			$list[ $subset ] = esc_html( $name );
		}

		return $list;
	}
}

if ( ! function_exists( 'ideapark_choose_google_font_variants' ) ) {
	function ideapark_choose_google_font_variants( $font, $variants = [], $filter = [ 'regular', '700' ] ) {
		$chosen_variants = [];
		if ( empty( $variants ) ) {
			$fonts = &ideapark_get_google_fonts();

			if ( array_key_exists( $font, $fonts ) ) {
				$variants = $fonts[ $font ]['variants'];
			}
		}

		foreach ( $filter as $var ) {
			if ( in_array( $var, $variants ) && ! array_key_exists( $var, $chosen_variants ) ) {
				$chosen_variants[] = $var;
			}
		}

		if ( empty( $chosen_variants ) ) {
			$variants[0];
		}

		return apply_filters( 'ideapark_font_variants', array_unique( $chosen_variants ), $font, $variants );
	}
}

if ( ! function_exists( 'ideapark_sanitize_font_choice' ) ) {
	function ideapark_sanitize_font_choice( $value ) {
		if ( is_int( $value ) ) {
			// The array key is an integer, so the chosen option is a heading, not a real choice
			return '';
		} else if ( array_key_exists( $value, ideapark_get_font_choices() ) ) {
			return $value;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_customizer_banners' ) ) {
	function ideapark_customizer_banners() {
		$result = [];
		if ( $banners = get_posts( [
			'posts_per_page'   => - 1,
			'post_type'        => 'banner',
			'meta_key'         => '_thumbnail_id',
			'suppress_filters' => false,
			'order'            => 'ASC',
			'orderby'          => 'menu_order'
		] ) ) {
			foreach ( $banners as $banner ) {
				$attachment_id = get_post_thumbnail_id( $banner->ID );
				$image         = wp_get_attachment_image_url( $attachment_id );
				if ( $image ) {
					$result[ $banner->ID ] = $image;
				} elseif ( ! empty( $banner->post_title ) ) {
					$result[ $banner->ID ] = $banner->post_title;
				} elseif ( $image_alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) ) {
					$result[ $banner->ID ] = $image_alt;
				} else {
					$result[ $banner->ID ] = '#' . $banner->ID;
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'ideapark_customizer_product_tab_list' ) ) {
	function ideapark_customizer_product_tab_list() {
		$list = [
			'*main'                 => esc_html__( 'Main', 'luchiana' ),
			'featured_products'     => esc_html__( 'Featured Products', 'luchiana' ),
			'sale_products'         => esc_html__( 'Sale Products', 'luchiana' ),
			'best_selling_products' => esc_html__( 'Best-Selling Products', 'luchiana' ),
			'recent_products'       => esc_html__( 'Recent Products', 'luchiana' ),
			'*categories'           => esc_html__( 'Categories', 'luchiana' ),
		];

		$args = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'term_group',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
			'exclude'      => ideapark_hidden_category_ids() ?: null,
		];
		if ( $all_categories = get_categories( $args ) ) {

			$category_name   = [];
			$category_parent = [];
			foreach ( $all_categories as $cat ) {
				$category_name[ $cat->term_id ]    = esc_html( $cat->name );
				$category_parent[ $cat->parent ][] = $cat->term_id;
			}

			$get_category = function ( $parent = 0, $prefix = '' ) use ( &$list, &$category_parent, &$category_name, &$get_category ) {
				if ( array_key_exists( $parent, $category_parent ) ) {
					$categories = $category_parent[ $parent ];
					foreach ( $categories as $category_id ) {
						$list[ $category_id ] = $prefix . $category_name[ $category_id ];
						$get_category( $category_id, $prefix . ' - ' );
					}
				}
			};

			$get_category();
		}

		return $list;
	}
}

if ( ! function_exists( 'ideapark_add_last_control' ) ) {
	function ideapark_add_last_control() {
		global $ideapark_customize;

		$ideapark_customize[ sizeof( $ideapark_customize ) - 1 ]['controls']['last_option'] = [
			'label'             => '',
			'description'       => '',
			'type'              => 'hidden',
			'default'           => '',
			'sanitize_callback' => 'ideapark_sanitize_checkbox',
			'class'             => 'WP_Customize_Hidden_Control',
		];
	}
}

$_ideapark_google_fonts_cache   = false;
$_ideapark_google_fonts_subsets = [];

if ( ! function_exists( 'ideapark_get_google_fonts' ) ) {
	function & ideapark_get_google_fonts() {
		global $_ideapark_google_fonts_cache, $_ideapark_google_fonts_subsets;

		if ( $_ideapark_google_fonts_cache ) {
			return $_ideapark_google_fonts_cache;
		}

		if ( ideapark_is_file( $fn = IDEAPARK_UPLOAD_DIR . 'google_fonts_cache.php' ) ) {
			try {
				include( $fn );
			} catch ( \ParseError $e ) {
				unlink( $fn );
				$_ideapark_google_fonts_cache = [];
			} catch ( \Throwable $e ) {
				unlink( $fn );
				$_ideapark_google_fonts_cache = [];
			}
			if ( ! empty( $_ideapark_google_fonts_ver ) && $_ideapark_google_fonts_ver == IDEAPARK_VERSION && $_ideapark_google_fonts_cache ) {
				return $_ideapark_google_fonts_cache;
			}
		}

		if ( ( $data = get_option( 'ideapark_google_fonts' ) ) && ! empty( $data['version'] ) && ! empty( $data['list'] ) && ! empty( $data['subsets'] ) ) {
			if ( $data['version'] == IDEAPARK_VERSION ) {
				$_ideapark_google_fonts_cache   = $data['list'];
				$_ideapark_google_fonts_subsets = $data['subsets'];

				return $_ideapark_google_fonts_cache;
			} else {
				ideapark_delete_file( IDEAPARK_UPLOAD_DIR . 'google_fonts_cache.php' );
				delete_option( 'ideapark_google_fonts' );
			}
		}

		$decoded_google_fonts = json_decode( ideapark_fgc( IDEAPARK_DIR . '/includes/customize/webfonts.json' ), true );
		$webfonts             = [];
		foreach ( $decoded_google_fonts['items'] as $key => $value ) {
			$font_family                          = $decoded_google_fonts['items'][ $key ]['family'];
			$webfonts[ $font_family ]             = [];
			$webfonts[ $font_family ]['label']    = $font_family;
			$webfonts[ $font_family ]['variants'] = $decoded_google_fonts['items'][ $key ]['variants'];
			$webfonts[ $font_family ]['subsets']  = $decoded_google_fonts['items'][ $key ]['subsets'];
			$_ideapark_google_fonts_subsets       = array_unique( array_merge( $_ideapark_google_fonts_subsets, $decoded_google_fonts['items'][ $key ]['subsets'] ) );
		}

		sort( $_ideapark_google_fonts_subsets );
		$_ideapark_google_fonts_cache = apply_filters( 'ideapark_get_google_fonts', $webfonts );

		$code = "<?php\n";
		$code .= '$_ideapark_google_fonts_ver = "' . IDEAPARK_VERSION . '";' . "\n";
		foreach ( [ '_ideapark_google_fonts_cache', '_ideapark_google_fonts_subsets' ] as $var_name ) {
			$code .= 'global $' . $var_name . ";\n";
			$code .= '$' . $var_name . ' = ' . ideapark_array_to_php_code( $$var_name ) . ";\n\n";
		}
		ideapark_fpc( $fn = IDEAPARK_UPLOAD_DIR . 'google_fonts_cache.php', $code );
		if ( ! ideapark_is_file( $fn ) ) {
			update_option( 'ideapark_google_fonts', [
				'version' => IDEAPARK_VERSION,
				'list'    => $_ideapark_google_fonts_cache,
				'subsets' => $_ideapark_google_fonts_subsets
			], '', 'no' );
		}

		return $_ideapark_google_fonts_cache;
	}
}

if ( ! function_exists( 'ideapark_save_customize_colors' ) ) {
	function ideapark_save_customize_colors( $_this ) {
		$changeset_setting_values          = $_this->unsanitized_post_values(
			[
				'exclude_post_data' => true,
				'exclude_changeset' => false,
			]
		);
		$need_to_change_elementor_settings = false;
		foreach ( $changeset_setting_values as $setting_id => $value ) {
			if ( preg_match( '~color~', $setting_id ) ) {
				$need_to_change_elementor_settings = true;
				ideapark_mod_set_temp( $setting_id, $value );
			}
		}

		if ( $need_to_change_elementor_settings ) {
			ideapark_set_theme_elementor_settings();
		}
	}
}

if ( ! function_exists( 'ideapark_save_product_features' ) ) {
	function ideapark_save_product_features( $_this ) {
		$changeset_setting_values = $_this->unsanitized_post_values(
			[
				'exclude_post_data' => true,
				'exclude_changeset' => false,
			]
		);
		foreach ( $changeset_setting_values as $setting_id => $value ) {
			if ( strpos( $value, 'product_feature' ) !== false ) {
				if ( ( $features = get_theme_mod( 'product_feature' ) ) && is_array( $features ) && ( ! empty( $features[0]['name'] ) || ! empty( $features[0]['description'] ) ) ) {
					foreach ( $features as $feature ) {
						if ( ! empty( $feature['name'] ) ) {
							do_action( 'wpml_register_single_string', IDEAPARK_SLUG, 'Product Feature Title - ' . $feature['name'], $feature['name'] );
						}

						if ( ! empty( $feature['description'] ) ) {
							do_action( 'wpml_register_single_string', IDEAPARK_SLUG, 'Product Feature Description - ' . $feature['description'], $feature['description'] );
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'ideapark_after_customizer_save' ) ) {
	function ideapark_after_customizer_save() {
		global $ideapark_customize;
		if ( ! empty( $ideapark_customize ) ) {
			foreach ( $ideapark_customize as $section ) {
				if ( ! empty( $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( isset( $control['class'] ) && $control['class'] == 'WP_Customize_Image_Control' ) {
							if ( ( $url = get_theme_mod( $control_name ) ) && ( $attachment_id = attachment_url_to_postid( $url ) ) ) {
								$params = wp_get_attachment_image_src( $attachment_id, 'full' );
								set_theme_mod( $control_name . '__url', $params[0] );
								set_theme_mod( $control_name . '__attachment_id', $attachment_id );
								set_theme_mod( $control_name . '__width', $params[1] );
								set_theme_mod( $control_name . '__height', $params[2] );
							} else {
								remove_theme_mod( $control_name . '__url' );
								remove_theme_mod( $control_name . '__attachment_id' );
								remove_theme_mod( $control_name . '__width' );
								remove_theme_mod( $control_name . '__height' );
							}
						}
						if ( ! empty( $control['is_option'] ) ) {
							$val = get_theme_mod( $control_name, null );
							if ( $val === null && isset( $control['default'] ) ) {
								$val = $control['default'];
							}
							if ( $val !== null ) {
								update_option( IDEAPARK_SLUG . '_mod_' . $control_name, $val );
							} else {
								delete_option( IDEAPARK_SLUG . '_mod_' . $control_name );
							}
						}
					}
				}
			}
		}

		if ( ( $fonts = get_theme_mod( 'custom_fonts' ) ) && is_array( $fonts ) ) {
			$fonts = array_filter( $fonts, function ( $item ) {
				return ! empty( $item['name'] );
			} );
			set_theme_mod( 'custom_fonts', $fonts );
		}

		delete_option( 'ideapark_google_font_uri' );
		if ( $languages = ideapark_active_languages() ) {
			foreach ( $languages as $lang_code => $lang ) {
				delete_option( 'ideapark_google_font_uri' . '_' . $lang_code );
				delete_option( 'ideapark_styles_hash' . '_' . $lang_code );
			}
		}
		delete_option( 'ideapark_styles_hash' );
		delete_option( 'ideapark_editor_styles_hash' );

		if ( IDEAPARK_DEMO ) {
			ideapark_fpc( IDEAPARK_UPLOAD_DIR . 'customizer_var.css', ideapark_customize_css( true ) );
		}
	}
}

if ( ! function_exists( 'ideapark_clear_customize_cache' ) ) {
	function ideapark_clear_customize_cache() {
		ideapark_after_customizer_save();
		ideapark_delete_file( IDEAPARK_UPLOAD_DIR . 'customizer_vars.php' );
		ideapark_delete_file( IDEAPARK_UPLOAD_DIR . 'google_fonts_cache.php' );
		delete_option( 'ideapark_customize' );
		delete_option( 'ideapark_google_fonts' );
		ideapark_init_theme_customize();
		ideapark_editor_style();
	}
}

if ( ! function_exists( 'ideapark_mod_hex_color_norm' ) ) {
	function ideapark_mod_hex_color_norm( $option, $default = 'inherit' ) {
		if ( preg_match( '~^\#[0-9A-F]{3,6}$~i', $option ) ) {
			return $option;
		} elseif ( preg_match( '~^\#[0-9A-F]{3,6}$~i', $color = '#' . ltrim( ideapark_mod( $option ) ?: '', '#' ) ) ) {
			return $color;
		} else {
			return $default;
		}
	}
}

if ( ! function_exists( 'ideapark_hex_to_rgb_overlay' ) ) {
	function ideapark_hex_to_rgb_overlay( $hex_color_1, $hex_color_2, $alpha_2 ) {
		list( $r_1, $g_1, $b_1 ) = sscanf( $hex_color_1, "#%02x%02x%02x" );
		list( $r_2, $g_2, $b_2 ) = sscanf( $hex_color_2, "#%02x%02x%02x" );

		$r = min( round( $alpha_2 * $r_2 + ( 1 - $alpha_2 ) * $r_1 ), 255 );
		$g = min( round( $alpha_2 * $g_2 + ( 1 - $alpha_2 ) * $g_1 ), 255 );
		$b = min( round( $alpha_2 * $b_2 + ( 1 - $alpha_2 ) * $b_1 ), 255 );

		return "rgb($r, $g, $b)";
	}
}

if ( ! function_exists( 'ideapark_hex_lighting' ) ) {
	function ideapark_hex_lighting( $hex_color_1 ) {
		list( $r_1, $g_1, $b_1 ) = sscanf( $hex_color_1, "#%02x%02x%02x" );

		return 0.299 * $r_1 + 0.587 * $g_1 + 0.114 * $b_1;
	}
}

if ( ! function_exists( 'ideapark_hex_to_rgb_shift' ) ) {
	function ideapark_hex_to_rgb_shift( $hex_color, $k = 1 ) {
		list( $r, $g, $b ) = sscanf( $hex_color, "#%02x%02x%02x" );

		$r = min( round( $r * $k ), 255 );
		$g = min( round( $g * $k ), 255 );
		$b = min( round( $b * $k ), 255 );

		return "rgb($r, $g, $b)";
	}
}

if ( ! function_exists( 'ideapark_hex_to_rgba' ) ) {
	function ideapark_hex_to_rgba( $hex_color, $opacity = 1 ) {
		list( $r, $g, $b ) = sscanf( $hex_color, "#%02x%02x%02x" );

		return "rgba($r, $g, $b, $opacity)";
	}
}

if ( ! function_exists( 'ideapark_set_theme_elementor_settings' ) ) {
	function ideapark_set_theme_elementor_settings() {
		ideapark_ra( 'elementor/core/files/clear_cache', 'ideapark_set_theme_elementor_settings', 2 );
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		if ( ideapark_is_elementor() && ( $kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit() ) ) {
			if ( $kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id() ) {
				$kit_settings                                    = $kit->get_settings();
				$kit_settings['container_width_tablet']['size']  = $kit_settings['viewport_tablet'] = 1189;
				$kit_settings['container_width']['size']         = 1160;
				$kit_settings['space_between_widgets']['size']   = 0;
				$kit_settings['space_between_widgets']['column'] = 0;
				$kit_settings['space_between_widgets']['row']    = 0;

				if ( ! empty( $kit_settings['system_colors'] ) ) {
					/**
					 * @var $text_color            string
					 * @var $text_color_light      string
					 * @var $background_color      string
					 * @var $accent_color          string
					 */
					extract( ideapark_theme_colors() );
					foreach ( $kit_settings['system_colors'] as $index => $color ) {
						switch ( $color['_id'] ) {
							case 'primary':
								$kit_settings['system_colors'][ $index ]['color'] = $text_color;
								$kit_settings['system_colors'][ $index ]['title'] = esc_html__( 'Headers', 'luchiana' );
								break;
							case 'secondary':
								$kit_settings['system_colors'][ $index ]['color'] = $background_color;
								$kit_settings['system_colors'][ $index ]['title'] = esc_html__( 'Background', 'luchiana' );
								break;
							case 'text':
								$kit_settings['system_colors'][ $index ]['color'] = $text_color_light;
								break;
							case 'accent':
								$kit_settings['system_colors'][ $index ]['color'] = $accent_color;
								break;
						}
					}
				}

				$page_settings_manager = Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
				$page_settings_manager->save_settings( $kit_settings, $kit_id );

				$elementor_instance = Elementor\Plugin::instance();
				$elementor_instance->files_manager->clear_cache();
			}
		}
	}

	add_action( 'after_switch_theme', 'ideapark_set_theme_elementor_settings', 2 );
	add_action( 'after_update_theme_late', 'ideapark_set_theme_elementor_settings', 2 );
	add_action( 'elementor/core/files/clear_cache', 'ideapark_set_theme_elementor_settings', 2 );
}

if ( ! function_exists( 'ideapark_mce4_options' ) ) {
	function ideapark_mce4_options( $init ) {

		/**
		 * @var $text_color            string
		 * @var $text_color_light      string
		 * @var $background_color      string
		 * @var $accent_color          string
		 */
		extract( ideapark_theme_colors() );

		$default_colours = '
			"000000", "Black",
			"993300", "Burnt orange",
			"333300", "Dark olive",
			"003300", "Dark green",
			"003366", "Dark azure",
			"000080", "Navy Blue",
			"333399", "Indigo",
			"333333", "Very dark gray",
			"800000", "Maroon",
			"FF6600", "Orange",
			"808000", "Olive",
			"008000", "Green",
			"008080", "Teal",
			"0000FF", "Blue",
			"666699", "Grayish blue",
			"808080", "Gray",
			"FF0000", "Red",
			"FF9900", "Amber",
			"99CC00", "Yellow green",
			"339966", "Sea green",
			"33CCCC", "Turquoise",
			"3366FF", "Royal blue",
			"800080", "Purple",
			"999999", "Medium gray",
			"FF00FF", "Magenta",
			"FFCC00", "Gold",
			"FFFF00", "Yellow",
			"00FF00", "Lime",
			"00FFFF", "Aqua",
			"00CCFF", "Sky blue",
			"993366", "Brown",
			"C0C0C0", "Silver",
			"FF99CC", "Pink",
			"FFCC99", "Peach",
			"FFFF99", "Light yellow",
			"CCFFCC", "Pale green",
			"CCFFFF", "Pale cyan",
			"99CCFF", "Light sky blue",
			"CC99FF", "Plum",
			"FFFFFF", "White"
		';

		$custom_colours = "
			\"$text_color_light\", \"Text color\",
			\"$text_color\", \"Header color\",
			\"$accent_color\", \"Accent color\",
			\"$background_color\", \"Background color\"
		";

		$init['textcolor_map'] = '[' . $default_colours . ', ' . $custom_colours . ']';

		$init['textcolor_rows'] = 6;

		return $init;
	}
}

if ( ! function_exists( 'ideapark_is_shop_configured' ) ) {
	function ideapark_is_shop_configured() {
		return ideapark_woocommerce_on() && wc_get_page_id( 'shop' ) > 0 ? 1 : 0;
	}
}

if ( ! function_exists( 'ideapark_customizer_social_links' ) ) {
	function ideapark_customizer_social_links() {
		$ret = [];
		foreach ( ideapark_social_networks() as $code => $name ) {
			$ret[ $code ] = [
				'label'             => sprintf( __( '%s url', 'luchiana' ), $name ),
				'type'              => 'text',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			];
		}

		for ( $i = 1; $i <= max( 1, (int) apply_filters( 'ideapark_custom_soc_count', 2 ) ); $i ++ ) {
			$ret[ 'custom_soc_icon_' . $i ] = [
				'label'             => __( 'Custom icon', 'luchiana' ) . ' #' . $i,
				'class'             => 'WP_Customize_Font_Icons_Control',
				'sanitize_callback' => 'sanitize_text_field',
				'dependency'        => [
					'is_font_icons_loader_on' => [ 'not_empty' ],
				],
			];

			$ret[ 'custom_soc_url_' . $i ] = [
				'label'             => __( 'Custom url', 'luchiana' ) . ' #' . $i,
				'type'              => 'text',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			];
		}

		return $ret;
	}
}

if ( ! function_exists( 'ideapark_customize_scripts' ) ) {
	function ideapark_customize_scripts() {
		$assets_url    = IDEAPARK_URI . '/includes/megamenu/assets/';
		$script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'select2', esc_url( $assets_url ) . 'css/select2.min.css', false, '4.1.0-beta.1', 'all' );
		wp_register_script( 'select2', esc_url( $assets_url ) . 'js/select2.full' . $script_suffix . '.js', [ 'jquery' ], '4.1.0-beta.1', true );
		wp_enqueue_style( 'select2' );
		wp_enqueue_script( 'select2' );

		if ( class_exists( 'Ideapark_Fonts' ) ) {
			$instance = Ideapark_Fonts::instance( __FILE__ );
			$instance->admin_enqueue_styles();
		}

		wp_enqueue_script( 'ideapark-lib', IDEAPARK_URI . '/assets/js/site-lib.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_DIR . '/assets/js/site-lib.js' ), true );
		wp_enqueue_script( 'ideapark-admin-customizer', IDEAPARK_URI . '/assets/js/admin-customizer.js', [
			'jquery',
			'customize-controls',
			'ideapark-lib'
		], ideapark_mtime( IDEAPARK_DIR . '/assets/js/admin-customizer.js' ), true );
		wp_localize_script( 'ideapark-admin-customizer', 'ideapark_dependencies', ideapark_get_theme_dependencies() );

		$post_url        = ( $latest = get_posts( [ 'numberposts' => 1 ] ) ) && ! empty( $latest[0]->ID ) ? get_permalink( $latest[0]->ID ) : '';
		$blog_url        = ( $page_for_posts = get_option( 'page_for_posts' ) ) ? get_permalink( $page_for_posts ) : get_home_url();
		$product_url     = '';
		$shop_url        = '';
		$product_cat_url = '';

		if ( ideapark_woocommerce_on() ) {
			$args = [ 'numberposts' => 1, 'post_type' => 'product', 'orderby' => 'ID' ];
			if ( ( $exclude_catalog_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' ) ) && ! is_wp_error( $exclude_catalog_term ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'product_visibility',
						'terms'    => [ $exclude_catalog_term->term_id ],
						'operator' => 'NOT IN'
					]
				];
			}
			$posts = get_posts( $args );
			if ( $posts && ! is_wp_error( $posts ) ) {
				$product_url = get_permalink( $posts[0]->ID );
			}

			$shop_url = wc_get_page_id( 'shop' ) > 0 ? wc_get_page_permalink( 'shop' ) : '';

			$terms = get_terms( 'product_cat', [ 'hide_empty' => true, 'number' => 1 ] );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$product_cat_url = get_term_link( array_shift( $terms )->term_id, 'product_cat' );
			}
		}

		wp_localize_script( 'ideapark-admin-customizer', 'ideapark_ac_vars', [
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'shopUrl'       => $shop_url,
			'productUrl'    => $product_url,
			'productCatUrl' => $product_cat_url,
			'blogUrl'       => $blog_url,
			'postUrl'       => $post_url,
			'errorText'     => esc_html__( 'Something went wrong...', 'luchiana' ),
		] );
	}
}

if ( ! function_exists( 'ideapark_active_languages' ) ) {
	function ideapark_active_languages() {
		static $cache;
		if ( $cache !== null ) {
			return $cache;
		}
		$GLOBALS['ideapark_locale_to_code'] = [];
		if ( function_exists( 'pll_the_languages' ) && ( $polylang_languages = pll_the_languages( [ 'raw' => 1 ] ) ) ) {
			$languages = [];
			foreach ( $polylang_languages as $code => &$lang ) {
				$languages[ $locale = str_replace( '-', '_', $lang['locale'] ) ] = $lang['name'];

				$GLOBALS['ideapark_locale_to_code'][ $locale ] = $code;
			}
			$cache = $languages;

			return $languages;
		} elseif ( ( $wpml_languages = apply_filters( 'wpml_active_languages', [] ) ) && sizeof( $wpml_languages ) >= 2 ) {
			$languages = [];
			foreach ( $wpml_languages as $code => &$lang ) {
				if ( ! empty( $lang['default_locale'] ) ) {
					$languages[ $locale = $lang['default_locale'] ] = $lang['native_name'];

					$GLOBALS['ideapark_locale_to_code'][ $locale ] = $code;
				}
			}
			$cache = $languages;

			return $languages;
		} elseif ( class_exists( 'TRP_Translate_Press' ) ) {
			$trp           = TRP_Translate_Press::get_trp_instance();
			$settings      = $trp->get_component( 'settings' )->get_settings();
			$trp_languages = $trp->get_component( 'languages' );
			if ( ! empty( $settings['translation-languages'] ) && sizeof( $settings['translation-languages'] ) >= 2 ) {
				$published_languages = $trp_languages->get_languages();
				$languages           = [];
				foreach ( $settings['translation-languages'] as $lang_code ) {
					$languages[ $lang_code ] = array_key_exists( $lang_code, $published_languages ) ? $published_languages[ $lang_code ] : $lang_code;
				}

				$cache = $languages;

				return $languages;
			}
		}

		$cache = false;

		return false;
	}
}

if ( ! function_exists( 'ideapark_default_language' ) ) {
	function ideapark_default_language() {
		if ( function_exists( 'pll_default_language' ) && ( $default_lang = pll_default_language( 'locale' ) ) ) {
			return $default_lang;
		} elseif ( $default_lang = apply_filters( 'wpml_default_language', null ) ) {
			if ( ( $wpml_languages = apply_filters( 'wpml_active_languages', [] ) ) && array_key_exists( $default_lang, $wpml_languages ) ) {
				return $wpml_languages[ $default_lang ]['default_locale'];
			}
		}

		if ( class_exists( 'TRP_Translate_Press' ) ) {
			$trp      = TRP_Translate_Press::get_trp_instance();
			$settings = $trp->get_component( 'settings' )->get_settings();

			if ( ! empty( $settings['default-language'] ) ) {
				return $settings['default-language'];
			}
		}

		return null;
	}
}

if ( ! function_exists( 'ideapark_current_language' ) ) {
	function ideapark_current_language() {
		return get_locale();
	}
}

if ( ! function_exists( 'ideapark_query_lang' ) ) {
	function ideapark_query_lang() {
		$lang = '';
		if ( ! empty( $_REQUEST['lang'] ) ) {
			ideapark_active_languages();
			$lang = $_REQUEST['lang'];
			if ( isset( $GLOBALS['ideapark_locale_to_code'] ) && is_array( $GLOBALS['ideapark_locale_to_code'] ) && array_key_exists( $lang, $GLOBALS['ideapark_locale_to_code'] ) ) {
				$lang = $GLOBALS['ideapark_locale_to_code'][ $lang ];
			}

			if ( $lang == ideapark_current_language() ) {
				$lang = '';
			}
		}

		return $lang;
	}
}

if ( ! function_exists( 'ideapark_parse_added_blocks' ) ) {
	function ideapark_parse_added_blocks() {
		global $ideapark_customize;
		if ( $added_blocks = get_option( 'ideapark_added_blocks' ) ) {
			foreach ( $ideapark_customize as $section_index => $section ) {
				if ( ! empty( $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( ! empty( $section['panel'] ) && ! empty( $control['can_add_block'] ) && ! empty( $control['type'] ) && $control['type'] == 'checklist' && array_key_exists( $section['panel'], $added_blocks ) ) {
							foreach ( $added_blocks[ $section['panel'] ] as $item ) {
								$section_orig_id   = $item['section_id'];
								$index             = $item['index'];
								$checklist_control = &$ideapark_customize[ $section_index ]['controls'][ $control_name ];

								foreach ( $ideapark_customize as $_section ) {
									if ( ! empty( $_section['section_id'] ) && $_section['section_id'] == $section_orig_id ) {
										$section_new               = $_section;
										$section_new['section_id'] .= '-' . $index;
										$section_new['title']      .= ' - ' . $index;
										if ( ! empty( $section_new['refresh'] ) ) {
											$section_new['refresh'] .= '-' . $index;
										}
										$new_controls = [];
										if ( ! empty( $section_new['controls'] ) ) {
											foreach ( $section_new['controls'] as $_control_name => $_control ) {
												if ( ! empty( $_control['dependency'] ) ) {
													foreach ( $_control['dependency'] as $key => $val ) {
														if ( $key == $control_name ) {
															$_control['dependency'][ $key ] = [ 'search!=' . $section_orig_id . '-' . $index . '=1' ];
														} elseif ( array_key_exists( $key, $_section['controls'] ) ) {
															$_control['dependency'][ $key . '_' . $index ] = $val;
															unset( $_control['dependency'][ $key ] );
														}
													}
												}
												$new_controls[ $_control_name . '_' . $index ] = $_control;
											}
											$section_new['controls'] = $new_controls;
										}
										$ideapark_customize[] = $section_new;
										break;
									}
								}

								$checklist_control['default']                                    .= '|' . $section_orig_id . '-' . $index . '=0';
								$checklist_control['choices'][ $section_orig_id . '-' . $index ] = $checklist_control['choices'][ $section_orig_id ] . ' - ' . $index;
								if ( ! empty( $checklist_control['choices_edit'][ $section_orig_id ] ) ) {
									$checklist_control['choices_edit'][ $section_orig_id . '-' . $index ] = $checklist_control['choices_edit'][ $section_orig_id ] . '_' . $index;
								}
								if ( empty( $checklist_control['choices_delete'] ) ) {
									$checklist_control['choices_delete'] = [];
								}
								$checklist_control['choices_delete'][] = $section_orig_id . '-' . $index;
							}
						}
					}
				}
			}
		}

		if ( $languages = ideapark_active_languages() ) {
			foreach ( $ideapark_customize as $section_index => &$section ) {
				if ( ! empty( $section['controls'] ) && isset( $section['controls']['theme_font_header'] ) ) {
					$orig_controls = $section['controls'];
					$default_lang  = ideapark_default_language();
					foreach ( $languages as $lang_code => $lang_name ) {
						if ( $lang_code != $default_lang ) {
							$section['controls'][ 'header_font_lang_' . $lang_code ] = [
								'label'             => __( 'Fonts for', 'luchiana' ) . ' ' . $lang_name,
								'class'             => 'WP_Customize_Info_Control',
								'sanitize_callback' => 'sanitize_text_field',
								'priority'          => 101,
							];
							foreach ( $orig_controls as $control_name => $control ) {
								if ( $control_name == 'header_custom_fonts_info' ) {
									break;
								}
								$control['priority']                                     = 101;
								$section['controls'][ $control_name . '_' . $lang_code ] = $control;
							}
						}
					}
					break;
				}
			}
		}
	}
}

add_action( 'init', 'ideapark_init_theme_customize', 0 );
add_action( 'wp_loaded', 'ideapark_init_theme_mods' );
add_action( 'customize_register', 'ideapark_register_theme_customize', 100 );
add_action( 'customize_controls_print_styles', 'ideapark_customize_admin_style' );
add_action( 'customize_controls_enqueue_scripts', 'ideapark_customize_scripts' );
add_action( 'customize_save_after', 'ideapark_save_product_features', 99 );
add_action( 'customize_save_after', 'ideapark_after_customizer_save', 100 );
add_action( 'customize_save_after', 'ideapark_save_customize_colors', 101 );
add_action( 'after_update_theme_late', 'ideapark_clear_customize_cache', 100 );
add_action( 'after_update_theme_late', 'ideapark_fix_products_per_page', 10, 2 );
add_action( 'after_update_theme_late', 'ideapark_fix_woo_variation_swatches_shape', 10, 2 );
add_action( 'after_update_theme_late', 'ideapark_fix_theme_mods', 10, 2 );

add_filter( 'tiny_mce_before_init', 'ideapark_mce4_options' );
add_filter( 'elementor/editor/localize_settings', function ( $config ) {
	$t = [];
	$c = &$config['initial_document']['panel']['elements_categories'];
	foreach ( $c as $name => $value ) {
		if ( ! in_array( $name, [ 'basic', 'ideapark-elements' ] ) ) {
			$t[ $name ] = $value;
			unset( $c[ $name ] );
		}
	}
	foreach ( $t as $name => $value ) {
		$c[ $name ] = $value;
	}

	return $config;
}, 99 );