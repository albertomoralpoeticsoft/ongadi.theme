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

add_filter('xmlrpc_enabled', '__return_false');
add_filter('login_display_language_dropdown', '__return_false');

/* Theme setup */

add_post_type_support( 'page', 'excerpt' );

/**
* In construction
*/

global $post;

add_action(
  'template_redirect', 
  function () use ($post){

    if(
      !is_user_logged_in()
      &&
      !has_category('allow-in-construction', $post->ID)
    ) {

      wp_redirect('/volvemos-pronto'); 
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
  }
); 

/**
 * Enqueue styles
 */

add_action( 
	'wp_enqueue_scripts', 
	function () {

    wp_enqueue_script(
      'astra-child-ongadi-theme-js', 
      get_stylesheet_directory_uri() . '/js-css/main.js',
      array(
        'jquery'
      ), 
      filemtime(get_stylesheet_directory() . '/js-css/main.js'),
      true
    );

		wp_enqueue_style( 
			'astra-child-ongadi-theme-css',
			get_stylesheet_directory_uri() . '/js-css/main.css', 
			array(
        'astra-theme-css'
      ), 
			filemtime(get_stylesheet_directory() . '/js-css/main.css'),
			'all' 
		);
	}, 
	999 
);


