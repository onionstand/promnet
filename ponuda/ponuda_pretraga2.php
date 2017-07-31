<?php
require("../include/DbConnectionPDO.php");
$brojprofak=$_POST['broj_profak'];
$imerob=$_POST['naziv_robe'];
$fak_kol=$_POST['fak_kol'];
if (!$_POST['fak_rab']){
	$fak_rab=0;
}
else{
	$fak_rab=$_POST['fak_rab'];
}
$cena_r=$_POST['cena_r'];
$porez=$_POST['porez'];
$jed_mere=$_POST['jed_mere'];

if (isset($_POST['sifra_robe'])){
	$sifra_robe=$_POST['sifra_robe'];

	$upit_profak_unos = "INSERT INTO ponuda_stavke (br_ponude, naziv_robe, sifra_robe, kolicina, jed_mere, cena_profak, rabat, porez)
						VALUES
						(:brojprofak, :imerob, :sifra_robe, :fak_kol, :jed_mere, :cena_r, :fak_rab, :porez)";

	$stmt_profak_unos = $baza_pdo->prepare($upit_profak_unos);
	$stmt_profak_unos->bindParam(':brojprofak', $brojprofak, PDO::PARAM_INT);
	$stmt_profak_unos->bindParam(':imerob', $imerob, PDO::PARAM_STR);
	$stmt_profak_unos->bindParam(':sifra_robe', $sifra_robe, PDO::PARAM_INT);
	$stmt_profak_unos->bindParam(':fak_kol', $fak_kol, PDO::PARAM_STR);
	$stmt_profak_unos->bindParam(':jed_mere', $jed_mere, PDO::PARAM_STR);
	$stmt_profak_unos->bindParam(':cena_r', $cena_r, PDO::PARAM_STR);
	$stmt_profak_unos->bindParam(':fak_rab', $fak_rab, PDO::PARAM_STR);
	$stmt_profak_unos->bindParam(':porez', $porez, PDO::PARAM_STR);
	$stmt_profak_unos->execute() or die(print_r($stmt_profak_unos->errorInfo(), true));
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$(".dugme_zeleno").focus();
			});
		</script>
		<title>Ponuda</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<p>Roba je uneta.</p>
			<div class="cf"></div>
			<form action="ponuda1.php" method="get">
				<input type="hidden" name="ponuda_stara" value="<?php echo $brojprofak; ?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>