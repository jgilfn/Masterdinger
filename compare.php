<?php
//Color light: 15,31,47 #0f1f2f
//Color dark: 12,23,34

?>

<html lang="en">
<?php include "head.php";

require_once "functions.php";
require_once "summoner.php";

include "vars.php";

?>

<body>

  <div class="background"></div>

  <?php include "header.php";

  //Compare mode
  if (isset($_GET['Summoner1']) && isset($_GET['Summoner2']) && isset($_GET['Region1']) && isset($_GET['Region2']))
  {
    $nowTime = (new \DateTime())->format('Y-m-d H:i:s');

    $summoner1 = summoner_info_array_name($_GET["Summoner1"]);
    $region1 = summoner_info_array_name($_GET["Region1"]);

    $summoner2 = summoner_info_array_name($_GET["Summoner2"]);
    $region2 = summoner_info_array_name($_GET["Region2"]);

    $SummonerData1 = new SummonerData;
    $SummonerData1->getData($summoner1, $region1, $nowTime, $apikey);

    $SummonerData1->getChampions($apikey);

    $SummonerData2 = new SummonerData;
    $SummonerData2->getData($summoner2, $region2, $nowTime, $apikey);
    $SummonerData2->getChampions($apikey);


  ?>

  <div class ="container">


          <form action="compare.php" method="get">

            <input type="hidden" value="<?php echo $_GET["Summoner1"]; ?>" name="Summoner1"></input>
            <input type="hidden" value="<?php echo $_GET["Region1"]; ?>" name="Region1"></input>

            <input type="hidden" value="<?php echo $_GET["Summoner2"]; ?>" name="Summoner2"></input>
            <input type="hidden" value="<?php echo $_GET["Region2"]; ?>" name="Region2"></input>

            <input type="checkbox" style="margin-top: 2vh; float:right; clear:both;" onchange="this.form.submit()" id="c1" value="true" name="BothOwned" <?php if (isset($_GET['BothOwned'])) { echo "checked"; }?>/><label style="margin-right: 10px; margin-top: 2vh; float:right;">Only champions played by both</label>

          </form>

    <table id="grid-basic" style="margin-top: 1vh;" class="table table-condensed table-hover table-striped text-center">

        <thead >
            <tr class="table-header" data-align="center">
                <th data-column-id="pos" data-header-css-class="tinycol" data-type="numeric">#</th>
                <th data-column-id="pic" data-header-css-class="piccol" data-formatter="pix" >Icon</th>
                <th data-column-id="id" data-visible="false">Champion ID</th>
                <th data-column-id="champion"  data-order="asc">Champion</th>
                <th data-column-id="summoner1points"  data-type="numeric"><?php echo $SummonerData1->summoner; ?>'s Points</th>
                <th data-column-id="summoner2points"  data-type="numeric"><?php echo $SummonerData2->summoner; ?>'s Points</th>
                <th data-column-id="highestGrade1"><?php echo $SummonerData1->summoner; ?>'s Highest Grade</th>
                <th data-column-id="highestGrade2"><?php echo $SummonerData2->summoner; ?>'s Highest Grade</th>
                <th data-column-id="Level1" data-visible="false"><?php echo $SummonerData1->summoner; ?> - Level</th>
                <th data-column-id="Level2" data-visible="false"><?php echo $SummonerData2->summoner; ?> - Level</th>
                <th data-column-id="ReceivedChest1" data-visible="false"><?php echo $SummonerData1->summoner; ?> - Chest Received</th>
                <th data-column-id="ReceivedChest2" data-visible="false"><?php echo $SummonerData2->summoner; ?> - Chest Received</th>
            </tr>
        </thead>
        <tbody>
            <?php
              $result = mysqli_query($con, "SELECT * FROM Champions ORDER BY Name ASC");

              $pos = 0;

              while($row = mysqli_fetch_array($result))
              {
                $key = $row['Key'];
				        $id = $row['ID'];
                $name = $row['Name'];

                //Checks if both Summoners contains the specified champion
                $bool = ($SummonerData1->getChampionPos($id) != -1 || $SummonerData2->getChampionPos($id) != -1);

                if (isset($_GET['BothOwned']))
                {
                  //Checks if atleast one of the Summoners contains the specified champion
                  $bool = ($SummonerData1->getChampionPos($id) != -1 && $SummonerData2->getChampionPos($id) != -1);
                }

                if ($bool)
                {

                  $Pos1 = $SummonerData1->getChampionPos($id);
                  $champion1 = $SummonerData1->Champions[$Pos1];

                  $Pos2= $SummonerData2->getChampionPos($id);
                  $champion2 = $SummonerData2->Champions[$Pos2];

                  $pos++;

                  if ($champion1['chest'] == "true")
                  {
                    $champion1['chest'] = "Received";
                  }
                  else {
                    $champion1['chest'] = "Not Received";
                  }

                  if ($champion2['chest'] == "true")
                  {
                    $champion2['chest'] = "Received";
                  }
                  else {
                    $champion2['chest'] = "Not Received";
                  }

            ?>
            <tr>
                <td><?php echo $pos; ?></td>
                <td></td>
                <td><?php echo $id; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $champion1['points'];?></td>
                <td><?php echo $champion2['points'];?></td>
                <td><?php echo $champion1['highestGrade'];?></td>
                <td><?php echo $champion2['highestGrade'];?></td>
                <td><?php echo $champion1['level'];?></td>
                <td><?php echo $champion2['level'];?></td>
                <td><?php echo $champion1['chest'];?></td>
                <td><?php echo $champion2['chest'];?></td>
            </tr>
            <?php
                }
              }
            ?>
        </tbody>
    </table>

  </div>
  <?php
  }
  ?>
  <?php
  include "footer.php";
  ?>
</body>

<script>
    $("#grid-basic").bootgrid({rowCount: 100,
      caseSensitive: false,
  selection: true,
  multiSelect: true,
  left:true,
  formatters: {
      "pix": function (column, row) {
          return "<img align=\"middle\" style=\"margin-left: auto; margin-right: auto;\" src='http://lkimg.zamimg.com/images/v2/champions/icons/size64x64/" + row.id + ".png'>";
          }
      }
    });
</script>

</html>
