<?php
  //database sqlite connection
  include('load_db.php');

  //show only the first $limit problems
  $limit=5;
?>
<!doctype html><html><head>
  <meta charset="utf-8">
  <title>waterbase problem finder</title>
  <link rel=stylesheet href=index.css>
  <!--
    Vue JS
      production version
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
      development version
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  -->
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

  <script>
    //update: via POST
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
  <a href="https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-6" target=_blank>eea.europa.eu/wwtd</a> |
  <a href="https://github.com/icra/waterbase-uwwtd/" target=_blank>github/icra/waterbase-uwwtd</a> |
  <a href="../1-export_mdb_to_sqlite/" target=_blank>explore files</a> |
  <a href="phpliteadmin.php" target=_blank>phpLiteAdmin</a> |
</nav><hr>

<!--title-->
<h2 class=flex style="justify-content:space-between">
  <div>Waterbase uwwtd <?php echo $db_version ?>: problem finder platform</div>
  <div><small>
    showing first <?php echo $limit?> problems of each type
  </small></div>
</h2><hr>

<!--loading indication-->
<?php include'loading.php'?>

<?php /*php utils*/
  /*counter for total problems*/
  $total_problems = 0;

  //create google maps link
  function google_maps_link($lat, $lon){
    return "<a target=_blank href='https://www.google.com/maps/search/$lat,$lon?hl=es&source=opensearch'>$lat, $lon</a>";
  }

  //calculate distance between coordinates
  include 'distance.php'; //test: echo distance("40.6","2.0", "40.3","2.1"); //34.41 km
?>

<main><!--main is a css grid 30% 70% for summary and problem tables-->

<!--summary of problems detected 'index'-->
<div id=index_container>
  <!--actual index element-->
  <div id=index>
    <!--index view-->
    <div id=index_view>
      <h3>Summary of problems detected</h3>
      <div>
        <!--sections-->
        <ul v-for="problemes,key in index_model">
          <li><a :href="'#'+key">{{key}}</a>
            <ul>
              <!--each problem-->
              <li v-for="problema,i in problemes">
                <div class=problem_link v-on:click="go_to_problem(key,i)">
                  {{i+1}}. {{problema}}
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <!--index model-->
    <script>
      let index_model={
        'Table_T_Agglomerations': [
          "duplicated agglomerations",
          "agglomerations with latitude or longitude NULL",
          "agglomerations not in T_UWWTPAgglos",
        ],
        'Table_T_UWWTPS':[
          "duplicated uwwtps",
          "uwwtps with latitude or longitude NULL",
          "uwwtps not in T_UWWTPS_emission_load",
          "uwwtps not in T_UWWTPAgglos",
          "uwwtps not in T_DischargePoints",
          "uwwtps with multiple entries in T_DischargePoints",
        ],
        'Table_T_DischargePoints':[
          "duplicated discharge points",
          "discharge points with latitude or longitude NULL",
          "discharge points where uwwCode is not in T_UWWTPS",
        ],
        'Table_T_UWWTPS_emission_load':[
          "emissions with uwwCode duplicated",
          "emissions with uwwCode NULL",
          "emissions with uwwCode not in T_UWWTPS",
        ],
        'Table_T_UWWTPAgglos':[
          "connections with uwwCode or aggCode NULL",
          "connections with aggCode not in T_Agglomerations",
          "connections with uwwCode not in T_UWWTPS",
        ],
        Distances:[
          "distance agglomeration -- uwwtp < 30km",
          "distance uwwtp -- discharge point < 30km",
        ],
        Percentage_PE:[
          "check that PE sum is 100%",
        ],
      };
      let index_vue=new Vue({
        el:'#index_view',
        data:{
          index_model: index_model,
          visible:true,
        },
        methods:{
          go_to_problem(key,i){
            document.querySelectorAll(`#${key} li.problem`)[i].scrollIntoView();
          }
        },
      });
    </script>
  </div>
</div>

<!--problems-->
<ul id=problems>
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
  <li class=table id='Table_T_Agglomerations'>
    <h3>
      <a href='#Table_T_Agglomerations'>
        Problems in table T_Agglomerations
      </a>
    </h3>
    <ul>
      <li class=problem>
      <?php include 'problem_T_Agglomerations_duplicated.php'?>
      </li>
      <li class=problem>
      <?php include 'problem_T_Agglomerations_lat_long_null.php'?>
      </li>
      <li class=problem>
      <?php include 'problem_T_Agglomerations_not_in_T_UWWTPAgglos.php'?>
      </li>
    </ul>
  </li>

  <li class=table id='Table_T_UWWTPS'>
    <h3><a href='#Table_T_UWWTPS'>Problems in table T_UWWTPS</a></h3>
    <ul>
      <li class=problem>
      <?php include'problem_T_UWWTPS_duplicated.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_lat_long_null.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_not_in_T_UWWTPS_emission_load.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_not_in_T_UWWTPAgglos.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_not_in_T_DischargePoints.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_multiple_in_T_DischargePoints.php'?>
      </li>
    </ul>
  </li>

  <li class=table id='Table_T_DischargePoints'>
    <h3><a href='#Table_T_DischargePoints'>Problems in table T_DischargePoints</a></h3>
    <ul>
      <li class=problem>
      <?php include'problem_T_DischargePoints_duplicated.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_DischargePoints_lat_long_null.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_DischargePoints_not_in_T_UWWTPAgglos.php'?>
      </li>
    </ul>
  </li>

  <li class=table id='Table_T_UWWTPS_emission_load'>
    <h3><a href='#Table_T_UWWTPS_emission_load'>Problems in table T_UWWTPS_emission_load</a></h3>
    <ul>
      <li class=problem>
      <?php include'problem_T_UWWTPS_emission_load_duplicated.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_emission_load_uwwCode_null.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPS_emission_load_not_in_T_UWWTPS.php'?>
      </li>
    </ul>
  </li>

  <li class=table id='Table_T_UWWTPAgglos'>
    <h3><a href='#Table_T_UWWTPAgglos'>Problems in table T_UWWTPAgglos</a></h3>
    <ul>
      <li class=problem>
      <?php include'problem_T_UWWTPAgglos_uwwCode_aggCode_null.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPAgglos_aggCode_not_in_T_Agglomerations.php'?>
      </li>
      <li class=problem>
      <?php include'problem_T_UWWTPAgglos_uwwCode_not_in_T_UWWTPS.php'?>
      </li>
    </ul>
  </li>

  <li class=table id=Distances>
    <h3><a href='#Distances'>Problems in Distances</a></h3>
    <ul>
      <li class=problem>
      <?php include'problem_distances_agg_uww.php'?>
      </li>
      <li class=problem>
      <?php include'problem_distances_uww_dcp.php'?>
      </li>
    </ul>
  </li>

  <li class=table id=Percentage_PE>
    <h3><a href=#Percentage_PE>Problems in Percentage PE</a></h3>
    <ul>
      <li class=problem>
      <?php include'problem_percentage_PE.php'?>
      </li>
    </ul>
  </li>
</ul>

</main>

<!--total problems-->
<div id=total_problems>
  Total problems found:
  <span class=n_pro><?php echo $total_problems?></span>
</div>
