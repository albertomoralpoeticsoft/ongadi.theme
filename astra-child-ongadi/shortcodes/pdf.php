<?php

add_shortcode(
  'downloadpdf',
  function ($atts) {

    global $post;

    if(!$post) {

      return '';
    }

    $downloadpdftext = isset($atts['downloadpdf']) ?
      $atts['downloadpdf']
      :
      'Descargar PDF';

    return '<div class="shortcode downloadpdf">
      <form 
        action="/wp-json/ongadi/memoria/download/' . $post->ID . '"
        target="memoriapdf"
      >
        <button 
          class="wp-block-button"
          type="submit"
        >
          ' . $downloadpdftext . '
        </button>
      </form>
    </div>';
  }
);