<?php $menu = str_replace( '<nav', '<nav itemscope itemtype="http://schema.org/SiteNavigationElement"', wp_nav_menu( [
	'container'       => 'nav',
	'container_class' => 'c-top-menu js-top-menu' . (ideapark_mod( 'top_menu_arrow' ) ? ' c-top-menu--arrows' : ''),
	'echo'            => false,
	'menu_id'         => 'top-menu-desktop',
	'menu_class'      => 'c-top-menu__list',
	'theme_location'  => 'primary',
	'fallback_cb'     => '',
	'depth'           => ideapark_mod( 'top_menu_depth' ) == 'unlim' || ideapark_mod( 'top_menu_depth' ) < 1 ? 0 : (int) ideapark_mod( 'top_menu_depth' )
] ) );
preg_match_all( '~"c-top-menu__item~', $menu, $matches, PREG_SET_ORDER );
if ( sizeof( $matches ) >= 7 ) {
	$menu = preg_replace( '~"c-top-menu__list~', '"c-top-menu__list c-top-menu__list--compact', $menu );
}
echo ideapark_wrap( $menu );