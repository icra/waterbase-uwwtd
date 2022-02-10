<?php
  include'load_db.php';
  $sql = SQLite3::escapeString($_GET['sql']);
  $sql = urldecode($sql);
  $lc = strtolower($sql);

  if(
    strpos($lc,"create")!==false or
    strpos($lc,"drop")!==false or
    strpos($lc,"truncate")!==false or
    strpos($lc,"insert")!==false or
    strpos($lc,"delete")!==false or
    strpos($lc,"update")!==false or
    false
  ){
    die("Command <code>$sql</code> not allowed");
  }
?>

<code><?php echo $sql?><code><hr>

<table border=1>
<?php
  $res=$db->query($sql);
  $i=0;
  while($row=$res->fetchArray(SQLITE3_ASSOC)){
    $obj=(object)$row; //convert to object

    //print column names
    if($i==0){
      echo "<tr>";
      foreach($obj as $key=>$val){
        echo "<th>$key</th>";
      }
      echo "</tr>";
    }

    //new row
    echo "<tr>";

    //iterate keys
    foreach($obj as $key=>$val){
      echo "<td>$val</td>";
    }
    echo "</tr>";
    $i++;
  }
?>
</table>
