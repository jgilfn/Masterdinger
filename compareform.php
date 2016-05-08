<form class="navbar-form navbar-right barSearch" role="Search" action="compare.php" method="get">
  <div class="form-group">
    <input type="text" class="form-control" maxlength="16" name="Summoner2" placeholder="Compare to this Summoner">
  </div>
  <select name="Region2">
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

  <input type="hidden" name="Summoner1" value="<?php echo $_GET['Summoner'];?>"></input>
  <input type="hidden" name="Region1" value="<?php echo $_GET['Region'];?>"></input>
</form>
