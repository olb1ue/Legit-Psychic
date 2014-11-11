<?php
require './config/mysql_config.php';

class data_helper {
  
  public function __construct() {
    $config = mysql_config();
    mysql_connect($config['domain'], $config['username'], $config['pass']);
    mysql_select_db($config['db']) or die('Failed to connect to databse');
  }

  function update_mentions($mention=array()) {
    $status_id = $mention['id_str'];
    $tweet = mysql_real_escape_string($mention['text']);
    $user = $mention['user']['screen_name'];
    $user_id = $mention['user']['id_str'];
    $query = 
 
      "INSERT INTO  `haikume`.`mentions` (
	`status_id` ,
	`tweet` ,
	`user` ,
	`user_id` 
      )
      VALUES (
        '".$status_id."',  '".$tweet."',  '".$user."',  '".$user_id."'
      );";
    mysql_query($query) or die(mysql_error());
  }

  function get_last_mention() {
    $sql = 'SELECT `status_id` FROM `mentions` order by `status_id` desc LIMIT 1;'; 
    $db_latest = mysql_query($sql) or die(mysql_error());
    return mysql_result($db_latest, 0);
  }

  function get_haiku_requests() {
    $sql = 'SELECT * FROM `mentions` WHERE `tweet` LIKE "%predict%" and `new` = TRUE;';
    $result = mysql_query($sql);
    if (mysql_num_rows($result)!=0) {
      while($row=mysql_fetch_array($result)){
        $a[] = $row;
      }
      return $a;
    }
    else {
      return null;
    }
  }

  function mark_read($id) {
    $sql = 'UPDATE `haikume`.`mentions` SET `new` = \'0\' WHERE `mentions`.`id` = ' . $id . ';';
    mysql_query($sql) or die(mysql_error());
  }

  function check_for_requester($user) {
    $sql = 'SELECT `gravity` FROM `requesters` where `user` = ' . "'" .$user . "'";
    $result = mysql_query($sql);
    $gravity = mysql_result($result, 0);
    if (mysql_num_rows($result) != 0) {
      return $gravity;
    }
    else {
      return null;
    }
  }

  function add_requester($user, $gravity, $tweet) {
    $tweet = mysql_real_escape_string($tweet);
    $sql = 
      
      "INSERT INTO `haikume`.`requesters` (
        `user` ,
        `gravity` ,
	`tweet`
      )
      VALUES (
        '".$user."', '".$gravity."', '".$tweet."'
      );";
    mysql_query($sql) or die(mysql_error());
  }
}

?>
