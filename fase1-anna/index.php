<?php

  $conn = new mysqli("localhost", "root", "", "prova");
  if(!$conn){ die('No connection: ' . mysqli_connect_error()); }

  if (isset($_POST["import"])) {
      // DESCOMENTAR PER CONTROLAR QUE ES PUGIN TOTS ELS FITXERS I NO NOMÉS UN
      //if ($_FILES["uwwtps"]["size"] == 0 || $_FILES["agglo"]["size"] == 0 || $_FILES["dschrg"]["size"] == 0 || $_FILES["emission"]["size"] == 0 || $_FILES["uwwtpsA"]["size"] == 0) {
      //    $type = "error";
      //    $message = "Select all files";
      //}
      //else {

          // Assignació de taules auxiliars per cada fitxer
          $_FILES["uwwtps"]["table"] = 'aux_T_UWWTPS';
          $_FILES["agglo"]["table"] = 'aux_T_Agglomerations';
          $_FILES["dschrg"]["table"] = 'aux_T_DischargePoints';
          $_FILES["emission"]["table"] = 'aux_T_UWWTPS_emission_load';
          $_FILES["uwwtpsA"]["table"] = 'aux_T_UWWTPAgglos';

          // Arrays d'errors i warnings que es mostren al final
          $errors = [];
          $warnings = [];
          $result;

          // Array dels fitxers pujats
          $aux = [$_FILES["uwwtps"], $_FILES["agglo"], $_FILES["dschrg"], $_FILES["emission"], $_FILES["uwwtpsA"]];

          // Per comprovar si hi ha algun error amb la pujada del fitxer .csv
          /*if(array_key_exists('uwwtps', $_FILES)){
              if ($_FILES['uwwtps']['error'] === UPLOAD_ERR_OK) {
                echo 'upload was successful';
              } else {
                die("Upload failed with error code " . $_FILES['uwwtps']['error']);
              }
          }*/

          // Per cada fitxer, pujar-lo a la base de dades a la taula auxiliar
          for ($i = 0; $i < count($aux); $i++) {

              $fileName = $aux[$i]["tmp_name"];
              $tableName = $aux[$i]["table"];
      
              if ($aux[$i]["size"] > 0) {
                  
                  $file = fopen($fileName, "r");                                
                  
                  $flag = true;
                  while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
                      if($flag) { $flag = false; continue; }

                      $row = parseTable($tableName, $row);

                      if ($tableName == 'aux_T_UWWTPS') {

                          // Si és string buit, desar error
                          if (is_string($row[0]) && $row[0] == '') {
                              array_push($errors, "uwwState empty. File: " . $aux[$i]["name"] . ". aggCode: " . $row[2]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if (is_string($row[1]) && $row[1] == '') {
                              array_push($errors, "rptMStateKey empty. File: " . $aux[$i]["name"] . ". aggCode: " . $row[2]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if (is_string($row[1]) && $row[3] == '') {
                              array_push($errors, "uwwCode empty. File: " . $aux[$i]["name"] . ". aggCode: " . $row[2]);
                              $result = true;
                          }
                          // Si és string buit, desar warning
                          else if (is_string($row[2]) && $row[2] == '') {
                              array_push($warnings, "aggCode empty. File: " . $aux[$i]["name"] . ". uwwCode: " . $row[3]);

                              // Pujar igualment la fila
                              $sqlInsert = "INSERT INTO `".$tableName."` (uwwCode, uwwState, rptMStateKey, aggCode, uwwName, uwwCollectingSystem, uwwDateClosing, uwwLatitude, uwwLongitude, uwwLoadEnteringUWWTP, uwwCapacity, uwwPrimaryTreatment, uwwSecondaryTreatment, uwwOtherTreatment, uwwNRemoval, uwwPRemoval, uwwUV, uwwChlorination, uwwOzonation, uwwSandFiltration, uwwMicroFiltration, uwwOther, uwwBOD5Perf, uwwCODPerf, uwwTSSPerf, uwwNTotPerf, uwwPTotPerf, uwwOtherPerf, uwwEndLife)
                              VALUES ('" . $row[3] . "','" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "','" . $row[8] . "','" . $row[9] . "','" . $row[11] . "','" . $row[12] . "','" . $row[13] . "','" . $row[14] . "','" . $row[15] . "','" . $row[16] . "','" . $row[17] . "','" . $row[18] . "','" . $row[19] . "','" . $row[20] . "','" . $row[21] . "','" . $row[22] . "','" . $row[23] . "','" . $row[25] . "','" . $row[26] . "','" . $row[27] . "','" . $row[28] . "','" . $row[29] . "','" . $row[30] . "','" . $row[32] . "')";
                          
                              $result = mysqli_query($conn, $sqlInsert);
                              var_dump($result);
                          }
                          else { // Cap error ni warning, pujar la fila
                              $sqlInsert = "INSERT INTO `".$tableName."` (uwwCode, uwwState, rptMStateKey, aggCode, uwwName, uwwCollectingSystem, uwwDateClosing, uwwLatitude, uwwLongitude, uwwLoadEnteringUWWTP, uwwCapacity, uwwPrimaryTreatment, uwwSecondaryTreatment, uwwOtherTreatment, uwwNRemoval, uwwPRemoval, uwwUV, uwwChlorination, uwwOzonation, uwwSandFiltration, uwwMicroFiltration, uwwOther, uwwBOD5Perf, uwwCODPerf, uwwTSSPerf, uwwNTotPerf, uwwPTotPerf, uwwOtherPerf, uwwEndLife)
                              VALUES ('" . $row[3] . "','" . $row[0] . "','" . $row[1] . "','" . $row[2] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "','" . $row[8] . "','" . $row[9] . "','" . $row[11] . "','" . $row[12] . "','" . $row[13] . "','" . $row[14] . "','" . $row[15] . "','" . $row[16] . "','" . $row[17] . "','" . $row[18] . "','" . $row[19] . "','" . $row[20] . "','" . $row[21] . "','" . $row[22] . "','" . $row[23] . "','" . $row[25] . "','" . $row[26] . "','" . $row[27] . "','" . $row[28] . "','" . $row[29] . "','" . $row[30] . "','" . $row[32] . "')";
                      
                              $result = mysqli_query($conn, $sqlInsert);
                              var_dump($result);
                          }                        
                          
                      }          
                      else if ($tableName == 'aux_T_Agglomerations') {

                          // Si és string buit, desar error
                          if (is_string($row[1]) && $row[1] == '') {
                              array_push($errors, "aggCode empty. File: " . $aux[$i]["name"]);
                              $result = true;
                          }
                          // Si és string buit, desar error                        
                          else if ((is_string($row[9]) && $row[9] == '') || ($row[9] === 0.0)) {
                              array_push($errors, "aggLatitude empty. File: " . $aux[$i]["name"] . ". aggCode: " . $row[1]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if ((is_string($row[10]) && $row[10] == '') || ($row[10] === 0.0)) {
                              array_push($errors, "aggLongitude empty. File: " . $aux[$i]["name"] . ". aggCode: " . $row[1]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if (is_string($row[11]) && $row[11] == '') {
                              array_push($errors, "aggState empty. File: " . $aux[$i]["name"] . ". aggCode: " . $row[1]);
                              $result = true;
                          }
                          else { // Cap error ni warning, pujar la fila
                              $sqlInsert = "INSERT INTO `".$tableName."` (aggCode, aggName, aggEndLife, aggGenerated, aggLatitude, aggLongitude, C1, C2, C3, aggState)
                              VALUES ('" . $row[1] . "','" . $row[2] . "','" . $row[7] . "','" . $row[8] . "','" . $row[9] . "','" . $row[10] . "','" . $row[11] . "','" . $row[13] . "','" . $row[17] . "','" . $row[18] . "')";
                          
                              $result = mysqli_query($conn, $sqlInsert);
                              var_dump($result);
                          }          
                          
                      }
                      else if ($tableName == 'aux_T_DischargePoints') {
                          // Si és string buit, desar error
                          if (is_string($row[1]) && $row[1] == '') {
                              array_push($errors, "dcpState empty. File: " . $aux[$i]["name"] . ". uwwCode: " . $row[3]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if (is_string($row[3]) && $row[3] == '') {
                              array_push($errors, "uwwCode empty. File: " . $aux[$i]["name"]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if (is_string($row[4]) && $row[4] == '') {
                              array_push($errors, "dcpCode empty. File: " . $aux[$i]["name"] . ". uwwCode: " . $row[3]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if ((is_string($row[7]) && $row[7] == '') || ($row[7] === 0.0)) {
                              array_push($errors, "dcpLatitude empty. File: " . $aux[$i]["name"] . ". uwwCode: " . $row[3]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if ((is_string($row[8]) && $row[8] == '') || ($row[9] === 0.0)) {
                              array_push($errors, "dcpLongitude empty. File: " . $aux[$i]["name"] . ". uwwCode: " . $row[3]);
                              $result = true;
                          }
                          // Si és string buit, desar error
                          else if (is_string($row[9]) && $row[9] == '') {
                              array_push($errors, "dcpWaterBodyType empty. File: " . $aux[$i]["name"] . ". uwwCode: " . $row[3]);
                              $result = true;
                          }
                          else { // Cap error ni warning, pujar la fila
                              $sqlInsert = "INSERT INTO `".$tableName."` (dcpState, uwwCode, dcpCode, dcpName, dcpLatitude, dcpLongitude, dcpWaterBodyType, dcpIrrigation, dcpTypeofReceivingArea, rcaCode, dcpSurfaceWaters, dcpWaterBodyID, dcpEndLife)
                              VALUES ('" . $row[1] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[7] . "','" . $row[8] . "','" . $row[9] . "','" . $row[10] . "','" . $row[11] . "','" . $row[12] . "','" . $row[13] . "','" . $row[14] . "','" . $row[30] . "')";
                          
                              $result = mysqli_query($conn, $sqlInsert);
                              var_dump($result);
                          }                         
                          
                      }
                      else if ($tableName == 'aux_T_UWWTPS_emission_load') {
                          // Si és string buit, desar error
                          if (is_string($row[2]) && $row[2] == '') {
                              array_push($errors, "uwwCode empty. File: " . $aux[$i]["name"]);
                              $result = true;
                          }                        
                          else { // Cap error ni warning, pujar la fila
                              $sqlInsert = "INSERT INTO `".$tableName."` (uwwCode, uwwName, uwwBODIncoming, uwwCODIncoming, uwwNIncoming, uwwPIncoming, uwwBODDischarge, uwwCODDischarge, uwwNDischarge, uwwPDischarge)
                              VALUES ('" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] . "','" . $row[6] . "','" . $row[7] . "','" . $row[8] . "','" . $row[9] . "','" . $row[10] . "','" . $row[11] . "')";
                      
                              $result = mysqli_query($conn, $sqlInsert);
                              var_dump($result);
                          }  
                                                  
                      }
                      else if ($tableName == 'aux_T_UWWTPAgglos') {
                          // Si és string buit, desar error
                          if (is_string($row[2]) && $row[2] == '') {
                              array_push($errors, "uwwCode empty. File: " . $aux[$i]["name"] . ". aucUWWTP_AggloID: " . $row[0]);
                              $result = true;
                          }     
                          // Si és string buit, desar error
                          else if (is_string($row[5]) && $row[5] == '') {
                              array_push($errors, "aggCode empty. File: " . $aux[$i]["name"]  . ". aucUWWTP_AggloID: " . $row[0]);
                              $result = true;
                          }                      
                          else { // Cap error ni warning, pujar la fila
                              $sqlInsert = "INSERT INTO `".$tableName."` (uwwCode, aggCode, C4, C5)
                              VALUES ('" . $row[2] . "','" . $row[5] . "','" . $row[7] . "','" . $row[9] . "')";
                          
                              $result = mysqli_query($conn, $sqlInsert);
                              var_dump($result);
                          } 

                      } 
                      
                      // si hi ha error a la pujada de la fila, desar-lo
                      if (!$result) {
                          preg_match_all('/".*?"|\'.*?\'/', mysqli_error($conn), $matches);
                          array_push($errors,  mysqli_error($conn) . ". File: " . $aux[$i]["name"]);
                      }

                  }                              

                  // Comprovar si hi ha duplicats
                  checkDuplicates($conn, $errors, $aux[$i]["table"]);                
              }
          }

          // Mostrar si s'ha pujat tot correctament o no
          if (!empty($result)) {
              if (empty($errors)) {
                  // executar condicionals
                  runConditionals($conn);
                  // juntar taules
                  joinAll($conn);
                  $type = "success";
                  $message = "CSV Data Imported into the Database";
              }
              else {
                  // Buidar taules auxiliars si hi ha hagut errors
                  $sqlInsert = "TRUNCATE aux_T_UWWTPS";
                  $result = mysqli_query($conn, $sqlInsert); 
                  $sqlInsert = "TRUNCATE aux_T_Agglomerations";
                  $result = mysqli_query($conn, $sqlInsert); 
                  $sqlInsert = "TRUNCATE aux_T_DischargePoints";
                  $result = mysqli_query($conn, $sqlInsert); 
                  $sqlInsert = "TRUNCATE aux_T_UWWTPS_emission_load";
                  $result = mysqli_query($conn, $sqlInsert); 
                  $sqlInsert = "TRUNCATE aux_T_UWWTPAgglos";
                  $result = mysqli_query($conn, $sqlInsert); 
                  $type = "error";
                  $message = "Problem in Importing CSV Data"; 
              }                    
          } else {
              // Buidar taules auxiliars si hi ha hagut errors
              $sqlInsert = "TRUNCATE aux_T_UWWTPS";
              $result = mysqli_query($conn, $sqlInsert);
              $sqlInsert = "TRUNCATE aux_T_Agglomerations";
              $result = mysqli_query($conn, $sqlInsert);
              $sqlInsert = "TRUNCATE aux_T_DischargePoints";
              $result = mysqli_query($conn, $sqlInsert);
              $sqlInsert = "TRUNCATE aux_T_UWWTPS_emission_load";
              $result = mysqli_query($conn, $sqlInsert);
              $sqlInsert = "TRUNCATE aux_T_UWWTPAgglos";
              $result = mysqli_query($conn, $sqlInsert);
              $type = "error";
              $message = "Problem in Importing CSV Data";
          }
          
      //}   
  }
  mysqli_close($conn);

  //parse string al valor que toca per poder pujar els elements a les taules
  function parseTable($tableName, $row) {
      if ($tableName == 'aux_T_UWWTPS') {
          if ($row[4] != '') {
              $row[4] = str_replace("'", "\'", $row[4]);
          }
      
          $row[0] = intval($row[0]); //columna uwwState
          $row[8] = (float) $row[8];
          $row[9] = (float) $row[9];
          $row[11] = intval($row[11]);
          $row[12] = intval($row[12]);
          $row[13] = intval($row[13]);
          $row[14] = intval($row[14]);
          $row[15] = intval($row[15]);
          $row[16] = intval($row[16]);
          $row[17] = intval($row[17]);
          $row[18] = intval($row[18]);
          $row[19] = intval($row[19]);
          $row[20] = intval($row[20]);
          $row[21] = intval($row[21]);
          $row[22] = intval($row[22]);
          $row[23] = intval($row[23]);
      }
      else if ($tableName == 'aux_T_Agglomerations') {
          if ($row[2] != '') {
              $row[2] = str_replace("'", "\'", $row[2]);
          }

          $row[8] = intval($row[8]);
          $row[9] = (float) $row[9];
          $row[10] = (float) $row[10];
          $row[11] = (float) $row[11];
          $row[13] = (float) $row[13];
          $row[17] = (float) $row[17];
          $row[18] = intval($row[18]);
      }
      else if ($tableName == 'aux_T_DischargePoints') {
          if ($row[5] != '') {
              $row[5] = str_replace("'", "\'", $row[5]);
          }

          $row[7] = (float) $row[7];
          $row[8] = (float) $row[8];
          $row[13] = intval($row[13]);
      }
      else if ($tableName == 'aux_T_UWWTPS_emission_load') {
          if ($row[3] != '') {
              $row[3] = str_replace("'", "\'", $row[3]);
          }

          $row[4] = intval($row[4]);
          $row[5] = intval($row[5]);
          $row[6] = intval($row[6]);
          $row[7] = intval($row[7]);
          $row[8] = intval($row[8]);
          $row[9] = intval($row[9]);
          $row[10] = intval($row[10]);
          $row[11] = intval($row[11]);
      }
      else if ($tableName == 'aux_T_UWWTPAgglos') {
          $row[7] = (float) $row[7];
          $row[9] = (float) $row[9];
      }else{
        //altre taula
      }

      return $row;
  }

  // Comprovar si hi ha repetits ens els camps que s'indiquen a la documentacio
  function checkDuplicates($conn, & $errors, $nameFile) {
      if ($nameFile == 'aux_T_UWWTPS') {
          $sqlInsert = "SELECT uwwCode, COUNT(*) c FROM aux_T_UWWTPS GROUP BY uwwCode HAVING COUNT(*) > 1";
          $result = mysqli_query($conn, $sqlInsert);
      
          while($row = mysqli_fetch_array($result)) {
              array_push($errors,  "" . $row['uwwCode'] . " duplicated " . $row['c'] . " times. File:" . $nameFile);
          }
      }
      else if ($nameFile == 'aux_T_Agglomerations') {
          $sqlInsert = "SELECT aggCode, COUNT(*) c FROM aux_T_Agglomerations GROUP BY aggCode HAVING COUNT(*) > 1";
          $result = mysqli_query($conn, $sqlInsert);
      
          while($row = mysqli_fetch_array($result)) {
              array_push($errors,  "" . $row['aggCode'] . " duplicated " . $row['c'] . " times. File:" . $nameFile);
          }
      }
      else if ($nameFile == 'aux_T_UWWTPS_emission_load') {        
          $sqlInsert = "SELECT uwwCode, COUNT(*) c FROM aux_T_UWWTPS_emission_load GROUP BY uwwCode HAVING COUNT(*) > 1";
          $result = mysqli_query($conn, $sqlInsert);
      
          while($row = mysqli_fetch_array($result)) {            
              array_push($errors,  "" . $row['uwwCode'] . " duplicated " . $row['c'] . " times. File:" . $nameFile);
          }
      }    

      return $errors;
  }


  // Fer operacions condicionals a la taula aux_t_uwwtps
  function runConditionals($conn) {
      $sqlSelect = "SELECT uwwID, uwwPrimaryTreatment, uwwSecondaryTreatment, uwwNRemoval, uwwPRemoval, UwwChlorination, uwwOzonation, uwwSandFiltration, uwwMicroFiltration FROM aux_t_uwwtps";
      $select = mysqli_query($conn, $sqlSelect);
      $res = '';
      if (!empty($select)) {
          while ($row = mysqli_fetch_array($select)) {
              //var_dump($row);
              // $row[0] => uwwID; $row[1] => uwwPrimaryTreatment; $row[2] => uwwSecondaryTreatment; $row[3] => uwwNRemoval;
              // $row[4] => uwwPRemoval; $row[5] => UwwChlorination; $row[6] => uwwOzonation; $row[7] => uwwSandFiltration; 
              // $row[8] => uwwMicroFiltration;

              if ($row[1] == '') {
                  $res = NULL;
              }
              if ($row[1] == '1') {
                  $res = 'Pr';
              }
              if ($row[1] == '0') {
                  $res = 'No treatment';
              }
              if ($row[1] == '1' && $row[2] == '1') {
                  $res = 'PrS';
              }
              if (($row[1] == '0' && $row[2] == '1') || ($row[1] == '' && $row[2] == '1')) {
                  $res = 'S';
              }
              if ($row[1] == '1' && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1')) {
                  $res = 'PrST';
              }
              if ((($row[1] == '0' || $row[1] == '') && $row[2] == '1') && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1')) {
                  $res = 'ST';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1')) {
                  $res = 'T';
              }
              if ($row[1] == '1' && $row[3] == '1') {
                  $res = 'PrN';
              }
              if ($row[1] == '1' && $row[4] == '1') {
                  $res = 'PrP';
              }
              if ($row[1] == '1' && $row[3] == '1' && $row[4] == '1') {
                  $res = 'PrNP';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[3] == '1' && $row[4] == '1') {
                  $res = 'NP';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[3] == '0' || $row[3] == '') && $row[4] == '1') {
                  $res = 'P';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[3] == '1' && ($row[4] == '0' || $row[4] == '')) {
                  $res = 'N';
              }
              if ($row[1] == '1' && $row[2] == '1' && $row[3] == '1') {
                  $res = 'PrSN';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[2] == '1' && $row[3] == '1') {
                  $res = 'SN';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && $row[3] == '1') {
                  $res = 'N';
              }
              if ($row[1] == '1' && $row[2] == '1' && $row[4] == '1') {
                  $res = 'PrSP';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[2] == '1' && $row[4] == '1') {
                  $res = 'SP';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && $row[4] == '1') {
                  $res = 'P';
              }
              if ($row[1] == '1' && $row[2] == '1' && $row[3] == '1' && $row[4] == '1') {
                  $res = 'PrSNP';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[2] == '1' && $row[3] == '1' && $row[4] == '1') {
                  $res = 'SNP';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && $row[3] == '1' && $row[4] == '1') {
                  $res = 'NP';
              }
              if ($row[1] == '1' && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[3] == '1') {
                  $res = 'PrSTN';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[3] == '1') {
                  $res = 'STN';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[3] == '1') {
                  $res = 'TN';
              }
              if ($row[1] == '1' && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[4] == '1') {
                  $res = 'PrSTP';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[4] == '1') {
                  $res = 'STP';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[4] == '1') {
                  $res = 'TP';
              }
              if ($row[1] == '1' && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[3] == '1' && $row[4] == '1') {
                  $res = 'PrSTNP';
              }
              if (($row[1] == '0' || $row[1] == '') && $row[2] == '1' && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[3] == '1' && $row[4] == '1') {
                  $res = 'STNP';
              }
              if (($row[1] == '0' || $row[1] == '') && ($row[2] == '0' || $row[2] == '') && ($row[5] == '1' || $row[6] == '1' || $row[8] == '1' || $row[7] == '1') && $row[3] == '1' && $row[4] == '1') {
                  $res = 'TNP';
              }

              $sqlUpdate = "UPDATE aux_T_UWWTPS SET uwwTypeofTreatment = '" . $res . "' WHERE uwwID = '" . $row[0] . "'";
              $update = mysqli_query($conn, $sqlUpdate);

              if (!$update) {
                  echo("Error: " . mysqli_error($update));
              }
          }        
      }
      else {
          // ERROR AL FER SELECT
      }
  }

  // Juntar totes les taules auxiliars menys aux_T_DischargePoint
  function joinAll($conn) {
    $sqlDrop = "DROP TABLE IF EXISTS T_UWWTD_final";
    $drop = mysqli_query($conn, $sqlDrop);

    $sqlJoin = "CREATE TABLE T_UWWTD_final AS 
    SELECT aux_t_uwwtps.uwwCode, aux_t_uwwtps.uwwState, aux_t_uwwtps.rptMStateKey, aux_t_uwwtps.aggCode, aux_t_uwwtps.uwwName, aux_t_uwwtps.uwwCollectingSystem, aux_t_uwwtps.uwwDateClosing, aux_t_uwwtps.uwwLatitude, aux_t_uwwtps.uwwLongitude, aux_t_uwwtps.uwwLoadEnteringUWWTP, aux_t_uwwtps.uwwCapacity, aux_t_uwwtps.uwwPrimaryTreatment, aux_t_uwwtps.uwwSecondaryTreatment, aux_t_uwwtps.uwwOtherTreatment, aux_t_uwwtps.uwwNRemoval, aux_t_uwwtps.uwwPRemoval, aux_t_uwwtps.uwwUV, aux_t_uwwtps.uwwChlorination, aux_t_uwwtps.uwwOzonation, aux_t_uwwtps.uwwSandFiltration, aux_t_uwwtps.uwwMicroFiltration, aux_t_uwwtps.uwwOther, aux_t_uwwtps.uwwBOD5Perf, aux_t_uwwtps.uwwCODPerf, aux_t_uwwtps.uwwTSSPerf, aux_t_uwwtps.uwwNtotPerf, aux_t_uwwtps.uwwPTotPerf, aux_t_uwwtps.uwwOtherPerf, aux_t_uwwtps.uwwEndLife, aux_t_uwwtps.uwwTypeofTreatment, aux_t_agglomerations.aggName, aux_t_agglomerations.aggEndLife, aux_t_agglomerations.aggGenerated, aux_t_agglomerations.aggLatitude, aux_t_agglomerations.aggLongitude, aux_t_agglomerations.C1, aux_t_agglomerations.C2, aux_t_agglomerations.C3, aux_t_agglomerations.aggState, aux_t_uwwtps_emission_load.emissionID, aux_t_uwwtps_emission_load.uwwBODIncoming, aux_t_uwwtps_emission_load.uwwCODIncoming, aux_t_uwwtps_emission_load.uwwNIncoming, aux_t_uwwtps_emission_load.uwwPIncoming, aux_t_uwwtps_emission_load.uwwBODDischarge, aux_t_uwwtps_emission_load.uwwCODDischarge, aux_t_uwwtps_emission_load.uwwNDischarge, aux_t_uwwtps_emission_load.uwwPDischarge, aux_t_uwwtpagglos.uww_aggloID, aux_t_uwwtpagglos.C4, aux_t_uwwtpagglos.C5 FROM aux_t_uwwtps
    LEFT JOIN aux_t_agglomerations ON aux_t_uwwtps.aggCode=aux_t_agglomerations.aggCode
    LEFT JOIN aux_t_uwwtps_emission_load ON aux_t_uwwtps.uwwCode=aux_t_uwwtps_emission_load.uwwCode
    LEFT JOIN aux_t_uwwtpagglos ON aux_t_uwwtps.uwwCode=aux_t_uwwtpagglos.uwwCode AND aux_t_agglomerations.aggCode=aux_t_uwwtpagglos.aggCode";
    $join = mysqli_query($conn, $sqlJoin);    
  }
?>
<!doctype html><html><head>
  <script src="jquery-3.2.1.min.js"></script>
  <style>
    body {
      font-family: Arial;
      width: 550px;
    }

    .outer-scontainer {
      background: #F0F0F0;
      border: #e0dfdf 1px solid;
      padding: 20px;
      border-radius: 2px;
        width: 700px;
    }

    .input-row {
      margin-top: 0px;
      margin-bottom: 20px;
    }

    .btn-submit {
      background: #333;
      border: #1d1d1d 1px solid;
      color: #f0f0f0;
      font-size: 0.9em;
      width: 100px;
      border-radius: 2px;
      cursor: pointer;
    }

    .outer-scontainer table {
      border-collapse: collapse;
      width: 100%;
    }

    .outer-scontainer th {
      border: 1px solid #dddddd;
      padding: 8px;
      text-align: left;
    }

    .outer-scontainer td {
      border: 1px solid #dddddd;
      padding: 8px;
      text-align: left;
    }

    #response {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 2px;
        display:none;
    }

    .success {
        background: #c7efd9;
        border: #bbe2cd 1px solid;
    }

    .error {
        background: #fbcfcf;
        border: #f3c6c7 1px solid;
    }

    div#response.display-block {
        display: block;
    }
  </style>
  <script type="text/javascript">
    // Per pujar fitxers .csv
    $(document).ready(function() {
        $("#frmCSVImport").on("submit", function () {

          $("#response").attr("class", "");
            $("#response").html("");
            var fileType = ".csv";
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
            
            if (!regex.test($("#file").val().toLowerCase())) {
                $("#response").addClass("error");
                $("#response").addClass("display-block");
                $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
                return false;
            }
            return true;
        });
    });
  </script>
</head><body>

<h2>Import CSV file into Mysql using PHP</h2>
<div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
<div class="outer-scontainer">
  <div class="row">

  <!-- Formulari per pujar fitxers -->
  <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
    <div class="input-row">
      <!--agglomerations-->
      <label class="col-md-4 control-label">Choose agglomerations CSV File</label> 
      <input style="margin-bottom: 10px" type="file" name="agglo" id="agglo" accept=".csv"><br>
      <!--discharge points-->
      <label class="col-md-4 control-label">Choose discharge points CSV File</label> 
      <input style="margin-bottom: 10px" type="file" name="dschrg" id="dschrg" accept=".csv"><br>
      <!--uwwtpagglos-->
      <label class="col-md-4 control-label">Choose uwwtps agglomerations CSV File</label> 
      <input type="file" name="uwwtpsA" id="uwwtpsA" accept=".csv"><br><br>
      <!--uwwtps-->
      <label class="col-md-4 control-label">Choose uwwtps CSV File</label>
      <input style="margin-bottom: 10px" type="file" name="uwwtps" id="uwwtps" accept=".csv"><br>
      <!--emission loads-->
      <label class="col-md-4 control-label">Choose uwwtps emission load CSV File</label> 
      <input style="margin-bottom: 10px" type="file" name="emission" id="emission" accept=".csv"><br>
      <!--submit-->
      <button type="submit" id="submit" name="import" class="btn-submit">Import</button>
    </div>
  </form>

  </div>
</div>

<!-- Mostrar errors i warnings -->
<div style="width: 1000px">
  <?php
    if(isset($errors) && isset($warnings)) {
      foreach($errors as $err) {            
          echo '<p style="color: red">ERROR: ', $err, '</p>';
      }

      /*foreach($warnings as $warn) {            
          echo '<p style="color: orange">WARNING: ', $warn, '</p>';
      }*/
    }
    $errors = [];
    $warnings = [];     
  ?>    
</div>
