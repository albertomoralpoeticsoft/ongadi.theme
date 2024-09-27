<?php

use FileBird\Classes\Helpers as Helpers;

function ongadi_imagebank_folderlist( WP_REST_Request $req ) {
    
  $res = new WP_REST_Response();
  
  try {

    $folder_id = 21;
    $ids = Helpers::getAttachmentIdsByFolderId($folder_id);

    $res->set_data($ids);

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
      'folderlist',
      [
        'methods'  => 'GET',
        'callback' => 'ongadi_imagebank_folderlist',
        'permission_callback' => '__return_true'
      ]
    );
  }
);