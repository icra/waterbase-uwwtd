<?php
  #agglomerations amb longitud o latitud NULL
  $taula="T_Agglomerations";
  $idNom="aggCode";//v7
  $where="
    aggState=1 AND (
    aggLongitude is 0    OR
    aggLongitude is NULL OR
    aggLatitude  is 0    OR
    aggLatitude  is NULL
    )
  ";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>

<summary>
  Agglomerations where latitude or longitude is NULL:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>aggName
    <th>aggLatitude
    <th>aggLongitude
    <th>rptMStateKey
    <th>
      found coords in T_UWWTPS<br>
      <small>uww.aggCode==agg.aggCode</small>
    </th>
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
            $obj->aggName
          </a>
        </td>
        <td>$obj->rptMStateKey
        <td>
          <form action=update.php method=post>
            <input type=hidden name=taula    value=$taula>
            <input type=hidden name=idNom    value=$idNom>
            <input type=hidden name=idVal    value='".$obj->$idNom."'>
            <input type=hidden name=camp     value=aggLatitude>
            <input             name=nouValor value='$obj->aggLatitude' placeholder='enter aggLatitude' required autocomplete=off>
            <button>Save</button>
          </form>
        </td>
        <td>
          <form action=update.php method=post>
            <input type=hidden name=taula    value=$taula>
            <input type=hidden name=idNom    value=$idNom>
            <input type=hidden name=idVal    value='".$obj->$idNom."'>
            <input type=hidden name=camp     value=aggLongitude>
            <input             name=nouValor value='$obj->aggLongitude' placeholder='enter aggLongitude' required autocomplete=off>
            <button>Save</button>
          </form>
        </td>
      ";

      //search T_UWWTPS for current aggCode
      echo "<td>";
      $ress=$db->query("SELECT * FROM T_UWWTPS WHERE aggCode='$obj->aggCode'");
      while($roww=$ress->fetchArray(SQLITE3_ASSOC)){
        $objj=(object)$roww;
        echo "<div>
          ".google_maps_link($objj->uwwLatitude,$objj->uwwLongitude)."
        </div>";
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
