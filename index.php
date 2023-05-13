<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();




?>


<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Pong</title>
</head>

<body>

    <div class="row">
        <div class="col-12 bg-clip-content text-black text-center">
            <header>
                <h1>Pong</h1>
            </header>
        </div>
    </div>

    <div class="page-content bg-darkblue d-flex align-items-center justify-content-center">
        <canvas width="620" height="620" id="hra" class="d-none"></canvas>

        <div id="vstup" class="bg-darkblue d-flex flex-column justify-content-center align-items-center" style="width:620px; height:620px">
            <div id="name_screen" class="row w-100">

                <div class="col-8 mb-3 mx-auto text-center">
                    <label for="player_name" class="form-label fs-2 text-white d-block mx-auto">Meno</label>
                    <input type="text" id="player_name" class="form-control bg-primary text-white" required>
                </div>

                <div class="d-grid col-6 mx-auto mt-3">
                    <button type="button" id="btn_enter" class="btn btn-primary">Vstúpte</button>
                </div>
            </div>
            <div id="wait_screen" class="row w-100 d-none">
                <div class="col-8 mb-3 mx-auto text-center">
                    <p class="fs-2 text-white">Vitaj, <span id="lobby_player">Hráč</span></p>
                    <p class="fs-3 text-white">Počet hráčov: <span id="connection-count">1</span></p>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto mt-3">
                    <button type="button" id="btn-start" class="btn btn-light d-none">Hraj</button>
                    <button type="button" id="btn-leave" class="btn btn-light">Ukončiť</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 bg-clip-content text-white text-center bg-primary">
            <footer class="px-2">
                Michal Belan, &copy; 2023
            </footer>
        </div>

    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="hra.js"></script>
</body>

</html>