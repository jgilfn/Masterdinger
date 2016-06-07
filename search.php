<html lang="en">
<?php

include "head.php";

require_once "functions.php";
require_once "summoner.php";

include "vars.php";

?>

<body>
  <div class="background" style="height: 100%;"></div>

  <?php
  $isSearching = true;
  include "header.php";
  ?>

<?php

//One summoner compare mode
if (isset($_GET["Summoner"]) && isset($_GET["Region"]))
{

  if (!isset($_GET["Sort"]))
  {
    $_GET["Sort"] = "";
  }

  $nowTime = (new \DateTime())->format('Y-m-d H:i:s');
  $region = summoner_info_array_name($_GET["Region"]);
  $summoner = summoner_info_array_name($_GET["Summoner"]);

  //Gets current version for that region
  $version = getVersion($apikey, $region);

  $SummonerData = new SummonerData;

  $SummonerData->getData($summoner, $region, $nowTime, $apikey);
  $SummonerData->getChampions($apikey);

  if ($SummonerData->notfound)
  {
    showNotFound();
  }
  else {

  ?>

  <div class="user">
	<div class="container">
        <center><h2 style="color: #e4be73; margin: 0 auto;"><?php echo $SummonerData->summoner; ?></h2>
        <h3 style="margin-top:5px; margin-bottom:10px">Level <?php echo $SummonerData->SummonerInfo['summonerLevel']; ?></h3>

        <img align="middle" src='http://ddragon.leagueoflegends.com/cdn/<?php echo $version; ?>/img/profileicon/<?php echo $SummonerData->SummonerInfo['profileIconId']; ?>.png'/>

        <h4><?php echo $SummonerData->totalMasteryPoints; ?> <span style="color: #e4be73;">Mastery Points</span></h4>
        <p><?php echo $SummonerData->lastUpdated; ?></p></center>

  </div>
  </div>
  <div class="organization-bar" id="organization-bar">
	<div class="container">
            <div class="row">
			  <div class="searchChampion col-md-3">
			  <div class="form-group">
					<input type="text" maxlength="16" id="searchChampionInput" placeholder="Search Champion">

          <script>
          $('#searchChampionInput').on('input', function(){

              var txt = $('#searchChampionInput').val();
              if (txt == "")
              {
                //If is blank then scroll to top
                $('html, body').stop().animate({
                    scrollTop: $('#champion-section').offset().top - 200
                }, 250);

                //and resets everything to original brightness
                $('.championName').each(function(){
                    $(this).parent().parent().css('filter', 'brightness(100%)');
                    $(this).parent().parent().css('-webkit-filter', 'brightness(100%)');
                });
              }
              else {
                var count = 0;

              $('.championName').each(function(){
                 if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1 && count == 0){
                     $(this).parent().parent().css('filter', 'brightness(100%)');
                     $(this).parent().parent().css('-webkit-filter', 'brightness(100%)');

                     //If text matches then it scrolls to element
                     $('html, body').stop().animate({
                         scrollTop: $(this).offset().top - 200
                     }, 250);

                     count++;

                 }
                 else {
                   $(this).parent().parent().css('filter', 'brightness(30%)');
                   $(this).parent().parent().css('-webkit-filter', 'brightness(30%)');

                 }
              });
            }
          });
          </script>
			  </div>
			  </div>
              <div class="order-by col-md-3">
                <form action="search.php" method="get">

                  <input type="hidden" value="<?php echo $_GET["Summoner"]; ?>" name="Summoner"></input>
                  <input type="hidden" value="<?php echo $_GET["Region"]; ?>" name="Region"></input>

                  <select class="form-control" onchange="this.form.submit()" name="Sort">
                    <option value="" <?php if ($_GET["Sort"] == "") { echo "selected=\"selected\""; }?>>Sort by Level</option>
                    <option value="pointsDESC" <?php if ($_GET["Sort"] == "pointsDESC") { echo "selected=\"selected\""; }?>>Sort by Highest Score</option>
                    <option value="points" <?php if ($_GET["Sort"] == "points") { echo "selected=\"selected\""; }?>>Sort by Lowest Score</option>
                    <option value="lastPlayTimeDESC" <?php if ($_GET["Sort"] == "lastPlayTimeDESC") { echo "selected=\"selected\""; }?>>Sort by Last Played</option>
                    <option value="lastPlayTime" <?php if ($_GET["Sort"] == "lastPlayTime") { echo "selected=\"selected\""; }?>>Sort by not played for longest time</option>
                    <option value="highestGradeValueDESC" <?php if ($_GET["Sort"] == "highestGradeValueDESC") { echo "selected=\"selected\""; }?>>Sort by Highest Highest Grade</option>
                    <option value="highestGradeValue" <?php if ($_GET["Sort"] == "highestGradeValue") { echo "selected=\"selected\""; }?>>Sort by Lowest Highest Grade</option>
                    <option value="name" <?php if ($_GET["Sort"] == "name") { echo "selected=\"selected\""; }?>>Sort Alphabetically (A-Z)</option>
                    <option value="nameDESC" <?php if ($_GET["Sort"] == "nameDESC") { echo "selected=\"selected\""; }?>>Sort Alphabetically (Z-A)</option>
                  </select>

                </form>
              </div>
			  <div class="show-missing col-md-2">

          <form action="search.php" method="get">

            <input type="hidden" value="<?php echo $_GET["Summoner"]; ?>" name="Summoner"></input>
            <input type="hidden" value="<?php echo $_GET["Region"]; ?>" name="Region"></input>

    			  <input type="checkbox" onchange="this.form.submit()" id="c1" value="onlyChests" name="Sort" <?php if ($_GET["Sort"] == "onlyChests") { echo "checked"; }?>/><label>Missing Chests Only</label>

          </form>

			  </diV>

              <div class="compareTo col-md-4">
			    <form role="Search" action="compare.php" method="get">
				  <div class="form-group">
					<input type="text" maxlength="16" name="Summoner2" placeholder="Compare to another Summoner">
				  </div>
				  <select name="Region2">
						  <option value="EUW">EUW</option>
						  <option value="NA">NA</option>
						  <option value="EUNE">EUNE</option>
						  <option value="BR">BR</option>
						  <option value="KR">KR</option>
						  <option value="LAN">LAN</option>
						  <option value="LAS">LAS</option>
						  <option value="OCE">OCE</option>
						  <option value="TR">TR</option>
						  <option value="RU">RU</option>
						  <option value="JP">JP</option>
				 </select>
				  <button><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>

				  <input type="hidden" name="Summoner1" value="<?php echo $_GET['Summoner'];?>"></input>
          <input type="hidden" name="BothOwned" value="true"></input>
				  <input type="hidden" name="Region1" value="<?php echo $_GET['Region'];?>"></input>
				</form>

              </div>
          </div>
	</div>
  </div>

  <script>

  var isMobile = false; //initiate as false
  // device detection
  if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
      || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;

      if (!isMobile)
      {

          $(document).ready(function(){
           var scroll_start = 0;
           var startchange = $('#champion-section');
           var offset = startchange.offset();
          if (startchange.length){
           $(document).scroll(function() {
            scroll_start = $(this).scrollTop() + 10;
            if(scroll_start > offset.top) {
              $("#organization-bar").css('position', 'fixed');
              $("#organization-bar").css('width', '100%');
              $("#organization-bar").css('top', '50px');
              $("#organization-bar").css('padding-top', '10px');
              $("#organization-bar").css('z-index', '99');

             } else {
              $('#organization-bar').css('position', 'relative');
              $("#organization-bar").css('top', '0px');
              $("#organization-bar").css('padding-top', '0px');
             }
           });
          }
        });
      }

  </script>


  <div class="champion-section" id="champion-section">
  <div id="searchContainer champion-section" class="container" style="visibility: hidden;">
  <?php

  //Number of total columns
  $totalColumns = 4;

  //Number of the current column
  $col = 0;

  //Sorts array if it is told to
  if (isset( $_GET["Sort"]) && $_GET["Sort"] != "" && $_GET["Sort"] != "onlyChests")
  {
    $sort = str_replace("DESC", "", $_GET["Sort"]);

    $order = SORT_ASC;

    if (strpos($_GET["Sort"], 'DESC') !== false)
    {
      $order = SORT_DESC;
    }

    //orders with the specified sorting
    $raw = array();
    foreach ($SummonerData->Champions as $key => $row) {
      $raw[$key]  = $row[$sort];
    }
    array_multisort($raw, $order, $SummonerData->Champions);
  }
  else
  {
    $sort = 'level';

    $order = SORT_DESC;

    //orders with the specified sorting
    $level = array();
    $points = array();

    $previousLevel = 0;
    foreach ($SummonerData->Champions as $key => $row) {
      $level[$key]  = $row['level'];
      $points[$key] = $row['points'];
    }
    array_multisort($level, $order, $points, $order, $SummonerData->Champions);
  }

  $firstChampionID = $SummonerData->Champions[0]['id'];


  //print every champion
  for ($i = 0; $i < count($SummonerData->Champions); $i++)
  {
    $champion = $SummonerData->Champions[$i];

    //If it is sorting with the highestGradeValue then it will only show the champions who have a grade, the ones who do not won't be shown.
    if (str_replace("DESC", "", $_GET['Sort']) == "highestGradeValue")
    {
      $isValid = ($champion['highestGrade'] != "N/A");
    }
    else {
      $isValid = true;
    }


    //If the only chests value is enabled and the champion owns the chest then it won't show the champion
    if(isset($_GET['Sort']) && $_GET['Sort'] == "onlyChests" && $champion['chest'] == "true")
    {
      $isValid = false;
    }

    if (isset($champion['name']) && $isValid)
    {

      //Dirty fix to the new Level System while the new API is not released DISABLED
      /*if ($champion['level'] == 6 || $champion['level'] == 7)
      {
        $champion['level'] = 5;
      }*/

      if ($col == 0)
      {
        echo "<div class=\"row\">";
      }

      $delay = $col * 100;
      ?>

      <div class="col-md-<?php echo (12/$totalColumns); ?> col-sm-6 col-xs-12 wow fadeInUp" data-wow-delay="<?php echo $delay;?>ms">

        <center>
          <h2 style="color: #dbb365; margin-bottom:0px;" class="championName"><a href="leaderboard.php?SeeMore=Champion<?php echo $champion['id']; ?>" ><?php echo $champion['name']; ?></a></h2>
          <p style="color: #fff; text-transform: capitalize; margin-bottom:20px"><?php echo $champion['title']; ?></p>
        </center>

        <div class="piechart"><?php include "piechart.php"; ?></div>

        <div class="parent">

          <div class="champion">

            <div id="round-<?php echo $champion['id'];?>" class="round level<?php echo $champion['level']; ?>">

              <!--<img align="middle" style="margin-left: auto; margin-right: auto;" src='http://ddragon.leagueoflegends.com/cdn/<?php echo $version; ?>/img/champion/<?php echo $champion['key']; ?>.png'>-->
              <img align="middle" style="margin-left: auto; margin-right: auto;" src='summonericon.php?version=<?php echo $version; ?>&key=<?php echo $champion['key']; ?>'>

              <div id="levelinfo-<?php echo $champion['id'];?>" class="levelinfo">

                <p style="font-size:9px; margin-top: 10px; margin-bottom:0px;"><?php echo $champion['levelTitle']; ?></p>
                <h4 style="margin-top:0px; margin-bottom:0px;"><span class="level<?php echo $champion['level']; ?>">LVL</span> <?php echo $champion['level']; ?></h4>

                <?php
                //If level is level 5 then it will show a big gold number of champion points
                if ($champion['level'] >= 5)
                {
                  ?>

                  <h2 class="level<?php echo $champion['level']; ?>" style="margin-top:5px;"><?php echo getPoints($champion['points']); ?></h2>

                  <?php
                }


                //If level is not five then it will show a progress bar/text
                else
                {
                  //Shows small points
                  //Calculates how many points the level has by adding the 'sincelastlevel' to the 'untilnextlevel'
                  ?>
                    <h4 style="margin-top: 5px;"><?php echo getPoints($champion['points']); ?></h4>

                    <h6 style="margin-bottom: 0px;"><?php echo $champion['championPointsSinceLastLevel'];?>/<?php echo $champion['totalChampionPointsCurrentLevel'];?></h6>
                  <?php
                }
                ?>

                <a data-toggle="tooltip" data-placement="bottom" data-container="body" class="level<?php echo $champion['level']; ?>" data-html="true" title="<?php
                //If points has more than 4 digits then it will show the total champion points
                if (strlen($champion['points']) > 4)
                {
                  echo $champion['points'] . " total champion points</br>";
                }
                //If level is not five then it will show how many points left to reach next level
                if ($champion['level'] < 5)
                {
                  echo $champion['championPointsUntilNextLevel'] . " points until next level</br>";
                }
                ?>
                Last played on <?php echo gmdate("d-m-Y H:i:s", substr($champion['lastPlayTime'], 0, -3)); ?>"> <span>+</span></a>
              </div>
            </div>
          </div>

          <script>
          $(function(){
    $("#round-<?php echo $champion['id'];?>").hover(function(){
      $(this).find("#levelinfo-<?php echo $champion['id'];?>").css("opacity", "1");
    }
                    ,function(){
                        $(this).find("#levelinfo-<?php echo $champion['id'];?>").css("opacity", "0");
                    }
                   );
});
          </script>

          <div class="championBackground">
          </div>

          <img class="levelicon" src="images/levels/level<?php echo $champion['level']; ?>.png"/>
          <div title="Highest Grade" class="grade">
            <h3>
              <?php
              if($champion['highestGrade']!="N/A")
              {
                echo substr($champion['highestGrade'],0,1); ?>
                <sup><?php echo substr($champion['highestGrade'],1); ?></sup>
                <?php
              }
              else
              {
                echo $champion['highestGrade'];
              }?>
            </h3>
          </div>

          <div class="chest">
            <img align="middle" title="<?php if ($champion['chest'] == "true") { echo "Chest Received";} else { echo "No Chest Received";} ?>"class="<?php echo $champion['chest']; ?>" style="margin-left: auto; margin-right: auto;" src='http://vignette1.wikia.nocookie.net/leagueoflegends/images/6/60/Hextech_Crafting_Chest.png/revision/latest?cb=20160115124322'>
          </div>

        </div>

      </div>

      <?php

      if ($col < $totalColumns - 1)
      {
        $col = $col + 1;
      }
      else if ($col == $totalColumns - 1)
      {
        $col = 0;

        //closes the row
        echo "</div>";
      }
    }

  }

  //If last column was not the third one
  if ($col != 3)
  {
    //closes the row
    echo "</div>";
  }

}
}


//TODO: TABELINHA PARA COMPARAR COM O OUTRO PLAYER
//TODO: HIGHSCORES
//TODO: Create other regions database DONE
//TODO: Create Documentation (also explaining how to create a MySQL databased)
//TODO: ADD CHAMPION CHARTS (WITH LEVEL UPGRADES AND XP POINTS)
//TODO: Version finder for the images from lol server DONE, partially, lol images need to be cut

//TODO: Improve the rounding (arredondamentos??) of points DONE
?>

</div>
</div>
</div><!-- Closes container-->
<?php
include "footer.php";

function showNotFound()
{
  ?>
  <div class="main">

  <div class="main-content">
    <div class="block">
      <div class="notfound wow fadeInUp" data-wow-delay="100ms">
        <h2>Error: Summoner not found!</h2>
        <p>"Don't worry, you can learn so much from failure." - Heimerdinger</p>
      </div>

      <div class="searchInput wow fadeInUp">
        <form action="search.php" method="get">
          <input type="text" maxlength="16" name="Summoner" placeholder="Search a Summoner's Champions Mastery"></input>

          <select name="Region">
            <option value="EUW">EUW</option>
            <option value="NA">NA</option>
            <option value="EUNE">EUNE</option>
            <option value="BR">BR</option>
            <option value="KR">KR</option>
            <option value="LAN">LAN</option>
            <option value="LAS">LAS</option>
            <option value="OCE">OCE</option>
            <option value="TR">TR</option>
            <option value="RU">RU</option>
            <option value="JP">JP</option>
          </select>

          <button id="index"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
        </form>
      </div>

      <audio style="opacity: 0;" controls autoplay>
<source src="http://vignette2.wikia.nocookie.net/leagueoflegends/images/b/bd/Heimerdinger.taunt02.ogg/revision/latest?cb=20140228033044" type="audio/ogg">
Your browser does not support the audio element.
</audio>

    </div>
  </div>
</div>
  <?php
}
?>

</body>

<script>


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#compareButton').tooltip({ trigger: 'click'});
    $('#compareButtonTooltip').tooltip({ show: true, placement: 'right', title: 'Compare to another Summoner'}).tooltip('show');

    //Enables a tooltip, so it shows by default
    $('#round-<?php echo $firstChampionID;?>').tooltip({ show: true, placement: 'left', title: 'Hover me!'}).tooltip('show');

    //When hovered, destroys the tooltip
    $( '#round-<?php echo $firstChampionID;?>' ).hover(function() {
      $('#round-<?php echo $firstChampionID;?>').tooltip('destroy');
    });

    //When hovered, destroys the tooltip
    $( '#compareButtonTooltip' ).hover(function() {
      $('#compareButtonTooltip').tooltip('destroy');
    });

});


</script>

</html>
