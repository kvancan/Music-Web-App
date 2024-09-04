
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
         for($i= 0;$i<7;$i++){
            $song["link"] = $final["tracks"][$i]["album"]["external_urls"]["spotify"];
            $song["artist"] = $final["tracks"][$i]["album"]["artists"][0]["name"];
            $song["name"] = $final["tracks"][$i]["name"];
            $song["preview"] = $final["tracks"][$i]["preview_url"];
            $song["images"] = $final["tracks"][$i]["album"]["images"][0]["url"];
            array_push($songs, $song);
        }

      return $songs;  
}
if (isset($_GET["artist"])){
    $info = add_array();
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Music-Web-App</title>
  <link href="https://fonts.googleapis.com/css?family=Knewave&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'><link rel="stylesheet" href="./style.css">

</head>
<body>
  <div style="
    width: 10%;
    height: 36px;
    border-radius: 50px;
    text-align: center;
    background-color: white;
    width: 19%;
    height: 38px;
    border-radius: 50px;
    text-align: center;
    ">
  <input type="text" id="search" form="artist" name="artist" style="height: 99%;width: 79%;border: 0;outline:none"  >
  <a onclick="submitform()" ><ion-icon name="search-outline"></ion-icon></a>
</div>
    <div class="album-cover">
    <form id="artist" method="GET">
      <div class="swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img
              src="<?= $info[0]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[0]["url"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
          <div class="swiper-slide">
            <img
              src="<?= $info[1]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[1]["link"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
          <div class="swiper-slide">
            <img
              src="<?= $info[2]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[2]["link"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
          <div class="swiper-slide">
            <img
              src="<?= $info[3]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[3]["link"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
          <div class="swiper-slide">
            <img
              src="<?= $info[4]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[4]["link"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
          <div class="swiper-slide">
            <img
              src="<?= $info[5]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[5]["link"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
          <div class="swiper-slide">
            <img
              src="<?= $info[6]["images"] ?>" />
            <div class="overlay">
              <a
                href="<?= $info[6]["link"] ?>"
                target="_blank"
                ><ion-icon name="radio-outline"></ion-icon
              ></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="music-player">
      <h1>Title</h1>
      <p>Song Name</p>

      <audio id="song">
        <source src="song-list/Luke-Bergs-Gold.mp3" type="audio/mpeg" />
      </audio>

      <input type="range" value="0" id="progress" />

      <div class="controls">
        <button class="backward">
          <i class="fa-solid fa-backward"></i>
        </button>
        <button class="play-pause-btn">
          <i class="fa-solid fa-play" id="controlIcon"></i>
        </button>
        <button class="forward">
          <i class="fa-solid fa-forward"></i>
        </button>
      </div>
    </div>
  </body>
<!-- partial -->
  <script src='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js'></script>
<script src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js'></script>
<script src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js'></script>
<script type="text/javascript" > 

  Window.songs = [
  {
    title: "<?= $info[0]["name"] ?>",
    name: "<?= $info[0]["artist"] ?>",
    source:
      "<?= $info[0]["preview"] ?>",
  },
  {
    title: "<?= $info[1]["name"] ?>",
    name: "<?= $info[1]["artist"] ?>",
    source:
      "<?= $info[1]["preview"] ?>",
  },
  {
    title: "<?= $info[2]["name"] ?>",
    name: "<?= $info[2]["artist"] ?>",
    source:
      "<?= $info[2]["preview"] ?>",
  },
  {
    title: "<?= $info[3]["name"] ?>",
    name: "<?= $info[3]["artist"] ?>",
    source:
      "<?= $info[3]["preview"] ?>",
  },
  {
    title: "<?= $info[4]["name"] ?>",
    name: "<?= $info[4]["artist"] ?>",
    source:
      "<?= $info[4]["preview"] ?>",
  },

  {
    title: "<?= $info[5]["name"] ?>",
    name: "<?= $info[5]["artist"] ?>",
    source:
      "<?= $info[5]["preview"] ?>",
  },
  {
    title: "<?= $info[6]["name"] ?>",
    name: "<?= $info[6]["artist"] ?>",
    source:
      "<?= $info[6]["preview"] ?>",
  },
];

function submitform(){

  document.getElementById("artist").submit();

}

document.getElementById('search').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            submitform();
        }
    })
 </script>
<script type="text/javascript" src="./script.js"></script>
</body>
</html>
