<?php
  $taula="T_DischargePoints";
  $idNom="dcpCode";//v7
  $where="dcpCode is 0 OR dcpCode is NULL";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>

<summary>
  Discharge points where dcpCode is NULL:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>dcpName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula WHERE $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      echo "<tr>
        <td>".$obj->$idNom."
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->dcpName
          </a>
        </td>
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
