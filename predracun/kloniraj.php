<?php
require("../include/DbConnectionPDO.php");

$upit_profakrob = 'SELECT * FROM profakrob WHERE br_profak = :broj_profak';
$stmt = $baza_pdo->prepare($upit_profakrob);
$stmt->bindParam(':broj_profak', $_POST['broj_profak'], PDO::PARAM_STR);
$stmt->bindColumn('naziv_robe', $naziv_robe);
$stmt->bindColumn('sifra_robe', $sifra_robe);
$stmt->bindColumn('koli_profak', $koli_profak);
$stmt->bindColumn('jed_mere', $jed_mere);
$stmt->bindColumn('cena_profak', $cena_profak);
$stmt->bindColumn('rab_dos', $rab_dos);
$stmt->bindColumn('porez', $porez);
$stmt->execute();

$ima_stavki = $stmt->rowCount();
if ($ima_stavki) {
    $upit_profak = 'SELECT * FROM profak WHERE broj_prof = :broj_prof';
    $stmt_profak = $baza_pdo->prepare($upit_profak);
    $stmt_profak->bindParam(':broj_prof', $_POST['broj_profak'], PDO::PARAM_STR);
    $stmt_profak->bindColumn('datum_prof', $datum_prof);
    $stmt_profak->bindColumn('sifra_fir', $sifra_fir);
    $stmt_profak->bindColumn('rok', $rok);
    $stmt_profak->bindColumn('izzad', $izzad);
    $stmt_profak->bindColumn('ispor', $ispor);
    $stmt_profak->bindColumn('odo_rab', $odo_rab);
    $stmt_profak->bindColumn('bruc', $bruc);
    $stmt_profak->bindColumn('napomena', $napomena);
    $stmt_profak->execute();
    $stmt_profak->fetch();

    $upit_brofak_rucni = "SELECT MAX(brofak_rucni) AS brofak_rucni_max FROM profak";
    $result_brofak_rucni = $baza_pdo->query($upit_brofak_rucni);
    $red_brofak_rucni = $result_brofak_rucni->fetch();
    $poslednji_rucni_racun=$red_brofak_rucni['brofak_rucni_max']+1;
    
    $upit_profak_ins = 'INSERT INTO profak (datum_prof, sifra_fir, rok, izzad, ispor, odo_rab, bruc, napomena, brofak_rucni)
			  VALUES (:datum_prof, :sifra_fir, :rok, :izzad, :ispor, :odo_rab, :bruc, :napomena, :brofak_rucni)';
	$stmt_profak_ins = $baza_pdo->prepare($upit_profak_ins);
	$stmt_profak_ins->bindParam(':datum_prof', $datum_prof, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':sifra_fir', $sifra_fir, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':rok', $rok, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':izzad', $izzad, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':ispor', $ispor, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':odo_rab', $odo_rab, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':bruc', $bruc, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':napomena', $napomena, PDO::PARAM_STR);
    $stmt_profak_ins->bindParam(':brofak_rucni', $poslednji_rucni_racun, PDO::PARAM_INT);
	$stmt_profak_ins->execute();
	
	$broj_nove_prof = $baza_pdo->lastInsertId();
    
    
    
    $upit_profakrob_ins = 'INSERT INTO profakrob (br_profak, naziv_robe, sifra_robe, koli_profak, jed_mere, cena_profak, rab_dos, porez)
        VALUES (:br_profak, :naziv_robe, :sifra_robe, :koli_profak, :jed_mere, :cena_profak, :rab_dos, :porez)';
	$stmt_profakrob_ins = $baza_pdo->prepare($upit_profakrob_ins);
    
    while ($stmt->fetch()) {
        $stmt_profakrob_ins->bindParam(':br_profak', $broj_nove_prof, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':naziv_robe', $naziv_robe, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':sifra_robe', $sifra_robe, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':koli_profak', $koli_profak, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':jed_mere', $jed_mere, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':cena_profak', $cena_profak, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':rab_dos', $rab_dos, PDO::PARAM_STR);
        $stmt_profakrob_ins->bindParam(':porez', $porez, PDO::PARAM_STR);
        $stmt_profakrob_ins->execute();
    }
    $alarm="Profaktura je klonirana pod brojem ".$broj_nove_prof;
} else {
    $alarm="Nema stavki!";
}


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Kloniranje profakture</title>
</head>
<body>
	<div class="nosac_glavni_400">
	        <h2><?php echo $alarm;?></h2>
			<a href="stara_profak.php" class="dugme_crveno_92plus4">Povratak</a>
			<div class="cf"></div>
	</div>
</body>
</html>