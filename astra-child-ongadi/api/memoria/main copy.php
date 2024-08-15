<?php

require_once('./tools/dompdf/vendor/autoload.php');
require_once('html.php');

use Dompdf\Dompdf;

function academy_certificate_formatdate($datestring) {

  $months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];

  foreach($months as $month) {

    $datestring = str_replace($month, __($month, 'academy_theme'), $datestring);
  }

  return $datestring;
}

function academy_certificate_download( WP_REST_Request $req ) {

  global $wpdb;
  
  try {

    $baseprefix = $wpdb->base_prefix;
    if(is_multisite()) {

      $blogid = get_current_blog_id();
      $baseprefix .= $blogid . '_';
    }      
    $tableusercourses  = $baseprefix . 'userdata_usercourses';

    $courseid = $req->get_param('courseid');
    $course = get_post($courseid);
    $userid = $req->get_param('userid');
    $user = get_userdata($userid);
    $academycourseid = get_post_meta(
      $courseid,
      'academy_course_meta_id',
      true
    );

    $sql = "
      SELECT usercourses.end_date	
      FROM $tableusercourses as usercourses
      WHERE usercourses.academy_course_id = '$academycourseid'
        AND usercourses.user_id = $userid;
    ";
    $result = $wpdb->get_results($sql); 
    $enddate = $result[0]->end_date;
    $enddateseconds = $enddate / 1000; 
    $endatestring = date("j F, Y", $enddateseconds);
    
    $locale = $req->get_param('locale');

    load_textdomain('academy_theme', WP_CONTENT_DIR . '/languages/themes/academy_theme-' . $locale . '.mo');

    $localeenddatestring = academy_certificate_formatdate($endatestring);

    $html = academy_certificate_download_html($course, $user, $localeenddatestring);

    $dompdf = new Dompdf(); 

    $options = $dompdf->getOptions();
    $options->setChroot([__DIR__ .'/assets/']);
    $options->setDpi(300);
    $dompdf->setBasePath(__DIR__ .'/assets/');
    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream(sanitize_title($course->post_title) . '.pdf', array('Attachment' => false));
    // $dompdf->stream(sanitize_title($course->post_title) . '.pdf');
    
  } catch (Exception $e) {
    
    $res = new WP_REST_Response();

    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());

    return $res;
  }
}

function academy_certificate_text( WP_REST_Request $req ) {

  global $locale;
  
  $res = new WP_REST_Response();

  $data = [];
  
  try {

    $data[] = __('Courses', 'academy_theme');
    $data[] = WP_CONTENT_DIR;

    load_textdomain('academy_theme', WP_CONTENT_DIR . '/languages/themes/academy_theme-de_DE.mo');
    $data[] = __('Courses', 'academy_theme');

    $res->set_data($data);
    
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
      'academy/certificate',
      '/download',
      [
        'methods'  => 'GET',
        'callback' => 'academy_certificate_download',
        'permission_callback' => '__return_true'
      ]
    );

    register_rest_route(
      'academy/certificate',
      '/text',
      [
        'methods'  => 'GET',
        'callback' => 'academy_certificate_text',
        'permission_callback' => '__return_true'
      ]
    );
  }
);