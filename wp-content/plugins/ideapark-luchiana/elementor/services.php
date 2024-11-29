<?php

use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor services widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Services extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve services widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-services';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve services widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Services', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve services widget icon.
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
		return [ 'services', 'service', 'list' ];
	}

	/**
	 * Register services widget controls.
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
				'label' => __( 'Services', 'ideapark-luchiana' ),
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
				'default'     => __( 'This is the heading', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter your title', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description_text',
			[
				'label'       => __( 'Description', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'This is the description', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter your description', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'       => __( 'Button text', 'ideapark-luchiana' ),
				'default'     => __( 'Read more', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter text', 'ideapark-luchiana' ),
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
			'filter',
			[
				'label'   => __( 'Grayscale hover filter', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on'     => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => __( 'Item Height', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 515,
				],
				'range'      => [
					'px' => [
						'min' => 300,
						'max' => 700,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .c-ip-services__item' => '--item-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label'      => __( 'Text Min Height', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 325,
				],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 500,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .c-ip-services__content' => '--content-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render services widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$is_long  = false;
		foreach ( $settings['icon_list'] as $index => $item ) {
			if ( ! empty( $item['description_text'] ) ) {
				$is_long = true;
			}
		}
		?>
		<div class="c-ip-services">
			<div class="c-ip-services__wrap">
				<div class="c-ip-services__list">
					<?php
					foreach ( $settings['icon_list'] as $index => $item ) { ?>
						<div class="c-ip-services__item">
							<?php
							if ( ! empty( $item['link']['url'] ) ) {
								$is_link  = true;
								$link_key = 'link_' . $index;

								$this->add_link_attributes( $link_key, $item['link'] );
								$this->add_render_attribute( $link_key, 'class', 'c-ip-services__link' );
							} else {
								$is_link = false;
							} ?>
							<div class="c-ip-services__thumb">
								<?php if ( $is_link ) { ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php } ?>
									<?php if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) {
										if ( $type == 'image/svg+xml' ) {
											echo ideapark_get_inline_svg( $item['image']['id'], 'c-ip-services__svg' . ( $settings['filter'] == 'yes' ? ' c-ip-services__svg--filter' : '' ) );
										} else {
											echo ideapark_img( ideapark_image_meta( $item['image']['id'], 'medium' ), 'c-ip-services__image' . ( $settings['filter'] == 'yes' ? ' c-ip-services__image--filter' : '' ) );
										}
									}
									?>
									<?php if ( $is_link ) { ?>
								</a><?php } ?>
							</div>
							<div
								class="c-ip-services__content<?php if ( ! $is_long ) { ?> c-ip-services__content--short<?php } ?>">
								<?php if ( ! empty( $item['title_text'] ) ) { ?>
									<div class="c-ip-services__title">
										<?php if ( $is_link ) { ?>
										<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php } ?>
											<?php echo $item['title_text']; ?>
											<?php if ( $is_link ) { ?>
										</a><?php } ?>
									</div>
								<?php } ?>
								<?php if ( ! empty( $item['description_text'] ) ) { ?>
									<div
										class="c-ip-services__description"><?php echo $item['description_text']; ?></div>
								<?php } ?>
								<div class="c-ip-services__space"></div>

								<?php if ( $is_link && $item['button_text'] ) {
									$this->add_render_attribute( $link_key, 'class', 'c-ip-services__button c-button c-button--outline' );
									?>
									<div class="c-ip-services__button-wrap">
										<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
											<?php echo esc_html( $item['button_text'] ); ?>
										</a>
									</div>
								<?php } ?>
							</div>

						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render services widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
