
<?php

$json_events_path = $path . '/json/events/events_' . $_GET['id'] . '.json'; 

if (!file_exists($json_events_path)) { 

    $curl_event = curl_init();
    
    curl_setopt_array($curl_event, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures/events?fixture=' . $_GET['id'], 
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
    
    $response_event = curl_exec($curl_event);
    
    curl_close($curl_event);
    
      
if ( ($_GET['date'] < date('Y-m-d', strtotime('today')))) {

  $json_file_ev = fopen($json_events_path, "w");
  
  fwrite($json_file_ev, $response_event);
  
  fclose($json_file_ev);
  }
  
  $response_event = json_decode($response_event, true);
  
  }
  
  else {
    $response_json = file_get_contents($json_events_path, true);
  
    $response_event= json_decode($response_json, true);
  
  }

$num_events = $response_event['results'];

$min_playing_minute = 
$response_event['response'][$num_events-1]['time']['elapsed'] + 
$response_event['response'][$num_events-1]['time']['extra'];

$home_team_events = array();
$away_team_events = array();
$all_team_events = array();

$home_team_goals = array();
$away_team_goals = array();

 for ($i = 0; $i < $num_events; $i++) {
      
    if (($response_event['response'][$i]['team']['name'] === $homeTeam) && 
        ($response_event['response'][$i]['type'] === 'Goal' || 
        $response_event['response'][$i]['type'] === 'Card' ||
        $response_event['response'][$i]['type'] === 'subst')) {
        array_push($all_team_events, [ 
        'team' => $homeTeam,
        'type' => $response_event['response'][$i]['type'], 
        'detail' => $response_event['response'][$i]['detail'],
        'elapsed' => $response_event['response'][$i]['time']['elapsed'] + $response_event['response'][$i]['time']['extra'], 
        'name' => $response_event['response'][$i]['player']['name'],
        'assist_name' => $response_event['response'][$i]['assist']['name'],
        'comments' => $response_event['response'][$i]['comments']
        ]);
    }

     if (($response_event['response'][$i]['team']['name'] === $awayTeam) && 
        ($response_event['response'][$i]['type'] === 'Goal' || 
        $response_event['response'][$i]['type'] === 'Card' || 
        $response_event['response'][$i]['type'] === 'subst')) {
        array_push($all_team_events, [ 
       'team' => $awayTeam,
        'type' => $response_event['response'][$i]['type'], 
        'detail' => $response_event['response'][$i]['detail'],
        'elapsed' => $response_event['response'][$i]['time']['elapsed'] + $response_event['response'][$i]['time']['extra'], 
        'name' => $response_event['response'][$i]['player']['name'],
        'assist_name' => $response_event['response'][$i]['assist']['name'],
        'comments' => $response_event['response'][$i]['comments']
        ]);
    }
  }
 
  usort($all_team_events, function($a, $b) {
    return $a['elapsed'] <=> $b['elapsed'];
  });

    $prevent_loop = true;

    if (in_array($matchStatus, $statusInPlay) && ($num_events > 0))   
    { 
      echo '<div id="play_min"> min. '. $min_playing_minute . "'</div>"; 
    }
              
        echo '<div class="main_container_event">';
              
        for($i=0; $i < sizeof($all_team_events); $i++) {

          if ($all_team_events[$i]['team'] === $homeTeam) {

             echo '<div class= "event_container"><span class="align-left">'; 
          }

            else if ($all_team_events[$i]['team'] === $awayTeam) {
              echo '<div class= "event_container"><span class="align-right">'; 
            }
         
            if (array_key_exists($all_team_events[$i]['type'], $array_type)
             || array_key_exists($all_team_events[$i]['detail'], $array_type)
            ) 
            {
                echo
              
                '<img id="type_pic" src=' . $path . '/img/' . 
               $array_type[$all_team_events[$i]['type']] . 
               $array_type[$all_team_events[$i]['detail']] . ' ' . '">  ' .    
            
               (sizeof(explode('-', $all_team_events[$i]['elapsed'])) > 1 ? 
               explode('-', $all_team_events[$i]['elapsed'])[1]:
 
                  $all_team_events[$i]['elapsed']) . "' " .

               $all_team_events[$i]['name'];

               if ($all_team_events[$i]['type'] === 'subst') {
                echo ' (voor ' . $all_team_events[$i]['assist_name'] . ')';
               }

               if ($all_team_events[$i]['comments']) {

                if (array_key_exists($all_team_events[$i]['comments'], $array_comments))
                { 
                  echo ' <i>(' . $array_comments[$all_team_events[$i]['comments']] . ')</i>';
                  echo ($all_team_events[$i]['detail'] === 'Missed Penalty' ? '<span class="red"> '. strtoupper('<strong>gemist</strong>') . '</span>' : null);  
                    
              } else {

               echo ' <i>(' . $all_team_events[$i]['comments'] . ')</i>';
              }
               }
            };
           
            if (array_key_exists($all_team_events[$i]['detail'], $array_goal) &&
            ($all_team_events[$i]['comments'] != 'Penalty Shootout'))
                 { echo ' (' . $array_goal[$all_team_events[$i]['detail']] . ')'; 
               }
               echo '</div>';
         }
        
       echo '
       </span>
       </div> 
       </div>
     </div>';

         
?>