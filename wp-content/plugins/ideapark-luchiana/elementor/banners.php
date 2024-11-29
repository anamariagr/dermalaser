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
 * Elementor banners widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Banners extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve banners widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-banners';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve banners widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Banners', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve banners widget icon.
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
		return [ 'banners', 'image', 'list' ];
	}

	/**
	 * Register banners widget controls.
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
				'label' => __( 'Banners', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Choose image', 'ideapark-luchiana' ),
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
			'subheader',
			[
				'label'       => __( 'Subheader', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => __( 'Enter banner subheader', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);


		$repeater->add_control(
			'header',
			[
				'label'       => __( 'Header', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Header', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter banner header', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'       => __( 'Button text', 'ideapark-luchiana' ),
				'description' => __( 'Leave empty for a link to the entire block', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter text', 'ideapark-luchiana' ),
				'default'     => __( 'Explore', 'ideapark-luchiana' ),
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

		$repeater->add_control(
			'background_color',
			[
				'label'     => __( 'Background color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}'                          => 'background-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--outline:hover' => 'background-color: {{VALUE}} !important; color:{{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--default'       => 'color: {{VALUE}} !important;',
				],
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label'     => __( 'Text color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}'                          => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--outline'       => 'border-color: {{VALUE}} !important; color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--outline:hover' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--default'       => 'border-color: {{VALUE}} !important; background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'banner_list',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ header }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_settings',
			[
				'label' => __( 'Banners Settings', 'ideapark-luchiana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'font_size',
			[
				'label'      => __( 'Header font size', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 38,
				],
				'range'      => [
					'px' => [
						'min' => 12,
						'max' => 50,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-banners__header' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'max_width',
			[
				'label'      => __( 'Max width of the text block', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-banners__wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label'      => __( 'Min banner height', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 50,
						'max' => 600,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-banners__item' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'vertical_align',
			[
				'label'   => __( 'Vertical Align', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'stretch',
				'options' => [
					'stretch' => __( 'Default', 'ideapark-luchiana' ),
					'middle'  => __( 'Middle', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'horizontal_align',
			[
				'label'   => __( 'Horizontal Align', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
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
				'default' => 'left',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'   => __( 'Button type', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'outline',
				'options' => [
					'default' => __( 'Default', 'ideapark-luchiana' ),
					'outline' => __( 'Outline', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'random_sorting',
			[
				'label'        => __( 'Random sorting', 'ideapark-luchiana' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => __( 'No', 'ideapark-luchiana' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'banner_animation',
			[
				'label'   => __( 'Autoplay', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''                   => __( 'Disabled', 'ideapark-luchiana' ),
					'banners-fade'       => __( 'Fade', 'ideapark-luchiana' ),
					'banners-fade-scale' => __( 'Fade and Scale', 'ideapark-luchiana' ),
					'banners-slide-up'   => __( 'Slide Up', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'banner_animation_timeout',
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
					'banner_animation!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render banners widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="c-ip-banners">
			<div
				class="c-ip-banners__list c-ip-banners__list--<?php echo sizeof( $settings['banner_list'] ); ?><?php if ( $settings['banner_animation'] ) { ?> js-ip-banners<?php } ?>"
				data-animation="<?php echo esc_attr( $settings['banner_animation'] ); ?>"
				data-animation-timeout="<?php echo esc_attr( abs( ! empty( $settings['banner_animation_timeout']['size'] ) ? $settings['banner_animation_timeout']['size'] * 1000 : 1000 ) ); ?>">
				<?php
				if ( $settings['random_sorting'] ) {
					shuffle( $settings['banner_list'] );
				}
				foreach ( $settings['banner_list'] as $index => $item ) : ?>
					<?php
					if ( ! empty( $item['link']['url'] ) ) {
						$is_link  = true;
						$link_key = 'link_' . $index;

						$this->add_link_attributes( $link_key, $item['link'] );
						$this->add_render_attribute( $link_key, 'class', 'c-ip-banners__link' );
					} else {
						$is_link = false;
					}
					$item_id = ( ! empty( $item['image']['id'] ) ? $item['image']['id'] . '-' : '' ) . substr( md5( $item['header'] . ( $is_link ? $item['link']['url'] : '' ) ), 0, 8 );
					?>
					<div
						data-id="<?php echo esc_attr( $item_id ); ?>"
						class="c-ip-banners__item c-ip-banners__item--<?php echo $settings['horizontal_align']; ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<?php if ( $is_link ) { ?>
						<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
							<?php } ?>
							<?php if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) {
								if ( $type == 'image/svg+xml' ) {
									echo ideapark_get_inline_svg( $item['image']['id'], 'c-ip-banners__svg' );
								} else {
									echo ideapark_img( ideapark_image_meta( $item['image']['id'], 'medium_large' ), 'c-ip-banners__image' );
								}
							}
							?>
							<div
								class="c-ip-banners__wrap c-ip-banners__wrap--<?php echo $settings['vertical_align']; ?> c-ip-banners__wrap--<?php echo $settings['horizontal_align']; ?>">
								<?php if ( ! empty( $item['subheader'] ) ) { ?>
									<div
										class="c-ip-banners__subheader"><?php echo nl2br( esc_html( $item['subheader'] ) ); ?></div>
								<?php } ?>
								<?php if ( ! empty( $item['header'] ) ) { ?>
									<div class="c-ip-banners__header"><span
											class="c-ip-banners__header-size"><?php echo nl2br( esc_html( $item['header'] ) ); ?></span>
									</div>
								<?php } ?>
								<?php if ( $settings['vertical_align'] == 'stretch' ) { ?>
									<div class="c-ip-banners__space"></div>
								<?php } ?>
								<?php if ( $is_link && $item['button_text'] ) { ?>
									<span
										class="c-button c-button--<?php echo $settings['button_type']; ?> c-ip-banners__button"><?php echo esc_html( $item['button_text'] ); ?></span>
								<?php } ?>
							</div>
							<?php if ( $is_link ) { ?>
						</a>
					<?php } ?>
					</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render banners widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
