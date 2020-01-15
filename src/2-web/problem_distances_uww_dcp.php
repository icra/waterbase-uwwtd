<?php
  #distance uww-dcp > 30 km
  $taula="T_UWWTPS AS uww, T_DischargePoints AS dcp";
  $where="WHERE uww.uwwCode = dcp.uwwCode";
?>

<b>distance uwwtp &rarr; dcp &gt; 30km</b>

<table border=1>
  <tr>
    <th>nº
    <th>uwwCode
    <th>uwwName
    <th>uww coords
    <th>dcpCode
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
        <td>$obj->uwwCode
        <td>
          <a href='view.php?taula=T_UWWTPS&idNom=uwwCode&idVal=$obj->uwwCode' target=_blank>
            $obj->uwwName
          </a>
        </td>
        <td>
          ".google_maps_link($obj->uwwLatitude, $obj->uwwLongitude)."
          <br>
          <form method=POST action='update_coords.php'>
            <input type=hidden name=taula        value='T_UWWTPS'>
            <input type=hidden name=idNom        value='uwwCode'>
            <input type=hidden name=idVal        value='$obj->uwwCode'>
            <input type=hidden name=lat_nom      value='uwwLatitude'>
            <input             name=lat_nouValor value='$obj->uwwLatitude' placeholder='uwwLatitude'>
            <input type=hidden name=lon_nom      value='uwwLongitude'>
            <input             name=lon_nouValor value='$obj->uwwLongitude' placeholder='uwwLongitude'>
            <button>guarda coordenades</button>
          </form>
        </td>
        <td>$obj->dcpCode
        <td>
          <a href='view.php?taula=T_DischargePoints&idNom=dcpCode&idVal=$obj->dcpCode' target=_blank>
            $obj->dcpName
          </a>
        </td>
        <td>
          ".google_maps_link($obj->dcpLatitude, $obj->dcpLongitude)."
          <br>
          <form method=POST action='update_coords.php'>
            <input type=hidden name=taula        value='T_DischargePoints'>
            <input type=hidden name=idNom        value='dcpCode'>
            <input type=hidden name=idVal        value='$obj->dcpCode'>
            <input type=hidden name=lat_nom      value='dcpLatitude'>
            <input             name=lat_nouValor value='$obj->dcpLatitude' placeholder='dcpLatitude'>
            <input type=hidden name=lon_nom      value='dcpLongitude'>
            <input             name=lon_nouValor value='$obj->dcpLongitude' placeholder='dcpLongitude'>
            <button>guarda coordenades</button>
          </form>
        </td>
        <td>$distance
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
