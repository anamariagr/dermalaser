<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/success.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version      10.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $messages ) && ! empty( $notices ) ) {
	$messages = [];
	foreach ( $notices as $notice ) {
		$messages[] = $notice['notice'];
	}
}

if ( empty( $messages ) ) {
	return;
}

?>
<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-notice" role="alert">
		<i class="ip-wc-success woocommerce-notice-success-svg"></i>
		<?php echo wc_kses_notice( $message ); ?>
		<button class="h-cb h-cb--svg woocommerce-notice-close js-notice-close" type="button"><i
				class="ip-close-small woocommerce-notice-close-svg"></i></button>
	</div>
<?php endforeach; ?>
