<?php $currentpage = basename($_SERVER['PHP_SELF']); ?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
</button>
      <a class="navbar-brand" style="overflow: visible !important;" href="index.php">
        <img alt="Brand" src="images/smallIcon.png">
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
    		<li><a href="leaderboard.php">Leaderboard</a></li>
    		<li><a href="about.php">About</a></li>
      </ul>
	  <?php
		if($currentpage != "index.php")
		{
			 ?>
		<form class="navbar-form navbar-right barSearch" role="Search" action="search.php" method="get">
			<div class="form-group">
				<input type="text" class="form-control" maxlength="16" name="Summoner" placeholder="Search a Summoner's Champions Mastery">
			</div>
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
			<button><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
		</form>
			 <?php
		}
	  ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
