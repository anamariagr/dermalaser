<?php

defined( 'ABSPATH' ) or die();

class Ideapark_Elementor {

	private static $_instance = null;

	private $_version;
	private $file;
	private $dir;
	private $widgets_dir;
	private $assets_dir;
	private $assets_url;
	private $_token;

	function __construct( $file, $version = '1.0.0' ) {

		$this->_version    = $version;
		$this->file        = $file;
		$this->dir         = dirname( $this->file );
		$this->widgets_dir = trailingslashit( $this->dir ) . 'elementor';
		$this->assets_dir  = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url  = esc_url( rtrim( plugins_url( '/assets/', $this->file ), '/' ) );
		$this->_token      = 'ideapark_luchiana';

		include( 'wrapper-link-module.php' );
		Ideapark\WrapperLinks::instance();

		add_action( 'elementor/init', [ $this, 'load_elementor_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_category' ], 1 );
		add_action( 'elementor/widgets/register', [ $this, 'elementor_widgets_init' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'elementor_add_js' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'elementor_add_css' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'elementor_add_css_editor' ] );
		add_action( 'wp_insert_post', [ $this, 'set_canvas_template' ], 999, 3 );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 100 );

		add_filter( 'elementor/files/allow_unfiltered_upload', '__return_true' );

		add_filter( 'get_terms', [ $this, 'wc_change_term_counts' ], 20, 2 );

		add_action( 'edit_form_after_title', function ( $post ) {
			if ( function_exists( 'get_current_screen' ) && ! defined( 'ELEMENTOR_PRO_VERSION' ) && function_exists( 'wc_get_page_id' ) ) {
				global $post;
				$screen = get_current_screen();
				if ( $screen->parent_base == 'edit' && $screen->post_type == 'page' && $post->ID && ( $post->ID == wc_get_page_id( 'shop' ) || $post->ID == ideapark_mod( 'wishlist_page' ) ) ) {
					$admin = \Elementor\Plugin::instance()->admin;
					remove_action( 'edit_form_after_title', [ $admin, 'print_switch_mode_button' ] );
				}
			}

		}, 9 );
		add_action( 'enqueue_block_editor_assets', function () {
			if ( function_exists( 'get_current_screen' ) && ! defined( 'ELEMENTOR_PRO_VERSION' ) && function_exists( 'wc_get_page_id' ) ) {
				global $post;
				$screen = get_current_screen();
				if ( $screen->post_type == 'page' && $post->ID && ( $post->ID == wc_get_page_id( 'shop' ) || $post->ID == ideapark_mod( 'wishlist_page' ) ) ) {
					wp_scripts()->dequeue( 'elementor-gutenberg' );
				}
			}
		}, 11 );
	}

	public function wc_change_term_counts( $terms, $taxonomies ) {
		if ( ! ( is_admin() || wp_doing_ajax() ) || ! ( function_exists( 'ideapark_mod' ) && ideapark_mod( '_wc_change_term_counts' ) ) ) {
			return $terms;
		}

		if ( ! isset( $taxonomies[0] ) || ! in_array( $taxonomies[0], apply_filters( 'woocommerce_change_term_counts', array(
				'product_cat',
				'product_tag'
			) ), true ) ) {
			return $terms;
		}

		$o_term_counts = get_transient( 'wc_term_counts' );
		$term_counts   = false === $o_term_counts ? array() : $o_term_counts;

		foreach ( $terms as &$term ) {
			if ( is_object( $term ) ) {
				$term_counts[ $term->term_id ] =
					isset( $term_counts[ $term->term_id ] ) ?
						$term_counts[ $term->term_id ] :
						get_term_meta( $term->term_id, 'product_count_' . $taxonomies[0], true );

				if ( '' !== $term_counts[ $term->term_id ] ) {
					$term->count = absint( $term_counts[ $term->term_id ] );
				}
			}
		}

		// Update transient.
		if ( $term_counts !== $o_term_counts ) {
			set_transient( 'wc_term_counts', $term_counts, DAY_IN_SECONDS * 30 );
		}

		return $terms;
	}


	function is_elementor() {
		return class_exists( 'Elementor\Plugin' );
	}

	public function set_canvas_template( $post_ID, $post, $update ) {
		if ( ! $update && $post->post_type == 'html_block' && $this->is_elementor() ) {
			update_post_meta( $post_ID, '_wp_page_template', 'elementor_canvas' );
		}
	}

	public function frontend_scripts() {
		if ( $GLOBALS['pagenow'] != 'wp-login.php' && ! is_admin() ) {
			if ( function_exists( 'ideapark_add_style' ) ) {
				ideapark_add_style( 'ideapark-elementor', $this->assets_url . '/css/style.min.css', [], $this->mtime( $this->assets_dir . '/css/style.min.css' ), 'all', $this->assets_dir . '/css/style.min.css' );

				ideapark_add_script( 'jquery-countdown', $this->assets_url . '/js/jquery.countdown.min.js', [ 'jquery' ], '2.2.0', true, $this->assets_dir . '/js/jquery.countdown.min.js' );
				ideapark_add_script( 'ideapark-elementor', $this->assets_url . '/js/site.js', [ 'jquery' ], $this->mtime( $this->assets_dir . '/js/site.js' ), true, $this->assets_dir . '/js/site.js' );

			} else {
				wp_enqueue_style( 'ideapark-elementor', $this->assets_url . '/css/style.min.css', [], $this->mtime( $this->assets_dir . '/css/style.min.css' ), 'all' );

				wp_enqueue_script( 'jquery-countdown', $this->assets_url . '/js/jquery.countdown.min.js', [ 'jquery' ], '2.2.0', true );
				wp_enqueue_script( 'ideapark-elementor', $this->assets_url . '/js/site.js', [ 'jquery' ], $this->mtime( $this->assets_dir . '/js/site.js' ), true );
			}
		}
	}

	public function add_widget_category( $elements_manager ) {

		$elements_manager->add_category(
			'ideapark-elements',
			[
				'title' => esc_html__( 'Luchiana Widgets', 'ideapark-luchiana' ),
				'icon'  => 'luchiana-logo',
			]
		);

	}

	public function load_elementor_widgets() {
		require_once $this->widgets_dir . '/heading.php';
		require_once $this->widgets_dir . '/page-header.php';
		require_once $this->widgets_dir . '/button.php';
		require_once $this->widgets_dir . '/inline-menu.php';
		require_once $this->widgets_dir . '/mega-menu.php';
		require_once $this->widgets_dir . '/social.php';
		require_once $this->widgets_dir . '/slider.php';
		require_once $this->widgets_dir . '/woocommerce-carousel.php';
		require_once $this->widgets_dir . '/woocommerce-grid.php';
		require_once $this->widgets_dir . '/video-carousel.php';
		require_once $this->widgets_dir . '/news-carousel.php';
		require_once $this->widgets_dir . '/news-grid.php';
		require_once $this->widgets_dir . '/icon-list-1.php';
		require_once $this->widgets_dir . '/image-list-1.php';
		require_once $this->widgets_dir . '/image-list-2.php';
		require_once $this->widgets_dir . '/reviews.php';
		require_once $this->widgets_dir . '/instagram.php';
		require_once $this->widgets_dir . '/team.php';
		require_once $this->widgets_dir . '/accordion.php';
		require_once $this->widgets_dir . '/tabs.php';
		require_once $this->widgets_dir . '/countdown.php';
		require_once $this->widgets_dir . '/product-tabs.php';
		require_once $this->widgets_dir . '/video.php';
		require_once $this->widgets_dir . '/banners.php';
		require_once $this->widgets_dir . '/services.php';
		require_once $this->widgets_dir . '/gift.php';
		require_once $this->widgets_dir . '/pricing.php';
		require_once $this->widgets_dir . '/brand-list.php';
		require_once $this->widgets_dir . '/circle-text.php';
		require_once $this->widgets_dir . '/running-line.php';
		require_once $this->widgets_dir . '/categories-inline.php';
		require_once $this->widgets_dir . '/before-after.php';
	}

	public function elementor_widgets_init() {
		if ( class_exists( 'Ideapark_Elementor_Heading' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Heading() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Page_Header() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Button() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Social() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Inline_Menu() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Mega_Menu() );

			if ( ideapark_woocommerce_on() ) {
				\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Product_Tabs() );
				\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Woocommerce_Carousel() );

				\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Woocommerce_Grid() );
				\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Video_Carousel() );
			}
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_News_Carousel() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_News_Grid() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Slider() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Reviews() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Icon_List_1() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Image_List_1() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Instagram() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Team() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Accordion() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Tabs() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Countdown() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Video() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Banners() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Image_List_2() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Services() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Gift() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Pricing() );
			if ( ideapark_woocommerce_on() ) {
				\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Brand_List() );
			}

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Circle_Text() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Running_Line() );

			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Categories_Inline() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Ideapark_Elementor_Before_After() );
		}
	}

	public function elementor_add_js() {
		$is_preview_mode = ideapark_is_elementor_preview_mode();
		if ( $is_preview_mode ) {
			wp_register_script( $this->_token . '-elementor', esc_url( $this->assets_url ) . '/js/elementor.js', [ 'jquery' ], $this->mtime( $this->assets_dir . '/js/elementor.js' ) );
			wp_enqueue_script( $this->_token . '-elementor' );
		}
	}

	public function elementor_add_css() {
	}

	public function elementor_add_css_editor() {
		wp_register_style( $this->_token . '-elementor', esc_url( $this->assets_url ) . '/css/elementor.css', [], $this->mtime( $this->assets_dir . '/css/elementor.css' ) );
		wp_enqueue_style( $this->_token . '-elementor' );
	}

	public function mtime( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->mtime( $file );
			}
		}

		return '';
	}

	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()


	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ideapark-luchiana' ), $this->_version );
	} // End __clone ()


	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ideapark-luchiana' ), $this->_version );
	} // End __wakeup ()

}