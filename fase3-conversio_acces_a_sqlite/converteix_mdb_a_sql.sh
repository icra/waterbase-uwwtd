#!/bin/bash
#exporta les taules que ens interessen del fitxer acces mdb a un fitxer sqlite

mdb="Waterbase_UWWTD_v6_20171207.mdb"; #mdb file ms acces
taules=("T_UWWTPS" "T_Agglomerations" "T_UWWTPAgglos" "T_DischargePoints" "T_UWWTPS_emission_load")

#exporta de mdb
for taula in ${taules[@]}; do
  echo -n "Exportant $taula de Access (mdb) a SQL..."
  mdb-schema --drop-table $mdb sqlite --table $taula > $taula.schema.sql; #estructura
  mdb-export -I sqlite $mdb $taula > $taula.sql;                          #dades
done

#compta nombre de files
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

#compta files a cada taula
for taula in ${taules[@]}; do
  sql="SELECT COUNT(*) FROM $taula;"
  files=$(echo "$sql" | sqlite3 $mdb.sqlite);
  echo ">> [sqlite] taula $taula: $files files ";
done

#conciliació dades
#TODO
