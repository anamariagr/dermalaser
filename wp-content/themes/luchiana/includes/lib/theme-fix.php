<?php

class Luchiana_Theme_Fix {
	public $is_changed = false;

	public function __construct() {
		add_action( 'after_update_theme', [ $this, 'team_widget_fix' ], 10, 1 );
		add_action( 'after_update_theme', [ $this, 'image_post_format_fix' ], 10, 1 );
	}

	public function image_post_format_fix( $old ) {
		global $post;
		if ( version_compare( $old, '4.11', '<' ) ) {
			$posts = new WP_Query( [
				'post_type' => 'post',
				'tax_query' => [
					[
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => [
							'post-format-image','post-format-video','post-format-gallery',
						],
						'operator' => 'NOT IN'
					]
				]
			] );

			if ( $posts->have_posts() ) {
				while ( $posts->have_posts() ) {
					$posts->the_post();
					set_post_format($post->ID, 'image' );
				}
			}
		}
	}

	public function team_widget_fix( $old ) {
		global $wpdb;
		if ( version_compare( $old, '1.13', '<' ) ) {
			$sql = "
			SELECT *
			FROM {$wpdb->postmeta}
			WHERE meta_key = '_elementor_data' OR meta_key = '_elementor_controls_usage'
			";
			if ( $results = $wpdb->get_results( $sql, OBJECT ) ) {
				foreach ( $results as $post_meta ) {
					if ( $post_meta->meta_key == '_elementor_controls_usage' ) {
						$data = unserialize( $post_meta->meta_value );
						if ( array_key_exists( 'medium', $data ) ) {
							$data['ideapark-team'] = $data['medium'];
							unset( $data['medium'] );
							$wpdb->update( $wpdb->postmeta, [
								'meta_value' => serialize( $data )
							], [
								'post_id'  => $post_meta->post_id,
								'meta_key' => $post_meta->meta_key
							] );
						}
					} elseif ( $post_meta->meta_key == '_elementor_data' ) {
						$data = json_decode( $post_meta->meta_value );

						$this->is_changed = false;

						foreach ( $data as &$element ) {
							$this->_elementor_data( $element, 0 );
						}

						if ( $this->is_changed ) {
							$wpdb->update( $wpdb->postmeta, [
								'meta_value' => wp_json_encode( $data )
							], [
								'post_id'  => $post_meta->post_id,
								'meta_key' => $post_meta->meta_key
							] );
						}
					}
				}
			}
		}
	}

	private function _elementor_data( &$data, $level ) {

		$widget_type = $data->elType == 'widget' ? $data->widgetType : '';

		if ( isset( $data->elements ) ) {
			foreach ( $data->elements as &$element ) {
				$this->_elementor_data( $element, $level + 1 );
			}
			unset( $element );
		}

		if ( $widget_type == 'medium' ) {
			$data->widgetType = 'ideapark-team';
			$this->is_changed = true;
		}
	}
}

new Luchiana_Theme_Fix();