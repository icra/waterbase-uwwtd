<?php
  //duplicate emissions
  $taula="T_UWWTPS_emission_load";
  $idNom="uwwCode";
  $where="GROUP BY uwwCode HAVING COUNT(uwwCode)>1";
  $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
  $total_problems += $n_pro;
?>

<details class=problem open>
<summary>
  Emissions where uwwCode is duplicated:
  <span class=n_pro><?php echo (is_null($n_pro)?"0":$n_pro) ?></span>
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
      $obj=(object)$row; //convert row to object

      //busca emission loads duplicats
      $res_2=$db->query("SELECT * FROM $taula WHERE uwwCode='$obj->uwwCode'");
      while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
        $obj_2 = (object)$row_2; //convert row to object
        echo "<tr>
          <td>$obj_2->uwwCode
          <td>
            <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>
              $obj_2->uwwName
            </a>
          </td>
          <td>$obj_2->rptMStateKey
        ";
      }
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>
</details>
