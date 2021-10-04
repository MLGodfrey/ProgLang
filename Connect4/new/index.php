<?php
//strategy and identifier getters


$strat = array( "Smart", "Random" );

//gets strategy from url
$strategy = $_GET[ "strategy" ];

//set unique id
$id = uniqid();

identifyStrategy();


function saveGame(){ //saving game as <uniqid>.txt
	#board
	$board = array(
			array (0,0,0,0,0,0,0),
	
			array (0,0,0,0,0,0,0),
	
			array (0,0,0,0,0,0,0),
	
			array (0,0,0,0,0,0,0),
	
			array (0,0,0,0,0,0,0),
	
			array (0,0,0,0,0,0,0)
	);
	$height = array( 5, 5, 5, 5, 5, 5, 5 );
	global  $strategy, $id;
	$file = fopen( "../../../writable/$id",'w');
	fwrite( $file, json_encode( array ( $strategy, $board, $height ) ) );
	fclose( $file );
}


function identifyStrategy(){ //identifying strategy
	global $strategy, $strat;
	if( $strategy == null ){ echo json_encode( array('response' => false, 'reason' => "Strategy Unknown") ); return; }
	foreach($strat as $str ){
		if( $str == $strategy ){ handleStrategy(); return; } #found given strategy
	}
	echo json_encode( array('response' => false, 'reason' => "Unknown strategy") );
}

function handleStrategy(){ //game response
	global $id;
	saveGame();
	echo json_encode( array('response' => true, 'pid' => $id) );
}


