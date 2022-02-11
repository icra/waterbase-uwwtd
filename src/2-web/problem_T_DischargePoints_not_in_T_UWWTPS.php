<?php
  //discharge points where uwwCode is not in T_UWWTPS
  $taula="T_DischargePoints";
  $idNom="dcpCode";
  $where="WHERE dcpState=1 AND uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems += $n_pro;
?>

<details class=problem open>

<summary>
  Discharge points where uwwCode is not in T_UWWTPS:
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
    <th>dcpName
    <th>rptMStateKey
    <th>uwwCode
    <th>coords
  </tr>
  <?php
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      echo "<tr>
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->dcpCode
          </a>
        </td>
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->dcpName
          </a>
        </td>
        <td>$obj->rptMStateKey
        <td>$obj->uwwCode
        <td>".google_maps_link($obj->dcpLatitude,$obj->dcpLongitude)."
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>

</details>
