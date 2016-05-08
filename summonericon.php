<?php //load the image

  cropImage("http://ddragon.leagueoflegends.com/cdn/" . $_GET['version'] . "/img/champion/" . $_GET['key'] . ".png", 10, 10, 100, 100);

  function cropImage($imagePath, $startX, $startY, $width, $height) {
    $remote_image = file_get_contents($imagePath);
    $imagick = new Imagick();
    $imagick -> readImageBlob($remote_image);
    $imagick->cropImage($width, $height, $startX, $startY);
      header("Content-Type: image/png");
      echo $imagick->getImageBlob();
  } ?>
