<?php
ob_start();
$header_blocks = ideapark_parse_checklist( ideapark_mod( 'header_buttons' ) );
foreach ( $header_blocks as $block_index => $enabled ) {
	if ( $enabled ) {
		if ( ideapark_mod( 'header_type' ) == 'header-type-2' && $block_index == 'search' ) {
			$block_index = 'search-to-top';
		} elseif ( $block_index == 'search' ) {
			$block_index = 'search-button';
		}
		get_template_part( 'templates/header-' . $block_index );
	}
}
$content = trim( ob_get_clean() );
if ( $content ) {
	echo ideapark_wrap( $content );
}