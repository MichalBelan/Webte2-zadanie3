<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');                             //https://serverfault.com/questions/801628/for-server-sent-events-sse-what-nginx-proxy-configuration-is-appropriate
header("Connection: keep-alive");
header("Access-Control-Allow-Origin: *");

$lastId = $_SERVER["HTTP_LAST_EVENT_ID"];
if (isset($lastId) && !empty($lastId) && is_numeric($lastId)) {
	$lastId = intval($lastId);
	$lastId++;
} else {
	$lastId = 0;
}

while (true) {
  $x = $lastId;
  $y1=sin(deg2rad($x))+0.01*rand(1,10); 
  $y2=cos(deg2rad($x))+0.01*rand(1,10);  
  echo "id: $lastId" . PHP_EOL;
  echo "data: {". PHP_EOL;
  echo "data: \"x\": \"{$x}\", ". PHP_EOL;
  echo "data: \"y1\": \"{$y1}\", ". PHP_EOL;
  echo "data: \"y2\": \"{$y2}\" ". PHP_EOL;
  echo "data: }". PHP_EOL;
  echo PHP_EOL;
  $lastId++;
  ob_flush();
  flush();
  
  
  sleep(2);
}
?> 