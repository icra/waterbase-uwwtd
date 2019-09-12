<?php
  $db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');
  $limit=5; //mostrar només els n primers problemes de cada
?>
<!doctype html><html><head>
  <title>Problem finder waterbase</title>
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
  <a href="https://github.com/icra/waterbase-uwwtd/" target=_blank>github/icra/wwtd5</a> |
  <a href="phpliteadmin.php" target=_blank>database (phpLiteAdmin)</a> |
</nav><hr>

<!--title-->
<h2 class=flex style="justify-content:space-between">
  <div> Problem finder waterbase </div>
  <div><small>
    (note: showing only the first <?php echo $limit?> problems found of each type)
  </small></div>
</h2><hr>

<main>

<!--index-->
<div id=index_container>
  <!--actual index element-->
  <div id=index>
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

    <!--index model-->
    <script>
      let index_model={
        T_Agglomerations : [
          "duplicated agglomerations",
          "agglomerations with latitude or longitude NULL",
          "agglomerations not appearing in T_UWWTPAgglos",
        ],
        T_UWWTPS:[
          "duplicated uwwtps (by uwwCode)",
          "uwwtps with latitude or longitude NULL",
          "uwwtps without       discharge point",
          "uwwtps with multiple discharge points",
          "uwwtps          without connection to any agglomeration",
        ],
        T_DischargePoints:[
          "duplicated discharge points (by dcpCode)",
          "discharge points with latitude or longitude NULL",
          "discharge points where uwwCode is not in T_UWWTPS",
        ],
        T_UWWTPS_emission_load:[
          "emissions with uwwCode duplicated",
          "emissions with uwwCode NULL",
          "emissions with uwwCode not in T_UWWTPS",
        ],
        T_UWWTPAgglos:[
          "connections with uwwCode NULL or aggCode NULL",
          "connections with uwwCode not in T_UWWTPS",
          "connections with aggCode not in T_Agglomerations",
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
</div>

<!--problems-->
<ul id=problems>
  <li class=table id=T_Agglomerations>
    <h3><a href=#T_Agglomerations>Problems in T_Agglomerations</a></h3>
    <ul>
      <li class=problem>
        <?php #aglomeracions duplicades
          $taula="T_Agglomerations";
          $idNom="aggAgglomorationsID";
          $where="GROUP BY aggCode HAVING COUNT(aggCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
        ?>
        <b>duplicated agglomerations: <span class=n_pro><?php echo $n_pro?></span></b>
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
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php #agglomerations amb longitud o latitud NULL
          $taula="T_Agglomerations";
          $idNom="aggAgglomorationsID";
          $where="aggLongitude is NULL OR aggLatitude is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
          $total_problems += $n_pro;
        ?>
        <b>agglomerations with latitude or longitude NULL: <span class=n_pro><?php echo $n_pro?></span></b>
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
                    <button>Save</button>
                  </form>
                </td>
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=aggLongitude>
                    <input             name=nouValor value='$obj->aggLongitude' placeholder='enter aggLongitude' required autocomplete=off>
                    <button>Save</button>
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
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php #agglomerations not in T_UWWTPAgglos
          $taula="T_Agglomerations";
          $idNom="aggAgglomorationsID";
          $where="WHERE aggCode NOT IN (SELECT aucAggCode FROM T_UWWTPAgglos)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems += $n_pro;
        ?>
        <b>agglomerations not in T_UWWTPAgglos: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>aggName
            <th>rptMStateKey
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row;
              echo "<tr>
                <td>$i
                <td>$obj->aggAgglomorationsID
                <td>
                  <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
                    $obj->aggName
                  </a>
                <td>$obj->rptMStateKey
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>

  <li class=table id=T_UWWTPS>
    <h3><a href=#T_UWWTPS>Problems in T_UWWTPS</a></h3>
    <ul>
      <li class=problem>
        <?php #depuradores duplicades
          $taula="T_UWWTPS";
          $idNom="uwwUWWTPSID";
          $where="GROUP BY uwwCode HAVING COUNT(uwwCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
        ?>
        <b>duplicated uwwtps: <span class=n_pro><?php echo $n_pro?></span></b>
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
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php #wwpts with longitude or latitude NULL
          $taula="T_UWWTPS";
          $idNom="uwwUWWTPSID";
          $where="WHERE uwwLongitude is NULL OR uwwLatitude is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems += $n_pro;
        ?>
        <b>uwwtps with latitude or longitude NULL: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>uwwName
            <th>rptMStateKey
            <th>uwwLatitude
            <th>uwwLongitude
            <th>found coords in T_Agglomerations  <br><small>where agg.aggCode==uww.aggCode</small>
            <th>found coords in T_DischargePoints <br><small>where dcp.uwwCode==uww.uwwCode</small>
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj = (object)$row; //convert to object

              //var_dump($obj);break;
              echo "<tr>
                <td>$i
                <td>".$obj->$idNom."
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->uwwName</a>
                <td>$obj->rptMStateKey
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=uwwLatitude>
                    <input             name=nouValor value='$obj->uwwLatitude' placeholder='enter uwwLatitude' required autocomplete=off>
                    <button>Save</button>
                  </form>
                </td>
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=uwwLongitude>
                    <input             name=nouValor value='$obj->uwwLongitude' placeholder='enter uwwLongitude' required autocomplete=off>
                    <button>Save</button>
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
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php //uwwtps not in T_DischargePoints
          $taula="T_UWWTPS";
          $idNom="uwwUWWTPSID";
          $where="WHERE uwwCode NOT IN (SELECT uwwCode FROM T_DischargePoints)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems+=$n_pro;
        ?>
        <b>uwwtps not in T_DischargePoints: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>uwwName
            <th>rptMStateKey
            <th>uwwCode
            <th>coords
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
                <td>".$obj->$idNom."
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->uwwName</a>
                <td>$obj->rptMStateKey
                <td>$obj->uwwCode
                <td>".google_maps_link($obj->uwwLatitude,$obj->uwwLongitude)."
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php //uwwtps with multiple discharge points
          $taula="T_UWWTPS";
          $where="WHERE uwwCode IN (SELECT uwwCode FROM T_DischargePoints GROUP BY uwwCode HAVING COUNT(uwwCode)>1)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems+=$n_pro;
          $idNom="uwwUWWTPSID";
        ?>
        <b>uwwtps with multiple dcps in T_DischargePoints: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>uwwName
            <th>rptMStateKey
            <th>uwwCode
            <th>coords
            <th>dcps found in T_DischargePoints
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
                <td>$obj->uwwUWWTPSID
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->uwwName</a>
                <td>$obj->rptMStateKey
                <td>$obj->uwwCode
                <td>".google_maps_link($obj->uwwLatitude,$obj->uwwLongitude)."
              ";

              echo "<td>";
              $ress=$db->query("SELECT * FROM T_DischargePoints WHERE uwwCode='$obj->uwwCode'");
              while($roww=$ress->fetchArray(SQLITE3_ASSOC)){
                $objj=(object)$roww;
                echo "<div>
                  <a href='view.php?taula=T_DischargePoints&idNom=dcpDischargePointsID&idVal=$objj->dcpDischargePointsID' target=_blank>$objj->dcpName</a>
                  (".google_maps_link($objj->dcpLatitude,$objj->dcpLongitude).")
                </div>";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php //uwwtps not in T_UWWTPAgglos
          $taula="T_UWWTPS";
          $where="WHERE uwwCode NOT IN (SELECT aucUwwCode FROM T_UWWTPAgglos)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems+=$n_pro;
          $idNom="uwwUWWTPSID";
        ?>
        <b>uwwtps not in T_UWWTPAgglos: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>uwwName
            <th>rptMStateKey
            <th>uwwCode
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->uwwName</a>
                <td>$obj->rptMStateKey
                <td>$obj->uwwCode
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>

  <li class=table id=T_DischargePoints>
    <h3><a href=#T_DischargePoints>Problems in T_DischargePoints</a></h3>
    <ul>
      <li class=problem>
        <?php //dcps duplicats
          $taula="T_DischargePoints";
          $idNom="dcpDischargePointsID";
          $where="GROUP BY dcpCode HAVING COUNT(dcpCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
        ?>
        <b>duplicated discharge points: <span class=n_pro><?php echo $n_pro?></span></b>
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
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php //discharge points with latitude or longitude NULL
          $taula="T_DischargePoints";
          $idNom="dcpDischargePointsID";
          $where="WHERE dcpLatitude is NULL OR dcpLongitude is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems += $n_pro;
        ?>
        <b>discharge points with latitude or longitude NULL: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>dcpName
            <th>rptMStateKey
            <th>dcpLatitude
            <th>dcpLongitude
            <th>found coords in T_UWWTPS <br><small>where dcp.uwwCode==uww.uwwCode</small>
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj = (object)$row; //convert to object

              //var_dump($obj);break;
              echo "<tr>
                <td>$i
                <td>".$obj->$idNom."
                <td>
                  <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
                    $obj->dcpName
                  </a>
                </td>
                <td>$obj->rptMStateKey
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=dcpLatitude>
                    <input             name=nouValor value='$obj->dcpLatitude' placeholder='enter dcpLatitude' required autocomplete=off>
                    <button>Save</button>
                  </form>
                </td>
                <td>
                  <form action=update.php method=post>
                    <input type=hidden name=taula    value=$taula>
                    <input type=hidden name=idNom    value=$idNom>
                    <input type=hidden name=idVal    value='".$obj->$idNom."'>
                    <input type=hidden name=camp     value=dcpLongitude>
                    <input             name=nouValor value='$obj->dcpLongitude' placeholder='enter dcpLongitude' required autocomplete=off>
                    <button>Save</button>
                  </form>
                </td>
              ";

              $res_coords_uww = $db->query("SELECT uwwName,uwwLatitude,uwwLongitude FROM T_UWWTPS WHERE uwwCode='$obj->uwwCode'");

              echo "<td>";
              while($roww=$res_coords_uww->fetchArray(SQLITE3_ASSOC)){
                $objj=(object)$roww;
                echo "<div>".google_maps_link($objj->uwwLatitude,$objj->uwwLongitude)." ($objj->uwwName)</div>";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php //discharge points where uwwCode is not in T_UWWTPS
          $taula="T_DischargePoints";
          $idNom="dcpDischargePointsID";
          $where="WHERE uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems += $n_pro;
        ?>
        <b>discharge points where uwwCode is not in T_UWWTPS: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>dcpName
            <th>rptMStateKey
            <th>uwwCode
            <th>coords
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
                <td>".$obj->$idNom."
                <td>
                  <a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>
                    $obj->dcpName
                  </a>
                </td>
                <td>$obj->rptMStateKey
                <td>$obj->uwwCode
                <td>".google_maps_link($obj->dcpLatitude,$obj->dcpLongitude)."
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>

  <li class=table id=T_UWWTPS_emission_load>
    <h3><a href=#T_UWWTPS_emission_load>Problems in T_UWWTPS_emission_load</a></h3>
    <ul>
      <li class=problem>
        <?php //duplicate emissions 
          $taula="T_UWWTPS_emission_load";
          $where="GROUP BY uwwCode HAVING COUNT(uwwCode)>1";
          $n_pro=$db->querySingle("SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM $taula $where)");
          $total_problems += $n_pro;
          $idNom="uwwCode";
        ?>
        <b>emissions with uwwCode duplicated: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>uwwCode
            <th>uwwName
            <th>rptMStateKey
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert row to object

              //busca emission loads duplicats
              $res_2=$db->query("SELECT * FROM $taula WHERE uwwCode='$obj->uwwCode'");
              while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
                $obj_2 = (object)$row_2; //convert row to object
                echo "<tr>
                  <td>$i
                  <td>$obj_2->uwwCode
                  <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>$obj_2->uwwName</a>
                  <td>$obj_2->rptMStateKey
                  <td><button onclick=delete_item('$taula','$idNom','".$obj_2->$idNom."')>delete</button>
                ";
              }

              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
      <li class=problem>
        <?php //emissions with uwwCode NULL
          $taula="T_UWWTPS_emission_load";
          $idNom="uwwCode";
          $where="WHERE uwwCode is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems += $n_pro;
        ?>
        <b>emissions with uwwCode NULL: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
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
                <td>$i
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
      </li>
      <li class=problem>
        <?php //emissions with uwwCode not in T_UWWTPS
          $taula="T_UWWTPS_emission_load";
          $idNom="uwwCode";
          $where="WHERE uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems+=$n_pro;
        ?>
        <b>emissions with uwwCode not in T_UWWTPS: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>uwwCode
            <th>uwwName
            <th>rptMStateKey
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
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
      </li>
    </ul>
  </li>

  <li class=table id=T_UWWTPAgglos>
    <h3><a href=#T_UWWTPAgglos>Problems in T_UWWTPAgglos</a></h3>
    <ul>
      <li class=problem>
        <?php //connections where uwwCode NULL or aggCode NULL
          $taula="T_UWWTPAgglos";
          $idNom="aucUWWTP_AggloID";
          $where="WHERE aucUwwCode is NULL or aucAggCode is NULL";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems += $n_pro;
        ?>
        <b>connections with uwwCode or aggCode NULL: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>aucUwwName
            <th>aucAggName
            <th>rptMStateKey
            <th>aucUwwCode
            <th>aucAggCode
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row;
              echo "<tr>
                <td>$i
                <td>$obj->aucUWWTP_AggloID
                <td>$obj->aucUwwName
                <td>$obj->aucAggName
                <td>$obj->rptMStateKey
                <td>form 1
                <td>form 2
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>

      <li class=problem>
        <?php //connections with uwwCode not in T_UWWTPS
          $taula="T_UWWTPAgglos";
          $idNom="aucUWWTP_AggloID";
          $where="WHERE aucUwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems+=$n_pro;
        ?>
        <b>connections with aucUwwCode not in T_UWWTPS: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>aucUwwCode
            <th>aucUwwName
            <th>rptMStateKey
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
                <td>$obj->aucUWWTP_AggloID
                <td>$obj->aucUwwCode
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->aucUwwName</a>
                <td>$obj->rptMStateKey
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>

      <li class=problem>
        <?php //connections with aggCode not in T_Agglomerations
          $taula="T_UWWTPAgglos";
          $idNom="aucUWWTP_AggloID";
          $where="WHERE aucAggCode NOT IN (SELECT aggCode FROM T_Agglomerations)";
          $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
          $total_problems+=$n_pro;
        ?>
        <b>connections with aucAggCode not in T_Agglomerations: <span class=n_pro><?php echo $n_pro?></span></b>
        <table border=1>
          <tr>
            <th>#
            <th>id
            <th>aucAggCode
            <th>aucAggName
            <th>rptMStateKey
          </tr>
          <?php
            $sql="SELECT * FROM $taula $where";
            $res=$db->query("$sql LIMIT $limit");
            $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
              $obj=(object)$row; //convert to object
              echo "<tr>
                <td>$i
                <td>$obj->aucUWWTP_AggloID
                <td>$obj->aucAggCode
                <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->aucAggName</a>
                <td>$obj->rptMStateKey
              ";
              $i++;
            }
            if($i==1){echo "<tr><td colspan=100 class=blank>";}
            echo "<tr><td colspan=100 class=sql>$sql";
          ?>
        </table>
      </li>
    </ul>
  </li>
</ul>

</main>

<!--total problems-->
<div style="background:orange">
  Total problems found: <?php echo $total_problems ?>
</div>
