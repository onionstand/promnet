<?php
require("../include/DbConnectionPDO.php");

//global $baza_pdo;
$upit_za_glknjiga="INSERT INTO glknjiga (sifradok, brdok, brkonta, datdok, duguje, potraz, opis, prokont)
					VALUES (:sifradok, :brdok, :brkonta, :datdok, :duguje, :potraz, :opis, :prokont)";
$stmt_za_glknjiga = $baza_pdo->prepare($upit_za_glknjiga);

function UbacivanjePodatakaUGlknjiga($sifradok, $brdok, $brkonta, $datdok, $duguje, $potraz, $opis, $prokont){
	global $stmt_za_glknjiga;
	$stmt_za_glknjiga->bindParam(':sifradok', $sifradok, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':brdok', $brdok, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':brkonta', $brkonta, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':datdok', $datdok, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':duguje', $duguje, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':potraz', $potraz, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':opis', $opis, PDO::PARAM_STR);
	$stmt_za_glknjiga->bindParam(':prokont', $prokont, PDO::PARAM_STR);

	$stmt_za_glknjiga->execute();

	//$OK_glknjiga = $stmt_za_glknjiga->rowCount();
	//if (!$OK_glknjiga) {
	//	$error = $stmt_za_glknjiga->errorInfo();
	//	if (isset($error[2])) {
	//		$error = $error[2];
	//	}
	//}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Glavna Knjiga</title>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<link rel="stylesheet" type="text/css" href="../include/tablesorter/style.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="../include/tablesorter/jquery.tablesorter.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#sorttabela").tablesorter();
			});
		</script>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			if (isset($_POST['obrisi'])) {
				$baza_pdo->query("TRUNCATE TABLE glknjiga");  
			}


			if (isset($_POST['osvezi_podatke'])) {

				$upit_sinhronizuj_kalk = 'SELECT * FROM kalk';
				foreach ($baza_pdo->query($upit_sinhronizuj_kalk) as $red_sinh) {
					$kalk_ruc=$red_sinh['pro_vre']-($red_sinh['nabav_vre']-$red_sinh['ukal_porez']);
					UbacivanjePodatakaUGlknjiga("1000", $red_sinh['broj_kalk'], "130", $red_sinh['datum'], $red_sinh['pro_vre'],"0", "Zaduzuje skladiste za robu", "433");
					UbacivanjePodatakaUGlknjiga("1001", $red_sinh['broj_kalk'], "270", $red_sinh['datum'], $red_sinh['ukal_porez'],"0", "Ukalkulisan PDV dobavljaca", "433");
					UbacivanjePodatakaUGlknjiga("1002", $red_sinh['broj_kalk'], "139", $red_sinh['datum'], "0",$kalk_ruc, "Ukalkulisana RUC", "130");
					UbacivanjePodatakaUGlknjiga("1100", $red_sinh['broj_kalk'], "433", $red_sinh['datum'], "0",$red_sinh['nabav_vre'], "Potrazivanje dobavljaca po fakturnoj ceni", "130");
				}

				$upit_sinhronizuj_dost = 'SELECT * FROM dosta';
				foreach ($baza_pdo->query($upit_sinhronizuj_dost) as $red_sinh_dost) {
					$prihod_od_prodaje = $red_sinh_dost['izzad']- $red_sinh_dost['ispor'];
					$nabavna_vrednost_robe = $red_sinh_dost['izzad']- $red_sinh_dost['ispor'] + $red_sinh_dost['odo_rab'];
					$iznos_ruca = $red_sinh_dost['bruc']-$red_sinh_dost['odo_rab'];
					$iznos_ruca_minus = $iznos_ruca * -1;
					UbacivanjePodatakaUGlknjiga("2000", $red_sinh_dost['broj_dost'], "201", $red_sinh_dost['datum_d'], $red_sinh_dost['izzad'],"0", "Zaduzenje kupca", "600");
					UbacivanjePodatakaUGlknjiga("2100", $red_sinh_dost['broj_dost'], "600", $red_sinh_dost['datum_d'], "0", $prihod_od_prodaje, "Prihod od prodaje", "202");
					UbacivanjePodatakaUGlknjiga("2101", $red_sinh_dost['broj_dost'], "470", $red_sinh_dost['datum_d'], "0", $red_sinh_dost['ispor'], "Poreska obaveza", "202");
					UbacivanjePodatakaUGlknjiga("2201", $red_sinh_dost['broj_dost'], "500", $red_sinh_dost['datum_d'], $nabavna_vrednost_robe, "0", "Utvrdjena nab. vrednost", "130");
					UbacivanjePodatakaUGlknjiga("2004", $red_sinh_dost['broj_dost'], "130", $red_sinh_dost['datum_d'], "0", $nabavna_vrednost_robe, "Razduzenje zalihe za prodatu robu", "500");
					UbacivanjePodatakaUGlknjiga("2004", $red_sinh_dost['broj_dost'], "500", $red_sinh_dost['datum_d'], $iznos_ruca_minus, "0", "Preos. RUCa", "139");
					UbacivanjePodatakaUGlknjiga("2002", $red_sinh_dost['broj_dost'], "139", $red_sinh_dost['datum_d'], $iznos_ruca, "0", "Prenos RUCa", "500");
					if ($red_sinh_dost['odo_rab'] > 0){
						UbacivanjePodatakaUGlknjiga("2004", $red_sinh_dost['broj_dost'], "500", $red_sinh_dost['datum_d'], $red_sinh_dost['odo_rab'], "0", "Odobren rabat", "500");
						UbacivanjePodatakaUGlknjiga("2004", $red_sinh_dost['broj_dost'], "139", $red_sinh_dost['datum_d'], ($red_sinh_dost['odo_rab'] * -1), "0", "Odobren rabat", "139");
					}
				}


				$upit_sinhronizuj_usluge = 'SELECT * FROM usluge';
				foreach ($baza_pdo->query($upit_sinhronizuj_usluge) as $red_sinh_usluge) {
					UbacivanjePodatakaUGlknjiga("4000", $red_sinh_usluge['br_usluge'], $red_sinh_usluge['kontous'], $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Zaduzenje za troskove", "0");
					if ($red_sinh_usluge['pdv'] > 0){
						UbacivanjePodatakaUGlknjiga("4000", $red_sinh_usluge['br_usluge'], $red_sinh_usluge['kontous'], $red_sinh_usluge['datum'], ($red_sinh_usluge['pdv']*-1), "0", "PDV", "0");
						UbacivanjePodatakaUGlknjiga("4000", $red_sinh_usluge['br_usluge'], "270", $red_sinh_usluge['datum'], $red_sinh_usluge['pdv'], "0", "Zaduzenje za PDV", "0");
					}
					UbacivanjePodatakaUGlknjiga("4000", $red_sinh_usluge['br_usluge'], "433", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveze dobavljacima", "0");
				}

			}
			?>
			<form method="post" action="">
				<input type="submit" name="obrisi" value="Obrisi stare podatke" class="dugme_zeleno">
			</form>
			<form method="post" action="">
				<input type="submit" name="osvezi_podatke" value="Sinhronizuj" class="dugme_zeleno">
			</form>
			<div class="cf"></div>
			<p>Tabela moze da se sortira</p>
			<table id="sorttabela" class="tablesorter">
				<thead>
					<tr>
						<th>ID</th>
						<th>Sifra dok.</th>
						<th>Br. dok.</th>
						<th>Br. konta</th>
						<th>Opis konta</th>
						<th>Datum</th>
						<th>Duguje</th>
						<th>Potrazuje</th>
						<th>Opis</th>
						<th>Prokont</th>
					</tr>
				</thead>
					<tbody>
				<?php $upit_prikaz_gl_k = 'SELECT * FROM glknjiga
							LEFT JOIN konto ON glknjiga.brkonta=konto.broj_kont
							ORDER BY datdok';
					foreach ($baza_pdo->query($upit_prikaz_gl_k) as $red_gl_k) {
						?>
						<tr>
							<td><?php echo $red_gl_k['id_glknjiga']; ?></td>
							<td><?php echo $red_gl_k['sifradok']; ?></td>
							<td><?php echo $red_gl_k['brdok']; ?></td>
							<td><?php echo $red_gl_k['brkonta']; ?></td>
							<td><?php echo $red_gl_k['naziv_kont']; ?></td>
							<td><?php echo $red_gl_k['datdok']; ?></td>
							<td><?php echo $red_gl_k['duguje']; ?></td>
							<td><?php echo $red_gl_k['potraz']; ?></td>
							<td><?php echo $red_gl_k['opis']; ?></td>
							<td><?php echo $red_gl_k['prokont']; ?></td>
						</tr>
						<?php
					}
				?>
				</tbody>
			</table>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
			<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
			<div class="cf"></div>
		</div>
	</body>
</html>
<?php
////
//$sql = "INSERT INTO solucion (rid, pid) VALUES ";
//$vls = array();
//foreach($questions as $value) {
//    $idanswer = ($answers[$value[pid]]) ? $answers[$value[pid]] : 0;
//    $idquestion = $value[pid];
//    $vls[] = " ( '$idanswer ', '$idquestion ')";
//
//}
//$sql .= implode(', ', $vls);
//$db->query($sql);
?>