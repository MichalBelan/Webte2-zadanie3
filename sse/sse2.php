<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');  
header("Connection: keep-alive");

$lastId = $_SERVER["HTTP_LAST_EVENT_ID"];
if (isset($lastId) && !empty($lastId) && is_numeric($lastId)) {
	$lastId = intval($lastId);
	$lastId++;
} else {
	$lastId = 0;
}

while (true) {
  $time = date('h:i:s');
  $num=rand(1,15);  
  echo "id: $lastId" . PHP_EOL;
  /*
  echo "data: {". PHP_EOL;
  echo "data: \"msg\": \"The server time is: {$time}\", ". PHP_EOL;
  echo "data: \"num\": \"{$num}\" ". PHP_EOL;
  echo "data: }". PHP_EOL;
  echo PHP_EOL;
    */
  $data = array(
       'msg' => "The server time is: {$time}",
       'num' => "{$num}"
  );     
  echo 'data: ' . json_encode($data) . PHP_EOL . PHP_EOL;   
    
  $lastId++;
  ob_flush();
  flush();
  
  if ($lastId % 10 == 0) {
		die();
	}
  
  sleep(5);
}
?> 