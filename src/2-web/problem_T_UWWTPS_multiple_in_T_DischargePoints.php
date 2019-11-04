<?php
  //uwwtps with multiple discharge points
  $taula="T_UWWTPS";
  $idNom="uwwCode";
  $where="WHERE uwwCode IN (
    SELECT uwwCode FROM T_DischargePoints
    GROUP BY uwwCode HAVING COUNT(uwwCode)>1
  )";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems+=$n_pro;
?>

<b>
  uwwtps with multiple entries in T_DischargePoints:
  <span class=n_pro><?php echo $n_pro?></span>
</b>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>uwwName
    <th>rptMStateKey
    <th>coords
    <th>repeated uwwCodes found in T_DischargePoints
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert to object
      echo "<tr>
        <td>".$obj->$idNom."
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->uwwName
          </a>
        </td>
        <td>$obj->rptMStateKey
        <td>".google_maps_link($obj->uwwLatitude,$obj->uwwLongitude)."
      ";
      echo "<td>";
      $ress=$db->query("SELECT * FROM T_DischargePoints WHERE uwwCode='$obj->uwwCode'");
      while($roww=$ress->fetchArray(SQLITE3_ASSOC)){
        $objj=(object)$roww;
        echo "<div>
          <a href='view.php?taula=T_DischargePoints&idNom=dcpDischargePointsID&idVal=$objj->dcpDischargePointsID' target=_blank>$objj->dcpName</a>
          (".google_maps_link($objj->dcpLatitude,$objj->dcpLongitude).")
        </div>";
      }
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
