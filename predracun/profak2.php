<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Predracun</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("button:first").focus();
			});
		</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			//require("../include/DbConnection.php");
			require("../include/DbConnectionPDO.php");



			$upit_br_rac = "SELECT * FROM profak WHERE brofak_rucni= '".$_POST['brofak_rucni']."'";
			$broj_rac_rucni_prov=$baza_pdo->query($upit_br_rac);
			$broj_redova = $broj_rac_rucni_prov->fetchColumn();
			if($broj_redova == 0){
		
				$broj_rac_rucni=$_POST['brofak_rucni'];
				
					/*zvanje sifre*/ 


				$upit = "SELECT * FROM dob_kup WHERE sif_kup=".$_POST['partnersif'];
				$result = $baza_pdo->query($upit);
				$red = $result->fetch();
				$part=$red['sif_kup'];
				$rokpl=$_POST['rok_placanja'];
				
				echo "<p>Sifra kupca: " . $part . "<br>Partner: " . $red['naziv_kup'] . "<br>Rok placanja: " . $rokpl . "<br>";

				$OK = false;
				$upit_profak_unos = 'INSERT INTO profak (datum_prof, sifra_fir, rok, brofak_rucni) VALUES(CURDATE(),:sifra_fir,:rok,:brofak_rucni)';
				$stmt_profak_unos = $baza_pdo->prepare($upit_profak_unos);
				$stmt_profak_unos->bindParam(':sifra_fir', $part, PDO::PARAM_INT);
				$stmt_profak_unos->bindParam(':rok', $rokpl, PDO::PARAM_INT);
				$stmt_profak_unos->bindParam(':brofak_rucni', $broj_rac_rucni, PDO::PARAM_INT);
				$stmt_profak_unos->execute() or die(print_r($stmt_profak_unos->errorInfo(), true));
				$OK = $stmt_profak_unos->rowCount();
				if ($OK) {}
				else {
					$error = $stmt_profak_unos->errorInfo();
					if (isset($error[2])) {
						$error = $error[2];
					}
				}

				//var_dump($stmt_profak_unos->errorCode());

				$profbr = $baza_pdo->lastInsertId();
				echo "ID broj: ".$profbr;
				echo "<br>";
				echo "Rucni broj".$broj_rac_rucni;
				echo "<br>";
							
				$brojfak = $profbr;
				$racun_rucni=$broj_rac_rucni;
				include("../include/ConfigFirma.php");
				$napomena=$inkfaktekst;

				$upit_napomena = "UPDATE profak SET napomena = ? WHERE broj_prof=?";
				$stmt_napomena = $baza_pdo->prepare($upit_napomena);
				$stmt_napomena->execute(array($napomena, $profbr));

				echo "Broj profakture: " . $profbr . "<br>";
				


				$datfak = "SELECT date_format(datum_prof , '%d.%m.%Y') as formatted_date FROM profak WHERE broj_prof=".$profbr;
				$result_datfak = $baza_pdo->query($datfak);
				$red_datfak = $result_datfak->fetch();

				echo "Datum: " . $red_datfak['formatted_date'] . "</p>";
				?> 
				<div class="cf"></div>
				<form action="profak3a.php" method="post">
					<input type="hidden" name="broj_profak" value="<?php echo $profbr; ?>"/>
					<button type="submit" class="dugme_zeleno">Unesi</button>
				</form>
				<form action="profak_brisi1.php" method="post">
					<input type="hidden" name="broj_profak" value="<?php echo $profbr; ?>"/>
					<button type="submit" class="dugme_crveno">Ponisti</button>
				</form>
				<div class="cf"></div>
				<?php	
			}
			else{
				?>
				<h2>Vec postoji faktura sa tim brojem!</h2>
				<a href="profak1.php" class="dugme_crveno_92plus4 print_hide">Povratak</a>
				<div class="cf"></div>
				<?php
			}

			
		?>
		</div>
	</body>
</html>
