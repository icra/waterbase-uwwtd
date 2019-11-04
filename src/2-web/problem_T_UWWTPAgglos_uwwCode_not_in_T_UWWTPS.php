<?php
  //connections with uwwCode not in T_UWWTPS
  $taula="T_UWWTPAgglos";
  $idNom="aucUWWTP_AggloID";
  $where="WHERE aucUwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems+=$n_pro;
?>

<b>
  connections with aucUwwCode not in T_UWWTPS:
  <span class=n_pro><?php echo $n_pro?></span>
</b>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>aucUwwCode
    <th>aucUwwName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert to object
      echo "<tr>
        <td>$obj->aucUWWTP_AggloID
        <td>$obj->aucUwwCode
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->aucUwwName
          </a>
        </td>
        <td>$obj->rptMStateKey
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
