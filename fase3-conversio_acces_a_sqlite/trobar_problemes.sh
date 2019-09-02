#!/bin/bash

# TROBAR PROBLEMES AL FITXER .SQLITE
echo "PROBLEMES TROBATS A 'Waterbase_UWWTD_v6_20171207.mdb.sqlite'";
echo "============================================================";

#ruta fitxer base de dades
db="./1-exportacio_mdb_a_sql/Waterbase_UWWTD_v6_20171207.mdb.sqlite";

#########
# UTILS #
#########

# query sqlite
function query(){
  if [[ $2 == "--count" ]]; then
    echo "$1" | sqlite3 $db | wc -l | awk '{print $1}'
  else
    opcions="-csv"
    echo "$1" | sqlite3 $opcions $db
  fi
}

# veure estructura taules (columnes)
function veure_schemas(){
  query '.schema T_Agglomerations';       # aglomeracions
  query '.schema T_UWWTPS';               # depuradores
  query '.schema T_UWWTPS_emission_load'; # emissions depuradores
  query '.schema T_UWWTPAgglos';          # connexions aglomeració-depuradora
  query '.schema T_DischargePoints';      # punts de descàrrega depuradores
}

####################
# BUSCAR PROBLEMES #
####################

# AGLOMERACIONS
  n=$(query 'SELECT 1 FROM T_Agglomerations' --count)
  echo;echo "> AGLOMERACIONS 'T_Agglomerations': $n"

  # aglomeracions duplicades
  n=$(
    query 'SELECT * FROM T_Agglomerations GROUP BY aggCode HAVING COUNT(aggCode)>1;' --count
  );
  echo ">> Aglomeracions duplicades 'COUNT(aggCode)>1': $n"

  # aglomeracions amb longitud o latitud NULL
  n=$(
    query 'SELECT * FROM T_Agglomerations WHERE aggLongitude is NULL OR aggLatitude is NULL' --count
  );
  echo ">> Aglomeracions on 'aggLatitude' o 'aggLongitude' són NULL: $n"

# DEPURADORES
  n=$(query 'SELECT * FROM T_UWWTPS' --count)
  echo;echo "> DEPURADORES 'T_UWWTPS': $n"

  # depuradores duplicades
  n=$(
    query 'SELECT * FROM T_UWWTPs GROUP BY uwwCode HAVING COUNT(uwwCode)>1;' --count
  );
  echo ">> Depuradores duplicades 'COUNT(uwwCode)>1': $n";

  # depuradores sense latitud o longitud
  n=$(
    query 'SELECT * FROM T_UWWTPS WHERE uwwLatitude is NULL OR uwwLongitude is NULL' --count
  );
  echo ">> Depuradores on 'uwwLatitude' o 'uwwLongitude' són NULL: $n"

  #depuradores sense coordenades
  #query 'SELECT rptMStateKey,uwwName FROM T_UWWTPS WHERE uwwLatitude is NULL OR uwwLongitude';

# UWWTP EMISSION LOAD
  n=$(query 'SELECT * FROM T_UWWTPS_emission_load' --count)
  echo;echo "> EMISSIONS DEPURADORES 'T_UWWTPS_emission_load': $n";

  # emissions amb uwwCode duplicat
  n=$(
    query 'SELECT * FROM T_UWWTPS_emission_load GROUP BY uwwCode HAVING COUNT(uwwCode)>1' --count
  );
  echo ">> Emissions amb 'uwwCode' duplicat: $n"

  # emissions amb uwwCode NULL
  n=$(
    query 'SELECT * FROM T_UWWTPS_emission_load WHERE uwwCode is NULL' --count
  );
  echo ">> Emissions on 'uwwCode' és NULL: $n"

  # emissions amb uwwCode not in depuradores
  n=$(
    query 'SELECT * FROM T_UWWTPS_emission_load WHERE uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)' --count
  );
  echo ">> Emissions on 'uwwCode' no existeix a T_UWWTPS: $n"

# CONNEXIONS AGLOMERACIÓ - DEPURADORA
  n=$(query 'SELECT * FROM T_UWWTPAgglos' --count)
  echo;echo "> CONNEXIONS AGLOMERACIÓ-DEPURADORA 'T_UWWTPAgglos': $n";

  # connexions amb uwwCode NULL
  n=$(
    query 'SELECT * FROM T_UWWTPAgglos WHERE aucUwwCode is NULL' --count
  );
  echo ">> Connexions on 'uwwCode' és NULL: $n"

  # connexions amb uwwCode not in depuradores
  n=$(
    query 'SELECT * FROM T_UWWTPAgglos WHERE aucUwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)' --count
  );
  echo ">> Connexions on 'uwwCode' no existeix a T_UWWTPS: $n"

  # connexions amb aggCode NULL
  n=$(
    query 'SELECT * FROM T_UWWTPAgglos WHERE aucAggCode is NULL' --count
  );
  echo ">> Connexions on 'aggCode' és NULL: $n"

  # connexions amb aggCode not in aglomeracions
  n=$(
    query 'SELECT * FROM T_UWWTPAgglos WHERE aucAggCode NOT IN (SELECT aggCode FROM T_Agglomerations)' --count
  );
  echo ">> Connexions on 'aggCode' no existeix a T_Agglomerations: $n"

  # depuradores sense connexió amb aglomeració
  n=$(
    query 'SELECT uwwCode FROM T_UWWTPS WHERE uwwCode NOT IN (SELECT aucUwwCode FROM T_UWWTPAgglos)' --count
  );
  echo ">> Depuradores sense connexió 'uwwCode not in T_UWWTPAgglos': $n"

  # aglomeracions sense connexió amb depuradora
  n=$(
    query 'SELECT aggCode FROM T_Agglomerations WHERE aggCode NOT IN (SELECT aucAggCode FROM T_UWWTPAgglos)' --count
  );
  echo ">> Aglomeracions sense connexió 'aggCode not in T_UWWTPAgglos': $n"

# DISCHARGE POINTS
  n=$(query 'SELECT 1 FROM T_DischargePoints' --count);
  echo;echo "> DISCHARGE POINTS 'T_DischargePoints': $n"

  # discharge points duplicats
  n=$(
    query 'SELECT * FROM T_DischargePoints GROUP BY dcpCode HAVING COUNT(dcpCode)>1' --count
  );
  echo ">> Discharge points duplicats ('dcpCode'): $n";

  # discharge points sense latitud o longitud
  n=$(
    query 'SELECT * FROM T_DischargePoints WHERE dcpLatitude is NULL OR dcpLongitude is NULL' --count
  );
  echo ">> Discharge points on 'dcpLatitude' o 'dcpLongitude' són NULL: $n"

  # dps sense depuradora
  n=$(
    query 'SELECT * FROM T_DischargePoints WHERE uwwCode is NULL' --count
  );
  echo ">> Discharge points on 'uwwCode' és NULL: $n"

  # dps on depuradora no existeix
  n=$(
    query 'SELECT * FROM T_DischargePoints WHERE uwwCode NOT IN (SELECT uwwCode FROM T_UWWTPS)' --count
  );
  echo ">> Discharge points on 'uwwCode' no existeix a la taula 'T_UWWTPS': $n"

  # depuradores sense discharge point
  n=$(
    query 'SELECT * FROM T_UWWTPS WHERE uwwCode NOT IN (SELECT uwwCode FROM T_DischargePoints)' --count
  );
  echo ">> Depuradores sense discharge points: $n"

  # depuradores amb més d'un discharge point
  n=$(
    query 'SELECT * FROM T_DischargePoints GROUP BY uwwCode HAVING COUNT(uwwCode)>1' --count
  );
  echo ">> Depuradores amb múltiples discharge points: $n"
