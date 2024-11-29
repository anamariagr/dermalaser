<?php

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor gift widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Gift extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve gift widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-gift';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve gift widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Gift Certificates', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve gift widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'ip-z-image-box';
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
		return [ 'gift', 'members', 'list' ];
	}

	/**
	 * Register gift widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_members',
			[
				'label' => __( 'Gift Certificates', 'ideapark-luchiana' ),
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
			'title',
			[
				'label'       => __( 'Title', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter title', 'ideapark-luchiana' )
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'       => __( 'Description', 'ideapark-luchiana' ),
				'type'        => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true
			]
		);

		$repeater->add_control(
			'price',
			[
				'label'       => __( 'Price', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter price', 'ideapark-luchiana' )
			]
		);

		$repeater->add_control(
			'discount_price',
			[
				'label'       => __( 'Discount price', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter discount price', 'ideapark-luchiana' )
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'       => __( 'Button text', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Buy now', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter text', 'ideapark-luchiana' )
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
			'badge_text',
			[
				'label'       => __( 'Badge text', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Enter text', 'ideapark-luchiana' )
			]
		);

		$this->add_control(
			'gift_list',
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
			'type',
			[
				'label'   => __( 'Type', 'ideapark-luchiana' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'     => __( 'Grid', 'ideapark-luchiana' ),
					'carousel' => __( 'Carousel', 'ideapark-luchiana' ),
				]
			]
		);

		$this->add_control(
			'auto_width',
			[
				'label'     => __( 'Auto width', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
				'condition' => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'viewed_items',
			[
				'label'     => __( 'Viewed items', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'condition' => [
					'type'       => 'carousel',
					'auto_width' => 'no',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => __( 'Arrows', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'round-light',
				'options'   => [
					'round'       => __( 'Dark', 'ideapark-luchiana' ),
					'round-light' => __( 'Light', 'ideapark-luchiana' ),
					'round-white' => __( 'White', 'ideapark-luchiana' ),
					'nav-hide'    => __( 'Hide', 'ideapark-luchiana' ),
				],
				'condition' => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'dots',
			[
				'label'     => __( 'Navigation dots', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'     => esc_html__( 'Show', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'Hide', 'ideapark-luchiana' ),
				'condition' => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => __( 'Enable Loop', 'ideapark-luchiana' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
				'condition'    => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_autoplay',
			[
				'label'     => __( 'Autoplay', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'     => esc_html__( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => esc_html__( 'No', 'ideapark-luchiana' ),
				'condition' => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_animation_timeout',
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
					'type'              => 'carousel',
					'carousel_autoplay' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render gift widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$count          = sizeof( $settings['gift_list'] );
		if ( $count > $settings['viewed_items'] ) {
			$count = $settings['viewed_items'];
		}
		if ( $count > 4 ) {
			$count = 4;
		}
		$carousel_class = $settings['type'] == 'carousel' ? ( $settings['auto_width'] != 'yes' ? ' c-ip-gift__list--' . $count : '' ) . ' js-gift-carousel h-carousel h-carousel--default-dots h-carousel--flex h-carousel--' . esc_attr( $settings['arrows_color'] ) . ' h-carousel--border ' . ( $settings['dots'] != 'yes' ? 'h-carousel--dots-hide' : 'c-ip-gift__list--dots' ) . ' ' . ( $settings['loop'] == 'yes' ? 'h-carousel--loop' : '' ) : '';
		?>
		<div class="c-ip-gift">
			<div class="c-ip-gift__wrap">
				<div
					class="c-ip-gift__list c-ip-gift__list--<?php echo $settings['type']; ?> <?php echo $carousel_class; ?>">
					<?php
					foreach ( $settings['gift_list'] as $index => $item ) { ?>

						<?php if ( ! empty( $item['link']['url'] ) ) {
							$is_link  = true;
							$link_key = 'link_' . $index;

							$this->add_link_attributes( $link_key, $item['link'] );
							$this->add_render_attribute( $link_key, 'class', 'c-ip-gift__link' );
						} else {
							$is_link = false;
						} ?>

						<div class="c-ip-gift__item">
							<div
								class="c-ip-gift__thumb-wrap">
								<?php if ( $is_link ) { ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
									<?php } ?>
									<?php
									if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) {
										if ( $type == 'image/svg+xml' ) {
											echo ideapark_get_inline_svg( $item['image']['id'], 'c-ip-gift__svg' );
										} else {
											echo ideapark_img( ideapark_image_meta( $item['image']['id'], 'medium' ), 'c-ip-gift__image' );
										}
									} ?>
									<?php if ( $item['badge_text'] ) { ?>
										<div class="c-ip-gift__badge">
											<?php echo esc_html( $item['badge_text'] ); ?>
										</div>
									<?php } ?>
									<?php if ( $is_link ) { ?>
								</a>
							<?php } ?>
							</div>

							<div class="c-ip-gift__content">
								<?php if ( $is_link ) { ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
									<?php } ?>
									<?php if ( ! empty( $item['title'] ) ) { ?>
										<div class="c-ip-gift__title"><?php echo esc_html( $item['title'] ); ?></div>
									<?php } ?>
									<?php if ( $is_link ) { ?>
								</a>
							<?php } ?>

								<?php if ( ! empty( $item['description'] ) ) { ?>
									<div
										class="c-ip-gift__description"><?php echo do_shortcode( $item['description'] ); ?></div>
								<?php } ?>

								<div class="c-ip-gift__space"></div>

								<?php if ( ! empty( $item['price'] ) && ! empty( $item['discount_price'] ) ) { ?>
									<div class="c-ip-gift__discount_price"><?php echo $item['price']; ?></div>
								<?php } ?>
								<?php if ( ! empty( $item['price'] ) ) { ?>
									<div
										class="c-ip-gift__price"><?php echo ! empty( $item['discount_price'] ) ? $item['discount_price'] : $item['price']; ?></div>
								<?php } ?>

								<?php if ( $is_link && $item['button_text'] ) {
									$this->add_render_attribute( $link_key, 'class', 'c-ip-gift__button c-button c-button--outline c-button--full' );
									?>
									<div class="c-ip-gift__button-wrap">
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
	 * Render gift widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
