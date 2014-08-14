<?php
require 'tmhOAuth/tmhOAuth.php';
require 'config/tmh_myconfig.php';
require 'lib/data_helper.php';
require 'lib/generator.php';

error_reporting(E_ALL);
 ini_set('display_errors', 1);

$data_helper = new data_helper();
$user = 'new_user_4';

$requester_gravity = $data_helper->check_for_requester($user);
  $response = generator($user, $requester_gravity);
$tweet = $response['tweet'];
$gravity = $response['gravity'];

if (strlen($tweet) > 140) {
echo "<font color='red'>".$tweet."</font>";
}
else {
echo $tweet;}
$data_helper->add_requester($user, $gravity, $tweet);
?>
