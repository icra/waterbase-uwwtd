<?php
  #distance agg-uww > 30 km
  $taula="T_Agglomerations AS agg, T_UWWTPS AS uww, T_UWWTPAgglos AS auc";
  $where="WHERE
    auc.aucAggCode = agg.aggCode AND
    auc.aucUwwCode = uww.uwwCode
  ";
  $n_pro=0;
?>

<details class=problem open>

<summary>
  Distance agglomeration &rarr; uwwtp &gt; 30km:
  <span class=n_pro id=problem_distances_agg_uww>0</span>
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
    <th>nº
    <th>aggCode
    <th>aggName
    <th>agg Coords
    <th>uwwName
    <th>uww Coords
    <th>distance (km)
  </tr>
  <?php
    $res=$db->query($sql);
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj = (object)$row; //convert row to object
      $distance=distance($obj->aggLatitude, $obj->aggLongitude, $obj->uwwLatitude, $obj->uwwLongitude);
      if($distance==false || $distance<30) continue;
      $n_pro++;
      echo "<tr>
        <td>$i
        <td>$obj->aucAggCode
        <td>
          <a href='view.php?taula=T_Agglomerations&idNom=aggCode&idVal=$obj->aggCode' target=_blank>
            $obj->aggName
          </a>
        </td>
        <td>
          ".google_maps_link($obj->aggLatitude, $obj->aggLongitude)."
          <br>
          <form method=POST action='update_coords.php'>
            <input type=hidden name=taula    value='T_Agglomerations'>
            <input type=hidden name=idNom    value='aggCode'>
            <input type=hidden name=idVal    value='$obj->aucAggCode'>
            <input type=hidden name=lat_nom      value='aggLatitude'>
            <input             name=lat_nouValor value='$obj->aggLatitude' placeholder='aggLatitude'>
            <input type=hidden name=lon_nom      value='aggLongitude'>
            <input             name=lon_nouValor value='$obj->aggLongitude' placeholder='aggLongitude'>
            <button>guarda coordenades</button>
          </form>
        </td>
        <td>
          <a href='view.php?taula=T_UWWTPS&idNom=uwwCode&idVal=$obj->uwwCode' target=_blank>
            $obj->uwwName
          </a>
        </td>

        <td>
          ".google_maps_link($obj->uwwLatitude, $obj->uwwLongitude)."
          <br>

          <form method=POST action='update_coords.php'>
            <input type=hidden name=taula    value='T_UWWTPS'>
            <input type=hidden name=idNom    value='uwwCode'>
            <input type=hidden name=idVal    value='$obj->uwwCode'>
            <input type=hidden name=lat_nom      value='uwwLatitude'>
            <input             name=lat_nouValor value='$obj->uwwLatitude' placeholder='uwwLatitude'>
            <input type=hidden name=lon_nom      value='uwwLongitude'>
            <input             name=lon_nouValor value='$obj->uwwLongitude' placeholder='uwwLongitude'>
            <button>guarda coordenades</button>
          </form>
        </td>
        <td>$distance
      ";
      //if($i==$limit)break;
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    $total_problems += $n_pro;
  ?>
</table>

<script>
  document.querySelector("span#problem_distances_agg_uww").innerHTML="<?php echo $n_pro?>";
</script>

</details>
