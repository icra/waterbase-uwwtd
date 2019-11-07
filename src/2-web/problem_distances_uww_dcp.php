<?php
  #distance uww-dcp > 30 km
  $taula="T_UWWTPS AS uww, T_DischargePoints AS dcp";
  $where="WHERE uww.uwwCode = dcp.uwwCode";
?>

<b>distance uwwtp &rarr; dcp &gt; 30km</b>

<table border=1>
  <tr>
    <th>nº
    <th>uwwName
    <th>uww coords
    <th>dcpName
    <th>dcp coords
    <th>distance (km)
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query($sql);
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj = (object)$row; //convert row to object
      $distance=distance($obj->dcpLatitude, $obj->dcpLongitude, $obj->uwwLatitude, $obj->uwwLongitude);
      if($distance==false) continue;
      if($distance<30) continue;
      echo "<tr>
        <td>$i
        <td>$obj->uwwName
        <td>".google_maps_link($obj->uwwLatitude, $obj->uwwLongitude)."
        <td>$obj->dcpName
        <td>".google_maps_link($obj->dcpLatitude, $obj->dcpLongitude)."
        <td>$distance
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
