<?php

require_once "functions.php";

//Gathers data from a champion according to its ID. Will choose from the DB if it is already there or will request data from the API if it is a new champion
class ChampionData
{
  public $id;
  public $name;
  public $title;
  public $maintag;
  public $secondtag;

  public $key;

  function getData ($id, $region, $apikey)
  {
    include "vars.php";

    //Connects to database and checks if there is information about that champion
    $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM Champions WHERE ID='" . $id . "' LIMIT 1"));

    $this->id = $id;

    //If the db has information about that champion then it will get it from there
    if (isset($result['ID']))
    {
      $this->name = str_replace("§", "'", $result['Name']);
      $this->title = str_replace("§", "'", $result['Title']);
      $this->key = $result['ChampKey'];
      $this->maintag = $result['MainTag'];
      $this->secondtag = $result ['SecondTag'];
    }
    //Else it will get it from the API and store it in the DB.
    else {

      //Get champion-specific information, such as name and title, from api

      $ChampionInfo = loadAPIPage("https://global.api.pvp.net/api/lol/static-data/" . $region . "/v1.2/champion/" . $id . "?champData=tags&api_key=" . $apikey);

      $this->name = $ChampionInfo['name'];
      $this->title = $ChampionInfo['title'];
      $this->maintag = $ChampionInfo['tags'][0];
      $this->secondtag = $ChampionInfo['tags'][1];
      $this->key = $ChampionInfo['key'];

      //stores the champion in the database
      if ($id != 0 && isset($this->name))
      {
        insertChampionDB($id, str_replace("'", "§", $this->name), str_replace("'", "§", $this->title), $this->key, $this->maintag, $this->secondtag);
      }
    }
  }

}

?>
