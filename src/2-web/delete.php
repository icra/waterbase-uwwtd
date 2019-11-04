<?php
/*
  delete one item from database
*/
include 'load_db.php';

//input
$taula = SQLite3::escapeString($_GET['taula']);
$idNom = SQLite3::escapeString($_GET['idNom']);
$idVal = SQLite3::escapeString($_GET['idVal']);

//count rows before delete
$count_1=$db->querySingle("SELECT COUNT(1) FROM $taula");

//query delete
$sql="DELETE FROM $taula WHERE $idNom='$idVal'";
$db->exec($sql) or die(print_r($db->errorInfo(), true));

//count rows after delete and calculate difference
$count_2=$db->querySingle("SELECT COUNT(1) FROM $taula");
$num_rows = $count_1 - $count_2;

//display deletion info and go back
echo "
  <ul>
    <li>$sql
    <li>Query successful
    <li>Rows eliminated: $num_rows
    <li><a href=index.php>Back</a>
  </ul>
";
?>
