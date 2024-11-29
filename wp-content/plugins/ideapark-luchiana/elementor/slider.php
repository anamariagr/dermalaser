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
 * Elementor slider widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Slider extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve slider widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-slider';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve slider widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Slider Carousel', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-slides';
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
		return [ 'carousel', 'slider' ];
	}

	/**
	 * Register slider widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_slider_list',
			[
				'label' => __( 'Slides', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image_desktop',
			[
				'label'   => __( 'Image (Desktop)', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'image_mobile',
			[
				'label'   => __( 'Image (Mobile)', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'text_above',
			[
				'label'       => __( 'Text above the title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter text', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter title', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'       => __( 'Description', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter description', 'ideapark-luchiana' ),
				'separator'   => 'none',
				'rows'        => 5,
				'show_label'  => false,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'       => __( 'Button text', 'ideapark-luchiana' ),
				'default'     => __( 'Read more', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter title', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label'     => __( 'Button link', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::URL,
				'default'   => [
					'url' => '#',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'button_type',
			[
				'label'   => __( 'Button type', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'ideapark-luchiana' ),
					'outline' => __( 'Outline', 'ideapark-luchiana' ),
				]
			]
		);

		$repeater->add_control(
			'background_color',
			[
				'label'     => __( 'Background Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}'                          => 'background-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--outline:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$repeater->add_control(
			'custom_color',
			[
				'label'     => __( 'Custom Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-ip-slider__title--full'      => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-ip-slider__text-above--full' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-ip-slider__description'      => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--outline'             => 'color: {{VALUE}} !important; border-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--outline:hover'       => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--default'             => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .c-button--default:hover'       => 'opacity: 0.6;',
				],
			]
		);

		$this->add_control(
			'slider_list',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => __( 'Slider Settings', 'ideapark-luchiana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'full'  => __( 'Fullwidth', 'ideapark-luchiana' ),
					'boxed' => __( 'Boxed', 'ideapark-luchiana' ),
				]
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
						'max' => 1160,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-slider__wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Text alignment', 'ideapark-luchiana' ),
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
				'default' => '',
			]
		);

		$this->add_control(
			'vertical_align',
			[
				'label'   => __( 'Vertical Alignment (mobile)', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'start'  => [
						'title' => __( 'Top', 'ideapark-luchiana' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'ideapark-luchiana' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'end'    => [
						'title' => __( 'Bottom', 'ideapark-luchiana' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default' => '',
			]
		);

		$this->add_control(
			'slider_animation',
			[
				'label'   => __( 'Animation', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''               => __( 'Default', 'ideapark-luchiana' ),
					'banners-fade'   => __( 'Fade', 'ideapark-luchiana' ),
					'owl-fade-scale' => __( 'Fade and Scale', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label'     => __( 'Autoplay', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off' => esc_html__( 'No', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'slider_animation_timeout',
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
					'slider_autoplay' => 'yes',
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
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off' => esc_html__( 'Hide', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'random',
			[
				'label'     => __( 'Random sorting', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'Yes', 'ideapark-luchiana' ),
				'label_off' => __( 'No', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'fixed_height',
			[
				'label'     => __( 'Slider fixed height', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off' => esc_html__( 'No', 'ideapark-luchiana' ),
				'condition' => [
					'layout' => 'full',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'      => __( 'Height', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1280,
					],
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-slider__item--full' => 'height: {{SIZE}}{{UNIT}};min-height:unset;max-height: unset;',
				],

				'condition' => [
					'fixed_height' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'font_size_text_above_title',
			[
				'label'       => __( 'Custom subtitle font size', 'ideapark-luchiana' ),
				'description' => __( 'Text above the title', 'ideapark-luchiana' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 10,
						'max' => 30,
					]
				],
				'devices'     => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-slider__text-above' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'font_size',
			[
				'label'      => __( 'Custom title font size', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 100,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-slider__title' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_colors',
			[
				'label' => __( 'Colors', 'ideapark-luchiana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_dark',
			[
				'label'     => __( 'Color (dark)', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .c-ip-slider' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_light',
			[
				'label'     => __( 'Color (light)', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .c-ip-slider__title--full'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .c-ip-slider__text-above--full' => 'color: {{VALUE}};',
					'{{WRAPPER}} .c-ip-slider__scroll'           => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_dots',
			[
				'label'     => __( 'Arrows Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .h-carousel--inner .owl-prev' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .h-carousel--inner .owl-next' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'color_nav',
			[
				'label'     => __( 'Navigation Dots Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .owl-dots'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .c-ip-slider__circle' => 'color: {{VALUE}};',
					'{{WRAPPER}} .c-ip-slider__scroll' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_button',
			[
				'label'     => __( 'Button Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-button--default'       => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .c-button--default:hover' => 'opacity: 0.6;',
					'{{WRAPPER}} .c-button--outline'       => 'color: {{VALUE}} !important;border-color: {{VALUE}};',
					'{{WRAPPER}} .c-button--outline:hover' => 'background-color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	private function _align_class( $settings, $class_prefix ) {
		$a = [];
		if ( ! empty( $settings['align'] ) ) {
			$a[] = $class_prefix . $settings['align'];
		}
		if ( ! empty( $settings['align_tablet'] ) ) {
			$a[] = $class_prefix . 'tablet-' . $settings['align_tablet'];
		}
		if ( ! empty( $settings['align_mobile'] ) ) {
			$a[] = $class_prefix . 'mobile-' . $settings['align_mobile'];
		}

		echo implode( ' ', $a );
	}

	/**
	 * Render slider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div
			class="c-ip-slider <?php $this->_align_class( $settings, 'c-ip-slider--align-' ) ?> <?php if ( $settings['fixed_height'] == 'yes' ) { ?> c-ip-slider--fixed-height<?php } ?><?php if ( $settings['layout'] == 'boxed' ) { ?> l-section__container<?php } ?> c-ip-slider--<?php echo ideapark_mod( 'header_type' ); ?> c-ip-slider--<?php echo $settings['layout']; ?> js-slider">
			<div
				class="c-ip-slider__list c-ip-slider__list--<?php echo $settings['layout']; ?> js-slider-carousel h-carousel h-carousel--big-dots <?php if ( $settings['layout'] == 'full' ) { ?>h-carousel--inner<?php } else { ?>h-carousel--round-light h-carousel--outside<?php } ?> h-carousel--hover <?php if ( $settings['dots'] != 'yes' ) { ?> h-carousel--dots-hide<?php } else { ?> c-ip-slider__list--dots <?php if ( $settings['slider_autoplay'] == 'yes' ) { ?> h-carousel--dot-animated <?php } ?><?php } ?> <?php if ( $settings['arrows'] != 'yes' ) { ?> h-carousel--nav-hide<?php } ?>"
				data-autoplay="<?php echo esc_attr( $settings['slider_autoplay'] ); ?>"
				data-animation="<?php echo esc_attr( $settings['slider_animation'] ); ?>"
				<?php if ( ! empty( $settings['slider_animation_timeout']['size'] ) ) { ?>
					data-animation-timeout="<?php echo esc_attr( abs( $settings['slider_animation_timeout']['size'] * 1000 ) ); ?>"
				<?php } ?>
				data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>">
				<?php
				if ( $settings['random'] == 'yes' ) {
					shuffle( $settings['slider_list'] );
				}
				?>
				<?php foreach ( $settings['slider_list'] as $index => $item ) { ?>
					<?php $dot = $settings['slider_autoplay'] == 'yes' ? '<svg role="button" data-index="' . $index . '" class="c-ip-slider__circle" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid"><path d="M0,0 " id="arc-' . $this->get_id() . '-' . $index . '" fill="none" stroke="inherit" stroke-width="2"/></svg><button role="button" class="h-cb c-ip-slider__dot" ></button>' : '<button role="button" class="h-cb c-ip-slider__dot" ></button>'; ?>
					<div
						class="c-ip-slider__item <?php $this->_align_class( $settings, 'c-ip-slider__item--align-' ) ?> <?php if ( $settings['vertical_align'] ) { ?> c-ip-slider__item--v-align-<?php echo $settings['vertical_align']; ?><?php } ?> c-ip-slider__item--<?php echo $settings['layout']; ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>"
						data-dot="<?php echo esc_attr( $dot ); ?>"
						data-index="<?php echo esc_attr( $index ); ?>">
						<?php
						$has_desktop_image = ! empty( $item['image_desktop']['id'] );
						$has_mobile_image  = ! empty( $item['image_mobile']['id'] );
						if ( $has_desktop_image ) {
							echo ideapark_img( ideapark_image_meta( $item['image_desktop']['id'], 'full' ), 'c-ip-slider__image c-ip-slider__image--' . $settings['layout'] . ( $has_mobile_image ? ' c-ip-slider__image--desktop' : '' ), $index && ideapark_mod( 'lazyload' ) ?: 'eager', [ 'data-index' => $index ] );
						}
						if ( $has_mobile_image ) {
							echo ideapark_img( ideapark_image_meta( $item['image_mobile']['id'], 'full' ), 'c-ip-slider__image c-ip-slider__image--' . $settings['layout'] . ' c-ip-slider__image--mobile', $index && ideapark_mod( 'lazyload' ) ?: 'eager', [ 'data-index' => $index ] );
						}

						if ( ! empty( $item['button_link']['url'] ) ) {
							$link_key = 'link_' . $index;
							$this->add_link_attributes( $link_key, $item['button_link'] );
							if ( $item['button_text'] ) {
								$this->add_render_attribute( $link_key, 'class', 'c-button c-button--' . $item['button_type'] . ' c-ip-slider__button c-ip-slider__button--' . $settings['layout'] );
							} else {
								$this->add_render_attribute( $link_key, 'class', 'c-ip-slider__link' );
							}
						} else {
							$link_key = '';
						}
						?>
						<div class="c-ip-slider__wrap c-ip-slider__wrap--<?php echo $settings['layout']; ?>">
							<?php echo ideapark_wrap( $item['text_above'], '<div class="c-ip-slider__text-above c-ip-slider__text-above--' . $settings['layout'] . '"><span class="c-ip-slider__text-above-inner">', '</span></div>' ); ?>
							<?php echo ideapark_wrap( $item['title'], '<div class="c-ip-slider__title c-ip-slider__title--' . $settings['layout'] . '"><span class="c-ip-slider__title-inner">', '</span></div>' ); ?>
							<?php echo ideapark_wrap( $item['description'], '<div class="c-ip-slider__description c-ip-slider__description--' . $settings['layout'] . '">', '</div>' ); ?>
							<?php if ( $link_key && $item['button_text'] ) { ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php echo esc_html( $item['button_text'] ); ?></a>
							<?php } ?>
						</div>
						<?php if ( $link_key && ! $item['button_text'] ) { ?>
							<a <?php echo $this->get_render_attribute_string( $link_key ); ?>></a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php if ( $settings['layout'] == 'full' && $settings['fixed_height'] != 'yes' ) { ?>
				<svg class="c-ip-slider__scroll c-ip-slider__scroll--<?php echo $settings['layout']; ?>"
				     xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision"
				     text-rendering="geometricPrecision" width="26" height="33">
					<style>@keyframes e2xlmpnkgrry3_to__to {
							   0% {
								   transform:                 translate(0, 0);
								   animation-timing-function: cubic-bezier(.54, .01, .46, 1.005)
							   }
							   46.666667% {
								   transform:                 translate(0, 6px);
								   animation-timing-function: cubic-bezier(.54, .01, .46, 1.005)
							   }
							   to {
								   transform: translate(0, 0)
							   }
						   }</style>
					<rect id="e2xlmpnkgrry2" width="24" height="31" rx="12" ry="12" transform="translate(1 1)"
					      fill="none"
					      stroke="inherit" stroke-width="1.8" stroke-opacity=".2"/>
					<g style="animation:e2xlmpnkgrry3_to__to 1500ms linear infinite normal forwards">
						<g id="e2xlmpnkgrry3" fill="none" stroke="inherit" stroke-linecap="round">
							<path id="e2xlmpnkgrry4" d="M16 17l-3 3-3-3"/>
							<path id="e2xlmpnkgrry5" d="M13 19v-7"/>
						</g>
					</g>
				</svg>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render slider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
