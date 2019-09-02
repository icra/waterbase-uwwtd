#!/bin/bash

# CREA CAPES GIS EN FORMAT CSV

# fitxer sqlite
db="../Waterbase_UWWTD_v6_20171207.mdb.sqlite"

# crea csvs
echo "SELECT uwwName,uwwLatitude,uwwLongitude FROM T_UWWTPS"         |sqlite3 -csv $db > capa_depuradores.csv
echo "SELECT aggName,aggLatitude,aggLongitude FROM T_Agglomerations" |sqlite3 -csv $db > capa_aglomeracions.csv
echo "SELECT dcpName,dcpLatitude,dcpLongitude FROM T_DischargePoints"|sqlite3 -csv $db > capa_punts_descarrega.csv
