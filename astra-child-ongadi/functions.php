<?php
/**
 * Astra Child OngAdi Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child OngAdi
 * @since 1.0.0
 */

function core_log($display) { 

  $text = is_string($display) ? $display : json_encode($display, JSON_PRETTY_PRINT);

  file_put_contents(
    WP_CONTENT_DIR . '/core_log.txt',
    $text . PHP_EOL,
    FILE_APPEND
  );
}

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

add_action(
  'init',
  function () {

    /**
    * Dynamic blocks 
    */

    // Query Image bank 

    require_once(get_stylesheet_directory() . '/block/imagebank/callback.php');

    wp_register_script(
      'astra-child-ongadi-theme-block-imagebank-js', 
      get_stylesheet_directory_uri() . '/block/imagebank/main.js',
      [], 
      filemtime(get_stylesheet_directory() . '/block/imagebank/main.js'),
      true
    );  

    wp_register_style( 
      'astra-child-ongadi-theme-block-imagebank-css',
      get_stylesheet_directory_uri() . '/block/imagebank/main.css', 
      array(), 
      filemtime(get_stylesheet_directory() . '/block/imagebank/main.css'),
      'all' 
    );  

    register_block_type( 
      'ongadi/imagebank', 
      [
        'editor_script' => 'astra-child-ongadi-theme-block-imagebank-js',
        'style' => 'astra-child-ongadi-theme-block-imagebank-css',
        'render_callback' => 'astra_child_ongadi_theme_block_imagebank_callback'
      ]
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

      if(is_archive()) {

        $wp_query->set( 'orderby', 'menu_order');
        $wp_query->set( 'order', 'ASC' );
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

    // Admin scripts - sStyles

    wp_enqueue_script(
      'astra-child-ongadi-theme-admin-js', 
      get_stylesheet_directory_uri() . '/js-css/admin.js',
      array('jquery'), 
      filemtime(get_stylesheet_directory() . '/js-css/admin.js'),
      true
    );

		wp_enqueue_style( 
			'astra-child-ongadi-theme-admin-css',
			get_stylesheet_directory_uri() . '/js-css/admin.css', 
			array(), 
			filemtime(get_stylesheet_directory() . '/js-css/admin.css'),
			'all' 
		);
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

// Blocks category

add_filter(
  'block_categories_all',
  function ($categories, $post) {

    return array_merge(
      $categories,
      array(
        array(
          'slug' => 'ongadi',
          'title' => __(
            'ONG ADI',
            'ongadi' 
          ),
        ),
      )
    );
  },
  10,
  2
);


