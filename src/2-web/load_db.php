<?php

/*db version sqlite file paths*/
$db_versions=array("v7","v7 OC","v8","v9");

//default version if no cookie set
$db_version="v9";
$db_file_path='../1-export_mdb_to_sqlite/db_versions/v9.accdb.sqlite';

//load
if(isset($_COOKIE["db_version"])){
  switch($_COOKIE["db_version"]){
    case "v7":
      $db_version="v7";
      $db_file_path='../1-export_mdb_to_sqlite/db_versions/v7.accdb.sqlite'; //v7
      break;
    case "v7 OC":
      $db_version="v7 OC";
      $db_file_path='../1-export_mdb_to_sqlite/db_versions/v7_OC.accdb.sqlite';
      break;
    case "v8":
      $db_version="v8";
      $db_file_path='../1-export_mdb_to_sqlite/db_versions/v8.accdb.sqlite'; //v8
      break;
    case "v9":
      $db_version="v9";
      $db_file_path='../1-export_mdb_to_sqlite/db_versions/v9.accdb.sqlite'; //v9
      break;
    default: break;
  }
}

//sqlite connection
$db=new SQLite3($db_file_path,SQLITE3_OPEN_READONLY);
//var_dump(get_class_methods($db));

?>
