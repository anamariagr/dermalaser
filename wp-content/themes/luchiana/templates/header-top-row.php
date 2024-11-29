<?php
ob_start();
$header_blocks = ideapark_parse_checklist( ideapark_mod( ideapark_mod( 'header_type' ) == 'header-type-2' ? 'header_blocks_2' : 'header_blocks_3' ) );
foreach ( $header_blocks as $block_index => $enabled ) {
	if ( $enabled ) {
		get_template_part( 'templates/header-' . $block_index );
	}
}
$content = trim( ob_get_clean() );
if ( $content ) { ?>
	<div class="c-header__top-row c-header__top-row--<?php echo esc_attr( ideapark_mod( 'header_type' ) ); ?>">
		<ul class="c-header__top-row-list c-header__top-row-list--<?php echo esc_attr( ideapark_mod( 'header_blocks_layout' ) ); ?>">
			<?php
			echo ideapark_wrap( $content );
			?>
		</ul>
	</div>
<?php }