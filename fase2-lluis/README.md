*status*: en desenvolupament (seguint passos arxiu 'operacions.docx.md')

__Objectiu__: processar 5 taules (arxius CSV grans descarregats de
https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5)
per poder fer consultes SQL avançades des de diferents programes com ara GIS o R.

__fase2__: seguir feina fase 1 anna, transformant gui php a fitxer python (+ ràpid)

## Requeriments
- python3 >= 3.6 (important per mantenir ordre columnes, per sota v3.6 no funciona)
- sqlite3

## Procediment
Per generar l'arxiu SQL (base de dades) ```waterbase.sqlite``` que combina les 5 taules, executar en
un terminal (bash) la següent comanda:

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
