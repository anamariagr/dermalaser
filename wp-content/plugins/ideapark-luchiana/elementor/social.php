<?php

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Ideapark_Elementor_Social extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'ideapark-social';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return esc_html__( 'Social', 'ideapark-luchiana' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-social-icons';
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
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_social',
			[
				'label' => __( 'Social Media Links', 'ideapark-luchiana' ),
			]
		);

		foreach ( ideapark_social_networks() as $code => $name ) {
			$this->add_control(
				'soc-' . $code,
				[
					'label'       => sprintf( __( '%s url', 'ideapark-luchiana' ), $name ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
				]
			);
		}

		for ( $i = 1; $i <= max( 1, (int) apply_filters( 'ideapark_custom_soc_count', 2 ) ); $i ++ ) {
			$this->add_control(
				'custom-soc-icon-' . $i,
				[
					'label'                  => __( 'Custom icon', 'ideapark-luchiana' ) . ' #' . $i,
					'type'                   => Controls_Manager::ICONS,
					'label_block'            => true,
					'default'                => [
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					],
					'fa4compatibility'       => 'icon',
					'skin'                   => 'inline',
					'exclude_inline_options' => [ 'svg' ],
				]
			);


			$this->add_control(
				'custom-soc-url-' . $i,
				[
					'label'       => __( 'Custom url', 'ideapark-luchiana' ) . ' #' . $i,
					'type'        => Controls_Manager::URL,
					'placeholder' => '',
					'label_block' => true,
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Widget Settings', 'ideapark-luchiana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Icons Color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-ip-social__link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label'     => __( 'Icons Color on Hover', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .c-ip-social__link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon size', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 16,
				],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 30,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-social' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label'      => __( 'Space', 'ideapark-luchiana' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 30,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'devices'    => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} .c-ip-social__icon' => 'margin: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .c-ip-social'       => 'margin: calc(-{{SIZE}}{{UNIT}} / 2);'
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'        => __( 'Alignment', 'ideapark-luchiana' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'    => [
						'title' => __( 'Left', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'ideapark-luchiana' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$networks = ideapark_social_networks();
		ob_start();
		foreach ( $settings as $item_index => $row ) {
			if ( strpos( $item_index, 'soc-' ) !== false && ! empty( $row['url'] ) ) {
				$soc_index = str_replace( 'soc-', '', $item_index );

				if ( array_key_exists( $soc_index, $networks ) ) {
					$link_key = 'link_' . $item_index;

					$this->add_link_attributes( $link_key, $row );
					$this->add_render_attribute( $link_key, 'class', 'c-ip-social__link' );
					$this->add_render_attribute( $link_key, 'aria-label', ideapark_social_networks()[ $soc_index ] );
					?>
					<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><i
							class="ip-<?php echo esc_attr( $soc_index ) ?> c-ip-social__icon c-ip-social__icon--<?php echo esc_attr( $soc_index ) ?>">
							<!-- --></i></a>
				<?php }
			}
		}
		for ( $i = 1; $i <= max( 1, (int) apply_filters( 'ideapark_custom_soc_count', 2 ) ); $i ++ ) {
			if ( ! empty( $settings[ 'custom-soc-icon-' . $i ] ) && ! empty( $settings[ 'custom-soc-url-' . $i ]['url'] ) ) {
				$link_key = 'link_custom_' . $i;
				$host     = parse_url( $settings[ 'custom-soc-url-' . $i ]['url'], PHP_URL_HOST );

				$this->add_link_attributes( $link_key, $settings[ 'custom-soc-url-' . $i ] );
				$this->add_render_attribute( $link_key, 'class', 'c-ip-social__link' );
				$this->add_render_attribute( $link_key, 'aria-label', $host );
				?>
				<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php
					Icons_Manager::render_icon( $settings[ 'custom-soc-icon-' . $i ], [
						'aria-hidden' => 'true',
						'class'       => ' c-ip-social__icon c-ip-social__icon--' . preg_replace( '~[^a-z0-9]~', '-', strtolower( $host ?: ( 'custom-' . $i ) ) )
					] );
					?></a>
				<?php
			}
		}
		$content = ob_get_clean();
		echo ideapark_wrap( $content, '<div class="c-ip-social">', '</div>' );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {

	}
}
