<?php
  #check that PE sum is 100%
  $cols ="*,
    aggC1 AS c1,
    aggC2 AS c2,
    aggPercWithoutTreatment AS c3,
    aucPercEnteringUWWTP AS c4,
    aucPercC2T AS c5";
  $taula="T_Agglomerations AS agg, T_UWWTPAgglos AS con";
  $where="WHERE agg.aggCode = con.aucAggCode";
  $n_pro=0;
?>

<details class=problem open>

<summary>
  Check c1+c2+c3+c5 == 100%:
  <span class=n_pro id=problem_percentage_PE>0</span>
</summary>

<table border=1>
  <tr>
    <th>aggName
    <th title="%PE to sewer">C1
    <th title="%PE to IAS">C2
    <th title="%PE without treatment">C3
    <th title="%C1 to UWWTP">C4
    <th title="%PE C2T">C5
    <th title="C1-C4">C6
    <th title="C1+C2+C3+C5">sum
  </tr>
  <?php
    $sql="SELECT $cols FROM $taula $where";
    $res=$db->query("$sql LIMIT $limit");
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      $c6 = $obj->c1 - $obj->c4;
      $sum = $obj->c1 + $obj->c2 + $obj->c3 + $obj->c5;
      if($sum==100)continue;
      $n_pro++;
      echo "<tr>
        <td>
          <a href='view.php?taula=$taula&idNom=$idNom&idVal=$obj->aggAgglomorationsID'>
            $obj->aggName
          </a>
        <td>$obj->c1
        <td>$obj->c2
        <td>$obj->c3
        <td>$obj->c4
        <td>$obj->c5
        <td>$c6
        <td>$sum
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
    echo "<tr>
      <td colspan=100 class=sql>
        <a href='problem.php?sql=$sql' target=_blank>$sql</a>
      </td>
    </tr>";
    $total_problems += $n_pro;
  ?>
</table>

<script>
  document.querySelector("span#problem_percentage_PE").innerHTML="<?php echo $n_pro?>";
</script>

</details>
