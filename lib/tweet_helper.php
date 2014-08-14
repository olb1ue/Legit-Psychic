<?php
require './tmhOAuth/tmhOAuth.php';
require './config/tmh_myconfig.php';

class tweet_helper extends tmhOAuth {
  
  public function __construct($config=array()) {
    $this->config = array_merge(
      tmh_myconfig(),
      $config
    );
  
    parent::__construct($this->config);
  }

  function tweet_requests($request=array(), $tweet){
    $params = array(
      'in_reply_to_status_id' => $request['status_id'],
      'status'                => $tweet
    );
    $code = $this->user_request(array(
      'method' => 'POST',
      'url' => $this->url("1.1/statuses/update"),
      'params' => $params
    ));

    if($code == 200) {
      return json_decode($this->response['response'], true);
    }
    else {
      return $this->response['error'];
    }
  }

  function get_mentions() {
    $code = $this->user_request(array(
      'url' => $this->url('1.1/statuses/mentions_timeline')
    ));

    if($code == 200) {
      return json_decode($this->response['response'], true);
    }
    else {
      return null;
    }
  }
}
?>
