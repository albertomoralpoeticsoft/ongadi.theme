<?php

add_shortcode(
  'imagebank',
  function ($atts) {

    return '<div class="shortcode imagebank">
      <form>
        <label for"b">Buscar, Cercar</label>
        <input
          class="b" 
          type="text" 
          name="b" 
        />
        <input 
          class="submit"
          type="submit" 
          value="Buscar"
        />
      </div>
      <div class="result">' .
        $_GET['b'] .
      '</div>
    </div>';
  }
);