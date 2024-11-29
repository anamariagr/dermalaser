<?php if ( strpos( ideapark_mod( 'header_buttons' ), 'search=1' ) !== false ) { ?>
	<button class="h-cb c-header__menu-search js-search-button" type="button" aria-label="<?php esc_attr_e('Search', 'luchiana'); ?>"><i class="<?php echo ( ideapark_mod( 'custom_header_icon_search' ) ?: 'ip-search' ); ?>"><!-- --></i>
	</button>
<?php } ?>