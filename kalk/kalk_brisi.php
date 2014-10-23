<?php require("../include/DbConnection.php"); 
$sifra_kalk=$_POST['broj_kalkulaci'];
mysql_query("DELETE FROM kalk WHERE broj_kalk='$sifra_kalk'");
mysql_query("DELETE FROM pods_kalk WHERE b_kalkulacije='$sifra_kalk'");
?>
<script type="text/javascript">
window.location = "../index.php"
</script>
