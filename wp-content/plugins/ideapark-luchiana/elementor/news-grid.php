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
 * Elementor news grid widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_News_Grid extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve news grid widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-news-grid';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve news grid widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'News Grid', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve news grid widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
		return [ 'grid', 'news' ];
	}

	/**
	 * Register news grid widget controls.
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
				'default' => 4,
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

		$this->end_controls_section();
	}

	/**
	 * Render news grid widget output on the frontend.
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
			'numberposts' => $settings['count'],
			'suppress_filters' => false
		];
		if ( ! empty( $settings['category'] ) ) {
			$args['category'] = $settings['category'];
		}
		$news = get_posts( $args );
		if ( ! $news ) {
			return;
		}
		?>
		<div class="c-ip-news-grid">
			<div class="c-ip-news-grid__list">
				<?php foreach ( $news as $index => $post ) { ?>
					<?php setup_postdata( $post ); ?>
					<div class="c-ip-news-grid__item <?php if (!has_post_thumbnail()) { ?>c-ip-news-grid__item--no-thumb<?php } ?>">
						<div class="c-ip-news-grid__thumb">
							<?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php echo get_permalink() ?>">
									<?php the_post_thumbnail( 'medium', [ 'class' => 'c-ip-news-grid__img' ] ); ?>
								</a>
							<?php } ?>
							<?php if ( ! ideapark_mod( 'post_grid_list_hide_date' ) ) { ?>
								<div class="c-ip-news-grid__meta-date">
									<?php the_time( get_option( 'date_format' ) ); ?>
								</div>
							<?php } ?>
						</div>
						<div class="c-ip-news-grid__wrap">
							<h2 class="c-ip-news-grid__header">
								<a class="c-ip-news-grid__header-link" href="<?php echo get_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>
							<?php if ( ! ideapark_mod( 'post_grid_list_hide_category' ) ) { ?>
								<div class="c-ip-news-grid__meta-category">
									<?php ideapark_category( '<span class="h-bullet"></span>', null, 'c-ip-news-grid__categories-item-link' ); ?>
								</div>
							<?php } ?>
							<a class="c-ip-news-grid__continue"
							   href="<?php echo get_permalink(); ?>"><?php esc_html_e( 'Read More', 'ideapark-luchiana' ); ?></a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	}

	/**
	 * Render news grid widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
