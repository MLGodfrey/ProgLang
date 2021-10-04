<?php

function save()
{
    global $strategy, $id, $board, $height;
    $file = fopen("../writable/$id", 'w');
    fwrite($file, json_encode(array($strategy, $board, $height)));
    fclose($file);
}