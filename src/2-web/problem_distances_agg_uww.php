<?php
  #distance agg-uww > 30 km
  $taula="T_Agglomerations AS agg, T_UWWTPS AS uww, T_UWWTPAgglos AS auc";
  $where="WHERE 
    auc.aucAggCode = agg.aggCode AND
    auc.aucUwwCode = uww.uwwCode
  ";
?>

<b>distance agglomeration &rarr; uwwtp &gt; 30km</b>

<table border=1>
  <tr>
    <th>nº
    <th>aggName
    <th>agg Coords
    <th>uwwName
    <th>uww Coords
    <th>distance (km)
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query($sql);
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj = (object)$row; //convert row to object
      $distance=distance($obj->aggLatitude, $obj->aggLongitude, $obj->uwwLatitude, $obj->uwwLongitude);
      if($distance==false) continue;
      if($distance<30) continue;
      echo "<tr>
        <td>$i
        <td>$obj->aggName
        <td>
          ".google_maps_link($obj->aggLatitude, $obj->aggLongitude)."
          <br>
          <form method=POST action='update.php'>
            <input type=hidden name=taula    value='T_Agglomerations'>
            <input type=hidden name=idNom    value='aggCode'>
            <input type=hidden name=idVal    value='$obj->aggCode'>
            <input type=hidden name=camp     value='aggLatitude'>
            <input name=nouValor value='$obj->aggLatitude' placeholder='aggLatitude'>
            <button>guarda aggLatitude</button>
          </form>
          <form method=POST action='update.php'>
            <input type=hidden name=taula    value='T_Agglomerations'>
            <input type=hidden name=idNom    value='aggCode'>
            <input type=hidden name=idVal    value='$obj->aggCode'>
            <input type=hidden name=camp     value='aggLongitude'>
            <input name=nouValor value='$obj->aggLongitude' placeholder='aggLongitude'>
            <button>guarda aggLongitude</button>
          </form>
        </td>
        <td>$obj->uwwName
        <td>
          ".google_maps_link($obj->uwwLatitude, $obj->uwwLongitude)."
          <br>
          <form method=POST action='update.php'>
            <input type=hidden name=taula    value='T_UWWTPS'>
            <input type=hidden name=idNom    value='uwwCode'>
            <input type=hidden name=idVal    value='$obj->uwwCode'>
            <input type=hidden name=camp     value='uwwLatitude'>
            <input name=nouValor value='$obj->uwwLatitude' placeholder='uwwLatitude'>
            <button>guarda uwwLatitude</button>
          </form>
          <form method=POST action='update.php'>
            <input type=hidden name=taula    value='T_UWWTPS'>
            <input type=hidden name=idNom    value='uwwCode'>
            <input type=hidden name=idVal    value='$obj->uwwCode'>
            <input type=hidden name=camp     value='uwwLongitude'>
            <input name=nouValor value='$obj->uwwLongitude' placeholder='uwwLongitude'>
            <button>guarda uwwLongitude</button>
          </form>
        </td>
        <td>$distance
      ";
      //if($i==$limit)break;
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
