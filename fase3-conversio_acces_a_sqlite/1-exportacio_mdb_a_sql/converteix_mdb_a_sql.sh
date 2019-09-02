#!/bin/bash
#exporta les taules que ens interessen del fitxer acces mdb a un fitxer sqlite

mdb="Waterbase_UWWTD_v6_20171207.mdb"; #ruta arxiu mdb (ms acces)
taules=("T_UWWTPS" "T_Agglomerations" "T_UWWTPAgglos" "T_DischargePoints" "T_UWWTPS_emission_load")

#exporta de mdb
for taula in ${taules[@]}; do
  echo -n "Exportant $taula d'Access (mdb) a SQL..."
  mdb-schema --drop-table $mdb sqlite --table $taula > $taula.schema.sql; #schema taula
  mdb-export -I sqlite $mdb $taula > $taula.sql;                          #dades taula
done

#compta files de cada taula
for taula in ${taules[@]}; do
  files=$(wc -l $taula.sql | awk '{print $1}'); #nombre de files
  echo ">> [mdb] taula $taula: $files files ";
done

#importa a sqlite
for taula in ${taules[@]}; do
  echo -n "Important $taula a sqlite3... "
  sqlite3 $mdb.sqlite < $taula.schema.sql
  sqlite3 $mdb.sqlite < $taula.sql
  echo "Fet"
done

#compta files de cada taula
for taula in ${taules[@]}; do
  sql="SELECT COUNT(*) FROM $taula;"
  files=$(echo "$sql" | sqlite3 $mdb.sqlite);
  echo ">> [sqlite] taula $taula: $files files ";
done

#reconciliació dades
