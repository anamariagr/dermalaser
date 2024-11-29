<?php

use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor video carousel  widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Video_Carousel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve video carousel  widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-video-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve video carousel  widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Product Videos', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve video carousel  widget icon.
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
		return [ 'carousel', 'video' ];
	}

	/**
	 * Register video carousel  widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_carousel',
			[
				'label' => __( 'Carousel', 'ideapark-luchiana' ),
			]
		);

		$list = [
			'' => esc_html__( 'Shop', 'ideapark-luchiana' ),
		];

		$args = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'meta_value_num',
			'meta_key'     => 'order',
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

			$get_category = function ( $parent = 0, $prefix = ' - ' ) use ( &$list, &$category_parent, &$category_name, &$category_slug, &$get_category ) {
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

		$this->add_control(
			'product_category',
			[
				'label'   => __( 'Product Category', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $list,
			]
		);

		$this->add_control(
			'count',
			[
				'label'   => __( 'Videos count', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => 8,
				'step'    => 1,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Sort', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'rand',
				'options' => [
					'rand' => __( 'Random sorting', 'ideapark-luchiana' ),
					'date' => __( 'Sort by date the product was published', 'ideapark-luchiana' ),
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'ideapark-luchiana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color',
			[
				'label'       => __( 'Text Color', 'ideapark-luchiana' ),
				'description' => __( 'Select color or leave empty for display default.', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .c-ip-video-carousel__title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .h-carousel .owl-next'       => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .h-carousel .owl-prev'       => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'arrows',
			[
				'label'     => __( 'Arrows', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off' => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'dots',
			[
				'label'     => __( 'Navigation dots', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off' => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render video carousel  widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  .0.0
	 * @access protected
	 */
	protected function render() {
		global $wpdb;
		$settings = $this->get_settings();

		$exclude_term_ids = [];
		if ( $exclude_search_term = get_term_by( 'name', 'exclude-from-search', 'product_visibility' ) ) {
			$exclude_term_ids[] = (int) $exclude_search_term->term_taxonomy_id;
		}
		if ( ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) && ( $exclude_outofstock_term = get_term_by( 'name', 'outofstock', 'product_visibility' ) ) ) {
			$exclude_term_ids[] = (int) $exclude_outofstock_term->term_taxonomy_id;
		}

		$exclude_term_ids = array_unique( array_merge( $exclude_term_ids, ideapark_hidden_category_ids() ) );

		$limit = (int) $settings['count'];

		$sql     = "
			SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, pm_video.meta_value AS video_url, pm_video_thumb.meta_value AS video_thumb
			FROM {$wpdb->posts}
			INNER JOIN {$wpdb->postmeta} pm_video ON ( {$wpdb->posts}.ID = pm_video.post_id AND pm_video.meta_key='_ip_product_video_url')
			LEFT JOIN {$wpdb->postmeta} pm_video_thumb ON ( {$wpdb->posts}.ID = pm_video_thumb.post_id AND pm_video_thumb.meta_key='_ip_product_video_thumb')
			" . ( $settings['product_category'] ? "
			INNER JOIN {$wpdb->wc_category_lookup} wcl ON wcl.category_tree_id = " . abs( (int) $settings['product_category'] ) . "
			INNER JOIN {$wpdb->term_relationships} cat ON {$wpdb->posts}.ID = cat.object_id AND cat.term_taxonomy_id = wcl.category_id
			" : '' ) . "
			WHERE pm_video.meta_value!='' 
			AND ( {$wpdb->posts}.post_type IN ( 'product' ) )
			AND ( {$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'private' )
			" . ( ! empty( $exclude_term_ids ) ? "AND ( {$wpdb->posts}.ID NOT IN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN (" . implode( ',', $exclude_term_ids ) . ") ) )" : "" ) . "
			GROUP BY {$wpdb->posts}.ID
			ORDER BY " . ( $settings['orderby'] == 'rand' ? 'RAND()' : 'post_date DESC' ) . "
			LIMIT $limit
			";
		$results = $wpdb->get_results( $sql, OBJECT );
		?>
		<?php if ( ! empty( $results ) ) { ?>
			<div class="c-ip-video-carousel">
				<div class="c-ip-video-carousel__wrap">
					<div
						class="c-ip-video-carousel__list c-ip-video-carousel__list--<?php echo sizeof( $results ); ?> js-video-carousel h-carousel h-carousel--default-dots<?php if ( $settings['arrows'] != 'yes' ) { ?> h-carousel--nav-hide<?php } else { ?> h-carousel--outside<?php } ?><?php if ( $settings['dots'] != 'yes' ) { ?> h-carousel--dots-hide<?php } else { ?> c-ip-video-carousel__list--dots<?php } ?>">
						<?php foreach ( $results as $index => $post ) {
							$video_url  = $post->video_url;
							$image_code = '';
							if ( ( $video_thumb_id = $post->video_thumb ) && ( $image_meta = ideapark_image_meta( $video_thumb_id, 'woocommerce_thumbnail' ) ) ) {
								$image_code = ideapark_img( $image_meta, 'c-ip-video-carousel__img' );
							} else {
								$pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
								if ( preg_match( $pattern, $video_url, $match ) ) {
									$image_url  = 'https://img.youtube.com/vi/' . $match[1] . '/maxresdefault.jpg';
									$image_code = '<img class="c-ip-video-carousel__img" src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $post->post_title ) . '" ' . ( ideapark_mod( 'lazyload' ) ? 'loading="lazy"' : '' ) . ' />';
								}
							}
							?>
							<div class="c-ip-video-carousel__item">
								<a class="js-ip-video" data-vbtype="video" data-autoplay="true"
								   href="<?php echo esc_url( $video_url ); ?>" target="_blank" onclick="return false;">
									<div class="c-ip-video-carousel__thumb">
										<?php echo ideapark_wrap( $image_code ); ?>
										<i class="c-play c-ip-video-carousel__play"></i>
									</div>
								</a>
								<a href="<?php esc_url( get_permalink( $post->ID ) ); ?>">
									<div class="c-ip-video-carousel__title">
										<?php echo esc_html( $post->post_title ) ?>
									</div>
								</a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php
	}

	/**
	 * Render video carousel  widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
