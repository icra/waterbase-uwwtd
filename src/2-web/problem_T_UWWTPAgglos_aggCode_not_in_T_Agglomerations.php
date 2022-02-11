<?php
  //connections with aggCode not in T_Agglomerations
  $taula="T_UWWTPAgglos";
  $idNom="aucUWWTP_AggloID";
  $where="WHERE aucAggCode NOT IN (SELECT aggCode FROM T_Agglomerations)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>

<summary>
  Connections where aucAggCode not in T_Agglomerations:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <?php
    $sql="SELECT * FROM $taula $where";
    echo "<tr>
      <td colspan=100 class=sql>
        <a href='problem.php?sql=$sql' target=_blank>$sql</a>
      </td>
    </tr>";
  ?>
  <tr>
    <th><?php echo $idNom?>
    <th>aucAggCode
    <th>aucAggName
    <th>rptMStateKey
  </tr>
  <?php
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert to object
      echo "<tr>
        <td>$obj->aucUWWTP_AggloID
        <td>$obj->aucAggCode
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->aucAggName
          </a>
        </td>
        <td>$obj->rptMStateKey
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>

</details>
