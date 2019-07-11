#!/bin/bash

# comanda SQL per combinar totes les taules
# una vegada creat el fitxer 'waterbase.sqlite'

taules=(
  'T_Agglomerations' \
  'T_DischargePoints' \
  'T_UWWTP_Agglo' \
  'T_UWWTPs_emission_load' \
  'T_UWWTPs' \
)

#sqlite3 options:
# -line display formatted output
# -html  Query results will be output as simple HTML tables.
# -csv   Set output mode to CSV (comma separated values).
# -[no]header Turn headers on or off.
opcions="-line"
sqlite="sqlite3 $opcions waterbase.sqlite"

for taula in ${taules[@]};do
  echo $taula
  echo "----------"
  $sqlite "SELECT * FROM $taula LIMIT 1"
done
