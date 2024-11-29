<?php

use Elementor\Control_Media;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Woocommerce_Carousel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-woocommerce-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon list widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Product Carousel', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon list widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'ip-pr-carousel';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 */
	public function get_categories() {
		return [ 'ideapark-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since  2.1.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [ 'carousel', 'woocommerce', 'list' ];
	}

	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Product Carousel', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'   => __( 'Type', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent_products',
				'options' => $this->type_list()
			]
		);

		$this->add_control(
			'shortcode',
			[
				'label'       => __( 'Enter Woocommerce shortcode', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => '[product_attribute attribute="color" filter="black"]',
				'default'     => '',
				'condition'   => [
					'type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Widget Settings', 'ideapark-luchiana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Sort', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'menu_order',
				'options' => [
					''           => __( 'Default sorting', 'ideapark-luchiana' ),
					'rand'       => __( 'Random sorting', 'ideapark-luchiana' ),
					'date'       => __( 'Sort by date the product was published', 'ideapark-luchiana' ),
					'id'         => __( 'Sort by post ID of the product', 'ideapark-luchiana' ),
					'menu_order' => __( 'Sort by menu order', 'ideapark-luchiana' ),
					'popularity' => __( 'Sort by number of purchases', 'ideapark-luchiana' ),
					'rating'     => __( 'Sort by average product rating', 'ideapark-luchiana' ),
					'title'      => __( 'Sort by product title', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => __( 'Order', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => [
					'ASC'  => 'ASC',
					'DESC' => 'DESC',
				]
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => __( 'Products in carousel', 'ideapark-luchiana' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => 6,
			]
		);

		$this->add_control(
			'product_layout',
			[
				'label'   => __( 'Product layout', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'ideapark-luchiana' ),
					'large'   => __( 'Large', 'ideapark-luchiana' ),
					'compact' => __( 'Compact', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'auto_width',
			[
				'label'   => __( 'Auto width', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'viewed_items',
			[
				'label'     => __( 'Viewed items', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'condition' => [
					'auto_width' => 'no',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'   => __( 'Arrows', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'round-light',
				'options' => [
					'round'       => __( 'Dark', 'ideapark-luchiana' ),
					'round-light' => __( 'Light', 'ideapark-luchiana' ),
					'round-white' => __( 'White', 'ideapark-luchiana' ),
					'nav-hide'    => __( 'Hide', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'arrows_placement',
			[
				'label'     => __( 'Arrows placement', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'outside',
				'options'   => [
					'outside' => __( 'Outside', 'ideapark-luchiana' ),
					'border'  => __( 'On border', 'ideapark-luchiana' ),
				],
				'condition' => [
					'arrows_color!' => 'nav-hide',
				],

			]
		);

		$this->add_control(
			'dots',
			[
				'label'   => __( 'Navigation dots', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on'     => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => __( 'Enable Loop', 'ideapark-luchiana' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => __( 'No', 'ideapark-luchiana' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'carousel_autoplay',
			[
				'label'   => __( 'Autoplay', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'carousel_animation_timeout',
			[
				'label'      => __( 'Autoplay Timeout (sec)', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 5,
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'condition'  => [
					'carousel_autoplay' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$cat_id   = preg_match( '~^-\d+$~', $settings['type'] ) ? $cat_id = absint( $settings['type'] ) : 0;

		if ( $settings['product_layout'] == 'compact' ) {
			$layout = 'compact';
		} elseif ( $settings['product_layout'] == 'large' ) {
			$layout = '3-per-row';
		} else {
			$layout = '4-per-row';
		}

		$_old_product_layout = ideapark_mod( '_product_layout' );
		$_old_product_layout_class = ideapark_mod( '_product_layout_class' );
		ideapark_mod_set_temp( '_product_layout', $layout );
		ideapark_mod_set_temp( '_product_layout_class', 'c-product-grid__item--' . $layout . ( $layout !== 'compact' ? ' c-product-grid__item--normal c-product-grid__item--' . ideapark_mod( 'atc_button_visibility' ) . ideapark_grid_class_mobile( $layout ) : '' ) );

		if ( $layout == '3-per-row' ) {
			add_filter( 'single_product_archive_thumbnail_size', [ $this, 'wc_thumbnail_size' ], 99 );
		}

		ob_start();
		?>
		<?php if ( $cat_id ) { ?>
			<?php echo do_shortcode( '[products category="' . $cat_id . '" limit="' . $settings['limit'] . '"' . ( $settings['orderby'] ? ' orderby="' . $settings['orderby'] . '" order="' . $settings['order'] . '"' : '' ) . ']' ); ?>
		<?php } elseif ( $settings['type'] == 'custom' && ( $settings['shortcode'] = trim( $settings['shortcode'] ) ) && preg_match( '~\[([^\] ]+)~', $settings['shortcode'], $match ) && shortcode_exists( $match[1] ) ) { ?>
			<?php
			$settings['shortcode'] = preg_replace( '~(limit|order|orderby)\s*=\s*["\'][\s\S]*["\']~uUi', '', $settings['shortcode'] );
			$settings['shortcode'] = preg_replace( '~\]~', ' limit="' . $settings['limit'] . '"' . ( $settings['orderby'] ? ' orderby="' . $settings['orderby'] . '" order="' . $settings['order'] . '"' : '' ) . ']', $settings['shortcode'] );
			echo do_shortcode( $settings['shortcode'] ); ?>
		<?php } elseif ( $settings['type'] != 'custom' ) { ?>
			<?php echo do_shortcode( '[' . $settings['type'] . ' limit="' . $settings['limit'] . '"' . ( $settings['orderby'] ? ' orderby="' . $settings['orderby'] . '" order="' . $settings['order'] . '"' : '' ) . ']' ); ?>
		<?php } ?>
		<?php
		$content = ob_get_clean();

		if ( $layout == '3-per-row' ) {
			ideapark_rf( 'single_product_archive_thumbnail_size', [ $this, 'wc_thumbnail_size' ], 99 );
		}

		ideapark_mod_set_temp( '_product_layout', $_old_product_layout );
		ideapark_mod_set_temp( '_product_layout_class', $_old_product_layout_class );

		preg_match_all( '~class="c-product-grid__item ~', $content, $matches, PREG_SET_ORDER );
		$count = sizeof( $matches );
		if ( $count > $settings['viewed_items'] ) {
			$count = $settings['viewed_items'];
		}
		$_data = '';
		if ( $settings['carousel_autoplay'] ) {
			$_data .= 'data-autoplay="' . esc_attr( $settings['carousel_autoplay'] ) . '" ';
			if ( ! empty( $settings['carousel_animation_timeout']['size'] ) ) {
				$_data .= 'data-animation-timeout="' . esc_attr( abs( $settings['carousel_animation_timeout']['size'] * 1000 ) ) . '" ';
			}
		}
		$content = str_replace( 'class="c-product-grid__list ', $_data . 'class="c-product-grid__list c-ip-woocommerce-carousel__list c-ip-woocommerce-carousel__list--' . $layout . ( $settings['auto_width'] != 'yes' ? ' c-ip-woocommerce-carousel__list--' . $count : '' ) . ' js-woocommerce-carousel h-carousel h-carousel--default-dots h-carousel--flex h-carousel--' . esc_attr( $settings['arrows_color'] ) . ' h-carousel--' . esc_attr( $settings['arrows_placement'] ) . ' ' . ( $settings['dots'] != 'yes' ? 'h-carousel--dots-hide' : 'c-ip-woocommerce-carousel__list--dots' ) . ' ' . ( $settings['loop'] == 'yes' ? 'h-carousel--loop' : '' ) . ' ', $content );
		echo ideapark_wrap( $content, '<div class="c-ip-woocommerce-carousel"><div class="c-ip-woocommerce-carousel__wrap">', '</div></div>' );
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}

	function type_list() {
		$list = [
			'recent_products'       => esc_html__( 'Recent Products', 'ideapark-luchiana' ),
			'featured_products'     => esc_html__( 'Featured Products', 'ideapark-luchiana' ),
			'sale_products'         => esc_html__( 'Sale Products', 'ideapark-luchiana' ),
			'best_selling_products' => esc_html__( 'Best-Selling Products', 'ideapark-luchiana' ),
			'top_rated_products'    => esc_html__( 'Top Rated Products', 'ideapark-luchiana' ),
			'custom'                => esc_html__( 'Custom Woocommerce Shortcode', 'ideapark-luchiana' ),
		];

		$args = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'term_group',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
			'exclude'      => ideapark_hidden_category_ids()? : null,
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
						$list[ '-' . $category_id ] = $prefix . $category_name[ $category_id ];
						$get_category( $category_id, $prefix . ' - ' );
					}
				}
			};

			$get_category();
		}

		return $list;
	}

	function wc_thumbnail_size( $size ) {
		return 'medium';
	}
}
