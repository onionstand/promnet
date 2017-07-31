<?php
require("../include/DbConnectionPDO.php");
$brojprofak=$_GET['brojfak'];

$upit_profak = "SELECT * FROM profak WHERE broj_prof=".$brojprofak;
$obracun_profak = $baza_pdo->query($upit_profak);
$red_profak = $obracun_profak->fetch();

$rok_placanja=$red_profak['rok'];// rok placanja
$sifra_fir=$red_profak['sifra_fir'];// sifra partnera
$napomena=$red_profak['napomena'];// rok placanja


$upit_max_broj_rac_rucni = "SELECT MAX(racun_rucni) AS max_racun_r FROM dosta;";
$obracun_max_b_r_r = $baza_pdo->query($upit_max_broj_rac_rucni);
$red_max_b_r_r = $obracun_max_b_r_r->fetch();
$max_broj_r_r=$red_max_b_r_r['max_racun_r']+1;// rucni broj racuna


$upit_ubacivanje_dost = "INSERT INTO dosta (datum_d,sifra_fir, rok, uplaceni_avans, napomena, racun_rucni, datum_prom) VALUES 
						(CURDATE(), :sifra_fir, :rokpl, 0, :napomena, :broj_rac_rucni, CURDATE())";
$obracun_ubacivanje_dost = $baza_pdo->prepare($upit_ubacivanje_dost);
$obracun_ubacivanje_dost->bindParam(':sifra_fir', $sifra_fir, PDO::PARAM_INT);
$obracun_ubacivanje_dost->bindParam(':napomena', $napomena, PDO::PARAM_STR);
$obracun_ubacivanje_dost->bindParam(':rokpl', $rok_placanja, PDO::PARAM_STR);
$obracun_ubacivanje_dost->bindParam(':broj_rac_rucni', $max_broj_r_r, PDO::PARAM_STR);
$obracun_ubacivanje_dost->execute() or die(print_r($obracun_ubacivanje_dost->errorInfo(), true));
$ubaciOK = $obracun_ubacivanje_dost->rowCount();
if ($ubaciOK) {
	$dost_id = $baza_pdo->lastInsertId(); //broj racuna
}


$upit_ubacivanje_izlaz = "INSERT INTO izlaz (br_dos, srob_dos, koli_dos, cena_d, rab_dos) VALUES
							(:brojfak, :sifrarob, :fak_kol, :cena_r, :fak_rab)";
$obracun_ubacivanje_izlaz = $baza_pdo->prepare($upit_ubacivanje_izlaz);

$upit_novo_stanje = "UPDATE roba SET stanje = ? WHERE sifra=?";
$obracun_novo_stanje = $baza_pdo->prepare($upit_novo_stanje);

$upit_profakroba = "SELECT * FROM profakrob WHERE br_profak=".$brojprofak;
foreach ($baza_pdo->query($upit_profakroba) as $red_profakrob) {
	$sifra_r=$red_profakrob['sifra_robe'];
	$kolicina_r=$red_profakrob['koli_profak'];
	$obracun_ubacivanje_izlaz->bindParam(':brojfak', $dost_id, PDO::PARAM_INT);
	$obracun_ubacivanje_izlaz->bindParam(':sifrarob', $sifra_r, PDO::PARAM_INT);
	$obracun_ubacivanje_izlaz->bindParam(':fak_kol', $kolicina_r, PDO::PARAM_STR);
	$obracun_ubacivanje_izlaz->bindParam(':cena_r', $red_profakrob['cena_profak'], PDO::PARAM_INT);
	$obracun_ubacivanje_izlaz->bindValue(':fak_rab', $red_profakrob['rab_dos'], PDO::PARAM_INT);
	$obracun_ubacivanje_izlaz->execute() or die(print_r($obracun_ubacivanje_izlaz->errorInfo(), true));

	$upit_stanje = "SELECT stanje FROM roba WHERE sifra=".$sifra_r;
	$obracun_stanje = $baza_pdo->query($upit_stanje);
	$red_stanje = $obracun_stanje->fetch();
	$stanje=$red_stanje['stanje']-$kolicina_r;

	$obracun_novo_stanje->execute(array($stanje, $sifra_r)) or die(print_r($obracun_novo_stanje->errorInfo(), true));

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
		<title>profaktura</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<p>Faktura je kreirana.</p>
			<div class="cf"></div>
			<form action="../fak/faktura.php" method="post">
				<input type="hidden" name="broj_fak_stampa" value="<?php echo $dost_id;?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>