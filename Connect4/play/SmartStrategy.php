<?php
//smart strategy
function smart()
{ //searches for a potential connect 4 and moves on it
    $move = findLine(2); //to connect 4
    if ($move != -1) {
        return $move;
    }
    $move = findLine(1); // to block connect 4
    if ($move != -1) {
        return $move;
    }
    return random();
}
