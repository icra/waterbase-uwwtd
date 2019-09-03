<?php
  $db=new SQLite3('../1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite');

  $limit=5; //mostrar només els n primers problemes de cada
?>
<!doctype html><html><head>
  <title>Problemes waterbase</title>
  <meta charset=utf-8>
  <style>
    table{
      border-collapse:collapse;
    }
    div.problema{
      margin-bottom:10px;
      padding-bottom:10px;
      border-bottom:1px solid #ccc;
    }
  </style>
</head><body>

<!--top navbar-->
<nav>
  <a href="phpliteadmin.php" target=_blank>obrir base de dades sencera</a> |
  <a href="https://github.com/icra/waterbase-uwwtd/" target=_blank>github</a> |
</nav><hr>

<!--titol--><h2>Problemes trobats waterbase (veient <?php echo $limit?> primers problemes de cada tipus)</h2><hr>

<!--PROBLEMES-->
<div class=problema>
  <b>Problema: Aglomeracions amb longitud o latitud NULL</b>
  <?php
    #aglomeracions amb longitud o latitud NULL
    $taula="T_Agglomerations";
    $idNom="aggAgglomorationsID";
    $sql="SELECT * FROM $taula WHERE aggLongitude is NULL OR aggLatitude is NULL";
    $sql="SELECT * FROM $taula";
    $res=$db->query("$sql LIMIT $limit");
  ?>
  <table border=1>
    <tr>
      <th>nº
      <th>aggName
      <th>rptMStateKey
      <th>aggLatitude
      <th>aggLongitude
    </tr>
    <?php
      $i=1;while($row=$res->fetchArray(SQLITE3_ASSOC)){
        $obj = (object)$row; //convert to object
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
              <input             name=nouValor value='$obj->aggLatitude' placeholder='aggLatitude' required>
              <button>Guardar</button>
            </form>
          </td>
          <td>
            <form action=update.php method=post>
              <input type=hidden name=taula    value=$taula>
              <input type=hidden name=idNom    value=$idNom>
              <input type=hidden name=idVal    value='".$obj->$idNom."'>
              <input type=hidden name=camp     value=aggLongitude>
              <input             name=nouValor value='$obj->aggLongitude' placeholder='aggLongitude' required>
              <button>Guardar</button>
            </form>
          </td>
          <td><a target=_blank href='https://www.google.com/maps/search/$obj->aggLatitude,$obj->aggLongitude?hl=es&source=opensearch'>veure a maps</a>
        ";
        $i++;
      }
    ?>
  </table>
</div>

<?php //die()?>

<div class=problema>
  <b>Problema: Depuradores amb longitud o latitud NULL</b>
  <?php
    #depuradores amb longitud o latitud NULL
    $taula="T_UWWTPS";
    $idNom="uwwUWWTPSID";
    $sql="SELECT * FROM $taula WHERE uwwLongitude is NULL OR uwwLatitude is NULL";
    $sql="SELECT * FROM $taula"; //temp
    $res=$db->query("$sql LIMIT $limit");
  ?>
  <table border=1>
    <tr>
      <th>nº
      <th>uwwName
      <th>rptMStateKey
      <th>uwwLatitude
      <th>uwwLongitude
    </tr>
    <?php
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
              <input             name=nouValor value='$obj->uwwLatitude' placeholder='uwwLatitude' required>
              <button>Guardar</button>
            </form>
          </td>
          <td>
            <form action=update.php method=post>
              <input type=hidden name=taula    value=$taula>
              <input type=hidden name=idNom    value=$idNom>
              <input type=hidden name=idVal    value='".$obj->$idNom."'>
              <input type=hidden name=camp     value=uwwLongitude>
              <input             name=nouValor value='$obj->uwwLongitude' placeholder='uwwLongitude' required>
              <button>Guardar</button>
            </form>
          </td>
          <td><a target=_blank href='https://www.google.com/maps/search/$obj->uwwLatitude,$obj->uwwLongitude?hl=es&source=opensearch'>veure a maps</a>
        ";
        $i++;
      }
    ?>
  </table>
</div>

<b>programació en procés (setembre 2019)</b>
<pre>
  # AGLOMERACIONS
    # aglomeracions duplicades
    # aglomeracions amb longitud o latitud NULL
  # DEPURADORES
    # depuradores duplicades
    # depuradores sense latitud o longitud
  # UWWTP EMISSION LOAD
    # emissions amb uwwCode duplicat
    # emissions amb uwwCode NULL
    # emissions amb uwwCode not in depuradores
  # CONNEXIONS AGLOMERACIÓ - DEPURADORA
    # connexions amb uwwCode NULL
    # connexions amb uwwCode not in depuradores
    # connexions amb aggCode NULL
    # connexions amb aggCode not in aglomeracions
    # depuradores sense connexió amb aglomeració
    # aglomeracions sense connexió amb depuradora
  # DISCHARGE POINTS
    # discharge points duplicats
    # discharge points sense latitud o longitud
    # dps sense depuradora
    # dps on depuradora no existeix
    # depuradores sense discharge point
    # depuradores amb més d'un discharge point
</pre>
