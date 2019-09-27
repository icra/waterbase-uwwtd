*status*: please go to phase3, this folder is no longer used, because used another approach: creating the database from the csv files, which generated a lot of problems

[lang=catalan]

__fase2__: seguir feina fase 1 anna, transformant gui php a fitxer python (+ ràpid)

__Objectiu__: processar 5 taules (arxius CSV grans descarregats de
https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5)
per poder fer consultes SQL avançades des de diferents programes com ara GIS o R.

__permalink versió waterbase utilitzada__: https://www.eea.europa.eu/ds_resolveuid/8396aa5079544dab9d8b8966237a9f3b

__permalink última versió waterbase__: https://www.eea.europa.eu/ds_resolveuid/DAT-106-en

__arxius que ens interessen del waterbase__:
<table>
  <tr><td>dbo.VL_Agglomerations.csv       <td>aglomeracions
  <tr><td>dbo.VLS_DischargePoints.csv     <td>punts de descàrrega
  <tr><td>dbo.VL_UWWTPAgglos.csv          <td>info aglomeracions - depuradores
  <tr><td>dbo.VL_UWWTPS.csv               <td>depuradores
  <tr><td>dbo.V_UWWTPS_emission_load.csv  <td>emissions depuradores
</table>

## Requeriments
- python >= v3.7 (important per mantenir ordre columnes, per sota v3.7 no funcionarà)
- sqlite3

## Procediment
Per generar l'arxiu SQL (base de dades) ```waterbase.sqlite``` que combina les 5 taules, executar en
un terminal (sh/bash/zsh...) la següent comanda:

```shell
  $ python3.7 import_csv_files.py
```
Resultat:
```
  Importing  T_Agglomerations.csv...        Done  (27848  rows)
  Importing  T_DischargePoints.csv...       Done  (30031  rows)
  Importing  T_UWWTPAgglos.csv...           Done  (28372  rows)
  Importing  T_UWWTPS_emission_load.csv...  Done  (30437  rows)
  Importing  T_UWWTPs.csv...                Done  (30437  rows)
```
Temps execució estimat: uns 5 segons

Aquesta instrucció crea un arxiu anomenat ```waterbase.sqlite```, que es
pot obrir amb la comanda ```sqlite3(1)```

Nota: els fitxers descarregats de la web europea tenen codificació ASCII o
ISO-8859 (entre d'altres). És molt important que els arxius csv estiguin en
format UTF-8, sinó la comanda ```python3.7 import_csv_files.py``` donarà error.
Per convertir els fitxers a utf-8 es pot fer servir la comanda ```iconv(1)``` o
copiar i enganxar els arxius a un nou fitxer de text fent servir un editor de
text.

Per obrir la base de dades de forma interactiva, executar la següent comanda:

```shell
  $ sqlite3 waterbase_UWWTD_v6.sqlite
```

Ara ja es poden fer consultes SQL directament a la consola, com per exemple:
```shell
  SQLite version 3.24.0 2018-06-04 14:10:15
  Enter ".help" for usage hints.
  sqlite> SELECT * FROM T_Agglomerations LIMIT 2;
  2297481|ATAG_1-00000001|Andau|20061231|treatment capacity of plant as indication for generated load|no|||8000|47.7787|17.0491|98.7|E|1.3|E|AT112|E|0|1||AT|27679
  2297482|ATAG_1-00000005|Deutschkreuz (Mittleres Burgenland) Goldbachtal|20061231|treatment capacity of plant as indication for generated load|no|||65000|47.5945|16.6406|98.7|E|1.3|E|A T111|E|0|1||AT|27679
```

Per realitzar consultes de forma no interactiva (útil per scripting), es pot
executar la següent comanda:

```shell
  $ sqlite3 -line waterbase_UWWTD_v6.sqlite "SELECT * FROM T_Agglomerations LIMIT 1"
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

Per fer comandes avançades (més complicades) millor fer-ho dins de scripts, com
per exemple ```query_sqlite.sh```, que conté un exemple de comanda SQL
avançada.

```shell
  $ bash query_sqlite.sh
```

Finalment, per fer consultes a la base de dades ```waterbase.sqlite``` des de
qGIS, R, o altres programes, consultar el manual corresponent per fer una
connexió amb l'arxiu ```waterbase.sqlite```.
