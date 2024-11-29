<?php if ( strpos( ideapark_mod( 'header_buttons' ), 'search=1' ) !== false ) { ?>
	<div class="c-header__search disabled js-ajax-search">
		<?php ideapark_af( 'get_search_form', 'ideapark_search_form_header', 90 ); ?>
		<?php get_search_form(); ?>
		<?php ideapark_rf( 'get_search_form', 'ideapark_search_form_header', 90 ); ?>
		<div class="c-header__search-result js-ajax-search-result"></div>
	</div>
<?php } ?>





