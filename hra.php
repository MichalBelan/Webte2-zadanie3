<?php 
enum Side : int {
  case Left = 1;
  case Right = 2;
  case Top = 3;
  case Bottom = 4;
} 

enum State {
  case Pending;
  case Running;
}
//Definícia konštánt pre hru a inicializácia globálnych premenných
define('MAX_PLAYER_COUNT', 4);
define('CANVAS_LENGTH', 620);
define('GRID', 15);
define('PADDLE_LENGTH', GRID * 4);

$usersInLobby = [];
$players = [];
$startingPlayer = null;
$ball = null;
$gameState = State::Pending;

//Načítanie potrebných súborov
require_once 'objects.php';
require_once 'websocket.php';

//Funkcia refresh_users aktualizuje počet hráčov v lobby a informuje klientov o zmene
function refresh_users() {
  global $gameState, $usersInLobby, $startingPlayer, $ws_worker;

  if ($gameState == State::Running)
      return;

  if (!array_key_exists($startingPlayer, $usersInLobby)) {
      $startingPlayer = array_key_first($usersInLobby);
      if ($startingPlayer)
          send_msg_to_client($startingPlayer, 'can_start_game', '');
  }

  foreach($ws_worker->connections as $connection)
      send_msg_to_client($connection->id, 'connection_count', count($usersInLobby));
}

//Funkcia start_game inicializuje hru, vytvára objekty hráčov (Paddle) a loptičky (Ball)
function start_game() {
  global $gameState, $players, $usersInLobby, $ball, $ws_worker;
  $players = [];
  $count = 0;
  foreach ($usersInLobby as $key => $value) {
      $count++;
      $players[$key] = new Paddle(Side::from($count), $value);
      unset($usersInLobby[$key]);
      if ($count == 4)
          break;
  }
  $ball = new Ball();

  $gameState = State::Running;
  foreach($ws_worker->connections as $connection)
      send_msg_to_client($connection->id, 'game_status', ['state' => $gameState->name]);
}

//Funkcia is_colliding overuje, či dva objekty kolizujú
function is_colliding($obj1, $obj2) {
  return $obj1->x < $obj2->x + $obj2->width &&
      $obj1->x + $obj1->width > $obj2->x &&
      $obj1->y < $obj2->y + $obj2->height &&
      $obj1->y + $obj1->height > $obj2->y;
}
?>