<?php
/*
  update one field of one item from database
*/
$db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');

//input
$taula    = SQLite3::escapeString($_POST['taula']);
$idNom    = SQLite3::escapeString($_POST['idNom']);
$idVal    = SQLite3::escapeString($_POST['idVal']);
$camp     = SQLite3::escapeString($_POST['camp']);
$nouValor = SQLite3::escapeString($_POST['nouValor']);

//query
$sql="UPDATE $taula SET $camp='$nouValor' WHERE $idNom=$idVal";
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
