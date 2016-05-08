<head>
  <meta http-equiv="Content-Type" content="text/html" charset="utf-8"/>
  <!--TODO:Insert description for the server-->
  <meta name="description" content="Explore your stats, your friend stats, you favorite player stats and compare eachother in Masterdinger, a project that is participaiting in League Of Legends 2016 API contest!" />
  <!--TODO:Insert more keywords for the server-->
  <meta name="keywords" content="League Of Legends, LoL, Masteries, Masterdinger, Stats, Statistics, Champions, Heimerdinger, API Challenge"/>
  <meta name="robots" content="index,follow"/>
  <title>Masterdinger</title>

  <link rel="stylesheet" type="text/css" href="css/main.css"/>
  <link rel="stylesheet" type="text/css" href="css/header.css"/>
  <link rel="stylesheet" type="text/css" href="css/footer.css"/>

  <link rel="icon" href="images/smallicon.ico"/>

  <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no"/>
  <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
  <meta name="GOOGLEBOT" content="index follow"/>
  <meta name="apple-mobile-web-app-capable" content="yes"/>

  <!-- Load JS -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/jquery.waypoints.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.min.css" crossorigin="anonymous"/>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.min.js"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.0.2/Chart.bundle.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css"/>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

  <script type="text/javascript" src="js/main.js"></script>

</head>

<script>
new WOW({mobile: false}).init();
</script>

<!-- Selects random wallpaper from the list of wallpapers -->
<style>

<?php include "wallpapers.php";

$brightness = 50;

//If the page is the search then it will set brightness to 30
if(stripos($_SERVER['REQUEST_URI'], 'search.php'))
{
  $brightness = 30;
}

header("Content-Type: text/html;charset=utf-8");

?>

.background {
  height: 100%;
  width: 100%;

  position: fixed;
  left: 0;
  right: 0;
  z-index: -1;

  display: block;

  -webkit-filter: brightness(<?php echo $brightness;?>%);
  -moz-filter: brightness(<?php echo $brightness;?>%);
  -o-filter: brightness(<?php echo $brightness;?>%);
  -ms-filter: brightness(<?php echo $brightness;?>%);
  filter: brightness(<?php echo $brightness;?>%);

  background: url("<?php echo $wallpapers[rand(1, count($wallpapers))]; ?>") no-repeat center center fixed;


      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;

      -ms-transform: scale(1.2); /* IE 9 */
    -webkit-transform: scale(1.2); /* Safari */
    transform: scale(1.2);

}
</style>
