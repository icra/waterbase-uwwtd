#!/bin/bash

#sqlite3 options:
# -line display formatted output
# -html  Query results will be output as simple HTML tables.
# -csv   Set output mode to CSV (comma separated values).
# -[no]header Turn headers on or off.
sqlite="sqlite3 -line waterbase_UWWTD_v6.sqlite"

tables=(
  'T_Agglomerations' \
  'T_DischargePoints' \
  'T_UWWTP_Agglo' \
  'T_UWWTPs_emission_load' \
  'T_UWWTPs' \
)

for table in ${tables[@]};do
  echo $table
  echo "----------"
  $sqlite "SELECT * FROM $table LIMIT 1"
done
