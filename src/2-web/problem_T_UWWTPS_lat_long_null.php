<?php
  //wwpts with longitude or latitude NULL
  $taula="T_UWWTPS";
  $idNom="uwwCode";//v7
  $where="uwwLongitude is 0    OR 
          uwwLongitude is NULL OR 
          uwwLatitude  is 0    OR 
          uwwLatitude  is NULL";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
  $total_problems+=$n_pro;
?>

<b>
  uwwtps with latitude or longitude NULL:
  <span class=n_pro><?php echo $n_pro?></span>
</b>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>uwwName
    <th>rptMStateKey
    <th>uwwLatitude
    <th>uwwLongitude
    <th>found coords in T_Agglomerations  <br><small>where agg.aggCode==uww.aggCode</small>
    <th>found coords in T_DischargePoints <br><small>where dcp.uwwCode==uww.uwwCode</small>
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
            $obj->uwwName
          </a>
        </td>
        <td>$obj->rptMStateKey
        <td>
          <form action=update.php method=post>
            <input type=hidden name=taula    value=$taula>
            <input type=hidden name=idNom    value=$idNom>
            <input type=hidden name=idVal    value='".$obj->$idNom."'>
            <input type=hidden name=camp     value=uwwLatitude>
            <input             name=nouValor value='$obj->uwwLatitude' placeholder='enter uwwLatitude' required autocomplete=off>
            <button>Save</button>
          </form>
        </td>
        <td>
          <form action=update.php method=post>
            <input type=hidden name=taula    value=$taula>
            <input type=hidden name=idNom    value=$idNom>
            <input type=hidden name=idVal    value='".$obj->$idNom."'>
            <input type=hidden name=camp     value=uwwLongitude>
            <input             name=nouValor value='$obj->uwwLongitude' placeholder='enter uwwLongitude' required autocomplete=off>
            <button>Save</button>
          </form>
        </td>
      ";

      //search T_Agglomerations and T_DischargePoints for current uwwCode
      $res_coords_agg = $db->query("SELECT aggName,aggLatitude,aggLongitude FROM T_Agglomerations  WHERE aggCode='$obj->aggCode'");
      $res_coords_dcp = $db->query("SELECT dcpName,dcpLatitude,dcpLongitude FROM T_DischargePoints WHERE uwwCode='$obj->uwwCode'");

      echo "<td>";
      while($roww=$res_coords_agg->fetchArray(SQLITE3_ASSOC)){
        $objj=(object)$roww;
        echo "<div>".google_maps_link($objj->aggLatitude,$objj->aggLongitude)." ($objj->aggName)</div>";
      }
      echo "<td>";
      while($roww=$res_coords_dcp->fetchArray(SQLITE3_ASSOC)){
        $objj=(object)$roww;
        echo "<div>".google_maps_link($objj->dcpLatitude,$objj->dcpLongitude)." ($objj->dcpName)</div>";
      }

      $i++;
    }

    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
