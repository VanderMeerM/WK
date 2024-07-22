
<?php 

$league_id = 1;
$path= './WK';
$current_wc_season = 2022;

$seasonInUrl = $_GET['season'];
$startSeasonInUrl = $wc_seasons[$seasonInUrl]['start'];
$endSeasonInUrl = $wc_seasons[$seasonInUrl]['end'];

if (!$_COOKIE['selected_wc_league_season'] && $_GET['season']) {
  setcookie('selected_wc_league_season', $_GET['season'], time() + 3600, "/");
}

?>

<script> 
let leagueId = <?php echo json_encode($league_id) ?>; 
let path = <?php echo json_encode($path) ?>; 


</script>

<?php 
echo '
<div class="title_container"> 
<div id="logo">
 <img src= "https://media.api-sports.io/football/leagues/' . $league_id . '.png"/> 
 
</div>

<div class= "btn_container"> ' . '

<div id="header_info">'. 
$headerinfo . '
</div>
<p>
<form action=" " method="post">

<label for="season_selection">Jaar</label>

<select name="season_selection" onchange="this.form.submit()">'; 

foreach ($wc_seasons as $key=>$value) {

 echo '<option ' . (($key == intval($_COOKIE['selected_wc_league_season']) || $key == $_GET['season']) ? 'selected ' : null) . 'value='. $key . '> ' . $key . '</option>';
}

echo '
</select>
</form>';


echo '
/<label for="datepicker">Datum</label>
<input type="text" name="datepicker" id="datepicker" value = ' . (!$_GET['date'] ? date($selectedWCSeason . '-m-d') : $_GET['date']) . '>

</div>
</div>
</div>
</div>';


$day = $_GET['date'];

if (!$_GET['date']) { 

  setcookie('selected_wc_league_season', $current_wc_season, time() + 3600, "/");
  $start_date_last_wc_season = $wc_seasons[$current_wc_season]['start'];
  $end_date_last_wc_season = $wc_seasons[$current_wc_season]['end'];
  ?>
  <script>
  let currentWCSeason = <?php echo json_encode($current_wc_season) ?>;
  let startDateLastWCSeason = <?php echo json_encode($start_date_last_wc_season) ?>;
  let endDateLastWCSeason = <?php echo json_encode($end_date_last_wc_season) ?>;

  sessionStorage.setItem('selectedWCLeagueSeason', currentWCSeason);
  sessionStorage.setItem('startWCLeagueSeason', startDateLastWCSeason);
  sessionStorage.setItem('endWCLeagueSeason', endDateLastWCSeason);

  window.open(`${path}.php?season=${currentWCSeason}&league=${leagueId}&date=${endDateLastWCSeason}`, '_self');
  </script>
  <?php
}

?>

<script>

let selectedWCSeason;
let startWCSeason;
let endWCSeason;
let seasonInURL;
let startSeasonInUrl;
let endSeasonInUrl;

if ((!sessionStorage.getItem('startWCLeagueSeason')) || 
(!sessionStorage.getItem('endWCLeagueSeason'))) {

seasonInURL = <?php echo json_encode($seasonInUrl) ?>;
startSeasonInUrl =  <?php echo json_encode($startSeasonInUrl) ?>;
endSeasonInUrl =  <?php echo json_encode($endSeasonInUrl) ?>;
sessionStorage.setItem('selectedWCLeagueSeason', seasonInURL);
sessionStorage.setItem('startWCLeagueSeason', startSeasonInUrl);
sessionStorage.setItem('endWCLeagueSeason', endSeasonInUrl);
};

</script>

<?

if(isset($_POST["season_selection"])){
  $selectedWCSeason = $_POST['season_selection'];
  setcookie('selected_wc_league_season', $selectedWCSeason, time() + 3600, "/");
  $startWCSeason = $wc_seasons[$selectedWCSeason]['start'];
  $endWCSeason = $wc_seasons[$selectedWCSeason]['end'];
  ?>

  <script>
    selectedWCSeason =  <?php echo json_encode($selectedWCSeason) ?>;
    startWCSeason = <?php echo json_encode($startWCSeason) ?>;
    endWCSeason =  <?php echo json_encode($endWCSeason) ?>;
    sessionStorage.setItem('selectedWCLeagueSeason', selectedWCSeason);
    sessionStorage.setItem('startWCLeagueSeason', startWCSeason);
    sessionStorage.setItem('endWCLeagueSeason', endWCSeason);

  window.open(`${path}.php?season=${selectedWCSeason}&league=${leagueId}&date=${endWCSeason}`, '_self');

  </script>
  <?php

   }
     
 ?>

 <script>

$( "#datepicker" ).datepicker({
  monthNames: [ "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December" ],
  dayNamesMin: [ "Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za" ],
   dateFormat: "yy-mm-dd",
   minDate: new Date(sessionStorage.getItem('startWCLeagueSeason', startWCSeason)),
   maxDate: new Date(sessionStorage.getItem('endWCLeagueSeason', endWCSeason))
    
 });

  
  $( "#datepicker" ).on('change', function() {

    let selectedDateInPicker = document.getElementById('datepicker').value;
   window.open(`${path}.php?season=${sessionStorage.getItem('selectedWCLeagueSeason', selectedWCSeason)}&league=${leagueId}&date=${selectedDateInPicker}`, '_self')
 });
  </script>
  




