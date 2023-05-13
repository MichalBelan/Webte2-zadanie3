<?php


use Workerman\Worker;
use Workerman\Lib\Timer;


require_once './vendor/autoload.php';


function send_msg_to_client($connection_id, $type, $data = '')
{
    global $ws_worker;
    $obj = new stdClass();
    $obj->type = $type;
    $obj->data = $data;
    $connection = $ws_worker->connections[$connection_id];
    $connection->send(json_encode($obj));
}

// SSL context.
$context = [
    'ssl' => [
        'local_cert'  => './certifikat/webte_fei_stuba_sk.pem',
        'local_pk'    => './certifikat/webte.fei.stuba.sk.key',
        'verify_peer' => false,
    ]
];

// Vytvorte pracovníka a počúva port 9000, použite protokol Websocket
$ws_worker = new Worker("websocket://0.0.0.0:9000", $context);

// Povolenie SSL. WebSocket+SSL znamená, že Secure WebSocket (wss://). 
// Podobné prístupy pre Https atď.
$ws_worker->transport = 'ssl';

// 4 procesy
$ws_worker->count = 1;

// Pridanie časovača do každého pracovného procesu pri spustení pracovného procesu
$ws_worker->onWorkerStart = function ($ws_worker) {
    Timer::add(0.03, function () use ($ws_worker) {
        global $gameState, $players, $ball, $startingPlayer;
        if ($gameState == State::Pending)
            return;

        $game_data = ['players' => [], 'ball' => []];
        foreach ($players as $key => $value) {
            $game_data['players'][$value->playerSide->name] = [
                'x' => $value->x, 'y' => $value->y, 'width' => $value->width,
                'height' => $value->height, 'lives' => $value->lives, 'name' => $value->player_name
            ];
        }
        if ($ball) {
            $ball->move();
            $game_data['ball'] = [
                'x' => $ball->x, 'y' => $ball->y, 'width' => $ball->width,
                'height' => $ball->height, 'hit_count' => $ball->hit_count
            ];
        }

        if (count($players) == 0) {
            $gameState = State::Pending;
            $startingPlayer = null;
            foreach ($ws_worker->connections as $connection)
                send_msg_to_client($connection->id, 'game_status', ['state' => $gameState->name, 'grid_size' => GRID]);
            return;
        }
        foreach ($ws_worker->connections as $connection)
            send_msg_to_client($connection->id, 'game_update', $game_data);
    });
};

// Vysiela sa pri príchode nového spojenia
$ws_worker->onConnect = function ($connection) {
    // Vypustené po skončení handshake websocket
    $connection->onWebSocketConnect = function ($connection) {
        global $gameState;

        echo "New connection\n";
        send_msg_to_client($connection->id, 'game_status', ['state' => $gameState->name, 'grid_size' => GRID]);
    };
};

$ws_worker->onMessage = function ($connection, $data) {
    $data = json_decode($data, true);
    global $gameState;

    if ($gameState == State::Running) {
        if ($data['type'] == 'paddle_control') {
            global $players;

            if (array_key_exists($connection->id, $players))
                $players[$connection->id]->move($data['direction']);
        }
    } else if ($gameState == State::Pending) {
        if ($data['type'] == 'enter_lobby') {
            global $usersInLobby, $ws_worker;

            $usersInLobby[$connection->id] = $data['name'];
            send_msg_to_client(
                $connection->id,
                'lobby_wait',
                ['name' => $data['name'], 'connection_count' => count($ws_worker->connections)]
            );
        } else if ($data['type'] == 'leave_game') {
            global $usersInLobby;

            unset($usersInLobby[$connection->id]);
            send_msg_to_client($connection->id, 'game_status', ['state' => $gameState->name, 'grid_size' => GRID]);
        } else if ($data['type'] == 'start_game') {
            global $startingPlayer;

            if ($startingPlayer == $connection->id)
                start_game();
        }
    }
    refresh_users();
};

// Vypúšťa sa pri uzavretí spojenia
$ws_worker->onClose = function ($connection) {
    global $usersInLobby, $players;

    echo "Connection closed\n";
    unset($usersInLobby[$connection->id]);
    unset($players[$connection->id]);

    refresh_users();
};

// Spustiť Worker
Worker::runAll();
