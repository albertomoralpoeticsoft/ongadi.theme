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
require_once(dirname(__FILE__) . '/mail/main.php');

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

/**
* In construction

global $post;

add_action(
  'template_redirect', 
  function () use ($post){

    if(
      (
        $post
        &&
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

*/

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

    /**
      * Tags for posts
      */  
    /*
    */
    unregister_taxonomy_for_object_type( 
      'post_tag', 
      'post' 
    );
  }
);    

/* Query archive

add_action( 
  'pre_get_posts', 
  function ($q) { 

    if(
      is_admin()
      && $q->is_main_query()
      && $q->is_tag()
    ) {
      
      $q->set(
        'post_type', 
        [
          'attachment'
        ] 
      );
      
      $q->set(
        'tax_query', 
        null
      );
      
      // error_log('----------------------------------------');
      // error_log(json_encode($q, JSON_PRETTY_PRINT));
    }
  }
);

add_filter( 
  'request', 
  function($query_vars) {

    if(
      is_admin()
      &&
      isset($query_vars['post_type'])
      &&
      $query_vars['post_type'] == 'attachment'
      &&
      isset($query_vars['tag'])
      &&
      $query_vars['tag'] == "0"
    ) {
      
      $query_vars['tag'] = "";
    }

    return $query_vars;
  }
);

*/

if (!is_admin()) {

  add_action( 
    'pre_get_posts', 
    function ($wp_query) {
	
      if ( 
        $wp_query->get('category_name') 
        || 
        $wp_query->get('cat')
      ) {
        
        $wp_query->set('post_type', ['post','page']);
      }
    }
  );    
}

/**
 * Admin enqueue
 */

add_action( 
	'admin_enqueue_scripts', 
	function () {

		wp_enqueue_style( 
			'astra-child-ongadi-theme-css',
			get_stylesheet_directory_uri() . '/js-css/admin.css', 
			array(
        
      ), 
			filemtime(get_stylesheet_directory() . '/js-css/admin.css'),
			'all' 
		);
	}, 
	999 
);

/**
 * Enqueue styles
 */

add_action( 
	'wp_enqueue_scripts', 
	function () {

    // JS

    wp_enqueue_script(
      'astra-child-ongadi-theme-flickity-js', 
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
        'astra-child-ongadi-theme-flickity-js'
      ), 
      filemtime(get_stylesheet_directory() . '/js-css/main.js'),
      true
    );

    // CSS

		wp_enqueue_style( 
			'astra-child-ongadi-theme-flickity-css',
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
        'astra-child-ongadi-theme-flickity-css'
      ), 
			filemtime(get_stylesheet_directory() . '/js-css/main.css'),
			'all' 
		);
	}, 
	999 
);


