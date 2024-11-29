<?php

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor team widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Team extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve team widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-team';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve team widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Team Members', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve team widget icon.
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
		return [ 'team', 'members', 'list' ];
	}

	/**
	 * Register team widget controls.
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
				'label' => __( 'Team Members', 'ideapark-luchiana' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Choose Photo', 'ideapark-luchiana' ),
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
			'name',
			[
				'label'       => __( 'Name', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'John Doe', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter Name', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'occupation',
			[
				'label'       => __( 'Occupation', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Manager', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter Occupation', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'       => __( 'Description', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter Description', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'phone',
			[
				'label'       => __( 'Phone', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Phone', 'ideapark-luchiana' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'email',
			[
				'label'       => __( 'Email', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Email', 'ideapark-luchiana' ),
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

		$repeater->add_control(
			'separator',
			[
				'label'     => __( 'Social links', 'ideapark-luchiana' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'soc-facebook',
			[
				'label'       => __( 'Facebook url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-instagram',
			[
				'label'       => __( 'Instagram url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-vk',
			[
				'label'       => __( 'VK url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-ok',
			[
				'label'       => __( 'OK url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-telegram',
			[
				'label'       => __( 'Telegram url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-whatsapp',
			[
				'label'       => __( 'Whatsapp url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-twitter',
			[
				'label'       => __( 'Twitter url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-youtube',
			[
				'label'       => __( 'YouTube url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-vimeo',
			[
				'label'       => __( 'Vimeo url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-linkedin',
			[
				'label'       => __( 'LinkedIn url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-flickr',
			[
				'label'       => __( 'Flickr url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-tumblr',
			[
				'label'       => __( 'Tumblr url', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'https://your-link.com', 'ideapark-luchiana' ),
			]
		);

		$repeater->add_control(
			'soc-github',
			[
				'label'       => __( 'Github url', 'ideapark-luchiana' ),
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
				'default'     => [
					[
						'name'       => __( 'Name #1', 'ideapark-luchiana' ),
						'occupation' => __( 'Manager', 'ideapark-luchiana' ),
					],
					[
						'name'       => __( 'Name #2', 'ideapark-luchiana' ),
						'occupation' => __( 'Manager', 'ideapark-luchiana' ),
					],
					[
						'name'       => __( 'Name #3', 'ideapark-luchiana' ),
						'occupation' => __( 'Manager', 'ideapark-luchiana' ),
					],
				],
				'title_field' => '{{{ name }}}',
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
			'text_color',
			[
				'label'     => __( 'Text color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-ip-team__item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Background color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .c-ip-team__item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => __( 'Border color', 'ideapark-luchiana' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .c-ip-team__content'    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .c-ip-team__thumb-wrap' => 'background-color: {{VALUE}};',
				],
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
				'label_on'     => __( 'Yes', 'ideapark-luchiana' ),
				'label_off'    => __( 'No', 'ideapark-luchiana' ),
				'return_value' => 'yes',
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
	 * Render team widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$count          = sizeof( $settings['icon_list'] );
		if ( $count > $settings['viewed_items'] ) {
			$count = $settings['viewed_items'];
		}
		if ( $count > 4 ) {
			$count = 4;
		}
		$carousel_class = $settings['type'] == 'carousel' ? ( $settings['auto_width'] != 'yes' ? ' c-ip-team__list--' . $count : '' ) . ' js-team-carousel h-carousel h-carousel--default-dots h-carousel--flex h-carousel--' . esc_attr( $settings['arrows_color'] ) . ' h-carousel--border ' . ( $settings['dots'] != 'yes' ? 'h-carousel--dots-hide' : 'c-ip-team__list--dots' ) . ' ' . ( $settings['loop'] == 'yes' ? 'h-carousel--loop' : '' ) : '';
		?>
		<div class="c-ip-team c-ip-team--<?php echo $settings['layout']; ?>">
			<div class="c-ip-team__wrap c-ip-team__wrap--<?php echo $settings['layout']; ?>">
				<div
					class="c-ip-team__list c-ip-team__list--<?php echo $settings['layout']; ?> c-ip-team__list--<?php echo $settings['type']; ?> <?php echo $carousel_class; ?>">
					<?php
					foreach ( $settings['icon_list'] as $index => $item ) { ?>

						<?php if ( ! empty( $item['link']['url'] ) ) {
							$is_link  = true;
							$link_key = 'link_' . $index;

							$this->add_link_attributes( $link_key, $item['link'] );
							$this->add_render_attribute( $link_key, 'class', 'c-ip-team__link' );
						} else {
							$is_link = false;
						} ?>

						<div class="c-ip-team__item c-ip-team__item--<?php echo $settings['layout']; ?>">
							<div
								class="c-ip-team__thumb-wrap c-ip-team__thumb-wrap--<?php echo $settings['layout']; ?>">
								<?php if ( $is_link ) { ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
									<?php } ?>
									<?php
									if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) {
										if ( $type == 'image/svg+xml' ) {
											echo ideapark_get_inline_svg( $item['image']['id'], 'c-ip-team__svg' );
										} else {
											echo ideapark_img( ideapark_image_meta( $item['image']['id'], $settings['layout'] == 'layout-1' ? 'medium_large' : 'medium' ), 'c-ip-team__image' );
										}
									} ?>
									<?php if ( $is_link ) { ?>
								</a>
							<?php } ?>
							</div>

							<div class="c-ip-team__content">
								<?php if ( $is_link ) { ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
									<?php } ?>
									<?php if ( ! empty( $item['name'] ) ) { ?>
										<div class="c-ip-team__name"><?php echo esc_html( $item['name'] ); ?></div>
									<?php } ?>
									<?php if ( $is_link ) { ?>
								</a>
							<?php } ?>
								<?php if ( ! empty( $item['occupation'] ) ) { ?>
									<?php if ( ! empty( $item['name'] ) ) { ?>
										<i class="ip-romb c-ip-team__separator"></i>
									<?php } ?>
									<div
										class="c-ip-team__occupation"><?php echo esc_html( $item['occupation'] ); ?></div>
								<?php } ?>
								<?php if ( ! empty( $item['description'] ) ) { ?>
									<div class="c-ip-team__description"><?php echo $item['description']; ?></div>
								<?php } ?>
								<?php
								$soc_count = 0;
								ob_start();
								foreach ( $item as $item_index => $row ) {
									if ( strpos( $item_index, 'soc-' ) !== false && ! empty( $item[ $item_index ]['url'] ) ) {
										$soc_count ++;

										$link_key = 'link_' . $index . '_' . $item_index;

										$this->add_link_attributes( $link_key, $item[ $item_index ] );
										$this->add_render_attribute( $link_key, 'class', 'c-ip-team__soc-link' );

										$soc_index = str_replace( 'soc-', '', $item_index );
										?>
										<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><i
												class="ip-<?php echo esc_attr( $soc_index ) ?> c-ip-team__soc-icon c-ip-team__soc-icon--<?php echo esc_attr( $soc_index ) ?>">
												<!-- --></i></a>
									<?php };
								}
								$content = ob_get_clean();
								?>
								<?php echo ideapark_wrap( $content, '<div class="c-ip-team__soc">', '</div>' ) ?>
								<?php if ( ! empty( $item['phone'] ) || ! empty( $item['email'] ) ) { ?>
									<div class="c-ip-team__bottom-row">
										<?php if ( ! empty( $item['phone'] ) ) { ?>
											<?php echo ideapark_phone_wrap( $item['phone'], '<div class="c-ip-team__bottom-col c-ip-team__bottom-row--phone"><i class="ip-z-phone c-ip-team__bottom-icon c-ip-team__bottom-icon--phone"></i><span class="c-ip-team__bottom-text">', '</span></div>' ); ?>
										<?php } ?>
										<?php if ( ! empty( $item['email'] ) ) { ?>
											<div
												class="c-ip-team__bottom-col c-ip-team__bottom-row--email"><i
													class="ip-email c-ip-team__bottom-icon c-ip-team__bottom-icon--email"></i><span
													class="c-ip-team__bottom-text"><?php echo make_clickable( esc_html( $item['email'] ) ); ?></span>
											</div>
										<?php } ?>
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
	 * Render team widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
