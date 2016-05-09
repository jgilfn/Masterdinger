<?php

require_once "leaderboard_functions.php";
require_once "functions.php";
require_once "summoner.php";

?>

<html lang="en">
<?php

include "vars.php";

//Gets current version for that region
$version = getVersion($apikey, "euw");

include "head.php";

$time_start = microtime(true);
?>
<body>

  <div class="background"></div>

  <?php include "header.php"; ?>


  <?php if (!isset($_GET['SeeMore'])) { ?>
  <div class="container">
    <div class="row leaderboard">

      <?php showTotalPoints(10 , "col-md-5 col-sm-10", $version, $apikey); ?>

	<div class="col-md-2"></div>

      <?php showChampionPoints(10 , "col-md-5 col-sm-10", $version, $apikey); ?>

    </div>

    <?php showAllChamps(5);
    $time_end = microtime(true);
    $time = $time_end - $time_start;

    //echo $time;

    ?>



  </div>

  <?php } else {
    $argument = $_GET['SeeMore'];

    if ($argument == "TotalPoints")
    {
      ?>

      <div class="container">
        <div class="row leaderboard">

          <?php showTotalPoints(100 , "col-md-12 col-sm-12", $version, $apikey, "false"); ?>

        </div>
      </div>

      <?php
    }
    else if ($argument == "ChampionPoints")
    {
      ?>

      <div class="container">
        <div class="row leaderboard">

          <?php showChampionPoints(100 , "col-md-12 col-sm-12", $version, $apikey, "false"); ?>

        </div>
      </div>

      <?php
    }
    else if (startsWith($argument, "Champion"))
    {
      $id = str_replace("Champion", "", $argument);

      ?>
      <div class="container">
        <div class="row leaderboard">

          <?php showChamp(100, $id, $version, $apikey); ?>

        </div>
      </div>
      <?php
    }

    include "footer.php";

  }
  ?>

</body>
</html>

<?php
function showTotalPoints($number, $mainclass, $version, $apikey, $seemore = "true")
{
  ?>

  <?php if ($number > 20) { ?>
    <center><h2 id="tabletitle" style="margin-bottom: 10vh;">Total Points</h2></center>
    <?php } ?>

  <?php
  $topPlayers = topPlayersMasteryPoints ($number, $apikey);

  if ($number == 100)
  {
    showTopHeader($topPlayers, $version, $apikey);
  }

  ?>

  <div class="<?php echo $mainclass; ?> totalpoints">

    <?php if ($number < 20) { ?><div class="header">
      <center><h2 id="tabletitle">Total Points</h2></center>
      </div><?php } ?>

        <table id="grid-basic" style="margin-bottom: 0px;" class="table table-condensed table-hover table-striped text-center">

            <thead >
                <tr class="table-header" data-align="center">
                    <th data-column-id="pos" data-header-css-class="smallcol" data-type="numeric">Rank</th>
                    <th data-column-id="links" data-formatter="link">Summoner</th>


                  <?php if ($number >= 20) {
                    ?>
                    <th data-column-id="summoner" data-visible="false">Summoner</th>
                    <th data-column-id="region" data-header-css-class="smallcol" data-visible="false">Region</th>
                    <?php } ?>
                    <th data-column-id="points" data-type="numeric" data-order="desc">Points</th>
                </tr>
            </thead>

        <tbody>
            <tr>

              <?php

                for ($i = 0; $i < count($topPlayers); $i++)
                {

                  $player = $topPlayers[$i];

              ?>

                <td><?php echo ($i+1); ?></td>
                <td><a href="search.php?Summoner=<?php echo $player['name']; ?>&Region=<?php echo $player['region']; ?>"><?php echo $player['name']; ?> (<?php echo mb_strtoupper($player['region']); ?>)</a></td>

                <?php if ($number >= 20) { ?>
                <td><?php echo $player['name']; ?></td>
                <td><?php echo mb_strtoupper($player['region']); ?></td>
                <?php } ?>

                <td><?php echo $player['points']; ?></td>

            </tr>

      <?php

        }
       ?>
        </tbody>

        <?php if ($number >= 20) {
          ?>
          <script>
              $("#grid-basic").bootgrid({rowCount: 25,
                caseSensitive: false,
            selection: true,
            multiSelect: true,
            left:true,
            formatters: {
                "link": function (column, row) {
                    return "<a href=\"search.php?Summoner=" + row.summoner + "&Region=" + row.region + "\">" + row.summoner + " (" + row.region + ")</a>";
                    }
                }
              });
          </script>
          <?php
        }?>

    </table>


    <?php if ($seemore == "true") {?><a href="leaderboard.php?SeeMore=TotalPoints" class="btn" role="button">See More</a><?php } ?>
  </div>

  <?php
}

function showChampionPoints($number, $mainclass, $version, $apikey, $seemore = "true")
{
  ?>
  <?php if ($number > 20) { ?>
    <center><h2 id="tabletitle" style="margin-bottom: 10vh;">Champion Points</h2></center>
    <?php } ?>

    <?php
    $topPlayers = topPlayersChampionPoints ($number, $apikey);

    if ($number == 100)
    {
      showTopHeader($topPlayers, $version, $apikey);


      //Shows statistics for all champions
      showStatistics(0);
    }

    ?>
    <div class="<?php echo $mainclass; ?> championTable">


          <?php if ($number < 20) { ?><div class="header">
            <center><h2 id="tabletitle">Champion Points</h2></center>
            </div><?php } ?>

          <table id="grid-basic" style="margin-bottom: 0px;" class="table table-condensed table-hover table-striped text-center">

              <thead >
                  <tr class="table-header" data-align="center">
                      <th data-column-id="pos" data-header-css-class="smallcol" data-type="numeric">Rank</th>
                      <th data-column-id="Champion">Champion</th>
                      <th data-column-id="links" data-formatter="link">Summoner</th>


                    <?php if ($number >= 20) {
                      ?>
                      <th data-column-id="summoner" data-visible="false">Summoner</th>
                      <th data-column-id="region" data-header-css-class="smallcol" data-visible="false">Region</th>
                      <?php } ?>
                      <th data-column-id="points" data-type="numeric" data-order="desc">Points</th>
                  </tr>
              </thead>

          <tbody>
              <tr>

                <?php

                  for ($i = 0; $i < count($topPlayers); $i++)
                  {

                    $player = $topPlayers[$i];

                ?>

                  <td><?php echo ($i+1); ?></td>
                  <td><?php echo $player['champion']; ?></td>
                  <td><a href="search.php?Summoner=<?php echo $player['name']; ?>&Region=<?php echo $player['region']; ?>"><?php echo $player['name']; ?> (<?php echo mb_strtoupper($player['region']); ?>)</a></td>

                  <?php if ($number >= 20) { ?>
                  <td><?php echo $player['name']; ?></td>
                  <td><?php echo mb_strtoupper($player['region']); ?></td>
                  <?php } ?>

                  <td><?php echo $player['points']; ?></td>

              </tr>

        <?php

          }
         ?>
          </tbody>

          <?php if ($number >= 20) {
            ?>
            <script>
                $("#grid-basic").bootgrid({rowCount: 25,
                  caseSensitive: false,
              selection: true,
              multiSelect: true,
              left:true,
              formatters: {
                  "link": function (column, row) {
                      return "<a href=\"search.php?Summoner=" + row.summoner + "&Region=" + row.region + "\">" + row.summoner + " (" + row.region + ")</a>";
                      }
                  }
                });
            </script>
            <?php
          }?>

      </table>


      <?php if ($seemore == "true") {?><a href="leaderboard.php?SeeMore=ChampionPoints" class="btn" role="button">See More</a><?php } ?>
    </div>

  <?php
}

function showAllChamps($number)
{
  ?>
  <div id="champ-section">
      <div id="searchChampion" class="searchChampion" style="margin-top: 50px;">
        <input type="text" id="searchChampionInput" class="barSearch form-control" placeholder="Search Champion"/>
      </div>

      <script>
      $(document).ready(function(){
       var scroll_start = 0;
       var startchange = $('#champ-section');
       var offset = startchange.offset();
      if (startchange.length){
       $(document).scroll(function() {
        scroll_start = $(this).scrollTop() + 100;
        if(scroll_start > offset.top) {
          $("#searchChampion").css('position', 'fixed');
          $("#searchChampion").css('width', '100%');
          $("#searchChampion").css('top', '50px');
          $("#searchChampion").css('left', '25px');

          //$("#searchChampion").css('margin-top', '30px');
          $("#searchChampion").css('z-index', '99');

         } else {
          $('#searchChampion').css('position', 'relative');
          $("#searchChampion").css('top', '0px');
          //$("#searchChampion").css('margin-top', '0px');
         }
       });
      }
    });

      $('#searchChampionInput').on('input', function(){

          var txt = $('#searchChampionInput').val();
          if (txt == "")
          {
            //If is blank then scroll to top
            $('html, body').stop().animate({
                scrollTop: $('#champ-section').offset().top - 300
            }, 250);

            //and resets everything to original brightness
            $('.championTitle').each(function(){
                $(this).parent().parent().parent().css('filter', 'brightness(100%)');
            });
          }
          else {
            var count = 0;
          $('.championTitle').each(function(){
             if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1 && count == 0){
                 $(this).parent().parent().parent().css('filter', 'brightness(100%)');

                 //If text matches then it scrolls to element
                 $('html, body').stop().animate({
                     scrollTop: $(this).offset().top - 200
                 }, 250);

                 count++;
             }
             else {
               $(this).parent().parent().parent().css('filter', 'brightness(30%)');

             }
          });
        }
      });
      </script>

  <?php

  include "vars.php";

  $champions= ChampionsList($apikey, $number);

  for ($i = 0; $i < count($champions); $i++)
  {
    $id = $champions[$i]['id'];
    $champion = $champions[$i];

   ?>
  <div class="champion-leaderboard" style="background:url('http://ddragon.leagueoflegends.com/cdn/img/champion/splash/<?php echo $champion['key']; ?>_0.jpg') no-repeat top left; background-size: 700px; background-position: 0% 30%;">
  <div class="row content">
    <div class="col-md-7 image">
      <h3 class="name-text name championTitle"><?php echo $champion['name']; ?></h3>
    <h4 class="name-text title"><?php echo $champion['title']; ?></h4>
    </div>
    <div class="col-md-5 champion-scores">
      <div class="row header">
        <h4 class="col-md-3">Rank</h4>
        <h4 class="col-md-6">Player</h4>
        <h4 class="col-md-3">Points</h4>
      </div>


      <?php

        $type = 0;

        for ($a = 0; $a < count($champion['Summoners']); $a++)
        {
          if ($type == 0)
          {
            $type = 1;
          }
          else
          {
            $type = 0;
          }

          $summoner = $champion['Summoners'][$a];

       ?>
      <div class="row rank<?php echo $type?>">
        <p class="col-md-3"><?php echo $a+1; ?></p>
        <p class="col-md-6"><a href="search.php?Summoner=<?php echo $summoner['name']; ?>&Region=<?php echo $summoner['region']; ?>"><?php echo $summoner['name']; ?> (<?php echo mb_strtoupper($summoner['region']); ?>)</a></p>
        <p class="col-md-3"><?php echo $summoner['points']; ?></p>
      </div>

      <?php

        }
       ?>

       <!-- I know, width 106.5%, strange number but it works... -->
       <div class="row rank0 seemorebtn" style="height: 100%; width: 106.5%;">
         <a style="height: 100%; width: 100%; line-height: 100%;" href="leaderboard.php?SeeMore=Champion<?php echo $champion['id']; ?>" class="btn" role="button">See More</a>
       </div>
    </div>
  </div>
  </div>
  <?php
  }
  ?>

  </div>

  <?php
}

function showChamp($number, $id, $version, $apikey)
{
    $champion = ChampionHighScore($apikey, $number,$id);

   ?>

   <div class="row">

     <script>
     $('.background').css('background-image', 'url(\'http://ddragon.leagueoflegends.com/cdn/img/champion/splash/<?php echo $champion['key']; ?>_0.jpg\')');
     </script>

           <div class="image" style="margin-bottom: 100px;">
             <center><h1 id='seemoreName' class="name-text name"><?php echo $champion['name']; ?></h1></center>
             <center><h3 id='seemoreTitle' class="name-text title"><?php echo $champion['title']; ?></h3></center>
           </div>

     <?php
      //Shows the 3 top summoners
      showTopHeader($champion['Summoners'], $version, $apikey);
      showStatistics($id);
      ?>

    <table id="grid-basic" style="margin-top: 10vh;" class="table table-condensed table-hover table-striped text-center">
        <thead >
            <tr class="table-header" data-align="center">
                <th data-column-id="pos" data-header-css-class="smallcol"  data-type="numeric">Rank</th>
                <th data-column-id="links" data-formatter="link">Summoner</th>
                <th data-column-id="summoner" data-visible="false">Summoner</th>
                <th data-column-id="region" data-header-css-class="smallcol" data-visible="false">Region</th>
                <th data-column-id="points" data-type="numeric" data-order="desc">Points</th>
            </tr>
        </thead>

        <tbody>
            <tr>

              <?php

                $type = 0;

                for ($a = 0; $a < count($champion['Summoners']); $a++)
                {
                  if ($type == 0)
                  {
                    $type = 1;
                  }
                  else
                  {
                    $type = 0;
                  }

                  $summoner = $champion['Summoners'][$a];

               ?>

                <td><?php echo $a+1; ?></td>
                <td><?php echo $summoner['name']; ?></td>
                <td><?php echo $summoner['name']; ?></td>
                <td><?php echo mb_strtoupper($summoner['region']); ?></td>
                <td><?php echo $summoner['points']; ?></td>

            </tr>



                                  <?php

                                    }
                                   ?>
        </tbody>

    </table>


    <script>
        $("#grid-basic").bootgrid({rowCount: 25,
          caseSensitive: false,
      selection: true,
      multiSelect: true,
      left:true,
      formatters: {
          "link": function (column, row) {
              return "<a href=\"search.php?Summoner=" + row.summoner + "&Region=" + row.region + "\">" + row.summoner + " (" + row.region + ")</a>";
              }
          }
        });
    </script>

  </div>


    <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>

  <?php

}

function showTopHeader ($summoners, $version, $apikey)
{
  $type = 0;

  $pos = array(1,0,2);

  for ($i = 0; $i < count($pos); $i++)
  {
    $a = $pos[$i];

    if ($type == 0)
    {
      $type = 1;
    }
    else
    {
      $type = 0;
    }

    $summoner = $summoners[$a];
    $SummonerData = new SummonerData;
    $nowTime = (new \DateTime())->format('Y-m-d H:i:s');

    $SummonerData->getData($summoner['name'], $summoner['region'], $nowTime, $apikey, false);

    if ($a == 0)
    {
    $level = 5;
    $margin = "0px";

    $multiplier = 1.25;

   }
   else if ($a == 1)
   {
     $level = 4;
     $margin = "40px";

     $multiplier = 1.1;

   }
   else if ($a == 2)
   {
     $level = 3;
     $margin = "80px";
     $multiplier = 1.0;

   }

 ?>

<div class="col-md-4" style="margin-top: <?php echo $margin;?>; -ms-transform: scale(<?php echo $multiplier; ?>); -webkit-transform: scale(<?php echo $multiplier; ?>); transform: scale(<?php echo $multiplier; ?>);" data-wow-delay="100ms">

  <center>
    <a href="search.php?Summoner=<?php echo $summoner['name']; ?>&Region=<?php echo $summoner['region']; ?>"><h2 style="color: #fff; margin-bottom:0px;"><?php echo $summoner['name']; ?></h2></a>
  </center>

  <div class="parent">

    <div class="champion" style="background: none;">

      <div id="round-<?php echo $a;?>" class="round level<?php echo $level; ?>">

        <img align="middle" style="margin-left: auto; margin-right: auto;" src='http://ddragon.leagueoflegends.com/cdn/<?php echo $version; ?>/img/profileicon/<?php echo $SummonerData->SummonerInfo['profileIconId']; ?>.png'>

        <div id="levelinfo-<?php echo $a;?>" class="levelinfo">

            <h2 class="level<?php echo $level; ?>" style="margin-top: 30px; margin-bottom: 5px;"><?php echo getPoints($summoner['points']); ?></h2>

            <a data-toggle="tooltip" data-placement="bottom" data-container="body" class="level1" data-html="true" title="<?php echo $summoner['points']; ?> total points"> <span>+</span></a>
        </div>
      </div>
    </div>

    <script>
    $(function(){
$("#round-<?php echo $a;?>").hover(function(){
$(this).find("#levelinfo-<?php echo $a;?>").css("opacity", "1");
}
              ,function(){
                  $(this).find("#levelinfo-<?php echo $a;?>").css("opacity", "0");
              }
             );
});
    </script>


        <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        </script>

    <img class="levelicon" src="images/levels/level5.png"/>

  </div>

</div>

<?php

  }

}

function showStatistics($id)
{
  $stats = ChampionStats($id);

  ?>

  <div class="champion-statistics">
  <div class="container">
  <div class="col-md-8 col-md-offset-2">
    <div class="header"  style="width: 104.1%; margin-left: -2%;"><center><h2 style="color: #e4be73;"><?php if ($id == 0) { echo "Global "; } else { echo "Champion "; } ?>Statistics</h2></center></div>
 <div id="champStats1" class="row">
   <div class="col-md-4 col-sm-4 col-xs-4 averageHG">
     <h4>Average Highest Grade</h4>
     <h3><?php echo $stats['averageGrade']; ?></h3>
   </div>
   <div class="col-md-4 col-sm-4 col-xs-4 ownedPerc">

     <?php if ($id != 0)
     {
       ?>
     <h4>Played by</h4>
     <h3><?php echo $stats['percentageOfOwned'] . "%"; ?></h3>
     <?php
   } else {
     ?>
     <h4>Average Played Champions</h4>
     <h3><?php echo $stats['averageChampionsOwned']; ?></h3>
     <?php
   }

   ?>

   </div>
   <div class="col-md-4 col-sm-4 col-xs-4 averageLVL">
     <h4>Average Level</h4>
     <h3><?php echo $stats['averageLevel']; ?></h3>
     <p>Average Score: <?php echo getPoints($stats['averagePoints']); ?></p>
   </div>
 </div>
 <div id="champStats2" class="row">
   <div class="col-md-4 col-sm-4 col-xs-4 HGPerc">
     <h4>Highest Grade %</h4>

     <div style="margin-top: 5px;"><canvas id="gradeChart" height="256px"></canvas></div>

     <script>


     var ctx1 = document.getElementById("gradeChart").getContext("2d");

     var data = {
     labels: [
     "S+",
     "S",
     "S-",
     "A+",
     "A",
     "A-",
     "B+",
     "B",
     "B-",
     "C+",
     "C",
     "C-",
     "D+",
     "D"

     ],
     datasets: [
     {
     data: [<?php echo $stats['percentageOfGrades']['S+'];?>,
     <?php echo $stats['percentageOfGrades']['S'];?>,
     <?php echo $stats['percentageOfGrades']['S-'];?>,
     <?php echo $stats['percentageOfGrades']['A+'];?>,
     <?php echo $stats['percentageOfGrades']['A'];?>,
     <?php echo $stats['percentageOfGrades']['A-'];?>,
     <?php echo $stats['percentageOfGrades']['B+'];?>,
     <?php echo $stats['percentageOfGrades']['B'];?>,
     <?php echo $stats['percentageOfGrades']['B-'];?>,
     <?php echo $stats['percentageOfGrades']['C+'];?>,
     <?php echo $stats['percentageOfGrades']['C'];?>,
     <?php echo $stats['percentageOfGrades']['C-'];?>,
     <?php echo $stats['percentageOfGrades']['D+'];?>,
     <?php echo $stats['percentageOfGrades']['D'];?>],
     backgroundColor: [
     "#116611",
     "#2E882E",
     "#55AA55",
     "#0D4D4D",
     "#226666",
     "#407F7F",
     "#804616",
     "#AA6C39",
     "#D49A6A",
     "#801616",
     "#AA3939",
     "#D46A6A",
     "#444",
     "#000"
     ],
     hoverBackgroundColor: [
       "#116611",
       "#2E882E",
       "#55AA55",
       "#0D4D4D",
       "#226666",
       "#407F7F",
       "#804616",
       "#AA6C39",
       "#D49A6A",
       "#801616",
       "#AA3939",
       "#D46A6A",
       "#444",
       "#000"
     ],
     borderWidth: 0
     }]
     };

     var ctx = document.getElementById("gradeChart");

       var myChart1 = new Chart(ctx, {
     type: 'pie',
     data: data,
     options: {
     legend: {
       display:false
     }
     }
     });
     </script>



   </div>
   <div class="col-md-4 col-sm-4 col-xs-4 chestPerc">
     <h4>Chest Granted</h4>
     <h3><?php echo $stats['percentageReceivedChest'] . "%"; ?></h3>
   </div>
   <div class="col-md-4 col-sm-4 col-xs-4 LVLPerc">
     <h4>Level %</h4>

     <div div style="margin-top: 5px;"><canvas id="levelChart" height="256px"></canvas></div>

     <script>


     var ctx1 = document.getElementById("levelChart").getContext("2d");

     var gradient1 = ctx1.createLinearGradient(0,0,0,180);
     gradient1.addColorStop(0, '<?php echo getColor(1); ?>');
     gradient1.addColorStop(1, '<?php echo getColor2(1); ?>');

     var gradient2 = ctx1.createLinearGradient(0,0,0,180);
     gradient2.addColorStop(0, '<?php echo getColor(2); ?>');
     gradient2.addColorStop(1, '<?php echo getColor2(2); ?>');


     var gradient3 = ctx1.createLinearGradient(0,0,0,180);
     gradient3.addColorStop(0, '<?php echo getColor(3); ?>');
     gradient3.addColorStop(1, '<?php echo getColor2(3); ?>');


     var gradient4 = ctx1.createLinearGradient(0,0,0,180);
     gradient4.addColorStop(0, '<?php echo getColor(4); ?>');
     gradient4.addColorStop(1, '<?php echo getColor2(4); ?>');


     var gradient5 = ctx1.createLinearGradient(0,0,0,180);
     gradient5.addColorStop(0, '<?php echo getColor(5); ?>');
     gradient5.addColorStop(1, '<?php echo getColor2(5); ?>');

     var data = {
     labels: [
     "Level 5",
     "Level 4",
     "Level 3",
     "Level 2",
     "Level 1"

     ],
     datasets: [
     {
     data: [<?php echo $stats['percentageOfLevels'][5];?>, <?php echo $stats['percentageOfLevels'][4];?>, <?php echo $stats['percentageOfLevels'][3];?>, <?php echo $stats['percentageOfLevels'][2];?>, <?php echo $stats['percentageOfLevels'][1];?>],
     backgroundColor: [
     gradient5,
     gradient4,
     gradient3,
     "rgba(33,33,33,0.75)",
     "rgba(33,33,33,1)"
     ],
     hoverBackgroundColor: [
     gradient5,
     gradient4,
     gradient3,
     "rgba(33,33,33,0.75)",
     "rgba(33,33,33,1)"
     ],
     borderWidth: 0
     }]
     };

     var ctx = document.getElementById("levelChart");

       var myChart = new Chart(ctx, {
     type: 'pie',
     data: data,
     options: {
     legend: {
       display:false
     }
     }
     });
     </script>


   </div>
 </div>
 </div>
 </div>
 </div>

  <?php
}
?>
