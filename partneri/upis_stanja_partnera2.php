<?php
require("../include/DbConnection.php");
$id=$_GET["id"];
$kol=$_GET["kol"];
mysql_query("UPDATE dob_kup SET stanje = '$kol'
WHERE sif_kup='$id'");
?>