<?php
function moveStrategy()
{

    $given_move = $_GET["move"];

    $game_info = getGameInfo();

    $strategy = $game_info[0];


    $height = $game_info[2];


    if (!place_at($given_move, 1)) { //checks if coin placement is possible
        echo json_encode(array('response' => false, 'reason' => "column " . $given_move . " is Full!"));
        return;
    }

//checks if player won
    $points = checkWin(4, $height[$given_move] + 1, $given_move);

//if player won, end game
    if (sizeof($points) != 0) {
        echo json_encode(array('response' => true,
                'ack_move' => array('slot' => (int)$given_move,
                    'isWin' => !(sizeof($points) == 0),
                    'isDraw' => tie(),
                    'row' => $points
                )
            )
        );
        return;
    }

    /* This section decides how to choose a move from chosen strategy and handles error of unknown one */


    if ($strategy == "Random") {
        $move = random();
        place_at($move, 2);
    } else if ($strategy == "Smart") {
        $move = smart();
        place_at($move, 2);
    } else {
        echo json_encode(array('response' => false, 'reason' => "Unknown Strategy"));
        return;
    }

#points of strategy's winning line if it exists
    $points2 = checkWin(4, $height[$move] + 1, $move);

#game play information in json displayed
    echo json_encode(
        array('response' => true,
            'ack_move' => array('slot' => (int)$given_move,
                'isWin' => !(sizeof($points) == 0),
                'isDraw' => tie(),
                'row' => $points
            ),
            'move' => array(
                'slot' => $move,
                'isWin' => !(sizeof($points2) == 0),
                'isDraw' => tie(),
                'row' => $points2
            )
        )
    );
}

function findLine($player)
{ //searches for possible wins / blocks
    global $board, $height;
    for ($col = 0; $col < 7; $col++) {
        if (!colFull($col)) {
            $board[$height[$col]][$col] = $player;
            if (sizeof(checkWin(4, $height[$col], $col)) != 0) {
                remove($col);
                return $col;
            }
            remove($col);
        }
    }
    return -1;
}