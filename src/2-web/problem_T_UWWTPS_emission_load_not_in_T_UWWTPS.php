<?php
  //emissions with uwwCode not in T_UWWTPS
  $taula="T_UWWTPS_emission_load";
  $idNom="uwwCode";
  $where="WHERE uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>
<summary>
  Emissions where uwwCode is not in T_UWWTPS:
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
    <th>uwwCode
    <th>uwwName
    <th>rptMStateKey
  </tr>
  <?php
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
  ?>
</table>
</details>
