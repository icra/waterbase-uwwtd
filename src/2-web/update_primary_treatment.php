<?php
/*
  update uwwPrimaryTreatment only
*/
include 'load_db.php';

//inputs
$uwwCode = SQLite3::escapeString($_POST['uwwCode']);

//query
$sql="
  UPDATE T_UWWTPS
  SET uwwPrimaryTreatment=1
  WHERE uwwCode='$uwwCode'
";
$db->exec($sql) or die(print_r($db->errorInfo(), true));

//display update info and go back
echo "
  <ul>
    <li>$sql
    <li>Query successful
    <li><a href=index.php>Back</a>
  </ul>
";
?>
