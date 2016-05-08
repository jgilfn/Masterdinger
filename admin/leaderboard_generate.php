<?php

require_once "summoner.php";

include "vars.php";

//Deletes the leaderboard
$result = mysqli_query($con, "DELETE FROM Leaderboard");

//Resets lastTime from all Summoners
$result = mysqli_query($con, "UPDATE Summoners SET lastTime='2009-10-10 00:00:00'");

$result = mysqli_query($con, "SELECT Name, Region FROM Summoners");

while ($row = mysqli_fetch_array($result))
{
  $name = $row['Name'];
  $region = $row['Region'];
  $nowTime = (new \DateTime())->format('Y-m-d H:i:s');

  $SummonerData = new SummonerData;

  //Requests data from the server for each summoner
  $SummonerData->getData($name, $region, $nowTime, $apikey);
  $SummonerData->getChampions($apikey);

  //Maximum of 10 requests per second
  sleep(0.1);
}

?>
