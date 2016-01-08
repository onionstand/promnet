<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
		<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
		<script type="text/javascript">
		 jQuery(document).ready(function() {

			$("#obaveznaf").validity(function() {
								$("#kolicina")
									.require("Neophodno polje.")
									.match("number")
							});

			$(".polje_100_92plus4:visible:first").focus();
			$("#daljebuton:visible:first").focus();
			});
		</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php"); 
			/*zvanje sifre kalk*/ 
			$sifra_kalk=$_POST['broj_kalkulaci'];

			if (isset($_POST['kolicina']))
			{
				$kalkcena=$_POST['kalkcena'];
				$pdv=$_POST['porez_pdv'];
				$rabat2=$_POST['rabat'];
				$kalkcena_min_rab=($kalkcena/100)*(100-$rabat2);
				$brojkalku=$_POST['broj_kalkulaci'];
				$sif_rob=$_POST['sifra_r'];
				

				$ubaciv="INSERT INTO ulaz (srob_kal, br_kal, kol_kalk, cena_k, rab_kalk)
				VALUES
				('$sif_rob' , '$brojkalku','$_POST[kolicina]','$kalkcena','$rabat2')";
				mysql_query($ubaciv);

				/*dodavanje robe*/

				$dodrob=mysql_query("SELECT * FROM roba
				WHERE sifra='$sif_rob'");
				$row = mysql_fetch_array($dodrob);
				//STANJE ROBE ZA OBRACUN STANJA
				$stanje_robe_za_unos=$row['stanje'];

				//STANJE ZA OBRADU RUCA
				if ($row['stanje']>=0){
					$stanje_robe=$row['stanje'];
				}
				else{
					$stanje_robe=0;
				}
				$cena_robe_stanje=$row['cena_robe'];
				$preruc=$row['ruc'];
				
				$kalk_kolicina=$_POST['kolicina'];
				$nova_kolicina=$stanje_robe_za_unos+$kalk_kolicina;
				
				$iznos_razlika_u_ceni_kalk=($cena_robe_stanje*$kalk_kolicina)-($kalkcena_min_rab*$kalk_kolicina);
				$iznos_razlika_u_ceni_stanja=$cena_robe_stanje*$stanje_robe*$preruc/100;


				$novaruc=($iznos_razlika_u_ceni_kalk+$iznos_razlika_u_ceni_stanja)/((($cena_robe_stanje*$kalk_kolicina)+($cena_robe_stanje*$stanje_robe))/100);

				echo "<p>Broj kalkulacije: " . $sifra_kalk;
				echo "<br>";
				echo "Razlika u ceni stanja: " . $iznos_razlika_u_ceni_stanja;
				echo "<br>";
				echo "Razlika u ceni kalkulacije: " . $iznos_razlika_u_ceni_kalk;
				echo "<br>";
				echo "Marza: " . $novaruc;
				echo "<br>";
				echo "Prodajna cena: " . $cena_robe_stanje;
				echo "</p>";

				mysql_query("UPDATE roba SET stanje = '$nova_kolicina'
				WHERE sifra='$sif_rob'");
				mysql_query("UPDATE roba SET ruc = '$novaruc'
				WHERE sifra='$sif_rob'");
				?>
				<h2>Roba je uneta.</h2>
				<form action='kalk_nov6.php' method='post'>
					<input type='hidden' name='broj_kalkulaci' value='<?php echo $sifra_kalk;?>'/>
					<button type='submit' class='dugme_zeleno' id='daljebuton'>Dalje</button>
				</form>
				<div class="cf"></div>
				<?php
			}
			else
			{	
				?>
				<form action='' method='post'>
					<label>Kolicina: </label>
					<input type='text' name='kolicina' size='6' class='polje_100_92plus4'/>
					<input type='hidden' name='broj_kalkulaci' value='<?php echo $sifra_kalk;?>' id='kolicina'/>
				<input type='hidden' name='sifra_r' value='<?php echo $_POST['sifra_r'];?>'/>
				<input type='hidden' name='kalkcena' value='<?php echo $_POST['kalkcena'];?>'/>
				<input type='hidden' name='porez_pdv' value='<?php echo $_POST['porez_pdv'];?>'/>
				<input type='hidden' name='rabat' value='<?php echo $_POST['rabat'];?>'/>
				<input type='hidden' name='marza' value='<?php echo $_POST['marza'];?>'/>
				<input type='hidden' name='prodajna_cena' value='<?php echo $_POST['prodajna_cena'];?>'/>
				<div class="cf"></div>
				<button type='submit' class='dugme_zeleno'>Unesi</button>
				</form>
				<?php
			}?>
			<div class="cf"></div>
		</div>
	</body>
</html>