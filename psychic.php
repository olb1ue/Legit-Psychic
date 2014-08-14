<?php
require 'lib/data_helper.php';
require 'lib/tweet_helper.php';
require 'lib/generator.php';

//error_reporting(E_ALL);
// ini_set('display_errors', 1);

$data_helper = new data_helper();
$tweet_helper = new tweet_helper();
$data = $tweet_helper->get_mentions(); 

if($data) {
  $latest_mention = $data['0']['id_str'];
  $data = array_reverse($data);
}
else {
	echo $tmhOAuth->response['raw'];
	echo 'failed';
     }
//get last mention from database
$last_mention = $data_helper->get_last_mention();
$have_new_tweets = ($last_mention == $latest_mention) ? FALSE :TRUE;

//update mentions table if there are new mentions
if ($have_new_tweets) {
  foreach($data as $v){
    if ($v['id_str'] > $last_mention) {
      $data_helper->update_mentions($v);
    }
  }
}
  //get requests if there are any
$fortune_requests = $data_helper->get_haiku_requests();

if ($fortune_requests) {
  foreach($fortune_requests as $v) {
  $user = $v['user'];

//Get requester's "gravity" if they've gotten a fortune today
    
  $requester_gravity = $data_helper->check_for_requester($user);
  do { 
    $response = generator($user, $requester_gravity); 
    $tweet = $response['tweet'];
    $gravity = $response['gravity'];
  } while (strlen($tweet) > 140);

  $tweet_response = $tweet_helper->tweet_requests($v,$tweet);
  $data_helper->mark_read($v['id']);
  $data_helper->add_requester($user, $gravity, $tweet);
  echo'<pre>'; print_r($tweet_response);
  }
}
else echo 'no requests';
?>
