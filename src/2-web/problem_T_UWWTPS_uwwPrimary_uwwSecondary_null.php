<?php
  $taula="T_UWWTPS";
  $idNom="uwwCode";
  $where="
    uwwPrimaryTreatment   is NULL AND
    uwwSecondaryTreatment is NULL AND
    uwwState is 1
  ";
  $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
  $total_problems+=$n_pro;
?>

<details class=problem open>
<summary>
  Uwwtps where uwwPrimaryTreatment is NULL AND uwwSecondaryTreatment is NULL:
  <span class=n_pro><?php echo $n_pro?></span>
</summary>

<table border=1>
  <?php
    $sql="SELECT * FROM $taula WHERE $where ORDER BY uwwLoadEnteringUWWTP DESC";
    echo "<tr>
      <td colspan=100 class=sql>
        <a href='problem.php?sql=$sql' target=_blank>$sql</a>
      </td>
    </tr>";
  ?>
  <tr>
    <th><?php echo $idNom?>
    <th>uwwName
    <th>rptMStateKey
    <th>coords
    <th>uwwLoadEnteringUWWTP
    <th>modify fields
  </tr>
  <?php
    $res=$db->query($sql);
    $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row;
      echo "<tr>
        <td>".$obj->$idNom."
        <td>
          <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
            $obj->uwwName
          </a>
        </td>
        <td>$obj->rptMStateKey
        <td>".google_maps_link($obj->uwwLatitude,$obj->uwwLongitude)."
        <td>$obj->uwwLoadEnteringUWWTP
        <td>
          <form action='update_primary_treatment.php' method=post>
            <input type=hidden name=uwwCode value='$obj->uwwCode'>
            <button>set Primary Treatment to 1</button>
          </form>
          <form action='update_primary_and_secondary_treatment.php' method=post>
            <input type=hidden name=uwwCode value='$obj->uwwCode'>
            <button>set Primary Treatment AND Secondary Treatment to 1</button>
          </form>
        </td>
      ";
      $i++;
    }
    if($i==1){echo "<tr><td colspan=100 class=blank>";}
  ?>
</table>
</details>
