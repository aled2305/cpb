<?php

register_activation_hook(__FILE__, 'my_activation');

function my_activation() {
    if (! wp_next_scheduled ( 'my_hourly_event' )) {
	wp_schedule_event(time(), 'hourly', 'my_hourly_event');
    }
}

add_action('my_hourly_event', 'do_this_hourly');

function do_this_hourly() {
	// do something every hour
}







register_deactivation_hook(__FILE__, 'my_deactivation');

function my_deactivation() {
	wp_clear_scheduled_hook('my_hourly_event');
}








$myvar1 = "test1"; 
$myvar2 = "test2";


$url = 'http://creativepixel.design/rec.php';
$myvars = 'myvar1=' . $myvar1 . '&myvar2=' . $myvar2;

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );

