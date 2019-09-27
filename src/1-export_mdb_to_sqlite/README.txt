EXPORT FROM MDB TO SQLITE
==========================
  software used: mdbtools (https://github.com/brianb/mdbtools)
    - mdb-tables(1)
    - mdb-schema(1)
    - mdb-export(1)

  see script 'convert_mdb_to_sqlite.sh'

EXPORT RESULTS
====================
  >> [mdb]    table T_UWWTPS: 30451 rows
  >> [sqlite] table T_UWWTPS: 30437 rows

  >> [mdb]    table T_Agglomerations: 28169 rows
  >> [sqlite] table T_Agglomerations: 27848 rows

  >> [mdb]    table T_UWWTPAgglos: 28382 rows
  >> [sqlite] table T_UWWTPAgglos: 28372 rows

  >> [mdb]    table T_DischargePoints: 30052 rows
  >> [sqlite] table T_DischargePoints: 30031 rows

  >> [mdb]    table T_UWWTPS_emission_load: 30446 rows
  >> [sqlite] table T_UWWTPS_emission_load: 30437 rows

HOW TO CONNECT SQLITE TO C
==========================
http://zetcode.com/db/sqlitec/
