<?php /* alerts box */
/*  usually use 2 plugins with an alerts box,
*   post expirator & ultiple posts widget(with zig's mods to not show when empty)
*/

/* first add a widget area */

/* add widget area for above the content*/
	if ( function_exists('register_sidebar') ){

		 // alerts box at top of site
		register_sidebar(array(
			'name' => 'Homepage Alerts Box',
			'id' => 'rmmz_alerts_box',
			'description' => 'Widget under header (for alerts)',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		));
    }
