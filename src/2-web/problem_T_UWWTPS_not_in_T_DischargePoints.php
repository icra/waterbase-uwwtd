<?php
  //uwwtps not in T_DischargePoints
  $taula="T_UWWTPS";
  $idNom="uwwCode";
  $where="WHERE uwwState=1 AND uwwCode NOT IN (SELECT uwwCode FROM T_DischargePoints)";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>

<summary>
  Uwwtps not in T_DischargePoints:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>uwwName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert to object
      echo "<tr>
        <td>".$obj->$idNom."
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->uwwName
          </a>
        </td>
        <td>$obj->rptMStateKey
      ";
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
