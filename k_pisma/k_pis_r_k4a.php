<?php
require("../include/DbConnection.php");
$q=$_GET["q"];
$ido=$_GET["ido"];
echo $q;
mysql_query("UPDATE k_pism_r SET tekst_k = '$q'
WHERE broj_k='$ido'");
?>