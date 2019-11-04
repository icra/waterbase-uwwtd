<?php
  #agglomerations not in T_UWWTPAgglos
  $taula="T_Agglomerations";
  $idNom="aggCode";
  $where="WHERE aggCode NOT IN (SELECT aucAggCode FROM T_UWWTPAgglos)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems += $n_pro;
?>
<b>
  agglomerations not in T_UWWTPAgglos:
  <span class=n_pro><?php echo $n_pro?></span>
</b>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>aggName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      echo "<tr>
        <td>".$obj->$idNom."
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->aggName
          </a>
        <td>$obj->rptMStateKey
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
