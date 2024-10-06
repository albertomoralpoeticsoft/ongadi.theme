<?php

function astra_child_ongadi_theme_block_imagebank_callback(
  $block_attributes,
  $content
) {

  return '<div class="wp-block-ongadi-imagebank">
    <div id="blockattributes" style="display: none;">' . 
      json_encode($block_attributes) . 
    '</div>
    <div class="Search">
      <input name="text" />
      <div class="wp-block-button disabled">
        <a 
          class="wp-block-button__link wp-element-button " 
          href="#"
        >' .  
          __('Buscar') .
        '</a>
      </div>
    </div>
    <div class="Results">
      <div class="Message"></div>
      <div class="List">
        <div class="ListMessage"></div>
        <div class="ListPosts"></div>
      </div>
    </div>
  </div>';
}