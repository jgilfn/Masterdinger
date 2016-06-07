<?php

function loadAPIPage ($url)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($curl);
  curl_close($curl);
  return json_decode($result, true);
}

function summoner_info_array_name($summoner) {
  $summoner_lower = mb_strtolower($summoner, 'UTF-8');
  $summoner_nospaces = str_replace(' ', '', $summoner_lower);

  return $summoner_nospaces;
}

function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}

function insertChampionDB ($id, $name, $title, $key, $maintag, $secondtag)
{
    include "vars.php";

    mysqli_query($con, "INSERT INTO Champions (ID, Name, Title, ChampKey, MainTag, SecondTag) VALUES ('" . $id . "', '" . $name . "', '" . $title . "', '" . $key . "', '" . $maintag . "', '" . $secondtag ."')");
}


function timeDiff ($initTime, $currentTime)
{
    //Converts string time to unix time
    $initTimeT = strtotime($initTime);
    $currentTimeT = strtotime($currentTime);

    //returns time in hours
    return ($currentTimeT-$initTimeT);
}

function saveDataDB ($region, $Summoner, $SummonerInfo, $MasteriesInfo, $nowTime)
{
  include "vars.php";

  $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM Summoners WHERE Name='" . $Summoner . "' AND Region='" . $region . "'"));

  if (isset($result['Name']))
  {
    //Update old values to new ones
    mysqli_query($con, "UPDATE Summoners SET SummonerInfo='" . $SummonerInfo . "', MasteriesInfo='" . $MasteriesInfo . "', lastTime='" . $nowTime . "' WHERE Name='" . $Summoner . "' AND Region='" . $region . "'");

  }
  else {
    //Insert new values
    mysqli_query($con, "INSERT INTO Summoners (Region, Name, SummonerInfo, MasteriesInfo, lastTime) VALUES ('" . $region . "', '" . $Summoner . "', '" . $SummonerInfo . "', '" . $MasteriesInfo . "', '" . $nowTime ."')");

  }
}

//Not working for a reason
function betterEncode ($string)
{
  //replaces the ' character with a § so it does not mess with the mySql insert command

  return str_replace("'", "§", json_encode($string));
}

//Not working for a reason
function betterDecode ($string)
{
  return json_decode(str_replace("§", "'", $string), true);
}

function updateLeaderboard ($Region, $Summoner, $ChampionID, $ChampionPoints)
{
  include "vars.php";

  $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM Leaderboard WHERE Region='" . $Region . "' AND Summoner='" . $Summoner . "' AND ChampionID='" . $ChampionID . "' LIMIT 1"));

  //Checks if that champion and summoner is already at the leaderboard
  if (isset($result['Summoner']))
  {
    //If it is, then it will update it
    mysqli_query($con, "UPDATE Leaderboard SET ChampionPoints='" . $ChampionPoints . "' WHERE Region='" . $Region . "' AND Summoner='" . $Summoner . "' AND ChampionID='" . $ChampionID . "'");

  }
  else {
    //Else it will create a new row
    mysqli_query($con, "INSERT INTO Leaderboard (Region, Summoner, ChampionID, ChampionPoints) VALUES ('" . $Region . "', '" . $Summoner . "', '" . $ChampionID . "', '" . $ChampionPoints ."')");

  }
}

function getPlatform ($region)
{
  $platform = "";

  if ($region == "na")
  {
    $platform = "na1";
  }
  else if ($region == "euw")
  {
    $platform = "euw1";
  }
  else if ($region == "br")
  {
    $platform = "br1";
  }
  else if ($region == "eune")
  {
    $platform = "eun1";
  }
  else if ($region == "kr")
  {
    $platform = "kr";
  }
  else if ($region == "lan")
  {
    $platform = "la1";
  }
  else if ($region == "las")
  {
    $platform = "la2";
  }
  else if ($region == "oce")
  {
    $platform = "oc1";
  }
  else if ($region == "tr")
  {
    $platform = "tr1";
  }
  else if ($region == "ru")
  {
    $platform = "ru";
  }
  else if ($region == "jp")
  {
    $platform = "jp1";
  }

  return $platform;
}

function jsonBooleanReader($bool)
{
  if($bool)    // suggested by **mario**
{
    return "true";
}
else {
    return "false";
}
}

//Generates an int array with the id of each champion owned by this Summoner
function genChampions ($MasteriesInfo)
{
  $Champions = array();

  for ($i = 0; $i < count($MasteriesInfo); $i++)
  {
    $id = $MasteriesInfo[$i]['championId'];
    $Champions[] = $id;
  }

  return $Champions;
}

function getPoints($int)
{
      if ($int < 10000)
      {
        $n_format = $int;
      }
      else if ($int < 1000000) {
          // Anything less than a million
          $n_format = round($int / 1000, 1) . "K";
      } else if ($int < 1000000000) {
          // Anything less than a billion
          $n_format = round($int / 1000000, 1) . 'M';
      } else {
          // At least a billion
          $n_format = round($int / 1000000000, 1) . 'B';
      }

      return $n_format;
}

//Returns current version
function getVersion($apikey, $region)
{
  include "vars.php";

  //Connects to database and checks if there is information about that champion
  $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM Versions WHERE Region='" . $region . "' LIMIT 1"));

  $nowTime = (new \DateTime())->format('Y-m-d H:i:s');

  //If there is an entry with that region and the time it was registered is less than 1 hour ago
  if (isset($result['Version']) && timeDiff($result['Time'], $nowTime) <= 3600)
  {
    $version = $result['Version'];
    return $version;
  }
  else
  {
    $versionData = loadAPIPage("https://global.api.pvp.net/api/lol/static-data/" . $region . "/v1.2/versions?api_key=" . $apikey);

    $version = $versionData[0];

    //If there is an entry with that region, it needs to be updated
    if (isset($result['Region']))
    {
      //Update old values to new ones
      mysqli_query($con, "UPDATE Versions SET Version='" . $version . "', Time='" . $nowTime . "' WHERE Region='" . $region . "'");

    }
    //If there isn't an entry with that region, it needs to be created
    else {
      //Insert new values
      mysqli_query($con, "INSERT INTO Versions (Region, Version, Time) VALUES ('" . $region . "', '" . $version . "', '" . $nowTime ."')");

    }

    return $version;
  }
}

function getGradeValue ($grade) {
  $grades = array(
    'S+' => 13,
    'S' => 12,
    'S-' => 11,
    'A+' => 10,
    'A' => 9,
    'A-' => 8,
    'B+' => 7,
    'B' => 6,
    'B-' => 5,
    'C+' => 4,
    'C' => 3,
    'C-' => 2,
    'D+' => 1,
    'D' => 0,
    '' => -1,
    'N/A' => -1
  );

  return $grades[$grade];
}


//returns the right color for this champion level
function getColor ($level)
{
  if ($level == "7")
  {
    return "#54BDB6";
  }
  else if ($level == "6")
  {
    return "#B331B9";
  }
  else if ($level == "5")
  {
    return "#dbb365";
  }
  else if ($level == "4")
  {
    return "#bababa";
  }
  else if ($level == "3")
  {
    return "#BA752A";
  }
  else {
    return "#ffffff";
  }
}

//returns the right color for this champion level
function getColor2 ($level)
{
  if ($level == "7")
  {
    return "#257572";
  }
  else if ($level == "6")
  {
    return "#7B227E";
  }
  else if ($level == "5")
  {
    return "#96702a";
  }
  else if ($level == "4")
  {
    return "#6f6f6f";
  }
  else if ($level == "3")
  {
    return "#663200";
  }
  else {
    return "#cfcfcf";
  }
}

//Returns the title for the specified champion (and level)
function getLevelTitle ($level, $maintag)
{
  $titles = array (

  "Assassin" => array(
    1 => "Thug",
    2 => "Prowler",
    3 => "Cutthroat",
    4 => "Reaper",
    5 => "Slayer",
    6 => "Executioner",
    7 => "Deathmaster"
  ),

  "Fighter" => array(
    1 => "Scrapper",
    2 => "Brawler",
    3 => "Warrior",
    4 => "Veteran",
    5 => "Destroyer",
    6 => "Warmonger",
    7 => "Warlord"
  ),

  "Mage" => array(
    1 => "Initiate",
    2 => "Conjurer",
    3 => "Invoker",
    4 => "Magus",
    5 => "Warlock",
    6 => "Sorcerer",
    7 => "Archmage"
  ),

  "Marksman" => array(
    1 => "Tracker",
    2 => "Strider",
    3 => "Scout",
    4 => "Ranger",
    5 => "Pathfinder",
    6 => "Sharpshooter",
    7 => "Sniper"
  ),

  "Support" => array(
    1 => "Aide",
    2 => "Protector",
    3 => "Keeper",
    4 => "Defender",
    5 => "Guardian",
    6 => "Sentinel",
    7 => "Warden"
  ),

  "Tank" => array(
    1 => "Grunt",
    2 => "Bruiser",
    3 => "Bulward",
    4 => "Enforcer",
    5 => "Brute",
    6 => "Colossus",
    7 => "Juggernaut"
  )

);

return $titles[$maintag][$level];

}

function getOrdinalPos ($number)
{
  $suffix = "th";


  if (strlen($number) == 2 && substr( $number, 0, 1 ) === "1")
  {
    $suffix = "th";
  }
  else if (substr( $number, -1 ) == "1")
  {
    $suffix = "st";
  }
  else if (substr( $number, -1 ) == "2")
  {
    $suffix = "nd";
  }
  else if (substr( $number, -1 ) == "3")
  {
    $suffix = "rd";
  }

  return $number . $suffix;
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
 ?>
