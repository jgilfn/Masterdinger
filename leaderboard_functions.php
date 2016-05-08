<?php

require_once "summoner.php";

function ChampionStats($id)
{
  include "vars.php";

  $result = mysqli_query($con, "SELECT * FROM Summoners");

  $ownschampion = 0;

  $totalGrade = 0;
  $totalGrades = array();
  $totalSummonersWhoHaveGrade = 0;

  $trueChest = 0;
  $totalLevel = 0;

  $levels = array();

  $totalPoints = 0;

  $totalSummoners = 0;

  while($row = mysqli_fetch_array($result))
  {
    $name = $row['Name'];
    $region = $row['Region'];

    $totalSummoners++;

    $MasteriesInfo = json_decode(str_replace("§", "'", $row['MasteriesInfo']), true);

    for ($i = 0; $i < count($MasteriesInfo); $i++)
    {

        $champID = $MasteriesInfo[$i]['championId'];

        //If the specified ID is not zero then it will only search for a specific champion
        if ($id != 0)
        {
          $bool = ($champID == $id);
        }
        //Else it will search for all champions
        else {
          $bool = true;
        }

        //If the champion id is the specified id, then it means that the summoner owns the champion
        if ($bool)
        {

            $ownschampion++;

            if (isset($MasteriesInfo[$i]['highestGrade']))
            {
              $totalGrade = $totalGrade + getGradeValue($MasteriesInfo[$i]['highestGrade']);
              $totalSummonersWhoHaveGrade++;
              $totalGrades[$MasteriesInfo[$i]['highestGrade']]++;
            }

            if ($MasteriesInfo[$i]['chestGranted'] == "true")
            {
              $trueChest++;
            }

            $totalLevel = $totalLevel + $MasteriesInfo[$i]['championLevel'];

            $levels[$MasteriesInfo[$i]['championLevel']]++;

            $totalPoints = $totalPoints + $MasteriesInfo[$i]['championPoints'];
        }
    }

  }

  $percentageOfOwned = round(($ownschampion / $totalSummoners) * 100, 0);
  $averageGrade = round(($totalGrade / $totalSummonersWhoHaveGrade), 0);
  $percentageReceivedChest = round(($trueChest / $ownschampion) * 100, 0);
  $averageLevel = round(($totalLevel / $ownschampion), 2);
  $averagePoints = round(($totalPoints / $ownschampion), 0);

  if ($id == 0)
  {
    $averageChampionsOwned = round(($ownschampion / $totalSummoners), 0);
  }

  //Calculates percentage of Levels
  $percentageOfLevels = array();

  for ($i = 1; $i <= 5; $i++)
  {
    $percentageOfLevels[$i] = round(($levels[$i] / $ownschampion) * 100, 2);
  }

  //Calculates Percentages of Grades
  $percentageOfGrades = array();

  $grades = array('S+', 'S', 'S-', 'A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D');

  for ($i = 0; $i < count($grades); $i++)
  {
    $grade = $grades[$i];
    $percentageOfGrades[$grade] = round(($totalGrades[$grade] / $totalSummonersWhoHaveGrade) * 100, 2);
  }

  $data = array('percentageOfOwned' => $percentageOfOwned,
                'averageGrade' => getGradeFromValue($averageGrade),
                'percentageReceivedChest' => $percentageReceivedChest,
                'averageLevel' => $averageLevel,
                'averagePoints' => $averagePoints,
                'percentageOfLevels' => $percentageOfLevels,
                'percentageOfGrades' => $percentageOfGrades,
                'averageChampionsOwned' => $averageChampionsOwned
              );

  return $data;

}

function getGradeFromValue ($value)
{
  $grades = array(
    13 => 'S+',
    12 => 'S',
    11 => 'S-',
    10 => 'A+',
    9 => 'A',
    8 => 'A-',
    7 => 'B+',
    6 => 'B',
    5 => 'B-',
    4 => 'C+',
    3 => 'C',
    2 => 'C-',
    1 => 'D+',
    0 => 'D'
  );

  return $grades[$value];
}

//Returns the percentage of summoners who own a specific champion
function percentageOfSummonersWhoOwnChampion ($id) {
  return round((numberOfSummonersWhoOwnChampion ($id) / totalSummoners ()) * 100, 0) . "%";
}

//Returns the total of summoners who own a specific champion
function numberOfSummonersWhoOwnChampion ($id)
{
  include "vars.php";

  $result = mysqli_query($con, "SELECT * FROM Summoners");

  $ownschampion = 0;

  while($row = mysqli_fetch_array($result))
  {
    $name = $row['Name'];
    $region = $row['Region'];

    $MasteriesInfo = json_decode(str_replace("§", "'", $row['MasteriesInfo']), true);

    for ($i = 0; $i < count($MasteriesInfo); $i++)
    {
        $champID = $MasteriesInfo[$i]['championId'];

        //If the champion id is the specified id, then it means that the summoner owns the champion
        if ($champID == $id)
        {
            $ownschampion = $ownschampion + 1;
        }
    }

  }

  return $ownschampion;
}

//Returns the total of summoners who are in the database
function totalSummoners ()
{
  include "vars.php";

  $result = mysqli_query($con, "SELECT * FROM Summoners");

  $totalSummoners = mysqli_num_rows($result);

  return $totalSummoners;
}

//Returns top players based on Mastery Points
function topPlayersMasteryPoints ($number, $apikey)
{
  include "vars.php";

  $result = mysqli_query($con, "SELECT Summoner, Region, SUM(ChampionPoints) totalPoints FROM Leaderboard GROUP BY Summoner ORDER BY totalPoints DESC LIMIT " . $number);

  $data = array();

  while($row = mysqli_fetch_array($result))
  {
    $name = $row['Summoner'];
    $region = mb_strtoupper($row['Region']);
    $points = $row['totalPoints'];

    $data[] = array( 'name' => $name, 'region' => $region, 'points' => $points);

  }

  return $data;

}

//Returns top players based on their champion with the highestPoints
function topPlayersChampionPoints ($number, $apikey)
{
  include "vars.php";

  $result = mysqli_query($con, "SELECT * FROM Leaderboard ORDER BY ChampionPoints DESC LIMIT " . $number);

  $data = array();

  while($row = mysqli_fetch_array($result))
  {
    $name = $row['Summoner'];
    $region = $row['Region'];
    $points = $row['ChampionPoints'];
    $id = $row['ChampionID'];
    $info = GetChampInfo($id);
    $champion = $info['name'];
    $title = $info['title'];

    $data[] = array ('id' => $id, 'name' => $name, 'region' => $region, 'points' => $points, 'champion' => $champion, 'title' => $title);

  }

  return $data;
}

function GetChampInfo($id)
{
  include "vars.php";

  $result = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM Champions WHERE ID='" . $id . "' LIMIT 1"));

    $name = str_replace("§", "'", $result['Name']);
    $title = str_replace("§", "'", $result['Title']);
    $id = $result['ID'];
    $key = $result['ChampKey'];

    $data = array ('id' => $id, 'name' => $name, 'title' => $title, 'key' => $key);

  return $data;
}

function ListChampions()
{
  include "vars.php";

  $result = mysqli_query($con, "SELECT * FROM Champions ORDER BY Name");

  while($row = mysqli_fetch_array($result))
  {
    $name = str_replace("§", "'", $row['Name']);
    $title = str_replace("§", "'", $row['Title']);
    $id = $row['ID'];
    $key = $row['ChampKey'];

    $data[] = array ('id' => $id, 'name' => $name, 'title' => $title, 'key' => $key);

  }

  return $data;
}

function ChampionsList ($apikey, $number)
{
  include "vars.php";

  $champions = ListChampions();

  for ($i = 0; $i < count($champions); $i++)
  {
    $champion = $champions[$i];

      $result = mysqli_query($con, "SELECT * FROM Leaderboard WHERE ChampionID='". $champion['id'] . "' ORDER BY ChampionPoints DESC LIMIT " . $number);

      while($row = mysqli_fetch_array($result))
      {
        $name = $row['Summoner'];
        $region = $row['Region'];
        $points = $row['ChampionPoints'];

        $champions[$i]['Summoners'][] = array ('name' => $name, 'region' => $region, 'points' => $points);

      }
  }

  return $champions;
}

function ChampionHighScore ($apikey, $number, $id)
{
  include "vars.php";

  $champion = GetChampInfo($id);

  $result = mysqli_query($con, "SELECT * FROM Leaderboard WHERE ChampionID='". $id . "' ORDER BY ChampionPoints DESC LIMIT " . $number);

  while($row = mysqli_fetch_array($result))
  {
    $name = $row['Summoner'];
    $region = $row['Region'];
    $points = $row['ChampionPoints'];

    $champion['Summoners'][] = array ('name' => $name, 'region' => $region, 'points' => $points);

  }

  return $champion;
}


?>
