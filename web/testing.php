<?php

set_time_limit(0); 
$APIkey = '824081357f27a5011d08dabe8655705b6772339af2a58e6a15d080f6a2940cb8';
$from = '2020-03-06';
$to = '2020-03-06';

$curl_options = array(
    CURLOPT_URL => "https://allsportsapi.com/api/football/?met=Fixtures&APIkey=$APIkey&from=$from&to=$to",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CONNECTTIMEOUT => 5
);

$curl = curl_init();
curl_setopt_array( $curl, $curl_options );
$result = curl_exec( $curl );

$result = (array) json_decode($result);

foreach ($result['result'] as $item){
    echo '<pre>';
    print_r($item);
    echo '</pre>';
}




