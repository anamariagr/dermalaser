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
 * Elementor pricing widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Ideapark_Elementor_Pricing extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve pricing widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'ideapark-pricing';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve pricing widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Luchiana Price List', 'ideapark-luchiana' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve pricing widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-price-list';
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
		return [ 'pricing', 'service', 'list' ];
	}

	/**
	 * Register pricing widget controls.
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
				'label' => __( 'Pricing', 'ideapark-luchiana' ),
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
			'price',
			[
				'label'       => __( 'Price', 'ideapark-luchiana' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '$00.00', 'ideapark-luchiana' ),
				'placeholder' => __( 'Enter your price', 'ideapark-luchiana' ),
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
	}

	/**
	 * Render pricing widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="c-ip-pricing">
			<div class="c-ip-pricing__wrap">
				<div class="c-ip-pricing__list">
					<?php
					foreach ( $settings['icon_list'] as $index => $item ) { ?>
						<div class="c-ip-pricing__item">
							<?php
							$with_thimb = false;
							if ( ! empty( $item['link']['url'] ) ) {
								$is_link  = true;
								$link_key = 'link_' . $index;

								$this->add_link_attributes( $link_key, $item['link'] );
								$this->add_render_attribute( $link_key, 'class', 'c-ip-pricing__link' );
							} else {
								$is_link = false;
							} ?>
							<?php if ( ! empty( $item['image']['id'] ) && ( $type = get_post_mime_type( $item['image']['id'] ) ) ) { ?>
								<div class="c-ip-pricing__thumb">
									<?php if ( $is_link ) { ?>
									<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php } ?>
										<?php
										if ( $type == 'image/svg+xml' ) {
											echo ideapark_get_inline_svg( $item['image']['id'], 'c-ip-pricing__svg' );
										} else {
											echo ideapark_img( ideapark_image_meta( $item['image']['id'], 'thumbnail' ), 'c-ip-pricing__image' );
										}
										$with_thimb = true;
										?>
										<?php if ( $is_link ) { ?>
									</a><?php } ?>
								</div>
							<?php } ?>
							<div class="c-ip-pricing__content">
								<div class="c-ip-pricing__top-row">
									<?php if ( ! empty( $item['title_text'] ) ) { ?>
										<div class="c-ip-pricing__title">
											<?php if ( $is_link ) { ?>
											<a <?php echo $this->get_render_attribute_string( $link_key ); ?>><?php } ?>
												<?php echo $item['title_text']; ?>
												<?php if ( $is_link ) { ?>
											</a><?php } ?>
										</div>
									<?php } ?>
									<?php if ( ! empty( $item['price'] ) ) { ?>
										<div
											class="c-ip-pricing__price c-ip-pricing__price--desktop"><?php echo esc_html( $item['price'] ); ?></div>
									<?php } ?>
								</div>
								<?php if ( ! empty( $item['price'] ) ) { ?>
									<div
										class="c-ip-pricing__price c-ip-pricing__price--mobile"><?php echo esc_html( $item['price'] ); ?></div>
								<?php } ?>
								<?php if ( ! empty( $item['description_text'] ) ) { ?>
									<div
										class="c-ip-pricing__description<?php if ($with_thimb) { ?> c-ip-pricing__description-with-thumb<?php } ?>"><?php echo $item['description_text']; ?></div>
								<?php } ?>
								<div class="c-ip-pricing__space"></div>

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
	 * Render pricing widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
