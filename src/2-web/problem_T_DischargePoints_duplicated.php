<?php
  //dcps duplicats
  $taula="T_DischargePoints";
  $idNom="dcpCode";
  $where="GROUP BY dcpCode HAVING COUNT(dcpCode)>1";
  $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
  $total_problems += $n_pro;
?>

<details class=problem open>

<summary>
  Duplicated discharge points:
  <span class=n_pro><?php echo (is_null($n_pro)?"0":$n_pro) ?></span>
</summary>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>dcpName
    <th>rptMStateKey
    <th>coords
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert row to object

      //busca dps duplicats
      $res_2=$db->query("SELECT * FROM $taula WHERE dcpCode='$obj->dcpCode'");
      while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
        $obj_2=(object)$row_2; //convert row to object
        echo "<tr>
          <td>".$obj_2->$idNom."
          <td>
            <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>
              $obj_2->dcpName
            </a>
          </td>
          <td>$obj_2->rptMStateKey
          <td>".google_maps_link($obj_2->dcpLatitude, $obj_2->dcpLongitude)."
        ";
      }

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
