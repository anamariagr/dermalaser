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
 * Elementor news carousel  widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_News_Carousel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve news carousel  widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-news-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve news carousel  widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'News Carousel', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve news carousel  widget icon.
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
		return [ 'carousel', 'news' ];
	}

	/**
	 * Register news carousel  widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_category',
			[
				'label' => __( 'News', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'count',
			[
				'label'   => __( 'News count', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => 3,
				'step'    => 1,
			]
		);

		$options = [ 0 => __( 'All', 'ideapark-luchiana' ) ];
		if ( $categories = get_categories() ) {
			foreach ( $categories as $category ) {
				$options[ $category->term_id ] = $category->name;
			}
		}
		$this->add_control(
			'category',
			[
				'label'   => __( 'Category', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 0,
				'options' => $options
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
	 * Render news carousel  widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  .0.0
	 * @access protected
	 */
	protected function render() {
		global $post;
		$settings = $this->get_settings();
		$args     = [
			'numberposts'      => $settings['count'],
			'suppress_filters' => false
		];
		if ( ! empty( $settings['category'] ) ) {
			$args['category'] = $settings['category'];
		}
		$news = get_posts( $args );
		if ( ! $news ) {
			return;
		}
		$old_sidebar_blog = ideapark_mod( 'sidebar_blog' );
		$old_post_layout  = ideapark_mod( 'post_layout' );
		ideapark_mod_set_temp( 'sidebar_blog', false );
		ideapark_mod_set_temp( 'post_layout', 'grid' );
		ideapark_mod_set_temp( '_disable_post_gallery', true );
		?>
		<div class="c-ip-news-carousel">
			<div class="c-ip-news-carousel__wrap">
				<div
					class="c-ip-news-carousel__list c-ip-news-carousel__list--<?php echo sizeof( $news ); ?> js-news-carousel h-carousel h-carousel--default-dots h-carousel--flex h-carousel--<?php echo esc_attr( $settings['arrows_placement'] ); ?> h-carousel--<?php echo esc_attr( $settings['arrows_color'] ); ?><?php if ( $settings['dots'] != 'yes' ) { ?> h-carousel--dots-hide<?php } else { ?> c-ip-news-carousel__list--dots<?php } ?>">
					<?php foreach ( $news as $index => $post ) { ?>
						<?php setup_postdata( $post ); ?>
						<?php get_template_part( 'templates/content-list' ); ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		ideapark_mod_set_temp( '_disable_post_gallery', false );
		ideapark_mod_set_temp( 'sidebar_blog', $old_sidebar_blog );
		ideapark_mod_set_temp( 'post_layout', $old_post_layout );
		wp_reset_postdata();
	}

	/**
	 * Render news carousel  widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
