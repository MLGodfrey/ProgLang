<?php

$game_info = getGameInfo();

$id = $_GET["pid"];

$board = $game_info[1];

//begin move
moveStrategy();

//save game
save();



