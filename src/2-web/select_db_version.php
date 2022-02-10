<form action="setcookie_db_version.php" method="get">
  <b>Current db version: <?php echo $db_version?></b> |
  <span>
    Select db version
    <select name="db_version">
      <?php
        foreach($db_versions as $version){
          $selected = $db_version==$version?"selected":"";
          echo"
            <option value='$version' $selected>$version</option>
          ";
        }
      ?>
    </select>
    <button>go</button>
  </span>
</form>
