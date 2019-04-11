## Passos a seguir 

Objectiu: poder combinar les taules i fer consultes SQL avançades.

Per processar els csv extrets de https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5 cal executar en un terminal la següent comanda:

```shell
  python3.7 import_waterbase_csv_files.py
```
Resultat:
```
  Importing T_Agglomerations.csv... Done (27848 rows)
  Importing T_DischargePoints.csv... Done (30031 rows)
  Importing T_UWWTPAgglos.csv... Done (28372 rows)
  Importing T_UWWTPS_emission_load.csv... Done (30437 rows)
  Importing T_UWWTPs.csv... Done (30437 rows)
```

Aquesta instrucció generarà un arxiu ```waterbase_UWWTD_vX.sqlite``` que es pot obrir amb la comanda ```sqlite3(1)``` 

Nota: els fitxers descarregats de la web europea tenen codificacions no estàndard
(ascii i ISO-8859 entre d'altres). És molt important que els arxius csv
estiguin en format utf-8, sinó donarà error. Per convertir els fitxers a utf-8
es pot fer servir la utilitat ```iconv(1)``` o copiar i enganxar els arxius a
un nou fitxer de text fent servir un editor de text.

Per obrir la base de dades de forma interactiva, executar la comanda:

```shell
  sqlite3 waterbase_UWWTD_v6.sqlite
```

O per realitzar consultes de forma no interactiva (en shell scripts), es pot fer:

```shell
  sqlite3 -line waterbase_UWWTD_v6.sqlite "SELECT * FROM T_Agglomerations LIMIT 1"
```
Resultat:
```shell
  aggID = 2297481
  aggCode = ATAG_1-00000001
  aggName = Andau
  aggBeginLife = 20061231
  aggCalculation = treatment capacity of plant as indication for generated load
  aggChanges = no
  aggChangesComment = 
  aggEndLife = 
  aggGenerated = 8000
  aggLatitude = 47.7787
  aggLongitude = 17.0491
  aggC1 = 98.7
  aggMethodC1 = E
  aggC2 = 1.3
  aggMethodC2 = E
  aggNUTS = AT112
  aggMethodWithoutTreatment = E
  aggPercWithoutTreatment = 0
  aggState = 1
  bigCityID = 
  rptMStateKey = AT
  ReportNetEnvelopeFileId = 27679
```

executar consulta amb sqlite

```shell
  bash query_sqlite.sh
```


