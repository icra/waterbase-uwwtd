<?php
  #agglomerations not in T_UWWTPAgglos
  $taula="T_Agglomerations";
  $idNom="aggCode";

  /*
  $where="WHERE
    aggState=1 AND
    aggGenerated>=2000 AND
    aggCode NOT IN (SELECT aucAggCode FROM T_UWWTPAgglos) AND
    (
      aggRemarks IS NULL OR
      (
        aggRemarks NOT LIKE '%IAS%' AND aggRemarks NOT LIKE '%septic%'
      )
    )
  ";
  */

  $where="
    WHERE
      aggState=1 AND
      aggCode NOT IN (SELECT aucAggCode FROM T_UWWTPAgglos) AND
      aggCode NOT IN (SELECT aggCode FROM T_UWWTPS WHERE uwwState=1)
    ORDER BY
      aggGenerated DESC
  ";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
  $total_problems += $n_pro;
?>

<details class=problem open>

<summary>
  Agglomerations not in T_UWWTPAgglos:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<span>"Agglomerations not connected to any uwwtp"</span>

<table border=1>
  <?php
    $sql="SELECT * FROM $taula $where";
    echo "<tr>
      <td colspan=100 class=sql>
        <a href='problem.php?sql=".urlencode($sql)."' target=_blank>$sql</a>
      </td>
    </tr>";
  ?>
  <tr>
    <th><?php echo $idNom?>
    <th>aggName
    <th>aggState
    <th>aggGenerated
    <th>aggRemarks
    <th>rptMStateKey
    <th>aggPercWithoutTreatment
    <th>found in T_UWWTPS?
  </tr>
  <?php
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;

      //mostra dades aglomeració
      echo "<tr>
        <td>".$obj->$idNom."</td>
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->aggName
          </a>
        <td>$obj->aggState
        <td>$obj->aggGenerated
        <td>$obj->aggRemarks
        <td>$obj->rptMStateKey
        <td>$obj->aggPercWithoutTreatment
        <td>
      ";

      //search current aggCode in T_UWWTPS
      $ress=$db->query("SELECT * FROM T_UWWTPS WHERE aggCode='$obj->aggCode'");
      while($roww=$ress->fetchArray(SQLITE3_ASSOC)){
        $objj=(object)$roww;
        echo "<div>
          <a target=_blank href='view.php?taula=T_UWWTPS&idNom=uwwCode&idVal=$objj->uwwCode'>
            $objj->uwwName
          </a>
        </div>";
      }

      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>

</details>
