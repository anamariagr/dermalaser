<?php

use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor image list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Before_After extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image list widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-before-after';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image list widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Before | After', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image list widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-image';
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
		return [ 'before', 'after', 'image' ];
	}

	/**
	 * Register image list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Content', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'image_before',
			[
				'label'   => __( 'Image (Before)', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->add_control(
			'title_before',
			[
				'label'       => __( 'Title (Before)', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'image_after',
			[
				'label'   => __( 'Image (After)', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->add_control(
			'title_after',
			[
				'label'       => __( 'Title (After)', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
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
			'color',
			[
				'label'       => __( 'Text Color', 'ideapark-luchiana' ),
				'description' => __( 'Select color or leave empty for display default.', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .c-ip-before-after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .c-ip-before-after__title',
			]
		);

		$this->add_responsive_control(
			'aspect_ratio',
			[
				'label'      => __( 'Aspect Ratio', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => [
					'size' => 1,
				],
				'range'      => [
					'px' => [
						'min'  => 0.3,
						'max'  => 3,
						'step' => 0.1
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-before-after' => 'aspect-ratio: {{SIZE}};',
				],
			]
		);


		$this->add_responsive_control(
			'max_width',
			[
				'label'      => __( 'Max Width', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1920,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'vw' ],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],
				'selectors'  => [
					'{{WRAPPER}} .c-ip-before-after' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render image list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="c-ip-before-after js-before-after">
			<div class="c-ip-before-after__before js-before">
				<?php if ( ! empty( $settings['image_before']['id'] ) && ( $type = get_post_mime_type( $settings['image_before']['id'] ) ) ) {
					if ( $type == 'image/svg+xml' ) {
						echo ideapark_get_inline_svg( $settings['image_before']['id'], 'c-ip-before-after__svg js-before-image' );
					} else {
						echo ideapark_img( ideapark_image_meta( $settings['image_before']['id'], 'large' ), 'c-ip-before-after__image js-before-image' );
					}
				}
				?>
				<?php if ( ! empty( $settings['title_before'] ) ) { ?>
					<div class="c-ip-before-after__title c-ip-before-after__title--before"><?php echo $settings['title_before']; ?></div>
				<?php } ?>
			</div>
			<div class="c-ip-before-after__after">
				<?php if ( ! empty( $settings['image_after']['id'] ) && ( $type = get_post_mime_type( $settings['image_after']['id'] ) ) ) {
					if ( $type == 'image/svg+xml' ) {
						echo ideapark_get_inline_svg( $settings['image_after']['id'], 'c-ip-before-after__svg' );
					} else {
						echo ideapark_img( ideapark_image_meta( $settings['image_after']['id'], 'large' ), 'c-ip-before-after__image' );
					}
				}
				?>
				<?php if ( ! empty( $settings['title_after'] ) ) { ?>
					<div class="c-ip-before-after__title c-ip-before-after__title--after"><?php echo $settings['title_after']; ?></div>
				<?php } ?>
			</div>
			<div class="c-ip-before-after__resizer js-resizer"></div>
		</div>
		<?php
	}

	/**
	 * Render image list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
