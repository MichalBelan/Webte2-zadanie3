//Vytvorenie referencie na canvas a jeho 2D kontext
const canvas = document.getElementById('hra');
const context = canvas.getContext('2d');

//Definovanie premenných a konštánt pre hru
var isRunning = false;
var gamesData = null;
var grid =15;

//Hlavná funkcia loop je zodpovedná za vykreslenie hry
function loop() {
    if (gamesData == null) {
        if (isRunning) {
            requestAnimationFrame(loop);
        }
        return;
    }

    //Vykreslenie hracieho poľa
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.fillStyle = "black";
    context.fillRect(0, 0, canvas.width, canvas.height);

    //Vykreslenie rakiet a informácií o hráčoch
    context.fillStyle = 'blue';
    context.font = "30px Arial";
    Object.values(gamesData.players).forEach(playerData => {
        context.fillRect(playerData.x, playerData.y, playerData.width, playerData.height);
        if (playerData.side == 'Left') {
            context.fillText(playerData.name, grid * 7, canvas.height / 2 - 15);
            context.fillText(playerData.lives + ' ❤', grid * 6, canvas.height / 2 + 15);
        } else if (playerData.side == 'Right') {
            context.fillText(playerData.name, canvas.width - grid * 7, canvas.height / 2 - 15);
            context.fillText(playerData.lives + ' ❤', canvas.width - grid * 7, canvas.height / 2 + 15);
        } else if (playerData.side == 'Top') {
            context.fillText(playerData.name, canvas.width / 2, grid * 6);
            context.fillText(playerData.lives + ' ❤', canvas.width / 2, grid * 6 + 30);
        } else if (playerData.side == 'Bottom') {
            context.fillText(playerData.name, canvas.width / 2, canvas.height - grid * 6 - 30);
            context.fillText(playerData.lives + ' ❤', canvas.width / 2, canvas.height - grid * 6);
        }
    });

    //vykresli lopticku
    context.fillRect(gamesData.ball.x, gamesData.ball.y, gamesData.ball.width, gamesData.ball.height);
    context.textAlign = "center";
    context.fillText(gamesData.ball.hit_count, canvas.width / 2, canvas.height / 2);

    //Vykreslenie stien na hracom poli
    context.fillStyle = 'grey';
    if (!Object.keys(gamesData.players).includes('Left')) {
        context.fillRect(0, 0, grid, canvas.height); // left
    }
    if (!Object.keys(gamesData.players).includes('Right')) {
        context.fillRect(canvas.width - grid, 0, grid, canvas.height); 
    }
    if (!Object.keys(gamesData.players).includes('Top')) {
        context.fillRect(0, 0, canvas.width, grid); // top
    }
    if (!Object.keys(gamesData.players).includes('Bottom')) {
        context.fillRect(0, canvas.height - grid, canvas.width, grid); 
    }

    // Vykreslenie stien na hracom poli
    context.fillRect(0, 0, grid * 2, grid * 2); 
    context.fillRect(0, canvas.height - grid * 2, grid * 2, grid * 2); 
    context.fillRect(canvas.width - grid * 2, 0, grid * 2, grid * 2); 
    context.fillRect(canvas.width - grid * 2, canvas.height - grid * 2, grid * 2, grid * 2); 
    context.fillRect(0, 0, grid * 2, grid); 
    context.fillRect(canvas.width - grid * 2, 0, grid * 2, grid); 
    context.fillRect(0, canvas.height - grid, grid * 2, grid); 
    context.fillRect(canvas.width - grid * 2, canvas.height - grid, grid * 2, grid); 

    if (isRunning) {
        requestAnimationFrame(loop);
    }
} 