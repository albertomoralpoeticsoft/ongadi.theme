<?php

add_action(
  'phpmailer_init', 
  function($phpmailer) {

    $phpmailer->isSMTP();
    $phpmailer->Host = 'ssl0.ovh.net';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 465;
    $phpmailer->Username = 'hola@ongadi.org';
    $phpmailer->Password = 'JsAU8)0000';
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->From = 'hola@ongadi.org';
    $phpmailer->FromName = 'ONG ADI';    
    $phpmailer->isHTML(true);
  }
);

add_action(
  'wp_mail_failed',
  function ($wp_error) {

    error_log('wp_mail_failed');
    error_log(json_encode($wp_error));
  } ,
  10, 
  1 
);

function ongadi_mail_sendtest( WP_REST_Request $req ) {
      
  $res = new WP_REST_Response();
  
  $process = [];

  $process[] = 'Intento de envio de mail';  

  try { 

    $process = [];

    $mailsent = wp_mail(
      'poeticsoft@gmail.com',
      'Mail test from ONG ADI',
      'Body'
    );

    $process[] = $mailsent ? 'sent' : 'not sent';      

    $res->set_data($process);
  
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
      'ongadi',
      'mail/sendtest',
      [
        'methods'  => 'GET',
        'callback' => 'ongadi_mail_sendtest',
        'permission_callback' => '__return_true'
      ]
    );
  }
);