<?php
require("../include/DbConnection.php");
$id=$_GET["id"];
$kol=$_GET["kol"];
mysql_query("UPDATE roba SET kolicina = '$kol'
			WHERE sifra='$id'");
?>