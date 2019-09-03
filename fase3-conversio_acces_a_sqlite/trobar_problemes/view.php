<?php
/*
  view single item
*/
$db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');

//input
$taula    = SQLite3::escapeString($_GET['taula']);
$idNom    = SQLite3::escapeString($_GET['idNom']);
$idVal    = SQLite3::escapeString($_GET['idVal']);

?>

Taula <?php echo $taula?>

<table border=1>
<?php
  $sql="SELECT * FROM $taula WHERE $idNom=$idVal";
  $res=$db->query($sql);
  while($row=$res->fetchArray(SQLITE3_ASSOC)){
    $obj = (object)$row; //convert to object
    //var_dump($obj);break;
    foreach($obj as $key=>$val){
      echo "<tr>
        <th>$key
        <td>$val
      ";
    }
  }
  ?>
</table>
