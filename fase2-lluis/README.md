# passos a seguir per poder combinar les taules i fer consultes SQL avançades

Per processar els csv extrets de<br>https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5 :

```shell
python3.7 import_waterbase_csv_files.py
```

Els fitxers descarregats de la web europea tenen codificacions no estàndard
(ascii i ISO-8859 entre d'altres). És molt important que els arxius csv
estiguin en format utf-8, sinó donarà error. Per convertir els fitxers a utf-8
es pot fer servir la utilitat ```iconv(1)``` o copiar i enganxar els arxius a
un nou fitxer de text fent servir un editor de text.

Aquesta instrucció generarà un arxiu "waterbase_UWWTD_vX.sqlite" que es llavors es pot obrir amb la comanda ```sqlite3``` 

```shell
sqlite3 waterbase_UWWTD_v6.sqlite
```

```shell
sqlite -line waterbase_UWWTD_v6.sqlite "SELECT * FROM T_Agglomerations LIMIT 1"
```

executar consulta amb sqlite

```shell
bash query_sqlite.sh
```
