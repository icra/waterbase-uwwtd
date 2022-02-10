<?php
$db_version = $_GET["db_version"];
setcookie("db_version",$db_version,time()+(86400*365),"/");
header("Location: index.php");
?>

