
<?php

$json_lineup_path = $path . '/json/lineups/lineup_' . $_GET['id'] . '.json'; 

if (!file_exists($json_lineup_path)) { 

    $curl_lineup = curl_init();
    
    curl_setopt_array($curl_lineup, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures/lineups?fixture=' . $_GET['id'], 
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
    
    $response_lineup = curl_exec($curl_lineup);
    
    curl_close($curl_lineup);
    
      
if ( ($_GET['date'] < date('Y-m-d', strtotime('today')))) {

  $json_file_lu = fopen($json_lineup_path, "w");
  
  fwrite($json_file_lu, $response_lineup);
  
  fclose($json_file_lu);
  }
  
  $response_lineup = json_decode($response_lineup, true);
  
  }
  
  else {
    $response_json = file_get_contents($json_lineup_path, true);
  
    $response_lineup= json_decode($response_json, true);
  
  }

  $num_lineups = $response_lineup['results'];

$home_team_lineup = array();
$away_team_lineup = array();

$home_startXI = array();
$away_startXI = array();

$home_sub = array();
$away_sub = array();

if (($response_lineup['response'][0]['startXI'] > 0 ) || 
($response_lineup['response'][0]['startXI'] >0 )) {

for ($i=0; $i < sizeof($response_lineup['response'][0]['startXI']); $i++) {

    array_push($home_startXI, [
    'name' => $response_lineup['response'][0]['startXI'][$i]['player']['name'],
    'number' => $response_lineup['response'][0]['startXI'][$i]['player']['number'],
    'position' => $response_lineup['response'][0]['startXI'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup['response'][1]['startXI']); $i++) {

    array_push($away_startXI, [
    'name' => $response_lineup['response'][1]['startXI'][$i]['player']['name'],
    'number' => $response_lineup['response'][1]['startXI'][$i]['player']['number'],
    'position' => $response_lineup['response'][1]['startXI'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup['response'][0]['substitutes']); $i++) {

    array_push($home_sub, [
    'name' => $response_lineup['response'][0]['substitutes'][$i]['player']['name'],
    'number' => $response_lineup['response'][0]['substitutes'][$i]['player']['number'],
    'position' => $response_lineup['response'][0]['substitutes'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup['response'][1]['substitutes']); $i++) {

    array_push($away_sub, [
    'name' => $response_lineup['response'][1]['substitutes'][$i]['player']['name'],
    'number' => $response_lineup['response'][1]['substitutes'][$i]['player']['number'],
    'position' => $response_lineup['response'][1]['substitutes'][$i]['player']['pos']
    ]);
}


for ($i = 0; $i < $num_lineups; $i++) {
      
    if ($response_lineup['response'][$i]['team']['name'] === $homeTeam) 
       {
        array_push($home_team_lineup, [ 
        'team' => $homeTeam,
        'formation' => $response_lineup['response'][$i]['formation'], 
        'coach' => $response_lineup['response'][$i]['coach']['name'],
        'coach_img' => $response_lineup['response'][$i]['coach']['photo'] 
       ]);
    }

    if ($response_lineup['response'][$i]['team']['name'] === $awayTeam) 
    {
     array_push($away_team_lineup, [ 
     'team' => $awayTeam,
     'formation' => $response_lineup['response'][$i]['formation'], 
     'coach' => $response_lineup['response'][$i]['coach']['name'],
     'coach_img' => $response_lineup['response'][$i]['coach']['photo'] 
        ]);
    }
  }


  echo '

  <div class="btn_lineup">
  <button id="show_hide_lineup"> </button>
  </div>
  
  <div id="show_hide"> 
  <div class="main_container_lineup_coach">
  <div class="lineup_container">
  <div>' 
  . $home_team_lineup[0]['formation'] .
  '</div>' .
 
  '<u>Coach:</u> ' . $home_team_lineup[0]['coach'] . 
  '<div>
  <img class="img_coach" src=' . $home_team_lineup[0]['coach_img'] . '>
  </div>
  </div> 

  <div class="lineup_container">
  <div>' 
  . $away_team_lineup[0]['formation'] .  
  '</div>' .
 
  '<u>Coach:</u> ' . $away_team_lineup[0]['coach'] . 
  '<div>
  <img class="img_coach" src=' . $away_team_lineup[0]['coach_img'] . '>
  </div>
  </div>
  </div>'; 

  echo '<div id="start_sub_team">Basiselftal</div>';

     $prevent_loop = true;

     echo '<div class="main_container_lineup">

     <div class="main_container_lineup_home">';

     for ($i=0; $i < sizeof($home_startXI); $i++ ) {

        echo  '    
        <div class="lineup_container"><span class="align-left">' .
        $home_startXI[$i]['number'] . '. ' . $home_startXI[$i]['name'] . 
        '</span></div>';
     }

     echo '</div>
    <div class="main_container_lineup_away">';


     for ($i=0; $i < sizeof($away_startXI); $i++ ) {

        echo '
        <div class="lineup_container"><span class="align-left">' .
        $away_startXI[$i]['number'] . '. ' . $away_startXI[$i]['name'] . 
        '</span></div>';
     }

     echo '</div>
     </div>';

     echo '<div id="start_sub_team">Wisselspelers</div>';

     echo '<div class="main_container_lineup">

     <div class="main_container_lineup_home">';

     for ($i=0; $i < sizeof($home_sub); $i++ ) {

        echo  '    
        <div class="lineup_container"><span class="align-left">' .
        $home_sub[$i]['number'] . '. ' . $home_sub[$i]['name'] . 
        '</span></div>';
     }

     echo '</div>
    <div class="main_container_lineup_away">';


     for ($i=0; $i < sizeof($away_sub); $i++ ) {

        echo '
        <div class="lineup_container"><span class="align-left">' .
        $away_sub[$i]['number'] . '. ' . $away_sub[$i]['name'] . 
        '</div>';
     }

     echo '</div>
     </div>
     </div>';

    }
    else {
        echo '<div class="nomatches"> Geen details beschikbaar </div>';

    }

?>

<script>
    let showLineup = true;

    const showHideLineup = document.getElementById('show_hide_lineup');

    const showHideId = document.querySelector('#show_hide');

    showHideLineup.innerText = 'Toon opstellingen';
    showHideId.setAttribute('class', 'hide');

    showHideLineup.addEventListener('click', () => {

        showLineup = !showLineup;

        if (showLineup) {
            showHideId.setAttribute('class', 'hide');
            showHideLineup.innerText = 'Toon opstellingen';       
        } 
        else {
            showHideId.removeAttribute('class', 'hide');
            showHideLineup.innerText = 'Verberg opstellingen';
        }       

        })
</script>