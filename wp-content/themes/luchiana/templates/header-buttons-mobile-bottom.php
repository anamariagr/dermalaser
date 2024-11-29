<?php
ob_start();
$mobile_header_buttons_cnt = 0;

$header_blocks = ideapark_parse_checklist( ideapark_mod( 'bottom_buttons_mobile' ) );
foreach ( $header_blocks as $block_index => $enabled ) {
	if ( $enabled ) {
		if ( $block_index == 'search' ) {
			$block_index = 'search-button';
		}
		ideapark_get_template_part( 'templates/header-' . $block_index, [ 'device' => 'mobile' ] );
		$mobile_header_buttons_cnt ++;
	}
}
$content = trim( ob_get_clean() );
if ( $content ) { ?>
	<div class="c-header__menu-bottom c-header__menu-bottom--<?php echo esc_attr( $mobile_header_buttons_cnt ); ?> c-header__menu-bottom--<?php echo ideapark_mod( 'bottom_buttons_mobile_locations' ); ?>">
		<?php echo ideapark_wrap( $content ); ?>
	</div>
<?php }
