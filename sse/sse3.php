<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');  
header("Connection: keep-alive");

while (true) {
  $time = date('h:i:s');
  $num=rand(1,15);  
  echo "id: $lastId" . PHP_EOL;
  $data = array(
       'msg' => "The server time is: {$time}",
       'num' => "{$num}"
  );     
  echo 'data: ' . json_encode($data) . PHP_EOL . PHP_EOL;   
    
  echo "event: mojaUdalost" . PHP_EOL; 
  echo "data: " . $num*$num . "\n\n";
    
  ob_flush();
  flush();
  
  sleep(2);
}
?> 