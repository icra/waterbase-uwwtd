<?php
  //discharge points with latitude or longitude NULL
  $taula="T_DischargePoints";
  $idNom="dcpCode";
  $where="WHERE
    dcpState=1 AND (
    dcpLongitude is 0    OR
    dcpLongitude is NULL OR
    dcpLatitude  is 0    OR
    dcpLatitude  is NULL)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems += $n_pro;
?>

<details class=problem open>

<summary>
  Discharge points with latitude or longitude NULL:
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
    <th>dcpLatitude
    <th>dcpLongitude
    <th>found coords in T_UWWTPS <br><small>where dcp.uwwCode==uww.uwwCode</small>
  </tr>
  <?php
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert to object
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
        <td>
          <form action=update.php method=post>
            <input type=hidden name=taula    value=$taula>
            <input type=hidden name=idNom    value=$idNom>
            <input type=hidden name=idVal    value='".$obj->$idNom."'>
            <input type=hidden name=camp     value=dcpLatitude>
            <input             name=nouValor value='$obj->dcpLatitude' placeholder='enter dcpLatitude' required autocomplete=off>
            <button>Save</button>
          </form>
        </td>
        <td>
          <form action=update.php method=post>
            <input type=hidden name=taula    value=$taula>
            <input type=hidden name=idNom    value=$idNom>
            <input type=hidden name=idVal    value='".$obj->$idNom."'>
            <input type=hidden name=camp     value=dcpLongitude>
            <input             name=nouValor value='$obj->dcpLongitude' placeholder='enter dcpLongitude' required autocomplete=off>
            <button>Save</button>
          </form>
        </td>
      ";

      //search T_UWWTPS for current dcp->uwwCode
      $res_coords_uww = $db->query("SELECT uwwName,uwwLatitude,uwwLongitude FROM T_UWWTPS WHERE uwwCode='$obj->uwwCode'");

      echo "<td>";
      while($roww=$res_coords_uww->fetchArray(SQLITE3_ASSOC)){
        $objj=(object)$roww;
        echo "<div>".google_maps_link($objj->uwwLatitude,$objj->uwwLongitude)." ($objj->uwwName)</div>";
      }

      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>

</details>
