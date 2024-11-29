<?php

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Ideapark_Elementor_Page_Header extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'ideapark-page-header';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return esc_html__( 'Page Header', 'ideapark-luchiana' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'ip-page-header';
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
		global $post;
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'custom_title',
			[
				'label'       => esc_html__( 'Custom Title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type your title text here', 'ideapark-luchiana' ),
			]
		);

		$this->add_control(
			'hide_breadcrumbs',
			[
				'label'        => esc_html__( 'Hide breadcrumbs', 'ideapark-luchiana' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'global_header',
			[
				'label' => esc_html__( 'Global height, color and background settings', 'ideapark-luchiana' ),
				'type'  => \Elementor\Controls_Manager::RAW_HTML,
				'raw'   => '<a target="_blank" href="' . esc_url( admin_url( 'customize.php?autofocus[control]=header_bg_page' ) ) . '">' . __( 'Manage', 'ideapark-luchiana' ) . '</a>',
			]
		);

		if ( ! empty( $post->ID ) ) {
			$this->add_control(
				'custom_header',
				[
					'label' => esc_html__( 'Page custom height, color and background settings', 'ideapark-luchiana' ),
					'type'  => \Elementor\Controls_Manager::RAW_HTML,
					'raw'   => '<a target="_blank" href="' . esc_url( admin_url( 'post.php?post=' . $post->ID . '&action=edit#page-header' ) ) . '">' . __( 'Manage', 'ideapark-luchiana' ) . '</a>',
				]
			);
		}
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings();
		$params   = [];
		if ( ! empty( $settings['custom_title'] ) ) {
			$params['title'] = $settings['custom_title'];
		}
		if ( ! empty( $settings['hide_breadcrumbs'] ) ) {
			$params['hide_breadcrumbs'] = $settings['hide_breadcrumbs'];
		}
		ideapark_get_template_part( 'templates/page-header', $params );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {

	}
}
