<?php
  #troba aglomeracions duplicades
  $taula="T_Agglomerations";
  $idNom="aggCode";//v7
  $where="GROUP BY aggCode HAVING COUNT(aggCode)>1";
  $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
  $total_problems += $n_pro;
?>

<details class=problem open>

<summary>
  Duplicated agglomerations:
  <span class=n_pro><?php echo (is_null($n_pro)?"0":$n_pro)?></span>
</summary>

<table border=1>
  <tr>
    <th><?php echo $idNom?>
    <th>aggName
    <th>rptMStateKey
  </tr>
  <?php
    $sql="SELECT * FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;

      //busca aglomeracions duplicades
      $res_2=$db->query("SELECT * FROM $taula WHERE aggCode='$obj->aggCode'");
      while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
        $obj_2=(object)$row_2;
        echo "<tr>
          <td>$obj_2->aggCode
          <td>
            <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>
              $obj_2->aggName
            </a>
          </td>
          <td>$obj_2->rptMStateKey
        ";
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
