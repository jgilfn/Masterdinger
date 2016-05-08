<html lang="en">
<?php include "head.php"; ?>

<body>

  <div class="background"></div>

  <?php include "header.php"; ?>

  <div class="main">

    <div class="main-content">
      <div class="block">
        <div class="logo wow fadeInUp" data-wow-delay="100ms">
          <img src="images/masterdinger.png" alt="Masterdinger"/>
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

      </div>
    </div>
  </div>

  <div class="features">
	<div class="container">
		<div class="col-md-4 wow fadeInUp" data-wow-delay="100ms">
			<h2>Detailed Champion Mastery Statistics</h2>
			<img src="images/details.png" alt="Detailed Information"/>
			<p>Find detailed statistics about a Summoner's Champion Mastery, aswell as overall champions statistics and performance.</p>
		</div>
		<div class="col-md-4 wow fadeInUp" data-wow-delay="200ms">
			<h2>Compare stats of two Summoners</h2>
			<img src="images/vs.png" alt="Detailed Information"/>
			<p>Compare two Summoners champion mastery statistics to see who has better statistics, not necessarily who's the best player.</p>
		</div>
		<div class="col-md-4 wow fadeInUp" data-wow-delay="300ms">
			<h2>Check the leaderboard</h2>
			<img src="images/highscores.png" alt="Detailed Information"/>
			<p>Check the leaderboard to see what Summoners, that have been searched in our website already, have the highest scores.</p>
		</div>
	</div>
  </div>

  <?php include "footer.php"; ?>

</body>

</html>
