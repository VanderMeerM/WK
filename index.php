<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WK-wedstrijden</title>  
    <link rel="shortcut icon" href="https://www.api-football.com/public/img/favicon.ico">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css"> 
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
   <link rel="stylesheet" type="text/css" href="./worldcup.css" />   
  
</head>
<body>

<?php 

include('../euro_wc_translations.php');
include('./header.php');


$json_matches_path = './json/matches/matches_date_' . $day . '_.json'; 

if (!file_exists($json_matches_path)) { 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures?season=' . intval($_COOKIE['selected_wc_league_season']) . '&league=' . $league_id .'&date='.$day,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'x-rapidapi-key: f7e1aa54fd70dd93a3c920f503282930',
    'x-rapidapi-host: v3.football.api-sports.io',
    
  ),
));


$response = curl_exec($curl);

curl_close($curl);

if ($day < date('Y-m-d', strtotime('today'))) {

$json_file_mt = fopen($json_matches_path, "w");

fwrite($json_file_mt, $response);

fclose($json_file_mt);
}

$response= json_decode($response, true);

}

else {
  $response_json = file_get_contents($json_matches_path, true);

  $response= json_decode($response_json, true);

}

$numGames= $response['results'];

$prevent_loop = false;


if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

  $homeTeam = $response['response'][$i]['teams']['home']['name'];
  $awayTeam = $response['response'][$i]['teams']['away']['name'];
  $matchId = $response['response'][$i]['fixture']['id'];
  $matchStatus = $response['response'][$i]['fixture']['status']['short'];
  
  if ((!$_GET['id']) || ($_GET['id'] && $_GET['id'] == $matchId)) {

  echo '
  <div class="main_container">'; 
  
  if (!$_GET['id']) {

    if (!$_GET['date']) {
      $_GET['date'] = date('Y-m-d', strtotime('today'));
    }

    echo '<a href="' . $_SERVER['PHP_SELF'] . '?date='. $_GET['date'] . '&id=' . $matchId . '">';
  }

  echo '
  <div class="country_container">
  <div class="flag_container">
  <img src="'.$response['response'][$i]['teams']['home']['logo'] . '"/></div>'; 

  if (array_search($homeTeam, $countries)) { echo array_search($homeTeam, $countries); } 
  else { echo $homeTeam; }  
  
 // Check if hometeam won after penalty shootout 
 if ((strpos($response['response'][$i]['league']['round'], 'Group') === false) && 
 ($response['response'][$i]['teams']['home']['winner']) && 
 (!is_null($response['response'][$i]['score']['penalty']['home']))
 ) 
 { echo '<div class="p_shootout" >w.n.s (' . $response['response'][$i]['score']['penalty']['home'] . '-' . 
  $response['response'][$i]['score']['penalty']['away'] .')</div>';} 
  
  echo '</div>
          <div class="stscore_container">';
                     
         if ($_GET['id']) { echo $response['response'][$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $response['response'][$i]['fixture']['venue']['city'] . '<br>'; }
         
         echo date('H:i', $response['response'][$i]['fixture']['timestamp'])  . '<br>';

         echo 
         '<div class=' . (in_array($matchStatus, $statusInPlay)? '"score red"' : "score") . '>' . 
         $response['response'][$i]['goals']['home'] . '-' . 
         $response['response'][$i]['goals']['away'];
          
         echo '<div style="font-size:15pt">'. (array_key_exists($matchStatus, $status)? 
         $status[$matchStatus] : null) . 
          '</div>
          </div>'; 

         // check if a team won after extra time 
          if ((strpos($response['response'][$i]['league']['round'], 'Group') === false) && 
          (!is_null($response['response'][$i]['score']['extratime']['home'])) &&  
          (is_null($response['response'][$i]['score']['penalty']['home']))) 
          {
           echo '<div>(na verlenging)</div>';
          } 
         

          if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $response['response'][$i]['fixture']['referee'])[0] . 
           '<br>'; 

           
          if (sizeof(explode(',', $response['response'][$i]['fixture']['referee'])) > 1) {

            echo

           (array_search(explode(', ', $response['response'][$i]['fixture']['referee'])[1], $countries) ? 
           '(' . array_search(explode(', ', $response['response'][$i]['fixture']['referee'])[1], $countries) : 
           '(' . explode(', ', $response['response'][$i]['fixture']['referee'])[1]) . ')
           <br></div>'; 
                   
           }

           else {
            echo '</div>';
           }
          }

        echo '</div>';
         
   echo '<div class="country_container">
   <div class="flag_container">
   <img src="'.$response['response'][$i]['teams']['away']['logo'] . '"/></div>'; 

  if (array_search($awayTeam, $countries)) { echo array_search($awayTeam, $countries); } 
  else { echo $awayTeam; }  

// Check if away team won after penalty shootout 
if ((strpos($response['response'][$i]['league']['round'], 'Group') === false) && 
($response['response'][$i]['teams']['away']['winner']) && 
(!is_null($response['response'][$i]['score']['penalty']['home']))
) 
{ echo '<div class="p_shootout" >w.n.s (' . $response['response'][$i]['score']['penalty']['home'] . '-' . 
 $response['response'][$i]['score']['penalty']['away'] .')</div>';} 

  
   echo '</div>
   </div>';

   if (!$_GET['id']) {
    echo '</a>';
   }

   if ($_GET['id']) {
   include ('./events.php');
   include ('./lineup.php');
   }      

  }}
  }}

else {
$selectedDate = date_create($_GET['date']);
echo '<div class="nomatches"> Geen wedstrijden op ' . date_format($selectedDate, 'd-m-Y') . '</div>';
};

?>

</body>
</html>
