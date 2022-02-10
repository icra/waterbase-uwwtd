<?php
  //connections where uwwCode NULL or aggCode NULL
  $taula="T_UWWTPAgglos";
  $idNom="aucUWWTP_AggloID";
  $where="WHERE aucUwwCode is NULL or aucAggCode is NULL";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems += $n_pro;
?>

<details class=problem open>

<summary>
  Connections where uwwCode or aggCode NULL:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>aucUwwCode
    <th>aucUwwName
    <th>aucAggCode
    <th>aucAggName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      echo "<tr>
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            ".$obj->$idNom."
          </a>
        </td>
        <td>$obj->aucUwwCode
        <td>$obj->aucUwwName
        <td>$obj->aucAggCode
        <td>$obj->aucAggName
        <td>$obj->rptMStateKey
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr>
      <td colspan=100 class=sql>
        <a href='problem.php?sql=$sql' target=_blank>$sql</a>
      </td>
    </tr>";
  ?>
</table>

</details>
