<?php
  $db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');

  $limit=5; //mostrar només els n primers problemes de cada
?>
<!doctype html><html><head>
  <title>Problemes waterbase</title>
  <meta charset=utf-8>
  <style>
    hr{
      border:none;
      border-bottom:1px solid #ccc;
    }
    table{
      border-collapse:collapse;
    }
    div.problema{
      margin-bottom:10px;
      padding-bottom:10px;
      border-bottom:1px solid #ccc;
    }
  </style>

  <!--Vue JS-->
  <!--
    - production version
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    - development version
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  -->
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head><body>

<!--top navbar-->
<nav>
  <a href="phpliteadmin.php" target=_blank>obrir base de dades sencera</a> |
  <a href="https://github.com/icra/waterbase-uwwtd/" target=_blank>github</a> |
</nav><hr>

<!--titol-->
<h2>
  Problemes trobats waterbase
  <small>
    (veient <?php echo $limit?> primers problemes de cada tipus)
  </small>
</h2><hr>

<!--index-->
<div id=index>
  <b>Índex</b>
  <ul v-for="problema in problmes">
  </ul>
</div><hr>
<script>
  let index_model = {
    Aglomeracions : [
      "aglomeracions amb longitud o latitud NULL",
    ],
    Depuradores:[
      "depuradores duplicades",
      "depuradores sense latitud o longitud",
    ],
    'UWWTP emission load':[
      "emissions amb uwwCode duplicat",
      "emissions amb uwwCode NULL",
      "emissions amb uwwCode not in depuradores",
    ],
    'Connexions Aglomeració-Depuradora':[
      "connexions amb uwwCode NULL",
      "connexions amb uwwCode not in depuradores",
      "connexions amb aggCode NULL",
      "connexions amb aggCode not in aglomeracions",
      "depuradores sense connexió amb aglomeració",
      "aglomeracions sense connexió amb depuradora",
    ],
    'Discharge points':[
      "discharge points duplicats",
      "discharge points sense latitud o longitud",
      "dps sense depuradora",
      "dps on depuradora no existeix",
      "depuradores sense discharge point",
      "depuradores amb més d'un discharge point",
    ],
  };

  let index_vue = new Vue({
    el:'#index',
    data:{
      index_model: index_model,
    },
    methods:{
    },
  });
</script>

<!--PROBLEMES-->

<div id=aglomeracions>
  <!--aglomeracions amb longitud o latitud NULL-->
  <div class=problema>
    <?php
      #aglomeracions amb longitud o latitud NULL
      $taula="T_Agglomerations";
      $idNom="aggAgglomorationsID";
      $where="aggLongitude is NULL OR aggLatitude is NULL";
      $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
    ?>
    <b>Problema: Aglomeracions amb longitud o latitud NULL (<?php echo $n_pro?>)</b>
    <table border=1>
      <tr>
        <th>nº
        <th>aggName
        <th>rptMStateKey
        <th>aggLatitude
        <th>aggLongitude
      </tr>
      <?php
        $sql="SELECT * FROM $taula WHERE $where";
        $res=$db->query("$sql LIMIT $limit");
        $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
          $obj = (object)$row; //convert row to object
          //var_dump($obj);break;
          echo "<tr>
            <td>$i
            <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj->$idNom."'>$obj->aggName</a>
            <td>$obj->rptMStateKey
            <td>
              <form action=update.php method=post>
                <input type=hidden name=taula    value=$taula>
                <input type=hidden name=idNom    value=$idNom>
                <input type=hidden name=idVal    value='".$obj->$idNom."'>
                <input type=hidden name=camp     value=aggLatitude>
                <input             name=nouValor value='$obj->aggLatitude' placeholder='escriu aggLatitude' required>
                <button>Guardar</button>
              </form>
            </td>
            <td>
              <form action=update.php method=post>
                <input type=hidden name=taula    value=$taula>
                <input type=hidden name=idNom    value=$idNom>
                <input type=hidden name=idVal    value='".$obj->$idNom."'>
                <input type=hidden name=camp     value=aggLongitude>
                <input             name=nouValor value='$obj->aggLongitude' placeholder='escriu aggLongitude' required>
                <button>Guardar</button>
              </form>
            </td>
            <td><a target=_blank href='https://www.google.com/maps/search/$obj->aggLatitude,$obj->aggLongitude?hl=es&source=opensearch'>veure a maps</a>
          ";
          $i++;
        }
        echo "<tr><td colspan=100><code>$sql";
      ?>
    </table>
  </div>
</div>

<div id=depuradores>
  <!--depuradores duplicades-->
  <div class=problema>
    <?php
      #depuradores duplicades
      $taula="T_UWWTPS";
      $idNom="uwwUWWTPSID";
      $where="GROUP BY uwwCode HAVING COUNT(uwwCode)>1";
      $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula $where");
    ?>
    <b>Problema: Depuradores duplicades (<?php echo $n_pro?>)</b>
    <table border=1>
      <tr>
        <th>nº
        <th>uwwUWWTPSID
        <th>uwwName
        <th>rptMStateKey
        <th>eliminar
      </tr>
      <?php
        $sql="SELECT * FROM $taula $where";
        $res=$db->query("$sql LIMIT $limit");
        $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
          $obj = (object)$row; //convert row to object
          //var_dump($obj);break;

          //busca depuradores duplicades
          $res_2=$db->query("SELECT * FROM $taula WHERE uwwCode='$obj->uwwCode'");
          while($row_2=$res_2->fetchArray(SQLITE3_ASSOC)){
            $obj_2 = (object)$row_2; //convert row to object
            echo "<tr>
              <td>$i
              <td>".$obj_2->$idNom."
              <td><a target=_blank href='view.php?taula=$taula&idNom=$idNom&idVal=".$obj_2->$idNom."'>$obj_2->uwwName</a>
              <td>$obj_2->rptMStateKey
              <td><button>eliminar</button>
            ";
          }


          $i++;
        }
        echo "<tr><td colspan=100><code>$sql";
      ?>
    </table>
  </div>
</div>

<!--depuradores amb longitud o latitud NULL-->
<div class=problema>
  <?php
    #depuradores amb longitud o latitud NULL
    $taula="T_UWWTPS";
    $idNom="uwwUWWTPSID";
    $where="uwwLongitude is NULL OR uwwLatitude is NULL";
    $n_pro=$db->querySingle("SELECT COUNT(*) FROM $taula WHERE $where");
  ?>
  <b>Problema: Depuradores amb longitud o latitud NULL (<?php echo $n_pro?>)</b>
  <table border=1>
    <tr>
      <th>nº
      <th>uwwName
      <th>rptMStateKey
      <th>uwwLatitude
      <th>uwwLongitude
      <th><th>Coord aglomeració aggCode
      <th>Coord discharge point
    </tr>
    <?php
      $sql="SELECT * FROM $taula WHERE $where";
      $res=$db->query("$sql LIMIT $limit");
      $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
        $obj = (object)$row; //convert to object

        $coord_lat = $db->querySingle("SELECT aggLatitude  FROM T_Agglomerations WHERE aggCode='$obj->aggCode'");
        $coord_lon = $db->querySingle("SELECT aggLongitude FROM T_Agglomerations WHERE aggCode='$obj->aggCode'");

        $coord_lat_dp = $db->querySingle("SELECT dcpLatitude  FROM T_DischargePoints WHERE uwwCode='$obj->uwwCode'");
        $coord_lon_dp = $db->querySingle("SELECT dcpLongitude FROM T_DischargePoints WHERE uwwCode='$obj->uwwCode'");

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
              <input             name=nouValor value='$obj->uwwLatitude' placeholder='escriu uwwLatitude' required>
              <button>Guardar</button>
            </form>
          </td>
          <td>
            <form action=update.php method=post>
              <input type=hidden name=taula    value=$taula>
              <input type=hidden name=idNom    value=$idNom>
              <input type=hidden name=idVal    value='".$obj->$idNom."'>
              <input type=hidden name=camp     value=uwwLongitude>
              <input             name=nouValor value='$obj->uwwLongitude' placeholder='escriu uwwLongitude' required>
              <button>Guardar</button>
            </form>
          </td>
          <td><a target=_blank href='https://www.google.com/maps/search/$obj->uwwLatitude,$obj->uwwLongitude?hl=es&source=opensearch'>veure a maps</a>
          <td>$coord_lat, $coord_lon
          <td>$coord_lat_dp, $coord_lon_dp
        ";
        $i++;
      }
      echo "<tr><td colspan=100><code>$sql";
    ?>
  </table>
</div>


<?php //die()?>
