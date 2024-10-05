<?php

use FileBird\Classes\Helpers as Helpers;
use FileBird\Classes\Tree as Tree;

/*
add_filter( 
  'wp_search_stopwords', 
  function($stopwords) {
    
    core_log($stopwords);
  }
);
*/

function ongadi_imagebank_search( WP_REST_Request $req ) {
  
  global $wpdb;
  
  $res = new WP_REST_Response();
  
  include_once(__DIR__ . '/stopwords.php');
  
  try {
    
    $baseprefix = $wpdb->base_prefix;
    if(is_multisite()) {

      $blogid = get_current_blog_id();
      $baseprefix .= $blogid . '_';
    };

    $params = $req->get_params();
    $folders = $params['folders'];
    $text = sanitize_text_field($params['text']);

    if(!$folders) {

      throw new Exception('Folder ids not provided', 400);
    }

    if(!$text) {

      throw new Exception('Text not provided', 400);
    }

    // Find attachment ids from folder ids
    
    $sql = "
    SELECT attachment_id
    FROM {$baseprefix}fbv_attachment_folder
    WHERE folder_id IN ($folders)
    ";    
    $ids = $wpdb->get_results($sql);
    $idsarray = array_map(
      function($attachment) { return intval($attachment->attachment_id); },
      $ids
    );
    $idslist = implode(',', $idsarray);
    $searchtextarray = explode(' ', $text);
    $cleansearchtextarray = [];
    foreach($searchtextarray as $term) {

      if(
        $term != ''
        &&
        !in_array($term, $ongadi_search_stopwords)
      ) {

        // Clean term

        $cleansearchtextarray[] = $term;
      }
    }
    $searchlikes = array_map(
      function($term) {

        return "%$term%";
      },
      $cleansearchtextarray
    );
    $searchcolumns = [
      'post_title',
      'post_excerpt',
      'post_content'
    ];

    $searchs = [];  

    foreach($searchcolumns as $column) {

      foreach($searchlikes as $term) {        

        $searchs[] = "$column LIKE '$term'";
      }
    }

    $sqlsearchs = implode (' OR ', $searchs);
    
    $sql = "
    SELECT ID, post_title, post_excerpt, post_content, guid
    FROM {$baseprefix}posts
    WHERE ID IN ($idslist)
    AND (
      $sqlsearchs
    )
    "; 
    $resultposts = $wpdb->get_results($sql);

    $res->set_data([
      // 'search' => $sql, 
      'terms' => $cleansearchtextarray,
      'attachmentcount' => count($ids),
      'postcount' => count($resultposts),
      'posts' => $resultposts
    ]);

  } catch (Exception $e) {

    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;
}

function ongadi_imagebank_folders( WP_REST_Request $req ) {
    
  $res = new WP_REST_Response();
  
  try {
    
    $folders = Tree::getFolders(null);

    $res->set_data($folders);

  } catch (Exception $e) {

    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;
}

function ongadi_imagebank_folderimages( WP_REST_Request $req ) {
    
  $res = new WP_REST_Response();
  
  try {

    $folderid = $req->get_param('folderid');
    
    $ids = Helpers::getAttachmentIdsByFolderId($folderid);

    $res->set_data([
      "count" => count($ids),
      "ids" => $ids,
      "list" => implode(',', $ids)
    ]);

  } catch (Exception $e) {

    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;
}

add_action(
  'rest_api_init',
  function () {

    register_rest_route(
      'ongadi/imagebank',
      'search',
      [
        'methods'  => 'POST',
        'callback' => 'ongadi_imagebank_search',
        'permission_callback' => '__return_true'
      ]
    );

    register_rest_route(
      'ongadi/imagebank',
      'folders',
      [
        'methods'  => 'GET',
        'callback' => 'ongadi_imagebank_folders',
        'permission_callback' => '__return_true'
      ]
    );

    register_rest_route(
      'ongadi/imagebank',
      'folderimages/(?P<folderid>\d+)',
      [
        'methods'  => 'GET',
        'callback' => 'ongadi_imagebank_folderimages',
        'permission_callback' => '__return_true'
      ]
    );
  }
);