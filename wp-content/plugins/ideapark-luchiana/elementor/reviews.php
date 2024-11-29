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
 * Elementor reviews widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Reviews extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve reviews widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-reviews';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve reviews widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Reviews Carousel', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve reviews widget icon.
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
		return [ 'carousel', 'reviews', 'reviews' ];
	}

	/**
	 * Register reviews widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_reviews',
			[
				'label' => __( 'Reviews', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Avatar', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],

			]
		);

		$repeater->add_control(
			'reviewer_name',
			[
				'label'       => __( 'Reviewer name', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter name', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'reviewer_text',
			[
				'label'       => __( 'Review text', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter review text', 'ideapark-luchiana' ),
				'separator'   => 'none',
				'rows'        => 5,
				'show_label'  => false,
			]
		);

		$this->add_control(
			'review_list',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'reviewer_name' => __( 'Name #1', 'ideapark-luchiana' ),
						'reviewer_text' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
					],
					[
						'reviewer_name' => __( 'Name #2', 'ideapark-luchiana' ),
						'reviewer_text' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
					],
					[
						'reviewer_name' => __( 'Name #3', 'ideapark-luchiana' ),
						'reviewer_text' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
					],
				],
				'title_field' => '{{{ reviewer_name }}}',
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
			'items',
			[
				'label'   => __( 'Items per row (Desktop)', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => __( '1 per row', 'ideapark-luchiana' ),
					'2' => __( '2 per row', 'ideapark-luchiana' ),
					'3' => __( '3 per row', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => __( 'Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-ip-reviews' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows',
			[
				'label'   => __( 'Arrows', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on'     => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'   => __( 'Arrows', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'     => __( 'Default', 'ideapark-luchiana' ),
					'round'       => __( 'Dark', 'ideapark-luchiana' ),
					'round-light' => __( 'Light', 'ideapark-luchiana' ),
					'round-white' => __( 'White', 'ideapark-luchiana' ),
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots',
			[
				'label'   => __( 'Navigation dots', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on'     => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'use_avatars',
			[
				'label'   => __( 'Avatars', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on'     => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => __( 'Autoplay', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .c-ip-reviews' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label'      => __( 'Items margin', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 50,
				],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 100,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],
			]
		);

		$this->add_responsive_control(
			'max_width',
			[
				'label'      => __( 'Max width of the review block', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1160,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-reviews__wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render reviews widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="c-ip-reviews c-ip-reviews--<?php echo esc_attr( $settings['items'] ); ?><?php if ( $settings['dots'] == 'yes' ) { ?> c-ip-reviews--dots<?php } ?> js-reviews">
			<div class="c-ip-reviews__wrap">
				<div
					data-margin="<?php echo esc_attr( $settings['margin']['size'] ); ?>"
					data-items="<?php echo esc_attr( $settings['items'] ); ?>"
					class="c-ip-reviews__list c-ip-reviews__list--<?php echo esc_attr( $settings['items'] ); ?> js-reviews-carousel h-carousel h-carousel--flex h-carousel--default-dots <?php if ( $settings['dots'] != 'yes' ) { ?> h-carousel--dots-hide<?php } else { ?> c-ip-reviews__list--dots<?php } ?> <?php if ( $settings['arrows'] != 'yes' ) { ?> h-carousel--nav-hide<?php } else { ?> h-carousel--outside h-carousel--<?php echo esc_attr( $settings['arrows_color'] ); ?><?php } ?> <?php if ( $settings['autoplay'] == 'yes' ) { ?> h-carousel--autoplay<?php } ?>">
					<?php foreach ( $settings['review_list'] as $index => $item ) { ?>
						<?php
						$avatar = '';
						if ( $settings['use_avatars'] == 'yes' ) {
							if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) {
								if ( $type == 'image/svg+xml' ) {
									$avatar = ideapark_get_inline_svg( $item['image']['id'], 'c-ip-reviews__svg' );
								} else {
									$avatar = ideapark_img( ideapark_image_meta( $item['image']['id'], 'thumbnail' ), 'c-ip-reviews__image' );
								}
							}
						}
						?>
						<div class="c-ip-reviews__item" data-index="<?php echo esc_attr( $index ); ?>">
							<?php echo ideapark_wrap( $item['reviewer_text'], '<div class="c-ip-reviews__text">', '</div>' ); ?>
							<?php echo ideapark_wrap( $avatar . $item['reviewer_name'], '<div class="c-ip-reviews__name">', '</div>' ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render reviews widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
