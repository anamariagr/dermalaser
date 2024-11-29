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
class Ideapark_Elementor_Categories_Inline extends Widget_Base {

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
		return 'ideapark-categories-inline';
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
		return __( 'Categories inline', 'ideapark-luchiana' );
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
		return 'ip-inline-menu';
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
		return [ 'categories', 'image', 'list' ];
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
				'label' => __( 'Category List', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Choose Image', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);


		$repeater->add_control(
			'title_text',
			[
				'label'       => __( 'Title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'This is the title', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'       => __( 'Link', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title_text }}}',
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
			'icon_svg',
			[
				'label'            => __( 'Separator', 'ideapark-luchiana' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'default'          => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon'
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
					'{{WRAPPER}} .c-ip-categories-inline' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_hover',
			[
				'label'       => __( 'Text Color on Hover', 'ideapark-luchiana' ),
				'description' => __( 'Select color or leave empty for display default.', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .c-ip-categories-inline__item:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .c-ip-categories-inline__title',
			]
		);


		$this->add_responsive_control(
			'space',
			[
				'label'      => __( 'Space between separator and text', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 25,
				],
				'range'      => [
					'px' => [
						'min' => 5,
						'max' => 100,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-categories-inline__list' => 'gap: {{SIZE}}{{UNIT}};column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_style',
			[
				'label'   => __( 'Image style', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'round',
				'options' => [
					'round'    => __( 'Round', 'ideapark-luchiana' ),
					'square'   => __( 'Square', 'ideapark-luchiana' ),
					'original' => __( 'Original', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_responsive_control(
			'item_width',
			[
				'label'      => __( 'Image Width', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 115,
				],
				'range'      => [
					'px' => [
						'min' => 90,
						'max' => 350,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-categories-inline__thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label'      => __( 'Separator size', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 12,
				],
				'range'      => [
					'px' => [
						'min' => 5,
						'max' => 30,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-categories-inline__separator' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .c-ip-categories-inline__list svg'  => 'height: {{SIZE}}{{UNIT}};',
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
		<div class="c-ip-categories-inline">
		<div class="c-ip-categories-inline__list">
			<?php
			foreach ( $settings['icon_list'] as $index => $item ) { ?>
				<div class="c-ip-categories-inline__item">
					<?php
					if ( ! empty( $item['link']['url'] ) ) {
						$is_link  = true;
						$link_key = 'link_' . $index;

						$this->add_link_attributes( $link_key, $item['link'] );
						$this->add_render_attribute( $link_key, 'class', 'c-ip-categories-inline__link' );
					} else {
						$is_link = false;
					} ?>
					<?php if ( $is_link ) { ?>
					<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
						<?php } ?>
						<?php if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) {
							if ( $type == 'image/svg+xml' ) {
								echo ideapark_get_inline_svg( $item['image']['id'], 'c-ip-categories-inline__thumb c-ip-categories-inline__thumb--svg c-ip-categories-inline__thumb--' . esc_attr( $settings['image_style'] ) );
							} else {
								echo ideapark_img( ideapark_image_meta( $item['image']['id'], 'medium' ), 'c-ip-categories-inline__thumb c-ip-categories-inline__thumb--image c-ip-categories-inline__thumb--' . esc_attr( $settings['image_style'] ) );
							}
						}
						?>

						<?php if ( ! empty( $item['title_text'] ) ) { ?>
							<div class="c-ip-categories-inline__title"><?php echo $item['title_text']; ?></div>
						<?php } ?>
						<?php if ( $is_link ) { ?>
					</a>
				<?php } ?>
				</div>
				<?php if ( $index + 1 < sizeof( $settings['icon_list'] ) && ! empty( $settings['icon_svg'] ) ) {
					Icons_Manager::render_icon( $settings['icon_svg'], [
						'aria-hidden' => 'true',
						'class'       => 'c-ip-categories-inline__separator'
					] );
				} ?>
				<?php
			}
			?>
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
