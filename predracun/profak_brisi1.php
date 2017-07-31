
<?php require("../include/DbConnectionPDO.php");
$sifra_profak=$_POST['broj_profak'];
$sql = 'DELETE FROM profak WHERE broj_prof = ?';
$stmt = $baza_pdo->prepare($sql);
$stmt->execute(array($sifra_profak));

$deleted = $stmt->rowCount();
  if (!$deleted) {
	$error = 'Greska. Podatak nije obrisan.';
  }
  else{
  	?>
  	<script type="text/javascript">
		window.location = "../index.php"
	</script>
  	<?php
  }

?>