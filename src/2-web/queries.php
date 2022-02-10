<?php
  /*sqlite database connection*/
  include 'load_db.php';

  //predefined queries
  $queries=array(
    "Agglomerations" =>
    "
      SELECT
        aggCode,
        aggLatitude,
        aggLongitude,
        aggGenerated,
        (coalesce(aggC2,0))*aggGenerated/100 as ias,
        (coalesce(aggPercWithoutTreatment,0))*aggGenerated/100 as medi
      FROM
        T_Agglomerations
      WHERE 1;
    ",

    "Discharge Points"=>
    "
      SELECT
        dcpCode,
        dcpLatitude,
        dcpLongitude,
        SUM((aucPercEnteringUWWTP+coalesce(aucPercC2T,0))*aggGenerated/100) AS habitants_tractats_edar,
        uwwPrimaryTreatment,
        uwwSecondaryTreatment,
        uwwOtherTreatment,
        uwwNRemoval,
        uwwPRemoval,
        uwwUV,
        uwwChlorination,
        uwwOzonation,
        uwwSandFiltration,
        uwwMicroFiltration,
        uwwOther,
        uwwSpecification
      FROM
        T_DischargePoints,
        T_UWWTPAgglos,
        T_Agglomerations,
        T_UWWTPS
      WHERE
        T_DischargePoints.dcpState is 1 AND
        T_UWWTPS.uwwState          is 1 AND
        T_UWWTPS.uwwCode          = T_DischargePoints.uwwCode AND
        T_DischargePoints.uwwCode = T_UWWTPAgglos.aucUwwCode AND
        T_Agglomerations.aggCode  = T_UWWTPAgglos.aucAggCode
      GROUP BY aucUwwCode
      ORDER BY
        aucUwwCode
      ;
    "
  );

  if(isset($_GET["q"])){
    $q   = $_GET["q"];
    $sql = $queries[$q];
    if(!$sql){die("error in q");}

    //print result
    $res=$db->query($sql) or die("error in query");
    $i=0;
    while($row=$res->fetchArray(SQLITE3_ASSOC)){
      $obj=(object)$row; //convert to object

      //print column names
      if($i==0){
        foreach($obj as $key=>$val){
          echo "$key;";
        }
        echo "<br>\n";
      }

      //new row
      //iterate keys
      foreach($obj as $key=>$val){
        echo "$val;";
      }
      echo "<br>\n";
      $i++;
    }

    die();
  }
?>
<!doctype html><html><head>
  <title>Predefined queries</title>
</head><body>
<h1><a href="index.php">Home</a> / Predefined queries (for INVEST project)</h1>

<!--curent db version-->
<?php include'select_db_version.php'?>

<ul>
  <?php
    foreach($queries as $key=>$val){
      echo "<li>
        <a href='queries.php?q=$key'><b>$key</b></a>:
        <code><pre>$val</pre></code>
      </li>";
    }
  ?>
</ul>
