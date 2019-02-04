<?php 
/*
  Plugin Name: Simple wordpress hooks speed debug
  Plugin URI: https://dind.biz/
  Description: Wordpress Hooks Speed Calculator
  Version: 1.0.0
  Author: Fstarlike
  Author URI: https://www.dind.biz/
*/

$GLOBALS['dbgr_hooks_data'] = [ 
    'dbgr_hooks_data' => [
        'start' => time(),
        'dir'   => ABSPATH . '' // The path to data storage
    ]  
];

add_action( 'all', function ( $tag ) {
	global $dbgr_hooks_data;
	$bt = debug_backtrace();
	$id = 0;
	foreach ( $bt as $b ) {
		$dbgr_hooks_data[$tag]['data'][$id]['file'] = $b['file'];
		$dbgr_hooks_data[$tag]['data'][$id]['line'] = $b['line'];
		$id++;
	}
	$dbgr_hooks_data[$tag]['start'] = time();

} , -66666 );

add_action( 'all', function ( $tag ) {
	global $dbgr_hooks_data;
	$ru = time();
	$execution_time = ( $ru - $dbgr_hooks_data[$tag]['start'] );
	$dbgr_hooks_data[$tag]['end'] = $execution_time;
} , 66666 );

add_action( 'shutdown', function () {
	global $dbgr_hooks_data;

	$ru = time();
	$execution_time = ( $ru - $dbgr_hooks_data['dbgr_hooks_data']['start'] );
	$all = 0;

	foreach ( $dbgr_hooks_data as $key ) {
		if ( isset ( $key['end'] ) ) {
			$all = $all + $key['end'];
		}
	}

	$dbgr_hooks_data['dbgr_hooks_data']['all'] = $all;
	$dbgr_hooks_data['dbgr_hooks_data']['end'] = $execution_time;

	file_put_contents( $dbgr_hooks_data['dbgr_hooks_data']['dir'], json_encode( $dbgr_hooks_data ) );
} , 66666 );

?>