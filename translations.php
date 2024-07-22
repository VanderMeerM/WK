<?php 

$countries = array('Duitsland' => 'Germany','Zwitserland' => 'Switzerland', 'Schotland' => 'Scotland', 'Hongarije' => 'Hungary', 
'Spanje' => 'Spain', 'Italië' => 'Italy', 'Albanië' => 'Albania', 'Kroatië' => 'Croatia', 'Engeland' => 'England', 'Denemarken' => 'Denmark', 'Slovenië' => 'Slovenia','Servië' => 'Serbia', 'Nederland' => 'Netherlands', 'Frankrijk' => 'France', 'Polen' => 'Poland', 'Oostenrijk' => 'Austria', 'Roemanië' => 'Romania', 'Slowakije' => 'Slovakia', 'België' => 'Belgium', 'Oekraine' => 'Ukraïne', 'Tsjechië' => 'Czech Republic', 'Turkije' => 'Türkiye', 'Georgië' => 'Georgia', 'Griekenland' => 'Greece', 'Zweden' => 'Sweden', 
'Ierland' => 'Rep. Of Ireland', 'IJsland' => 'Iceland', 'Rusland' => 'Russia');

$headerinfo = 'Selecteer een jaar en datum. Klik op de wedstrijd voor meer details.';

$status_short = array('1H', 'HT', '2H', 'ET');

$status_nl = array('1e helft', 'Rust', '2e helft', 'Verlenging');

$status = array_combine($status_short, $status_nl);

$statusInPlay = array('1H', 'HT', '2H','ET', 'BT', 'P', 'SUSP', 'INT');


$array_type = array('Goal' => 'football.png', 'Yellow Card' => 'yellow_card.png', 
'Red Card' => 'red_card.png', 'subst' => 'substitute.png');

$array_goal = array(
'Own Goal' => 'eigen goal', 
'Penalty' => 'strafschop', 
'Missed Penalty' => 'strafschop gemist');

$array_comments = array(
  'Foul' => 'overtreding', 
  'Argument' => 'commentaar',
  'Time wasting' => 'tijd rekken',
  'Handball' => 'hands',
  'Unallowed field entering' => 'geen toestemming betreden veld',
  'Holding' => 'vasthouden',
  'Penalty Shootout' => 'penaltyserie',
  'Unsportsmanlike conduct' => 'onsportief gedrag'
);

$wc_seasons = 
[
    2010 => [
      'start' => '2010-06-11',
      'end' => '2010-07-11'  
    ], 

    2014 => [
    'start' => '2014-06-12',
    'end' => '2014-07-13' 
    ], 

    2018 => [
    'start' => '2018-06-14',
    'end' => '2018-07-15' 
    ], 
    
    2022 => [
    'start' => '2022-11-20',
    'end' => '2022-12-18' 
    ]
];


?>
