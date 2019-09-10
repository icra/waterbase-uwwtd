<?php
  $db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');
  $limit=5; //mostrar només els n primers problemes de cada
?>
<!doctype html><html><head><title>Problems waterbase</title>
  <meta charset="utf-8">
  <link rel=stylesheet href=index.css>

  <!--Vue JS-->
  <!--
    production version
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    development version
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  -->
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

  <script>
    function delete_item(taula,idNom,idVal){
      if(!taula || !idNom || !idVal) return;
      if(!confirm(`Deleting item ${taula}.${idVal}. Continue?`)) return;
      window.location=`delete.php?taula=${taula}&idNom=${idNom}&idVal=${idVal}`;
    }
  </script>
</head><body>

<?php /*php utils*/
  //create google maps link
  function google_maps_link($lat, $lon){
    return "<a target=_blank href='https://www.google.com/maps/search/$lat,$lon?hl=es&source=opensearch'>$lat, $lon</a>";
  }

  /*counter for total problems*/
  $total_problems = 0;
?>

<!--top links--><nav>
  <a href="https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5" target=_blank>eea.europa.eu/wwtd5</a> |
  <a href="https://github.com/icra/waterbase-uwwtd/" target=_blank>github</a> |
  <a href="phpliteadmin.php" target=_blank>database (phpLiteAdmin)</a> |
</nav><hr>

<!--titol-->
<h2>
  Problems found waterbase
  <small>
    (seeing first <?php echo $limit?> problems of each type)
  </small>
</h2><hr>

<!--main-->
<main>

<!--index-->
<div>
  <div id=index_view>
    <h3>Summary of problems</h3>
    <div>
      <!--sections-->
      <ul v-for="problemes,key in index_model">
        <li><a :href="'#'+key">{{key}}</a>
          <ul>
            <!--each problem-->
            <li v-for="problema in problemes">{{problema}}
          </ul>
        </li>
      </ul>
    </div>
  </div>

  <script>
    let index_model = {
      T_Agglomerations : [
        "duplicated agglomerations",
        "agglomerations with latitude or longitude NULL",
      ],
      T_UWWTPS:[
        "duplicated wwtps",
        "wwtps with latitude or longitude NULL",
      ],
      T_DischargePoints:[
        "duplicated discharge points (by dcpCode)",
        "discharge points with latitude or longitude NULL",
        "discharge points without uwwCode",
        "discharge points where uwwCode is not in T_UWWTPS",
        "wwtps without       discharge point",
        "wwtps with multiple discharge points",
      ],
      T_UWWTPS_emission_load:[
        "emissions with uwwCode duplicated",
        "emissions with uwwCode NULL",
        "emissions with uwwCode not in T_UWWTPS",
      ],
      T_UWWTPAgglos:[
        "connections with uwwCode NULL",
        "connections with uwwCode not in T_UWWTPS",
        "connections with aggCode NULL",
        "connections with aggCode not in T_Agglomerations",
        "wwtps          without connection to any agglomeration",
        "agglomerations without connection to any wwtp",
      ],
    };
    let index_vue = new Vue({
      el:'#index_view',
      data:{
        index_model: index_model,
        visible:true,
      },
    });
  </script>
</div>

<!--problems-->
<ul id=problems>
  <li class=table id=T_Agglomerations><h3>Problems in T_Agglomerations</h3>
    <ul>
      <!--aglomeracions duplicades-->
      <li class=problem>
        <?php
          #aglomeracions duplicades
          $taula="T_Agglomerations";
          $idNom="aggAgglomorationsID";
          $where="GROUP BY aggCode HAVING COUNT(aggCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
        ?>
        <b>duplicated agglomerations (<?php echo $n_pro?>)</b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>aggName
            <th>rptMStateKey
            <th>aggCode
            <th>delete
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj = (object)$row; //convert row to object

              //busca agglomerations duplicades
              $res_2=$db->query("SELECT * FROM $taula WHERE aggCode='$obj->aggCode'");
              while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
                $obj_2 = (object)$row_2; //convert row to object
                echo "<tr>
                  <td>$i
                  <td>".$obj_2->$idNom."
                  <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>$obj_2->aggName</a>
                  <td>$obj_2->rptMStateKey
                  <td>$obj_2->aggCode
                  <td><button onclick=delete_item('$taula','$idNom','".$obj_2->$idNom."')>delete</button>
                ";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>~no problems found";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>

      <!--agglomerations longitud latitud NULL-->
      <li class=problem>
        <?php
          #agglomerations amb longitud o latitud NULL
          $taula="T_Agglomerations";
          $idNom="aggAgglomorationsID";
          $where="aggLongitude is NULL OR aggLatitude is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
          $total_problems += $n_pro;
        ?>
        <b>agglomerations with latitude or longitude NULL (<?php echo $n_pro?>)</b>
        <table border=1>
          <tr>
            <th># <th>id <th>aggName <th>rptMStateKey <th>aggLatitude <th>aggLongitude
            <th>found coords in T_UWWTPS<br><small>where uww.aggCode==agg.aggCode</small>
          </tr>
          <?php
            $sql="SELECT * FROM $taula WHERE $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row;
              echo "<tr>
                <td>$i
                <td>$obj->aggAgglomorationsID
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->aggName</a>
                <td>$obj->rptMStateKey
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=aggLatitude>
                    <input             name=nouValor value='$obj->aggLatitude' placeholder='enter aggLatitude' required autocomplete=off>
                    <button>Guardar</button>
                  </form>
                </td>
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=aggLongitude>
                    <input             name=nouValor value='$obj->aggLongitude' placeholder='enter aggLongitude' required autocomplete=off>
                    <button>Guardar</button>
                  </form>
                </td>
              ";

              //busca depuradores relacionades
              echo "<td>";
              $ress=$db->query("SELECT * FROM T_UWWTPS WHERE aggCode='$obj->aggCode'");
              while($roww=$ress->fetchArray(SQLITE3_ASSOC)){
                $objj=(object)$roww;
                echo "<div>
                  ".google_maps_link($objj->uwwLatitude,$objj->uwwLongitude)."
                </div>";
              }
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>~no problems found";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>

  <li class=table id=T_UWWTPS><h3>Problems in T_UWWTPS</h3>
    <ul>
      <!--depuradores duplicades-->
      <li class=problem>
        <?php
          #depuradores duplicades
          $taula="T_UWWTPS";
          $idNom="uwwUWWTPSID";
          $where="GROUP BY uwwCode HAVING COUNT(uwwCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
        ?>
        <b>duplicated wwtps (<?php echo $n_pro?>)</b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>uwwName
            <th>rptMStateKey
            <th>uwwCode
            <th>delete
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj = (object)$row; //convert row to object

              //busca depuradores duplicades
              $res_2=$db->query("SELECT * FROM $taula WHERE uwwCode='$obj->uwwCode'");
              while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
                $obj_2 = (object)$row_2; //convert row to object
                echo "<tr>
                  <td>$i
                  <td>".$obj_2->$idNom."
                  <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>$obj_2->uwwName</a>
                  <td>$obj_2->rptMStateKey
                  <td>$obj_2->uwwCode
                  <td><button onclick=delete_item('$taula','$idNom','".$obj_2->$idNom."')>delete</button>
                ";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>~no problems found";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>

      <!--depuradores amb longitud o latitud NULL-->
      <li class=problem>
        <?php
          #depuradores amb longitud o latitud NULL
          $taula="T_UWWTPS";
          $idNom="uwwUWWTPSID";
          $where="uwwLongitude is NULL OR uwwLatitude is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
          $total_problems += $n_pro;
        ?>
        <b>wwtps with latitude or longitude NULL (<?php echo $n_pro?>)</b>
        <table border=1>
          <tr>
            <th>#
            <th>uwwName
            <th>rptMStateKey
            <th>uwwLatitude
            <th>uwwLongitude
            <th>found coords in T_Agglomerations  <br><small>where agg.aggCode==uww.aggCode</small>
            <th>found coords in T_DischargePoints <br><small>where dcp.uwwCode==uww.uwwCode</small>
          </tr>
          <?php
            $sql="SELECT * FROM $taula WHERE $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj = (object)$row; //convert to object

              //var_dump($obj);break;
              echo "<tr>
                <td>$i
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->uwwName</a>
                <td>$obj->rptMStateKey
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=uwwLatitude>
                    <input             name=nouValor value='$obj->uwwLatitude' placeholder='enter uwwLatitude' required>
                    <button>Guardar</button>
                  </form>
                </td>
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=uwwLongitude>
                    <input             name=nouValor value='$obj->uwwLongitude' placeholder='enter uwwLongitude' required>
                    <button>Guardar</button>
                  </form>
                </td>
              ";

              $res_coords_agg = $db->query("SELECT aggName,aggLatitude,aggLongitude FROM T_Agglomerations  WHERE aggCode='$obj->aggCode'");
              $res_coords_dcp = $db->query("SELECT dcpName,dcpLatitude,dcpLongitude FROM T_DischargePoints WHERE uwwCode='$obj->uwwCode'");

              echo "<td>";
              while($roww=$res_coords_agg->fetchArray(SQLITE3_ASSOC)){
                $objj=(object)$roww;
                echo "<div>".google_maps_link($objj->aggLatitude,$objj->aggLongitude)." ($objj->aggName)</div>";
              }
              echo "<td>";
              while($roww=$res_coords_dcp->fetchArray(SQLITE3_ASSOC)){
                $objj=(object)$roww;
                echo "<div>".google_maps_link($objj->dcpLatitude,$objj->dcpLongitude)." ($objj->dcpName)</div>";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>~no problems found";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>

  <li class=table id=T_DischargePoints><h3>Problems in T_DischargePoints</h3>
    <ul>
      <!--dps duplicats-->
      <li class=problem>
        <?php
          #dps duplicats
          $taula="T_DischargePoints";
          $idNom="dcpDischargePointsID";
          $where="GROUP BY dcpCode HAVING COUNT(dcpCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
        ?>
        <b>duplicated discharge points (<?php echo $n_pro?>)</b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>dcpName
            <th>rptMStateKey
            <th>dcpCode
            <th>coords
            <th>delete
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj = (object)$row; //convert row to object

              //busca dps duplicats
              $res_2=$db->query("SELECT * FROM $taula WHERE dcpCode='$obj->dcpCode'");
              while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
                $obj_2 = (object)$row_2; //convert row to object
                echo "<tr>
                  <td>$i
                  <td>".$obj_2->$idNom."
                  <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>$obj_2->dcpName</a>
                  <td>$obj_2->rptMStateKey
                  <td>$obj_2->dcpCode
                  <td>".google_maps_link($obj_2->dcpLatitude, $obj_2->dcpLongitude)."
                  <td><button onclick=delete_item('$taula','$idNom','".$obj_2->$idNom."')>delete</button>
                ";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>~no problems found";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>

  <li class=table id=T_UWWTPS_emission_load><h3>Problems in T_UWWTPS_emission_load</h3>
    <ul>
      <li class=problem>
        TODO
      </li>
    </ul>
  </li>

  <li class=table id=T_UWWTPAgglos><h3>Problems in T_UWWTPAgglos</h3>
    <ul>
      <li class=problem>
        TODO
      </li>
    </ul>
  </li>
</ul>

</main>

<!--total problems-->
<div style="background:orange;">
  Total problems found: <?php echo $total_problems ?>
</div>
