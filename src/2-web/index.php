<?php
  /*sqlite database connection*/
  include 'load_db.php';

  //function to calculate distances between 2 coordinates
  include 'distance.php'; //test: echo distance("40.6","2.0", "40.3","2.1"); //34.41 km

  /*counter for total problems*/
  $total_problems=0;

  //show only the first $limit problems
  $limit=5;

  //create google maps link
  function google_maps_link($lat, $lon){
    return "<a target=_blank href='https://www.google.com/maps/search/$lat,$lon?hl=es&source=opensearch'>$lat, $lon</a>";
  }
?>
<!doctype html><html><head>
  <meta charset="utf-8">
  <title>Waterbase <?php echo $db_version?> problem finder</title>
  <link rel=stylesheet href=index.css>
  <!--
    Vue JS
      production version
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
      development version
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  -->
  <script>
    //delete: via GET
    function delete_item(taula,idNom,idVal){
      if(!taula || !idNom || !idVal) return;
      if(!confirm(`Deleting item ${taula}.${idVal}. Continue?`)) return;
      window.location=`delete.php?taula=${taula}&idNom=${idNom}&idVal=${idVal}`;
    }
  </script>
</head><body>

<!--navbar-->
<nav>
  <a href="https://www.eea.europa.eu/ds_resolveuid/DAT-106-en" target=_blank>eea.europa.eu db official page</a> |
  <a href="https://github.com/icra/waterbase-uwwtd/" target=_blank>github/icra/waterbase-uwwtd</a> |
  <!--<a href="../1-export_mdb_to_sqlite/" target=_blank>explore files</a> |-->
  <a href="phpliteadmin.php" target=_blank>phpLiteAdmin</a> |
  <a href="queries.php" target=_blank>Predefined queries</a> |
</nav><hr>

<!--title-->
<div>
  <h2>Waterbase UWWTD: problem finder platform (READONLY)</h2>
  <!--loading indication-->
  <?php include'loading.php'?>
  <!--curent db version-->
  <form action="setcookie_db_version.php" method="get">
    <b>Current db version: <?php echo $db_version?></b> |
    <span>
      Select db version
      <select name="db_version">
        <?php
          foreach($db_versions as $version){
            $selected = $db_version==$version?"selected":"";
            echo"
              <option value='$version' $selected>$version</option>
            ";
          }
        ?>
      </select>
      <button>go</button>
    </span>
  </form>
  <!--total problems-->
  <div id=total_problems>
    Total problems found:
    <span class=n_pro>wait...</span>
  </div>
</div><hr>

<!--problems-->
<div>
  <!--database file status (readable and writable)-->
  <?php
    if(!is_readable($db_file_path)){
      echo "<li><span style=background:red;color:white>
        Attention: The database-file is not readable, so its content cannot be read to find problems.
      </span></li>";
    }
    if(!is_writable($db_file_path)){
      echo "<li><span style=background:red;color:white>
        Attention: The database-file is not writable, so its content cannot be changed in any way.
      </span></li>";
    }
  ?>

  <!--problem tables-->
  <div id='Table_T_Agglomerations'>
    <details class=table open>
      <summary>T_Agglomerations &mdash; <?php echo $db->querySingle("SELECT COUNT(*) FROM T_Agglomerations")?> rows</summary>
      <div>
        <?php include'problem_T_Agglomerations_aggCode_null.php'?>
        <?php include'problem_T_Agglomerations_duplicated.php'?>
        <?php include'problem_T_Agglomerations_lat_long_null.php'?>
        <?php include'problem_T_Agglomerations_not_in_T_UWWTPAgglos.php'?>
      </div>
    </details>
  </div>

  <div id='Table_T_UWWTPS'>
    <details class=table open>
      <summary>T_UWWTPS &mdash; <?php echo $db->querySingle("SELECT COUNT(*) FROM T_UWWTPS")?> rows</summary>
      <div>
        <?php include'problem_T_UWWTPS_uwwCode_null.php'?>
        <?php include'problem_T_UWWTPS_duplicated.php'?>
        <?php include'problem_T_UWWTPS_lat_long_null.php'?>
        <?php include'problem_T_UWWTPS_not_in_T_UWWTPS_emission_load.php'?>
        <?php include'problem_T_UWWTPS_not_in_T_UWWTPAgglos.php'?>
        <?php include'problem_T_UWWTPS_not_in_T_DischargePoints.php'?>
        <?php include'problem_T_UWWTPS_multiple_in_T_DischargePoints.php'?>
      </div>
    </details>
  </div>

  <div id='Table_T_DischargePoints'>
    <details class=table open>
      <summary>T_DischargePoints &mdash; <?php echo $db->querySingle("SELECT COUNT(*) FROM T_DischargePoints")?> rows</summary>
      <div>
        <?php include'problem_T_DischargePoints_dcpCode_null.php'?>
        <?php include'problem_T_DischargePoints_duplicated.php'?>
        <?php include'problem_T_DischargePoints_lat_long_null.php'?>
        <?php include'problem_T_DischargePoints_not_in_T_UWWTPS.php'?>
      </div>
    </details>
  </div>

  <div id='Table_T_UWWTPS_emission_load'>
    <details class=table open>
      <summary>T_UWWTPS_emission_load &mdash; <?php echo $db->querySingle("SELECT COUNT(*) FROM T_UWWTPS_emission_load")?> rows</summary>
      <div>
        <?php include'problem_T_UWWTPS_emission_load_duplicated.php'?>
        <?php include'problem_T_UWWTPS_emission_load_uwwCode_null.php'?>
        <?php include'problem_T_UWWTPS_emission_load_not_in_T_UWWTPS.php'?>
      </div>
    </details>
  </div>

  <div id='Table_T_UWWTPAgglos'>
    <details class=table open>
      <summary>T_UWWTPAgglos &mdash; <?php echo $db->querySingle("SELECT COUNT(*) FROM T_UWWTPAgglos")?> rows</summary>
      <div>
        <?php include'problem_T_UWWTPAgglos_uwwCode_aggCode_null.php'?>
        <?php include'problem_T_UWWTPAgglos_aggCode_not_in_T_Agglomerations.php'?>
        <?php include'problem_T_UWWTPAgglos_uwwCode_not_in_T_UWWTPS.php'?>
      </div>
    </details>
  </div>

  <div id=Distances>
    <details class=table open>
      <summary>Distances (agg-uww-dcp)</summary>
      <div>
        <?php include'problem_distances_agg_uww.php'?>
        <?php include'problem_distances_uww_dcp.php'?>
      </div>
    </details>
  </div>

  <div id=Percentage_PE>
    <details class=table open>
      <summary>Sum of Percentage PE equal to 100%</summary>
      <div>
        <?php include'problem_percentage_PE.php'?>
      </div>
    </details>
  </div>
</div>

<!--end-->
<script>
  document.querySelector("#total_problems span.n_pro").innerHTML="<?php echo $total_problems ?>";

  //tanca problemes amb 0 problemes
  window.addEventListener('load',function(){
    let spans = document.querySelectorAll("span.n_pro");
    spans.forEach(span=>{
      if(span.innerHTML=="0"){
        span.classList.add("zero");
      }
      let pare = span.parentNode.parentNode;
      if(pare.tagName=="DETAILS" && pare.hasAttribute('open')){
        pare.removeAttribute('open');
      }
    });
  });
</script>
