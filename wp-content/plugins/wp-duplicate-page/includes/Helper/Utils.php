<?php
namespace NjtDuplicate\Helper;

defined( 'ABSPATH' ) || exit;

class Utils {

	public static function isCurrentUserAllowedToCopy() {
		return current_user_can( 'njt_duplicate_page' );
	}

	public static function checkPostTypeDuplicate( $postType ) {
		$duplicatePostTypes = get_option( 'njt_duplicate_post_types', array( 'post', 'page' ) );
		if ( ! is_array( $duplicatePostTypes ) ) {
			$duplicatePostTypes = array( $duplicatePostTypes );
		}
		return in_array( $postType, $duplicatePostTypes );
	}
}

