<?php
  $taula="T_Agglomerations";
  $idNom="aggCode";//v7
  $where="aggCode is 0 OR aggCode is NULL";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>

<summary>
  Agglomerations where aggCode is NULL:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <?php
    $sql="SELECT * FROM $taula WHERE $where";
    echo "<tr>
      <td colspan=100 class=sql>
        <a href='problem.php?sql=$sql' target=_blank>$sql</a>
      </td>
    </tr>";
  ?>
  <tr>
    <th><?php echo $idNom?>
    <th>aggName
    <th>rptMStateKey
  </tr>
  <?php
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
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>

</details>
