<?php

add_shortcode(
  'imagebank',
  function ($atts, $contentn, $block) {

    return '<div class="shortcode imagebank">' .

      json_encode($atts) . $content . json_encode($block) .
      
    '</div>';
  }
);