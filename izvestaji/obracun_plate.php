<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Obracun Plate</title>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">

		<script src="../include/jquery/jquery-1.6.2.min.js" type="text/javascript"></script>

		<script src="../include/jquery/jquery.ui.core.js"></script>
		<script src="../include/jquery/jquery.ui.widget.min.js"></script>
		<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
		<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>

		<script>
			$(function() {
				$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			});
		</script>
		<script>
			function saberi_ukupno_na_teret_radnika(){
				$("#ukupno_na_teret_radnika").val(
					Number($("#poreska_stopa").val()) +
					Number($("#pio_na_teret_radnika_stopa").val()) +
					Number($("#zdravstveno_teret_radnika_stopa").val()) +
					Number($("#zaposlavanje_teret_radnika_stopa").val())
				);
			}

			function saberi_ukupno_na_teret_preduzeca(){
				$("#ukupno_na_teret_preduzeca").val(
					Number($("#pio_na_teret_preduzeca_stopa").val()) +
					Number($("#zdravstveno_teret_preduzeca_stopa").val()) +
					Number($("#zaposlavanje_teret_preduzeca_stopa").val())
				);
			}

			function saberi_bruto(){
				$("#bruto_zarada").val((
					
					(
						Number($("#neto_zarada").val()) -
						(
							(Number($("#iznos_poreskog_umanjenja").val())) / 100 * 
							Number($("#poreska_stopa").val())
						)
					)/
					(
						(100-(
							Number($("#poreska_stopa").val())+
							Number($("#pio_na_teret_radnika_stopa").val())+
							Number($("#zdravstveno_teret_radnika_stopa").val())+
							Number($("#zaposlavanje_teret_radnika_stopa").val())
						)
						)/100
					)

				).toFixed(2));
			}

			function poreskoUmanjenje(){
				$("#poresko_umanjenje").val(
					Number($("#iznos_poreskog_umanjenja").val()).toFixed(2)
				);
			}

			function osnovicaZaObracunPoreza(){
				$("#osnovica_za_obracun_poreza").val(
					(
					(Number($("#bruto_zarada").val()))-
					(Number($("#iznos_poreskog_umanjenja").val()))
					)
					.toFixed(2)
				);
			}

			function porezNaPrimanjaZaUplatu(){
				$("#porez_na_primanja_za_uplatu").val(
					(
					(Number($("#osnovica_za_obracun_poreza").val()))/100*
					(Number($("#poreska_stopa").val()))
					)
					.toFixed(2)
				);
			}

			function pioNaTeretRadnikaZaUplatu(){
				$("#pio_na_teret_radnika_za_uplatu").val(
					(
					(Number($("#bruto_zarada").val()))/100*
					(Number($("#pio_na_teret_radnika_stopa").val()))
					)
					.toFixed(2)
				);
			}


			function zdravstvenoNaTeretRadnikaZaUplatu(){
				$("#zdravstveno_na_teret_radnika_za_uplatu").val(
					(
					(Number($("#bruto_zarada").val()))/100*
					(Number($("#zdravstveno_teret_radnika_stopa").val()))
					)
					.toFixed(2)
				);
			}

			function zaposljavanjeNaTeretRadnikaZaUplatu(){
				$("#zaposljavanje_na_teret_radnika_za_uplatu").val(
					(
					(Number($("#bruto_zarada").val()))/100*
					(Number($("#zaposlavanje_teret_radnika_stopa").val()))
					)
					.toFixed(2)
				);
			}

			function pioNaTeretPreduzecaZaUplatu(){
				$("#pio_na_teret_preduzeca_za_uplatu").val(
					(
					(Number($("#bruto_zarada").val()))/100*
					(Number($("#pio_na_teret_preduzeca_stopa").val()))
					)
					.toFixed(2)
				);
			}

			function zdravstvenoNaTeretPreduzecaZaUplatu(){
				$("#zdravstveno_na_teret_preduzeca_za_uplatu").val(
					(
					(Number($("#bruto_zarada").val()))/100*
					(Number($("#zdravstveno_teret_preduzeca_stopa").val()))
					)
					.toFixed(2)
				);
			}

			function zaposljavanjeNaTeretPreduzecaZaUplatu(){
				$("#zaposljavanje_na_teret_preduzeca_za_uplatu").val(
					(
					(Number($("#bruto_zarada").val()))/100*
					(Number($("#zaposlavanje_teret_preduzeca_stopa").val()))
					)
					.toFixed(2)
				);
			}


			function ukupnoPorezaIDoprinosaZaUplatu(){
				$("#ukupno_poreza_i_doprinosa_za_uplatu").val(
					(
					(Number($("#porez_na_primanja_za_uplatu").val()))+
					(Number($("#pio_na_teret_radnika_za_uplatu").val()))+
					(Number($("#zdravstveno_na_teret_radnika_za_uplatu").val()))+
					(Number($("#zaposljavanje_na_teret_radnika_za_uplatu").val()))+
					(Number($("#pio_na_teret_preduzeca_za_uplatu").val()))+
					(Number($("#zdravstveno_na_teret_preduzeca_za_uplatu").val()))+
					(Number($("#zaposljavanje_na_teret_preduzeca_za_uplatu").val()))
					)
					.toFixed(2)
				);
			}



			$(document).ready(function(){
				$( "#ukupno_na_teret_radnika" ).ready(saberi_ukupno_na_teret_radnika);
				$( "#ukupno_na_teret_preduzeca" ).ready(saberi_ukupno_na_teret_preduzeca);
				$("#pio_na_teret_preduzeca_stopa,#zdravstveno_teret_preduzeca_stopa,#zaposlavanje_teret_preduzeca_stopa")
				.keyup(saberi_ukupno_na_teret_preduzeca);
				$( "#bruto_zarada" ).ready(saberi_bruto);
				$( "#poresko_umanjenje" ).ready(poreskoUmanjenje);
				$("#iznos_poreskog_umanjenja").keyup(poreskoUmanjenje);
				$( "#osnovica_za_obracun_poreza" ).ready(osnovicaZaObracunPoreza);
				$( "#porez_na_primanja_za_uplatu" ).ready(porezNaPrimanjaZaUplatu);
				$( "#pio_na_teret_radnika_za_uplatu" ).ready(pioNaTeretRadnikaZaUplatu);
				$( "#zdravstveno_na_teret_radnika_za_uplatu" ).ready(zdravstvenoNaTeretRadnikaZaUplatu);
				$( "#zaposljavanje_na_teret_radnika_za_uplatu" ).ready(zaposljavanjeNaTeretRadnikaZaUplatu);
				$( "#pio_na_teret_preduzeca_za_uplatu" ).ready(pioNaTeretPreduzecaZaUplatu);
				$( "#zdravstveno_na_teret_preduzeca_za_uplatu" ).ready(zdravstvenoNaTeretPreduzecaZaUplatu);
				$( "#zaposljavanje_na_teret_preduzeca_za_uplatu" ).ready(zaposljavanjeNaTeretPreduzecaZaUplatu);
				$( "#ukupno_poreza_i_doprinosa_za_uplatu" ).ready(ukupnoPorezaIDoprinosaZaUplatu);
				$("#poreska_stopa,#pio_na_teret_radnika_stopa,#zdravstveno_teret_radnika_stopa,#zaposlavanje_teret_radnika_stopa,#iznos_poreskog_umanjenja,#neto_zarada")
					.keyup(saberi_ukupno_na_teret_radnika)
					.keyup(saberi_bruto)
					.keyup(zaposljavanjeNaTeretRadnikaZaUplatu)
					.keyup(pioNaTeretPreduzecaZaUplatu)
					.keyup(osnovicaZaObracunPoreza)
					.keyup(porezNaPrimanjaZaUplatu)
					.keyup(pioNaTeretRadnikaZaUplatu)
					.keyup(zdravstvenoNaTeretRadnikaZaUplatu)
					.keyup(zdravstvenoNaTeretPreduzecaZaUplatu)
					.keyup(zaposljavanjeNaTeretPreduzecaZaUplatu)
					.keyup(ukupnoPorezaIDoprinosaZaUplatu);

			});
		</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			$plata_json = file_get_contents("plata.json");
			$plata = json_decode($plata_json,true);
	 		if (isset ($_POST['redni_broj'])&& ($_POST['prezime'])&& ($_POST['neto_zarada'])){
	 			$plata["redniBroj"] = $_POST['redni_broj'];
	 			$plata["vrstaIdentifikacije"] = $_POST['vrsta_ident_primaoca'];
	 			$plata["jmbg"] = $_POST['jmbg'];
	 			$plata["prezime"] = $_POST['prezime'];
	 			$plata["ime"] = $_POST['ime'];
	 			$plata["sifraOpstinePrebivalista"] = $_POST['sifra_opstine_preb'];
	 			$plata["sifraVrstePrihoda"] = $_POST['sifra_vrste_prihoda'];
	 			$plata["brojKalendarskihDana"] = $_POST['broj_kalendarskih_dana'];
	 			$plata["brojRadnihSati"] = $_POST['broj_radnih_sati'];
	 			$plata["iznosPoreskogUmanjenja"] = $_POST['iznos_poreskog_umanjenja'];
	 			$plata["poreskaStopa"] = $_POST['poreska_stopa'];
	 			$plata["pioNaTeretRadnikaStopa"] = $_POST['pio_na_teret_radnika_stopa'];
	 			$plata["zdravstvenoNaTeretRadnikaStopa"] = $_POST['zdravstveno_teret_radnika_stopa'];
	 			$plata["zaposlavanjeNaTeretRadnikaStopa"] = $_POST['zaposlavanje_teret_radnika_stopa'];
	 			$plata["pioNaTeretPreduzecaStopa"] = $_POST['pio_na_teret_preduzeca_stopa'];
	 			$plata["zdravstvenoNaTeretPreduzecaStopa"] = $_POST['zdravstveno_teret_preduzeca_stopa'];
	 			$plata["zaposlavanjeNaTeretPreduzecaStopa"] = $_POST['zaposlavanje_teret_preduzeca_stopa'];
	 			$plata["netoZarada"] = $_POST['neto_zarada'];
	 		
	 			$fajl = fopen("plata.json", 'w')or die("Error opening output file");
	 			fwrite($fajl, json_encode($plata,JSON_UNESCAPED_UNICODE));
	 			fclose($fajl);

	 			require("../include/DbConnectionPDO.php");
	 			$datum=$_POST['datum'];
				$danstr=strtotime( $datum );
				$datum_za_bazu=date("Y-m-d",$danstr);

				
				function UbacivanjeDoprinosaUUsluge( $sifusluge, $br_dok_us, $opis_usluge, $konto_usluge, $iznosusluge){
					global $baza_pdo;
					global $datum_za_bazu;
					$upit_za_usluge="INSERT INTO usluge (sifusluge, br_dok_us, opis, datum, kontous, iznosus)
						VALUES (:sifusluge, :br_dok_us, :opis, :datum, :kontous, :iznosus)";
					$stmt_za_usluge = $baza_pdo->prepare($upit_za_usluge);
					$stmt_za_usluge->bindParam(':sifusluge', $sifusluge, PDO::PARAM_STR);
					$stmt_za_usluge->bindParam(':br_dok_us', $br_dok_us, PDO::PARAM_STR);
					$stmt_za_usluge->bindParam(':opis', $opis_usluge, PDO::PARAM_STR);
					$stmt_za_usluge->bindParam(':datum', $datum_za_bazu, PDO::PARAM_STR);
					$stmt_za_usluge->bindParam(':kontous', $konto_usluge, PDO::PARAM_STR);
					$stmt_za_usluge->bindParam(':iznosus', $iznosusluge, PDO::PARAM_STR);
					//$stmt_za_usluge->bindParam(':pdv', '0', PDO::PARAM_STR);
					$stmt_za_usluge->execute();
					$usluge_id = $baza_pdo->lastInsertId();
					$OK_usluga = $stmt_za_usluge->rowCount();
					if (!$OK_usluga) {
						$error = $stmt_za_usluge->errorInfo();
						if (isset($error[2])) {
					  		$error = $error[2];
						}
					}
					return $usluge_id;

				}

				$UbacivanjeDoprinosaUUsluge_id="";
				$UbacivanjeDoprinosaUUsluge_id.=UbacivanjeDoprinosaUUsluge("6", "38041778", "POREZ NA ZARADE", "451", number_format($_POST['porez_na_primanja_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("6", "38041778", "POREZ NA ZARADE", "451", number_format(($_POST['porez_na_primanja_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("7", "38041778", "PENZIONO OSIGURANJE TERET RADNIKA", "452", number_format($_POST['pio_na_teret_radnika_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("7", "38041778", "PENZIONO OSIGURANJE TERET RADNIKA", "452", number_format(($_POST['pio_na_teret_radnika_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("9", "38041778", "ZDRAVSTVENO OSIGURANJE RADNIKA", "453", number_format($_POST['zdravstveno_na_teret_radnika_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("9", "38041778", "ZDRAVSTVENO OSIGURANJE RADNIKA", "453", number_format(($_POST['zdravstveno_na_teret_radnika_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("11", "38041778", "ZAPOSLJAVANJE NA TERET RADNIKA", "454", number_format($_POST['zaposljavanje_na_teret_radnika_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("11", "38041778", "ZAPOSLJAVANJE NA TERET RADNIKA", "454", number_format(($_POST['zaposljavanje_na_teret_radnika_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("8", "38041778", "PENZIONO NA TERET PREDUZECA", "455", number_format($_POST['pio_na_teret_preduzeca_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("8", "38041778", "PENZIONO NA TERET PREDUZECA", "455", number_format(($_POST['pio_na_teret_preduzeca_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("10", "38041778", "ZDRAVSTVENO OSIGURANJE TERET PREDUZECA", "456", number_format($_POST['zdravstveno_na_teret_preduzeca_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("10", "38041778", "ZDRAVSTVENO OSIGURANJE TERET PREDUZECA", "456", number_format(($_POST['zdravstveno_na_teret_preduzeca_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("12", "38041778", "ZAPOSLJAVANJE NA TERET PREDUZECA", "457", number_format($_POST['zaposljavanje_na_teret_preduzeca_za_uplatu'], 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("12", "38041778", "ZAPOSLJAVANJE NA TERET PREDUZECA", "457", number_format(($_POST['zaposljavanje_na_teret_preduzeca_za_uplatu']*-1), 0, '.', ''));
				$UbacivanjeDoprinosaUUsluge_id.=",".UbacivanjeDoprinosaUUsluge("313", "38041778", "ZBIRNI POREZ I DOPRINOSI NA ZARADE", "458",
					number_format(($_POST['porez_na_primanja_za_uplatu']+
						$_POST['pio_na_teret_radnika_za_uplatu']+
						$_POST['zdravstveno_na_teret_radnika_za_uplatu']+
						$_POST['zaposljavanje_na_teret_radnika_za_uplatu']+
						$_POST['pio_na_teret_preduzeca_za_uplatu']+
						$_POST['zdravstveno_na_teret_preduzeca_za_uplatu']+
						$_POST['zaposljavanje_na_teret_preduzeca_za_uplatu']
						),
						0, '.', ''));
				//print_r($UbacivanjeDoprinosaUUsluge_id);

	 			


	 			$upit_plata="INSERT INTO plate (
		 				datum_plate,
		 				redni_br,
		 				vrsta_ind_prim_prih,
		 				jmbg,
		 				ime,
		 				prezime,
		 				sifra_opstine,
		 				sifra_vrste_prih,
		 				broj_dana,
		 				broj_sati,
		 				iznos_por_umanje,
		 				poreska_stopa,
		 				pio_radnika_stopa,
		 				zdrav_radnika_stopa,
		 				zapos_radnika_stopa,
		 				ukupno_ter_radnik,
		 				pio_preduz_stopa,
		 				zdrav_predu_stopa,
		 				zapos_preduz_stopa,
		 				ukupno_ter_predu,
		 				neto_zarada,
		 				bruto_zarada,
		 				poresko_umanj,
		 				osnovica_za_porez,
		 				porez_na_licna_prim,
		 				pio_radnik_uplat,
		 				zdrav_radnik_upl,
		 				zaposl_radnik_upl,
		 				pio_preduz_uplat,
		 				zdravstv_preduz_upl,
		 				zaposlj_preduz_upl,
		 				ukupni_doprinosi,
		 				id_usluge_doprinosi
		 				)
					VALUES
						(
							:datum_plate,
							:redni_br,
							:vrsta_ind_prim_prih,
							:jmbg,
							:ime,
			 				:prezime,
			 				:sifra_opstine,
			 				:sifra_vrste_prih,
			 				:broj_dana,
			 				:broj_sati,
			 				:iznos_por_umanje,
			 				:poreska_stopa,
			 				:pio_radnika_stopa,
			 				:zdrav_radnika_stopa,
			 				:zapos_radnika_stopa,
			 				:ukupno_ter_radnik,
			 				:pio_preduz_stopa,
			 				:zdrav_predu_stopa,
			 				:zapos_preduz_stopa,
			 				:ukupno_ter_predu,
			 				:neto_zarada,
			 				:bruto_zarada,
			 				:poresko_umanj,
			 				:osnovica_za_porez,
			 				:porez_na_licna_prim,
			 				:pio_radnik_uplat,
			 				:zdrav_radnik_upl,
			 				:zaposl_radnik_upl,
			 				:pio_preduz_uplat,
			 				:zdravstv_preduz_upl,
			 				:zaposlj_preduz_upl,
			 				:ukupni_doprinosi,
			 				:id_usluge_doprinosi
						)";

	 			$stmt = $baza_pdo->prepare($upit_plata);
				$stmt->bindParam(':datum_plate', $datum_za_bazu, PDO::PARAM_STR);
				$stmt->bindParam(':redni_br', $_POST['redni_broj'], PDO::PARAM_STR);
				$stmt->bindParam(':vrsta_ind_prim_prih', $_POST['vrsta_ident_primaoca'], PDO::PARAM_STR);
				$stmt->bindParam(':jmbg', $_POST['jmbg'], PDO::PARAM_STR);
				$stmt->bindParam(':ime', $_POST['ime'], PDO::PARAM_STR);
				$stmt->bindParam(':prezime', $_POST['prezime'], PDO::PARAM_STR);
				$stmt->bindParam(':sifra_opstine', $_POST['sifra_opstine_preb'], PDO::PARAM_STR);
				$stmt->bindParam(':sifra_vrste_prih', $_POST['sifra_vrste_prihoda'], PDO::PARAM_STR);
				$stmt->bindParam(':broj_dana', $_POST['broj_kalendarskih_dana'], PDO::PARAM_STR);
				$stmt->bindParam(':broj_sati', $_POST['broj_radnih_sati'], PDO::PARAM_STR);
				$stmt->bindParam(':iznos_por_umanje', $_POST['iznos_poreskog_umanjenja'], PDO::PARAM_STR);
				$stmt->bindParam(':poreska_stopa', $_POST['poreska_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':pio_radnika_stopa', $_POST['pio_na_teret_radnika_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':zdrav_radnika_stopa', $_POST['zdravstveno_teret_radnika_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':zapos_radnika_stopa', $_POST['zaposlavanje_teret_radnika_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':ukupno_ter_radnik', $_POST['ukupno_na_teret_radnika'], PDO::PARAM_STR);
				$stmt->bindParam(':pio_preduz_stopa', $_POST['pio_na_teret_preduzeca_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':zdrav_predu_stopa', $_POST['zdravstveno_teret_preduzeca_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':zapos_preduz_stopa', $_POST['zaposlavanje_teret_preduzeca_stopa'], PDO::PARAM_STR);
				$stmt->bindParam(':ukupno_ter_predu', $_POST['ukupno_na_teret_preduzeca'], PDO::PARAM_STR);
				$stmt->bindParam(':neto_zarada', $_POST['neto_zarada'], PDO::PARAM_STR);
				$stmt->bindParam(':bruto_zarada', $_POST['bruto_zarada'], PDO::PARAM_STR);
				$stmt->bindParam(':poresko_umanj', $_POST['poresko_umanjenje'], PDO::PARAM_STR);
				$stmt->bindParam(':osnovica_za_porez', $_POST['osnovica_za_obracun_poreza'], PDO::PARAM_STR);
				$stmt->bindParam(':porez_na_licna_prim', $_POST['porez_na_primanja_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':pio_radnik_uplat', $_POST['pio_na_teret_radnika_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':zdrav_radnik_upl', $_POST['zdravstveno_na_teret_radnika_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':zaposl_radnik_upl', $_POST['zaposljavanje_na_teret_radnika_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':pio_preduz_uplat', $_POST['pio_na_teret_preduzeca_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':zdravstv_preduz_upl', $_POST['zdravstveno_na_teret_preduzeca_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':zaposlj_preduz_upl', $_POST['zaposljavanje_na_teret_preduzeca_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':ukupni_doprinosi', $_POST['ukupno_poreza_i_doprinosa_za_uplatu'], PDO::PARAM_STR);
				$stmt->bindParam(':id_usluge_doprinosi', $UbacivanjeDoprinosaUUsluge_id, PDO::PARAM_STR);

				$stmt->execute();
				$OK = $stmt->rowCount();


				if ($OK) {
					?>
					<h2>Plata je pustena.</h2>
	 				<a href="../index.php" class="dugme_crveno_92plus4">Pocetna</a>
	 				<a href="plate_pregled.php" class="dugme_plavo_92plus4">Pregledaj plate</a>
					<?php

				} else {
					$error = $stmt->errorInfo();
					if (isset($error[2])) {
					  $error = $error[2];
					}
				}



	 		}
	 		else{
	 			?>
		 		<form method="post">
			 		<p style="text-align:center"><strong>Подаци о примаоцима / физичким лицима</strong></p>
			 		<label>Датум:</label>
					<input id="biracdatuma" type="text" name="datum" value="" class="polje_100_92plus4" />
			 		<label>3.1 Редни број:</label>
					<input type="text" name="redni_broj" class="polje_100_92plus4" value="<?php echo $plata["redniBroj"];?>"/>
					<label>3.2 Врста идентификације примаоца прихода:</label>
					<input type="text" name="vrsta_ident_primaoca" class="polje_100_92plus4" value="<?php echo $plata["vrstaIdentifikacije"];?>"/>
					<label>3.3 Податак за идентификацију лица:</label>
					<input type="text" name="jmbg" class="polje_100_92plus4" value="<?php echo $plata["jmbg"];?>"/>
					<label>3.4 Презиме: </label>
					<input type="text" name="prezime" class="polje_100_92plus4" value="<?php echo $plata["prezime"];?>"/>
					<label>3.4а Име: </label>
					<input type="text" name="ime" class="polje_100_92plus4" value="<?php echo $plata["ime"];?>"/>
					<label>3.5 Шифра општине пребивалишта: </label>
					<input type="text" name="sifra_opstine_preb" class="polje_100_92plus4" value="<?php echo $plata["sifraOpstinePrebivalista"];?>"/>
					<label>3.6 Шифра врсте прихода: </label>
					<input type="text" name="sifra_vrste_prihoda" class="polje_100_92plus4" value="<?php echo $plata["sifraVrstePrihoda"];?>"/>
					<label>3.7 Број календарских дана (УНОСИ СЕ:) </label>
					<input type="text" name="broj_kalendarskih_dana" class="polje_100_92plus4" value="<?php echo $plata["brojKalendarskihDana"];?>"/>
					<label>3.8 Број сати УНОСИ СЕ </label>
					<input type="text" name="broj_radnih_sati" class="polje_100_92plus4" value="<?php echo $plata["brojRadnihSati"];?>"/>
					<label>Износ пореског умањења (УНОСИ СЕ:) </label>
					<input type="text" name="iznos_poreskog_umanjenja" id="iznos_poreskog_umanjenja" class="polje_100_92plus4" value="<?php echo $plata["iznosPoreskogUmanjenja"];?>"/>
					
					<p style="text-align:center"><strong>Тренутно важећи елементи обрачуна</strong></p>
					<label>Пореска стопа </label>
					<input type="text" name="poreska_stopa" id="poreska_stopa" class="polje_100_92plus4" value="<?php echo $plata["poreskaStopa"];?>"/>
					<label>ПИО Осигуранје на терет радника стопа </label>
					<input type="text" name="pio_na_teret_radnika_stopa" id="pio_na_teret_radnika_stopa" class="polje_100_92plus4" value="<?php echo $plata["pioNaTeretRadnikaStopa"];?>"/>
					<label>Здравствено осигур. терет радника стопа </label>
					<input type="text" name="zdravstveno_teret_radnika_stopa" id="zdravstveno_teret_radnika_stopa" class="polje_100_92plus4" value="<?php echo $plata["zdravstvenoNaTeretRadnikaStopa"];?>"/>
					<label>Запошљавање на терет радника стопа </label>
					<input type="text" name="zaposlavanje_teret_radnika_stopa" id="zaposlavanje_teret_radnika_stopa" class="polje_100_92plus4" value="<?php echo $plata["zaposlavanjeNaTeretRadnikaStopa"];?>"/>
					<label>Укупно на терет радника: </label>
					<input type="text" name="ukupno_na_teret_radnika" id="ukupno_na_teret_radnika" class="polje_100_92plus4"/>


					<label>ПИО осигурањје на терет предузећа стопа </label>
					<input type="text" name="pio_na_teret_preduzeca_stopa" id="pio_na_teret_preduzeca_stopa" class="polje_100_92plus4" value="<?php echo $plata["pioNaTeretPreduzecaStopa"];?>"/>
					<label>Здравствено осиг.на терет предзећа стопа </label>
					<input type="text" name="zdravstveno_teret_preduzeca_stopa" id="zdravstveno_teret_preduzeca_stopa" class="polje_100_92plus4" value="<?php echo $plata["zdravstvenoNaTeretPreduzecaStopa"];?>"/>
					<label>Запошљавање на терет предузећа стопа </label>
					<input type="text" name="zaposlavanje_teret_preduzeca_stopa" id="zaposlavanje_teret_preduzeca_stopa" class="polje_100_92plus4" value="<?php echo $plata["zaposlavanjeNaTeretPreduzecaStopa"];?>"/>
					<label>Укуно на терет предузећа: </label>
					<input type="text" name="ukupno_na_teret_preduzeca" id="ukupno_na_teret_preduzeca" class="polje_100_92plus4" />

					<p style="text-align:center"><strong>Oбрачун</strong></p>
					<label>Нето зарада УНОСИ СЕ: </label>
					<input type="text" name="neto_zarada" id="neto_zarada" class="polje_100_92plus4" value="<?php echo $plata["netoZarada"];?>"/>
					<label>Бруто зарада: </label>
					<input type="text" name="bruto_zarada" id="bruto_zarada" class="polje_100_92plus4" readonly>
					<label>Пореско умањење: </label>
					<input type="text" name="poresko_umanjenje" id="poresko_umanjenje" class="polje_100_92plus4" readonly>
					<label>Основица за обрачун пореза: </label>
					<input type="text" name="osnovica_za_obracun_poreza" id="osnovica_za_obracun_poreza" class="polje_100_92plus4" readonly>
					<br>
					<label>Порез на лична примања за уплату: </label>
					<input type="text" name="porez_na_primanja_za_uplatu" id="porez_na_primanja_za_uplatu" class="polje_100_92plus4" readonly>
					<label>ПИО на терет радника: </label>
					<input type="text" name="pio_na_teret_radnika_za_uplatu" id="pio_na_teret_radnika_za_uplatu" class="polje_100_92plus4" readonly>
					<label>Здравствено осигуранје на терет радника: </label>
					<input type="text" name="zdravstveno_na_teret_radnika_za_uplatu" id="zdravstveno_na_teret_radnika_za_uplatu" class="polje_100_92plus4" readonly>
					<label>Запошљавање на терет радника: </label>
					<input type="text" name="zaposljavanje_na_teret_radnika_za_uplatu" id="zaposljavanje_na_teret_radnika_za_uplatu" class="polje_100_92plus4" readonly>
					<label>ПИО на терет предузећа: </label>
					<input type="text" name="pio_na_teret_preduzeca_za_uplatu" id="pio_na_teret_preduzeca_za_uplatu" class="polje_100_92plus4" readonly>
					<label>Здравствено осигуранје на терет предузећа: </label>
					<input type="text" name="zdravstveno_na_teret_preduzeca_za_uplatu" id="zdravstveno_na_teret_preduzeca_za_uplatu" class="polje_100_92plus4" readonly>
					<label>Запошљавање на терет предузећа: </label>
					<input type="text" name="zaposljavanje_na_teret_preduzeca_za_uplatu" id="zaposljavanje_na_teret_preduzeca_za_uplatu" class="polje_100_92plus4" readonly>

					<label>Укупно доприноса и пореза за уплату: </label>
					<input type="text" name="ukupno_poreza_i_doprinosa_za_uplatu" id="ukupno_poreza_i_doprinosa_za_uplatu" class="polje_100_92plus4" readonly>
					
					<button type='submit' class='dugme_zeleno'>Unesi</button>
					<a href="../index.php" class="dugme_crveno_92plus4">Odustani</a>
				</form>
			<?php
			}
			?>
		</div>
	</body>
</html>