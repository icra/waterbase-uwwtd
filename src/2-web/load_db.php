<?php

  //sqlite file path and version
  $db_file_path='../1-export_mdb_to_sqlite/Waterbase_UWWTD_v7_20190913021736.accdb.sqlite';
  $db_version="v7";

  //sqlite connection
  $db=new SQLite3($db_file_path);
?>
