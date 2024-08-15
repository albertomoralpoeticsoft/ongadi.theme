<?php
/**
 * Astra Child OngAdi Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child OngAdi
 * @since 1.0.0
 */

require_once(dirname(__FILE__) . '/cleanhead.php'); 
require_once(dirname(__FILE__) . '/shortcodes/main.php');
require_once(dirname(__FILE__) . '/api/main.php');

add_filter('xmlrpc_enabled', '__return_false');
add_filter('login_display_language_dropdown', '__return_false');

/* Theme setup */

add_post_type_support( 
  'page', 
  'excerpt' 
);

// Prevent the loading of patterns from the WordPress.org Pattern Directory
add_filter( 
  'should_load_remote_block_patterns', 
  '__return_false' 
);

// Remove patterns that ship with WordPress Core.
add_action( 
  'after_setup_theme', 
  function () {
    remove_theme_support( 'core-block-patterns' );
  }
);

// Query archive

add_action( 
  'pre_get_posts', 
  function ($q) { 
      
    error_log(json_encode($q));

    if(
      !is_admin() // Only target front end queries
      && $q->is_main_query() // Only target the main query
      && $q->is_category()   // Only target category archives [comment out if not needed]
      // && $q->is_tag()        // Only target tag archives [comment out if not needed]
    ) {
      
      $q->set(
        'post_type', 
        [
          'post', 
          'page'
        ] 
      );   
      
      $q->set('orderby', 'menu_order'); 
      $q->set('order', 'ASC');
    }
});

/**
* In construction
*/

/* 

*/

global $post;

add_action(
  'template_redirect', 
  function () use ($post){

    if(
      (
        !is_user_logged_in()
        &&
        !has_category('allow-in-construction', $post->ID)
      )
    ) {

      wp_redirect('/en-construccion'); 
      exit;
    }
  }
);

/**
* Init
*/

add_action( 
  'init', 
  function () {

    /**
      * Categories for pages
      */  

    register_taxonomy_for_object_type( 
      'category', 
      'page' 
    );

    /**
      * Categories for media
      */  
    /*
    register_taxonomy_for_object_type( 
      'category', 
      'attachment' 
    );
    */

    /**
      * Tags for media
      */  
    /*
    register_taxonomy_for_object_type( 
      'post_tag', 
      'attachment' 
    );
    */
  }
); 

/**
 * Enqueue styles
 */

add_action( 
	'wp_enqueue_scripts', 
	function () {

    // JS

    wp_enqueue_script(
      'astra-child-noshibari-theme-flickity-js', 
      get_stylesheet_directory_uri() . '/js-css/flickity.pkgd.min.js',
      array(), 
      filemtime(get_stylesheet_directory() . '/js-css/flickity.pkgd.min.js'),
      true
    );

    wp_enqueue_script(
      'astra-child-ongadi-theme-js', 
      get_stylesheet_directory_uri() . '/js-css/main.js',
      array(
        'jquery',        
        'astra-child-noshibari-theme-flickity-js'
      ), 
      filemtime(get_stylesheet_directory() . '/js-css/main.js'),
      true
    );

    // CSS

		wp_enqueue_style( 
			'astra-child-noshibari-theme-flickity-css',
			get_stylesheet_directory_uri() . '/js-css/flickity.css', 
			array(
        'astra-theme-css'
      ), 
			filemtime(get_stylesheet_directory() . '/js-css/flickity.css'),
			'all' 
		);

		wp_enqueue_style( 
			'astra-child-ongadi-theme-css',
			get_stylesheet_directory_uri() . '/js-css/main.css', 
			array(
        'astra-child-noshibari-theme-flickity-css'
      ), 
			filemtime(get_stylesheet_directory() . '/js-css/main.css'),
			'all' 
		);
	}, 
	999 
);


