<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
function access(){
    $grant_type = $_ENV["grant_type"];
    $client_id = $_ENV["client_id"];
    $client_secret = $_ENV["client_secret"];
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://accounts.spotify.com/api/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'grant_type='.$grant_type.'&client_id='.$client_id.'&client_secret='.$client_secret.'',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: __Host-device_id=AQALWa-ti_ERtGj1jSYupTPJ-ryKz3zH1wM_anoM9EVa8l30s2d7tgKUJMTgiMSYULm_5czRyYpvYOEQcduSNvi-WGDf9FLANtc'
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $value = json_decode($response, true);
    $access_key = $value['access_token'];
    return $access_key;
}
function apidenvericek(){
    $authorization = access();
    $artist = $_GET["artist"];
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.spotify.com/v1/search?q='.urlencode($artist).'&type=artist',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$authorization.''
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $value = json_decode($response, true);
    $artist_id = $value["artists"]["items"][0]["id"];
    return $artist_id;
}

function top_tracks(){
    $authorization = access();
    $artist_id = apidenvericek();
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.spotify.com/v1/artists/'.$artist_id.'/top-tracks',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$authorization.''
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $value = json_decode($response, true);
    return $value;
}

function add_array(){
    $songs = [];
    $final = top_tracks();
         for($i= 0;$i<5;$i++){
            $songs["artist"] = $final["tracks"][0]["album"]["artists"][0]["name"];
            $song["name"] = $final["tracks"][$i]["name"];
            $song["preview"] = $final["tracks"][$i]["preview_url"];
            $song["images"] = $final["tracks"][$i]["album"]["images"][0]["url"];
            array_push($songs, $song);
        }
    var_dump($songs);
}
if (isset($_GET["artist"])){
    add_array();
}
?>

<!DOCTYPE html>
<html lang="en" >
<head></head>
<body>
<form method="GET">
  Artist: <input type="text" name="artist">
  <input type="submit">
  </body>
  </html>