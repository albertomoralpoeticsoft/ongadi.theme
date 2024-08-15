<?php

require_once(dirname(__FILE__) . '/tools/dompdf/vendor/autoload.php');
require_once(dirname(__FILE__) . '/tools/pretty/vendor/autoload.php');

use Dompdf\Dompdf;
use Wa72\HtmlPrettymin\PrettyMin;
use Dompdf\Options;

function ongadi_memoria_download( WP_REST_Request $req ) {
  
  try {
    
    $postid = $req->get_param('postid');
    $post = get_post($postid);

    $htmldocpath = dirname(__FILE__) . '/memoria.html';

    $content = do_shortcode($post->post_content);
    $html = '<html>
      <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" href="main.css" rel="stylesheet" />
      </head>
      <body>' .
        $content . 
      '</body>
    </html>';

    $htmldom = new DOMDocument('1.0');
    $htmldom->substituteEntities = false;
    libxml_use_internal_errors(true);
    $htmldom->loadHTML($html);
    libxml_use_internal_errors(false);
    $htmldomx = new DOMXPath($htmldom);

    $htmlcomments = $htmldomx->query('//comment()');
    foreach($htmlcomments as $htmlcomment) {

      $htmlcomment->parentNode->removeChild($htmlcomment);   
    }
    
    $htmlwithstyles = $htmldomx->query('//*[@style]');
    foreach($htmlwithstyles as $htmlwithstyle) {

      $htmlwithstyle->removeAttribute('style');   
    }    

    $htmlshortcodes = $htmldomx->query('//*[contains(@class, "shortcode")]');
    foreach($htmlshortcodes as $htmlshortcode) {

      $htmlshortcode->parentNode->removeChild($htmlshortcode);   
    }  

    $htmlspaces = $htmldomx->query('//*[contains(@class, "wp-block-spacer")]');
    foreach($htmlspaces as $htmlspace) {

      $htmlspace->parentNode->removeChild($htmlspace);   
    }

    $htmlbodychilds = $htmldomx->query('//body/child::node()');
    $sectiontitle = '';
    
    foreach($htmlbodychilds as $htmlbodychild) {

      if($htmlbodychild->nodeType == 1) {

        if($htmlbodychild->nodeName == 'h2') {

          $sectiontitle = $htmlbodychild->nodeValue;
          $htmlbodychild->parentNode->removeChild($htmlbodychild);
        } 
        
        if(str_contains($htmlbodychild->getAttribute('class'), 'pagina')) {
        
          // Slide
          
          foreach($htmlbodychild->childNodes as $slideChildNode) {

            // Titulo compuesto

            if($slideChildNode->nodeName == 'h3') {

              $h3content = $slideChildNode->nodeValue;
              $h3title = $sectiontitle . ' / ' . $h3content; 
              $slideChildNode->nodeValue = $h3title;
            }

            // Cover

            if(
              $slideChildNode->nodeType == 1
              &&
              str_contains($slideChildNode->getAttribute('class'), 'wp-block-cover')
            ) {

              foreach($slideChildNode->childNodes as $coverChildNode) {

                if(
                  $coverChildNode->nodeType == 1
                  &&
                  str_contains($coverChildNode->getAttribute('class'), 'wp-block-cover__image-background')
                ) {

                  $imgsrc = $coverChildNode->getAttribute('src');
                  $slideChildNode->setAttribute('style', 'background-image: url("' . $imgsrc . '")');
                  $coverChildNode->parentNode->removeChild($coverChildNode);
                }
              }
            }
          }
        } 
      }   
    }
    
    $pm = new PrettyMin();
    $pm->load($htmldom)->indent();
    unset($pm);

    $htmlcontent = $htmldom->saveHTML();

    file_put_contents(
      $htmldocpath,
      $htmlcontent
    );

    $options = new Options();
    $options->setChroot([__DIR__ .'/assets/']);
    $options->setDpi(300);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options); 

    $dompdf->setBasePath(__DIR__ .'/assets/');
    $dompdf->loadHtml($htmlcontent);
    $dompdf->render();
    $dompdf->stream(sanitize_title($post->post_title) . '.pdf', array('Attachment' => false));
    exit();

    /*
    
    header('Content-Type: text/html');
    echo $htmlcontent ; 
    exit();

    */  

  } catch (Exception $e) {
    
    $res = new WP_REST_Response();

    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());

    return $res;
  }
}

add_action(
  'rest_api_init',
  function () {

    register_rest_route(
      'ongadi/memoria',
      '/download/(?P<postid>.+)',
      [
        'methods'  => 'GET',
        'callback' => 'ongadi_memoria_download',
        'permission_callback' => '__return_true'
      ]
    );
  }
);