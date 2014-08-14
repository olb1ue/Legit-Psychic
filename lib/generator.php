<?php

error_reporting(E_ALL);
 ini_set('display_errors', 1);


function load_json() {
  $dir = 'json';
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if (strpos($file, 'json')) {
          $key = str_replace('.json', '', $file);
          $json[$key] = json_decode(file_get_contents($dir.DIRECTORY_SEPARATOR.$file));
        }
      }
      closedir($dh);
    }
  }
  if (!empty($json)) {
    return $json;
  }
  else {
    return null;
  }
}

function generator($user, $gr) {
  if (!isset($gr)) $gr = mt_rand(0,4);
  $phrases = load_json();

  $tweet = '@'.$user.' '.
	   $phrases['starters'][mt_rand(0, 9)].' '.
	   $phrases['advice_phrases'][$gr][mt_rand(0, 9)].' '.
	   $phrases['times'][mt_rand(0, 4)].' '.
 	   $phrases['explain_phrases'][$gr][mt_rand(0, 9)];
  return array('tweet' => $tweet, 'gravity' => $gr);
}


?>
