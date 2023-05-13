// WebSocket spojenie
const ws = new WebSocket("wss://site48.webte.fei.stuba.sk:9000/webte2_zadanie3/hra.php");

// Handler pre otvorenie spojenia
ws.addEventListener('open', function(event) {
    console.log("Connected to WebSocket server.");
});

// Handler pre spracovanie správ od servera
ws.addEventListener('message', function(event) {
    const msg = JSON.parse(event.data);
    switch (msg.type) {
        case 'game_status':
            handleGameStatus(msg.data);
            break;
        case 'lobby_wait':
            handleLobbyWait(msg.data);
            break;
        case 'connection_count':
            handleConnectionCount(msg.data);
            break;
        case 'can_start_game':
            handleCanStartGame();
            break;
        case 'game_update':
            handleGameUpdate(msg.data);
            break;
        default:
            console.warn('Unhandled message type:', msg.type);
            break;
    }
});

// Handler pre uzavretie spojenia
ws.addEventListener('close', function(event) {
    console.log("Disconnected from WebSocket server.");
});

// Handler pre chyby spojenia
ws.addEventListener('error', function(event) {
    console.error("WebSocket error:", event);
});

// Funkcia na odosielanie správ na server
function sendMessageToWS(obj) {
    ws.send(JSON.stringify(obj));
}

// Handler pre kliknutie na tlačidlo vstupu do lobby
$('#btn_enter').on('click', function() {
    const playerName = $('#player_name').val().trim();
    if (playerName === '') {
        alert('Name is required');
        return;
    }
    sendMessageToWS({type: 'enter_lobby', name: playerName});
});

// Handler pre kliknutie na tlačidlo spustenia hry
$('#btn-start').on('click', function() {
    sendMessageToWS({type: 'start_game'});
});

// Handler pre kliknutie na tlačidlo opustenia hry
$('#btn-leave').on('click', function() {
    sendMessageToWS({type: 'leave_game'});
});

// Handler pre klávesové skratky
$(document).on('keydown', function(e) {
    if (e.which === 38 || e.which === 39) { // stlačená šípka nahor alebo doprava
        sendMessageToWS({type: 'paddle_control', direction: 'up'});  
    } else if (e.which === 37 || e.which === 40) { // stlačená šípka nadol alebo doľava
        sendMessageToWS({type: 'paddle_control', direction: 'down'});
    }
});

// Funkcia pre spracovanie správy o stave hry
function handleGameStatus(data) {
    if (data.state === 'Pending') {
    $('#hra').addClass('d-none');
    $('#vstup').removeClass('d-none');
    $('#name_screen').removeClass('d-none');
    $('#wait_screen').addClass('d-none');
    $('#btn-start').addClass('d-none');
    grid = data.grid_size;
    isRunning = false;
    } else if (data.state === 'Running') {
    $('#hra').removeClass('d-none');
    $('#vstup').addClass('d-none');
    isRunning = true;
    requestAnimationFrame(loop);
    }
    }
    
    // Funkcia pre spracovanie správy o čakaní v miestnosti
    function handleLobbyWait(data) {
    $('#name_screen').addClass('d-none');
    $('#wait_screen').removeClass('d-none');
    $('#lobby_player').text(data.name);
    $('#connection-count').text(data.connection_count);
    }
    
    // Funkcia pre spracovanie správy o počte pripojení v miestnosti
    function handleConnectionCount(data) {
    $('#connection-count').text(data);
    }
    
    // Funkcia pre spracovanie správy o spustiteľnosti hry
    function handleCanStartGame() {
    $('#btn-start').removeClass('d-none');
    }
    
    // Funkcia pre spracovanie správy o stave hry
    function handleGameUpdate(data) {
    // window.data = data;
    // window.grid = data.grid_size;
    gamesData = data;
    }