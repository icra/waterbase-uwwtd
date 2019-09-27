<?php
/*
  view single item
*/
$db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');

//input
$taula = SQLite3::escapeString($_GET['taula']);
$idNom = SQLite3::escapeString($_GET['idNom']);
$idVal = SQLite3::escapeString($_GET['idVal']);
?>
<!doctype html><html><head>
  <meta charset=utf8>
  <title><?php echo "$taula $idVal"?></title>
</head><body>

<h3><?php echo "Taula $taula, id $idVal"?></h3>

<?php
  $sql="SELECT * FROM $taula WHERE $idNom='$idVal'";
  $res=$db->query($sql);
  while($row=$res->fetchArray(SQLITE3_ASSOC)){
    echo "<table border=1>";
    $obj = (object)$row; //convert to object
    //var_dump($obj);break;
    foreach($obj as $key=>$val){
      echo "<tr>
        <th>$key
        <td>$val
      ";
    }
    echo "</table><hr>";
  }
?>
