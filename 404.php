<html lang="en">
<?php include "head.php";
?>

<body>

  <div class="background"></div>

  <?php include "header.php"; ?>

  <div class="main">
    <?php
    header("HTTP/1.0 404 Not Found");

      $sounds = array(
        1 => array (
        'sound' => 'http://vignette3.wikia.nocookie.net/leagueoflegends/images/c/c6/Heimerdinger.move24.ogg/revision/latest?cb=20140228032949',
        'text' => 'Not enough variables... hmm... not nearly enough variables.'
      ),
        2 => array (
        'sound' => 'http://vignette2.wikia.nocookie.net/leagueoflegends/images/b/bd/Heimerdinger.taunt02.ogg/revision/latest?cb=20140228033044',
        'text' => 'Don\'t worry, you can learn so much from failure.'
      ),
        3 => array (
        'sound' => 'http://vignette2.wikia.nocookie.net/leagueoflegends/images/6/68/Heimerdinger.RQ1.ogg/revision/latest?cb=20140228032951',
        'text' => 'It\'s gone mad!'
      ),
        4 => array (
        'sound' => 'http://vignette1.wikia.nocookie.net/leagueoflegends/images/f/f4/Heimerdinger.move23.ogg/revision/latest?cb=20140228032839',
        'text' => 'Fascinating, isn\'t it?'
      ),
        5 => array (
        'sound' => 'http://vignette4.wikia.nocookie.net/leagueoflegends/images/4/4a/Heimerdinger.move22.ogg/revision/latest?cb=20140228032839',
        'text' => 'One step closer to greater understanding!'
      ),
        6 => array (
        'sound' => 'http://vignette4.wikia.nocookie.net/leagueoflegends/images/7/77/Heimerdinger.move16.ogg/revision/latest?cb=20140228032837',
        'text' => 'What an uncommon denominator!'
      ),
        7 => array (
        'sound' => 'http://vignette1.wikia.nocookie.net/leagueoflegends/images/3/3f/Heimerdinger.move07.ogg/revision/latest?cb=20140228032758',
        'text' => 'Hmm... let me fix that.'
      ),
        8 => array (
        'sound' => 'http://vignette2.wikia.nocookie.net/leagueoflegends/images/1/16/Heimerdinger.move17.ogg/revision/latest?cb=20140228032837',
        'text' => 'Back to the drawing board!'
      )
      );

      $rand = rand(1, count($sounds));
    ?>

    <div class="main-content">
      <div class="block">
        <div class="logo">
          <h1 style="color: #fff;"><?php echo $sounds[$rand]['text']; ?></h1>

          <h2 style="color: #fff; margin-top: 10vh;">Error 404: Page not found!</h2>
        </div>

        <audio style="opacity: 0;" controls autoplay>
 <source src="<?php echo $sounds[$rand]['sound']; ?>" type="audio/ogg">
 Your browser does not support the audio element.
</audio>


      </div>
    </div>
  </div>
</body>
</html>
