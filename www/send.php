<?php


$room = $_REQUEST['room'];
$message = $_REQUEST['message'];

$url = "http://securesocket.io:180/secret_message?room=".urlencode($room)."&message=".urlencode($message);


system('curl -s -f -m 3 \''.$url.'\'&');

?>
