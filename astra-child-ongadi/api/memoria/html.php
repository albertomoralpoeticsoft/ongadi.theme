<?php

function academy_certificate_download_html($course, $user, $enddate) {

  return '<html>
    <head>
      <link type="text/css" href="main.css" rel="stylesheet" />
    </head>
    <body>
      <div class="Frame">
        <div class="LogoBahcoAcademy">
          <img src="logo-bahco-academy.png" />
        </div>
        <div class="CertificateTitle">' . 
          __('Certificate of Achievement', 'academy_theme') . 
        '</div>
        <div class="Presented">' . 
          __('This certificate is proudly presented to:', 'academy_theme') . 
        '</div>
        <div class="User">' . 
          $user->first_name .
          ' ' . 
          $user->last_name .
        '</div>
        <div class="Succesfully">' . 
          __('for successfully completing the Bahco Academy course on', 'academy_theme') . 
        '</div>
        <div class="CourseTitle">' . 
          $course->post_title .
        '</div>
        <div class="Date">' . 
          __('on', 'academy_theme') . ' ' . $enddate .
        '</div>
        <div class="Footer"> 
          <div class="Text">
            <span class="Bold">' .
              __('Bahco Academy', 'academy_theme') . 
            '</span>
            <span>' .
              __('is a free learning-platform for acquiring new skills and knowledge on the use of professional hand tools.', 'academy_theme') .
            '</span>
          </div>' .
        '</div>
      </div>
    </body>
  </html>';
}