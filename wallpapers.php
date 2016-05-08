<?php
//List of wallpapers

$currentpage = basename($_SERVER['PHP_SELF']);

if ($currentpage != "404.php")
{
  $wallpapers = array(
      1 => "http://na.leagueoflegends.com/sites/default/files/upload/art/teambuilder-wallpaper.jpg",
      2 => "http://na.leagueoflegends.com/sites/default/files/upload/art/morgana_vs_ahri_3.jpg",
      3 => "http://na.leagueoflegends.com/sites/default/files/upload/art/akali_vs_baron_3.jpg",
      4 => "http://na.leagueoflegends.com/sites/default/files/upload/art/team_graves_2.jpg",
      5 => "http://na.leagueoflegends.com/sites/default/files/upload/art/riven_v_shyvana_1920x1080.jpg",
      6 => "http://na.leagueoflegends.com/sites/default/files/upload/art/wp_alistar_vs_olaf_1920x1080.jpg",
      7 => "http://na.leagueoflegends.com/sites/default/files/upload/art/promoart_2_1920x1080.jpg",
      8 => "http://na.leagueoflegends.com/sites/default/files/upload/art/promoart_1_1920x1080.jpg"

  );
}
else {

  $wallpapers = array (
  1 => 'images/heimerdinger.jpg'
);
}

?>
