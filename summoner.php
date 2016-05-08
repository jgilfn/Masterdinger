<?php


require_once "functions.php";
require_once "champion.php";

class SummonerData
{
  public $platform;
  public $summoner;
  public $SummonerInfo;
  public $MasteriesInfo;
  public $region;
  public $lastUpdated;

  public $totalMasteryPoints;

  public $Champions = array ();

  public $notfound = false;


    function getData($summoner, $region, $nowTime, $apikey, $updateDB = true)
    {
      include "vars.php";

      $this->region = $region;
      $this->platform = getPlatform($this->region);
      $this->summoner = summoner_info_array_name($summoner);

      //Selects from the database, but lowers all the cases of both values in order to make the command case insensitive.
      $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM Summoners WHERE LOWER( REPLACE (Name, ' ', '') ) = LOWER('" . $this->summoner . "') AND Region='" . $this->region . "' LIMIT 1"));

      //If table contains the summoner name and the last time he logged in was less than an hour ago, it will use the data from the database
      if (isset($result['Name']) && timeDiff($result['lastTime'], $nowTime) <= 3600) //3600 seconds or 1 hour
      {
        //echo "readdb";
        //Use data from database, replaces § with '
        $this->SummonerInfo = json_decode(str_replace("§", "'", $result['SummonerInfo']), true);
        $this->summoner = $result['Name'];

        $this->MasteriesInfo = json_decode(str_replace("§", "'", $result['MasteriesInfo']), true);

        $mins = round(timeDiff($result['lastTime'], $nowTime)/60);

        if ($mins == 0)
        {
          $this->lastUpdated = "Updated now";
        }
        else if ($mins == 1)
        {
          $this->lastUpdated = "Last updated " . $mins . " minute ago";
        }
        else {
          $this->lastUpdated = "Last updated " . $mins . " minutes ago";
        }
      }
      else {
        //Request new data from API
        //Gets the ID of the Summoner
        $this->SummonerInfo = loadAPIPage("https://" . $this->region . ".api.pvp.net/api/lol/" . $this->region . "/v1.4/summoner/by-name/" . rawurlencode($this->summoner) . "?api_key=" . $apikey)[$this->summoner];

        //Uses the ID of the Summoner to list all of the Player's champions and their mastery
        $this->MasteriesInfo = loadAPIPage("https://" . $this->region . ".api.pvp.net/championmastery/location/" . $this->platform . "/player/" . $this->SummonerInfo['id'] . "/champions?api_key=" . $apikey);

        //Renames the summoner name so it respects the case sensitivity that is in the API
        $this->summoner = $this->SummonerInfo['name'];

        //Saves requested data to a Table, replaces the ' character with a § so it does not mess with the mySql command
        //It won't save if there is an internal server error
        if ($this->SummonerInfo != null && !empty($this->MasteriesInfo) && strpos($this->MasteriesInfo, 'Internal server error') !== false&& $updateDB)
        {
          saveDataDB($this->region, $this->summoner, str_replace("'", "§", json_encode($this->SummonerInfo)), str_replace("'", "§", json_encode($this->MasteriesInfo)), $nowTime);
        }
        else
        {
          //If the Summoner was not found
          $this->notfound = true;
        }

        $this->lastUpdated = "Updated now";

      }

        //Counts all mastery points based on each champion level
        $this->totalMasteryPoints = 0;

        for ($i = 0; $i < count($this->MasteriesInfo); $i++)
        {
          $level = $this->MasteriesInfo[$i]['championLevel'];

          $this->totalMasteryPoints = $this->totalMasteryPoints + $level;
        }

    }

    //Parses the API data and assigns a Champion, including name and title, to it, creating an array with all information. For size storing reasons this should not be stored in the database
    function getChampions ($apikey)
    {
      include "vars.php";

      for ($i = 0; $i < count($this->MasteriesInfo); $i++)
      {
        //Parses info from API array
        $id = $this->MasteriesInfo[$i]['championId'];
        $level = $this->MasteriesInfo[$i]['championLevel'];

        if (isset($this->MasteriesInfo[$i]['highestGrade']))
        {
          $highestGrade = $this->MasteriesInfo[$i]['highestGrade'];
        }
        else {
          $highestGrade = "";
        }


        $points = $this->MasteriesInfo[$i]['championPoints'];
        $chest = jsonBooleanReader($this->MasteriesInfo[$i]['chestGranted']);

        //Parses info from API array related with points
        $championPointsSinceLastLevel = $this->MasteriesInfo[$i]['championPointsSinceLastLevel'];
        $championPointsUntilNextLevel = $this->MasteriesInfo[$i]['championPointsUntilNextLevel'];
        $totalChampionPointsCurrentLevel = $championPointsSinceLastLevel + $championPointsUntilNextLevel;

        //Converts UNIX time to a human readable date
        $lastPlayTime = $this->MasteriesInfo[$i]['lastPlayTime'];

        //If there is no highest grade then it will show 'Not Available'
        if ($highestGrade == "")
        {
          $highestGrade = "N/A";
        }


        $champion = new ChampionData;
        $champion->getData($id, $this->region, $apikey);

        //Puts data inside the array
        $this->Champions[] = array (
          'id' => $id,
          'level' => $level,

          'highestGrade' => $highestGrade,
          'highestGradeValue' => getGradeValue($highestGrade),

          'points' => $points,
          'championPointsSinceLastLevel' => $championPointsSinceLastLevel,
          'championPointsUntilNextLevel' => $championPointsUntilNextLevel,
          'totalChampionPointsCurrentLevel' => $totalChampionPointsCurrentLevel,

          'chest' => $chest,

          /*Champion class Related*/
          'name' => $champion->name,
          'title' => $champion->title,
          'key' => $champion->key,
          'mainTag' => $champion->maintag,
          'SecondTag' => $champion->secondtag,
          'levelTitle' => getLevelTitle($level, $champion->maintag),

          'lastPlayTime' => $lastPlayTime

        );


            //Updates the champion and respective player in the leaderboard
            if ($points != 0)
            {
              updateLeaderboard($this->region, $this->summoner, $id, $points);
            }
      }
    }

    //Returns the specified champion id inside the array's position
    function getChampionPos($id)
    {
      $internal = -1;

      for ($i = 0; $i < count($this->Champions); $i++)
      {
        if ($this->Champions[$i]['id'] == $id)
        {
          $internal = $i;
        }
      }

      return $internal;

    }
}

//TODO FIND OUT WHY SUMMONERS WITH THE SAME NAME BUT DIFFERENT REGIONS ARE NOT SAVED



?>
