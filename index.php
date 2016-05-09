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
		<div class="col-md-4">
			<h2>Detailed Champion Mastery Info</h2>
			<img src="images/details.png"/>
			<p>Find detailed information about a Summoner's Champion Mastery, aswell as overall champions statistics and performance.</p>
      <p>Wanna know if you've already received a Chest for a Champion? No problem. Or how many points you still have to get to reach next level? Got it.</p>
      <p>Maybe you just want to see what was your Highest Grade? Easy. Search your Summoner name and you'll find it all.</p>
		</div>
		<div class="col-md-4">
			<h2>Compare Two Summoners</h2>
			<img src="images/vs.png"/>
			<p>Compare two Summoners to see who has more points, highest grade and highest level. Do not confuse this with who's the best player.</p>
      <p>This can be accessed from the search page, after you search one Summoner you can compare it to the other.</p>
      <p>Just look at the bar below your Summoner info, find the Compare input, type in your name, select region and you're ready to go!</p>
		</div>
		<div class="col-md-4">
			<h2>Check the Leaderboard</h2>
			<img src="images/highscores.png"/>
			<p>Check the leaderboard to see what Summoners, that have been searched in our website already, have the highest scores.</p>
      <p>You can see the Leaderboard of Total Points, Champion Points or of a specific champion.</p>
      <p>If you are curious, you can see global statistics or per champion statistics, such as: average Highest Grade, percentage of Chests granted, average Score, etc.</p>
		</div>
	</div>
  </div>

  <?php include "footer.php"; ?>

</body>

</html>
