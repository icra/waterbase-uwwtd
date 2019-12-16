<?php
/*
  update coords
*/
include 'load_db.php';

//inputs
$taula        = SQLite3::escapeString($_POST['taula']);
$idNom        = SQLite3::escapeString($_POST['idNom']);
$idVal        = SQLite3::escapeString($_POST['idVal']);

//inputs coordenades: nom camp i nou valor
$lat_nom      = SQLite3::escapeString($_POST['lat_nom']); //per exemple 'uwwLatitude'
$lon_nom      = SQLite3::escapeString($_POST['lon_nom']); //per exemple 'aggLongitude'
$lat_nouValor = SQLite3::escapeString($_POST['lat_nouValor']); //numero
$lon_nouValor = SQLite3::escapeString($_POST['lon_nouValor']); //numero

//query
$sql="UPDATE $taula SET $lat_nom=$lat_nouValor, $lon_nom=$lon_nouValor WHERE $idNom='$idVal'";
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
