#!/bin/bash

# CREATE GIS LAYERS IN CSV FORMAT

# sqlite file path
db="../1-export_mdb_to_sqlite/Waterbase_UWWTD_v6_20171207.mdb.sqlite"

# create csvs
echo "SELECT aggName,aggLatitude,aggLongitude FROM T_Agglomerations" |sqlite3 -csv $db > layer_agglomerations.csv
echo "SELECT uwwName,uwwLatitude,uwwLongitude FROM T_UWWTPS"         |sqlite3 -csv $db > layer_uwwtps.csv
echo "SELECT dcpName,dcpLatitude,dcpLongitude FROM T_DischargePoints"|sqlite3 -csv $db > layer_discharge_points.csv
