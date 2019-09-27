<?php
/*
  delete one item from database
*/
include 'load_db.php';

//input
$taula = SQLite3::escapeString($_GET['taula']);
$idNom = SQLite3::escapeString($_GET['idNom']);
$idVal = SQLite3::escapeString($_GET['idVal']);

//query
$sql="DELETE FROM $taula WHERE $idNom='$idVal'";
$db->exec($sql) or die(print_r($db->errorInfo(), true));

//tornar enrere
echo "
  <ul>
    <li>$sql
    <li>Query successful
    <li><a href=index.php>Back</a>
  </ul>
";
?>
