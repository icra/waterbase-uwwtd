## 1-export_mdb_to_sqlite
script for exporting mdb file to a new sqlite file.

## 2-web
web platform that reads the sqlite file and identifies data problems so the
user can correct them.

## 3-how_to_create_gis_layers_from_sqlite
script to generate a GIS layer from the sqlite database file

## queries done so far to find problems

```sql
  SELECT agg.aggLatitude,agg.aggLongitude FROM T_UWWTPS as uww, T_Agglomerations as agg, T_UWWTPAgglos as con WHERE (uww.uwwCode = con.aucUwwCode AND agg.aggCode = con.aucAggCode AND) AND (uww.uwwLongitude is 0 OR uww.uwwLongitude is NULL OR uww.uwwLatitude is 0 OR uww.uwwLatitude is NULL)
  SELECT agg.aggLatitude,agg.aggLongitude FROM T_UWWTPS as uww, T_Agglomerations as agg, T_UWWTPAgglos as con WHERE (uww.uwwCode = con.aucUwwCode AND agg.aggCode = con.aucAggCode) AND (uww.uwwLongitude is 0 OR uww.uwwLongitude is NULL OR uww.uwwLatitude is 0 OR uww.uwwLatitude is NULL)
  SELECT uww.uwwCode,agg.aggLatitude,agg.aggLongitude FROM T_UWWTPS as uww, T_Agglomerations as agg, T_UWWTPAgglos as con WHERE (uww.uwwCode = con.aucUwwCode AND agg.aggCode = con.aucAggCode) AND (uww.uwwLongitude is 0 OR uww.uwwLongitude is NULL OR uww.uwwLatitude is 0 OR uww.uwwLatitude is NULL)
  SELECT uww.uwwCode, dcp.dcpLatitude, dcp.dcpLongitude FROM T_UWWTPS as uww, T_DischargePoints as dcp WHERE (uww.uwwCode = dcp.uwwCode) AND (uww.uwwLongitude is 0 OR uww.uwwLongitude is NULL OR uww.uwwLatitude is 0 OR uww.uwwLatitude is NULL)
  SELECT uww.uwwCode, agg.aggLatitude, agg.aggLongitude FROM T_UWWTPS as uww, T_Agglomerations as agg WHERE (uww.aggCode = agg.aggCode) AND (uww.uwwLongitude is 0 OR uww.uwwLongitude is NULL OR uww.uwwLatitude is 0 OR uww.uwwLatitude is NULL)
  SELECT agg.aggCode, uww.uwwLatitude, uww.uwwLongitude FROM T_UWWTPS as uww, T_Agglomerations as agg WHERE (uww.aggCode = agg.aggCode) AND (agg.aggLongitude is 0 OR agg.aggLongitude is NULL OR agg.aggLatitude is 0 OR agg.aggLatitude is NULL)
```
