<?php

use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Inline Menu widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Inline_Menu extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Inline Menu widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-inline-menu';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Inline Menu widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Inline Menu', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Inline Menu widget icon.
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
		return [ 'inline', 'menu' ];
	}

	/**
	 * Register Inline Menu widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_inline_menu',
			[
				'label' => __( 'Inline Menu', 'ideapark-luchiana' ),
			]
		);

		$options = [
			'' => __( '— Select —', 'ideapark-luchiana' )
		];
		if ( $menus = wp_get_nav_menus() ) {
			foreach ( $menus as $menu ) {
				$options[ $menu->term_id ] = $menu->name;
			}
		}

		$this->add_control(
			'menu',
			[
				'label'   => __( 'Select Menu', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $options
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
				'description'      => __( 'Dot if nothing is selected', 'ideapark-luchiana' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'icon'
			]
		);

		$this->add_responsive_control(
			'items_space',
			[
				'label'      => __( 'Space', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 60,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-inline-menu svg'        => 'margin-bottom: calc({{SIZE}}{{UNIT}} * 0.6);',
					'{{WRAPPER}} .c-ip-inline-menu__separator' => 'margin-bottom: calc({{SIZE}}{{UNIT}} * 0.6);',
					'{{WRAPPER}} .c-ip-inline-menu__item'      => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2);margin-bottom: calc({{SIZE}}{{UNIT}} * 0.6);',
					'{{WRAPPER}} .c-ip-inline-menu'            => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-bottom: calc(-{{SIZE}}{{UNIT}} * 0.6);',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'    => [
						'title' => __( 'Left', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'        => [
						'title' => __( 'Center', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'      => [
						'title' => __( 'Right', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => __( 'Justified', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .c-ip-inline-menu' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Separator Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-ip-inline-menu__separator' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-ip-inline-menu__item a' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'text_color_hover',
			[
				'label'     => __( 'Text and Icon Color on Hover', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ideapark_mod_hex_color_norm( 'background_color', '#ffffff' ),
				'selectors' => [
					'{{WRAPPER}} .c-ip-inline-menu__item a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .c-ip-inline-menu',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .c-ip-inline-menu',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label'     => __( 'Blend Mode', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => __( 'Normal', 'ideapark-luchiana' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .c-ip-inline-menu' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Inline Menu widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( $settings['menu'] ) {
			$items = wp_get_nav_menu_items( $settings['menu'] );
		}
		if ( ! empty( $settings['icon_svg']['value'] ) ) {
			ob_start();
			Icons_Manager::render_icon( $settings['icon_svg'], [
				'aria-hidden' => 'true',
				'class'       => 'c-ip-inline-menu__separator c-ip-inline-menu__separator--icon'
			] );
			$separator = ob_get_clean();
		} else {
			$separator = '<i class="ip-dot c-ip-inline-menu__separator c-ip-inline-menu__separator--dot"></i>';
		}

		if ( $items ) { ?>
			<div class="c-ip-inline-menu">
				<?php
				foreach ( $items as $index => $item ) { ?>
					<?php if ( $item->menu_item_parent == 0 ) { ?>
						<?php if ( $index ) {
							echo $separator;
						} ?>
						<span class="c-ip-inline-menu__item">
						<a href="<?php echo esc_url( $item->url ); ?>">
							<?php echo esc_html( $item->title ); ?>
						</a>
					</span>
					<?php } ?>
				<?php } ?>
			</div>
		<?php }
	}

	/**
	 * Render Inline Menu widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
