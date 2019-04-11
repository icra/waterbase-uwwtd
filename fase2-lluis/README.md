
# passos a seguir

1. per importar els arxius csv extrets de https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5

```shell
python3.7 import_waterbase_csv_files.py
```

Aquesta instrucció generarà un arxiu "waterbase_UWWTD_vX.sqlite" que es llavors es pot obrir amb la comanda ```sqlite3``` 

```shell
sqlite3 waterbase_UWWTD_v6.sqlite
```

```shell
sqlite -line waterbase_UWWTD_v6.sqlite "SELECT * FROM T_Agglomerations LIMIT 1"
```

2. executar consulta amb sqlite

```shell
bash query_sqlite.sh
```
