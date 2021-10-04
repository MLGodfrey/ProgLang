<?php
//random strategy
function random()
{ //just randomly places a coin
    $move = rand(0, 6);
    if (!colFull($move)) {
        return $move;
    }
    return random();
}
