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
        <td>".google_maps_link($obj->aggLatitude, $obj->aggLongitude)."
        <td>$obj->uwwName
        <td>".google_maps_link($obj->uwwLatitude, $obj->uwwLongitude)."
        <td>$distance
      ";
      //if($i==$limit)break;
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
