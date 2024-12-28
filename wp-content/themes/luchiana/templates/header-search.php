<div class="c-header-search disabled js-ajax-search">
	<div class="c-header-search__wrap">
		<div class="c-header-search__shadow js-search-close"></div>
		<div class="c-header-search__form">
			<div class="c-header-search__tip"><?php esc_html_e( 'Que estas buscando?', 'luchiana' ); ?></div>
			<?php ideapark_af( 'get_search_form', 'ideapark_search_form_ajax', 90 ); ?>
			<?php get_search_form(); ?>
			<?php ideapark_rf( 'get_search_form', 'ideapark_search_form_ajax', 90 ); ?>
		</div>
		<div class="l-section l-section--container c-header-search__result js-ajax-search-result">

		</div>
		<button type="button" class="h-cb h-cb--svg c-header-search__close js-search-close"><i
				class="ip-close-small"></i></button>
	</div>
</div>
