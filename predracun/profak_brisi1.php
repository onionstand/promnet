
<?php require("../include/DbConnection.php"); 
$sifra_fak=$_POST['broj_fak'];
mysql_query("DELETE FROM dosta WHERE broj_dost='$sifra_fak'");
?>
<script type="text/javascript">
	window.location = "../index.php"
</script>