<?php 
require("DbConnection.php");
$brojpod= $_POST['id'];
mysql_query('DELETE FROM pods_kalk WHERE brojpod = '.$brojpod);
?>