
#sqlite3 options:
# -line display formatted output
# -html  Query results will be output as simple HTML tables.
# -csv   Set output mode to CSV (comma separated values).
# -[no]header Turn headers on or off.

arxiu="../Waterbase_UWWTD_v7_20190913021736.accdb.sqlite"
opcions="-line"
sqlite="sqlite3 $opcions $arxiu"
$sqlite "
  SELECT aggCode, aggName, rptMStateKey FROM T_Agglomerations
  WHERE aggState=1 AND aggCode NOT IN (SELECT aucAggCode FROM T_UWWTPAgglos) AND ( aggRemarks IS NULL OR ( aggRemarks NOT LIKE '%IAS%' AND aggRemarks NOT LIKE '%septic%' ) )
"
