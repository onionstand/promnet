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
				 /* $baza_pdo->query("TRUNCATE TABLE glknjiga"); */
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


				$upit_sinhronizuj_usluge = 'SELECT * FROM usluge WHERE kontous!=470
					AND kontous!=471
					AND kontous!=270
					AND kontous!=271';
				foreach ($baza_pdo->query($upit_sinhronizuj_usluge) as $red_sinh_usluge) {
					if ($red_sinh_usluge['kontous'] == 481){
						UbacivanjePodatakaUGlknjiga("4040", $red_sinh_usluge['br_usluge'], "721", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "porez iz rezultata", "481");
						UbacivanjePodatakaUGlknjiga("4040", $red_sinh_usluge['br_usluge'], "481", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "porez iz rezultata", "721");
					}
					else{
						if ($red_sinh_usluge['kontous'] == 450){
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "450", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza za neto zaradu", "520");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "520", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza za neto zaradu", "450");
						}
						elseif ($red_sinh_usluge['kontous'] == 451) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "451", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza poreza na zaradu", "520");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "520", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza poreza na zaradu", "451");
						}
						elseif ($red_sinh_usluge['kontous'] == 452) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "452", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza pio na teret zaposlenog", "520");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "520", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza pio na teret zaposlenog", "452");
						}
						elseif ($red_sinh_usluge['kontous'] == 453) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "453", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza zdravstvenog osig. na ter. zaposl.", "520");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "520", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza zdravstvenog osig. na ter. zaposl.", "453");
						}
						elseif ($red_sinh_usluge['kontous'] == 454) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "454", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza za zaposlj. ter.zaposlenog", "520");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "520", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza za zaposlj. ter.zaposlenog", "454");
						}
						elseif ($red_sinh_usluge['kontous'] == 455) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "455", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveze PIO na teret preduzeca", "522");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "522", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveze PIO na teret preduzeca", "455");
						}
						elseif ($red_sinh_usluge['kontous'] == 456) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "456", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza zdravstvenog osig. na ter. preduzeca", "522");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "522", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza zdravstvenog osig. na ter. preduzeca", "456");
						}
						elseif ($red_sinh_usluge['kontous'] == 457) {
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "457", $red_sinh_usluge['datum'], "0", $red_sinh_usluge['iznosus'], "Obaveza za zaposlj. ter. preduzeca", "522");
							UbacivanjePodatakaUGlknjiga("4050", $red_sinh_usluge['br_usluge'], "522", $red_sinh_usluge['datum'], $red_sinh_usluge['iznosus'], "0", "Obaveza za zaposlj. ter. preduzeca", "457");
						}

						else{

							
							UbacivanjePodatakaUGlknjiga(
								"4000",
								$red_sinh_usluge['br_usluge'],
								$red_sinh_usluge['kontous'],
								$red_sinh_usluge['datum'],
								$red_sinh_usluge['iznosus'],
								"0",
								"Zaduzenje za troskove",
								"433"
							);
							UbacivanjePodatakaUGlknjiga(
								"4030",								//sifradok
								$red_sinh_usluge['br_usluge'],		//brdok
								"433",								//brkonta
								$red_sinh_usluge['datum'],			//datdok
								"0",								//duguje
								$red_sinh_usluge['iznosus'],		//potraz
								"Obaveze dobavljacima",				//opis
								$red_sinh_usluge['kontous']			//prokont
							);	

							if ($red_sinh_usluge['pdv'] > 0){
								UbacivanjePodatakaUGlknjiga(
									"4010",
									$red_sinh_usluge['br_usluge'],
									$red_sinh_usluge['kontous'],
									$red_sinh_usluge['datum'],
									($red_sinh_usluge['pdv']*-1),
									"0",
									"umanjenje za PDV",
									$red_sinh_usluge['kontous']
								);
								UbacivanjePodatakaUGlknjiga(
									"4020",
									$red_sinh_usluge['br_usluge'],
									"270",
									$red_sinh_usluge['datum'],
									$red_sinh_usluge['pdv'],
									"0",
									"Prenos PDV",
									$red_sinh_usluge['kontous']
								);
							}
							
						}
					}
				}

				
				//$upit_sinhronizuj_blagajnu = 'SELECT * FROM blagajna LEFT JOIN konto ON blagajna.br_konta=konto.broj_kont';
				$upit_sinhronizuj_blagajnu = 'SELECT * FROM blagajna';
				foreach ($baza_pdo->query($upit_sinhronizuj_blagajnu) as $red_sinh_blagajnu) {
					if ($red_sinh_blagajnu['blagulaz'] != 0){
						//UbacivanjePodatakaUGlknjiga("5020", $red_sinh_blagajnu['br_blag'], "243", $red_sinh_blagajnu['datum'], $red_sinh_blagajnu['blagulaz'], $red_sinh_blagajnu['blagizn'], $red_sinh_blagajnu['opis_troska'], "0");
						//UbacivanjePodatakaUGlknjiga("5020", $red_sinh_blagajnu['br_blag'], "242", $red_sinh_blagajnu['datum'], $red_sinh_blagajnu['blagizn'], $red_sinh_blagajnu['blagulaz'], $red_sinh_blagajnu['opis_troska'], "0");
					}
					if ($red_sinh_blagajnu['br_konta'] > 500){
						if ($red_sinh_blagajnu['pdv_izn'] > 0){
							UbacivanjePodatakaUGlknjiga(
								"5010",
								$red_sinh_blagajnu['br_blag'],
								$red_sinh_blagajnu['br_konta'],
								$red_sinh_blagajnu['datum'],
								($red_sinh_blagajnu['blagizn']-$red_sinh_blagajnu['pdv_izn']),
								$red_sinh_blagajnu['blagulaz'],
								"Knjizenje troskova sa PDV",
								"242"
							);
							UbacivanjePodatakaUGlknjiga(
								"5010",
								$red_sinh_blagajnu['br_blag'],
								$red_sinh_blagajnu['br_konta'],
								$red_sinh_blagajnu['datum'],
								($red_sinh_blagajnu['pdv_izn']*-1),
								$red_sinh_blagajnu['blagulaz'],
								"Knjizenje troskova sa PDV",
								"242"
							);
							UbacivanjePodatakaUGlknjiga(
								"5010",
								$red_sinh_blagajnu['br_blag'],
								"270",
								$red_sinh_blagajnu['datum'],
								$red_sinh_blagajnu['pdv_izn'],
								$red_sinh_blagajnu['blagulaz'],
								"Prenos PDV",
								$red_sinh_blagajnu['br_konta']
							);
							UbacivanjePodatakaUGlknjiga(
								"5010",
								$red_sinh_blagajnu['br_blag'],
								"242",
								$red_sinh_blagajnu['datum'],
								$red_sinh_blagajnu['blagulaz'],
								$red_sinh_blagajnu['blagizn'],
								$red_sinh_blagajnu['br_konta']. " - Troskovi placeni gotovinom",
								$red_sinh_blagajnu['br_konta']
							);
						}
						else {
							//function UbacivanjePodatakaUGlknjiga($sifradok, $brdok, $brkonta, $datdok, $duguje, $potraz, $opis, $prokont)

							//id_glknjiga 	sifradok 	brdok 	brkonta 	datdok 	duguje 	potraz 	opis 	prokont
							// br_blag 	br_konta 	opis_troska 	blagulaz 	blagizn 	pdv_izn 	datum 	brupl 	napomena
							
							UbacivanjePodatakaUGlknjiga(
								"5000",								//sifradok
								$red_sinh_blagajnu['br_blag'],		//brdok
								$red_sinh_blagajnu['br_konta'],		//brkonta
								$red_sinh_blagajnu['datum'],		//datdok
								$red_sinh_blagajnu['blagizn'],		//duguje
								$red_sinh_blagajnu['blagulaz'],		//potraz
								"Troskovi placeni gotovinom",		//opis
								"242"								//prokont
							);
							UbacivanjePodatakaUGlknjiga(
								"5000",								//sifradok
								$red_sinh_blagajnu['br_blag'],		//brdok
								"242",								//brkonta
								$red_sinh_blagajnu['datum'],		//datdok
								$red_sinh_blagajnu['blagulaz'],		//duguje
								$red_sinh_blagajnu['blagizn'],		//potraz
								"Troskovi placeni gotovinom",		//opis
								$red_sinh_blagajnu['br_konta']		//prokont
							);
							

							//UbacivanjePodatakaUGlknjiga("5000", $red_sinh_blagajnu['br_blag'], "551", $red_sinh_blagajnu['datum'], $red_sinh_blagajnu['blagulaz'], $red_sinh_blagajnu['blagizn'], "BLAGAJNA", "551");
							//UbacivanjePodatakaUGlknjiga("5000", $red_sinh_blagajnu['br_blag'], "242", $red_sinh_blagajnu['datum'], $red_sinh_blagajnu['blagizn'], $red_sinh_blagajnu['blagulaz'], "TROSKOVI REPREZENTACIJE", $red_sinh_blagajnu['br_konta']);
						}
					}
				}
				

				
				$upit_bankaupis_sifra_par_6 = 'SELECT * FROM bankaupis WHERE sifra_par=6';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_6) as $red_bankaupis_sifra_par_6) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_6['br_izvoda'], "451", $red_bankaupis_sifra_par_6['datum_izv'], $red_bankaupis_sifra_par_6['izlaz_novca'], "0", "Porez na zarade", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_6['br_izvoda'], "241", $red_bankaupis_sifra_par_6['datum_izv'], "0", $red_bankaupis_sifra_par_6['izlaz_novca'], "Porez na zarade", "451");
				}

				$upit_bankaupis_sifra_par_7 = 'SELECT * FROM bankaupis WHERE sifra_par=7';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_7) as $red_bankaupis_sifra_par_7) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_7['br_izvoda'], "452", $red_bankaupis_sifra_par_7['datum_izv'], $red_bankaupis_sifra_par_7['izlaz_novca'], "0", "penzisko na teret radnika", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_7['br_izvoda'], "241", $red_bankaupis_sifra_par_7['datum_izv'], "0", $red_bankaupis_sifra_par_7['izlaz_novca'], "penzisko na teret radnika", "452");
				}

				$upit_bankaupis_sifra_par_9 = 'SELECT * FROM bankaupis WHERE sifra_par=9';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_9) as $red_bankaupis_sifra_par_9) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_9['br_izvoda'], "453", $red_bankaupis_sifra_par_9['datum_izv'], $red_bankaupis_sifra_par_9['izlaz_novca'], "0", "zdravstveno osiguranje na teret radnika", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_9['br_izvoda'], "241", $red_bankaupis_sifra_par_9['datum_izv'], "0", $red_bankaupis_sifra_par_9['izlaz_novca'], "zdravstveno osiguranje na teret radnika", "453");
				}

				$upit_bankaupis_sifra_par_11 = 'SELECT * FROM bankaupis WHERE sifra_par=11';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_11) as $red_bankaupis_sifra_par_11) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_11['br_izvoda'], "454", $red_bankaupis_sifra_par_11['datum_izv'], $red_bankaupis_sifra_par_11['izlaz_novca'], "0", "zaposljavanje na teret radnika", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_11['br_izvoda'], "241", $red_bankaupis_sifra_par_11['datum_izv'], "0", $red_bankaupis_sifra_par_11['izlaz_novca'], "zaposljavanje na teret radnika", "454");
				}

				$upit_bankaupis_sifra_par_8 = 'SELECT * FROM bankaupis WHERE sifra_par=8';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_8) as $red_bankaupis_sifra_par_8) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_8['br_izvoda'], "455", $red_bankaupis_sifra_par_8['datum_izv'], $red_bankaupis_sifra_par_8['izlaz_novca'], "0", "penziono na teret preduzeca", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_8['br_izvoda'], "241", $red_bankaupis_sifra_par_8['datum_izv'], "0", $red_bankaupis_sifra_par_8['izlaz_novca'], "penziono na teret preduzeca", "455");
				}

				$upit_bankaupis_sifra_par_10 = 'SELECT * FROM bankaupis WHERE sifra_par=10';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_10) as $red_bankaupis_sifra_par_10) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_10['br_izvoda'], "456", $red_bankaupis_sifra_par_10['datum_izv'], $red_bankaupis_sifra_par_10['izlaz_novca'], "0", "zdravstveno na teret preduzeca", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_10['br_izvoda'], "241", $red_bankaupis_sifra_par_10['datum_izv'], "0", $red_bankaupis_sifra_par_10['izlaz_novca'], "zdravstveno na teret preduzeca", "456");
				}

				$upit_bankaupis_sifra_par_11 = 'SELECT * FROM bankaupis WHERE sifra_par=11';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_11) as $red_bankaupis_sifra_par_11) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_11['br_izvoda'], "457", $red_bankaupis_sifra_par_11['datum_izv'], $red_bankaupis_sifra_par_11['izlaz_novca'], "0", "zaposljavanje na teret preduzeca", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_11['br_izvoda'], "241", $red_bankaupis_sifra_par_11['datum_izv'], "0", $red_bankaupis_sifra_par_11['izlaz_novca'], "zaposljavanje na teret preduzeca", "457");
				}

				$upit_bankaupis_sifra_par_14 = 'SELECT * FROM bankaupis WHERE sifra_par=14';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_14) as $red_bankaupis_sifra_par_14) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_14['br_izvoda'], "470", $red_bankaupis_sifra_par_14['datum_izv'], $red_bankaupis_sifra_par_14['izlaz_novca'], "0", "uplacen pdv", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_14['br_izvoda'], "241", $red_bankaupis_sifra_par_14['datum_izv'], "0", $red_bankaupis_sifra_par_14['izlaz_novca'], "uplacen pdv", "470");
				}

				$upit_bankaupis_sifra_par_18 = 'SELECT * FROM bankaupis WHERE sifra_par=18';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_18) as $red_bankaupis_sifra_par_18) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_18['br_izvoda'], "450", $red_bankaupis_sifra_par_18['datum_izv'], $red_bankaupis_sifra_par_18['izlaz_novca'], "0", "uplacen pdv", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_18['br_izvoda'], "241", $red_bankaupis_sifra_par_18['datum_izv'], "0", $red_bankaupis_sifra_par_18['izlaz_novca'], "uplacen pdv", "450");
				}

				$upit_bankaupis_sifra_par_24 = 'SELECT * FROM bankaupis WHERE sifra_par=24';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_24) as $red_bankaupis_sifra_par_24) {
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_24['br_izvoda'], "242", $red_bankaupis_sifra_par_24['datum_izv'], $red_bankaupis_sifra_par_24['izlaz_novca'], "0", "uplacen pdv podignuta gotovina", "241");
					UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par_24['br_izvoda'], "241", $red_bankaupis_sifra_par_24['datum_izv'], "0", $red_bankaupis_sifra_par_24['izlaz_novca'], "uplacen pdv podignuta gotovina", "242");
				}

				$upit_bankaupis_sifra_par_24 = 'SELECT * FROM bankaupis WHERE sifra_par=49';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par_24) as $red_bankaupis_sifra_par_24) {
					UbacivanjePodatakaUGlknjiga("6030", $red_bankaupis_sifra_par_24['br_izvoda'], "481", $red_bankaupis_sifra_par_24['datum_izv'], $red_bankaupis_sifra_par_24['izlaz_novca'], "0", "uplata poreza na prihod", "241");
					UbacivanjePodatakaUGlknjiga("6030", $red_bankaupis_sifra_par_24['br_izvoda'], "241", $red_bankaupis_sifra_par_24['datum_izv'], "0", $red_bankaupis_sifra_par_24['izlaz_novca'], "uplata poreza na prihod", "481");
				}
				

				$upit_bankaupis_sifra_par = 'SELECT * FROM bankaupis
					WHERE sifra_par!=24
					AND sifra_par!=14
					AND sifra_par!=11
					AND sifra_par!=10
					AND sifra_par!=8
					AND sifra_par!=11
					AND sifra_par!=9
					AND sifra_par!=7
					AND sifra_par!=6
					AND sifra_par!=18
					AND sifra_par!=49
					';
				foreach ($baza_pdo->query($upit_bankaupis_sifra_par) as $red_bankaupis_sifra_par) {
					if ($red_bankaupis_sifra_par['izlaz_novca'] == 0){
						UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par['br_izvoda'], "201", $red_bankaupis_sifra_par['datum_izv'], "0", $red_bankaupis_sifra_par['ulaz_novca'], "uplata kupca", "241");
						UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par['br_izvoda'], "241", $red_bankaupis_sifra_par['datum_izv'], $red_bankaupis_sifra_par['ulaz_novca'], "0", "uplata kupca", "201");
					}
					if ($red_bankaupis_sifra_par['ulaz_novca'] == 0){
						if ($red_bankaupis_sifra_par['svrha']=="GOTOVINA") {
							// UbacivanjePodatakaUGlknjiga($sifradok, $brdok, $brkonta, $datdok, $duguje, $potraz, $opis, $prokont)
							UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par['br_izvoda'], "241", $red_bankaupis_sifra_par['datum_izv'], "0", $red_bankaupis_sifra_par['izlaz_novca'], "gotovina", "242");
							UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par['br_izvoda'], "242", $red_bankaupis_sifra_par['datum_izv'], $red_bankaupis_sifra_par['izlaz_novca'], "0", "gotovina", "241");
						}
						else{

							UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par['br_izvoda'], "433", $red_bankaupis_sifra_par['datum_izv'], $red_bankaupis_sifra_par['izlaz_novca'], "0", "placena roba dobavljacu", "241");
							UbacivanjePodatakaUGlknjiga("6010", $red_bankaupis_sifra_par['br_izvoda'], "241", $red_bankaupis_sifra_par['datum_izv'], "0", $red_bankaupis_sifra_par['izlaz_novca'], "placena roba dobavljacu", "433");
						}
					}
				}
			
				$upit_bankaupis_nivelacija = 'SELECT * FROM niv_robe LEFT JOIN nivel ON niv_robe.br_niv=nivel.broj_niv';
				foreach ($baza_pdo->query($upit_bankaupis_nivelacija) as $red_bankaupis_nivelacija) {
					UbacivanjePodatakaUGlknjiga("3000", $red_bankaupis_nivelacija['br_niv'], "130", $red_bankaupis_nivelacija['datum_niv'], $red_bankaupis_nivelacija['iznos_niv'], "0", "nivelacija", "139");
					UbacivanjePodatakaUGlknjiga("3000", $red_bankaupis_nivelacija['br_niv'], "139", $red_bankaupis_nivelacija['datum_niv'], "0", $red_bankaupis_nivelacija['iznos_niv'], "nivelacija", "130");
				}
			}

			?>
			<h2>Pre sinhronizacije obrisi stare podatke!</h2>
			<form method="post" action="">
				<input type="submit" name="obrisi" value="Obrisi stare podatke" class="dugme_zeleno">
			</form>
			<form method="post" action="">
				<input type="submit" name="osvezi_podatke" value="Sinhronizuj" class="dugme_zeleno">
			</form>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
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