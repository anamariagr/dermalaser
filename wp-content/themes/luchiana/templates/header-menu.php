<?php if ( has_nav_menu( 'top_bar' ) ) { ?>
	<li class="c-header__top-row-item c-header__top-row-item--menu">
		<?php echo str_replace( '<nav', '<nav itemscope itemtype="http://schema.org/SiteNavigationElement"', wp_nav_menu( [
			'container'       => 'nav',
			'container_class' => 'c-top-bar-menu',
			'echo'            => false,
			'menu_id'         => 'top-bar-menu',
			'menu_class'      => 'c-top-bar-menu__list',
			'theme_location'  => 'top_bar',
			'fallback_cb'     => '',
			'depth'           => ideapark_mod( 'header_top_bar_menu_depth' ) == 'unlim' || ideapark_mod( 'header_top_bar_menu_depth' ) < 1 ? 0 : (int) ideapark_mod( 'header_top_bar_menu_depth' )
		] ) ); ?>
	</li>
<?php } ?>