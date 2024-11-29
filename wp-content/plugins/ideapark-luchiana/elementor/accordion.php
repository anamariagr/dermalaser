<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Accordion widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Accordion extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Accordion widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-accordion';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Accordion widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Luchiana Accordion', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Accordion widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-accordion';
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
		return [ 'accordion', 'icon', 'list', 'FAQ' ];
	}

	/**
	 * Register Accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_list',
			[
				'label' => __( 'Accordion Items', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter title', 'ideapark-luchiana' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'content',
			[
				'label'       => __( 'Content', 'ideapark-luchiana' ),
				'type'        => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true
			]
		);

		$this->add_control(
			'accordion',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title'   => __( 'Title #1', 'ideapark-luchiana' ),
						'content' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
					],
					[
						'title'   => __( 'Title #2', 'ideapark-luchiana' ),
						'content' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
					],
					[
						'title'   => __( 'Title #3', 'ideapark-luchiana' ),
						'content' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'ideapark-luchiana' ),
					],
				],
				'title_field' => '{{{ title }}}',
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
			'layout',
			[
				'label'   => __( 'Layout', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'layout-1',
				'options' => [
					'layout-1' => __( 'Layout 1', 'ideapark-luchiana' ),
					'layout-2' => __( 'Layout 2', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-ip-accordion__item' => 'color: {{VALUE}};',
				],
				'condition'   => [
					'layout' => 'layout-2',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Accordion widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="c-ip-accordion">
			<div class="c-ip-accordion__wrap c-ip-accordion__wrap--<?php echo $settings['layout']; ?>">
				<div class="c-ip-accordion__list c-ip-accordion__list--<?php echo $settings['layout']; ?>">
					<?php
					foreach ( $settings['accordion'] as $index => $item ) { ?>
					<div class="c-ip-accordion__item c-ip-accordion__item--<?php echo $settings['layout']; ?>">
						<?php echo ideapark_wrap( esc_html( $item['title'] ), '<a class="c-ip-accordion__header c-ip-accordion__header--' . $settings['layout'] . ' js-accordion-title" href="" onclick="return false;">', '<i class="ip-right c-ip-accordion__arrow c-ip-accordion__arrow--' . $settings['layout'] . '"></i></a>' ) ?>
						<?php echo ideapark_wrap( do_shortcode( $item['content'] ), '<div class="c-ip-accordion__content c-ip-accordion__content--' . $settings['layout'] . '">', '</div>' ); ?>
					</div>
					<?php if ( $settings['layout'] == 'layout-2' && $index == ceil( sizeof( $settings['accordion'] ) / 2 - 1) ) { ?>
				</div>
				<div class="c-ip-accordion__list c-ip-accordion__list--<?php echo $settings['layout']; ?>">
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		$structured_data = ideapark_mod( 'wc_structured_data' ) ?: [];
		if ( ! array_key_exists( 'FAQPage', $structured_data ) ) {
			$markup               = [];
			$markup['@type']      = 'FAQPage';
			$markup['mainEntity'] = [];
		} else {
			$markup = $structured_data['FAQPage'];
		}

		foreach ( $settings['accordion'] as $item ) {
			$markup['mainEntity'][] = [
				'@type'          => 'Question',
				'name'           => $item['title'],
				"acceptedAnswer" => [
					"@type" => "Answer",
					"text " => $item['content'],
				],
			];
		}

		$structured_data['FAQPage'] = $markup;
		ideapark_mod_set_temp('wc_structured_data', $structured_data );
	}

	/**
	 * Render Accordion widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
