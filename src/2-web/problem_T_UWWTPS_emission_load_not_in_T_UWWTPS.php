<?php
  //emissions with uwwCode not in T_UWWTPS
  $taula="T_UWWTPS_emission_load";
  $idNom="uwwCode";
  $where="WHERE uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems+=$n_pro;
?>

<b>
  emissions with uwwCode not in T_UWWTPS:
  <span class=n_pro><?php echo $n_pro?></span>
</b>

<table border=1>
  <tr>
    <th>uwwCode
    <th>uwwName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      echo "<tr>
        <td>$obj->uwwCode
        <td>$obj->uwwName
        <td>$obj->rptMStateKey
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr><td colspan=100 class=sql>$sql";
  ?>
</table>
