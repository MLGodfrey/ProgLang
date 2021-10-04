<?php


function remove($col)
{
    global $board, $height;
    $board[$height[$col]][$col] = 0;
}




// Draws win Line
function getPoints($line)
{
    $i = $line[0][0];
    $j = $line[0][1];
    $points = array(0, 0, 0, 0, 0, 0, 0, 0);
    $index = 0;
    while ($i != $line[1][0] || $j != $line[1][1]) {
        $points[$index] = (int)$j;
        $points[$index + 1] = (int)$i;
        $i = $i + $line[2][0];
        $j = $j + $line[2][1];
        $index = $index + 2;
    }
    $points[$index] = $j;
    $points[$index + 1] = $i;
    return $points;
}

#returns end points of winning line to plot
function winLine($h, $w, $dy, $dx, $cons1, $cons2)
{
    $cons1--;
    $cons2--;
    $line = array(array(0, 0), array(0, 0), array(0, 0));
    if ($dy == 1) {
        $line[0][0] = $h - $cons2;
        if ($dx == 0) {
            $line[0][1] = $w;
            $line[1][0] = $h + $cons1;
            $line[1][1] = $w;
        } else {
            $line[0][1] = $w - $cons2;
            $line[1][0] = $h + $cons1;
            $line[1][1] = $w + $cons1;
        }
    } else {
        if ($dy == 0) {
            $line[0][0] = $h;
            $line[0][1] = $w - $cons2;
            $line[1][0] = $h;
        } else {
            $line[0][0] = $h + $cons2;
            $line[0][1] = $w - $cons2;
            $line[1][0] = $h - $cons1;
        }
        $line[1][1] = $w + $cons1;
    }
    $line[2][0] = $dy;
    $line[2][1] = $dx;
    return getPoints($line);
}

// To determine when done
function done($h, $w, $i, $j, $count)
{
    global $board;
    if ($count == 0) {
        return true;
    } // this may be commented out.
    if ($i < 0 || $j < 0) {
        return true;
    }
    if ($i >= 6) {
        return true;
    }
    if ($j >= 7) {
        return true;
    }
    if ($board[$i][$j] != $board[$h][$w]) {
        return true;
    }
    return false;
}

// counts consecutive marks
function consecutive($h, $w, $dy, $dx, $toWin)
{
    $i = $h;
    $j = $w;
    $consecutive = 0;
    for ($count = $toWin; !done($h, $w, $i, $j, $count); $count--) {
        $consecutive++;
        $i = $i + $dy;
        $j = $j + $dx;
    }
    return $consecutive;
}

// checks consecutive in both ways of the line starting from last move
function line($h, $w, $dy, $dx, $toWin)
{
    $cons1 = consecutive($h, $w, $dy, $dx, $toWin);
    $cons2 = consecutive($h, $w, -$dy, -$dx, $toWin);
    if ($cons1 + $cons2 > $toWin) {
        return winLine($h, $w, $dy, $dx, $cons1, $cons2);
    }
    return null;
}

// returns x,y coordinates of winning line if it exists otherwise null
function checkWin($toWin, $h, $w)
{
    $winLine = line($h, $w, 1, 0, $toWin); // vertical
    if ($winLine != null) {
        return $winLine;
    }
    $winLine = line($h, $w, 0, 1, $toWin); // horizontal
    if ($winLine != null) {
        return $winLine;
    }
    $winLine = line($h, $w, 1, 1, $toWin); // doDiagonal
    if ($winLine != null) {
        return $winLine;
    }
    $winLine = line($h, $w, -1, 1, $toWin); // upDiagonal
    if ($winLine != null) {
        return $winLine;
    }
    return array();
}

// get true if the game has been drawn( all slots are full ) and false otherwise
function tie()
{
    global $height;
    for ($j = 0; $j < 7; $j++) {
        if ($height [$j] != -1) {
            return false;
        }
    }
    return true;
}

// place given disk in board and return true, otherwise return false
// this also updates the height to drop disk automatically
function place_at($move, $player)
{
    global $height, $board;
    if (isColFull()) {
        return false;
    }
    $board[$height[$move]--][$move] = $player;
    return true;
}

// it determines if the given move is valid based on space in the board.
function isColFull()
{
    global $height, $given_move;
    if ($height [$given_move] == -1) {
        return true;
    }
    return false;
}

function colFull($move)
{
    global $height;
    if ($height [$move] == -1) {
        return true;
    }
    return false;
}

// extracts and decodes jason info from file
function getGameInfo()
{
    global $id;
    $file = fopen("../../../writable/$id", 'r');
    $temp = fgets($file);
    $game_info = json_decode($temp);
    fclose($file);
    return $game_info;
}